<?php

/*******************************************************************
 ** 文件名称: DeliverApplyController.class.php
 ** 功能描述: 平台车销配送申请接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DeliverApplyController extends Controller {

	// 待收货的车存申请
	// 创建人员: richie
	// 创建日期: 2016-08-15
	public function getNotReceivingAction()
    {
        $where["staff_id"] = I("userId",0); // 配送人员ID
        $where["apply_status"] = 2; // 审核通过
        $aInfo = M("car_apply")->field("apply_id, add_time")->where($where)->select();
		foreach($aInfo as $k=>$val) { $aInfo[$k]["add_time"]=date("Y-m-d H:i",$val["add_time"]); }
        
		// 返回数据
		if(!empty($aInfo))
		{
			echo json_encode(array('error' => '-1', 'msg' => '查询成功', 'data' =>$aInfo));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '暂无数据'));
		}
    }
 
    // 待收货的车存申请详细信息
	// 创建人员: richie
	// 创建日期: 2016-08-15
    public function getApplyDetailAction()
    {
		// 查询车存单详细
		$where["apply_id"] = I("orderId");
		$aGoods = M("car_apply_goods")->field("goods_name, goods_sepc, apply_num, apply_price, goods_unit")->where($where)->select();
		
		// 返回数据
		if(!empty($aGoods))
		{
			echo json_encode(array('error' => '-1', 'msg' => '查询成功', 'data' =>$aGoods));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '暂无数据'));
		}
    }

	// 收货
	// 创建人员: richie
	// 创建日期: 2016-08-15
    public function submitApplyGoodsAction()
    {
		// 车存申请单编号, 配送人员ID
		$apply_id = I("orderId");
		$staff_id = I("userId");
		
		// 查询该车存申请单, 状态是否是已收货
		$where['apply_id'] = $apply_id;
		$where['staff_id'] = $staff_id;
		$applay_status = M('car_apply')->field('apply_id,apply_status')->where($where)->getField('apply_status');
		//if(intval($applay_status) == 3) { echo json_encode(array('error' => '2', 'msg' => '已经收货')); exit; }

		// 修改为收货状态
		$data["apply_status"] = 3;
		$data["accept_time"] = time();
		$flag = M('car_apply')->where("apply_id = $apply_id")->save($data);
		
		// 添加车存库存
		$goodsList = array();
		$results = M('car_apply_goods')->where("apply_id = $apply_id")->order("cv_id asc")->select();
		foreach($results as $val)
		{
			$temp = array();
			$temp['cv_id'] = $val['cv_id'];
			$temp['goods_id'] = $val['goods_id'];
			$temp['goods_num'] = $val['apply_num'];
			$goodsList[] = $temp;
		}
		D('DeliverStock')->updateCarInfo($staff_id, $goodsList, 1);
		
		// 返回成功
		if(!empty($flag))
		{
			echo json_encode(array('error' => '-1', 'msg' => '收货成功'));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '收货失败'));
		}
    }
	
	// 实时库存
	// 创建人员: richie
	// 创建日期: 2016-08-15
    public function getCarportInfoAction()
    {
		// 配送人员ID
		$where["staff_id"] = I("userId");
		
		// 查询实时车存
		$results = M("car_stock")->where($where)->order('goods_id asc')->select();
		
		// 数据格式化
		$goodsList = array();
        foreach($results as $val)
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
			echo json_encode(array('error' => '-1', 'msg' => '查询成功', 'data' =>$goodsList));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '查询失败'));
		}
    }
	
	// 实时库存商品详细
	public function getCarportGoodsAction()
	{
		// 商品ID和经销商ID
		$goods_id = intval($_REQUEST['goodsId']);
		$org_parent_id = intval($_REQUEST['companyId']);
        $user_id = intval($_REQUEST['userId']);

        if($org_parent_id>0){
            $where['org_parent_id'] = $org_parent_id;
        }
        else{
            $where['admin_id'] = $user_id;
            $where['goods_id'] = $goods_id;

            $res = M('admin_user')->alias('au')->field('au.depot_id,ds.org_parent_id')
                ->join('__DEPOT_STOCK__ ds on ds.depot_id=au.depot_id')
                ->where($where)
                ->find();
            if($res){
                $org_parent_id = $res['org_parent_id'];
            }
            //echo M('admin_user')->getLastSql();die;
            //$org_parent_id = M('depot_stock')->where("depot_id=".$depotId . " and goods_id=". $goods_id )->getField('org_parent_id');
            //dump($res);die;
        }

		// 查询各单位价格信息
		$goodsList = array();
        $where = array();
		$where['goods_id'] = $goods_id;
        $where['org_parent_id'] =  $org_parent_id;
		$results = M('org_goods_convert')->where($where)->order("goods_unit_type asc")->select();

		// 循环商品单位信息, 获取历史订单价格
		foreach($results as $val)
		{
			$temp2 = array();
			$temp2['cv_id'] = $temp_cv_id = $val['cv_id'];
			$temp2['goods_unit_type'] = $val['goods_unit_type'];
			$temp2['goods_unit'] = $val['goods_unit'];
			$temp2['unit_default'] = $val['unit_default'];
			
			// 商品价格
			$price = M('org_goods_convert')->where("cv_id = $temp_cv_id AND org_parent_id = $org_parent_id")->getField('goods_base_price');
			$temp2['goods_base_price'] = empty($price) ? 0 : $price;
			
			$goodsList[] = $temp2;
		}
		
		// 返回数据
		if(!empty($goodsList))
		{
			echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $goodsList));
		}
		else
		{
			echo json_encode(array('error' => '2', 'msg' => '查询失败'));
		}
	}
	
	// 退库
	// 创建人员: richie
	// 创建日期: 2016-08-15
    public function addCarToDepotAction()
    {
		// 配送人员ID
		$staff_id = I("userId", 0);
		
		// 退库商品: "goods_id":"2078","cv_id":"6122","goods_num":1
		$goodsList = json_decode($_REQUEST["goodsList"], true);
		
		// 配送人员所在的仓库
		$repertory_id = M("admin_user")->where("admin_id = $staff_id")->getField("depot_id");
		
		// 添加退库订单
		$data['return_code'] = create_uniqid_code('CB', $staff_id);
		$data['staff_id'] = $staff_id;
		$data['org_parent_id'] = 0;
		$data['repertory_id'] = intval($repertory_id);
		$data['return_status'] = 1; // 已提交
		$data['return_remark'] = '';
		$data['is_admin_order'] = 0;
		$data['add_id'] = 0;
		$data['add_time'] = time();
		$data['check_id'] = 0;
		$data['check_time'] = 0;
		$data['is_cancel'] = 0;
		$data['cancel_time'] = 0;		
		$return_id = M("car_return_stock")->add($data);
		
		// 查询商品信息
		$orderGoods = array();
		foreach($goodsList as $v)
		{
			// 商品ID和数量
			$goods_id = intval($v['goods_id']);
			$cv_id = intval($v['cv_id']);
			$goods_num = intval($v['goods_num']);
		
			// 查询商品信息
			$goodsInfo = M('goods_product')->where("cv_id = $cv_id")->find();
			
			// 商品信息
			$temp = array();
			$temp['return_id'] = $return_id;
			$temp['cv_id'] = $cv_id;
			$temp['goods_id'] = $goods_id;
			$temp['goods_name'] = $goodsInfo['goods_name'];
			$temp['goods_spec'] = $goodsInfo['goods_spec'];
			$temp['goods_num'] = $goods_num;
			$temp['goods_unit'] = $goodsInfo['goods_unit'];
			$temp['goods_money'] = 0;
			$orderGoods[] = $temp;
		}
		$flag = M("car_return_stock_goods")->addAll($orderGoods);

		// 返回结果
		if(!empty($return_id) && !empty($flag))
		{
			echo json_encode(array('error' => '-1', 'msg' => '退库成功'));
		}
		else
		{
			echo json_encode(array('error' => '1', 'msg' => '退库失败'));
		}
    }

}

/*************************** end ************************************/