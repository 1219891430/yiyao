<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>北极光-抓单宝</title>
    <link href="/Public/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/Public/assets/css/style.css" rel="stylesheet">
    <link href="/Public/assets/css/font-awesome.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery-ui.min.js"></script>
    <link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
     <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
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

<div class="main-container" id="main-container">
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

    <div class="main-right container-fluid">
        <!--右侧菜单导航开始-->
        <div class="r-sub-nav row-fluid">
            <?php
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
                  
                    <input type="hidden" id="check_org" value="<?php echo ($org_id); ?>">
                    <input type="hidden" id="check_dep" value="<?php echo ($dep_id); ?>">
      
                    <select id="staff_id" class="w200 form-control" name="staff_id">
                        <option value="0">业务员</option>
                        <?php if(is_array($aStaff)): $i = 0; $__LIST__ = $aStaff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ivo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ivo["admin_id"]); ?>"><?php echo ($ivo["true_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select id="is_cancel" class="w100 form-control" name="staff_id">
                    	<?php if($urlPara["is_cancel"]){ ?>
                    		<option value="0" >未撤销</option>
                    		<option value="1" selected="selected">已撤销</option>
                    		
                    	<?php }else{ ?>
                    		<option value="0" selected="selected">未撤销</option>
                    		<option value="1" >已撤销</option>
                    	<?php } ?>
                        
                        
                    </select>
                    <select id="is_full_pay" class="w100 form-control" name="staff_id">
                    	<?php if($urlPara["is_full_pay"]){ ?>
                    		<option value="0" >未结清</option>
                    		<option value="1" selected="selected">已结清</option>
                    		
                    	<?php }else{ ?>
                    		<option value="0" selected="selected">未结清</option>
                    		<option value="1" >已结清</option>
                    	<?php } ?>
                        
                        
                    </select>

                    <input type="text" class="w150 form-control" id="shopid" placeholder="请输入店铺名称">

                    <input type="text" class="w150 form-control" readonly="readonly" <?php if($urlPara['start'] != 0): ?>value="<?php echo ($urlPara['start']); ?>"<?php endif; ?>  class="form-control w100 cursor-pointer" id="start_time"
                    placeholder="起始时间">
                    <input type="text" class="w150 form-control" readonly="readonly" <?php if($urlPara['end'] != 0): ?>value="<?php echo ($urlPara['end']); ?>"<?php endif; ?> class="form-control w100 cursor-pointer" id="end_time"
                    placeholder="结束时间">

                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>

                </div>
                <div class="fr">
                <a class="btn btn-primary bg_3071a9" href="javascript:void(0)" id="explode" role="button">导出</a>
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table">
                <thead>
                <tr>
                    <td width="10%">赊款店铺</td>
                    <td width="10%">订单编号</td>
                    <td width="10%">订单金额</td>
                    <td width="10%">赊款金额</td>
                    <td width="8%">赊款订单时间</td>
                    <td width="8%">业务员</td>
                    <td width="10%">操作</td>
                </tr>
                </thead>
                <tbody id="content">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qian): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($qian["cust_name"]); ?></td>
                        <td><?php echo ($qian["order_code"]); ?></td>
                        <td><?php echo ($qian["order_total_money"]); ?></td>
                        <td><?php echo ($qian["qiankuan"]); ?></td>
                        <td><?php echo ($qian["create_time"]); ?></td>
                        <td><?php echo ($qian["true_name"]); ?></td>
                        <td>
                        <a class="detail" attr="<?php echo ($qian["order_id"]); ?>" href="#">订单详情</a>&nbsp&nbsp&nbsp
                        <a href="<?php echo U('QDetails',array('order_id'=>$qian['order_id']));?>">清欠明细</a>&nbsp&nbsp&nbsp
                        <?php if($qian["is_cancel"]==0&&$qian["is_full_pay"]==0){ ?>
                        <a id="<?php echo ($qian["order_id"]); ?>" class="chexiao">撤销</a>&nbsp&nbsp&nbsp
                        <a id="<?php echo ($qian["order_id"]); ?>" class="qingqian">清欠</a>&nbsp&nbsp&nbsp
                        <?php } ?>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
            <?php echo W('Page/page',array("/index.php/Admin/SheQian/index",$pnum,$pagelist,$urlPara));?>
            
            <!--分页查询结束-->
        </div>
    </div>
</div>
<div id="await" class="await waits">
        <span> <img src="_/Public/assets/images/loding.gif" title="加载图片"/></span>
    </div>
    <!--新建弹出层开始-->
    <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="modal-con"  class="modal-dialog modal_850 ">
        	
        </div>
    </div>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script language="javascript" src="/Public/assets/js/PCASClass.js"></script>
<script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>
<script type="text/javascript">

    $(".detail").click(function(){
    	
    	
    		
    	
    	
    	var data={order_id:$(this).attr("attr")}
    
        
        ajaxDataPara("/index.php/Admin/SheQian/detail",data);
    })

    $("#explode").click(function(){

        var is_cancel=$("#is_cancel").val();
        var is_full_pay=$("#is_full_pay").val();
        var start=$("#start_time").val()==""?0:$("#start_time").val()
        var end=$("#end_time").val()==""?0:$("#end_time").val()
        if((start==0&&end!=0)||(start!=0&&end==0))
            alert("请点击选择起始时间跟结束时间")
        else{
            shopid = $("#shopid").val();
            if(shopid == ""){
                shopid=0;
            }
            location.href="/index.php/Admin/SheQian/index/explode/explode/is_full_pay/"+is_full_pay+"/is_cancel/"+is_cancel+"/shopid/"+shopid+"/staff_id/"+$("#staff_id").val()+"/start_time/"+start+"/end_time/"+end;
        }
    })


    $(function () {
        $("#start_time,#end_time").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 2000,//年份的开始默认为1949
            endY :2049//年份的结束默认为2049
        });
     $("#find").click(function(){
        var is_cancel=$("#is_cancel").val();
        var is_full_pay=$("#is_full_pay").val();
        var start=$("#start_time").val()==""?0:$("#start_time").val()
        var end=$("#end_time").val()==""?0:$("#end_time").val()
        if((start==0&&end!=0)||(start!=0&&end==0))
            alert("请点击选择起始时间跟结束时间")
        else{
            shopid = $("#shopid").val();
            if(shopid == ""){
                shopid=0;
            }
            location.href="/index.php/Admin/SheQian/index/is_full_pay/"+is_full_pay+"/is_cancel/"+is_cancel+"/shopid/"+shopid+"/staff_id/"+$("#staff_id").val()+"/start_time/"+start+"/end_time/"+end;
        }
     })
        
        $(".chexiao").click(function(){
        	//href="<?php echo U('chexiao',array('orderId'=>$qian['order_id']));?>";
        	if(confirm("确定要撤销吗")){
        		var id=$(this).attr("id");
        		var data={'order_id':id};
        		$.post("/index.php/Admin/SheQian/chexiao",data,function(res){
        			if(res){
        				alert("撤销成功!");
        				location.reload();
        			}else{
        				alert("撤销失败!");
        			}
        		});
        	}
        	
        	
        });
        $(".qingqian").click(function(){
        	
        	var id=$(this).attr("id");
        	ajaxDataPara("/index.php/Admin/SheQian/qingqian",{"id":id});
        });
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