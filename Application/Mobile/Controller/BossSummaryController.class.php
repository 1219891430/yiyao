<?php

/*******************************************************************
 ** 文件名称: BossSummaryController.class.php
 ** 功能描述: 经销商老板端数据汇总接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossSummaryController extends Controller {

	// 首页轮播销售明细：业务员销货列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function salesmanSalesListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		$shopId = I('shopId');
        $time = I('time');

		// 今日区间
		$start_time = strtotime(date('Y-m-d') . ' 00:00:00');
		$end_time 	= $start_time + 86400;

        //按月查询
        if($time=='month'){
            $Ym = date('Y-m',time());
            $start_time = strtotime( $Ym . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }
        elseif( strlen($time)==7 ){
            $start_time = strtotime( $time . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }

		// 查询业务员下单情况
		$where = array();
		$where['o.is_cancel'] = 0;
		$where['o.org_parent_id'] = $orgData;
        if(!empty($shopId)){
            $where['o.cust_id'] = $shopId;
        }
		$where['o.create_time'] = array(array('gt',$start_time),array('lt',$end_time));
		$salesData = M("carsale_orders")->alias('o')->field("count(o.order_id) as ordernumber, o.staff_id, s.staff_name, sum(o.order_total_money) as allmoney")
		->join('__ORG_STAFF__ as s on o.staff_id = s.staff_id')
		->where($where)->group("o.staff_id")->order('o.staff_id asc')->select();
		
		// 返回
		if(empty($salesData))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $salesData));
		}
	}
	
	// 首页轮播收益明细：产品出货列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function goodsListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		$shopId = I('shopId');
        $time = I('time');

		// 今日区间
		$start_time = strtotime(date('Y-m-d') . ' 00:00:00');
        $end_time 	= $start_time + 86400;

        //按月查询
        if($time=='month'){
            $Ym = date('Y-m',time());
            $start_time = strtotime( $Ym . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }
        elseif( strlen($time)==7 ){
            $start_time = strtotime( $time . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }


		// 查询商品信息
		$where = array();
		$where['o.is_cancel'] = 0;
		$where['o.org_parent_id'] = $orgData;
		$where["gc.org_parent_id"]=$orgData;
        if(!empty($shopId)){
            $where['o.cust_id'] = $shopId;
        }
		$where['o.create_time'] = array(array('gt',$start_time),array('lt',$end_time));

		$goodsData = M("carsale_orders")->alias("o")->field("og.cv_id, og.good_name, og.goods_spec, sum(og.number) as numTotal, sum(og.singleprice * og.number) as saleTotal, sum(gc.goods_jin_price * og.number) as jinTotal")
		->join("__CARSALE_ORDERS_GOODS__ as og on o.order_id = og.order_id")
		->join("__ORG_GOODS_CONVERT__ as gc on gc.cv_id = og.cv_id")
		->group("og.cv_id")->where($where)->select();
		
		// 返回
		if(empty($goodsData))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $goodsData));
		}
	}

	// 首页轮播赊欠详细列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function sheQianDetailsListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		$time = I('time');

		// 今日区间
		$start_time = strtotime(date('Y-m-d') . ' 00:00:00');
		$end_time 	= $start_time + 86400;

        //按月查询
        if($time=='month'){
            $Ym = date('Y-m',time());
            $start_time = strtotime( $Ym . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }
        elseif( strlen($time)==7 ){
            $start_time = strtotime( $time . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }

		// 查询赊欠订单
		$where['is_cancel'] = 0;
		$where['is_full_pay']= 0 ;
		$where['org_parent_id'] = $orgData;
		$where['create_time'] = array(array('gt',$start_time),array('lt',$end_time));
		$orderList = M('carsale_orders')->alias('o')
            ->field('o.order_id, o.cust_id, o.cust_name, o.order_total_money, o.order_real_money, sum(q.price) as qingqian')
		    ->join('left join __CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
            ->where($where)
		    ->group('o.order_id')
            ->order('o.order_id desc')
            ->select();


		// 按照终端店分类
		$shopQianList = array();
		foreach($orderList as $val)
		{
			$cust_id = intval($val['cust_id']);
			if(empty($shopQianList[$cust_id]))
			{
				$temp = array();
				$temp['cust_id'] = $val['cust_id'];
				$temp['cust_name'] = $val['cust_name'];
				$temp['order_total_money'] = $val['order_total_money'];
				$temp['order_real_money'] = $val['order_real_money'];
				$temp['qingqian'] = floatval($val['qingqian']);
				$shopQianList[$cust_id] = $temp;
			}
			else
			{
				$shopQianList[$cust_id]['order_total_money'] += $val['order_total_money'];
				$shopQianList[$cust_id]['order_real_money'] += $val['order_real_money'];
				$shopQianList[$cust_id]['qingqian'] += floatval($val['qingqian']);
			}
		}
		
		// 返回
		$data = array();
		foreach($shopQianList as $v){$data[] = $v;}
		if(empty($data))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $data));
		}
	}
	
	// 首页轮播退货订单列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function tuiHuoListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		$time = I('time');
        $shopId = I('shopId');

		// 今日区间
		$start_time = strtotime(date('Y-m-d') . ' 00:00:00');
		$end_time 	= $start_time + 86400;

        //按月查询
        if($time=='month'){
            $Ym = date('Y-m',time());
            $start_time = strtotime( $Ym . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }
        elseif( strlen($time)==7 ){
            $start_time = strtotime( $time . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }


		// 查询退货订单列表
		$where['is_cancel'] = 0;
		$where["cr.org_parent_id"] = $orgData;
        if(!empty($shopId)){
            $where['cust_id'] = $shopId;
        }
		$where["create_time"] = array(array('gt',$start_time),array('lt',$end_time));
		$res=M("carsales_return")->alias('cr')->field("return_id,return_code,cust_id, cust_name, create_time,real_money as return_total_money,os.staff_name")
            ->join('__ORG_STAFF__ os on cr.staff_id=os.staff_id')
            ->where($where)
            ->select();


		foreach ($res as $k=>$v){ $res[$k]["create_time"]=date("Y-m-d",$v["create_time"]); }
		
		// 返回
		if(empty($res))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $res));
		}
	}

	// 退货商品列表
	// 创建人员: richie
	// 创建日期: 2016-08-26
	public function tuiHuoGoodsListAction()
	{
		// 退货ID
		$return_id = I("return_id");

		// 查询退货商品
		$where["a.return_id"]=$return_id;
		$goodsData = M("carsales_return_goods a")->field("a.*, b.cust_name")
		->join("__CARSALES_RETURN__ as b on b.return_id = a.return_id")
		->where($where)->select();
		
		// 返回
		if(empty($goodsData))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $goodsData));
		}				
	}

}

/*************************** end ************************************/