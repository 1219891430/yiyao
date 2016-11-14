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
<!--[if lt IE 9]>
<script src="/Public/assets/js/html5shiv.min.js"></script>
<script src="/Public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
<script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
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

    <div class="main-right container-fluid">
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
    <div class="row-fluid main-content">
        <!--右侧查询开始-->
            <div class="sel-data mb20">
                <div class="fl">
                    <select name="query[repertory_id]" id="repertory_id" class="form-control w200">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["repertory_id"] == $query['repertory_id']): ?><option selected="selected" value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option>
                                <?php else: ?><option value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select id="staff_id" class="w150 form-control">
                        <option value="0">请选择业务员</option>
                        <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["admin_id"] == $query['staff_id']): ?><option selected="selected" value="<?php echo ($vo["admin_id"]); ?>"><?php echo ($vo["true_name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($vo["admin_id"]); ?>"><?php echo ($vo["true_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <input type="text" class="w150 form-control" id="cust_name" name="cust_name" value="<?php echo ($query['cust_name']); ?>" placeholder="请输入购货方">
                    <input type="text" readonly="readonly" <?php if($query['start_time'] != 0): ?>value="<?php echo ($query['start_time']); ?>"<?php endif; ?>  class="form-control w100 cursor-pointer" id="start_time"
                    placeholder="起始时间">
                    <input type="text" readonly="readonly" <?php if($query['end_time'] != 0): ?>value="<?php echo ($query['end_time']); ?>"<?php endif; ?> class="form-control w100 cursor-pointer" id="end_time"
                    placeholder="结束时间">
                    <a class="btn btn-default" href="javascript:void(0)" id="find" role="button">查询</a>
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr><td width="10%">单据编号</td>
                    <td width="13%">商铺名称</td>
                    <td width="8%">联系人</td>
                    <td width="8%">业务员</td>
                    <td width="8%">金额（￥）</td>
                    
                    <td width="12%">时间</td>
                    <td width="10%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($vo["return_code"]); ?></td>
                        <td><?php echo ($vo["cust_name"]); ?></td>
                        <td><?php echo ($vo["cust_contact"]); ?></td>
                        <td><?php echo ($vo["true_name"]); ?></td>
                        <td><?php echo ($vo["total_money"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
                        <td><ul class="operate-menu li-width50">
                            <li><a attr="<?php echo ($vo["return_id"]); ?>" class="collapsed collapse-menu icons-href detail" href="javascript:void(0)">
                                <i class="icon-edit"></i>查看
                            </a></li>
                            <!--<li><a href="<?php echo U('CarSalesReturn/detail',array('code'=>$vo['return_id'],'state' =>'print','del' => $_GET['del']));?>" target="_blank">打印</a></li>-->
                        </ul></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
            <?php echo W('Page/page',array("/index.php/Admin/DeliverReturn/index",$pnum,$pagelist,$query));?>
            <!--分页查询结束-->
        </div>
    
    
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<!--新建弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con"  class="modal-dialog modal_850 "></div>
</div>
<!--新建弹出层结束-->
<script type="text/javascript">
    $(function(){
        $("#start_time,#end_time").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 2014,//年份的开始默认为1949
            endY :2049//年份的结束默认为2049
        });
    });

    //部门联动
    $("#repertory_id").change(function(){
        var repertory_id = $(this).val();
        if(repertory_id ==0){
            $("#staff_id").html('<option value=0>请选择业务员</option>');
            return;
        }
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getRoleStaff');?>",data:{depot_id:repertory_id,role_id:5 }, dataType:'json',timeout: 5000,
            error: function(){
            },
            success: function($r){
                $("#staff_id").html('<option value=0>请选择业务员</option>');
                if($r.status){
                    var html = '<option value=0>请选择业务员</option>';
                    $.each($r.rows,function(index,item){
                        html+= '<option value="'+item.admin_id+'">'+item.true_name+'</option>';
                    });
                    $("#staff_id").html(html);
                }
            }
        });
    });

    //查询
    $("#find").click(function() {
        var con = "";
        if ($("#repertory_id").val() != '0') {
            con += "/repertory_id/" + $("#repertory_id").val();
        }
        if ($("#staff_id").val() != 0) {
            con += "/staff_id/" + $("#staff_id").val();
        }
        if ($('#cust_name').val() != '') {
            con += '/cust_name/'+$('#cust_name').val();
        }
        if ($('#start_time').val() != '') {
            con += '/start_time/'+$('#start_time').val();
        }
        if ($('#end_time').val() != '') {
            con += '/end_time/'+$('#end_time').val();
        }
        location.href="/index.php/Admin/DeliverReturn/index"+con;
    });
    $(".detail").click(function(){
    	var return_id=$(this).attr("attr");
    	var data={'return_id':return_id}
    	
        ajaxDataPara("/index.php/Admin/DeliverReturn/detail",data);
    });
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