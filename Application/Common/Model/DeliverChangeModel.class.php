<?php

/*******************************************************************
 ** 文件名称: DeliverChangeModel.class.php
 ** 功能描述: 平台车销配送调换货公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DeliverChangeModel extends Model {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'car_change';
	
	// 其他操作
	public function selectChangeOrder($change_id){

		
		$obj1 = M("car_change a");
		$obj2 = M("car_change_goods b");
		
		
		$where["change_id"]=$change_id;

        // 检查车销单权限
        $depot_id = intval($_SESSION["depot_id"]);

        if ($depot_id > 0) {

            $where["a.repertory_id"] = $depot_id;
        }

		$res=$obj1->field("zdb_customer_info.cust_name,zdb_customer_info.contact,zdb_customer_info.telephone,zdb_customer_info.province,zdb_customer_info.city,zdb_customer_info.district,zdb_customer_info.address,zdb_admin_user.true_name as staff_name,a.*")
		->join("zdb_customer_info on zdb_customer_info.cust_id = a.cust_id")
		->join("zdb_admin_user on zdb_admin_user.admin_id=a.staff_id")
		->where($where)->find();

        if (!$res) {
            return false;exit;
        }
		if ($depot_id > 0) {
            unset($where["a.repertory_id"]);
            
        }
		$goods=$obj2->field("zdb_goods_info.goods_code,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_goods_product.goods_unit,b.singleprice,b.number,b.is_change_in")->
		join("zdb_goods_product on zdb_goods_product.cv_id=b.cv_id")->
		join("zdb_goods_info on zdb_goods_info.goods_id=b.goods_id")->
		where($where)->select();
		
		$goods_in=array();
		$goods_out=array();
		foreach($goods as &$v){
			if($v['is_change_in']){
				$goods_in[]=$v;
			}else{
				$goods_out[]=$v;
			}		
		}
		
		$data['res']=$res;
		$data['goods_in']=$goods_in;
		$data['goods_out']=$goods_out;
		
		return $data;
	}
	
}

/****************************** end *******************************/