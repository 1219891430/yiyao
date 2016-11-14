<?php
/**
 * 文件名称：ActionPositionModel.class.php.
 * 功能：业务员的行动轨迹
 * 创建人: zy
 * Time:14-10-27 17:02
 */


namespace Common\Model;


use Think\Model;

class ActionPositionModel extends Model{
    protected $tableName="org_action_position";
    protected $res;

    //间隔数据
    public function selectPostion($where)
    {
    	
       
        $map = array(
            'longitude'=>array('neq','0.0'),
            'dimension' => array('neq','0.0'),
              'saleman_id' =>$where["saleman_id"],
              'today' =>$where["today"],
            'org_parent_id' =>$where["org_parent_id"],           		
        );
        
		
        $field = "now_time,longitude,dimension";
        $res=$this->field($field)->where($map)->order("now_time desc")->select();
        
        
        foreach($res as &$v){
        	$date = date('Y-m-d H:i',$v['now_time']);
        	
        	$v['now_time'] = $date;
        	$v['now_Hi'] = $date;
        	$v['time'] = $v['now_time'];
        }
        
        
        return $res;
    }
//  //添加行动轨迹
//  public function addPosition($data)
//  {
//      $posCount=0;
//      if($data["act_status"]==2&&$data["longitude"]!="0.0"&&$data["dimension"]!="0.0")
//      {
//          $where=$data;
//          unset($where["now_time"]);
//          unset($where["longitude"]);
//          unset($where["dimension"]);
//          $lastPosition=$this->field("pos_id,longitude,dimension")->where($where)->order("now_time desc")->limit("0,1")->find();
//          $dataTime["now_time"]=time();
//          if($lastPosition["longitude"]==$data["longitude"]&&$lastPosition["dimension"]==$data["dimension"])
//              $this->res=$this->where("pos_id=%d",array($lastPosition["pos_id"]))->save($dataTime);
//          else
//              $this->res=$this->data($data)->add();
//      }
//      else{
//          $this->res=$this->data($data)->add();
//      }
//      return  $this->res?true:false;//成功:失败
//  }
//  //业务员上次拜访时间  参数数组
//  public function selBeforePosition($data)
//  {
//      $data["act_status"]=1;
//      
//      foreach($data as $k=>$v){
//      	if(!$v){
//      		unset($data[$k]);        		
//      	}
//      	
//      }
//      $res=$this->where($data)->field("today")->order("today desc")->find();
//      return $res["today"];
//  }
//  public function selBeforePositionTime($data)
//  {
//      $data["act_status"]=1;
//      $res=$this->where($data)->field("now_time as today")->order("today desc")->find();
//      return $res["today"];
//  }
//  public function selPosByid($org_parent_id,$pos_id)
//  {
//      $where["org_parent_id"]=$org_parent_id;
//      $where["pos_id"]=$pos_id;
//     return $this->where($where)->find();
//  }
//  
//  /**
//   * 拜访动作
//   * @param  $org_parent_id：商户id
//   * @param  $staffId ：业务员id
//   * @param  $start_time   
//   * @param  $end_time           
//   * hjj
//   */
//  
//	public function getVisitList($org_parent_id,$staffId,$start_time,$end_time){
//		
//		//日期字符串
//		$str_start =$start_time;
//		$end =strtotime($end_time)+24*3600-1;
//		$str_end = date("Y-m-d H:i:s",$end);
//		//日期时间戳
//		$start_time=$start_time==''?0:strtotime($start_time);
// 		$end_time=$end_time==''?0:strtotime($end_time)+24*3600-1;
//		
//		
//		if($cust_id) $where['cust_id'] = $cust_id;
//  	$where["zstb_customer_staff.staff_id"]=$staffId;
//  	$where["zstb_customer_info.create_time"]=array(array("gt",$start_time),array("lt",$end_time));
//  	$Model=M();
//  	//店铺新增
//  	$list=$Model
//  	->field("DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,create_time as enterTime,'新增店铺' as type")
//  	->table('zstb_customer_info')
//  	->join("zstb_customer_staff on zstb_customer_info.cust_id=zstb_customer_staff.shop_id")
//  	->where($where)
//  	
//  	//拍照
//  	->union(
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,add_time as enterTime,'拍照' as type FROM zstb_shop_display
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_shop_display.shop_id
//  			where add_time>$start_time and add_time<$end_time and saleman_id=$staffId
//  			"
//  	)
//  	//预单
//  	->union(
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_st_orders.create_time as enterTime,'预单' as type FROM zstb_st_orders
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_st_orders.cust_id
//  			where zstb_st_orders.create_time>$start_time and zstb_st_orders.create_time<$end_time and zstb_st_orders.staff_id=$staffId and zstb_st_orders.order_type=5
//  			
//  			"
//  	)
//  	//陈列兑付
//  	->union(
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_st_orders.create_time as enterTime,'陈列兑付' as type FROM zstb_st_orders
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_st_orders.cust_id
//  			where zstb_st_orders.create_time>$start_time and zstb_st_orders.create_time<$end_time and zstb_st_orders.staff_id=$staffId and zstb_st_orders.order_type=3
//  			 
//  			"
//  	)
//  	//车销
//  	->union(
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_st_orders.create_time as enterTime,'车销' as type FROM zstb_st_orders
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_st_orders.cust_id
//  			where zstb_st_orders.create_time>$start_time and zstb_st_orders.create_time<$end_time and zstb_st_orders.staff_id=$staffId and zstb_st_orders.order_type=1
//  	
//  			"
//  	)
//  	//退货
//  	->union(
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_carsales_return.create_time as enterTime,'退货' as type FROM zstb_carsales_return
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_carsales_return.cust_id
//  			where zstb_carsales_return.create_time>$start_time and zstb_carsales_return.create_time<$end_time and zstb_carsales_return.staff_id=$staffId
//  			"
//  	)
//  	//终端调换货
//  	->union(
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_change_orders.create_time as enterTime,'终端调换货' as type FROM zstb_change_orders
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_change_orders.cust_id
//  			where zstb_change_orders.create_time>$start_time and zstb_change_orders.create_time<$end_time and zstb_change_orders.staff_id=$staffId
//  			"
//  		   )		
//  	//店铺日志
//  	->union (
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_shop_log.log_time as enterTime,'店铺日志' as type FROM zstb_shop_log
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_shop_log.shop_id
//  			where zstb_shop_log.log_time>$start_time and zstb_shop_log.log_time<$end_time and zstb_shop_log.saleman_id=$staffId
//  			"
//  			)
//  	//预存款
//  	->union (
//  			"
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_st_yufu_orders.time as enterTime,'预存款' as type FROM zstb_st_yufu_orders
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_st_yufu_orders.shopId
//  			where zstb_st_yufu_orders.time>$start_time and zstb_st_yufu_orders.time<$end_time and zstb_st_yufu_orders.userId=$staffId
//  			
//  			"
//  			)
//  	//库存盘点
//  	->union("
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_inventory_submit.create_time as enterTime,'库存盘点' as type FROM zstb_inventory_submit
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_inventory_submit.cust_id
//  			where zstb_inventory_submit.create_time>$start_time and zstb_inventory_submit.create_time<$end_time and zstb_inventory_submit.staff_id=$staffId
//  					 
//  			")
//     //付费陈列->陈列照片
//  	->union("
//  			SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_display_photo.add_time as enterTime,'陈列照片' as type FROM zstb_display
//  			inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_display.shop_id
//  			inner join zstb_display_photo on zstb_display_photo.display_id=zstb_display.id
//  			where zstb_display_photo.add_time>'".$str_start."' and zstb_display_photo.add_time<'".$str_end."' and zstb_display.staff_id=$staffId
//  			
//  		   ")
//     //付费陈列->陈列新增
//     ->union("
//     		   SELECT DISTINCT zstb_customer_info.cust_name as shopName,zstb_customer_info.cust_id,zstb_display.create_time as enterTime,'陈列新增' as type FROM zstb_display
//     		   inner join zstb_customer_info on zstb_customer_info.cust_id=zstb_display.shop_id
//     		   where zstb_display.create_time>'".$str_start."' and zstb_display.create_time<'".$str_end."' and zstb_display.staff_id=$staffId
//     		")
//   
//  		  
//     ->select();
//  	
//  	foreach ($list as $k=>&$v){
//  		
//  		if($v["type"] =="陈列新增" || $v["type"]=="陈列照片"){
//  			
//  			
//  			$v["enter_today"]=date("Y-m-d",strtotime($v["enterTime"]));
//  			
//  		}else{
//  			
//  		    $v["enter_today"]=date("Y-m-d",$v["enterTime"]);
//  			$v["enterTime"]=date("Y-m-d H:i:s",$v["enterTime"]); 
//  		}
//
//  		
//  		
//  	}
//  	
//  	
//  	
//  	$new_list=array();
//  	foreach ($list as $kk=>$vv){
//  		
//  		if(isset($new_list[$vv['cust_id']][$vv['enter_today']])){
//  			
//  			if(strpos($new_list[$vv['cust_id']][$vv['enter_today']]['type'],$vv['type'])===false){
//  				$new_list[$vv['cust_id']][$vv['enter_today']]['type'] .='/'.$vv['type'];
//  			}    			
// 			    			
//  		}else{
//  			
//  			$new_list[$vv['cust_id']][$vv['enter_today']]=$vv;
//  		}
//  		
//  	}
//      
//  	
//  	$res_array=array();
//  	foreach ($new_list as $k=>$v){
//  		foreach ($v as $kk=>$vv){
//  			$res_array[]=$vv;
//  		}
//  		
//  	}    	    	
//  	
//   	foreach ($res_array as $key => $value) {
//   	
//   		$times[$key] = $value['enterTime'];
//   	
//  	}
//  	
//  	array_multisort($times, SORT_DESC, $res_array);
//   	return $res_array;
//  	
// }
    
    
    public function getActionList($staffId,$date){
    	
    	$start_time=strtotime($date);
    	$end_time=$start_time+3600*24;
		$where["add_time"]=array(array("gt",$start_time),array("lt",$end_time));
    	$where["saleman_id"]=$staffId;
    	$list=M("customer_weihu")
    	->field("shop_id as shopid,cust_name as shopname,address,longitude,dimension,add_time as entertime,type")
    	->join("zdb_customer_info on zdb_customer_info.cust_id=zdb_customer_weihu.shop_id")
		->order("entertime desc")
    	->where($where)->select();
		
    	return $list;
    		
    }
   
    
    /* 获取所有业务员在线天数
     * @param unknown $saleman_id(业务员id)
     * @param unknown $org_parent_id (经销商id)
     * @param unknown $start_time
     * @param unknown $end_time
     * hjj
     */
//  public function getAllStaff($org_parent_id,$start_time,$end_time){
//  
//
//  	$where["ap.org_parent_id"]  = array("eq",$org_parent_id);
//  	$where['ap.now_time'] = array('between',array($start_time,$end_time));
//  	
//	    
//		$res = M('action_position ap')
//		       ->join('zstb_base_staff zs on zs.staff_id=ap.saleman_id')
//		       ->field('zs.staff_name,zs.staff_id,ap.today')
//		       ->where($where)
//		       ->group("ap.saleman_id,ap.today")
//		       ->select();
//  
//		foreach ($res as $v){
//			
// 			$new[$v['staff_id']][$v['today']]= $v;
//	    }
//	   
//	 
//	    $res_list=array();
//	    
//	    foreach($new as $kk=>$vv){
//	    	$res_list[$kk]=count($vv);	    
//	    }
//	  
//		
//		return $res_list;
// 
//  }
///*
//   * 获取特定时间业务员在线列表
//   * @param unknown $staff_ids(必填) :业务员id数组
//   * @param unknown $time(必填)：时间戳
//   * @return int 
//   * zhaolina
//   * */
//  
//  public function getOnline($staff_ids,$time){
//  	
//  	$staff_str=implode(',', $staff_ids);
//  	$start_time=$time-60*10;//10分钟之内
//	    $sql ="select ap.saleman_id as staff_id,bs.staff_name from  ".C(DB_PREFIX)."action_position as ap left join ".C(DB_PREFIX)."base_staff as bs on ap.saleman_id=bs.staff_id   where ap.saleman_id in (".$staff_str.") and  ap.now_time between ".$start_time." and ".$time." group by ap.saleman_id";
//	    $list=$this->query($sql);
//	    return $list;
//	}
//	
//	
//	/*
//	 * 获取某一业务员在当月的时间段的拜访量个数
//	 * @param $org_parent_id
//	 * @param $staffId
//	 * @param $start_time 
//	 * return array
//	 * hjj
//	 * */
//	 public function getStaffMonthNum($org_parent_id,$staffId,$start_time,$end_time){
//	 	
//	 	//在规定的时间段的拜访量
//	 	$list=$this->getStaffVisitList($org_parent_id,$staffId,$start_time,$end_time);
//	 	
//      foreach ($list as $k=>$v){
//	    	
//	    	if(!isset ($list[$v['saleman_id']][$v['shop_id'].'/'.$v['today']]))
//	    		
//	    		$list[$v['saleman_id']][$v['shop_id'].'/'.$v['today']] = $v;
//	    }
//	    
//	   
//	    $new_list = array();
//	    
//	    foreach($list as $kk=>$vv){                                                                      
//	    	
//	    	$new_list[$kk] = count($vv);
//	    }
//	   
//	  
//	   return $new_list;
//	 }
//	
//  /*
//	* 获取某一业务员在规定的时间段的拜访量
//	* @param unknown $staffId(必填) : 业务员id
//	* @param unknown $date (必填)   : 年月，格式“2016-05”
//	* @return int
//	* zhaolina
//	* */
//		
//	public function getStaffVisitNum($org_parent_id,$staffId,$start_time,$end_time){
//		
//		$list=$this->getStaffVisitList($org_parent_id,$staffId,$start_time,$end_time);	
//		
//		$res = array();
//		foreach($list as $item) {
//			
//			if(! isset($res[$item['shop_id']])) $res[$item['shop_id']] = $item;
//		}			
//		$count=count($res); 
//		return $count;	 
//	}
//	
//	
//	
//	/*
//	 * 获取某一业务员在规定的时间段的拜访量
//	* @param unknown $staffId(必填) : 业务员id
//	* @param unknown $day (必填)   : 当前日期,格式"2016-05-01"
//	* @return 
//	* zhaolina
//	* */
//	public function getStaffVisitList($org_parent_id,$staffId=0,$start_time,$end_time){
//		
//		if($staffId){
//			$where['ap.saleman_id']=$staffId;
//			$map2=" and sd.saleman_id=".$staffId;
//			$map3=" and so.staff_id=".$staffId;
//			$map4=" and cr.staff_id=".$staffId;
//			$map5=" and co.staff_id=".$staffId;
//			$map6=" and sl.saleman_id=".$staffId;
//			$map7=" and syo.userId=".$staffId;
//			$map8=" and ins.staff_id=".$staffId;
//		}
//
//		//定位表
//		$where['ap.org_parent_id']=$org_parent_id;		
//		$where['ap.now_time']=array('between',array($start_time,$end_time));
//		$list1=$this
//		->alias('ap')
//		->join("inner join ".C(DB_PREFIX)."customer_info as ci on ap.shop_id=ci.cust_id")
//		->where($where)->field("ap.saleman_id,ap.today,ap.shop_id,ci.cust_name,from_unixtime(ap.now_time,'%Y-%m-%d %H:%m:%s') as time ")->group('ap.today,ap.shop_id')->select();
//		
//		
//		//是否给店铺拍照
//		$sql="select  from_unixtime(sd.add_time,'%Y-%m-%d') as today,sd.saleman_id,sd.shop_id,ci.cust_name,from_unixtime(sd.add_time,'%Y-%m-%d %H:%m:%s') as time  from ".C(DB_PREFIX)."shop_display  as sd
//				inner join ".C(DB_PREFIX)."customer_info as ci on sd.shop_id=ci.cust_id
//				where sd.org_parent_id=".$org_parent_id.$map2."  and sd.add_time between ".$start_time." and ".$end_time." group by from_unixtime(sd.add_time,'%Y-%m-%d'),sd.shop_id";
//		
//		$list2=$this->query($sql);
//		$list2=$this->mergeList($list1, $list2);
//		
//		//是否下订单
//		$sql="select  from_unixtime(so.create_time,'%Y-%m-%d') as today, so.staff_id as saleman_id,so.cust_id as shop_id, ci.cust_name,from_unixtime(so.create_time,'%Y-%m-%d %H:%m:%s') as time from ".C(DB_PREFIX)."st_orders as so
//				inner join ".C(DB_PREFIX)."customer_info as ci on so.cust_id=ci.cust_id
//				where so.org_parent_id=".$org_parent_id.$map3." and so.create_time between ".$start_time." and ".$end_time." group by from_unixtime(so.create_time,'%Y-%m-%d'),so.cust_id";
//		$list3=$this->query($sql);	
//		$list3=$this->mergeList($list2, $list3);
//		
//		//是否有退货
//		$sql="select  from_unixtime(cr.create_time,'%Y-%m-%d') as today, cr.staff_id as saleman_id,cr.cust_id as shop_id,ci.cust_name,from_unixtime(cr.create_time,'%Y-%m-%d %H:%m:%s') as time  from ".C(DB_PREFIX)."carsales_return as cr
//				inner join ".C(DB_PREFIX)."customer_info as ci on cr.cust_id=ci.cust_id
//				where cr.org_parent_id=".$org_parent_id.$map4." and cr.create_time between ".$start_time." and ".$end_time." group by from_unixtime(cr.create_time,'%Y-%m-%d'),cr.cust_id";
//		
//		$list4=$this->query($sql);		
//		$list4=$this->mergeList($list3, $list4);
//		
//		//终端调换货
//		$sql="select  from_unixtime(co.create_time,'%Y-%m-%d') as today, co.staff_id as saleman_id,co.cust_id as shop_id,ci.cust_name,from_unixtime(co.create_time,'%Y-%m-%d %H:%m:%s') as time  from ".C(DB_PREFIX)."change_orders as co
//				inner join ".C(DB_PREFIX)."customer_info as ci on co.cust_id=ci.cust_id
//				where co.org_parent_id=".$org_parent_id.$map5." and co.create_time between ".$start_time." and ".$end_time." group by from_unixtime(co.create_time,'%Y-%m-%d'),co.cust_id";
//		
//		$list5=$this->query($sql);		
//		$list5=$this->mergeList($list4, $list5);
//		
//		//店铺日志
//		$sql="select  from_unixtime(sl.log_time,'%Y-%m-%d') as today, sl.saleman_id,sl.shop_id,ci.cust_name,from_unixtime(sl.log_time,'%Y-%m-%d %H:%m:%s') as time  from ".C(DB_PREFIX)."shop_log as sl
//				inner join ".C(DB_PREFIX)."customer_info as ci on sl.shop_id=ci.cust_id
//				where sl.org_parent_id=".$org_parent_id.$map6." and sl.log_time between ".$start_time." and ".$end_time." group by from_unixtime(sl.log_time,'%Y-%m-%d'),sl.shop_id";		
//		$list6=$this->query($sql);		
//		$list6=$this->mergeList($list5, $list6);
//		
//		//预存款
//		$sql="select  from_unixtime(syo.time,'%Y-%m-%d') as today,syo.userId as saleman_id,syo.shopId as shop_id,ci.cust_name,from_unixtime(syo.time,'%Y-%m-%d %H:%m:%s') as time  from ".C(DB_PREFIX)."st_yufu_orders as syo
//				inner join ".C(DB_PREFIX)."customer_info as ci on syo.shopId=ci.cust_id
//				where syo.pid=".$org_parent_id.$map7." and syo.time between ".$start_time." and ".$end_time." group by from_unixtime(syo.time,'%Y-%m-%d'),syo.shopId";
//		$list7=$this->query($sql);
//		$list7=$this->mergeList($list6, $list7);
//		
//		//库存盘点
//		$sql="select  from_unixtime(ins.create_time,'%Y-%m-%d') as today,ins.staff_id as saleman_id,ins.cust_id as shop_id,ci.cust_name,from_unixtime(ins.create_time,'%Y-%m-%d %H:%m:%s') as time  from ".C(DB_PREFIX)."inventory_submit as ins
//				inner join ".C(DB_PREFIX)."customer_info as ci on ins.cust_id=ci.cust_id
//				where ins.org_parent_id=".$org_parent_id.$map8." and ins.create_time between ".$start_time." and ".$end_time." group by from_unixtime(ins.create_time,'%Y-%m-%d'),ins.cust_id";
//		$list8=$this->query($sql);
//		$list8=$this->mergeList($list7, $list8);
//	
//		return $list8;
//	}
//	
//	/*
//	 * 合并两个数组
//	 * @param array $list1(必填)：数组一
//	 * @param array $list2(必填)：数组二
//	 * zhaolina
//	 * */
//	
//	private function mergeList($list1,$list2){
//		if($list1){					
//			$list=array_merge($list1,$list2);
//		}else{
//			$list=$list2;
//		}
//		return $list;		
//	}
	
    

} 