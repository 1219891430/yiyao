<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>北极光抓单宝系统后台</title>
<link href="/Public/assets/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
</head>
<body>

<div class="login">
    <div class="login-box">
        <div class="l_box">
            <img src="/Public/assets/images/logo-login.png">
        </div>
        <div class="d_box" style="margin-top: 160px;">
            <form name="user_login" method="post" action="<?php echo U('login');?>" onSubmit="return checkLogin()">
                <?php if(!empty($error)): ?><h2 style="text-align:center; color:#FF0000"><?php echo ($error); ?></h2><?php endif; ?>
                <h2><span>登录账号：</span><input name="l_name" class="l_name" type="text" placeholder="登录账号" /></h2>
                <h2><span>登录密码：</span><input name="l_pwd" class="l_pwd" type="password" placeholder="登录密码" /></h2>
                <h2><input class="st f14 fb" value="登录" type="submit" /></h2>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">$(function(){$(".login").height($(window).height()-$(".top").height()-1);})</script>
<script type="text/javascript">
function checkLogin()
{
	username = $("input[name='l_name']").val();
	password = $("input[name='l_pwd']").val();
	if(username == '' || password == '')
	{
		alert('请输入账号和密码');
		return false;
	}
	else
	{
		return true;
	}
}
</script>
<script src="/Public/assets/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/timer.js"></script>
<script type="text/javascript">

    $(function(){
        var warning_sound_num = $.cookie('warning_sound_num');
        //console.log("warning_sound_num:" + warning_sound_num)

        var i=warning_sound_num;

        $('body').everyTime('8s',function(){
            i--
            if(i>=0){
                playAudio()
                $.cookie('warning_sound_num',i)
                console.log($.cookie('warning_sound_num'))
            }

        },warning_sound_num);

    });

    function playAudio() {
        $('<audio id="chatAudio"><source src="/Public/assets/sound/zhuoling.wav" type="audio/mpeg"></audio> ').appendTo('body');//载入声音文件

        $('#chatAudio')[0].play(); //播放声音
    }
</script>
</body>
</html>