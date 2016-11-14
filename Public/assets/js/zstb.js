/**
 * Created by rj02 on 14-10-8.
 */
$(function(){
    //左侧高度自适应
    leftMenuAuto();
    //左侧点击事件
    $("#main_left li").click(function(){
        var index=$(this).index();
//        var li_length=$("#main_left > li").length;
//        var li_height=$(this).height();
        $(this).siblings("li").removeClass("active").find(".main-left-menu").hide()
        if($("#main_left li > a").hasClass("collapse-menu-bg"))
        {
            $("#main_left > li > a").addClass("collapse-menu").removeClass("collapse-menu-bg")
        }
            if( $(".main-left-menu:visible",this).length==1)
            {
                $(".main-left-menu",this).hide();
                $(this).removeClass("active")
            }
            else
            {
                $(this).addClass("active")
                $("#main_left > li > a").eq(index) .removeClass("collapse-menu").addClass("collapse-menu-bg")
                $(".main-left-menu",this).show()
            }
//        var menu_height=(li_length-index+1)*li_height;
//        var sub_height=$(".main-left-menu",this).height();
//        alert(sub_height+"---"+menu_height);
//            $(".main-left").height($(".main-left").height()+sub_height-menu_height);
    })
})
    $("#top,.main-right").click(function(){
            $("#main_left li").siblings("li").removeClass("active").find(".main-left-menu").hide()
            if($("#main_left li > a").hasClass("collapse-menu-bg"))
            {
                $("#main_left > li > a").addClass("collapse-menu").removeClass("collapse-menu-bg")
            }
        })
    function leftMenuAuto(){//左侧自适应浏览器跟内容

        var top=$("#top").height();
        var b_hieght=$("body,html").height()-top;
        var w_hieght=$(window).height()-top;
        if(b_hieght>=w_hieght)
        {
            $(".main-left").height(b_hieght)
        }
        else{
            $(".main-left").height(w_hieght)
        }
    }
    function check_num(obj){   //第一个数不能为0
//        if(obj.value.length==1){
//            obj.value=obj.value.replace(/[^1-9]/g,'')
//        }
        if(obj.value.length>=2)
        {
            if(obj.value.substr(0, 1)==0)
                obj.value=0;
            else
            obj.value=obj.value.replace(/[^0-9]/g,'')
        }
        else{
            obj.value=obj.value.replace(/\D/g,'')
         }
    }
    //调取get页面无参数
    function ajaxData(url)
    {
        $.ajax({
            url:url+"?r="+new Date().getTime(),
            type:"get",
            dataType:"html",
            beforeSend:function(){
                /*$(".await").show();*/
            },
            success:function(data){
                if(data.length!=0){
                    $("#modal-con").empty().append(data);
                    $("#myModal").modal({backdrop:"static"});
                }
                /*$(".await").hide();*/
            }
        })
        $("#myModal").modal({backdrop:"static"});
    }
    //调取get页面有参数
    function ajaxDataPara(url, data)
    {
        $.ajax({
            url:url+"?r="+new Date().getTime(),
            type:"get",
            dataType:"html",
            data:data,
            beforeSend:function(){
                /*$(".await").show();*/
            },
            success:function(data){
                if(data.length!=0){
                    $("#modal-con").empty().append(data);
                    $("#myModal").modal({backdrop:"static"});
                }
                /*$(".await").hide();*/
            }
        })
        $("#myModal").modal({backdrop:"static"});
    }
    //post查询结果，只显示数值
    function postCountData(url,para,element)
    {
        $.post(url,para,function(data){
            var aData=eval(data)
            $(element).text(aData["data"]);
        },"json")
    }
    //添加、修改、删除、数据ajax
    function ajaxDataAUD(url, data,is_reload)
    {
        $.ajax({
            url:url,
            type:"post",
            dataType:"json",
            data:data,
            beforeSend:function(){
                $(".await").show();
            },
            success:function(data){
                
                if(data["res"]==1)
                {
                	alert(data["info"]);
                    if(is_reload)
                        reload();
                    else
                        $(".await").hide();
                }
                else if(data["res"]==2){
                    $(".await").hide();
                }else{
                	alert(data["info"]);
					if(is_reload) location.reload(true);
                	$(".await").hide();
                }
            }
        })
//        if(is_reload)
//        {
//            $(".await").show();
//            window.setTimeout(reload, 1000);
//        }
    }

    //页面刷新
    function reload()
    {
        location.reload();
    }
    //根据元素选中状态
    function checkboxSelected(selectId,selector)
    {
        var depid=$(selectId).val();
        $(selector).each(function(){
            if($(this).val()==depid)
            {
                $(this).attr("selected","selected");
            }
        })
    }
    //根据json值选中状态
    function JsonSelected(select_id,selector)
    {
        $(selector).each(function(){
            if($(this).val()==select_id)
            {
                $(this).attr("selected","selected");
            }
        })
    }
//checked数组赋予checkbox
function checkManyCheckbox(optionData,FormCheckbox){
    var checkbox=$("#"+FormCheckbox+" input[type='checkbox']");
    for(var i=0;i<optionData.length;i++)
    {
        checkbox.each(function(){
            if($(this).val()==optionData[i])
            {
                $(this).attr("checked",true);
            }
        })
    }
}
//获取所有选中的checked，转化成字符串
function checkBox(checkboxForm){
    var str="";
    $("#"+checkboxForm+" :checked").each(function(i){
        if(i==0){
            str = $(this).val();
        }else{
            str += ","+$(this).val();
        }
    });
    return str;
}
//导入excel表
function importExcel(){
$('#excel_import').click(function(){
    $('#myModel').find('.wait').show();
    $('.waits').show();
    if(verify_execl()){
        var options={
            dataType:'json',
            //data:{cid:cid},
            success:function(result){
                $('#myModel').find('.wait').hide();
                $('.waits').hide();
                if(result['res'] == 1){
                    $('#import_execl_error').html(result['info']).show();
                }else{
                    $('#import_execl_error').html(result['info']).show();
                }
            },
            error:function(){
                $('#import_execl_error').html("数据错误").show();
                $('#myModel').find('.wait').hide();
                $('.waits').hide();
            },
            complete:function(){
                $('#myModel').find('.wait').hide();
                $('.waits').hide();
            }
        };
        $('#uploadform').ajaxSubmit(options);
    }else{
        $('#myModel').find('.wait').hide();
        $('.waits').hide();
    }
    return false;
});
}

//execl验证
function verify_execl(){
    var flag = false;
    var excel = $("#excel_upload").val();
    if (excel == "" || excel == null) {
        $('#import_execl_error').html('请选择文件').show();
        return flag;
    } else {
        var index = excel.lastIndexOf(".");
        if (index < 0) {
            $('#import_execl_error').html('上传的文件格式不正确，请选择2007Excel文件(*.xlsx)或者97-2003Excel文件(*.xls)！').show();
            return flag;
        } else {
            var ext = excel.substring(index + 1, excel.length);
            //console.log(index,ext);
            if (ext != "xlsx"&&ext != "xls") {
                $('#import_execl_error').html('上传的文件格式不正确，请选择2007Excel文件(*.xlsx)或者97-2003Excel文件(*.xls)！').show();
                return flag;
            }else{
                $('#import_execl_error').empty().hide();
            }
        }
    }
    flag = true;
    return flag;
}


/////////////////////////商品订单页面函数开始/////////////////////////


//验证是不是浮点数
function isfloat(oNum){
    if(!oNum) return false;
    var strP=/^\d+(\.\d+)?$/;
    if(!strP.test(oNum)) return false;
    try{
        if(parseFloat(oNum)!=oNum) return false;
    }catch(ex){
        return false;
    }
    return true;
}
//验证正整数
function isNumber(oNum){
    if(!oNum) return false;
    var strP=/^\+?[0-9][0-9]*$/; //正整数,第一位不能为0
    if(!strP.test(oNum)) return false;
    return true;
}
//选中所有商品
function selectAll(){
    $("#choice_all").click(function(){
        if($(this).attr("checked")!="checked")
            $(".goods_search tbody input").removeAttr("checked");
        else
            $(".goods_search tbody input").attr("checked","checked");
    })
}

