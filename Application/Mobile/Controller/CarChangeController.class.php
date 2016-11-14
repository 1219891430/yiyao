<?php

/*******************************************************************
 ** 文件名称: CarReturnController.class.php
 ** 功能描述: 配送调换货接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarChangeController extends Controller {
	
	
     public function detailAction(){
		
		$change_id =I("change_id",1);
		$deliverChange= new \Common\Model\DeliverChangeModel();
		$data=$deliverChange->selectChangeOrder($change_id);
//		$res=$data['res'];
//		$goods_in=$data['goods_in'];
//		$goods_out=$data['goods_out'];

        
		
		
		$orderInfo["order_id"]=$data['res']["change_id"];
		$orderInfo["order_code"]=$data['res']["change_code"];
		$orderInfo["org_parent_id"]=$data['res']["org_parent_id"];
		$orderInfo["cust_id"]=$data['res']["cust_id"];
		$orderInfo["cust_name"]=$data['res']["cust_name"];
		$orderInfo["cust_address"]=$data['res']["cust_address"];
		$orderInfo["order_total_money"]=$data['res']["total_money"];
		$orderInfo["create_time"]=$data['res']["create_time"];
		$orderInfo["order_way"]=$data['res']["pay_type"];
		$orderInfo["order_remark"]=$data['res']["change_remark"];
		
		$goodsIn=array();
		
		
		foreach($data['goods_in'] as $k=>$v){
			$goodsIn[$k]["cv_id"]=$v["cv_id"];
			$goodsIn[$k]["goods_id"]=$v["goods_id"];
			$goodsIn[$k]["goods_name"]=$v["goods_name"];
			$goodsIn[$k]["goods_spec"]=$v["goods_spec"];
			$goodsIn[$k]["singleprice"]=$v["singleprice"];
			$goodsIn[$k]["number"]=$v["number"];
			$goodsIn[$k]["unit_name"]=$v["goods_unit"];
		}
		$goodsOut=array();
		foreach($data['goods_out'] as $k=>$v){
			$goodsOut[$k]["cv_id"]=$v["cv_id"];
			$goodsOut[$k]["goods_id"]=$v["goods_id"];
			$goodsOut[$k]["goods_name"]=$v["goods_name"];
			$goodsOut[$k]["goods_spec"]=$v["goods_spec"];
			$goodsOut[$k]["singleprice"]=$v["singleprice"];
			$goodsOut[$k]["number"]=$v["number"];
			$goodsOut[$k]["unit_name"]=$v["goods_unit"];
		}
		
	    if($data['res']){
			 $data1["orderInfo"]=$orderInfo;
		     $data1["orderGoodsIn"]=$goodsIn;
			 $data1["orderGoodsOut"]=$goodsOut;
			 $res["error"]=-1;
			 $res['msg']="成功";
			 $res["data"]=$data1;
		}else{
			$res["error"]=1;
			$res['msg']="失败";
		}
       
		echo json_encode($res);
	}
	
}