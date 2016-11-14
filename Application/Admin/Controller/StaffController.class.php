<?php

/*******************************************************************
 ** 文件名称: StaffController.class.php
 ** 功能描述: 系统后台人员管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class StaffController extends BaseController {

    var $_mod_admin_user;

    public function __construct(){
        parent::__construct();
        $this->_mod_admin_user = M('admin_user');
    }

	// 控制器默认页
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function indexAction()
	{
		// 查询条件仓库和角色
		$queryDepot = I('queryDepot', 0);
		$queryRole = I('queryRole', 0);
		$this->assign('queryDepot', $queryDepot);
		$this->assign('queryRole', $queryRole);
		
		// 搜索条件
		$where['is_admin'] = 0;
        if($this->_depot_id>0){
            $where['depot_id'] = $this->_depot_id;
        }
        else{
            if($queryDepot > 0){ $where['depot_id'] = $queryDepot; }
        }

		if($queryRole > 0){ $where['role_id'] = $queryRole; }
		
		// 页码
		$pnum = I('get.pnum', 10);
		$p = I('get.p', 1);
		
		// 查询总记录计算页码
		$total = $this->_mod_admin_user->where($where)->count();
		$page = get_page_code($total, $pnum, $p,5);
		$this->assign('pnum', $pnum);
	    
		// 分页查询人员
		$staff_list = $this->_mod_admin_user->where($where)->order('admin_id desc')->page($p, $pnum)->select();
		$this->assign('staff_list', $staff_list);
	    $this->assign("pagelist",$page);
		// 查询所有角色和所有仓库
		$this->assign('depot_list', format_array(queryDepot($this->_depot_id),'repertory_id','repertory_name'));
		$this->assign('role_list', queryAllRole());
		$this->display();
    }
	
	// 添加人员
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function addAction()
	{

		if(IS_GET)
		{
			// 查询所有角色和所有仓库
			$this->assign('depot_list', format_array(queryDepot($this->_depot_id),'repertory_id','repertory_name'));
			$this->assign('role_list', queryAllRole());
			$this->display('staff_add');
		}
		
		if(IS_POST)
		{		
			// 用户录入
			$data['login_account'] = $mobile = I('mobile');
			$password = I('login_pwd');
			$data['login_pwd'] = md5($password);
			$data['true_name'] = $truename = I('true_name');
			$data['sex'] = I('sex');
			$data['age'] = I('age');
			$data['mobile'] = I('mobile');
			$data['role_id'] = $roleID = I('role_id');
			$data['depot_id'] = I('depot_id');
            //权限判断
            if($this->_depot_id>0){
                $data['depot_id'] = $this->_depot_id;
            }
			$data['is_admin'] = 0;
			$data['is_close'] = 0;
			
			// 录入信息不完善
			if(empty($mobile) || empty($password) || empty($truename) || empty($roleID) )
			{
				echo json_encode(array("info" => "录入信息不完善！", "res" => 0)); exit;
			}
			
			// 手机号不能重复
			$flag = checkMobileUnique($mobile);
			if(!$flag){ echo json_encode(array("info" => "手机号已被使用！", "res" => 0)); exit; }

			// 增加用户
			$admin_id = $this->_mod_admin_user->add($data);
			if($admin_id > 0)
			{
				echo json_encode(array("info" => "保存成功！", "res" => 1)); exit;
			}
			else
			{
				echo json_encode(array("info" => "保存失败！", "res" => 1)); exit;
			}
		}
	}
	
	// 检查手机是否使用
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function checkMobileAction()
	{
		$mobile = I('mobile');
		$flag = checkMobileUnique($mobile);
		if($flag){ die("1"); }
		else { die("0"); }
	}

	// 修改人员
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function editAction()
	{
		if(IS_GET)
		{
			// 查询人员信息
			$admin_id = I('id', 0);
			$userInfo = $this->_mod_admin_user->where('admin_id = ' . intval($admin_id))->find();
			$this->assign('userInfo', $userInfo);
			
			// 显示页面
			$this->assign('depot_list', format_array(queryDepot($this->_depot_id),'repertory_id','repertory_name'));
			$this->assign('role_list', queryAllRole());
			$this->display('staff_edit');
		}
		
		if(IS_POST)
		{
			// 用户录入
			$admin_id = I('admin_id', 0);
			$data['login_account'] = $mobile = I('mobile');
			$password = I('login_pwd', '');
			if(!empty($password)){ $data['login_pwd'] = md5($password); }
			$data['true_name'] = $truename = I('true_name');
			$data['sex'] = I('sex');
			$data['age'] = I('age');
			$data['mobile'] = I('mobile');
			$data['role_id'] = $roleID = I('role_id');
			$data['depot_id'] = I('depot_id');

            //权限判断
            if($this->_depot_id>0){
                $data['depot_id'] = $this->_depot_id;
            }
			// 录入信息不完善
			if(empty($admin_id) || empty($mobile) || empty($truename) || empty($roleID) )
			{
				echo json_encode(array("info" => "录入信息不完善！", "res" => 0)); exit;
			}
			
			// 手机号不能重复
			$flag = checkMobileUnique($mobile, $admin_id);
			if(!$flag){ echo json_encode(array("info" => "手机号已被使用！", "res" => 0)); exit; }
		
			// 修改用户信息
			$flag = $this->_mod_admin_user->where("admin_id = " . intval($admin_id))->save($data);
			if(empty($flag))
			{
				echo json_encode(array("info" => "保存失败！", "res" => 0)); exit;
			}
			else
			{
				echo json_encode(array("info" => "保存成功！", "res" => 1)); exit;
			}
		}
	}
	
	// 删除人员
	// 创建人员: richie
	// 创建日期: 2016-08-04
	public function delAction()
	{
	    //暂停删除
        return false;

		// 超管才能删除
		if($_SESSION['is_admin'] == 0){ die("0"); }


		// 删除人员
        $admin_id = I('id', 0);
		$where['admin_id'] = $admin_id;

        //判断权限
		if($this->_depot_id>0){
            $data = $this->_mod_admin_user->where($where)->find();
            if($data['depot_id'] != $this->_depot_id){
                echo 0;
                return;
            }
        }

        $this->_mod_admin_user->where($where)->delete();
		
		// 删除采单分配商铺数据
		M('admin_shop')->where($where)->delete();

		// 返回成功
		echo 1;
	}

	// 关闭打开人员登录
	public function closeAction()
	{
		$admin_id = I('id', 0);
		$status = I('st', 0);
        $where['admin_id'] =  $admin_id;

        //权限判断---地区管理员没有权限管理其他地区的数据
        if($this->_depot_id>0){
            $data = $this->_mod_admin_user->find($admin_id);
            if($this->_depot_id != $data['depot_id']){
                echo 0;
                return;
            }
        }


        $this->_mod_admin_user->where($where)->setField('is_close', $status);
		die("1");
	}

	/** 其他Action **/

}

/*************************** end ************************************/