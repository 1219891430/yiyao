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
    <script type="text/javascript" src="/Public/assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
<!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
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
                        <option value="0">请选择业务员</option>
                        <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['admin_id'] == $urlPara['staff_id']): ?><option selected="selected" value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>

                    <input type="text" readonly="readonly" <?php if($urlPara['start'] != 0): ?>value="<?php echo ($urlPara['start']); ?>"<?php endif; ?>  class="form-control w100 cursor-pointer" id="start_time"
                    placeholder="起始时间">
                    <input type="text" readonly="readonly" <?php if($urlPara['end'] != 0): ?>value="<?php echo ($urlPara['end']); ?>"<?php endif; ?> class="form-control w100 cursor-pointer" id="end_time"
                    placeholder="结束时间">
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>
                </div>
                <div class="fr">
                    <a class="btn btn-primary bg_3071a9" href="javascript:void(0)" id="cre_apply" role="button">创建</a>
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr><td width="15%">单据编号</td>
                    <!--<td width="13%">供货方</td>-->
                    <!--<td width="7%">联系人</td>-->
                    <td width="10%">业务员</td>
                    <td width="15%">仓库</td>
                    <td width="10%">状态</td>
                    <td width="10%">时间</td>
                    <td width="20%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($vo["apply_code"]); ?></td>
                        <!--<td><?php echo ($vo["supp_name"]); ?></td>-->
                        <!--<td><?php echo ($vo["supp_contact"]); ?></td>-->
                        <td><?php echo ($vo["true_name"]); ?></td>
                        <td><?php echo ($vo["repertory_name"]); ?></td>
                        <td>
                            <?php switch($vo["apply_status"]): case "1": ?>已提交<?php break;?>
                                <?php case "2": ?><span style="color: green">已审核</span><?php break;?>
                                <?php case "3": ?><span style="color: red">已出库</span><?php break;?>
                                <?php default: ?>其他<?php endswitch;?>

                        </td>
                        <td><?php echo (date('Y-m-d H:i:s', $vo["add_time"])); ?></td>
                        <td>

                        <ul class="operate-menu li-width50">


                            <li style="width:15%">
                                <a attr="<?php echo ($vo["apply_id"]); ?>" class="collapsed collapse-menu icons-href apply_look" href="javascript:void(0)">
                            <i class="icon-edit"></i>查看
                            </a></li>
                            
                            <?php if($vo["apply_status"]==1){ ?>
                            <li style="width:15%"><a attr="<?php echo ($vo["apply_id"]); ?>" attr_status="2" class="collapsed collapse-menu icons-href apply_edit" id="edit" href="javascript:void(0)">
                                <i class="icon-edit"></i>修改
                            </a></li>
                             <li style="width:15%"><a attr="<?php echo ($vo["apply_id"]); ?>" attr_status="3" class="collapsed collapse-menu icons-href apply_check" href="javascript:void(0)">
                                    <i class="icon-edit"></i>审核
                                </a></li>
                            <?php } ?>
                            <li style="width:15%"><a attr="<?php echo ($vo["apply_id"]); ?>" attr_status="2" class="collapsed collapse-menu icons-href apply_excel" id="excel"  href="javascript:void(0)">
                                <i class="icon-edit"></i>导出
                            </a></li>
                            <!--<li style="width:15%"><a href="<?php echo U('DeliverApply/look?excel=excel',array('apply_code'=> $vo['apply_code']));?>" class="collapsed collapse-menu icons-href" target="_blank"><i class="icon-edit"></i>导出</a></li>-->

                                <!--<?php if($vo['apply_status'] == 1): ?><li style="width:15%"><a attr="<?php echo ($vo["apply_id"]); ?>" attr_status="6" class="collapsed collapse-menu icons-href apply_del" href="javascript:void(0)">
                                    <i class="icon-edit"></i>作废
                                </a></li><?php endif; ?>
-->


                        </ul></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
           
            <?php echo W('Page/page',array("/index.php/Admin/DeliverApply/index",$pnum,$pagelist,$urlPara));?>

            <!--分页查询结束-->
        </div>
    
    
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>

<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/goods.js?v=27"></script>
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
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getRoleStaff');?>",data:{ depot_id:depot_id,role_id:5  }, dataType:'json',timeout: 5000,
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
        var h = "/index.php/Admin/DeliverApply?"

        // depot_id
        h += "depot_id=" + $("#depot_id").val();

        // cust
        h += "&cust=" + $("#cust").val();

        // start_time
        h += "&start=" + $("#start_time").val();

        // end_time
        h += "&end=" + $("#end_time").val();

        location.href = h;
    });



    $("#cre_apply").click(function(){
        ajaxData("/index.php/Admin/DeliverApply/add");
    })
    $(".apply_edit").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/DeliverApply/edit",data);
    })
    $(".apply_look").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/DeliverApply/look",data);
    })
    $(".apply_check").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/DeliverApply/check",data);
    })
    $(".apply_del").click(function(){
        if(confirm("确定作废申请单吗?"))
        {
            var data={id:$(this).attr("attr")};
            ajaxDataAUD("/index.php/Admin/DeliverApply/del",data,true);
        }
    })
    $("#find").click(function(){
        location.href="/index.php/Admin/DeliverApply?depot_id="+$("#depot_id").val() + "&staff_id=" + $("#staff_id").val() + "&start=" + $("#start_time").val() + "&end=" + $("#end_time").val();
    });

    $(".apply_excel").click(function(){
        id = $(this).attr("attr");
        console.log(id)
        location.href="/index.php/Admin/DeliverApply/look?excel=excel&id=" + id;
    });

    function del(obj)
    {
        layer.confirm('将删除经销商下的所有信息,删除后不可恢复,确定要删除该记录吗????', {icon: 5}, function(){
            var url = obj.getAttribute('target-url');
            window.location.href = url;
            layer.closeAll();
        });
    }
</script>

</body>
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
</html>