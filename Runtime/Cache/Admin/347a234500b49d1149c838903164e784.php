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
    <script type="text/javascript" src="/Public/assets/js/layer/layer.js"></script>
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

                    <select id="depid" class="w200 form-control">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($depot)): $i = 0; $__LIST__ = $depot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i; if($d["repertory_id"] == $_GET['depot_id']): ?><option selected="selected" value="<?php echo ($d["repertory_id"]); ?>"><?php echo ($d["repertory_name"]); ?></option>
                                <?php else: ?>
                                <option value="<?php echo ($d["repertory_id"]); ?>"><?php echo ($d["repertory_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <a class="btn btn-default" href="javascript:void(0)" id="find" role="button">查询</a>
                </div>
                
                <div class="fr">
                    <a class="btn btn-primary bg_3071a9" id="cre_c"  role="button">添加</a>
                </div>
                
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr><td width="10%">仓库编号</td>
                    <td width="13%">仓库名称</td>
                    <td width="10%">经销商编号</td>
                    <td width="8%">经销商名称</td>
                    <td width="12%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($dolist)): $i = 0; $__LIST__ = $dolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td class="center"><?php echo ($vo["repertory_code"]); ?></td>
                        <td class="center"><?php echo ($vo["repertory_name"]); ?></td>
                        <td ><?php echo ($vo["org_id"]); ?></td>
                        <td><?php echo ($vo["org_name"]); ?></td>
                        <td>
                           <!-- <a href="javascript:void(0)" data-id="<?php echo ($vo["org_id"]); ?>" data-depot="<?php echo ($vo["repertory_id"]); ?>" class="editjing"><i class="icon-edit"></i>&nbsp;修改</a>&nbsp;-->
                            <a href="javascript:void(0)" data-id="<?php echo ($vo["org_id"]); ?>" data-depot="<?php echo ($vo["repertory_id"]); ?>" class="delete"><i class="icon-remove-circle"></i>&nbsp;删除</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
            <?php echo W('Page/page',array("/index.php/Admin/Depot/dealer",$pnum,$pagelist,$urlPara));?>

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

<script type="text/javascript">
    $("#cre_c").click(function(){
        ajaxData("/index.php/Admin/Depot/addDealer");
    });
    $(".editjing").click(function(){
        var data={org_id:$(this).attr("org"), depot_id:$(this).attr("depot")};
        ajaxDataPara("/index.php/Admin/Depot/editDealer",data);
    });
    $("#find").click(function(){

        // org_name
        // contacts
        // telephone
        // area
        // is_check
        // types
        // status

        location.href="/index.php/Admin/Depot/dealer?depot_id="+$("#depid").val();
    });

    //删除
    $(".delete").click(function() {

        if (confirm("将删除经销商下的所有信息,删除后不可恢复,确定要删除该记录吗？")) {
            var id = $(this).attr('data-id');
            var depot_id = $(this).attr('data-depot');

            $.post("<?php echo U('Admin/Depot/DelDealer');?>", { org_id: id, depot_id: depot_id }, function (result) {
                if (result == 1) {
                    alert("删除成功!");
                    location.reload();
                } else if (result == 2) {
                    alert('仓库不存在');
                    location.reload();
                } else if (result == 3) {
                    alert('此仓库下经销商商品还有剩余，禁止删除');
                    location.reload();
                } else {
                    alert("失败");
                }
            })
        }
    });


</script>

</body>
</html>