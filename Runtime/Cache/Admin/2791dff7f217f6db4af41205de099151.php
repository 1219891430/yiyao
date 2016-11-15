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
    <div class="r-sub-nav row-fluid">
            <?php
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
                    <select name="query[repertory_id]" id="repertory_id" class="form-control w200">
                                <option value="0">请选择仓库</option>
                                <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["repertory_id"] == $query['repertory_id']): ?><option selected="selected" value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option>
                                        <?php else: ?><option value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                    <select name="query[dealer_id]" id="dealer_id" class="form-control w200">
                        <option value="0">请选择经销商</option>
                        <?php if(is_array($dealerList)): $i = 0; $__LIST__ = $dealerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["org_id"] == $query['dealer_id']): ?><option selected="selected" value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option>
                                <?php else: ?><option value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
            <input type="text" class="form-control w200" id="cust_name" name="query['cust_name']"  value="<?php echo ($query["cust_name"]); ?>"  placeholder="请输入商铺名称" />
            <a class="btn btn-default" href="#" id="find" role="button">查询</a>
        </div>
        <div class="fr">
            <!--<a class="btn btn-primary bg_3071a9" id="cre_c"  role="button">创建</a>-->
        </div>
    </div>
    <!--右侧查询结束-->
    <!--表格查询开始-->
    <table class="table list_table">
        <thead>
        <tr>
            <td width="10%">客户名称</td>
            <td width="10%">联系人</td>
            <td width="10%">电话</td>
            <td>地址</td>
            <td width="60">状态</td>

            <td width="180">建店时间</td>
            <td width="200">操作</td>
        </tr>
        </thead>
        <tbody id="cust_table">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($list["cust_id"]); ?>">
            <td><?php echo ($list["cust_name"]); ?></td>
            <td><?php echo ($list["contact"]); ?></td>
            <td><?php echo ($list["telephone"]); ?></td>
            <td><?php echo ($list["province"]); echo ($list["city"]); echo ($list["district"]); echo ($list["address"]); ?></td>
            <td id="td_<?php echo ($list["cust_id"]); ?>" class='<?php if($list["is_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'><?php if($list["is_close"] == 1): ?>关闭<?php else: ?>开启<?php endif; ?></td>
            <td><?php echo (date("Y-m-d H:i:s",$list["reg_time"])); ?></td>
            <td>
                <ul class="operate-menu li-width33">
                    <li><a class="collapsed collapse-menu icons-href edit"  role="button" data-p="<?php echo ($p); ?>"  data-id="<?php echo ($list["cust_id"]); ?>"><i class="icon-edit"></i>修改</a></li>
                    <?php if($list["is_close"] == 1): ?><li><a data-id="<?php echo ($list["cust_id"]); ?>" class="collapsed collapse-menu icons-href open" href="javascript:void(0)"><i class="icon-remove-circle"></i>启用</a></li>
                        <?php else: ?>
                        <li><a data-id="<?php echo ($list["cust_id"]); ?>" class="collapsed collapse-menu icons-href closed" href="javascript:void(0)"><i class="icon-remove-circle"></i>关闭</a></li><?php endif; ?>
                    <!--
                    <li><a class="collapsed collapse-menu icons-href delete" data-id="<?php echo ($list["cust_id"]); ?>"  data-close="<?php echo ($list["is_close"]); ?>" href="javascript:void(0)"><i class="icon-remove-circle"></i>删除</a></li>
                    -->
                </ul>
            </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>

        <?php echo W('Page/page',array("/index.php/Admin/Shops/index",$pnum,$pagelist,$query));?>
        </div>
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<!--新建弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con"  class="modal-dialog modal_850 "></div>
</div>
<!--新建弹出层结束-->
<!--地图弹出层开始-->
<div class="jwd_map">
    <div id="iCenter"></div>
    <div class="iCenter_info">
        <span>&nbsp;&nbsp;&nbsp;&nbsp;经度&nbsp;&nbsp;&nbsp;&nbsp;<input readonly="readonly" type="text" class="form-control w130" id="lngX"></span>
        <span>&nbsp;&nbsp;&nbsp;&nbsp;纬度&nbsp;&nbsp;&nbsp;&nbsp;<input readonly="readonly" type="text" class="form-control w130" id="latY"></span>
        <span class="w50"><input type="button" class="form-control" id="jwd_add" value="确定"></span>
        <span class="w50"><input type="button" class="form-control" id="jwd_close" value="关闭"></span>
    </div>
