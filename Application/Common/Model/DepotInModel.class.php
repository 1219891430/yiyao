<?php

/*******************************************************************
 ** 文件名称: DepotInModel.class.php
 ** 功能描述: 入库操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DepotInModel extends Model {

	// 数据主表: 入库表
	protected $tableName = 'depot_in';
	
	// 其他操作
	public function addDepotInOrder($data){
		
		$data1["depot_in_code"]=create_uniqid_code("I");
        $data1["depot_id"]=$data["depot_id"];
        $data1["org_parent_id"]=$data["org_parent_id"];
        $data1["in_type"]=$data["in_type"];
		if($data["in_status"]){
			$data1["in_status"]=$data["in_status"];
		}else{
			$data1["in_status"]=1;
		}
		
		$data1["in_remark"]=$data["in_remark"];
        $data1["staff_id"]=$data["staff_id"];
		$data1["create_id"]=$data["create_id"];
		
		$data1["create_time"]=time();
		$data1["checker_id"]=0;
		$data1["checker_time"]=0;
        $depot_in_id=$this->add($data1);
		
		$goods=$data["goods_info"];
		
		$goodsdata=array();
		foreach($goods as $k=>$v){
			$goodsdata[$k]["depot_in_id"]=$depot_in_id;
			$goodsdata[$k]["org_parent_id"]=$data1["org_parent_id"];
			$goodsdata[$k]["cv_id"]=$v["cv_id"];
			$goodsdata[$k]["in_num"]=$v["goods_num"];
			$goodsdata[$k]["goods_id"]=$v["goods_id"];
			$res=M("goods_info")->field("goods_name,goods_spec")->where("goods_id=".$v["goods_id"])->find();
			$goodsdata[$k]["goods_name"]=$res["goods_name"];
			$goodsdata[$k]["goods_spec"]=$res["goods_spec"];
			$goodsdata[$k]["unit_name"]=M("goods_product")->where("cv_id=".$v["cv_id"])->getField("goods_unit");
		}
		
		$res=M("depot_in_goods")->addAll($goodsdata);
		if($res){
			return true;
		}else{
			return false;
		}
		
		
	}


    public function EditDepotInOrder($data){
    			
    	$where["depot_in_id"]=$data["depot_in_id"];	
    	
        $data1["depot_id"]=$data["depot_id"];
        $data1["org_parent_id"]=$data["org_parent_id"];
        $data1["in_type"]=$data["in_type"];
		
		$data1["in_remark"]=$data["in_remark"];
        
		
		
		
        $this->where($where)->save($data1);
		
		$res=M("depot_in_goods")->where($where)->delete();
		
		
		$goods=$data["goods_info"];
		
		$goodsdata=array();
		foreach($goods as $k=>$v){
			$goodsdata[$k]["depot_in_id"]=$data["depot_in_id"];
			$goodsdata[$k]["org_parent_id"]=$data1["org_parent_id"];
			$goodsdata[$k]["cv_id"]=$v["cv_id"];
			$goodsdata[$k]["in_num"]=$v["goods_num"];
			$goodsdata[$k]["goods_id"]=$v["goods_id"];
			$res=M("goods_info")->field("goods_name,goods_spec")->where("goods_id=".$v["goods_id"])->find();
			$goodsdata[$k]["goods_name"]=$res["goods_name"];
			$goodsdata[$k]["goods_spec"]=$res["goods_spec"];
			$goodsdata[$k]["unit_name"]=M("goods_product")->where("cv_id=".$v["cv_id"])->getField("goods_unit");
		}
		
		$res=M("depot_in_goods")->addAll($goodsdata);
		if($res){
			return true;
		}else{
			return false;
		}
    }
	
}

/****************************** end *******************************/