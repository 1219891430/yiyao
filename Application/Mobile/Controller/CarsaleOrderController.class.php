<?php

/*******************************************************************
 ** 文件名称: CarsaleOrderController.class.php
 ** 功能描述: 经销商车销销售,终端退货, 调换货接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleOrderController extends Controller {

	// 提交车销单
	// 创建人员: richie
	// 创建日期: 2016-08-04\
	/**
	 * @name 提交车销单
	 * @method post
	 * @uri /index.php/Mobile/CarsaleOrder/add 
	 * @param shopId int  商铺id
	 * @param userId int  业务员id 
	 * @param orderWay int  结算方式 
	 * @param remark string  订单备注 
	 * @param goodsList json  商品数据 
	 * @param cuxiaoList json  促销商品 
	 * @param companyId int  经销商ID
	 * @param money float  订单实收金额
	 * @response id int 订单id
	 */
	public function addAction()
	{
		// 用户提交数据
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay');			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goodList	= $_REQUEST["goodsList"];	// 商品数据
		$cxpList	= $_REQUEST["cuxiaoList"];		// 促销商品
		
		// 经销商ID
		$org_parent_id = I('companyId');

		// 订单实收金额
		$order_real_money = I('money');

		// 商品信息
		$goods_list = json_decode($goodList,true);
		$cxp_list = json_decode($cxpList, true);
		// $goodList 商品信息
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['unit_name']				// 单位名称
		
		// 商品信息合并
		$allGoods = array();
		foreach($goods_list as $item)
		{
			$item['cuxiao'] = 0;
			$allGoods[] = $item;
		}
		foreach($cxp_list as $item)
		{
			$item['singleprice'] = 0;
			$item['cuxiao'] = 1;
			$allGoods[] = $item;
		}

		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

		// 添加订单
		$order_info = array();
		$order_info['order_code'] = create_uniqid_code('O', $staff_id);
		$order_info['staff_id'] = $staff_id;
		$order_info['cust_id'] = $cust_id;
		$order_info['cust_name'] = $shopInfo['cust_name'];
		$order_info['cust_contact'] = $shopInfo['contact'];
		$order_info['cust_address'] = $shopInfo['address'];
		$order_info['cust_tel'] = $shopInfo['telephone'];
		$order_info['order_real_money'] = $order_real_money; // 订单实收金额
		$order_info['order_remark'] = $remark;
		$order_info['order_way'] = $order_way;
		$flag = D('CarsaleOrder')->addOrder($staff_id, $org_parent_id, $cust_id, $order_info, $allGoods);

		// 减少车销库存
		$goods_list = array();
		foreach($allGoods as $k=>$item)
		{
			$goods_list[$k]['staff_id'] =$staff_id;
			$goods_list[$k]['org_parent_id'] =$org_parent_id;
			$goods_list[$k]['cv_id'] = $item['cv_id'];
			$goods_list[$k]['goods_id'] = $item['goods_id'];
			$goods_list[$k]['goods_num'] = $item['number'];
				
			
		}
		$CarportInfoModel = new \Common\Model\CarportInfoModel();
		$CarportInfoModel->updateCarInfo($goods_list, "业务员车销下单", "del",4);

		// 添加店铺维护记录
		D('CarsaleShop')->addPosition($org_parent_id, $staff_id, $cust_id, 2);

		// 返回
		if($flag)
		{
			echo json_encode(array('error' => -1, 'msg' => '添加成功', 'id'=>$flag));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '添加失败'));
		}
    }
	
	// 提交终端退货
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function returnAction()
	{
		// 用户提交数据
		$cust_id	= I('shopId');				// 商铺id
		$staff_id	= I('userId');				// 业务员id
		$order_way	= I('orderWay', 0);			// 结算方式
		$remark		= I("remark");				// 订单备注
		$goodList	= $_REQUEST["goodsList"];	// 商品数据
		$org_parent_id = I('companyId');		// 经销商ID

		// 商品信息
		$goods_list = json_decode($goodList,true);
		// $goodList 商品信息
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['unit_name']				// 单位名称
		
		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

		// 添加订单
		$order_info = array();
		$order_info['return_code'] = create_uniqid_code('R', $staff_id);
		$order_info['cust_name'] = $shopInfo['cust_name'];
		$order_info['cust_contact'] = $shopInfo['contact'];
		$order_info['cust_address'] = $shopInfo['address'];
		$order_info['cust_tel'] = $shopInfo['telephone'];
		$order_info['order_remark'] = $remark;
		$order_info['order_way'] = $order_way;
		$flag = D('CarsaleOrder')->addReturnOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);

		// 增加车存
		$aReturnGoods = array();
		foreach($goods_list as $k=>$item)
		{
			
			$aReturnGoods[$k]['cv_id'] = $item['cv_id'];
			$aReturnGoods[$k]['goods_id'] = $item['goods_id'];
			$aReturnGoods[$k]['goods_num'] = $item['number'];
			$aReturnGoods[$k]['staff_id']=$staff_id;
			$aReturnGoods[$k]['org_parent_id']=$org_parent_id;
		}
		
		
		$CarportInfoModel = new \Common\Model\CarportInfoModel();
		$CarportInfoModel->updateCarInfo($aReturnGoods, "业务员终端退货", "add",2);

		// 添加店铺维护记录
		D('CarsaleShop')->addPosition($org_parent_id, $staff_id, $cust_id, 4);	

		// 返回
		if($flag)
		{
			echo json_encode(array('error' => -1, 'msg' => '添加成功', 'id'=>$flag));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '添加失败'));
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
		$org_parent_id = I('companyId');		// 经销商ID
		
		// 商品信息
		$goods_in = json_decode($goods_in,true);
		$goods_out = json_decode($goods_out,true);
		//$temp['cv_id']			// 货品ID
		//$temp['goods_id']			// 商品ID
		//$temp['goods_name']		// 商品名称
		//$temp['goods_spec']		// 商品规格
		//$temp['singleprice']		// 销售价格
		//$temp['number']			// 销售数量
		//$temp['unit_name']		// 数量单位

		// 通过cust_id查找商铺的信息
		$shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();
		
		// 添加调换货单
		$order_info = array();
		$order_info['change_code'] = create_uniqid_code('C', $staff_id);
		$order_info['cust_name'] = $shopInfo['cust_name'];
		$order_info['cust_contact'] = $shopInfo['contact'];
		$order_info['cust_address'] = $shopInfo['address'];
		$order_info['cust_tel'] = $shopInfo['telephone'];
		$order_info['order_remark'] = $remark;
		$order_info['order_way'] = $order_way;
		$flag = D('CarsaleOrder')->addChangeOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_in, $goods_out);

		// 调回商品
		$goods_list = array();
		foreach($goods_in as $k=>$item)
		{
			$goods_list[$k]['staff_id']=$staff_id;
			$goods_list[$k]['org_parent_id']=$org_parent_id;
			$goods_list[$k]['cv_id'] = $item['cv_id'];
			$goods_list[$k]['goods_id'] = $item['goods_id'];
			$goods_list[$k]['goods_num'] = $item['number'];
			
		}
		
		$CarportInfoModel = new \Common\Model\CarportInfoModel();
		$CarportInfoModel->updateCarInfo($goods_list, "业务员调入商品", "add",2);
		// 调出商品
		$goods_list = array();
		foreach($goods_out as $item)
		{
			$goods_list[$k]['staff_id']=$staff_id;
			$goods_list[$k]['org_parent_id']=$org_parent_id;
			$goods_list[$k]['cv_id'] = $item['cv_id'];
			$goods_list[$k]['goods_id'] = $item['goods_id'];
			$goods_list[$k]['goods_num'] = $item['number'];
			
		}
		
        $CarportInfoModel = new \Common\Model\CarportInfoModel();
		$CarportInfoModel->updateCarInfo($goods_list, "业务员调出商品", "del",2);
		
		// 添加店铺维护记录
		D('CarsaleShop')->addPosition($org_parent_id, $staff_id, $cust_id, 3);		
		
		// 返回
		if($flag)
		{
			echo json_encode(array('error' => -1, 'msg' => '调换货成功', 'id'=>$flag));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '调换货失败'));
		}
	}
	
	// 返回车销单详细, 打印使用
	public function orderDetailAction()
	{
		// 经销商ID和业务员ID和订单ID
		//$org_parent_id = I('companyId');
		//$staff_id = I('userId');
		$order_id = I('orderId');
		
		// 订单信息
		$result = M('carsale_orders')->where("order_id = $order_id")->find();
		$orderInfo['order_id'] = $result['order_id'];
		$orderInfo['order_code'] = $result['order_code'];
		$orderInfo['order_total_money'] = $result['order_total_money'];
		$orderInfo['order_real_money'] = $result['order_real_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);
		$orderInfo['cust_name'] = $result['cust_name'];

		// 订单商品信息
		$orderGoods = array();
		$results = M('carsale_orders_goods')->where("order_id = $order_id")->select();
		foreach($results as $val)
		{
			$temp = array();
			$temp['goods_name'] = $val['good_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['number'] = $val['number'];
			$temp['goods_unit'] = $val['unit_name'];
			$temp['singleprice'] = $val['singleprice'];
			$orderGoods[] = $temp;
		}
		
		// 返回
		if(!empty($orderInfo) && !empty($orderInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>array('orderInfo'=>$orderInfo,'orderGoods'=>$orderGoods)));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}
	
	// 返回终端退货详细, 打印使用
	public function returnDetailAction()
	{
		// 经销商ID和业务员ID和订单ID
		//$org_parent_id = I('companyId');
		//$staff_id = I('userId');
		$order_id = I('orderId');
		
		// 订单信息
		$result = M('carsales_return')->where("return_id = $order_id")->find();
		$orderInfo['order_id'] = $result['return_id'];
		$orderInfo['order_code'] = $result['return_code'];
		$orderInfo['order_total_money'] = $result['total_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);
		$orderInfo['cust_name'] = $result['cust_name'];
		
		// 订单商品信息
		$orderGoods = array();
		$results = M('carsales_return_goods')->where("return_id = $order_id")->select();
		foreach($results as $val)
		{
			$temp = array();
			$temp['goods_name'] = $val['goods_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['number'] = $val['goods_num'];
			$temp['goods_unit'] = $val['goods_unit'];
			$temp['singleprice'] = $val['goods_money'];
			$orderGoods[] = $temp;
		}

		// 返回
		if(!empty($orderInfo) && !empty($orderInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>array('orderInfo'=>$orderInfo,'orderGoods'=>$orderGoods)));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}
	
	// 返回调换货详细, 打印使用
	public function changeDetailAction()
	{
		// 经销商ID和业务员ID和订单ID
		//$org_parent_id = I('companyId');
		//$staff_id = I('userId');
		$order_id = I('orderId');
		
		// 订单信息
		$result = M('carsales_change')->where("change_id = $order_id")->find();
		$orderInfo['order_id'] = $result['change_id'];
		$orderInfo['order_code'] = $result['change_code'];
		$orderInfo['order_total_money'] = $result['total_money'];
		$orderInfo['create_time'] = date('Y-m-d H:i', $result['create_time']);
		$orderInfo['cust_name'] = $result['cust_name'];
		//$orderInfo['telphone'] = $result['cust_tel'];
		//$orderInfo['address'] = $result['cust_address'];
		
		// 订单商品信息
		$goodsin = array();
		$goodsout = array();
		$results = M('carsales_change_goods')->where("change_id = $order_id")->select();
		foreach($results as $val)
		{
			$is_change_in = $val['is_change_in'];
		
			$temp = array();
			$temp['goods_name'] = $val['goods_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['number'] = $val['number'];
			$temp['goods_unit'] = $val['unit_name'];
			$temp['singleprice'] = $val['singleprice'];

			if($is_change_in == 1)
			{
				$goodsin[] = $temp;
			}
			else
			{
				$goodsout[] = $temp;
			}			
		}

		// 返回
		if(!empty($orderInfo) && !empty($orderInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>array('orderInfo'=>$orderInfo,'goodsin'=>$goodsin,'goodsout'=>$goodsout)));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}

	// 返回经销商信息, 打印使用
	public function orgAction()
	{
		// 经销商ID
		$org_parent_id = I('companyId');
		$orgInfo = M('org_info')->field('org_name, telephone')->where("org_id = $org_parent_id")->find();
		$orgInfo['message'] = '农乐汇B2B商城：b2b.nlh360.com';
		
		// 业务员名称和手机号
		$staff_id = I('userId');
		$staff_info = M('org_staff')->where('staff_id = ' . $staff_id)->field('staff_name, mobile')->find();
		$orgInfo['true_name'] = $staff_info['staff_name'];
		$orgInfo['mobile'] = $staff_info['mobile'];
		
		// 返回
		if(!empty($orgInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data'=>$orgInfo));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
	}

}

/*************************** end ************************************/