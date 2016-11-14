<?php if (!defined('THINK_PATH')) exit();?>
<div class="modal-dialog modal_650 ">
    <div class="modal-content modal_650">
        <div class="modal-header">
            <button type="button" class="close"
                    data-dismiss="modal" aria-hidden="true">
                &times;
            </button>
            <h4 class="modal-title fb f16">
                添加新的人员
            </h4>
        </div>
        <form action="" id="submit_form" method="post">
            <div class="modal-body modal_650">
                <table class="table no_border">
                    <thead></thead>
                    <tbody>
                    <tr>

                        <td class="title-error" align="right">人员姓名：</td>

                        <td>
                            <input name="staff_name" id="staff_name" value="<?php echo ($staff["staff_name"]); ?>" type="text" class="unit_name form-control w200"></td>
                        <td class="title-error" align="right">归属部门：</td>
                        <td><select name="dep_id" id="dep_id" class="form-control w200">
                            <option value="0">请选择归属部门</option>
                            <?php if(is_array($dep)): $i = 0; $__LIST__ = $dep;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i; if($staff['dep_id'] == $d['dep_id']): ?><option value="<?php echo ($d["dep_id"]); ?>" selected><?php echo ($d["dep_name"]); ?></option><?php endif; ?>
                                <option value="<?php echo ($d["dep_id"]); ?>"><?php echo ($d["dep_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="title-error" align="right">手机：</td>

                        <td><input maxlength="11" name="mobile"  value="<?php echo ($staff["mobile"]); ?>" id="mobile" type="text" class="form-control w200">
                        </td>

                        <td class="title-error">所属角色</td>
                        <td>
                            <select name="role_id" id="role_id" class="form-control w200">
                                <option value="0">请选择角色</option>
                                <option value="2" <?php if($staff['role_id'] == 2): ?>selected<?php endif; ?>>内勤</option>
                                <option value="3" <?php if($staff['role_id'] == 3): ?>selected<?php endif; ?>>业务员</option>
                            </select>
                        </td>

                    </tr>
                    <tr>
                        <td align="right">职务：</td>
                        <td>
                            <input name="job_post"  value="<?php echo ($staff["job_post"]); ?>" id="job_post" type="text" class="unit_name form-control w200">
                        </td>
                    </tr>
                    <tr>
                        <td align="right">email：</td>
                        <td><input name="email"  value="<?php echo ($staff["email"]); ?>" id="email" type="text" class="form-control w200"></td>
                        <td align="right">性别：</td>
                        <td>
                            <select name="gender" id="gender" class="form-control w200">
                                <option value="1" <?php if($staff['gender'] == 1): ?>selected<?php endif; ?>>男</option>
                                <option value="0" <?php if($staff['gender'] == 0): ?>selected<?php endif; ?>>女</option>
                            </select></td>
                    </tr>
                    <tr id="userTr">

                        <td class="title-error" align="right">用户名：</td>
                        <td><input id="login_user" disabled  readonly="readonly" value="<?php echo ($staff["login_user"]); ?>" name="login_user" type="text" class="form-control w200">
                        </td>
                    </tr>

                    <tr>
                        <td align="right">备注：</td>
                        <td><textarea name="remark" id="remark" class="form-control" style="resize: none;"
                                      rows="2"><?php echo ($staff["remark"]); ?></textarea></td>
                    </tr>

                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
            <div class="error">
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-default"
                   data-dismiss="modal">关闭
                </a>
                <a id="submit_unit" class="btn btn-primary">
                    修改
                </a>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $("#mobile").keyup(function(){
        $("#login_user").val($(this).val())

    })

    $("#submit_unit").click(function(){
        $("#submit_form").submit();
    })
    $("#submit_form").validate({
        submitHandler:function(){
            staffAdd()
        },
        rules:{
            staff_name:{
                required:true
            },
            mobile: {
                minlength: 11,
                maxlength: 11,
                required: true
            },
            login_user: {
                required: true
            },
            dep_id: {
                valNeqZero:true
            },
            role_id: {
                valNeqZero:true
            },
            email: {
                email: true
            }
        },
        messages:{
            staff_name: {
                required: "人员姓名不能为空",
            },
            mobile: {
                minlength: "手机号为11位",
                maxlength: "手机号为11位",
                required: "手机号不能为空",
            },
            login_user: {
                required: "登录名不能为空",
            },
            dep_id: {
                valNeqZero:"请选择部门"
            },
            email: {
                email: "email格式不对"
            },
            role_id: {
                valNeqZero:"请选择角色"
            }
        }
    })
    function staffAdd(){
        var data={
            id:<?php echo ($staff["staff_id"]); ?>,
            staff_name:$("#staff_name").val(),
            dep_id:$("#dep_id").val(),
            mobile:$("#mobile").val(),
            role_id:$("#role_id").val(),
            job_post:$("#job_post").val(),
            email:$("#email").val(),
            gender:$("#gender").val(),
            login_user:$("#login_user").val(),
            remark:$("#remark").val(),
        };
        ajaxDataAUD("/index.php/Dealer/Staff/edit",data,true)
    }
</script>