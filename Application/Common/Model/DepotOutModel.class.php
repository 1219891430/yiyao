<?php

/*******************************************************************
 ** 文件名称: DepotOutModel.class.php
 ** 功能描述: 出库操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DepotOutModel extends Model {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'depot_out';
	
	// 其他操作
	// 其他操作
	public function addDepotOutOrder($data){
		
		$data1["depot_out_code"]=create_uniqid_code("O");
        $data1["depot_id"]=$data["depot_id"];
        $data1["org_parent_id"]=$data["org_parent_id"];
        $data1["out_type"]=$data["out_type"];
		if($data['out_status']){
			$data1["out_status"]=$data['out_status'];
		}else{
			$data1["out_status"]=1;
		}
		
		$data1["out_remark"]=$data["out_remark"];
        $data1["send_staff_id"]=$data["send_staff_id"];
		$data1["create_id"]=$data["create_id"];
		
		$data1["create_time"]=time();
		$data1["checker_id"]=0;
		$data1["checker_time"]=0;
		
        $depot_out_id=$this->add($data1);
		
		$goods=$data["goods_info"];
		
		$goodsdata=array();
		foreach($goods as $k=>$v){
			$goodsdata[$k]["depot_out_id"]=$depot_out_id;
			$goodsdata[$k]["org_parent_id"]=$data1["org_parent_id"];
			$goodsdata[$k]["cv_id"]=$v["cv_id"];
			$goodsdata[$k]["out_num"]=$v["goods_num"];
			$goodsdata[$k]["goods_id"]=$v["goods_id"];
			$res=M("goods_info")->field("goods_name,goods_spec")->where("goods_id=".$v["goods_id"])->find();
			$goodsdata[$k]["goods_name"]=$res["goods_name"];
			$goodsdata[$k]["goods_spec"]=$res["goods_spec"];
			$goodsdata[$k]["unit_name"]=M("goods_product")->where("cv_id=".$v["cv_id"])->getField("goods_unit");
		}
		
		$res=M("depot_out_goods")->addAll($goodsdata);
		if($res){
			return true;
		}else{
			return false;
		}
		
		
	}
	
	public function EditDepotoutOrder($data){
    		
    	$where["depot_out_id"]=$data["depot_out_id"];	
    	
        $data1["depot_id"]=$data["depot_id"];
        $data1["org_parent_id"]=$data["org_parent_id"];
        $data1["out_type"]=$data["out_type"];
		$data1["send_staff_id"]=$data["send_staff_id"];
		$data1["out_remark"]=$data["out_remark"];
        
		
		
		
        $this->where($where)->save($data1);
		
		$res=M("depot_out_goods")->where($where)->delete();
		
		
		$goods=$data["goods_info"];
		
		$goodsdata=array();
		foreach($goods as $k=>$v){
			$goodsdata[$k]["depot_out_id"]=$data["depot_out_id"];
			$goodsdata[$k]["org_parent_id"]=$data1["org_parent_id"];
			$goodsdata[$k]["cv_id"]=$v["cv_id"];
			$goodsdata[$k]["out_num"]=$v["goods_num"];
			$goodsdata[$k]["goods_id"]=$v["goods_id"];
			$res=M("goods_info")->field("goods_name,goods_spec")->where("goods_id=".$v["goods_id"])->find();
			$goodsdata[$k]["goods_name"]=$res["goods_name"];
			$goodsdata[$k]["goods_spec"]=$res["goods_spec"];
			$goodsdata[$k]["unit_name"]=M("goods_product")->where("cv_id=".$v["cv_id"])->getField("goods_unit");
		}
		
		$res=M("depot_out_goods")->addAll($goodsdata);
		if($res){
			return true;
		}else{
			return false;
		}
    }
	
	
}

/****************************** end *******************************/