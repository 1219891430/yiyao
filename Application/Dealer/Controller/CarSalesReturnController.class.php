<?php

/*******************************************************************
 ** 文件名称: CarSalesReturnController.class.php
 ** 功能描述: 经销商PC端终端退货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CarSalesReturnController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		$del=I("get.del","");
		
		$in = M("carsales_return");
		
		$staff_id=I("get.staff_id");
		if($staff_id){
			$where["zdb_carsales_return.staff_id"]=$staff_id;
		}
		$name=I("get.name");
		if($name){
			$where["cust_name"]=array("like","%$name%");
		}
		$start_time=I("get.start_time");
		$start_time=strtotime($start_time);
		$end_time=I("get.end_time");
		$end_time=strtotime($end_time);
		$end_time=$end_time+24*3600;
		if($start_time){
			$where["zdb_carsales_return.create_time"]=array(array('gt',$start_time),array('lt',$end_time));
		}

		$where["zdb_carsales_return.org_parent_id"]=session("org_parent_id");
		$p=I("get.p",1);
        $pnum=I("get.pnum",10);
		$carsalesreturn=$in->join("zdb_org_staff on zdb_org_staff.staff_id=zdb_carsales_return.staff_id")->where($where)->page($p,$pnum)->order("create_time desc")->select();
		$total=$in->where($where)->count();
		$page=get_page_code($total,$pnum,$p, $page_code_len = 5);
		$this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
		$this->assign("carsalesreturn",$carsalesreturn);
		//业务员
		$where1["zdb_org_staff.org_parent_id"]=session("org_parent_id");
		$where1["role_id"]=3;
		$staffList=M("org_staff")->field("zdb_org_staff.staff_id,staff_name")->where($where1)->select();
		$this->assign("staffList",$staffList);
		$sid = 0;
		if($staff_id){
			$sid = $staff_id;
		}
		$stime = 0;
		if($start_time){
			$stime = $start_time;
			$stime=date("Y-m-d",$stime);
		}
		$etime = 0;
		$end_time=$end_time-24*3600;
		if($end_time){
			$etime = $end_time;
			$etime=date("Y-m-d",$etime);
		}
		$user = 0;
		if($name){
			$user = $name;
		}
		
		
		$this->assign('urlPara',array("staff_id"=>$sid,"start_time"=>$stime,"end_time"=>$etime,"name"=>$user));
		$this->assign("del",$del);
		$this->display();
    }


	/** 其他Action **/
    public function detailAction(){
    	
    	$list = M("carsales_return");//正常订单
    	$lists = M("carsales_return_goods a");

        $where["return_id"]=I("get.code",'');
        $where["org_parent_id"]=session("org_parent_id");
        $aOrder=$list->where($where)->find();
        
		 $aOrder["time"]=date("Y-m-d H:i",$aOrder["create_time"]); 
		
        $whereSS["return_id"]=I("get.code",'');
        $aGoods=$lists
            ->join("zdb_goods_product on zdb_goods_product.cv_id=a.cv_id")
            ->join("zdb_goods_info on zdb_goods_product.goods_id=zdb_goods_info.goods_id")
            ->where($whereSS)->select();
        foreach($aGoods as $k=>$v){
        	$aGoods[$k]['goods_total_money']=$v['goods_money']*$v["goods_num"];
        }
        $this->assign("return_id",$where["return_id"]);
        $this->assign("aOrder",$aOrder);
        $this->assign("aGoods",$aGoods);
        $this->display();

    }

}

/*************************** end ************************************/