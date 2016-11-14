<?php

/*******************************************************************
 ** 文件名称: BossOrderController.class.php
 ** 功能描述: 经销商老板端订单接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossOrderController extends Controller {

	// 业务员成交订单数量
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function salesmanListAction()
	{
		// 经销商ID和时间
		$org_parent_id = I("companyId");
		$start_time = date("Y-m-d",time());
		$end_time = date('Y-m-d',strtotime('+1 day'));
		$start_time = strtotime($start_time);
		$end_time = strtotime($end_time);
		
		// 今天经销商订单成交数量
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt',$start_time),array('lt',$end_time));
		$orderList = M('carsale_orders')->field('staff_id, count(order_id) as num')->where($where)->group('staff_id')->order('staff_id asc')->select();
		$orderStaffList = array();
		foreach($orderList as $v)
		{
			$staff_id = intval($v['staff_id']);
			$num = intval($v['num']);
			$orderStaffList[$staff_id] = $num;
		}
		
		// 查询所有业务员
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $org_parent_id and role_id = 3")->order("staff_id asc")->select();
		foreach($staffList as $key=>$val)
		{
			$staff_id = intval($val['staff_id']);
			
			if(empty($orderStaffList[$staff_id]))
			{
				$staffList[$key]['num'] = 0;
			}
			else
			{
				$staffList[$key]['num'] = $orderStaffList[$staff_id];
			}
		}

		// 返回
		if(empty($staffList))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $staffList));
		}	
	}

	// 赊欠订单列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function sheQiansalesmanListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		
		// 今日区间
		
		
		$start_time = strtotime(date("Y-m-d 00:00:00", time()));
		$end_time = strtotime(date('Y-m-d 23:59:59', time()));
		
		// 查询赊欠订单
		$where['is_cancel'] = 0;
		$where['is_full_pay']= 0 ;
		$where['org_parent_id'] = $orgData;
		$where['create_time'] = array(array('gt',$start_time), array('lt',$end_time));
		$results = M('carsale_orders')->field('staff_id, count(order_id) as num')->where($where)->group('staff_id')->select();
		
		$orderStaffList = array();
		foreach($results as $v)
		{
			$staff_id = $v['staff_id'];
			$num =$v['num'];
			$orderStaffList[$staff_id] = $num;
		}

		// 查询所有业务员
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $orgData and role_id = 3")->order("staff_id asc")->select();
		foreach($staffList as $key=>$val)
		{
			$staff_id = intval($val['staff_id']);
			if(empty($orderStaffList[$staff_id]))
			{
				$staffList[$key]['num'] = 0;
			}
			else
			{
				$staffList[$key]['num'] = $orderStaffList[$staff_id];
			}
		}

		// 返回
		if(empty($staffList))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $staffList));
		}
	}

	// 调换货订单列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function changeStaffListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		
		// 今日区间
		$start_time = strtotime(date("Y-m-d 00:00:00", time()));
		$end_time = strtotime(date('Y-m-d 23:59:59', time()));
		
		// 查询调换货订单
		$where['is_cancel'] = 0;
		$where['org_parent_id'] = $orgData;
		$where['create_time'] = array(array('gt',$start_time), array('lt',$end_time));
		$results = M('carsales_change')->field('staff_id, count(change_id) as num')->where($where)->group('staff_id')->select();
		$orderStaffList = array();
		foreach($results as $v)
		{
			$staff_id = $v['staff_id'];
			$num = $v['num'];
			$orderStaffList[$staff_id] = $num;
		}

		// 查询所有业务员
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $orgData and role_id = 3")->order("staff_id asc")->select();
		foreach($staffList as $key=>$val)
		{
			$staff_id = intval($val['staff_id']);
			if(empty($orderStaffList[$staff_id]))
			{
				$staffList[$key]['num'] = 0;
			}
			else
			{
				$staffList[$key]['num'] = $orderStaffList[$staff_id];
			}
		}

		// 返回
		if(empty($staffList))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $staffList));
		}
	}
	
	// 终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function returnStaffListAction()
	{
		// 经销商ID
		$orgData = I("companyId");
		
		// 今日区间
		$start_time = strtotime(date('Y-m-d') . ' 00:00:00');
		$end_time 	= $start_time + 86400;
		
		// 查询终端退货订单
		$where['is_cancel'] = 0;
		$where['org_parent_id'] = $orgData;
		$where['create_time'] = array(array('gt',$start_time), array('lt',$end_time));
		$results = M('carsales_return')->field('staff_id, count(return_id) as num')->where($where)->group('staff_id')->select();
		$orderStaffList = array();
		foreach($results as $v)
		{
			$staff_id = intval($v['staff_id']);
			$num = intval($v['num']);
			$orderStaffList[$staff_id] = $num;
		}

		// 查询所有业务员
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $orgData and role_id = 3")->order("staff_id asc")->select();
		foreach($staffList as $key=>$val)
		{
			$staff_id = intval($val['staff_id']);
			if(empty($orderStaffList[$staff_id]))
			{
				$staffList[$key]['num'] = 0;
			}
			else
			{
				$staffList[$key]['num'] = $orderStaffList[$staff_id];
			}
		}

		// 返回
		if(empty($staffList))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $staffList));
		}
	}

}

/*************************** end ************************************/