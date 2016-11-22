<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
<div class="modal-header">
    <button type="button" class="close"
            data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title fb f16">
        <span>添加预售订单</span>
    </h4>
</div>
<form action="" id="submit_form" method="post">
    <div class="modal-body modal_850">
        <table class="table no_border">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td>出库仓库：</td><td class="tl"><select name="depot_id" id="depot_id" class="w200 form-control">
                <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
                <ul id="data_sel_ul" class="data_sel_ul">
                </ul>
            </td>
                <td class="tr">单据编号：</td><td><span class="f16 fb"><?php echo ($code); ?></span><input type="hidden" id="code" value="<?php echo ($code); ?>"></td>
            </tr>
            <tr>
                <td>业务员</td>
                <td>
                    <select id="apage_staff_id" name="staff_id" class="w200 form-control">
                        <option value="0">选择业务员</option>
                      <?php if(is_array($aStaff)): $i = 0; $__LIST__ = $aStaff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$avo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($avo["staff_id"]); ?>"><?php echo ($avo["staff_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                <td class="tr">经销商</td>
                <td>
                	<select id="org_id" name="org_id" class="w200 form-control">
                       
                      <?php if(is_array($orglist)): $i = 0; $__LIST__ = $orglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>购货方</td>
                <td>
                    <select id="apage_staff_id" name="staff_id" class="w200 form-control">
                        <option value="0">选择业务员</option>
                      <?php if(is_array($aStaff)): $i = 0; $__LIST__ = $aStaff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$avo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($avo["staff_id"]); ?>"><?php echo ($avo["staff_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                <td class="tr">结算方式</td>
                <td>
                	<select id="org_id" name="org_id" class="w200 form-control">
                       
                      <?php if(is_array($orglist)): $i = 0; $__LIST__ = $orglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>备注：</td>
                <td colspan="3"><input class="w300 form-control" id="apply_remark"></td>
            </tr>
            </tbody>
            <tfoot></tfoot>
        </table>
        <table class="table list_table" id="goods_table">
            <thead>
            <tr>
                <td width="20%">商品条码</td>
                <td width="25%">商品名称</td>
                <td width="7%">单位</td>
                <td width="7%">数量</td>
                <td width="7%">单价</td>
                <td width="10%">金额(￥)</td>
                <td width="12%">备注</td>
                <td width="12%">当前库存</td>
            </tr>
            </thead>
            <tbody>
            <tr id="goods_add_tr">
                <td></td>
                <td style="padding:0">
                    <span class="fb f24 cursor-pointer pull-right mr20" id="goods_add">+</span>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr><td colspan='4'>&nbsp;</td><td class="tr" id='num_total'></td><td class="tr" id='price_total'></td><td></td><td></td></tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="8">商品备注：<input id="apply_goods_remark" type="text" class="w300"></td>
            </tr>
            </tfoot>
        </table>
        <table class="table no_border">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td width="80px">制单人：</td><td class="tl"><?php echo ($staff_name); ?></td>
                <td class="tr">时间：</td><td><span><?php echo ($time); ?></span></td>
            </tr>

            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
    <div class="error">
    </div>
    <input type="hidden" name="supp_id" id="sid" value="">
</form>
<div class="modal-footer">
    <a href="#" class="btn btn-default"
       data-dismiss="modal">关闭
    </a>
    <a id="create_form" class="btn btn-primary">
        <span>创建</span>
    </a>
</div>
<div class="goods_div" id="goods_div">
    <h3>选择商品</h3>
    <div class="mt20">
        <select id="brand" class="w150 form-control">
            <!--<option value="0">全部品牌</option>
            <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["brand_id"]); ?>"><?php echo ($vo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>-->
        </select>
        &nbsp;<input id="goods" placeholder="请输入商品名称" type="text" class="form-control w200">
        <input type="button" id="find_goods" class="btn btn-primary" value="搜索"/>
    </div>
    <div class="goods_body">
        <table class="table list_table mt10 goods_search" id="goods_search">
            <thead>
            <tr><td class="tc" width="50px"><input id="choice_all" class="check_mt0" type="checkbox"></td><td>商品名称</td></tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="goods_operate">
        <input type="button" id="goods-add" class="btn btn-primary" value="添加">
        <input type="button" id="goods-close" class="btn btn-default" value="关闭">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
<script type="text/javascript">


	$("#org_id").change(function(){
    	
    	getOrgBrand();
    });
    
    getOrgBrand();
    function getOrgBrand(){
    	
    	var org_id=$("#org_id").val();
    	
    	var data={org_id:org_id};
    	
    	
    	
    	$.post("<?php echo U('Admin/DepotIn/getBrandByOrg');?>",data,function(res){
    		
    		if(res.code==1){
    			
    			var brandlist= res.list;
    			var htmlString="<option value='0'>全部品牌</option>";
    			for(var i=0;i<brandlist.length;i++){
    				
    				htmlString+="<option value='"+brandlist[i]["brand_id"]+"'>"+brandlist[i]["brand_name"]+"</option>"
    			}
    			$("#brand").html(htmlString);
    			
    		}else{
    			var htmlString="<option value='0'>全部品牌</option>";
    			$("#brand").html(htmlString);
    		}
    		
    	},"json");
    }


    $("#goods_add").click(function(){

		var a_depot_out = <?php echo ($_SESSION["depot_id"]); ?>;
		if(a_depot_out == 0){ alert("请选择仓库");return false; }
		
		// 显示库存
		setGoodsDataInit($("#depot_id").val(), "<?php echo U('Dealer/CarsaleApply/checkStock');?>", 1, 0);
    });

    $("#find_goods").click(function(){
        if($("#goods").val()!="") queryGoodsListOrg("<?php echo U('Admin/PresaleOrder/selGoodsAndStock');?>", $("#brand").val(), $("#goods").val(), $("#depot_id").val(),0,0,$("#org_id").val());
        else alert("请填写商品");
    });
    
    // 商品下拉选择后，列出所有的商品信息
	$("#brand").change(function(){
		
		if($("#brand").val()==0 && $("#goods").val()==""){
			$("#goods_search").find("tbody").empty();
        }else{
			$("#goods").val('');
			alert($("#org_id").val());
        	queryGoodsListOrg("<?php echo U('Admin/PresaleOrder/selGoodsAndStock');?>", $("#brand").val(), $("#goods").val(), $("#depot_id").val(),0,0,$("#org_id").val());
        }
	});

    $("#create_form").click(function(){
        $("#submit_form").submit();
    })
    $("#submit_form").validate({
        submitHandler:function(){
            applyAdd()
        },
        rules:{
            staff_id:{
                valNeqZero:true
            }
        },
        messages:{
            staff_id:{
                valNeqZero:"请选择业务员"
            }
        }
    })
    function applyAdd(){
        if($("#goods_table .tr_operate").length==0)
            alert("请添加商品")
//            else if($("#sid").val()==0||$("#sid").val()=="")
//                alert("未查询到供应商信息,请先搜索选择供应商");
        else if(!checkGoodsNeqZero())
        {
            return false;
        }
        else
        {
            var aGoodsData=goodsTransferArr1("#goods_table");
            var data={code:$("#code").val(),staff_id:$("#apage_staff_id").val(),depot_id:$("#depot_id").val(),goods_info:aGoodsData,remark:$("#apply_remark").val()}
            
            ajaxDataAUD("/index.php/Admin/PresaleOrder/addex",data,true)
        }
    }
</script>
</div>