//选中所有店铺
function selectShopsAll(){
    $("#shops_choice_all").click(function(){
        if($(this).attr("checked")!="checked")
            $(".shops_search tbody input").removeAttr("checked");
        else
            $(".shops_search tbody input").attr("checked","checked");
    })
}

function addGoodsToTable()
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods();
    })
}
//店铺销量上传版本
function addGoodsToTable1()
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods1();
    })
}
//库存调拨版本
function addGoodsToTable2(url)
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods2(url);
    })
}
//车存申请版本
function addGoodsToTable3(url)
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods3(url);
    })
}
function addGoodsToTable4()
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods4();
    })
}

function addGoodsToTable5()
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods5();
    })
}
function addGoodsToTable6()
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods6();
    })
}

/**
 * 添加陈列商品
 * @param url
 * @param _arr          陈列形式
 * @param _unit_arr     陈列单位
 */
function addGoodsToTable7(url,_arr, _unit_arr)
{
    $("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods7(url, _arr, _unit_arr);
    })
}

/**
 * 陈列兑付商品
 * @param url
 * @param obj  + 元素
 */
function addGoodsToTable8(url,obj)
{
    $("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods8(url,obj);
    })
}
/**
 * 预单修改
 * @param url
 * @param obj  + 元素
 */

function addGoodsToTable9(url)
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods9(url);
    })
}


// 预单退货版本
function addGoodsToTable11()
{
	$("#goods-add").unbind();
    $("#goods-add").click(function(){ addGoods11(); });
}

/**
 * 添加店铺
 * @param url
 */
function addShopsToTable(url)
{
    $("#shops-add").unbind();
    $("#shops-add").click(function(){
        addShops(url);
    })
}


//查找产品初始化页面
function setGoodsPageData()
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable();
}
//店铺销量上传版本
function setGoodsPageData1()
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable1();
}
//库存调拨版本
function setGoodsPageData2(url)
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable2(url);
}
//车存申请版本
function setGoodsPageData3(url)
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable3(url);
}

//查找产品初始化页面(进价版本)
function setGoodsPageData4()
{
	
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable4();
}
//批量参加预付款活动版本
function setGoodsPageData5()
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable5();
}
function setGoodsPageData6()
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable6();
}

/**
 * 付费陈列添加陈列商品
 * @param url
 * @param _arr
 * @param _unit_arr
 */
function setGoodsPageData7(url,_arr, _unit_arr)
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty();
    $(".check_mt0").removeAttr('checked');
    $("#goods_div").show();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable7(url, _arr, _unit_arr);
}

/**
 * 陈列兑付商品
 * @param url
 * @param obj + 元素
 */
function setGoodsPageData8(url,obj)
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty();
    $(".check_mt0").removeAttr('checked');
    $("#goods_div").show();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable8(url,obj);
}
/**
 * 预单修改
 * @param url
 * @param obj + 元素
 */

function setGoodsPageData9(url)
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable9(url);
}

// 预单退货版本
function setGoodsPageData10()
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();
    closeGoodsDiv();
    addGoodsToTable11();
}

/**
 * 设置店铺
 * @param url
 */
function setShopsPageData(url)
{
    //$("#brand").val(0);
    $("#search_val").val("");
    $("#shops_search tbody").empty();
    $(".shops_div").show();
    $(".check_mt0").removeAttr('checked');
    selectShopsAll();//点击选中事件
    closeShopsDiv();
    addShopsToTable(url);
}


/**
 * 消除全选事件
 * @param obj
 */
function checkAllfunc(obj) {
    var _cur_tbody = $(obj).parent().parent().parent(),_cur_tr_len = _cur_tbody.find('tr').length;     //当前列表的长度
    var _cur_checked_len = _cur_tbody.find("input[class='check_mt0']:checked").length;
    if(_cur_tr_len - _cur_checked_len != 0) {
        _cur_tbody.parent().find("thead .tc .check_mt0").prop("checked",false);
    } else {
        _cur_tbody.parent().find("thead .tc .check_mt0").prop("checked",true);
    }
}

function closeGoodsDiv()
{
	$("#goods-close").unbind();
    $("#goods-close").click(function(){
        $("#goods_div").hide();
    })
}

/**
 * 关闭指定店铺弹窗
 */
function closeShopsDiv()
{
    $("#shops-close").unbind();
    $("#shops-close").click(function(){
        $(".shops_div").hide();
    })
}

//查找货物,参数brand为品牌id，goods为商品名称
function findGoods(url,brand,goods,depot_id) // 添加仓库限制
{
    $.ajax({
        url:url,
        type:"post",
        dataType:"json",
        data:{brand:brand,goods:goods,depot_id:depot_id},
        beforeSend:function(){
            //$(".await").show();
        },
        success:function(data){
            $("#goods_search tbody").empty()
            if(data["res"]==1)
            {
                var con="";
                $.each(data["data"],function(i){
                    var convert_data=JSON.stringify(data['data'][i]['convert_data']);
                    con+="<tr><td class='tc'><input class='check_mt0' value='"+data["data"][i]["goods_id"]+"' type='checkbox' onclick='checkAllfunc(this)'></td><td>"+data["data"][i]['goods_name']+data['data'][i]['goods_spec']+"/"+data['data'][i]['goods_convert']+"</td>" +
                        "<input class='goods_convert' type='hidden' value='"+data['data'][i]['goods_convert']+"'><input class='goods_name' type='hidden' value='"+data['data'][i]['goods_name']+"'><input class='goods_spec' type='hidden' value='"+data['data'][i]['goods_spec']+"'><input class='cv_data' type='hidden' value='"+convert_data+"'></tr>";
                })
                $("#goods_search tbody").append(con);
            }
            else
                alert("暂无数据");
            //$(".await").hide();
        }
    })
}
//查找货物,参数brand为品牌id，goods为商品名称,出库，数量是否充足
function findOutGoods(url,brand,goods,depot)
{
    $.ajax({
        url:url,
        type:"post",
        dataType:"json",
        data:{brand:brand,goods:goods,depot:depot},
        beforeSend:function(){
            //$(".await").show();
        },
        success:function(data){
            $("#goods_search tbody").empty()
            if(data["res"]==1)
            {
                var con="";
                $.each(data["data"],function(i){
                    var convert_data=JSON.stringify(data['data'][i]['convert_data']);
                    con+="<tr><td class='tc'><input class='check_mt0' value='"+data["data"][i]["goods_id"]+"' type='checkbox'></td><td>"+data["data"][i]['goods_name']+data['data'][i]['goods_spec']+"/"+data['data'][i]['goods_convert']+"</td>" +
                        "<input class='goods_convert' type='hidden' value='"+data['data'][i]['goods_convert']+"'><input class='goods_name' type='hidden' value='"+data['data'][i]['goods_name']+"'><input class='goods_spec' type='hidden' value='"+data['data'][i]['goods_spec']+"'><input class='cv_data' type='hidden' value='"+convert_data+"'></tr>";
                })
                $("#goods_search tbody").append(con);
            }
            else
                alert("暂无数据");
            //$(".await").hide();
        }
    })
}

/**
 * 查找店铺
 * @param url
 * @param provice
 * @param city
 * @param district
 * @param search_type
 * @param search_val
 */
