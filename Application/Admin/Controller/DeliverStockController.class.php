<?php

/*******************************************************************
 ** 文件名称: DeliverStockController.class.php
 ** 功能描述: 系统后台配送实时车存控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DeliverStockController extends BaseController {

    var $_mod_deliverStock;

    public function __construct(){
        parent::__construct();
        $this->_mod_deliverStock = new \Common\Model\DeliverStockModel();
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
        $query['goods_name'] = I('get.goods_name');
        $this->assign('query', $query);

        if($depotID>0) {
            $where["au.depot_id"] = array("eq",$depotID);
        }
        else {
            if (!empty($query["repertory_id"])) {
                $where["au.depot_id"] = array("eq", $query["repertory_id"]);
            }
        }

        if (!empty($query["staff_id"])){
            $where["staff_id"] = array("eq",$query["staff_id"]);
        }
        if (!empty($query["goods_name"])){
            $where["goods_name"] = array("like","%{$query["goods_name"]}%");
        }

        //列表数据
        $total = $this->_mod_deliverStock->table("zdb_car_stock as cs")
            ->field('cs.*, au.depot_id, au.true_name,zdb_goods_info.goods_convert_m,zdb_goods_info.goods_convert_b,zdb_goods_info.goods_convert_s')
            ->join('left join __ADMIN_USER__ as au on cs.staff_id = au.admin_id')
			->join("zdb_goods_info on zdb_goods_info.goods_id=cs.goods_id")
            ->where($where)
            ->count();//总数

        $list = $this->_mod_deliverStock->table("zdb_car_stock as cs")
            ->field('cs.*, au.depot_id, au.true_name,zdb_goods_info.goods_convert_m,zdb_goods_info.goods_convert_b,zdb_goods_info.goods_convert_s')
            ->join('left join __ADMIN_USER__ as au on cs.staff_id = au.admin_id')
			->join("zdb_goods_info on zdb_goods_info.goods_id=cs.goods_id")
            ->where($where)
            ->page($p,$pnum)
            ->select();//列表
        
        
		
        foreach($list as &$v){
        	$v["goods_name"]=$v['goods_name']."/".$v['goods_spec'];     
        	
        	$bigNumber=$v["goods_convert_m"]*$v["goods_convert_b"];
            $inNumber=$v["goods_convert_m"];
            
            $aUnit=M("goods_product")->where("goods_id=%d",array($v["goods_id"]))->order("goods_unit_type asc")->getField("goods_unit",true);
            $v["small_stock"]= $v["goods_num"].$aUnit[0];
            if($v["goods_num"]/$inNumber>0){
            	$v["in_stock"]=floor($v["goods_num"]/$inNumber).$aUnit[1];
            }else{
            	$v["in_stock"]=ceil($v["goods_num"]/$inNumber).$aUnit[1];
            }
            if($v["goods_num"]/$bigNumber>0){
            	$v["big_stock"]=floor($v["goods_num"]/$bigNumber).$aUnit[2];
            }else{
            	$v["big_stock"]=ceil($v["goods_num"]/$bigNumber).$aUnit[2];
            }
            
        
            $v["read_stock"]=getGoodsUnitString($v["goods_id"], (int)$v["small_stock"]);
        }
        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/DeliverStock/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
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
        $this->assign('pnum',$pnum);
		$this->display();
    }


	public function recordAction(){

        $p=I("get.page",1);
        $goods_id=I("get.goods");
        $staff_id=I("get.staff");
        $where["zdb_car_stock_log.goods_id"]=$goods_id;
        $where["zdb_car_stock_log.staff_id"]=$staff_id;

        /*// 权限内仓库下
        if ($depot_id > 0) {
            $where["zdb_admin_user.depot_id"] = $depot_id;
        }*/


        $total=M("car_stock_log")->field("zdb_car_stock_log.*,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_admin_user.true_name as staff_name")->
        join("zdb_goods_info on zdb_goods_info.goods_id=zdb_car_stock_log.goods_id")->
        join("zdb_admin_user on zdb_admin_user.admin_id=zdb_car_stock_log.staff_id")->
        where($where)->count();
        $list=M("car_stock_log")->field("zdb_car_stock_log.*,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_admin_user.true_name as staff_name")->
        join("zdb_goods_info on zdb_goods_info.goods_id=zdb_car_stock_log.goods_id")->
        join("zdb_admin_user on zdb_admin_user.admin_id=zdb_car_stock_log.staff_id")->
        where($where)->page($p,10)->order("datetime desc")->select();
        
        foreach($list as $k=>$v){
            $where1["goods_id"]=$v["goods_id"];
            $where1["goods_unit_type"]=1;
            $cv_id=M("goods_product")->where($where1)->getField("cv_id");
            $bigstock=getTransUnitToBig($cv_id,$v["goods_num"]);
        
            $list[$k]["big_stock"]=$bigstock["good_num"].$bigstock["goods_unit"];
            $midstock=getTransUnitToMid($cv_id,$v["goods_num"]);
            $list[$k]["mid_stock"]=$midstock["good_num"].$midstock["goods_unit"];
            $smallstock=getTransUnit($cv_id,$v["goods_num"]);
            $list[$k]["small_stock"]=$smallstock["good_num"].$smallstock["goods_unit"];
            $list[$k]["bianhua"]=$v["bianhua"].$smallstock["goods_unit"];
        
        }
        $page=get_page_code($total,10,$p, $page_code_len = 5);
        $this->assign('page',$page);
        $this->assign("list",$list);
        $this->assign('goods_id',$goods_id);
        $this->assign("staff_id",$staff_id);
        $this->display();
    }

    

    //获取仓库下的业务员
    public function getRoleStaffAction()
    {
        $repertory_id = I('repertory_id');
        $role_id = 5; //配送人员
        if ($repertory_id > 0) {
            $data = queryAdminStaff($repertory_id,$role_id);
            $this->ajaxReturn(array('status'=>true,'rows'=>$data));
            return;
        }
        $this->ajaxReturn(array('status'=>false));
    }
	
	/** 其他Action **/


}

/*************************** end ************************************/