<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
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
<div class="zy-search">
    <div class="sch-w clearfix">
        <a class="go-back" href="#"><img src="/Public/assets/wap/images/go-back.png"></a>
        <div class="search">
            <button class="go-sch" id="search" href="#"></button>
            <input type="text" id="name1" placeholder="商品名或关键字">
        </div>
    </div>
    <a class="top-user" href="<?php echo U('WapMall/Member/index');?>"><img src="/Public/assets/wap/images/i-user.png"></a>
</div>
    <!--分类页-->
    <div class="wrap clearfix">
        <div class="cate-l">
            <ul>
                <?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li onclick="jump(<?php echo ($vo['class_id']); ?>)" <?php if($class_id == $vo['class_id']): ?>class="cur"<?php endif; ?> ><?php echo ($vo['class_name']); ?></li>

                    <div id="sub_<?php echo ($vo['class_id']); ?>" hidden>
                        <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><a class="animated" href="/index.php/WapMall/Search/search?cat=<?php echo ($sub["class_id"]); ?>"><?php echo ($sub['class_name']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="cate-r">
            <div class="cate-tit"><span>&nbsp;&nbsp;热门分类&nbsp;&nbsp;</span></div>
            <div class="cate-box clearfix" id="subclass">
                <!--<a class="animated tot" href="/index.php/WapMall/Search/search?cat=<?php echo ($first_class["class_id"]); ?>"><?php echo ($first_class['class_name']); ?></a>--><!--第一个链接到一级分类-->
                <?php if(is_array($first_class['child'])): $i = 0; $__LIST__ = $first_class['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><a class="animated" href="/index.php/WapMall/Search/search?cat=<?php echo ($sub["class_id"]); ?>"><?php echo ($sub['class_name']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>

            </div>
            <div class="cate-tit"><span>&nbsp;&nbsp;热门品牌&nbsp;&nbsp;</span></div>
            <div class="cate-brand clearfix">
                <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vob): $mod = ($i % 2 );++$i;?><a class="brand" href="/index.php/WapMall/Search/search?brand=<?php echo ($vob["brand_id"]); ?>"><img class="lazy" data-original="<?php echo C('BRAND_IMG'); echo ($vob["brand_logo"]); ?>"></a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>

    <!--底部悬浮按钮-->
<div class="foot-btn">
    <a href="/index.php/WapMall/index"><i class="nlh-home"></i>首页</a>
    <a class="active" href="/index.php/WapMall/Index/nav"><i class="nlh-ct"></i>分类</a>
    <a href="/index.php/WapMall/Cart"><i class="nlh-cart"></i>进货单</a>
</div>



<!--loading start-->
<div class="loading_box"></div>
<!--loading end-->
<script>

    $(function() {
		try{
			$("img.lazy").lazyload({effect: "fadeIn"});
		}
		catch(e){}
	});

    $("#search").click(function(){
    	var name=$("#name1").val();
    	name=$.trim(name)
     	if(name==""){
    		alert("搜索条件不能为空");
    		return;
    	}
    	location.href="/index.php/WapMall/Search/search/word/"+name;
    })
    
    //导航页
    var wH = $(window).height();
    var ctLH = wH - $(".foot-btn").height() - $(".zy-search").height();
    $(".cate-l").css("min-height",ctLH);

    $(".go-back").click(function () {
        history.go(-1)
    })

    var jump = function (class_id) {
        var html = $("#sub_"+class_id).html()
        //console.log(html)
        $("#subclass").html(html)
    }
</script>

</body>
</html>