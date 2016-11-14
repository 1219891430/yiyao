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
<!--[if lt IE 9]>
<script src="/Public/assets/js/html5shiv.min.js"></script>
<script src="/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/jquery-ui.min.js"></script>
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
            <input id="shopName" name="shopName" type="text" placeholder="请输入终端名称"  class="form-control w400" />
            <input type="hidden" id="shopID" name="shopID" value="0" />
            <input type="hidden" id="adminID" name="adminID" value="<?php echo ($admin_info["admin_id"]); ?>" />
            <a class="btn btn-default" href="javascript:void(0)" onClick="addShops()" role="button">添加</a>
            </div>
		</div>

        <table class="table list_table">
        <thead>
        <tr>
        <td width="15%">采单人员</td>
        <td width="20%">店铺名称</td>
        <td width="15%">联系方式</td>
        <td width="10%">所在地区</td>
        <td>详细地址</td>
        <td width="10%">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($shops)): $i = 0; $__LIST__ = $shops;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($admin_info["true_name"]); ?></td>
        <td><?php echo ($da["cust_name"]); ?></td>
        <td><?php echo ($da["contact"]); ?>【<?php echo ($da["telephone"]); ?>】</td>
        <td><?php echo ($da["district"]); ?></td>
        <td><?php echo ($da["address"]); ?></td>
        <td>
        <ul class="operate-menu li-width33">
        <li><a class="collapsed collapse-menu icons-href" href="javascript:void(0)" onClick="delShops('<?php echo ($admin_info[admin_id]); ?>','<?php echo ($da[cust_id]); ?>')"><i class="icon-edit"></i>删除</a></li>
        </ul>
        </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <tfoot></tfoot>
        </table>
	</div>
    </div>
</div>

<script type="text/javascript">

// 查询店铺
$("#shopName").autocomplete({
	width: 400,//提示的宽度
	max: 20,//列表条目数
	scrollHeight: 50,//提示的高度
	scroll: true, //是否使用滚动条
	matchContains: true,//是否只要包含文本框里的就可以
	autoFill:true,//自动填充
	multiple: false,
	source: function(request, response) {
		$.ajax({
			url: "/index.php/Admin/CollectShop/queryShops",
			dataType: "json",
			data: {'shop_name':$('#shopName').val()},
			success: function(data) {
				response( $.map( data.info, function( item ) {
					return { label: item.name,value: item.name, orgid: item.id}
				}));
			}
		});
	},
	select: function( event, ui ) {
		var shopID = ui.item.orgid;
		$('#shopID').val(shopID);
	}
});

// 增加店铺
function addShops()
{
	var shopID = $('#shopID').val();
	var adminID = $('#adminID').val();
	if(shopID == 0 || shopID == '' || adminID == 0 || adminID == '')
	{
		alert('请选择终端店');
		return;
	}
	else
	{
		if(confirm("确定要添加该商铺吗？"))
		{
			var url = "<?php echo U('addshop');?>" + "?aid=" + adminID + "&sid=" + shopID;
			$.get(url, function(result){
                if(result == 2) {
                    alert("该店铺已添加，无需再次添加!");
                }
				else if(result == 1) {
					alert("添加成功!");
					location.reload(true);
				} else {
					alert("添加失败");
				}
			});
		}
	}
}

// 删除店铺
function delShops(aid, sid)
{
	if(confirm("确定要删除该记录吗？"))
	{
		var url = "<?php echo U('delshop');?>" + "?aid=" + aid + "&sid=" + sid;
		$.get(url, function(result){
			if(result == 1) {
				alert("删除成功!");
				location.reload(true);
			} else {
				alert("删除失败");
			}
		});
	}
}
</script>

<style type="text/css">
.ui-autocomplete {background-color:#e4e4e4; z-index:99; width:140px;}
.ui-autocomplete .ui-menu-item {height:25px; padding-left:12px;}
.ui-autocomplete .ui-menu-item:hover{background-color:#ccc;}
</style>
</body>

</html>