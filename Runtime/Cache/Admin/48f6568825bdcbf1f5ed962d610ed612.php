<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>审核入库单</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td width="80px">入库仓库：</td><td class="tl" colspan="3">
                        	<input type="hidden" id="dpeot_in_id" value="<?php echo ($depot_in_id); ?>" />
                        <select name="depot_id" id="depot_id" class="w200 form-control" disabled="disabled">
                            
                            <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i; if($res["depot_id"]==$dvo["repertory_id"]){ ?>
                            	<option value="<?php echo ($dvo["repertory_id"]); ?>" selected="selected"><?php echo ($dvo["repertory_name"]); ?></option>	
                            	<?php }else{ ?>
                                <option value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option>
                                <?php } endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select class="w200 form-control" name="in_types" id="in_types" disabled="disabled">
                        	
                        <option value="1" <?php if($res["in_type"]==1){ echo "selected='selected'";} ?>>经销商入库</option>                 
                        <option value="2" <?php if($res["in_type"]==2){ echo "selected='selected'";} ?>>经销商退库</option>         
                        <option value="3" <?php if($res["in_type"]==3){ echo "selected='selected'";} ?>>配送退库</option>       
                        <option value="4" <?php if($res["in_type"]==4){ echo "selected='selected'";} ?>>盘盈入库</option>
                        
                        </select>
                       
                        </td>
                      </tr>
                      <tr>
                      	<td width="80px">经销商：</td><td class="tl" colspan="3">
                        <select name="org_id" id="org_id" class="w200 form-control" disabled="disabled">
                            <?php if(is_array($org_list)): $i = 0; $__LIST__ = $org_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i; if($res["org_parent_id"]==$dvo["org_id"]){ ?>
                            		<option value="<?php echo ($dvo["org_id"]); ?>" selected="selected"><?php echo ($dvo["org_name"]); ?></option>
                            		
                            	<?php }else{ ?>
                            		<option value="<?php echo ($dvo["org_id"]); ?>"><?php echo ($dvo["org_name"]); ?></option>
                            	<?php } endforeach; endif; else: echo "" ;endif; ?>
                           
                        </select>
                      </tr>
                    <tr>
                        <td>入库备注：</td>
                        <td colspan="3"><input class="w300 form-control" value="<?php echo ($res["in_remark"]); ?>" id="in_remark"></td>
                    </tr>
                </tbody>
                <tfoot></tfoot>
            </table>
            <table class="table list_table" id="goods_table">
                <thead>
                    <tr>
                        <td width="20%">商品条码</td>
                        <td width="20%">商品名称</td>
                        <td width="20%">单位</td>
                        <td width="20%">数量</td>
                        <td width="20%">商品区域</td>
                        <!--<td width="10%">金额(￥)</td>
                        <td width="12%">备注</td>-->
                    </tr>
                </thead>
                <tbody>
                	
            <?php if(is_array($resGoods)): $i = 0; $__LIST__ = $resGoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr class='tr_operate'>
                    <input type='hidden' class='goods_id' name='goods_id' value="<?php echo ($gvo["goods_id"]); ?>"><input type='hidden' class='cv_id' name='cv_id' value="<?php echo ($gvo["cv_id"]); ?>">
                    <td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a><?php echo ($gvo["goods_code"]); ?></td>
                    <td><?php echo ($gvo["goods_name"]); ?>/<?php echo ($gvo["goods_spec"]); ?></td>
                    <td>
                    <select class='w50 goods_unit_select' disabled="disabled">
                        <?php $gunit=$gvo["goods_unit"]; ?>
                        <?php if(is_array($gunit)): $i = 0; $__LIST__ = $gunit;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$uvo): $mod = ($i % 2 );++$i;?><option attr='<?php echo ($uvo["json"]); ?>' <?php if($uvo['cv_id'] == $gvo['cv_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($uvo["cv_id"]); ?>"><?php echo ($uvo["goods_unit"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    
                    </td>
                    <td><input class='w50 tr goods_num' type='text' disabled="disabled" value='<?php echo (getGoodsNum($gvo["in_num"])); ?>'></td>
                    <td><?php echo ($gvo["area_name"]); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>	
                	
                <tr id="goods_add_tr">
                    <td></td>
                    <td></td>
                    <td style="padding:0">
                      <!--<span class="fb f24 cursor-pointer pull-right mr20" id="goods_add">+</span>-->
                    </td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr><td colspan='4'>&nbsp</td><td></td></tr>
                </tbody>
                <tfoot>
                
                </tfoot>
            </table>
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td width="80px">制单人：</td><td class="tl"><?php echo ($create_name); ?></td>
                    <td class="tr">时间：</td><td><span><?php echo ($res["create_time"]); ?></span></td>
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
            <span>审核</span>
        </a>
    </div>
    <div class="goods_div" id="goods_div">
        <h3>选择商品</h3>
        <div class="mt20">
          <select id="brand" class="w150 form-control">
              <option value="0">全部品牌</option>
              <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["brand_id"]); ?>"><?php echo ($vo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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
    <script type="text/javascript" src="/yiyao/Public/assets/js/validate_form.js"></script>
   
    
    
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
                
               
                ajaxDataAUD("/yiyao/index.php/Admin/DepotIn/inpassEx",data,true)
            }
        }
    
</script>



</div>