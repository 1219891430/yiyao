var calUtil = {
  //当前日历显示的年份
  showYear:2015,
  //当前日历显示的月份
  showMonth:1,
  //当前日历显示的天数
  showDays:1,
  eventName:"load",
  //初始化日历
  init:function(signList,uid,year,month){
    calUtil.setMonthAndDay(year,month);
    calUtil.draw(signList,uid,year,month);
    calUtil.bindEnvent();
  },
  draw:function(signList,uid,year,month){
    //绑定日历
    var str = calUtil.drawCal(year,month,signList,uid);
    $("#calendar").html(str);
    //绑定日历表头
    var calendarName=year+"年"+month+"月";
    $(".calendar_month_span").html(calendarName);  
  },
  //绑定事件
  bindEnvent:function(){

  },
  //获取当前选择的年月
  setMonthAndDay:function(year,month){
    switch(calUtil.eventName)
    {
      case "load":
        calUtil.showYear=year;
        calUtil.showMonth=month;
        break;
    }
  },
  getDaysInmonth : function(iMonth, iYear){
   var dPrevDate = new Date(iYear, iMonth, 0);
   return dPrevDate.getDate();
  },
  bulidCal : function(iYear, iMonth) {
   var aMonth = new Array();
   aMonth[0] = new Array(7);
   aMonth[1] = new Array(7);
   aMonth[2] = new Array(7);
   aMonth[3] = new Array(7);
   aMonth[4] = new Array(7);
   aMonth[5] = new Array(7);
   aMonth[6] = new Array(7);
   var dCalDate = new Date(iYear, iMonth - 1, 1);
   var iDayOfFirst = dCalDate.getDay();
   var iDaysInMonth = calUtil.getDaysInmonth(iMonth, iYear);
   var iVarDate = 1;
   var d, w;
   aMonth[0][0] = "日";
   aMonth[0][1] = "一";
   aMonth[0][2] = "二";
   aMonth[0][3] = "三";
   aMonth[0][4] = "四";
   aMonth[0][5] = "五";
   aMonth[0][6] = "六";
   for (d = iDayOfFirst; d < 7; d++) {
    aMonth[1][d] = iVarDate;
    iVarDate++;
   }
   for (w = 2; w < 7; w++) {
    for (d = 0; d < 7; d++) {
     if (iVarDate <= iDaysInMonth) {
      aMonth[w][d] = iVarDate;
      iVarDate++;
     }
    }
   }
   return aMonth;
  },
  ifHasSigned : function(signList,day){
   var signed = false;
   $.each(signList,function(index,item){
    if(item.signDay == day) {
     signed = true;
     return false;
    }
   });
   return signed ;
  },
  drawCal : function(iYear, iMonth ,signList,uid) {
   var myMonth = calUtil.bulidCal(iYear, iMonth);
   var htmls = new Array();
   htmls.push("<div class='sign_main' id='sign_layer'>");
   htmls.push("<div class='sign_succ_calendar_title'>");
   htmls.push("<div class='calendar_month_span'></div>");
   htmls.push("</div>");
   htmls.push("<div class='sign' id='sign_cal'>");
   htmls.push("<table>");
   htmls.push("<tr>");
   htmls.push("<th>" + myMonth[0][0] + "</th>");
   htmls.push("<th>" + myMonth[0][1] + "</th>");
   htmls.push("<th>" + myMonth[0][2] + "</th>");
   htmls.push("<th>" + myMonth[0][3] + "</th>");
   htmls.push("<th>" + myMonth[0][4] + "</th>");
   htmls.push("<th>" + myMonth[0][5] + "</th>");
   htmls.push("<th>" + myMonth[0][6] + "</th>");
   htmls.push("</tr>");
   var d, w;
   for (w = 1; w < 7; w++) {
    htmls.push("<tr>");
    for (d = 0; d < 7; d++) {
     var ifHasSigned = calUtil.ifHasSigned(signList,myMonth[w][d]);
     // console.log(ifHasSigned);
     if(ifHasSigned){
      htmls.push("<td class='on'><a href='javascript:void(0);' class='rid' uid="+uid+" ids="+iYear+'-'+iMonth+'-'+(!isNaN(myMonth[w][d]) ? myMonth[w][d] : " ")+" >" + (!isNaN(myMonth[w][d]) ? myMonth[w][d] : " ") + "</a></td>");
     } else {
      htmls.push("<td>" + (!isNaN(myMonth[w][d]) ? myMonth[w][d] : " ") + "</td>");
     }
    }
    htmls.push("</tr>");
   }
   htmls.push("</table>");
   htmls.push("</div>");
   htmls.push("</div>");
   return htmls.join('');
  }
};


