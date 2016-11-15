<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>北极光-抓单宝</title>
    <link href="/Public/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/Public/assets/css/style.css" rel="stylesheet">
    <link href="/Public/assets/css/font-awesome.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/PCASClass.js"></script>
    <script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
</head>
<body>

<div id="top">
<div class="navbar">
<div class="navbar-inner">
<div class="logo"><img src="/Public/assets/images/logoG.png" /></div>
<ul class="pull-right navInfo">
    <?php if($_SESSION['depot_id'] > 0): ?><li><a href="<?php echo U('Admin/GoodsWarning/warning_view');?>" class="head_goods_warning" id="head_warning">预警提示
        <?php if(!empty($_COOKIE['warning_count'])): ?><span class="badge bg_red"><?php echo ($_COOKIE['warning_count']); ?></span><?php endif; ?>
    </a></li><?php endif; ?>
    <li><a href='javascript:void(0)' id="refresh_cache">刷新缓存</a> </li>
    <li class="login_info">
        <span><a href="<?php echo U('Admin/Index/logout');?>">退出</a></span>
    </li>
    <script type="text/javascript">
        $("#refresh_cache").click(function () {
            $.get("<?php echo U('Admin/Index/refreshCache');?>",{},function(res){
            	if(res==1){
            		alert("清除成功")
            	}
            });
        })

    </script>
</ul>
</div>
</div>
</div>

<div class="main-container">
    <ul class="main-left nav nav-stacked" id="main_left">
	<!-- 菜单 -->
    <?php if(is_array($_SESSION['menu'])): $y = 0; $__LIST__ = $_SESSION['menu'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$leftvo): $mod = ($y % 2 );++$y;?><li class="dropdown">
    <a href="javascript:void(0)" class="collapsed collapse-menu"><i class="left-bg <?php echo ($leftvo["icon"]); ?>"></i><span><?php echo ($leftvo["name"]); ?></span></a>
    <ul class="main-left-menu">
    <?php if(is_array($leftvo["subclass"])): $i = 0; $__LIST__ = $leftvo["subclass"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$leftsubvo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Admin/'.$leftsubvo['controller'].'/'.$leftsubvo['action']);?>"><span><?php echo ($leftsubvo["subname"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>
    <li></li>
</ul>

    <div class="main-right container-fluid">
        <div class="r-sub-nav row-fluid">
            <?php
 $menuID = 0; foreach($_SESSION['menu'] as $k=>$v) { foreach($v['subclass'] as $val) { if($val['controller']==CONTROLLER_NAME){ $menuID = $k; break; } } if($menuID > 0) { break; }; } $sub_memu = $_SESSION['menu'][$menuID]['subclass']; ?>

<!-- 子菜单 -->

<?php if(is_array($sub_memu)): $i = 0; $__LIST__ = $sub_memu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rightsubvo): $mod = ($i % 2 );++$i;?><dl>
    <dd <?php if((CONTROLLER_NAME == $rightsubvo['controller']) AND (ACTION_NAME == $rightsubvo['action'])): ?>class="selected"<?php endif; ?> >
    <a href="<?php echo U('Admin/'.$rightsubvo['controller'].'/'.$rightsubvo['action'].'');?>"><?php echo ($rightsubvo["subname"]); ?></a>
    </dd>
    <dt></dt>
    </dl><?php endforeach; endif; else: echo "" ;endif; ?>

        </div>
        <div class="row-fluid main-content">

            <div class="sel-data mb20">
                <div class="fl">
                    <select name="query[depot_id]" id="depot_id" class="form-control w200">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["repertory_id"] == $query['depot_id']): ?><option selected="selected" value="<?php echo ($vod["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option>
                                <?php else: ?><option value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>
                </div>
                <div class="fr">
                    <a class="btn btn-primary bg_3071a9" id="cre_c"  role="button">创建</a>
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table">
                <thead>
                <tr>
                    <td width="">区域名称</td>
                    <td width="20%">区域编号</td>
                    <td width="20%">备注</td>
                    <td width="250">操作</td>
                </tr>
                </thead>
                <tbody id="cust_table">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($vo["area_id"]); ?>">
                        <td><?php echo ($vo["area_name"]); ?></td>
                        <td><?php echo ($vo["area_code"]); ?></td>
                        <td><?php echo ($vo["area_note"]); ?></td>
                        <td>
                            <ul class="operate-menu li-width33">
                                <li><a class="collapsed collapse-menu icons-href view"  role="button" data-p="<?php echo ($p); ?>"  data-id="<?php echo ($vo["area_id"]); ?>"><i class="icon-edit"></i>区域商品</a></li>
                                <li><a class="collapsed collapse-menu icons-href edit"  role="button" data-p="<?php echo ($p); ?>"  data-id="<?php echo ($vo["area_id"]); ?>"><i class="icon-edit"></i>修改</a></li>
                                <li><a class="collapsed collapse-menu icons-href delete" data-id="<?php echo ($vo["area_id"]); ?>"   href="javascript:void(0)"><i class="icon-remove-circle"></i>删除</a></li>
                            </ul>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>

            <?php echo W('Page/page',array("/index.php/Admin/Area/index",$pnum,$pagelist,$query));?>
        </div>
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<!--新建弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con"  class="modal-dialog modal_850 "></div>
</div>
<!--新建弹出层结束-->

