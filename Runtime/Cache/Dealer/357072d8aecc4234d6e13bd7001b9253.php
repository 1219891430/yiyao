<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>添加车存退库</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>入库仓库：</td><td class="tl"><select name="depot_id" id="depot_id" class="w200 form-control">
                    <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                </td>
                    <td class="tr">单据编号：</td><td><span class="f16 fb"><?php echo ($code); ?></span></td>
                </tr>
                <tr>
                    <td>业务员：</td>
                    <td>
                        <select id="apage_staff_id" name="staff_id" class="w200 form-control">
                            <option value="0">选择业务员</option>
                            <?php if(is_array($aStaff)): $i = 0; $__LIST__ = $aStaff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$avo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($avo["staff_id"]); ?>"><?php echo ($avo["staff_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                    <td class="tr"></td>
                    <td>
                    </td>
                </tr>
                </tbody>
                <tfoot></tfoot>
            </table>
            <table class="table list_table" id="goods_table">
                <thead>
                <tr>
                    <td width="20%">商品条码</td>
                    <td width="25%">商品名称</td>
                    <td width="12%">单位</td>
                    <td width="12%">数量</td>
                    <td width="12%">退库数量</td>
                    <td width="20%">备注</td>
                </tr>
                </thead>
                <tbody>
                <tr><td colspan='6'>&nbsp</td>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">商品备注：<input id="return_goods_remark" type="text" class="w300"></td>
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
    <script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
    <script type="text/javascript">
        $("#apage_staff_id").change(function(){
            var data={staff_id:$(this).val()}
            CarReturnGoods("<?php echo U('Dealer/CarsaleStock/getCarportGoods');?>",data)
        })
        $("#create_form").click(function(){
            $("#submit_form").submit();
        })
        $("#submit_form").validate({
            submitHandler:function(){
                applyAdd()
            },
            rules:{
                depot_id:{
                    valNeqZero:true
                },
                staff_id:{
                    valNeqZero:true
                }
            },
            messages:{
                depot_id:{
                    valNeqZero:"请选择仓库"
                },
                staff_id:{
                    valNeqZero:"请选择业务员"
                }
            }
        })
        function applyAdd(){
            //if(!checkReturnGoodsNeqZero())
            if(!true)
            {
                return false;
            }
            else
            {
                var aGoodsData=returnTransferArr("#goods_table");
                var data={staff_id:$("#apage_staff_id").val(),depot_id:$("#depot_id").val(),goods_info:aGoodsData,remark:$("#return_remark").val(),goods_remark:$("#return_goods_remark").val()}
               
                
                ajaxDataAUD("/index.php/Dealer/CarsaleBack/doAdd",data,true)
            }
        }

    </script>
</div>