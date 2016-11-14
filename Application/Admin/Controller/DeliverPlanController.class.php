<?php

/*******************************************************************
 ** 文件名称: DeliverPlanController.class.php
 ** 功能描述: 系统后台配送预单控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-11
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DeliverPlanController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    // 根据配送线路中的终端门店查询未派送预单
	// 创建人员: richie
	// 创建日期: 2016-08-11
    public function indexAction()
	{
        //内勤人员标识
        $depotID = $this->_depot_id;

        $depot_id = I('depot_id');
		// 搜索条件
		$line_id = I('lid',0);
		$this->assign('queryLine', $line_id);
        if (isset($_GET["start"]) && isset($_GET["end"])) {
            $start_time = I('start');
            $this->assign('startTime', $start_time);
            $start_time = strtotime($start_time . ' 00:00:00');

            $end_time = I('end');
            $this->assign('endTime', $end_time);
            $end_time = strtotime($end_time . ' 23:59:59');

            $where['add_time'] = array(array('gt', $start_time),array('lt', $end_time));

        }
		
		// 配送线路的仓库
		$depot_id = M('shipping_line')->where("line_id = $line_id")->getField('depot_id');

		// 配送线路中的店铺
		$shop_id = M('shipping_shop')->where("line_id = $line_id")->getField('shop_id', true);
		
		$shop_id_array = implode(",", $shop_id);
        
        //print_r($shop_id);die();
		
		// 拼接where条件
		$where['is_cancel'] = 0;
		$where['order_status'] = 1;
		if($depot_id){
             $where['repertory_id'] = intval($depot_id);
        }

		if($shop_id){
		    $where['cust_id'] = array('in', $shop_id_array);
		}else{
			$where['cust_id'] =0;
		}

		//print_r($where);die();
		
		// 订单列表, 按照终端店归类
		$orderList = array();
		$presaleIDList = array();
		$returnIDList = array();
		$changeIDList = array();
		
		// 查询预单
		$results = M('presale_orders')->where($where)->order('order_id asc')->select();
		
        //print_r($results);die();

		foreach($results as $item) {
			// 店铺ID
			$shopID = $item['cust_id'];
			$shopName = $item['cust_name'];
		
			// 订单信息
			$temp = array();
			$temp['order_id'] = $item['order_id'];
			$temp['order_code'] = $item['order_code'];
			$temp['order_total_money'] = $item['order_total_money'];
			$temp['add_time'] = date('Y-m-d H:i', $item['add_time']);
			
			// 记录订单ID和商铺ID
			$presaleIDList[] = array('order_id'=>$item['order_id'], 'shop_id'=>$item['cust_id']);
			
			// 先添加店铺，在添加店铺预单
			if(empty($orderList[$shopID])){
			    $orderList[$shopID] = array(
			        'cust_id'=>$shopID,
                    'cust_name'=>$shopName,
                    'order_list'=>array()
                );
			}
			
			// 添加店铺预单
			$orderList[$shopID]['order_list'][] = $temp;
		}
		
		// 查询退货
		$results = M('presale_return')->where($where)->order('return_id asc')->select();
		foreach($results as $item)
		{
			// 店铺ID
			$shopID = intval($item['cust_id']);
			$shopName = $item['cust_name'];
		
			// 订单信息
			$temp = array();
			$temp['order_id'] = $item['return_id'];
			$temp['order_code'] = $item['return_code'];
			$temp['order_total_money'] = $item['return_real_money'];
			$temp['add_time'] = date('Y-m-d H:i', $item['add_time']);
			
			// 记录订单ID和商铺ID
			$returnIDList[] = array('order_id'=>$item['return_id'], 'shop_id'=>$item['cust_id']);			
			
			// 先添加店铺，再添加店铺退单
			if(empty($orderList[$shopID])){ $orderList[$shopID] = array('cust_id'=>$shopID, 'cust_name'=>$shopName, 'order_list'=>array()); }
			
			// 添加店铺退单
			$orderList[$shopID]['order_list'][] = $temp;
		}
		
		// 查询调换货
		$results = M('presale_change')->where($where)->order('change_id asc')->select();
        //print_r($results);die();
		foreach($results as $item)
		{

			// 店铺ID
			$shopID = intval($item['cust_id']);
			$shopName = $item['cust_name'];

			// 订单信息
			$temp = array();
			$temp['order_id'] = $item['change_id'];
			$temp['order_code'] = $item['change_code'];
			$temp['order_total_money'] = $item['order_total_money'];
			$temp['add_time'] = date('Y-m-d H:i', $item['add_time']);
			
			// 记录订单ID和商铺ID
			$changeIDList[] = array('order_id'=>$item['change_id'], 'shop_id'=>$item['cust_id']);					
			
			// 先添加店铺，再添加店铺调换货单
			if(empty($orderList[$shopID])){
			    $orderList[$shopID] = array(
			        'cust_id'=>$shopID,
                    'cust_name'=>$shopName,
                    'order_list'=>array()
                );
			}
			
			// 添加店铺调换货单
			$orderList[$shopID]['order_list'][] = $temp;
		}

		// 注册数据	
		$this->assign('orderList', $orderList);
		$this->assign('presaleIDList', json_encode($presaleIDList));
		$this->assign('returnIDList', json_encode($returnIDList));
		$this->assign('changeIDList', json_encode($changeIDList));

		// 配送线路
        $where=array();
        if($depotID>0){
            $where['depot_id'] = $depotID;
        }
		$line_list = M('shipping_line')->where($where)->order('line_id asc')->select();
		$this->assign('line_list', $line_list);
		$this->assign('depotList', queryDepot($depotID)); // 仓库
		$this->assign('staffList', queryAdminStaff($depotID, 5)); // 业务员
		$this->display();
    }

	// 添加人员配送线路和订单
	public function addAction() {

	    $depot_id = $this->_depot_id;

		// 配送人员
		$staff_id = intval($_POST['staff_id']);

		// 配送路线
		$line_id = intval($_POST['lineID']);

        // 检查是否有未申请车存的订单
        $ocount = M("admin_order")->alias("ao")
            ->join("__PRESALE_ORDERS__ as po on ao.order_id=po.order_id")
            ->where("ao.admin_id=$staff_id AND po.is_cancel=0 AND ao.order_state <> 3")
            ->count();

        if ($ocount > 0) {
            echo "<script>alert('该配送人员还有未配送完的订单！');window.location='/index.php/Admin/DeliverPlan/list.html';</script>"; exit;
        }

        //print_r($line_id);die();

        // 检测配送路线是否在管理权限内
        if ($depot_id > 0) {
            $line = M("shipping_line")->where("depot_id=$depot_id")->find();

            if(!$line) {
                echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
            }
        }


		// 配送订单
		$presale_list = json_decode($_POST['presale_list'], true);
		$return_list = json_decode($_POST['return_list'], true);
		$change_list = json_decode($_POST['change_list'], true);

		// 给配送人员指定配送线路
		$data['line_id'] = $line_id;
		$data['user_id'] = $staff_id;
		$data['add_time'] = time();
		M('shipping_user')->where("user_id = $staff_id")->delete();
		M('shipping_user')->add($data);

		// 给配送人员指定配送订单
		$orderData = array();
		$presale_id_list = array();
		$return_id_list = array();
		$change_id_list = array();
		foreach($presale_list as $item)
		{
			$orderData[] = array('admin_id'=>$staff_id, 'order_type'=>1, 'order_id'=>$item['order_id'], 'order_state'=>2, 'shop_id'=>$item['shop_id']);
			$presale_id_list[] = $item['order_id'];
		}
		foreach($return_list as $item)
		{
			$orderData[] = array('admin_id'=>$staff_id, 'order_type'=>2, 'order_id'=>$item['order_id'], 'order_state'=>2, 'shop_id'=>$item['shop_id']);
			$return_id_list[] = $item['order_id'];
		}
		foreach($change_list as $item)
		{
			$orderData[] = array('admin_id'=>$staff_id, 'order_type'=>3, 'order_id'=>$item['order_id'], 'order_state'=>2, 'shop_id'=>$item['shop_id']);
			$change_id_list[] = $item['order_id'];
		}

		M('admin_order')->where("admin_id = $staff_id")->delete();
		M('admin_order')->addAll($orderData);

		// 修改原始预单配送状态
		if(!empty($presale_id_list)){ M('presale_orders')->where("order_id in (" . implode(",", $presale_id_list) . ")")->setField('order_status', 2); }
		if(!empty($return_id_list)){ M('presale_return')->where("return_id in (" . implode(",", $return_id_list) . ")")->setField('order_status', 2); }
		if(!empty($change_id_list)){ M('presale_change')->where("change_id in (" . implode(",", $change_id_list) . ")")->setField('order_status', 2); }

		// 返回页面
		$this->redirect('list');
	}

	// 查询所有人员配送线路
	// 创建人员: richie
	// 创建日期: 2016-08-11
	public function listAction(){
        //内勤人员标识
        $depotID = $this->_depot_id;
		// 搜索条件
		$queryDepotID = intval($_GET['depot_id']);
		$queryStaffID = intval($_GET['staff_id']);
		$queryLineID = intval($_GET['line_id']);
		$this->assign('queryDepotID', $queryDepotID);
		$this->assign('queryStaffID', $queryStaffID);
		$this->assign('queryLineID', $queryLineID);

        // 仓库名称
        $depotName = M('depot_info')->where("repertory_id = $queryDepotID")->getField('repertory_name');
        $this->assign('depotName', $depotName);

        // 拼接SQLWhere条件
        if($depotID > 0) {$where['s.depot_id'] = $depotID;}
        if($queryDepotID > 0) { $where['s.depot_id'] = $queryDepotID;}
        if($queryLineID > 0) { $where['su.line_id'] = $queryLineID; }
        if($queryStaffID > 0) { $where['su.user_id'] = $queryStaffID; }

        //$where[""]

        // 查询当前配送人员路线分配情况
        $shippint_list = M('shipping_user')->alias('su')
            ->field('u.true_name, s.line_name, su.*,di.repertory_name')
            ->join('__SHIPPING_LINE__ as s on su.line_id = s.line_id')
            ->join('__ADMIN_USER__ as u on su.user_id = u.admin_id')
            ->join('__DEPOT_INFO__ as di on s.depot_id=di.repertory_id')
            ->where($where)->order('su.user_id asc')->select();
        $this->assign('shippint_list', $shippint_list);


		// 配送线路
        $where=array();
        if($depotID>0){
            $where['depot_id'] = $depotID;
        }
        if ($queryDepotID > 0) {
            $where['depot_id'] = $queryDepotID;
        }
		$line_list = M('shipping_line')->where($where)->order('line_id asc')->select();
		$this->assign('line_list', $line_list);
		
        // 仓库
        $this->assign('depotList', queryDepot($depotID));

        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID, 5));
		$this->display();
    }

	// 指定人员配送线路订单列表, 按照店铺归类
	// 创建人员: richie
	// 创建日期: 2016-08-11
	public function detailAction()
	{
        $depot_id = $this->_depot_id;

        // 配送人员
        $uid = intval($_GET['uid']);
        $lid = intval($_GET['lid']);

        $where1["admin_id"] = $uid;
        $where2["line_id"] = $lid;

        if ($depot_id > 0) {
            $where1["depot_id"] = $depot_id;
            $where2["depot_id"] = $depot_id;
        }

        // 人员信息
        $userInfo = M('admin_user')->where($where1)->find();
        if (!$userInfo) {
            echo "<script>alert('非法操作！code: 10001');window.location='/index.php/Admin/DeliverPlan/list';</script>"; die;
        }
        $this->assign('userInfo', $userInfo);

        // 路线信息
        $lineInfo = M("shipping_line")->where($where2)->find();
        if (!$lineInfo) {
            echo "<script>alert('非法操作！code: 10002');window.location='/index.php/Admin/DeliverPlan/list';</script>"; exit;
        }

        $this->assign('lineInfo', $lineInfo);
	   
		
		// 查询是否已经申请车存
		$orderIdList = array();
		
		// 订单列表, 按照店铺归类
		$orderList = array();
		
		// 配送预单
		$where['ao.admin_id'] = $uid;
		$where['ao.order_type'] = 1;
		$results = M('admin_order')->alias('ao')->field('o.*')
		->join('__PRESALE_ORDERS__ as o on ao.order_id = o.order_id')
		->where($where)->order('ao.order_id asc')->select();
		
		// 按照店铺归类预单
		foreach($results as $item)
		{
			// 店铺ID
			$shopID = $item['cust_id'];
			$shopName = $item['cust_name'];
		
			// 订单信息
			$temp = array();
			$temp['order_id'] = $orderIdList[] = $item['order_id'];
			$temp['order_code'] = $item['order_code'];
			$temp['order_total_money'] = $item['order_total_money'];
			$temp['add_time'] = date('Y-m-d H:i', $item['add_time']);
			
			// 添加店铺
			if(empty($orderList[$shopID]))
			{
				$orderList[$shopID] = array('cust_id'=>$shopID, 'cust_name'=>$shopName, 'order_list'=>array());
			}
			
			// 添加预单
			$orderList[$shopID]['order_list'][] = $temp;
		}

		// 配送退货单
		$where['ao.order_type'] = 2;
		$results = M('admin_order')->alias('ao')->field('o.*')
		->join('__PRESALE_RETURN__ as o on ao.order_id = o.return_id')
		->where($where)->order('ao.order_id asc')->select();

		// 按照店铺归类退货单
		foreach($results as $item)
		{
			// 店铺ID
			$shopID = $item['cust_id'];
			$shopName = $item['cust_name'];
		
			// 订单信息
			$temp = array();
			$temp['order_id'] = $item['return_id'];
			$temp['order_code'] = $item['return_code'];
			$temp['order_total_money'] = $item['return_real_money'];
			$temp['add_time'] = date('Y-m-d H:i', $item['add_time']);
			
			// 添加店铺
			if(empty($orderList[$shopID]))
			{
				$orderList[$shopID] = array('cust_id'=>$shopID, 'cust_name'=>$shopName, 'order_list'=>array());
			}
			
			// 添加预单
			$orderList[$shopID]['order_list'][] = $temp;
		}

		// 配送调货单
		$where['ao.order_type'] = 3;
		$results = M('admin_order')->alias('ao')->field('o.*')
		->join('__PRESALE_CHANGE__ as o on ao.order_id = o.change_id')
		->where($where)->order('ao.order_id asc')->select();
		
		// 按照店铺归类调货单
		foreach($results as $item)
		{
			// 店铺ID
			$shopID = $item['cust_id'];
			$shopName = $item['cust_name'];
		
			// 订单信息
			$temp = array();
			$temp['order_id'] = $item['change_id'];
			$temp['order_code'] = $item['change_code'];
			$temp['order_total_money'] = $item['order_total_money'];
			$temp['add_time'] = date('Y-m-d H:i', $item['add_time']);
			
			// 添加店铺
			if(empty($orderList[$shopID]))
			{
				$orderList[$shopID] = array('cust_id'=>$shopID, 'cust_name'=>$shopName, 'order_list'=>array());
			}
			
			// 添加预单
			$orderList[$shopID]['order_list'][] = $temp;
		}

		// 查看是否已经存在车存申请
		if(empty($orderIdList)){ $orderIdList[] = $uid; }
		$carApplyFlag = implode(",", $orderIdList);
		$carApplyFlag = md5($carApplyFlag);
		$flag = M('car_apply')->where("staff_id = $uid and apply_flag = '" . $carApplyFlag . "'")->count();
		$this->assign('flag', $flag);

		// 返回页面
		$this->assign('orderList', $orderList);
		$this->display();
	}

	// 删除人员配送路线
	// 创建人员: richie
	// 创建日期: 2016-08-11
	public function delAction()
	{
		$uid = intval($_GET['uid']);
		$lid = intval($_GET['lid']);

        // 检测权限
        $depot_id = $this->_depot_id;
        if ($depot_id > 0) {
            $line = M("shipping_line")->where("line_id=$lid AND depot_id=$depot_id")->find();
            if (!$line) {
                echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
            }
        }
		
		// 删除路线
		M("shipping_user")->where("line_id = $lid AND user_id = $uid")->delete();
		
		// 删除订单, 这里有问题
		M('admin_order')->where("admin_id = $uid")->delete();
		
		// 返回页面
		$this->redirect('list');
	}

	// 生成车存申请单据
	public function applyAction()
	{
		// 商品页面
		if(IS_GET)
		{
			// 配送人员
			$userID = intval($_GET['uid']);
			$line_id = intval($_GET['line_id']);
			
			// 人员信息
			$userInfo = M('admin_user')->where("admin_id=$userID")->find();
			$this->assign('userInfo', $userInfo);
			
			// 拼接Where条件
			$where['o.admin_id'] = $userID;

			// 配送预单商品列表
			$where['o.order_type'] = 1; 
			$presale_goods = M('presale_orders_goods')->alias('g')->field('g.*')
			->join('__ADMIN_ORDER__ as o on o.order_id = g.order_id')
			->where($where)->order('cv_id asc')->select();

			// 调换货出货商品列表
			$where['o.order_type'] = 3;
			$where['g.is_change_in'] = 0; // 调出
			$change_goods = M('presale_change_goods')->alias('g')->field('g.*')
			->join('__ADMIN_ORDER__ as o on o.order_id = g.change_id')
			->where($where)->order('cv_id asc')->select();

			// 商品数量汇总
			$goodsList = array();
			foreach($presale_goods as $item)
			{
				// 计算商品最小单位数量
				$goods_id = intval($item['goods_id']);
				$cv_id = intval($item['cv_id']);
				$number = intval($item['number']);
				$small_number = getSmallNumber($cv_id, $number);
				
				// 添加车存申请商品
				$goodsList[$goods_id]["number"] += $small_number;
				$goodsList[$goods_id]["goods_id"] = $goods_id;
				$goodsList[$goods_id]["goods_name"] = $item['goods_name'];
				$goodsList[$goods_id]["goods_spec"] = $item['goods_spec'];
			}
			foreach($change_goods as $item)
			{
				// 计算商品最小单位数量
				$goods_id = intval($item['goods_id']);
				$cv_id = intval($item['cv_id']);
				$number = intval($item['number']);
				$small_number = getSmallNumber($cv_id, $number);
				
				// 添加车存申请商品
				$goodsList[$goods_id]["number"] += $small_number;
				$goodsList[$goods_id]["goods_id"] = $goods_id;
				$goodsList[$goods_id]["goods_name"] = $item['goods_name'];
				$goodsList[$goods_id]["goods_spec"] = $item['goods_spec'];
			}
			
			// 数量整件化
            foreach($goodsList as $k=>$v){ $goodsList[$k]["number_string"] = getGoodsUnitString($v["goods_id"], $v["number"]); }

            if (empty($goodsList)) {
                $this->redirect('DeliverPlan/list');
            }
			
			// 返回页面
			$this->assign("line_id", $line_id);
			$this->assign('goodsList', $goodsList);
			$this->display();
		}
		
		// 添加车存申请
		if(IS_POST)
		{
			// 配送人员
			$staffId = intval($_POST['staffId']);
			$line_id = intval($_POST['line_id']);

			// 查询配送预单标示, 将预单ID降序排列后再进行MD5编码
			$adminIdArray = M('admin_order')->where("admin_id = $staffId and order_type = 1")->order('order_id asc')->getField('order_id', true);
			if(empty($adminIdArray)){$adminIdArray[] = $staffId; }
			$carApplyFlag = implode(",", $adminIdArray);
			$carApplyFlag = md5($carApplyFlag);

			// 配送员所在的仓库
			$repertory_id = M("admin_user")->where("admin_id = $staffId")->getField('depot_id');
			
			// 车存申请商品列表
			$goodsArray = $_POST['goods_id'];
			$goodsNumberArray = $_POST['goods_num'];
			
			// 商品ID和数量
			$goodsIDNumber = array();
			foreach($goodsArray as $key=>$gid){
			    $goodsIDNumber[$gid] = $goodsNumberArray[$key];
			}
			
			// 查询商品信息, 最小单位下
			$where['goods_id'] = array('in', implode(',', $goodsArray));
			$where['goods_unit_type'] = 1; // 最小单位
			$goodsList = M('goods_product')->where($where)->order('goods_id asc')->select();
			
			// 申请商品信息
			$goodsData = array();
			foreach($goodsList as $item)
			{
				$temp = array();
				$temp['apply_id'] = 0;
				$temp['cv_id'] = $item['cv_id'];
				$temp['goods_id'] = $gid = $item['goods_id'];
				$temp['goods_name'] = $item['goods_name'];
				$temp['goods_sepc'] = $item['goods_spec'];
				$temp['apply_price'] = 0;
				$temp['apply_num'] = $goodsIDNumber[$gid];
				$temp['goods_unit'] = $item['goods_unit'];
				$goodsData[] = $temp;

                // 检查库存是否充足
                $check = checkStockFunction($item['goods_id'], $repertory_id, $item['cv_id'], $goodsIDNumber[$gid] );

                if(!$check) {
                    echo "<script>alert('【". $item['goods_name'] ."】库存不足！');window.location='./';</script>"; exit;
                }

			}
			
			// 添加申请单信息
			$data['apply_code'] = create_uniqid_code('CA', $staffId);
			$data['staff_id'] = $staffId;
			$data['org_parent_id'] = 0; // 没有经销商
			$data['repertory_id'] = $repertory_id; // 配送员所在的仓库
			$data['apply_status'] = 2; // 已审核
			$data['apply_total_money'] = 0;
			$data['apply_remark'] = '';
			$data['apply_flag'] = $carApplyFlag; // 配送预单标示
			$data['add_id'] = $_SESSION['admin_id'];
			$data['add_time'] = time();
			$data['check_id'] = $_SESSION['admin_id'];
			$data['check_time'] = time();
			$data['accept_time'] = 0;
			$data['is_cancel'] = 0;
			$data['cancel_time'] = 0;
			$apply_id = M('car_apply')->add($data);
			
			// 添加车申商品
			foreach($goodsData as &$item){ $item['apply_id'] = $apply_id; }
			M('car_apply_goods')->addAll($goodsData);
			
			// 删除配送线路
			//$whereS["line_id"] = $line_id;
			//$whereS["user_id"] = $staffId;
			//M("shipping_user")->where($whereS)->delete();
			$this->addDepotOutOrder($apply_id);
			// 返回车存申请页面
			$this->redirect('DeliverApply/index');
		}
	}

    private function addDepotOutOrder($id){
    	    $where["apply_id"] = $id;
		
    	    $res=M("car_apply")->where($where)->find();
			$data["send_staff_id"]=0;
        	$data["out_type"]=2; // 入库类型
        	$data["out_status"]=2;
        	$data["out_remark"]=$res["apply_remark"];
        	$data["depot_id"]=$res["repertory_id"];
			$data["org_parent_id"]=$res["org_parent_id"];
        	//$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":5}]';
        	$goods_info=M("car_apply_goods")->field("goods_id,cv_id,apply_num as goods_num")->where($where)->select();
			
        	$data["goods_info"]=$goods_info;
		
       
			$data["create_id"]=session("admin_id");

	    	$res = D("DepotOut")->addDepotOutOrder($data);
			
			foreach($goods_info as $k=>$v){
				$stockData[$k]["cv_id"]=$v["cv_id"];
				$stockData[$k]["goods_id"]=$v["goods_id"];
				$stockData[$k]["small_stock"]=$v["goods_num"];
				$stockData[$k]["org_parent_id"]=$data["org_parent_id"];
				$stockData[$k]["depot_id"]=$data["depot_id"];
			}
			
			$msg = queryDepotOutType($data['out_type']);
					
			D("DepotStock")->updateStock($stockData, $msg, "del", $data['out_type']);
    }

	/** 其他Action **/

}

/*************************** end ************************************/