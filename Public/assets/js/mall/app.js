
// 加入购物车
function addcar(cv, num,org, async,url) {

    if (num == 0) {
        num = 1
    }

    if (async == undefined) {
        async = true
    }

    var cart_num = parseInt($('.cart_num').attr('attr'), 10)

    var status;

    $.ajax({
        url: '/index.php/Mall/Cart/add',
        type: 'get',
        async: async,
        data: {
            cv_id: cv,
            quantity: num,
            org:org
        },
        success: function (d) {
            status = d.status
            if(d.status) {
                cart_num += num*1

                $('.cart_num').html(cart_num)
                $('.cart_num').attr('attr', cart_num)

                if(url != undefined) {
                    location.href=url
                }

            } else {
                alert(d.msg)
                return false;
            }

        }

    })

    return status;

}