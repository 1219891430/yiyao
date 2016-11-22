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
<script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
<!--<script type="text/javascript" src="/Public/assets/js/jquery-form.js"></script>-->
<!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<style>
	.li-width li{
		width:90px;
	}
    .text-danger{color:#d9534f !important;}
	
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
                    <select id="op_brand" class="w150 form-control">
                        <option value="0">全部品牌</option>
                        <?php if(is_array($brandRes)): $i = 0; $__LIST__ = $brandRes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i;?><option <?php if($bvo['brand_id'] == $urlPara['bid']): ?>selected="selected"<?php endif; ?> value="<?php echo ($bvo["brand_id"]); ?>"><?php echo ($bvo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select id="op_class" class="w150 form-control">
                        <option value="0">全部类别</option>
                        <?php if(is_array($classRes)): $i = 0; $__LIST__ = $classRes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cvo): $mod = ($i % 2 );++$i;?><option <?php if($cvo['class_id'] == $urlPara['cid']): ?>selected="selected"<?php endif; ?> value="<?php echo ($cvo["class_id"]); ?>"><?php echo ($cvo["class_name"]); ?></option>
                            <?php if(is_array($cvo["class_list"])): $i = 0; $__LIST__ = $cvo["class_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i;?><option <?php if($svo['class_id'] == $urlPara['cid']): ?>selected="selected"<?php endif; ?> value="<?php echo ($svo["class_id"]); ?>">|------<?php echo ($svo["class_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <input type="text" class="form-control w150" id="op_name"   placeholder="请输入产品名称" value="<?php echo ($urlPara['gid']); ?>"/>
                    <a class="btn btn-default" href="#" id="selPro" role="button">查询</a>
                </div>
                <div class="fr">
                    <a class="btn btn-primary bg_3071a9" href="javascript:void(0)" id="cre_goods" role="button">创建</a>
                    
                    <a class="btn btn-primary bg_3071a9" href="/index.php/Admin/GoodsInfo/index/explode/explode" id="explode" role="button">导出</a>
                   
                </div>
            </div>
            <!--右侧查询结束-->
            <!--表格查询开始-->
            <table class="table list_table">
                <thead>
                <tr>
                   
                    <td width="20%">产品名称</td>
                    <td width="9%">品类</td>
                    <td width="9%">品牌</td>
                    <td width="7%">小包装</td>
                    <td width="7%">中包装</td>
                    <td width="7%">大包装</td>
                    <td width="7%">转换系数</td>
                    
                    <!--<td width="9%">状态</td>-->
                    <td width="20%">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    
                    <td><?php echo ($vo["goods_name"]); ?>/<?php echo ($vo["goods_spec"]); ?></td>
                    <td><?php echo ($vo["class_name"]); ?></td>
                    <td><?php echo ($vo["brand_name"]); ?></td>
                    <td><?php echo ($vo["goods_unit_s"]); if(($vo["goods_small_unit_default"]) == "1"): ?>&nbsp;<span style="color:#FF0000">(默认)</span><?php endif; ?></td>
                    <td><?php echo ($vo["goods_unit_m"]); if(($vo["goods_mid_unit_default"]) == "1"): ?>&nbsp;<span style="color:#FF0000">(默认)</span><?php endif; ?></td>
                    <td><?php echo ($vo["goods_unit_b"]); if(($vo["goods_big_unit_default"]) == "1"): ?>&nbsp;<span style="color:#FF0000">(默认)</span><?php endif; ?></td>
                    <td><?php echo ($vo["goods_convert_s"]); ?>*<?php echo ($vo["goods_convert_m"]); ?>*<?php echo ($vo["goods_convert_b"]); ?></td>
                    <!--
                      <td class='<?php if($vo["is_close"] == 1): ?>red<?php else: ?>green<?php endif; ?>'>
                          <?php if($vo["is_close"] == 1): ?>已封存<?php else: ?>未封存<?php endif; ?>
                      </td>
                      -->
                      <td><ul class="operate-menu li-width">
                      	
                      	<?php if($depot_id==0){ ?>
                          <li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_edit" href="javascript:void(0)">
                              <i class="icon-edit"></i>修改
                          </a></li>
                          </if>

                          <?php if($vo["is_close"]==0){ ?>
						    	<li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_offpass" href="javascript:void(0)">
								<i class="icon-edit"></i>已审核
								</a>
						       	</li>
						  <?php }else{ ?>
						    	<li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_pass" href="javascript:void(0)">
								<i class="icon-edit"></i>审核
								</a>
						       	</li>
						  <?php } ?>
						  
                        <?php }else{ ?>
                        	
                        	
                        	<?php if($vo["is_close"]==0){ ?>
						    	<li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_offpass" href="javascript:void(0)">
								<i class="icon-edit"></i>已审核</a></li>
                            <li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_edit" href="javascript:void(0)">
                              <i class="icon-edit"></i>修改</a></li>
						   <?php }else{ ?>
						    	<li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_pass" href="javascript:void(0)">
								<i class="icon-edit"></i>审核</a></li>
						       	<li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_edit" href="javascript:void(0)">
                              <i class="icon-edit"></i>修改</a></li>
						   <?php } ?>
						   
						   <li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href area <?php if(empty($vo["area"])): ?>text-danger<?php endif; ?> " href="javascript:void(0)">
                              <i class="icon-edit"></i>区域设置
                          </a></li>

                          <li><a attr="<?php echo ($vo["goods_id"]); ?>" name="<?php echo ($vo["goods_name"]); ?>" class="collapsed collapse-menu icons-href goods_warning" href="javascript:void(0)">
                              <i class="icon-warning-sign"></i>设置预警值
                              </a>
                          </li>
                          <li><a attr="<?php echo ($vo["goods_id"]); ?>" class="collapsed collapse-menu icons-href goods_set_org <?php if(empty($vo["org_parent_id"])): ?>text-danger<?php endif; ?>" href="javascript:void(0)">
                              <i class="icon-edit"></i>经销商
                          </a></li>
                        	
                        <?php } ?>
                      </ul></td>
                  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <!--表格查询结束-->
            <!--分页查询开始-->
          <?php echo W('Page/page',array("/index.php/Admin/GoodsInfo/index",$pnum,$pagelist,$urlPara));?>

            <!--分页查询结束-->
        </div>
    
    
    </div>
</div>
<div id="await" class="await"><span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span></div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_850 ">
       
    </div>
</div>

<script type="text/javascript">

$(function(){
        $("#ok_time").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 2014,//年份的开始默认为1949
            endY :2049//年份的结束默认为2049
        });
   })

