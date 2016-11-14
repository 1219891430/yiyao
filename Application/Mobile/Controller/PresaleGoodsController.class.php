<?php

/*******************************************************************
 ** 文件名称: PresaleGoodsController.class.php
 ** 功能描述: 平台采单商品接口
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class PresaleGoodsController extends Controller {

	// 品牌列表, 采单员所在仓库的所有品牌
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function brandAction()
	{
		// 采单员所在仓库
		$userId = intval($_REQUEST['userId']);
		$order_form = intval($_REQUEST['orderFrom']);
		if(empty($order_from)){
			$order_from=1;
		}
		if($order_form==2){
			$org_parent_id = M('org_staff')->alias('os') ->where('staff_id = ' . $userId)->getField('org_parent_id');
			
			$brandList = M("goods_brand")->alias("gb")
            ->join("__GOODS_INFO__ as gi on gb.brand_id=gi.brand_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->where(array("org_parent_id"=>$org_parent_id))
            ->field("DISTINCT gb.brand_id as id,gb.brand_name as name")
            ->select();
		}else{
			$depotId = M('admin_user')->where("admin_id = " . $userId)->getField('depot_id');
			// 仓库下品牌
			$brandList = M('depot_brand')->alias('d')->field('b.brand_id as id , b.brand_name as name')
			->join('__GOODS_BRAND__ as b on d.brand_id = b.brand_id')
			->where("d.repertory_id = $depotId")
                ->order('b.brand_id asc')->select();
		}
		
		
		
		
		// 返回
		if(empty($brandList))
		{
			echo json_encode(array('error' => '1', 'msg' => '暂无品牌'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $brandList));
		}
    }

	// 商品列表, 指定品牌下的商品列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function goodsAction()
	{
		// 采单员ID和品牌ID
		$userId = intval($_REQUEST['userId']);
		$brandId = intval($_REQUEST['brandId']);
		
		// 根据采单人员查询仓库信息
		$depotId = M('admin_user')->where("admin_id = $userId")->getField('depot_id');
		
		// 根据品牌查询指定仓库下的商品列表, 同一个仓库，一件商品只能隶属于一个经销商, 否则会出问题
		$goodsList = M('depot_stock')->alias('d')->field('g.*, d.org_parent_id')
		->join('__GOODS_INFO__ as g on d.goods_id = g.goods_id and g.brand_id = ' . $brandId)
		->where('d.depot_id = ' . intval($depotId))->order('g.goods_id asc')->select();

		// 格式化商品数据
		foreach($goodsList as $key => &$value)
		{
			// 大单位, 中单位转换系数
			$value["bigFactor"] = intval($value['goods_convert_m']) * intval($value['goods_convert_b']);
			$value["midFactor"] = intval($value['goods_convert_m']);
			
			// 取消别的数据
			unset($value['class_id']);
			unset($value['class_id']);
			unset($value['goods_convert_s']);
			unset($value['goods_convert_m']);
			unset($value['goods_convert_b']);
			unset($value['is_close']);
			unset($value['main_img']);
			unset($value['goods_desc']);
			
		}

		// 返回数据
        if(empty($goodsList))
		{
			echo json_encode(array('error' => '1', 'msg' => '暂无商品'));
        }
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '查询成功', 'data' => $goodsList));
        }
    }

    // 搜索商品
    public function searchAction() {
        $userId = intval($_REQUEST['userId']);
        $keyword = I("keyword");
        // 根据采单人员查询仓库信息
        $depotId = M('admin_user')->where("admin_id = $userId")->getField('depot_id');

        // 构造条件
        $where = [];
        $where['d.depot_id'] = intval($depotId);
        $where['g.goods_name'] = array("like", "%$keyword%");

        // 根据条件查询
        $goods_list = M('depot_stock')->alias('d')
            ->join('__GOODS_INFO__ as g on d.goods_id = g.goods_id')
            ->join('__GOODS_BRAND__ as gb on gb.brand_id = g.brand_id')
            ->field('g.*, gb.brand_id, gb.brand_name, d.org_parent_id')
            ->where($where)
            ->order('g.goods_id asc')
            ->select();

        $res = [];
        // 格式化商品数据
        foreach($goods_list as $key => &$value)
        {

            // 大单位, 中单位转换系数
            $value["bigFactor"] = intval($value['goods_convert_m']) * intval($value['goods_convert_b']);
            $value["midFactor"] = intval($value['goods_convert_m']);

            // 取消别的数据
            unset($value['class_id']);
            unset($value['class_id']);
            unset($value['goods_convert_s']);
            unset($value['goods_convert_m']);
            unset($value['goods_convert_b']);
            unset($value['is_close']);
            unset($value['main_img']);
            unset($value['goods_desc']);


            $res[$value['brand_id']]["brand_name"] = $value['brand_name'];
            $res[$value['brand_id']]["goods"][] = $value;
        }

        $res = array_values($res);


        // 返回数据
        if(empty($res))
        {
            echo json_encode(array('error' => '1', 'msg' => '暂无商品'));
        }
        else
        {
            echo json_encode(array('error' => '-1', 'msg' => '查询成功', 'data' => $res));
        }

    }
	
	// 商品详情
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function detailAction()
	{
		// 采单人员ID和商品ID, 以及经销商ID
		$userId = intval($_REQUEST['userId']);
		$goodsId = intval($_REQUEST['goodsId']);
		$orgId = intval($_REQUEST['orgId']);
		
		// 查询商品包装以及经销商价格
		$productListRes = M('goods_product')->alias('p')->field('p.cv_id, p.goods_unit, p.goods_unit_type, c.goods_base_price, c.unit_default')
		->join('__ORG_GOODS_CONVERT__ as c on p.cv_id = c.cv_id and c.org_parent_id = ' . $orgId . ' and c.goods_base_price > 0')
		->where("p.goods_id = $goodsId")->order('p.goods_unit_type asc')->select();
		$productList=array();
		foreach($productListRes as $k=>$v){
			if(! empty($v["goods_unit"])){
				$productList[]=$v;
			}
		}
        foreach($productList as $k=>$v){
        	
        		$where["cv_id"]=$v["cv_id"];
        		$where["goods_id"]=$goodsId;
        		$where["is_close"]=0;
        		$res=M("activity")->where($where)->find();
        		if($res){
        			if($res["act_type"]==0){
        				$productList[$k]["act_type"]=0;
        				$productList[$k]["act_price"]=$res["act_price"];
        				$productList[$k]["act_money"]=0;
        				$productList[$k]["act_offer_money"]=0;
        				$productList[$k]["act_des"]="单价优惠（原价".$v["goods_base_price"]."，现价".$res["act_price"]."）";
        			}elseif($res["act_type"]==1){
        				$productList[$k]["act_type"]=1;
        				$productList[$k]["act_price"]=0;
        				$productList[$k]["act_money"]=$res["act_money"];
        				$productList[$k]["act_offer_money"]=$res["act_offer_money"];
        				$productList[$k]["act_des"]="满减优惠（满".$res["act_money"]."减".$res["act_offer_money"]."）";
        			}else{
        				$productList[$k]["act_type"]=2;
        				$productList[$k]["act_price"]=0;
        				$productList[$k]["act_money"]=0;
        				$productList[$k]["act_offer_money"]=0;
        				$productList[$k]["act_des"]="赠品优惠";
        			}
        		}else{
        			$productList[$k]["act_type"]="-1";
        			$productList[$k]["act_price"]=0;
        			$productList[$k]["act_money"]=0;
        			$productList[$k]["act_offer_money"]=0;
        			$productList[$k]["act_des"]="无";
        		
        		
        		}
        	
        	
        	
        }
        //echo M('goods_product')->getLastSql();die;

		// 返回数据
        if(empty($productList))
		{
			echo json_encode(array('error' => '1', 'msg' => '暂无商品信息'));
        }
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '查询成功', 'data' => $productList));
        }		
	}

}

/*************************** end ************************************/