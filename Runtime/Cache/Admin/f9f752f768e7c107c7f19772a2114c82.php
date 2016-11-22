<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>添加入库单</span>
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
                        <select name="depot_id" id="depot_id" class="w200 form-control"> 
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i; if(empty($dvo["depot_list"])): ?><option value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option>
                            <?php else: ?>
                                <option value="<?php echo ($dvo["repertory_id"]); ?>" disabled="disabled"><?php echo ($dvo["repertory_name"]); ?></option><?php endif; ?>
                            <?php if(is_array($dvo["depot_list"])): $i = 0; $__LIST__ = $dvo["depot_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["repertory_id"]); ?>">  |----<?php echo ($v["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select class="w200 form-control" name="in_types" id="in_types">
                        <option value="1">经销商入库</option>                 
                        <option value="2">经销商退库</option>         
                        <option value="3">配送退库</option>       
                        <option value="4">盘盈入库</option>
                        </select>
                        </td>
                      </tr>
                      <tr>
                      	<td width="80px">经销商：</td><td class="tl" colspan="3">
                        <select name="org_id" id="org_id" class="w200 form-control">
                            
                            <?php if(is_array($org_list)): $i = 0; $__LIST__ = $org_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($dvo["org_id"]); ?>"><?php echo ($dvo["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                      </tr>
                    <tr>
                        <td>入库备注：</td>
                        <td colspan="3"><input class="w300 form-control" id="in_remark"></td>
                    </tr>
                    <tr>
                        <td>扫描调码：</td>
                        <td colspan="3"><input class="w300 form-control" id="in_goods_code"></td>
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
                <tr id="goods_add_tr">
                    <td></td>
                    <td></td>
                    <td style="padding:0">
                      <span class="fb f24 cursor-pointer pull-right mr20" id="goods_add">+</span>
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
                    <td width="80px">制单人：</td><td class="tl"><?php echo ($staff_name); ?></td>
                    <td class="tr">时间：</td><td><span><?php echo ($date); ?></span></td>
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
    			
    		}
    		$("#brand")
    	},"json");
    }
    
    $("#in_goods_code").change(function(){
    	var org_id=$("#org_id").val();
    	var goods_code=$(this).val();
    	var data={goods_code:goods_code,org_id:org_id};
    	
    	$.post("<?php echo U('Admin/GoodsInfo/selectGoodsByCode');?>",data,function(res){
    		if(res.code==1){
    			var isRe=false;
    			var goods_id=res.res.goods_id;
    			$("#goods_table tbody .goods_id").each(function(){
    				var goods_id_this=$(this).val();
    				if(goods_id_this==goods_id){
    					isRe=true;
    				}
    			});
    			if(isRe){
    				alert("重复商品");
    				$("#in_goods_code").val('');
    				return;
    			}
    			
    			var goods_code=res.res.goods_code;
    			var goods_name=res.res.goods_name;
    			var goods_spec=res.res.goods_spec;
    			var goods_area=res.res.goods_area;
    			var convert=res.goods;
                var goodsHTML=createGoodsHTML(goods_id, goods_code,goods_name, goods_spec,goods_area, convert, 0, 0);
                $("#goods_table #goods_add_tr").before(goodsHTML);
                $("#in_goods_code").val('');
                $("#org_id").attr("disabled",true);
                // 单位Select变化初始化事件
    			goodsUnitChangeInit(is_show_stock, is_show_last_price);
	
				// 商品数量改变初始化事件
   				blurGoodsNumChangeInit(depotID, checkStockURL);
	
				// 商品价格改变初始化事件
				blurGoodsPriceChangeInit();
	
				// 商品删除初始化事件
    			goodsDelInit();
    			
    			
    		}else{
    		   alert("无此商品编码");
    		}
    	},"json");
    	
    });
	 
	$("#goods_add").click(function(){
		//setGoodsPageData();
		setGoodsDataInit(0, '', 0, 0);
		
	});

	$("#find_goods").click(function(){
		var depot_id=$("#depot_id").val();
		var org_id=$("#org_id").val();
		
		if($("#goods").val()!="")
			queryGoodsList("<?php echo U('Admin/GoodsInfo/selGoods');?>", $("#brand").val(), $("#goods").val(),depot_id,0 ,0,org_id);
		else
			alert("请填写商品");
	});

	// 商品下拉选择后，列出所有的商品信息
	$("#brand").change(function(){
		if($("#brand").val()==0 && $("#goods").val()==""){
			$("#goods_search").find("tbody").empty();
		}else{
			var depot_id=$("#depot_id").val();
		    var org_id=$("#org_id").val();
			queryGoodsList("<?php echo U('Admin/GoodsInfo/selGoods');?>", $("#brand").val(), '',depot_id,0 ,0,org_id);
		}
	});
	
	
	
	
	
		
	
	
	
 
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
                
                var data={types:$("#in_types").val(),depot_id:$("#depot_id").val(),org_id:$("#org_id").val(),goods_info:aGoodsData,remark:$("#in_remark").val()}
               
                
                ajaxDataAUD("/index.php/Admin/DepotIn/addex",data,true)
            }
        }
        
        
        $('#in_types').change(function(){
        	
        	
        	if($(this).val() == 1){
        		
        		$('.jxs_info').show();
        		
        	}else{
        		
        		$('.jxs_info').hide();
        	}
        		
        });
    </script>
</div>