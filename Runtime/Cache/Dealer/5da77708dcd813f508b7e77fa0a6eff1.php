<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>经销商后台-北极光抓单宝</title>
<link href="/yiyao/Public/assets/css/bootstrap.css" rel="stylesheet">
<link href="/yiyao/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/yiyao/Public/assets/css/style.css" rel="stylesheet">
<link href="/yiyao/Public/assets/css/font-awesome.min.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="/yiyao/Public/assets/js/html5shiv.min.js"></script>
<script src="/yiyao/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/yiyao/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/yiyao/Public/assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/yiyao/Public/assets/js/jquery.validate.min.js"></script>
    <!-- <script type="text/javascript" src="/yiyao/Public/assets/js/jquery-messages_cn.js"></script> -->
<script type="text/javascript" src="/yiyao/Public/assets/js/zstb.js"></script>
</head>
<body>

<div id="top">
<div class="navbar">
<div class="navbar-inner">
    <div class="logo"><img src="/yiyao/Public/assets/images/logoG.png" /></div>
    <ul class="navInfo">
    	<li><a class="fb tel" style="font-size:14px">热线电话：400-0311-995</a></li>
        <li><a href="tencent://message/?Menu=yes&uin=3414136692" class="fb"><img src="/yiyao/Public/assets/images/backgrounds/qq.gif"></a></li>
    </ul>
    <ul class="pull-right navInfo">
    	<li><a href="<?php echo U('Dealer/CarsaleApply/index');?>" class="carApply" id="cheshen">车存申请
            <?php if(!empty($_SESSION['apply_num'])): ?><span class="badge bg_gren"><?php echo ($_SESSION['apply_num']); ?></span><?php endif; ?>
        </a></li>
        <li><a href="<?php echo U('Dealer/CarsaleBack/index');?>">车销退库
            <?php if(!empty($_SESSION['return_stock_num'])): ?><span class="badge bg_gren"><?php echo ($_SESSION["return_stock_num"]); ?></span><?php endif; ?>
        </a></li>
        <li><a href="<?php echo U('Dealer/CarSalesOrder/index');?>" class="carApply" id="chexiao">车销订单
            <?php if(!empty($_SESSION['order_num'])): ?><span class="badge bg_gren"><?php echo ($_SESSION['order_num']); ?></span><?php endif; ?>
        </a></li>
        <li><a href="<?php echo U('Dealer/PlanOrder/index');?>" class="carApply" id="yudan">预售订单
            <?php if(!empty($_SESSION['car_order_num'])): ?><span class="badge bg_gren"><?php echo ($_SESSION['car_order_num']); ?></span><?php endif; ?>
        </a></li>
        <!--<li><a href="#">新消息<img src="/yiyao/Public/assets/images/backgrounds/mess_icon.png"><span class="badge bg_gren">9</span></a></li>-->
        <li class="login_info">
        <a href="javascript:void(0)" id="AdminStaffName" onclick="editInfo();" style="padding:0px;padding-right:10px;"><?php echo (session('staff_name')); ?></a>
        <img src="/yiyao/Public/assets/images/hengx.png">
        <span><a href="<?php echo U('Dealer/Index/logout');?>">退出</a></span>
        </li>
        <li><a href="javascript:void(0);" onclick="AddFavorite('农乐汇-抓单宝',location.href)">收藏本页</a></li>
	</ul>
</div>
</div>
</div>

<!--编辑人员弹出层开始-->
<div class="modal" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal_650">
<div class="modal-content modal_650" id="adminInfo"></div>
</div>
</div>
<!--弹出层结束-->

