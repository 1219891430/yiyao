<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>农乐汇-抓单宝</title>
    <link href="/Public/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/Public/assets/css/style.css" rel="stylesheet">
    <link href="/Public/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
    <!-- <script type="text/javascript" src="/Public/js/jquery-messages_cn.js"></script> -->
</head>
<body>
<div id="top">
<div class="navbar">
<div class="navbar-inner">
<div class="logo"><img src="/Public/assets/images/logoG.png" /></div>
<ul class="pull-right navInfo">
    <?php if($_SESSION['depot_id'] > 0): ?><li><a href="<?php echo U('Admin/GoodsWarning/warning_view');?>" class="head_goods_warning" id="head_warning">预警提示
        <?php if(!empty($_COOKIE['warning_count'])): ?><span class="badge bg_red"><?php echo ($_COOKIE['warning_count']); ?></span><?php endif; ?>
    </a></li><?php endif; ?>
    <li><a href='javascript:void(0)' id="refresh_cache">刷新缓存</a> </li>
    <li class="login_info">
        <span><a href="<?php echo U('Admin/Index/logout');?>">退出</a></span>
    </li>
    <script type="text/javascript">
        $("#refresh_cache").click(function () {
            $.get("<?php echo U('Admin/Index/refreshCache');?>",{},function(res){
            	if(res==1){
            		alert("清除成功")
            	}
            });
        })

    </script>
</ul>
</div>
</div>
</div>

<div class="main-container">
    <ul class="main-left nav nav-stacked" id="main_left">
	<!-- 菜单 -->
    <?php if(is_array($_SESSION['menu'])): $y = 0; $__LIST__ = $_SESSION['menu'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$leftvo): $mod = ($y % 2 );++$y;?><li class="dropdown">
    <a href="javascript:void(0)" class="collapsed collapse-menu"><i class="left-bg <?php echo ($leftvo["icon"]); ?>"></i><span><?php echo ($leftvo["name"]); ?></span></a>
    <ul class="main-left-menu">
    <?php if(is_array($leftvo["subclass"])): $i = 0; $__LIST__ = $leftvo["subclass"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$leftsubvo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Admin/'.$leftsubvo['controller'].'/'.$leftsubvo['action']);?>"><span><?php echo ($leftsubvo["subname"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>
    <li></li>
</ul>

    <div class="main-right">
        <!--右侧菜单导航开始-->
        <div class="r-sub-nav row-fluid "><?php
 $menuID = 0; foreach($_SESSION['menu'] as $k=>$v) { foreach($v['subclass'] as $val) { if($val['controller']==CONTROLLER_NAME){ $menuID = $k; break; } } if($menuID > 0) { break; }; } $sub_memu = $_SESSION['menu'][$menuID]['subclass']; ?>

<!-- 子菜单 -->

<?php if(is_array($sub_memu)): $i = 0; $__LIST__ = $sub_memu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rightsubvo): $mod = ($i % 2 );++$i;?><dl>
    <dd <?php if((CONTROLLER_NAME == $rightsubvo['controller']) AND (ACTION_NAME == $rightsubvo['action'])): ?>class="selected"<?php endif; ?> >
    <a href="<?php echo U('Admin/'.$rightsubvo['controller'].'/'.$rightsubvo['action'].'');?>"><?php echo ($rightsubvo["subname"]); ?></a>
    </dd>
    <dt></dt>
    </dl><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
        <!--右侧菜单导航结束-->
        <div class="row-fluid main-content">
            <!--右侧查询开始-->
            <div class="sel-data mb20">
                <div class="fl">
                    <!-- 
                    <select id="status" class="w100 form-control">
                        <option value="3" <?php if($status==3){ ?>selected="selected"<?php } ?>>全部状态</option>
                        <option value="0" <?php if($status==0){ ?>selected="selected"<?php } ?>    >未对账</option>
                        <option value="1" <?php if($status==1){ ?>selected="selected"<?php } ?>>已对账</option>
                    </select>
                    
                    <input type="text" readonly="readonly" value="<?php echo ($date); ?>"  class="form-control w200 cursor-pointer" id="date"
                    placeholder="时间">
                    
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>
                     -->
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table">
                <thead>
                <tr>
               
                
                    <td width="16%">业务员</td>
                    <td width="16%">上次对账时间</td>
                    <td width="16%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                       <!--  <td><?php echo ($vo["order_code"]); ?></td> -->
                        
                        <td><?php echo ($vo["staff_name"]); ?></td>
                        <td><?php echo (date("Y-m-d H:i:s",$vo["lastCheck"])); ?></td>
                       
                        <td>
                        	<a staff="<?php echo ($vo["staff_id"]); ?>" start="<?php echo ($vo["start"]); ?>" status="0" class="collapsed collapse-menu icons-href chexiaoduizhang" href="javascript:void(0)">
                                    <i class="icon-edit"></i>对账
                               </a> &nbsp; &nbsp; &nbsp; &nbsp;
                               <!--<a staff="<?php echo ($vo["staff_id"]); ?>" start="<?php echo ($vo["start"]); ?>" status="0" class="collapsed collapse-menu icons-href print" href="javascript:void(0)">
                                    <i class="icon-edit"></i>打印
                               </a> &nbsp; &nbsp; &nbsp; &nbsp;
                               <a staff="<?php echo ($vo["staff_id"]); ?>" start="<?php echo ($vo["start"]); ?>" status="0" class="collapsed collapse-menu icons-href export" href="javascript:void(0)">
                                    <i class="icon-edit"></i>导出
                               </a> &nbsp; &nbsp; &nbsp; &nbsp;-->
                                
                             <!--<span><a href="<?php echo U('CarSalesCheck/showHistoryDuizhang',array('state'=>'print','id'=>$vo['duizhang_id']));?>" target="_blank">打印</a></span>
                             &nbsp; &nbsp;&nbsp; &nbsp;
                             <span><a href="<?php echo U('CarSalesCheck/showHistoryDuizhang',array('state'=>'export','id'=>$vo['duizhang_id']));?>" target="_blank">导出</a></span>-->
                             
                              
                              <a staff="<?php echo ($vo["staff_id"]); ?>" class="collapsed collapse-menu icons-href history" href="javascript:void(0)">
                                    <i class="icon-edit"></i>历史对账
                               </a>
                         </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            
        </div>
    </div>
