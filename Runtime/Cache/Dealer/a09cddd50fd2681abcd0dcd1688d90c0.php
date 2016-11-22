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
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery.mousewheel.js"></script>
<link rel="stylesheet" type="text/css" href="http://developer.amap.com/Public/css/demo.Default.css" />
<style type="text/css">.markerContentStyle span{font-family: "微软雅黑"}</style>
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

    <div class="main-right">

    <div class="r-sub-nav row-fluid "><?php
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
                <input type="text" value="<?php echo ($today); ?>"  readonly="readonly" class="form-control w200 cursor-pointer" id="route_time"
                       placeholder="时间">
                <select class="form-control w200" id="staff">
                    <option value="0">请选择业务员</option>
                    <?php if(is_array($stafflist)): $i = 0; $__LIST__ = $stafflist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$staffvo): $mod = ($i % 2 );++$i; if($staffvo["staff_id"] == $saleman_id): ?><option selected="selected" value="<?php echo ($staffvo["staff_id"]); ?>"><?php echo ($staffvo["staff_name"]); ?></option>
                            <?php else: ?><option value="<?php echo ($staffvo["staff_id"]); ?>"><?php echo ($staffvo["staff_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <a class="btn btn-default" id="search" href="javascript:void(0)">搜索</a>
            </div>
            <!--<div class="fr">
                <a class="btn btn-primary bg_3071a9" href="javascript:void(0)" id="cre_rousta" role="button">定位设置</a>
            </div>-->
        </div>

        <div class="route pull-left pb40">
            <ul class="nav nav-tabs">
                <li class="active"><a id="route_t1" href="#iCenter" data-toggle="tab">拜访轨迹</a></li>
                <!--<li><a id="route_t2" href="#iCenter" data-toggle="tab">行动轨迹</a></li>-->
                <li><a id="route_t3" href="#route_list" data-toggle="tab">店铺拜访列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="iCenter"></div>
                <div class="tab-pane route-list" id="route_list">
                    <table class="table list_table" id="role_table">
                        <thead>
                        <tr><td width="5%">序号</td>
                            <td widht="15%">店铺名称</td>
                            <td width="28%">地址</td>
                            <td width="12%">时间</td>
                            <!--<td width="10%">单据金额</td>-->
                            <!--<td width="10%">收款</td>-->
                            <!--<td width="10%">查询订单</td>-->
                            <td width="40%">店铺操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
</div>


<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div id="modal-con" class="modal-dialog modal_650"></div></div>

<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script language="javascript" src="http://webapi.amap.com/maps?v=1.3&key=372a8961a7f4ade22c2fc3e7558d337b"></script>
<script language="javascript">
    getRoute();//获取行动轨迹
    userControl();
    $("#route_t1").click(function(){ getRoute(); })
    $("#route_t2").click(function(){ getNowRoute(); })
    var mapObj,toolBar;
    //初始化地图对象，加载地图
    mapObj = new AMap.Map("iCenter");

        $(".route").mousewheel(function(){ CheckState();}).dblclick(function(){ CheckState();})
        function userControl(){
        $(".amap-zoom-plus,.amap-zoom-ruler,.amap-zoom-minus").click(function(){
            CheckState();
        })
    }
    //地图缩放时候的标注显示
    function CheckState()
    {
        var zoom=mapObj.getZoom();
        if(zoom<14)
        {
            $(".markerContentStyle span").hide();
        }
        else
        {
            $(".markerContentStyle span").show();
        }
    }
    function mapInit(arr,arr_now,uid){
        var aLine=arr;
        var aLineNow=arr_now;
        var uid = uid;
        var AlineNew=Array();
        if(aLine.length!=0)
        {
            AlineNew.push(new Array("",aLine[0][1],aLine[0][2]))
        }
        else if(aLine.length==0&&aLineNow.length!=0)
        {
            AlineNew.push(new Array("",aLineNow[0][1],aLineNow[0][2]))
        }
        else if(aLine.length==0&&aLineNow.length==0)
        {
            var Center=getMapCenter();
            if(uid){
                AlineNew.push(new Array("暂无轨迹",Center[0],Center[1]));
            }else{
                AlineNew.push(new Array("请选择业务员",Center[0],Center[1]));
            }
            
        }
        mapObj = new AMap.Map("iCenter",{
            view: new AMap.View2D({
                center:new AMap.LngLat( AlineNew[0][1],AlineNew[0][2]),//地图中心点
                zoom:14 //地图显示的缩放级别
            })
        });
        //在地图中添加ToolBar插件
        mapObj.plugin(["AMap.ToolBar"],function(){
            toolBar = new AMap.ToolBar();
            mapObj.addControl(toolBar);
            userControl();
        });
        if(aLine.length!=0)
        {
            addMarker(aLine,false,1);
            addLine(aLine,false);
        }
        if(aLineNow.length!=0)
        {
            addMarker(aLineNow,false,2);
            addLine(aLineNow,true);
        }
        if(aLineNow.length==0&&aLine.length==0)
        {
            addMarker(AlineNew,false);
        }
    }
    function getMapCenter(){
        var mapCenter = mapObj.getCenter();
        var rArr=new Array(mapCenter.getLng(),mapCenter.getLat());
        return rArr;
    }
    function getRoute()
    {
    	
        $.ajax({
            url:"/index.php/Dealer/Route/sd_selRoute",
            type:"post",
            data:{staff_id:$("#staff").val(),today:$("#route_time").val()},
            dataType:"json",
            beforeSend:function(){
                $(".await").show();
            },
            success:function(data){
                var mapArr=new Array();
                var mapNowArr=new Array();
                var appendCon="";
                var Center= getMapCenter();
                if(data["res"]==1)
                {
                    if(data["info"]!=0)
                    {

                        $.each(data["info"],function(i){
                            var j=i+1;
                            mapArr.push(new Array(data["info"][i]["shopname"]+"    "+data["info"][i]["entertime"],data["info"][i]["longitude"],data["info"][i]["dimension"]));
                            appendCon+="<tr><td>"+j+"</td><td>"+data["info"][i]["shopname"]+"</td><td>"+data["info"][i]["address"]+"</td><td>"+data["info"][i]["entertime"]+"</td><td>"+data["info"][i]["repara_data"]+"</td></tr>"

                        })
                    }
                }
                if($("#staff").val() != 0 && $("#staff") != ''){
                    var uid = $("#staff").val();
                }
                mapInit(mapArr,mapNowArr,uid);
                $("#route_list tbody").empty().append(appendCon);
                routeData();
                $(".await").hide();
            }
        })
    }
    function getNowRoute()
    {
        $.ajax({
            url:"/index.php/Dealer/Route/selNowRoute",
            type:"post",
            data:{staff_id:$("#saleman_id").val(),today:$("#today").val()},
            dataType:"json",
            beforeSend:function(){
                $(".await").show();
            },
            success:function(data){
                var mapArr=new Array();
                var mapNowArr=new Array();
                var Center= getMapCenter();
                if(data["res"]==1)
                {
                    if(data["info_now"]!=0)
                    {
                        $.each(data["info_now"],function(i){
                            mapNowArr.push(new Array(data["info_now"][i]["now_Hi"],data["info_now"][i]["longitude"],data["info_now"][i]["dimension"]));
                        })
                    }
                }
                if($("#saleman_id").val() != 0 && $("#saleman_id") != ''){
                    var uid = $("#saleman_id").val();
                }
                mapInit(mapArr,mapNowArr,uid);
                $(".await").hide();
            }
        })
    }
    //详情
    function routeData()
    {
        $(".route-data").click(function(){
            var para={pos_id:$(this).attr("attr-data"),r:new Date().getTime()};
            ajaxDataPara("/index.php/Dealer/Route/"+$(this).attr("attr"),para);
        })
    }
    //type为true  间隔提取时间
    function addLine(arr,type) {
        var lineArr = new Array();//创建线覆盖物节点坐标数组
        var color=type?"red":"#3366FF";
        for(var i=0;i<arr.length;i++)
        {
            lineArr.push(new AMap.LngLat(arr[i][1],arr[i][2]));
        }
        polyline = new AMap.Polyline({
            path:lineArr, //设置线覆盖物路径
            strokeColor:""+color+"", //线颜色
            strokeOpacity:1, //线透明度
            strokeWeight:2, //线宽
            strokeStyle:"solid", //线样式
            strokeDasharray:[10,5] //补充线样式
        });
        polyline.setMap(mapObj);
    }
    //type为true  间隔提取时间
    function addMarker(arr,type,action){
        //自定义窗体信息
        var infoWindow = new AMap.InfoWindow({
                offset: new AMap.Pixel(0, -30)
            });
        for (var i = 0, marker; i < arr.length; i++) {
            var markerContent = document.createElement("div");
            markerContent.className = "markerContentStyle";
            if(!type){
                var markerImg= document.createElement("img");
                markerImg.className="markerlnglat";
                if(i==arr.length-1)
                {
                    markerImg.src="/Public/assets/images/gps_qidian.png";
                }
                else if(i==0)
                {
                    markerImg.src="/Public/assets/images/gps_zhongdian.png";
                }
                else{
                    if(action==1)
                    markerImg.src="/Public/assets/images/position_shop.png";
                    else
                    markerImg.src="/Public/assets/images/position_time.png";
                }
                markerContent.appendChild(markerImg);
            }
            var markerSpan = document.createElement("span");
            // markerContent.appendChild(markerSpan);
            marker = new AMap.Marker({
                isCustom: true,  //使用自定义窗体
                content: markerContent,
                map:mapObj,
                position:new AMap.LngLat(arr[i][1],arr[i][2]), //基点位置
                offset:new AMap.Pixel(-10,-34), //相对于基点的偏移位置
                draggable:false,  //是否可拖动
            });
            marker.content = arr[i][0];
            marker.on('click', markerClick);
            marker.emit('click', {target: marker});
            mapObj.setFitView();
            }
            function markerClick(e) {
                infoWindow.setContent(e.target.content);
                infoWindow.open(mapObj, e.target.getPosition());
            }
    }
</script>
<script type="text/javascript">
    $(function () {
        $("#route_time").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 2014,//年份的开始默认为1949
            endY :2017//年份的结束默认为2049
        });
        //搜索
        $("#search").click(function(){
        	var today= $("#route_time").val();
        	if(today==""){
        		alert("请选择时间");
        		return;
        	}
            location.href="/index.php/Dealer/Route/index/today/"+$("#route_time").val()+"/staff_id/"+$("#staff").val();
        })
        //设置行动轨迹人员
        $("#cre_rousta").click(function(){
            $.ajax({
                url:"/index.php/Dealer/Route/selStaffRoute",
                type:"get",
                dataType:"html",
                beforeSend:function(){
                    $(".await").show();
                },
                success:function(data){
                    $("#modal-con").empty().append(data);
                    $(".await").hide();
                }
            })
            $("#myModal").modal({backdrop:"static"});
        })
        $(".task_edit").click(function()
        {
            var index=$("#role_table tbody tr .role_del").index($(this));
            var task_id=$(this).attr("attr");
            $.ajax({
                url:"/index.php/Dealer/Route/edit",
                type:"get",
                dataType:"html",
                data:{task_id:task_id},
                beforeSend:function(){
                    $(".await").show();
                },
                success:function(data){
                    $("#modal-con").empty().append(data);
                    $(".await").hide();
                }
            })
            $("#myModal").modal();
        })
    })
</script>
</body>
</html>