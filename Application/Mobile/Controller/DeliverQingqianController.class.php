<?php

/*******************************************************************
 ** 文件名称: DeliverQingqianController.class.php
 ** 功能描述: 平台车销配送清欠接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DeliverQingqianController extends Controller {

	// 今日赊欠
	public function orderListAction()
	{
		// 配送人员
		$staff_id = intval($_REQUEST['userId']);
		
		// 查询该店铺赊欠订单
		$where['staff_id'] = $staff_id;
		$where['is_full_pay'] = 0;
		$where['is_cancel'] = 0;
		$orderList = M('car_orders')->field('order_id, order_code, cust_id, cust_name, order_total_money, order_real_money, create_time')->where($where)->order('order_id desc')->select();
		
		// 查询历史清欠
		foreach($orderList as $key => $item)
		{
			$order_id = intval($item['order_id']);
			$qingPrice = M('car_orders_qiankuan')->where("orderid = $order_id")->sum('price');
			$orderList[$key]['banlance_money'] = $item['order_total_money'] - $item['order_real_money'] - floatval($qingPrice);
            $orderList[$key]['banlance_money'] = sprintf('%.2f',$orderList[$key]['banlance_money']);

			$orderList[$key]['create_time'] = date('Y-m-d H:i', $item['create_time']);
		}
		
		// 返回数据
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $orderList));
	}

	// 清欠订单列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function orderAction()
	{
		// 配送人员和商铺ID
		$staff_id = intval($_REQUEST['userId']);
		$shop_id = intval($_REQUEST['shopId']);
		
		// 查询该店铺赊欠订单
		$where['staff_id'] = $staff_id;
		$where['cust_id'] = $shop_id;
		$where['is_full_pay'] = 0;
		$where['is_cancel'] = 0;
		$orderList = M('car_orders')->field('order_id, order_code, order_total_money, order_real_money, create_time')->where($where)->order('order_id desc')->select();
		
		// 查询历史清欠
		foreach($orderList as $key => $item)
		{
			$order_id = intval($item['order_id']);
			$qingPrice = M('car_orders_qiankuan')->where("orderid = $order_id")->sum('price');
			$orderList[$key]['banlance_money'] = $item['order_total_money'] - $item['order_real_money'] - floatval($qingPrice);
			$orderList[$key]['banlance_money']=sprintf('%.2f',$orderList[$key]['banlance_money']);
			
			$orderList[$key]['create_time'] = date('Y-m-d H:i', $item['create_time']);
		}
		
		// 返回数据
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $orderList));
    }

	// 清欠订单详细sprintf='%.2f',###
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function detailAction()
	{
		// 配送人员和商铺ID
		$staff_id = intval($_REQUEST['userId']);
		$shop_id = intval($_REQUEST['shopId']);
		$order_id = intval($_REQUEST['orderId']);
		
		// 查询订单详细
		$orderInfo = array();
		$result = M("car_orders")->where("order_id = $order_id")->find();
		$orderInfo['order_id'] = $result['order_id'];
		$orderInfo['order_code'] = $result['order_code'];
		$orderInfo['order_total_money'] = $result['order_total_money'];
		$orderInfo['order_real_money'] = $result['order_real_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);

		// 历史清欠记录
		$qingPrice = 0;
		$qingList = M('car_orders_qiankuan')->field('price, addtime')->where("orderid = $order_id")->order("oq_id asc")->select();
		foreach($qingList as $key=>$val)
		{
			$qingList[$key]['addtime'] = date('Y-m-d H:i', $val['addtime']);
			$qingPrice += floatval($val['price']);
		}

		// 历史清欠总额
		$orderInfo['qingPrice'] = $qingPrice;
		$orderInfo['banlance_money'] = $orderInfo['order_total_money'] - $orderInfo['order_real_money'] - floatval($qingPrice);

        $orderInfo['banlance_money'] =sprintf('%.2f',$orderInfo['banlance_money']);


		// 查询订单商品列表
		$goodsList = array();
		$results = M("car_orders_goods")->where("order_id = $order_id")->order('cv_id')->select();
		foreach($results as $item)
		{
			$temp = array();
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['good_name'] = $item['good_name'];
			$temp['good_spec'] = $item['good_spec'];
			$temp['singleprice'] = $item['singleprice'];
			$temp['number'] = $item['number'];
			$temp['unit_name'] = $item['unit_name'];
			$goodsList[] = $item;
		}
		
		// 返回
		$data['orderInfo'] = $orderInfo;
		$data['qingList'] = $qingList;
		$data['goodsList'] = $goodsList;
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
    }
	
	// 清欠订单
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function payAction()
	{
		// 添加清欠记录
		$data['orderid'] = $orderId = I('orderId');
		$data['cust_id'] = I('shopId');
		$data['staff_id'] = I('userId');
		$data['price'] = I('price');
		$data['mark'] = I('remark');
		$data['addtime'] = time();
		$data['qk_type'] = 0;
		M('car_orders_qiankuan')->add($data);
		
		// 查看是否清欠完毕
		$orderInfo = M('car_orders')->field('order_total_money, order_real_money')->where("order_id = $orderId")->find();
		$qingPrice = M('car_orders_qiankuan')->where("orderid = $orderId")->sum('price');
		$banlance = $orderInfo['order_total_money'] - $orderInfo['order_real_money'] - floatval($qingPrice);
		$banlance=sprintf("%.2f",$banlance);
		if($banlance <= 0){ M('car_orders')->where("order_id = $orderId")->setField('is_full_pay', 1); }
		
		// 返回成功
		echo json_encode(array('error' => '-1', 'msg' => '成功'));
	}

}

/*************************** end ************************************/