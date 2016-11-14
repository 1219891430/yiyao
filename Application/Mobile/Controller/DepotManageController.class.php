<?php

/*******************************************************************
 ** 文件名称: DepotManageController.class.php
 ** 功能描述: 手机端库存管理接口
 ** 创建人员: sudi
 ** 创建日期: 2016-09-01
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class DepotManageController extends Controller {
	
	public function getGoodByCodeAction(){
		$code=I("post.code","001");
		$where["goods_code"]=$code;
		$res=M("goods_info")->field("goods_id,goods_code,goods_spec,main_img")->find();
		
		unset($where);
		if($res["goods_id"]){
			$where["goods_id"]=$res["goods_id"];
			$resd=M("goods_product")->field("cv_id,goods_unit,goods_unit_type")->where($where)->select();
			if($resd){
				$res["unit"]=$resd;
				$data["res"]=$res;
				$data["error"]=-1;
				$data['msg']="查询成功";
			}else{
				$data["error"]=1;
				$data['msg']="商品无单位";
			}
			
		}else{
			$data["error"]=1;
			$data['msg']="无此商品";
		}
		
		echo json_encode($data);
		
		
		
	}
    //	staff_id:1 ,send_staff_id: 0, types: "1", depot_id: "4", org_id: "2", goods_info: "[{"goods_id":7,"cv_id":14,"goods_num":5}]",types:"1"
	public function addDepotOutOrderAction()
	{
		$staff_id=I("post.staff_id",'1');
		$data["send_staff_id"]=I("post.send_staff_id",0);
        $data["out_type"]=I("post.types",'1'); // 入库类型
        $data["out_remark"]=I("post.remark",'');
        $data["depot_id"]=I("post.depot_id",1);
		$data["org_parent_id"]=I("post.org_id",1);
        //$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":5}]';
        $data["goods_info"]=json_decode($_POST["goods_info"],true);
		
       
		$data["create_id"]=$staff_id;
		
	    $res = D("DepotOut")->addDepotOutOrder($data);
		if($res){
			 aJsonReturn("1","添加成功");
		}else{
			 aJsonReturn("1","添加失败");
		}
		
		
	}
	/*{staff_id:1 , types: "1", depot_id: "4", org_id: "2", goods_info: "[{"goods_id":7,"cv_id":14,"goods_num":7}]", remark: "tgh"}*/
	public function addDepotInOrderAction()
	{
		$staff_id=I("post.staff_id",'1');
        $data["in_type"]=I("post.types",'1'); // 入库类型
        $data["in_remark"]=I("post.remark",'');
        $data["depot_id"]=I("post.depot_id",1);
		$data["org_parent_id"]=I("post.org_id",1);
        
        $data["goods_info"]=json_decode($_POST["goods_info"],true);
		
        $data["staff_id"]=$staff_id;
		$data["create_id"]=$staff_id;
		
	    $res = D("DepotIn")->addDepotInOrder($data);
		if($res){
			aJsonReturn("1","添加成功");
		}else{
			aJsonReturn("1","添加失败");
		}
		
		
	}
	
	
}