<?php

/** ****************************************************************
 ** 文件名称:BaseStaffModel.class.php
 ** 功能描述:人员数据库操作。
 ** 创建人员:CST
 ** 创建日期:2014-10-14
 ******************************************************************/
 
namespace Common\Model;
use Think\Model;

class BaseStaffModel extends Model
{
	// 数据表
	protected $tableName = 'org_staff';

	// 数据操作范文
	protected $pcSelectFields = array();
	protected $pcInsertFields = array();
	protected $pcUpdateFields = array();
	protected $mobileSelectFields = array();
	protected $mobileInsertFields = array();
	protected $mobileUpdateFields = array();
	
	
	
	
	// 数据范围检查, 过滤非指定字段
	protected function checkData($data, $operate = 'select', $is_pc = 1)
	{
		// 新数据
		$new_data = array();
		
		// 指定数据
		$appoint_data = $this->getAppointData($operate, $is_pc);
		
		// 过滤非指定字段
		foreach($data as $key=>$value)
		{
			$key = strtoupper($key);
			if(in_array($key, $appoint_data))
			{
				$new_data[$key] = $value;
			}
			else
			{
				continue;
			}
		}
	
		// 返回新数据
		return $new_data;
	}
    //查看人员是否存在
    public function checkStaff($where)
    {
        $res = $this->where($where)->count("staff_id");
        return $res==0?true:false;//不存在，存在
    }
	
	
	// 根据ID查询人员
	public function selectStaffById($staff_id,$org_parent_id)
	{
        $where["staff_id"]=array("eq",$staff_id);
        $where["org_parent_id"]=array("eq",$org_parent_id);
        $res=$this->field("staff_id,staff_name")->where($where)->find();
        return $res;
	}
    
    //注册添加
    public function addStaffRegister($data)
    {
        $res=$this->data($data)->add();
        return $res;
    }
    // 删除人员
    public function delStaff($id)
	{
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }
    //取消封存
    public function open($id,$data){
        $return= $this->where('staff_id='.$id)->save($data);
        return $return?true:false;
    }
    //封存人员
    public function close($id,$data){
        $return=$this->where('staff_id='.$id)->save($data);
        return $return?true:false;
    }
    // 更新人员
    public function editStaff($id,$data,$role_id)
	{
        if(!empty($role_id)){
            $role["role_id"]=$role_id;
            $res1=M("base_staff_role")->where('staff_id='.$id)->save($role);
        }
        return $this->where('staff_id='.$id)->save($data);
	}


    //修改人员信息
    public function editStaffInfo($data,$staff_id,$org_parent_id){
        $where["org_parent_id"]=$org_parent_id;
        $where["staff_id"]=$staff_id;
        $res=$this->where($where)->save($data);
        return $res>=0?true:false;  //成功：失败
    }
	// 人员所有角色
	public function getStaffRole($staff_id)
	{
		return array();
	}
	
	// 添加人员角色
	public function addStaffRole($data)
	{
		return true;
	}
	
	// 修改人员角色
	public function editStaffRole($staff_id, $data)
	{
		return ture;
	}
	
	// 删除人员角色
	public function delStaffRole($record_id)
	{
		return true;
	}
	//查看所有人员
    public function selStaffList($field,$where)
    {
        $res=$this->field($field)->where($where)->select();       
        return $res;
    }
	

