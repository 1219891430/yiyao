<?php

/*******************************************************************
 ** 文件名称: BossShopController.class.php
 ** 功能描述: 经销商老板端终端门店接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossShopController extends Controller {

	// 店铺列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
    public function shopListAction()
    {
		// 经销商ID
		$org_parent_id = I('companyId');
		
		// 查询经销商下所有商铺
		$where["org_parent_id"] = $org_parent_id;
		$shop = M("org_customer")->alias('oc')->field('c.*')
		->join('__CUSTOMER_INFO__ as c on oc.shop_id = c.cust_id')
		->where("oc.org_parent_id = $org_parent_id")->order('oc.shop_id asc')->select();
		
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
	
	// 门店维护列表
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function serviceListAction()
	{
		// 经销商ID
		$org_parent_id = I('companyId');
		
		// 今天
        $start_time = strtotime(date('Y-m-d',time()));
        $end_time = $start_time + 24*60*60;
	
		// 查询人员维护情况
		$where['org_parent_id'] = $org_parent_id;
		$where['add_time'] = array(array('gt',$start_time), array('lt',$end_time));
		$results = M('customer_weihu')->field('saleman_id, count(distinct shop_id) as num')->where($where)->group('saleman_id')->order('saleman_id asc')->select();
		$weihu_list = array();
		foreach($results as $v)
		{
			$saleman_id = intval($v['saleman_id']);
			$weihu_list[$saleman_id] = $v;
		}

		// 查询所有业务员
		$staffList = M('org_staff')->field('staff_id, staff_name')->where("org_parent_id = $org_parent_id and role_id = 3")->order("staff_id asc")->select();
		foreach($staffList as $key=>$val)
		{
			$staff_id = intval($val['staff_id']);
			
			if(empty($weihu_list[$staff_id]))
			{
				$staffList[$key]['num'] = 0;
			}
			else
			{
				$staffList[$key]['num'] = $weihu_list[$staff_id]['num'];
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

	// 获取商铺销量排行
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function salesListByshopsAction()
	{
		// 经销商ID	
		$org_parent_id = I('companyId');
		
		// 时间筛选
		$month_begin = I('start_time'); // 2016-07-01
		$month_end = I('end_time'); // 2016-07-31
		$start_time = strtotime($month_begin . " 00:00:00");
		$end_time = strtotime($month_end . " 00:00:00");
		
		// 查询销售额，按店铺分类
		$where = array();
		$where['is_cancel'] = 0;
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt',$start_time), array('lt',$end_time));
		$results = M('carsale_orders')->field('cust_id, cust_name, sum(order_total_money) as total')->where($where)->group('cust_id')->order('total desc')->select();
		
		// 返回
		if(empty($results))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $results));
		}
	}

}

/*************************** end ************************************/