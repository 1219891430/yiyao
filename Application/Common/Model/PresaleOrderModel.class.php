<?php

/*******************************************************************
 ** 文件名称: PresaleOrderModel.class.php
 ** 功能描述: 平台预单操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class PresaleOrderModel extends Model {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'presale_orders';

	// 添加预单
	// 创建人员: richie
	// 创建日期: 2016-08-05
	public function addOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list)
	{
		// 订单总价格
		$order_total = 0;
		
		// 商品数组
		$goodsArray = array();
		foreach($goods_list as $value)
		{
			$temp = array();
			$temp['order_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['cuxiao'] = $value["cuxiao"]; // 正常销售商品, 非促销
			$temp['cust_id'] = $order_info['cust_id'];
			$temp['org_parent_id'] = $order_info['org_parent_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_spec'] = $value['goods_spec'];

			$temp['singleprice'] = $value['singleprice'];
			$temp['number'] = $value['number'];
			$temp['unit_name'] = $value['goods_unit'];
			
			
			$temp['goods_total_money']=0;
			$temp['goods_remark']="";
			
			if($value["cuxiao"]==0){
				$where["is_close"]=0;
				$time=time();
				$where["end_time"]=array("gt",$time);
				$where["start_time"]=array("lt",$time);
				$where["org_parent_id"]=$order_info['org_parent_id'];
				$where["goods_id"]=$value['goods_id'];
				$where["cv_id"]=$value['cv_id'];
				$res=M("activity")->where($where)->find();
			

			    if($res['act_type']==0){
			    	if($res['act_price']){
			    		$temp['singleprice'] = $res['act_price'];
			    	}
                    $temp['goods_total_money']= floatval($temp['number']) * floatval($temp['singleprice']);
               }elseif($res["act_type"]==1){
					$total=floatval($temp['number']) * floatval($temp['singleprice']);
					$order_total -=floor($total/$res["act_money"])*$res["act_offer_money"];
					$temp['goods_total_money']=$total-floor($total/$res["act_money"])*$res["act_offer_money"];
					$temp['goods_remark']="满".$res["act_money"]."减".$res["act_offer_money"];
				}else{
					$temp['goods_total_money']= floatval($temp['number']) * floatval($temp['singleprice']);
				}
				
                $order_total += floatval($temp['number']) * floatval($temp['singleprice']);
			}
			$goodsArray[] = $temp;
		}
		
		// 订单信息
		$orderArray = array();
		$orderArray['order_code'] = $order_info['order_code'];
		$orderArray['staff_id'] = $order_info['staff_id'];
		$orderArray['org_parent_id'] = $order_info['org_parent_id'];
		$orderArray['cust_id'] = $order_info['cust_id'];
		$orderArray['repertory_id'] = $order_info['repertory_id'];
		$orderArray['cust_name'] = $order_info['cust_name'];
		$orderArray['cust_contact'] = $order_info['cust_contact'];
		$orderArray['cust_address'] = $order_info['cust_address'];
		$orderArray['cust_tel'] = $order_info['cust_tel'];
		$orderArray['order_total_money'] = $order_total;
		$orderArray['order_status'] = $order_info['order_status']; // 默认1, 1已下单 2已派送 3已完成
		$orderArray['order_remark'] = $order_info['order_remark'];
		$orderArray['order_way'] = $order_info['order_way'];
		if($order_info['order_from']){
			$orderArray['order_from']=$order_info['order_from'];
		}else{
			$orderArray['order_from']=1;
		}
		$orderArray['add_time'] = time();
		$orderArray['is_cancel'] = 0;
		$orderArray['cancel_time'] = 0;
		
		// 启动事务
		M()->startTrans();
		
		// 添加订单
		$order_id = M('presale_orders')->add($orderArray);
		if(empty($order_id)){ M()->rollback(); return false; }

		// 添加订单商品
		foreach($goodsArray as &$value){ $value['order_id'] = intval($order_id); }
		$flag = M('presale_orders_goods')->addAll($goodsArray);	

		// 提交事务
		if(!empty($flag))
		{
			M()->commit();
			return true;
		}
		else
		{
			M()->rollback();
			return false;
		}
	}

	// 添加终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-08
	public function addReturnOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list)
	{
		// 订单总价格
		$order_total = 0;
		
		// 商品数组
		$goodsArray = array();
		foreach($goods_list as $value)
		{
			$temp = array();
			$temp['return_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_sepc'] = $value['goods_spec'];
			$temp['goods_money'] = $value['singleprice'];
			$temp['goods_num'] = $value['number'];
			$temp['goods_unit'] = $value['goods_unit'];
			$goodsArray[] = $temp;
			$order_total += floatval($temp['goods_num']) * floatval($temp['goods_money']);
		}
		
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
		$orderArray['return_real_money'] = $order_total;
		$orderArray['order_remark'] = $order_info['order_remark'];
		$orderArray['order_status'] = $order_info['order_status']; // 默认1, 1已下单 2已派送 3已完成
		$orderArray['order_way'] = $order_info['order_way'];
		$orderArray['add_time'] = time();
		
		if($order_info['order_from']){
			$orderArray['order_from']=$order_info['order_from'];
		}else{
			$orderArray['order_from']=1;
		}
		
		$orderArray['is_cancel'] = 0;
		$orderArray['cancel_time'] = 0;
		
		// 启动事务
		M()->startTrans();
		
		// 添加订单
		$return_id = M('presale_return')->add($orderArray);
		if(empty($return_id)){ M()->rollback(); return false; }

		// 添加订单商品
		foreach($goodsArray as &$value){ $value['return_id'] = intval($return_id); }
		$flag = M('presale_return_goods')->addAll($goodsArray);	

		// 提交事务
		if(!empty($flag))
		{
			M()->commit();
			return true;
		}
		else
		{
			M()->rollback();
			return false;
		}
	}

	// 添加调换货
	// 创建人员: richie
	// 创建日期: 2016-08-08
	public function addChangeOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_in_list, $goods_out_list)
	{
		// 订单总价格
		$order_total = 0;
		
		// 调回商品数组
		$goodsInArray = array();
		foreach($goods_in_list as $value)
		{
			$temp = array();
			$temp['change_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_sepc'] = $value['goods_spec'];
			$temp['singleprice'] = $value['singleprice'];
			$temp['number'] = $value['number'];
			$temp['unit_name'] = $value['goods_unit'];
			$temp['is_change_in'] = 1; // 1调回，0调出
			$goodsInArray[] = $temp;
			
			// 价格应该减
			$order_total -= floatval($temp['number']) * floatval($temp['singleprice']);
		}
			
		// 调出商品数组
		$goodsOutArray = array();
		foreach($goods_out_list as $value)
		{
			$temp = array();
			$temp['change_id'] = 0;
			$temp['cv_id'] = $value['cv_id'];
			$temp['goods_id'] = $value['goods_id'];
			$temp['goods_name'] = $value['goods_name'];
			$temp['goods_sepc'] = $value['goods_spec'];
			$temp['singleprice'] = $value['singleprice'];
			$temp['number'] = $value['number'];
			$temp['unit_name'] = $value['goods_unit'];
			$temp['is_change_in'] = 0; // 1调回，0调出
			$goodsOutArray[] = $temp;
			
			// 价格应该加
			$order_total += floatval($temp['number']) * floatval($temp['singleprice']);
		}
        
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
		$orderArray['order_total_money'] = $order_total;
		$orderArray['order_remark'] = $order_info['order_remark'];
		$orderArray['order_way'] = $order_info['order_way'];
		$orderArray['order_status'] = $order_info['order_status']; // 默认1, 1已下单 2已派送 3已完成
		$orderArray['add_time'] = time();
		
		if($order_info['order_from']){
			$orderArray['order_from']=$order_info['order_from'];
		}else{
			$orderArray['order_from']=1;
		}
		$orderArray['is_cancel'] = 0;
		$orderArray['cancel_time'] = 0;

		// 启动事务
		M()->startTrans();
		
		// 添加调换货单
		$change_id = M('presale_change')->add($orderArray);
		
		if(empty($change_id)){ M()->rollback(); return false; }

		// 添加订单商品
		foreach($goodsInArray as &$value){ $value['change_id'] = intval($change_id); }
		if($goodsInArray){
			$flag = M('presale_change_goods')->addAll($goodsInArray);
		}
		
		
		foreach($goodsOutArray as &$value){ $value['change_id'] = intval($change_id); }
		if($goodsOutArray){
			$flag = M('presale_change_goods')->addAll($goodsOutArray);
		}
		
		

		// 提交事务
		if(!empty($flag))
		{
			M()->commit();
			return true;
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