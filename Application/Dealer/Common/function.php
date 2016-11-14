<?php

/******************************************************************
 ** 文件名称: function.php
 ** 功能描述: 经销商基础公共函数库
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

// 经销商所有角色
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDealerRole($roleID = 0)
{
	$roleList = array(1=>'老板',2=>'内勤',3=>'业务员');
	if(intval($roleID) > 0)
	{
		return $roleList[$roleID];
	}
	else
	{
		return $roleList;
	}
}

// 查询经销商业务员
// 创建人员: wangbo
// 创建日期: 2016-08-11
function queryOrgStaff($orgID = 0, $roleID = 0)
{
    $where = array();
    if(intval($orgID) > 0){ $where['org_parent_id'] = $orgID; }
    if(intval($roleID) > 0){ $where['role_id'] = $roleID; }
    return M('org_staff')->where($where)->order('staff_id desc')->select();
}

function queryBrand($depotID =0,$close=0){
    $where = array();
    if(intval($depotID) > 0){ $where['repertory_id'] = $depotID; }
    $where['db.is_close'] = $close;

    return M('goods_brand')->alias('gb')
        ->join("zdb_depot_brand db on gb.brand_id=db.brand_id")
        ->where($where)->select();
}

function QueryBrandByOrgId($org_parent_id){
	$brand = M("goods_brand")->alias("gb")
            ->join("__GOODS_INFO__ as gi on gb.brand_id=gi.brand_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->where(array("org_parent_id"=>$org_parent_id))
            ->field("DISTINCT gb.brand_id,gb.brand_name,gb.remark,brand_logo")
            ->select();
	return $brand;
}

function QueryClassByOrgId($org_parent_id){
	$class = M("goods_class")->alias("gc")
            ->join("__GOODS_INFO__ as gi on gc.class_id=gi.class_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->where(array("org_parent_id"=>$org_parent_id))
            ->field("DISTINCT gc.class_id,gc.class_name,remark")
            ->select();
	return $class;
}

function queryGoodsByOrgId($org_parent_id){
	$goods = M("depot_stock")->alias("ds")
	        ->field("distinct gi.goods_id,gi.goods_name, gi.goods_spec")
            ->join("__GOODS_INFO__ as gi on ds.goods_id=gi.goods_id")
            ->where("org_parent_id=$org_parent_id")->select();
	return $goods;
}

/*************************** end **********************************/
function getCarSalesWay($type=0,$is_arr=true){
    $aType=array(1=>"预付结算",2=>"货到付款",3=>"月度结算",4=>"账期结算");
    if($type==0&&$is_arr)
        return $aType;
    else
        return $aType[$type];
}
//车销订单状态，1:未结款、2:部分结款、3:已结请  0&&true返回数组，反之返回数组数值
function getCarSalesStatus($type=0,$is_arr=true){
    //,4=>"未结款",5=>"部分结款",6=>"已结请",7=>"放弃"=====>，4:未结款、5:部分结款、6:已结请、7:放弃
    $aType=array(1=>"未结款",2=>"部分结款",3=>"已结请");
    if($type==0&&$is_arr)
        return $aType;
    else
        return $aType[$type];
}
function getCarportReturnStatus($type=0,$is_arr=true){
    $aType=array(1=>"已提交",2=>"已审核",3=>"已入库");
    if($type==0&&$is_arr)
        return $aType;
    else
        return $aType[$type];
}
function getCarsaleApplyStatus($status){
	$aStatus=array(1=>"已提交",2=>"已审核",3=>"已出库");
	if($status){
		return $aStatus[$status];
	}else{
		return $aStatus;
	}
}

// 获取一段时间内车销订单
function getCarsaleOrderNumByCreatetime($org_id, $start_time, $end_time) {
    if ($start_time >= $end_time) {
        return false;
    }
    $where['org_parent_id'] = $org_id;
    $where['create_time'] = array("between",array($start_time,$end_time));
    $order_num = M("carsale_orders")->where($where)->count();
    return $order_num;
}

// 获取一段时间内预单
function getCarOrderNumByCreatetime($org_id, $start_time, $end_time) {
    if ($start_time >= $end_time) {
        return false;
    }
    $where['org_parent_id'] = $org_id;
    $where['create_time'] = array("between", array($start_time,$end_time));
    $order_num = M("car_orders")->where($where)->count();

    return $order_num;
}
