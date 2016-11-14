<?php

/*******************************************************************
 ** 文件名称: CarsaleChangeController.class.php
 ** 功能描述: 经销商PC端车销调换货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CarsaleChangeController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){
		$del=I("get.del","");
		$p = isset($_GET['p']) ? $_GET['p'] : 1;
		$pnum=I("get.pnum",10);
		$staff_id=I("get.staff_id",0);
		$start=I("get.start_time",0);
		$end=I("get.end_time",0);
		$cust_name=I("get.name","@@");
		
		
		$data=D("CarsaleChange")->selectPageChangeOrder($p, $pnum, $staff_id, $start, $end, $cust_name,$del);
		$list=$data["list"];
		$page=$data["page"];
		
		
		$aStaff=queryOrgStaff(session("org_parent_id"),3);
		$this->assign("aStaff",$aStaff);
		$this->assign('aUrlPara', array('staff_id'=>$staff_id,'name'=>$cust_name,'start'=>$start,'end'=>$end,'del'=>$del));
		$this->assign('pagelist', $page);
		$this->assign("del",$del);
		$this->assign("pnum",$pnum);
		$this->assign('list',$list);
		$this->display();
		
	}
	
	public function detailAction(){
		
		$change_id =I("get.change_id",1);
		
		$data=D("CarsaleChange")->selectChangeOrder($change_id);
		$res=$data['res'];
		
		$goods_in=$data['goods_in'];
		$goods_out=$data['goods_out'];
		$this->assign("res",$res);
		$this->assign("goods_in",$goods_in);
		$this->assign("goods_out",$goods_out);
		
		$this->display();
	}


	/** 其他Action **/


}

/*************************** end ************************************/