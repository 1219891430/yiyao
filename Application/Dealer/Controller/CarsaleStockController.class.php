<?php

/*******************************************************************
 ** 文件名称: CarsaleStockController.class.php
 ** 功能描述: 经销商PC端车销实时库存控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CarsaleStockController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		
        $where["zdb_carsale_stock.org_parent_id"]=session("org_parent_id");
        $p=I("get.p",1);
        $pnum=I("get.pnum",10);
        $staff_id=I("get.staff_id",0);
        $goods_name=I("get.name","@@");
        if($goods_name!="@@")
            $where["zdb_goods_info.goods_name"]=array("like","%{$goods_name}%");
        if($staff_id!=0)
            $where["zdb_carsale_stock.staff_id"]=$staff_id;



        
        $aInfo=M("carsale_stock")
        ->field("zdb_carsale_stock.goods_id,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_carsale_stock.goods_num,zdb_carsale_stock.staff_id,zdb_goods_info.goods_convert_s,zdb_goods_info.goods_convert_m,zdb_goods_info.goods_convert_b")
        ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_carsale_stock.goods_id")
        ->where($where)
        // ->having("goods_num<>0")//显示等于0的商品
        ->page($p,$pnum)->select();
        $total=M("carsale_stock")     
        ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_carsale_stock.goods_id")
        ->where($where)
        // ->having("goods_num<>0")
        ->select();
        $total = count($total);
		$mGc=M("goods_product");
        foreach($aInfo as &$v)
        {
        	$v["goods_name"]=$v['goods_name']."/".$v['goods_spec'];     
        	
        	 $bigNumber=$v["goods_convert_m"]*$v["goods_convert_b"];
            $inNumber=$v["goods_convert_m"];
           
            $aUnit=$mGc->where("goods_id=%d",array($v["goods_id"]))->order("goods_unit_type asc")->getField("goods_unit",true);
            $v["small_stock"]= $v["goods_num"].$aUnit[0];
            if($v["small_stock"]/$inNumber>0){
            	$v["in_stock"]=floor($v["small_stock"]/$inNumber).$aUnit[1];
            }else{
            	$v["in_stock"]=ceil($v["small_stock"]/$inNumber).$aUnit[1];
            }
            if($v["small_stock"]/$bigNumber>0){
            	$v["big_stock"]=floor($v["small_stock"]/$bigNumber).$aUnit[2];
            }else{
            	$v["big_stock"]=ceil($v["small_stock"]/$bigNumber).$aUnit[2];
            }
            
        
            $v["staff_name"]=M("org_staff")->where("staff_id=%d",array($v["staff_id"]))->getField("staff_name");
            $v["read_stock"]=getGoodsUnitString($v["goods_id"], (int)$v["small_stock"]);
			
		 }
        
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        
        $aStaff=M("org_staff")->where("org_parent_id=".session("org_parent_id")." and role_id=3")->select();
        $this->assign("aStaff",$aStaff);
        $this->assign("aInfo",$aInfo);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("staff_id"=>$staff_id,"name"=>$goods_name));
        $this->display();
		
    }
    
    
	
	public function recordAction(){
        $p=I("get.page",1);
        $goods_id=I("get.goods");
        $staff_id=I("get.staff");
        $where["zdb_carsale_stock_log.goods_id"]=$goods_id;
        $where["zdb_carsale_stock_log.staff_id"]=$staff_id;
        $total=M("carsale_stock_log")->field("zdb_carsale_stock_log.*,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_org_staff.staff_name")->
        join("zdb_goods_info on zdb_goods_info.goods_id=zdb_carsale_stock_log.goods_id")->
        join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsale_stock_log.staff_id")->
        where($where)->count();
        $list=M("carsale_stock_log")->field("zdb_carsale_stock_log.*,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_org_staff.staff_name")->
        join("zdb_goods_info on zdb_goods_info.goods_id=zdb_carsale_stock_log.goods_id")->
        join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsale_stock_log.staff_id")->
        where($where)->page($p,10)->order("datetime desc")->select();
        
        foreach($list as $k=>$v){
            $where1["goods_id"]=$v["goods_id"];
            $where1["goods_unit_type"]=1;
            $cv_id=M("org_goods_convert")->where($where1)->getField("cv_id");
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
	


	/** 其他Action **/
    
    //获取业务员车库的产品通过，业务员id
    public function getCarportGoodsAction()
    {
        $mCpGoods=new \Common\Model\CarportInfoModel();
        $staff_id=I("post.staff_id",2);
        $aCpGoods=$mCpGoods->getCarportGoods(session("org_parent_id"),$staff_id);
        for($i=0;$i<count($aCpGoods);$i++)
        {
        	$res=M("goods_info")->where("goods_id=".$aCpGoods[$i]["goods_id"])->find();
        	$aCpGoods[$i]["goods_code"]=$res["goods_code"];
			$inNumber=$res["goods_convert_m"]; 
			$bigNumber=$res["goods_convert_m"]*$res["goods_convert_b"];
            $aCpGoods[$i]["goods_unit"]=M("org_goods_convert")->field("cv_id,goods_unit,goods_unit_type,unit_default")->where("goods_id=%d",array($aCpGoods[$i]["goods_id"]))->select();
			
            for($j=0;$j<count($aCpGoods[$i]["goods_unit"]);$j++)
            {
                $arr=$aCpGoods[$i]["goods_unit"][$j];
				$arr["goods_code"]=$res['goods_code'];
                if($arr["goods_unit_type"]==1)
                    $arr["num"]=$aCpGoods[$i]["goods_num"];
                else if($aCpGoods[$i]["goods_unit"][$j]["goods_unit_type"]==2)
                    $arr["num"]=floor($aCpGoods[$i]["goods_num"]/$inNumber);
                else if($aCpGoods[$i]["goods_unit"][$j]["goods_unit_type"]==3)
                    $arr["num"]=floor($aCpGoods[$i]["goods_num"]/$bigNumber);
                $aCpGoods[$i]["goods_unit"][$j]=$arr;
            }
        }
        

        if($aCpGoods)
            echo $this->ajaxReturn(array("res"=>1,"data"=>$aCpGoods),"json");
        else
            echo $this->ajaxReturn(array("res"=>0),"json");

    }    

}

/*************************** end ************************************/