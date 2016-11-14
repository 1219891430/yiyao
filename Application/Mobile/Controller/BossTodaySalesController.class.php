<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/13/0013
 * Time: 14:09
 */

namespace Mobile\Controller;
use Think\Controller;

class BossTodaySalesController extends Controller
{
    public function goodsPromotionAction(){
        $time = I('time');
        $org_parent_id = I('pid');

        /*
         * ordernumber:订单数，staff_id：业务员id，staff_name：业务员名称，allmoney：总金额
         [{"ordernumber":"1","staff_id":"1","staff_name":"张三","allmoney":"100"},{"ordernumber":"3","staff_id":"2","staff_name":"李四","allmoney":"1200"} ]
        */

        if(empty($dataList)){
            //echo json_encode([]);
        }
        else{

        }

        $dataList = array(
            array('ordernumber'=>'1','staff_id'=>'1','staff_name'=>'张三','allmoney'=>'100'),
            array('ordernumber'=>'2','staff_id'=>'2','staff_name'=>'李四','allmoney'=>'1020'),
        );
        echo json_encode($dataList);
    }


}