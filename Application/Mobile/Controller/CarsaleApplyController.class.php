<?php

/*******************************************************************
 ** 文件名称: CarsaleApplyController.class.php
 ** 功能描述: 经销商车销申请接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleApplyController extends Controller {

	// 业务员申请车存第一步, 获取仓库商品列表
    public function applyGoodsByDepotAction()
    {
		// 获取经销商ID和业务员ID
		$staff_id = I('userId');
		$org_parent_id = I('companyId');
		
		// 查询经销商库存商品
		$results = M('depot_stock')->where("org_parent_id = $org_parent_id")->order("goods_id asc")->getField('goods_id', true);

		// 返回数据
		if(!empty($results))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $results));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
    }

	// 业务员申请车存第二步, 获取商品库存信息
    public function getApplyPageInfoAction()
    {
		// 经销商ID和商品ID
		$goods_id = I("productId");
		$org_parent_id = I("companyId");
		
		// 查询当前库存
		$where['goods_id'] = $goods_id;
		$where['org_parent_id'] = $org_parent_id;
		$small_stock = M('depot_stock')->where($where)->getField('small_stock');
		if(empty($small_stock)){ $small_stock = 0; }
		
		// 转换成整箱库存
		$data = getGoodsUnitString($goods_id, $small_stock);

		// 返回
		echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $data));
    }
	
    // 业务员申请车存第三步, 业务员提交申请车存
    public function mApplyAddAction()
    {
		// 用户提交数据
		$staff_id = I("userId",0);
		$org_parent_id = I("companyId", 0);
		$remark = I("remark",'');
		
		// 商品信息
		$goodsList = json_decode($_REQUEST["goodsList"], true);
		// $goodList 商品信息
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['unit_name']				// 单位名称
		
		// 经销商所在的仓库
		$repertory_id = M('depot_org')->where("org_parent_id = $org_parent_id")->order('repertory_id asc')->getField('repertory_id');
		
		// 车存申请商品
		$goodsData = array();
		foreach($goodsList as $item)
		{
			$temp = array();
			$temp['apply_id'] = 0;
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_spec'];
			$temp['apply_price'] = $item['singleprice'];
			$temp['apply_num'] = $item['number'];
			$temp['goods_unit'] = $item['unit_name'];
			$goodsData[] = $temp;
		}
		
		// 增加车存
		$data['apply_code'] = create_uniqid_code('CA', $staff_id);
		$data['staff_id'] = $staff_id;
		$data['org_parent_id'] = $org_parent_id;
		$data['repertory_id'] = intval($repertory_id);
		$data['apply_status'] = 1; // 1 已提交 2 已审核 3 已出库
		$data['apply_total_money'] = 0;
		$data['apply_remark'] = $remark;
		$data['is_admin_order'] = 0;
		$data['add_id'] = 0;
		$data['add_time'] = time();
		$data['check_id'] = 0;
		$data['check_time'] = 0;
		$data['accept_time'] = 0;
		$data['is_cancel'] = 0;
		$data['cancel_time'] = 0;
		$apply_id = M('carsale_apply')->add($data);
		
		// 增加车存商品
		foreach($goodsData as &$item){ $item['apply_id'] = intval($apply_id); }
		$flag = M('carsale_apply_goods')->addAll($goodsData);
		
		// 返回结果
        if($apply_id > 0 && !empty($flag))
		{
			echo json_encode(array('error' => -1, 'msg' => '申请成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '申请失败'));
		}
    }

    // 业务员收货第一个步, 查询待收货的车存申请单列表
    public function getNotReceivingAction()
    {
		// 查询已发货的车销申请
		$where["is_cancel"] = 0;
		$where["apply_status"] = 2;
		$where["staff_id"] = I("userId",0);
		$where["org_parent_id"] = I("companyId",0);
        $aInfo = M("carsale_apply")->field("apply_id, apply_code, add_time")->where($where)->select();
		foreach($aInfo as $k=>$val){ $aInfo[$k]["add_time"]=date("Y-m-d H:i", $val["add_time"]); }
        
		// 返回
		if(!empty($aInfo))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $aInfo));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
    }

    // 业务员收货第二个步, 查询待收货的车存申请单详细
    public function getApplyDetailAction()
    {
		// 查询车存单详细
		$apply_id = I("orderId");
		$aGoods = M("carsale_apply_goods")->field("goods_id, goods_name,apply_num,apply_price,goods_unit")->where("apply_id = $apply_id")->select();

		// 返回
		if(!empty($aGoods))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $aGoods));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
    }

    // 业务员收货第三个步, 确认收货
    public function submitApplyGoodsAction()
    {
		// 请求参数, 经销商ID, 车存申请单ID, 业务员ID
		$org_parent_id = I("companyId");
		$apply_id = I("orderId");
		$staff_id = I("userId");
		
		// 状态是否是已收货
		$where['apply_id'] = $apply_id;
		$where['staff_id'] = $staff_id;
		$where['org_parent_id'] = $org_parent_id;
		$apply_info = M('carsale_apply')->field('apply_id, apply_status')->where($where)->find();
		$applay_status = intval($apply_info['apply_status']);
		if($applay_status == 4) { echo json_encode(array('error' => '1', 'msg' => '已经收货')); exit; }

		// 修改为收货状态
		$data["apply_status"] = 4;
		$data["accept_time"] = time();
		$flag = M('carsale_apply')->where("apply_id = $apply_id")->save($data);
		
		// 添加车存库存
		$goods_list = array();
		$results = M('carsale_apply_goods')->where("apply_id = $apply_id")->order("cv_id asc")->select();
		foreach($results as $item)
		{
			$temp = array();
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_num'] = $item['apply_num'];
			$temp['org_parent_id'] = $org_parent_id;
			$temp['staff_id'] =$staff_id;
			$goods_list[] = $temp;
		}
		//D('CarsaleStock')->updateCarInfo($org_parent_id, $staff_id, $goods_list, 1);
		
		
		$CarportInfoModel = new \Common\Model\CarportInfoModel();
		$CarportInfoModel->updateCarInfo($goods_list, "手机端车销申请", "add",1);
		
		// 返回
		if(!empty($flag))
		{
			echo json_encode(array('error' => -1, 'msg' => '收货成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '收货失败'));
		}
    }

    // 业务员收货第三个步, 取消收货
    public function cancelReceivingAction()
    {
		$where['apply_id'] = I("orderId", 0);
		
		$data["is_cancel"] = 1;
		$data["cancel_time"] = time();
		$flag = M("car_apply")->where($where)->save($data);
	
		// 返回
		if($flag)
		{
			echo json_encode(array('error' => -1, 'msg' => '收货成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '收货失败'));
		}
    }
	
	// 业务员实时车存接口
    public function getCarportInfoAction()
    {
		// 业务员ID
		$where["staff_id"] = I("userId",6);
		$where["org_parent_id"] = I("companyId",1);
		$results = M("carsale_stock")->where($where)->order('goods_id asc')->select();
		
		// 商品单位转换
		$goodsList = array();
        foreach($results as $k=>$val)
        {
			$temp = array();
			$temp['goods_id'] = $val['goods_id'];
			$temp['goods_name'] = $val['goods_name'];
			$temp['goods_spec'] = $val['goods_spec'];
			$temp['num_string'] = $val['num_string'];
			$goodsList[] = $temp;
        }

		// 返回数据
		if(!empty($goodsList))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' =>$goodsList));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
    }
	
    // 车存退到仓库
    public function addCarToDepotAction()
    {
		// 提交数据
        $staff_id = I("userId", 0);
        $org_parent_id = I("companyId", 0);

		// 商品信息
		$goodsList = json_decode($_REQUEST["goodsList"], true);
		// $goodList 商品信息
		// $temp['cv_id']					// 货品ID
		// $temp['goods_id']				// 商品ID
		// $temp['goods_name']				// 商品名称
		// $temp['goods_spec']				// 商品规格
		// $temp['singleprice']				// 商品价格
		// $temp['number']					// 数量
		// $temp['unit_name']				// 单位名称
		
		// 经销商所在的仓库
		$repertory_id = M('depot_org')->where("org_parent_id = $org_parent_id")->order('repertory_id asc')->getField('repertory_id');
		
		// 商品信息
		$goodsData = array();
		foreach($goodsList as $item)
		{
			$temp = array();
			$temp['return_id'] = 0;
			$temp['cv_id'] = $item['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_spec'];
			$temp['goods_money'] = $item['singleprice'];
			$temp['goods_num'] = $item['number'];
			$temp['goods_unit'] = $item['unit_name'];
			$goodsData[] = $temp;
		}

		// 添加退货单
		$data['return_code'] = create_uniqid_code('CB', $staff_id);
		$data['staff_id'] = $staff_id;
		$data['org_parent_id'] = $org_parent_id;
		$data['repertory_id'] = intval($repertory_id);
		$data['return_status'] = 1;
		$data['return_remark'] = '';
		$data['is_admin_order'] = 0;
		$data['add_id'] = 0;
		$data['add_time'] = time();
		$data['check_id'] = 0;
		$data['check_time'] = 0;
		$data['accept_time'] = 0;
		$data['is_cancel'] = 0;
		$data['cancel_time'] = 0;
		$return_id = M('carsale_return_stock')->add($data);
		
		// 添加退库商品
		foreach($goodsData as &$item){ $item['return_id'] = $return_id; }
		$flag = M('carsale_return_stock_goods')->addAll($goodsData);
		
		// 返回数据
		if(!empty($flag))
		{
			echo json_encode(array('error' => -1, 'msg' => '提交成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '提交失败'));
		}
    }

    // 一键全部退库, 车存为负数不能退库
    public function addAllCarToDepotAction()
    {
		// 业务员ID和经销商ID
		$staff_id = I("userId", 0);
		$org_parent_id = I("companyId", 0);

		// 经销商所在的仓库
		$repertory_id = M('depot_org')->where("org_parent_id = $org_parent_id")->order('repertory_id asc')->getField('repertory_id');
		
		// 业务员获取当前有效车存, 车存不能为零
		$where['staff_id'] = $staff_id;
		$where['org_parent_id'] = $org_parent_id;
		$where['goods_num'] = array('gt', 0);
		$results = M("carsale_stock")->where($where)->select();

		// 整理商品
		$goodsData = array();
		foreach($results as $item)
		{
			// 查询商品最小单位
			$cv_info = M('goods_product')->where("goods_id = " .  $item['goods_id'] . " AND goods_unit_type = 1")->find();
		
			$temp = array();
			$temp['return_id'] = 0;
			$temp['cv_id'] = $cv_info['cv_id'];
			$temp['goods_id'] = $item['goods_id'];
			$temp['goods_name'] = $item['goods_name'];
			$temp['goods_spec'] = $item['goods_spec'];
			$temp['goods_money'] = 0;
			$temp['goods_num'] = $item['goods_num'];
			$temp['goods_unit'] = $cv_info['goods_unit'];
			$goodsData[] = $temp;
		}

		// 退库单
		$data['return_code'] = create_uniqid_code('CB', $staff_id);
		$data['staff_id'] = $staff_id;
		$data['org_parent_id'] = $org_parent_id;
		$data['repertory_id'] = intval($repertory_id);
		$data['return_status'] = 1;
		$data['return_remark'] = '';
		$data['is_admin_order'] = 0;
		$data['add_id'] = 0;
		$data['add_time'] = time();
		$data['check_id'] = 0;
		$data['check_time'] = 0;
		$data['accept_time'] = 0;
		$data['is_cancel'] = 0;
		$data['cancel_time'] = 0;
		$return_id = M('carsale_return_stock')->add($data);
		
		// 添加退库商品
		foreach($goodsData as &$item){ $item['return_id'] = $return_id; }
		$flag = M('carsale_return_stock_goods')->addAll($goodsData);
		
		// 返回数据
		if(!empty($flag))
		{
			echo json_encode(array('error' => -1, 'msg' => '提交成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '提交失败'));
		}
    }

}

/*************************** end ************************************/