<?php

/*******************************************************************
 ** 文件名称: BossHomeController.class.php
 ** 功能描述: 经销商老板端首页接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossHomeController extends Controller {

	// 老板端首页销售情况
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function todaysellsAction()
	{
		// 经销商ID
		$pid = I("companyId");

		// 今日区间
		$start_time1 = date("Y-m-d", strtotime(date("Y-m-d")));
		$end_time1 = date('Y-m-d', strtotime('+1 day'));
		$start_time1 = strtotime($start_time1);
		$end_time1 = strtotime($end_time1);
		
		// 昨日区间
		$start_time2 = date('Y-m-d', strtotime('-1 day'));
		$end_time2 = date("Y-m-d", strtotime(date("Y-m-d")));
		$start_time2 = strtotime($start_time2);
		$end_time2 = strtotime($end_time2);
		
		// 本月区间
		$start_m = date( 'Y-m-01', strtotime(date("Y-m-d")));
		$end_m = date( 'Y-m-d', strtotime("$start_m +1 month -1 day"));
		$start_m = strtotime($start_m);
		$end_m = strtotime($end_m);
				
		// 今日收益：(销售价-进货价)*销售数量
		$where = array();
		$where['o.org_parent_id'] = $pid;
		$where['oc.org_parent_id'] = $pid;
		$where['o.create_time'] = array(array('gt', $start_time1),array('lt', $end_time1));
		$where['o.is_cancel'] = 0;
		$where['og.cuxiao'] = 0; // 去掉促销品
		$dataShouT = M('carsale_orders')->alias('o')
		->join('__CARSALE_ORDERS_GOODS__ as og on o.order_id = og.order_id')
		->join('__ORG_GOODS_CONVERT__ as oc on og.cv_id = oc.cv_id')
		->where($where)->sum('(og.singleprice - oc.goods_jin_price) * og.number');
		if(empty($dataShouT)){ $dataShouT = 0; }
		$dataShouT = number_format($dataShouT,2,".","");

		// 昨日收益
		$where['o.create_time'] = array(array('gt', $start_time2),array('lt', $end_time2));
		$dataShouY = M('carsale_orders')->alias('o')
		->join('__CARSALE_ORDERS_GOODS__ as og on o.order_id = og.order_id')
		->join('__ORG_GOODS_CONVERT__ as oc on og.cv_id = oc.cv_id')
		->where($where)->sum('(og.singleprice - oc.goods_jin_price) * og.number');
		if(empty($dataShouY)){ $dataShouY = 0; }
		$dataShouY = number_format($dataShouY,2,".","");
		
		// 本月收益
		$where['o.create_time'] = array(array('gt', $start_m),array('lt', $end_m));
		$dataShouM = M('carsale_orders')->alias('o')
		->join('__CARSALE_ORDERS_GOODS__ as og on o.order_id = og.order_id')
		->join('__ORG_GOODS_CONVERT__ as oc on og.cv_id = oc.cv_id')
		->where($where)->sum('(og.singleprice - oc.goods_jin_price) * og.number');
		if(empty($dataShouM)){ $dataShouM = 0; }
		$dataShouM = number_format($dataShouM,2,".","");
		
		// 今日销售
		$where = array();
		$where['org_parent_id'] = $pid;
		$where['create_time'] = array(array('gt', $start_time1),array('lt', $end_time1));
		$where['is_cancel'] = 0;
		$dataSellT = M('carsale_orders')->where($where)->sum('order_total_money');
		if(empty($dataSellT)) { $dataSellT = 0; }
		$dataSellT=number_format($dataSellT,2,".","");
		
		// 昨日销售
		$where['create_time'] = array(array('gt', $start_time2),array('lt', $end_time2));
		$dataSellY = M('carsale_orders')->where($where)->sum('order_total_money');
		if(empty($dataSellY)) { $dataSellY = 0; }
		$dataSellY = number_format($dataSellY,2,".","");

		// 本月销售
		$where['create_time'] = array(array('gt', $start_m),array('lt', $end_m));
		$dataSellM = M('carsale_orders')->where($where)->sum('order_total_money');
		if(empty($dataSellM)) { $dataSellM = 0; }
		$dataSellM=number_format($dataSellM,2,".","");
		
		// 今日欠款
		$where = array();
		$where['o.is_cancel']= 0;
		$where['o.is_full_pay']= 0;
		$where['o.org_parent_id']= $pid;
		$where['o.create_time']= array('between', array($start_time1, $end_time1));
		$result= M('carsale_orders')->alias('o')
            ->field('o.order_total_money, o.order_real_money, sum(q.price) as qingqian')
		    ->join('left join __CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
		    ->group('o.order_id')
            ->where($where)
            ->select();

		$todayqiankuan = 0;
		foreach($result as $v){ $todayqiankuan += ($v['order_total_money'] - $v['order_real_money'] - $v['qingqian']); }
		
		// 昨日欠款
		$where['o.create_time']= array('between', array($start_time2, $end_time2));
		$result= M('carsale_orders')->alias('o')->field('o.order_total_money, o.order_real_money, sum(q.price) as qingqian')
		->join('__CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
		->group('o.order_id')->where($where)->select();
		$dataQianY = 0;
		foreach($result as $v){ $dataQianY += ($v['order_total_money'] - $v['order_real_money'] - $v['qingqian']); }

		// 本月欠款
		$where['o.create_time']= array('between', array($start_m, $end_m));
		$result= M('carsale_orders')->alias('o')->field('o.order_total_money, o.order_real_money, sum(q.price) as qingqian')
		->join('__CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
		->group('o.order_id')->where($where)->select();
		$dataQianM = 0;
		foreach($result as $v){ $dataQianM += ($v['order_total_money'] - $v['order_real_money'] - $v['qingqian']); }

		// 今日退货
		$where = array();
		$where['is_cancel'] = 0;
		$where["org_parent_id"] = $pid;
		$where["create_time"] = array(array("gt",$start_time1), array("lt",$end_time1));
		$dataTuiT = M("carsales_return")->where($where)->sum('total_money');
		if(empty($dataTuiT)) { $dataTuiT = 0; }
		$dataTuiT = number_format($dataTuiT,2,".","");
		
		// 昨日退货
		$where["create_time"] = array(array("gt", $start_time2), array("lt", $end_time2));
		$dataTuiY = M("carsales_return")->where($where)->sum('total_money');
		if(empty($dataTuiY)){ $dataTuiY = 0; }
		$dataTuiY = number_format($dataTuiY,2,".","");

		// 本月退货
		$where["create_time"] = array(array("gt", $start_m), array("lt", $end_m));		
		$dataTuiM = M("carsales_return")->where($where)->sum('total_money');
		if(empty($dataTuiM)) { $dataTuiM = 0; }
		$dataTuiM = number_format($dataTuiM,2,".","");

		// 返回数据
		$data["shouyi"]['tshou'] = "".$dataShouT;
		$data["shouyi"]['yshou'] = "".$dataShouY;
		$data["shouyi"]['m'] = "".$dataShouM;
		$data["xiaoshou"]['tsell'] = "".$dataSellT;
		$data["xiaoshou"]['ysell'] = "".$dataSellY;
		$data["xiaoshou"]["m"]="".$dataSellM;
		$data["qiankuan"]['tqian'] = "".$todayqiankuan;
		$data["qiankuan"]['yqian'] = "".$dataQianY;
		$data["qiankuan"]['m'] = "".$dataQianM;
		$data["tuihuo"]['ttui'] = "".$dataTuiT;
		$data["tuihuo"]['ytui'] = "".$dataTuiY;
		$data["tuihuo"]['m'] = "".$dataTuiM;
		echo json_encode(array('error' => -1, 'msg' => '成功', 'data' =>$data));
	}

	// 老板端首页交易数量
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function getNumAction()
	{
		// 经销商ID
		$org_parent_id = I("companyId");

		// 今日时间区间
		$start_time = strtotime(date("Y-m-d 00:00:00", time()));
		$end_time = strtotime(date('Y-m-d 23:59:59', time())); 
		
		// 经销商店铺总数量
		$weihuNum = M('org_customer')->where("org_parent_id = $org_parent_id")->count();
		
		// 今日维护门店的数量
		$where = array();
		$where['org_parent_id'] = $org_parent_id;
		$where['add_time'] = array(array('gt', $start_time), array('lt', $end_time));
		$weihuTNum = M('customer_weihu')->where($where)->count('distinct shop_id');
        if(empty($weihuTNum)){ $weihuTNum = 0; } else{ $weihuTNum = intval($weihuTNum); }


		// 今日成交订单数量
		$where = array();
		$where['is_cancel'] = 0;
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
		$chengjiaoNum = M('carsale_orders')->where($where)->count();
        if(empty($chengjiaoNum)){ $chengjiaoNum = 0; } else{ $chengjiaoNum = intval($chengjiaoNum); }

		// 今日促销费用
		$where = array();
		$where['o.is_cancel'] = 0;
		$where['o.org_parent_id'] = $org_parent_id;
		$where['o.create_time'] = array(array('gt', $start_time), array('lt', $end_time));
		$where['og.cuxiao'] = 1;
		$cuxiaoList = M('carsale_orders')->alias('o')->field("sum(gc.goods_jin_price * og.number) as chengben")
		->join('__CARSALE_ORDERS_GOODS__ as og on o.order_id = og.order_id')
		->join("__ORG_GOODS_CONVERT__ as gc on gc.cv_id = og.cv_id")
		->group("og.cv_id")->where($where)->select();
		$cuxiaoFee=0;
		foreach($cuxiaoList as $v){
			$cuxiaoFee+=$v["chengben"];
		}
		
		if(empty($cuxiaoFee)){ $cuxiaoFee = 0; } else { $cuxiaoFee = floatval($cuxiaoFee); }

        $cuxiaoFee = number_format($cuxiaoFee, 2);

		// 今日欠款情况
		$where = array();
		$where['is_cancel'] = 0;
		$where['is_full_pay'] = 0;
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
		$sheqianNum = M('carsale_orders')->where($where)->count('distinct cust_id');
        if(empty($sheqianNum)){ $sheqianNum = 0; } else{ $sheqianNum = intval($sheqianNum); }
		
		// 今日终端退货情况
		$where = array();
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt', $start_time),array('lt', $end_time));
		$returnOrderMoney = M('carsales_return')->where($where)->sum('total_money');
		if(empty($returnOrderMoney)){ $returnOrderMoney = 0; } else{ $returnOrderMoney=floatval($returnOrderMoney); }
		
		// 今日调换货情况
		$where = array();
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt', $start_time),array('lt', $end_time));
		$changeOrderMoney = M('carsales_change')->where($where)->sum('total_money');
		if(empty($changeOrderMoney)){ $changeOrderMoney = 0; } else { $changeOrderMoney=floatval($changeOrderMoney); }

		// 返回
		$data['weihuNum'] 		= "$weihuTNum/$weihuNum";
		$data['chengjiaoNum'] 	= $chengjiaoNum;
		$data['cuxiaoFee'] 		= $cuxiaoFee;
		$data['sheqianNum'] 	= $sheqianNum;
		$data['returnOrderMoney'] 		= $returnOrderMoney;
		$data['changeOrderMoney'] 		= $changeOrderMoney;
		echo json_encode(array('error' => -1, 'msg' => '成功', 'data' =>$data));
	}

}

/*************************** end ************************************/