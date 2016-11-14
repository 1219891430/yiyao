<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <form action="<?php echo U('Admin/Area/edit');?>" method="post" enctype="multipart/form-data"  id="submit_form">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title fb f16">编辑区域</h4>
        </div>
        <div class="modal-body">
            <div class="modal_850" id="customer_add_div">
                <table class="table no_border">
                    <thead></thead>
                    <tbody>
                    <tr><input type="hidden" name="area_id" value="<?php echo ($data["area_id"]); ?>">
                        <td width="100">所属仓库：</td><td colspan="3"><select name="depot_id" id="depot_id" class="form-control w200">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["repertory_id"]); ?>" <?php if(($vo["repertory_id"]) == $data["depot_id"]): ?>selected<?php endif; ?> style="text-indent: 1em"><?php echo ($vo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select></td>

                    </tr>
                    <tr>
                        <td >名称：</td><td colspan="3">
                        <input name="area_name" id="area_name" value="<?php echo ($data["area_name"]); ?>" type="text" class="unit_name form-control w400">
                    </td>
                    <tr>
                        <td >编号：</td><td colspan="3">
                        <input name="area_code" id="area_code" value="<?php echo ($data["area_code"]); ?>" type="text" class="unit_name form-control w400">
                    </td>
                    <tr>
                        <td >备注：</td><td colspan="3">
                        <input name="area_note" id="area_note" value="<?php echo ($data["area_note"]); ?>" type="text" class="unit_name form-control w400">
                    </td>
                    </tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="close_customer" class="btn btn-default" data-dismiss="modal">关闭</a>
            <a id="submit_unit" class="btn btn-primary">修改</a>
        </div>
    </form>
</div>
<script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>

<script type="text/javascript">

    $("#submit_form").validate({
        rules:{
            area_name:{
                required:true,
                maxlength:30
            },
            area_note:{
                maxlength:200
            },
            depot_id:{
                valNeqZero:true,
            },
            area_code:{
                required:true,
                maxlength:30
            }
        },
        messages: {
            area_name: {
                required: "名称不能为空",
                maxlength: "名称最大为15位"
            },
            area_note: {
                maxlength: "备注最大为100位"
            },
            depot_id: {
                valNeqZero: "请选择仓库"
            },
            area_code: {
                required: "编号不能为空"
            }
        }
    });

    $("#submit_unit").click(function(){
        if ($('#submit_form').valid()) {
            $('#submit_form').submit();
        }
    });
</script>