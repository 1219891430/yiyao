<?php

/*******************************************************************
 ** 文件名称: RoleController.class.php
 ** 功能描述: 系统后台人员角色控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class RoleController extends BaseController {

    private $menu = [];

    public function __construct(){
        parent::__construct();
    }
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		$this->assign('role_list', queryAllRole());

		$this->display();
    }

    public function settingAction() {
        $depot_id = session('depot_id');

        $role_id = I('role_id');
        $this->assign('role_id', $role_id);

        $menu = S("menu");

        if (empty($menu)) {

            $menu = $this->getAllMenu();

            S('menu',$menu,7*24*60*60);
        }

        $_menu = [];

        foreach ($menu as $val) {
            $jm = [
                'id'=>$val['id'],
                'pId'=>$val['pid'],
                'name'=>$val['title'],
                'open'=>true
            ];
            array_push($_menu, $jm);
        }

        $json_menu = json_encode($_menu);

        //echo $json_menu;die;


        $this->assign("jsonMenu", $json_menu);

        $this->assign("menu", $menu);

        $depot = queryDepot($depot_id);

        $this->assign('depot', $depot);

        $this->display();
    }

    private function getAllMenu() {
        $where = [];
        $where['status'] = 1;

        $_menu = M("menu")->where($where)->select();

        return $_menu;
    }

    public  function settingxAction() {
        $depot_id = session('depot_id');

        /*if ($_depot > 0) {
            $res = [
                "status"=>0,
                "msg" => "非法操作",
            ];
            $this->ajaxReturn($res,"JSON");
        }*/

        if ($depot_id <= 0) {
            $depot_id = I('depot_id');
        }

        $role_id = I('role_id');

        $menu_ids = I('ids');

        $menu_id_arr = array_filter(explode(",", $menu_ids));

        $where = [];
        $where['id'] = ['in', $menu_ids];
        $menus = M('menu')->where($where)->select();

        $menu = [];

        foreach ($menus as $m) {
            if ($m['level'] == 1) {
                $menu[$m['id']] = $m;
            }
        }

        foreach ($menus as $m) {
            foreach ($menu as $k=>$val) {
                if ($m['pid'] != 0 && $m['pid'] == $k) {
                    $menu[$k]['submenu'][] = $m;
                }
            }

        }

        $menu['ids'] = $menu_id_arr;

        $jsonMenus = json_encode($menu);

        $Awhere = [];
        $Awhere['depot_id'] = $depot_id;
        $Awhere['role_id'] = $role_id;

        $has = M('admin_access')->where($Awhere)->count();

        if ($has > 0) {
            $succ = M('admin_access')->where($Awhere)->setField('menus', $jsonMenus);
            if ($succ) {
                $res = [
                    "status"=>1,
                    "msg" =>"success",
                    "menus" => $menu_id_arr,
                ];

            } else {
                $res = [
                    "status"=>0,
                    "menus" => $menu_id_arr,
                ];
            }
            $this->ajaxReturn($res,"JSON");
        } else {
            $data = [
                "depot_id" => $depot_id,
                "role_id" => $role_id,
                "menus" => $jsonMenus,
            ];

            $succ = M('admin_access')->add($data);

            if ($succ) {
                $res = [
                    "status"=>1,
                    "msg" =>"success",
                    "menus" => $menu_id_arr,
                ];

            } else {
                $res = [
                    "status"=>0,
                    "menus" => $menu_id_arr,
                ];
            }
            $this->ajaxReturn($res,"JSON");

        }

    }

    // 获取选中的菜单
    public function getMenuIdsAction() {
        $depot_id = I("depot_id");
        $role_id = I("role_id");

        $where = [];
        $where['depot_id'] = $depot_id;
        $where['role_id'] = $role_id;

        $access = M('admin_access')->where($where)->getField('menus');

        $menus = json_decode($access, true);

        if (array_key_exists("ids", $menus)) {
            $ids = $menus['ids'];
        } else {
            $ids = [];
        }

        $this->ajaxReturn($ids,"JSON");

    }

    // 获取树形结构菜单
    private function getMenu($pid) {

        $where = [];
        $where['pid'] = $pid;
        $where['status'] = 1;

        $_menu = M("menu")->where($where)->select();

        foreach ($_menu as $val) {

            if ($val['pid'] == 0) {
                $this->menu[$val["id"]] = $val;
            } else {
                $this->menu[$val["pid"]]['submenu'][] = $val;
            }

            $this->getMenu($val['id']);
        }

    }

}

/*************************** end ************************************/