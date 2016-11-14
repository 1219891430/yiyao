<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>经销商后台-北极光抓单宝</title>
<link href="/yiyao/Public/assets/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/yiyao/Public/assets/js/jquery-1.7.2.min.js"></script>
</head>
<body>


<div class="login">
    <div class="login-box">
        <div class="l_box">
            <img src="/yiyao/Public/assets/images/logo-login.png">
        </div>
        <div class="d_box">
            <form name="user_login" method="post" action="<?php echo U('login');?>" onSubmit="return doSubmit_user_login()">
                <h2><span>登录账号：</span><input name="l_name" class="l_name" id="name" type="text" placeholder="登录账号"></h2>
                <h2><span>登录密码：</span><input name="l_pwd" class="l_pwd" id="pwd" type="password" placeholder="登录密码"></h2>
                <h2><span>　验证码：</span><input name="verify" id="verify" class="" style="width:100px;padding-left: 15px" placeholder="验证码" type="text">
                    <img style="cursor: pointer" id="logo_verify" width="80px" height="30px" src="/yiyao/index.php/Dealer/Index/verify"></h2>
                <h2><input class="st" value="登录" type="submit"></h2>
            </form>
			<!--
            <div class="code-ctrl"></div>
            <div class="code-box">
                <img class="ewm_img" src="/yiyao/Public/assets/images/ewm.jpg" width="120px;">
                <span style="display:block">扫一扫,下载手机客户端!</span>
            </div>
			-->
        </div>
    </div>
</div>

</body>
<script type="text/javascript">$(function(){$(".login").height($(window).height()-$(".top").height()-1);})</script>
<script language="javascript">
$("#logo_verify").click(function(){ $(this).attr("src","/yiyao/index.php/Dealer/Index/verify/tm/"+new Date().getTime()); });
function doSubmit_user_login()
{
	var Notnull;
	Notnull=true;
	if (document.user_login.l_name.value=="")
	{
		window.document.user_login.l_name.focus();
		alert("请填写用户名！");
		Notnull=false;
	}
	else if (document.user_login.l_pwd.value=="")
	{
		window.document.user_login.l_pwd.focus();
		alert("请填写密码！");
		Notnull=false;
	}
    else if(document.user_login.verify.value=="")
    {
        window.document.user_login.verify.focus();
        alert("请输入验证码！");
        Notnull=false;
    }
	return Notnull;
}
    if(typeof($(".code-ctrl").attr("display")=="block")){
        $(".code-ctrl").click(function(){
            $(".code-ctrl").css("display","none");
            $(".code-box").css("display","block");
        })
    }
    if(typeof($(".code-box").attr("display")=="block")){
        $(".code-box").click(function(){
            $(".code-box").css("display","none");
            $(".code-ctrl").css("display","block");
        })
    }

</script>
</html>