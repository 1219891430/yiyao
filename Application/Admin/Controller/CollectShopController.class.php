<?php

/*******************************************************************
 ** 文件名称: CollectShopController.class.php
 ** 功能描述: 系统后台采单人员分配店铺控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-05
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class CollectShopController extends BaseController {


    public function __construct()
    {
        parent::__construct();
    }
    // 平台采单人员列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 查询条件仓库
		$queryDepot = I('queryDepot', 0);
		$this->assign('queryDepot', $queryDepot);
		
		// 搜索条件
		$where['is_admin'] = 0;
		$where['role_id'] = 4;
        if($this->_depot_id>0){
            $where['depot_id'] = $this->_depot_id;
        }
        else{
            if($queryDepot > 0){ $where['depot_id'] = $queryDepot; }
        }

		
		// 页码
		$page_size = I('pnum', 10);
		$page_num = I('p', 1);
		
		// 查询总记录计算页码
		$total = M('admin_user')->where($where)->count();
		$page = get_page_code($total, $page_size, $page_num,5);
		$this->assign('pagelist', $page);
	    $this->assign("page_size",$page_size);
		// 分页查询人员
		$staff_list = M('admin_user')->where($where)->order('admin_id desc')->page($page_num, $page_size)->select();
		$this->assign('staff_list', $staff_list);


        $query['p'] = $page_num;
        $query['pnum'] = $page_size;
        $query['dep_id'] = $where['depot_id'];
        session('jump_url', U('Admin/CollectShop/index',$query) );


		// 查询所有角色和所有仓库
		$this->assign('depot_list', format_array(queryDepot($this->_depot_id),'repertory_id','repertory_name'));
		$this->display();
    }
	
	// 店铺列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function shopsAction()
	{
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

		// 人员信息
		$admin_id = intval($_GET['id']);
        $where['admin_id'] = $admin_id;

        //权限判断
        if($this->_depot_id>0){
            $data = M('admin_user')->where($where)->find();
            if($data['depot_id'] != $this->_depot_id){
                alertToUrl('非法操作', $jump_url);
                return;
            }
        }

		$admin_info = M('admin_user')->where($where)->find();
		$this->assign('admin_info', $admin_info);
		
		// 店铺信息
		$shops_list = M('admin_shop')->alias('a')->field('s.*')
		->join('__CUSTOMER_INFO__ as s on a.shop_id = s.cust_id')
		->where('a.admin_id = ' . $admin_id)->order('s.cust_id asc')->select();
		$this->assign('shops', $shops_list);
		$this->display();
	}

	// 获取店铺列表
    public function getshopsAction() {
        // 人员信息
        $admin_id = intval($_GET['id']);
        $where['admin_id'] = $admin_id;

        //权限判断
        if($this->_depot_id>0){
            $data = M('admin_user')->where($where)->find();
            if($data['depot_id'] != $this->_depot_id){
                $this->ajaxReturn(["code"=>0, "msg"=>"非法操作"], "JSON");
                return;
            }

            $where1["s.repertory_id"] = $this->_depot_id;
        }

        // 店铺信息
        $shops_list = M("customer_info")->alias("s")->field('s.*')
            ->join("left join __ADMIN_SHOP__ as a on a.shop_id = s.cust_id")
            ->where($where1)->select();

        $this->ajaxReturn([
            "code"=>1,
            "shops" => $shops_list,
        ], "JSON");

    }
	
	// 添加店铺
	// 创建人员: richie
	// 创建日期: 2016-08-04
	function addshopAction()
	{
		$data['admin_id'] = intval($_GET['aid']);
		$data['shop_id'] = intval($_GET['sid']);


        //权限判断
        if($this->_depot_id>0) {
            $where['admin_id'] = $data['admin_id'];
            $user_info = M('admin_user')->where($where)->find();
            $where = array();
            $where['cust_id'] = $data['shop_id'];
            $shop_info = M('customer_info')->where($where)->find();

            if($user_info['depot_id']!= $this->_depot_id || $shop_info['repertory_id']!= $this->_depot_id){
                echo 0;
                return;
            }
        }

        $row = M('admin_shop')->where($data)->find();
        if(!$row) {
            $res = M('admin_shop')->add($data);
            if($res){
                echo "1";
            }
            else{
                echo "0";
            }
        }
        else{
            echo "2";
        }
	}
	
	// 删除店铺
	// 创建人员: richie
	// 创建日期: 2016-08-04
	function delshopAction()
	{
        //权限判断
        if($this->_depot_id>0) {
            $where['admin_id'] = intval($_GET['aid']);
            $user_info = M('admin_user')->where($where)->find();
            $where = array();
            $where['cust_id'] = intval($_GET['sid']);
            $shop_info = M('customer_info')->where($where)->find();

            if($user_info['depot_id']!= $this->_depot_id || $shop_info['repertory_id']!= $this->_depot_id){
                echo 0;
                return;
            }
        }

        $where = array();
        $where['admin_id'] = intval($_GET['aid']);
        $where['shop_id'] = intval($_GET['sid']);
		M('admin_shop')->where($where)->delete();
		die("1");
	}
	
	// 模糊查询商铺
	// 创建人员: richie
	// 创建日期: 2016-08-04
	function queryShopsAction(){

		$data = array();
		$shopName = trim($_GET["shop_name"]);
        if($this->_depot_id>0){
            $where["repertory_id"] = $this->_depot_id;
        }
		$where["cust_name"]=array("like", "%{$shopName}%");
		$results = M('customer_info')->field("cust_id, cust_name, contact, telephone, address")->where($where)->order('cust_id desc')->limit(20)->select();
		$shops = array();
		foreach($results as $item)
		{
			$itemID = $item['cust_id'];
			$itemName = $item['cust_name'].' '.$item['contact']. ' ' .$item['telephone'] . ' ' . $item['address'];
			$shops[] = array('id'=>$itemID, 'name'=>$itemName);
		}
		unset($results);
		
		// 返回
		if ($shops) {
            $data["res"] = 1;
            $data["info"] = $shops;
        } else {
            $data["res"] = 0;
        }
        return $this->ajaxReturn($data, "json");
	}


	public function mapsAction() {

        $admin_id = intval($_GET['id']);
        $where['admin_id'] = $admin_id;

        //权限判断
        if($this->_depot_id>0){
            $data = M('admin_user')->where($where)->find();
            if($data['depot_id'] != $this->_depot_id){
                echo "<script>alert('非法操作！');window.location='./';</script>";
                exit;
            }
        }

        $admin_info = M('admin_user')->where($where)->find();

        $this->assign("admin", $admin_info);

        // 店铺信息
        $shops_list = M('admin_shop')->alias('a')->field('s.*')
            ->join('__CUSTOMER_INFO__ as s on a.shop_id = s.cust_id')
            ->where('a.admin_id = ' . $admin_id)->order('s.cust_id asc')->select();

        $this->assign("shops", $shops_list);

	    $this->display();
    }

	/** 其他Action **/

}

/*************************** end ************************************/