<?php

/*******************************************************************
 ** 文件名称: PresaleOrderController.class.php
 ** 功能描述: 平台采单端销售预单,终端退货, 调换货接口
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class PresaleOrderController extends Controller {

	// 提交预单, 根据商品提交情况, 按照经销商拆分预单
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function addAction()
        {
		// 用户提交数据
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay');			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goodList	= $_REQUEST["goodsList"];	// 商品数据
		$cuxiaoList	= $_REQUEST["cuxiaoList"];
        $order_from = I('orderFrom');           //订单来源1采单员2业务员3商城
        if(empty($order_from)){ $order_from = 1; }

		// $goodList 商品信息
		// $temp['org_parent_id']			// 经销商ID
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['goods_unit']				// 单位名称

        if($order_from==2){
            // 业务员所在仓库
            $depot_id = M('org_staff')->alias('os')
                ->join('__DEPOT_ORG__ do on do.org_parent_id = os.org_parent_id')
                ->where('staff_id = ' . $staff_id)
                ->getField('repertory_id');
        }
        else{
            // 采单员所在仓库
            $depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');
        }


		// 商品按照经销商归类
		$org_parent_array = array();
		$goodsList=json_decode($goodList,true);
		$cuxiaoList=json_decode($cuxiaoList,true);
		
		$allGoods=array();
		
		foreach($goodsList as $v){
			$v["cuxiao"]=0;
			$allGoods[]=$v;
		}
		foreach($cuxiaoList as $v){
			$v["cuxiao"]=1;
			$v["singleprice"]=0;
			$allGoods[]=$v;
		}
		
		foreach($allGoods as $val)
		{
			$org_parent_id = $val['org_parent_id'];
			$org_parent_array[$org_parent_id][] = $val;
		}
		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();


		// 添加订单
		$flag = false;
		foreach($org_parent_array as $org_parent_id=>$goods_list)
		{
			// 订单信息
			$order_info = array();
			$order_info['order_code'] = create_uniqid_code('PO', $staff_id);
			$order_info['repertory_id'] = intval($depot_id);
			$order_info['org_parent_id'] = $org_parent_id;
			$order_info['staff_id'] = $staff_id;
			$order_info['cust_id'] = $cust_id;
			$order_info['cust_name'] = $shopInfo['cust_name'];
			$order_info['cust_contact'] = $shopInfo['contact'];
			$order_info['cust_address'] = $shopInfo['address'];
			$order_info['cust_tel'] = $shopInfo['telephone'];
			$order_info['order_remark'] = $remark;
			$order_info['order_status'] = 1; // 1已下单 2已派送 3已完成
			$order_info['order_way'] = $order_way;
            $order_info['order_from'] = $order_from;
			$flag = D('PresaleOrder')->addOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);
		}
		
		// 返回
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '添加成功'));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '添加失败'));
		}
    }

	// 采单端取消预单, 只能取消当前预单
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function cancelAction()
	{
		// 订单ID
		$order_id = intval($_REQUEST['orderId']);
		$staff_id = intval($_REQUEST['userId']);
	
		// 是否今天订单
		$beginTime = date('Y-m-d');
		$beginTime = strtotime($beginTime." 00:00:00");
		$endTime = $beginTime + 60*60*24;
		
		// 搜索条件
		$where['order_id'] = $order_id;
		$where['staff_id'] = $staff_id;
		$where['order_status'] = 1; // 未处理的订单
		$where['add_time'] = array(array('gt', $beginTime), array('lt', $endTime));
		$where['is_cancel'] = 0; // 未取消的订单
		$orderInfo = M('presale_orders')->where($where)->field('order_id, order_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('presale_orders')->where($where)->save($data);
		
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}
	
	// 配送端取消预单, 只能取消当前预单
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function deliverCancelAction()
	{
		// 订单ID
		$order_id = intval($_REQUEST['orderId']);
		

		$where['order_id'] = $order_id;
		
		$where['is_cancel'] = 0; // 未取消的订单
		$orderInfo = M('presale_orders')->where($where)->field('order_id, order_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('presale_orders')->where($where)->save($data);
		
		// 返回结果
		$whereA["order_id"]=$order_id;
		$whereA["order_type"]=1;
	    $whereA["order_state"]=2;
	    $dataA["order_state"]=3;
		M("admin_order")->where($whereA)->save($dataA);
		
		
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}
	

	// 提交终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function returnAction()
	{
		// 提交信息
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay');			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goodList	= $_REQUEST["goodsList"];	// 商品数据
        $order_from = I('orderFrom');           //订单来源1采单员2业务员3商城
        if(empty($order_from)){ $order_from = 1; }

		// 商品信息
		//$temp['org_parent_id']	// 经销商ID
		//$temp['cv_id']			// 货品ID
		//$temp['goods_id']			// 商品ID
		//$temp['goods_name']		// 商品名称
		//$temp['goods_spec']		// 商品规格
		//$temp['singleprice']		// 销售价格
		//$temp['number']			// 销售数量
		//$temp['goods_unit']		// 数量单位

        if($order_from==2){
            // 业务员所在仓库
            $depot_id = M('org_staff')->alias('os')
                ->join('__DEPOT_ORG__ do on do.org_parent_id = os.org_parent_id')
                ->where('staff_id = ' . $staff_id)
                ->getField('repertory_id');
        }
        else{
            // 采单员所在仓库
            $depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');
        }

		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();
		
		// 商品按照经销商归类
		$org_parent_array = array();
		$goodsList=json_decode($goodList,true);
		foreach($goodsList as $val)
		{
			$org_parent_id = $val['org_parent_id'];
			$org_parent_array[$org_parent_id][] = $val;
		}
		
		// 添加退货单
		$flag = false;
		foreach($org_parent_array as $org_parent_id=>$goods_list)
		{
			// 订单信息
			$order_info = array();
			$order_info['return_code'] = create_uniqid_code('PR', $staff_id);
			$order_info['repertory_id'] = intval($depot_id);
			$order_info['org_parent_id'] = $org_parent_id;
			$order_info['staff_id'] = $staff_id;
			$order_info['cust_id'] = $cust_id;
			$order_info['cust_name'] = $shopInfo['cust_name'];
			$order_info['cust_contact'] = $shopInfo['contact'];
			$order_info['cust_address'] = $shopInfo['address'];
			$order_info['cust_tel'] = $shopInfo['telephone'];
			$order_info['order_remark'] = $remark;
			$order_info['order_status'] = 1; // 1已下单 2已派送 3已完成
			$order_info['order_way'] = $order_way;
            $order_info['order_from'] = $order_from;
			$flag = D('PresaleOrder')->addReturnOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);
		}
		
		// 返回
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '退货成功'));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '退货失败'));
		}
	}
	
	// 采单端取消终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function cancelReturnAction()
	{
		// 订单ID
		$return_id = intval($_REQUEST['orderId']);
		$staff_id = intval($_REQUEST['userId']);
	
		// 是否今天订单
		$beginTime = date('Y-m-d');
		$beginTime = strtotime($beginTime." 00:00:00");
		$endTime = $beginTime + 60*60*24;
		
		// 搜索条件
		$where['return_id'] = $return_id;
		$where['staff_id'] = $staff_id;
		$where['add_time'] = array(array('gt', $beginTime), array('lt', $endTime));
		$where['is_cancel'] = 0;
		$orderInfo = M('presale_return')->where($where)->field('return_id, return_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('presale_return')->where($where)->save($data);
		
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}
	
	// 配送端取消终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function deliverCancelReturnAction()
	{
		// 订单ID
		$return_id = intval($_REQUEST['orderId']);
		
	
		// 是否今天订单
		
		
		// 搜索条件
		$where['return_id'] = $return_id;
		
		
		$where['is_cancel'] = 0;
		$orderInfo = M('presale_return')->where($where)->field('return_id, return_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('presale_return')->where($where)->save($data);
		
		
		
		$whereA["order_id"]=$return_id;
		$whereA["order_type"]=2;
	    $whereA["order_state"]=2;
	    $dataA["order_state"]=3;
		M("admin_order")->where($whereA)->save($dataA);
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}
	
	// 提交调换货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function changeAction()
	{
		// 提交信息
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay');			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goods_in	= $_REQUEST["goods_in"];	// 调回商品数据
		$goods_out	= $_REQUEST["goods_out"];	// 调出商品数据
        $order_from = I('orderFrom');           //订单来源1采单员2业务员3商城
        if(empty($order_from)){ $order_from = 1; }

		// 商品信息
		//$temp['org_parent_id']	// 经销商ID
		//$temp['cv_id']			// 货品ID
		//$temp['goods_id']			// 商品ID
		//$temp['goods_name']		// 商品名称
		//$temp['goods_spec']		// 商品规格
		//$temp['singleprice']		// 销售价格
		//$temp['number']			// 销售数量
		//$temp['goods_unit']		// 数量单位

        if($order_from==2){
            // 业务员所在仓库
            $depot_id = M('org_staff')->alias('os')
                ->join('__DEPOT_ORG__ do on do.org_parent_id = os.org_parent_id')
                ->where('staff_id = ' . $staff_id)
                ->getField('repertory_id');
        }
        else{
            // 采单员所在仓库
            $depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');
        }
		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();
		
		// 商品按照经销商归类
		// 如果存在调回商品属于一个经销商, 调出商品属于另一个经销商, 怎么办？
		$goodsList = array();
		$org_parent_array = array();
		$goods_in = json_decode($goods_in,true);
		$goods_out = json_decode($goods_out,true);
		foreach($goods_in as $v){
			$a[]=$v["org_parent_id"];
		}
		foreach($goods_out as $v){
			$b[]=$v["org_parent_id"];
		}
		$c=array_diff($a,$b);
		if($c){
			echo json_encode(array('error' => '1', 'msg' => '调入调出不属于一个经销商'));
			return;
		}
		
		foreach($goods_in as &$v){
			 $v['is_change_in'] = 1; 
			 $goodsList[] = $v; 
	    }
		foreach($goods_out as &$v){ 
			$v['is_change_in'] = 0; 
			$goodsList[] = $v; 
		}
		
		
		foreach($goodsList as $val)
		{
			$org_parent_id = $val['org_parent_id'];
			$is_change_in = intval($val['is_change_in']);
			if($is_change_in == 1)
			{
				$org_parent_array[$org_parent_id]['in'][] = $val;
			}
			else
			{
				$org_parent_array[$org_parent_id]['out'][] = $val;
			}
		}
		
		
		// 添加调换货单
		$flag = false;
		foreach($org_parent_array as $org_parent_id=>$goods_list)
		{
			// 订单信息
			$order_info = array();
			$order_info['change_code'] = create_uniqid_code('PC', $staff_id);
			$order_info['repertory_id'] = intval($depot_id);
			$order_info['org_parent_id'] = $org_parent_id;
			$order_info['staff_id'] = $staff_id;
			$order_info['cust_id'] = $cust_id;
			$order_info['cust_name'] = $shopInfo['cust_name'];
			$order_info['cust_contact'] = $shopInfo['contact'];
			$order_info['cust_address'] = $shopInfo['address'];
			$order_info['cust_tel'] = $shopInfo['telephone'];
			$order_info['order_remark'] = $remark;
			$order_info['order_status'] = 1; // 1已下单 2已派送 3已完成
			$order_info['order_way'] = $order_way;
            $order_info['order_from'] = $order_from;

			$flag = D('PresaleOrder')->addChangeOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list['in'], $goods_list['out']);
			
		}
		
		// 返回
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '调换货成功'));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '调换货失败'));
		}
	}
	
	// 采单端取消调换货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function cancelChangeAction()
	{
		// 订单ID
		$change_id = intval($_REQUEST['orderId']);
		$staff_id = intval($_REQUEST['userId']);
	
		// 是否今天订单
		$beginTime = date('Y-m-d');
		$beginTime = strtotime($beginTime." 00:00:00");
		$endTime = $beginTime + 60*60*24;
		
		// 搜索条件
		$where['change_id'] = $change_id;
		$where['staff_id'] = $staff_id;
		$where['add_time'] = array(array('gt', $beginTime), array('lt', $endTime));
		$where['is_cancel'] = 0;
		$orderInfo = M('presale_change')->where($where)->field('change_id, change_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
	
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('presale_change')->where($where)->save($data);
		
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}
	
	// 配送端取消调换货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function deliverCancelChangeAction()
	{
		// 订单ID
		$change_id = intval($_REQUEST['orderId']);
		
	
		
		// 搜索条件
		$where['change_id'] = $change_id;
		
		$where['is_cancel'] = 0;
		$orderInfo = M('presale_change')->where($where)->field('change_id, change_code')->find();
		if(empty($orderInfo)){ echo json_encode(array('error' => '2', 'msg' => '不能取消')); exit; }
		
		
		// 取消订单
		$data['is_cancel'] = 1;
		$data['cancel_time'] = time();
		$flag = M('presale_change')->where($where)->save($data);
		
		$whereA["order_id"]=$change_id;
		$whereA["order_type"]=3;
	    $whereA["order_state"]=2;
	    $dataA["order_state"]=3;
		M("admin_order")->where($whereA)->save($dataA);
		// 返回结果
		if(empty($flag))
		{
			echo json_encode(array('error' => '1', 'msg' => '取消失败'));
		}
		else
		{
			echo json_encode(array('error' => '-1', 'msg' => '取消成功'));
		}
	}

}

/*************************** end ************************************/