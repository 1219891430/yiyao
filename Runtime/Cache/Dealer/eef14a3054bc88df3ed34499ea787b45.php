<?php if (!defined('THINK_PATH')) exit();?><div class="modal-content modal_650">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>定位设置</span>
        </h4>
    </div>
    <form action="/index.php/Dealer/Action/edit" id="submit_route_form" method="post">
        <div class="modal-body modal_650">
            <table class="table no_border list_table">
                <thead>
                <tr>
                    <td class="tc fb" width="15%">账号</td>
                    <td class="tc fb" width="15%">手机</td>
                    <td class="tc fb" width="10%">姓名</td>
                    <td class="tc fb" width="20%">开始时间</td>
                    <td class="tc fb" width="20%">结束时间</td>
                    <td class="tc fb" width="20%">间隔</td>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($route)): $i = 0; $__LIST__ = $route;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td class="tc"><?php echo ($vo["login_user"]); ?><input type="hidden" value="<?php echo ($vo["staff_id"]); ?>" name="staff_id[]"/></td>
                    <td class="tc"><?php echo ($vo["mobile"]); ?></td>
                    <td class="tc"><?php echo ($vo["staff_name"]); ?></td>
                    <td class="tc">
                    <select class="form-control w100 starttime" name="start_time[]">
                        <?php if(($vo["begin_time"]) == "0"): ?><option selected="selected" value="0">未设置</option>
                        <?php else: ?>
                           <option value="0">未设置</option><?php endif; ?>
                        <?php if(is_array($arStart)): $i = 0; $__LIST__ = $arStart;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item); ?>" <?php if($vo['begin_time'] == $item): ?>selected="selected"<?php endif; ?>><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    </td>
                    <td class="tc">
                    	<select class="form-control w100 endtime" name="end_time[]">
                        <?php if(($vo["end_time"]) == "0"): ?><option selected="selected" value="0">未设置</option><?php else: ?><option value="0">未设置</option><?php endif; ?>
                        <?php if(is_array($arEnd)): $i = 0; $__LIST__ = $arEnd;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item); ?>" <?php if($vo['end_time'] == $item): ?>selected="selected"<?php endif; ?>><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <?php $__FOR_START_4730__=$start[0];$__FOR_END_4730__=$start[1];for($time=$__FOR_START_4730__;$time < $__FOR_END_4730__;$time+=$step){ } ?>
                    </td>
                    <td class="tc"><input onkeyup="check_num(this)" maxlength="3" class="w50 tinterval" name="interval[]" style="color:#777" type="text" value="<?php echo ($vo["interval"]); ?>"/>分钟</td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr>
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
        <div class="error">
        </div>
    </form>
    <div class="modal-footer">
        <a href="#" class="btn btn-default"
           data-dismiss="modal">关闭
        </a>
        <a id="submit_route" class="btn btn-primary">
            <span>保存</span>
        </a>
    </div>
    <script type="text/javascript">
        $(function(){
            $("#submit_route").click(function(){
              if(!check())
              {
                alert("输入正确的时间间隔")
              }
              else if(!check_time())
              {
                  alert("设置起始结束时间")
              }
              else
              {
                routeCongif()
              }
            })
        })
        function check_time()
        {
            var j=0;
            $(".list_table select").each(function(i){
                    if($(".list_table .starttime").eq(i).val()!=0&&$(".list_table .endtime").eq(i).val()==0)
                    {
                        j++;
                    }
                else if($(".list_table .starttime").eq(i).val()!=0&&$(".list_table .endtime").eq(i).val()==0)
                    {
                        j++;
                    }
            })
            return j>0?false:true;
        }
        function routeCongif()
        {
            $.ajax({
                url:"/index.php/Dealer/Action/updateStaffRoute",
                type:"post",
                dataType:"json",
                data:$("#submit_route_form").serialize(),
                beforeSend:function(){
                    $(".await").show();
                },
                success:function(data){
                    alert(data["info"]);
                    $("#myModal").modal("hide");
                    $(".await").fadeOut(500);
                }
            })
        }
            function check()
            {
               var i=0;
               $(".tinterval").each(function(){
                   if($(this).val()==""||$(this).val()<0)
                   {
                       i++;
                   }
               })
                return i>0?false:true;
            }

    </script>


</div>