</div>
<!--地图弹出层结束-->
<script language="javascript" src="http://webapi.amap.com/maps?v=1.3&key=372a8961a7f4ade22c2fc3e7558d337b"></script>
<script type="text/javascript">
    var mapObj,marker;
    mapInit();
    //初始化地图对象，加载地图
    function mapInit(){
        mapObj = new AMap.Map("iCenter",{
           resizeEnable: true,
           zoom:12,
        });
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
    //部门联动
    $("#repertory_id").change(function(){
        var depot_id = $(this).val();
        if(depot_id ==0){
            $("#dealer_id").html('<option value=0>请选择经销商</option>');
            return;
        }
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getDepotOrg');?>",data:{ depot_id:depot_id }, dataType:'json',timeout: 5000,
            error: function(){
            },
            success: function($r){
                $("#dealer_id").html('<option value=0>请选择经销商</option>');
                if($r.status){
                    var html = '<option value=0>请选择经销商</option>';
                    $.each($r.rows,function(index,item){
                        html+= '<option value="'+item.org_id+'">'+item.org_name+'</option>';
                    });
                    $("#dealer_id").html(html);
                }
            }
        });
    });

    //地图
    $("#jwd_close").click(function(){
        $(".jwd_map").hide();
        $("#submit_unit,#close_customer").removeAttr("disabled");
    });
    $("#jwd_add").click(function(){
        $(".jwd_val").val($("#lngX").val()+","+$("#latY").val())
        $("#submit_unit,#close_customer").removeAttr("disabled");
        $(".jwd_map").hide();
    });

    //创建
    $("#cre_c").click(function () {
        $.ajax({
            url: "/index.php/Admin/Shops/add/r/"+new Date().getTime(),
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

    //查询
    $("#find").click(function() {
        var con = "";
        if ($("#cust_name").val() != '') {
            con += "/cust_name/" + $("#cust_name").val();
        }
        if ($("#repertory_id").val() != 0) {
            con += "/repertory_id/" + $("#repertory_id").val();
        }
        if ($('#dealer_id').val() != 0) {
            con += '/dealer_id/'+$('#dealer_id').val();
        }
        location.href="/index.php/Admin/Shops/index"+con;
    });


    //编辑
    $(".edit").click(function () {
        $.ajax({
            url: "/index.php/Admin/Shops/edit/r/"+new Date().getTime(),
            type: "get",
            dataType: "html",
            data:{cust_id:$(this).attr('data-id'),p:$(this).attr('data-p')},
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

    //删除
    $(".delete").click(function(){
        var state=$(this).attr("data-close");
        if(state==1){
            alert("该客户已封存，暂不能删除，如果确定删除，请先解除封存状态！");
        }else{
            if(confirm("确定要删除该记录吗？"))
            {
                var id=$(this).attr('data-id');
                $.post("<?php echo U('Admin/Shops/del');?>",{cust_id:id},function(result){
                    if(result==1){
                        alert("删除成功!");
                        location.reload();
                    }else{
                        alert("失败");
                    }
                })
            }
        }
    });

    // 启用
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

        }});

    // 关闭
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
        }});

    // 审核
    $(".checkok").click(function () {
        if (confirm("确定设置为【已审核】状态吗？")) {
            var id = $(this).attr('data-id');
            var url = "<?php echo U('check');?>" + "?id=" + id + "&st=1";
            $.get(url, function(result){
                if(result == 1) {
                    alert("操作成功!");
                    location.reload(true);
                } else {
                    alert("操作失败");
                }
            });
        }});

    // 关闭
    $(".checkno").click(function (){
        if (confirm("确定设置为【未审核】状态吗？")) {
            var id = $(this).attr('data-id');
            var url = "<?php echo U('check');?>" + "?id=" + id + "&st=0";
            $.get(url, function(result){
                if(result == 1) {
                    alert("操作成功!");
                    location.reload(true);
                } else {
                    alert("操作失败");
                }
            });
        }});


    $(function(){

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