function findShops(url,province,city,district,search_type,search_val)
{
    $.ajax({
        url:url,
        type:"post",
        dataType:"json",
        data:{province:province,city:city,district:district,search_type:search_type,search_val:search_val},
        beforeSend:function(){
            $(".await").show();
        },
        success:function(data){
            $("#shops_search tbody").empty();
            if(data["res"]==1)
            {
                var con="";
                $.each(data["data"],function(i){
                    var convert_data=JSON.stringify(data['data'][i]['convert_data']);
                    con+="<tr><td class='tc'><input class='check_mt0' value='"+data["data"][i]["cust_id"]+"' type='checkbox' onclick='checkAllfunc(this)'></td><td class='shops_name'>"+data["data"][i]['cust_name']+"</td><td class='contacts'>"+data["data"][i]['contact']+"</td><td class='telephones'>"+data["data"][i]["telephone"]+"</td>";
                    if(data["data"][i]["staffs"].length > 0)
                    {
                        var _str = "",_staffs = data["data"][i]["staffs"];
                        for(var j = 0,_lens = _staffs.length; j < _lens; j++)
                        {
                            if(j === _lens -1)
                            {
                                _str += _staffs[j]["staff_name"];
                            }
                            else
                            {
                                _str += _staffs[j]["staff_name"]+"，";
                            }
                        }
                        con += "<td class='staffs'>"+_str+"</td>";
                    }
                    else
                    {
                        con += "<td class='staffs'></td>"
                    }
                    con += "</tr>";
                });
                $("#shops_search tbody").append(con);
            }
            else
            {
                alert("暂无数据");
            }

            $(".await").hide();
        }
    })
}


