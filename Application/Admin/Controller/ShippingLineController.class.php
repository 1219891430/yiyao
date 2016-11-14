<?php

/*******************************************************************
 ** 文件名称: ShippingLineController.class.php
 ** 功能描述: 配送线路管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-04
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class ShippingLineController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    // 配送线路列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{

		// 查询条件仓库
		$queryDepot = I('queryDepot', 0);
		$this->assign('queryDepot', $queryDepot);
		
		// 搜索条件
		$where = array();
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
		$total = M('shipping_line')->where($where)->count();
		$page = get_page_code($total, $page_size, $page_num);
		$this->assign('pagelist', $page);
	
		// 分页查询配送路线
		$line_list = M('shipping_line')->where($where)->order('line_id desc')->page($page_num, $page_size)->select();
		$this->assign('line_list', $line_list);
	    $this->assign("page_size",$page_size);
		// 查询所有角色和所有仓库
		$this->assign('depot_list', format_array(queryDepot($this->_depot_id),'repertory_id','repertory_name'));
		$this->display();
    }
	
	// 添加配送路线
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function addAction()
	{
		if(IS_GET)
		{
			$this->assign('depot_list', format_array(queryDepot($this->_depot_id),'repertory_id','repertory_name'));
			$this->display();
		}
		
		if(IS_POST)
		{
			// 录入数据
			$data['line_name'] = I('line_name');
			$data['line_desc'] = I('line_desc');
			$data['depot_id'] = I('depot_id');
			$data['add_time'] = time();

            //权限判断
            if($this->_depot_id>0){
                $data['depot_id'] = $this->_depot_id;

            }
			// 不能为空
			if(empty($data['line_name']) || empty($data['depot_id']))
			{
				echo json_encode(array("info" => "录入信息不完善！", "res" => 0)); exit;
			}
			
			// 添加路线
			M('shipping_line')->add($data);
			echo json_encode(array("info" => "添加成功！", "res" => 1)); exit;
		}
	}
	
	// 删除配送路线
	public function delAction()
	{
		$line_id = intval($_GET['id']);
        //权限判断
        if($this->_depot_id>0){
            $line_info = M('shipping_line')->where("line_id = ". $line_id)->find();
            if($line_info['depot_id']!= $this->_depot_id){
                echo 0;
                return;
            }
        }
		M('shipping_line')->where("line_id = $line_id")->delete();
		M('shipping_shop')->where("line_id = $line_id")->delete();
		die("1");
	}

	// 路线店铺列表
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function shopsAction()
	{
		// 线路信息
		$line_id = intval($_GET['lid']);
		$line_info = M('shipping_line')->where('line_id = ' . $line_id)->find();

        //权限判断
        if($this->_depot_id>0){
            if($line_info['depot_id'] != $this->_depot_id){
                alertToUrl('非法操作', "");
                return;
            }
        }
		$this->assign('line_info', $line_info);
		
		// 线路商铺
		$shop_list = M('shipping_shop')->alias('s')->field('c.*')
		->join('__CUSTOMER_INFO__ as c on s.shop_id = c.cust_id')
		->where('s.line_id = ' . $line_id)->order('c.cust_id asc')->select();
		$this->assign('shops', $shop_list);
		
		// 返回页面
		$this->display();
	}

	// 添加商铺
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function addshopAction()
	{
		$data['line_id'] = intval($_GET['lid']);
		$data['shop_id'] = intval($_GET['sid']);

        //权限判断
        if($this->_depot_id>0){
            $line_info = M('shipping_line')->where("line_id = ". $data['line_id'])->find();
            if($line_info['depot_id']!= $this->_depot_id){
                echo 0;
                return;
            }
        }

        $row = M('shipping_shop')->where($data)->find();
        if(!$row) {
            $res = M('shipping_shop')->add($data);
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

	// 删除商铺
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function delshopAction()
	{
		$where['line_id'] = intval($_GET['lid']);
		$where['shop_id'] = intval($_GET['sid']);

        //权限判断
        if($this->_depot_id>0){
            $line_info = M('shipping_line')->where("line_id = ". $where['line_id'])->find();
            if($line_info['depot_id']!= $this->_depot_id){
                echo 0;
                return;
            }
        }

		M('shipping_shop')->where($where)->delete();
		die("1");
	}

	/** 其他Action **/

	// 线路地图
    public function mapsAction() {
        $line_id = intval($_GET['lid']);

        //权限判断
        if($this->_depot_id>0){
            $line_info = M('shipping_line')->where("line_id = ". $line_id)->find();
            if($line_info['depot_id']!= $this->_depot_id){
                alertToUrl('非法操作', "");
                return;
            }
        }


        $line_info = M('shipping_line')->alias("sl")
            ->join("left join __DEPOT_INFO__ as di on di.repertory_id=sl.depot_id")
            ->where('line_id = ' . $line_id)
            ->find();
        $this->assign('line_info', $line_info);

        // 线路商铺
        $shop_list = M('shipping_shop')->alias('s')->field('c.*')
            ->join('__CUSTOMER_INFO__ as c on s.shop_id = c.cust_id')
            ->where('s.line_id = ' . $line_id)->order('c.cust_id asc')->select();

        $this->assign('shops', $shop_list);

        //print_r($line_info);die();

        $this->display();
    }

    // 获取所有经销商的坐标
    public function coorAction() {
        $depot_id = I('depot_id');

        //权限判断
        if($this->_depot_id>0){
            $where['repertory_id'] = $this->_depot_id;
        }
        else{
            $where['repertory_id'] = $depot_id;
        }

        $coor = M("customer_info")->where($where)->field("cust_id,cust_name,longitude, dimension")->select();
        $this->ajaxReturn($coor);
    }

    // 删除经销商
    public function del_custAction() {
        $cust_id = I("post.cust_id");


    }

}

/*************************** end ************************************/