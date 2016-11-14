<?php

/*******************************************************************
 ** 文件名称: CarsaleChangeModel.class.php
 ** 功能描述: 经销商车销调换货公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class CarsaleChangeModel extends Model {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'carsales_change';
	
	// 其他操作
	/*
	 * 调换货单分页查询
	 */
	public function selectPageChangeOrder($p,$pnum,$staff_id,$start,$end,$cust_name){
		if($cust_name!="@@")
			$where["zstb_customer_info.cust_name"]=array("like","%{$cust_name}%");
		if($staff_id!=0)
			$where["a.staff_id"]=$staff_id;
		if($start!=0&&$end!=0)
			$where["a.create_time"]=array(array("egt",strtotime($start)),array("elt",strtotime($end)+24*60*59));
		
		$find = M("carsales_change a");
		
		$where["a.org_parent_id"]=session("org_parent_id");
		
		
		$list=$find->field("zdb_customer_info.cust_name,zdb_customer_info.contact,zdb_org_staff.staff_name,a.*")
		->join("zdb_customer_info on zdb_customer_info.cust_id = a.cust_id")
		->join("zdb_org_staff on zdb_org_staff.staff_id=a.staff_id")
		->where($where)
		->page($p,$pnum)->order("a.create_time desc")->select();
		
		$total=$find->field("zdb_customer_info.cust_name,zdb_customer_info.contact,zdb_org_staff.staff_name,a.*")
		->join("zdb_customer_info on zdb_customer_info.cust_id = a.cust_id")
		->join("zdb_org_staff on zdb_org_staff.staff_id=a.staff_id")
		->where($where)
		->count();
		$page = get_page_code($total, $pnum, $p, $page_code_len = 10);
		$data["list"]=$list;
		$data["page"]=$page;
		return $data;
	}
	
	
	
	
	
	public function selectChangeOrder($change_id){

		
		$obj1 = M("carsales_change a");
		$obj2 = M("carsales_change_goods b");
		
		
		$where["change_id"]=$change_id;
		$res=$obj1->field("zdb_customer_info.cust_name,zdb_customer_info.contact,zdb_customer_info.telephone,zdb_customer_info.province,zdb_customer_info.city,zdb_customer_info.district,zdb_customer_info.address,zdb_org_staff.staff_name,a.*")
		->join("zdb_customer_info on zdb_customer_info.cust_id = a.cust_id")
		->join("zdb_org_staff on zdb_org_staff.staff_id=a.staff_id")
		->where($where)->find();
		
		$goods=$obj2->field("zdb_goods_info.goods_code,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_org_goods_convert.goods_unit,b.singleprice,b.number,b.is_change_in")->
		join("zdb_org_goods_convert on zdb_org_goods_convert.cv_id=b.cv_id")->
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
	
	
	
	
	
	
	
	
	/*
	 * $data=array(
	 *     'change_code'=>'ch454543534',
	 *     'staff_id'=>'71',
	 *     'org_parent_id'=>'2',
	 *     'create_time'=>'454543534',
	 *     'order_total_money'=>'45',
	 *     'cust_id'=>'454543534'
	 * );
	 * $goods=array(
	 *    array(
	 *       'goods_id'=>3,
	 *       'cv_id'=>103,
	 *       'singleprice'=>3.00,
	 *       'number'=>3,
	 *       'is_change_in'=>1,
	 *    );
	 * );
	 */ 

	//添加 调换货单 操作

	public function addChangeOrder($data,$goods,$org_parnt_id){

		// 通过对比业务最后操作时间，防止重复生产

		$lastTime = M("change_orders")
					->where(array_intersect_key($data,array('staff_id' => '','org_parent_id' => '','cust_id' => '')))
					->order('create_time desc')
					->getField('create_time');
		
		if ($data['create_time'] - $lastTime > 5) {

			$change_id = M("change_orders")->add($data);

		} else {

			$change_id = false;

		}
		
		if($change_id !== false){

			foreach($goods as $k=>&$v){

				$goods[$k]["change_id"]=$change_id;

			}

			$res=M("change_order_goods")->addAll($goods);

			if($res !== false){

				$dataCarAdd=array();

				$dataCarDel=array();

				foreach($goods as $k=>&$v){

					if($v["is_change_in"]){

						$dataCarAdd[$k]["goods_id"]=$v["goods_id"];

						$dataCarAdd[$k]["cv_id"]=$v["cv_id"];

						$dataCarAdd[$k]["org_parent_id"]=$org_parnt_id;

						$dataCarAdd[$k]["goods_num"]=$v["number"];

						$dataCarAdd[$k]["staff_id"]=$data["staff_id"];

					}else{

						$dataCarDel[$k]["goods_id"]=$v["goods_id"];

						$dataCarDel[$k]["cv_id"]=$v["cv_id"];

						$dataCarDel[$k]["org_parent_id"]=$org_parnt_id;

						$dataCarDel[$k]["goods_num"]=$v["number"];

						$dataCarDel[$k]["staff_id"]=$data["staff_id"];

					}	

				}

				$CarportInfoModel=new CarportInfoModel();

				$CarportInfoModel->updateCarInfo($dataCarDel,"调换货出车存","del");

				$CarportInfoModel->updateCarInfo($dataCarAdd,"调换货入车存","add");

				return array('res'=>1,'msg'=>'调换成功','id'=>$change_id);	

			}else{

				M("change_orders")->where("change_id=$change_id")->delete();

				return array('res'=>-2,'msg'=>'添加失败','id'=>0);

			}

		}else{

			return array('res'=>-1,'msg'=>'添加失败','id'=>0);

		}
	}
	
	
	/*
	 * 调换货查询
	 */
	public function getChangeOrderInfo($wherea)
	{
		$info = M('ChangeOrders')->where($wherea)->select();
		return $info;
	}
	
}

/****************************** end *******************************/