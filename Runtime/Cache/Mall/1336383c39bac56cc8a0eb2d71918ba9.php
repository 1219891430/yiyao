<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>B2B批发商城</title>

    <link rel="stylesheet" href="/yiyao/Public/assets/css/mall/style.css">
    <link rel="stylesheet" href="/yiyao/Public/assets/css/mall/animate.min.css">
    <script type="text/javascript" src="/yiyao/Public/assets/js/mall/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/yiyao/Public/assets/js/mall/shop-nav.js"></script>
    <script type="text/javascript" src="/yiyao/Public/assets/js/mall/slide.js"></script>
    <script type="text/javascript" src="/yiyao/Public/assets/js/mall/app.js"></script>
    <script type="text/javascript" src="/yiyao/Public/assets/js/goods.js"></script>
    <script src="/yiyao/Public/assets/js/jquery.lazyload.js"></script>

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
                <img src="/yiyao/Public/assets/images/mall/logo.png">
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
            <div class="navCon-cate-title"><a href="#"><img src="/yiyao/Public/assets/images/mall/nav-tit.png"></a></div>
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
                <li><a href="/yiyao/index.php/Mall">首页</a></li> <!--当前栏目，加class="curMenu"-->
                <li><a href="/yiyao/index.php/Mall/Member/index">个人中心</a></li>
            </ul>
        </div>
    </div>
</div>
<!--导航 end-->


<style>
    .headNav .navCon .navCon-cate .cateMenu{height:460px;overflow: hidden;}
</style>
<!-- banner start -->
<div class="banner">
    <div class="swap">
        <ul id="slider">
            <li style="background:url(/yiyao/Public/assets/images/mall/nav1-bg.jpg) center center no-repeat #00e0bc;">
                <div class="s-banner-con">
                    <img class="nav1-wz animated fadeInLeft" src="/yiyao/Public/assets/images/mall/nav1-1.png">
                    <img class="nav1-pic animated fadeInRight" src="/yiyao/Public/assets/images/mall/nav1-2.png">
                    <a href="#" target="_blank"></a>
                </div>
            </li>
            <li style="background:url(/yiyao/Public/assets/images/mall/nav1-bg.jpg) center center no-repeat #00e0bc;">
                <div class="s-banner-con">
                    <img class="nav1-wz animated fadeInLeft" src="/yiyao/Public/assets/images/mall/nav1-1.png">
                    <img class="nav1-pic animated fadeInRight" src="/yiyao/Public/assets/images/mall/nav1-2.png">
                    <a href="#" target="_blank"></a>
                </div>
            </li>
        </ul>
    </div>
    <ul id="naviSlider">
        <li sindex="1" class="on"></li>
        <li sindex="2"></li>
    </ul>
</div>
<!-- banner end -->
<!--特色推荐 start-->
<div class="slide-r">
    <div class="slide-r-box">
        <div class="slide-r-cont1">
            <h3>我要加盟</h3>
            <div class="slide-r-go">
                <a href="">我有仓库</a>
                <a href="">我有产品</a>
                <a href="">我有商店</a>
                <a href="">我有人员</a>
                <a href="">我有物流</a>
                <a href="">我有资源</a>
            </div>
            <a class="slide-r-btn" href="<?php echo U('mall/join/index');?>">申请加盟入驻</a>
        </div>
        <div class="slide-r-cont2">
            <h3>今日推荐</h3>
            <div class="slide-r-pic">
                <a href="#">
                    <img src="images/pic.jpg" width="100" height="100">
                    <p>厂商批发 冻干太空零食 无添加</p>
                </a>
            </div>
        </div>
    </div>
</div>

<!--品牌-->
<div class="ind-tit w">人气品牌</div>
<div class="floor1 w clearfix">
    <div class="flr1-l">
        <img src="/yiyao/Public/assets/images/mall/pic2.jpg" width="223" height="358">
        <p>人气品牌街<br><small>Famous Brand</small></p>
    </div>
    <div class="flr1-r">
        <ul>

            <?php if(is_array($brands)): $i = 0; $__LIST__ = array_slice($brands,0,15,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/index.php/Mall/Search?brand=<?php echo ($vo["brand_id"]); ?>"><img class="lazy" data-original="<?php echo C('BRAND_IMG'); echo ($vo["brand_logo"]); ?>"><p><?php echo ($vo['brand_name']); ?></p></a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>

<!--楼层-->
<div class="ind-tit w">热销百货市场</div>
<div class="floor w clearfix">
    <div class="flr-l">
        <div class="flr-t">
            <img src="/yiyao/Public/assets/images/mall/pic3.jpg" width="232" height="377">
        </div>
        <div class="flr-f">
            <div class="ind-l-nav">
                <?php if(is_array($classes)): $i = 0; $__LIST__ = array_slice($classes,0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?cat=<?php echo ($class["class_id"]); ?>"><?php echo ($class["class_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="brand2">
                <?php if(is_array($brands)): $i = 0; $__LIST__ = $brands;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="/index.php/Mall/Search?brand=<?php echo ($vo["brand_id"]); ?>">
                        <img class="lazy" width="180px" height="70px" data-original="<?php echo C('BRAND_IMG'); echo ($vo["brand_logo"]); ?>">
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <div class="flr-r">
        <div class="flr-t">
            <a class="flr-tit" href="#">热销单品</a>
            <div class="clearfix item-box">

                <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item">
                        <div class="brd">
                            <a href="/index.php/Mall/Search?word=<?php echo ($vo["goods_name"]); ?>">
                                <img class="lazy" data-original="<?php echo C('GOODS_IMG'); echo ($vo["main_img"]); ?>">
                            </a>
                            <p class="tit"><a href="/index.php/Mall/Search?word=<?php echo ($vo["goods_name"]); ?>"><?php echo ($vo["goods_name"]); ?></a></p>
                            <p class="price">￥<span><?php echo getPrice($vo['goods_base_price']);?></span></p>
                        </div>
                        <span class="info"><?php echo ($vo["wholesale_num"]); ?> <?php echo ($vo["goods_unit"]); ?>起批</span>
                        <!--<p class="font-gry">参考零售价:￥<?php echo ($vo["goods_base_price"]*$per_price); ?></p>-->
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <!--<div class="flr-f">
            <a class="flr-tit" href="#">热门商家</a>

            <div class="clearfix item-box">
                <div class="item">
                    <a href="#">
                        <img src="/yiyao/Public/assets/images/mall/pic.jpg">
                        <i class="i-store"></i>
                        <p class="tit">河北泽农信息科技有限公司</p>
                    </a>
                </div>

            </div>
        </div>-->
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
            <img src="/yiyao/Public/assets/images/mall/chg-ft.jpg">
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
        <p><a href="/yiyao/index.php/Mall/index.html">首页</a> |
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
    var MODEL_NAME = '<?php echo ACTION_NAME;?>';
    $(document).ready(function(){
        //幻灯
        $("#slider").jSlider({
                    pause:10000,
                    naviSlider:'naviSlider'
                }
        );
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