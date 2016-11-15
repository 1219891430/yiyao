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
            登录
        </div>
    </div>
</div>
<div class="login-w">
    <!--登录-->
    <form method="post" action="/index.php/WapMall/Member/login">
        <div class="form-group clearfix">
            <i class="i-user"></i>
            <input class="bd-b" name="user_name" type="text" placeholder="请输入手机号">
        </div>
        <div class="form-group clearfix">
            <i class="i-pwd"></i>
            <input type="password" name="user_pass" placeholder="请输入密码">
        </div>
        <input type="submit" class="btn-brand">
        <div class="find-pwd clearfix">
            <!--<a href="#">找回密码</a>-->
        </div>
    </form>
</div>


<!--loading start-->
<div class="loading_box"></div>
<!--loading end-->


<script>
    // 弹框
    function toggleCard() {
        $('.modal').slideToggle(300);
    }
    $('.sku-control-box,.btn-close').click(toggleCard);

    $(".go-back").click(function () {
        history.go(-1)
    })
</script>

</body>
</html>