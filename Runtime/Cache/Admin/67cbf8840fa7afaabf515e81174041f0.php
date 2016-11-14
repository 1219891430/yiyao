<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>查看退货预单</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>

                <tr>
                    <td colspan="5" class="title" style="text-align: center;font-size: 25px;">
                        <h3>退货预单</h3>
                    </td>
                </tr>

                <tr>
                    <td colspan="5" class="title" style="text-align: center;font-size: 13px">
                        <h3>单据号:<?php echo ($return["return_code"]); ?>&nbsp;&nbsp;日期:<?php echo date("Y-m-d H:i:s",$return['add_time']);?></h3>
                    </td>
                </tr>

                </thead>
                <tbody>
                <tr>
                    <td class="title">购货方：</td>
                    <td><input name="cust_name" id="cust_name" value="<?php echo ($return["cust_name"]); ?>" type="text"
                               readonly="readonly" class="unit_number form-control w200">
                        <ul class="ul-div" id="cust-data">
                        </ul>
                    </td>
                    <td class="title" align="right">联系人：</td>
                    <td><input name="cust_contacts" id="cust_contacts" value="<?php echo ($return["cust_contact"]); ?>"
                               readonly="readonly" type="text"
                               class="unit_name form-control w200">
                    </td>
                </tr>
                <tr>
                    <td> 电话：</td>
                    <td><input name="cust_phone" id="phone" readonly="readonly" value="<?php echo ($return["cust_tel"]); ?>"
                               type="text" class="unit_number form-control w200">
                    </td>
                    <td class="title" align="right">地址：</td>
                    <td>
                        <input name="cust_address" id="cust_address" type="text" value="<?php echo ($return["cust_address"]); ?>"
                               readonly="readonly" class="unit_number form-control w300">
                    </td>
                </tr>
                <tr>
                    <td> 出货仓库：</td>
                    <td>

                        <input name="outdepot" id="outdepot" type="text" readonly class="unit_number form-control w200" value="<?php echo ($return["repertory_name"]); ?>">
                    </td>


                </tr>
                <tr>
                    <td align="right">经销商：</td>
                    <td>
                        <?php echo ($return["org_name"]); ?>
                    </td>
                    <td align="right">业务员：</td>
                    <td>
                        <?php echo ($return["true_name"]); ?>
                    </td>
                </tr>

                <!--<tr>
                    <td align="right">送货司机：</td>
                    <td>
                        <?php echo ($order["o_driver"]); ?>
                    </td>
                </tr>-->
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
                    <td width="7%">单价</td>
                    <td width="10%">金额(￥)</td>
                    <td width="12%">备注</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr class='tr_operate'>
                        <td><?php echo ($gvo["goods_code"]); ?></td>
                        <td><?php echo ($gvo["goods_name"]); echo ($gvo["goods_spec"]); ?></td>
                        <td><?php echo ($gvo["goods_unit"]); ?></td>
                        <td><input class='w50 tr goods_num' type='text' value='<?php echo (getGoodsNum($gvo["goods_num"])); ?>'></td>
                        <td><input class='w50 tr goods_price' type='text' value='<?php echo ($gvo["goods_money"]); ?>'></td>
                        <td class='tr tr_total'><input id="class='w50 tr goods_all_price' type="text" value=" <?php echo (sprintf('%.2f',$gvo['goods_money']*$gvo['goods_num'])); ?>"></td>
                        <td><input value="<?php echo ($gvo["order_remark"]); ?>" class='w70 remark' type='text'></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr id="goods_add_tr">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- 总价 -->
                <tr><td colspan='4'>&nbsp;</td><td class="tr" id='num_total'></td><td class="tr" id='price_total'><?php echo ($return["return_real_money"]); ?></td><td></td></tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="8">备注：<?php echo ($return["order_remark"]); ?></td>
                </tr>
                </tfoot>
            </table>
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td width="80px">制单人：</td><td class="tl"><?php echo ($_SESSION['true_name']); ?></td>
                    <td>
                        制单时间：<span><?php echo date('Y-m-d H:i:s');?></span>
                    </td>
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