<?php

/*******************************************************************
 ** 文件名称: CustomerController.class.php
 ** 功能描述: 经销商PC端客户管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CustomerController extends Controller {

	// 店铺 
	public function indexAction()
	{
		
       
        $p=isset($_GET['p'])?$_GET['p']:1;
        $pnum=I("get.pnum",10);

        $customer=M("CustomerInfo");
        
		
        $where["oc.org_parent_id"]=session("org_parent_id");
        
        $staff_id=I("get.staff_id",0);
        
        if($staff_id){
        	$where["osc.staff_id"]=$staff_id;
        }
           
        // 店铺创建时间区间查询
        $total=$customer->table("zdb_customer_info as ci")->
        join("left join zdb_org_customer as oc on oc.shop_id=ci.cust_id")->
         join("left join zdb_org_staff_customer as osc on osc.shop_id=oc.shop_id")->
        join("left join zdb_customer_type as ct on ct.ct_id=oc.shop_type")->
        where($where)->count("distinct(cust_id)");//总数

        $list =$customer->table("zdb_customer_info as ci")->
        field("distinct(cust_id),cust_name,ct_name,contact,telephone,province,city,district,address,is_close,ci.reg_time")->
        join("left join zdb_org_customer as oc on oc.shop_id=ci.cust_id")->
        join("left join zdb_org_staff_customer as osc on osc.shop_id=oc.shop_id")->
		
        join("left join zdb_customer_type as ct on ct.ct_id=oc.shop_type")->
        where($where)->order('ci.reg_time desc')->page($p,$pnum)->select();//列表
        foreach($list as $k=>$v){
        	
			$staffList=M("org_staff_customer")->field("zdb_org_staff.staff_name")->join("zdb_org_staff on zdb_org_staff_customer.staff_id=zdb_org_staff.staff_id")->where("shop_id=".$v["cust_id"])->select();
			foreach($staffList as $vv){
				$list[$k]["staff_name"].=$vv["staff_name"].",";
			}
			
			
        }
        $whereStaff["org_parent_id"]=session("org_parent_id");
        $staffList=M("org_staff")->where($whereStaff)->select();
		$this->assign("staffList",$staffList);
		
		
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示
        $this->assign("staff_id",$staff_id);
        $this->assign("p",$p);
        $this->assign("pnum",$pnum);
        $this->display("index");
    }
	
	
	//添加客户
    public function addCustomerAction(){
        // 添加页面
        if(IS_GET)
        {
            
            $whereStaff["org_parent_id"]=session("org_parent_id");
			$whereStaff["role_id"]=3;
        	$staffList=M("org_staff")->where($whereStaff)->select();
			$this->assign("staffList",$staffList);
			$typelist=M("customer_type")->where($whereStaff)->select();
			$this->assign("typelist",$typelist);
            $this->display("customer_add");
        }
//      //提交添加
        if(IS_POST){
        	
            $mCustormer=D("CustomerInfo");
            $data=array();
            $data["org_parent_id"]=session("org_parent_id");

            $data["repertory_id"]=session("depot_id");

            $data["cust_name"]=I("post.cust_name");
            $data["contact"]=I("post.contact");
			$data["telephone"]=I("post.telephone");
            $data["shop_type"]=I("post.cust_type");
		    $data["business_area"]=I("post.business_area");
			
			$data["province"]=I("post.province","dfg");
			$data["city"]=I("post.city","dfgf");
			
			$data["district"]=I("post.district","fg");
			
			$data["lngX"]=I("post.lngX","122");
			$data["latY"]=I("post.latY","30");
			$data["address"]=I("post.address","fg");
			$data["staffIds"]=I("post.staffIds",array(1,2));
            
            $res=$mCustormer->addCust($data);
            echo json_encode($res); 
                 
              
        }
    }
	
	public function delCustomerAction(){
		$cust_id=I("post.cust_id");
		$res=D("CustomerInfo")->OrgDeleteCustomer($cust_id);
		echo json_encode($res);
	}
	
	public function updateAllAction(){
    	$cust_ids=I("get.cust_ids");
    	
    	$where["cust_id"]=array("in",$cust_ids);
    	$custlist=M("customer_info")->field("cust_id,cust_name,province,city,district,address")->where($where)->select();
		
		unset($where);
        $where["org_parent_id"]=session("org_parent_id");
		$where["role_id"]=3;
		
     	$staff_list=M("org_staff")->field("staff_id,staff_name")->where($where)->select();
    	$this->assign("cust_ids",$cust_ids);
    	$this->assign("staff_list",$staff_list);
    	$this->assign("custlist",$custlist);
    	$this->display();
    }
    public function updateAllExecAction(){
    	$staff_ids=I("post.staff_ids");
    	$cust_ids=I("post.cust_ids");
    	$i=0;
    	foreach ($staff_ids as &$staff_id){
    		foreach($cust_ids as &$cust_id){
    			$data[$i]["staff_id"]=$staff_id;
    			$data[$i]["shop_id"]=$cust_id;
    			$data[$i]["org_parent_id"]=session("org_parent_id");
    			$i++;
    		}
    		
    	}
    		 
    	$where["shop_id"]=array("in",$cust_ids);
    	$res=M("org_staff_customer")->where($where)->delete();
    	if($res){
    		$res1=M("org_staff_customer")->addAll($data);
    		if($res1){
    			echo 1;
    		}else{
    			echo 2;
    		}
    	}else{
    		echo 0;
    	}
    	
    	
    }
    
	
	
	
	
	
	
	
	/* 
	 * 店铺类型 
	 */
	public function typeAction(){
		
		$type=M("customer_type");
		$p=I("get.p",1);
        $pnum=I("get.pnum",10);
		
		$where["org_parent_id"]=session("org_parent_id");
		$list=$type->where($where)->page($p,$pnum)->select();
		$total=$type->where($where)->count();
		
		$page=get_page_code($total,$pnum,$p, $page_code_len = 5);
		$this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
		$this->assign("list",$list);
		$this->display();
	}
	
	public function addTypeAction(){
        //header('Content-Type:text/html    ;charset=utf-8');
        // 添加页面
        if(IS_GET)
        {
//          $dataList=$this->getDep(1,0,3);
//          $this->assign('dataList',$dataList);//列表内容
            $this->display("type_add");
        }
        //提交添加
        if(IS_POST){
            //机构Id
            $type=M("customer_type");
            $_POST["org_parent_id"]=session("org_parent_id");
            $res=$type->add($_POST);
            if($res){
                echo json_encode(array("info"=>"添加成功！","res"=>1));
            }else{
                echo json_encode(array("info"=>"添加失败！","res"=>0));
            }
        }
    }
	
	
	
	public function delTypeAction(){

	    $id = I('post.ct_id');

        $has = M('org_customer')->where("shop_type=$id")->count();

        if ($has > 0) {
            $res["res"]=0;
            $res["info"]="删除失败，该类型下存在客户";
            echo $this->ajaxReturn($res,"json");
            return;
        }


        $dep=D("CustomerType");
        $result=$dep->delete($id);
        if($result){
            $res["res"]=1;
            $res["info"]="删除成功";
             echo $this->ajaxReturn($res,"json");
        }else{
            $res["res"]=0;
            $res["info"]="删除失败";
            echo $this->ajaxReturn($res,"json");
        }
        
    }

    //编辑客户类型
    public function editTypeAction(){
        //编辑页面
        if(IS_GET){
            $type=M("CustomerType");
            $data=$type->find($_GET["id"]);
            $this->assign('data',$data);
            $this->display("type_edit");
        }
        //提交页面
        if(IS_POST){
            $type=M("CustomerType");
			$where["ct_id"]=$_POST["ct_id"];
			$data["ct_name"]=$_POST["ct_name"];
			$data["ct_remark"]=$_POST["ct_remark"];
			$data["org_parent_id"]=$_SESSION["org_parent_id"];
            $return=$type->where($where)->save($data);
            if($return){
                echo json_encode(array("info"=>"修改成功！","res"=>1));
            }else{
                echo json_encode(array("info"=>"修改失败！","res"=>0));
            }
        }
    }

    // 封存
    public function fengcunAction() {
        $cust_id = I('post.cust_id');

        $cust = M('customer_info')->where("cust_id=$cust_id")->find();

        if ($cust['is_close'] == 0) {
            $data['is_close'] = 1;
            $succ = M("customer_info")->where("cust_id=$cust_id")->save($data);

            if ($succ) {
                $res = [
                    'status'=>true,
                    'msg'=>'封存成功'
                ];
            }else{
                $res = [
                    'status'=>false,
                    'msg' => '封存失败'
                ];
            }
        } elseif ($cust['is_close'] == 1) {
            $data['is_close'] = 0;
            $succ = M("customer_info")->where("cust_id=$cust_id")->save($data);

            if ($succ) {
                $res = [

                    'status'=>true,
                    'msg'=>'解封成功'
                ];

            }else{
                $res = [
                    'status'=>false,
                    'msg' => '解封失败'
                ];

            }
        } else {
            $data['is_close'] = 1;
            M("customer_info")->where("cust_id=$cust_id")->save($data);
            $res = [
                'status'=>false,
                'msg' => '数据错误，已将用户封存，请重新操作'
            ];
        }

        echo $this->ajaxReturn($res,"json");

    }
	
	
	
	
	
	/** 其他Action **/


}

/*************************** end ************************************/