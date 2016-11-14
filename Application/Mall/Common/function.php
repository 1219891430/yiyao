<?php
/**
 * Created by PhpStorm.
 * User: wangbo
 * Date: 2016/8/15
 * Time: 9:09
 */

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
    $key = 'data_brand_'.$depotID;
    if(C('GLOBAL_CACHE') == true){
        $data = S($key);
    }

    if($data == false){
        if ($depotID > 0) {
            $data = queryBrandByDepot($depotID);
        } else {
            $data = queryAllBrand();
        }
        S($key,$data, 86400);
    }
    return $data;
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
        ->where($where)->order('c.class_id asc')->select();

    return $results;
}

// 查询品类
// 创建人员: richie
// 创建日期: 2016-08-03
function queryCategory($depotID = 0)
{
    $key = 'data_category_'.$depotID;
    if(C('GLOBAL_CACHE') == true){
        $data = S($key);
    }
    if($data == false) {
        if ($depotID > 0) {
            $data = queryDepotCategory($depotID);
        } else {
            $data = queryAllCategory();
        }
    }
    return $data;
}

// 查询仓库
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepot($depotID = 0)
{
    $where = array();
    $where["repertory_close"] = 0;
    if($depotID > 0){ $where['repertory_id'] = intval($depotID); }
    return M('depot_info')->where($where)->order('repertory_id asc')->select();
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
    $key = 'data_dealer_'.$depotID;
    if(C('GLOBAL_CACHE') == true){
        $data = S($key);
    }
    if($data == false) {
        if (intval($depotID) > 0) {
            $data = queryDealerByDepot($depotID);
        } else {
            $data = queryAllDealer();
        }
    }
    return $data;
}


// 最新添加
function getNewestGoods($depot_id, $limit=1000) {
    $where['ds.depot_id'] = $depot_id;
    //$where['po.order_status'] = 3;

    /*$goods = M('depot_stock')->alias('ds')
        ->join('left join __GOODS_INFO__ as gi on gi.goods_id=ds.goods_id')
        ->join('left join __ORG_GOODS_CONVERT__ as ogc on gi.goods_id=ogc.goods_id')
        ->group('gi.goods_id')
        ->order('gi.goods_id desc')
        ->limit($limit)
        ->select();*/

    $where["ogc.unit_default"] = 1;
    $where["ogc.goods_base_price"] = array("neq", 0);
    $where["gi.is_close"] = 0;

    $goods = M('goods_info')->alias('gi')
        ->join('left join __PRESALE_ORDERS_GOODS__ as pog on gi.goods_id=pog.goods_id')
        ->join('left join __PRESALE_ORDERS__ as po on pog.order_id=po.order_id')
        ->join('left join __DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id')
        ->join('left join __ORG_GOODS_CONVERT__ as ogc on gi.goods_id=ogc.goods_id')
        ->field('gi.*, ogc.cv_id, ogc.org_parent_id, ogc.goods_unit, ogc.unit_default, ogc.goods_base_price, ogc.goods_jin_price, ogc.wholesale_num, sum(pog.number) as sale_num')
        ->where($where)
        ->group('gi.goods_id')
        ->order('gi.goods_id desc')
        ->limit($limit)
        ->select();

    foreach ($goods as &$item) {
        $item["act_price"] = $item["goods_base_price"];
        $item["is_act"] = 0;
        // 查询活动
        $where = [];
        $where["depot_id"] = $depot_id;
        $where["cv_id"] = $item["cv_id"];
        $where["is_close"] = 0;
        $where["start_time"] = array("ELT", time());
        $where["end_time"] = array("EGT", time());
        $activity = M("activity")->where($where)->select();
        if ($activity) {
            foreach ($activity as $val) {

                if ($val["act_type"] == 0) {
                    $item["act_price"] = $val["act_price"];
                    $item["is_act"] = 1;
                }
            }
        }

    }

    return $goods;
}

// 热销品类
function getHotClass($depot_id, $limit=1000) {
    $where['po.repertory_id'] = $depot_id;
    $where["gc.is_close"] = 0;
    //$where['po.order_status'] = 3;

    $classes = M('goods_class')->alias('gc')
        // 出库
        ->join('left join __GOODS_INFO__ as gi on gi.class_id=gc.class_id')
        ->join('left join __PRESALE_ORDERS_GOODS__ as pog on gi.goods_id=pog.goods_id')
        ->join('left join __PRESALE_ORDERS__ as po on pog.order_id=po.order_id')
        //->join('left join __ORG_GOODS_CONVERT__ as ogc on dog.org_parent_id=ogc.org_parent_id AND gi.goods_id=ogc.goods_id AND dog.cv_id=ogc.cv_id')
        ->field('gc.*, count(*) as class_num')
        ->where($where)
        ->group('gi.class_id')
        ->order('class_num desc')
        ->limit($limit)
        ->select();

    return $classes;
}

