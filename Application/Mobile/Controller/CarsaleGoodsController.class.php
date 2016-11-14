<?php

/*******************************************************************
 ** 文件名称: CarsaleGoodsController.class.php
 ** 功能描述: 经销商车销商品接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleGoodsController extends Controller {

	// 商品列表
	// 创建人员: richie
	// 创建日期: 2016-08-16
    public function goodsInfoListAction()
    {
		// 经销商ID
		$org_parent_id = intval($_REQUEST['companyId']);
		
		// 先读取缓存中的商品数据和品牌数据
		//$goodsList = S('goodsList' . $org_parent_id);
		//$brandList = S('brandList' . $org_parent_id);
		//if(!empty($goodsList) && !empty($brandList))
		//{
			//echo json_encode(array('error' => '-1', 'msg' => '成功', 'products' => $goodsList, 'brands' => $brandList));
			//exit;
		//}
		
		// 根据经销商库存查询经销商商品
		$goodsList = array();
		$brandList = array();
		$results = M('depot_stock')->alias('s')->field('g.*')
		->join('__GOODS_INFO__ as g on s.goods_id = g.goods_id')
		->where("s.org_parent_id = $org_parent_id")->order('s.goods_id asc')->select();
		
        // 格式化商品列表        
		foreach($results as $item)
		{
			// 获取品牌, 后面做去重工作
			$brandList[] = $item['brand_id'];
			
			// 商品信息
			$temp = array();
			$temp['brandId'] = $item['brand_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_code'] = $item['goods_code'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_spec'];
			$temp['goods_convert_s'] = $item['goods_convert_s'];
			$temp['goods_convert_m'] = $item['goods_convert_m'];
			$temp['goods_convert_b'] = $item['goods_convert_b'];
			
			// 查询商品包装和价格
            $where['org_parent_id'] = $org_parent_id;
            $where['goods_id'] = $item["goods_id"];
			$convert = M("org_goods_convert")->where($where)->order("goods_unit_type asc")->select();
			foreach($convert as $val)
			{
				if(! empty($val['goods_unit'])){
					$temp2 = array();
					$temp2['cv_id'] = $val['cv_id'];
					$temp2['goods_unit_type'] = $val['goods_unit_type'];
					$temp2['goods_unit'] = $val['goods_unit'];
					$temp2['goods_base_price'] = $val['goods_base_price'];
					$temp2['unit_default'] = $val['unit_default'];
				
					///
					$where1["cv_id"]=$val["cv_id"];
        			$where1["goods_id"]=$item["goods_id"];
        			$where1["org_parent_id"]=$org_parent_id;
        			$where1["is_close"]=0;
        			$res=M("activity")->where($where1)->find();
        			
        			if($res){
        				if($res["act_type"]==0){
        					$temp2["act_type"]=0;
        					$temp2["act_price"]=$res["act_price"];
        					$temp2["act_money"]=0;
        					$temp2["act_offer_money"]=0;
        					$temp2["act_des"]="单价优惠（原价".$val["goods_base_price"]."，现价".$res["act_price"]."）";
        				}elseif($res["act_type"]==1){
        					$temp2["act_type"]=1;
        					$temp2["act_price"]=0;
        					$temp2["act_money"]=$res["act_money"];
        					$temp2["act_offer_money"]=$res["act_offer_money"];
        					$temp2["act_des"]="满减优惠（满".$res["act_money"]."减".$res["act_offer_money"]."）";
        				}else{
        					$temp2["act_type"]=2;
        					$temp2["act_price"]=0;
        					$temp2["act_money"]=0;
        					$temp2["act_offer_money"]=0;
        					$temp2["act_des"]="赠品优惠";
        				}	
        			}else{
        				$temp2["act_type"]="-1";
        				$temp2["act_price"]=0;
        				$temp2["act_money"]=0;
        				$temp2["act_offer_money"]=0;
        				$temp2["act_des"]="无";
        		
        		
        			}
        			///
				
					if($val['goods_base_price']>0){
						$temp['goods_convert'][] = $temp2;
					}
				}
				
				
			}
			$convertCount=count($temp['goods_convert']);
			// 价格不能为空
			if(!empty($convert)&&$convertCount>0){
				 $goodsList[] = $temp; 
			}	
        }
		
        // 品牌去重后进行查询
        $brandList = array_unique($brandList);
		$brandList = M('goods_brand')->where("brand_id in (" . implode(',', $brandList) . ")")->order("brand_id asc")->select();
		foreach($brandList as &$v)
		{
			unset($v['brand_logo']);
			unset($v['remark']);
			unset($v['is_close']);
		}

		// 商品字母排序
        $goods_letter = array();
        foreach ($goodsList as $v1) {
            $temp_goods = $v1['goods_name'];
            $goods_letter[] = getFirstCharter($temp_goods);
        }
        array_multisort($goods_letter, SORT_ASC, SORT_STRING, $goodsList);

		// 品牌字母排序
        $brand_letter = array();
        foreach ($brandList as $v2) {
            $temp_brand = $v2['brand_name'];
            $brand_letter[] = getFirstCharter($temp_brand);
        }
        array_multisort($brand_letter, SORT_ASC, SORT_STRING, $brandList);

		// 缓存数据一天, 单位是秒
		S('goodsList' . $org_parent_id, $goodsList, 60*60*24);
		S('brandList' . $org_parent_id, $brandList, 60*60*24);
		
		// 返回
		if(!empty($goodsList) && !empty($brandList))
		{
			echo json_encode(array('error' => -1, 'msg' => '成功', 'products' => $goodsList, 'brands' => $brandList));
		}
		else
		{
			echo json_encode(array('error' => 2, 'msg' => '查询失败'));
		}
    }

	// 商品详细接口, 返回商品包装信息
	// 创建人员: richie
	// 创建日期: 2016-08-16
	// ?userId=793&companyId=2&good_id=2560 业务员下单商品弹窗
    public function getCarportInfoAction()
    {
		// 业务员ID和商品ID
		$staff_id = intval($_REQUEST['userId']);
		$org_parent_id = intval($_REQUEST['companyId']);
		$goods_id = intval($_REQUEST['goodsId']);
		$shop_id = intval($_REQUEST['shopId']);
		
		// 查询各单位价格信息, 包括历史价格信息
		$priceData = array();
		$where['org_parent_id'] = $org_parent_id;
		$where['goods_id'] = $goods_id;
		$results = M('org_goods_convert')->where($where)->order("goods_unit_type asc")->select();

		// 循环商品单位信息, 获取历史订单价格
		foreach($results as $val)
		{
			// 查询历史订单价格
			$where = array();
			$where['o.staff_id'] = $staff_id;
			$where['o.cust_id'] = $shop_id;
			$where['o.is_cancel'] = 0;
			$where['g.cv_id'] = intval($val['cv_id']);
			$where['g.singleprice'] = array('gt', 0);
			$lastPrice = M('carsale_orders_goods')->alias('g')->field('g.singleprice')
			->join('__CARSALE_ORDERS__ as o on o.order_id = g.order_id')
			->where($where)->order('cv_id asc')->limit(1)->find();
			
			// 替换历史订单价格
			$goods_base_price = $val['goods_base_price'];
			if(!empty($lastPrice)){ $goods_base_price = $lastPrice['singleprice']; }
		
		
			$temp2 = array();
			$temp2['cv_id'] = $val['cv_id'];
			$temp2['goods_unit_type'] = $val['goods_unit_type'];
			$temp2['goods_unit'] = $val['goods_unit'];
			$temp2['goods_base_price'] = $goods_base_price;
			$temp2['unit_default'] = $val['unit_default'];
			$priceData[] = $temp2;
		}
		
		// 返回数据
		if(!empty($priceData))
		{
			echo json_encode(array('error' => -1, 'msg' => '成功', 'data' => $priceData));
		}
		else
		{
			echo json_encode(array('error' => 2, 'msg' => '查询失败'));
		}	
    }

}

/*************************** end ************************************/