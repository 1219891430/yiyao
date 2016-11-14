<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850" style="margin-top:120px;">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title fb f16">编辑人员</h4>
</div>

<form action="<?php echo U('edit');?>" id="submit_form" method="post">
    <div class="modal-body modal_850">
    <table class="table no_border">
    <thead></thead>
    <tbody>
    
    <tr>
    <td align="right">人员姓名：</td>
    <td><input name="true_name" id="true_name" type="text" class="unit_name form-control w200" value="<?php echo ($userInfo["true_name"]); ?>" /></td>
    <td align="right">登录手机：</td>
    <td><input name="mobile" id="mobile" type="text" class="unit_name form-control w200" value="<?php echo ($userInfo["login_account"]); ?>" /></td>
    </tr>
    <tr>
    
    <tr>
    <td align="right">年龄：</td>
    <td><input name="age" id="age" type="text" class="form-control w200" value="<?php echo ($userInfo["age"]); ?>" /></td>
    <td align="right">性别：</td>
    <td>
    <select name="sex" id="sex" class="form-control w200">
    <option value="">请选择性别</option>
    <option value="1" <?php if($userInfo["sex"] == 1): ?>selected="selected"<?php endif; ?> >男</option>
    <option value="2" <?php if($userInfo["sex"] == 2): ?>selected="selected"<?php endif; ?> >女</option>
    </select>
    </td>
    </tr>

    <tr>
    <td  align="right">所属角色：</td>
    <td><select name="role_id" id="role_id" class="form-control w200">
    <option value="">请选择角色</option>
    <?php if(is_array($role_list)): $roleID = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$roleName): $mod = ($roleID % 2 );++$roleID; if($userInfo["role_id"] == $roleID): ?><option value="<?php echo ($roleID); ?>" selected="selected"><?php echo ($roleName); ?></option>
    <?php else: ?>
    <option value="<?php echo ($roleID); ?>"><?php echo ($roleName); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </select>
    </td>
    <td align="right">所属仓库：</td>
    <td><select name="depot_id" id="depot_id" class="form-control w200">
    <option value="">请选择仓库</option>
    <?php if(is_array($depot_list)): $depotID = 0; $__LIST__ = $depot_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$depotName): $mod = ($depotID % 2 );++$depotID; if($userInfo["depot_id"] == $depotID): ?><option value="<?php echo ($depotID); ?>" selected="selected"><?php echo ($depotName); ?></option>
    <?php else: ?>
    <option value="<?php echo ($depotID); ?>"><?php echo ($depotName); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </select>
    </td>
    </tr>

    <tr>
    <td align="right">登录密码：</td>
    <td><input id="login_pwd" name="login_pwd" type="password" class="form-control w200" /></td>
    <td align="right">密码确认：</td>
    <td><input id="login_pwd2" name="login_pwd2" type="password" class="form-control w200" /></td>
    </tr>
    
    </tbody>
    <tfoot></tfoot>
    </table>
    </div>
    <div class="error"></div>

    <div class="modal-footer">
    	<input type="hidden" name="admin_id" value="<?php echo ($userInfo["admin_id"]); ?>" />
        <a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>
        <a id="submit_unit" class="btn btn-primary">保存</a>
    </div>
</form>
          
</div>

<script type="text/javascript">
$(function(){

$("#submit_form").validate({
	submitHandler:function(){ submit_add(); },
	rules: {
		true_name: "required",
		mobile: { required:true, digits:true, minlength:11, maxlength:11 },
		age: { required:true, digits:true },
		sex: { required:true ,digits:true },
		role_id: { required:true ,digits:true },
		depot_id: { required:true, digits:true }
	},
	messages: {
		true_name: "人员名称不能为空",
		mobile: {required:"手机不能为空", digits:"手机格式不对", minlength: "手机号为11位",maxlength: "手机号为11位"},
		age:{ required:"年龄不能为空", digits:"年龄格式不对" },
		sex:{ required:"请选择性别", digits:"请选择性别" },
		role_id:{ required:"请选择角色", digits:"请选择角色" },
		depot_id:{ required:"请选择仓库", digits:"请选择仓库" }
	}
});

function submit_add(){
	$.ajax({
	url: "<?php echo U('edit');?>",
	type: "post",
	data: $("#submit_form").serialize(),
	dataType: "json",
	success: function (data) {
		alert(data["info"]);
		if (data["res"] == 1) { location.reload(true); }
	}});
}

$("#submit_unit").click(function(){
    $("#submit_form").submit();
});

});
</script>