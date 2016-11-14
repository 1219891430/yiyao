<?php


namespace Common\Model;
use Think\Model;

class CustomerInfoModel extends Model {
	protected $tableName = "customer_info";
	
	public function isHaveCustomer($telephone){
		 $where["telephone"]=$telephone;
		 $res=$this->where($where)->find();
		 return $res;
	}
	
	
	public function OrgDeleteCustomer($cust_id){
		$where["shop_id"]=$cust_id;
		$res1=M("org_customer")->where($where)->delete();
		$res2=M("org_staff_customer")->where($where)->delete();
		if($res1&&$res2){
			$res["code"]=1;
			$res["msg"]="删除成功";
			return $res;
		}else{
			$res["code"]=0;
			$res["msg"]="删除失败";
			return $res;
		}
	}
	
	
	public function addCust($data){
		$telephone=$data["telephone"];
		$res=$this->isHaveCustomer($telephone);
		if($res){//有重复数据
			$dataCust["shop_id"]=$res["cust_id"];
			$dataCust["org_parent_id"]=$data["org_parent_id"];
			$dataCust["shop_type"]=$data["shop_type"];
			$dataCust["staff_id"]=end($data["staffIds"]);
			$dataCust["add_time"]=time();
			$where["shop_id"]=$dataCust["shop_id"];
			$where["org_parent_id"]=$dataCust["org_parent_id"];
			$resOrgCust=M("org_customer")->where($where)->count();
			if($resOrgCust){
				$resl["msg"]="已有数据不要重复插入";
				$resl["code"]=2;
				return $resl;
			}else{
				
				
				
				$res1=M("org_customer")->add($dataCust);
				
				unset($dataCust);
				foreach($data["staffIds"] as $k=>$staff_id){
					$dataCust[$k]["staff_id"]=$staff_id;
					$dataCust[$k]["org_parent_id"]=$data["org_parent_id"];
					$dataCust[$k]["shop_id"]=$res["cust_id"];
				}
				$res2=M("org_staff_customer")->addAll($dataCust);
				if($res1==$res2){
					$resl["msg"]="创建成功";
					$resl["code"]=1;
					return $resl;
				}else{
					$resl["msg"]="创建失败";
					$resl["code"]=0;
					return $resl;
				}
			}
			
			
		}else{//没有重复数据
		
		     //插入主表
		     $dataCust["cust_name"]=$data["cust_name"];
			 $dataCust["contact"]=$data["contact"];
			 $dataCust["telephone"]=$data["telephone"];
		     $dataCust["province"]=$data["province"];
			 $dataCust["city"]=$data["city"];
			 $dataCust["district"]=$data["district"];
			 $dataCust["address"]=$data["address"];
			 $dataCust["longitude"]=$data["lngX"];
			 $dataCust["dimension"]=$data["latY"];
			 $dataCust["repertory_id"]=$data["repertory_id"];
			 $dataCust["staff_id"]=0;
			 $dataCust["reg_time"]=time();
			 $dataCust["is_check"]=0;
			 $dataCust["is_close"]=0;
			 $dataCust["loginname"]=$dataCust["telephone"];
			 $dataCust["loginpwd"]=md5("123456");
			 $cust_id=$this->add($dataCust);
			
			 if($cust_id){
			 	//插入从表
			 	$dataCust["shop_id"]=$cust_id;
				$dataCust["org_parent_id"]=$data["org_parent_id"];
			 	$dataCust["shop_type"]=$data["shop_type"];
			 	$dataCust["staff_id"]=end($data["staffIds"]);
			 	$dataCust["add_time"]=time();
				$res1=M("org_customer")->add($dataCust);
				unset($dataCust);
				foreach($data["staffIds"] as $k=>$staff_id){
					$dataCust[$k]["staff_id"]=$staff_id;
					$dataCust[$k]["org_parent_id"]=$data["org_parent_id"];
					$dataCust[$k]["shop_id"]=$cust_id;
				
			    }
			    $res2=M("org_staff_customer")->addALL($dataCust);
			    
			 	if($res1&&$res2){
					$resl["msg"]="创建成功";
					$resl["code"]=1;
					return $resl;
				}else{
					$resl["msg"]="创建失败";
					$resl["code"]=0;
					return $resl;
				}
			 }else{
			 	
				$resl["msg"]="创建失败";
				$resl["code"]=0;
				return $resl;
			}
			
			 
			 
		}
	}
	
	
	
}