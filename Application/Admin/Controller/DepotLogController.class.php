<?php

/*******************************************************************
 ** 文件名称: DepotLogController.class.php
 ** 功能描述: 系统后台仓库日志控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DepotLogController extends BaseController {

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
        $wArr["goods_name"]=I("get.goods_name",'');
        $wArr["depot_id"] = $depot_id =I("get.depot_id",0);
		
		// 仓库筛选判断	
		//if($wArr["depot_id"]){ $where["depot_id"]=$wArr["depot_id"]; }		
        if($depot_id!=0)
		{
			$depot_results = M("depot_info")->where("repertory_parent = " . $depot_id)->getField('repertory_id', true);
			if(empty($depot_results))
			{
				$where["zdb_depot_stock_log.depot_id"] = $depot_id;
			}
			else
			{
				$where["zdb_depot_stock_log.depot_id"]= array('in', implode(',', $depot_results));
			}
		}
        if($depotID > 0) $where["zdb_depot_stock_log.depot_id"] = array('in', implode(',', $depotList));

		if($wArr["goods_name"]){
		    $where["zdb_goods_info.goods_name"]=array("like","%".$wArr["goods_name"]."%");
		}
		
		$aDepot=queryDepotTree($depotID);
        $this->assign("depotList",$aDepot);
		
		$list=M("depot_stock_log")->field("zdb_depot_stock_log.*,zdb_goods_info.goods_name,zdb_goods_info.goods_spec")->where($where)->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_stock_log.goods_id")->page($p,$pnum)->order("datetime desc")->select();
		
		$total=M("depot_stock_log")->where($where)->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_stock_log.goods_id")->count();
		foreach($list as $k=>$v){
			$list[$k]["depot_name"]=D("Depot")->getDepotName($v["depot_id"]);
			
			$list[$k]["org_name"]=M("org_info")->where("org_id=".$v["org_parent_id"])->getField("org_name");
			
		}
		
		$this->assign("list",$list);
		
		$page=get_page_code($total,$pnum,$p, $page_code_len = 5);
		$this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("goods_name"=>$wArr["goods_name"],"depot_id"=>$wArr["depot_id"]));
		
		$this->display();
    }
	
	/** 其他Action **/


}

/*************************** end ************************************/