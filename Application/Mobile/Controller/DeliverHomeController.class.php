<?php

/*******************************************************************
 ** 文件名称: DeliverHomeController.class.php
 ** 功能描述: 平台车销配送首页接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DeliverHomeController extends Controller {

	// 配送端首页
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 平台配送人员ID
		$staff_id = I("userId", 0);
		
		// 今日时间
		$today = date('Y-m-d', time());
		$begin_time = strtotime($today . " 00:00:00");
		$end_time = strtotime($today . " 23:59:59");

		// 今日销售
		$where['is_cancel'] = 0;
		$where['staff_id'] = $staff_id;
		$where['create_time'] = array(array('gt', $begin_time),array('lt', $end_time));
		$order_list = M('car_orders')->field('order_id,order_total_money,order_real_money,is_full_pay')->where($where)->order('order_id desc')->select();
		
		// 今日销售总额
		$totalMoney = 0;
		$totalNumber = count($order_list);
		
		// 今日实收
		$totalPayMoney = 0;
		
		// 今日欠款
		$owedNumber = 0;
		$oweMoney = 0;
		
		// 循环计算订单价格
		foreach($order_list as $item)
		{
			// 存在欠款订单
			$order_id = $item['order_id'];
			$is_full_pay = intval($item['is_full_pay']);
			$banlance = $item['order_total_money'] - $item['order_real_money'];
			if($is_full_pay == 0 && $banlance > 0)
			{
				// 查询今日是否已经清欠过
				$qwhere = array();
				$qwhere['orderid'] = $order_id;
				$qwhere['addtime'] = array(array('gt', $begin_time),array('lt', $end_time));
				$payMoney = M('car_orders_qiankuan')->where($qwhere)->sum('price');
				$payMoney = floatval($payMoney);
				
				// 欠款订单累加
				$owedNumber++;
				
				// 欠款费用计算
				$oweMoney = $banlance - $payMoney;
			}

			// 销售总额和实收总额			
			$totalMoney += $item['order_total_money'];
			$totalPayMoney += $item['order_real_money'];
		}
		
		// 今日退货
		$results = M('car_return')->where($where)->field('return_id, total_money')->order('return_id asc')->select();
		$returnNumber = count($results);
		$returnMoney = 0;
		foreach($results as $item){ $returnMoney += floatval($item['total_money']); }
		
		// 今日调货
		$results = M('car_change')->field('change_id, total_money')->where($where)->order('change_id asc')->select();
		$changeNumber = count($results);
		$changeMoney = 0;
		foreach($results as $item){ $changeMoney += floatval($item['total_money']); }
		
		// 今日清欠
		$where = array();
		$where['qk_type'] = 0; // 0代表配送人员清欠, 1代表平台内勤清欠 
		$where['staff_id'] = $staff_id;
		$where['addtime'] = array(array('gt', $begin_time),array('lt', $end_time));
		$results = M('car_orders_qiankuan')->field('oq_id, price')->where($where)->order('oq_id')->select();
		$qingqianNumber = count($results);
		$qingqianMoney = 0;
		foreach($results as $item){ $qingqianMoney += $item['price']; }
		
		// 返回数据
		$data['totalMoney'] = $totalMoney;			// 今日销售总额
		$data['totalNumber'] = $totalNumber;		// 今日销售订单总数量	
		$data['totalPayMoney'] = $totalPayMoney;	// 今日销售实收总额
		$data['owedNumber'] = $owedNumber;			// 今日欠款订单数量
		$data['oweMoney'] = $oweMoney;				// 今日欠款总额
		$data['returnNumber'] = $returnNumber;		// 今日终端退货订单数量
		$data['returnMoney'] = $returnMoney;		// 今日终端退货总额
		$data['changeNumber'] = $changeNumber;		// 今日调换货订单数量
		$data['changeMoney'] = $changeMoney;		// 今日调换货总额
		$data['qingqianNumber'] = $qingqianNumber;	// 今日清欠笔数
		$data['qingqianMoney'] = $qingqianMoney;	// 今日清欠总额
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' =>$data));
    }

}

/*************************** end ************************************/