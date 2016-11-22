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
    <div class="r-sub-nav row-fluid"><?php
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

        <div class="sel-data mb20">
        <div class="fl">
                        
            <select id="depot_id" class="w150 form-control">
            <option value="0">请选择仓库</option>
            <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['repertory_id'] == $queryDepotID): ?><option selected="selected" value="<?php echo ($svo["repertory_id"]); ?>"><?php echo ($svo["repertory_name"]); ?></option>
            <?php else: ?>
            <option value="<?php echo ($svo["repertory_id"]); ?>"><?php echo ($svo["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            
            <select id="staff_id" class="w150 form-control">
            <option value="0">请选择业务员</option>
            <?php if(is_array($staffList)): $i = 0; $__LIST__ = $staffList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i; if($svo['admin_id'] == $queryStaffID): ?><option selected="selected" value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option>
            <?php else: ?>
            <option value="<?php echo ($svo["admin_id"]); ?>"><?php echo ($svo["true_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            
            <select id="line_id" class="w250 form-control">
            <option value="0">请选择配送线路</option>
            <?php if(is_array($line_list)): $i = 0; $__LIST__ = $line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lvo): $mod = ($i % 2 );++$i; if($lvo['line_id'] == $queryLineID): ?><option selected="selected" value="<?php echo ($lvo["line_id"]); ?>"><?php echo ($lvo["line_name"]); ?></option>
            <?php else: ?>
            <option value="<?php echo ($lvo["line_id"]); ?>"><?php echo ($lvo["line_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a class="btn btn-default" href="#" id="find" role="button">查询</a>
        </div>
        <div class="fr">
            <a class="btn btn-primary bg_3071a9" href="<?php echo U('index');?>" role="button">配送预单</a>
        </div>
        </div>

        <table class="table list_table" id="role_table">
        <thead>
        <tr>
        <td width="20%">仓库</td>
        <td width="20%">路线</td>
        <td width="20%">配送人员</td>
        <td width="20%">时间</td>
        <td width="20%">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($shippint_list)): $i = 0; $__LIST__ = $shippint_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td><?php echo ($vo["repertory_name"]); ?></td>
        <td><?php echo ($vo["line_name"]); ?></td>
        <td><?php echo ($vo["true_name"]); ?></td>
        <td><?php echo (date('Y-m-d H:i', $vo["add_time"])); ?></td>
        <td>
        <ul class="operate-menu li-width50">
        <li><a class="collapsed collapse-menu icons-href" href="<?php echo U('detail', array('uid'=>$vo[user_id],'lid'=>$vo['line_id']));?>"><i class="icon-edit"></i>查看</a></li>
        <li><a class="collapsed collapse-menu icons-href line_del" href="javascript:void(0)" data-userid="<?php echo ($vo["user_id"]); ?>" data-lineid="<?php echo ($vo["line_id"]); ?>"><i class="icon-edit"></i>删除</a></li>
        </ul>
        </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <tfoot></tfoot>
        </table>

	</div>
    </div>
</div>

<script type="text/javascript">

    //部门联动
    $("#depot_id").change(function(){
        var depot_id = $(this).val();
        if(depot_id ==0){
            $("#staff_id").html('<option value=0>请选择业务员</option>');
            $("#line_id").html('<option value=0>请选择配送线路</option>');
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

        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getShippingLine');?>",data:{ depot_id:depot_id  }, dataType:'json',timeout: 5000,
            error: function(){
            },
            success: function($r){
                $("#line_id").html('<option value=0>请选择配送线路</option>');
                if($r.status){
                    var html = '<option value=0>请选择配送线路</option>';
                    $.each($r.rows,function(index,item){
                        html+= '<option value="'+item.line_id+'">'+item.line_name+'</option>';
                    });
                    $("#line_id").html(html);
                }
            }
        });

    });

$("#find").click(function(){

	var depot_id = $('#depot_id').val();
	var staff_id = $('#staff_id').val();
	var line_id = $('#line_id').val();

	//if(depot_id == 0){ alert('请选择仓库'); return; }

	var url = "<?php echo U('list');?>" + "?depot_id=" + depot_id + "&staff_id=" + staff_id + "&line_id=" + line_id;
	location.href = url;
});

$(".line_del").click(function(){
	if(confirm("确定作废申请单吗?"))
	{
		var userid = $(this).attr('data-userid');
		var lineid = $(this).attr('data-lineid');
		var url = "<?php echo U('del');?>" + "?uid=" + userid + "&lid=" + lineid;
		location.href = url;
	}
})
</script>
</body>
</html>