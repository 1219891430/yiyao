<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width = device-width ,initial-scale = 1,minimum-scale = 1,maximum-scale = 1,user-scalable =no,"/>
    <title>批发商城</title>
    <link rel="stylesheet" href="/Public/assets/wap/css/style.css">
    <link rel="stylesheet" href="/Public/assets/wap/css/media.css">
    <script type="text/javascript" src="/Public/assets/js/mall/jquery-1.8.3.min.js"></script>
    <script src="/Public/assets/wap/js/common.js"></script>
    <script src="/Public/assets/js/goods.js"></script>
    <script src="/Public/assets/js/jquery.lazyload.js"></script>
    <!--<link rel="stylesheet" href="/Public/assets/mui/css/mui.min.css">-->
</head>



<body>
<!--子页顶部-->
<div class="zy-search zy-tit">
    <div class="sch-w clearfix">
        <a class="go-back" href="#"><img src="/Public/assets/wap/images/go-back.png"></a>
        <div class="tit">
            进货单
        </div>
    </div>
    <a class="top-user" href="<?php echo U('/WapMall/member');?>"><img src="/Public/assets/wap/images/i-user.png"></a>
    <div class="pch-control" id="_edit"><!--完成-->编辑</div>
</div>
<div class="pch-w clearfix">
    <!--进货单-->
    <?php if(is_array($carts)): $i = 0; $__LIST__ = $carts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pch-list">
        <div class="pch-tit clearfix">
            <input class="checkByOrg" value="<?php echo ($vo["org"]["id"]); ?>" type="checkbox">
            <?php echo ($vo['org']['name']); ?>

        </div>

            <?php if(is_array($vo["cart"])): foreach($vo["cart"] as $key=>$cart): ?><div class="item clearfix" id="goods_<?php echo ($cart["cart_id"]); ?>">
            <img class="lazy" data-original="<?php echo C('GOODS_IMG'); echo ($cart["goods_image"]); ?>">
            <div class="info">
                <p class="tit"><?php echo ($cart["goods_name"]); ?></p>

                <p id="zengpin_<?php echo ($cart["cart_id"]); ?>">
                    <?php if($cart['act']['act_type'] == 0): ?><!--限时优惠，原价 <?php echo ($cart["price"]); ?> 元，现价 <?php echo ($cart["act_price"]); ?> 元--><?php endif; ?>
                    <?php if($cart['act']['act_type'] == 1): ?><!--限时满减，共优惠 <?php echo ($cart['total'] - $cart['act_total']); ?> 元--><?php endif; ?>
                    <?php if($cart['act']['act_type'] == 2): ?><small>赠品：<a href="/index.php/WapMall/goods?goods_id=<?php echo ($cart['act']['song_goods_id']); ?>&org_id=<?php echo ($cart['act']['org_parent_id']); ?>"><?php echo ($cart['song']['goods_name']); ?></a>/<?php echo ($cart['song']['goods_spec']); ?> <span>×<?php echo ($cart['song_goods_num']); ?> <?php echo ($cart['song']['goods_unit']); ?></span></small><?php endif; ?>
                </p>

                <div class="spec-box">
                    <!--不同规格-->
                    <div class="spec">
                        <div class="radio-w">
                            <input name='cart_id' attr="<?php echo ($cart["cv_id"]); ?>" value="<?php echo ($cart["cart_id"]); ?>" class="goods_checkbox_<?php echo ($vo["org"]["id"]); ?>" type="checkbox" id="checkbox_<?php echo ($cart["cv_id"]); ?>">
                        </div>
                        <div class="spec-tit"><span>单位:<?php echo ($cart["goods_unit"]); ?></span><span>￥<?php echo ($cart["act_price"]); ?></span></div>
                        <div class="spec-num clearfix">
                            <p class="price">￥<span id="total_<?php echo ($cart["cart_id"]); ?>"><?php echo ($cart['act_total']); ?></span></p>
                            <input type="hidden" class="old_quantity" id="old_<?php echo ($cart['cart_id']); ?>" value="<?php echo ($cart["quantity"]); ?>">
                            <div class="item-amount" data-quantity="<?php echo ($cart["quantity"]); ?>" data-cartid="<?php echo ($cart["cart_id"]); ?>" data-cvid="<?php echo ($cart["cv_id"]); ?>" data-unit="<?php echo ($cart["goods_unit"]); ?>">

                                <input type="hidden" id="quantity" value="<?php echo ($cart["quantity"]); ?>">

                                <input disabled id="J_amount_<?php echo ($cart["cart_id"]); ?>" class="text-amount J_ItemAmount" type="text" value="<?php echo ($cart["quantity"]); ?>">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><?php endforeach; endif; ?>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>

    <div class="pch-foot clearfix">
        <div class="radio-w">
            <input class="checkAll" type="checkbox">全选
        </div>
        <div class="total <!--hidden-->">
            <p>共<strong class="cart_num"><?php echo ($total_num); ?></strong>件</p>合计：<big>￥<span id="cart_price_total"><?php echo ($total_price); ?></span></big></div>
        <a class="btn" id="sub_btn" href="javascript:orderSubmit();">去结算<!--删除--></a>
    </div>
