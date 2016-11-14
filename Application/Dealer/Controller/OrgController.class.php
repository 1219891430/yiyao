<?php

/*******************************************************************
 ** 文件名称: OrgController.class.php
 ** 功能描述: 经销商PC端机构控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class OrgController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

	    // 经销商信息
        $where["org_id"] = session("org_parent_id");

        $org = M("org_info")->where($where)->find();

        $this->assign("org", $org);
		
		$this->display();
    }


	/** 其他Action **/


}

/*************************** end ************************************/