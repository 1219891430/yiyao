<?php

/*******************************************************************
 ** 文件名称: IndexController.class.php
 ** 功能描述: 经销商PC端默认控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class IndexController extends Controller {

	// 控制器默认页, 登录页面
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function indexAction()
	{
		$this->display();
    }
	
	// 登录验证码
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function verifyAction()
	{
    	ob_clean();
        $verify=new \Think\Verify();
        $verify->fontSize = 12;
        $verify->length   = 4;
        $verify->useNoise = false;
        $verify->useCurve=false;
        $verify->imageW = 80;
        $verify->imageH = 30;
        $verify->fontttf = '4.ttf';
        echo $verify->entry();
		exit;
	}

	// 登录
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function loginAction()
	{
		// js返回中文编码
		header('Content-Type:text/html;charset=utf-8');
		
		// 登录账号,密码和验证码
		$username = I('l_name');
		$password = I('l_pwd');
		$verify_code = I('verify');
		
		// 判断验证码
		$verify = new \Think\Verify();
		$flag = $verify->check($verify_code);
		if(!$flag){ echo "<script>alert('验证码错误！');window.location='./';</script>"; exit; }
		
		// 查询人员信息
		$condition['login_user'] = $username;
		$result = M('org_staff')->where($condition)->find();
		
		// 没有该用户
		if(empty($result)) { echo "<script>alert('账号不存在,请输入正确账号！');window.location='./';</script>"; exit; }
		
		// 查询经销商
		$org_parent_id = intval($result['org_parent_id']);
		$org_info = M('org_info')->where('org_id = ' . $org_parent_id)->find();

        //查询仓库
        $depot_org = M('depot_org')->where('org_parent_id='.$org_parent_id)->find();

		// 禁止登陆
		if($result['is_close'] == 1 || $org_info['is_close'] == 1) { echo "<script>alert('账号已禁止登陆！');window.location='./';</script>"; exit; }
		
		// 查看密码是否正确
		if($result['login_pwd']!= md5($password)) { echo "<script>alert('密码错误！');window.location='./';</script>"; exit; }
		
		// 查看角色权限, 老板和内勤能够登陆
		if(!in_array($result['role_id'], array(1,2))){ echo "<script>alert('没有权限！');window.location='./';</script>"; exit; }

		// 查询未处理的车存申请数量
        $apply_num = M("carsale_apply")->where("org_parent_id=$org_parent_id AND apply_status=1")->count();

        // 查询未处理的退库数量
        $return_stock_num = M("carsale_return_stock")->where("org_parent_id=$org_parent_id AND return_status=1")->count();

        // 查询某段时间内车销订单量
        $start = strtotime(date("Y-m-d 00:00:00"));
        $end = strtotime(date("Y-m-d 23:59:59"));

        $order_num = getCarsaleOrderNumByCreatetime($org_parent_id, $start, $end);

        // 预单数量
        $car_order_num = getCarOrderNumByCreatetime($org_parent_id, $start, $end);

		// session保存登陆者信息
		session("staff_id",$result['staff_id']);			// 员工ID
		session("staff_name",$result['staff_name']);		// 员工名称
		session('org_parent_id',$result["org_parent_id"]);	// 经销商ID
		session("org_parent_name",$org_info['org_name']);	// 经销商名称
        session('depot_id',$depot_org['repertory_id']);     //仓库ID
		session("dep_id",$result['dep_id']);				// 部门ID
		session('role_id',$result['role_id']);				// 角色ID
		session("is_admin",$result['is_admin']);			// 是否老板

        // 未处理的申请车存统计
        session("apply_num", intval($apply_num));

        // 未处理的车销退库统计
        session("return_stock_num", intval($return_stock_num));

        // 一段时间内车存申请
        session("order_num", intval($order_num));

        // 一段时间内预单
        session("car_order_num", intval($car_order_num));
		
		// 返回页面
		echo "<script>window.location='../Index/home.html';</script>";
	}
	
	// 经销商后台管理主页
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function homeAction()
	{
		// 载入导航菜单
		if(empty($_SESSION['menu_dealer'])){ $_SESSION['menu_dealer'] = require_once('./Application/Dealer/Common/auth.php'); }
		$this->display();
	}

	// 欢迎页面
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function welcomeAction()
	{
		$this->display();
	}
	
	// 退出界面
	public function logoutAction()
	{
		// 销毁session信息
		session("staff_id", NULL);
		session("staff_name", NULL);
		session('org_parent_id', NULL);
		session("org_parent_name", NULL);
		session("dep_id", NULL);
		session('role_id', NULL);
		session("is_admin", NULL);
		session("menu_dealer", NULL);
		echo "<script>window.location='../Index/index';</script>";
	}

	/** 其他Action **/


}

/*************************** end ************************************/