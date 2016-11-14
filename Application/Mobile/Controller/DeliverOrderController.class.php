<?php

/*******************************************************************
 ** 文件名称: DeliverOrderController.class.php
 ** 功能描述: 平台车销配送销售,终端退货, 调换货接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DeliverOrderController extends Controller {

	// 预单列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 配送人员ID和终端店ID
		$userId = intval($_REQUEST['userId']);
		$shopId = intval($_REQUEST['shopId']);
		
		// 查询需要配送的预单
		$where['ao.admin_id'] = $userId;
		$where['ao.shop_id'] = $shopId;
		$where["o.cust_id"]=$shopId;
		$where['ao.order_type'] = 1; // 预单
		$where['ao.order_state'] = 2; // 已派送
		$results = M('admin_order')->alias('ao')->field('o.*, ao.order_state')
		->join('__PRESALE_ORDERS__ as o on ao.order_id = o.order_id')
		->where($where)->order('o.order_id asc')->select();
		
		// 整理预单列表
		$saleList = array();
		foreach($results as $item)
		{
			$temp = array();
			$temp['order_id'] = $item['order_id'];
			$temp['order_code'] = $item['order_code'];
			$temp['cust_name'] = $item['cust_name'];
			$temp['order_total'] = $item['order_total_money'];
			$temp['order_way'] = $item['order_way'];
			$temp['create_time'] = date('Y-m-d H:i', $item['add_time']);
			$temp['order_state'] = $item['order_state'];
			$saleList[] = $temp;
		}

		// 返回
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $saleList));
	}
	
	// 预单详细
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function detailAction()
	{
		// 配送人员ID和订单ID
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

	// 提交车销单
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function addAction()
	{
		// 用户提交数据
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay');			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goodList	= $_REQUEST["goodsList"];	// 商品数据
		
		// 经销商ID和预单ID
		$org_parent_id = I('orgId');
		$orderId = I('orderId');

		// 订单实收金额
		$order_real_money = I('money');

		// 商品信息
		$goods_list = json_decode($goodList,true);
		// $goodList 商品信息
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['unit_name']				// 单位名称

		// 采单员所在仓库
		$depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');
		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

		// 添加订单
		$order_info = array();
		$order_info['order_code'] = create_uniqid_code('CO', $staff_id);
		$order_info['repertory_id'] = intval($depot_id);
		$order_info['org_parent_id'] = $org_parent_id; // 经销商
		$order_info['staff_id'] = $staff_id;
		$order_info['cust_id'] = $cust_id;
		$order_info['cust_name'] = $shopInfo['cust_name'];
		$order_info['cust_contact'] = $shopInfo['contact'];
		$order_info['cust_address'] = $shopInfo['address'];
		$order_info['cust_tel'] = $shopInfo['telephone'];
		$order_info['order_real_money'] = $order_real_money; // 订单实收金额
		$order_info['order_remark'] = $remark;
		$order_info['order_way'] = $order_way;
		$order_info['order_id'] = $orderId; // 预单ID
		$flag = D('DeliverOrder')->addOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);

		// 减少车销库存
		

		// 设置订单已完成
		$where = array();
		$where['admin_id'] = $staff_id;
		$where['order_type'] = 1;
		$where['order_id'] = $orderId;		
		M("admin_order")->where($where)->setField('order_state', 3);

		// 返回
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '添加成功', 'id'=>$flag));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '添加失败'));
		}
    }

	// 取消车销单, 只能取消当天车销单
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function cancelAction()
	{
		// 订单ID
		$order_id = intval($_REQUEST['orderId']);
		$staff_id = intval($_REQUEST['userId']);
	
		// 是否今天订单
		$beginTime = date('Y-m-d');
		$beginTime = strtotime($beginTime." 00:00:00");
		$endTime = $beginTime + 60*60*24;
		
		// 搜索条件
		$where['order_id'] = $order_id;
		$where['staff_id'] = $staff_id;
		$where['create_time'] = array(array('gt', $beginTime), array('lt', $endTime));
		$where['is_cancel'] = 0; // 未取消的订单
		$orderInfo = M('car_orders')->where($where)->field('order_id, order_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('car_orders')->where($where)->save($data);
		
		// 增加车销库存		
		
		
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}

	/***********************************/

	// 终端退货订单列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function returnListAction()
	{
		// 配送人员ID和终端店ID
		$userId = intval($_REQUEST['userId']);
		$shopId = intval($_REQUEST['shopId']);
		
		// 查询需要配送的预单
		$where['ao.admin_id'] = $userId;
		$where['ao.shop_id'] = $shopId;
		$where['ao.order_type'] = 2; // 退货单
		$where['ao.order_state'] = 2; // 已派送

		// 查询待配送的退货单
		$results = M('admin_order')->alias('ao')->field('r.*, ao.order_state')
		->join('__PRESALE_RETURN__ as r on ao.order_id = r.return_id')
		->where($where)->order('r.return_id asc')->select();
		
		// 整理终端退货订单列表
		$returnList = array();
		foreach($results as $item)
		{
			$temp = array();
			$temp['order_id'] = $item['return_id'];
			$temp['order_code'] = $item['return_code'];
			$temp['cust_name'] = $item['cust_name'];
			$temp['order_total'] = $item['return_real_money'];
			$temp['order_way'] = $item['order_way'];
			$temp['create_time'] = date('Y-m-d H:i', $item['add_time']);
			$temp['order_state'] = $item['order_state'];
			$returnList[] = $temp;
		}

		// 返回
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $returnList));
	}
	
	// 终端退货详细
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function returnDetailAction()
	{
		// 配送人员ID和退货单ID
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

	// 提交终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function returnAction()
	{
		// 用户提交数据
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay', 0);			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goodList	= $_REQUEST["goodsList"];	// 商品数据
		
		// 经销商ID和退货ID
		$org_parent_id = I('orgId');
		$orderId = I('orderId');

		// 订单实收金额
		//$order_real_money = I('money');

		// 商品信息
		$goods_list = json_decode($goodList,true);
		// $goodList 商品信息
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['unit_name']				// 单位名称

		// 采单员所在仓库
		$depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');
		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

		// 添加订单
		$order_info = array();
		$order_info['return_code'] = create_uniqid_code('CR', $staff_id);
		$order_info['repertory_id'] = intval($depot_id);
		$order_info['org_parent_id'] = $org_parent_id; // 经销商
		$order_info['staff_id'] = $staff_id;
		$order_info['cust_id'] = $cust_id;
		$order_info['cust_name'] = $shopInfo['cust_name'];
		$order_info['cust_contact'] = $shopInfo['contact'];
		$order_info['cust_address'] = $shopInfo['address'];
		$order_info['cust_tel'] = $shopInfo['telephone'];
		//$order_info['order_real_money'] = $order_real_money; // 订单实收金额
		$order_info['order_remark'] = $remark;
		$order_info['order_way'] = $order_way;
		//$order_info['order_id'] = $orderId; // 预单ID
		$flag = D('DeliverOrder')->addReturnOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);

		// 增加车存
		

		// 设置订单已完成
		$where = array();
		$where['admin_id'] = $staff_id;
		$where['order_type'] = 2;
		$where['order_id'] = $orderId;
		M("admin_order")->where($where)->setField('order_state', 3);

		// 返回
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '添加成功', 'id'=>$flag));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '添加失败'));
		}
	}
	
	// 取消终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function cancelReturnAction()
	{
		// 订单ID
		$order_id = intval($_REQUEST['orderId']);
		$staff_id = intval($_REQUEST['userId']);
	
		// 是否今天订单
		$beginTime = date('Y-m-d');
		$beginTime = strtotime($beginTime." 00:00:00");
		$endTime = $beginTime + 60*60*24;
		
		// 搜索条件
		$where['return_id'] = $order_id;
		$where['staff_id'] = $staff_id;
		$where['create_time'] = array(array('gt', $beginTime), array('lt', $endTime));
		$where['is_cancel'] = 0; // 未取消的订单
		$orderInfo = M('car_return')->where($where)->field('return_id, return_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('car_return')->where($where)->save($data);
		
		// 减少车存		
		
		
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}
	
	/***********************************/	
	
	// 调换货订单列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function changeListAction()
	{
		// 配送人员ID和终端店ID
		$userId = intval($_REQUEST['userId']);
		$shopId = intval($_REQUEST['shopId']);
		
		// 查询需要配送的预单
		$where['ao.admin_id'] = $userId;
		$where['ao.shop_id'] = $shopId;
		$where['ao.order_type'] = 3; // 调换货单
		$where['ao.order_state'] = 2; // 已派送
		
		// 查询待配送的退货单
		$results = M('admin_order')->alias('ao')->field('r.*, ao.order_state')
		->join('__PRESALE_CHANGE__ as r on ao.order_id = r.change_id')
		->where($where)->order('r.change_id asc')->select();
		
		// 整理终端退货订单列表
		$returnList = array();
		foreach($results as $item)
		{
			$temp = array();
			$temp['order_id'] = $item['change_id'];
			$temp['order_code'] = $item['change_code'];
			$temp['cust_name'] = $item['cust_name'];
			$temp['order_total'] = $item['order_total_money'];
			$temp['order_way'] = $item['order_way'];
			$temp['create_time'] = date('Y-m-d H:i', $item['add_time']);
			$temp['order_state'] = $item['order_state'];
			$returnList[] = $temp;
		}

		// 返回
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $returnList));
	}
	
	// 调换货详细
	public function changeDetailAction()
	{
		// 配送人员ID和退货单ID
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

	// 提交调换货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function changeAction()
	{
		// 提交信息
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay');			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goods_in	= $_REQUEST["goods_in"];	// 调回商品数据
		$goods_out	= $_REQUEST["goods_out"];	// 调出商品数据

		// 经销商ID和调换货ID
		$org_parent_id = I('orgId');
		$orderId = I('orderId');
		
		// 商品信息
		$goods_in = json_decode($goods_in,true);
		$goods_out = json_decode($goods_out,true);
		//$temp['cv_id']			// 货品ID
		//$temp['goods_id']			// 商品ID
		//$temp['goods_name']		// 商品名称
		//$temp['goods_spec']		// 商品规格
		//$temp['singleprice']		// 销售价格
		//$temp['number']			// 销售数量
		//$temp['unit_name']		// 数量单位

		// 采单员所在仓库
		$depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');
		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();
		
		// 添加调换货单
		$order_info = array();
		$order_info['change_code'] = create_uniqid_code('CC', $staff_id);
		$order_info['repertory_id'] = intval($depot_id);
		$order_info['org_parent_id'] = $org_parent_id;
		$order_info['staff_id'] = $staff_id;
		$order_info['cust_id'] = $cust_id;
		$order_info['cust_name'] = $shopInfo['cust_name'];
		$order_info['cust_contact'] = $shopInfo['contact'];
		$order_info['cust_address'] = $shopInfo['address'];
		$order_info['cust_tel'] = $shopInfo['telephone'];
		$order_info['order_remark'] = $remark;
		$order_info['order_way'] = $order_way;
		$flag = D('DeliverOrder')->addChangeOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_in, $goods_out);

		// 设置订单已完成
		$where = array();
		$where['admin_id'] = $staff_id;
		$where['order_type'] = 3;
		$where['order_id'] = $orderId;		
		M("admin_order")->where($where)->setField('order_state', 3);
		
		

		// 返回
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '调换货成功', 'id'=>$flag));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '调换货失败'));
		}
	}
	
	// 取消调换货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function cancelChangeAction()
	{
		// 订单ID
		$order_id = intval($_REQUEST['orderId']);
		$staff_id = intval($_REQUEST['userId']);
	
		// 是否今天订单
		$beginTime = date('Y-m-d');
		$beginTime = strtotime($beginTime." 00:00:00");
		$endTime = $beginTime + 60*60*24;
		
		// 搜索条件
		$where['change_id'] = $order_id;
		$where['staff_id'] = $staff_id;
		$where['create_time'] = array(array('gt', $beginTime), array('lt', $endTime));
		$where['is_cancel'] = 0; // 未取消的订单
		$orderInfo = M('car_change')->where($where)->field('change_id, change_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('car_change')->where($where)->save($data);
		
		// 减少车存		
		
		
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}

	// 返回车销单详细, 打印使用
	public function orderDetail2Action()
	{
		// 订单ID
		$order_id = I('orderId');
		
		// 订单信息
		$result = M('car_orders')->where("order_id = $order_id")->find();
		$orderInfo['order_id'] = $result['order_id'];
		$orderInfo['order_code'] = $result['order_code'];
		$orderInfo['order_total_money'] = $result['order_total_money'];
		$orderInfo['order_real_money'] = $result['order_real_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);
		$orderInfo['cust_name'] = $result['cust_name'];

		// 订单商品信息
		$orderGoods = array();
		$results = M('car_orders_goods')->where("order_id = $order_id")->select();
		foreach($results as $val)
		{
			$temp = array();
			$temp['goods_name'] = $val['good_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['number'] = $val['number'];
			$temp['goods_unit'] = $val['unit_name'];
			$temp['singleprice'] = $val['singleprice'];
			$orderGoods[] = $temp;
		}
		
		// 返回
		if(!empty($orderInfo) && !empty($orderInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>array('orderInfo'=>$orderInfo,'orderGoods'=>$orderGoods)));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}
	
	// 返回终端退货详细, 打印使用
	public function returnDetail2Action()
	{
		// 订单ID
		$order_id = I('orderId');
		
		// 订单信息
		$result = M('car_return')->where("return_id = $order_id")->find();
		$orderInfo['order_id'] = $result['return_id'];
		$orderInfo['order_code'] = $result['return_code'];
		$orderInfo['order_total_money'] = $result['total_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);
		$orderInfo['cust_name'] = $result['cust_name'];
		
		// 订单商品信息
		$orderGoods = array();
		$results = M('car_return_goods')->where("return_id = $order_id")->select();
		foreach($results as $val)
		{
			$temp = array();
			$temp['goods_name'] = $val['goods_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['number'] = $val['goods_num'];
			$temp['goods_unit'] = $val['goods_unit'];
			$temp['singleprice'] = $val['goods_money'];
			$orderGoods[] = $temp;
		}

		// 返回
		if(!empty($orderInfo) && !empty($orderInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>array('orderInfo'=>$orderInfo,'orderGoods'=>$orderGoods)));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}
	
	// 返回调换货详细, 打印使用
	public function changeDetail2Action()
	{
		// 订单ID
		$order_id = I('orderId');
		
		// 订单信息
		$result = M('car_change')->where("change_id = $order_id")->find();
		$orderInfo['order_id'] = $result['change_id'];
		$orderInfo['order_code'] = $result['change_code'];
		$orderInfo['order_total_money'] = $result['total_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);
		$orderInfo['cust_name'] = $result['cust_name'];
		
		// 订单商品信息
		$goodsin = array();
		$goodsout = array();
		$results = M('car_change_goods')->where("change_id = $order_id")->select();
		foreach($results as $val)
		{
			$is_change_in = $val['is_change_in'];
		
			$temp = array();
			$temp['goods_name'] = $val['goods_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['number'] = $val['number'];
			$temp['goods_unit'] = $val['unit_name'];
			$temp['singleprice'] = $val['singleprice'];

			if($is_change_in == 1)
			{
				$goodsin[] = $temp;
			}
			else
			{
				$goodsout[] = $temp;
			}			
		}

		// 返回
		if(!empty($orderInfo) && !empty($orderInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>array('orderInfo'=>$orderInfo,'goodsin'=>$goodsin,'goodsout'=>$goodsout)));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}

	// 返回经销商信息, 打印使用
	public function orgAction()
	{
		// 平台信息
		$orgInfo['org_name'] = '农乐汇';
		$orgInfo['telephone'] = '400-0311-995';
		$orgInfo['message'] = '农乐汇B2B商城：b2b.nlh360.com';
		
		// 业务员名称和手机号
		$staff_id = I('userId');
		$staff_info = M('admin_user')->field('true_name, mobile')->where('admin_id = ' . $staff_id)->find();
		$orgInfo['true_name'] = $staff_info['true_name'];
		$orgInfo['mobile'] = $staff_info['mobile'];
		
		// 返回
		if(!empty($orgInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>$orgInfo));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}

	// 小票拍摄
	public function ticketAction()
	{
		// 订单ID 
		$orderId = intval($_REQUEST['orderId']);
		
		// 上传图片
		$upload = new \Think\Upload();
		$upload->maxSize = 3000000;
		$upload->exts = array();
		$upload->savePath = 'ticket/';
		$upload->rootPath = './Public/Uploads/';
		$info = $upload->upload();
		if(!$info) { echo json_encode(array('error' => '1', 'msg' => '图片上传失败')); exit; }
		
		// 保存图片
		$data["order_ticket"] = $info['picture']['savepath'] . $info['picture']['savename'];
		$flag = M('car_orders')->where("order_id = $orderId")->save($data);
	
		// 返回
		if(!empty($flag))
		{
			echo json_encode(array('error' => '-1', 'msg' => '图片上传成功'));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '图片上传失败'));
		}
	}

}

/*************************** end ************************************/