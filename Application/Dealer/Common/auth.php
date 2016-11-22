<?php

/******************************************************************
 ** 文件名称: auth.php
 ** 功能描述: 经销商PC权限文件
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

return array(

	'1' => array('name' => '基础设置', 'icon' => 'left-bg-base', 'subclass' => array(
		'11' => array('subname' => '机构设置', 'controller' => 'Org',				'action' => 'index'),
		'12' => array("subname" => '部门设置', 'controller' => 'Dep',				'action' => 'index'),
		'13' => array("subname" => '人员设置', 'controller' => 'Staff',			'action' => 'index'),
		'14' => array("subname" => '品牌管理', 'controller' => 'GoodsBrand',		'action' => 'index'),
		'15' => array("subname" => '品类管理', 'controller' => 'GoodsCategory',	'action' => 'index'),
		'16' => array("subname" => '药品管理', 'controller' => 'GoodsInfo',		'action' => 'index'),
		'17' => array("subname" => '药品价格', 'controller' => 'GoodsPrices',		'action' => 'index'),
		'18' => array("subname" => '促销活动', 'controller' => 'Promotion',		'action' => 'index')
	)),
	
	'2' => array('name' => '客户管理', 'icon' => 'left-bg-customer', 'subclass' => array(
		'21' => array("subname" => '客户类型', 'controller' => 'Customer', 'action' => 'type'),
		'22' => array("subname" => '客户管理', 'controller' => 'Customer', 'action' => 'index')
    )),
	
	'3' => array('name' => '行动管理', 'icon' => 'left-bg-action', 'subclass' => array(
	    '35' => array('subname' => '行动轨迹', 'controller' => 'Action',		'action' => 'index'),
		'31' => array('subname' => '拜访轨迹', 'controller' => 'Route',		'action' => 'index'),
		'32' => array('subname' => '人员签到', 'controller' => 'Signin',		'action' => 'index'),
		//'33' => array('subname' => '店铺陈列', 'controller' => 'Display',		'action' => 'index'),
		'34' => array('subname' => '店铺日志', 'controller' => 'Shoplog',		'action' => 'index'),
		'36' => array('subname' => '拜访周期', 'controller' => 'Cycle',		'action' => 'index'),
    )),
	
//	'4' => array('name' => '仓库管理', 'icon' => 'left-bg-depot', 'subclass' => array(
//		'41' => array("subname" => '药品入库', 'controller' => 'DepotIn',		'action' => 'index'),
//		'42' => array("subname" => '药品出库', 'controller' => 'DepotOut',	'action' => 'index'),
//		'43' => array("subname" => '实时库存', 'controller' => 'DepotStock',	'action' => 'index')
//	)),
	
	
//  '5' => array('name' => '车销管理', 'icon' => 'left-bg-deliver', 'subclass' => array(
//      '51' => array("subname" => '车存申请',	'controller' => 'CarsaleApply',		'action' => 'index'),
//      '52' => array("subname" => '车销退库',	'controller' => 'CarsaleBack',		'action' => 'index'),
//      '53' => array("subname" => '实时车存',	'controller' => 'CarsaleStock',		'action' => 'index'),
//      '54' => array("subname" => '车销明细',	'controller' => 'CarSalesOrder',	'action' => 'index'),
//      '55' => array("subname" => '终端退货',	'controller' => 'CarSalesReturn',	'action' => 'index'),
//      '56' => array("subname" => '调换货明细',	'controller' => 'CarsaleChange',	'action' => 'index'),
//      '57' => array("subname" => '车销对账',	'controller' => 'CarSalesCheck',	'action' => 'index')
//  )),
	
	'6' => array('name' => '预单管理', 'icon' => 'left-bg-order', 'subclass' => array(
        '61' => array('subname' => '预单订单',	'controller' => 'PlanOrder',		'action' => 'index'),
        '62' => array('subname' => '终端退货',	'controller' => 'PlanReturn',		'action' => 'index'),
		//'63' => array('subname' => '调换货明细',	'controller' => 'PlanChange',		'action' => 'index')
    )),
	
	'7' => array('name' => '赊款管理', 'icon' => 'left-bg-sheqian', 'subclass' => array(
		'71' => array('subname' => '赊款管理', 'controller' => 'SheQian', 'action' => 'index')
	)),
	
	'8' => array('name' => '数据统计', 'icon' => 'left-bg-report', 'subclass' => array(
		'81' => array('subname' => '药品统计',	'controller' => 'OrderReport',	'action' => 'goods'),
		'82' => array('subname' => '药店统计',	'controller' => 'OrderReport',	'action' => 'shops')
    ))

);
/*************************** end **********************************/
