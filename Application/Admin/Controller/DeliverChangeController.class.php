<?php

/*******************************************************************
 ** 文件名称: DeliverChangeController.class.php
 ** 功能描述: 系统后台配送调换货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DeliverChangeController extends BaseController {

    var $_mod_deliverChange;

    public function __construct(){
        parent::__construct();
        $this->_mod_deliverChange = new \Common\Model\DeliverChangeModel();
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
        $total = $this->_mod_deliverChange->table("zdb_car_change as cc")
            ->field('cc.*, au.depot_id, au.true_name')
            ->join('left join __ADMIN_USER__ as au on cc.staff_id = au.admin_id')
            ->where($where)
            ->count();//总数

        $list = $this->_mod_deliverChange->table("zdb_car_change as cc")
            ->field('cc.*, au.depot_id, au.true_name')
            ->join('left join __ADMIN_USER__ as au on cc.staff_id = au.admin_id')
            ->where($where)->page($p,$pnum)->select();//列表

        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/DeliverChange/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        //仓库select
        $depotList = queryDepot($depotID);
        $this->assign('depotList',$depotList);
        $this->assign('pnum',$pnum);
        if($query['repertory_id'] >0){
            $role_id = 5; //配送人员
            $staffList = queryAdminStaff($query['depot_id'], $role_id);
            //dump($staffList);die;
            $this->assign('staffList',$staffList);
        }

        $this->display();
    }
	
	/** 其他Action **/
    public function detailAction(){
		
		$change_id =I("get.change_id",1);
		
		$data=$this->_mod_deliverChange->selectChangeOrder($change_id);

        if (!$data) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

		$res=$data['res'];
		$goods_in=$data['goods_in'];
		$goods_out=$data['goods_out'];
		$this->assign("res",$res);
		$this->assign("goods_in",$goods_in);
		$this->assign("goods_out",$goods_out);
		
		$this->display();
	}

}

/*************************** end ************************************/