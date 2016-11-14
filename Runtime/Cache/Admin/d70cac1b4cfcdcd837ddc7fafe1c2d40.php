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
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/PCASClass.js"></script>
    <!-- <script type="text/javascript" src="/Public/assets/js/jquery-messages_cn.js"></script> -->
    <script type="text/javascript" src="/Public/assets/js/zstb.js"></script>
    <script type="text/javascript" src="/Public/assets/js/goods.js"></script>

    <link rel="stylesheet" type="text/css" href="http://developer.amap.com/Public/css/demo.Default.css" />

    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>

    <style type="text/css">.markerContentStyle span{font-family: "微软雅黑"}</style>
</head>

<style>
    body { margin: 0; font: 13px/1.5 "Microsoft YaHei", "Helvetica Neue", "Sans-Serif";min-width: 100% !important;background-color: #fff !important; }
</style>

<body>
<!--主体操作区域开始-->
<div class="row-fluid main-content" style="border: none">
<div style="margin-bottom: 20px;">
            	<input type="text" id="custName" value="<?php echo ($urlPara["custName"]); ?>" class="w200 form-control" /> <a class="btn btn-default" href="#" id="find_cust" role="button">查找</a>
            	</div>
            	<table class="table list_table" style="margin-top: 20px;" id="goods_table">
                <thead>
                <tr>
                    <td width="20%"><input type="checkbox" id="allshop"/></td>
                    <td width="25%">店铺名称</td>
                    <td width="55%">店铺地址</td>
                    
                </tr>
                </thead>
                <tbody>
                	<?php if(is_array($shops)): $i = 0; $__LIST__ = $shops;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="shop<?php echo ($vo["cust_id"]); ?>">	
                	<td><input class="shopId" cust_name="<?php echo ($vo["cust_name"]); ?>" address="<?php echo ($vo["address"]); ?>" value="<?php echo ($vo["cust_id"]); ?>" type="checkbox"/></td>
                    <td><?php echo ($vo["cust_name"]); ?></td>
                    <td><?php echo ($vo["address"]); ?></td>
                    
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                
                    

                
                
                </tbody>
                <tfoot>

                </tfoot>
</table>

<?php echo W('Page/page',array("/index.php/Admin/PresaleSummary/setting",$pnum,$pagelist,$urlPara));?>
</div>
<div style="padding-left: 30px;">
        
        <a data-dismiss="modal" id="add_form" class="btn btn-primary">
            <span>添加</span>
        </a>
</div>
<script type="text/javascript">

   

    // 查找
    $("#find_cust").click(function () {
        var name = $("#custName").val();
        
        window.location.href = "/index.php/Admin/PresaleSummary/setting?custName="+name;

    })
    
    $("#add_form").click(function(){
    	    
            $(".shopId:checked").each(function(){
            	var cust_name=$(this).attr("cust_name");
            	var address=$(this).attr("address");
            	var cust_id=$(this).val();
            	html="<tr>	"+
                	"<td><input class='shopId' checked='checked' value='"+cust_id+"' type='checkbox'/></td>"+
                    "<td>"+cust_name+"</td>"+
                    "<td>"+address+"</td>"+
                    
                    "</tr>"
            	
            	$('#custList', window.parent.document).append(html);
            	
            });
            var val="";
            
            $('.shopId', window.parent.document).each(function(){
            	
            	var vals=$(this).val();
            	val=val+vals+",";
            });
            val1=val.substr(0,val.length-1);
            window.location.href = "/index.php/Admin/PresaleSummary/setting?custName=<?php echo ($urlPara["custName"]); ?>&cust_ids="+val1;
           
    });

</script>


</body>
</html>