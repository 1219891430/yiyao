/**
**
** 商品筛选窗口商品列表
**
** JavaScript方法：	queryGoodsList
**
** PHP后台方法：		Home/Goods/selGoods.html
**
** 返回商品所有信息, convert_data 代表商品单位信息, 里面有 num 库存 和 last_base_price 上次交易价格
**
** <tr>
** <td class='tc'><input type='checkbox' value='商品ID' /></td>
** <td>商品名称+商品规格</td>
** <input class='goods_name' type='hidden' value='商品名称' />
** <input class='goods_spec' type='hidden' value='商品规格' />
** <input class='cv_data' type='hidden' value='商品单位信息' />
** </tr>
**
**
** 单据商品列表：：addGoodsIntoOrderGoodsList：：
**
** <div class="goods_body">
** <table id="goods_search">
** <thead><tr><td><input id="choice_all" type="checkbox"></td><td>商品名称</td></tr></thead>
** <tbody>
**
** <tr class='tr_operate'>
** <input type='hidden' class='goods_id' name='goods_id' value="商品ID" />
** <input type='hidden' class='cv_id' name='cv_id' value="商品单位ID" />
** <td><a href='javascript:void(0)' class='goods_del'></a>商品编码</td>
** <td>商品名称+商品规格</td>
** <td>
** <select class='goods_unit_select'>
** <option attr='商品单位全信息' value='商品单位ID' > 商品单位名称 </option>
** <option attr='商品单位全信息' value='商品单位ID' > 商品单位名称 </option>
** <option attr='商品单位全信息' value='商品单位ID' > 商品单位名称 </option>
** </select>
** </td>
** <td><input class='goods_num' type='text' value='商品数量录入' /></td>

** <td><input class='goods_price'  type='text' value='商品销售价格|上次交易价格' /></td>
** <td><input class='goods_price2' type='text' value='商品销售价格' /></td>

** <td class='tr_total'>价格小计</td>
** <td><input class='remark' type='text' value='商品备注' /></td>
** <td><input class='num' type='text' value='商品单位库存' /></td>
** </tr>
**
** </tbody>
** </table>
** </div>
**
**/

// 修改单据时候，初始化单据商品列表事件
// depotID 仓库ID, 为零标示不检查库存
// checkStockURL库存检查接口, 为空标示不检查库存
// is_show_stock 显示商品库存
// is_show_last_price 显示上次交易价格
function editGoodsListDataInit(depotID, checkStockURL, is_show_stock, is_show_last_price)
{
	goodsDelInit();
	goodsUnitChangeInit(is_show_stock, is_show_last_price);
	blurGoodsNumChangeInit(depotID, checkStockURL);
	blurGoodsPriceChangeInit();
}

// 添加单据时候，商品筛选弹框初始化
// depotID 仓库ID, 为零标示不检查库存
// checkStockURL库存检查接口, 为空标示不检查库存
// is_show_stock 显示商品库存
// is_show_last_price 显示上次交易价格
function setGoodsDataInit(depotID, checkStockURL, is_show_stock, is_show_last_price)
{
	// 清除品牌选择
	$("#brand").val(0);
	
	// 清空商品名称输入
    $("#goods").val("");
	
	// 清空筛选商品列表
    $("#goods_search tbody").empty();
	
	// 筛选窗口可以拖动
    $("#goods_div").show().draggable();
	
	// 初始化全选按钮
	selectAllInit();
	
	// 初始化关闭窗口按钮
	closeGoodsBtnInit();
	
	// 初始化添加商品按钮
	addGoodsToTableBtnInit(depotID, checkStockURL, is_show_stock, is_show_last_price);
}

// 初始化全选按钮
function selectAllInit()
{
	$("#choice_all").removeAttr("checked");
	$("#choice_all").click(function(){
		if($(this).attr("checked")!="checked") $(".goods_search tbody input").removeAttr("checked");
        else $(".goods_search tbody input").attr("checked","checked");
    });
}

// 初始化关闭窗口按钮
function closeGoodsBtnInit()
{
	$("#goods-close").unbind();
	$("#goods-close").click(function(){ $("#goods_div").hide(); })
}

