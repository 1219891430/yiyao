<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>修改配送单【<?php echo ($apply['apply_code']); ?>】</span>
        </h4>
    </div>
    <form action="" id="submit_form" method="post">
        <input type="hidden" name="id" value="<?php echo ($apply["apply_id"]); ?>">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>出库仓库：</td>
                    <td class="tl">
                        <select name="depot_id" id="depot_ids" class="w200 form-control">
                            <option value="0">选择仓库</option>
                            <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['repertory_id'] == $apply['repertory_id']): ?><option selected="selected" value="<?php echo ($svo["repertory_id"]); ?>"><?php echo ($svo["repertory_name"]); ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo ($svo["repertory_id"]); ?>"><?php echo ($svo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>

                    <td class="tr">
                        单据编号：
                    </td>
                    <td>
                        <span class="f16 fb"><?php echo ($apply["apply_code"]); ?></span>
                        <input type="hidden" id="code" value="<?php echo ($apply["apply_code"]); ?>">
                    </td>

                </tr>
                <tr>
                    <td>业务员</td>
                    <td>
                        <select id="apage_staff_id" name="staff_id" class="w200 form-control">
                            <option value="0">选择业务员</option>
                            <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['admin_id'] == $apply['staff_id']): ?><option selected="selected" value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                    <td class="tr"></td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>备注：</td>
                    <td colspan="3">
                        <input class="w300 form-control" id="apply_remark" value="<?php echo ($apply["apply_remark"]); ?>">
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
                    <!--<td width="12%">当前库存</td>-->
                </tr>
                </thead>
                <tbody>

                <?php if(is_array($goods_info)): $i = 0; $__LIST__ = $goods_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr class='tr_operate'>
                        <input type='hidden' class='goods_id' name='goods_id' value="<?php echo ($gvo["goods_id"]); ?>">
                        <td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a><?php echo ($gvo["goods_code"]); ?></td>
                        <td><?php echo ($gvo["goods_name"]); ?>/<?php echo ($gvo["goods_sepc"]); ?></td>
                        <td>
                            <select name="cv_id" class='w50 goods_unit_select'>

                                <?php if(is_array($gvo["goods_unit"])): $i = 0; $__LIST__ = $gvo["goods_unit"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$uvo): $mod = ($i % 2 );++$i;?><option attr='<?php echo ($uvo["json"]); ?>' <?php if($uvo['cv_id'] == $gvo['cv_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($uvo["cv_id"]); ?>"><?php echo ($uvo["goods_unit"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>

                        </td>
                        <td><input class='w50 tr goods_num' type='text' value='<?php echo (getGoodsNum($gvo["apply_num"])); ?>'></td>

                        <!--<td>
                            <?php echo $gvo['depot_stock']['stock_string'] ?>
                        </td>-->
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                <tr id="goods_add_tr">
                    <td></td>
                    <td style="padding:0">
                        <span class="fb f24 cursor-pointer pull-right mr20" id="goods_add">+</span>
                    </td>
                    <td></td>
                    <td></td>


                </tr>
                <tr><td colspan='3'>&nbsp;</td><td class="tr" id='num_total'></td><!--<td class="tr" id='price_total'></td>--></tr>
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
                    <td class="tr">时间：</td><td><span><?php echo date("Y-m-d H:i:s");?></span></td>
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
            <span>修改</span>
        </a>
    </div>
    <div class="goods_div" id="goods_div">
        <h3>选择商品</h3>
        <div class="mt20">
            <select id="brand" class="w150 form-control">
                <option value="0">全部品牌</option>
                <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["brand_id"]); ?>"><?php echo ($vo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            &nbsp;<input id="goods" placeholder="请输入商品名称" type="text" class="form-control w200">
            <input type="button" id="find_goods" class="btn btn-primary" value="搜索"/>
        </div>
        <div class="goods_body">
            <table class="table list_table mt10 goods_search" id="goods_search">
                <thead>
                <tr><td class="tc" width="50px"><input id="choice_all" class="check_mt0" type="checkbox"></td><td>商品名称</td></tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="goods_operate">
            <input type="button" id="goods-add" class="btn btn-primary" value="添加">
            <input type="button" id="goods-close" class="btn btn-default" value="关闭">
        </div>
    </div>
    <script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
    <script type="text/javascript">



        $("#goods_add").click(function(){
            $depot_id = $("#depot_ids").val();
            setGoodsDataInit($depot_id, "<?php echo U('Admin/DeliverApply/checkStock');?>", 0, 0);
        });

        $("#find_goods").click(function(){
        	$depot_id = $("#depot_ids").val();
            if($("#goods").val()!="")
                queryGoodsList("<?php echo U('Admin/GoodsInfo/selGoods');?>", $("#brand").val(), $("#goods").val(), $depot_id);
            else
                alert("请填写商品");
        });

        // 商品下拉选择后，列出所有的商品信息
        $("#brand").change(function(){
        	$depot_id = $("#depot_ids").val();
            if($("#brand").val()==0 && $("#goods").val()==""){
                $("#goods_search").find("tbody").empty();
            }else{
                queryGoodsList("<?php echo U('Admin/GoodsInfo/selGoods');?>", $("#brand").val(), '', $depot_id);
            }
        });

        $("#create_form").click(function(){
            //console.log($("#depot_ids").val());
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
            if($("#goods_table .tr_operate").length==0)
                alert("请添加商品")
//            else if($("#sid").val()==0||$("#sid").val()=="")
//                alert("未查询到供应商信息,请先搜索选择供应商");
            else if(!checkGoodsNeqZero())
            {
                return false;
            }
            else
            {
                var aGoodsData=goodsTransferArr("#goods_table");
                var data={
                    id:<?php echo ($apply["apply_id"]); ?>,
                    code:$("#code").val(),
                    staff_id:$("#apage_staff_id").val(),
                    depot_id:$("#depot_ids").val(),
                    remark:$("#apply_remark").val(),
                    goods_info:aGoodsData
                };
                ajaxDataAUD("/index.php/Admin/DeliverApply/edit",data,true)
            }
        }
    </script>
</div>