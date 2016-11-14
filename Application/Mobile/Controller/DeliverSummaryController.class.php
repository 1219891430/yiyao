<?php

/*******************************************************************
 ** 文件名称: DeliverSummaryController.class.php
 ** 功能描述: 平台车销配送汇总接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DeliverSummaryController extends Controller {

	// 今日销售情况
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function todayAction()
	{
		// 配送人员
		$staff_id = intval($_REQUEST['userId']);
		
		// 开始时间
		$start_time = date('Y-m-d');
		$start_time = strtotime($start_time . ' 00:00:00');
		$end_time = $start_time + 24*60*60;
		
		// 查询条件
		$where['o.staff_id'] = $staff_id;
		$where['o.create_time'] = array(array('gt', $start_time),array('lt', $end_time));
		$where['o.is_cancel'] = 0;

		// 今日订单
		$orderList = M('car_orders')->alias('o')->field('order_id, order_code, cust_id, cust_name, create_time, order_total_money')->where($where)->order('order_id asc')->select();
		foreach($orderList as $key=>$val){ $orderList[$key]['create_time'] = date("Y-m-d H:i", $val['create_time']); }

		// 今日销售商品
		$results = M('car_orders_goods')->alias('g')->field('g.*')
		->join('__CAR_ORDERS__ as o on o.order_id = g.order_id')
		->where($where)->order('g.goods_id asc, g.cv_id asc')->select();

		// 整理订单商品
		/*$goodsList = array();
		foreach($results as $item)
		{
			$goods_id = $item['goods_id'];
			$goods_price = $item['singleprice'] * $item['number'];
			$goods_small_number = getSmallNumber($item['cv_id'], $item['number']);
			
			if(!empty($goodsList[$goods_id]))
			{
				$goodsList[$goods_id]['goods_price'] += $goods_price;
				$new_small_number = $goodsList[$goods_id]['goods_small_number'] + $goods_small_number;
				$goodsList[$goods_id]['goods_small_number'] = new_small_number;
				$goodsList[$goods_id]['goods_number'] = getGoodsUnitString($goods_id, $new_small_number);
			}
			else
			{
				$temp = array();
				$temp['goods_id'] = $goods_id;
				$temp['goods_name'] = $item['goods_name'];
				$temp['goods_spec'] = $item['goods_spec'];
				$temp['goods_price'] = $goods_price;
				$temp['goods_small_number'] = $goods_small_number;
				$temp['goods_number'] = getGoodsUnitString($goods_id, $goods_small_number);
				$goodsList[$goods_id] = $temp;
			}
		}*/

		// 今日清欠
		$Qwhere['q.staff_id'] = $staff_id;
		$Qwhere['q.addtime'] = array(array('gt', $start_time),array('lt', $end_time));
		$results = M('car_orders_qiankuan')->alias('q')->field('o.order_id, o.order_code, o.cust_id, o.cust_name, o.order_total_money, q.price, q.addtime')
		->join('__CAR_ORDERS__ as o on q.orderid = o.order_id')
		->where($Qwhere)->order('q.oq_id asc')->select();
		$qingList = array();
		foreach($results as $item)
		{
			$temp = array();
			$temp['order_id'] = $item['order_id'];
			$temp['order_code'] = $item['order_code'];
			$temp['cust_id'] = $item['cust_id'];
			$temp['cust_name'] = $item['cust_name'];
			$temp['order_total_money'] = $item['order_total_money'];
			$temp['price'] = $item['price'];
			$temp['addtime'] = date('Y-m-d H:i', $item['addtime']);
			$qingList[] = $temp;
		}

		// 今日调换货
    	$changeList = M('car_change')->alias('o')->field('change_id, change_code, cust_id, cust_name, create_time, total_money')->where($where)->order('change_id asc')->select();	
		foreach($changeList as $key=>$val){ $changeList[$key]['create_time'] = date("Y-m-d H:i", $val['create_time']); }

		// 今日退货
		$returnList = M('car_return')->alias('o')->field('return_id, return_code, cust_id, cust_name, create_time, total_money')->where($where)->order('return_id asc')->select();
		foreach($returnList as $key=>$val){ $returnList[$key]['create_time'] = date("Y-m-d H:i", $val['create_time']); }
		
		
		$orderList1=array();
		foreach($orderList as $v){
			$orderList1[]=$v;
		}
		$qingList1=array();
		foreach($qingList as $v){
			$qingList1[]=$v;
			
		}
		$goodsList1=array();
		foreach($goodsList as $v){
			$goodsList1[]=$v;
			
		}
		$changeList1=array();
		foreach($changeList as $v){
			$changeList1[]=$v;
			
		}
		$returnList1=array();
		foreach($returnList as $v){
			$returnList1[]=$v;
			
		}
		
		// 返回
		$data['orderList'] = $orderList1;
		$data['qingList'] = $qingList1;
		//$data['goodsList'] = $goodsList1;
		$data['changeList'] = $changeList1;
		$data['returnList'] = $returnList1;
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
    }
	
	// 本月销售情况
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function monthAction()
	{
	
	}

}

/*************************** end ************************************/