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
    <link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
    <!-- <script type="text/javascript" src="/Public/js/jquery-messages_cn.js"></script> -->
    <style type="text/css">
    #custs{
    position: relative;
    }
    #custs li{
    
    width:200px;
    height:30px;
    background-color:#FFFFFF;
    }
    .li-width li{
    	width:30%;
    }
    </style>
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
                    <select id="depot" class="w200 form-control">
                        <option value="0">全部仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option <?php if($dvo['repertory_id'] == $urlPara['depot_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option>
                            <?php if(is_array($dvo["depot_list"])): $i = 0; $__LIST__ = $dvo["depot_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option <?php if($v['repertory_id'] == $urlPara['depot_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($v["repertory_id"]); ?>">|------<?php echo ($v["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select id="out_type" class="w200 form-control">
                        <option value="0">出库类型</option>
                        <?php if(is_array($aOutType)): $k = 0; $__LIST__ = $aOutType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ivo): $mod = ($k % 2 );++$k; if($k == $urlPara['out_type']): ?><option selected="selected" value="<?php echo ($k); ?>"><?php echo ($ivo); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($k); ?>"><?php echo ($ivo); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select id="out_status" class="w200 form-control" value="v">
                        <option value="0">审核状态</option>
                         <option value="1">已提交</option>
                          <option value="2">已审核</option>
                         
                    </select>
                    <input type="text" readonly="readonly" <?php if($urlPara['start'] != 0): ?>value="<?php echo ($urlPara['start']); ?>"<?php endif; ?>  class="form-control w100 cursor-pointer" id="start_time"
                    placeholder="起始时间">
                    <input type="text" readonly="readonly" <?php if($urlPara['end'] != 0): ?>value="<?php echo ($urlPara['end']); ?>"<?php endif; ?> class="form-control w100 cursor-pointer" id="end_time"
                    placeholder="结束时间">
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>
                </div>
                <div class="fr">
                    <a class="btn btn-primary bg_3071a9" href="javascript:void(0)" id="cre_out" role="button">创建</a>
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr><td width="10%">单据编号</td>
                    <td width="15%">出库仓库</td>
                    
                    <td width="8%">出库类型</td>
                    <td width="8%">出库状态</td>
                    <td width="10%">日期</td>
                    <td width="10%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($depot_in)): $i = 0; $__LIST__ = $depot_in;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($vo["depot_out_code"]); ?></td>
                        <td><?php echo ($vo["out_name"]); ?></td>
                        <td><?php echo ($vo["type"]); ?></td>
                        <td><?php echo ($vo["status"]); ?></td>
                        <td><?php echo ($vo["time"]); ?></td>
                        <td><ul class="operate-menu li-width">
                            <?php if($vo['out_status'] == 2): ?><li><a attr="<?php echo ($vo["depot_out_id"]); ?>" class="collapsed collapse-menu icons-href out_show" href="javascript:void(0)">
                                    <i class="icon-edit"></i>查看
                                </a></li>
                                
                            <?php else: ?>
                                <li><a attr="<?php echo ($vo["depot_out_id"]); ?>" class="collapsed collapse-menu icons-href out_shenhe" href="javascript:void(0)">
                                    <i class="icon-edit"></i>审核
                                </a></li>
                                <li><a attr="<?php echo ($vo["depot_out_id"]); ?>" class="collapsed collapse-menu icons-href out_edit" href="javascript:void(0)">
                                    <i class="icon-edit"></i>修改
                                </a></li><?php endif; ?>
                            <li><a target="_blank" attr="<?php echo ($vo["depot_out_id"]); ?>" class="collapsed collapse-menu icons-href print" href="javascript:void(0)">
                                    <i class="icon-edit"></i>打印
                                </a></li>
                        </ul></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
            
           <?php echo W('Page/page',array("/index.php/Admin/DepotOut/index",$pnum,$pagelist,$urlPara));?>
            
            <!--分页查询结束-->
        </div>
    </div>
</div>
<div id="await" class="await">
    <span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/zstb.js?v=27"></script>
<script type="text/javascript" src="/Public/assets/js/depotGoods.js?v=27"></script>
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
    })
    $(".print").click(function(){
    	var depot_out_id=$(this).attr("attr");
        
    	location.href="/index.php/Admin/DepotOut/show/print/print/depot_out_id/"+depot_out_id;
    });
    
    
    $("#cre_out").click(function(){
        ajaxData("/index.php/Admin/DepotOut/add");
    })
    $(".out_edit").click(function(){
        var data={depot_out_id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/DepotOut/edit",data);
    })
    $(".out_shenhe").click(function(){
        var data={depot_out_id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/DepotOut/outPass",data);
    })
    
    $(".out_show").click(function(){
    	var data={depot_out_id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/DepotOut/show",data);
    });
    $("#find").click(function(){
        var start=$("#start_time").val()==""?0:$("#start_time").val()
        var end=$("#end_time").val()==""?0:$("#end_time").val()
        if((start==0&&end!=0)||(start!=0&&end==0))
            alert("请点击选择起始时间跟结束时间")
        else
            location.href="/index.php/Admin/DepotOut/index/depot_id/"+$("#depot").val()+"/out_type/"+$("#out_type").val()+"/out_status/"+$("#out_status").val()+"/start_time/"+start+"/end_time/"+end;
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