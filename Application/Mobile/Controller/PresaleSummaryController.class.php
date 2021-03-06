<?php

/*******************************************************************
 ** 文件名称: PresaleSummaryController.class.php
 ** 功能描述: 平台采单汇总接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class PresaleSummaryController extends Controller {

	// 今日销售情况
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function todayAction()
	{
		// 采单人员
		$staff_id = intval($_REQUEST['userId']);
		$order_from = intval($_REQUEST['orderFrom']);
		if(empty($order_from)){
			$order_from=1;
		}
		// 开始时间
		$start_time = date('Y-m-d');
		$start_time = strtotime($start_time . ' 00:00:00');
		$end_time = $start_time + 24*60*60;
		
		// 查询条件
		$where["o.order_from"]=$order_from;
		$where['o.staff_id'] = $staff_id;
		$where['o.add_time'] = array(array('gt', $start_time),array('lt', $end_time));
		$where['o.is_cancel'] = 0;

		// 今日订单
		$orderList = M('presale_orders')->alias('o')->field('order_id, order_code, cust_id, cust_name, add_time, order_total_money,order_status')->where($where)->order('order_id asc')->select();
		foreach($orderList as $key=>$val){ $orderList[$key]['add_time'] = date("Y-m-d H:i", $val['add_time']); }

		// 今日销售商品
		$results = M('presale_orders_goods')->alias('g')->field('g.*')
		->join('__PRESALE_ORDERS__ as o on o.order_id = g.order_id')
		->where($where)->order('g.goods_id asc, g.cv_id asc')->select();

		// 整理订单商品
		$goodsList = array();
		foreach($results as $item)
		{
			$goods_id = $item['goods_id'];
			$goods_price = $item['singleprice'] * $item['number'];
			$goods_small_number = getSmallNumber($item['cv_id'], $item['number']);
			
			if(!empty($goodsList[$goods_id]))
			{
				$goodsList[$goods_id]['goods_price'] += $goods_price;
				$new_small_number = $goodsList[$goods_id]['goods_small_number'] + $goods_small_number;
				$goodsList[$goods_id]['goods_small_number'] = $new_small_number;
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
		}
		$newGoodsList = array();
		foreach($goodsList as $val){ $newGoodsList[] = $val; }
		unset($goodsList);


		// 今日调换货
    	$changeList = M('presale_change')->alias('o')->field('change_id, change_code, cust_id, cust_name, add_time, order_total_money, order_status')->where($where)->order('change_id asc')->select();
		foreach($changeList as $key=>$val){ $changeList[$key]['add_time'] = date("Y-m-d H:i", $val['add_time']); }

		// 今日退货
		$returnList = M('presale_return')->alias('o')->field('return_id,return_code,cust_id,cust_name,return_real_money,add_time,order_status')->where($where)->order('return_id asc')->select();
		foreach($returnList as $key=>$val){ $returnList[$key]['add_time'] = date("Y-m-d H:i", $val['add_time']); }
		
		// 返回
		$data['orderList'] = $orderList;
		$data['goodsList'] = $newGoodsList;
		$data['changeList'] = $changeList;
		$data['returnList'] = $returnList;
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
    }
	
	// 预单详细
	// 创建人员: richie
	// 创建日期: 2016-08-15
	public function orderDetailAction()
	{
		// 采单ID和订单ID
		$userId = intval($_REQUEST['userId']);
		$orderId = intval($_REQUEST['orderId']);
		
		// 查询订单信息
		$result = M('presale_orders')->where("order_id = $orderId")->find();
		$orderInfo['order_id'] = $result['order_id'];
		$orderInfo['order_code'] = $result['order_code'];
		$orderInfo['org_parent_id'] = $result['org_parent_id'];
		$orderInfo['cust_id'] = $result['cust_id'];
		$orderInfo['cust_name'] = $result['cust_name'];
		$orderInfo['cust_address'] = $result['cust_address'];
		$orderInfo['order_total_money'] = $result['order_total_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['add_time']);
		$orderInfo['order_way'] = $result['order_way'];
		$orderInfo['order_remark'] = $result['order_remark'];
		$orderInfo['order_status'] = $result['order_status'];

		// 订单商品信息
		$results = M('presale_orders_goods')->where("order_id = $orderId")->order("cv_id asc")->select();
		$orderGoods = array();
		foreach($results as $item)
		{
			$temp = array();
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_spec'];
			$temp['singleprice'] = $item['singleprice'];
			$temp['number'] = $item['number'];
			$temp['unit_name'] = $item['unit_name'];
			$orderGoods[] = $temp;
		}
		
		// 返回
		$data['orderInfo'] = $orderInfo;
		$data['orderGoods'] = $orderGoods;
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
	}

	// 退货详细
	// 创建人员: richie
	// 创建日期: 2016-08-15
	public function returnDetailAction()
	{
		// 采单人员ID和退货单ID
		$userId = intval($_REQUEST['userId']);
		$orderId = intval($_REQUEST['orderId']);
		
		// 查询订单信息
		$result = M('presale_return')->where("return_id = $orderId")->find();
		$orderInfo['order_id'] = $result['return_id'];
		$orderInfo['order_code'] = $result['return_code'];
		$orderInfo['org_parent_id'] = $result['org_parent_id'];
		$orderInfo['cust_id'] = $result['cust_id'];
		$orderInfo['cust_name'] = $result['cust_name'];
		$orderInfo['cust_address'] = $result['cust_address'];
		$orderInfo['order_total_money'] = $result['return_real_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['add_time']);
		$orderInfo['order_way'] = $result['order_way'];
		$orderInfo['order_remark'] = $result['order_remark'];

		// 订单商品信息
		$results = M('presale_return_goods')->where("return_id = $orderId")->order("cv_id asc")->select();
		$orderGoods = array();
		foreach($results as $item)
		{
			$temp = array();
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_sepc'];
			$temp['singleprice'] = $item['goods_money'];
			$temp['number'] = $item['goods_num'];
			$temp['unit_name'] = $item['goods_unit'];
			$orderGoods[] = $temp;
		}
		
		// 返回
		$data['orderInfo'] = $orderInfo;
		$data['orderGoods'] = $orderGoods;
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
	}
	
	// 调换货详细
	// 创建人员: richie
	// 创建日期: 2016-08-15
	public function changeDetailAction()
	{
		// 采单人员ID和退货单ID
		$userId = intval($_REQUEST['userId']);
		$orderId = intval($_REQUEST['orderId']);

		// 查询订单信息
		$result = M('presale_change')->where("change_id = $orderId")->find();
		$orderInfo['order_id'] = $result['change_id'];
		$orderInfo['order_code'] = $result['change_code'];
		$orderInfo['org_parent_id'] = $result['org_parent_id'];
		$orderInfo['cust_id'] = $result['cust_id'];
		$orderInfo['cust_name'] = $result['cust_name'];
		$orderInfo['cust_address'] = $result['cust_address'];
		$orderInfo['order_total_money'] = $result['order_total_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['add_time']);
		$orderInfo['order_way'] = $result['order_way'];
		$orderInfo['order_remark'] = $result['order_remark'];

		// 订单商品信息
		$results = M('presale_change_goods')->where("change_id = $orderId")->order("cv_id asc")->select();
		$orderGoodsIn = array();
		$orderGoodsOut = array();
		foreach($results as $item)
		{
			$is_change_in = $item['is_change_in'];
		
			$temp = array();
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_sepc'];
			$temp['singleprice'] = $item['singleprice'];
			$temp['number'] = $item['number'];
			$temp['unit_name'] = $item['unit_name'];

			if($is_change_in == 1)
			{
				$orderGoodsIn[] = $temp;
			}
			else
			{
				$orderGoodsOut[] = $temp;
			}
		}
		
		// 返回
		$data['orderInfo'] = $orderInfo;
		$data['orderGoodsIn'] = $orderGoodsIn;
		$data['orderGoodsOut'] = $orderGoodsOut;
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
	}
	
	// 本月销售情况
	public function monthAction()
	{
	
	}

}

/*************************** end ************************************/