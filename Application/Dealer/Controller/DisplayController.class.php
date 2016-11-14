<?php

/*******************************************************************
 ** 文件名称: DisplayController.class.php
 ** 功能描述: 经销商PC端店铺陈列控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class DisplayController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	//陈列列表
    public function indexAction(){
        header('Content-Type:text/html;charset=utf-8');
        
        if(isset($_GET["start"])&&isset($_GET["end"])){
            $start=strtotime($_GET['start']);
            $end=strtotime($_GET['end'].'+1 day');
            $where["add_time"]=array('between',"$start,$end");
        }
        if(isset($_GET["shop"])){
        	$where["cust_name"]=array("like","%{$_GET["shop"]}%");
        }
		    
        $staff_id=I("get.staff_id");    
		if($staff_id){
			$where["saleman_id"]=$staff_id;
		}
		$where["sd.org_parent_id"]=session("org_parent_id");
		

        $p=isset($_GET['p'])?$_GET['p']:1;
        $pnum=I("get.pnum",10);
		
      	$total=M("customer_display")->table("zdb_customer_display sd")
	        ->join("inner join zdb_org_staff bs on bs.staff_id=sd.saleman_id")
	        ->join("inner join zdb_customer_info ci on sd.shop_id=ci.cust_id")
	        ->where($where)->count();
        $list=M("customer_display")->table("zdb_customer_display sd")
	        ->field("sd_id,saleman_id,shop_id,display_img,display_thumb,add_time,cdt.sdt_name,sd.remark,staff_name,cust_name")
			->join("inner join zdb_customer_display_type cdt on cdt.sdt_id=sd.sdt_id")
	        ->join("inner join zdb_org_staff bs on bs.staff_id=sd.saleman_id")
	        ->join("inner join zdb_customer_info ci on sd.shop_id=ci.cust_id")
	        ->where($where)
	        ->order("add_time desc")
	        ->page($p,$pnum)
	        ->select();
			
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        //人员列表
      	unset($where);
      	$where["org_parent_id"]=session("org_parent_id");
		$where["role_id"]=3;
      	$staffList=M("org_staff")->field("staff_id,staff_name")->where($where)->select();
        $this->assign('start', $_GET["start"]);
        $this->assign('end',$_GET["end"]);
        $this->assign('shop', $_GET["shop"]);
        $this->assign('display_name', $_GET["display_name"]);
        $this->assign('stafflist',$staffList);
        $this->assign('pagelist',$page);
        $this->assign('list',$list);//列表内容
        $this->assign("pnum",$pnum);
        $this->display();
    }
    
	public function detailAction(){
	    $shopId = I('shopId');
        $staffId = I('staffId');

        if(!empty($shopId)){
            $where['shop_id'] = $shopId;
        }
        if(!empty($staffId)){
            $where['os.staff_id'] = $staffId;
        }

        if(empty($where)){
            echo '参数错误';
            return;
        }

        $list = M('customer_display')->alias('cd')->field('display_img,cust_id,cust_name,add_time,staff_name')
            ->join('__CUSTOMER_INFO__ ci on cd.shop_id=ci.cust_id')
            ->join('__ORG_STAFF__ os on cd.saleman_id=os.staff_id')
            ->order('sd_id desc')
            ->where($where)
            ->select();

        $this->assign('list',$list);
        $this->display();
	}

	//删除陈列
    public function delDisAction(){
        $res=D("CustomerDisplay")->delete($_POST["sd_id"]);
        if($res>0)
            echo "1";
    }
    //陈列类型
    public function typeListAction(){
        $list=M("customer_display_type")->field("sdt_id,sdt_name")->where("org_parent_id=".session("org_parent_id"))->select();
        $this->assign('list',$list);//列表内容
        $this->display();
    }
    //添加陈列类型
    public function addTypeAction(){
        if(IS_GET){
            $this->display('type_add');
        }
        if(IS_POST){
            $_POST["org_parent_id"]=session("org_parent_id");
            $res=M("customer_display_type")->data($_POST)->add();
            if($res){
                echo json_encode(array("info"=>"添加成功！","res"=>1,'id'=>$res));
            }else{
                echo json_encode(array("info"=>"添加失败！","res"=>0));
            }
        }
    }
    //删除陈列类型
    public function delTypeAction(){
    	$count=M("customer_display")->where("sdt_id=".$_POST["sdt_id"])->count();
		if($count){
			$arr["res"]=0;
            $arr["info"]="该陈列类型下有陈列";
			
		}else{
			if(M("customer_display_type")->delete($_POST["sdt_id"])){
            	$arr["res"]=1;
            	$arr["info"]="删除成功";
       		}else{
            	$arr["res"]=0;
            	$arr["info"]="删除失败";
        	}
		}
        
        echo $this->ajaxReturn($arr,"json");
    }

	/** 其他Action **/
    

}

/*************************** end ************************************/