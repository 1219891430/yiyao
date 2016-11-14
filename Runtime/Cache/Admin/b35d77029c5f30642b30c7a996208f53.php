<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>农乐汇-抓单宝</title>
<link href="/nlh_zdb/Public/assets/css/bootstrap.css" rel="stylesheet">
<link href="/nlh_zdb/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/nlh_zdb/Public/assets/css/style.css" rel="stylesheet">
<link href="/nlh_zdb/Public/assets/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/nlh_zdb/Public/assets/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">

<!--[if lt IE 9]>
<script src="/nlh_zdb/Public/assets/js/html5shiv.min.js"></script>
<script src="/nlh_zdb/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/nlh_zdb/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/nlh_zdb/Public/assets/js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="/nlh_zdb/Public/assets/js/jquery-messages_cn.js"></script> -->

    <script type="text/javascript" src="/nlh_zdb/Public/assets/zTree/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/nlh_zdb/Public/assets/zTree/js/jquery.ztree.excheck.js"></script>

<script type="text/javascript" src="/nlh_zdb/Public/assets/js/zstb.js"></script>
</head>
<body>

<div id="top">
<div class="navbar">
<div class="navbar-inner">
<div class="logo"><img src="/nlh_zdb/Public/assets/images/logoG.png" /></div>
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
    <div class="r-sub-nav row-fluid"><?php
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
    <table class="table list_table" id="role_table" >
    <thead><tr><td>编号</td><td>角色</td></tr></thead>
    <tbody>
    <?php if(is_array($role_list)): $k = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$role): $mod = ($k % 2 );++$k;?><tr>
    <td style="text-align: center;"><?php echo ($k); ?></td>
    <td style="text-align: center;"><?php echo ($role); ?>
        <?php if(($_SESSION['role_id'] == 1) OR ($_SESSION['depot_id'] == 0)): if(in_array($k, [1,2,3])): ?><button class="setting" data-id="<?php echo ($k); ?>">设置</button><?php endif; endif; ?>

    </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
    <tfoot></tfoot>
    </table>
    </div>
    </div>
</div>


<div id="await" class="await waits">
    <span> <img src="_/nlh_zdb/Public/assets/images/loding.gif" title="加载图片"/></span>
</div>
<!--新建弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con"  class="modal-dialog modal_850 ">

    </div>
</div>

<script type="text/javascript">

    $(".setting").click(function(){
        var role_id = $(this).data('id');
        var url = "<?php echo U('setting');?>";
        ajaxDataPara(url,{role_id: role_id});
    });

</script>

<script src="/nlh_zdb/Public/assets/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/nlh_zdb/Public/assets/js/timer.js"></script>
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
        $('<audio id="chatAudio"><source src="/nlh_zdb/Public/assets/sound/zhuoling.wav" type="audio/mpeg"></audio> ').appendTo('body');//载入声音文件

        $('#chatAudio')[0].play(); //播放声音
    }
</script>
</body>
</html>