<?php

/*******************************************************************
 ** 文件名称: DeliverShopController.class.php
 ** 功能描述: 平台采单终端店接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DeliverShopController extends Controller {

	// 终端店列表, 根据预单分配情况, 查询预单对应的店铺列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 配送人员ID
		$staff_id = I("userId", 0);

		// 根据预单分配情况, 查询配送终端店列表
		$shop = M('admin_order')->alias('o')->field('c.*')
		->join('__CUSTOMER_INFO__ as c on o.shop_id = c.cust_id')
		->where("admin_id = $staff_id")->group('o.shop_id')->order('c.cust_id asc')->select();
		
		// 店铺为空
		if(empty($shop)){ echo json_encode(array('error' => '1', 'msg' => "暂无店铺")); exit; }
		
		// 格式化店铺列表
		$shopList = array();
		for($i=0; $i<count($shop); $i++)
		{
			$shopList[$i]['id'] = $shop[$i]["cust_id"];
			$shopList[$i]['name'] = $shop[$i]["cust_name"];
			$shopList[$i]['boss'] = $shop[$i]["contact"];
			$shopList[$i]['tel'] = $shop[$i]["telephone"];
			$shopList[$i]['province'] = $shop[$i]["province"];
			$shopList[$i]['city'] = $shop[$i]["city"];
			$shopList[$i]['district'] = $shop[$i]["district"];
			$shopList[$i]['address'] = $shop[$i]["address"];
			$shopList[$i]['companyId'] = 0;
			$shopList[$i]['longitude'] = empty($shop[$i]["longitude"]) ? "0" : $shop[$i]["longitude"];
			$shopList[$i]['latitude'] = empty($shop[$i]["dimension"]) ? "0" : $shop[$i]["dimension"];
			$link = DOMAIN . "Public/Uploads/" . $shop[$i]['head_pic'];
			$shopList[$i]['picture'] = empty($shop[$i]["head_pic"]) ? "" : $link;
		}
		unset($shop);
		
		// 返回
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $shopList));
    }
	
	// 终端店详细, 根据预单情况, 显示预单数量和处理情况
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function detailAction()
	{
		// 配送人员ID和终端店ID
		$userId = intval($_REQUEST['userId']);
		$shopId = intval($_REQUEST['shopId']);

		// 上次交易
		$where = array();
		$where["cust_id"] = $shopId;
		$where['staff_id'] = $userId;
		$where['is_cancel'] = 0;
		$orderInfo = M("car_orders")->field('create_time, order_total_money')->where($where)->order("create_time desc")->find();

		// 上次拜访时间
        $data['lasttime'] = '';
        if($orderInfo['add_time']>0) {
            $data['lasttime'] = date('Y-m-d H:i', $orderInfo['create_time']);
        }
		
		// 上次销售
		$data['lastmoney'] = $orderInfo['order_total_money'];
		if(empty($data['lastmoney'])){ $data['lastmoney'] = 0; }
		
		// 上月销售
		$begintime= strtotime(date('Y-m-01', strtotime('-1 month')));
		$endtime =  strtotime(date('Y-m-t', strtotime('-1 month')));
        $where['create_time'] = array(array("gt", $begintime),array("lt", $endtime));
		$data['lastMonthTotal'] = M('car_orders')->where($where)->sum('order_total_money');
		if(empty($data['lastMonthTotal'])){ $data['lastMonthTotal'] = 0; }
		
		// 店铺欠款
		$qiankMoney = 0;
		$where = array();
		$where['o.staff_id'] = $userId;
		$where['o.cust_id'] = $shopId;
		$where['o.is_full_pay'] = 0;
		$where['o.is_cancel'] = 0;
		$results = M('car_orders')->alias('o')->field('o.order_total_money, o.order_real_money, sum(q.price) as qing_total_money')
		->join('left join __CAR_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
		->where($where)->group('o.order_id')->order('o.order_id asc')->select();
		
		// 循环欠款订单，计算欠款
		foreach($results as $item)
		{
			$qiankMoney += ($item['order_total_money'] - $item['order_real_money'] - $item['qing_total_money']);
		}
		$data['qiankMoney'] = $qiankMoney;
		
		// 预单数量
		$where = array();
		$where['admin_id'] = $userId;
		$where['shop_id'] = $shopId;
		$where['order_state'] = array('lt', 3);
		$where['order_type'] = 1;
		$data['salesNumber'] = M('admin_order')->where($where)->count();
		
		// 退货单数量
		$where['order_type'] = 2;
		$data['returnNumber'] = M('admin_order')->where($where)->count();
		
		// 调换货单数量
		$where['order_type'] = 3;
		$data['changeNumber'] = M('admin_order')->where($where)->count();
		
		// 返回
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
	}

}

/*************************** end ************************************/