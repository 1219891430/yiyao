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

                </div>
                    
                        <div class="fr">
                            <a class="btn btn-primary bg_3071a9" id="cre_brand" href="javascript:void(0)" role="button">创建</a>
                        </div>
                    
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table" id="role_table">
                <thead>
                <tr>
                    <td>品牌名称</td>
                    
                    <td width="30%">备注</td>
                    <td width="120">logo</td>
                    <td width="20%">操作</td>
                </tr>
                </thead>
                <tbody>
                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo ($bvo["brand_name"]); ?></td>
                                <td><?php echo ($bvo["remark"]); ?></td>
                                <td><img src="/Public/Uploads/brand/<?php echo ($bvo["brand_logo"]); ?>" width="80" height="80" /> </td>
                                <td>
                               
                                	
                                	<ul class="operate-menu li-width33">
                                		
                                <?php if(1==1 || $depot_id==0){ ?>
                                    <li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href brand_edit" href="javascript:void(0)">
                                        <i class="icon-edit"></i>修改
                                    </a></li>

                                    <?php if($bvo["is_close"]==0){ ?>
						    				<li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href brand_offpass" href="javascript:void(0)">
											<i class="icon-edit"></i>已审核
											</a></li>
						    		<?php }else{ ?>
						    				<li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href brand_pass" href="javascript:void(0)">
											<i class="icon-edit"></i>审核</a></li>
						    		<?php } ?>

                                    <li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href brand_del" href="javascript:void(0)">
                                        <i class="icon-remove-circle"></i>删除
                                    </a></li>
                                <?php }else{ ?>
                                	<?php if($bvo["is_close"]){ ?>
                                        <li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href" href="javascript:void(0)">
										<i class="icon-edit"></i>未审核</a></li>
                                    <?php }else{ ?>
						       				<li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href" href="javascript:void(0)">
											<i class="icon-edit"></i>已审核</a></li>
                                        <li><a attr="<?php echo ($bvo["brand_id"]); ?>" class="collapsed collapse-menu icons-href brand_edit" href="javascript:void(0)">
                                            <i class="icon-edit"></i>修改
                                        </a></li>
                                    <?php } ?>
                                <?php } ?>
                                </ul></td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                </tbody>
                <tfoot></tfoot>
            </table>
            <?php echo W('Page/page',array("/index.php/Admin/GoodsBrand/index",$pnum,$pagelist));?>
            <!--表格查询结束-->
        </div>
    
    
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_650 ">
    </div>
</div>

<script type="text/javascript">
    $("#cre_brand").click(function(){
    	
        ajaxData("/index.php/Admin/GoodsBrand/add");
    })
    $("#cre_class").click(function(){
        ajaxData("/index.php/Admin/GoodsBrand/classAdd");
    })
    
    $(".brand_del").click(function(){
        if(confirm("确定删除品牌"))
        {
        	var bid=$(this).attr("attr");

        	var data={bid:bid};
        	
            $.post("/index.php/Admin/GoodsBrand/del",data,function(msg){
     	 	
     	 		if(msg.res==1){
     	 			alert(msg.info);
     	 			location.reload();
     	 		}else{
     	 			alert(msg.info);
     	 		}
     	 	 
     	   },"json");
        }

    });
    

    
    $(".brand_edit").click(function(){
        var data={id:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/GoodsBrand/edit",data)
    })
    
    $(".brand_close").click(function(){
            var data={bid:$(this).attr("attrid"),type:$(this).attr("attr")};
            ajaxDataAUD("/index.php/Admin/GoodsBrand/close",data,true);
    })
    $(".class_edit").click(function(){
        var data={bid:$(this).attr("attr")};
        ajaxDataPara("/index.php/Admin/GoodsBrand/classEdit",data)
    })
    
    
    $(".brand_pass").click(function(){
    	if(confirm("确定要通过审核吗")){
    		var brand_id=$(this).attr("attr");
    		var data={brand_id:brand_id};
    		console.log(data);
    		$.post("/index.php/Admin/GoodsBrand/setPass",data,function(res){
    			if(res.res==1){
    				alert(res.msg);
    				location.reload();
    			}else{
    				alert(res.msg);
    			}
    		},"json");
    	}
    	
    });
    
    $(".brand_offpass").click(function(){
    	if(confirm("确定要设置成未审核状态吗")){
    		var brand_id=$(this).attr("attr");
    		var data={brand_id:brand_id};
    		
    		$.post("/index.php/Admin/GoodsBrand/setOffPass",data,function(res){
    			if(res.res==1){
    				alert(res.msg);
    				location.reload();
    			}else{
    				alert(res.msg);
    			}
    		},"json");
    	}
    	
    });
    
    
    
    $(".class_del").click(function(){
        if(confirm("确定删除该类别"))
        {
            var data={cid:$(this).attr("attr")};
            ajaxDataAUD("/index.php/Admin/GoodsBrand/classDel",data,true)
        }
    })
    $(".class_close").click(function(){
        var data={cid:$(this).attr("attrid"),type:$(this).attr("attr")};
        ajaxDataAUD("/index.php/Admin/GoodsBrand/classClose",data,true);
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