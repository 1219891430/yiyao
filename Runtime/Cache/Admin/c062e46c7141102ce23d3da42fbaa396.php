<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <form action="<?php echo U('Admin/Shops/edit');?>" method="post" enctype="multipart/form-data"  id="submit_form">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title fb f16">编辑终端店</h4>
        </div>
        <div class="modal-body">
            <div class="modal_850" id="customer_add_div">
                <table class="table no_border">
                    <thead></thead>
                    <tbody>
                    <tr>
                        <input type="hidden" name="cust_id" value="<?php echo ($data["cust_id"]); ?>">
                        <td class="title-error">名称：</td><td><input name="cust_name" id="cust_name" value="<?php echo ($data["cust_name"]); ?>"  type="text" class="unit_number form-control w200"></td>
                        <td class="title-error" align="right">联系人：</td><td><input name="contact" id="contact" value="<?php echo ($data["contact"]); ?>" type="text" class="unit_name form-control w200">
                    </td>
                    </tr>
                    <tr>
                        <td class="title-error" align="right">所属仓库：</td><td><select name="repertory_id" id="repertory_id" class="form-control w200">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["repertory_id"]); ?>" <?php if(($vo["repertory_id"]) == $data["repertory_id"]): ?>selected<?php endif; ?> style="text-indent: 1em"><?php echo ($vo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select></td>
                        <td class="title-error" align="right">电话：</td><td><input name="telephone" id="telephone" value="<?php echo ($data["telephone"]); ?>" type="text" class="unit_name form-control w200"></td>
                    </tr>

                    <tr>
                        <td class="title-error" align="right">所在地：</td><td colspan="3">
                        <select name="province" id="province" class="form-control w130"></select><select id="city" name="city" class="form-control w130"></select><select name="district" id="district" class="form-control w130"></select>
                    </td>
                    </tr>
                    <tr>
                        <td class="title-error" align="right">经纬度：</td><td colspan="3">
                        <input type="text" name="jwd" readonly="readonly" value="<?php echo ($data["longitude"]); ?>,<?php echo ($data["dimension"]); ?>" class="w400 form-control jwd_val"/>&nbsp;&nbsp;&nbsp;&nbsp;<a id="jwd" href="javascript:void(0)" style="text-decoration: underline">点击获取经纬度</a>
                    </td>
                    </tr>
                    <tr>
                        <td>地址：</td><td colspan="3">
                        <input name="address" id="address" type="text" value="<?php echo ($data["address"]); ?>" class="unit_name form-control w400">
                    </td>
                    </tr>
                    <tr>
                        </td>
                        <td class="title-error">是否关闭：</td>
                        <td><select name="is_close" id="is_close" class="form-control w200">
                            <option value="1" <?php if(($$data["is_close"]) == "1"): ?>selected="selected"<?php endif; ?>>是</option>
                            <option value="0" <?php if(($data["is_close"]) == "0"): ?>selected="selected"<?php endif; ?>>不是</option>
                        </select>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="close_customer" class="btn btn-default" data-dismiss="modal">关闭</a>
            <a id="submit_unit" class="btn btn-primary">保存</a>
        </div>
    </form>
</div>
<script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
<script type="text/javascript">
    new PCAS("province","city","district","<?php echo ($data['province']); ?>","<?php echo ($data['city']); ?>","<?php echo ($data['district']); ?>");

    $("#submit_form").validate({
        rules:{
            cust_name:{
                required:true,
                maxlength:30
            },
            contact:{
                required:true,
                maxlength:30
            },
            repertory_id:{
                valNeqZero:true
            },
            telephone:{
                minlength:6,
                maxlength:12,
                number:true,
                required:true
            },
            province:{
                required:true
            },
            city:{
                required:true
            },
            jwd:{
                required:true
            },
            address:{

            }
        },
        messages: {
            cust_name: {
                required: "终端店名称不能为空",
                maxlength: "终端店名称最大为15位"
            },
            contact: {
                required: "老板名称不能为空",
                maxlength: "老板名称最大为15位"
            },
            repertory_id: {
                valNeqZero: "请选择仓库"
            },
            telephone: {
                minlength: "请填写正确电话",
                maxlength: "请填写正确电话",
                number: "请填写正确电话",
                required: "电话不能为空"
            },
            province: {
                required: "请选择省份"
            },
            city: {
                required: "请选择市"
            },
            jwd: {
                required: "经纬度不能为空"
            },
            address: {
                required: "请填写地址"
            }
        }
    });

    $("#jwd").click(function(){
        mapObj.clearMap();
        $(".jwd_map").show();
        $("#submit_unit,#close_customer").attr("disabled","disabled");

        var arr=$(".jwd_val").val().split(",");
        addMarker(arr[0],arr[1]);
        $("#lngX").val(arr[0]);
        $("#latY").val(arr[1]);

    });
    $("#submit_unit").click(function(){
        if ($('#submit_form').valid()) {
            $('#submit_form').submit();
        }
    });
</script>