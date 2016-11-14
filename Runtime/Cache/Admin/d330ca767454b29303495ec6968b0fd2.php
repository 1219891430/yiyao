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
            <?php if(is_array($depot_list)): $i = 0; $__LIST__ = $depot_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["repertory_id"] == $queryDepot): ?><option selected="selected" value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option>
            <?php else: ?>
            <option value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </select>

        <select id="queryOrg" class="w200 form-control">
            <option value="0">请选择经销商</option>
            <?php if(is_array($org_list)): $i = 0; $__LIST__ = $org_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["org_id"] == $queryOrg): ?><option selected="selected" value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </select>

        <input type="hidden" value="<?php echo ($_GET["shopIds"]); ?>" id="shopIds" />
        <input type="text" readonly="readonly" value="<?php echo ($queryBeginTime); ?>"  class="form-control w200 cursor-pointer " onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="start_time" placeholder="起始时间">
        <input type="text" readonly="readonly" value="<?php echo ($queryEndTime); ?>"  class="form-control w200 cursor-pointer " onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="end_time" placeholder="结束时间">
        <a class="btn btn-default" href="#" id="selectShop" role="button">选择店铺</a>
        <a class="btn btn-default" href="#" id="findC" role="button">查询（店铺汇总）</a>

    </div>
    </div>
	<div class="main-container">

        <div id="printHtml">
            <?php if(is_array($summaryData)): $i = 0; $__LIST__ = $summaryData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><div><h2 style="line-height:40px; font-size:18px; font-weight:700;" ><?php echo ($data["org_name"]); ?></h2></div>
                <table class="table list_table" id="tb">
                    <thead>
                    <tr>
                        <td width="5%">分类</td>
                        <td width="5%">名称</td>
                        <td width="8%">合计</td>
                        <?php if(is_array($shops)): foreach($shops as $key=>$shop): ?><td><?php echo ($shop['cust_name']); ?></td><?php endforeach; endif; ?>


                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data["class_list"])): $i = 0; $__LIST__ = $data["class_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i; if(is_array($class["goods_list"])): $i = 0; $__LIST__ = $class["goods_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?><tr>
                                <?php if($i == 1): ?><td style="vertical-align: middle;" rowspan="<?php echo (count($class["goods_list"])); ?>"><?php echo ($class["class_name"]); ?></td><?php endif; ?>
                                <td style="vertical-align: middle;"><?php echo ($goods["goods_name"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo ($goods['total_numstring']); ?></td>

                                <?php if(is_array($shops)): foreach($shops as $key=>$shop): ?><td style="vertical-align: middle;"><?php echo ($goods[$shop['cust_id']]['total_numstring']); ?></td><?php endforeach; endif; ?>

                            </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                    <tfoot></tfoot>
                </table><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div style="margin-bottom:10px; text-align:left;">
            <a id="print" class="btn btn-primary"><span>打印</span></a>
            <a id="exportS" class="btn btn-primary"><span>导出（店铺汇总）</span></a>
            <?php if($queryOrg > 0): ?><a id="markPurchase" class="btn btn-primary"><span>生成（采购单）</span></a><?php endif; ?>
        </div>
       
	</div>
	</div>
	</div>
</div>



<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>


<div class="modal" id="myModal1" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal_650 ">
        <div class="modal-content modal_650">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title fb f16">
                    选择需要生成的分类
                </h4>
            </div>
                <div class="modal-body modal_650">
                    <input name="id" value="<?php echo ($order['order_id']); ?>" type="hidden">
                    <table class="table no_border">
                        <thead></thead>
                        <tbody>
                        <tr>
                            <td>
                                <?php if(is_array($summaryData)): $i = 0; $__LIST__ = $summaryData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i; if(is_array($data["class_list"])): $i = 0; $__LIST__ = $data["class_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i; echo ($class["class_name"]); ?><input name="filterClass" class="check_mt" type="checkbox" value="<?php echo ($class["class_id"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

                            </td>
                        </tr>
                        </tbody>
                    </table>


                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-default"
                       data-dismiss="modal">关闭
                    </a>
                    <a id="submit_unit" class="btn btn-primary">
                        确认
                    </a>
                </div>
        </div>

    </div>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog" style="width:1200px;">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/jquery.jqprint-0.3.js"></script>

<script type="text/javascript">
$(function(){

    //联动
    $("#queryDepot").change(function(){
        var depot_id = $(this).val();
        if(depot_id ==0){
            $("#queryOrg").html('<option value=0>请选择经销商</option>');
            return;
        }
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getDepotOrg');?>",data:{ depot_id:depot_id }, dataType:'json',timeout: 5000,
            error: function(){
            },
            success: function($r){
                $("#queryOrg").html('<option value=0>请选择经销商</option>');
                if($r.status){
                    var html = '<option value=0>请选择经销商</option>';
                    $.each($r.rows,function(index,item){
                        html+= '<option value="'+item.org_id+'">'+item.org_name+'</option>';
                    });
                    $("#queryOrg").html(html);
                }
            }
        });
    });


    $("#findC").click(function(){

		var depot_id = $('#queryDepot').val();
        var org_id = $('#queryOrg').val();
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		var shopIds=$('#shopIds').val();
		
		var url = "<?php echo U('shop');?>" + "?type=2&did=" + depot_id + "&oid=" + org_id + "&st=" + start_time + "&et=" + end_time + "&shopIds=" + shopIds + "&r=" + new Date().getTime();

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
	$("#selectShop").click(function(){
		
        ajaxData("/index.php/Admin/PresaleSummary/selectShop");
    
		
	});
	
//	$("#selectShop").click(function(){
//		
//      ajaxData("/index.php/Admin/PresaleSummary/setting");
//  
//		
//	});


    $("#exportS").click(function(){
		var depot_id = $('#queryDepot').val();
        var org_id = $('#queryOrg').val();
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		var url = "<?php echo U('shop');?>" + "?exportS=exportS&did=" + depot_id + "&oid=" + org_id + "&st=" + start_time + "&et=" + end_time + "&r=" + new Date().getTime();

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

    $("#markPurchase").click(function(){
        $("#myModal1").modal({backdrop:"static"});
    });

    $('#submit_unit').click(function(){

        var id_array=new Array();
        $('input[name="filterClass"]:checked').each(function(){
            id_array.push($(this).val());
        });
        var idstr=id_array.join(',');

        var depot_id = $('#queryDepot').val();
        var org_id = $('#queryOrg').val();
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        var url = "<?php echo U('shop');?>" + "?markPurchase=markPurchase&did=" + depot_id + "&oid=" + org_id + "&st=" + start_time + "&et=" + end_time + "&class="+ idstr + "&r=" + new Date().getTime();

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