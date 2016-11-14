<?php

/*******************************************************************
 ** 文件名称: BossDisplayController.class.php
 ** 功能描述: 经销商老板端陈列接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossDisplayController extends Controller {

	// 陈列情况
    public function pclInfoAction()
    {
		// 经销商ID
		$where["t1.org_parent_id"] = I("companyId");
		
		// 页码
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		
		// 分页查询陈列照片
		$list = M("customer_display")->alias('t1')
		->field("t2.staff_name role,t1.remark content,t1.add_time time,t1.display_img img, t1.shop_id, c.cust_name")
		->join("__ORG_STAFF__ t2 on t2.staff_id = t1.saleman_id")
		->join("__CUSTOMER_INFO__ as c on t1.shop_id = c.cust_id")
		->where($where)->page($page, 20)->order("t1.sd_id desc")->select();
		
		// 格式化时间
		for($i=0; $i<count($list); $i++) { $list[$i]["time"] = date("Y-m-d H:i", $list[$i]["time"]); }

		// 返回
		if(empty($list))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $list));
		}
    }

}

/*************************** end ************************************/