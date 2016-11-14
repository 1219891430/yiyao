<?php

/******************************************************************
 ** 文件名称: function.php
 ** 功能描述: 系统平台基础公共函数库
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

// 查询所有品牌
// 创建人员: richie
// 创建日期: 2016-08-03
function queryAllBrand()
{
	return M('goods_brand')->where('is_close = 0')->order('brand_id asc')->select();
}

// 查询指定仓库下的品牌
// 创建人员: richie
// 创建日期: 2016-08-03
function queryBrandByDepot($depotID = 0)
{
	$where['b.is_close'] = 0; 
	$where['db.is_close'] = 0;
	$where['db.repertory_id'] = intval($depotID);
	$results = M('depot_brand')->alias('db')->field('b.*')
	->join('__GOODS_BRAND__ as b on db.brand_id = b.brand_id')
	->where($where)->order('b.brand_id asc')->select();
	return $results;
}

// 查询品牌
// 创建人员: richie
// 创建日期: 2016-08-03
function queryBrand($depotID = 0)
{
	if($depotID > 0)
	{
		return queryBrandByDepot($depotID);
	}
	else
	{
		return queryAllBrand();
	}
}

function queryBrandByOrg($org_id=0){
	
	$where["zdb_depot_stock.org_parent_id"]=$org_id;
	$list=M("depot_stock")->field("distinct zdb_goods_brand.brand_id,zdb_goods_brand.brand_name")
	->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_stock.goods_id")
	->join("zdb_goods_brand on zdb_goods_brand.brand_id=zdb_goods_info.brand_id")->where($where)->select();
	
	return $list;
}

// 查询所有品类
// 创建人员: richie
// 创建日期: 2016-08-03
function queryAllCategory()
{
	return M('goods_class')->where('is_close = 0')->order('class_id asc')->select();
}

// 查询指定仓库下的品类
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepotCategory($depotID = 0)
{
	$where['dc.is_close'] = 0;
	$where['c.is_close'] = 0;
	$where['dc.repertory_id'] = intval($depotID);
	$results = M('depot_class')->alias('dc')
	->join('__GOODS_CLASS__ as c on dc.class_id = c.class_id')
	->where($where)->order('class_id asc')->select();
}

// 查询品类
// 创建人员: richie
// 创建日期: 2016-08-03
function queryCategory($depotID = 0)
{
	if(intval($depotID) > 0)
	{
		return queryDepotCategory($depotID);
	}
	else
	{
		return queryAllCategory();
	}
}

// 查询仓库树
function getCatTree()
{
	$where['parent_class'] = 0;
	$list=M("goods_class")->where($where)->select();
	foreach($list as $key=>$value)
	{
		$list[$key]['class_list'] = M('goods_class')->where('parent_class = ' . intval($value['class_id']))->select();
	}
	return $list;
}

// 查询仓库
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepot($depotID = 0)
{
	$where = array();
	if($depotID > 0){ $where['repertory_id'] = intval($depotID); }
	else{ $where['repertory_parent'] = 0; }
	
	return M('depot_info')->where($where)->order('repertory_id asc')->select();
}

// 查询仓库树结构
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepotTree($depotParent = 0)
{
	$where = array();
	$where['repertory_parent'] = 0;
	if($depotParent > 0){ $where['repertory_id'] = intval($depotParent); }
	$depostList = M('depot_info')->where($where)->order('repertory_id asc')->select();
	foreach($depostList as $key=>$value)
	{
		$depostList[$key]['depot_list'] = M('depot_info')->where("repertory_parent = " . $value['repertory_id'])->order('repertory_id asc')->select();	
	}
	return $depostList;
}

// 查询所有经销商
// 创建人员: richie
// 创建日期: 2016-08-03
function queryAllDealer()
{
	return M('org_info')->order('org_id asc')->select();
}

// 查询指定仓库下经销商
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDealerByDepot($depotID = 0)
{
	$result = M('depot_org')->alias('do')->field('o.*')
	->join('__ORG_INFO__ as o on do.org_parent_id = o.org_id')
	->where('do.repertory_id = ' . intval($depotID))->order('o.org_id asc')->select();
	return $result;
}

// 查询经销商
function queryDealer($depotID = 0)
{
	if(intval($depotID) > 0)
	{
		return queryDealerByDepot($depotID);
	}
	else
	{
		return queryAllDealer();
	}
}

// 平台所有角色
// 创建人员: richie
// 创建日期: 2016-08-03
function queryAllRole($roleID = 0)
{
	$roleList = array(1=>'平台内勤',2=>'仓库库管',3=>'财务人员',4=>'采单人员',5=>'配送人员',6=>'采购人员');
	if(intval($roleID) > 0)
	{
		return $roleList[$roleID];
	}
	else
	{
		return $roleList;
	}
}

function getDeliverWay($type=0,$is_arr=true){
    $aType=array(1=>"货到付款",2=>"账期结算",3=>"月度结算");
    if($type==0&&$is_arr)
        return $aType;
    else
        return $aType[$type];
}

// 查询平台业务员
// 创建人员: richie
// 创建日期: 2016-08-03
function queryAdminStaff($depotID = 0, $roleID = 0)
{
	$where = array();
	if(intval($depotID) > 0){ $where['depot_id'] = $depotID; }
	if(intval($roleID) > 0){ $where['role_id'] = $roleID; }
	return M('admin_user')->where($where)->order('admin_id desc')->select();
}

// 检查人员手机号是否重复
// 创建人员: richie
// 创建日期: 2016-08-04
function checkMobileUnique($mobile, $userID = 0)
{
	// 手机号格式不对
	if(!preg_match("/^1[34578]{1}\d{9}$/", $mobile)){ return false; } 

	// 平台人员是否已经使用号
	$admin_where['login_account'] = $mobile;
	if(intval($userID) > 0){ $admin_where['admin_id'] = array('neq', $userID); }
	$admin_id = M('admin_user')->where($admin_where)->getField('admin_id');
	if($admin_id > 1){ return false; }

	// 经销商人员是否已经使用手机号
	$staff_where['login_user'] = $mobile;
	if(intval($userID) > 0){ $staff_where['staff_id'] = array('neq', $userID); }
	$staff_id = M('org_staff')->where($staff_where)->getField('staff_id');
	if($staff_id > 1){ return false; }
	
	// 返回成功
	return true;
}
function barcode($code) {
    $barcode = new \Common\Utils\BarCode128($code);
    $barcode->createBarCode();
}

function array_sort($arr, $keys, $type = 'desc') {
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}


// 自动报警设置
function autoWarning($goods_id, $org_id, $days) {

    $depot_id = intval($_SESSION['depot_id']);
    // 根据车销查询固定天数内销售总量
    $start = strtotime("-$days day");
    $end = strtotime("+1 day");

    $where['co.org_parent_id'] = $org_id;
    $where['co.repertory_id'] = $depot_id;
    $where['co.is_cancel'] = 0;
    $where['co.create_time'] = array('between',"$start, $end");
    $where['cog.goods_id'] = $goods_id;

    $goods = M("car_orders_goods")->alias("cog")
        ->join("left join __CAR_ORDERS__ as co on cog.order_id=co.order_id")
        ->join("left join __GOODS_PRODUCT__ as gp on cog.goods_id=gp.goods_id AND cog.cv_id=gp.cv_id")
        ->join("left join __GOODS_INFO__ as gi on cog.goods_id=gi.goods_id")
        ->where($where)
        ->select();

    $num = 0;
    foreach ($goods as $val) {

        switch ($val["goods_unit_type"]) {
            case 1:
                $num += $val["number"] * $val["goods_convert_s"];
                break;
            case 2:
                $num += $val["number"] * $val["goods_convert_m"];
                break;
            case 3:
                $num += $val["number"] * $val["goods_convert_b"];
                break;
            default:
                $num += $val["number"] * $val["goods_convert_s"];
        }
    }

    //echo M("car_orders_goods")->getLastSql();die();

    return $num;
}








/*************************** end **********************************/