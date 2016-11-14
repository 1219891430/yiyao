<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>B2B批发商城</title>

    <link rel="stylesheet" href="/Public/assets/css/mall/style.css">
    <link rel="stylesheet" href="/Public/assets/css/mall/animate.min.css">
    <script type="text/javascript" src="/Public/assets/js/mall/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/mall/shop-nav.js"></script>
    <script type="text/javascript" src="/Public/assets/js/mall/slide.js"></script>
    <script type="text/javascript" src="/Public/assets/js/mall/app.js"></script>
    <script type="text/javascript" src="/Public/assets/js/goods.js"></script>
    <script src="/Public/assets/js/jquery.lazyload.js"></script>

</head>

<style type="text/css">
    .active {
        color: red;
    }
</style>

<body>
<!--头部-->
<div class="top-wrap clearfix">
    <div class="w">
        <div class="tw-l">欢迎来到农乐汇批发商城!</div>
        <div class="tw-r">
            <?php if(session('cust_id') == null): ?>请<a class="top-login" href="/index.php/Mall/Member/login">登录</a>

                <?php else: ?>

                <?php echo session('cust_name'); endif; ?>
            <span class="gy-l">|</span><a href="#">移动端</a><span class="gy-l">|</span>
            <a href="/index.php/Mall/Member/order">我的订单</a>

            <?php if(session('cust_id') == null): else: ?>
                <span class="gy-l">|</span>
                <a class="top-login" href="/index.php/Mall/Member/logout">退出</a><?php endif; ?>
            <span class="gy-l">|</span>
            <a href="javascript:void(0)" id="choiceDepot" class="top-login">选择仓库</a>

        </div>
    </div>
</div>
<div class="top">
    <div class="w clearfix">
        <div class="logo">
            <a href="/index.php/Mall/index">
                <img src="/Public/assets/images/mall/logo.png">
            </a>
        </div>
        <div class="header-tit fl">批发商城</div>
        <div class="top-r">
            <div class="search-w">
                <form method="GET" action="/index.php/Mall/Search">
                    <div class="search">
                        <input class="keyword" name="word" type="text" value="<?php echo ($urlParam['word']); ?>" placeholder="请输入商品名称或关键字">
                        <input class="submit" type="submit" value="搜索">
                    </div>
                </form>
            </div>
            <div class="top-ord">
                <a href="/index.php/Mall/Cart/index">
                    <span class="top-num cart_num" attr="<?php if(empty($_SESSION['cart_num'])): ?>0<?php else: echo session('cart_num'); endif; ?>" id="cart_num"><?php if(empty($_SESSION['cart_num'])): ?>0<?php else: echo session('cart_num'); endif; ?><i></i></span>
                    <em class="top-car"></em>
                    <span>我的进货单</span>
                    <em class="top-go"></em>
                </a>
            </div>
        </div>
    </div>
</div>

