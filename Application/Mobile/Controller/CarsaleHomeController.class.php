<?php

/*******************************************************************
 ** 文件名称: CarsaleHomeController.class.php
 ** 功能描述: 经销商车销首页接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleHomeController extends Controller {

	// 业务员欢迎页面
	// 创建人员: richie
	// 创建日期: 2016-08-16
	public function indexAction()
	{
		// 经销商业务员ID
		$staff_id = I("userId");
		$org_parent_id = I("companyId");
		
		// 今日时间
		$today = date('Y-m-d', time());
		$begin_time = strtotime($today . " 00:00:00");
		$end_time = strtotime($today . " 23:59:59");
		
		// 赊欠订单
		$owedOrderList = array();
		
		// 查询今日车销单
		$where['staff_id'] = $staff_id;
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt', $begin_time),array('lt', $end_time));
		$order_list = M('carsale_orders')->field('order_code, order_real_money, order_total_money')->where($where)->select();
		
		// 今日销售情况
		$saleOrderRealMoney = 0;
		$saleOrderTotalMoney = 0;
		foreach($order_list as $val)
		{
			$order_real_money = floatval($val['order_real_money']);
			$order_total_money = floatval($val['order_total_money']);
			$saleOrderRealMoney += $order_real_money;
			$saleOrderTotalMoney += $order_total_money;
			
			// 车销欠款订单
			if($order_total_money > $order_real_money){ $owedOrderList[] = $val; }
		}
		$data['saleOrderNum'] = count($order_list);
		$data['saleOrderRealMoney'] = $saleOrderRealMoney;
		$data['saleOrderTotalMoney'] = $saleOrderTotalMoney;
		
		// 今日赊欠情况
		$owedOrderMoney = 0;
		$owedOrderFinishOrderNum = 0;
		foreach($owedOrderList as $val)
		{
			$order_real_money = floatval($val['order_real_money']);
			$order_total_money = floatval($val['order_total_money']);
			$order_banlance_money = $order_total_money - $order_real_money;
			$owedOrderMoney += $order_banlance_money;
			
			// 今日清欠情况
			$where2['orderid'] = $val['order_code'];
			$where2['staff_id'] = $staff_id;
			$where2['addtime'] = array(array('gt', $begin_time),array('lt', $end_time));
			$price = M('carsale_orders_qiankuan')->where($where2)->sum('price');
			$owedOrderMoney -= floatval($price);
			
			// 今天下单，今天清欠完毕
			if($order_banlance_money <= $price){ $owedOrderFinishOrderNum++; }	
		}
		$data['owedOrderNum'] = count($owedOrderList) - $owedOrderFinishOrderNum;
		$data['owedOrderMoney'] = $owedOrderMoney;
		//改成销售情况
		$data['owedOrderNum']=$data['saleOrderNum'];
        $data['owedOrderMoney']=$data['saleOrderTotalMoney'];
		// 终端退货情况
		$where = array();
		$where['org_parent_id'] = $org_parent_id;
		$where['staff_id'] = $staff_id;
		$where['create_time'] = array(array('gt', $begin_time),array('lt', $end_time));
		$return_order_list = M('carsales_return')->field('real_money, total_money')->where($where)->select();
		$returnOrderMoney = 0;
		foreach($return_order_list as $val)
		{
			$returnOrderMoney += floatval($val['real_money']);
		}
		$data['returnOrderNum'] = count($return_order_list);
		$data['returnOrderMoney'] = $returnOrderMoney;
		
		// 调换货情况
		$where = array();
		$where['staff_id'] = $staff_id;
		$where['org_parent_id'] = $org_parent_id;
		$where['create_time'] = array(array('gt', $begin_time),array('lt', $end_time));
		$change_orders_list = M('carsales_change')->field('total_money')->where($where)->select();
		$changeOrderMoney = 0;
		foreach($change_orders_list as $val)
		{
			$changeOrderMoney += floatval($val['total_money']);
		}
		$data['changeOrderNum'] = count($change_orders_list);
		$data['changeOrderMoney'] = $changeOrderMoney;
		
		// 店铺清欠情况
		$where = array();
		$where['staff_id'] = $staff_id;
		$where['addtime'] = array(array('gt', $begin_time),array('lt', $end_time));
		$repay_order_list = M('carsale_orders_qiankuan')->field('price')->where($where)->select();
		$repayOrderMoney = 0;
		foreach($repay_order_list as $val)
		{
			$repayOrderMoney += floatval($val['price']);
		}
		$data['repayOrderNum'] = count($repay_order_list);
		$data['repayOrderMoney'] = $repayOrderMoney;

		// 返回
		// Array
		// 	(
		// 	    [saleOrderNum] => 2			车销订单数量
		// 	    [saleOrderRealMoney] => 38  销售实收
		// 	    [saleOrderTotalMoney] => 76 销售总额
		// 	    [owedOrderNum] => 2
		// 	    [owedOrderMoney] => 98      赊欠金额
		// 	    [returnOrderNum] => 1
		// 	    [returnOrderMoney] => 25    店铺退货金额
		// 	    [changeOrderNum] => 1
		// 	    [changeOrderMoney] => 50    调换货金额
		// 	    [repayOrderNum] => 1
		// 	    [repayOrderMoney] => 2      清欠金额
		echo json_encode(array('error' => -1, 'msg' => '成功', 'data' =>$data));
	}

    // 登录业务员设置
	// 创建人员: richie
	// 创建日期: 2016-08-16
	public function userconfigAction()
    {
		// 经销商ID和业务员ID
		$staff_id = intval($_REQUEST["userId"]);
		$org_parent_id = intval($_REQUEST["companyId"]);
		
		$data['userid'] = $staff_id;
		
		// 行动轨迹设置
		$where["saleman_id"] = $staff_id;
		$where["org_parent_id"] = $org_parent_id;
		$timeRes = M('org_action_config')->field("begin_time,end_time,interval")->where($where)->find();
		$data["uploadStartTime"] = !empty($timeRes["begin_time"]) ? $timeRes["begin_time"] : '9:00';
		$data["uploadEndTime"]   = !empty($timeRes["end_time"]) ? $timeRes["end_time"] : '17:00';
		$data['uploadRate'] = !empty($timeRes["interval"]) ? intval($timeRes["interval"]) : 30;

        // 店铺陈列
        $shopPic = M("customer_display_type")->field("sdt_id, sdt_name")->where("org_parent_id=" . $org_parent_id)->select();
		if(empty($shopPic))
		{
            $data["shopPic"] = array();
        }
		else
		{
			for ($i = 0; $i < count($shopPic); $i++)
			{
				$data["shopPic"][$i]["id"] = $shopPic[$i]["sdt_id"];
				$data["shopPic"][$i]["name"] = $shopPic[$i]["sdt_name"];
			}
        }
		
		// 人员所属店铺id列表
        $shopList = M("org_staff_customer")->field("shop_id id")->where("staff_id=" . $staff_id)->select();
        $data["shopIds"] = array();
        for ($i = 0; $i < count($shopList); $i++)
		{
            $data["shopIds"][$i] = $shopList[$i]["id"];
        }
		
		// 返回数据	
        if($data)
		{
            echo json_encode(array("error" => -1, "msg" => "查询成功", "data" => $data));
        }
		else
		{
            echo json_encode(array("error" => 2, "msg" => "请求失败", "data" => array()));
        }
    }
	
	// 签到次数
	// 创建人员: richie
	// 创建日期: 2016-08-27
    public function signCountAction()
	{
		$userId = intval($_REQUEST['userId']);
		$count = D()->query("select count(*) as num from zdb_org_staff_signin where staff_id={$userId} and date_format(from_unixtime(now_time),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d') ");
		if($count)
		{
			echo json_encode(array('error' => '-1', 'msg' => $count[0]['num']));
        }
		else
		{
			echo json_encode(array('error' => '2', 'msg' => 0));
		}
    }

	// 员工签到
	// 创建人员: richie
	// 创建日期: 2016-08-16
    public function signInAction()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3000000;
		$upload->exts = array();
		$upload->savePath = 'signin/';
		$upload->rootPath = './Public/Uploads/';
		$info = $upload->upload();
		if(!$info) { echo json_encode(array('error' => '1', 'msg' => '图片上传失败')); exit; }
		
		// 添加签到记录
		$data["staff_id"] = intval($_REQUEST["userId"]);
		$data["today"] = time();
		$data['org_parent_id'] = intval($_REQUEST["org_parent_id"]);
		$data["longitude"] = $_REQUEST["longitude"];
		$data["dimension"] = $_REQUEST["latitude"];
		$data['address'] = $_REQUEST["address"];
		$data["now_time"] = time();
		$data["img"] = $info['picture']['savepath'] . $info['picture']['savename'];
		$signIn = M("org_staff_signin")->data($data)->add();

		// 返回
		if(!empty($signIn))
		{
			echo json_encode(array('error' => '-1', 'msg' => '创建成功'));
		}
		else
		{
			echo json_encode(array('error' => '2', 'msg' => '失败'));
		}
    }

    // 添加行动轨迹
	// 创建人员: richie
	// 创建日期: 2016-08-27
    public function addPosAction()
	{
		$data['saleman_id'] = intval($_REQUEST["userId"]);
		$data['today'] = date('Ymd');
		$data['org_parent_id'] = intval($_REQUEST["org_parent_id"]);
		$data['longitude'] = I("longitude");
		$data['dimension'] = I("latitude");
		$data['now_time'] = time();
		$data['img'] = '';
		M('org_action_position')->add($data);
        echo json_encode(array('error' => '-1', 'msg' => '上传成功'));
    }

}

/*************************** end ************************************/