<script type="text/javascript">

    //创建
    $("#cre_c").click(function () {
        $.ajax({
            url: "/index.php/Admin/Area/add/r/"+new Date().getTime(),
            type: "get",
            dataType: "html",
            beforeSend: function () {
                $(".await").show();
            },
            success: function (data) {
                $("#modal-con").empty().append(data);
                $(".await").hide();
            }
        })
        $("#myModal").modal({backdrop: "static"});
    });

    //查询
    $("#find").click(function() {
        var con = "";
        if ($("#depot_id").val() != 0) {
            con += "/depot_id/" + $("#depot_id").val();
        }
        location.href="/index.php/Admin/Area/index"+con;
    });


    //编辑
    $(".edit").click(function () {
        $.ajax({
            url: "/index.php/Admin/Area/edit/r/"+new Date().getTime(),
            type: "get",
            dataType: "html",
            data:{ area_id:$(this).attr('data-id'),p:$(this).attr('data-p')},
            beforeSend: function () {
                $(".await").show();
            },
            success: function (data) {
                $("#modal-con").empty().append(data);
                $(".await").hide();
            }
        })
        $("#myModal").modal({backdrop: "static"});
    });

    //删除
    $(".delete").click(function(){
        var state=$(this).attr("data-close");

            if(confirm("确定要删除该记录吗？"))
            {
                var id=$(this).attr('data-id');
                $.post("<?php echo U('Admin/Area/del');?>",{ area_id:id},function(result){
                    if(result==1){
                        alert("删除成功!");
                        location.reload();
                    }else{
                        alert("失败");
                    }
                })
            }

    });

    //编辑
    $(".view").click(function () {
        $.ajax({
            url: "/index.php/Admin/Area/detail/r/"+new Date().getTime(),
            type: "get",
            dataType: "html",
            data:{ area_id:$(this).attr('data-id'),p:$(this).attr('data-p')},
            beforeSend: function () {
                $(".await").show();
            },
            success: function (data) {
            	$(".await").hide();
                $("#modal-con").empty().append(data);
                
            }
        });

        $("#myModal").modal({backdrop: "static"});
    });

    $(function(){

    });
</script>
</body>
<script src="/Public/assets/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/timer.js"></script>
<script type="text/javascript">

    $(function(){
        var warning_sound_num = $.cookie('warning_sound_num');
        //console.log("warning_sound_num:" + warning_sound_num)

        var i=warning_sound_num;

        $('body').everyTime('8s',function(){
            i--
            if(i>=0){
                playAudio()
                $.cookie('warning_sound_num',i)
                console.log($.cookie('warning_sound_num'))
            }

        },warning_sound_num);

    });

    function playAudio() {
        $('<audio id="chatAudio"><source src="/Public/assets/sound/zhuoling.wav" type="audio/mpeg"></audio> ').appendTo('body');//载入声音文件

        $('#chatAudio')[0].play(); //播放声音
    }
</script>
</html>