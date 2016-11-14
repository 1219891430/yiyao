<?php

/*******************************************************************
 ** 文件名称: CarsaleShopController.class.php
 ** 功能描述: 经销商终端门店接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleShopController extends Controller {

	// 经销商业务员创建终端店
	// 创建人员: richie
	// 创建日期: 2016-08-25
	public function addShopAction()
	{
		// 经销商和业务员
		$org_parent_id = intval($_REQUEST['commpanyId']);
		$staff_id = intval($_REQUEST['userId']);
		
		// 终端店铺手机号
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
				// 添加经销商和终端店关系
				$data2 = array();
				$data2['org_parent_id'] = $org_parent_id;
				$data2['shop_type'] = I('shop_type', 0);
				$data2['staff_id'] = $staff_id;
				$data2['shop_id'] = $shop_id;
				$data2['add_time'] = time();
				$flag = M('org_customer')->add($data2);
				
				// 添加经销商业务员和终端店关系
				$data2 = array();
				$data2['staff_id'] = $staff_id;
				$data2['shop_id'] = $shop_id;
				$data2['org_parent_id'] = $org_parent_id;
				$flag = M('org_staff_customer')->add($data2);
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

		// 经销商所在仓库
		$repertory_id = M('depot_org')->where("org_parent_id = $org_parent_id")->getField('repertory_id');
		
		// 门头照片
		if($_FILES['picture']['name'])
		{
			$upload = new \Think\Upload();
			$upload->maxSize = 3000000;
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
		$data['staff_id'] = 0; // 不记录经销商业务员
		$data['reg_time'] = time();
		$data['is_check'] = 1;
		$data['is_close'] = 0;	
		$cust_id = M("customer_info")->add($data);
		if(empty($cust_id)){ echo json_encode(array('error' => '5', 'msg' => '创建失败')); exit; }		
					
		// 添加经销商和终端店关系
		$data2 = array();
		$data2['org_parent_id'] = $org_parent_id;
		$data2['shop_type'] = I('shop_type', 0);
		$data2['staff_id'] = $staff_id;
		$data2['shop_id'] = $cust_id;
		$data2['add_time'] = time();
		$flag = M('org_customer')->add($data2);

		// 添加经销商业务员和终端店关系
		$data2 = array();
		$data2['staff_id'] = $staff_id;
		$data2['shop_id'] = $cust_id;
		$data2['org_parent_id'] = $org_parent_id;
		$flag = M('org_staff_customer')->add($data2);

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

	// 经销商店铺列表
	// 创建人员: richie
	// 创建日期: 2016-08-16
	public function shopListAction()
	{
		// 业务员ID
		$staff_id = intval($_REQUEST["userId"]);
		
		// 联合查询店铺
		$shop = M('org_staff_customer')->alias('sc')->field('c.*')
		->join('__CUSTOMER_INFO__ as c on sc.shop_id = c.cust_id')
		->where("sc.staff_id = $staff_id")->order("c.cust_id asc")->select();
		
		// 格式化店铺列表
		$shopList = array();
		for($i=0; $i<count($shop); $i++)
		{
			// 店铺基本信息
			$shopList[$i]['id'] = $shopID = $shop[$i]["cust_id"];
			$shopList[$i]['name'] = $shop[$i]["cust_name"];
			$shopList[$i]['boss'] = $shop[$i]["contact"];
			$shopList[$i]['tel'] = $shop[$i]["telephone"];
			$shopList[$i]['address'] = $shop[$i]["address"];
			$shopList[$i]['longitude'] = empty($shop[$i]["longitude"]) ? "0" : $shop[$i]["longitude"];
			$shopList[$i]['latitude'] = empty($shop[$i]["dimension"]) ? "0" : $shop[$i]["dimension"];
                
			// 店铺类型	
			$typeName = M("customer_type")->field("ct_name")->where("ct_id=" . intval($shop[$i]["cust_type"]))->find();
			$shopList[$i]["typeName"] = !empty($typeName["ct_name"]) ? $typeName["ct_name"]  : '';
			
			// 拜访记录
			$signTime = M('customer_weihu')->where("saleman_id = $staff_id and shop_id = $shopID")->order("add_time desc")->getField('add_time');
			if(empty($signTime))
			{
				$shopList[$i]['signTime']="暂无拜访记录";
				$shopList[$i]['notTo'] = -1; // 
			}
			else
			{
				$shopList[$i]['signTime'] = date("Y-m-d H:i:s", $signTime);
				$shopList[$i]['notTo'] = (int)floor((time()-strtotime(date('Y-m-d 00:00:00', $signTime)))/(24*60*60));
			}

			// 门头照片
			$link = DOMAIN . "Public/Uploads/" . $shop[$i]['head_pic'];
			$shopList[$i]['picture'] = empty($shop[$i]["head_pic"]) ? "" : $link;
		}

		// 返回
		if(!empty($shopList))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $shopList));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => "暂无店铺"));
		}
    }

    // 终端门店详细
	// 创建人员: richie
	// 创建日期: 2016-08-16
    public function getShopDataAction()
    {
		// 业务员ID和终端门店ID
		$staff_id = intval($_REQUEST['userId']);
		$org_parent_id = intval($_REQUEST['companyId']);
		$shop_id = intval($_REQUEST['shopId']);

		// 上次销售额
		$where["is_cancel"] = 0;
		$where["cust_id"] = $shop_id;
		$where['staff_id'] = $staff_id;
		$where["org_parent_id"] = $org_parent_id;
		$data['lastmoney'] = M("carsale_orders")->where($where)->order("create_time desc")->limit(1)->getField('order_total_money');
		if(empty($data['lastmoney'])){
		    $data['lastmoney'] = 0;
		}

		// 上月销售
		$begintime = strtotime(date('Y-m-01', strtotime('-1 month')));
		$endtime =  strtotime(date('Y-m-t', strtotime('-1 month')));
		$where['create_time'] = array(array('gt', $begintime), array('lt', $endtime));
		$data['lastMonthTotal'] = M('carsale_orders')->where($where)->sum('order_total_money');
		if(empty($data['lastMonthTotal'])){
		    $data['lastMonthTotal'] = 0;
		}

        // 店铺欠款
		$totalQiankuan = 0;
		$where2["o.is_cancel"] = 0;
		$where2['o.is_full_pay'] = 0;
		$where2["o.cust_id"] = $shop_id;
		$where2['o.staff_id'] = $staff_id;
		$where2["o.org_parent_id"] = $org_parent_id;
		$qiankuan = M('carsale_orders')->alias('o')->field('o.order_total_money, o.order_real_money, sum(q.price) as order_pay_money')
		->join('left join __CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
		->where($where2)->group('o.order_id')->order('o.order_id asc')->select();	
		foreach($qiankuan as $item){
		    $totalQiankuan += ($item['order_total_money'] - $item['order_real_money'] - floatval($item['order_pay_money']));
		}
        $data['qiankuan'] = sprintf("%.2f", $totalQiankuan );

		// 返回数据
        $data['riqi']=date('Y-m', strtotime('-1 month'));
        echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $data));
    }

	// 店铺日志列表
	// 创建人员: richie
	// 创建日期: 2016-08-27
    public function shopLogListAction()
    {
		// 业务员ID,终端门店ID,分页页码
		$staff_id = intval($_REQUEST['userId']);
		$shop_id = intval($_REQUEST['shopId']);
		$p = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
		//$where["saleman_id"]=$staff_id;
		$where["shop_id"]=$shop_id;
		
		$staff=M("org_staff")->field("role_id")->where("staff_id=$staff_id")->find();
		if($staff["role_id"]==3){
			$where["saleman_id"]=$staff_id;
		}
		
		
		// 查询店铺日志
		$list = M("customer_log")->alias("cl")->field("cl.*,os.staff_name")
            ->join("left join __ORG_STAFF__ os on cl.saleman_id=os.staff_id")
            ->where($where)
            ->order("log_time desc")
            ->page($p, 20)
            ->select();
		for($i=0; $i<count($list); $i++)
		{
			$data[$i]["shopId"] = $list[$i]["shop_id"];
			$data[$i]["alarm"] = $list[$i]["remind_time"];
			$data[$i]["logId"] = $list[$i]["log_id"];
			$data[$i]["time1"] = date("Y-m-d", $list[$i]["log_time"]);
			$data[$i]["time2"] = date("H:i", $list[$i]["log_time"]);
			$data[$i]["content"] = $list[$i]["log_content"];
            $data[$i]['userName'] = $list[$i]['staff_name'];
            $data[$i]['file_path'] = $list[$i]['file_path'];
            $data[$i]['record'] = $list[$i]['record'];

		}
		
		// 返回
		if(!empty($list))
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $data));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => "暂无数据"));
		}
    }

	// 店铺日志编辑
	// 创建人员: richie
	// 创建日期: 2016-08-27
    public function shopLogEditAction()
    {  
		$data["saleman_id"] = $_REQUEST["userId"];
		$data["shop_id"] = $_REQUEST["shopId"];
		$data["remind_time"] = $_REQUEST["alarm"];
		$data["log_content"] = $_REQUEST["content"];
		$shopLog = M("customer_log")->where('log_id = ' . $_REQUEST["logId"])->save($data);
        if($shopLog >= 0)
		{
			echo json_encode(array('error' => '-1', 'msg' => '编辑成功', "logId" => $_REQUEST["logId"]));
        }
		else
		{
			echo json_encode(array('error' => '2', 'msg' => '失败'));
        }
    }

	// 店铺日志添加
	// 创建人员: richie
	// 创建日期: 2016-08-27
    public function shopLogAddAction()
    {
		// 添加店铺日志
		$data['org_parent_id'] = $org_parent_id = intval($_REQUEST['org_parent_id']);
		$data['saleman_id'] = $staff_id = intval($_REQUEST['userId']);
		$data['shop_id'] = $cust_id = intval($_REQUEST['shopId']);
		$data['log_content'] = I('content');
		$data['file_path'] = '';
		$data['record'] = 0;
		$data['file_name'] = '';
		$data['log_time'] = time();
		$data['remind_time'] = I('alarm', 0);
		$data['is_read'] = 0;
		$logId = M("customer_log")->data($data)->add();
		
		// 添加店铺维护记录
		D('CarsaleShop')->addPosition($org_parent_id, $staff_id, $cust_id, 6);	
		
		// 返回
		if(!empty($logId))
		{
			echo json_encode(array('error' => -1, 'msg' => '创建成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => "创建失败"));
		}
    }

	// 店铺日志添加文件
	// 创建人员: richie
	// 创建日期: 2016-08-27
    public function  shopLogAddFilesAction()
    {
		// 上传语音文件
		if($_FILES['file']['name'])
		{
			$upload = new \Think\Upload();
			$upload->maxSize = 3000000;
			$upload->saveName = '';
			$upload->savePath = 'shoplog/';
			$upload->rootPath = './Public/Uploads/';
			$info = $upload->upload();
			if($info)
			{
				$data["file_path"] = $info['file']['savepath'] . $info['file']['savename'];
				$data["file_name"] = $info['file']['savename'];


                // 添加日志
                $data["record"] = 1;
                $data["saleman_id"] = $staff_id = I("userId");
                $data["shop_id"] = $cust_id = I("shopId");
                $data["log_time"] = time();
                $data['org_parent_id'] = $org_parent_id = I('org_parent_id');
                $data['log_content'] = '';
                $data['log_time'] = time();
                $data['remind_time'] = 0;
                $data['is_read'] = 0;
                $logId = M("customer_log")->data($data)->add();

                // 添加店铺维护记录
                D('CarsaleShop')->addPosition($org_parent_id, $staff_id, $cust_id, 6);

                // 返回
                if(!empty($logId))
                {
                    echo json_encode(array('error' => -1, 'msg' => '创建成功'));
                }
                else
                {
                    echo json_encode(array('error' => 1, 'msg' => "创建失败"));
                }
                return;
			}
		}
        echo json_encode(array('error' => 2, 'msg' => "缺少语音文件"));

    }

}

/*************************** end ************************************/