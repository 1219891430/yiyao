<?php

/*******************************************************************
 ** 文件名称: DeliverOrderController.class.php
 ** 功能描述: 系统后台配送车销单控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DeliverOrderController extends BaseController {

    var $_mod_deliverOrder;

    public function __construct(){
        parent::__construct();
        $this->_mod_deliverOrder = new \Common\Model\DeliverOrderModel();
    }
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
    public function indexAction()
    {
        //内勤人员标识
        $depotID = $this->_depot_id;

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $query['repertory_id'] = I('get.repertory_id');
        $query['staff_id'] = I('get.staff_id');
        $query['cust_name'] = I('get.cust_name');
        $query['start_time'] = I('get.start_time');
        $query['end_time'] = I('get.end_time');

        $this->assign('query', $query);

        if($depotID>0) {
            $where["depot_id"] = array("eq",$depotID);
        }
        else {
            if (!empty($query["repertory_id"])) {
                $where["depot_id"] = array("eq", $query["repertory_id"]);
            }
        }

        if (!empty($query["staff_id"])){
            $where["staff_id"] = array("eq",$query["staff_id"]);
        }
        if (!empty($query["cust_name"])){
            $where["cust_name"] = array("like","%{$query["cust_name"]}%");
        }

        if (!empty($query["start_time"])){
            $where["create_time"] = array("egt",strtotime($query["start_time"]));
        }
        if (!empty($query["end_time"])) {
            $where["create_time"] = array("elt", strtotime($query["end_time"]) + 24 * 60 * 59);
        }


        //列表数据
        $total = $this->_mod_deliverOrder->table("zdb_car_orders as co")
            ->field('co.*, au.depot_id, au.true_name')
            ->join('left join __ADMIN_USER__ as au on co.staff_id = au.admin_id')
            ->where($where)
            ->count();//总数

        $list = $this->_mod_deliverOrder->table("zdb_car_orders as co")
            ->field('co.*, au.depot_id, au.true_name')
            ->join('left join __ADMIN_USER__ as au on co.staff_id = au.admin_id')
            ->where($where)->page($p,$pnum)->order("create_time desc")->select();//列表

        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/DeliverOrder/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pnum',$pnum);
        $this->assign('pagelist',$page);//分页显示

        //仓库select
        $depotList = queryDepot($depotID);
        $this->assign('depotList',$depotList);

        if($query['repertory_id'] >0){
            $role_id = 5; //配送人员
            $staffList = queryAdminStaff($query['depot_id'], $role_id);
            //dump($staffList);die;
            $this->assign('staffList',$staffList);
        }

        $this->display();
    }

	/** 其他Action **/
    public function detailAction()
    {
    	$where["order_id"]=I("get.order_id",'1');
    	//$where["org_parent_id"]=session("org_parent_id");

        // 检查车销单权限
        $depot_id = $this->_depot_id;

        if ($depot_id > 0) {

            $where["repertory_id"] = $depot_id;
        }
    	
    	$aOrder=M("car_orders")->where($where)->find();

        //print_r($aOrder);die();

        if (!$aOrder) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

    	$aOrder["time"]=date("Y-m-d H:i",$aOrder["create_time"]);
		
		$aOrder["staff_name"]=M("org_staff")->where("staff_id=".$aOrder["staff_id"])->getField("staff_name");	
		$where1["zdb_car_orders_goods.order_id"]=I("get.order_id",'');
    	//$where1["zdb_car_orders_goods.org_parent_id"]=session("org_parent_id");
    	$aGoods=M("car_orders_goods")
    	->join("zdb_goods_product on zdb_goods_product.cv_id=zdb_car_orders_goods.cv_id")
    	->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_goods_product.goods_id")
    	->where($where1)->select();
    	
        foreach($aGoods as $k=>$v){
        	$aGoods[$k]["allPrice"]=$v["singleprice"]*$v["number"];
        }
			
		
        $this->assign("aOrder",$aOrder);
        $this->assign("aGoods",$aGoods);
       
        $this->display();
    }

}

/*************************** end ************************************/