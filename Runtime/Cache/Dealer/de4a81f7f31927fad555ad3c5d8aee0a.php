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
<!--[if lt IE 9]>
<script src="/Public/assets/js/html5shiv.min.js"></script>
<script src="/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
    <link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
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
            <div class="fl">
                <input type="text" value="<?php echo ($urlPara['cust']); ?>" class="w150 form-control" id="cust"  placeholder="请输入购货方">
            <input type="text" readonly="readonly" value="<?php echo ($urlPara["start"]); ?>"  class="form-control w130 cursor-pointer" id="start_time"
            placeholder="起始时间">
            <input type="text" readonly="readonly" value="<?php echo ($urlPara["end"]); ?>" class="form-control w130 cursor-pointer" id="end_time"
            placeholder="结束时间">
            <a class="btn btn-default" href="#" id="find" role="button">查询</a>
            </div>
            <div class="fr"></div>
            </div>
    
            <table class="table list_table" id="role_table">
            <thead>
            <tr>
            <td width="15%">单据号</td>
            <td>购货方</td>
            <td width="8%">订单金额</td>
            <td width="8%">结算方式</td>
            <td width="8%">业务员</td>
            <td width="10%">日期</td>
            <!--<td width="8%">预单状态</td>-->
            <td width="20%">操作</td>
            </tr>
            </thead>
            <tbody>

            <?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td class="text-center"><?php echo ($vo["order_code"]); ?></td>
            <td class="text-center"><?php echo ($vo["cust_name"]); ?></td>
            <td class="text-center"><?php echo ($vo["order_total_money"]); ?></td>
            <td class="text-center">
            <?php switch($vo["order_way"]): case "1": ?>货到付款<?php break;?>
            <?php case "2": ?>账期结算<?php break;?>
            <?php case "3": ?>月度结算<?php break;?>
            <?php default: ?>
            其他<?php endswitch;?>
            </td>
            <td class="text-center"><?php echo ($vo["staff_name"]); ?></td>
            <td class="text-center"><?php echo (date("Y-m-d H:i:s", $vo['create_time'])); ?></td>
                <!--<td class="text-center">
                    <?php switch($vo['order_status']): case "1": ?>已下单<?php break;?>
                        <?php case "2": ?>已配送<?php break;?>
                        <?php default: ?>
                        其他<?php endswitch;?>
                </td>-->
            
            <td>
            <ul class="operate-menu li-width22">
            <!-- add begin -->
            <li style="width:33%">
            <a class="collapsed collapse-menu icons-href look" role="button"
            attr="<?php echo ($vo["order_id"]); ?>"><i class="icon-edit"></i>查看&nbsp;&nbsp;
            </a>
                </li>
            
            </ul>
            </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            
            </tbody>
            <tfoot></tfoot>
            </table>
    
            <?php echo W('Page/page',array("/index.php/Dealer/PlanOrder/index",$pnum,$pagelist,$urlPara));?>
    
        </div>
    </div>
</div>

<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>

<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>

<script type="text/javascript">

    $(function(){
        $("#start_time,#end_time").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 2014,//年份的开始默认为1949
            endY :2049//年份的结束默认为2049
        });
    });

    //查询
    $("#find").click(function(){
        //depot_id  staff_id  start_time
        var h = "/index.php/Dealer/PlanOrder?";

        // cust
        h += "&cust=" + $("#cust").val();

        // start_time
        h += "&start=" + $("#start_time").val();

        // end_time
        h += "&end=" + $("#end_time").val();

        location.href = h;
    });

    //查看
    $(".look").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Dealer/PlanOrder/look/r/" + new Date().getTime(),data);
    })

</script>

</body>
</html>