<?php

/*******************************************************************
 ** 文件名称: PresaleShopController.class.php
 ** 功能描述: 平台采单终端店接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class PresaleShopController extends Controller {

	// 创建终端店
	// 创建人员: richie
	// 创建日期: 2016-08-12
	public function addShopAction()
	{
		// 采单人员
		$staff_id = intval($_REQUEST['userId']);
		  
		// 电话, 手机号
		$telephone = I("tel");
		
		// 查询店铺是否已经存在
		$shopInfo = M("customer_info")->where("telephone = '" . $telephone . "'")->find();
		if(!empty($shopInfo))
		{
			// 查询是否已经关联
			$shop_id = $shopInfo['cust_id'];
			$flag = M("admin_shop")->where("admin_id = $staff_id and shop_id = $shop_id")->find();
			if(empty($flag))
			{
				// 添加采单员与终端店关联
				$data2['admin_id'] = $staff_id;
				$data2['shop_id'] = $shop_id;
				$flag = M('admin_shop')->add($data2);
			}

			// 返回	
			if($flag)
			{
				echo json_encode(array('error' => '-1', 'msg' => '创建成功'));
			}
			else
			{
				echo json_encode(array('error' => '5', 'msg' => '创建失败'));
			}
			
			exit;
		}

		// 采单人员所在仓库
		$repertory_id = M('admin_user')->where("admin_id = $staff_id")->getField('depot_id');
		
		// 门头照片
		if($_FILES['picture']['name'])
		{
			$upload = new \Think\Upload(); //实例化上传类
			$upload->maxSize = 3000000; //上传大小
			$upload->exts = array();
			$upload->savePath = 'shop/';
			$upload->rootPath = './Public/Uploads/';
			$info = $upload->upload();
			if(!$info){ echo json_encode(array('error' => '1', 'msg' => '图片上传失败')); exit; }
			
			$head_pic = $info['picture']['savepath'] . $info['picture']['savename'];
		}
		else
		{
			$head_pic = '';
		}
		
		// 店铺信息
		$data['cust_name'] = I('name');
		$data['contact'] = I('boss');
		$data['telephone'] = $telephone; // 联系手机
		$data['loginname'] = $telephone; // 手机账号
		$data['loginpwd'] = md5(substr($telephone,-6)); // 初始化密码
		$data['repertory_id'] = $repertory_id;
		$data["head_pic"] = $head_pic;
		$data['province'] = I("province");
		$data['city'] = I("city");
		$data['district'] = I("district");
		$data['address'] = I("address");
		$data['longitude'] = I('longitude'); // 经度
		$data['dimension'] = I('latitude'); // 维度
		$data['staff_id'] = $staff_id; // 采单人员
		$data['reg_time'] = time();
		$data['is_check'] = 1;
		$data['is_close'] = 0;	
		$cust_id = M("customer_info")->add($data);
		if(empty($cust_id)){ echo json_encode(array('error' => '5', 'msg' => '创建失败')); exit; }		
					
		// 添加采单员与终端店关联
		$data2 = array();
		$data2['admin_id'] = $staff_id;
		$data2['shop_id'] = $cust_id;
		$flag = M('admin_shop')->add($data2);
		
		// 返回	
		if($flag)
		{
			echo json_encode(array('error' => '-1', 'msg' => '创建成功'));
		}
		else
		{
			echo json_encode(array('error' => '5', 'msg' => '创建失败'));
		}
	}

	// 终端店列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 采单人员ID
		$staff_id = intval($_REQUEST["userId"]);
        $order_from = I('orderFrom');           //订单来源1采单员2业务员
        if(empty($order_from)){ $order_from = 1; }

        if($order_from==2){
            // 联合查询店铺
            $shop = M('org_staff_customer')->alias('sc')->field('c.*')
                ->join('__CUSTOMER_INFO__ as c on sc.shop_id = c.cust_id')
                ->where("sc.staff_id = $staff_id")->order("c.cust_id asc")->select();
        }
        else{
            // 查询分配给采单人员的终端店
            $shop = M('admin_shop')->alias('a')->field('s.*')
                ->join('__CUSTOMER_INFO__ as s on a.shop_id = s.cust_id')
                ->where('a.admin_id = ' . $staff_id)->order('s.cust_id asc')->select();
        }

		// 店铺为空
		if(empty($shop)){ echo json_encode(array('error' => '1', 'msg' => "暂无店铺")); exit; }

		// 格式化店铺列表
		$shopList = array();
		for($i=0; $i<count($shop); $i++)
		{
		    
			$shopList[$i]['id'] = $shop[$i]["cust_id"];
			$shopList[$i]['name'] = $shop[$i]["cust_name"];
			$shopList[$i]['boss'] = $shop[$i]["contact"];
			$shopList[$i]['tel'] = $shop[$i]["telephone"];
			$shopList[$i]['province'] = $shop[$i]["province"];
			$shopList[$i]['city'] = $shop[$i]["city"];
			$shopList[$i]['district'] = $shop[$i]["district"];
			$shopList[$i]['address'] = $shop[$i]["address"];
			$shopList[$i]['companyId'] = 0; // 采单人员并不关心终端店属于哪个经销商
			$shopList[$i]['longitude'] = empty($shop[$i]["longitude"]) ? "0" : $shop[$i]["longitude"];
			$shopList[$i]['latitude'] = empty($shop[$i]["dimension"]) ? "0" : $shop[$i]["dimension"];
			$link = DOMAIN . "Public/Uploads/" . $shop[$i]['head_pic'];
			$shopList[$i]['picture'] = empty($shop[$i]["head_pic"]) ? "" : $link;
		}
		unset($shop);
		
		// 返回
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $shopList));
    }
	
	// 终端店详细
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function detailAction()
	{
		// 采单人员ID和终端店ID
		$userId = intval($_REQUEST['userId']);
		$shopId = intval($_REQUEST['shopId']);
		$order_from = I('orderFrom');
		if(empty($order_from)){
			$order_from=1;
		}
		// 上次下单
		$where = array();
		$where["cust_id"] = $shopId;
		$where['staff_id'] = $userId;
		$where['order_from'] = $order_from;
		$orderInfo = M("presale_orders")->field('add_time, order_total_money')->where($where)->order("add_time desc")->find();
		
		// 上次下单时间
        $data['lasttime'] = '';
        if($orderInfo['add_time']>0) {
            $data['lasttime'] = date('Y-m-d H:i', $orderInfo['add_time']);
        }

		// 上次销售
		$data['lastmoney'] = $orderInfo['order_total_money'];
		if(empty($data['lastmoney'])){ $data['lastmoney'] = 0; }
		
		// 上月销售
		$begintime= strtotime(date('Y-m-01', strtotime('-1 month')));
		$endtime =  strtotime(date('Y-m-t', strtotime('-1 month')));
        $where['add_time'] = array(array("gt", $begintime),array("lt", $endtime));
		$data['lastMonthTotal'] = M('presale_orders')->where($where)->sum('order_total_money');
		if(empty($data['lastMonthTotal'])){ $data['lastMonthTotal'] = 0; }
		
		// 返回	
		echo json_encode(array('error' => '-1', 'msg' => '成功', 'data' => $data));
	}

}

/*************************** end ************************************/