<script type="text/javascript">
function AddFavorite(title, url) 
{
	try { window.external.addFavorite(url, title); }
	catch (e) {
		try { window.sidebar.addPanel(title, url, ""); }
		catch (e) { alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加"); }
	}
}
function getCarApply()
{
	$.ajax({
		url:"<?php echo U('Home/CarportApply/getCarApply');?>",
		type:"post",
		dataType:"json",
		success:function(data){
			$("#cheshen span").html(data.applyOrderNum);
			$("#chexiao span").html(data.carOrderNum);
			$("#yudan span").html(data.yuOrderNum);
		}
	})
}
getCarApply();
//window.setInterval(getCarApply, 10000);

function editInfo()
{
	var url = "<?php echo U('Home/Staff/editAdmin');?>";
	$.ajax({url:url,success:function(data){
		$('#adminInfo').html(data);
		$("#myModaledit").modal({backdrop:"static"});
	}});
}

function edit_admin_info()
{
	var url = "<?php echo U('Home/Staff/editAdmin');?>";
	var staff_name = $("#staff_name1").val();
	$.post(url,{staff_name:staff_name},function(result){
		var flag = parseInt(result);
		if(flag == 1)
		{
			alert('修改成功');
			$('#AdminStaffName').html(staff_name);
			$("#myModaledit").modal('hide');
		}
		else
		{
			alert('名称重复，修改失败');
		}
	});
}
</script>
<div class="main-container">
    <ul class="main-left nav nav-stacked" id="main_left">
	<!-- 菜单 -->
    <?php if(is_array($_SESSION['menu_dealer'])): $y = 0; $__LIST__ = $_SESSION['menu_dealer'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$leftvo): $mod = ($y % 2 );++$y;?><li class="dropdown">
    <a href="javascript:void(0)" class="collapsed collapse-menu"><i class="left-bg <?php echo ($leftvo["icon"]); ?>"></i><span><?php echo ($leftvo["name"]); ?></span></a>
    <ul class="main-left-menu">
    <?php if(is_array($leftvo["subclass"])): $i = 0; $__LIST__ = $leftvo["subclass"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$leftsubvo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Dealer/'.$leftsubvo['controller'].'/'.$leftsubvo['action']);?>"><?php echo ($leftsubvo["subname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>
    <li></li>
</ul>

    <div class="main-right container-fluid">
    
    	<div class="r-sub-nav row-fluid"><?php
 $menuID = 0; foreach($_SESSION['menu_dealer'] as $k=>$v) { foreach($v['subclass'] as $val) { if($val['controller']==CONTROLLER_NAME){ $menuID = $k; break; } } if($menuID > 0) { break; }; } $sub_memu = $_SESSION['menu_dealer'][$menuID]['subclass']; ?>

<!-- 子菜单 -->

<?php if(is_array($sub_memu)): $i = 0; $__LIST__ = $sub_memu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rightsubvo): $mod = ($i % 2 );++$i;?><dl>
    <dd <?php if((CONTROLLER_NAME == $rightsubvo['controller']) AND (ACTION_NAME == $rightsubvo['action'])): ?>class="selected"<?php endif; ?> >
    <a href="<?php echo U('Dealer/'.$rightsubvo['controller'].'/'.$rightsubvo['action'].'');?>"><?php echo ($rightsubvo["subname"]); ?></a>
    </dd>
    <dt></dt>
    </dl><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
        <div class="row-fluid main-content">
			<div class="sel-data mb20">
                <div class="fl"></div>

                <div class="fr"><a class="btn btn-primary bg_3071a9" href="#" id="cre_zc" role="button">添加</a></div>
            </div>

            <table class="table list_table">
            <thead>
            <tr>
            <td width="10%">姓名</td>
            <td width="10%">手机号</td>
            <td width="7%">登录号</td>
            <td width="7%">密码</td>
            <td width="7%">职务</td>
            <td width="7%">角色</td>
            <td width="10%">归属部门</td>
            <td width="5%">状态</td>
            <td width="12%">操作</td>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($staff)): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($da["staff_id"]); ?>">
            <td><?php echo ($da["staff_name"]); ?></td>
            <td><?php echo ($da["mobile"]); ?></td>
            <td>
            <?php if($da['login_user'] == ''): ?>-<?php else: ?> <?php echo ($da["login_user"]); endif; ?>
            </td>
            <td>
            <?php if(session('is_admin') == 1): ?><a class="reset" attr="<?php echo ($da["staff_id"]); ?>" style="color: tomato">重置密码</a>
            <?php else: ?>
                -<?php endif; ?>
            </td>
            <td>
            <?php echo ($da["job_post"]); ?>
            </td>
            
            <td>
            <?php if($da["role_id"] == 1): ?>老板
            <?php elseif($da["role_id"] == 2): ?>
            内勤
            <?php elseif($da["role_id"] == 3): ?>
            业务员
            <?php elseif($da["role_id"] == 4): ?>
            库管
            <?php else: ?>
            司机<?php endif; ?>
            </td>
            <td>
            <?php echo ($da["dep_name"]); ?>
            </td>
            <td id="tdc_<?php echo ($da["staff_id"]); ?>" class=' <?php if($da["is_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'>
            <?php if($da["is_close"] == 1): ?>禁用<?php else: ?>正常<?php endif; ?>
            </td>
            <td>
            <ul class="operate-menu li-width33">
                <?php if($da["is_admin"] != 1): ?><li><a class="collapsed collapse-menu icons-href staff_edit" attr="<?php echo ($da["staff_id"]); ?>" href="javascript:void(0)">
            <i class="icon-edit"></i>修改
            </a></li><?php endif; ?>
                <?php if($da["staff_id"] != $_SESSION['staff_id']): ?><li style="display:<?php if($da['is_admin']==1) echo none; ?> ;"><a attr="<?php echo ($da["staff_id"]); ?>" class="collapsed collapse-menu icons-href staff_del" href="javascript:void(0)">
            <i class="icon-remove-circle"></i>删除
            </a></li>
            <li style="display:<?php if($da['is_admin']==1) echo none; ?> ;">

            <a class="collapsed collapse-menu icons-href staff_close" attr="<?php echo ($da["staff_id"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>
                <span id="close_<?php echo ($da["staff_id"]); ?>">
                <?php if($da["is_close"] == 1): ?>解封
                <?php else: ?>
                    禁用<?php endif; ?>

                </span>

            </a><?php endif; ?>
            </li>
            </ul>
            </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
            <tfoot></tfoot>
            </table>

    <!--表格查询结束-->
    <!--分页查询开始-->
    <?php echo W('Page/page',array("/yiyao/index.php/Dealer/Staff/index",$pnum,$pagelist,$urlPara));?>

    <!--分页查询结束-->

		</div>

    </div>
