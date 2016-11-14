<?php

/*******************************************************************
 ** 文件名称: PresaleHomeController.class.php
 ** 功能描述: 平台采单首页接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class PresaleHomeController extends Controller {

	// 采单端首页
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 平台采单人员ID
		$staff_id = I("staffId", 0);
		$order_from =I("orderFrom", 0); 
		
		
		// 今日时间
		$today = date('Y-m-d', time());
		$begin_time = strtotime($today . " 00:00:00");
		$end_time = strtotime($today . " 23:59:59");

		// 查询今日预单
		$where['is_cancel'] =0; 
		$where['staff_id'] = $staff_id;
		$where['order_from'] = $order_from;
		$where['add_time'] = array(array('gt', $begin_time),array('lt', $end_time));
		$order_list = M('presale_orders')->field('order_code, order_total_money')->where($where)->select();
		
		// 今日销售总额和订单数量
		$saleOrderTotalMoney = 0;
		foreach($order_list as $val)
		{
			$order_total_money = floatval($val['order_total_money']);
			$saleOrderTotalMoney += $order_total_money;
		}
		$data['saleOrderNum'] = count($order_list);
		$data['saleOrderTotalMoney'] = $saleOrderTotalMoney;

		// 查询今日终端退货
		$return_order_list = M('presale_return')->field('return_real_money')->where($where)->select();
		
		// 今日退货总额和退货数量
		$returnOrderMoney = 0;
		foreach($return_order_list as $val)
		{
			$returnOrderMoney += floatval($val['return_real_money']);
		}
		$data['returnOrderNum'] = count($return_order_list);
		$data['returnOrderMoney'] = $returnOrderMoney;
		
		// 查询今日调换货
		$change_orders_list = M('presale_change')->field('order_total_money')->where($where)->select();

		// 今日调换货总额和数量
		$changeOrderMoney = 0;
		foreach($change_orders_list as $val)
		{
			$changeOrderMoney += floatval($val['order_total_money']);
		}
		$data['changeOrderNum'] = count($change_orders_list);
		$data['changeOrderMoney'] = $changeOrderMoney;

		// 返回
		// Array
		// 	(
		// 	    [saleOrderNum] => 2			// 销售订单数量
		// 	    [saleOrderTotalMoney] => 76 // 销售总额
		// 	    [returnOrderNum] => 1		// 终端退货数量
		// 	    [returnOrderMoney] => 25    // 终端退货金额
		// 	    [changeOrderNum] => 1		// 调换货数量
		// 	    [changeOrderMoney] => 50    // 调换货金额
		// 	)
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' =>$data));
    }

}

/*************************** end ************************************/