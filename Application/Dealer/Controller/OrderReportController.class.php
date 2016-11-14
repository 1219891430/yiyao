<?php

/*******************************************************************
 ** 文件名称: OrderReportController.class.php
 ** 功能描述: 经销商PC端销售报表控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class OrderReportController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		$this->display();
    }
	
	public function goodsAction(){
		//获得业务员
		
    	$aStaff=queryOrgStaff(session("org_parent_id"),3);
    	$this->assign("aStaff",$aStaff);
        //经销商id
		$org_parent_id = session("org_parent_id");
		
		$goods=queryGoodsByOrgId($org_parent_id);
		$this->assign("goods",$goods);

		// 搜索品牌
   		$aBrand=QueryBrandByOrgId($org_parent_id);
    	$this->assign("aBrand",$aBrand);

        $start=I("get.start");
        $starts=strtotime($start);
		$end=I("get.end");
		$ends=strtotime($end);
		$ends=$ends+24*3600;
		
		
		$staff_id=I("get.staff_id",0);
		$shop=I("get.shop");
		
		$brand_id=I("get.brand_id",0);
		
		$maps['ord.create_time']=array(
				array(
						'gt',$starts
				),
				array(
						'lt',$ends
				)
		);
		
		$maps['og.org_parent_id']=session("org_parent_id");
		$maps['og.cuxiao']=0;
		
		if($staff_id != 0){
			$maps['ord.staff_id'] = $staff_id;
		}
		if($cust){
			$maps['ord.cust_name'] = array("like","%$shop%");
		}
		if($brand_id){
			$maps['gb.brand_id']=$brand_id;
		}
		if($goods_id){
			$maps['gi.goods_id']=$goods_id;
		}
		$data=M("carsale_orders_goods")
		->alias("og")
		->join("__CARSALE_ORDERS__ as ord on ord.order_id=og.order_id")
		->join("__ORG_GOODS_CONVERT__ as gc on gc.cv_id=og.cv_id")
		->join("__GOODS_INFO__ as gi on gi.goods_id=og.goods_id")
		->join("__GOODS_BRAND__ as gb on gb.brand_id=gi.brand_id")
		->where($maps)
		->field("
				gb.brand_id,
				gb.brand_name,
				gi.goods_name as good_name,
				gc.goods_unit as unit_name,
				og.singleprice,
				gi.goods_spec,
				sum(og.number) as number,
				sum(og.number*og.singleprice) as allprice
				")
		->group("
				og.cv_id,og.singleprice
				")	
		->order("gb.brand_id,good_name,unit_name,singleprice")
		->select();
		$newData=array();
		$ddnum=0;
		foreach($data as $v){
			$newData[$v["brand_id"]]["brand_name"]=$v["brand_name"];
			$newData[$v["brand_id"]]["total"][]=$v;
			$newData[$v["brand_id"]]["totalmoney"]+=$v["allprice"];
			$ddnum+=$v["allprice"];
		}
		$this->assign("ddnum",$ddnum);
		$this->assign("newData",$newData);
		

		/**

		 * 退货数据开始（按商品）
		 */
		if($staff_id != 0){
			$twhere['a.staff_id'] = $staff_id;
		}
		$twhere['a.org_parent_id'] = $org_parent_id;
		if($cust){
			$twhere['a.cust_name'] = array("like","%$shop%");
		}
		if($brand_id){
			$twhere['c.brand_id']=$brand_id;
		}
		
		$twhere["a.create_time"]=array(array("gt",$starts),array('lt',$ends));

		if($goods_id){
			$twhere['b.goods_id']=$goods_id;
		}
		$tfield = "a.cust_name,a.return_id,b.goods_id,sum(b.goods_money * b.goods_num) as return_real_money,a.create_time,b.goods_name,b.goods_unit,b.goods_money,sum(b.goods_num) as goods_num";

		$tdata=M("carsales_return a")
				->join("zdb_carsales_return_goods b on a.return_id = b.return_id")
				->join("zdb_goods_info c on c.goods_id = b.goods_id")
				->field($tfield)
				->where($twhere)
				->order("b.goods_money desc")
				->group("a.cust_name,b.cv_id,b.goods_money")
				->select();
				
		$newTData=array();
		$ttnum=0;
		foreach($tdata as $v){
			$newTData[$v["goods_id"]]["goods_name"]=$v["goods_name"];
			$newTData[$v["goods_id"]]["total"][]=$v;
			$newTData[$v["goods_id"]]["totalnum"]++;
			$ttnum+=$v["return_real_money"];
			
		}
		
		$this->assign("ttnum",$ttnum);
		$this->assign("newTData",$newTData);
		/**

		 * 退货数据结束
		 */

		/**
		 * 促销品汇总(获得所有的促销品)
		 */
		
		$cuxiaomaps['ord.create_time']=array(
				array(
						'gt',$starts
				),
				array(
						'lt',$ends
				)
		);
		$cuxiaomaps['og.org_parent_id']=session("org_parent_id");
		$cuxiaomaps['og.cuxiao']=1;
		
		if($staff_id != 0){
			$cuxiaomaps['ord.staff_id'] = $staff_id;
		}
		if($cust){
			$cuxiaomaps['ord.cust_name'] = array("like","%$shop%");
		}
		if($brand_id){
			$cuxiaomaps['gb.brand_id']=$brand_id;
		}
		if($goods_id){
			$cuxiaomaps['gi.goods_id']=$goods_id;
		}
		$cxdata=M("carsale_orders_goods")
		->alias("og")
		->join("__CARSALE_ORDERS__ as ord on ord.order_id=og.order_id")
		->join("__ORG_GOODS_CONVERT__ as gc on gc.cv_id=og.cv_id")
		->join("__GOODS_INFO__ as gi on gi.goods_id=og.goods_id")
		->join("__GOODS_BRAND__ as gb on gb.brand_id=gi.brand_id")
		->where($cuxiaomaps)
		->field("
				gb.brand_id,
				gb.brand_name,
				gi.goods_name as good_name,
				gc.goods_unit as unit_name,
				gc.goods_base_price,
				gi.goods_spec,
				sum(og.number) as number,
				sum(og.number*gc.goods_base_price) as allprice
				")
		->group("
				og.cv_id,gc.goods_base_price
				")	
		->order("gb.brand_id,good_name,unit_name,singleprice")
		->select();
		$newCXData=array();
		$cxddnum=0;
		foreach($cxdata as $v){
			$newCXData[$v["brand_id"]]["brand_name"]=$v["brand_name"];
			$newCXData[$v["brand_id"]]["total"][]=$v;
			$newCXData[$v["brand_id"]]["totalmoney"]+=$v["allprice"];
			$cxddnum+=$v["allprice"];
		}
		$this->assign("cxddnum",$cxddnum);
		$this->assign("newCXData",$newCXData);
		
		
        
	
	  
	

		

		/**
		 * 赊账统计,sum(o.order_total_money) as total,sum(o.order_real_money) as real
		 */				 
		

		if($staff_id != 0){
			$whereShe["o.staff_id"] = $staff_id;
		}
		if($cust){
			$whereShe['o.cust_name'] = array("like","%$shop%");
		}
		$sData = M('carsale_orders o')
				->where($whereShe)
				->where("
					o.order_total_money > o.order_real_money
					and o.org_parent_id = '$org_parent_id'
					and o.is_full_pay = 0 
					and o.create_time > '$starts'
					and o.create_time < '$ends' 
					")
				->field('o.cust_name,
						 o.cust_contact as contact,
						 sum(o.order_total_money) as totalm,
						 sum(o.order_real_money) as realm
					')
				->group('o.cust_id')
				->select();
		
		$snum = array();
		foreach ($sData as $k => $v) {
			$sData[$k]['nopay'] = $v['totalm'] - $v['realm'];
			$snum['totaln'] += $v['totalm'];
			$snum['totalnopay'] += $sData[$k]['nopay'];
			$snum['pay'] += $v['realm'];
		}
		$this->assign('sData',$sData);
		$this->assign('snum',$snum);
		
        
		

		
        
		/*
		 * 调换货统计
		 * $staff_id
		 * $org_parent_id
		 * $_REQUEST['shop']
		 * $starts
		 * $ends
		 */
		$THdata=$this->getChangeGoodsSummary($org_parent_id,$starts,$ends,$_REQUEST['shop'],$staff_id);

		
		$this->assign('thIndata',$THdata['in']);
		$this->assign('thOutdata',$THdata['out']);
		$this->assign('in_money',$THdata['in_money']);
		$this->assign('out_money',$THdata['out_money']);
		
		
		
		
		
		$this->assign('start',$_REQUEST['start']);
		$this->assign('end',$_REQUEST['end']);
		$this->assign('type_lx',$type_lx);
		$this->assign('type',$type);	
	

		$this->display();
	}
	
	public function shopsAction(){
		$aStaff=queryOrgStaff(session("org_parent_id"),3);
    	$this->assign("aStaff",$aStaff);
        //经销商id
		$org_parent_id = session("org_parent_id");
		//获得类型（流水，汇总）
		$goods=queryGoodsByOrgId($org_parent_id);
		$this->assign("goods",$goods);

   		$aBrand=QueryBrandByOrgId($org_parent_id);
    	$this->assign("aBrand",$aBrand);

        $start = I("get.start");
		$end = I("get.end");
	
		$starts=strtotime($start);
		$ends=strtotime($end);
		$ends=$ends+24*3600;
		
		
		
		$staff_id=I("get.staff_id",0);
		$shop=I("get.shop");
		
		$brand_id=I("get.brand_id",0);
		
		
		
		$dmap['d.create_time']=array(
				array(
						'gt',$starts
				),
				array(
						'lt',$ends
				)
		);
		
		$dmap['d.org_parent_id']=session("org_parent_id");
		
		if($staff_id != 0){
			$dmap['d.staff_id'] = $staff_id;
		}
		if($cust){
			$dmap['d.cust_name'] = array("like","%$shop%");
		}
		if($brand_id){
			$dmap['c.brand_id']=$brand_id;
		}
		if($goods_id){
			$dmap['b.goods_id']=$goods_id;
		}
	
		// 商铺 名称
		 
		$data=M("carsale_orders_goods a")
		->join("__GOODS_INFO__ as b on b.goods_id=a.goods_id")
		->join("__CARSALE_ORDERS__ d on d.order_id = a.order_id")
		->join("__GOODS_BRAND__ as c on c.brand_id = b.brand_id")
		->where($dmap)
		->field("a.cuxiao,sum(a.number) as num,sum(a.singleprice*a.number) as allprice,b.goods_spec,a.unit_name,a.good_name,a.singleprice,c.brand_name,d.cust_name,d.cust_id,a.goods_id,a.cv_id")
		->group("d.cust_id,a.cv_id,a.singleprice,a.cuxiao")
		->order('a.singleprice desc')
		->select();
		$newData=array();
		foreach($data as $v){
			$newData[$v["cust_id"]]["cust_name"]=$v['cust_name'];
			$newData[$v["cust_id"]]["total"][]=$v;
			$newData[$v["cust_id"]]["totalnum"]++;
			if($v["cuxiao"]==0){
				$newData[$v["cust_id"]]["allmoney"]+=$v["allprice"];
			}else{
				$newData[$v["cust_id"]]["allcuxiao"]+=$v["allprice"];
			}
			if($v["cuxiao"]==0){
				$totalmoney+=$v["allprice"];
			}else{
				$cuxiaomoney+=$v["allprice"];
			}		
		}
		$this->assign("tonum",array("totalmoney"=>$totalmoney,"cuxiaomoney"=>$cuxiaomoney));
		$this->assign("newData",$newData);
		/**
		 * 退货数据开始（按商铺）
		 */
		$twhere['a.create_time']=array(
				array(
						'gt',$starts
				),
				array(
						'lt',$ends
				)
		);
		
		$twhere['a.org_parent_id']=session("org_parent_id");
		
		if($staff_id != 0){
			$twhere['a.staff_id'] = $staff_id;
		}
		if($cust){
			$twhere['a.cust_name'] = array("like","%$shop%");
		}
		if($brand_id){
			$twhere['g.brand_id']=$brand_id;
		}
	    if($goods_id){
			$twhere['b.goods_id']=$goods_id;
		}
		
		$tfield = "a.cust_id,a.cust_name,a.return_id,a.real_money as return_real_moneys,sum(b.goods_money * b.goods_num) as return_real_money,a.create_time,g.goods_name,g.goods_spec,b.goods_unit,b.goods_money,sum(b.goods_num) as goods_num";
		$tdata=M("carsales_return a")
				->join("zdb_carsales_return_goods b on a.return_id = b.return_id")
				->join("zdb_goods_info g on g.goods_id=b.goods_id")
				->field($tfield)
				->where($twhere)
				->order("b.goods_money desc")
				->group("a.cust_id,b.cv_id,b.goods_money")
				->select();
		$newTData=array();
		$ttnum=0;
		foreach($tdata as $v){
			$newTData[$v["cust_id"]]["cust_name"]=$v["cust_name"];
			$newTData[$v["cust_id"]]["total"][]=$v;
			$newTData[$v["cust_id"]]["totalnum"]++;
			$newTData[$v["cust_id"]]["totalmoney"]+=$v["return_real_money"];
			$ttnum+=$v["return_real_money"];
		}
		$this->assign("ttnum",$ttnum);
		$this->assign("newTData",$newTData);
		
		/**
		 * 退货数据结束
		 */
	
		
	
	
		//赊账统计,sum(o.order_total_money) as total,sum(o.order_real_money) as real				 
		$sData['o.org_parent_id'] = session("org_parent_id");
		if($staff_id != 0){
			$she['o.staff_id'] = $staff_id;
		}
		$sData = M('carsale_orders o')->where($she)->where("
					o.order_total_money > o.order_real_money
					and o.org_parent_id = '$org_parent_id' 
					and o.create_time > '$starts'
					and o.create_time < '$ends' 
					and o.is_full_pay = 0
					")->field('o.cust_name,
						 o.cust_contact as contact,
						 sum(o.order_total_money) as totalm,
						 sum(o.order_real_money) as realm
					')->group('o.cust_id')->select();
		$snum = array();
		foreach ($sData as $k => $v) {
			$sData[$k]['nopay'] = $v['totalm'] - $v['realm'];
			$snum['totaln'] += $v['totalm'];
			$snum['totalnopay'] += $sData[$k]['nopay'];
			$snum['pay'] += $v['realm'];
		}
	
		
	
		/*
		 * 调换货统计
		 * $staff_id
		 * $org_parent_id
		 * $_REQUEST['shop']
		 * $starts
		 * $ends
		 */
		$resTHdata=$this->getChangeGoodsReport($org_parent_id,$starts,$ends,$_REQUEST['shop'],$staff_id);
		
		if(!empty($_REQUEST['start']) && !empty($_REQUEST['end'])){
		$this->assign('THdata',$resTHdata['data']);
		$this->assign('thnum',$resTHdata['thnum']);
		
		
		
		$this->assign('cData',$cData);
		$this->assign('cnum',$cnum['total']);
		$this->assign('yData',$yData1);
		$this->assign('ynum',$ynum['total']);
		$this->assign('rData',$rData);
		$this->assign("ttdata",$ttdata);
		$this->assign("ttnum",$ttnum);
		}
		$this->assign('sData',$sData);
		$this->assign('snum',$snum);
		$this->assign('start',$_REQUEST['start']);
		$this->assign('end',$_REQUEST['end']);
		$this->assign('type_lx',$type_lx);
		$this->assign('type',$type);
		$this->display();
	
	}


	/** 其他Action **/

   public function getChangeGoodsReport($org_parent_id,$start_time=0,$end_time=0,$cust_name="",$staff_id=0){
  	  if($cust_name){
  	  	$where["zdb_customer_info.cust_name"]=array("like","%$cust_name%");
  	  }
	    if($staff_id){
	    	$where["zdb_org_staff.staff_id"]=$staff_id;
	    }
  	    $where["zdb_carsales_change.create_time"]=array(array("gt",$start_time),array("lt",$end_time));
  		$where["zdb_carsales_change.org_parent_id"]=$org_parent_id;
  	 	$list=M("carsales_change")->field("zdb_customer_info.cust_name,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_org_goods_convert.goods_unit,zdb_carsales_change_goods.singleprice,sum(zdb_carsales_change_goods.number) as sumnumber,is_change_in")
			->join("zdb_carsales_change_goods on zdb_carsales_change_goods.change_id=zdb_carsales_change.change_id")
			->join("zdb_customer_info on zdb_customer_info.cust_id = zdb_carsales_change.cust_id")
			->join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsales_change.staff_id")
			->join("zdb_org_goods_convert on zdb_org_goods_convert.cv_id=zdb_carsales_change_goods.cv_id")
			->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_org_goods_convert.goods_id")
			->where($where)->group("zdb_carsales_change_goods.cv_id,zdb_carsales_change_goods.singleprice,zdb_carsales_change_goods.is_change_in,zdb_customer_info.cust_id")->select();
			$data=array();
			$thnum=0;
			$in_num=0;
			$out_num=0;
			foreach($list as $k=>$v){
				$data[$v["cust_name"]]["cust_name"]=$v["cust_name"];
				$data[$v["cust_name"]]["data"][]=$v;
				if($v["is_change_in"]){
					$data[$v["cust_name"]]["totalmoney"]+=$v['singleprice']*$v["sumnumber"];
				  $thnum+=$v['singleprice']*$v["sumnumber"];
				  $in_num+=$v['singleprice']*$v["sumnumber"];
				}else{
					$data[$v["cust_name"]]["totalmoney"]-=$v['singleprice']*$v["sumnumber"];
				  $thnum-=$v['singleprice']*$v["sumnumber"];
				  $out_num+=$v['singleprice']*$v["sumnumber"];
				}
				
			}
			
			$res["data"]=$data;
			$res["thnum"]=array('thnum'=>$thnum,'in_num'=>$in_num,'out_num'=>$out_num);
			return $res;
	}



	

	public function getChangeGoodsSummary($org_parent_id,$start_time=0,$end_time=0,$cust_name="",$staff_id=0){
	  if($cust_name){
	  	$where["zdb_customer_info.cust_name"]=array("like","%$cust_name%");
	  }
	    if($staff_id){
	    	$where["zdb_org_staff.staff_id"]=$staff_id;
	    }
	  $where["zdb_carsales_change.create_time"]=array(array("gt",$start_time),array("lt",$end_time));
  		$where["zdb_carsales_change.org_parent_id"]=$org_parent_id;
  	 	$list=M("carsales_change")->field("zdb_goods_info.goods_id,zdb_customer_info.cust_name,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_org_goods_convert.goods_unit,zdb_carsales_change_goods.singleprice,sum(zdb_carsales_change_goods.number) as sumnumber,is_change_in")
			->join("zdb_carsales_change_goods on zdb_carsales_change_goods.change_id=zdb_carsales_change.change_id")
			->join("zdb_customer_info on zdb_customer_info.cust_id = zdb_carsales_change.cust_id")
			->join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsales_change.staff_id")
			->join("zdb_org_goods_convert on zdb_org_goods_convert.cv_id=zdb_carsales_change_goods.cv_id")
			->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_org_goods_convert.goods_id")
			->where($where)->group("zdb_carsales_change_goods.cv_id,zdb_carsales_change_goods.singleprice,zdb_carsales_change_goods.is_change_in,zdb_customer_info.cust_id")->select();
			$data_in=array();
			$data_out=array();
			$data_in_money=0;
			$data_out_money=0;
		
			foreach($list as $k=>$v){
				if($v["is_change_in"]){
					 $data_in[$v["goods_id"]]["goods_name"]=$v["goods_name"]."/".$v["goods_spec"];
				   //$data_in[$v["goods_id"]]["data"][]=$v;
				   $data_in[$v["goods_id"]]["data"][$v['cust_name']]['cust_name']=$v['cust_name'];
				   $data_in[$v["goods_id"]]["data"][$v['cust_name']]['data'][]=$v;
				   
				   $data_in[$v["goods_id"]]["totalmoney"]+=$v['singleprice']*$v["sumnumber"];
				   
				   $data_in[$v["goods_id"]]["count"]++;
				   $data_in_money+=$v['singleprice']*$v["sumnumber"];
				}else{
					 $data_out[$v["goods_id"]]["goods_name"]=$v["goods_name"]."/".$v["goods_spec"];
				   //$data_out[$v["goods_id"]]["data"][]=$v;
				   $data_out[$v["goods_id"]]["data"][$v['cust_name']]['cust_name']=$v['cust_name'];
				   $data_out[$v["goods_id"]]["data"][$v['cust_name']]['data'][]=$v;
				   
				   $data_out[$v["goods_id"]]["totalmoney"]+=$v['singleprice']*$v["sumnumber"];
				   
				   $data_out[$v["goods_id"]]["count"]++;
				   $data_out_money+=$v['singleprice']*$v["sumnumber"];
				}
				
			}
			
			$data["in"]=$data_in;
			$data["out"]=$data_out;
			$data["in_money"]=$data_in_money;
			$data["out_money"]=$data_out_money;
			return $data;
	}
   
}

/*************************** end ************************************/