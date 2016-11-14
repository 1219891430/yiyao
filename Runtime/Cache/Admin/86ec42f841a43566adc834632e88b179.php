<?php if (!defined('THINK_PATH')) exit();?><div class="modal-dialog modal_650 ">
    <div class="modal-content modal_650">
        <div class="modal-header">
            <button type="button" class="close"
                    data-dismiss="modal" aria-hidden="true">
                &times;
            </button>
            <h4 class="modal-title fb f16">
                设置采购员
            </h4>
        </div>
        <form action="/index.php/Admin/PurchaseOrder/set_staff" id="submit_form" method="post">
            <div class="modal-body modal_650">
                <input name="id" value="<?php echo ($order['order_id']); ?>" type="hidden">
                <table class="table no_border">
                    <thead></thead>
                    <tbody>
                    <tr>
                        <td>
                            来源业务员：
                            <select class="form-control w200" name="staff_id" id="staff_id">
                                <option value="">请选择业务员</option>
                                <?php if(is_array($staff_list)): $i = 0; $__LIST__ = $staff_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$staff): $mod = ($i % 2 );++$i; if($staff['staff_id'] == $order['staff_id']): ?><option value="<?php echo ($staff["staff_id"]); ?>" selected="selected" ><?php echo ($staff["staff_name"]); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo ($staff["staff_id"]); ?>"><?php echo ($staff["staff_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                </tr>

</tbody>
                    </table>


            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-default"
                   data-dismiss="modal">关闭
                </a>
                <a id="submit_unit" class="btn btn-primary">
                    设置
                </a>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
    <script type="text/javascript">
        $("#submit_unit").click(function(){

            $("#submit_form").submit();
        });

        $("#submit_form").validate({
            rules:{
                staff_id:{
                    required: true
                }
            },
            messages:{
                staff_id: {
                    required: "必须选择采购人员"
                }
            }
        });

    </script>

</div>