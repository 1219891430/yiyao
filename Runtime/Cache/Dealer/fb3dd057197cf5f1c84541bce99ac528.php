<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_650">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>设置活动价格</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <div class="modal-body modal_650">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                	
                <tr>
                    <td>商品进价：</td><td class="tl">
                    <input type="hidden" id="cv_id" value="<?php echo ($res["cv_id"]); ?>"/>
                    <input name="goods_jin_price" id="goods_jin_price" value="<?php echo ($res["goods_jin_price"]); ?>" class="w200 form-control" />
                    </td>
                </tr>
                
                
                <tr>
                    <td>商品售价：</td><td class="tl">
                    <input name="goods_base_price" id="goods_base_price" value="<?php echo ($res["goods_base_price"]); ?>" class="w200 form-control" />
                    </td>
                </tr>
                
                
                
                
                
                </tbody>
                
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
            <span>确定</span>
        </a>
    </div>
    <script type="text/javascript" src="/yiyao/Public/assets/js/validate_form.js"></script>
    <script type="text/javascript">
    
        
        $("#create_form").click(function(){
            $("#submit_form").submit();
        })
        $("#submit_form").validate({
            submitHandler:function(){
                applyAdd()
            },
            rules:{
            	goods_base_price:{
                    valNeqZero:true,
                    number:true
                },
                goods_jin_price:{
                    valNeqZero:true,
                    number:true
                }
            },
            messages:{
            	goods_base_price:{
                    valNeqZero:"请填写进价",
                    number:"请输入数字"
                },
                goods_jin_price:{
                    valNeqZero:"请填写售价",
                    number:"请输入数字"
                }
            }
        })
        function applyAdd(){
        	var cv_id=$("#cv_id").val();
            var goods_jin_price=$("#goods_jin_price").val();
            var goods_base_price=$("#goods_base_price").val();
             if(goods_jin_price<0){
             	alert("进价不能为负数");
             	return;
             }
             if(goods_base_price<0){
             	alert("售价不能为负数");
             	return;
             }
            
            
            
            var data={
            	cv_id:cv_id,
            	jin_price:goods_jin_price,
            	base_price:goods_base_price
            };
               
                
            ajaxDataAUD("/yiyao/index.php/Dealer/GoodsPrices/setPriceEx",data,true);
            
        }

    </script>
</div>