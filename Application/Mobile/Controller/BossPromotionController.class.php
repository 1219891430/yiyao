<?php

/*******************************************************************
 ** 文件名称: BossPromotionController.class.php
 ** 功能描述: 经销商老板端促销数据接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossPromotionController extends Controller {

	// 促销费用统计
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function promotionCostAction()
	{
		// 经销商ID
		$org_parent_id = I("companyId");

		// 今日
		$start_time = date("Y-m-d",time());
		$end_time = date('Y-m-d',strtotime('+1 day'));
		$start_time = strtotime($start_time);
		$end_time = strtotime($end_time);
		

		// 查询今日促销商品
		$map = array();
		$map['od.is_cancel'] = 0;
		$map ["od.create_time"] = array (array ('gt',$start_time),array ('lt',$end_time));
		$map ['od.org_parent_id'] = $org_parent_id;
		$map ['oi.cuxiao'] = 1;
		$cuxiao = M("carsale_orders")->alias("od")->field("oi.goods_id, oi.good_name, oi.goods_spec, oi.unit_name, sum(oi.number) as shuliang, sum(gc.goods_jin_price * oi.number) as chengben")
		->join("__CARSALE_ORDERS_GOODS__ as oi on od.order_id = oi.order_id")
		->join("__ORG_GOODS_CONVERT__ as gc on gc.cv_id = oi.cv_id")
		->group("oi.cv_id")->where($map)->select();


		// 返回
		if(empty($cuxiao))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $cuxiao));
		}
	}
	
	// 
	
}

/*************************** end ************************************/