var i=0;
    $("#checkedgoods").click(function(){
    	
    	//var check= $("#checkedgoods").prop("checked");
    	if(i==0){
    		$(".check_good").prop("checked","checked");
    		i=1;
    	}else{
    		$(".check_good").prop("checked",false);
    		i=0;
    	}
    });

$(".goods_warning").click(function () {
    var data={gid:$(this).attr("attr"), gname:$(this).attr('name')};

    ajaxDataPara("/index.php/Admin/GoodsWarning/warning",data);
})
    
    		
    $(".goods_edit").click(function(){
        var data={gid:$(this).attr("attr")};

        ajaxDataPara("/index.php/Admin/GoodsInfo/edit",data);
    })

    $(".goods_set_org").click(function(){
        var data={gid:$(this).attr("attr")};

        ajaxDataPara("/index.php/Admin/GoodsInfo/setorg",data);
    })
    	
    $(".area").click(function(){
        var data={goods_id:$(this).attr("attr")};

        ajaxDataPara("/index.php/Admin/GoodsInfo/area",data);
    })	
    
    $("#cre_goods").click(function(){
        ajaxData("/index.php/Admin/GoodsInfo/add");
    })
    $("#selPro").click(function(){
        location.href="/index.php/Admin/GoodsInfo/index/bid/"+$("#op_brand").val()+"/cid/"+$("#op_class").val()+"/gid/"+$("#op_name").val();
    })
    

   
    $(".goods_del").click(function(){
        if(confirm("是否删除该产品,商品删除后相关单据数据为空"))
        {
            var data={gid:$(this).attr("attr")};
            ajaxDataAUD("/index.php/Admin/GoodsInfo/del",data,true);
        }
    })
    $(".goods_close").click(function(){
            var data={gid:$(this).attr("attrid"),type:$(this).attr("attr")};
            ajaxDataAUD("/index.php/Admin/GoodsInfo/close",data,true);
    })
    $("#import").click(function(){
        ajaxData("/index.php/Admin/GoodsInfo/import");
    })
    $('#explode').click(function(){

		
		//ajaxDataPara("/index.php/Admin/GoodsInfo/edit_pwd",data);
	})
    
    $(".goods_pass").click(function(){
    	if(confirm("确定要通过审核吗")){
    		var goods_id=$(this).attr("attr");
    		var data={goods_id:goods_id};
    		console.log(data);
    		$.post("/index.php/Admin/GoodsInfo/setPass",data,function(res){
    			if(res.res==1){
    				alert(res.msg);
    				location.reload();
    			}else{
    				alert(res.msg);
    			}
    		},"json");
    	}
    	
    });
    
    $(".goods_offpass").click(function(){
    	if(confirm("确定要设置成未审核状态吗")){
    		var goods_id=$(this).attr("attr");
    		var data={goods_id:goods_id};
    		console.log(data);
    		$.post("/index.php/Admin/GoodsInfo/setOffPass",data,function(res){
    			if(res.res==1){
    				alert(res.msg);
    				location.reload();
    			}else{
    				alert(res.msg);
    			}
    		},"json");
    	}
    	
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
<span style="display:none"><script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260673330'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1260673330' type='text/javascript'%3E%3C/script%3E"));</script></span>
</body>
</html>