// 初始化添加商品按钮
function addGoodsToTableBtnInit(depotID, checkStockURL, is_show_stock, is_show_last_price)
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){ addGoodsIntoOrderGoodsList(depotID, checkStockURL, is_show_stock, is_show_last_price); })
}

// 检索商品, 根据品牌，商品名称检查
// 仓库ID为了查询库存
// 业务员ID，客户ID是为了查询上次交易价格
// convert_data 代表商品单位信息, 里面有 num 库存 和 last_base_price 上次交易价格
function queryGoodsList(url, brand, goods, depot_id, cust_id, staff_id,org_id)
{
	
	$.ajax({
		url:url,
		type:"post",
		dataType:"json",
		data:{brand:brand, goods:goods, depot_id:depot_id, cust_id:cust_id, staff_id:staff_id,org_id:org_id},
		success:function(data){
			
			// 清空商品筛选列表
			$("#goods_search tbody").empty()
			if(data["res"]==0) { alert("暂无数据"); return; }
			
			// 循环商品列表
			var conHTML = "";
			$.each(data["data"],function(i){	
				
				// 商品信息
				var goods_id = data["data"][i]["goods_id"];
				var goods_code = data['data'][i]['goods_code'];
				var goods_name = data["data"][i]['goods_name'];
				var goods_spec = data['data'][i]['goods_spec'];
				
				// 商品单位信息, 单位ID, 单位名称, 价格goods_base_price, 库存num, 上次交易价格last_base_price
				var convert_data = JSON.stringify(data['data'][i]['convert_data']);

				// 拼接商品HTML
				conHTML += "<tr>";
				conHTML += "<td class='tc'><input class='check_mt0' value='"+goods_id+"' type='checkbox'></td>";
				conHTML += "<td>"+goods_name+goods_spec+"</td>";
				conHTML += "<input class='goods_code' type='hidden' value='"+goods_code+"'>";
				conHTML += "<input class='goods_name' type='hidden' value='"+goods_name+"'>";
				conHTML += "<input class='goods_spec' type='hidden' value='"+goods_spec+"'>";
				conHTML += "<input class='cv_data' type='hidden' value='"+convert_data+"'>";
				conHTML += "</tr>";
			});
			$("#goods_search tbody").append(conHTML);
		}
	});
}

// 拼接无库存HTNL
// is_show_stock是否显示库存, 1显示, 0不显示
// is_show_last_price是否显示上次交易价格, 1显示, 0不显示
function createGoodsHTML(goods_id, goods_code,goods_name, goods_spec, convert, is_show_stock, is_show_last_price)
{
	// 商品默认单位信息
	var optionHTML = '';
	var default_cv_id = 0;
	var default_goods_code = '';
	var default_goods_last_base_price = 0;
	var default_goods_base_price = 0;
	var default_goods_stock = 0;
	
	// 循环单位列表, 拼接单选选择Select, 找到默认单位
	for(var j=0; j<convert.length; j++)
	{
		if(convert[j]["unit_default"]==1)
		{
			default_cv_id = convert[j]["cv_id"];
//			default_goods_code = convert[j]["goods_code"];
//			default_goods_base_price = convert[j]["goods_base_price"];
//			default_goods_last_base_price = convert[j]['last_base_price'];
			default_goods_stock = convert[j]["num"];
			
			optionHTML += "<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+" selected >"+convert[j]["goods_unit"]+"</option>";
		}
		else
		{
			optionHTML += "<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
		}
 	}
	
	// 拼接商品列表
	var goodsHTML = '';
	goodsHTML += "<tr class='tr_operate'>";
	goodsHTML += "<input type='hidden' class='goods_id' name='goods_id' value="+goods_id+">";
	goodsHTML += "<input type='hidden' class='cv_id' name='cv_id' value="+default_cv_id+">";
	goodsHTML += "<input type='hidden' class='goods_name' name='goods_name' value="+goods_name+">";
	goodsHTML += "<input type='hidden' class='goods_spec' name='goods_spec' value="+goods_spec+">";
	goodsHTML += "<td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+goods_code+"</td>";
	goodsHTML += "<td>"+goods_name+goods_spec+"</td><td><select class='w50 goods_unit_select'>"+optionHTML+"</select></td>";
	goodsHTML += "<td><input class='w50 tr goods_num' type='text' value='0'></td>";
	if(is_show_last_price == 1)
	{
//		goodsHTML += "<td><input class='w50 tr goods_price' type='text' style='color:#FF0000' value='"+default_goods_last_base_price+"'></td>";
//		goodsHTML += "<td><input class='w50 tr goods_price2' type='text' value='"+default_goods_base_price+"' readonly></td>";
	}
	else
	{
//		goodsHTML += "<td><input class='w50 tr goods_price' type='text' value='"+default_goods_base_price+"'></td>";
	}
	//goodsHTML += "<td class='tr tr_total'>0.00</td>";
	//goodsHTML += "<td><input class='w70 remark' type='text'></td>";
	if(is_show_stock == 1){ goodsHTML += "<td><input class='w50 tr num' type='text' value='"+default_goods_stock+"'></td>"; }
	goodsHTML += "</tr>";
	return goodsHTML;
}