    //以下未检查
    //获取人员名字id//
   public function getStaffList($org_parent_id,$staff_type=0)
   {
       if($staff_type==1)
           $where["is_admin"]=1;
       else
           $where["is_admin"]=0;
       $where["org_parent_id"]=$org_parent_id;
       $where["is_close"]=0;
       $res=$this->field("staff_id,staff_name")->where($where)->order('staff_id asc')->select();
       return $res;
   }
    public function getStaffListId($org_parent_id)
    {
        $where["org_parent_id"]=$org_parent_id;
        $where["is_close"]=0;
        $where["is_admin"]=0;
        $res=$this->where($where)->getField("staff_id",true);
        return $res;
    }
   //添加金币数量
    public function addGold($num,$staff_id){
        if($this->where("staff_id=".$staff_id)->setInc('gold_num',$num))
            return true;
        else
            return false;
    }
    //查询用户pushid
    public function pushid($userId){
        $result=$this->field("pushid")->where("staff_id=".$userId)->find();
        return $result["pushid"];
    }
    //查询用户金币总数
    public function goldNum($userId){
        $result=$this->field("gold_num")->where("staff_id=".$userId)->find();
        return $result["gold_num"];
    }
    //根据商铺去查业务员姓名
    public function custStaff($org_parent_id,$cust_id)
    {
       return $this->table("zstb_customer_staff cs")->join("inner join zstb_base_staff bs on bs.staff_id=cs.staff_id and bs.org_parent_id=$org_parent_id")->where("cs.org_parent_id=$org_parent_id and cs.shop_id=$cust_id")->getField("staff_name",true);
    }
    //获所有条人员数据（在select中使用） $org_parent_id:父机构id,$status:1已封存的，0为未封存的,$id:排除部门id,$dep_id;所属部门id
    public function getStaff($org_parent_id, $status, $dep_id="")
    {
        if(!empty($dep_id))
            $where["dep_id"] = array('eq',$dep_id);
        $where["org_parent_id"]=$org_parent_id;
        $where["gender"]=array("neq",0);
        if($status==1)
            $where["is_close"]=1;
        else if($status==0)
            $where["is_close"]=0;
        $res=$this->field("staff_id,staff_name")->where($where)->select();
        return $res;
    }
    public function getStaffName($id){
    	$name=$this->where("staff_id=$id")->getField("staff_name");
    	return $name;
    }

    //获取指定角色人员列表（option使用）   //5司机 3 业务员 * **
    public function getStaffRoleList($org_parent_id,$role_id){
        $res=M("BaseStaffRole")->field("staff_id")->where("role_id=$role_id and org_parent_id=$org_parent_id")->select();
        if(count($res)!=0){
            $ids=array();
            for($i=0;$i<count($res);$i++){
                $ids[$i]=$res[$i]["staff_id"];
            }
            $where["staff_id"]=array("in",$ids);
            $data=$this->field("staff_id,staff_name")->where($where)->select();
            if(count($data)==0)
                $return=array();
            else
                $return=$data;
        }else{
            $return=array();
        }
        return $return;
    }
    
    //根据业务员名字获取ID
    public function getStaffId($org_parent_id, $staff_name)
    {
    	$staff = $this->where(array('org_parent_id'=>$org_parent_id,'staff_name'=>$staff_name))->find();
		if ($staff && !empty($staff)) {
			return $staff;
		}
		return array();
    }

    /**
     * llx  获取所有用户信息
     */
    public function getAllBaseStaff(){
    	
    	
    	$Allstaff = S('Allstaff');
    	if($Allstaff){
    		$staff = $Allstaff;
    		
    	}else{
    	
	    	$Allstaff = $this->field('staff_id,staff_name')->select();
	    	
	    	foreach($Allstaff as $v){
	    		
	    		$staff[$v['staff_id']] = $v['staff_name'];
	    	
	    	}
	    	
	    	S('Allstaff',$staff,10*60);
    	}
    	return $staff;
    }
    
    
    /**
     * 获取在线/离线业务员列表及人数
     * @param unknown org_parent_id(必填)：商户id
     * @return array $num                      
     * zhaolina
     */
    
     public function getStaffwork($org_parent_id){
     	
     	$staff_ids=array();
	    $allList=$this->getStaffRoleList($org_parent_id,3);//获取该经销商下所有业务员	     	    		     	
	    foreach ($allList as $v){     		
	     	$staff_ids[]=$v['staff_id'];
	     }   
     	 
     	$allNum=count($allList);//总人数
     	//在线人数
     	$time=time();     	
     	$actionM = new \Common\Model\ActionPositionModel();
     	$num['online']=$actionM->getOnline($staff_ids, $time);
     	$num['online_num']=count($num['online']);
     	$num['off_num']=$allNum-$num['online_num']; 
     	$num['off']=array();
     	$i=0;
     	
     	//获取离线列表
     	if($num['online']){     		     		
     		foreach ($allList as $k=>$v){
     			$flag=true;     			     			
    			foreach ($num['online'] as $kk=>$vv){     				
     				if($v['staff_id']==$vv['staff_id']){
     					$flag=false;
     				}
     			}
     			if($flag) $num['off'][$i]=$v;
     			$i++;
     		}
     	}else{     		
     		$num['off']=$allList;
     	}    	       	
     	return $num;
     }
    
     
     /**
      * 获取在线/离线业务员某天的拜访量
      * @param unknown $org_parent_id(必填):经销商id
      * @param unknown $day(必填)          :日期"2016-06-08"
      * @param unknown $staff_name(选填)   :业务员名称
      * @return array
      * zhaolina
      */
     
