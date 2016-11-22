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
<link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="/Public/assets/js/html5shiv.min.js"></script>
<script src="/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
<!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
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
            <select id="line_id" class="w250 form-control">
            <option value="0">配送线路</option>
            <?php if(is_array($line_list)): $i = 0; $__LIST__ = $line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lvo): $mod = ($i % 2 );++$i; if($lvo['line_id'] == $queryLine): ?><option selected="selected" value="<?php echo ($lvo["line_id"]); ?>"><?php echo ($lvo["line_name"]); ?></option>
            <?php else: ?>
            <option value="<?php echo ($lvo["line_id"]); ?>"><?php echo ($lvo["line_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>                    
            <input type="text" readonly="readonly" class="form-control w150 cursor-pointer" id="start_time" placeholder="起始时间" value="<?php echo ($startTime); ?>" />
            <input type="text" readonly="readonly" class="form-control w150 cursor-pointer" id="end_time" placeholder="结束时间" value="<?php echo ($endTime); ?>" />
            <a class="btn btn-default" href="#" id="find" role="button">查询预单</a>
		</div>
		</div>

        <table class="table list_table" id="role_table">
        <thead>
        <tr><td width="40%">终端门店</td>
        <td width="20%">订单编号</td>
        <td width="20%">订单费用</td>
        <td width="20%">下单时间</td>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($orderList)): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shop): $mod = ($i % 2 );++$i; if(is_array($shop["order_list"])): $i = 0; $__LIST__ = $shop["order_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?><tr>
        <?php if($i == 1): ?><td rowspan="<?php echo (count($shop["order_list"])); ?>" style="vertical-align:middle;"><?php echo ($shop["cust_name"]); ?></td><?php endif; ?>
        <td><?php echo ($order["order_code"]); ?></td>
        <td><?php echo ($order["order_total_money"]); ?></td>
        <td><?php echo ($order["add_time"]); ?></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <tfoot></tfoot>
        </table>

		<form id="shippingForm" action="<?php echo U('add');?>" method="post">

            <select id="staff_id" name="staff_id" class="w150 form-control">
            <option value="0">配送人员</option>
            <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['admin_id'] == $queryStaffID): ?><option selected="selected" value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option>
            <?php else: ?>
            <option value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            
			<input type="hidden" id="selectLine" name="lineID" value="<?php echo ($queryLine); ?>" />
        	<input type="hidden" name="presale_list" value='<?php echo ($presaleIDList); ?>' />
            <input type="hidden" name="return_list" value='<?php echo ($returnIDList); ?>' />
            <input type="hidden" name="change_list" value='<?php echo ($changeIDList); ?>' />
            <input type="hidden" name="customer_order_list" value='<?php echo ($customerOrderIDList); ?>' />
            <a class="btn btn-primary bg_3071a9" href="javascript:void(0)" onClick="saveShipping()" role="button">指派配送</a>
        
        </form>
	</div>
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

$("#find").click(function(){

	var line_id = $('#line_id').val();
	var start_time = $("#start_time").val();
	var end_time = $("#end_time").val();
	
	if(line_id == 0 || start_time == '' || end_time == '')
	{
		alert('请选择查询条件');
		return;
	}

	$('#selectLine').val(line_id);
	var url = "<?php echo U('index');?>" + "?lid=" + line_id + "&start=" + start_time + "&end=" + end_time;
	location.href = url;
});

function saveShipping()
{
	var staff_id = $('#staff_id').val();
	if(staff_id == 0)
	{
		alert('请选择配送人员');
		return;
	}
	else
	{
		$('#shippingForm').submit();
	}
}
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