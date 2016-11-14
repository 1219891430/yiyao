/*
 * JuheSlider
 * Author:JONEDOO
 */
(function($){
    $.fn.jSlider = function(options){
        var defaults = {
            pause:3000,
            fadeOutTime:500,
            fadeInTime:500,
            naviSlider:'naviSlider'
        }

        var options = $.extend(defaults, options);

        var obj = $(this);

        obj.find('li:first').show();	//显示第一个item

        var itemCount = obj.children('li').length;

        var naviObj=$("#"+options.naviSlider);

        var i=0;	//初始化

        naviObj.children('li').click(function(){
            //var index = $(this).text();
            var index =$(this).attr("sIndex");
            i = parseInt(index)-2;
            showSlider();
        })

        var showSlider = function(){

            var next =i+1;
            if(i >= (itemCount-1)){
                i=-1;
                next=0;
            }
            obj.children('li').filter(":visible").fadeOut(options.fadeOutTime).parent().children().eq(next).fadeIn(options.fadeInTime);

            naviObj.children('li').removeClass('on').eq(next).addClass('on');

            i = i+1;
        }

        var interval = setInterval(function(){
            showSlider();
        }, options.pause);
    }
})(jQuery);