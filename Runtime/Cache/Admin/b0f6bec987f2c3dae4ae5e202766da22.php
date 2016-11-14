<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>区域商品列表</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                    
                    <!--<tr>
                      	<td width="80px">经销商：</td><td class="tl" colspan="3">
                        <select name="org_id" id="org_id" class="w200 form-control" disabled="disabled">
                            
                            <?php if(is_array($org_list)): $i = 0; $__LIST__ = $org_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($dvo["org_id"]); ?>"><?php echo ($dvo["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                      </tr>
                    <tr>
                        <td>入库备注：</td>
                        <td colspan="3"><input class="w300 form-control" value="<?php echo ($res["in_remark"]); ?>" id="in_remark"></td>
                    </tr>-->
                </tbody>
                
            </table>
            <table class="table list_table" id="goods_table">
                <thead>
                    <tr>
                        <td width="25%">商品条码</td>
                        <td width="25%">商品名称</td>
                        <!--<td width="25%">操作</td>-->
                        
                    </tr>
                </thead>
                <tbody>
                	
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr class='tr_operate'>
                    <td><?php echo ($gvo["goods_code"]); ?></td>
                    <td><?php echo ($gvo["goods_name"]); ?>/<?php echo ($gvo["goods_spec"]); ?></td>
                    <!--<td>
                   
                        
                    </td>-->
                    
                 
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>	
                	
                
                <tfoot>
                
                </tfoot>
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
        
    </div>
    
    <script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
   
    
    
    <script type="text/javascript">
    
	
	editGoodsListDataInit(0, '', 0, 0);
	
	
	$("#find_goods").click(function(){
		if($("#goods").val()!="")
			queryGoodsList("<?php echo U('Admin/GoodsInfo/selGoods');?>", $("#brand").val(), $("#goods").val(), 0);
		else
			alert("请填写商品");
	});

	// 商品下拉选择后，列出所有的商品信息
	$("#brand").change(function(){
		if($("#brand").val()==0 && $("#goods").val()==""){
			$("#goods_search").find("tbody").empty();
		}else{
			queryGoodsList("<?php echo U('Admin/GoodsInfo/selGoods');?>", $("#brand").val(), '', 0);
		}
	});
	

	$("#goods_add").click(function(){ setGoodsDataInit(0, '', 0, 0); });

	$("#create_form").click(function(){
        	
        	
            $("#submit_form").submit();
        })
        $("#submit_form").validate({
            submitHandler:function(){
                depotIn()
            },
            rules:{
                depot_id:{
                    valNeqZero:true
                }
            },
            messages:{
                depot_id:{
                    valNeqZero:"请选择仓库"
                }
            }
        })
        function depotIn(){
            if($("#goods_table .tr_operate").length==0)
                alert("请添加商品")

            else if(!checkGoodsNeqZero())
            {
                return false;
            }
            else
            {
            	
            	
                var aGoodsData=goodsTransferArr("#goods_table");
                
                var data={depot_in_id:<?php echo ($depot_in_id); ?>}
                
               
                ajaxDataAUD("/index.php/Admin/Area/InpassEx",data,true)
            }
        }
    
</script>



</div>