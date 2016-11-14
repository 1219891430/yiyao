/**
 * Created by rj02 on 15-2-15.
 */
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
            alert(data["info"]);
            if(data["res"]==1)
            {
                if(is_reload)
                    reload();
                else
                    $(".await").hide();
            }
            else{
                $(".await").hide();
            }
        }
    })
    //页面刷新
    function reload()
    {
        location.reload();
    }
}