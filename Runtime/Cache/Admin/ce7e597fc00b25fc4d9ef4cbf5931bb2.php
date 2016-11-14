<?php if (!defined('THINK_PATH')) exit();?>
<link href="/yiyao/Public/assets/css/bootstrap-switch.css" rel="stylesheet">

<!-- 按钮 -->
<script type="text/javascript" src="/yiyao/Public/assets/js/bootstrap-switch.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/highlight.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/main.js"></script>
<!-- 按钮 -->

<script type="text/javascript" src="/yiyao/Public/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/yiyao/Public/assets/js/validate_form.js"></script>


<link href="/yiyao/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
<script type="text/javascript" src="/yiyao/Public/assets/js/manhuaDate.1.0.js"></script>


<body>

<div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>添加经销商</span>
        </h4>
    </div>
    <form action="<?php echo U('Admin/Dealer/edit');?>" method="post" enctype="multipart/form-data"  id="submit_form">
        <input type="hidden" id="org_id" name="org_id" value="<?php echo ($org_info['org_id']); ?>"/>
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>经销商名称：</td><td>
                    <input type="text" name="org_name" value="<?php echo ($org_info["org_name"]); ?>" maxlength="120"  class="w200 form-control">

                    <td>联系人：</td><td>
                    <input type="text" name="contacts" value="<?php echo ($org_info["contacts"]); ?>" maxlength="120"  class="w200 form-control">

                </td>
                </tr>
                <tr>
                    <td>手机：</td>
                    <td>
                        <input type="text" id="mobile" name="mobile" value="<?php echo ($org_info["mobile"]); ?>"    class="w200 form-control">
                    </td>
                    <td>固话：</td><td>
                    <input type="text" name="telephone"  value="<?php echo ($org_info["telephone"]); ?>"  class="w200 form-control">
                </td>
                </tr>

                <tr>
                    <td>邮编：</td>
                    <td>
                        <input type="text" name="zip_code" value="<?php echo ($org_info["zip_code"]); ?>" maxlength="12" class="w200 form-control">
                    </td>
                </tr>

                <tr>
                    <td>是否禁用：</td>
                    <td>

                        <input name="is_close" id="switch-onText" <?php if($org_info['is_close'] == 1): ?>checked="checked"<?php endif; ?> type="checkbox"  data-on-text="禁">

                    </td>
                </tr>

                <tr>

                    <td>老板数量：</td>
                    <td><input type="number" name="boss_num" value="<?php echo ($org_info["boss_num"]); ?>" class="w100 form-control"></td>

                    <td>业务员数量：</td>
                    <td><input type="number"  name="saleman_num" value="<?php echo ($org_info["saleman_num"]); ?>" class="w100 form-control"></td>
                </tr>

                <tr>

                    <td>内勤数量：</td>
                    <td>
                        <input type="number"  name="work_num" value="<?php echo ($org_info["work_num"]); ?>" class="w100 form-control">
                    </td>
                </tr>

                <tr>
                    <td>所在地：</td>
                    <td colspan="3">
                        <select name="province" id="province" class="form-control w130"></select>&nbsp;
                        <select name="city" id="city" class="form-control w130"></select>&nbsp;
                        <select name="district" id="district" class="form-control w130"></select>
                    </td>
                </tr>
                <tr>
                    <td>地址：</td>
                    <td colspan="3"><input name="address"  value="<?php echo ($org_info["address"]); ?>" type="text" class="w400 form-control"></td></tr>
                <tr><td>备注：</td><td colspan="3"><input name="remark"  value="<?php echo ($org_info["remark"]); ?>" type="text" class="w400 form-control"></td></tr>
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>

        <div class="error">
        </div>
    </form>
    <div class="modal-footer">
        <a href="#" class="btn btn-default"
           data-dismiss="modal">关闭
        </a>
        <a id="submit_unit" class="btn btn-primary">
            <span>修改</span>
        </a>
    </div>
    <script type="text/javascript">

        $(function(){
            $("#start_time,#end_time").manhuaDate({
                Event : "click",//可选
                Left : 0,//弹出时间停靠的左边位置
                Top : -16,//弹出时间停靠的顶部边位置
                fuhao : "-",//日期连接符默认为-
                isTime : false,//是否开启时间值默认为false
                beginY : 2014,//年份的开始默认为1949
                endY :2049//年份的结束默认为2049
            });
        })

        new PCAS("province","city","district","<?php echo empty($org_info['province'])?'请选择':$org_info['province'];?>","<?php echo empty($org_info['city'])?'请选择':$org_info['city'];?>","<?php echo empty($org_info['district'])?'请选择':$org_info['district'];?>");
        $("#price").blur(function(){
            isMoney($(this));
        });

        $("#submit_form").validate({
            rules:{
                org_name:{
                    required:true
                },
                mobile:{
                    valNeqZero:true,
                    remote: {
                        url: "<?php echo U('Admin/Dealer/check_mobile');?>",
                        type: 'get',
                        data: {
                            mobile: function() {
                                return $('#mobile').val();
                            },
                            org_id:function(){
                                return $('#org_id').val();
                            }
                        },
                        beforeSend: function() {
                            var _checking = $('#checking_user');
                            _checking.prev('.field_notice').hide();
                            _checking.next('label').hide();
                            $(_checking).show();
                        },
                        complete: function() {
                            $('#checking_user').hide();
                        }
                    }
                },
                contacts:{
                    required:true
                }
            },
            messages:{
                org_name:{
                    required:"经销商名称不能为空"
                },
                mobile:{
                    valNeqZero:"手机不能为空",
                    remote:"手机号码已被使用，请更换"
                },
                contacts:{
                    required:"联系人不能为空"
                },

            }
        });
        $("#submit_unit").click(function(){
            if ($('#submit_form').valid()) {
                $('#submit_form').submit();
            }
        });
    </script>

</div>
</body>
</html>