//选中商品，添加到添加页面中
function addGoods()
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        for(var j=0;j<convert.length;j++)
        {
        	if(convert[j]["unit_default"]==1){
        		base_price=convert[j]["goods_base_price"];
        		default_cv=convert[j]["cv_id"];
        		optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td><td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr goods_num' type='text' value='0'></td><td><input class='w50 tr goods_price' type='text' value='"+base_price+"'></td><td class='tr tr_total'>0.00</td><td><input class='w70 remark' type='text'></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum();
    blurGoodsPrice();
    goodsDel();
}
//店铺销量上传版本
function addGoods1()
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        for(var j=0;j<convert.length;j++)
        {
        	if(convert[j]["unit_default"]==1){
        		base_price=convert[j]["goods_jin_price"];
        		default_cv=convert[j]["cv_id"];
        		optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td><td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr goods_num' type='text' value='0'></td><td><input class='w50 tr goods_price' type='text' value='"+base_price+"'></td><td class='tr tr_total'>0.00</td><td><input class='w70 remark' type='text'></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange1();
    goodsDel();
}
//仓库调拨版本
function addGoods2(url)
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        var last_base_price = 0;
        for(var j=0;j<convert.length;j++)
        {
            if(convert[j]["unit_default"]==1){
                base_price=convert[j]["goods_base_price"];
				last_base_price = convert[j]['last_base_price'];
                num=convert[j]["num"];
                default_cv=convert[j]["cv_id"];
                optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'>" +
            "<input type='hidden' class='goods_id' name='goods_id' value="+checkval+">" +
            "<input type='hidden' class='cv_id' name='cv_id' value="+default_cv+">" +
            "<td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td>" +
            "<td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td>" +
            "<td><input class='w50 tr goods_num' type='text' value='0'></td>" +
            "<td><input class='w50 tr goods_price' type='text' style='color:#FF0000' value='"+last_base_price+"'></td>" +
            "<td><input readonly class='w50 tr last_goods_price' type='text' value='"+base_price+"'></td>" +
            "<td class='tr tr_total'>0.00</td>" +
            "<td><input class='w70 remark' type='text'></td>" +
            "<td><input class='w50 tr num' type='text' value='"+num+"'></td>" +
            "</tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum2(url);
    blurGoodsPrice();
    goodsDel();
}

//车存申请版本
function addGoods3(url)
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        for(var j=0;j<convert.length;j++)
        {
        	if(convert[j]["unit_default"]==1){
                num=convert[j]["num"];
        		base_price=convert[j]["goods_base_price"];
        		default_cv=convert[j]["cv_id"];
        		optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td><td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr goods_num' type='text' value='0'></td><td><input class='w50 tr goods_price' type='text' value='"+base_price+"'></td><td class='tr tr_total'>0.00</td><td><input class='w70 remark' type='text'></td><td><input class='w70 num' type='text' value='"+num+"'></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum3(url);
    blurGoodsPrice();
    goodsDel();
}

function addGoods4()
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var jin_price=0;
        var default_cv=0;
        var optionSelected="";
        for(var j=0;j<convert.length;j++)
        {
        	if(convert[j]["unit_default"]==1){
        		jin_price=convert[j]["goods_jin_price"];
        		
        		default_cv=convert[j]["cv_id"];
        		optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td><td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr goods_num' type='text' value='0'></td><td><input class='w50 tr goods_price' type='text' value='"+jin_price+"'></td><td class='tr tr_total'>0.00</td><td><input class='w70 remark' type='text'></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange4();
    blurGoodsNum();
    blurGoodsPrice();
    goodsDel();
}

function addGoods5()
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        for(var j=0;j<convert.length;j++)
        {
        	if(convert[j]["unit_default"]==1){
        		base_price=convert[j]["goods_base_price"];
        		default_cv=convert[j]["cv_id"];
        		optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
        	}
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td><td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr goods_acti_price' type='text' value='"+base_price+"'></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum();
    blurGoodsPrice();
    goodsDel();
}
function addGoods6()
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        
        var default_cv=0;
        var optionSelected="";
        
        
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+goods_name+/*"/"+goods_convert+*/"</td><td>"+goods_spec+"</td><td><input class='w100 tr remark' type='text' value=''></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum();
    blurGoodsPrice();
    goodsDel();
}


/**
 * 添加陈列商品
 * @param url
 * @param _arr
 * @param _unit_arr
 */
function addGoods7(url,_arr, _unit_arr)
{
    console.log(_unit_arr);
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");   //获取所有选中的checkbox

    for (var i=0;i<checkbox.length;i++) {
        var option="",g = 0;
        var disoption = '', newOptions = '';
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val());
        var base_price=0;
        var default_cv = 0,newdefault_cv_id = 0;
        var optionSelected="";

        //陈列形式
        for (;g < _arr.length;g++) {
            disoption += "<option >"+_arr[g]+"</option>";
        }

        // 陈列单位
        for (g = 0;g < _unit_arr.length;g++) {
            newOptions += "<option value='"+_unit_arr[g]+"'>"+_unit_arr[g]+"</option>";
        }

        //单位
        for (var j=0;j<convert.length;j++) {
            if ($.trim(convert[j]["is_close"]) == 0) {

                if (convert[j]["unit_default"]==1) {
                    //base_price=convert[j]["goods_base_price"];
                    default_cv=convert[j]["cv_id"];
                    //optionSelected="<option  attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
                } else {
                    //option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
                }
            }
        }
        //option=optionSelected+option;
        if (default_cv > 0) {
            newdefault_cv_id = default_cv;
        } else {
            newdefault_cv_id = convert[0]["cv_id"]
        }

       /* con += "<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+" /><input type='hidden' class='cv_id' name='cv_id' value="+newdefault_cv_id+" /><td class='sname td_del'><a href='javascript:void(0)' class='model_goods_del'></a><span class='dis_p_name'>"+goods_name+goods_spec+"</span></td><td><select class='form-control w70 goods_unit_select2 goods_units' onchange='changeCl(this)'>"+option+"</select><input type='text' placeholder='自定义单位' class='form-control user_units w100' style='margin-left: 5px'/></td><td><input class='form-control w70 tr goods_num' type='text' value='0'></td><td><select class='form-control w90 goods_unit_select2 dis_type'>"+disoption+"</select></td></tr>";*/


        con += "<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+" /><input type='hidden' class='cv_id' name='cv_id' value="+newdefault_cv_id+" /><td class='sname td_del'><a href='javascript:void(0)' class='model_goods_del'></a><span class='dis_p_name'>"+goods_name+goods_spec+"</span></td><td><select class='form-control w90 goods_unit_select2 dis_type'>"+disoption+"</select></td><td><input class='form-control w70 tr goods_num' type='text' value='0'></td><td><select class='form-control w70 goods_unit_select2 goods_units' >"+newOptions+"</select></td></tr>";
    }
    $("#models_add_tr").before(con);
    blurGoodsNum4(url);
    //blurGoodsPrice();
    goodsDel2();
}

/**
 * 兑付添加商品
 * @param url
 */
function addGoods8(url,obj)
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox

    for(var i=0;i<checkbox.length;i++)
    {
        var option="",g = 0;
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0,default_cv=0,optionSelected="",_default_base_price = 0,_default_cv_id = 0,_default_flag = 0;

        //单位
        for(var j=0;j<convert.length;j++)
        {
            if($.trim(convert[j]["is_close"]) == 0)
            {
                if(convert[j]["unit_default"]==1){
                    _default_flag = 1;      //有默认值
                    base_price = _default_base_price = convert[j]["goods_base_price"];
                    default_cv = _default_cv_id = convert[j]["cv_id"];
                    optionSelected="<option data-price='"+convert[j]["goods_base_price"]+"' attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
                }else{
                    option+="<option data-price='"+convert[j]["goods_base_price"]+"' attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
                }
            }
        }
        option=optionSelected+option;
        if(parseInt(_default_flag) <= 0)    //没有默认值。则取第一个
        {
            _default_base_price = convert[0]["goods_base_price"];
            _default_cv_id = convert[0]["cv_id"];
        }
        con += "<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+" /><input type='hidden' class='cv_id' name='cv_id' value="+_default_cv_id+" /><input type='hidden' class='goods_price' value='"+_default_base_price+"' /><input type='hidden' class='goods_name' value='"+goods_name+"'/><input type='hidden' class='goods_spec' value='"+goods_spec+"' /><td class='sname td_del'><a href='javascript:void(0)' class='model_goods_del'></a><span class='df_type' name='df_product'>货品</span></td><td>"+goods_name+goods_spec+"</td><td><input class='form-control w70 tr goods_num' onblur='blurDfPrice(this)' type='text' value='0'><select class='form-control w70 goods_unit_select2' onchange='changeDfPrice(this)' style='margin-left: 6px'>"+option+"</select></td><td style='padding-top: 15px'><span class='cur_tr_price'>0</span>元</td></tr>";
    }
    $(obj).parent().parent().before(con);
    //$("#cash_add_tr").before(con);
    //blurGoodsNum4(url,obj);
    //blurGoodsPrice();
    goodsDel2();
}
//预单修改
function addGoods9(url)
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        var last_base_price = 0;
        for(var j=0;j<convert.length;j++)
        {
            if(convert[j]["unit_default"]==1){
                base_price=convert[j]["goods_base_price"];
				last_base_price = convert[j]['last_base_price'];
                num=convert[j]["num"];
                if (!num && typeof(num)!="undefined" && num!=0){ 
                	num=0;
                }　
                
                default_cv=convert[j]["cv_id"];
                optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'>" +
            "<input type='hidden' class='goods_id' name='goods_id' value="+checkval+">" +
            "<input type='hidden' class='cv_id' name='cv_id' value="+default_cv+">" +
            "<td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td>" +
            "<td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td>" +
            "<td><input class='w50 tr goods_num' type='text' value='0'></td>" +
           
            "<td><input class='w50 tr goods_price' type='text' value='"+base_price+"'></td>" +
            "<td class='tr tr_total'>0.00</td>" +
            "<td><input class='w70 remark' type='text'></td>" +
            "<td><input class='w50 tr num' type='text' value='"+num+"'></td>" +
            "</tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum5(url);
    blurGoodsPrice();
    goodsDel();
}
/**
 * 添加店铺并显示
 * @param url
 * @param obj
 */
function addShops(url,obj)
{
    var _str="",_html = '';
    var checkbox=$("#shops_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox

    if(checkbox.length > 0)
    {
        for(var i=0;i<checkbox.length;i++)
        {
            var checkval=checkbox.eq(i).val();          //店铺号
            var _cust_name=checkbox.eq(i).parent().parent().find(".shops_name").text();
            var _contacts=checkbox.eq(i).parent().parent().find(".contacts").text();
            var _telephones=checkbox.eq(i).parent().parent().find(".telephones").text();
            var _staffs = checkbox.eq(i).parent().parent().find(".staffs").text();

            _str += '<div class="chip">'+_cust_name+'<span class="closebtn" data-id="'+checkval+'" onclick="modifyShops(this)">&times;</span></div> ';
            _html += "<input type='hidden' name='hide_shops_id' id='hide_shop_id_"+checkval+"' value='"+checkval+"' />";
            if (i < checkbox.length - 1) {
                _str += "&nbsp;";
            }

            console.log(checkval);
        }
        $("#hide_shops_area .shops_area").append(_str);
        $("#hide_shops_area .shops_input_area").append(_html);
        $("#hide_shops_area").show();
        $("#usual_shops_set").attr("checked",false);
    }
}

/**
 * 添加现金行 陈列兑付
 * @param obj
 * @returns {boolean}
 */
function addCrashMoney(obj)
{
    var _monLen = $(obj).parent().parent().parent().find(".crash_money").length;
    if(_monLen == 0)
    {
        var _option = "<tr class='tr_operate'><td class='sname td_del'><a href='javascript:void(0)' class='model_goods_del'></a><span class='df_type' name='df_money'>现金</span></td><td>---</td><td>--</td><td><input class='form-control w70 tr crash_money' type='text' value='0' onblur='blurDfPrice(this)'>元</td></tr>";

        $(obj).parent().parent().before(_option);
        //$("#cash_add_tr").before(_option);
        goodsDel2();
    }
    else
    {
        alert("现金只能添加一个");
        return false;
    }
}


function goodsDel()
{
    $(".goods_del").click(function(){
        $(this).parent().parent().remove();
        goodsCountMoney();
    })
}

/**
 * 付费陈列商品
 */
function goodsDel2()
{
    $(".model_goods_del").click(function(){

        var _df = $(this).parent().find('.df_type');
        if(_df && _df.length > 0)
        {
            var _cur_tr = $(this).parent().parent(),_cur_tbody = _cur_tr.parent(),_total_num = _cur_tbody.find('.cash_num_total').text(),_total_amount = _cur_tbody.find('.cash_price_total').text();

            if(_df.attr('name') === 'df_product')
            {
                var _goods_price = _cur_tr.find('.goods_price').val(),_goods_num = _cur_tr.find('.goods_num').val();

                _cur_tbody.find('.cash_num_total').text((_total_num-_goods_num <= 0) ? 0 : parseInt(_total_num-_goods_num));
                _cur_tbody.find('.cash_price_total').text((_total_amount-_goods_price*_goods_num <= 0) ? 0 : parseFloat(_total_amount-_goods_price*_goods_num));
            }
            else
            {
                var _money = _cur_tr.find('.crash_money ').val();
                _cur_tbody.find('.cash_price_total').text(_total_amount-_money);
            }
        }
        $(this).parent().parent().remove();
        //goodsCountMoney();
    })
}

//商品单位改变时，商品编码、填写数量，计算改变
function goodsUnitChange()
{
    $(".goods_unit_select").change(function(){
        var index=$(this).index();
        var aCv=JSON.parse($(this).find("option:selected").attr("attr"));

        if (typeof($(this).parent().parent().find(".last_goods_price")) == "object") {
            $(this).parent().parent().find(".last_goods_price").val(aCv['last_base_price']);
        }
        $(this).parent().siblings(".sname").html("<a href='javascript:void(0)' class='goods_del'></a>"+aCv["goods_code"])
        $(this).parent().parent().find(".cv_id").val(aCv["cv_id"]);
        $(this).parent().parent().find(".goods_num").val(0);
        $(this).parent().parent().find(".goods_price").val(aCv["goods_base_price"]);
        $(this).parent().parent().find(".num").val(aCv["num"]);//库存数量变化
        goodsCountMoney();
        goodsDel();
    })
}
//店铺销量上传版本
function goodsUnitChange1()
{
    $(".goods_unit_select").change(function(){
    	var cv_id=$(this).val();
        var index=$(this).index();
        var aCv=JSON.parse($(this).find("option:selected").attr("attr"));
        $(this).parent().parent().find(".cv_id").val(aCv["cv_id"]);
        $(this).parent().siblings(".sname").html("<a href='javascript:void(0)' class='goods_del'></a>"+aCv["goods_code"])
        goodsCountMoney1();
        goodsDel();
    });
}

//进价版本
function goodsUnitChange4()
{
    $(".goods_unit_select").change(function(){
        var index=$(this).index();
        var aCv=JSON.parse($(this).find("option:selected").attr("attr"));
        $(this).parent().siblings(".sname").html("<a href='javascript:void(0)' class='goods_del'></a>"+aCv["goods_code"])
        $(this).parent().parent().find(".cv_id").val(aCv["cv_id"]);
        $(this).parent().parent().find(".goods_num").val(0);
        $(this).parent().parent().find(".goods_price").val(aCv["goods_jin_price"]);
        goodsCountMoney();
        goodsDel();
    })
}



//初始化goods_div操作
function setGoodsDivData()
{
    goodsDel();
    goodsUnitChange();
    blurGoodsNum();
    blurGoodsPrice();
}
//初始化goods_div操作设置销量库存版本
function setGoodsDivData1(){
	goodsDel();
    goodsUnitChange();
}
//预单修改
function setGoodsDivData3(url)
{
    goodsDel();
    goodsUnitChange();
    blurGoodsNum5(url);
    blurGoodsPrice();
}

//判断商品数量是整数,元素，数值
function blurGoodsNum()
{
   $(".goods_num").blur(function(){
       if(!isNumber($(this).val()))
           $(this).val(0);
       //checkGoodsNeqZero();
       goodsCountMoney();
   })
}

function blurGoodsNum2(url)
{
   $(".goods_num").blur(function(){
       if(!isNumber($(this).val())){
    	   $(this).val(0);
    	   return;
       }
       console.log("2222");
	   // url为空则不检查库存, 直接计算产品总价
	   if(url == '') { goodsCountMoney(); return; }
	   
	   // 
       var depot_out=$("#a_depot_out").val();
       var goods_num=$(this).val();
       
       var goods=$(this).parent().parent();
       
       var goods_id=goods.find(".goods_id").val();
       var cv_id=goods.find(".cv_id").val();
       
       var data={"repertory_id":depot_out,"goods_id":goods_id,"pageNum":goods_num,"cv_id":cv_id};    
       //checkGoodsNeqZero();
       var th=$(this);
       $.post(url,data,function(isNotFull){
    	   
    	  if(isNotFull==1){
    		  alert("库存不足");
    		  th.val(0);
    		  goodsCountMoney();
    	  } 
       });
       
       
       
       goodsCountMoney();
   })
}

/**
 * 付费陈列的库存监测
 */
function blurGoodsNum4()
{
    $(".goods_num").blur(function(){
        if(!isNumber($(this).val())){
            $(this).val(0);
            return;
        }
        //modelsGoodsCountMoney(obj);
        //goodsCountMoney();
    })
}

//车存申请版本
function blurGoodsNum3(url)
{
	
   $(".goods_num").blur(function(){
       if(!isNumber($(this).val()))
           $(this).val(0);
       //checkGoodsNeqZero();

       console.log("333333");
       var depot_out=$("#depot_id").val();
       
       var goods_num=$(this).val();
      
       var goods=$(this).parent().parent();
       
       var goods_id=goods.find(".goods_id").val();
       //alert(goods_id);
       var cv_id=goods.find(".cv_id").val();
       
       var data={"repertory_id":depot_out,"goods_id":goods_id,"pageNum":goods_num,"cv_id":cv_id};    
       //checkGoodsNeqZero();
       var th=$(this);
       $.post(url,data,function(isNotFull){
    	   
    	  if(isNotFull==1){
    		  alert("库存不足");
    		  th.val(0);
    		  goodsCountMoney();
    	  } 
       });
       
       
       goodsCountMoney();
   })
}

//预单修改版本
function blurGoodsNum5(url)
{
	
   $(".goods_num").blur(function(){
       if(!isNumber($(this).val()))
           $(this).val(0);
       //checkGoodsNeqZero();

       
       var depot_out=$("#a_depot_out").val();
       
       var goods_num=$(this).val();
      
       var goods=$(this).parent().parent();
       
       var goods_id=goods.find(".goods_id").val();
       //alert(goods_id);
       var cv_id=goods.find(".cv_id").val();
       
       var data={"repertory_id":depot_out,"goods_id":goods_id,"pageNum":goods_num,"cv_id":cv_id};    
       //checkGoodsNeqZero();
       
       var th=$(this);
       $.post(url,data,function(isNotFull){
    	   
    	  if(isNotFull==1){
    		  alert("库存不足");
    		  th.val(0);
    		  goodsCountMoney();
    	  } 
       });
       
       
       goodsCountMoney();
   })
}


//判断商品价格格式,元素，数值
function blurGoodsPrice()
{
    $(".goods_price").blur(function(){
        if(!isfloat($(this).val()))
            $(this).val("0.00");
        else
            $(this).val(parseFloat($(this).val()).toFixed(2));
        //checkGoodsNeqZero();
        goodsCountMoney();
    })

}
//商品价格跟数量发生改变时，自动计算这一行的价格跟所有产品的价格
function goodsCountMoney(){
    var goods=$("#goods_table tbody tr.tr_operate");
    var num_total=0;
    var price_total=0.00;
    for(var i=0;i<goods.length;i++)
    {
        var tr_num=parseInt(goods.eq(i).find(".goods_num").val());
        var tr_price=parseFloat(goods.eq(i).find(".goods_price").val()).toFixed(2);
        var tr_total=tr_num*tr_price;
        num_total=num_total+tr_num;
        price_total=price_total+tr_total;
        goods.eq(i).find(".tr_total").text(tr_total.toFixed(2));
    }
    $("#num_total").text(num_total);
    $("#price_total").text(price_total.toFixed(2));
    //添加预单到货时总金额赋值
    $("#total_moneys").val(price_total.toFixed(2));
    var real_money = $("#real_money").val();//已收金额
    var real_back_money = price_total.toFixed(2) - real_money;//计算应收金额，赋值
    $("#real_back_money").val(real_back_money.toFixed(2));

}

/**
 * 兑付表
 * @param ele +货品
 */
function modelsGoodsCountMoney(ele){

    var _df_ele = $(ele).parent().parent().parent().find(".tr_operate"),_lens = _df_ele.length;

    //var goods=$(ele+" tbody tr.tr_operate");
    var num_total=0;
    var price_total=0.00;
    for(var i = 0; i < _lens; i++)
    {
        var _types = _df_ele.eq(i).find(".df_type").text();
        var tr_num,tr_price,tr_total;
        if(_types == "货品")
        {
             tr_num=parseInt(_df_ele.eq(i).find(".goods_num").val());
             tr_price=parseFloat(_df_ele.eq(i).find(".goods_price").val()).toFixed(2);
             tr_total=tr_num*tr_price;
        }
        else
        {
            tr_num = 0;
            tr_total = parseFloat(_df_ele.eq(i).find(".crash_money").val()).toFixed(2)
        }
        num_total = num_total+tr_num;
        price_total = price_total+tr_total;
    }
    //_dfs.eq(i).find(".tr_total").text(tr_total.toFixed(2));
    /*for(var i=0;i<goods.length;i++)
    {
        var tr_num=parseInt(goods.eq(i).find(".goods_num").val());
        var tr_price=parseFloat(goods.eq(i).find(".goods_price").val()).toFixed(2);
        var tr_total=tr_num*tr_price;
        num_total=num_total+tr_num;
        price_total=price_total+tr_total;
        goods.eq(i).find(".tr_total").text(tr_total.toFixed(2));
    }*/
    _df_ele.parent().find(".cash_num_total").text(num_total);
    _df_ele.parent().find(".cash_price_total").text(price_total.toFixed(2));
    //$("#num_total").text(num_total);
    //$("#price_total").text(price_total.toFixed(2));
}





//店铺销量上传版本
function goodsCountMoney1(){
    var goods=$("#goods_table tbody tr.tr_operate");
    var num_total=0;
    var price_total=0.00;
    for(var i=0;i<goods.length;i++)
    {
        var tr_num=parseInt(goods.eq(i).find(".goods_num").val());
        var tr_price=parseFloat(goods.eq(i).find(".goods_price").val()).toFixed(2);
        var tr_total=tr_num*tr_price;
        num_total=num_total+tr_num;
        price_total=price_total+tr_total;
        goods.eq(i).find(".tr_total").text(tr_total.toFixed(2));
    }
    
}



function goodsTransferArr(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_operate");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"cv_id":');
        aGoods.push(goods.eq(i).find(".goods_unit_select").val());
        aGoods.push(',"goods_num":');
        aGoods.push(goods.eq(i).find(".goods_num").val());
        
        aGoods.push('}');
        aGoodsList.push(aGoods.join(''));

    }
    return "["+aGoodsList.join(",")+"]";
}



//商品验证，再以json返回
function goodsTransferArr1(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_operate");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"cv_id":');
        aGoods.push(goods.eq(i).find(".cv_id").val());
        aGoods.push(',"goods_num":');
        aGoods.push(goods.eq(i).find(".goods_num").val());
        aGoods.push(',"goods_price":');
        aGoods.push(goods.eq(i).find(".goods_price").val());
        aGoods.push(',"remark":"');
        var remark = goods.eq(i).find(".remark").val();
        var resultStr=remark.replace(/\ +/g,"");
        aGoods.push(resultStr);
        aGoods.push('"}');
        aGoodsList.push(aGoods.join(''));

    }
    return "["+aGoodsList.join(",")+"]";
}

//批量添加活动商品版本
function goodsTransferArr2(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_operate");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"cv_id":');
        aGoods.push(goods.eq(i).find(".cv_id").val());
        aGoods.push(',"goods_acti_price":');
        aGoods.push(goods.eq(i).find(".goods_acti_price").val());
        aGoods.push('}');
        aGoodsList.push(aGoods.join(''));

    }
    return "["+aGoodsList.join(",")+"]";
}
function goodsTransferArr3(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_operate");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"remark":"');
        aGoods.push(goods.eq(i).find(".remark").val());
        aGoods.push('"}');
        aGoodsList.push(aGoods.join(''));

    }
    return "["+aGoodsList.join(",")+"]";
}
//批次验证
function PiCiTransferArr(element)
{
  var aGoodsList=[];
  var goods=$(element+" tr.pici_add_tr");
  for(var i=0;i<goods.length;i++)
  {
      var aGoods=[];
      aGoods.push('{"pici":"');
      aGoods.push(goods.eq(i).find(".pici").val());
      aGoods.push('","period":"');
      aGoods.push(goods.eq(i).find(".period").val());
      aGoods.push('"}');
      aGoodsList.push(aGoods.join(''));

  }
  return "["+aGoodsList.join(",")+"]";
}



