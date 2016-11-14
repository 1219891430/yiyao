<?php

/*******************************************************************
 ** 文件名称: DepotStockController.class.php
 ** 功能描述: 系统后台仓库库存控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;

class DepotStockController extends BaseController {

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
        $wArr["depot_id"] = $depot_id = I("get.depot_id",0);
        $wArr["brand_id"]=I("get.op_brand",0);
		$wArr["org_id"]=I("get.org_id",0);
		
		if($wArr["brand_id"]){
		    $where["zdb_goods_info.brand_id"]=$wArr["brand_id"];
		}

		// 仓库筛选判断	
		//if($wArr["depot_id"]){ $where["zdb_depot_stock.depot_id"]=$wArr["depot_id"]; }	
        if($depot_id!=0)
		{
			$depot_results = M("depot_info")->where("repertory_parent = " . $depot_id)->getField('repertory_id', true);
			if(empty($depot_results))
			{
				$where["zdb_depot_stock.depot_id"] = $depot_id;
			}
			else
			{
				$where["zdb_depot_stock.depot_id"]= array('in', implode(',', $depot_results));
			}
		}
        if($depotID > 0) $where["zdb_depot_stock.depot_id"] = array('in', implode(',', $depotList));
		
		if($wArr["goods_name"]){
		    $where["zdb_goods_info.goods_name"]=array("like","%".$wArr["goods_name"]."%");
		}
		if($wArr["org_id"]){
			$where["zdb_depot_stock.org_parent_id"]=$wArr["org_id"];
		}
		
		$total=M("depot_stock")
		      ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_stock.goods_id")
		      ->join("zdb_goods_brand on zdb_goods_brand.brand_id=zdb_goods_info.brand_id")
			  ->where($where)
		      ->count();
		$list=M("depot_stock")->field("zdb_depot_stock.*,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_goods_brand.brand_name")
		      ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_stock.goods_id")
		      ->join("zdb_goods_brand on zdb_goods_brand.brand_id=zdb_goods_info.brand_id")
			  ->where($where)
			  ->page($p,$pnum)
		      ->select();
			  
		$depotM=D("Depot");
		foreach($list as $k=>$v){
			
			$list[$k]["depot_name"] =$depotM->getDepotName($v["depot_id"]);
			$list[$k]["numString"]=getGoodsUnitString($v["goods_id"],$v["small_stock"]);
			
		}
			  
	    $aDepot=queryDepotTree($depotID);
	    
		if($_GET["export"]=="export"){
        	
        	$this->BuildEXCEL($list);
        }
        $this->assign("depotList",$aDepot);
		
		$brandlist=D("GoodsBrand")->select();
		
		$this->assign("brandList",$brandlist);

        //经销商
		$orgList = queryDealer($depotID);
		$this->assign("orgList",$orgList);
		
		
		$this->assign("list",$list);
		
		
		$page=get_page_code($total,$pnum,$p, $page_code_len = 5);
		$this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("goods_name"=>$wArr["goods_name"],"depot_id"=>$wArr["depot_id"],"op_brand"=>$wArr["brand_id"]));
		$this->display();
    }


    // Excel导出数据
	private function BuildEXCEL($data)
	{
		$depot=$data;
		$EXCELBuilder=EXCELBuilder::getInstance();
		$i=1;
		$v="商品库存";
		$EXCELBuilder->setCellValue(0, "A".$i, $v);
		$EXCELBuilder->mergeCells(0, "A$i:F$i");

		$i++;
		$d_start=$i;
		$EXCELBuilder->setCellValue(0, "A".$i, "行号");
		$EXCELBuilder->setCellValue(0, "B".$i, "仓库名称");
		$EXCELBuilder->setCellValue(0, "C".$i, "品牌");
		$EXCELBuilder->setCellValue(0, "D".$i, "商品名称");
		$EXCELBuilder->setCellValue(0, "E".$i, "库存合计");		
		$EXCELBuilder->setCellValue(0, "F".$i, "库存小单位");
		
		
		$i++;
		$n=1;
		
		
		foreach($depot as $v){
			$EXCELBuilder->setCellValue(0, "A".$i, $n);
			$EXCELBuilder->setCellValueExplicit(0, "B".$i,$v['depot_name']);
			$EXCELBuilder->setCellValue(0, "C".$i, $v['brand_name']);
			
			$EXCELBuilder->setCellValue(0, "D".$i, $v['goods_name']."/".$v["goods_spec"]);
			$EXCELBuilder->setCellValue(0, "E".$i, $v['numString']);
			$EXCELBuilder->setCellValue(0, "F".$i, $v['small_stock']);
			
			
			$i++;
			$n++;
		}
		
		$d_end=$i-1;

		$EXCELBuilder->setOutlineBorder(0, "A2:F$d_end","thick");
		$EXCELBuilder->setInsideBorder(0, "A2:F$d_end","thin");
		$EXCELBuilder->setHorizontal(0, "A1:F1", "center");
		$EXCELBuilder->setHorizontal(0, "A$d_start:F$d_end", "center");
		$EXCELBuilder->setFontSize(0, "A1:F1", 20);
		$EXCELBuilder->setFontSize(0, "A2:F$i", 14);
		
		$EXCELBuilder->setColumnWidth(0, A, 15);
		$EXCELBuilder->setColumnWidth(0, B, 30);
		$EXCELBuilder->setColumnWidth(0, C, 30);
		$EXCELBuilder->setColumnWidth(0, D, 40);
		$EXCELBuilder->setColumnWidth(0, E, 15);
		$EXCELBuilder->setColumnWidth(0, F, 15);
		
		

		$EXCELBuilder->FileOutput(0,"商品库存");
	}
	
	/** 其他Action **/


}

/*************************** end ************************************/