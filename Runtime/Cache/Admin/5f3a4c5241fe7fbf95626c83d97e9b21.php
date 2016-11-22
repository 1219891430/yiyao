<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_650">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>设置经销商</span>
        </h4>
    </div>
    <form action="/index.php/Admin/GoodsInfo/setorg" id="setorg_form" method="post">
        <div class="modal-body modal_650">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>产品名称</td><td>
                    <input type="hidden" name="gid" value="<?php echo ($goods["goods_id"]); ?>">
                    <input type="text" name="goods_name" readonly value="<?php echo ($goods["goods_name"]); ?>" id="goods_name" maxlength="10" class="w200 form-control"/>
                    </td>
                </tr>

                <?php if($_SESSION['depot_id'] == 0): ?><tr>
                        <td>仓库</td><td>
                        <select class="w200 form-control" id="select_depot" name="depot_id">
                            <option value="<?php echo ($org["org_id"]); ?>">请选择仓库</option>
                            <?php if(is_array($depots)): foreach($depots as $key=>$depot): ?><option value="<?php echo ($depot["repertory_id"]); ?>"><?php echo ($depot["repertory_name"]); ?></option><?php endforeach; endif; ?>



                        </select>
                    </td>
                    </tr><?php endif; ?>

                <tr>
                    <td>经销商</td>
                    <td colspan="3">

                        <select class="w300 form-control" id="select_org" name="org_id">

                            <?php if($_SESSION['depot_id'] == 0): ?><option value="0">请选择经销商</option>
                            <?php else: ?>
                                <option value="0">请选择经销商</option>

                                <?php if(is_array($orgs)): foreach($orgs as $key=>$org): ?><option value="<?php echo ($org["org_id"]); ?>" <?php if($goods['org_parent_id'] == $org['org_id']): ?>selected<?php endif; ?> ><?php echo ($org["org_name"]); ?></option><?php endforeach; endif; endif; ?>

                        </select>

                    </td>
                </tr>


                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
        <input type="hidden" name="bid" value="<?php echo ($brand_info["brand_id"]); ?>">
        <div class="error">
        </div>
    </form>
    <div class="modal-footer">
        <a href="#" class="btn btn-default"
           data-dismiss="modal">关闭
        </a>
        <a id="submit_setorg" class="btn btn-primary">
            <span>修改</span>
        </a>
    </div>
    <input type="hidden" id="brand_close" value="<?php echo ($brand_info["is_close"]); ?>">

    <script type="text/javascript">
        $("#select_depot").change(function () {
            var depot_id = $(this).children('option:selected').val()

            $.ajax({
                url: "/index.php/Admin/GoodsInfo/getOrgByDepot",
                data: {"depot_id": depot_id},
                type: "get",
                success: function (d) {
                    $("#select_org").html('<option value="0">请选择经销商</option>');

                    var selected = "";

                    var old_orgid = "<?php echo ($goods["org_parent_id"]); ?>";

                    $.each(d, function (i, v) {
                        console.log(v.org_id == old_orgid)
                        if (v.org_id == old_orgid) {
                            selected = "selected"
                        } else {
                            selected = ""
                        }
                        $("#select_org").append("<option "+ selected +" value='"+ v.org_id +"'>"+ v.org_name +"</option>")
                    })
                }
            })
        })

        $("#submit_setorg").click(function () {
            $("#setorg_form").submit()
        })
    </script>
</div>