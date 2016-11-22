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
<link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="http://developer.amap.com/Public/css/demo.Default.css" />

<!--[if lt IE 9]>
<script src="/Public/assets/js/html5shiv.min.js"></script>
<script src="/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/PCASClass.js"></script>
<script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>

    <!-- <script type="text/javascript" src="/Public/js/jquery-messages_cn.js"></script> -->
    <style type="text/css">
        #show_img{width:auto; height:auto;position:absolute;z-index:300000;display:none}
        #img_src{border:3px solid gray;}
        #img_del{position:absolute;;right:10px;top:10px;width:30px;cursor:pointer;}
        .img_check{display:none}
    </style>
</head>
<body>
<div id="blockUI" style="display:none;background-color:gray; position: absolute; left: 0px; top: 0px; z-index: 1000;  opacity: 0.7;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50);" onClick="return false" onMouseDown="return false" onMouseMove="return false" onMouseUp="return false" onDblClick="return false">
</div>
<div id="show_img">
    <img id="img_del" src="/Public/assets/images/close.png"/>
    <img id="img_src" src="" >
</div>
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
        <div class="fl">
            <select name="staff_id" id="staff_id" class="form-control w200">
                <option value="0">请选择业务员</option>
                    <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["staff_id"] == $staff_id): ?><option selected="selected" value="<?php echo ($vo["staff_id"]); ?>"><?php echo ($vo["staff_name"]); ?></option>
                          <?php else: ?><option value="<?php echo ($vo["staff_id"]); ?>"><?php echo ($vo["staff_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            
            <a class="btn btn-default" href="#" id="find" role="button">查询</a>
            
        </div>
        <div class="fr">

           <!-- <a class="btn btn-primary bg_3071a9" id="cre_c"  role="button">创建</a>-->
           <a class="btn btn-primary bg_3071a9" id="updateAll"  role="button">分配业务员</a>
        </div>
    </div>
    <!--右侧查询结束-->
    <!--表格查询开始-->
    <table class="table list_table">
        <thead>
        <tr>
        	<td width="1%"><input type="checkbox" id="all"></td>
            <td width="8%">客户名称</td>
            <td width="5%">客户类型</td>
            <td width="5%">联系人</td>
            <td width="5%">电话</td>
            <td>地址</td>
            <td width="5%">状态</td>
            <td>所属业务员</td>
            <td width="14%">建店时间</td>
            <td width="14%">操作</td>
        </tr>
        </thead>
        <tbody id="cust_table">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($list["cust_id"]); ?>">
        	 <td><input <?php if($list["is_close"] == 1): ?>disabled='disabled'<?php endif; ?> value="<?php echo ($list["cust_id"]); ?>" class="optional" type="checkbox"/></td>

            <td><?php echo ($list["cust_name"]); ?></td>
            <td><?php echo ($list["ct_name"]); ?></td>
            <td><?php echo ($list["contact"]); ?></td>
            <td><?php echo ($list["telephone"]); ?></td>
            <td><?php echo ($list["province"]); echo ($list["city"]); echo ($list["district"]); echo ($list["address"]); ?></td>
            
            <td id="td_<?php echo ($list["cust_id"]); ?>" class='<?php if($list["is_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'><?php if($list["is_close"] == 1): ?>已封存<?php else: ?>未封存<?php endif; ?></td>
            <td><?php echo ($list["staff_name"]); ?></td>
            <td><?php echo (date("Y-m-d H:i:s",$list["reg_time"])); ?></td>
            <td>
                <ul class="operate-menu li-width33">
                    <!--<li><a class="collapsed collapse-menu icons-href delete" id="<?php echo ($list["cust_id"]); ?>"  attr="<?php echo ($list["is_close"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>删除</a></li>-->
                <?php if($list["is_close"] == 1): ?><li><a class="collapsed collapse-menu icons-href fengcun" id="<?php echo ($list["cust_id"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>解封</a></li>
                    <?php else: ?>
                    <li><a class="collapsed collapse-menu icons-href fengcun" id="<?php echo ($list["cust_id"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>封存</a></li><?php endif; ?>
                </ul>
            </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <!--表格查询结束-->
    
    <?php echo W('Page/page',array("/index.php/Dealer/Customer/index",$pnum,$pagelist,array("staff_id"=>$staff_id)));?>
</div>
<!--主体操作区域结束-->
    <div id="await" class="await waits">
        <span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span>
    </div>
    <!--新建弹出层开始-->
    <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="modal-con"  class="modal-dialog modal_850 "></div></div></div>
    <!--新建弹出层结束-->
</div>
</div>
<div class="jwd_map">
    <div id="iCenter"></div>
    <div class="iCenter_info">
       <span>&nbsp;&nbsp;&nbsp;&nbsp;经度&nbsp;&nbsp;&nbsp;&nbsp;<input readonly="readonly" type="text" class="form-control w130" id="lngX"></span>
       <span>&nbsp;&nbsp;&nbsp;&nbsp;纬度&nbsp;&nbsp;&nbsp;&nbsp;<input readonly="readonly" type="text" class="form-control w130" id="latY"></span>
       <span class="w50"><input type="button" class="form-control" id="jwd_add" value="确定"></span>
       <span class="w50"><input type="button" class="form-control" id="jwd_close" value="关闭"></span>
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script type="text/javascript">$("#myModal").on("hidden.bs.modal", function() {$(this).removeData("bs.modal");});</script>
<script language="javascript" src="http://webapi.amap.com/maps?v=1.3&key=372a8961a7f4ade22c2fc3e7558d337b"></script>
<script type="text/javascript">
    var mapObj,marker;
    mapInit();
    //初始化地图对象，加载地图
    function mapInit(){
        mapObj = new AMap.Map("iCenter");
        mapObj.plugin(["AMap.ToolBar"],function(){
            toolBar = new AMap.ToolBar();
            mapObj.addControl(toolBar);
            userControl();
        });
        AMap.event.addListener(mapObj,'click',getLnglat);
    }
    //鼠标在地图上点击，获取经纬度坐标
    function getLnglat(e){
        mapObj.clearMap();
        document.getElementById("lngX").value=e.lnglat.getLng();
        document.getElementById("latY").value=e.lnglat.getLat();
       addMarker(e.lnglat.getLng(),e.lnglat.getLat())
    }
    //添加标注
    function addMarker(j,w){
        marker = new AMap.Marker({
            icon:"/Public/assets/images/mark.png",
            position:new AMap.LngLat(j,w)
        });
        marker.setMap(mapObj);  //在地图上添加点
    }
</script>
<script type="text/javascript">
    
    $("#all").click(function(){
    	var res=$("#all").attr("checked");
    	
    	if(res=="checked"){
    	    $(".optional").attr("checked","checked");	
    	}else{
    		$(".optional").attr("checked",false);
    	}
    });
   
    $("#jwd_close").click(function(){
        $(".jwd_map").hide();
        $("#submit_unit,#close_customer").removeAttr("disabled");
    })
    $("#jwd_add").click(function(){
        $(".jwd_val").val($("#lngX").val()+","+$("#latY").val())
        $("#submit_unit,#close_customer").removeAttr("disabled");
        $(".jwd_map").hide();
    })
    
    $(".big_img").click(function(e){
        var img_con="";
        $("#blockUI").show().width($(window).width()).height($("body").height());
        var b_top=$(window).scrollTop()+25;
        $("#img_src").attr("src",$(this).attr("src"));
        var img_width=400;
        var b_left=($(window).width()-img_width)/2;
        $("#show_img").show(300).css({"top":b_top+"px","left":b_left+"px","z-index":"10000"});
        if($("#img_src").width()>=$("#img_src").height())
        {
            if($("#img_src").width()>=600)
                $("#img_src").width(600)
        }
        else{
            if($("#img_src").height()>=400)
                $("#img_src").height(400)
        }
    })
    $("#img_del").click(function(){
        $("#show_img").hide(300);
        $("#blockUI").hide(300)
    })
    
    
    
    $("#cre_c").click(function () {
        $.ajax({
            url: "/index.php/Dealer/Customer/addCustomer/r/"+new Date().getTime(),
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
    });
    $("#updateAll").click(function(){
    	var custs=$(".optional:checked");
    	if(custs.length==0){
		    alert("请选择要修改的店铺");
		    return;
		}
    	var cust_id;
    	var cust_ids=[];
    	custs.each(function(){
    		cust_id=$(this).val();
    		cust_ids.push(cust_id);
    	})
    	var data={"cust_ids":cust_ids};
    	ajaxDataPara("/index.php/Dealer/Customer/updateAll",data);
    });
   
    //删除
    $(".delete").click(function(){
        var state=$(this).attr("attr");
        if(state==1){
            alert("该客户已封存，暂不能删除，如果确定删除，请先解除封存状态！");
        }else{
            if(confirm("确定要删除该记录吗？"))
            {
                var id=$(this).attr('id');
                
                $.post("<?php echo U('Dealer/Customer/delCustomer');?>",{cust_id:id},function(result){
                    if(result.code==1){
                        alert("删除成功!");
                        location.reload();
                    }else{
                        alert("失败");
                    }
                },"json");
            }
        }
    })
    //封存
    $(".fengcun").click(function () {

        if(confirm("确定要改变封存状态吗？")) {
            var id=$(this).attr('id');

            $.post("<?php echo U('Dealer/Customer/fengcun');?>",{cust_id:id},function(result){

                if(result.status){
                    alert(result.msg);
                    location.reload();
                }else{
                    alert(result.msg);
                }
            },"json");
        }
    })
    

    $("#find").click(function(){
        var con="";
        if($("#staff_id").val()!=0)
            con+="/staff_id/"+$("#staff_id").val();        
        
        location.href="/index.php/Dealer/Customer/index"+con;
    })

   

    
    
 </script>
</body>
</html>