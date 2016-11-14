<?php

/*******************************************************************
 ** 文件名称: DeliverSummaryModel.class.php
 ** 功能描述: 平台车销配送汇总操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DeliverSummaryModel extends Model {

	// 数据主表: 配送车销单表
	protected $tableName = 'car_orders';
	
	// 根据业务员进行汇总
	public function summaryByStaff($staffID, $startTime, $endTime)
	{
		return true;
	}
	
	// 根据经销商进行汇总
	public function summaryByDealer($dealerID, $startTime, $endTime)
	{
		return true;
	}

}

/****************************** end *******************************/