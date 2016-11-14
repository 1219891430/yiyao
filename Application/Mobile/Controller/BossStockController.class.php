<?php

/*******************************************************************
 ** 文件名称: BossStockController.class.php
 ** 功能描述: 经销商老板端库存接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossStockController extends Controller {

	// 经销商商品库存情况
	public function getDepotStockAction()
	{
		// 经销商ID
		$org_parent_id = I('companyId');
		$where['org_parent_id'] = $org_parent_id;
		$list = M("depot_stock")->alias('s')->field('g.goods_id, g.goods_code, g.goods_name, g.goods_spec, s.small_stock, s.stock_string')
		->join('__GOODS_INFO__ as g on s.goods_id = g.goods_id')->where($where)->order('g.goods_id asc')->select();

		// 返回
		if(empty($list))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $list));
		}
	}
	
	public function getCarStockAction(){
		$org_parent_id = I('companyId');
		$where['org_parent_id'] = $org_parent_id;
		$stockList=M("carsale_stock")->field("staff_id,goods_id,goods_num")->where($where)->select();
		unset($where);
		$where['org_parent_id'] = $org_parent_id;
		$where["goods_unit_type"]=1;
		$goodsPriceList=M("org_goods_convert")->field("goods_id,goods_base_price")->where($where)->select();
		unset($where);
		$where['org_parent_id'] = $org_parent_id;
		$where['role_id']=3;
		$staffList=M("org_staff")->field("staff_id,staff_name")->where($where)->select();
		$goodsPrice=array();
		foreach($goodsPriceList as $v){
			$goodsPrice[$v["goods_id"]]=$v["goods_base_price"];
		}
		$staffName=array();
		foreach($staffList as $v){
			$staffName[$v["staff_id"]]=$v["staff_name"];
		}
		foreach($stockList as $k=>$v){
			if($staffName[$v["staff_id"]]){
				$stockList[$k]["staff_name"]=$staffName[$v["staff_id"]];
			}else{
				$stockList[$k]["staff_name"]="";
			}
			if($goodsPrice[$v["goods_id"]]){
				$stockList[$k]["goods_base_price"]=$goodsPrice[$v["goods_id"]];
			}else{
				$stockList[$k]["goods_base_price"]=0;
			}
			$stockList[$k]["total_money"]=$stockList[$k]["goods_base_price"]*$v["goods_num"];
			
		}
		
		if(empty($stockList))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $stockList));
		}
	}

}

/*************************** end ************************************/