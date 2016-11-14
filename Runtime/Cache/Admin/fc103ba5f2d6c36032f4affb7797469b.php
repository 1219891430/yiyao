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
<!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
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

                    <select id="depot_id" class="w200 form-control">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['repertory_id'] == $urlPara['depot_id']): ?><option selected="selected" value="<?php echo ($svo["repertory_id"]); ?>"><?php echo ($svo["repertory_name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($svo["repertory_id"]); ?>"><?php echo ($svo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                <select id="staff_id" class="w200 form-control">
                        <option value="0">请选择采购员</option>
                        <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['admin_id'] == $urlPara['staff_id']): ?><option selected="selected" value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>

                    <input type="text" readonly="readonly" value="<?php echo ($urlPara['start']); ?>"  class="form-control w100 cursor-pointer" id="start_time" placeholder="起始时间">
                    <input type="text" readonly="readonly" <?php if($urlPara['end'] != 0): ?>value="<?php echo ($urlPara['end']); ?>"<?php endif; ?> class="form-control w100 cursor-pointer" id="end_time" placeholder="结束时间">
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>

                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr>
                    <td width="20%">单据号</td>
                    <td>订单分类</td>
                    <td width="10%">采单员</td>
                    <td width="10%">时间</td>
                    <td width="10%">是否取消</td>
                    <td width="8%">是否备注</td>
                    <td width="15%">操作</td>
                </tr>
                </thead>
                <tbody>
                  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr id="tr_<?php echo ($list["order_id"]); ?>">
                        <td  style="text-align: center"><?php echo ($list["order_code"]); ?></td>
                        <td  style="text-align: center"><?php echo ($list["class_name"]); ?></td>
                        <td  style="text-align: center"><?php echo ($list["staff_name"]); ?></td>
                        <td  style="text-align: center"><?php echo date('Y-m-d H:i:s', $list['add_time']);?></td>
                        <td style="text-align: center"><?php if($list["is_cancel"] == 1): ?><span style="color: red">已取消(<?php echo date('Y-m-d H:i:s', $list['cancel_time']);?>)</span>  <?php else: ?> 正常<?php endif; ?></td>
                        <td style="text-align: center"><?php if(empty($list["order_remark"])): else: ?>  有<?php endif; ?>  </td>


                        <td style="text-align: center">
                            <ul class="operate-menu li-width22">

                                <li>
                                    <a class="collapsed collapse-menu icons-href staff" role="button"
                                       attr="<?php echo ($list["order_id"]); ?>"><i class="icon-edit"></i>采购人员&nbsp;&nbsp;
                                    </a>
                                </li>

                                <li>
                                    <a class="collapsed collapse-menu icons-href look" role="button"
                                       attr="<?php echo ($list["order_id"]); ?>"><i class="icon-edit"></i>查看&nbsp;&nbsp;
                                    </a>
                                </li>

                                <li>
                                    <?php if($list["is_cancel"] == 1): ?><li><a data-id="<?php echo ($list["order_id"]); ?>" class="collapsed collapse-menu icons-href open" href="javascript:void(0)"><i class="icon-remove-circle"></i>启用</a></li>
                                    <?php else: ?>
                                    <li><a data-id="<?php echo ($list["order_id"]); ?>" class="collapsed collapse-menu icons-href closed" href="javascript:void(0)"><i class="icon-remove-circle"></i>关闭</a></li><?php endif; ?>
                                </li>
                            </ul>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                </tbody>
                <tfoot></tfoot>
            </table>
        <!--分页查询开始-->
        <?php echo W('Page/page',array("/index.php/Admin/PurchaseOrder/index",$pnum,$pagelist,$aUrlPara));?>
        <!--分页查询结束-->
        </div>
    
    
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>

<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>

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
    $("#depot_id").change(function(){
        var depot_id = $(this).val();
        if(depot_id ==0){
            $("#staff_id").html('<option value=0>请选择业务员</option>');
            return;
        }
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getRoleStaff');?>",data:{ depot_id:depot_id,role_id:6  }, dataType:'json',timeout: 5000,
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

    $("#find").click(function(){
        //depot_id  staff_id  start_time
        var h = "/index.php/Admin/PurchaseOrder?"

        // depot_id
        h += "depot_id=" + $("#depot_id").val();

        // staff_id
        h += "&staff_id=" + $("#staff_id").val();

        // start_time
        h += "&start=" + $("#start_time").val();

        // end_time
            h += "&end=" + $("#end_time").val();

        location.href = h;
    });

    //查看
    $(".look").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/PurchaseOrder/look/r/" + new Date().getTime(),data);
    });

    $(".staff").click(function () {
        ajaxDataPara("/index.php/Admin/PurchaseOrder/set_staff/id/" + $(this).attr('attr') );
    });

    // 启用人员
    $(".open").click(function () {
        if (confirm("确定要启用吗？")) {
            var id = $(this).attr('data-id');
            var url = "<?php echo U('close');?>" + "?id=" + id + "&st=0";
            $.get(url, function(result){
                if(result == 1) {
                    alert("操作成功!");
                    location.reload(true);
                } else {
                    alert("操作失败");
                }
            });

        }})

    // 关闭人员
    $(".closed").click(function (){
        if (confirm("确定要关闭吗？")) {
            var id = $(this).attr('data-id');
            var url = "<?php echo U('close');?>" + "?id=" + id + "&st=1";
            $.get(url, function(result){
                if(result == 1) {
                    alert("操作成功!");
                    location.reload(true);
                } else {
                    alert("操作失败");
                }
            });
        }})
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