//商品验证，再以json返回
//采购到达转化
function goodsTransferArrArrival(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_arrival");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"cv_id":');
        aGoods.push(goods.eq(i).find(".cv_id").val());
        aGoods.push(',"goods_num":');
        aGoods.push(goods.eq(i).find(".goods_num").val());
        aGoods.push(',"goods_price":');
        aGoods.push(goods.eq(i).find(".goods_price").val());
        aGoods.push(',"good_arrival_num":');
        aGoods.push(goods.eq(i).find(".good_arrival_num").val());
        aGoods.push(',"good_c_num":');
        aGoods.push(goods.eq(i).find(".good_c_num").val());
        aGoods.push(',"remark":"');
        aGoods.push(goods.eq(i).find(".remark").val());
        aGoods.push('"}');
        aGoodsList.push(aGoods.join(''));

    }
    return "["+aGoodsList.join(",")+"]";
}

/**
 * 陈列商品转json
 * @param element
 * @returns {string}
 */
function goodsTransferArr6(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_operate");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[],_nums = goods.eq(i).find(".goods_num").val();
        //console.log(i);
        if (parseInt(_nums) <= 0 || $.trim(_nums) == '') {
            alert("请输入"+goods.eq(i).find(".dis_p_name").text()+"的陈列数量");
            return false;
        }
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"cv_id":');
        aGoods.push(goods.eq(i).find(".cv_id").val());
        //aGoods.push(0)
        aGoods.push(',"goods_num":');
        aGoods.push(goods.eq(i).find(".goods_num").val());
        aGoods.push(',"remark":"');
        aGoods.push(goods.eq(i).find(".remark").val());
        aGoods.push('","dis_type":"');
        aGoods.push(goods.eq(i).find(".dis_type").val());
        aGoods.push('","user_units":"');
        aGoods.push(goods.eq(i).find(".goods_units").val());
        aGoods.push('"}');
        aGoodsList.push(aGoods.join(''));
    }
    return "["+aGoodsList.join(",")+"]";
}