</div>



<footer>
    <p><a href="#">关于我们</a> | <a href="#">联系我们</a> | <a href="#">洽谈合作</a> | <a href="#">诚聘英才</a></p>
    <p>CopyRight @ 2015 河北泽农信息有限公司</p>
</footer>

<!--底部悬浮按钮-->
<div class="foot-btn">
    <a href="/index.php/WapMall/index"><i class="nlh-home"></i>首页</a>
    <a href="/index.php/WapMall/Index/nav"><i class="nlh-ct"></i>分类</a>
    <a class="active" href="/index.php/WapMall/Cart"><i class="nlh-cart"></i>进货单</a>
</div>


<!--loading start-->
<div class="loading_box"></div>
<!--loading end-->


<script>

    $(".J_ItemAmount").each(function () {
        var num = parseFloat($(this).val())

        $(this).val(num)

    })

    $(".old_quantity").each(function () {
        var num = parseFloat($(this).val())

        $(this).val(num)
    })

    /*$("input[type='checkbox']").each(function () {
        $(this).attr("checked", true);

    });*/
    $("img.lazy").lazyload({effect: "fadeIn"});
    // 弹框
    function toggleCard() {
        $('.modal').slideToggle(300);
    }
    $('.sku-control-box,.btn-close').click(toggleCard);

    $(".go-back").click(function () {
        history.go(-1)
    })

    // 全选
    $('.checkAll').click(function () {

        var is_check = $(this).attr("checked")

        console.log(is_check)

        if (is_check == undefined) {
            is_check = false
        }



        $("input[type='checkbox']").each(function () {
            $(this).attr("checked", is_check);
        });

    })

    // 根据经销商全选反选
    $('.checkByOrg').click(function () {
        var id = $(this).val()
        console.log(id)
        var is_check = $(this).attr("checked")
        if (is_check == undefined) {
            is_check = false
        }
        $('.goods_checkbox_'+id).each(function () {
            $(this).attr("checked", is_check);
        });
    });

    function item_amount(quantity,cart_id,cv_id) {
        $(this).html('<a class="J_Minus minus" href="javascript:J_Minus('+ cart_id +', '+ cv_id +')">-</a> <input id="J_amount_'+ cart_id +'" class="text-amount J_ItemAmount" onblur="J_amount_edit('+ cart_id +', '+ cv_id + ', '+  +')" type="number" value="'+ quantity +'"> <a class="J_Plus plus" href="javascript:J_Plus('+ cart_id +', '+ cv_id +')">+</a>')
    }

    $("#_edit").toggle(function () {
        $(this).html("完成")
        $("#sub_btn").html('删除')

        $(".item-amount").each(function () {
            var cart_id = $(this).data('cartid')
            var cv_id = $(this).data('cvid')
            var unit = $(this).data('unit')
            var quantity = $("#old_"+cart_id).val()

            $(this).html('<a class="J_Minus minus" href="javascript:J_Minus('+ cart_id +', '+ cv_id +', \'' + unit + '\')">-</a> <input id="J_amount_'+ cart_id +'" class="text-amount J_ItemAmount" onblur="J_amount_edit('+ cart_id +', '+ cv_id +', \''+ unit +'\')" type="text" value="'+ quantity +'"> <a class="J_Plus plus" href="javascript:J_Plus('+ cart_id +', '+ cv_id + ', \''+ unit +'\')">+</a>')
        })

        $("#sub_btn").attr('href','javascript:delAll();')


    },function () {
        $(this).html("编辑")
        $("#sub_btn").html('去结算')

        $(".item-amount").each(function () {
            var cart_id = $(this).data('cartid')
            var quantity = $("#old_"+cart_id).val()

            $(this).html('<input disabled class="text-amount" type="text" value="'+ quantity +'">')
        })

        $("#sub_btn").attr('href', "javascript:orderSubmit();")

    })


    // 数量加
    function J_Plus(cart_id,cv_id, unit) {
        var num = checkGoodsNumByUnit($('#J_amount_'+cart_id).val(), unit);

        num += 1

        updateCart(cart_id, cv_id, num, $('#old_'+cart_id).val())

        $('#J_amount_'+cart_id).val(num)
    }

    // 数量减
    function J_Minus(cart_id,cv_id, unit) {
        var num = checkGoodsNumByUnit($('#J_amount_'+cart_id).val(), unit);

        num -= 1

        if (num <= 0) {
            return false;
        }



        updateCart(cart_id, cv_id, num, $('#old_'+cart_id).val())

        $('#J_amount_'+cart_id).val(num)
    }

    // 输入
    function J_amount_edit(cart_id, cv_id, unit) {
        var num = checkGoodsNumByUnit($('#J_amount_'+cart_id).val(), unit);

        updateCart(cart_id, cv_id, num, $('#old_'+cart_id).val())

        $('#J_amount_'+cart_id).val(num)

    }


    function updateCart(cart_id, cv_id, quantity, old_num) {
        if (quantity == old_num) {
            return false;
        }

        $.ajax({
            url: '/index.php/WapMall/Cart/update?r='+new Date().getTime(),
            type:'get',
            data: {cv_id: cv_id, quantity: quantity},
            success: function (d) {
                console.log(d)
                if (d.status) {
                    $('#old_'+cart_id).val(d.rows.goods[cv_id]['quantity'])
                    $('.cart_num').html(d.rows.total_num)
                    $('#cart_price_total').html(d.rows.amount)
                    $('#total_' + cart_id).html(d.rows.subtotal.toFixed(2))
                    //$('#offer_money_' + cart_id).html(d.rows.goods[cv_id]['offer_money'])

                    var song = d.rows.goods[cv_id].song_goods
                    var song_num = d.rows.goods[cv_id].song_num
                    if (song_num > 0) {

                        $("#zengpin_" + cart_id).html("赠品：<a href='/index.php/WapMall/goods?goods_id="+song['goods_id']+"&org_id="+song['org_parent_id']+"'>"+song['goods_name']+"</a>"+ "/" + song['goods_spec'] +" <span>×"+song_num +" "+ song['goods_unit']+"</span>")
                    } else {
                        $("#zengpin_" + cart_id).html("");
                    }

                } else {
                    console.log("cv_id:"+cv_id)
                    console.log("quantity:"+quantity)
                    $('#J_amount_'+cart_id).val(old_num)
                    alert(d.msg)
                }

            },
            error: function (xhr) {
                console.log(xhr)
            }

        })
    }

    // 选择删除
    var delAll = function () {
        var ids = '';
        $('input:checkbox[name=cart_id]:checked').each(function(){
            ids += $(this).val() + ','

        });
        if (ids.length <= 0) {
            return false;
        }
        console.log(ids.length)
        ids = ids.substring(0, ids.length-1)

        delCart(ids)
    }

    function delCart(cart_id) {
        if(confirm("确定要删除商品吗？")) {

            $.ajax({
                url: '/index.php/WapMall/Cart/del',
                type:'get',
                data: {cart_id: cart_id},
                success: function (d) {
                    if (d.status) {
                        ids = cart_id.split(',')

                        $.each(ids, function (k,v) {
                            //console.log(v)
                            $('#goods_' + v).remove()
                        })

                        $('.cart_num').html(d.rows.total_num)
                        $('.cv_num').html(d.rows.cv_num)
                        $('#cart_price_total').html(d.rows.amount)
                    } else {
                        //$('#J_amount_'+cart_id).val(old_num)
                        alert(d.rows.msg)
                    }

                },
                error: function (xhr) {
                    console.log(xhr)
                }

            })
        }

    }

    // 提交订单
    function orderSubmit() {

        var cv_id=''
        $("input[name='cart_id']:checked").each(function (k, v) {
            //var cart_id = $(this).val()
            cv_id += $(this).attr('attr') + ','
        })
        cv_id = cv_id.substr(0, cv_id.length-1)

        if(cv_id.length == 0) {
            return;
        }

        $.ajax({
            url: '/index.php/WapMall/Cart/order',
            type: 'post',
            data: {
                cv_id: cv_id,
                remark: '',
            },
            success: function (d) {
                if (d.status) {
                    alert(d.msg)
                    location.href = '/index.php/WapMall/Member/order'
                } else {
                    alert(d.msg)
                }
            }

        })
    }

</script>

</body>
</html>