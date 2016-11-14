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

<!--产品列表页-->
<div class="w sch-t-info clearfix">
    <p class="fl">全部货源</p>
    <p class="fr">共<span class="font-brand"><?php echo ($count); ?></span>件<?php echo ($urlParam['word']); ?>相关产品</p>
</div>
<div class="list-class w">
    <!--<div class="class-row clearfix">
        <h3>价格</h3>
        <div class="class-item">
            <a href="#">0-99</a>
            <a href="#">100-299</a>
        </div>
        <div class="class-btn hidden">
            <a class="btn-whit" href="javascript:;">更多<i class="i-show"></i></a>
        </div>
    </div>-->
    <!--<div class="class-row clearfix">
        <h3>净含量</h3>
        <div class="class-item">
            <a href="#">1200ml</a>
            <a href="#">1000ml</a>
            <a href="#">800ml</a>
            <a href="#">400ml</a>
            <a href="#">200ml</a>
            <a href="#">100ml</a>
            <a href="#">50ml</a>
            <a href="#">40ml</a>
            <a href="#">30ml</a>
            <a href="#">25ml</a>
        </div>
        <div class="class-btn">
            <a class="btn-whit btnshow" href="javascript:;">更多 +</a>
        </div>
    </div>-->


    <div class="class-row clearfix">


            <h3>按分类</h3>
            <div class="class-item">
                <?php if(is_array($cats)): foreach($cats as $id=>$cat): ?><a class="cats <?php if($urlParam["cat"] == $id): ?>active<?php endif; ?>" attr="<?php echo ($id); ?>"  href="javascript:;"><?php echo ($cat); ?></a><?php endforeach; endif; ?>

            </div>

        <div class="class-btn hidden">
            <a class="btn-whit" href="javascript:;">更多<i class="i-show"></i></a>
        </div>
    </div>



    <div class="class-row clearfix">
        <h3>按品牌</h3>
        <div class="class-item">
            <?php if(is_array($brands)): foreach($brands as $id=>$vb): ?><a class="brand <?php if($urlParam["brand"] == $id): ?>active<?php endif; ?>"  attr="<?php echo ($id); ?>"  href="javascript:;"><?php echo ($vb); ?></a><?php endforeach; endif; ?>
        </div>
        <div class="class-btn hidden">
            <a class="btn-whit" href="javascript:;">更多<i class="i-show"></i></a>
        </div>
    </div>

</div>
<!--排序-->
<div class="btn-order w clearfix">
    <form>
        <a id="comp" <?php if(!isset($urlParam['order']) || $urlParam['order']=='comp'): ?>class="default-sort" <?php else: ?> class="sort-btn"<?php endif; ?> href="#">综合</a>

        <a id="sales" <?php if(isset($urlParam['order']) && $urlParam['order']=='sales'): ?>class="default-sort" <?php else: ?> class="sort-btn"<?php endif; ?> href="#">销量
        <?php if(session('search_order_sales'.$_SESSION['cust_id']) == 'desc'): ?>&darr;
        <?php else: ?> 
            &uarr;<?php endif; ?>
        </a>

        <a id="price" <?php if(isset($urlParam['order']) && $urlParam['order']=='price'): ?>class="default-sort" <?php else: ?> class="sort-btn"<?php endif; ?> href="#">价格
        <?php if(session('search_order_price'.$_SESSION['cust_id']) == 'desc'): ?>&darr;
            <?php else: ?> 
            &uarr;<?php endif; ?>
        </a>


        <div class="sort-price">
            <input type="text" id="sort_price_max" value="<?php echo ($urlParam['price_max']); ?>" placeholder="￥最高价">
            --
            <input type="text" id="sort_price_min" value="<?php echo ($urlParam['price_min']); ?>" placeholder="￥最低价">
        </div>
        <div class="sort-num">起定量<input type="text" value="<?php echo ($urlParam['snum']); ?>" id="snum">以下</div>
        <a class="submit" id="sort_submit" href="javascript:;">确定</a>
    </form>
