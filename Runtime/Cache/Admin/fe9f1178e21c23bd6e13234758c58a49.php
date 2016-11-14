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
    <link href="/Public/assets/css/jquery-ui.min.css" rel="stylesheet">
    

    <!--<link href="/Public/css/manhuaDate.1.0.css" rel="stylesheet">-->
    <style type="text/css">
    .duizhangtitle{
        font-size:20px;
        padding-bottom:2px;
    }
    .duizhangtitle span{
        margin-right:10px;
        float:left;
        
    }
    .inputremark{
       width:700px;
       
    }
    
    .remark{
       margin:30px 0;
    }
    .list_table{
       border-top:solid 2px;
       border-right:solid 2px;
       border-bottom:solid 1px;
       border-left:solid 1px;
       
    }
    .list_table  td{
        text-align:center;
        border-left:solid 1px;
        border-bottom:solid 1px;
        vertical-align: middle;
        
    }
    .duizhangfooter{
       font-size:20px;
    }
    
    .bn{
      float:left;
      margin-top: -10px;
      margin-bottom:10px;
      background-color: #FF0000;
      color:#FFFFFF;
    }
    .bn:hover{
      
      background-color: #990000;
      color:#FFFFFF;
    }
    .title{
      text-align: center;
      padding-top:50px;
      font-size: 24px;
    }
    
    .tishi{
      font-size: 14px;
      color:red;
    }
    </style>
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    
    <!--<script type="text/javascript" src="/Public/js/bootstrap.min.js"></script>-->
    <!--<script type="text/javascript" src="/Public/js/manhuaDate.1.0.js"></script>-->
    <!-- <script type="text/javascript" src="/Public/js/jquery-messages_cn.js"></script> -->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
<!--    <script type="text/javascript" src="/Public/assets/js/switch/jquery-ui.min.js"></script>
-->  <!--<script type="text/javascript" src="/Public/assets/js/jquery-ui-slide.min.js"></script>
  <script type="text/javascript" src="/Public/assets/js/jquery-ui-timepicker-addon.js"></script>-->
  
  
<!--  <link rel="stylesheet" type="text/css" href="/Public/assets/css/jquery-ui.css" />
-->  <script language="javascript" type="text/javascript" src="/Public/assets/My97DatePicker/WdatePicker.js"></script>
  <script type="text/javascript">
//$(function(){
//
//  $('#end_date').datetimepicker({
//      showSecond: true,
//      dateFormat: 'yy-mm-dd',
//      timeFormat: 'hh:mm:ss'
//    });
//});
  </script> 
  <style type="text/css"> 
  /*.table thead tr th, .table tbody tr th, .table tfoot tr th, .table thead tr td, .table tbody tr td, .table tfoot tr td {  padding:0px; height:auto; vertical-align:middle; line-height:normal; }*/
    </style>
</head>
<body>