<!--导航 start-->
<div class="headNav">
    <div class="navCon w">
        <div class="navCon-cate fl navCon_on">
            <div class="navCon-cate-title"><a href="#"><img src="/Public/assets/images/mall/nav-tit.png"></a></div>
            <div id="cateMenu" class="cateMenu hidden">
                <ul>
                    <?php if(is_array($class)): foreach($class as $key=>$vo): ?><li style="border-top:none;" class="">
                            <div class="cate-tag">
                                <strong><a href="/index.php/Mall/Search?cat=<?php echo ($vo["class_id"]); ?>" style="display:block"><?php echo ($vo["class_name"]); ?></a></strong>
                                <div class="cate-sm clearfix">
                                    <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = array_slice($vo['child'],0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($child["class_id"]); ?>"><?php echo ($child["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                            <div class="list-item hide" style="display: none; top: 0px;">
                                <ul class="itemleft">
                                    <dl>
                                        <dt><a href=""><?php echo ($vo["class_name"]); ?></a></dt>
                                        <dd>
                                            <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($child["class_id"]); ?>"><?php echo ($child["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </dd>
                                    </dl>
                                    <div class="fn-clear"></div>
                                    <div class="fn-clear"></div>
                                </ul>
                            </div>
                        </li><?php endforeach; endif; ?>
                    <!--<li style="border-top:none;" class="">
                        <div class="cate-tag">
                            <strong><a href="/index.php/Mall/Search?cat=<?php echo ($vo["class_id"]); ?>" style="display:block"><?php echo ($vo["class_name"]); ?></a></strong>
                            <div class="cate-sm clearfix">
                                <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = array_slice($vo['child'],0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($child["class_id"]); ?>"><?php echo ($child["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                        <div class="list-item hide" style="display: none; top: 0px;">
                            <ul class="itemleft">
                                <dl>
                                    <dt><a href=""><?php echo ($vo["class_name"]); ?></a></dt>
                                    <dd>
                                        <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($child["class_id"]); ?>"><?php echo ($child["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </dd>
                                </dl>
                                <div class="fn-clear"></div>
                                <div class="fn-clear"></div>
                            </ul>
                        </div>
                    </li>
                    <li style="border-top:none;" class="">
                        <div class="cate-tag">
                            <strong><a href="/index.php/Mall/Search?cat=<?php echo ($vo["class_id"]); ?>" style="display:block"><?php echo ($vo["class_name"]); ?></a></strong>
                            <div class="cate-sm clearfix">
                                <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = array_slice($vo['child'],0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($child["class_id"]); ?>"><?php echo ($child["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                        <div class="list-item hide" style="display: none; top: 0px;">
                            <ul class="itemleft">
                                <dl>
                                    <dt><a href=""><?php echo ($vo["class_name"]); ?></a></dt>
                                    <dd>
                                        <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($child["class_id"]); ?>"><?php echo ($child["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </dd>
                                </dl>
                                <div class="fn-clear"></div>
                                <div class="fn-clear"></div>
                            </ul>
                        </div>
                    </li>-->
                </ul>
            </div>
        </div>
        <div class="navCon-menu fl">
            <ul>
                <li><a href="/index.php/Mall">首页</a></li> <!--当前栏目，加class="curMenu"-->
                <li><a href="/index.php/Mall/Member/index">个人中心</a></li>
            </ul>
        </div>
    </div>
</div>
<!--导航 end-->


<div class="red-line"></div>

<!--产品详情页-->
<div class="ware-top w clearfix">
    <h1><?php echo ($goods_info["goods_name"]); ?></h1>
    <div class="ware-l"><img class="lazy" data-original="<?php echo C('GOODS_IMG'); echo ($goods_info["main_img"]); ?>"></div>
    <div class="ware-r">
        <div class="detail-price">
            <table>
                <tr><th>价格</th>
                    <?php if(is_array($goods_price)): foreach($goods_price as $key=>$vo): ?><td class="price">￥<span><?php echo getPrice($vo['act_price']);?></span></td><?php endforeach; endif; ?>
                </tr>
                <tr><th>起批量</th>
                    <?php if(is_array($goods_price)): foreach($goods_price as $key=>$vo): ?><td><?php echo ($vo["wholesale_num"]); ?> <?php echo ($vo["goods_unit"]); ?></td><?php endforeach; endif; ?>
                </tr>
            </table>
        </div>
        <!--<div class="detail-info clearfix color-detail">
            <h4>颜色</h4>
            <div class="detail-info-r">
                <span>粉色</span>
                <span class="hover">淡蓝</span>
                <span>米粉</span>
            </div>
        </div>-->
        <div class="detail-info clearfix Specifications-detail">
            <span class="sf-notice font-brand">注：只可选择1种货品</span>
            <h4>规格</h4>
            <div class="detail-info-r" id="de-line">
                <?php if(is_array($goods_price)): foreach($goods_price as $key=>$vo): ?><div class="spec-line clearfix">
                    <strong><?php echo ($vo["goods_spec"]); ?></strong><span><?php echo ($vo["goods_unit"]); ?></span><span><?php echo getPrice($vo['act_price']);?>元</span>
                    <div class="item-amount">
                        <a class="J_Minus minus" href="javascript:AJ_Minus(<?php echo ($vo["cv_id"]); ?>, '<?php echo ($vo["goods_unit"]); ?>')">-</a>
                        <input class="text-amount J_ItemAmount" attr="<?php echo ($vo["cv_id"]); ?>" name="goods" id="J_amount_<?php echo ($vo["cv_id"]); ?>" type="text" onblur="AJ_Edit(<?php echo ($vo["cv_id"]); ?>,'<?php echo ($vo["goods_unit"]); ?>')" autocomplete="off" data-max="365" value="0">
                        <a class="J_Plus plus" href="javascript:AJ_Plus(<?php echo ($vo["cv_id"]); ?>,'<?php echo ($vo["goods_unit"]); ?>')">+</a>
                    </div>
                </div><?php endforeach; endif; ?>

            </div>
            <i id="specifications" class="spec-show"></i>
        </div>
        <div class="detail-info clearfix free-detail">
            <h4>促销信息</h4>
            <div class="detail-info-r">
                <?php if(is_array($acts)): foreach($acts as $key=>$act): if($act['act_type'] == 0): ?><div class="clearfix">
                            <h5>买 <?php echo ($act['goods_unit']); ?> 装优惠</h5>
                            <div class="font-brand">
                                <p>
                                    原价 <?php echo ($act['goods_base_price']); ?> 元, 现价 <?php echo ($act['act_price']); ?> 元</br>
                                </p>
                            </div>
                        </div><?php endif; ?>

                    <?php if($act['act_type'] == 1): ?><div class="clearfix">
                            <h5>买 <?php echo ($act['goods_unit']); ?> 装返现</h5>
                            <div class="font-brand">
                                <p>
                                    每购满 <?php echo ($act['act_money']); ?> 元减 <?php echo ($act['act_offer_money']); ?> 元</br>
                                </p>
                            </div>
                        </div><?php endif; ?>

                    <?php if($act['act_type'] == 2): ?><div class="clearfix">
                            <h5>买 <?php echo ($act['goods_unit']); ?> 装赠品</h5>

                            <div class="free-d-c">
                                <p>
                                    <span>每够满<?php echo ($act['goods_num']); echo ($act['goods_unit']); ?> 赠送</span> <a href="/index.php/Mall/goods?goods_id=<?php echo ($act["goods_id"]); ?>&org_id=<?php echo ($act["org_parent_id"]); ?>"><img data-original="<?php echo C('GOODS_IMG'); echo ($act['song_goods']['main_img']); ?>" width="30" height="30"></a><span>×<?php echo ($act['song_goods_num']); ?> <?php echo ($act['song_goods']['goods_unit']); ?></span>
                                </p>
                            </div>

                        </div><?php endif; endforeach; endif; ?>



            </div>
        </div>
        <div class="good-menu clearfix">
            <a class="btn-brand" id="ordernow" href="javascript:void(0);">立即预购</a>
            <a class="btn-pink" id="addcar" href="javascript:void(0);"><i class="i-car"></i>加入进货单</a>
        </div>
    </div>
</div>
<div class="w clearfix mt30">
    <div class="hot-st-l">
        <!--<h2>热销商品</h2>-->
        <h2>最新上架</h2>
        <div class="hot-st">
            <?php if(is_array($goods_hot)): foreach($goods_hot as $key=>$vo): ?><a href="/index.php/Mall/goods?goods_id=<?php echo ($vo["goods_id"]); ?>&org_id=<?php echo ($vo["org_parent_id"]); ?>">
                <img class="lazy" data-original="<?php echo C('GOODS_IMG'); echo ($vo["main_img"]); ?>">
                <p class="tit"><?php echo ($vo["goods_name"]); ?></p>
                <p class="num clearfix"><span class="total">热销 <?php if(empty($vo['sale_num'])): ?>0 <?php else: ?> <?php echo getGoodsNumByUnit($vo['sale_num'], $vo['goods_unit']); endif; ?> <?php echo ($vo['goods_unit']); ?></span><span class="price">￥<?php echo getPrice($vo['act_price']);?></span></p>
            </a><?php endforeach; endif; ?>
        </div>
    </div>
    <div class="detail-r">
        <div class="detail-nav clearfix">
            <div class="tit"><i></i>商品详情</div>
        </div>
        <div class="obj-content">
            <div class="brand-nam">
                品牌名称：<a href="/index.php/Mall/Search?brand=<?php echo ($goods_info["brand_id"]); ?>"><?php echo ($goods_info["brand_name"]); ?> </a>
                品类名称：<a href="/index.php/Mall/Search?cat=<?php echo ($goods_info["class_id"]); ?>"><?php echo ($goods_info["class_name"]); ?> </a>
            </div>
            <h4>产品参数</h4>
            <ul class="obj-list clearfix">
                <li>商品条码：<?php echo ($goods_info["goods_code"]); ?></li>
                <li>商品规格：<?php echo ($goods_info["goods_spec"]); ?></li>
            </ul>
        </div>
        <div class="description-w">
            <?php echo ($goods_info["goods_desc"]); ?>
        </div>
    </div>
</div>

<!--底部-->


<!--切换仓库地址-->
<div class="bomb-bg hidden">
    <div class="change-bomb">
        <!--<em class="closed"></em>-->
        <div class="chge-tit"><i></i>仓库地址</div>
        <div class="chge-con">
            <ul class="clearfix">
                <?php if(is_array($depot)): foreach($depot as $key=>$vo): ?><li><a class="depot_choice <?php if($vo["repertory_id"] == $_SESSION['mall_depot_id']): ?>hover<?php endif; ?>" href="/index.php/mall/index/choiceDepot?depot_id=<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></a></li><?php endforeach; endif; ?>
            </ul>
        </div>
        <div class="chge-ft">
            <img src="/Public/assets/images/mall/chg-ft.jpg">
        </div>
    </div>
</div>


<div class="foot-ensure">
    <div class="ensure-box clearfix">
        <div class="item">
            <i class="i-zpbz"></i>
            <p><strong>正品保证</strong><br>品质保障 购物无忧</p>
        </div>
        <div class="item">
            <i class="i-ksxd"></i>
            <p><strong>快速下单</strong><br>快速准确下单</p>
        </div>
        <div class="item">
            <i class="i-hssd"></i>
            <p><strong>火速送达</strong><br>B2B模式 急速送达</p>
        </div>
        <div class="item" style="border: none;">
            <i class="i-tsfw"></i>
            <p><strong>特色服务</strong><br>不一样的购物体验</p>
        </div>
    </div>
</div>
<div class="copyright">
    <div class="w">
        <p><a href="/index.php/Mall/index.html">首页</a> |
            <a href="http://www.nlh360.com/article/61" target="_blank">关于我们</a> |
            <a href="http://company.zhaopin.com/CC324868137.htm" target="_blank">诚聘英才</a> |
            <a href="http://www.nlh360.com/" target="_blank">农乐汇商城</a> |
            <a href="http://www.nlh360.com/article/63" target="_blank">合作洽谈</a> |
            <a href="http://www.nlh360.com/article/64" target="_blank">农乐汇优势</a>
        </p>
        <p>
            CopyRight @ 2016 河北泽农信息科技有限公司 增值电信业务经营许可证：冀B2-20160058 网站备案：冀ICP备15028980号
            <span style="display: none;"><script src="http://s4.cnzz.com/z_stat.php?id=1257011767&web_id=1257011767" language="JavaScript"></script></span>
        </p>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        // 未登录弹出选择仓库
        <?php if(empty($_SESSION['mall_depot_id'])): ?>$(".bomb-bg").fadeToggle();


        $('.depot_choice').click(function () {
            $(this).addClass('hover')
            $(this).sibling().removeClass('hover')
        })<?php endif; ?>
    })

    $("#choiceDepot").click(function () {
        $(".bomb-bg").fadeToggle();

        $('.depot_choice').click(function () {
            $(this).addClass('hover')
            $(this).sibling().removeClass('hover')
        })
    })

</script>

<script type="text/javascript">
    $(function() {
    	
		try{
			$("img.lazy").lazyload({effect: "fadeIn"});
		}
		catch(e){}
	

        /*$(".J_ItemAmount").keyup(function () {

            var cv_id = $(this).attr("attr")

            $(".J_ItemAmount").each(function (i, v) {
                if ($(v).attr("attr") != cv_id) {

                    if ($(v).val() != 0) {

                        $(v).val(0)
                    }

                };

                //console.log($(this).attr("attr"))
            })

        })*/



        //配送范围的展开收起
        $("#specifications").click(function() {
            var aaa = $("#de-line").css("overflow");
            if (aaa == "hidden") {
                $("#de-line").css({'height': 'auto'});
                $("#de-line").css("overflow", "auto");
                $("#specifications").removeClass("spec-show");
                $("#specifications").addClass("spec-hold");
            }
            else {
                $("#de-line").css("height", "80");
                $("#de-line").css("overflow", "hidden");
                $("#specifications").removeClass("spec-hold")
                $("#specifications").addClass("spec-show")
            }
        });
        if ($("#de-line").height() > 80) {
            $("#de-line").css("height", "80");
            $("#de-line").css("overflow", "hidden");
            $("#specifications").css("display", "block");
        }
    });

    // 数量加
    function AJ_Plus(cv_id, unit) {

        $(".J_ItemAmount").each(function () {
            if ($(this).attr("attr") != cv_id) {

                if ($(this).val() != 0) {

                    $(this).val(0)
                }

            };

            //console.log($(this).attr("attr"))
        })

        var num = checkGoodsNumByUnit($('#J_amount_'+cv_id).val(), unit);

        num += 1

        $('#J_amount_'+cv_id).val(num)

    }

    // 数量减
    function AJ_Minus(cv_id, unit) {
        var num = checkGoodsNumByUnit($('#J_amount_'+cv_id).val(), unit);

        num -= 1

        if (num <= 0) {
            num = 0
        }

        $('#J_amount_'+cv_id).val(num)

    }

    function AJ_Edit(cv_id, unit) {
        $(".J_ItemAmount").each(function () {
            if ($(this).attr("attr") != cv_id) {

                if ($(this).val() != 0) {

                    $(this).val(0)
                }

            };

            //console.log($(this).attr("attr"))
        })

        var num = checkGoodsNumByUnit($('#J_amount_'+cv_id).val(), unit);

        if (num <= 0) {
            num = 0
        }

        $('#J_amount_'+cv_id).val(num)

    }

    // 加入购物车
    function addAll(action) {

        <?php if(!isset($_SESSION['cust_id'])): ?>alert('登陆后可添加商品');

                        location.href='/index.php/Mall/Member/login';
        return;<?php endif; ?>


    <?php if($_SESSION['mall_depot_id'] != $_SESSION['cust_depot']): ?>alert('超出配送范围， 禁止添加')

            return;<?php endif; ?>

        // 判断商品是否重复
        var repeat = 0;
        $(".J_ItemAmount").each(function () {
            if ($(this).val() != 0) {
                //console.log($(this).val())
                repeat += 1;
            }

            //console.log($(this).attr("attr"))
        })

        if(repeat != 1) {

            alert("请选择一种货品");
            return false;

        }

        // 获取选择的商品数量
        var s = 0
        var status;
        $("input[name='goods']").each(function (k, v) {
            var num = $(this).val()
            var cv = $(this).attr('attr')
            if (num == 0) {
                return
            }
            status = addcar(cv, num,<?php echo ($_GET["org_id"]); ?>, false)
            s += 1

        })

        console.log(status);

        if (s != 0 && action == 'addcar') {
            if(status) {
                alert('添加成功')
            }

        }
        if (s != 0 && action == 'ordernow') {
            if (status) {
                location.href = '/index.php/Mall/Cart/index'
            }
        }

    }


    // 加入购物车
    $('#addcar').click(function () {

        addAll('addcar')

        $("input[name='goods']").each(function (k, v) {
            $(this).val(0)
        })

    });

    // 立即下单
    $('#ordernow').click(function () {
        addAll('ordernow');

    })

</script>

</body>
</html>