     public function getStaffVisitNumList($org_parent_id,$day){
     	    	    	
        $start_time=strtotime($day);
        $end_time=strtotime($day)+24*60*60-1;     	          
     	$bs_actionM = new \Common\Model\BaseStaffModel();
	 	$list=$bs_actionM->getStaffwork($org_parent_id);//获取在线（离线）列表  
	 	
     	//在线列表
//      	$list['online']=$this->getStaffListVisitNum($org_parent_id, $list['online'], $start_time, $end_time);     	
//      	//离线列表
//      	$list['off']=$this->getStaffListVisitNum($org_parent_id, $list['off'], $start_time, $end_time);
        $visitNum=$this->getStaffListVisitNumNew($org_parent_id,$start_time, $end_time);
        $list['online']=$this->getStaffVisitCount($list['online'],$visitNum);
        $list['off']=$this->getStaffVisitCount($list['off'],$visitNum);    
     	return $list;
     }
     
     
     /**
      * 整合业务员拜访量
      * @param unknown $org_parent_id(必填):经销商id
      * @param unknown $day(必填)          :日期"2016-06-08"
      * @param unknown $staff_name(选填)   :业务员名称
      * @return array
      * zhaolina
      */
     
     public  function  getStaffVisitCount($list,$visitList){
     	foreach ($list as $k=>$v){
     		if(isset($visitList[$v['staff_id']])){
     			$list[$k]['count']=$visitList[$v['staff_id']];
     		}else{
     			$list[$k]['count']=0;
     		}
     	}
     	
     	$count=array();
     	foreach ($list as $v){
     		$count[]=$v['count'];
     	}
     	array_multisort($count,SORT_DESC,$list);
     	foreach ($list as $k=>$v){
     		$list[$k]['order']=$k+1;
     	}
     	return $list;
     }
     
     
     /**
      * 获取列表下业务员拜访量
      * @param unknown $org_parent_id(必填):经销商id
      * @param unknown $list(必填)         :业务员列表
      * @param unknown $start_time(必填)   :开始时间
      * @param unknown $end_time(必填)     :结束时间
      * @return array
      * zhaolina
      */
     
     public function getStaffListVisitNum($org_parent_id,$list,$start_time,$end_time){
     	
     	$actionM = new \Common\Model\ActionPositionModel();
     	foreach ($list as $k=>$v){
     		
     		$list[$k]['count']=$actionM->getStaffVisitNum($org_parent_id,$v['staff_id'],$start_time,$end_time);
     	}
        $count=array();
     	foreach ($list as $v){
     		$count[]=$v['count'];
     	}
     	array_multisort($count,SORT_DESC,$list);
     	foreach ($list as $k=>$v){
     		$list[$k]['order']=$k+1;
     	}
     	return $list;
     }
     
     /**
      * 获取列表下业务员拜访量
      * @param unknown $org_parent_id(必填):经销商id
      * @param unknown $list(必填)         :业务员列表
      * @param unknown $start_time(必填)   :开始时间
      * @param unknown $end_time(必填)     :结束时间
      * @return array
      * zhaolina
      */
      
