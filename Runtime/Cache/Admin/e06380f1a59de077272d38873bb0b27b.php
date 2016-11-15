<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title></title>
    <link href="/Public/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/Public/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/Public/assets/css/style.css" rel="stylesheet">
    <link href="/Public/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/assets/css/jquery.treetable.css" rel="stylesheet">
    <link href="/Public/assets/css/jquery.treetable.theme.default.css" rel="stylesheet">
    <link href="/Public/assets/css/bootstrap-switch.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/assets/js/html5shiv.min.js"></script>
    <script src="/Public/assets/js/respond.min.js"></script>
    <![endif]-->


</head>`
<body>

<div class="modal-content modal_850">
    <div class="modal-header">
        <button type="button" class="close"
                data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title fb f16">
            <span>添加仓库经销商</span>
        </h4>
    </div>
    <form action="<?php echo U('Admin/Depot/addDealer');?>" method="post" enctype="multipart/form-data"  id="submit_form">
        <div class="modal-body modal_850">
            <table class="table no_border">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="80px">仓库</td><td>
                    <select class="form-control w200" name="depot" id="depot">
                        <option value="0">选择仓库</option>
                        <?php if(is_array($depot)): $i = 0; $__LIST__ = $depot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["repertory_id"]); ?>" <?php if($vo["repertory_id"] == $depotID): ?>selected<?php endif; ?> ><?php echo ($vo["repertory_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                    <td width="80px">经销商</td>
                    <td>
                        <select class="form-control w200" name="org" id="org">
                            <option value="0">选择经销商</option>
                            <?php if(is_array($orglist)): $i = 0; $__LIST__ = $orglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$org): $mod = ($i % 2 );++$i;?><option value="<?php echo ($org["org_id"]); ?>"><?php echo ($org["org_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
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
        <a id="submit_unit" class="btn btn-primary">
            <span>添加</span>
        </a>
    </div>

    <script type="text/javascript" src="/Public/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/bootstrap.min.js"></script>
    <!-- 按钮 -->
    <script type="text/javascript" src="/Public/assets/js/bootstrap-switch.js"></script>
    <script type="text/javascript" src="/Public/assets/js/highlight.js"></script>
    <script type="text/javascript" src="/Public/assets/js/main.js"></script>
    <!-- 按钮 -->
    <script type="text/javascript" src="/Public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/Public/assets/js/validate_form.js"></script>

    <link href="/Public/assets/css/manhuaDate.1.0.css" rel="stylesheet">
    <script type="text/javascript" src="/Public/assets/js/manhuaDate.1.0.js"></script>
    <script type="text/javascript" src="/Public/assets/js/zstb.js"></script>

    <script type="text/javascript">

        $(function(){
            $("#start_time,#end_time").manhuaDate({
                Event : "click",//可选
                Left : 0,//弹出时间停靠的左边位置
                Top : -16,//弹出时间停靠的顶部边位置
                fuhao : "-",//日期连接符默认为-
                isTime : false,//是否开启时间值默认为false
                beginY : 2014,//年份的开始默认为1949
                endY :2049//年份的结束默认为2049
            });
        });

        $("#submit_unit").click(function(){
            if ($('#submit_form').valid()) {
                $('#submit_form').submit();
            }
        });
    </script>

</div>
</body>
</html>