</div>
<div id="await" class="await">
    <span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_1050">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script type="text/javascript">
    $(function(){
        $("#date").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 2014,//年份的开始默认为1949
            endY :2049//年份的结束默认为2049
        });
    })
    $(".history").click(function(){
    	var staff=$(this).attr("staff");
    	location.href="/index.php/Admin/DeliverSummary/history/staff/"+staff;
    });
    $(".check_detail").click(function(){
        var data={code:$(this).attr("attr"),status:$(this).attr("attr_status")};
        ajaxDataPara("/index.php/Admin/DeliverSummary/detail",data);
    });
    
    
   $(".chexiaoduizhang").click(function(){
	   var staff_id=$(this).attr("staff");
	   location.href="/index.php/Admin/DeliverSummary/duizhang/staff/"+staff_id;  
   });
   
   $(".export").click(function(){
	   var staff_id=$(this).attr("staff");
	   location.href="/index.php/Admin/DeliverSummary/chexiaoduizhang/export/export/staff/"+staff_id;  
   });
       
  
    $(".print").click(function(){
	   var staff_id=$(this).attr("staff");
	   location.href="/index.php/Admin/DeliverSummary/chexiaoduizhang/print/print/staff/"+staff_id;  
   });
    
    
    
    $("#find").click(function(){

        var date=$("#date").val()==""?0:$("#date").val()     
        location.href="/index.php/Admin/DeliverSummary/index/date/"+date;
    })
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