     public function getStaffListVisitNumNew($org_parent_id,$start_time,$end_time){
     
     	$actionM = new \Common\Model\ActionPositionModel();
     	
     	$list=$actionM->getStaffVisitList($org_parent_id,0,$start_time,$end_time);
     	
     	$new_list=array();
     	foreach ($list as $v){
     		if(!isset($new_list[$v['saleman_id']][$v['shop_id']]))$new_list[$v['saleman_id']][$v['shop_id']]=$v;
     	}
     	$res_list=array();
     	foreach ($new_list as $k=>$v){
     		$res_list[$k]=count($v);
     	}
     		
     	return $res_list;
     }
      
     
     /**
      * 获取经销商下业务员一个月的销量列表
      * @param unknown $org_parent_id(必填):经销商id
      * @param unknown $start_time(必填)   :开始日期
      * @param unknown $end_time(必填)     :结束日期
      * @param unknown $staff_name(选填)   :业务员名称
      * @return array
      * zhaolina
      */
     
     	public function saleslistByStaff($org_parent_id,$start_time,$end_time,$staff_name=''){
     		
//      		$where['_string'] = "(order_type = 1 and yu_status = 0) or (order_type = 5 and yu_status = 3)";
     		$where['org_parent_id']=$org_parent_id;
     		$start_time=strtotime($start_time);
     		$end_time=strtotime($end_time)+24*60*60-1;
     		$where['create_time']=array('between',array($start_time,$end_time));
     		if($staff_name){
     			$where['staff_name']=array('like',"%".$staff_name."%");
     			$allList[0]['staff_name']=$staff_name;
     			$staff=$this->getStaffId($org_parent_id, $staff_name);
     			$allList[0]['staff_id']=$staff['staff_id'];
     			$total=M("st_orders")->where($where)->group("staff_id")->sum('order_total_money');
     			
     			if(!$total){
     				$total='0';
     			}
     			$allList[0]['total']=$total;
     		}else{
     			$allList=$this->getStaffRoleList($org_parent_id,3);//获取该经销商下所有业务员
     			foreach ($allList as $k=>$v){     				
     				$where['staff_id']=$v['staff_id'];
     				$total=M("st_orders")->where($where)->sum('order_total_money');   		
     				$allList[$k]['total']=$total?$total:'0';
     			}
     		}     		
     		$sale_array=array();
     		foreach ($allList as $v){
     			$sale_array[]=$v['total'];
     		}
     		array_multisort($sale_array,SORT_DESC,$allList);
     		return $allList;
     	}
     	
     	
     	/**
     	 * 根据业务员在线状态获取一天的销量列表
     	 * @param unknown $org_parent_id(必填):经销商id
     	 * @param unknown $day(必填)          :日期"2016-05-12"
     	 * @param unknown $staff_name(选填)   :业务员名称
     	 * @return array
     	 * zhaolina
     	 */
     	
     	public function saleslistByStaffStatus($org_parent_id,$day){
     		
     		$start_time=strtotime($day);
     		$end_time=strtotime($day)+24*60*60-1;
     		$map['create_time']=array('between',array($start_time,$end_time));
     		$map['org_parent_id']=$org_parent_id;
     		$list=$this->getStaffwork($org_parent_id);//获取在线（离线）列表
     		//在线列表
     		$list['online']=$this->getStaffSalesByDay($list['online'], $map);     		
     		//离线列表
     		$list['off']=$this->getStaffSalesByDay($list['off'], $map);     		
			return $list;	
     	}
     
     	
     	/**
     	 * 获取业务员一天的销量
     	 * @param array $list(必填)      :业务员列表
     	 * @param array $map(必填)       :条件数组 
     	 * @return array
     	 * zhaolina
     	 */
     	
     	 public function getStaffSalesByDay($list,$map){
     	 	
     	 	$actionM= new \Common\Model\StOrdersModel();
     	 	foreach ($list as $k=>$v){
     	 		$map['staff_id']=$v['staff_id'];
     	 		$real_money=$actionM->getOrderRealMoney($map);
     	 		$list[$k]['total']=$real_money['order_total_money']?$real_money['order_total_money']:'0';
     	 	}
     	 	$total=array();
     	 	foreach ($list as $v){
     	 		$total[]=$v['total'];
     	 	}
     	 	array_multisort($total,SORT_DESC,$list);
     	 	foreach ($list as $k=>$v){
     	 		$list[$k]['order']=$k+1;
     	 	}
     	 	return $list;
     	 }    
     	
}

