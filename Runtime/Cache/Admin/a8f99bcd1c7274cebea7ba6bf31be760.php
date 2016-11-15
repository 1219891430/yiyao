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
<!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script type="text/javascript" src="/Public/assets/js/goods.js"></script>
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
            <select class="form-control w200" id="queryRole" name="queryRole" >
            <option value="0">请选择角色</option>
            <?php if(is_array($role_list)): $roleID = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$roleName): $mod = ($roleID % 2 );++$roleID; if($roleID == $queryRole): ?><option value="<?php echo ($roleID); ?>" selected><?php echo ($roleName); ?></option>
            <?php else: ?>
            <option value="<?php echo ($roleID); ?>"><?php echo ($roleName); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a class="btn btn-default" href="#" id="chaxun" role="button">查询</a>
            </div>    
            <div class="fr"><a class="btn btn-primary bg_3071a9" href="#" id="cre_zc" role="button">创建</a></div>
		</div>

        <table class="table list_table">
        <thead>
        <tr>
        <td width="10%">编号</td>
        <td width="15%">姓名</td>
        <td width="15%">手机号</td>
        <td width="15%">角色</td>
        <td width="15%">仓库</td>
        <td width="10%">状态</td>
        <td width="15%">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($staff_list)): $i = 0; $__LIST__ = $staff_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($da["admin_id"]); ?></td>
        <td><?php echo ($da["true_name"]); ?></td>
        <td><?php echo ($da["mobile"]); ?></td>
        <td><?php echo ($role_list[$da[role_id]]); ?></td>
        <td><?php echo ($depot_list[$da[depot_id]]); ?></td>
        <td id="td_<?php echo ($da["admin_id"]); ?>" class='<?php if($da["is_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'>
        <?php if($da["is_close"] == 1): ?>已关闭<?php else: ?>使用中<?php endif; ?>
        </td>
        <td>
        <ul class="operate-menu li-width33">
        <li><a data-id="<?php echo ($da["admin_id"]); ?>" class="collapsed collapse-menu icons-href edit" href="javascript:void(0)"><i class="icon-edit"></i>编辑</a></li>
        <!--
            <li><a data-id="<?php echo ($da["admin_id"]); ?>" class="collapsed collapse-menu icons-href delete" href="javascript:void(0)"><i class="icon-remove-circle"></i>删除</a></li>
        !-->
        <?php if($da["is_close"] == 1): ?><li><a data-id="<?php echo ($da["admin_id"]); ?>" class="collapsed collapse-menu icons-href open" href="javascript:void(0)"><i class="icon-remove-circle"></i>启用</a></li>
        <?php else: ?>
        <li><a data-id="<?php echo ($da["admin_id"]); ?>" class="collapsed collapse-menu icons-href closed" href="javascript:void(0)"><i class="icon-remove-circle"></i>关闭</a></li><?php endif; ?>
        </ul>
        </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <tfoot></tfoot>
        </table>
        
        <!--分页查询开始-->
        <?php echo W('Page/page',array("/index.php/Admin/Staff/index", $pnum, $pagelist, array('queryDepot'=>$queryDepot, 'queryRole'=>$queryRole)));?>
        <!--分页查询结束-->
	</div>
    </div>
</div>

<!--弹出层开始-->
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div id="modal-con" class="modal-dialog modal_850 "></div></div>
<!--弹出层结束-->

<script type="text/javascript">
$(function () {

	// 查询人员
    $("#chaxun").click(function () {
        var queryDepot = $("#queryDepot").val();
        var queryRole = $("#queryRole").val();
        location.href = "<?php echo U('index');?>" + "?queryDepot=" + queryDepot + "&queryRole=" + queryRole + "&r=" + new Date().getTime();
    });

	// 添加人员
	$("#cre_zc").click(function(){ ajaxData("<?php echo U('add');?>"); });

	// 编辑人员
	$(".edit").click(function(){ 
		var admin_id = $(this).attr('data-id');
		var url = "<?php echo U('edit');?>" + "?id=" + admin_id;
		ajaxData(url);
    });

    // 删除人员
    $(".delete").click(function(){
	if(confirm("确定要删除该记录吗？"))
	{
		var id = $(this).attr('data-id');
		var url = "<?php echo U('del');?>" + "?id=" + id;
		$.get(url, function(result){
			if(result == 1) {
				alert("删除成功!");
				location.reload(true);
			} else {
				alert("删除失败");
			}
		});
	}});

    // 启用人员
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
			
	}})
	
    // 关闭人员
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
	}})

})
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