// 筛选商品添加到单据（入库，出库，调拨，车申，预单，预单退货）商品列表中
function addGoodsIntoOrderGoodsList(depotID, checkStockURL, is_show_stock, is_show_last_price)
{
	// 商品表格html拼接
	var goodsHTML = "";

	// 获取所有选中的checkbox，也就是所有选中的商品	
	var checkbox = $("#goods_search tbody input[type='checkbox']:checked");
	
	// 循环每个商品，组合成tr，然后添加到单据商品列表中
	for(var i=0;i<checkbox.length;i++)
    {
		// 商品基本信息和单位信息
        var goods_id = checkbox.eq(i).val(); // 单位ID
		var goods_name = checkbox.eq(i).parent().siblings(".goods_name").val(); // 商品名称
		var goods_spec = checkbox.eq(i).parent().siblings(".goods_spec").val(); // 商品规格
		var convert = JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val()); // 商品大,中,小单位ID和名称，以及价格信息

		var goods_code=checkbox.eq(i).parent().siblings(".goods_code").val();
		
		// 拼接商品HTML
		goodsHTML += createGoodsHTML(goods_id, goods_code,goods_name, goods_spec, convert, is_show_stock, is_show_last_price);
    }
    $("#goods_table #goods_add_tr").before(goodsHTML);
	
	// 单位Select变化初始化事件
    goodsUnitChangeInit(is_show_stock, is_show_last_price);
	
	// 商品数量改变初始化事件
    blurGoodsNumChangeInit(depotID, checkStockURL);
	
	// 商品价格改变初始化事件
	blurGoodsPriceChangeInit();
	
	// 商品删除初始化事件
    goodsDelInit();
}

// 商品单位改变时，商品编码，商单价格，商品库存改变
function goodsUnitChangeInit(is_show_stock, is_show_last_price)
{
	$(".goods_unit_select").change(function(){
											
		// 获取商品单位数据，包括库存和价格，上次交易价格
		var aCv = JSON.parse($(this).find("option:selected").attr("attr"));
		
		// 商品删除按钮
		//$(this).parent().siblings(".sname").html("<a href='javascript:void(0)' class='goods_del'></a>");
		
		// 商品单位ID，价格和数量
		$(this).parent().parent().find(".cv_id").val(aCv["cv_id"]);
        $(this).parent().parent().find(".goods_num").val(0);

		// 库存数量变化
		if(is_show_stock == 1) { $(this).parent().parent().find(".num").val(aCv["num"]); }
		
		// 显示上次交易价格
		if(is_show_last_price == 1)
		{
			$(this).parent().parent().find(".goods_price").val(aCv['last_base_price']);
			$(this).parent().parent().find(".goods_price2").val(aCv["goods_base_price"]);
		}
		else
		{
			$(this).parent().parent().find(".goods_price").val(aCv['goods_base_price']);
		}

		// 计算商品价格
		calculateGoodsMoney();
		
		// 新加商品删除按钮事件初始化
		goodsDelInit();
    });
}

