<?php

/*******************************************************************
 ** 文件名称: DepotOutController.class.php
 ** 功能描述: 系统后台商品出库控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DepotOutController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{

		// 内勤人员仓库所属
		$depotID = intval($_SESSION['depot_id']);
		$depotList = $_SESSION['depot_list'];
		
        $p=I("get.p",1);
        $pnum=I("get.pnum",10);
        $out_type=I("get.out_type",0);
        $depot_id=I("get.depot_id",0);
        $start=I("get.start_time",0);
        $out_status=I("get.out_status",0);
        $end=I("get.end_time",0);
        if($out_type!=0) $where["out_type"]=$out_type;
        if($out_status!=0) $where["out_status"]=$out_status;

		// 仓库筛选判断	
        //if($depot_id!=0) $where["depot_id"]=$depot_id;
        if($depot_id!=0)
		{
			$depot_results = M("depot_info")->where("repertory_parent = " . $depot_id)->getField('repertory_id', true);
			if(empty($depot_results))
			{
				$where["depot_id"] = $depot_id;
			}
			else
			{
				$where["depot_id"]= array('in', implode(',', $depot_results));
			}
		}
        if($depotID > 0) $where["depot_id"] = array('in', implode(',', $depotList));

		if($start!=0&&$end!=0) $where["p1.create_time"]=array(array("egt",strtotime($start)),array("elt",strtotime($end)+24*60*59));
			
		// 查询出货单数量和列表
        $total=M("depot_out")->field("depot_out_id")->table("zdb_depot_out p1")->where($where)->order("p1.create_time desc")->count();
        $aOut=M("depot_out")->field("depot_out_id,depot_out_code,depot_id,out_type,out_total_price,out_status,p1.create_time")->table("zdb_depot_out p1")->where($where)->order("p1.create_time desc")->page($p,$pnum)->select();
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        for($i=0;$i<count($aOut);$i++)
        {
            $aOut[$i]["time"]=date("Y-m-d H:i:s",$aOut[$i]["create_time"]);
            $aOut[$i]["type"]=queryDepotOutType($aOut[$i]["out_type"]);
            $aOut[$i]["status"]=queryDepotOutState($aOut[$i]["out_status"],false);
            $aOut[$i]["out_name"]=D('Depot')->getDepotName($aOut[$i]["depot_id"]);
        }

        // 仓库列表
        //$aDepot=D('Depot')->getDeoptAllList();
        $aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
        $this->assign("depot_in",$aOut);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("out_type"=>$out_type,"depot_id"=>$depot_id,"start_time"=>$start,"out_status"=>$out_status,"end_time"=>$end));
        $this->assign("aOutType",queryDepotOutType(0));//出库类型
        $this->display();
    }
	
	/** 其他Action **/
    public function addAction()
	{
		// 内勤人员仓库所属
		$depotID = intval($_SESSION['depot_id']);
		$depotList = $_SESSION['depot_list'];
		
		$org_list=queryDealer($depotID);
		$this->assign("org_list",$org_list);
		
		$aBrand = D('GoodsBrand')->field("brand_id,brand_name")->select();
		$this->assign("brand",$aBrand);
		
		$aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
		
		$date=date("Y-m-d H:i:s");
		$this->assign("date",$date);
		$this->assign("staff_name",session("true_name"));
		
		$psStafflist=M("admin_user")->where("role_id=5")->select();
		$this->assign("psStafflist",$psStafflist);
		$this->display();
	}
	
	public function addexAction()
	{
		$data["send_staff_id"]=I("post.send_staff_id",6);
        $data["out_type"]=I("post.types",'1'); // 入库类型
        $data["out_remark"]=I("post.remark",'');
        $data["depot_id"]=I("post.depot_id",1);
		$data["org_parent_id"]=I("post.org_id",1);
        //$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":5}]';
        $data["goods_info"]=json_decode($_POST["goods_info"],true);
		
       
		$data["create_id"]=session("admin_id");
		
	    $res = D("DepotOut")->addDepotOutOrder($data);
		if($res) aJsonReturn("1","添加成功");
		
		
	}
	
	public function editAction(){
		
		$depotID = intval($_SESSION['depot_id']);
		$psStafflist=M("admin_user")->where("role_id=5")->select();
		$this->assign("psStafflist",$psStafflist);
    	
		$depot_out_id=I("get.depot_out_id");
		$where["depot_out_id"]=$depot_out_id;
		$res=M("depot_out")->where($where)->find();
		$res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);
		if($depotID){
			if($depotID!=$res["depot_id"]){
				 echo "<script>alert('非法操作！');window.location='./';</script>";
				 exit;
			}
		}
		
		$create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
		$this->assign("create_name",$create_name);
		$resGoods=M("depot_out_goods")
		->field("zdb_goods_info.goods_code,zdb_depot_out_goods.*,zdb_depot_area.area_name")
		->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_out_goods.goods_id")
		->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
        ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		->where($where)->select();
		
		
		foreach($resGoods as $k=>$v){
			$where["goods_id"]=$v["goods_id"];
			$list=M("goods_product")->where($where)->select();
			$resGoods[$k]["goods_unit"]=$list;
		}
		
		
		
		$this->assign("depot_out_id",$depot_out_id);
		$this->assign("res",$res);
		$this->assign("resGoods",$resGoods);
		
    	$org_list=queryDealer($depotID);
		$this->assign("org_list",$org_list);
		$aBrand = D('GoodsBrand')->field("brand_id,brand_name")->select();
		$this->assign("brand",$aBrand);
		$aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
		
    	$this->display();
    }
    
    
	public function editexAction(){
    	//$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":7}]';
    	$data["out_type"]=I("post.types",'1'); // 入库类型
        $data["out_remark"]=I("post.remark",'');
        $data["depot_id"]=I("post.depot_id",1);
		$data["org_parent_id"]=I("post.org_id",1);
        $data["depot_out_id"]=I("post.depot_out_id",10);
		$data["send_staff_id"]=I("post.send_staff_id",0);
        $data["goods_info"]=json_decode($_POST["goods_info"],true);
		
        
		
	    $res = D("DepotOut")->EditDepotOutOrder($data);
		if($res){
			aJsonReturn("1","修改成功");
		}else{
			aJsonReturn("1","修改失败");
		} 
    }
	
	public function outPassAction(){
		
		$depotID = intval($_SESSION['depot_id']);
		$psStafflist=M("admin_user")->where("role_id=5")->select();
		$this->assign("psStafflist",$psStafflist);
    	
		$depot_out_id=I("get.depot_out_id");
		$where["depot_out_id"]=$depot_out_id;
		$res=M("depot_out")->where($where)->find();
		$res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);
		
		if($depotID){
			if($depotID!=$res["depot_id"]){
				 echo "<script>alert('非法操作！');window.location='./';</script>";
				 exit;
			}
		}
		$create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
		
		$this->assign("create_name",$create_name);
		$resGoods=M("depot_out_goods")
		->field("zdb_goods_info.goods_code,zdb_depot_out_goods.*,zdb_depot_area.area_name")
		->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_out_goods.goods_id")
		->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
        ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		->where($where)->select();		
		
		foreach($resGoods as $k=>$v){
			$where["goods_id"]=$v["goods_id"];
			$list=M("goods_product")->where($where)->select();
			$resGoods[$k]["goods_unit"]=$list;
		}
		
		
		
		$this->assign("depot_out_id",$depot_out_id);
		$this->assign("res",$res);
		$this->assign("resGoods",$resGoods);
		
    	$org_list=queryDealer($depotID);
		$this->assign("org_list",$org_list);
		$aBrand = D('GoodsBrand')->field("brand_id,brand_name")->select();
		$this->assign("brand",$aBrand);
		$aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
    	$this->display();
    }
    
    public function showAction(){
		
		$depotID = intval($_SESSION['depot_id']);
		$psStafflist=M("admin_user")->where("role_id=5")->select();
		$this->assign("psStafflist",$psStafflist);
    	
		$depot_out_id=I("get.depot_out_id");
		$where["depot_out_id"]=$depot_out_id;
		$res=M("depot_out")->where($where)->find();
		$res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);
		
		if($depotID){
			if($depotID!=$res["depot_id"]){
				 echo "<script>alert('非法操作！');window.location='./';</script>";
				 exit;
			}
		}
		$create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
		
		$this->assign("create_name",$create_name);
		$resGoods=M("depot_out_goods")
		->field("zdb_goods_info.goods_code,zdb_depot_out_goods.*,zdb_depot_area.area_name")
		->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_out_goods.goods_id")
		->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
        ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		->where($where)->select();
		
		
		foreach($resGoods as $k=>$v){
			$where["goods_id"]=$v["goods_id"];
			$list=M("goods_product")->where($where)->select();
			$resGoods[$k]["goods_unit"]=$list;
		}
		
		
		
		$this->assign("depot_out_id",$depot_out_id);
		$this->assign("res",$res);
		$this->assign("resGoods",$resGoods);
		
    	$org_list=queryDealer($depotID);
		$this->assign("org_list",$org_list);
		$aBrand = D('GoodsBrand')->field("brand_id,brand_name")->select();
		$this->assign("brand",$aBrand);
		$aDepot =  queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
		if($_GET["print"]=="print"){
			
			$this->display("print");
			exit;
		}
		
    	$this->display();
    }
	public function outPassExAction(){
		
			
			// 审核通过
			
			$depot_out_id=I("post.depot_out_id",11);
    		$where["depot_out_id"]=$depot_out_id;
    		
    		

			// 查询出库商品
			
			$aDepot=M("depot_out")->where($where)->find();
			$arr=M("depot_out_goods")->field("goods_id,cv_id,out_num")->where($where)->select();
			
			$arError['rs'] =true;
			
			// 审核减库存
			$stockData=array();
			//$mDepotStock = D('DepotStock');
			foreach($arr as $k=>$v){
				$stockData[$k]["cv_id"]=$v["cv_id"];
				$stockData[$k]["goods_id"]=$v["goods_id"];
				$stockData[$k]["small_stock"]=$v["out_num"];
				$stockData[$k]["org_parent_id"]=$aDepot["org_parent_id"];
				$stockData[$k]["depot_id"]=$aDepot["depot_id"];
				
				
				$whereDepot["depot_id"]=$aDepot["depot_id"];
				$whereDepot["goods_id"]=$v["goods_id"];
				$goodDepotStock=M("depot_stock")->where($whereDepot)->getField("small_stock");
				$smallNum = getTransUnit($v["cv_id"],$v["out_num"]);
                // 库存中以最小单位为准，检查库存
               
				if ($smallNum['good_num'] > $goodDepotStock) {
					$arError['rs'] = false;
					$arError['msg'] .= $v['goods_name'].$v['goods_spec']."\n";
				}
			}

			if ($arError['rs'] != false) {
				
				$data["out_status"]=2;
    			$data["checker_id"]=session("admin_id");
    			$data["check_time"]=time();
    			$dAffect=M("depot_out")->where($where)->save($data);
				if ($dAffect) {
					// 出库说明备注
					
					$msg = queryDepotOutType($aDepot['out_type']);
					
					D("DepotStock")->updateStock($stockData, $msg, "del", $aDepot['out_type']);
					aJsonReturn(1,"审核通过");
				} else {
					aJsonReturn(0,"审核失败");
				}
			} else {
				aJsonReturn(0,"以下商品库存不足：\n".$arError['msg']);
			}	
			
		
		
	}
	

}

/*************************** end ************************************/