<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/13/0013
 * Time: 10:33
 */


namespace Mobile\Controller;
use Think\Controller;

class FinancialController extends Controller
{
    // 未收欠款
    public function qiankuanAction()
    {
        $org_parent_id = I("pid");
        $shopId = I('shopId');
        $time = I('time');

        // 今日区间
        $start_time = strtotime(date('Y-m-d') . ' 00:00:00');
        $end_time 	= $start_time + 86400;

        //按月查询
        if($time=='month'){
            $Ym = date('Y-m',time());
            $start_time = strtotime( $Ym . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }
        elseif( strlen($time)==7 ){
            $start_time = strtotime( $time . '-1 00:00:00');
            $end_time = strtotime(" +1 month",$start_time);
        }

        $where['co.org_parent_id'] = $org_parent_id;
        if(!empty($shopId)){
            $where['co.cust_id'] = $shopId;
        }

        $where['create_time'] = array(array('gt',$start_time),array('lt',$end_time));

        $list = M("carsale_orders")->alias('co')
            ->field("order_id,cust_name,order_real_money, order_total_money,(order_total_money-order_real_money) as qiankuan,create_time,staff_name,is_cancel,is_full_pay")
            ->where($where)
            ->where("order_total_money-order_real_money>0")
            ->join("zdb_org_staff os on os.staff_id=co.staff_id")
            ->select();

        $qianKuanData = array();
        foreach ($list as $k => $v) {
            $where1["orderid"] = $v["order_id"];
            $qingqianmoney = M("carsale_orders_qiankuan")->where($where1)->getField("sum(price)");
            if ($qingqianmoney) {
                $v["qiankuan"] = $v["qiankuan"] - $qingqianmoney;
            }

            $v["qiankuan"] = number_format($v["qiankuan"], 2);
            $qianKuanData[$k] = $v;
            $qianKuanData[$k]["create_time"] = date("Y-m-d H:i:s", $v["create_time"]);
        }


        echo json_encode($qianKuanData);
    }

   
    public function xiaoshouAction() {
		$start_time=I("start_time");
		$end_time=I("end_time");
		$start_time=strtotime($start_time);
		$end_time=strtotime($end_time);
		$orgData=I("pid");
		/* $salesData1=M("st_orders")
		 ->query("select count(order_id) as ordernumber,staff_id,staff_name,sum(order_real_money) as allmoney from zstb_st_orders GROUP BY staff_id;
		");	 */
		$map['zdb_carsale_orders.create_time']=array(
				array(
						'gt',$start_time
				),
				array(
						'lt',$end_time
				)
		);
		$map['zdb_carsale_orders.org_parent_id']=$orgData;
		$salesData=M("carsale_orders")->join("left join zdb_org_staff on zdb_carsale_orders.staff_id=zdb_org_staff.staff_id")
		->where($map)
		->field("count(order_id) as ordernumber,zdb_carsale_orders.staff_id,staff_name,truncate(sum(order_total_money),2) as allmoney")
		->group("staff_id")
		->select();
		
		if($salesData==NULL){
			$salesData=array();
		}
		$data["error"]="-1";
		$data["msg"]="成功";
		$data["data"]=$salesData;
		echo json_encode($data);
	}


    public function lirunAction() {

		$start_time=I("start_time");
	
		$end_time=I("end_time");
		
		$start_time=strtotime($start_time);
		$end_time=strtotime($end_time) + 24 * 60 * 60;
		$org_parent_id=I("pid");
		
		$whereBrand["org_parent_id"]=$org_parent_id;
		//销售额
		$where2['org_parent_id']=$org_parent_id;
		$where2['create_time']=array(array("gt",$start_time),array("lt",$end_time));
		$st_orders_obj = M("carsale_orders");
		$res=$st_orders_obj->field("sum(order_total_money) as total_money")->where($where2)->find(); 
		
		
		
		$brandList = M("goods_brand")->alias("gb")
            ->join("__GOODS_INFO__ as gi on gb.brand_id=gi.brand_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->where($whereBrand)
            ->field("DISTINCT gb.brand_id,gb.brand_name")
            ->select();
			
		
		foreach ($brandList as $k=>$v){
			
			$where["zdb_goods_info.brand_id"]=$v["brand_id"];
			$where["zdb_carsale_orders.org_parent_id"]=$org_parent_id;
			$where["zdb_carsale_orders.create_time"]=array(array("gt",$start_time),array("lt",$end_time));
			$where["zdb_org_goods_convert.org_parent_id"]=$org_parent_id;
			
			$goods = M("carsale_orders_goods")
			       
			         ->field("
			         zdb_goods_info.goods_id,
			         zdb_goods_info.goods_name,
			         zdb_goods_info.goods_spec,
			         zdb_org_goods_convert.goods_unit as unit_name,
			         zdb_carsale_orders_goods.singleprice,
			         sum(zdb_carsale_orders_goods.number) as num,
			         (singleprice*sum(number)) as totalmoney,
			         sum(zdb_org_goods_convert.goods_jin_price*zdb_carsale_orders_goods.number) as chengben,
			         sum((zdb_carsale_orders_goods.singleprice-zdb_org_goods_convert.goods_jin_price)*zdb_carsale_orders_goods.number) as maoli
			         ")
			         ->join("zdb_carsale_orders on zdb_carsale_orders.order_id = zdb_carsale_orders_goods.order_id")
			         ->join("zdb_org_goods_convert on zdb_org_goods_convert.cv_id=zdb_carsale_orders_goods.cv_id" )
			         ->join("zdb_goods_info on zdb_goods_info.goods_id = zdb_org_goods_convert.goods_id")
			         ->where($where)
			         ->group("goods_id,singleprice,zdb_carsale_orders_goods.cv_id")
			         ->select();
			
			$brandList[$k]["brandtotalmoney"]=0;
			$brandList[$k]["brandmaoli"]=0;
			foreach($goods as $kk=>$vv){
				$goods[$kk]["goods_name"]=$vv["goods_name"].$vv["goods_spec"];
				$goods[$kk]["num"]=$vv["num"].$vv["unit_name"];
				$goods[$kk]["maoli"]=round($vv["maoli"],2);
				$goods[$kk]["totalmoney"]=round($vv["totalmoney"],2);
				$brandList[$k]["brandtotalmoney"]+=$goods[$kk]["totalmoney"];
				$brandList[$k]["brandmaoli"]+=$goods[$kk]["maoli"];
				
			}
			
			$brandList[$k]["goods"]=$goods;
			if($brandList[$k]["goods"]==null){
				$brandList[$k]["goods"]=array();
			}
	
		} 
		$list=array();
		if($brandList){
			$list["list"]=$brandList;
			$list["msg"]="查询成功";
			$list["code"]=200;
		}else{
			$list["list"]=array();
			$list["msg"]="查询失败";
			$list["code"]=100;
		}
		echo json_encode($list);
	}


}