</div>
<!--产品列表-->
<div class="w clearfix goods-has">
    <?php if(is_array($goods)): foreach($goods as $key=>$val): ?><div class="item">
            <a href="/index.php/Mall/Goods?goods_id=<?php echo ($val["goods_id"]); ?>&org_id=<?php echo ($val["org_parent_id"]); ?>">
                <img class="lazy" data-original="<?php echo C('GOODS_IMG'); echo ($val["main_img"]); ?>">
            </a>
            <div class="price-w clearfix"><p class="price">￥<span><?php echo getPrice($val['goods_base_price']);?></span></p><!--<p class="font-gry">￥199</p>--></div>
            <p class="tit"><a href="/index.php/Mall/goods?goods_id=<?php echo ($val["goods_id"]); ?>&org_id=<?php echo ($val["org_parent_id"]); ?>"><?php echo ($val["goods_name"]); ?></a></p>
            <p class="company"><?php echo ($val["org_name"]); ?></p>
            <div class="clearfix info">
                <p class="fl"><i class="tag-ch"></i><?php echo ($val["reg_year"]); ?>年</p>
                <p class="font-gry">成交 <?php echo ($val["sales"]); ?>笔</p>
            </div>
        </div><?php endforeach; endif; ?>

</div>

<!--page-->
<!--分页查询开始-->
<?php echo W('Page/page',array("/index.php/Mall/Search", $pnum, $pagelist, $urlParam));?>
<!--分页查询结束-->


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

    // 品牌
    $('.brand').click(function () {
        var reg = /&brand=\d*$/g;

        var h = "<?php echo ($jump_url); ?>";

        if(reg.test(h)) {
            h = h.replace(reg, '');
        }

        //h = h.replace(reg, '&brand=' + $(this).attr('attr'));

        h += '&brand=' + $(this).attr('attr')

        location.href= h;
    })

    // 品类
    $('.cats').click(function () {
        var reg =/&cat=\d*$/g;

        var h = "<?php echo ($jump_url); ?>";

        console.log(reg.test(h))

        if(reg.test(h)) {
            h = h.replace(reg, "");
        }

        console.log(h)

        h += '&cat=' + $(this).attr('attr')

        location.href= h;
    })


    // 默认排序
    $("#comp").click(function () {
        var h = "<?php echo ($jump_url); ?>";

        var reg = /&order=\w*$/g;

        h = h.replace(reg, '');

        location.href= h;

    });

    // 销量排序
    $("#sales").click(function () {
        var reg = /&order=\w*$/g;

        var h = "<?php echo ($jump_url); ?>";

        //if(reg.test(h)) {
            h = h.replace(reg, '');
        //}
        var sort="<?php echo ($sort); ?>";
        if(sort=="desc"){
        	sort="asc"
        }else{
        	sort="desc"
        }
        h += '&order=sales&sort='+sort;

        location.href= h;

    });

    // 价格排序
    $("#price").click(function () {
        var reg = /&order=\w*$/g;

        var h = "<?php echo ($jump_url); ?>";

        //if(reg.test(h)) {
            h = h.replace(reg, '');
        //}
        var sort="<?php echo ($sort); ?>";
        if(sort=="desc"){
        	sort="asc"
        }else{
        	sort="desc"
        }
        h += '&order=price&sort='+sort;

        location.href= h;

    });

    $('#sort_submit').click(function () {
        var h = "<?php echo ($jump_url); ?>";

        var min_reg = /&price_min=\d*$/g;
        var max_reg = /&price_max=\d*$/g;
        var snum_reg = /&snum=\d*$/g;


        h = h.replace(min_reg, '');
        h = h.replace(max_reg, '');
        h = h.replace(snum_reg, '');

        // price_min

        var price_min = $("#sort_price_min").val();
        if (price_min.length > 0) {

            h += "&price_min=" + price_min;
        }

        // price_max

        var price_max = $("#sort_price_max").val();
        if (price_max.length > 0) {

            h += "&price_max=" + price_max;
        }

        // sale_num

        var snum = $("#snum").val();
        if (snum.length > 0) {

            h += "&snum=" + $("#snum").val();
        }


        location.href= h;
    });

    $(document).ready(function(e){
        //展开和隐藏
        $(".btnshow").click(function(e){
            $(".class-item").toggleClass("item-h");
            var shw = $(this).text();
            if(shw == "更多 +"){
                $(this).text("收起 -");
                $(this).css({"color":"#f85127","border-color":"#f85127"})
            }else{
                $(this).text("更多 +");
                $(this).css({"color":"#666","border-color":"#eee"})
            }
        })
    });
    
    $(function() {
		try{
			$("img.lazy").lazyload({effect: "fadeIn"});
		}
		catch(e){}
	});

    
</script>

</body>
</html>