</div>

<div id="await" class="await"><span> <img src="/yiyao/Public/assets/images/loding.gif" title="加载图片"/></span></div>

<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>
<script type="text/javascript" src="/yiyao/Public/assets/js/goods.js?v=27"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/validate_form.js"></script>
<script type="text/javascript">

    $("#cre_zc").click(function(){
        ajaxData("/yiyao/index.php/Dealer/Staff/add");
    })

    $(".staff_edit").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/yiyao/index.php/Dealer/Staff/edit",data);
    })

    $(".reset").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/yiyao/index.php/Dealer/Staff/resetPwd",data);
    })

    $(".staff_close").click(function(){
        var id = $(this).attr("attr")

        $.ajax({
            type: "get",
            url:"/yiyao/index.php/Dealer/Staff/close",
            data:{
                id:id
            },
            dataType: "json",
            success: function (data) {
                if (data === 0) {
                    alert("操作失败");
                } else if (data === 1) {
                    $("#close_" + id).html('解封');
                    $("#tdc_" + id).addClass("red").removeClass("green");
                    $("#tdc_" + id).html("禁用");
                } else {
                    $("#close_" + id).html('禁用');
                    $("#tdc_" + id).addClass("green").removeClass("red");
                    $("#tdc_" + id).html("正常");
                }
            }
        });
    })

    $(".staff_del").click(function(){
        if(confirm("确定删除吗?"))
        {
            var data={id:$(this).attr("attr")};
            ajaxDataAUD("/yiyao/index.php/Dealer/Staff/del",data, true);

        }
    })

</script>

</body>
</html>