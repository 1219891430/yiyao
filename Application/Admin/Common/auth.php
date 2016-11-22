<?php

/******************************************************************
 ** 文件名称: auth.php
 ** 功能描述: 系统管理PC权限文件
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

return array(

	'1' => array('name' => '药品总库', 'icon' => 'left-bg-base', 'subclass' => array(
		'11' => array('subname' => '药品品类', 'controller' => 'GoodsCategory',	'action' => 'index'),
		'12' => array("subname" => '药品品牌', 'controller' => 'GoodsBrand',		'action' => 'index'),
		'13' => array("subname" => '药品总库', 'controller' => 'GoodsInfo',		'action' => 'index'),
        '14' => array("subname" => '药品预警', 'controller' => 'GoodsWarning',	'action' => 'warning_view'),
        //'15' => array("subname" => '菜单管理', 'controller' => 'Menu',	'action' => 'index')
	)),

	'2' => array('name' => '客户管理', 'icon' => 'left-bg-customer', 'subclass' => array(
		'21' => array("subname" => '经销商', 'controller' => 'Dealer',	'action' => 'index'),
		'22' => array("subname" => '终端店', 'controller' => 'Shops',	'action' => 'index')
    )),
	
	'3' => array('name' => '人员管理', 'icon' => 'left-bg-action', 'subclass' => array(
		'31' => array('subname' => '角色列表', 'controller' => 'Role',			'action' => 'index'),
		'32' => array('subname' => '人员列表', 'controller' => 'Staff',			'action' => 'index'),
		'33' => array('subname' => '采单店铺', 'controller' => 'CollectShop',		'action' => 'index'),
		'34' => array('subname' => '配送线路', 'controller' => 'ShippingLine',	'action' => 'index'),
    )),
	
	'4' => array('name' => '仓库设置', 'icon' => 'left-bg-depot', 'subclass' => array(
		'41' => array("subname" => '仓库列表',	'controller' => 'Depot',	'action' => 'index'),
        '42' => array("subname" => '仓库区域',	'controller' => 'Area',	'action' => 'index'),
		'43' => array("subname" => '仓库品类',	'controller' => 'Depot',	'action' => 'category'),
		'44' => array("subname" => '仓库品牌',	'controller' => 'Depot',	'action' => 'brand'),
		'45' => array("subname" => '仓库经销商',	'controller' => 'Depot',	'action' => 'dealer')
	)),

    '5' => array('name' => '药品库存', 'icon' => 'left-bg-goods', 'subclass' => array(
        '51' => array("subname" => '药品入库',	'controller' => 'DepotIn',		'action' => 'index'),
        '52' => array("subname" => '药品出库',	'controller' => 'DepotOut',		'action' => 'index'),
        '53' => array("subname" => '药品库存',	'controller' => 'DepotStock',	'action' => 'index'),
        '54' => array("subname" => '仓库日志',	'controller' => 'DepotLog',		'action' => 'index')
    )),
	
	'6' => array('name' => '预单管理', 'icon' => 'left-bg-order', 'subclass' => array(
        '61' => array('subname' => '预单销售',	'controller' => 'PresaleOrder',		'action' => 'index'),
        '62' => array('subname' => '预单退货',	'controller' => 'PresaleReturn',	'action' => 'index'),
		'63' => array('subname' => '预单调换货',	'controller' => 'PresaleChange',	'action' => 'index'),
//		'64' => array('subname' => '商城下单',	'controller' => 'PresaleMall',		'action' => 'index'),
		'65' => array('subname' => '预单汇总（类型）',	'controller' => 'PresaleSummary',	'action' => 'index'),
        '66' => array('subname' => '预单汇总（店铺）',	'controller' => 'PresaleSummary',	'action' => 'shop'),
        '67' => array('subname' => '采购单管理',	'controller' => 'PurchaseOrder',	'action' => 'index'),
    )),
	
	'7' => array('name' => '配送管理', 'icon' => 'left-bg-deliver', 'subclass' => array(
		'71' => array('subname' => '配送预单',	'controller' => 'DeliverPlan',		'action' => 'list'),
		'72' => array('subname' => '配送申请',	'controller' => 'DeliverApply',		'action' => 'index'),
		'73' => array('subname' => '配送退库',	'controller' => 'DeliverBack',		'action' => 'index'),
		'74' => array('subname' => '配送车存',	'controller' => 'DeliverStock',		'action' => 'index'),
		'75' => array('subname' => '配送车销',	'controller' => 'DeliverOrder',		'action' => 'index'),
		'76' => array('subname' => '配送退货',	'controller' => 'DeliverReturn',	'action' => 'index'),
		'77' => array('subname' => '配送调换货',	'controller' => 'DeliverChange',	'action' => 'index'),
		'78' => array('subname' => '配送对账',	'controller' => 'DeliverSummary',	'action' => 'index'),
		'79' => array('subname' => '配送汇总',	'controller' => 'DeliverOrgSummary',	'action' => 'index')
	)),
	
	'8' => array('name' => '赊款管理', 'icon' => 'left-bg-sheqian', 'subclass' => array(
		'81' => array('subname' => '赊款管理',	'controller' => 'SheQian',			'action' => 'index')
	)),

//  '9' => array('name' => '站点管理', 'icon' => 'left-bg-goods', 'subclass' => array(
//      '91' => array('subname' => '信息管理',	'controller' => 'Msg',			'action' => 'index')
//  )),
);
/*************************** end **********************************/
