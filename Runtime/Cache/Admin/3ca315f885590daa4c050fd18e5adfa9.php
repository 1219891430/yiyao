<?php if (!defined('THINK_PATH')) exit();?>

<style>
    #showpic { z-index:99999; position:absolute; width:75; height:75; text-align:center; line-height:150%; border:2px solid #DEEFFA; padding:5px; background:#FFFFFF;}
</style>
<div id="showpic" style="display:none;"><img name="showpic_img" id="showpic_img" width="120" height="160"></div>
<div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>修改商品</span>
        </h4>
    </div>
    
    <form action="/index.php/Admin/GoodsInfo/editex" id="goods_form" method="post" enctype="multipart/form-data">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>商品名称</td>
                    <td colspan="3">
                        <input type="hidden" id="goods_id" name="goods_id" value="<?php echo ($res["goods_id"]); ?>" >
                        <input type="text" maxlength="100" value="<?php echo ($res["goods_name"]); ?>" id="goods_name" name="goods_name" class="w300 form-control"/>
                    </td>
                </tr>
                <tr>
                    <td>商品规格</td><td>
                    <input type="text" maxlength="50" id="goods_spec" value="<?php echo ($res["goods_spec"]); ?>" name="goods_spec" class="w170 form-control"/>
                </td>
                    <td>商品条码</td>
                    <td>
                        <input type="text" name="goods_code" id="barcode_input" value="<?php echo ($res["goods_code"]); ?>"  class="w170 form-control"/>
                        <a class="btn btn-success btn-sm" id="createBarCode">生成</a>
                    </td>
                </tr>


                <!--弹出-->


                <div class="modal fade" id="code128" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">条形码</h4>
                            </div>
                            <div class="modal-body text-center" id="barcode_img">
                            </div>
                            <div class="text-center">
                                <button type="button" id="downloadimg" class="btn btn-success">下载</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!------->



                <tr>
                    <td class="title-error">商品品牌</td><td>
                    <select id="goods_brand" name="goods_brand" class="w170 form-control">
                        <option value="0">请选择</option>
                        <?php if(is_array($brandRes)): $i = 0; $__LIST__ = $brandRes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i; if($res['brand_id']==$bvo["brand_id"]){ ?>
                        		<option value="<?php echo ($bvo["brand_id"]); ?>" selected="selected"><?php echo ($bvo["brand_name"]); ?></option>
                        	<?php }else{ ?>
                            <option  value="<?php echo ($bvo["brand_id"]); ?>"><?php echo ($bvo["brand_name"]); ?></option>
                            <?php } endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                    <td class="title-error">商品类别</td><td>
                    <select id="goods_class" name="goods_class" class="w170 form-control">
                        <option value="0">请选择</option>
                        <?php if(is_array($classRes)): $i = 0; $__LIST__ = $classRes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cvo): $mod = ($i % 2 );++$i; if($res['class_id']==$cvo["class_id"]){ ?>
                        		<option value="<?php echo ($cvo["class_id"]); ?>" selected="selected"><?php echo ($cvo["class_name"]); ?></option>
                        	<?php }else{ ?>
                            <option  value="<?php echo ($cvo["class_id"]); ?>"><?php echo ($cvo["class_name"]); ?></option>
                            <?php } ?>
                            
                            <?php if(is_array($cvo["class_list"])): $i = 0; $__LIST__ = $cvo["class_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i;?><option  <?php if($svo['class_id'] == $res['class_id']): ?>selected="selected"<?php endif; ?>  value="<?php echo ($svo["class_id"]); ?>">|------<?php echo ($svo["class_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                    
                </tr>
                <!--<tr>
                    <td>备注</td>
                    <td colspan="5"><input type="text" maxlength="15" name="gremark" class="w300 form-control"/></td>
                </tr>-->
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                	
                    <td class="title-error">小包装单位</td>
                    <td>
	                    <input type="text" placeholder="如:瓶,盒,包" maxlength="5" id="goods_unit_s" value="<?php echo ($res["goods_unit_s"]); ?>" name="goods_unit_s" class="w170 form-control"/>
                	</td>
                    
                    <td >小包装系数</td>
                    <td>
                    	<input type="text" maxlength="5"  value="<?php echo ($res["goods_convert_s"]); ?>" id="goods_convert_s" name="goods_convert_s" class="w170 form-control"  />
               		</td>
               		<!-- <td>进货价</td>
               		<td><input type="text" /></td> -->
                </tr>
                <tr>
                    <td>中包装单位</td>
                    <td>
                    	<input type="text"  placeholder="如:排,联包,提" maxlength="5" value="<?php echo ($res["goods_unit_m"]); ?>" id="goods_unit_m"  name="goods_unit_m" class="w170 form-control"/>
                	
                	</td>
                    <td>中包装系数</td><td>
                    <input type="text" id="goods_convert_m" name="goods_convert_m" value="<?php echo ($res["goods_convert_m"]); ?>" class="w170 form-control"/>
                </td>
                    
                </tr>
                <tr>
                    <td>大包装单位</td><td>
                    <input type="text" placeholder="如:箱" maxlength="5"  value="<?php echo ($res["goods_unit_b"]); ?>"  id="goods_unit_b" name="goods_unit_b" class="w170 form-control"/>
                </td>
                    
                    <td >大包装系数</td><td>
                    <input type="text"  maxlength="5" id="goods_convert_b"  value="<?php echo ($res["goods_convert_b"]); ?>" name="goods_convert_b" class="w170 form-control"/>
                </td>
                </tr>

                <tr>
                    <td>商品主图</td>
                    <td>
                        <input type="file" id="main_pic" name="main_pic" />
                        <?php if(!empty($res["main_img"])): ?><a id="show_img" href="javascript:void(0)" data-img="/Public/Uploads/goods/<?php echo ($res["main_img"]); ?>">查看主图</a><?php endif; ?>
                    </td>
                </tr>

                </tbody>
                <tfoot></tfoot>
            </table>
            <script id="editor" type="text/plain" style="height: 300px"></script>
        </div>
        <div class="error">
        </div>
    </form>
    <div class="modal-footer">
        <a href="#" class="btn btn-default"
           data-dismiss="modal">关闭
        </a>
        <a id="submit_goods" class="btn btn-primary">
            <span>修改</span>
        </a>
    </div>

    <!---ueditor--->
    <script type="text/javascript" charset="utf-8" src="/Public/assets/plugs/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/assets/plugs/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/Public/assets/plugs/ueditor/lang/zh-cn/zh-cn.js"></script>

    <script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>

    <script type="text/javascript">
        var ue = UE.getEditor('editor');

            ue.addListener("ready", function () {
                // editor准备好之后才可以使用
                ue.setContent('<?php echo ($res["goods_desc"]); ?>') ;
            });
            
        $("#createBarCode").click(function () {
            var code = $("#barcode_input").val()

            if ($.trim(code) == "") {
                alert('请输入正确的条码信息')
                return;
            }
            var data={code:code};
            $.get("/index.php/Admin/GoodsInfo/barcode",data, function (data) {
            	
                if (data.status) {
                    //$("#barcode_input").attr("disabled", true)

                    $("#barcode_img").html('<img src="/Public/'+ data.path +'">')

                    $("#code128").modal('show')

                } else {
                    alert(data.msg)
                    return;
                }
            })
        });


//      $("#createBarCode").click(function () {
//          var code = $("#barcode_input").val()
//          var id = $('#goods_id').val();
//
//          if ($.trim(code) == "") {
//              alert('请输入正确的条码信息')
//              return;
//          }
//
//          $.get("/index.php/Admin/GoodsInfo/barcode?code="+code + "&id=" + id, function (data) {
//              if (data.status) {
//                  //$("#barcode_input").attr("disabled", true)
//
//                  $("#barcode_img").html('<img src="/'+ data.path +'">')
//
//                  $("#code128").modal('show')
//
//              } else {
//                  alert(data.msg)
//                  return;
//              }
//          })
//      });

        $("#downloadimg").click(function () {

            var imgurl = $("#barcode_img img").attr('src')
            var code = $("#barcode_input").val()

            var a = $("<a></a>").attr("href", imgurl).attr("download", "条形码--"+code+".png").appendTo("body");

            a[0].click();
            a.remove();

        })



        $(function(){
            $('#show_img').hover(function(){
                var X = $(this).offset().top ;
                var Y = $(this).offset().left / 2 + 10;
                var imgsrc = $(this).attr('data-img');
                $("#showpic").css({left:Y,top:X,display:""});
                $("#showpic_img").attr("src",imgsrc);

            },function(){
                $("#showpic").css("display","none");
            });
        });
	    
        
        $("#submit_goods").click(function(){
        	
        	var goods_unit_b=$("#goods_unit_b").val();
        	var goods_unit_m=$("#goods_unit_m").val();
        	if($.trim(goods_unit_b)!=""){
        		if($.trim(goods_unit_m)==""){
        		     alert("填写大单位时，中单位不能为空");
        		     return;
        		}
        	}
        	
             //submit_goods_add();
        	 $("#goods_form").submit();
        })
        
        $("#goods_form").validate({
            /*submitHandler:function(){
                submit_goods_add();
            },*/
            rules:{
                goods_name:{
                    required:true,
                    maxlength:100
                },
                goods_spec:{
                    required:true
                },
                goods_code:{
                	required:true,
                    
                },
                goods_brand:{
                	valNeqZero:true,
                    required:true
                },
                goods_class:{
                	valNeqZero:true
                },
                goods_unit_s:{
                    required:true
                },
                goods_convert_s:{
                    required:true,
                    isIntGtZero:true,
                    digits:true
                },
//              goods_unit_m:{
//                  required:true
//                  
//              },
//              goods_convert_m:{
//                  required:true,
//                  isIntGtZero:true,
//                  digits:true
//              },
                /*goods_unit_b:{
                    required:true
                },
                goods_convert_b:{
                    required:true,
                    isIntGtZero:true,
                    digits:true
                }*/
            },
            messages:{
                goods_name:{
                    required:"商品名称不能为空"
                },
                goods_spec:{
                    required:"商品规格不能为空"
                },
                goods_brand:{
                    valNeqZero:"请选择商品品牌"
                },
                goods_class:{
                    valNeqZero:"请选择商品品类"
                },
                goods_code:{
                    required:"商品编码不能为空"
                },
                goods_unit_s:{
                    required:"小包装单位不能为空"
                },
                goods_unit_m:{
                    required:"中包装单位不能为空"
                },
                goods_unit_b:{
                    required:"大包装单位不能为空"
                },
                goods_convert_s:{
                    required:"小包装系数不能为空",
                    isIntEqZero:"小包装系数为正整数",
                    digits:"小包装系数为数字"
                },
                goods_convert_m:{
                    required:"中包装系数不能为空",
                    isIntEqZero:"中包装系数为正整数",
                    digits:"中包装系数为数字"
                },
                goods_convert_b:{
                    required:"大包装系数不能为空",
                    isIntEqZero:"大包装系数为正整数",
                    digits:"大包装系数为数字"
                }
            }
        })
        

        function submit_goods_add(){
            var data= $("#goods_form").serialize();
            
            ajaxDataAUD("/index.php/Admin/GoodsInfo/editex",data,true);
        }
        //检查重复的商品名和规格
        $("#gspec").blur(function() {
            var data={gname:$("#gname").val(),gspec:$("#gspec").val()}
            $.ajax({
                url:"/index.php/Admin/GoodsInfo/ajaxGoods",
                type:"post",
                dataType:"json",
                data:data,
                success:function(data){
                    if(data == false){
                        alert("名称规格有重复,请重新输入");
                        $("#gname").val('');
                        $("#gspec").val('');
                    }
                }
            })
        });
    </script>
</div>