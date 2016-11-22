<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>经销商后台-北极光抓单宝</title>
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
<!-- <script type="text/javascript" src="/Public/js/jquery-messages_cn.js"></script> -->

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
    <ul class="navInfo">
    	<li><a class="fb tel" style="font-size:14px">热线电话：400-0311-995</a></li>
        <li><a href="tencent://message/?Menu=yes&uin=3414136692" class="fb"><img src="/Public/assets/images/backgrounds/qq.gif"></a></li>
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
        <!--<li><a href="#">新消息<img src="/Public/assets/images/backgrounds/mess_icon.png"><span class="badge bg_gren">9</span></a></li>-->
        <li class="login_info">
        <a href="javascript:void(0)" id="AdminStaffName" onclick="editInfo();" style="padding:0px;padding-right:10px;"><?php echo (session('staff_name')); ?></a>
        <img src="/Public/assets/images/hengx.png">
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
<div class="main-container" id="main-container">
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
<!--顶部菜单导航开始-->
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
<!--顶部菜单导航结束-->
<!--主体操作区域开始-->
<div class="row-fluid main-content">


    <!--右侧查询开始-->
    <div class="sel-data mb20">
        <!--<div class="fr">
            <a class="btn btn-primary bg_3071a9" id="cre_c"  role="button">创建</a>
        </div>-->
    </div>
    <!--右侧查询结束-->
    <!--表格查询开始-->
    <table  id="treetable" class="table list_table treetable">
        <thead>
        <tr>
            <td width="25%">客户类型名称</td>
            <td width="42%">客户类型说明</td>
            <!--<td width="13%">操作</td>-->
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["ct_name"]); ?></td>
                
                <td><?php echo ($vo["ct_remark"]); ?></td>
                 
                <!--<td>
                    <ul class="operate-menu li-width50">
                        <?php if($org["is_system"] == 0): ?><li><a class="collapsed collapse-menu icons-href edit" attr="<?php echo ($vo["ct_id"]); ?>" role="button"><i class="icon-edit"></i>&nbsp;修改</a></li>
                        <li><a class="collapsed collapse-menu icons-href delete" id="<?php echo ($vo["ct_id"]); ?>" href="javascript:void(0)"
                                ><i class="icon-remove-circle"></i>删除</a></li><?php endif; ?>
                    </ul>
                </td>-->
                 
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
     <?php echo W('Page/page',array("/index.php/Dealer/Customer/type",$pnum,$pagelist));?>
    <!--表格查询结束-->


</div>
<!--主体操作区域结束-->

<!--新建部门弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div  id="modal-con"   class="modal-dialog modal_650 "></div></div>
<!--新建部门弹出层结束-->
</div>
</div>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script type="text/javascript">$("#myModal").on("hidden.bs.modal", function() {$(this).removeData("bs.modal");});</script>
<script type="text/javascript" src="/Public/assets/js/jquery.treetable.js"></script>
<script type="text/javascript">
    $("#treetable").treetable({ expandable: true }).treetable("expandAll");

    $(".edit").click(function () {
        $.ajax({
            url: "/index.php/Dealer/Customer/editType",
            type: "get",
            dataType: "html",
            data:{id:$(this).attr('attr')},
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

    $("#cre_c").click(function () {
        $.ajax({
            url: "/index.php/Dealer/Customer/addType/r"+new Date().getTime(),
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
    })

    //删除
    $(".delete").click(function(){
        if(confirm("确定要删除该记录吗？"))
        {
            var id=$(this).attr('id');
              
              
            $.post("/index.php/Dealer/Customer/delType",{ct_id:id},function(result){
                alert(result["info"]);
                if(result["res"]==1)
                    location.reload();
            })
        }
    })
//    //封存
//    $(".fengcun").click(function(){
//        if(confirm("确定要封存吗？"))
//        {
//            var id=$(this).attr('id');
//            $.post("<?php echo U('Home/Customer/close');?>",{ct_id:id},function(result){
//                var data=eval('('+result+')');
//                if(data.status==1){alert("操作成功!");
//                    $("#td_"+id).html("已封存");
//                }else{alert("操作失败");}
//            })
//        }
//    })
//    //取消封存
//    $(".open").click(function(){
//        if(confirm("确定要取消封存吗？"))
//        {
//            var id=$(this).attr('id');
//            $.post("<?php echo U('Home/Customer/open');?>",{ct_id:id},function(result){
//                if(result==1){alert("操作成功!");$("#td_"+id).html("未封存");
//                }else{alert("操作失败");}
//            })
//        }
//    })
</script>
</body>
</html>