<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>查看配送单</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>出库仓库：</td>
                    <td class="tl">
                        <input class="w200 form-control" type="text" disabled value="<?php echo ($apply["repertory_name"]); ?>">
                    </td>

                    <td class="tr">
                        单据编号：
                    </td>
                    <td>
                        <input class="w200 form-control" type="text" disabled value="<?php echo ($apply["apply_code"]); ?>">
                    </td>

                </tr>
                <tr>
                    <td>业务员</td>
                    <td>
                        <input class="w200 form-control" type="text" disabled value="<?php echo ($apply["staff_name"]); ?>">
                    </td>
                    <td class="tr"></td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>备注：</td>
                    <td colspan="3">
                        <input class="w300 form-control" type="text" disabled value="<?php echo ($apply["apply_remark"]); ?>">
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
                    <td width="7%">单位</td>
                    <td width="7%">数量</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="goods_add_tr">
                    <td><?php echo ($vo["goods_code"]); ?></td>
                    <td><?php echo ($vo["goods_name"]); ?></td>
                    <td><?php echo ($vo["goods_unit"]); ?></td>
                    <td><?php echo (getGoodsNum($vo["apply_num"])); ?></td>

                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr><td colspan='3'>&nbsp;</td><td class="tr" id='num_total'></td></td></tr>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td width="80px">制单人：</td><td class="tl"><?php echo ($_SESSION['true_name']); ?></td>
                    <td class="tr">时间：</td><td><span><?php echo date('Y-m-d H:i');?></span></td>
                </tr>

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
    </div>
</div>