<?php

/*******************************************************************
 ** 文件名称: BossCommonController.class.php
 ** 功能描述: 经销商老板端公共接口
 ** 创建人员: wangbo
 ** 创建日期: 2016-09-07
 *******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossCommonController extends Controller {

    public function getShopDataAction(){
        $org_parent_id = I('companyId');
        $shop_id = I('shopId');

        // 查询条件
        $where = array();
        $where["cust_id"] = $shop_id;
        $where["org_parent_id"] = $org_parent_id;
        $where['is_cancel'] = 0;

        $begintime= strtotime(date('Y-m-01', time()));
       // $endtime =  strtotime(date('Y-m-t', time()));
        $endtime = strtotime(" +1 month",$begintime);

        $where['create_time'] = array(array("gt", $begintime),array("lt", $endtime));


        //获取经销商下的所有人员
        /*$staff_list = M('org_staff')->where("org_parent_id= $org_parent_id")->order('staff_id desc')->select();
        foreach($staff_list as $a){
           $staff_ids[] = $a['staff_id'];
        }*/

        //$where['staff_id'] = array ('in',$staff_ids);

        //月销售额
        $monthMoney = 0;
        // 车销
        $carMonthMoney = 0;
        /*
        $carMonthMoney = M('car_orders')->where($where)->sum('order_total_money');
        if(empty($carMonthMoney)){
            $carMonthMoney = 0;
        }
        */

        // 经销商车销
        $carsaleMonthMoney = M('carsale_orders')->where($where)->sum('order_total_money');
        if(empty($carsaleMonthMoney)){
            $carsaleMonthMoney = 0;
        }


        $monthMoney += $carMonthMoney + $carsaleMonthMoney;

        //退货额
        $returnTotal = 0;

        // 车销退货
        $carReturnTotal = 0;
        /*
        $carReturnTotal = M('car_return')->where($where)->sum("total_money");
        if(empty($carReturnTotal)){
            $carReturnTotal = 0;
        }
        */
        // 经销商车销退货
        $carsaleReturnTotal = M('carsales_return')->where($where)->sum("total_money");
        if(empty($carsaleReturnTotal)){
            $carsaleReturnTotal = 0;
        }

        $returnTotal += $carReturnTotal + $carsaleReturnTotal;

        //欠款额
        $creditMoney = 0;

        $where2["o.is_cancel"] = 0;
        $where2['o.is_full_pay'] = 0;
        $where2["o.cust_id"] = $shop_id;
        //$where2['o.staff_id'] = array ('in',$staff_ids);
        $where2["o.org_parent_id"] = $org_parent_id;

        // 仓库车销欠款
        /*
        $carQiankuan = M("car_orders")->alias('o')
            ->join('left join __CAR_ORDERS_QIANKUAN__ as q on o.order_id=orderid')
            ->field('o.order_total_money, o.order_real_money, sum(q.price) as order_pay_money')
            ->where($where2)
            ->group("o.order_id")
            ->select();
        foreach ($carQiankuan as $item) {
            $creditMoney += ($item['order_total_money'] - $item['order_real_money'] - floatval($item['order_pay_money']));
        }
        */

        // 经销商车销欠款
        $carsaleQiankuan = M('carsale_orders')->alias('o')
            ->field('o.order_total_money, o.order_real_money, sum(q.price) as order_pay_money')
            ->join('left join __CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
            ->where($where2)
            ->group('o.order_id')
            ->order('o.order_id asc')
            ->select();

        foreach($carsaleQiankuan as $item){
            $creditMoney += ($item['order_total_money'] - $item['order_real_money'] - floatval($item['order_pay_money']));
        }

        //print_r($creditMoney);die();


        //陈列额
        $displayMoney = 0;

        $data = array(
            'monthMoney' => $monthMoney,   //月销售
            'returnTotal' => $returnTotal,  //退货
            'creditMoney' => $creditMoney,  //欠款
            'displayMoney' => $displayMoney  //陈列
        );

        echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
    }

    public function getShopOrderAction(){
        $org_parent_id = I('companyId');
        $page = I('page');
        $type = I('type');  //cx车销  yd预单
        $shopId = I('shopId');
        $pageSize = 20;
        //获取经销商下的所有人员
        /*
        $staff_list = M('org_staff')->where("org_parent_id= $org_parent_id")->order('staff_id desc')->select();
        foreach($staff_list as $a){
            $staff_ids[] = $a['staff_id'];
        }
        $where['o.staff_id'] = array ('in',$staff_ids);
        */
        $where['is_cancel'] = 0;
        $where['o.org_parent_id'] = $org_parent_id;
        $where['cust_id'] = $shopId;

        if($type=='cx'){
            $data = M('carsale_orders')->alias('o')->field('order_id,order_code,create_time as time,cust_name,order_total_money,os.staff_name')
                ->join('__ORG_STAFF__ os on o.staff_id=os.staff_id')
                ->where($where)
                ->page($page,$pageSize)
                ->order("create_time desc")
                ->select();
        }
        else{
            $data = M('car_orders')->alias('o')->field('order_id,order_code,create_time as time,cust_name,order_total_money,os.staff_name')
                ->join('__ORG_STAFF__ os on o.staff_id=os.staff_id')
                ->where($where)
                ->page($page,$pageSize)
                ->order("create_time desc")
                ->select();
        }

        if(is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key]['time'] = date("Y-m-d H:i", $val['time']);
                $data[$key]['type'] = $type=='cx' ? 1 : 4;
            }
        }

        echo json_encode(array('error' => -1, 'msg' => '成功', 'data' => $data));

    }
}
?>