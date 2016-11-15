<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>北极光-抓单宝</title>
<link href="/yiyao/Public/assets/css/bootstrap.css" rel="stylesheet">
<link href="/yiyao/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/yiyao/Public/assets/css/style.css" rel="stylesheet">
<link href="/yiyao/Public/assets/css/font-awesome.min.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="/yiyao/Public/assets/js/html5shiv.min.js"></script>
<script src="/yiyao/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/yiyao/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/PCASClass.js"></script>
<!-- <script type="text/javascript" src="/yiyao/Public/assets/js/jquery-messages_cn.js"></script> -->
<script type="text/javascript" src="/yiyao/Public/assets/js/zstb.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/goods.js"></script>
</head>
<body>

<div id="top">
<div class="navbar">
<div class="navbar-inner">
<div class="logo"><img src="/yiyao/Public/assets/images/logoG.png" /></div>
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
        <div class="sel-data mb20">
            <div class="fl">
            <select class="form-control w200" id="queryDepot" name="queryDepot">
            <option value="0">请选择仓库</option>
            <?php if(is_array($depot_list)): $depotID = 0; $__LIST__ = $depot_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$depotName): $mod = ($depotID % 2 );++$depotID; if($depotID == $queryDepot): ?><option value="<?php echo ($depotID); ?>" selected><?php echo ($depotName); ?></option>
            <?php else: ?>
            <option value="<?php echo ($depotID); ?>"><?php echo ($depotName); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a class="btn btn-default" href="#" id="chaxun" role="button">查询</a>
            </div>
		</div>

        <table class="table list_table">
        <thead>
        <tr>
        <td width="10%">编号</td>
        <td width="15%">姓名</td>
        <td width="15%">手机号</td>
        <td width="15%">仓库</td>
        <td width="20%">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($staff_list)): $i = 0; $__LIST__ = $staff_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($da["admin_id"]); ?></td>
        <td><?php echo ($da["true_name"]); ?></td>
        <td><?php echo ($da["mobile"]); ?></td>
        <td><?php echo ($depot_list[$da[depot_id]]); ?></td>
        <td>
        <ul class="operate-menu li-width33">
        <li><a class="collapsed collapse-menu icons-href" href="<?php echo U('shops',array('id'=>$da[admin_id]));?>"><i class="icon-edit"></i>店铺列表</a></li>

            <li><a class="collapsed collapse-menu icons-href" href="<?php echo U('maps',array('id'=>$da[admin_id]));?>"><i class="icon-edit"></i>地图显示</a></li>
        </ul>
        </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <tfoot></tfoot>
        </table>
        
        <!--分页查询开始-->
        <?php echo W('Page/page',array("/yiyao/index.php/Admin/CollectShop/index", $page_size, $pagelist, array('queryDepot'=>$queryDepot)));?>
        <!--分页查询结束-->
	</div>
    </div>
</div>

<script type="text/javascript">
$(function () {

	// 查询人员
    $("#chaxun").click(function () {
        var queryDepot = $("#queryDepot").val();
        location.href = "<?php echo U('index');?>" + "?queryDepot=" + queryDepot + "&r=" + new Date().getTime();
    });

})
</script>

</body>
<script src="/yiyao/Public/assets/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/timer.js"></script>
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
        $('<audio id="chatAudio"><source src="/yiyao/Public/assets/sound/zhuoling.wav" type="audio/mpeg"></audio> ').appendTo('body');//载入声音文件

        $('#chatAudio')[0].play(); //播放声音
    }
</script>
</html>