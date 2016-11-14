<?php

/*******************************************************************
 ** 文件名称: CarOrderController.class.php
 ** 功能描述: 配送订单接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarOrderController extends Controller {
	
	public function detailAction()
    {
        $order_id = I("orderId");

    	$where["order_id"] = $order_id;
    	//$where["org_parent_id"]=session("org_parent_id");
		
    	
    	$aOrder=M("car_orders")->where($where)->find();
    	$aOrder["create_time"]=date("Y-m-d H:i",$aOrder["create_time"]);
		
		$aOrder["staff_name"]=M("admin_user")->where("admin_id=".$aOrder["staff_id"])->getField("true_name");	
		$where1["zdb_car_orders_goods.order_id"]= $order_id;

    	//$where1["zdb_car_orders_goods.org_parent_id"]=session("org_parent_id");

        $aGoods=M("car_orders_goods")
		->field("zdb_goods_product.cv_id,zdb_goods_info.goods_id,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,singleprice,number,unit_name")
    	->join("zdb_goods_product on zdb_goods_product.cv_id=zdb_car_orders_goods.cv_id")
    	->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_goods_product.goods_id")
    	->where($where1)->select();

        //echo M("car_orders_goods")->getLastSql();die;
		
		$orderInfo["order_id"]=$aOrder["order_id"];
		$orderInfo["order_code"]=$aOrder["order_code"];
		$orderInfo["org_parent_id"]=$aOrder["org_parent_id"];
		$orderInfo["cust_id"]=$aOrder["cust_id"];
		$orderInfo["cust_name"]=$aOrder["cust_name"];
		$orderInfo["cust_address"]=$aOrder["cust_address"];
		$orderInfo["order_total_money"]=$aOrder["order_total_money"];
		$orderInfo["create_time"]=$aOrder["time"];
		$orderInfo["order_way"]=$aOrder["order_way"];
		$orderInfo["order_remark"]=$aOrder["order_remark"];
		
		
		if($aOrder){
			 $data["orderInfo"]=$aOrder;
		     $data["orderGoods"]=$aGoods;
			 $res["error"]=-1;
			 $res['msg']="成功";
			 $res["data"]=$data;
		}else{
			$res["error"]=1;
			$res['msg']="失败";
		}
       
		echo json_encode($res);
    }
}