// 热销品牌
function getHotBrand($depot_id, $limit=1000) {
    $where['po.repertory_id'] = $depot_id;
    $where["gb.is_close"] = 0;
    //$where['do.out_status'] = 2;

    $brands = M('goods_brand')->alias('gb')
        // 出库
        ->join('left join __GOODS_INFO__ as gi on gi.brand_id=gb.brand_id')
        ->join('left join __PRESALE_ORDERS_GOODS__ as pog on gi.goods_id=pog.goods_id')
        ->join('left join __PRESALE_ORDERS__ as po on pog.order_id=po.order_id')
        //->join('left join __ORG_GOODS_CONVERT__ as ogc on dog.org_parent_id=ogc.org_parent_id AND gi.goods_id=ogc.goods_id AND dog.cv_id=ogc.cv_id')
        ->field('gb.*, count(*) as brand_num')
        ->where($where)
        ->group('gi.brand_id')
        ->order('brand_num desc')
        ->limit($limit)
        ->select();

    return $brands;
}

// 热销商品
function getHotGoods($depot_id, $limit=1000) {
    $where['po.repertory_id'] = $depot_id;
    $where['ogc.unit_default'] = 1;
    $where["ogc.goods_base_price"] = array("neq", 0);
    $where["gi.is_close"] = 0;

    $goods = M('goods_info')->alias('gi')
        // 出库
        ->join('left join __PRESALE_ORDERS_GOODS__ as pog on gi.goods_id=pog.goods_id')
        ->join('left join __PRESALE_ORDERS__ as po on pog.order_id=po.order_id')
        ->join('left join __ORG_GOODS_CONVERT__ as ogc on pog.org_parent_id=ogc.org_parent_id AND gi.goods_id=ogc.goods_id AND pog.cv_id=ogc.cv_id')
        ->field('gi.*, ogc.*, sum(pog.number) as sale_num')
        ->where($where)
        ->group('gi.goods_id')
        ->order('sale_num desc')
        ->limit($limit)
        ->select();

    foreach ($goods as &$item) {
        $item["act_price"] = $item["goods_base_price"];
        $item["is_act"] = 0;
        // 查询活动
        $where = [];
        $where["depot_id"] = $depot_id;
        $where["cv_id"] = $item["cv_id"];
        $where["is_close"] = 0;
        $where["start_time"] = array("ELT", time());
        $where["end_time"] = array("EGT", time());
        $activity = M("activity")->where($where)->select();
        if ($activity) {
            foreach ($activity as $val) {

                if ($val["act_type"] == 0) {
                    $item["act_price"] = $val["act_price"];
                    $item["is_act"] = 1;
                }
            }
        }

    }

    return $goods;
}

// 热门商家
function getHotOrg($depot_id, $limit=1000) {
    $where['po.repertory_id'] = $depot_id;
    $where["oi.is_close"] = 0;
    //$where['do.out_status'] = 2;

    $orgs = M('org_info')->alias('oi')
        // 出库
        ->join('left join __PRESALE_ORDERS_GOODS__ as pog on oi.org_id=pog.org_parent_id')
        ->join('left join __PRESALE_ORDERS__ as po on pog.order_id=po.order_id')
        ->join('left join __ORG_GOODS_CONVERT__ as ogc on oi.org_id=ogc.org_parent_id AND pog.goods_id=ogc.goods_id AND pog.cv_id=ogc.cv_id')
        ->field('oi.*, ogc.*, count(*) as apply_num')
        ->where($where)
        ->group('oi.org_id')
        ->order('apply_num desc')
        ->limit($limit)
        ->select();

    return $orgs;
}

// 返回价格信息
function getPrice($price) {

    if (empty($_SESSION['cust_id'])) {
        return "***";
    } else {
        return $price;
    }
}

// 按照单位返回数量
function getGoodsNumByUnit($num, $unit) {
    $dUnit = ['千克','斤','公斤'];

	if (empty($num)) {
        $num = 0;
	};

	$quantity = 0;

	if (in_array($unit, $dUnit)) {
        $quantity = number_format($num, 2);
	} else {
        $quantity = intval($num);
	}

	if ($quantity <= 0) {
        $quantity = 0;
	}

	return $quantity;
}


?>
