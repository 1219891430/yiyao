<?php

/*******************************************************************
 ** 文件名称: CarSalesReturnController.class.php
 ** 功能描述: 经销商PC端终端退货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class ActionController extends Controller {
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
        $org_parent_id=$_SESSION["org_parent_id"];
		$where["role_id"]=3;
		$where["org_parent_id"]=$org_parent_id;
        $staff_list=M("org_staff")->where($where)->select();
        $saleman_id=empty($_GET["staff_id"])?0:$_GET["staff_id"];
		if($_GET['today']){
			$today=$_GET['today'];
		}else{
			$today=date("Y-m-d");
		}
		
        $this->assign("today",$today);
        $this->assign("saleman_id",$saleman_id);
        $this->assign("stafflist",$staff_list);
        $this->display();
    }

    
	/** 其他Action **/
	
	public function sd_selRouteAction(){
    	
//  	$date=I("post.today","2016-08-27");
//  	$staffId=I("post.staff_id",3);
		$date=I("post.today","");
    	$staffId=I("post.staff_id");
    	$postion=new \Common\Model\ActionPositionModel();
    	$list=$postion->getActionList($staffId, $date);
		
    	foreach($list as $k=>$v){
    		$list[$k]["repara_data"]=queryCustWeihuType($v["type"]);
			$list[$k]["entertime"]=date("Y-m-d H:i:s",$v["entertime"]);
    	}
    	if($list){
    		$resArr["res"]=1;
    		$resArr["info"]=$list;
    		echo json_encode($resArr);
    	}else{
    		$resArr["res"]=0;
    		echo json_encode($resArr);
    	}
    }
    
    public function selNowRouteAction()
    {
    	
        $resArr=array();
        $postion=new \Common\Model\ActionPositionModel();
        $where["today"]=empty($_POST["today"])?date("Ymd"):date("Ymd",strtotime($_POST["today"]));
        $where["saleman_id"]=empty($_POST["staff_id"])?0:$_POST["staff_id"];
        $where["org_parent_id"]=$_SESSION["org_parent_id"];
        $res_now=$postion->selectPostion($where);
        if(!empty($res_now))
        {
            $resArr["res"]=1;
            $resArr["info_now"]=!empty($res_now)?$res_now:0;
        }
        else
            $resArr["res"]=0;
        echo $this->ajaxReturn($resArr,"json");
    }
	
	
	
	public function updateStaffRouteAction()
    {
        $arr=array();
        $org_parent_id=$_SESSION["org_parent_id"];
        $staff=$_POST["staff_id"];
        $start_time=$_POST["start_time"];
        $end_time=$_POST["end_time"];
        $interval=$_POST["interval"];
        for($i=0;$i<count($staff);$i++)
        {
            $arr[]=array("staff"=>$staff[$i],"start"=>$start_time[$i],"end"=>$end_time[$i],"interval"=>$interval[$i]);
        }
        $route=new \Common\Model\ActionConfigModel();
        $res=$route->updateStaffRoute($arr,$org_parent_id);
        $resArr= $res?array("res"=>1,"info"=>"修改成功"):array("res"=>0,"info"=>"修改失败，刷新页面重新修改");

        
        
        echo $this->ajaxReturn($resArr,"json");
    }
	
	
	public function selStaffRouteAction()
    {
        $org_parent_id=$_SESSION["org_parent_id"];
        $route=new \Common\Model\ActionConfigModel();
        $res=$route->selectActionConfig($org_parent_id);
        foreach($res as $k=>&$v){
        	if(!$v["begin_time"]){
        		$v["begin_time"]=0;
        	}
        	if(!$v["end_time"]){
        		$v["end_time"]=0;
        	}
        	if(!$v["interval"]){
        		$v["interval"]=0;
        	}
        	
        }
        
        $this->assign('arStart', $this->makeTimeSection(7,9));
        $this->assign('arEnd', $this->makeTimeSection(17,23));
        $this->assign("route",$res);
		
        $this->display();
    }
	
	/**
     * 根据步长值生产时间段
     * @param $start
     * @param $end
     * @param $step
     */
    protected function makeTimeSection($start, $end, $step = 0.3)
    {
        $arSection = array();
        $value = $start;

        do {
            $arValue = explode('.', $value);
            if (isset($arValue[1])) {
                $quotient = $arValue[1] / 6;

                if ($quotient >= 1) {
                    $arValue[1] = $arValue[1] % 6;
                    $arValue[0] += 1;
                }
                $value = $arValue[0] + $arValue['1'] / 10;
                $arValue[1] = $arValue[1] * 10;
            } else {
                $arValue[1] = '0';
            }
            $arSection[] = $arValue[0] . ':' . str_pad($arValue[1], 2, 0);
            $value = $value + $step;

        } while ($value <= $end);

        return $arSection;
    }
	
}