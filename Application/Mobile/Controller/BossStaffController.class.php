<?php

/*******************************************************************
 ** 文件名称: BossStaffController.class.php
 ** 功能描述: 经销商老板端员工接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossStaffController extends Controller {

	// 业务员列表
	public function staffListAction()
	{
		// 查询所有业务员
		$org_parent_id = I("companyId");
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $org_parent_id and role_id = 3")->order("staff_id asc")->select();

        if ($staffList) {
            echo json_encode($staffList);
        } else {
            echo json_encode(array());
		}

	
	
		/*$where["org_parent_id"] = I("post.companyId");
		$where["role_id"] = 3;
		$where1["is_close"] = 0;*/



		/*$ids = M("BaseStaffRole")->where($where)->getField("staff_id", true);
		if ($ids) {
		$where1["staff_id"] = array("in", $ids);
		$list = M("BaseStaff")->field("staff_id id,staff_name name,mobile tel,org_parent_id companyId")->where($where1)->select();*/
		/*if ($list)
		echo json_encode($list);
		else
		echo json_encode(array());
		} else {
		echo json_encode(array());
		}*/
    }
	
	// 人员情况：在线统计
	public function worktimeAction()
	{
		// 经销商ID
		$org_parent_id = I('companyId');

		// 本月区间
		$begin_month = I('startday'); // 获取的参数格式 '2016-07-01'
		$end_month = I('endday'); // 获取的参数格式 '2016-07-01'
		$start_time = strtotime($begin_month . " 00:00:00");
		$end_time = strtotime($end_month . " 23:59:59");

		// 获取业务员在线情况
		$where = array();
		$where['org_parent_id'] = $org_parent_id;
		$where['add_time'] = array(array('gt',$start_time), array('lt',$end_time));
		$results = M('customer_weihu')->field("saleman_id, count(distinct FROM_UNIXTIME(add_time, '%Y-%m-%d')) as online, count(shop_id) as visit")->where($where)->group('saleman_id')->select();
		
		$staffWeiList = array();
		foreach($results as $v)
		{
			$saleman_id = intval($v['saleman_id']);
			$online = intval($v['online']);
			$visit = intval($v['visit']);
			$staffWeiList[$saleman_id] = array('online'=>$online, 'visit'=>$visit);
		}
		
		// 获取所有业务员
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $org_parent_id and role_id = 3")->order("staff_id asc")->select();
		foreach($staffList as $key=>$value)
		{
			$staff_id = intval($value['staff_id']);
			if(empty($staffWeiList[$staff_id]))
			{
				$staffList[$key]['online'] = 0;
				$staffList[$key]['visit'] = 0;
			}
			else
			{
				$staffList[$key]['online'] = $staffWeiList[$staff_id]['online'];
				$staffList[$key]['visit'] = $staffWeiList[$staff_id]['visit'];
				
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
	
	
	// 
	
	
	
	
}

/*************************** end ************************************/