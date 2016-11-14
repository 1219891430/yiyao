<?php

/*******************************************************************
 ** 文件名称: CarsaleBackController.class.php
 ** 功能描述: 经销商PC端车存退库控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class PromotionController extends Controller {
	
	public function indexAction(){
		
		$p = I("get.p",1);
        $pnum = I("get.pnum",10);
		$name=I("get.name");
		if($name){
			$where["act_name"]=array("like","%$name%");
		}
		
		$where["depot_id"]=session("depot_id");
		$where["org_parent_id"]=session("org_parent_id");
		$list=M("activity")
		->field("act_id,act_name,act_type,act_note,is_close,start_time,end_time")
		->where($where)->page($p,$pnum)->select();
		$total=M("activity")->where($where)->count();
		
		
		$page = get_page_code($total,$pnum,$p, $page_code_len = 5);
		
		$this->assign("list",$list);
		$this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("name"=>$name));
		$this->display();
	}
	
	public function addAction(){
		
		$brandList=QueryBrandByOrgId(session("org_parent_id"));
		$this->assign("brandlist",$brandList);
		
		$this->display();
	}
	
	public function getGoodsByBrandAction(){
		$brand_id=I("brand_id",1);
		$where["brand_id"]=$brand_id;
		$where["org_parent_id"]=session("org_parent_id");
		
		$goods = M("depot_stock")->alias("ds")
	        ->field("distinct gi.goods_id,gi.goods_name, gi.goods_spec")
            ->join("__GOODS_INFO__ as gi on ds.goods_id=gi.goods_id")
            ->where($where)->select();
		echo json_encode($goods);
	}
	
	public function getCvByGoodAction(){
		$goods_id=I("goods_id",1);
		$where["goods_id"]=$goods_id;
		//$where["org_parent_id"]=session("org_parent_id");
		
		$cvlist=M("goods_product")->field("cv_id,goods_unit")->where($where)->select();
		echo json_encode($cvlist);
	}
	
	public function doAddAction(){
		
		$data["act_name"]=I("post.act_name");
		$data["act_type"]=I("post.act_type");
		$data["act_note"]=I("post.act_note");
		if($data["act_type"]==0){
			$data["act_price"]=I("post.act_price");
		}else{
			$data["act_price"]=0;
		}
		
		if($data["act_type"]==1){
			$data["act_money"]=I("post.act_money");
			$data["act_offer_money"]=I("post.act_offer_money");
		}else{
			$data["act_money"]=0;
			$data["act_offer_money"]=0;
		}
		
		if($data["act_type"]==2){
			$data["song_goods_num"]=I("post.song_goods_num");
			$data["goods_num"]=I("post.goods_num");
			$data["song_cv_id"]=I("post.song_cv_id");
			$data["song_goods_id"]=I("post.song_goods_id");
			$data["song_brand_id"]=I("post.song_brand_id");
		}else{
			$data["song_goods_num"]=0;
			$data["goods_num"]=0;
			$data["song_cv_id"]=0;
			$data["song_goods_id"]=0;
			$data["song_brand_id"]=0;
		}
		
		$data["org_parent_id"]=session("org_parent_id");
		$data["depot_id"]=session("depot_id");
		$data["is_close"]=1;
		
		$data["cv_id"]=I("post.cv_id");
		
		$data["goods_id"]=I("post.goods_id");
		$where["cv_id"]=$data["cv_id"];
		$where["goods_id"]=$data["goods_id"];
		$res=M("activity")->where($where)->count();
		if($res){
			echo json_encode(array("res"=>0,"info"=>"该商品中的该货品已参加活动"));
			return;
		}
		
		$data["brand_id"]=I("post.brand_id");
		$data["start_time"]=I("post.start_time");
		$data["start_time"]=strtotime($data["start_time"]);
		$data["end_time"]=I("post.end_time");
		$data["end_time"]=strtotime($data["end_time"]);
		$data["end_time"]=$data["end_time"]+24*3600-1;
		$res=M("activity")->add($data);
		if($res){
			echo json_encode(array("res"=>1,"info"=>"添加成功"));
		}else{
			echo json_encode(array("res"=>1,"info"=>"添加失败"));
		}
		
	}
	
	public function getCvPriceAction(){
		$cv_id=I("post.cv_id");
		$where["org_parent_id"]=session("org_parent_id");
		$where["cv_id"]=$cv_id;
		$goods_base_price=M("org_goods_convert")->where($where)->getField("goods_base_price");
		if($goods_base_price){
			echo $goods_base_price;
		}else{
			echo 0;
		}
	}
	
	public function editAction(){
		$acti_id=I("get.act_id");
		
		$org_parent_id=session("org_parent_id");
		$depot_id=session("depot_id");
		
		$where["act_id"]=$acti_id;
		$where["depot_id"]=$depot_id;
		$where["org_parent_id"]=$org_parent_id;
		
		$res=M("activity")->where($where)->find();
		
		$this->assign("res",$res);
		
		$brandList=QueryBrandByOrgId(session("org_parent_id"));
		$this->assign("brandlist",$brandList);
		unset($where);
		
		
		$where["goods_id"]=$res["goods_id"];
		$goods_name=M("goods_info")->where($where)->getField("goods_name");
		$this->assign("goods_name",$goods_name);
		
		$where["goods_id"]=$res["song_goods_id"];
		$song_goods_name=M("goods_info")->where($where)->getField("goods_name");
		$this->assign("song_goods_name",$song_goods_name);
		
		unset($where);
		$where["cv_id"]=$res["cv_id"];
		$goods_unit=M("goods_product")->where($where)->getField("goods_unit");
		$this->assign("goods_unit",$goods_unit);
		
		$where["cv_id"]=$res["song_cv_id"];
		$song_goods_unit=M("goods_product")->where($where)->getField("goods_unit");
		$this->assign("song_goods_unit",$song_goods_unit);
		
		$this->display();
	}
	
	public function editExAction(){
		$act_id=I("post.act_id");
		
		$org_parent_id=session("org_parent_id");
		$depot_id=session("depot_id");
		
		$data["start_time"]=I("post.start_time");
		$data["end_time"]=I("post.end_time");
		
		$data["start_time"]=strtotime($data["start_time"]);
		$data["end_time"]=strtotime($data["end_time"]);
		$data["end_time"]=$data["end_time"]+24*3600-1;
		$where["act_id"]=$act_id;
		$where["depot_id"]=$depot_id;
		$where["org_parent_id"]=$org_parent_id;
		
		$res=M("activity")->where($where)->save($data);
		if($res){
			echo json_encode(array("res"=>1,"info"=>"修改成功"));
		}else{
			echo json_encode(array("res"=>0,"info"=>"修改失败"));
		}
	}
	
	public function detailAction(){
		$acti_id=I("get.act_id");
		
		$org_parent_id=session("org_parent_id");
		$depot_id=session("depot_id");
		
		$where["act_id"]=$acti_id;
		$where["depot_id"]=$depot_id;
		$where["org_parent_id"]=$org_parent_id;
		
		$res=M("activity")->where($where)->find();
		
		$this->assign("res",$res);
		
		$brandList=QueryBrandByOrgId(session("org_parent_id"));
		$this->assign("brandlist",$brandList);
		unset($where);
		
		
		$where["goods_id"]=$res["goods_id"];
		$goods_name=M("goods_info")->where($where)->getField("goods_name");
		$this->assign("goods_name",$goods_name);
		
		$where["goods_id"]=$res["song_goods_id"];
		$song_goods_name=M("goods_info")->where($where)->getField("goods_name");
		$this->assign("song_goods_name",$song_goods_name);
		
		unset($where);
		$where["cv_id"]=$res["cv_id"];
		$goods_unit=M("goods_product")->where($where)->getField("goods_unit");
		$this->assign("goods_unit",$goods_unit);
		
		$where["cv_id"]=$res["song_cv_id"];
		$song_goods_unit=M("goods_product")->where($where)->getField("goods_unit");
		$this->assign("song_goods_unit",$song_goods_unit);
		
		$this->display();
	}
	
	
	
	public function openAction(){
		$act_id=I("post.act_id");
		
		$org_parent_id=session("org_parent_id");
		$depot_id=session("depot_id");
		
		$where["act_id"]=$act_id;
		$where["depot_id"]=$depot_id;
		$where["org_parent_id"]=$org_parent_id;
		
		$res=M("activity")->where($where)->save(array("is_close"=>0));
		if($res){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	public function closeAction(){
		$act_id=I("post.act_id");
		
		$org_parent_id=session("org_parent_id");
		$depot_id=session("depot_id");
		
		$where["act_id"]=$act_id;
		$where["depot_id"]=$depot_id;
		$where["org_parent_id"]=$org_parent_id;
		
		$res=M("activity")->where($where)->save(array("is_close"=>1));
		if($res){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	public function deleteAction(){
		$act_id=I("post.act_id");
		
		$org_parent_id=session("org_parent_id");
		$depot_id=session("depot_id");
		
		$where["act_id"]=$act_id;
		$where["depot_id"]=$depot_id;
		$where["org_parent_id"]=$org_parent_id;
		
		$res=M("activity")->where($where)->delete();
		if($res){
			echo 1;
		}else{
			echo 0;
		}
	}
	
}