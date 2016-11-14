<?php

/*******************************************************************
 ** 文件名称: CarsaleShopModel.class.php
 ** 功能描述: 经销商终端门店维护公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-27
*******************************************************************/

namespace Common\Model;
use Think\Model;

class CarsaleShopModel extends Model {

	// 业务员轨迹表
	protected $tableName = 'customer_weihu';
	
	// 添加业务员轨迹
	public function addPosition($org_parent_id, $staff_id, $shop_id, $type)
	{
		$data['saleman_id'] = $staff_id;
		$data['shop_id'] = $shop_id;
		$data['org_parent_id'] = $org_parent_id;
		$data['type'] = $type;
		$data['remark'] = $this->getRemark($type);
		$data['add_time'] = time();
		M('customer_weihu')->add($data);
		return true;
	}
	
	// 类型说明
	private function getRemark($type)
	{
		$remark = '';
		switch($type)
		{
			case 1: $remark = '陈列拍照'; break;  
			case 2: $remark = '下车销单'; break;  
			case 3: $remark = '调换货'; break;  
			case 4: $remark = '退货'; break;  
			case 5: $remark = '清欠'; break;
			case 6: $remark = '店铺日志'; break;  
			default: break;
		}
		return $remark;
	}

}

/****************************** end *******************************/