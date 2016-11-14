<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>农乐汇-抓单宝</title>
<link href="/Public/assets/css/bootstrap.css" rel="stylesheet">
<link href="/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/Public/assets/css/style.css" rel="stylesheet">
<link href="/Public/assets/css/font-awesome.min.css" rel="stylesheet">
<link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
<link href="/Public/assets/css/jquery-ui.min.css" rel="stylesheet">
<!--<link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">-->
<!--[if lt IE 9]>
<script src="/Public/assets/js/html5shiv.min.js"></script>
<script src="/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script>
<script type="text/javascript" src="/Public/assets/js/switch/jquery-ui.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery-ui-slide.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script language="javascript" type="text/javascript" src="/Public/assets/My97DatePicker/WdatePicker.js"></script>


 
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
    
        <select id="queryDepot" class="w200 form-control">
        <option value="0">请选择仓库</option>
        <?php if(is_array($depot_list)): $depotID = 0; $__LIST__ = $depot_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$depotName): $mod = ($depotID % 2 );++$depotID; if($depotID == $queryDepot): ?><option selected="selected" value="<?php echo ($depotID); ?>"><?php echo ($depotName); ?></option>
        <?php else: ?>
        <option value="<?php echo ($depotID); ?>"><?php echo ($depotName); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <input type="text" readonly="readonly" value="<?php echo ($queryBeginTime); ?>"  class="form-control w200 cursor-pointer " onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="start_time" placeholder="起始时间">
        <input type="text" readonly="readonly" value="<?php echo ($queryEndTime); ?>"  class="form-control w200 cursor-pointer " onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="end_time" placeholder="结束时间">
        <a class="btn btn-default" href="#" id="find" role="button">查询（预单类型）</a>

    </div>
    </div>
	<div class="main-container">
        <div id="printHtml" >
            <?php if(is_array($summaryData)): $i = 0; $__LIST__ = $summaryData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><div><h2 style="line-height:40px; font-size:18px; font-weight:700;" ><?php echo ($data["org_name"]); ?></h2></div>
                <table class="table list_table" id="tb">
                    <thead>
                    <tr>
                        <td width="10%">品牌</td>
                        <td>产品</td>
                        <td width="10%">规格</td>
                        <td width="10%">销售</td>
                        <td width="10%">退货</td>
                        <td width="10%">调出</td>
                        <td width="10%">换回</td>
                        <td width="10%">小计</td>
                        <td width="10%">出货量</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data["brand_list"])): $i = 0; $__LIST__ = $data["brand_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$brand): $mod = ($i % 2 );++$i; if(is_array($brand["goods_list"])): $i = 0; $__LIST__ = $brand["goods_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?><tr>
                                <?php if($i == 1): ?><td style="vertical-align: middle;" rowspan="<?php echo (count($brand["goods_list"])); ?>"><?php echo ($brand["brand_name"]); ?></td><?php endif; ?>
                                <td style="vertical-align: middle;"><?php echo ($goods["goods_name"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo ($goods["goods_spec"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo ($goods["sales_numstring"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo ($goods["return_numstring"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo ($goods["change_out_numstring"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo ($goods["change_in_numstring"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo (sprintf('%.2f',$goods["total"])); ?>元</td>
                                <td style="vertical-align: middle;"><?php echo ($goods["total_numstring"]); ?></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                    <tfoot></tfoot>
                </table><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>


        <div style="margin-bottom:10px; text-align:left;">
            <a id="print" class="btn btn-primary"><span>打印</span></a>
            <a id="export" class="btn btn-primary"><span>导出（预单类型）</span></a>

            <!--<a id="check" class="btn btn-primary"><span>确认对账</span></a>-->
        </div>
       
	</div>
	</div>
	</div>
</div>


<script type="text/javascript" src="/Public/assets/js/jquery.jqprint-0.3.js"></script>

<script type="text/javascript">
$(function(){

	
	// 查询
	$("#find").click(function(){

		var depot_id = $('#queryDepot').val();
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		var url = "<?php echo U('index');?>" + "?type=1&did=" + depot_id + "&st=" + start_time + "&et=" + end_time + "&r=" + new Date().getTime();
		
		// 检查输入
		if(depot_id == 0 || start_time == '' || end_time == '')
		{
			alert('选择查询条件！');
		}
		else
		{

			location.href = url;
		}
	});
	
	$("#export").click(function(){
		var depot_id = $('#queryDepot').val();
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		var url = "<?php echo U('index');?>" + "?export=export&did=" + depot_id + "&st=" + start_time + "&et=" + end_time + "&r=" + new Date().getTime();
		
		// 检查输入
		if(depot_id == 0 || start_time == '' || end_time == '')
		{
			alert('选择查询条件！');
		}
		else
		{
			location.href = url;
		}
	});
	
	
	$("#print").click(function(){

        $("#printHtml").jqprint();
	});

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