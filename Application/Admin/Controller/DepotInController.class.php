<?php

/*******************************************************************
 ** 文件名称: DepotInController.class.php
 ** 功能描述: 系统后台商品入库控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DepotInController extends BaseController {

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
		
		$p = I("get.p",1);
		$pnum = I("get.pnum",10);
		$in_type = I("get.in_type",0);
		$depot_id = I("get.depot_id",0);
		$in_status = I("get.in_status",0);
		$start = I("get.start_time",0);
		$end = I("get.end_time",0);
		if($in_type!=0) $where["in_type"]=$in_type;
        if($in_status!=0) $where["in_status"]=$in_status;
		
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
        
		if($start!=0 && $end!=0) $where["create_time"]=array(array("egt",strtotime($start)),array("elt",strtotime($end)+24*60*59));
		
		// 查询入库单
        $total = M("depot_in")->where($where)->count();
		$aIn = M("depot_in")->field("depot_in_id,depot_in_code,depot_id,in_type,in_status,create_time,create_id")->where($where)->order("create_time desc")->page($p,$pnum)->select();
		$page = get_page_code($total,$pnum,$p, $page_code_len = 5);
		
		for($i=0;$i<count($aIn);$i++)
        {
            $aIn[$i]["time"]=date("Y-m-d H:i:s",$aIn[$i]["create_time"]);
            $aIn[$i]["type"]=queryDepotInType($aIn[$i]["in_type"]);
            $aIn[$i]["status"]=queryDepotInState($aIn[$i]["in_status"]);
            $aIn[$i]["in_name"]=D('Depot')->getDepotName($aIn[$i]["depot_id"]);
            $aIn[$i]["create_name"]=M('admin_user')->where("admin_id=".$aIn[$i]["create_id"])->getField("true_name");
            
        }
        
        // 仓库列表
        //$aDepot = D('Depot')->getDeoptAllList();
        //$this->assign("depotList",$aDepot);
        $aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
		
		// 返回页面
		$this->assign("depot_in",$aIn);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign("inType",queryDepotInType(0)); // 入库单所有类型
        $this->assign('urlPara',array("in_type"=>$in_type,"in_status"=>$in_status,"depot_id"=>$depot_id,"start_time"=>$start,"end_time"=>$end));
        $this->display();
    }
	
	/** 其他Action **/

    // 添加入库信息
	// 检查人员: 
	// 检查日期: 
	public function addAction() {
		
		// 内勤人员仓库所属
		$depotID = intval($_SESSION['depot_id']);
		$depotList = $_SESSION['depot_list'];
		
		$org_list=$org_list=queryDealer($depotID);
		$this->assign("org_list",$org_list);

        // 库存里该经销商的产品

		$aBrand = queryBrand();
		
		$this->assign("brand",$aBrand);
		
		
		$aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
		
		
		$date=date("Y-m-d H:i:s");
		$this->assign("date",$date);
		$this->assign("staff_name",session("true_name"));
		$this->display();
	}
	
	public function getBrandByOrgAction(){
		$org_id=I("post.org_id",1);
		$list=queryBrandByOrg($org_id);
		if($list){
			$res["list"]=$list;
			$res["code"]=1;
			echo json_encode($res);
		}else{
			$res["code"]=0;
			echo json_encode($res);
		}
	}
	
	public function addexAction()
	{
		$depotID = intval($_SESSION['depot_id']);
        $data["in_type"]=I("post.types",'1'); // 入库类型
        $data["in_remark"]=I("post.remark",'');
        $data["depot_id"]=I("post.depot_id",1);
		$data["org_parent_id"]=I("post.org_id",1);
		
		if($depotID){
			if(!$depotID==$data["depot_id"]){
				aJsonReturn("0","非法操作");
				return;
			}
		}
        
        $data["goods_info"]=json_decode($_POST["goods_info"],true);
		
        $data["staff_id"]=session("admin_id");
		$data["create_id"]=session("admin_id");
		
	    $res = D("DepotIn")->addDepotInOrder($data);
		if($res) aJsonReturn("1","添加成功");
		
		
	}


    public function editAction(){
    	$depotID = intval($_SESSION['depot_id']);
    	
		$depot_in_id=I("get.depot_in_id");
		$where["depot_in_id"]=$depot_in_id;
		$res=M("depot_in")->where($where)->find();
		$res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);
		if($depotID){
			if($depotID!=$res["depot_id"]){
				 echo "<script>alert('非法操作！');window.location='./';</script>";
				 exit;
			}
		}
		
		$create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
		$this->assign("create_name",$create_name);
		$resGoods=M("depot_in_goods")
		 ->field("zdb_goods_info.goods_code,zdb_depot_in_goods.*,zdb_depot_area.area_name")
		 ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_in_goods.goods_id")
		 ->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
        ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		 ->where($where)->select();
		
		
		foreach($resGoods as $k=>$v){
			$where["goods_id"]=$v["goods_id"];
			$list=M("goods_product")->where($where)->select();
			foreach($list as $kk=>$vv){
				$arrData=array();
				$arrData["cv_id"]=$vv["cv_id"];
				$arrData["goods_unit"]=$vv["goods_unit"];
				$list[$kk]["json"]=json_encode($arrData);
			}
			
			$resGoods[$k]["goods_unit"]=$list;
		}
		$this->assign("depot_in_id",$depot_in_id);
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
    	$depotID = intval($_SESSION['depot_id']);
    	//$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":7}]';
    	$data["in_type"]=I("post.types",'1'); // 入库类型
        $data["in_remark"]=I("post.remark",'');
        $data["depot_id"]=I("post.depot_id",1);
		$data["org_parent_id"]=I("post.org_id",1);
        $data["depot_in_id"]=I("post.depot_in_id",31);
        $data["goods_info"]=json_decode($_POST["goods_info"],true);
		
        if($depotID){
			if(!$depotID==$data["depot_id"]){
				aJsonReturn("0","非法操作");
				return;
			}
		}
		
	    $res = D("DepotIn")->EditDepotInOrder($data);
		if($res){
			aJsonReturn("1","修改成功");
		}else{
			aJsonReturn("1","修改失败");
		} 
    }
	
	public function inPassAction(){
		$depotID = intval($_SESSION['depot_id']);
		$depot_in_id=I("get.depot_in_id");
		$where["depot_in_id"]=$depot_in_id;
		$res=M("depot_in")->where($where)->find();
		$res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);
		if($depotID){
			if($depotID!=$res["depot_id"]){
				 echo "<script>alert('非法操作！');window.location='./';</script>";
				 exit;
			}
		}
		
		$create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
		$this->assign("create_name",$create_name);
		$resGoods=M("depot_in_goods")
		 ->field("zdb_goods_info.goods_code,zdb_depot_in_goods.*,zdb_depot_area.area_name")
		 ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_in_goods.goods_id")
		 ->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
        ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		 ->where($where)->select();
		
		
		foreach($resGoods as $k=>$v){
			$where["goods_id"]=$v["goods_id"];
			$list=M("goods_product")->where($where)->select();
			$resGoods[$k]["goods_unit"]=$list;
		}
		$this->assign("depot_in_id",$depot_in_id);
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
   // 审核通过
	// 检查人员: richie 
	// 检查日期: 2016-05-17
    public function inpassExAction()
    {
    	
          // 修改为审核状态
			$where["depot_in_id"]=I("post.depot_in_id",24);
    		
    		$data["in_status"]=2; // 审核通过
    		$data["checker_id"]=session("admin_id");
    		$data["checker_time"]=time();
    		$dAffect=M("depot_in")->where($where)->save($data);
			
			// 数据错误
			if(!$dAffect) {
				 aJsonReturn(0,"数据错误");
				 exit; 
			}
			
			$aDepot=M("depot_in")->where($where)->find();
			$arr=M("depot_in_goods")->field("goods_id,cv_id,in_num")->where($where)->select();// 查询入库商品
			
			
			
			
			// 更新仓库库存
			$data=array();
			foreach ($arr as $k=>&$v)
			{
				$data[$k]["goods_id"]=$v["goods_id"];
				$data[$k]["cv_id"]=$v["cv_id"];
				$data[$k]["small_stock"]=$v["in_num"];
				$data[$k]["org_parent_id"]=$aDepot["org_parent_id"];
				$data[$k]["depot_id"]=$aDepot["depot_id"];
				if( $aDepot["in_type"]==1){
					$this->addGoodsPrice($v["goods_id"], $aDepot["org_parent_id"]);
				}
			}
			
			// 备注日志说明
			$in_type = $aDepot["in_type"];
			$inType = queryDepotInType($in_type);
			
			// 修改库存并添加日志
			
			D("DepotStock")->updateStock($data, $inType, "add",$in_type);
			aJsonReturn(1,"审核通过");
    	

    	
    }

    private function addGoodsPrice($goods_id,$org_parent_id){
    	$where["goods_id"]=$goods_id;
		$res=M("org_goods_convert")->where($where)->where("org_parent_id=$org_parent_id")->count();
		if(!$res){
			$product=M("goods_product")->where($where)->select();
		
			foreach($product as $k=>$v){
			$product[$k]["org_parent_id"]=$org_parent_id;
			}
    		M("org_goods_convert")->addAll($product);
		}
    	
    }
	
    

    public function showAction(){
    	$depotID = intval($_SESSION['depot_id']);
		$depot_in_id=I("get.depot_in_id");
		$where["depot_in_id"]=$depot_in_id;
		$res=M("depot_in")->where($where)->find();
		if($depotID){
			if($depotID!=$res["depot_id"]){
				 echo "<script>alert('非法操作！');window.location='./';</script>";
				 exit;
			}
		}
		
		
		$res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);
		
		
		$create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
		$this->assign("create_name",$create_name);
		$resGoods=M("depot_in_goods")
		->field("zdb_goods_info.goods_code,zdb_depot_in_goods.*,zdb_depot_area.area_name")
		->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_in_goods.goods_id")
		->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
        ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		->where($where)->select();
		
		
		foreach($resGoods as $k=>$v){
			$where["goods_id"]=$v["goods_id"];
			$list=M("goods_product")->where($where)->select();
			$resGoods[$k]["goods_unit"]=$list;
		}
		$this->assign("depot_in_id",$depot_in_id);
		$this->assign("res",$res);
		$this->assign("resGoods",$resGoods);
		
    	$org_list=queryDealer($depotID);
		$this->assign("org_list",$org_list);
		$aBrand = D('GoodsBrand')->field("brand_id,brand_name")->select();
		$this->assign("brand",$aBrand);
		$aDepot = queryDepotTree($depotID);
		$this->assign("depotList",$aDepot);
		
		if($_GET["print"]=="print"){
			$this->display("print");
			exit;
		}
    	$this->display();
	}


}

/*************************** end ************************************/