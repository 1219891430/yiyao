<?php

/*******************************************************************
 ** 文件名称: DeliverOrderModel.class.php
 ** 功能描述: 平台车销配送订单公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DeliverOrderModel extends Model {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'car_orders';
	
	// 添加车销配送单
	// 创建人员: richie
	// 创建日期: 2016-08-00
	public function addOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list)
	{
		// 订单总价格
		$order_total = 0;
		
		// 商品数组
		$goods_list1=array();
		$goodsArray = array();
		foreach($goods_list as $k=>$value)
		{
			$goods_list1[$k]["goods_id"]=$value["goods_id"];
			$goods_list1[$k]["cv_id"]=$value["cv_id"];
			$goods_list1[$k]["goods_num"]=$value["number"];
			$temp = array();
			$temp['order_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['cuxiao'] = 0; // 正常销售商品, 非促销
			
			if(!floatval($value['singleprice'])){
				$temp['cuxiao'] = 1; // 正常销售商品, 非促销
			}
			
			$temp['cust_id'] = $order_info['cust_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['good_name'] = $value['goods_name'];
			$temp['good_spec'] = $value['goods_spec'];
			$temp['singleprice'] = $value['singleprice'];
			$temp['number'] = $value['number'];
			$temp['unit_name'] = $value['unit_name'];
			$goodsArray[] = $temp;
			
			$order_total += floatval($temp['number']) * floatval($temp['singleprice']);
		}
		D("DeliverStock")->updateCarInfo($staff_id, $goods_list1, 4);

		// 订单信息
		$orderArray = array();
		$orderArray['order_code'] = $order_info['order_code'];
		$orderArray['staff_id'] = $order_info['staff_id'];
		$orderArray['org_parent_id'] = $order_info['org_parent_id'];
		$orderArray['repertory_id'] = $order_info['repertory_id'];
		$orderArray['cust_id'] = $order_info['cust_id'];
		$orderArray['cust_name'] = $order_info['cust_name'];
		$orderArray['cust_contact'] = $order_info['cust_contact'];
		$orderArray['cust_address'] = $order_info['cust_address'];
		$orderArray['cust_tel'] = $order_info['cust_tel'];
		$orderArray['order_total_money'] = $order_total;
		$orderArray['order_real_money'] = $order_info['order_real_money'];
		$orderArray['is_full_pay'] = (sprintf("%.2f", $order_info['order_real_money'] - $order_total )>=0) ? 1 : 0;
		$orderArray['create_time'] = time();
		$orderArray['order_remark'] = $order_info['order_remark'];
		$orderArray['order_way'] = $order_info['order_way'];
		$orderArray['is_cancel'] = 0;
		$orderArray['cancel_time'] = 0;
		$orderArray['presale_order'] = $order_info['order_id'];
		
		// 启动事务
		M()->startTrans();
		
		// 添加订单
		$order_id = M('car_orders')->add($orderArray);
		if(empty($order_id)){ M()->rollback(); return false; }

		// 添加订单商品
		foreach($goodsArray as &$value){ $value['order_id'] = intval($order_id); }
		$flag = M('car_orders_goods')->addAll($goodsArray);	

		// 提交事务
		if(!empty($flag))
		{
			M()->commit();
			return $order_id;
		}
		else
		{
			M()->rollback();
			return false;
		}
	}

	// 添加车销配送终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-09
	public function addReturnOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list)
	{
		// 订单总价格
		$order_total = 0;
		
		// 商品数组
		$goodsArray = array();
		$goods_list1=array();
		foreach($goods_list as $k=>$value)
		{
			$goods_list1[$k]["goods_id"]=$value["goods_id"];
			$goods_list1[$k]["cv_id"]=$value["cv_id"];
			$goods_list1[$k]["goods_num"]=$value["number"];
			$temp = array();
			$temp['return_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_spec'] = $value['goods_spec'];
			$temp['goods_money'] = $value['singleprice'];
			$temp['goods_num'] = $value['number'];
			$temp['goods_unit'] = $value['unit_name'];
			$goodsArray[] = $temp;
			$order_total += floatval($temp['goods_num']) * floatval($temp['goods_money']);
		}
		D("DeliverStock")->updateCarInfo($staff_id, $goods_list1, 2);

		// 订单信息
		$orderArray = array();
		$orderArray['return_code'] = $order_info['return_code'];
		$orderArray['staff_id'] = $order_info['staff_id'];
		$orderArray['org_parent_id'] = $order_info['org_parent_id'];
		$orderArray['repertory_id'] = $order_info['repertory_id'];
		$orderArray['cust_id'] = $order_info['cust_id'];
		$orderArray['cust_name'] = $order_info['cust_name'];
		$orderArray['cust_contact'] = $order_info['cust_contact'];
		$orderArray['cust_address'] = $order_info['cust_address'];
		$orderArray['cust_tel'] = $order_info['cust_tel'];
		$orderArray['total_money'] = $order_total;
		$orderArray['pay_money'] = $order_total; // 终端退货没有实收概念, 自然也没有清欠
		$orderArray['return_way'] = $order_info['order_way'];
		$orderArray['return_remark'] = $order_info['order_remark'];
		$orderArray['create_time'] = time();
		$orderArray['is_cancel'] = 0;
		$orderArray['cancel_time'] = 0;
		
		// 启动事务
		M()->startTrans();
		
		// 添加订单
		$return_id = M('car_return')->add($orderArray);
		if(empty($return_id)){ M()->rollback(); return false; }

		// 添加订单商品
		foreach($goodsArray as &$value){ $value['return_id'] = intval($return_id); }
		$flag = M('car_return_goods')->addAll($goodsArray);	

		// 提交事务
		if(!empty($flag))
		{
			M()->commit();
			return $return_id;
		}
		else
		{
			M()->rollback();
			return false;
		}
	}

	// 添加车销配送调换货
	// 创建人员: richie
	// 创建日期: 2016-08-09
	public function addChangeOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_in_list, $goods_out_list)
	{
		// 订单总价格
		$order_total = 0;
		
		// 调回商品数组
		$goods_list=array();
		$goodsInArray = array();
		foreach($goods_in_list as $k=>$value)
		{
			$goods_list[$k]["goods_id"]=$value["goods_id"];
			$goods_list[$k]["cv_id"]=$value["cv_id"];
			$goods_list[$k]["goods_num"]=$value["number"];
			$temp = array();
			$temp['change_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_sepc'] = $value['goods_spec'];
			$temp['singleprice'] = $value['singleprice'];
			$temp['number'] = $value['number'];
			$temp['unit_name'] = $value['unit_name'];
			$temp['is_change_in'] = 1; // 1调回，0调出
			$goodsInArray[] = $temp;
			
			// 价格应该减
			$order_total -= floatval($temp['number']) * floatval($temp['singleprice']);
		}
        
		D("DeliverStock")->updateCarInfo($staff_id, $goods_list, 3);
		
		// 调出商品数组
		$goods_list=array();
		$goodsOutArray = array();
		foreach($goods_out_list as $k=> $value)
		{
			$goods_list[$k]["goods_id"]=$value["goods_id"];
			$goods_list[$k]["cv_id"]=$value["cv_id"];
			$goods_list[$k]["goods_num"]=$value["number"];
			$temp = array();
			$temp['change_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_sepc'] = $value['goods_spec'];
			$temp['singleprice'] = $value['singleprice'];
			$temp['number'] = $value['number'];
			$temp['unit_name'] = $value['unit_name'];
			$temp['is_change_in'] = 0; // 1调回，0调出
			$goodsOutArray[] = $temp;
			
			// 价格应该加
			$order_total += floatval($temp['number']) * floatval($temp['singleprice']);
		}
        D("DeliverStock")->updateCarInfo($staff_id, $goods_list, 6);
		// 订单信息
		$orderArray = array();
		$orderArray['change_code'] = $order_info['change_code'];
		$orderArray['staff_id'] = $order_info['staff_id'];
		$orderArray['org_parent_id'] = $order_info['org_parent_id'];
		$orderArray['repertory_id'] = $order_info['repertory_id'];
		$orderArray['cust_id'] = $order_info['cust_id'];
		$orderArray['cust_name'] = $order_info['cust_name'];
		$orderArray['cust_contact'] = $order_info['cust_contact'];
		$orderArray['cust_address'] = $order_info['cust_address'];
		$orderArray['cust_tel'] = $order_info['cust_tel'];
		$orderArray['total_money'] = $order_total;
		$orderArray['pay_money'] = $order_total;
		$orderArray['pay_type'] = $order_info['order_way'];
		$orderArray['change_remark'] = $order_info['order_remark'];
		$orderArray['create_time'] = time();
		$orderArray['is_cancel'] = 0;
		$orderArray['cancel_time'] = 0;

		// 启动事务
		M()->startTrans();
		
		// 添加调换货单
		$change_id = M('car_change')->add($orderArray);
		if(empty($change_id)){ M()->rollback(); return false; }

		// 添加订单商品
		foreach($goodsInArray as &$value){ $value['change_id'] = intval($change_id); }
		$flag = M('car_change_goods')->addAll($goodsInArray);
		
		foreach($goodsOutArray as &$value){ $value['change_id'] = intval($change_id); }
		$flag = M('car_change_goods')->addAll($goodsOutArray);

		// 提交事务
		if(!empty($flag))
		{
			M()->commit();
			return $change_id;
		}
		else
		{
			M()->rollback();
			return false;
		}
	}

	// 其他操作
	
	
}

/****************************** end *******************************/