//选择年月
    $(function() {  
        $.simpleCanleder = function(box, options){  
            var _canlederBox = "#SimpleCanleder_Year_Month";  
            var _title_ul_li = ".title li";  
            box = $(box);  
            var box_height = parseFloat( box.height());  
            var box_width = parseFloat( box.width());  
            var boxOffset = box.offset();  
      
            var canlederBox = null;  
            box.click(function(){  
                canlederBox = $(_canlederBox);  
                if($(canlederBox).size() > 0){  
                    $(canlederBox).show();  
                }else{  
                    _buildCanlederBox();  
                    $("body").append(canlederBox);  
      
                    $(document).click(function(e){  
                        var pointX = e.pageX;  
                        var pointY = e.pageY;  
                        var $box  = canlederBox.data("box");  
      
                        var isCanlederBox = $(e.target).parents(_canlederBox);  
      
                        if(canlederBox.is(":visible") && $box && e.target != $box[0] && isCanlederBox.size() <= 0){  
                            var offset = canlederBox.offset();  
                            var top  = offset.top - 4;  
                            var left  = offset.left - 4;  
                            var height = top + parseFloat(canlederBox.outerHeight()) +  4;  
                            var width = left + parseFloat(canlederBox.outerWidth()) + 4;  
                            if(pointX > left && pointY > top &&  
                                    pointX < width && pointY < height){  
      
                            }else{  
                                canlederBox.hide();  
                            }  
                        }  
                    });  
                }  
      
                  
                canlederBox.css({"top" : boxOffset.top + box_height + 6, "left": boxOffset.left});  
                canlederBox.data("box", box);   
      
                _init();  
              
            });   
      
      
              
      
            function _init(){  
                var now = new Date();  
                var year = now.getFullYear();  
                var month = now.getMonth() + 1;  
                if(box.val()){  
                    year = box.val().split("-")[0] * 1;  
                    month = box.val().split("-")[1] * 1;  
                }  
      
                canlederBox.find(_title_ul_li).eq(1).find("div.inner").html(_getSelect(year));  
                canlederBox.find(".body li").each(function(){  
                    if($(this).text() == month){  
                        $(this).addClass("cur");  
                    }else{  
                        $(this).removeClass("cur");  
                    };  
                });  
            }  
      
            function _buildCanlederBox(){  
                canlederBox = $("<div/>");  
                canlederBox.attr("id", "SimpleCanleder_Year_Month");   
                  
                _buildTitle(canlederBox);  
                _buildBody(canlederBox);  
                canlederBox.append($("<div/>").addClass("clear"));  
                _buildBottom(canlederBox);  
                  
            };  
              
               
            function _buildTitle(canlederBox){  
                var $title =  $("<div/>").addClass("title").append("<ul/>").appendTo(canlederBox);  
                var $title_ul = $title.find("ul");  
                for(var i = 0; i < 3; i++){  
                    var $li = $("<li/>").append( $("<div/>").addClass("inner") );  
                      
                    $li.hover(function(){  
                        $(this).addClass("over");     
                    }, function(){  
                        $(this).removeClass("over");  
                    });  
      
                    $title_ul.append($li);  
                }  
                var $title_ul_li = $title_ul.find("li");  
      
                $title_ul_li.eq(0).click(function(){  
                    var year = $select.val();   //$select 在_getSelect()有定义  
                    canlederBox.find(_title_ul_li).eq(1).find("div.inner").html(_getSelect(--year));  
                }).find("div.inner").text(" < ");  
      
                $title_ul_li.eq(1).addClass("middle").click(function(){  
                      
                })  
                .find("div.inner").addClass("paddingTop").html(_getSelect());  
      
                $title_ul_li.eq(2).click(function(){  
                    var year = $select.val();   //$select 在_getSelect()有定义  
                    canlederBox.find(_title_ul_li).eq(1).find("div.inner").html(_getSelect(++year));  
                }).find("div.inner").text(" > ");  
            };  
      
            function _buildBody(canlederBox){  
                var $body =  $("<div/>").addClass("body").append("<ul/>").appendTo(canlederBox);  
                var $body_ul = $body.find("ul");  
                for(var i = 0; i < 12; i++){  
                    var $inner = $("<div/>").addClass("inner").text(i+1);  
                    var $li = $("<li/>").append($inner).click(function(){  
                        var year = canlederBox.find(_title_ul_li).eq(1).find("select").val();  
                        var month = $(this).find("div.inner").text() * 1;  
                        month = month < 10 ? "0" + month : month;  
                        canlederBox.data("box").val(year + "-" + month);  
                        canlederBox.hide();  
                    });  
                      
                    $li.hover(function(){  
                        $(this).addClass("over");     
                    }, function(){  
                        $(this).removeClass("over");  
                    });  
      
                    $body_ul.append($li);  
                }  
            };  
      
            function _buildBottom(canlederBox){  
                var $button_clear = $("<button/>").addClass("clear").click(function(){  
                    canlederBox.data("box").val("");  
                    canlederBox.hide();  
                }).text("清空");  
                var $bottom = $("<div/>").addClass("bottom").append($button_clear);  
                canlederBox.append($bottom);  
                  
            };  
              
            var $select = null;  
            function _getSelect(year){  
                if(!year){  
                    year = new Date().getFullYear();  
                }  
                  
                $select = $("<select/>");  
                for(var i = 10; i >=0; i--){  
                    $select.append($("<option/>").text(year - i ));  
                }  
                for(var i = 1; i <= 10; i++){  
                    $select.append($("<option/>").text(year + i ));  
                }  
                $select.find("option").each(function(){  
                    if($(this).text() == year){  
                        $(this).attr("selected", "selected");  
                    }  
                });  
                return $select;  
            };  
      
               
      
        };  
      
        $.fn.extend({  
            simpleCanleder: function(options) {  
                options = $.extend({},options);  
                this.each(function() {  
                    new $.simpleCanleder(this, options);  
                });  
                return this;  
            }  
        });  
    });  