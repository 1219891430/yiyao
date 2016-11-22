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
        <!--右侧查询开始-->
          <div class="sel-data mb20">
                <div class="fr">
                    <?php if($depotID == 0): ?><a class="btn btn-primary bg_3071a9" id="cre_c" role="button">创建</a>
                        <?php else: endif; ?>
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table id="treetable" class="table list_table">
                <thead>
                <tr>
                   <td width="13%">仓库名称</td>
                   <td width="10%">仓库负责人</td>
                   <td width="12%">仓库电话</td>
                   <td>仓库地址</td>
                   <td width="60">状态</td>
                   <td width="160">操作</td>
                </tr>
                </thead>
                <tbody id="cust_table">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($vo["repertory_id"]); ?>">
                        <td><?php echo ($vo["repertory_name"]); ?></td>
                        <td><?php echo ($vo["repertory_user"]); ?></td>
                        <td><?php echo ($vo["repertory_tel"]); ?></td>
                        <td><?php echo ($vo["repertory_address"]); ?></td>
                        <td id="td_<?php echo ($vo["repertory_id"]); ?>" class='<?php if($vo["repertory_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'><?php if($vo["repertory_close"] == 1): ?>关闭<?php else: ?>开启<?php endif; ?></td>
                        <td>
                            <ul class="operate-menu li-width33">
                                <?php if($depotID == 0): ?><li><a class="collapsed collapse-menu icons-href edit"  role="button" data-p="<?php echo ($p); ?>" data-id="<?php echo ($vo["repertory_id"]); ?>"><i class="icon-edit"></i>修改</a></li>
                                <li><a class="collapsed collapse-menu icons-href delete" data-id="<?php echo ($vo["repertory_id"]); ?>"  data-close="<?php echo ($vo["repertory_close"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>删除</a></li><?php endif; ?>
                            </ul>
                        </td>
                    </tr>
                    <?php if(is_array($vo["depot_list"])): $i = 0; $__LIST__ = $vo["depot_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($v["repertory_id"]); ?>">
                        <td>|-----------<?php echo ($v["repertory_name"]); ?></td>
                        <td><?php echo ($v["repertory_user"]); ?></td>
                        <td><?php echo ($v["repertory_tel"]); ?></td>
                        <td><?php echo ($v["repertory_address"]); ?></td>
                        <td id="td_<?php echo ($v["repertory_id"]); ?>" class='<?php if($v["repertory_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'><?php if($v["repertory_close"] == 1): ?>关闭<?php else: ?>开启<?php endif; ?></td>
                        <td>
                            <ul class="operate-menu li-width33">
                                <li><a class="collapsed collapse-menu icons-href edit"  role="button" data-p="<?php echo ($p); ?>" data-id="<?php echo ($v["repertory_id"]); ?>"><i class="icon-edit"></i>修改</a></li>
                                <li><a class="collapsed collapse-menu icons-href delete" data-id="<?php echo ($v["repertory_id"]); ?>"  data-close="<?php echo ($v["repertory_close"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>删除</a></li>
                            </ul>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        <?php echo W('Page/page',array("/index.php/Admin/Depot/index",$pnum,$pagelist,array())) ;?>

        </div>
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
            url: "/index.php/Admin/Depot/addDepot/r/"+new Date().getTime(),
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

    //编辑
    $(".edit").click(function () {
        $.ajax({
            url: "/index.php/Admin/Depot/editDepot/r/"+new Date().getTime(),
            type: "get",
            dataType: "html",
            data:{repertory_id:$(this).attr('data-id'),cust_name:$(this).attr('repertory_name'),p:$(this).attr('p')},
            beforeSend: function () {
                $(".await").show();
            },
            success: function (data) {
                $("#modal-con").empty().append(data);
                $(".await").hide();
            }
        })
        $("#myModal").modal({backdrop: "static"});
    })
    //删除
    $(".delete").click(function(){
        var state=$(this).attr("data-close");
        if(state==1){
            alert("该仓库已封存，暂不能删除，如果确定删除，请先解除封存状态！");
        }else{
            if(confirm("确定要删除该记录吗？"))
            {
                var id=$(this).attr('data-id');
                $.post("<?php echo U('Admin/Depot/delDepot');?>",{repertory_id:id},function(result){
                    if(result==1) {
                        alert("删除成功!");
                        location.reload();
                    }else if (result == 2){
                        alert("仓库还有库存，禁止删除");
                    }else{
                        alert("失败");
                    }
                })
            }
        }
    });
    $(function(){

    });
</script>
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
</body>
</html>