//兑付表数据
function goodsTransferArr7(element,ele2)
{
    var aGoodsList=[],mGoodsList = [],_aaa = [];
    var goods=$(element+" tr.tr_operate"),goods_total = $(element+" tr.tr_total");
    var goods2 = $(ele2+" tr.tr_operate");      //新创建的兑付表
    mGoodsList.push('{"data":');
    //var _a = {};
    //_a["data"] = [];
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":"');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push('","cv_id":"');
        aGoods.push(goods.eq(i).find(".cv_id").val());
        aGoods.push('","goods_num":"');
        aGoods.push(goods.eq(i).find(".goods_num").val());
        aGoods.push('","remark":"');
        aGoods.push(goods.eq(i).find(".remark").val());
        aGoods.push('","df_type":"');
        aGoods.push(goods.eq(i).find(".df_type").text());
        aGoods.push('","goods_unit_select2":"');
        aGoods.push(goods.eq(i).find(".goods_unit_select2").val());
        aGoods.push('","crash_money":"');
        aGoods.push(goods.eq(i).find(".crash_money").val());
        aGoods.push('","user_units":"');
        aGoods.push(goods.eq(i).find(".user_units").val());     //自定义单位

        aGoods.push('"}');
        console.log(aGoods.join(''));
        mGoodsList.push(aGoods.join(''));
        //_a['data'].push(aGoods.join(''));
    }
    console.log(i);
    //console.log(_a);
    //_a['date_time'] = goods_total.find(".cash_time").val();
    //return _a;
    //_aaa.push('"data":');
    //_aaa.push(mGoodsList);
    //_aaa["data"] = mGoodsList;
    //_aaa["goods_nums_total"] = goods_total.find(".cash_num_total").text();
    //console.log(_aaa);
    mGoodsList.push(']');

    mGoodsList.push('"date_time":"'+goods_total.find(".cash_time").val()+'"');
    mGoodsList.push('"goods_nums_total":'+goods_total.find(".cash_num_total").text());
    mGoodsList.push('"goods_price_total":"'+goods_total.find(".cash_price_total").text()+'"');
    mGoodsList.push('}');
    //console.log(mGoodsList);
    //console.log("["+mGoodsList.join(",")+"]");
    //return _aaa;
    return "["+mGoodsList.join(",")+"]";

    //return "["+aGoodsList.join(",")+"]";
}
/**
 * 新兑付数据
 * @param element   第一个表格
 * @param ele2      新增的表格
 * @returns {string}
 */
