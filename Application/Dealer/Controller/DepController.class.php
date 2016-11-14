<?php

/*******************************************************************
 ** 文件名称: DepController.class.php
 ** 功能描述: 经销商PC端部门管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class DepController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){
		$p=I("get.p",1);
		$pnum=I("get.pnum",10);
		$where["org_parent_id"]=session("org_parent_id");
        $dep = M("org_dep")->where($where)->page($p,$pnum)->select();
        $total=M("org_dep")->where($where)->count();
		
		
		$page=get_page_code($total, $pnum, $p,5);
		$this->assign('pagelist',$page);
		$this->assign("pnum",$pnum);
        $this->assign("dep", $dep);

		$this->display();
    }

    // 创建部门
    public function addAction() {
        if (IS_GET) {
            $this->display();
        }

        if (IS_POST) {

            $data["org_parent_id"] = session("org_parent_id");
            $data["dep_name"] = I("post.dep_name");

            if (!$this->check($data["dep_name"])) {
                aJsonReturn(0, "部门名重复");
                return;
            }

            $data["dep_header"] = I("post.dep_header");
            $data["remark"] = I("post.remark");

            $dep = M("org_dep")->add($data);
            if ($dep) {
                $this->success('添加成功', U('Dealer/Dep/index'));
            } else {
                $this->error('添加失败', U('Dealer/Dep/index'));
            }
        }

    }

    // 修改部门
    public function editAction() {
        $id = I("id");

        $where["dep_id"] = $id;

        $dep = M("org_dep")->where($where)->find();

        if (IS_GET) {

            $this->assign("dep", $dep);

            $this->display();
        }

        if (IS_POST) {

            $data["org_parent_id"] = session("org_parent_id");
            $data["dep_name"] = I("post.dep_name");

            if ($data["dep_name"] != $dep["dep_name"] && !$this->check($data["dep_name"])) {
                aJsonReturn(0, "部门名重复");
                return;
            }


            $data["dep_header"] = I("post.dep_header");
            $data["remark"] = I("post.remark");

            $dep = M("org_dep")->where($where)->save($data);
            if ($dep) {
                $this->success('修改成功', U('Dealer/Dep/index'));
            } else {
                $this->error('修改失败', U('Dealer/Dep/index'));
            }
        }
    }

    // 删除
    public function delAction() {
        $id = I("id");
        if (!empty($id)) {
            $where["dep_id"] = $id;

            // 检查部门下是否存在人员
            $hwhere["dep_id"] = $id;
            $hwhere["org_parent_id"] = session('org_parent_id');
            $has = M('org_staff')->where($hwhere)->count();

            if ($has > 0) {
                $this->error("操作失败, 部门下存在相关人员", U("Dealer/Dep/index"));
                return;
            }


            $succ = M("org_dep")->where($where)->delete();

            if ($succ) {
                $this->success("删除成功", U("Dealer/Dep/index"));
            } else {
                $this->error("操作失败", U("Dealer/Dep/index"));
            }

        } else {
            $this->error("无效的id", U("Dealer/Dep/index"));
        }
    }

    // ajax检查部门名是否重复
    public function checkAction() {
        $name = I("name");
        echo $this->check($name);
    }

    // 检查经销商部门是否重复
    // 返回 false 部门名不可用
    // 返回 true 可用
    private function check($name) {
        $where["dep_name"] = $name;
        $where["org_parent_id"] = session("org_parent_id");

        $check = M("org_dep")->where($where)->count();

        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }


	/** 其他Action **/


}

/*************************** end ************************************/