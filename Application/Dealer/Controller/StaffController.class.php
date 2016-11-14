<?php

/*******************************************************************
 ** 文件名称: StaffController.class.php
 ** 功能描述: 经销商PC端员工管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class StaffController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

		$where['os.org_parent_id'] = $_SESSION['org_parent_id'];

        $total = M("org_staff")->alias('os')->where($where)->count();

        $staff = M("org_staff")->alias('os')
            ->field('os.*, dep.dep_name')
            ->join('left join __ORG_DEP__ as dep on os.dep_id = dep.dep_id')
			->where($where)
            ->page($p,$pnum)
            ->select();

        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Dealer/Staff/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);

        $this->assign('pagelist',$page);//分页显示
        $this->assign("pnum",$pnum);
        $this->assign("staff", $staff);

		$this->display();
    }

    // 新增人员
    public function addAction() {

        $where['org_parent_id'] = $_SESSION["org_parent_id"];

        $dep = M('org_dep')->where($where)->select();

        if (IS_GET) {
            $this->assign("dep", $dep);
            $this->display();
        }

        if (IS_POST) {
            $data = $_POST;
            $data["org_parent_id"] = session("org_parent_id");

            $data["login_pwd"] = md5($data["login_pwd"]);

            $succ = M('org_staff')->add($data);

            if ($succ) {
                $this->success('添加成功', U('Dealer/staff/index'));
            } else {
                $this->success('添加失败', U('Dealer/staff/index'));
            }

        }
    }

    // 修改人员信息
    public function editAction() {
        $id = I("id");

        $where["staff_id"] = $id;

        $staff = M("org_staff")->where($where)->find();

        if(IS_GET) {

            $dep = M('org_dep')->where(array("org_parent_id"=>$_SESSION["org_parent_id"]))->select();

            $this->assign("dep", $dep);

            $this->assign("staff", $staff);

            $this->display();
        }

        if(IS_POST) {
            $data = $_POST;

            // 验证手机是否重复
            if ($data["mobile"] != $staff["mobile"] && !$this->checkmobile($data["mobile"])) {
                aJsonReturn(0, "手机号已经存在");
                return;
            }

            // 验证员工名称是否重复
            if ($data["staff_name"] != $staff["staff_name"] && !$this->checkname($data["staff_name"])) {
                aJsonReturn(0, "员工名称重复");
                return;
            }

            $data["org_parent_id"] = session("org_parent_id");
            $succ = M('org_staff')->where($where)->save($data);

            if ($succ) {
                $this->success('修改成功', U('Dealer/staff/index'));
            } else {
                $this->success('修改失败', U('Dealer/staff/index'));
            }
        }
    }

    // 修改密码
    public function resetPwdAction() {
        $id = I("id");

        $where["staff_id"] = $id;

        $staff = M("org_staff")->where($where)->find();

        if (IS_GET) {
            $this->assign('staff', $staff);

            $this->display();

        }

        if (IS_POST) {

            $password = md5(I("post.login_pwd"));

            $succ = M("org_staff")->where($where)->save(array("login_pwd"=>$password));

            if ($succ) {
                $this->success('修改成功', U('Dealer/staff/index'));
            } else {
                $this->error('修改失败', U('Dealer/staff/index'));
            }

        }
    }

    // 删除
    public function delAction() {

        $id = I("id");

        if (!empty($id)) {
            $where["staff_id"] = $id;

            $succ = M("org_staff")->where($where)->delete();

            if ($succ) {
                $this->success("删除成功", U("Dealer/Staff/index"));
            } else {
                $this->error("操作失败", U("Dealer/Staff/index"));
            }

        } else {
            $this->error("无效的id", U("Dealer/Staff/index"));
        }

    }

    // 禁用
    // 1 -- 禁用成功
    // 2 -- 启用成功
    // 0 -- 操作失败
    public function closeAction() {
        $id = I("id");
        $where["staff_id"] = $id;

        $staff = M("org_staff")->where($where)->find();

        if ($staff["is_close"] == 1) {
            $data["is_close"] = 0;
        } else {
            $data["is_close"] = 1;
        }

        $succ = M("org_staff")->where($where)->save($data);


        if ($succ) {
            if ($staff["is_close"] != 1) echo 1;
            else
                echo 2;
        } else {
            echo 0;
        }

    }

    // 检查手机号是否存在
    public function checkmobileAction() {
        $mobile = I("user");

        echo $this->checkmobile($mobile);
    }

    // 检查用户名是否存在
    public function checknameAction() {
        $name = I('name');

        echo $this->checkname($name);
    }

    // 返回 false 登录名不可用
    // 返回 true 可用
    private function checkmobile($mobile) {
        $where["mobile"] = $mobile;

        $check = M("org_staff")->where($where)->count();

        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }

    // 返回 false 登录名不可用
    // 返回 true 可用
    private function checkname($name) {
        $where["staff_name"] = $name;
        $where["org_parent_id"] = $_SESSION["org_parent_id"];

        $check = M("org_staff")->where($where)->count();

        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }


	/** 其他Action **/


}

/*************************** end ************************************/