<?php

/*******************************************************************
 ** 文件名称: PurchaseOrderController.class.php
 ** 功能描述: 平台采购端
 ** 创建人员: wangbo
 ** 创建日期: 2016-10-12
 *******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class PurchaseOrderController extends Controller {

    public function indexAction()
    {

        // 采购端人员ID
        $staff_id = intval(I('userId'));

        // 起始时间，默认 前3天
        $day2 = date('Y-m-d', strtotime('-3day') );
        $begin_time = strtotime($day2 . " 00:00:00");
        // 截止时间
        $today = date('Y-m-d', time());
        $end_time = strtotime($today . " 23:59:59");


        $where['staff_id'] = $staff_id;
        $where['is_cancel'] = 0;
        //$where['add_time'] = array(array('gt', $begin_time),array('lt', $end_time));

        $list = M('purchase_orders')
            ->field('order_id,order_code,order_remark,add_time,is_cancel,cancel_time,staff_id,class_name')
            ->where($where)
            ->order('order_id desc')
            ->select();

        // 采购单为空
        if(empty($list)){ echo json_encode(array('error' => '1', 'msg' => "暂无采购单")); exit; }

        for($i=0; $i<count($list); $i++)
        {
            $list[$i]['add_time'] = date('Y-m-d H:i', $list[$i]['add_time']);
        }

        echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $list));
    }

    public function detailAction()
    {
        $order_id = I("order_id");

        $where["order_id"] = $order_id;

        $order = M("purchase_orders")->where($where)->find();

        $order_data = json_decode($order['order_data'],true);
        $shop_ids = $order_data['shop_ids'];
        $class_list = $order_data['data'];
        $order['order_data'] = array_values($class_list);


        $shops = array();
        if (!empty($shop_ids)) {
            $where = [];
            $where['cust_id'] = ["in", $shop_ids];
            $shops = M("customer_info")->field('cust_id,cust_name')->where($where)->select();
        }

        $order['shops']= $shops;
        if($order){
            $order['add_time'] = date('Y-m-d H:i', $order['add_time']);
            $data["orderInfo"] = $order;

            $res["error"]=-1;
            $res['msg']="成功";
            $res["data"]=$data;
        }else{
            $res["error"]=1;
            $res['msg']="失败";
        }

        echo json_encode($res);
    }
}