// 数量变化, 检查库存, 并计算商品总价格
// 检查接口：Home/PlanOrder/checkStock
// 接口返回0代表可以出库，1代表商品不足
function blurGoodsNumChangeInit(depotID, checkStockURL)
{
	$(".goods_num").blur(function() {
		
		
		// 商品数量录入格式检查
		if(!isNumber($(this).val())){ 
			var goods = $(this).parent().parent();
			
			//var aCv = JSON.parse(goods.find(".goods_unit_select").find("option:selected").attr("attr"));
			var aCv={};
			var goods_unit=goods.find(".goods_unit_select").find("option:selected").text();
			aCv.goods_unit=goods_unit;
			
			var val=parseFloat($(this).val());
			if(isNaN(val)){
				$(this).val(0); return; 
			}else{
				$(this).val(val);
			}
			
			if(!(aCv.goods_unit=="斤"||aCv.goods_unit=="公斤"||aCv.goods_unit=="千克")){
				$(this).val(0); return; 
			}
		}
		
		// url为空则不检查库存, 直接计算产品总价
		if(depotID == 0 || checkStockURL == '') { calculateGoodsMoney(); return; }
		
		// 仓库和商品数量
		var depot_out = depotID;
		var goods_num = $(this).val();
		
		// 商品ID和单位ID
		var goods = $(this).parent().parent();
		var goods_id = goods.find(".goods_id").val();
		var cv_id = goods.find(".cv_id").val();
		
		// AJAX请求验证库存情况
		var th = $(this);
		var data = {"repertory_id":depot_out,"goods_id":goods_id,"pageNum":goods_num,"cv_id":cv_id};
		$.post(checkStockURL,data,function(isNotFull) {
			if(isNotFull==1){ alert("库存不足"); th.val(0); calculateGoodsMoney(); } 
		});
		
		// 重新计算商品价格
		calculateGoodsMoney();
   });
}

// 判断商品价格格式,元素，数值
function blurGoodsPriceChangeInit()
{
	$(".goods_price").blur(function(){
		if(!isfloat($(this).val())) $(this).val("0.00");
        else $(this).val(parseFloat($(this).val()).toFixed(2));
        calculateGoodsMoney();
    });
}

// 删除商品操作, 附带计算商品价格
function goodsDelInit()
{
	$(".goods_del").click(function(){
		$(this).parent().parent().remove();
		calculateGoodsMoney();
	});
}

// 商品价格跟数量发生改变时候，商品添加和删除时候，自动计算小计和产品总价
function calculateGoodsMoney()
{
	// 单据商品列表
	var goods = $("#goods_table tbody tr.tr_operate");
	
	// 商品总数量
    var num_total = 0;
	
	// 商品总价格
    var price_total = 0.00;
	
	// 循环商品列表, 计算商品小计，总数量，总价格
	for(var i=0; i<goods.length; i++)
	{
		var tr_num = parseInt(goods.eq(i).find(".goods_num").val());
        var tr_price = parseFloat(goods.eq(i).find(".goods_price").val()).toFixed(2);
        var tr_total = tr_num * tr_price;
		num_total = num_total + tr_num;
        price_total = price_total + tr_total;
		goods.eq(i).find(".tr_total").text(tr_total.toFixed(2));
    }
	
	// 改变商品总数量和总价格
	$("#num_total").text(num_total);
	$("#price_total").text(price_total.toFixed(2));
	
	// 添加预单到货时总金额赋值
    $("#total_moneys").val(price_total.toFixed(2));
	
	// 已收金额
    var real_money = $("#real_money").val();
	
	// 计算应收金额，赋值
    var real_back_money = price_total.toFixed(2) - real_money;
    $("#real_back_money").val(real_back_money.toFixed(2));
}

function checkGoodsNumByUnit(num, unit) {

	var dUnit = ['千克','斤','公斤']

	if (isNaN(num)) {
		num = 0
	}

	var quantity = 0

	if ($.inArray(unit, dUnit) > 0) {
		quantity = parseFloat(num).toFixed(2)
		console.log(quantity)
	} else {
		quantity = parseInt(num)
	}

	if (quantity <= 0) {
		quantity = 0
	}

	return quantity*1

}
