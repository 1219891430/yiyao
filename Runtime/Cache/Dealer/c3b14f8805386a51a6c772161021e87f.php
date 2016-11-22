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
<script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
<style>
  li{
  	list-style-type: none;
  }
	
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
            
            <input class="w150 form-control" id="name" value="<?php echo ($urlPara["name"]); ?>" placeholder="商品名称"/>
            	
            <select id="brand_id" class="w150 form-control">
            <option value="0">全部品牌</option>
            <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i;?><option <?php if($bvo['brand_id'] == $urlPara['brand_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($bvo["brand_id"]); ?>"><?php echo ($bvo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select id="class_id" class="w150 form-control">
            <option value="0">全部类别</option>
            <?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cvo): $mod = ($i % 2 );++$i;?><option <?php if($cvo['class_id'] == $urlPara['class_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($cvo["class_id"]); ?>"><?php echo ($cvo["class_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a class="btn btn-default" href="#" id="find" role="button">查询</a>
            </div>
            <div class="fr"></div>
            </div>

            <table class="table list_table" id="role_table">
            <thead>
            <tr><td width="25%">商品名称</td>
            <td width="10%">包装单位</td>
            <td width="10%">包装类型</td>
            <td width="10%">商品进价</td>
            <td width="10%">商品售价</td>
            <td width="10%">操作</td>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($price)): $i = 0; $__LIST__ = $price;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
            <td>
            <?php echo ($list["goods_name"]); ?>/<?php echo ($list["goods_spec"]); ?>
            </td>
            <td><?php echo ($list["goods_unit"]); ?>
            <?php if($list["unit_default"] == 1): ?><input type="radio" name="goods_unit_default_<?php echo ($list["goods_id"]); ?>" data-goods="<?php echo ($list["goods_id"]); ?>" data-unit="<?php echo ($list["goods_unit_type"]); ?>" checked onChange="setDefaultUnit(this)" />
            <?php else: ?>
            <input type="radio" name="goods_unit_default_<?php echo ($list["goods_id"]); ?>" data-goods="<?php echo ($list["goods_id"]); ?>" data-unit="<?php echo ($list["goods_unit_type"]); ?>" onChange="setDefaultUnit(this)"  /><?php endif; ?>
            </td>
            <td>
            <?php if($list["goods_unit_type"] == 1): ?>小包装
            <?php elseif($list["goods_unit_type"] == 2): ?>中包装
            <?php elseif($list["goods_unit_type"] == 3): ?>大包装<?php endif; ?>
            </td>
            <td class="text-center">
            	<?php echo ($list["goods_jin_price"]); ?>
                <!--<input type="text" style="border-width:1px" class="base_price" attr="<?php echo ($list["cv_id"]); ?>" data-flag="1" name="jin_price" data-old="<?php echo ($list["goods_jin_price"]); ?>" onKeyUp="checkNum(this)" value="<?php echo ($list["goods_jin_price"]); ?>">-->
            </td>
            <td class="text-center">
            	<?php echo ($list["goods_base_price"]); ?>
                <!--<input type="text" style="border-width:1px" class="base_price" data-old="<?php echo ($list["goods_base_price"]); ?>" attr="<?php echo ($list["cv_id"]); ?>" data-flag="2" name="base_price" onKeyUp="checkNum(this)" value="<?php echo ($list["goods_base_price"]); ?>">-->
            </td>
            <td>
            	<li><a attr="<?php echo ($list["cv_id"]); ?>" class="collapsed collapse-menu icons-href goods_set" href="javascript:void(0)">
        			<i class="icon-remove-circle"></i>设置价格
        		</a></li>
            	
            </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
            <tfoot></tfoot>
            </table>
            <?php echo W('Page/page',array("/index.php/Dealer/GoodsPrices/index",$pnum,$pagelist,$urlPara));?>
		</div>
    
	</div>
</div>
<div id="await" class="await">
    <span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top: 100px;" >
    <div id="modal-con" class="modal-dialog modal_650">
    </div>
</div>
<script type="text/javascript">


    

    $(".goods_set").click(function(){
    	  var data={cv_id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Dealer/GoodsPrices/setPrice",data);
    });

    /*var old_price = 0;

    $(".base_price").focus(function () {
        old_price = $(this).val();
    });*/


    $(".base_price").blur(function () {
        var id = $(this).attr("attr");
        var price = $(this).val();
        var flag = $(this).attr("data-flag");
        var old_price = toDecimal2($(this).data("old"))

        var obj = $(this);
        $.ajax({
            url: "/index.php/Dealer/GoodsPrices/changePrice",
            type: "post",
            dataType: "json",
            data: {
                id: id,
                price: price,
                flag: flag
            },
            success: function (data) {
                if (data.res == 1) {
                    obj.val(data.info)
                }
                else {
                    //alert(data.info);
                    obj.val(old_price);
                }

            }
        });
    });

    function toDecimal2(x) {
        var f = parseFloat(x);
        if (isNaN(f)) {
            return false;
        }
        var f = Math.round(x*100)/100;
        var s = f.toString();
        var rs = s.indexOf('.');
        if (rs < 0) {
            rs = s.length;
            s += '.';
        }
        while (s.length <= rs + 2) {
            s += '0';
        }
        return s;
    }




        $("#find").click(function(){

                var h = "/index.php/Dealer/GoodsPrices?"

                h += "brand_id=" + $("#brand_id").val();


                h += "&class_id=" + $("#class_id").val();
                h += "&name=" + $("#name").val();

                location.href = h;
        });


        function checkNum(obj) {
            //检查是否是非数字值
            if (isNaN(obj.value)) {
                obj.value = "";
            }
            if (obj != null) {
                //检查小数点后是否对于两位
                if (obj.value.toString().split(".").length > 1 && obj.value.toString().split(".")[1].length > 2) {
                    alert("小数点后多于两位！");
                    obj.value = "";
                }
            }
        };


function setDefaultUnit(obj)
{
	var gid = $(obj).attr('data-goods');
	var uid = $(obj).attr('data-unit');
	var isChecked = $(obj).prop('checked');

	$.ajax({
		url: "<?php echo U('setDefaultUnit');?>" + "?gid="+gid+"&uid="+uid+"&r=" + new Date().getTime(),
		type: "post",
		dataType: "json",
		success: function(data){
               // $('*[data-goods=gid]').attr('checked',false);
                if(data.info=='ok'){
                        obj.attr('checked',true);
                }
                else{
                        alert('设置出错');
                        location.reload(true);
                }
		        //
		}
	});

}
</script>

</body>
</html>