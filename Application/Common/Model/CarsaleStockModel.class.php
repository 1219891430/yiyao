<?php

/*******************************************************************
 ** 文件名称: CarsaleStockModel.class.php
 ** 功能描述: 经销商车销实时车存公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class CarsaleStockModel extends Model {

	// 数据主表: 经销商车销库存表
	protected $tableName = 'carsale_stock';
	
	/**
	 * 更新配送车存
	 *
	 * @param int 		$staff_id  		经销商业务人员ID
	 * @param array  	$goods_list[] = array('cv_id'=>'', 'goods_id'=>'', 'goods_num'=>'')
	 * @param string 	$stock_type		1车存申请(+), 2终端退货(+), 3调换退货(+), 4车销(-), 5调换出货(-), 6车销退库(-)
	 * @return bool		返回成功失败
	 */
	public function updateCarInfo($org_parent_id, $staff_id, $goods_list, $stock_type)
	{
		// 循环商品进行更新
		foreach($goods_list as $item)
		{
			// 商品信息
			$cv_id = $item['cv_id'];
			$goods_id = $item['goods_id'];
			$goods_num = $item['goods_num'];
			
			// 是否已经存在
			$stockInfo = M('carsale_stock')->where("staff_id = $staff_id AND goods_id = $goods_id")->find();
			
			// 不存在商品信息的话, 添加商品信息
			if(empty($stockInfo))
			{
				// 查询商品信息
				$goodsInfo = M('goods_info')->field('goods_name,goods_spec')->where("goods_id = $goods_id")->find();
				$data = array();
				$data['staff_id'] = $staff_id;
				$data['goods_id'] = $goods_id;
				$data['goods_name'] = $goodsInfo['goods_name'];
				$data['goods_spec'] = $goodsInfo['goods_spec'];
				$data['goods_num'] = 0;
				$data['num_string'] = '';
				$data['org_parent_id'] = $org_parent_id;
				M('carsale_stock')->add($data);	
			}

			// 现有商品数量, 最小单位
			$stockNumber = !empty($stockInfo['goods_num']) ? intval($stockInfo['goods_num']) : 0;

			// 变化商品数量, 转换最小单位
			$changeGoodsNumber = getSmallNumber($cv_id, $goods_num);

			// 更新数量
			if($type <=3 ){ $num = $stockNumber + $changeGoodsNumber; }
			else { $num = $stockNumber - $changeGoodsNumber; }
			$data = array();
			$data['goods_num'] = $stock_num = $num;
			$data['num_string'] = $num_string = getGoodsUnitString($goods_id, $num);
			M('carsale_stock')->where("staff_id = $staff_id AND goods_id = $goods_id")->save($data);	

			// 添加日志
			$data = array();
			$data['staff_id'] = $staff_id;
			$data['org_parent_id'] = $org_parent_id;
			$data['goods_id'] = $goods_id;
			$data['stock_type'] = $stock_type;
			$data['goods_num'] = $changeGoodsNumber;
			$data['goods_string'] = getGoodsUnitString($goods_id, $changeGoodsNumber);
			$data['stock_num'] = $stock_num;
			$data['stock_string'] = $num_string;
			$data['datetime'] = time();
			$data['bianhua'] = $this->stockBianhua($stock_type, $num_string);
			M('carsale_stock_log')->add($data);
		}
		
		// 返回
		return true;
	}
	
	// 说明变化
	// 1车存申请(+), 2终端退货(+), 3调换退货(+), 4车销(-), 5调换出货(-), 6车销退库(-)
	private function stockBianhua($stock_type, $num_string)
	{
		$descString = '';
		switch ($stock_type)
		{
			case 1: $descString = "车存申请 + $num_string"; break;  
			case 2: $descString = "终端退货 + $num_string"; break;
			case 3: $descString = "终端调换退货 + $num_string"; break;  
			case 4: $descString = "车销订单 - $num_string"; break;  
			case 5: $descString = "终端调换出货 - $num_string"; break;  
			case 6: $descString = "车存退库 - $num_string"; break;  
			default:	break; 
		}
		return $descString;
	}

	// 其他操作
	
	
}

/****************************** end *******************************/