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
<script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
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
            <div class="sel-data mb20">
                <div class="fl">
                    <select id="depot_id" class="w200 form-control">
                        <option value="0">全部仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option <?php if($dvo['repertory_id'] == $urlPara['depot_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option>
                            <?php if(is_array($dvo["depot_list"])): $i = 0; $__LIST__ = $dvo["depot_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option <?php if($v['repertory_id'] == $urlPara['depot_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($v["repertory_id"]); ?>">|------<?php echo ($v["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    
                    <select id="org_id" class="w200 form-control">
                        <option value="0">全部经销商</option>
                        <?php if(is_array($orgList)): $i = 0; $__LIST__ = $orgList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option <?php if($dvo['org_id'] == $urlPara['org_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($dvo["org_id"]); ?>"><?php echo ($dvo["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>

                    <select id="op_brand" class="w150 form-control">
                        <option value="0">全部品牌</option>
                        <?php if(is_array($brandList)): $i = 0; $__LIST__ = $brandList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i;?><option <?php if($bvo['brand_id'] == $urlPara['op_brand']): ?>selected="selected"<?php endif; ?> value="<?php echo ($bvo["brand_id"]); ?>"><?php echo ($bvo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>



                    <input type="text" id="goods_name" value="<?php echo ($urlPara['goods_name']); ?>" class="form-control w200" placeholder="输入商品名称"/>
                    
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>
                    <a class="btn btn-default" href="#" id="export" role="button">导出</a>
                </div>
                <div class="fr">
                  
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr><td width="15%">仓库名称</td>
                    <td width="10%">品牌</td>
                    <td width="20%">商品名称</td>
                    <td width="10%">库存合计</td>
                    <td width="10%">库存小单位</td>
                    
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($vo["depot_name"]); ?></td>
                        <td><?php echo ($vo["brand_name"]); ?></td>
						<td><?php echo ($vo["goods_name"]); ?>/<?php echo ($vo["goods_spec"]); ?></td>
                        <td><?php echo ($vo["numString"]); ?></td>
                       
                        <td><?php echo (getGoodsNum($vo["small_stock"])); ?></td>
                        
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            
            <?php echo W('Page/page',array("/index.php/Admin/DepotStock/index",$pnum,$pagelist,$urlPara));?>
        </div>
    
    
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<script>
    //部门联动
    $("#depot_id").change(function(){
        var depot_id = $(this).val();
        if(depot_id ==0){
            $("#org_id").html('<option value=0>请选择经销商</option>');
            return;
        }
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getDepotOrg');?>",data:{ depot_id:depot_id }, dataType:'json',timeout: 5000,
            error: function(){
            },
            success: function($r){
                $("#org_id").html('<option value=0>请选择经销商</option>');
                if($r.status){
                    var html = '<option value=0>请选择经销商</option>';
                    $.each($r.rows,function(index,item){
                        html+= '<option value="'+item.org_id+'">'+item.org_name+'</option>';
                    });
                    $("#org_id").html(html);
                }
            }
        });
    });

	$("#find").click(function(){
        location.href="/index.php/Admin/DepotStock/index/org_id/"+$("#org_id").val()+"/depot_id/"+$("#depot_id").val()+"/op_brand/"+$("#op_brand").val()+"/goods_name/"+$("#goods_name").val();
    })
	$("#export").click(function(){
        location.href="/index.php/Admin/DepotStock/index/export/export/depot_id/"+$("#depot_id").val()+"/op_brand/"+$("#op_brand").val()+"/goods_name/"+$("#goods_name").val();
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