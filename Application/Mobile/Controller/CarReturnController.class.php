<?php

/*******************************************************************
 ** 文件名称: CarReturnController.class.php
 ** 功能描述: 配送退货接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarReturnController extends Controller {
	
	public function detailAction(){
    	
    	$list = M("car_return");//正常订单
    	$lists = M("car_return_goods a");

        $where["return_id"]=I("return_id",'2');
        
        $aOrder=$list
        ->field("return_id as order_id,return_code as order_code,org_parent_id,cust_id,cust_name,create_time,cust_address,total_money as order_total_money,return_remark as order_remark,return_way as order_way")
        ->where($where)->find();
        
		$aOrder["create_time"]=date("Y-m-d H:i",$aOrder["create_time"]); 
		
        $whereSS["return_id"]=I("return_id",'2');
        $aGoods=$lists
            ->field("zdb_goods_product.cv_id,zdb_goods_info.goods_id,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,goods_num as number,zdb_goods_product.goods_unit as unit_name,goods_money as singleprice")
            ->join("zdb_goods_product on zdb_goods_product.cv_id=a.cv_id")
            ->join("zdb_goods_info on zdb_goods_product.goods_id=zdb_goods_info.goods_id")
            ->where($whereSS)->select();
        foreach($aGoods as $k=>$v){
        	$aGoods[$k]['goods_total_money']=$v['goods_money']*$v["goods_num"];
        }
		
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