function goodsTransferArr8(element,ele2)
{
    var goods=$(element+" tr.tr_operate"),goods_total = $(element+" tr.tr_total");
    var goods2 = $(ele2+" tr.tr_operate");
    var _str = '[';
    if (goods.length > 0) {
        _str += '{"data":[';
        var newstr = '';
        for(var i=0;i<goods.length;i++)
        {
            if (goods.eq(i).find(".df_type").text() == '货品') {
                if (parseInt(goods.eq(i).find(".goods_num").val()) <= 0) {
                    alert('请输入兑付货品数量');
                    return false;
                }
            } else {
                if (parseInt(goods.eq(i).find(".crash_money").val()) <= 0) {
                    alert('请输入兑付金额');
                    return false;
                }
            }
            newstr += '{"goods_id":"'+goods.eq(i).find(".goods_id").val()+'",'+'"cv_id":"'+goods.eq(i).find(".cv_id").val()+'","goods_num":"'+goods.eq(i).find(".goods_num").val()+'","remark":"' + goods.eq(i).find(".remark").val()+'","df_type":"'+goods.eq(i).find(".df_type").text()+'","goods_unit_select2":"'+goods.eq(i).find(".goods_unit_select2").val()+'","crash_money":"'+goods.eq(i).find(".crash_money").val()+'","goods_name":"'+goods.eq(i).find(".goods_name").val()+'","goods_spec":"'+goods.eq(i).find(".goods_spec").val()+'"}';
            if ( i < goods.length -1) {
                newstr += ',';
            }
        }
        _str += newstr + '],';
        if (goods_total.find(".cash_time").val() == '') {
            alert('请填写兑付时间');
            return false;
        }
        _str += '"date_time":"'+goods_total.find(".cash_time").val()+'","goods_nums_total":"'+goods_total.find(".cash_num_total").text()+'","goods_price_total":"'+goods_total.find(".cash_price_total").text()+'","remarks":"'+$(element+ " .cash_out_remark").val()+'"';
        _str += '}';
    }

    // 多个新增兑付数据
    if ($(ele2).length > 0) {
        var _str2 = '',e2lens = $(ele2).length;
        for (var j = 0; j < e2lens; j++) {
            _str2 += '{"data":[';
            var _newstr = '';
            var _cur_tab = $(ele2).eq(j);      //当前兑付表
            for (var i=0;i<_cur_tab.find(".tr_operate").length;i++) {
                var _cur_tr = _cur_tab.find(".tr_operate").eq(i);       //当前行

                if (_cur_tr.find(".df_type").text() == '货品') {
                    if (parseInt(_cur_tr.find(".goods_num").val()) <= 0) {
                        alert('请输入兑付货品数量');
                        return false;
                    }
                } else {
                    if (parseInt(_cur_tr.find(".crash_money").val()) <= 0) {
                        alert('请输入兑付金额');
                        return false;
                    }
                }

                _newstr += '{"goods_id":"'+_cur_tr.find(".goods_id").val()+'",'+'"cv_id":"'+_cur_tr.find(".cv_id").val()+'","goods_num":"'+_cur_tr.find(".goods_num").val()+'","remark":"' + _cur_tr.find(".remark").val()+'","df_type":"'+_cur_tr.find(".df_type").text()+'","goods_unit_select2":"'+_cur_tr.find(".goods_unit_select2").val()+'","crash_money":"'+_cur_tr.find(".crash_money").val()+'","user_units":"'+_cur_tr.find(".user_units").val()+'","goods_name":"'+_cur_tr.find(".goods_name").val()+'","goods_spec":"'+_cur_tr.find(".goods_spec").val()+'"}';
                if ( i != _cur_tab.find(".tr_operate").length -1) {
                    _newstr += ','
                }
            }
            _str2 += _newstr + '],';
            if(_cur_tab.find(".cash_time").val() == '')
            {
                alert('请填写兑付时间');
                return false;
            }
            _str2 += '"date_time":"'+_cur_tab.find(".cash_time").val()+'","goods_nums_total":"'+_cur_tab.find(".cash_num_total").text()+'","goods_price_total":"'+_cur_tab.find(".cash_price_total").text()+'","remarks":"'+_cur_tab.find(".cash_out_remark").val()+'"';
            _str2 += '}';
            if (j < e2lens - 1) {
                _str2 += ',';
            }
        }

        if (goods.length > 0) {
            _str += ',' + _str2;
        } else {
            _str +=  _str2;
        }
    }

    if ($.trim(_str) == '[') {
        alert("请添加兑付条件");
        return false;
    }
    _str += ']';
    return _str;
}

/**
 * 兑付照片类型转str
 * @param ele
 * @returns {*}
 */
function goodsPhotosArr(ele)
{
    var _tab = $(ele+" tbody"),_data = '[';
    var _lens = _tab.find(".tr_operate").length;
    if(_lens > 0)
    {
        var i = 0;
        for(;i < _lens; i++)
        {
            var cu_tr = _tab.find(".tr_operate").eq(i);
            var _val = cu_tr.find(".photo_categories").val();
            if($.trim(_val) === '')
            {
                alert("请输入照片类型");
                return false;
            }
            /*var _num = cu_tr.find(".photo_nums").val();
            if($.trim(_num) == '')
            {
                alert("请输入数量");
                return false;
            }
            else if(parseInt(_num) <= 0)
            {
                alert("请输入数量");
                return false;
            }*/
            //_data += '{"categories":"'+_val+'","num":"'+_num+'"}';
            _data += '{"categories":"'+_val+'"}';
            if(i == _lens -1 )
            {
                _data += '';
            }
            else
            {
                _data += ',';
            }
        }

        _data += ']';
        return _data;
    }
    else
    {
        alert("请添加照片类型");
        return false;
    }

}




//入库，调拨，出库，商品页面，添加事件的时候，判断商品不能为0,价格不能为0.00;返回bool
function checkGoodsNeqZero()
{
    var error=0;
    var goodsNum=$("#goods_table .tr_operate .goods_num");
    var goodsPrice=$("#goods_table .tr_operate .goods_price");
    $.each(goodsNum,function(i){
    if(goodsNum.eq(i).val()<=0)
    {
        goodsNum.eq(i).addClass("input-red");
        error++;
    }
    else
        goodsNum.eq(i).removeClass("input-red");
    })
    $.each(goodsPrice,function(i){
        if(goodsPrice.eq(i).val()<0)
        {
            goodsPrice.eq(i).addClass("input-red");
            error++;
        }
        else
            goodsPrice.eq(i).removeClass("input-red");
    })
    return error>0?false:true;//没通过：通过
}

/////////////////////////商品订单页面函数结束/////////////////////////

//控制弹出页面的disabled跟readonly
function MenuStatus(status,type,module)
{
    if(module=="in"||module=="tf"||module=="out")
    {
        if(status==3||(module=="in"&&type!=6)||(module=="out"&&type!=4))
        {
            $("#goods_add_tr").remove();
            $("#goods_table select,#goods_table input:text").attr("disabled","disabled").css({"border":"0px","background":"#fff"})
            $("#pass,#create_form").attr("disabled","disabled")
        }
        if(status!=3)
            $("#pass").removeAttr("disabled")
    }
}
//车销退库通过业务员获取产品ajax数据
 // 2016.03.16 修改按默认单位显示 kxf
