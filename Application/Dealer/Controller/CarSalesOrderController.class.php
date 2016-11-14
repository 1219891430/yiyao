<?php

/*******************************************************************
 ** 文件名称: CarSalesOrderController.class.php
 ** 功能描述: 经销商PC端车销单控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CarSalesOrderController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{

		$mCo=M("carsale_orders");
        $where["zdb_carsale_orders.org_parent_id"]=session("org_parent_id");
        $p=I("get.p",1);
        $pnum=I("get.pnum",10);
        $staff_id=I("get.staff_id",0);
        $start=I("get.start_time",0);
        $end=I("get.end_time",0);
        $status=I("get.status",0);
        $way=I("get.way",0);
        $cust_name=I("get.name","@@");
        if($cust_name!="@@")
            $where["zdb_carsale_orders.cust_name"]=array("like","%{$cust_name}%");
        if($staff_id!=0)
            $where["zdb_carsale_orders.staff_id"]=$staff_id;
        if($start!=0&&$end!=0)
            $where["zdb_carsale_orders.create_time"]=array(array("egt",strtotime($start)),array("elt",strtotime($end)+24*60*59));
        
        if($way!=0)
            $where["zdb_carsale_orders.order_way"]=$way;
            
        $total=$mCo->join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsale_orders.staff_id")->field("order_id")->where($where)->order("create_time desc")->count();
        $aOrder=$mCo->join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsale_orders.staff_id")->where($where)->order("create_time desc")->page($p,$pnum)->select();

        //echo M()->getlastsql();
        for($i=0;$i<count($aOrder);$i++)
        {
            $aOrder[$i]["time"]=date("Y-m-d H:i:s",$aOrder[$i]["create_time"]);

            if(!$aOrder[$i]["order_way"]){
            	$aOrder[$i]["way_name"] = '陈列兑付';
            }else{
            	$aOrder[$i]["way_name"]=getCarSalesWay($aOrder[$i]["order_way"],false);
            }

            $aOrder[$i]["status_name"]=getCarSalesStatus($aOrder[$i]["order_status"],false);
        }
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        
        $where1["zdb_org_staff.org_parent_id"]=session("org_parent_id");
		$where1["role_id"]=3;
		$aStaff=M("org_staff")->field("zdb_org_staff.staff_id,staff_name")->where($where1)->select();
        $this->assign("aStaff",$aStaff);
        $this->assign("aOrder",$aOrder);
                // p($aOrder);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('aWay',getCarSalesWay(0,true));
        $this->assign("aStatus",getCarSalesStatus(0,true));
        $this->assign('urlPara',array("staff_id"=>$staff_id,"status"=>$status,"way"=>$way,"start_time"=>$start,"end_time"=>$end,"name"=>$cust_name,'del'=>$del));
        $this->assign("del",$del);
		$this->display();
    }


	/** 其他Action **/
	public function detailAction()
    {
		

    	
    	$where["order_id"]=I("get.code",'1');
    	$where["org_parent_id"]=session("org_parent_id");
		
    	
    	$aOrder=M("carsale_orders")->where($where)->find();
    	$aOrder["time"]=date("Y-m-d H:i",$aOrder["create_time"]);
		
		$aOrder["staff_name"]=M("org_staff")->where("staff_id=".$aOrder["staff_id"])->getField("staff_name");	
		$where1["zdb_carsale_orders_goods.order_id"]=I("get.code",'');
    	$where1["zdb_carsale_orders_goods.org_parent_id"]=session("org_parent_id");
    	$aGoods=M("carsale_orders_goods")
    	->join("zdb_goods_product on zdb_goods_product.cv_id=zdb_carsale_orders_goods.cv_id")
    	->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_goods_product.goods_id")
    	->where($where1)->select();
    	
        foreach($aGoods as $k=>$v){
        	$aGoods[$k]["allPrice"]=$v["singleprice"]*$v["number"];
        }
			
		
//  	$order_way = array('1'=>'预付结算','2'=>'货到付款','3'=>'月度结算','4'=>'账期结算');
//  	 
//  	$order_type = array('1'=>'普通销售','3' => '陈列支付','4' =>'预存款销售');
//  	 
//  	
//  	
//  	
//  	$aOrder['order_typetext'] =$order_type[$aOrder['order_type']];    	 
//  	$aOrder['order_waytext'] = $order_way[$aOrder['order_way']];
        $this->assign("aOrder",$aOrder);
        $this->assign("aGoods",$aGoods);

        //print_r($aGoods);
       
        $this->display();
    }


}

/*************************** end ************************************/