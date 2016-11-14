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
    <script type="text/javascript" src="/Public/assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
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
                    <select id="depot" class="w100 form-control">
                        <option value="0">全部仓库</option>
                        <?php if(is_array($depotList)): $i = 0; $__LIST__ = $depotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dvo): $mod = ($i % 2 );++$i;?><option <?php if($dvo['repertory_id'] == $urlPara['depot_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($dvo["repertory_id"]); ?>"><?php echo ($dvo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>

                    <select id="op_brand" class="w150 form-control">
                        <option value="0">全部品牌</option>
                        <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i;?><option <?php if($bvo['brand_id'] == $urlPara['brand_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($bvo["brand_id"]); ?>"><?php echo ($bvo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>



                    <input type="text" id="goods_name" value="<?php echo ($urlPara['goods_name']); ?>" class="form-control w200" placeholder="输入商品名称"/>
                    
                    <a class="btn btn-default" href="#" id="find" role="button">查询</a>
                    -->
                </div>
                
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr>
                    <td width="10%">业务员</td>
                    
                    <td width="10%">商品名称</td>
                   <!-- <td width="10%">库存合计</td> --> 
                    <td width="10%">车存大单位</td>
                    <td width="10%">车存中单位</td>
                    <td width="10%">车存小单位</td>
                    <td width="10%">变化</td>
                    
                    <td width="10%">时间</td>
                    <!--<td width="10%">备注</td>-->
                    
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($vo["staff_name"]); ?></td>
                        
                        <td><?php echo ($vo["goods_name"]); echo ($vo["goods_spec"]); ?></td>
                       <!--  <td><?php echo ($vo["read_stock"]); ?></td> -->
                        <td><?php echo ($vo["big_stock"]); ?></td>
                        <td><?php echo ($vo["mid_stock"]); ?></td>
                        <td><?php echo ($vo["small_stock"]); ?></td>
                        <td><?php echo ($vo["bianhua"]); ?></td>
                        
                        <td><?php echo (date('Y-m-d H:i:s',$vo["datetime"])); ?></td>
                       <!-- <td><?php echo ($vo["remark"]); ?></td>-->
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
            <div class="tc">
                <ul class="pagination">
                    <li><a href="/index.php/Admin/DeliverStock/record/page/1/goods/<?php echo ($goods_id); ?>/staff/<?php echo ($staff_id); ?>">&laquo;</a></li>
                    <?php if(is_array($page["page_num"])): $i = 0; $__LIST__ = $page["page_num"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$num): $mod = ($i % 2 );++$i; if($page["page_current"] == $num): ?><li class="active"><a href="javascript:void(0)"><?php echo ($num); ?></a></li>
                            <?php else: ?>
                            <li><a href="/index.php/Admin/DeliverStock/record/page/<?php echo ($num); ?>/goods/<?php echo ($goods_id); ?>/staff/<?php echo ($staff_id); ?>"><?php echo ($num); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    <li><a href="/index.php/Admin/DeliverStock/record/page/<?php echo ($page['page_total']); ?>/goods/<?php echo ($goods_id); ?>/staff/<?php echo ($staff_id); ?>">&raquo;</a></li>
                </ul>
            </div>
            <!--分页查询结束-->
        </div>
    </div>
</div>
<div id="await" class="await">
    <span> <img src="/Public/images/loding.gif" title="加载图片"/></span>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>

</body>
</html>