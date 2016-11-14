<?php

/*******************************************************************
 ** 文件名称: IndexController.class.php
 ** 功能描述: 移动接口默认控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class IndexController extends Controller {

	// 控制器默认登录接口
	// 创建人员: richie
	// 创建日期: 2016-08-03
	public function loginAction()
	{
		// 解决跨域请求, 允许所有域名的脚本访问该资源
		header('Access-Control-Allow-Origin:*');
		
		// 登录账号和密码, 以及推送ID
		$login_user = I("username");
		$login_pwd = I("password");
		// $pushid = I("pushId");
		
		// 查询经销商账号
		$field = 'staff_id as id, login_user as username, login_pwd as password, staff_name as name, role_id as role, mobile as tel, is_close, org_parent_id';
		$result = M("org_staff")->field($field)->where("login_user='{$login_user}'")->find();
		
		// 查询平台账号
		if(empty($result))
		{
			$field = 'admin_id as id, login_account as username, login_pwd as password, true_name as name, mobile as tel, role_id as role, is_close, 0 as org_parent_id';
			$result = M('admin_user')->field($field)->where("login_account='{$login_user}'")->find();
		}
		
		// 两者都没有该账号
		if(empty($result)){ echo json_encode(array('error' => '2', 'msg' => '帐号不存在')); exit; }
		
		// 禁止登陆
		if($result['is_close'] == 1) { echo json_encode(array('error' => '3', 'msg' => '您的账号已停用')); exit; }
		
		// 用户密码错误
		if($result['password'] != $login_pwd){ echo json_encode(array('error' => '1', 'msg' => '密码错误')); exit; }
		
		// 获取经销商信息
		$orgInfo = array();
		if(intval($result['org_parent_id']) > 0)
		{
			// 经销商停止使用
			$orgInfo = M('org_info')->field('org_id, org_name, telephone, is_close')->where("org_id = " . intval($result['org_parent_id']))->find();
			if(intval($orgInfo['is_close']) > 0){ echo json_encode(array('error' => '3', 'msg' => '您的账号已停用')); exit; }
		}

		// 没有权限, 角色不对
		if(!in_array(intval($result["role"]), array(1,3,4,5,6)))
		{
			echo json_encode(array('error' => '4', 'msg' => '没有权限')); exit;
		}
		
		// 返回数据登陆者信息
		$data["id"] = $result["id"];
		$data["role"] = $result["role"]; // 1 => 老板, 3 => 业务员, 4 => 采单人员, 5 => 配送人员,6 => 采购人员
		$data["username"] = $result["username"];
		$data["password"] = $result["password"];
		$data["name"] = $result["name"];
		$data["tel"] = $result['tel'];
		$data["companyId"] = $result['org_parent_id'];
		$data['companyName'] = !empty($orgInfo['org_name']) ? $orgInfo['org_name'] : '农乐汇抓单宝';
		$data["companyTel"] = !empty($orgInfo['telephone']) ? $orgInfo['telephone'] : '4000311995';

		// 返回数据
		echo json_encode(array('error' => '-1', 'msg' => '登陆成功', 'data' => $data)); exit;
    }

}

/*************************** end ************************************/