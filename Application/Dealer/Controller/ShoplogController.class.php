<?php

/*******************************************************************
 ** 文件名称: ShoplogController.class.php
 ** 功能描述: 经销商PC端店铺日志控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class ShoplogController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	//店铺日志显示
    public function indexAction()
    {
        
		
		$whereOS["org_parent_id"]=session("org_parent_id");
		$whereOS["role_id"]=3;
		$stafflist=M("org_staff")->field("staff_id,staff_name")->where($whereOS)->select();
		$this->assign("stafflist",$stafflist);
        $staff_id=I("get.staff_id");
		$shop=I("get.shop");
		$start=I("get.start");
		$end=I("get.end");
		if($end&&$start){
			$start=strtotime($start);
			$end=strtotime($end);
			$where["log_time"]=array(array("gt",$start),array("lt",$end));
		}
        if($staff_id){
        	$where["saleman_id"]=$staff_id;
        }
		if($shop){
        	$where["zdb_customer_info.cust_name"]=array("like","%$shop%");
        }
		
              
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
		$pnum=I("get.pnum",10);
		
		
		
		$where["zdb_customer_log.org_parent_id"]=session("org_parent_id");
		
        
		$total = M("customer_log")
            ->field("log_id,record,file_path,saleman_id,shop_id,log_content,date_format(from_unixtime(log_time),'%Y-%m-%d %H:%i:%s') as log_time,staff_name,cust_name")
            ->join("inner join zdb_org_staff  on zdb_org_staff.staff_id=zdb_customer_log.saleman_id")
            ->join("inner join zdb_customer_info on zdb_customer_log.shop_id=zdb_customer_info.cust_id ")
            ->where($where)->count();

        $list = M("customer_log")
            ->field("log_id,record,file_path,saleman_id,shop_id,log_content,date_format(from_unixtime(log_time),'%Y-%m-%d %H:%i:%s') as log_time,staff_name,cust_name")
            ->join("inner join zdb_org_staff  on zdb_org_staff.staff_id=zdb_customer_log.saleman_id")
            ->join("inner join zdb_customer_info on zdb_customer_log.shop_id=zdb_customer_info.cust_id ")
            ->where($where)
            
            ->order("log_time desc")
            ->page($p, $pnum)
            ->select();
        
		
        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        
        $this->assign('start', $_GET["start"]);
        $this->assign('end', $_GET["end"]);
        $this->assign('shop', $_GET["shop"]);
        $this->assign('pagelist', $page); //分页显示
        $this->assign('list', $list); //列表内容
        
        $this->assign('staff_id', $_GET["staff_id"]);
        $this->assign("pnum",$pnum);
        $this->display();
    }

    //删除店铺日志
    public function delLogAction()
    {
        $dep = M("customer_log");
        $result = $dep->delete($_POST['log_id']);
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    }


	/** 其他Action **/


}

/*************************** end ************************************/