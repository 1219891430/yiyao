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
    <link href="/Public/assets/css/jquery.treetable.css" rel="stylesheet">
    <link href="/Public/assets/css/jquery.treetable.theme.default.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/PCASClass.js"></script>
    <!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
    <script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
    <script type="text/javascript" src="/Public/assets/js/layer/layer.js"></script>
    <link href="/Public/assets/css/bootstrap-switch.css" rel="stylesheet">

	<!-- 按钮 -->
	<script type="text/javascript" src="/Public/assets/js/bootstrap-switch.js"></script>
	<script type="text/javascript" src="/Public/assets/js/highlight.js"></script>
	<script type="text/javascript" src="/Public/assets/js/main.js"></script>
	<!-- 按钮 -->
    
    <style type="text/css">
        .mtf2 img{margin-top:-2px;margin-right:5px;cursor: pointer}
        .text-index-2em td:first-child{text-indent: 2em}
        .text-index-4em td:first-child{text-indent: 4em}
    </style>
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

<div class="main-container" id="main-container">
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
                    <select name="dep_id" id="dep_id" class="form-control w200">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depotlist)): $i = 0; $__LIST__ = $depotlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$depot): $mod = ($i % 2 );++$i; if($depot["repertory_id"] == $aUrlPara['dep_id']): ?><option selected="selected" value="<?php echo ($repertory["repertory_id"]); ?>"><?php echo ($depot["repertory_name"]); ?></option>
                                <?php else: ?><option value="<?php echo ($depot["repertory_id"]); ?>"><?php echo ($depot["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <!--<input type="text" class="form-control w150" value="<?php echo ($org_name); ?>" id="org_name" placeholder="经销商名称"/>
                    <input type="text" class="form-control w150" value="<?php echo ($contacts); ?>" id="contacts" placeholder="联系人"/>
                    <input type="text" class="form-control w150" value="<?php echo ($telephone); ?>" id="telephone" placeholder="电话/手机"/>
                    <input type="text" class="form-control w150" value="<?php echo ($area); ?>" id="area" placeholder="地区"/>
                    <select class="form-control w150" name="status" id="status">
                        <option value="nil" >用户状态</option>
                        <option value="0">开启</option>
                        <option value="1">关闭</option>
                    </select>-->

                    <a class="btn btn-default" id="find" role="button">查询</a>
                </div>
                <div class="fr"><a class="btn btn-primary bg_3071a9" href="javascript:void(0)" id="chuangjian">创建</a></div>
            </div>
            <table class="table list_table treetable">
                <thead>
                <tr>
                    <td>经销商名称</td>
                    <td width="10%">地区</td>
                    <td width="15%">地址</td>
                    <td width="8%">联系人</td>
                    <td width="8%">固话</td>
                    <td width="10%">手机</td>
                    <td width="5%">邮编</td>
                    <td width="5%">状态</td>
                    <td width="10%">注册时间</td>
                    <td width="12%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$org): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($org["org_name"]); ?></td>
                        <td><?php echo ($org["province"]); echo ($org["city"]); echo ($org["district"]); ?></td>
                        <td><?php echo ($org["address"]); ?></td>
                        <td><?php echo ($org["contacts"]); ?></td>
                        <td><?php echo ($org["telephone"]); ?></td>
                        <td><?php echo ($org["mobile"]); ?></td>
                        <td><?php echo ($org["zip_code"]); ?></td>
                        <td id="td_<?php echo ($org["org_id"]); ?>" class='<?php if($org["is_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'><?php if($org["is_close"] == 1): ?>关闭<?php else: ?>开启<?php endif; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $org['reg_time']);?></td>
                        <td>
                            <ul class="operate-menu li-width33">
                             <li><a href="javascript:void(0)" attr="<?php echo ($org["org_id"]); ?>" class="editjing"><i class="icon-edit"></i>&nbsp;修改</a></li>

                            <?php if($org["is_close"] == 1): ?><li><a data-id="<?php echo ($org["org_id"]); ?>" class="collapsed collapse-menu icons-href open" href="javascript:void(0)"><i class="icon-remove-circle"></i>启用</a></li>
                                <?php else: ?>
                                <li><a data-id="<?php echo ($org["org_id"]); ?>" class="collapsed collapse-menu icons-href closed" href="javascript:void(0)"><i class="icon-remove-circle"></i>关闭</a></li><?php endif; ?>
                            </ul>
                           <!--
                            <a href="javascript:void(0)" attr="<?php echo ($org["org_id"]); ?>" class="delete"><i class="icon-remove-circle"></i>&nbsp;删除</a>
                            -->
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <!--分页查询开始-->
            <?php echo W('Page/page',array("/index.php/Admin/Dealer/index",$pnum,$pagelist,$aUrlPara));?>
            <!--分页查询结束-->
        </div>
    </div>
</div>
<div id="await" class="await">
    <span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>
<script type="text/javascript">
    $("#chuangjian").click(function(){
        ajaxData("/index.php/Admin/Dealer/add");
    })
    $(".editjing").click(function(){
        var data={ org_id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/Dealer/edit",data);
    })
    $(".chakan").click(function(){
        var data={ org_id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/Dealer/total",data);
    })

    //删除
    $(".delete").click(function(){
        var state=$(this).attr("data-close");
        if(state==1){
            alert("该数据已封存，暂不能删除，如果确定删除，请先解除封存状态！");
        }else{
            if(confirm("将删除经销商下的所有信息,删除后不可恢复,确定要删除该记录吗？"))
            {
                var id=$(this).attr('data-id');
                $.post("<?php echo U('Admin/Dealer/del');?>",{org_id:id},function(result){
                    if(result==1){
                        alert("删除成功!");
                        location.reload();
                    }else{
                        alert("失败");
                    }
                })
            }
        }
    });

    $("#find").click(function(){

        /*var h = "/index.php/Admin/Dealer?"

        // cust
        h += "&cust=" + $("#cust").val();

        // start_time
        h += "&start=" + $("#start_time").val();

        // end_time
        h += "&end=" + $("#end_time").val();

        location.href = h;*/

        location.href="/index.php/Admin/Dealer?dep_id="+$("#dep_id").val();
    })

    // 启用
    $(".open").click(function () {
        if (confirm("确定要启用吗？")) {
            var id = $(this).attr('data-id');
            var url = "<?php echo U('close');?>" + "?id=" + id + "&st=0";
            $.get(url, function(result){
                if(result == 1) {
                    alert("操作成功!");
                    location.reload(true);
                } else {
                    alert("操作失败");
                }
            });

        }});

    // 关闭
    $(".closed").click(function (){
        if (confirm("确定要关闭吗？")) {
            var id = $(this).attr('data-id');
            var url = "<?php echo U('close');?>" + "?id=" + id + "&st=1";
            $.get(url, function(result){
                if(result == 1) {
                    alert("操作成功!");
                    location.reload(true);
                } else {
                    alert("操作失败");
                }
            });
        }});


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