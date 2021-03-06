<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo ($start); ?>至<?php echo ($end); ?>日订单<?php echo ($type_lx); ?>报表</title>
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
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
    <script type="text/javascript" src="/Public/assets/js/layer/layer.js"></script>

    <!-- select多选 -->
    <script type="text/javascript" src="/Public/assets/js/bootstrap-select.js"></script>
    <link rel="stylesheet" href="/Public/assets/css/bootstrap-select.css" type="text/css">
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <link href="./table.css" rel="stylesheet" type="text/css">

    <style media="screen,print" type="text/css">
        body{text-align:center}
        tbody td{background:#fff;}
        .w100{width:150px;height:25px;font-size:16px;color: rgb(99, 99, 99)}
        ul{width:100%;margin:10px auto;height:40px;}
        li{width:auto;height:30px;border-radius:5px;text-align: center;line-height:30px;float:left;margin-right:3px}
        li.li_2{background: #66a522;width:60px;}
        li.li_3{background: red;width:90px;}
        li.li_1{background: #015289;width:60px;}
        li a{color:#fff;font-family: "微软雅黑";font-size:14px}
        #priew{margin: 10px auto;}
        .widget {width:80%;color: rgb(95, 95, 95);background: none repeat scroll 0% 0% rgb(247, 247, 247);border: 1px solid rgb(205, 205, 205);border-radius: 3px;box-shadow: 0px 2px 2px -2px rgb(204, 204, 204);position: relative;margin:10px auto;}
        .whead {border-bottom: 1px solid rgb(205, 205, 205);
        box-shadow: 0px 1px 0px rgb(255, 255, 255);text-shadow: 0px 1px rgb(255, 255, 255); position: relative;color: rgb(99, 99, 99);height:40px;
         background: -moz-linear-gradient(top, #f8f8f8 0%, #e8e8e8 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f8f8f8), color-stop(100%,#e8e8e8));
        background: -webkit-linear-gradient(top, #f8f8f8 0%,#e8e8e8 100%);
        width:100%;
        }
        .whead .titleIcon { float: left; padding: 9px 0 10px 0; width: 40px; border-right: 1px solid #D5D5D5; text-align: center; }
        .whead h6 {float: left;display: block;font-size: 16px;font-weight: bold;height:40px;line-height: 40px;padding-left:15px;}
        table {border-collapse: collapse;}
        table thead td {text-align: center;font-size: 12px;height:20px;line-height:20px;color: rgb(144, 144, 144); padding: 3px 5px 2px 5px;background: none repeat scroll 0% 0% rgb(238, 238, 238)}
        table tfoot tr {line-height:40px;height: 40px;border-top: 1px solid rgb(221, 221, 221);background: -moz-linear-gradient(center top , rgb(248, 248, 248) 0%, rgb(239, 239, 239) 100%) repeat scroll 0% 0% transparent;}
        table tbody td, table thead td {height:20px; border-left: 1px solid #DFDFDF; box-shadow: 0 1px 0 #fafafa inset; -webkit-box-shadow: 0 1px 0 #fafafa inset; -moz-box-shadow: 0 1px 0 #fafafa inset; }
        table tbody td { padding: 7px 11px; vertical-align: middle; text-align: center;}
        table tbody tr { border-top: 1px solid #DFDFDF; }
        table tbody tr:first-child { box-shadow: 0 1px 0px #fff inset; -webkit-box-shadow: 0 1px 0px #fff inset; -moz-box-shadow: 0 1px 0px #fff inset; }
        table tbody tr:nth-child(even) { background: #f2f2f2; }
        p.footer{width:80%;color: rgb(95, 95, 95);margin:50px auto;font-size:12px;}
        li{list-style: none;}
        .form-control{width: 120px;}
    </style>
</head>
<body>
<script type="text/javascript">
     $(window).on('load', function () {
         
         $('.selectpicker').selectpicker({
            noneSelectedText : '请选择',
            'selectedText': 'cat'
         });

         $("#zid").click(function() {
            $("#firstname").val('').attr("readonly",true);
            $("#brand").empty().attr("readonly",true);
            $("#brands").empty().attr("readonly",true);

            //获得经销商的所有店铺
            $.ajax({
                url:"<?php echo U('Home/Customer/find_shop');?>",
                type:"post",
                dataType:"json",
                success:function(data){
                    if( data ){
                        var option = "";
                        $.each(data, function(k,v) {
                             option += "<option value='"+v.cust_id+"''>"+v.cust_name+"</option>"
                        });
                        $("#id_select").empty().append(option);
                        // 重新初始化
                        $('#id_select').selectpicker('render');
                        $('#id_select').selectpicker('refresh');
                    }
                }
            })
            //获得经销商下所有的商品
            $.ajax({
                url:"<?php echo U('Home/Goods/get_goods');?>",
                type:"post",
                dataType:"json",
                success:function(data){
                    if( data ){
                        var option = "";
                        $.each(data, function(k,v) {
                             option += "<option value='"+v.goods_id+"''>"+v.goods_name+v.goods_spec+"</option>"
                        });
                        $("#goods_select").empty().append(option);
                        // 重新初始化
                        $('#goods_select').selectpicker('render');
                        $('#goods_select').selectpicker('refresh');
                    }
                }
            })

            $("#show").show(300);

         });

   // $('.selectpicker').selectpicker('hide');

        $("#start_time,#end_time").manhuaDate({
            Event : "click",//可选
            Left : 0,//弹出时间停靠的左边位置
            Top : -16,//弹出时间停靠的顶部边位置
            fuhao : "-",//日期连接符默认为-
            isTime : false,//是否开启时间值默认为false
            beginY : 1949,//年份的开始默认为1949
            endY :2049//年份的结束默认为2049
        });

        $("#send").click(function () {

            var start = $("#start_time").val();

            var end = $("#end_time").val();

            if(start==""){
                layer.tips('请选择开始时间', '#send', {
                  tips: 3
                });
                return;
            }
            if(end==""){
                layer.tips('请选择结束时间', '#send', {
                  tips: 3
                });
                return;
            }

            $("#formId").submit();

        });  

        });//end
</script>


<div class="widget">
    <div class="whead">

            <ul>

            <form action="<?php echo U('Dealer/OrderReport/goods');?>" method="get" id="formId">

                <li><input type="text" name="start" readonly="readonly"  class="form-control" value="<?php echo ($start); ?>" id="start_time" placeholder="起始时间" style="cursor:pointer;"></li>

                <li><input type="text" name="end" readonly="readonly"  class="form-control" value="<?php echo ($end); ?>" id="end_time" placeholder="结束时间"  style="cursor:pointer;"></li>

                <li>
                <div><input type="text" name="shop" value="<?php echo ($shop); ?>" class="form-control" id="firstname" placeholder="请输入店铺"></div>
                </li>

                <li>
                    <select class="form-control w200" id="brand" name="brand_id">
                        <option value="0">请选择品牌</option>
                        <?php if(is_array($aBrand)): $i = 0; $__LIST__ = $aBrand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$aBrand): $mod = ($i % 2 );++$i;?><option <?php if($aBrand["brand_id"] == $b_id): ?>selected="selected"<?php endif; ?> value="<?php echo ($aBrand["brand_id"]); ?>"><?php echo ($aBrand["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>

                <li>
                    <select class="w200 form-control" id="brands" name="goods_id">
                        <option value="0">请选择产品</option>
                        <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["goods_id"]); ?>"><?php echo ($vo["goods_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>

                <li>
                    <select id="staff_id" class="form-control" name="staff">
                        <option value="0">全部</option>
                        <?php if(is_array($aStaff)): $k = 0; $__LIST__ = $aStaff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ivo): $mod = ($k % 2 );++$k;?><option <?php if($ivo['staff_id'] == $staff): ?>selected="selected"<?php endif; ?> value="<?php echo ($ivo["staff_id"]); ?>"><?php echo ($ivo["staff_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>
                


                <li class="li_1"><a onClick="priew()" href="#">打印</a></li>

                <li class="li_2"><a href="#" id="send">生成</a>

                <!-- <li class="li_3"><a href="#" id="zid">自定义筛选</a> -->

                <input type="hidden" name="g_id" value="<?php echo ($g_id); ?>" id="g_id"><!-- 商品ID -->
                </li>
            </ul>
    </div>
</div>
<!-- 自定义 -->
<div class="widget" id="show" style="display:none;">
    <div class="whead">
        <ul>

            <li>

                <select id="id_select" class="selectpicker bla bla bli" multiple data-live-search="true" name="cust_id[]">

    
                </select>

            </li>

            <li></li>

            <li>

                <select id="goods_select" class="selectpicker bla bla bli" multiple data-live-search="true" name="good_id[]">


                </select>

            </li>
</form>
        </ul>

    </div>
</div>

<div id="priew">
<div class="widget" id="dingdan">

<div class="whead">
    <h6><?php echo ($start); ?>至<?php echo ($end); ?>日<?php echo ($type_lx); ?></h6>
    <span style="position:absolute;bottom:12px;right:10px"><b><?php echo ($name); ?></b></span>
    <div class="clear"></div>
</div>



            <table border="1px" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td width="20%">品牌</td>
                        <td width="20%"><b>产品</b></td>
                        <td width="20%"><b>单位</b></td>
                        <td width="10%"><b>单价</b></td>
                        <td width="15%"><b>总数量</b></td>
                        <td width="20%"><b>总金额</b></td>
                    </tr>
                </thead>
                <?php $ddnum=0; ?>
                <tbody class="f12">
                    <?php if(is_array($newData)): $i = 0; $__LIST__ = $newData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr >
                            <td rowspan="<?php echo count($d['total']); ?>"><?php echo ($d["brand_name"]); ?></td>
                            <?php if(is_array($d["total"])): $i = 0; $__LIST__ = $d["total"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ds): $mod = ($i % 2 );++$i;?><td><?php echo ($ds["good_name"]); ?>/<?php echo ($ds["goods_spec"]); ?></td>
                                    <td><?php echo ($ds["unit_name"]); ?></td>
                                    <td><?php echo ($ds["singleprice"]); ?></td>
                                    <td><?php echo ($ds["number"]); ?></td>
                                    <td><?php echo (number_format($ds["allprice"],2)); ?>元</td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold" colspan="6">小结：<span style="color:blue;font-weight:bold"><?php echo (number_format($d["totalmoney"],2)); ?> </span>元&nbsp;</td>
                            <?php $ddnum+=$d['totalmoney']; ?>
                        </tr>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>                 
                       <td style="text-align:right;font-weight:bold" colspan="6">总金额：<span style="color:red;font-weight:bold"><?php echo (number_format($ddnum,2)); ?></span>元&nbsp;</td> 
                    </tr>
                </tbody>
                 <tfoot>
                </tfoot>
            </table>

			<!-- 促销品统计 -->
            <table border="1px" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td width="20%">促销品统计(品牌)</td>
                        <td width="20%"><b>产品</b></td>
                        <td width="20%"><b>单位</b></td>
                        <td width="10%"><b>单价</b></td>
                        <td width="15%"><b>总数量</b></td>
                        <td width="20%"><b>总金额</b></td>
                    </tr>
                </thead>
                <tbody class="f12">
                    <?php if(is_array($newCXData)): $i = 0; $__LIST__ = $newCXData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td rowspan="<?php echo count($vo['total']) ?>"><?php echo ($vo["brand_name"]); ?></td>
                            <?php if(is_array($vo['total'])): $i = 0; $__LIST__ = $vo['total'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><td><?php echo ($v["good_name"]); ?>(<?php echo ($v["goods_spec"]); ?>)</td>
                                <td><?php echo ($v["unit_name"]); ?></td>
                                <td><?php echo ($v["goods_base_price"]); ?></td>
                                <td><?php echo ($v["number"]); echo ($v["unit_name"]); ?></td>
                                <td><?php echo (number_format($v["allprice"],2)); ?>元</td>
                                </tr>
                                <tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                 <tfoot>
                    <tr>                 
                       <td style="text-align:right;font-weight:bold" colspan="6">总金额：
                            <span style="color:red;font-weight:bold"><?php echo ($cxddnum); ?></span>元&nbsp;
                        </td> 
                    </tr>
                </tfoot>
            </table>
            
            
            
            
            <!-- 赊账统计 -->
            <table border="1px" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td width="20%">赊账统计</td>
                        <td width="20%"><b>商铺</b></td>
                        <td width="20%"><b>商铺老板</b></td>
                        <td width="10%"><b>总金额</b></td>
                        <td width="15%"><b>已付金额</b></td>
                        <td width="20%"><b>赊款金额</b></td>
                    </tr>
                </thead>
                <tbody class="f12">
                    <?php if(is_array($sData)): $i = 0; $__LIST__ = $sData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sData): $mod = ($i % 2 );++$i;?><tr>
                            <td></td>
                            <td><?php echo ($sData["cust_name"]); ?></td>
                            <td><?php echo ($sData["contact"]); ?></td>
                            <td><?php echo ($sData["totalm"]); ?></td>
                            <td><?php echo ($sData["realm"]); ?></td>
                            <td><?php echo ($sData["nopay"]); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:right;font-size:14px;" colspan="6">已收：<b><?php echo ($sData["realm"]); ?></b> 元</td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                 <tfoot>
                    <tr>                
                       <td style="text-align:right;font-weight:bold" colspan="6">总额：<span style="color:red;font-weight:bold"><?php echo ($snum['totaln']); ?></span>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已收：<span style="color:red;font-weight:bold"><?php echo ($snum['pay']); ?></span>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;赊款：<span style="color:red;font-weight:bold"><?php echo ($snum['totalnopay']); ?></span>元&nbsp;</td> 
                    </tr>
                </tfoot>
            </table>
            <!-- 退货 -->
            <table border="1px" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                    <tr>
                        <td width="20%">退货统计(产品/规格)</td>
                        <td width="20%"><b>商铺</b></td>
                        <td width="20%"><b>单位</b></td>
                        <td width="10%"><b>单价</b></td>
                        <td width="15%"><b>总数量</b></td>
                        <td width="20%"><b>总金额</b></td>
                    </tr>
                </thead>

                <tbody class="f12">
                    <?php if(is_array($newTData)): $i = 0; $__LIST__ = $newTData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr >
                            <td rowspan="<?php echo ($d["totalnum"]); ?>"><?php echo ($d["goods_name"]); ?></td>
                            <?php if(is_array($d["total"])): $i = 0; $__LIST__ = $d["total"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ds): $mod = ($i % 2 );++$i;?><td><?php echo ($ds["cust_name"]); ?></td>
                                    <td><?php echo ($ds["goods_unit"]); ?></td>
                                    <td><?php echo ($ds["goods_money"]); ?></td>
                                    <td><?php echo ($ds["goods_num"]); ?></td>
                                    <td><?php echo (number_format($ds["return_real_money"],2)); ?>元</td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>                 
                       <td style="text-align:right;font-weight:bold" colspan="6">实际退货总金额：<span style="color:red;font-weight:bold"><?php echo ($ttnum); ?></span>元&nbsp;</td> 
                    </tr>
                </tbody>
                 <tfoot>
                </tfoot>
            </table>
            <!-- 退货 -->
            
            <!-- 调换货 -->
            <table border="1px" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                    <tr>
                        <td width="20%">换回统计(产品/规格)</td>
                        <td width="20%"><b>商铺</b></td>
                        <td width="20%"><b>单位</b></td>
                        <td width="10%"><b>单价</b></td>
                        <td width="15%"><b>总数量</b></td>
                        <td width="20%"><b>总金额</b></td>
                    </tr>
                </thead>
                <tbody class="f12">
                    <?php if(is_array($thIndata)): $i = 0; $__LIST__ = $thIndata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr >
                            <td rowspan="<?php echo ($d["count"]); ?>}"><?php echo ($d["goods_name"]); ?></td>
                            <?php if(is_array($d["data"])): $i = 0; $__LIST__ = $d["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ds): $mod = ($i % 2 );++$i;?><td rowspan="<?php echo count($ds['data']); ?>"><?php echo ($ds["cust_name"]); ?></td>
                                  <?php if(is_array($ds["data"])): $i = 0; $__LIST__ = $ds["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dds): $mod = ($i % 2 );++$i;?><td><?php echo ($dds["goods_unit"]); ?></td>
                                    <td><?php echo ($dds["singleprice"]); ?></td>
                                    <td><?php echo ($dds["sumnumber"]); ?></td>
                                    <td><?php echo number_format($dds['singleprice']*$dds["sumnumber"],2); ?>元</td>
                                    </tr><tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold" colspan="6">小结：<span style="color:blue;font-weight:bold"><?php echo ($d["totalmoney"]); ?> </span>元&nbsp;</td> 
                        </tr>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>                 
                       <td style="text-align:right;font-weight:bold" colspan="6">调入货总金额：<span style="color:red;font-weight:bold"><?php echo ($in_money); ?></span>元&nbsp;</td> 
                    </tr>
                </tbody>
                 <tfoot>
                </tfoot>
            </table>
            
            <table border="1px" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                    <tr>
                        <td width="20%">调出统计(产品/规格)</td>
                        <td width="20%"><b>商铺</b></td>
                        <td width="20%"><b>单位</b></td>
                        <td width="10%"><b>单价</b></td>
                        <td width="15%"><b>总数量</b></td>
                        <td width="20%"><b>总金额</b></td>
                    </tr>
                </thead>
                <tbody class="f12">
                    <?php if(is_array($thOutdata)): $i = 0; $__LIST__ = $thOutdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr >
                            <td rowspan="<?php echo ($d["count"]); ?>}"><?php echo ($d["goods_name"]); ?></td>
                            <?php if(is_array($d["data"])): $i = 0; $__LIST__ = $d["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ds): $mod = ($i % 2 );++$i;?><td rowspan="<?php echo count($ds['data']); ?>"><?php echo ($ds["cust_name"]); ?></td>
                                  <?php if(is_array($ds["data"])): $i = 0; $__LIST__ = $ds["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dds): $mod = ($i % 2 );++$i;?><td><?php echo ($dds["goods_unit"]); ?></td>
                                    <td><?php echo ($dds["singleprice"]); ?></td>
                                    <td><?php echo ($dds["sumnumber"]); ?></td>
                                    <td><?php echo $dds['singleprice']*$dds["sumnumber"] ?>元</td>
                                    </tr><tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                        <tr>
                            <td style="text-align:right;font-weight:bold" colspan="6">小结：<span style="color:blue;font-weight:bold"><?php echo ($d["totalmoney"]); ?> </span>元&nbsp;</td> 
                        </tr>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>                 
                       <td style="text-align:right;font-weight:bold" colspan="6">调出货总金额：<span style="color:red;font-weight:bold"><?php echo ($out_money); ?></span>元&nbsp;</td> 
                    </tr>
                </tbody>
                 <tfoot>
                </tfoot>
            </table>
            <!-- 调换货 -->

            
</div>    

<div class="widget">
    <div class="whead">
        
        <ul>
            <li class="li_2"><a href="<?php echo U('Dealer/CarSalesOrder/index');?>">返回</a>
            </li>
            
        </ul>
    </div>
</div> 
<div style="display: none;" class="widget" id="yudan">
</div>   
<br><br><br>
<script type="text/javascript">

    function shenhe(){
        var time=document.getElementById('time').value;
        var name=document.getElementById('name').value;
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("get", "shenhe_data.php?time="+time+"&name=" +name);
        xmlHttp.onreadystatechange = function () {
            //alert('111');
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                if(xmlHttp.responseText=='1'){
                    alert('操作成功，谢谢使用！');
                }
            }
        }
        xmlHttp.send();
    }
    function priew()
    {
       //  $("tbody td").css("fontWeight","bold")
       // var newstr = document.getElementById("priew").innerHTML;
       // var oldstr = document.body.innerHTML;
       // document.body.innerHTML = newstr;
       window.print();
       // document.body.innerHTML = oldstr;
       // $("tbody td").css("fontWeight","normal")
       // return false;
        }


    //搜索产品
    $("#brand").change(function (){
        submit_searchunit(this.value);
        });

    function submit_searchunit(gid) {
        var outunits='<option value="0">请选择</option>';
        $.ajax({
            url: "<?php echo U('Home/Ljcx/searchgoods');?>",
            type: "post",
            data: {
                bid:gid
            },
            dataType: "json",
            success: function (data) {
                for(var i=0; i<data.length;i++)
                {
                    outunits+="<option value='"+ data[i].goods_id +"'>"+ data[i].infos +"</option>";
                }
              $('#brands').html(outunits);
            }
        })
    }  
</script>


</body></html>