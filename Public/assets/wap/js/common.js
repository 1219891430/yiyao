/**
 * Created by Administrator on 2016/9/2.
 */
$(document).ready(function(){
    //幻灯
    $("#slider").jSlider({
        pause:10000,
        naviSlider:'naviSlider'
    });

    //列表图片高度等于宽度
    var imgWidth = $(".equal-img").width();
    $(".equal-img").css("height",imgWidth);
})

//搜索页顶部效果
$(window).scroll(function(){
    height = $(window).scrollTop();
    if (height<300){
        $(".sch-top-empty").css("display","block");
        $(".zy-top").css({"height":"100px","transform":"translateY(0)","transition":"0.5s ease 0s"});
    }else{
        $(".sch-top-empty").css("display","none");
        $(".zy-top").css({"height":"44px","transform":"translateY(-96px)","transition":"0.5s ease 0s"});
    }
});
//弹框
function toggleGuestDetails() {
    $('.bom-member').toggle();
}
$('.p-imgbox').click(toggleGuestDetails);
$('.bom-member .btn-grn').click(toggleGuestDetails);


// 加入购物车
var addcar_wap = function (cv, num,org,url,reurl) {

    if (num == 0) {
        num = 1
    }

    var cart_num = parseInt(num, 10)

    $.ajax({
        url: url,
        type: 'get',
        data: {
            cv_id: cv,
            quantity: num,
            org:org
        },

        success: function (d) {
            
            if(d.status) {
                cart_num += num*1
                alert("添加成功");
                if(reurl != undefined) {
                    location.href=reurl
                }

            } else {
                alert(d.msg)
                return false;
            }

        }

    })
}

