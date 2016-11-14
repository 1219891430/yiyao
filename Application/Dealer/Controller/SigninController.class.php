<?php

/*******************************************************************
 ** 文件名称: SigninController.class.php
 ** 功能描述: 经销商PC端人员签到控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class SigninController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		
        //时间条件
        if(isset($_GET["start"])&&isset($_GET["end"])){
            $start=strtotime($_GET['start']);
            $end=strtotime($_GET['end'].'+1 day');
            $where["now_time"]=array('between',"$start,$end");
        }
		
		$staffId=I("get.staffId");
		if($staffId){
			$where["staff_id"]=$staffId;
		}
		
        $this->assign('start', $_GET["start"]); //列表内容
        $this->assign('end',$_GET["end"]); //列表内容
        $where["org_parent_id"]=session("org_parent_id");
        //人员列表
        $staffList = M("OrgStaff")->field("staff_id,staff_name")->where("org_parent_id=" . session("org_parent_id")." and role_id =3")->select();
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum=I("get.pnum",10);
        $total= M("org_staff_signin")->field("signin_id,staff_id,address,img,now_time")->where($where)->count(); //总数
        $list = M("org_staff_signin")->field("signin_id,staff_id,address,img,now_time")->where($where)->order("now_time desc")->page($p, $pnum)->select();
		
        if($list)
        {
            for ($i = 0; $i < count($list); $i++) {
                $staff = M("OrgStaff")->field('staff_name')->where("staff_id={$list[$i]['staff_id']}")->find();
                $list[$i]["staff"] = $staff["staff_name"];
                $list[$i]["now_time"] = date('Y-m-d H:i:s', $list[$i]["now_time"]);
            }
        }
        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('staff', $staffList);
        $this->assign('pagelist', $page);
        $this->assign('pnum', $pnum);
        $this->assign('staff_id', $_GET["staffId"]);
        $this->assign('list', $list); //列表内容
        $this->display();
		
    }


	/** 其他Action **/


}

/*************************** end ************************************/