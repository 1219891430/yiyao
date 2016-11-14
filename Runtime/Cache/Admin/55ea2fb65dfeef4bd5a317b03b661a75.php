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
            
          
            
            var repertory_id = $("#repertory_id").val();

            var org_parent_id = $("#org_parent_id").val();

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
            if(org_parent_id=="0"){
                layer.tips('请选择经销商', '#send', {
                  tips: 3
                });
                return;
            }
            if(repertory_id=="0"){
                layer.tips('请选择仓库', '#send', {
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

            <form action="<?php echo U('Admin/DeliverOrgSummary/index');?>" method="get" id="formId">

                <li><input type="text" name="start_time" readonly="readonly"  class="form-control" value="<?php echo ($start); ?>" id="start_time" placeholder="起始时间" style="cursor:pointer;"></li>

                <li><input type="text" name="end_time" readonly="readonly"  class="form-control" value="<?php echo ($end); ?>" id="end_time" placeholder="结束时间"  style="cursor:pointer;"></li>

               

                <li>
                    <select id="repertory_id" class="form-control" name="repertory_id" style="width:140px;">
                        <option value="0">请选择仓库</option>
                        <?php if(is_array($repertoryList)): $i = 0; $__LIST__ = $repertoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo['repertory_id'] == $repertory_id): ?>selected="selected"<?php endif; ?> value="<?php echo ($vo["repertory_id"]); ?>"><?php echo ($vo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>


                <li>
                    <select class="form-control w200" id="org_parent_id" name="org_parent_id" style="width:140px;">
                        <option value="0">请选择经销商</option>
                        <?php if(is_array($orglist)): $i = 0; $__LIST__ = $orglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo["org_id"] == $org_parent_id): ?>selected="selected"<?php endif; ?> value="<?php echo ($vo["org_id"]); ?>"><?php echo ($vo["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>

                <!--<li class="li_1"><a onClick="priew()" href="#">打印</a></li>-->

                <li class="li_2"><a href="#" id="send">生成</a>

                <!-- <li class="li_3"><a href="#" id="zid">自定义筛选</a> -->

                
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
                        <td width="10%"><b>经销商</b></td>
                        <td width="10%"><b>产品</b></td>
                        <td width="10%"><b>销售</b></td>
                        <td width="10%"><b>退货</b></td>
                        <td width="10%"><b>调出</b></td>
                        <td width="10%"><b>换回</b></td>
                        <td width="10%"><b>出货</b></td>
                        <td width="10%"><b>小计</b></td>
                    </tr>
             </thead>
             <tbody class="f12">
            
                <tr >
                	<?php if($org_name){ ?>
                          <td rowspan="<?php echo count($data); ?>"><?php echo ($org_name); ?></td>
                    <?php } ?>
                        <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["goods_name"]); ?>/<?php echo ($vo["goods_spec"]); ?></td>
                                    <td>
                                    	<?php echo (number_format($vo["xiaoshoumoney"],2)); ?>
                                    	<br>
                                    	<?php echo ($vo["xiaoshounumberString"]); ?>
                                    
                                    </td>
                                    <td>
                                    	<?php echo ($vo["tuihuomoney"]); ?>
                                    	<br>
                                    	<?php echo ($vo["tuihuonumberString"]); ?>
                                    </td>
                                    <td>
                                    	<?php echo ($vo["tiaochumoney"]); ?>
                                    	<br>
                                    	<?php echo ($vo["tiaochunumberString"]); ?>
                                    </td>
                                    <td>
                                    	<?php echo ($vo["huanhuimoney"]); ?>
                                    	<br>
                                    	<?php echo ($vo["huanhuinumberString"]); ?>
                                    
                                    </td>
                                    <td>
                                    	<?php echo ($vo["chuhuo"]); ?>
                                    	
                                    </td>
                                    <td>
                                    	<?php echo (number_format($vo["xiaoji"],2)); ?>
                                    </td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                    <tr>                 
                       <td style="text-align:right;font-weight:bold" colspan="8">总金额：<span style="color:red;font-weight:bold"><?php echo (number_format($zongji,2)); ?></span>元</td> 
                    </tr>
                </tbody>
                 <tfoot>
                </tfoot>
            </table>

		</div>    

<div class="widget">
    <div class="whead">
        
        <ul>
            <li class="li_2"><a href="<?php echo U('Admin/DeliverApply/index');?>">返回</a>
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


    //部门联动
    $("#repertory_id").change(function(){
        var depot_id = $(this).val();
        if(depot_id ==0){
            $("#org_parent_id").html('<option value=0>请选择经销商</option>');
            return;
        }
        $.ajax({type:'post',url: "<?php echo U('Admin/Ajax/getDepotOrg');?>",data:{ depot_id:depot_id }, dataType:'json',timeout: 5000,
            error: function(){
            },
            success: function($r){
                $("#org_parent_id").html('<option value=0>请选择经销商</option>');
                if($r.status){
                    var html = '<option value=0>请选择经销商</option>';
                    $.each($r.rows,function(index,item){
                        html+= '<option value="'+item.org_id+'">'+item.org_name+'</option>';
                    });
                    $("#org_parent_id").html(html);
                }
            }
        });
    });


    //搜索产品
    $("#brand").change(function (){
        submit_searchunit(this.value);
        });

    function submit_searchunit(gid) {
        var outunits='<option value="0">请选择</option>';
        $.ajax({
            url: "<?php echo U('Admin/Ljcx/searchgoods');?>",
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

</body>
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
</html>