<div class="title"><span><?php echo ($staff_name); ?>车销对账明细</span></div>
<div class="main-container">
    <div style="margin:100px;margin-bottom: 0px;margin-top: 50px;">
            <!--右侧查询结束-->
            <input type="hidden" id="staff_id" value="<?php echo ($staff_id); ?>"/>
            <input type="hidden" id="date" value="<?php echo ($date); ?>"/>
            <div class="duizhangtitle"><span>对账时间段：</span>  <span> <?php echo ($start_date); ?>--<input type="text" id="end_date" readonly="readonly" value='<?php echo ($end_date); ?>' onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="margin-top: -6px;  border-style:groove ; border-width:1px; background-color: #FFFFFF; cursor: pointer" /></span></div>
            <a class="btn btn-default bn" href="#" id="find" role="button">更新数据</a>
            <span class="tishi">（提示：对截止时间做更改后必须点击更新数据按钮对统计数据进行更新！）</span>
            <!--表格查询开始-->
            
           
            <table class="table list_table" id="tb">
                <thead>
                <tr>
                    <td width="6%">品牌</td>
                    <td width="8%">产品</td>
                    <td width="5%">规格</td>
                    <td width="7%">上次车存</td>
                    <td width="6%">出库</td>
                    <td width="5%">退库</td>
                    <td width="5%">调出</td>
                    <td width="5%">换回</td>
                    
                    <!--<td width="5%">预存款</td>-->
                    <!--<td width="5%">陈列</td>-->
                    <td width="5%">促销品</td> 
                    <td width="5%">退货</td>
                    
                    <td width="5%">销售</td>
                    <!--<td width="4%">价格</td>
                    <td width="5%">数量</td>-->
                    <td width="5%">小计(元)</td>
                    <td width="7%">剩余车存</td>
                </tr>
                </thead>
                <tbody>
                   <?php $cc=1; ?>
                <?php if(is_array($BrandData)): $i = 0; $__LIST__ = $BrandData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voBrand): $mod = ($i % 2 );++$i;?><tr>
                  <td rowspan="<?php echo count($voBrand['data']); ?>" style="vertical-align: middle;"><?php echo ($voBrand["brand_name"]); ?></td>
                  
                      <?php if(is_array($voBrand['data'])): $i = 0; $__LIST__ = $voBrand['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["goods_name"]); ?></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["goods_spec"]); ?></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["today_carport"]); ?></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["CKNumber"]); ?><br/></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["TKNumber"]); ?></td>
                         <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["TCHNumber"]); ?></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["HHHNumber"]); ?></td>
                        
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["CXNumber"]); ?></td> 
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["THNumber"]); ?></td>
                        
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo ($vo["XSNumber"]); ?></td>
                    
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" ><?php echo (sprintf('%.2f',$vo["xiaoji"])); ?></td>

                  <?php $a=0; ?>
                  
                  
                    <input 
                          type="hidden" 
                          class="goods"
                          brand_id="<?php echo ($voBrand["brand_id"]); ?>"
                          brand_name="<?php echo ($voBrand["brand_name"]); ?>"
                          goods_id="<?php echo ($vo["goods_id"]); ?>"
                          goods_name="<?php echo ($vo["goods_name"]); ?>"
                          goods_spec="<?php echo ($vo["goods_spec"]); ?>"
                          today_carport="<?php echo ($vo["today_carport"]); ?>"
                          CKNumber="<?php echo ($vo["CKNumber"]); ?>"
                          TKNumber="<?php echo ($vo["TKNumber"]); ?>"
                          
                          TCHNumber="<?php echo ($vo["TCHNumber"]); ?>"
                          HHHNumber="<?php echo ($vo["HHHNumber"]); ?>"
                          CXNumber="<?php echo ($vo["CXNumber"]); ?>"
                          THNumber="<?php echo ($vo["THNumber"]); ?>"
                          SSNumber="<?php echo ($vo["SSNumber"]); ?>"
                          SSNumber_int="<?php echo ($vo["SSNumber_int"]); ?>"
                          XSNumber="<?php echo ($vo["XSNumber"]); ?>"
                           
                          xiaoji="<?php echo ($vo["xiaoji"]); ?>"                  
                    />
                        
                        <!--<td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;"><?php echo number_format(($vv["singleprice"]),2,'.',''); ?></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;"><?php echo ($vv["number"]); echo ($vv["unit_name"]); ?></td>
                        <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;"><?php echo number_format(($vv["singleprice"]*$vv["number"]),2,'.',''); ?></td>-->
                        <?php if($a==0){ ?>
                          <td data-c="<?php echo ($cc); ?>" style="vertical-align: middle;" "><?php echo ($vo["SSNumber"]); ?></td>
                        <?php } ?>
                          
                    </tr>
                    <tr>
                      <?php $a++; endforeach; endif; else: echo "" ;endif; ?>
                       
                       
                      <?php if($cc==1){ $cc=2; }else{ $cc=1; } ?>    
                
                
                 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            
    </div>
    <div style="margin:0 100px;">
            <!--右侧查询结束-->
            <div class="duizhangtitle"><span>赊款统计</span></div>
            <!--表格查询开始-->
            <table class="table list_table bon">
                <thead>
                <tr>
                  
                   <td width="8%">商铺</td>
                    <td width="8%">商铺老板</td>
                    <td width="8%">总金额</td>
                    <td width="8%">已付金额</td>
                    <td width="10%">赊款金额</td>
                    
                </tr>
                </thead>
                <tbody>
                <?php $cc=1; ?>
                <?php if(is_array($sheqianData)): $i = 0; $__LIST__ = $sheqianData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr data-c="<?php echo ($cc); ?>">
                  <input
                   type="hidden" 
                   class="sheqian"
                   cust_name="<?php echo ($vo["cust_name"]); ?>"
                   cust_contact="<?php echo ($vo["cust_contact"]); ?>"
                   cust_id="<?php echo ($vo["cust_id"]); ?>"
                   order_total_money="<?php echo ($vo["total_money"]); ?>"
                   order_real_money="<?php echo ($vo["real_money"]); ?>"
                   qiankuan="<?php echo ($vo["qiankuan"]); ?>"
                   />
                   <td><?php echo ($vo["cust_name"]); ?></td>
                    <td><?php echo ($vo["cust_contact"]); ?></td>
                    <td><?php echo ($vo["total_money"]); ?></td>
                    <td><?php echo ($vo["real_money"]); ?></td>
                    <td><?php echo ($vo["qiankuan"]); ?></td>
                    
                </tr>
                 <?php if($cc==1){ $cc=2; }else{ $cc=1; } endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot>
                
                </tfoot>
            </table>
            
            
            
            
            

            <div class="duizhangfooter">
                订单总额：<?php echo (number_format($resSum["totalmoney"],2)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                订单实收：<?php echo (number_format($resSum["realmoney"],2)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                赊款金额：<?php echo (number_format($qianKuanMoney,2)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                退货退款：<?php echo (number_format($tui_total_money,2)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                业务员清欠金额：<?php echo (number_format($total_staff_qkmoney,2)); ?>
                <br>
                
                内勤清欠金额：<?php echo (number_format($total_admin_qkmoney,2)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp; 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                欠款撤销金额：<?php echo (number_format($qiankuanChexiao,2)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                调换货差价(<?php if($changeMoney>0){ ?>收入<?php }else{ ?>支出 <?php } ?>)：<?php $changeMoney1=abs($changeMoney);echo number_format($changeMoney1,2,'.',''); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               
                <br>
                对账金额：<?php echo (number_format($duizhangMoney,2)); ?>
            </div>
            
            <input type="hidden" id="order_total_money" value="<?php echo (number_format($resSum["totalmoney"],2)); ?>"/>
            <input type="hidden" id="order_real_money" value="<?php echo (number_format($resSum["realmoney"],2)); ?>"/>
            <input type="hidden" id="qiankuan" value="<?php echo (number_format($qianKuanMoney,2)); ?>"/>
            
            <input type="hidden" id="tuihuotuikuan" value="<?php echo (number_format($tui_total_money,2)); ?>"/>
                 <!-- 业务员清欠-->
            <input type="hidden" id="qingqian" value="<?php echo (number_format($total_staff_qkmoney,2)); ?>"/>
                <!-- 内勤清欠-->
            <input type="hidden" id="inHouseTotal" value="<?php echo (number_format($total_admin_qkmoney,2)); ?>"/>

            <input type="hidden" id="qiankuanChexiao" value="<?php echo (number_format($qiankuanChexiao,2)); ?>"/>
            <input type="hidden" id="changeMoney" value="<?php echo (number_format($changeMoney,2)); ?>"/>
            <div class="remark">备注：<input type="text" class="inputremark" id="inputremark"/></div>

       <div class="modal-footer" style="margin-bottom:100px;">
        
       
        <a id="close" class="btn">
            <span>关闭</span>
        </a>
        <!--<a id="print" class="btn btn-primary">
            <span>打印</span>
        </a>
        
        <a id="export" class="btn btn-primary">
            <span>导出</span>
        </a>-->
        
        <?php if($isGuoQi==0){ ?>
        <a id="check" class="btn btn-primary">
            <span>确认对账</span>
        </a>
        <?php } ?>
       
    
      </div>
    </div>

</div>
<div id="await" class="await">
    <span> <img src="/Public/assets/images/loding.gif" title="加载图片"/></span>
</div>
<!--弹出层开始-->
<div class="modal" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modal-con" class="modal-dialog modal_1050">
    </div>
</div>
<script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
<script type="text/javascript">  

    $("#export").click(function(){
    	 var staff_id=$("#staff_id").val();
    	 location.href="/index.php/Admin/DeliverSummary/chexiaoduizhang/export/export/staff/"+staff_id;
    });
    
    $("#print").click(function(){
    	 var staff_id=$("#staff_id").val();
    	 location.href="/index.php/Admin/DeliverSummary/chexiaoduizhang/print/print/staff/"+staff_id;
    });

    $("#check").click(function(){
    	
      //var end_date=$("#end_date").val();
    	
      var staff_id=$("#staff_id").val();
      var end_date='<?php echo ($end_date); ?>';
      var tuihuotuikuan=$("#tuihuotuikuan").val(); 
      
      var inHouseTotal=$("#inHouseTotal").val(); 
      
      var qiankuan=$("#qiankuan").val(); 
      var qingqian=$("#qingqian").val(); 
      var order_real_money=$("#order_real_money").val(); 
      var order_total_money=$("#order_total_money").val();
      
      var qiankuanChexiao=$("#qiankuanChexiao").val();
      var start_time_int="<?php echo ($start_time_int); ?>";
      var changeMoney=$("#changeMoney").val();
      
      
      
      var goods=$(".goods");
      
      var goodsJson=new Array();
      var i=0;
      while(i<goods.length){
        goodss=goods.eq(i);
        goodsJson[i]={
            "goods_id":goodss.attr("goods_id"),
            "goods_name":goodss.attr("goods_name"),
            "goods_spec":goodss.attr("goods_spec"),
            "brand_id":goodss.attr("brand_id"),
            "brand_name":goodss.attr("brand_name"),
            "lastNumber":goodss.attr("today_carport"),
            "CKNumber":goodss.attr("CKNumber"),
            "TKNumber":goodss.attr("TKNumber"),
            "TCHNumber":goodss.attr("TCHNumber"),
            "HHHNumber":goodss.attr("HHHNumber"),
           
            "CXNumber":goodss.attr("CXNumber"),
            "THNumber":goodss.attr("THNumber"),
            "SSNumber":goodss.attr("SSNumber"),
            "SSNumber_int":goodss.attr("SSNumber_int"),
            "XSNumber":goodss.attr("XSNumber"),
            
            "xiaoji":goodss.attr("xiaoji")
        };
        i++;
      }
      
        var sheqians=$(".sheqian");     
      var sheqiansJson={}
      var i=0
      while(i<sheqians.length){
        sheqianss=sheqians.eq(i);
        sheqiansJson[i]={
            "cust_name":sheqianss.attr("cust_name"),
            "cust_contact":sheqianss.attr("cust_contact"),
            "order_total_money":sheqianss.attr("order_total_money"),
            "order_real_money":sheqianss.attr("order_real_money"),
            "cust_id":sheqianss.attr("cust_id"),
            "qiankuan":sheqianss.attr("qiankuan")
        };
        i++;
      }

	
	  
      var data={
      	  "end_date":end_date,
          "order_total_money":order_total_money,
          "inHouseTotal":inHouseTotal,
          
          "order_real_money":order_real_money,
          "changeMoney":changeMoney,
          "start_time_int":start_time_int,
          
          "qingqian":qingqian,
          "qiankuan":qiankuan,
          
          "tuihuotuikuan":tuihuotuikuan,
          "remark":$("#inputremark").val(),
          "staff_id":staff_id,
          'qiankuanChexiao':qiankuanChexiao,
          "date":end_date,
          "goods":goodsJson,
          "sheqians":sheqiansJson
          
         
          
      };
      
      
      
//       $.post("/index.php/Admin/DeliverSummary/setDuizhang",data,function(res){
//    // //$.post("/index.php/Admin/DeliverSummary/test",data,function(res){
//      
//        if(res==1){
//          alert("确认成功");
//          window.opener=null;
//          window.location.href="/index.php/Admin/DeliverSummary/index";
//          //window.close();
//        }else{
//          alert("确认失败");
//        }
//       });

      //确认对账按钮加遮罩层 kxf  2016-05-26
      $.ajax({
            url:"/index.php/Admin/DeliverSummary/setDuizhang",
            type:"post",
            data:data,
            dataType:"text",
            beforeSend:function(){
                $(".await").show();
            },
            success:function(res){
            	
                if( res==1)
                {
                      alert("确认成功");
                      window.opener=null;
                      window.location.href="/index.php/Admin/DeliverSummary/index";
                }else if(res==2){
                	alert("请刷新本页面后再进行对账！");
                    $(".await").hide();
					//location.reload(true);
                }else
                {
                    alert("确认失败");
                    $(".await").hide();
                }
            }
        })
      
      
    });
    $("#find").click(function(){
      var end_date=$("#end_date").val();
      var start_date='<?php echo ($start_date); ?>';
      var end_date = Date.parse(end_date);
      var start_date = Date.parse(start_date);
      
      var time = Date.parse(new Date());
    time = time / 1000;
      
        end_date = end_date / 1000;
        start_date = start_date / 1000;
        if(end_date<start_date||end_date>time){
          alert("请正确选择时间（不能晚于当前时间或早于上次对账时间）！");
          return;
        }
    window.location.href="/index.php/Admin/DeliverSummary/duizhang/staff/<?php echo ($staff_id); ?>/end_date/"+end_date;
    });
    $("#close").click(function(){
      window.opener=null;
    //window.open('','_self');
    window.location.href="/index.php/Admin/DeliverSummary/index";
    });
    $("[data-c='2']").css("background-color","#DDEEEE");
    
    
    
</script>

</body>
</html>