function CarReturnGoods(url,data)
{
    $.ajax({
        url:url,
        type:"post",
        dataType:"json",
        data:data,
        beforeSend:function(){
            $(".await").show();
        },
        success:function(data){
            $("#goods_table tbody").empty();
            var con;
            if(data["res"]==1)
            {
                var resData =data["data"];
                $.each(resData,function(i){
                    var option="";
                    var aUnit=resData[i]["goods_unit"];
                    var optionSelected = '';
                    for(var j=0;j<resData[i]["goods_unit"].length;j++)
                    {

                        if(resData[i]["goods_unit"][j]["unit_default"]==1){

                            optionSelected="<option attr='"+JSON.stringify(resData[i]["goods_unit"][j])+"' value="+resData[i]["goods_unit"][j]["cv_id"]+">"+resData[i]["goods_unit"][j]["goods_unit"]+"</option>";
                            var num;
                            num = resData[i]["goods_unit"][j]['num'];
                            var cv_id = resData[i]["goods_unit"][j]["cv_id"];//
                        }else{

                            option+="<option attr='"+JSON.stringify(aUnit[j])+"' value="+aUnit[j]["cv_id"]+">"+aUnit[j]["goods_unit"]+"</option>";
                            // var num;
                            // num = aUnit[j]['num'];
                        }

                    }
                    option=optionSelected+option;
                    con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+resData[i]["goods_id"]+"><input type='hidden' class='cv_id' value='"+cv_id+"' name='cv_id'><td class='sname'>"+aUnit[0]["goods_code"]+"</td><td>"+resData[i]["goods_name"]+"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr car_num' disabled='disabled' type='text' value='"+num+"'></td><td><input class='w50 tr return_num' value='0' type='text'></td><td><input class='w100 tr return_remark' type='text'></td></tr>";
                })
            }
            con+="<tr><td colspan='6'>&nbsp</td>";
            $("#goods_table tbody").append(con);
            $(".await").hide();
            returnGoodsUnit();
            blurInputNum("#goods_table .return_num");
        }
    })
}
//库存盘点通过仓库获取产品ajax数据
function DepotCheckGoods(url,data)
{
    $.ajax({
        url:url,
        type:"post",
        dataType:"json",
        data:data,
        beforeSend:function(){
            $(".await").show();
        },
        success:function(data){
            $("#goods_table tbody").empty();
            var con;
            if(data["res"]==1)
            {
                var resData =data["data"];
                $.each(resData,function(i){
                    var option="";
                    var aUnit=resData[i]["goods_unit"];
                    for(var j=0;j<resData[i]["goods_unit"].length;j++)
                    {
                        option+="<option attr='"+JSON.stringify(aUnit[j])+"' value="+aUnit[j]["cv_id"]+">"+aUnit[j]["goods_unit"]+"</option>";
                    }
                    con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+resData[i]["goods_id"]+"><input type='hidden' class='cv_id' value='"+aUnit[0]["cv_id"]+"' name='cv_id'><td class='sname'>"+aUnit[0]["goods_code"]+"</td><td>"+resData[i]["goods_name"]+"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr car_num' disabled='disabled' type='text' value='"+aUnit[0]["num"]+"'></td><td><input class='w50 tr return_num' value='0' type='text'></td><td><input class='w100 tr return_remark' type='text'></td></tr>";
                })
            }
            con+="<tr><td colspan='6'>&nbsp</td>";
            $("#goods_table tbody").append(con);
            $(".await").hide();
            returnGoodsUnit();
            blurInputNum("#goods_table .return_num");
        }
    })
}
//退货单位select的change事件
function returnGoodsUnit()
{
    $(".goods_unit_select").change(function(){
        var aCv=JSON.parse($(this).find("option:selected").attr("attr"));
        $(this).parent().siblings(".sname").text(aCv["goods_code"])
        $(this).parent().parent().find(".cv_id").val(aCv["cv_id"]);
        $(this).parent().parent().find(".car_num").val(aCv["num"]);
        $(this).parent().parent().find(".return_num").val("0");
    })
}
//验证退货数量是否正确
function checkReturnGoodsNeqZero()
{
    var error=0;
    var car_num=$("#goods_table .car_num");
    var return_num=$("#goods_table .return_num");
    $.each(return_num,function(i){
        if(parseInt(return_num.eq(i).val())<=parseInt(car_num.eq(i).val())&&parseInt(return_num.eq(i).val())>0)
            return_num.eq(i).removeClass("input-red");
        else
        {
            return_num.eq(i).addClass("input-red");
            error++;
        }
    })
    return error>0?false:true;//没通过：通过
}
//input数值为正整数;
function blurInputNum(element)
{
    $(element).blur(function(){
        if(!isNumber($(this).val()))
            $(this).val(0);
    })
}
//判断价格格式
function checkPrice(element)
{
        if(!isfloat($(element).val()))
            $(element).val("0.00");
        else
            $(element).val(parseFloat($(element).val()).toFixed(2));
}
//退货商品验证，再以json返回
function returnTransferArr(element)
{
    var aGoodsList=[];
    var goods=$(element+" tr.tr_operate");
    for(var i=0;i<goods.length;i++)
    {
        var aGoods=[];
        aGoods.push('{"goods_id":');
        aGoods.push(goods.eq(i).find(".goods_id").val());
        aGoods.push(',"cv_id":');
        aGoods.push(goods.eq(i).find(".cv_id").val());
        aGoods.push(',"goods_num":');
        aGoods.push(goods.eq(i).find(".return_num").val());
        aGoods.push(',"goods_remark":"');
        aGoods.push(goods.eq(i).find(".return_remark").val());
        aGoods.push('"}');
        aGoodsList.push(aGoods.join(''));

    }
    return "["+aGoodsList.join(",")+"]";
}

//审核通过，调用ajax,修改一些样式
function passData(url, data)
{
    $.ajax({
        url:url,
        type:"post",
        dataType:"json",
        data:data,
        beforeSend:function(){
           $(".await").show();
        },
        success:function(data){
            alert(data["info"]);
            if(data["res"]==1)
                reload();
            else
                $(".await").hide();

        }
    })
}


//车存申请修改 使用
function setGoodsPageDatasq(url)
{
    $("#brand").val(0);
    $("#goods").val("");
    $("#goods_search tbody").empty()
    $("#goods_div").show().draggable();
    selectAll();//点击选中时间
    closeGoodsDiv();
    addGoodsToTable10(url);
}

//车存申请修改 使用
function addGoodsToTable10(url)
{
    $("#goods-add").unbind();
    $("#goods-add").click(function(){
        addGoods10(url);
    })
}

//车存申请修改 使用
function addGoods10(url)
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");//获取所有选中的checkbox
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        for(var j=0;j<convert.length;j++)
        {
            if(convert[j]["unit_default"]==1){
                num=convert[j]["num"];
                base_price=convert[j]["goods_base_price"];
                default_cv=convert[j]["cv_id"];
                optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'><input type='hidden' class='goods_id' name='goods_id' value="+checkval+"><input type='hidden' class='cv_id' name='cv_id' value="+default_cv+"><td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td><td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td><td><input class='w50 tr goods_num' type='text' value='0'></td><td><input class='w50 tr goods_price' type='text' value='"+base_price+"'></td><td class='tr tr_total'>0.00</td><td><input class='w70 remark' type='text'></td></tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
    blurGoodsNum3(url);
    blurGoodsPrice();
    goodsDel();
}

// 预单退货版本
function addGoods11()
{
    var con="";
    var checkbox=$("#goods_search tbody input[type='checkbox']:checked");
    for(var i=0;i<checkbox.length;i++)
    {
        var option="";
        var checkval=checkbox.eq(i).val();
        var goods_name=checkbox.eq(i).parent().siblings(".goods_name").val();
        var goods_spec=checkbox.eq(i).parent().siblings(".goods_spec").val();
        var goods_convert=checkbox.eq(i).parent().siblings(".goods_convert").val();
        var convert=JSON.parse(checkbox.eq(i).parent().siblings(".cv_data").val())
        var base_price=0;
        var default_cv=0;
        var optionSelected="";
        var last_base_price = 0;
        for(var j=0;j<convert.length;j++)
        {
            if(convert[j]["unit_default"]==1){
                base_price=convert[j]["goods_base_price"];
				last_base_price = convert[j]['last_base_price'];
                num=convert[j]["num"];
                default_cv=convert[j]["cv_id"];
                optionSelected="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }else{
                option+="<option attr='"+JSON.stringify(convert[j])+"' value="+convert[j]["cv_id"]+">"+convert[j]["goods_unit"]+"</option>";
            }
        }
        option=optionSelected+option;
        con+="<tr class='tr_operate'>" +
            "<input type='hidden' class='goods_id' name='goods_id' value="+checkval+">" +
            "<input type='hidden' class='cv_id' name='cv_id' value="+default_cv+">" +
            "<td class='sname td_del'><a href='javascript:void(0)' class='goods_del'></a>"+convert[0]["goods_code"]+"</td>" +
            "<td>"+goods_name+goods_spec+/*"/"+goods_convert+*/"</td><td><select class='w50 goods_unit_select'>"+option+"</select></td>" +
            "<td><input class='w50 tr goods_num' type='text' value='0'></td>" +
			"<td><input class='w50 tr goods_price' type='text' value='"+base_price+"'></td>" +
            "<td class='tr tr_total'>0.00</td>" +
            "<td><input class='w70 remark' type='text'></td>" +
            "</tr>";
    }
    $("#goods_table #goods_add_tr").before(con);
    goodsUnitChange();
	blurGoodsNum();
    blurGoodsPrice();
    goodsDel();
}




