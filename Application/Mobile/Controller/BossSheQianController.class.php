<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/13/0013
 * Time: 13:39
 */

namespace Mobile\Controller;
use Think\Controller;

class BossSheQianController extends Controller
{
    public function sheQiansingleOrderAction(){
        $orderId = I('orderId',1);
		$where["order_id"]=$orderId;
		$whereQian["orderid"]=$orderId;
		$res=M("carsale_orders")->field("order_total_money,order_real_money")->where($where)->find();
		$resQian=M("carsale_orders_qiankuan")->field("sum(price) as qing")->where($whereQian)->find();
		
		$res["qiankuan"]=$res['order_total_money']-$res['order_real_money']-$resQian["qing"];
		$res["order_real_money"]=$res["order_real_money"]+$resQian["qing"];
		
		$goods=M("carsale_orders_goods")->field("goods_name,zdb_goods_info.goods_spec,unit_name,singleprice,number")
		->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_carsale_orders_goods.goods_id")
		->where($where)->select();
		
		
		
		$data["res"]=$res;
		$data["goodsDetails"]["goods"]=$goods;

        /**
         * res : {"order_total_money":"60","order_real_money":"20","qiankuan":"40"}
		 * 
         * goodsDetails : {
		 *      "goods":[
		 *          {"goods_name":"特仑苏","unit_name":"箱","goods_convert":"1*12*1","singleprice":"60","number":"1"}
		 *      ]}
         */


        echo json_encode($data);


    }
}