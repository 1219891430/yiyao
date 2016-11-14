<?php

/*******************************************************************
 ** 文件名称: DepotStockController.class.php
 ** 功能描述: 经销商PC端仓库库存控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;

class DepotStockController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
        $p = I("get.p",1);
        $pnum = I("get.pnum",10);
        $urlPara["goods_name"] = I("get.goods_name",'');
        $urlPara["brand_id"] = I("get.brand_id",0);

        if($urlPara["brand_id"]){
            $where["gi.brand_id"]=$urlPara["brand_id"];
        }
        if($urlPara["goods_name"]){
            $where["gi.goods_name"]=array("like","%".$urlPara["goods_name"]."%");
        }
        $where['org_parent_id'] = session('org_parent_id');

        
        $total=M("depot_stock")->alias("ds")
            ->join("zdb_goods_info as gi on gi.goods_id=ds.goods_id")
            ->join("zdb_goods_brand as gb on gb.brand_id=gi.brand_id")
            ->field("ds.*, gi.goods_name, gi.goods_spec, gb.brand_name")
            ->where($where)
            ->count();
            
        $list=M("depot_stock")->alias("ds")
            ->join("zdb_goods_info as gi on gi.goods_id=ds.goods_id")
            ->join("zdb_goods_brand as gb on gb.brand_id=gi.brand_id")
            ->field("ds.*, gi.goods_name, gi.goods_spec, gb.brand_name")
            ->where($where)
            ->page($p,$pnum)
            ->select();

        //print_r($list);die();

        $depotM=D("Depot");
        foreach($list as $k=>$v){
            $list[$k]["depot_name"] =$depotM->getDepotName($v["depot_id"]);
            $list[$k]["numString"]=getGoodsUnitString($v["goods_id"],$v["small_stock"]);
           
        }

        $aDepot=D('Depot')->getDeoptAllList();

        if($_GET["export"]=="export"){

            $this->BuildEXCEL($list);
        }
        $this->assign("depotList",$aDepot);

        $brandlist=D("GoodsBrand")->select();
        $this->assign("brandList",$brandlist);
        $this->assign("list",$list);
        
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',$urlPara);
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