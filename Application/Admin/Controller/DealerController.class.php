<?php

/*******************************************************************
 ** 文件名称: DealerController.class.php
 ** 功能描述: 系统后台经销商管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\Page;
use Common\Utils\EXCELBuilder;
class DealerController extends BaseController {

    var $_mod_dealer;

    public function __construct(){
        parent::__construct();
        $this->_mod_dealer = new \Common\Model\DealerModel();
    }

	// 控制器默认页
	// 创建人员: richie
	// 创建日期: 2016-08-02
	public function indexAction(){

        //$list = queryDealer($_GET['dep_id']);
        $p=I("get.p",1);
		$pnum=I("get.pnum",10);

        $query['dep_id'] = I('dep_id');


        if($this->_depot_id > 0) {
            $where['do.repertory_id'] = $this->_depot_id;
        }
        else {
            if (!empty($query['dep_id'])) {
                $where['do.repertory_id'] = array("eq", $query['dep_id']);
            }
        }

        
        $list=M("org_info")->field('DISTINCT zdb_org_info.org_id,zdb_org_info.*')->join("left join zdb_depot_org do on do.org_parent_id=zdb_org_info.org_id")

            ->where($where)
            ->page($p,$pnum)
            ->select();

        //echo M()->getLastSql();die;
		$total=M("org_info")->join("left join zdb_depot_org do on do.org_parent_id=zdb_org_info.org_id")->where($where)->count();

        $query['p'] = $p;
        $query['pnum'] = $pnum;
        $query['dep_id'] = $_GET['dep_id'];
        session('jump_url', U('Admin/Dealer/index',$query) );

		$page=get_page_code($total, $pnum, $p,5);


        // 内勤人员仓库所属
        $depost_list = queryDepot($this->_depot_id);
        $this->assign('depotlist', $depost_list);


		$this->assign("pagelist",$page);
		$this->assign("aUrlPara",array("dep_id"=>$_GET['dep_id']));
		$this->assign("pnum",$pnum);
        $this->assign('list', $list);
        $this->display();
    }
	
	/** 其他Action **/

	public function addAction() {
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        // 添加页面
        if (IS_POST) {
            if($_POST['is_close'] == "on"){
                $_POST['is_close'] = 1;
            }
            else{
                $_POST['is_close'] = 0;
            }

            $_POST['reg_time'] = time();
            //$where['telephone']=$_POST["telephone"];
            // 检查手机号是否存在
            //$has = M("org_info")->where($where)->find();
            $has = $this->_mod_dealer->isCheckTel(0,$_POST["mobile"]);
            if ($has) {
                alertToUrl('已存在手机号码信息，请查询', $jump_url );
                return;
            }

            $succ = M('org_info')->add($_POST);
            //echo $succ;
            if ($succ) {
                //如果是地区管理添加的经销商，直接绑定关系
                if($this->_depot_id>0){
                    $data["org_parent_id"] = $succ;
                    $data["repertory_id"] = $this->_depot_id;
                    M("depot_org")->data($data)->add();
                }
                // 初始化登录
                $staff['org_parent_id'] = $succ;
                $staff['login_user'] = $_POST["mobile"];
                $staff['login_pwd'] = md5(substr($_POST["mobile"], -6));
                $staff['staff_name'] = $_POST['contacts'];
                $staff['role_id'] = 1;
                $staff['mobile'] = $_POST["mobile"];
                $staff['is_admin'] = 1;

                $staffsucc = M('org_staff')->add($staff);

                if (!$staffsucc) {
                    alertToUrl('初始化登录信息失败', $jump_url );
                    return;
                }
                alertToUrl('添加成功', $jump_url );
            } else {
                alertToUrl('添加失败', $jump_url );
            }
        }
        else{
            $this->display();
        }
    }

    public function check_mobileAction(){
        $mobile =I('mobile');
        $org_id=I('org_id');
        $has = $this->_mod_dealer->isCheckTel($org_id,$mobile);
        if($has){
            echo 'false';
        }
        else{
            echo 'true';
        }
    }

    // 修改经销商
    public function editAction() {
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        $id = I('org_id');
        if(empty($id)){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        //判断数据里有没有数据
        $data = $this->_mod_dealer->find($id);
        if(!$data){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        $where["org_parent_id"] = $id;
        $where["repertory_id"] = $this->_depot_id;
        $data_org = M("depot_org")->where($where)->find();
        //判断地区管理，有没有编辑权限
        if($this->_depot_id>0 ){
            if($data_org['repertory_id'] != $this->_depot_id) {
                alertToUrl('非法操作', $jump_url);
                return;
            }
        }

        // 写入数据库
        if (IS_POST) {
            M("org_info")->create();
            if($_POST['is_close'] == "on"){
                $_POST['is_close'] = 1;
            }else{
                $_POST['is_close'] = 0;
            }

            $where = array("org_id"=>$id);
            $succ = $this->_mod_dealer->where($where)->data($_POST)->save();

            if ($succ) {
                alertToUrl('修改成功', $jump_url );
            } else {
                alertToUrl('修改失败', $jump_url );
            }
        }
        else{
            $this->assign('org_info', $data);
            $this->display();
        }
    }

    // 删除
    public function delAction() {
        $id = intval($_GET['org_id']);
        if(empty($id)){
            echo 0;
            return;
        }

        $where = array("org_id"=>$id);
        //权限判断---地区管理员没有权限管理其他地区的数据
        if($this->_depot_id>0){
            $where["org_parent_id"] = $id;
            $where["repertory_id"] = $this->_depot_id;
            $data = M("depot_org")->where($where)->find();
            if($this->_depot_id != $data['repertory_id']){
                echo 0;
                return;
            }
        }
        $res = $this->_mod_dealer->where($where)->delete();

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }


    /**************************仓库关联**************************/
    public function doaddAction() {
        if(IS_GET) {
            // 经销商列表
            $orglist = M('org_info')->where("is_close <> 1")->select();
            $this->assign('orglist', $orglist);

            // 仓库列表
            $depot = M('depot_info')->where("repertory_close <> 1")->select();
            $this->assign('depot', $depot);

            $this->display();
        }

        if (IS_POST) {
            $data["org_parent_id"] = $_POST["org"];
            $data["repertory_id"] = $_POST['depot'];

            //权限判断
            if($this->_depot_id>0){
                $data["repertory_id"]  = $this->_depot_id;
            }

            // 检测是否重复
            $w = array(
                "org_parent_id" => $data["org_parent_id"],
                "repertory_id" => $data["repertory_id"],
            );
            $has = M("depot_org")->where($w)->count();
            if ($has > 0) {
                $this->success('重复添加', U('Admin/Dealer/depot'));
                die();
            }

            $succ = M("depot_org")->data($data)->add();
            if ($succ) {
                $this->success('添加成功', U('Admin/Dealer/depot'));
            } else {
                $this->success('添加失败', U('Admin/Dealer/depot'));
            }
        }
    }

    public function depotAction() {

        // 经销商列表
        $this->assign('orglist', queryDealer($this->_depot_id));

        // 仓库列表
        $this->assign('depot', queryDepot($this->_depot_id));

        // 查询
        $where = "";
        if (isset($_GET["org_id"]) && ($_GET["org_id"] > 0)) { //经销商名称
            $org_id = I("get.org_id");
            $where .= 'do.org_parent_id = ' . intval($org_id);
        }

        if (isset($_GET["depot_id"]) && ($_GET["depot_id"] > 0)) { //经销商名称
            $depot_id = I('get.depot_id');
            if (isset($org_id) && $org_id > 0) {
                $where .= " AND ";
            }
            $where .= ' do.repertory_id = ' . intval($depot_id);
        }

        //权限判断
        if($this->_depot_id>0){
            $where .= ' do.repertory_id = ' . $this->_depot_id;
        }


        $dolist = M('depot_org')->alias('do')->field('d.*, o.org_name, o.org_id')
            ->join('__DEPOT_INFO__ as d on do.repertory_id = d.repertory_id')
            ->join('__ORG_INFO__ as o on do.org_parent_id = o.org_id')
            ->where($where)
            ->order('d.repertory_id asc')
            ->select();

        $this->assign('dolist', $dolist);
        //print_r($dolist);die();

        $this->display('Depot:dealer');
    }

    public function deldoAction() {
        $org_id = intval(I('org_id'));
        $depot_id = I('depot_id');

        //权限判断---地区管理员没有权限管理其他地区的数据
        if($this->_depot_id>0){
            if($this->_depot_id != $depot_id){
                $this->error("非法操作", U("Admin/Dealer/depot"));
                return;
            }
        }

        if (!empty($org_id) && !empty($depot_id)) {
            $where = array("org_parent_id"=>$org_id, "repertory_id" => $depot_id);
            M("depot_org")->where($where)->delete();

            $this->redirect('/Admin/Dealer/depot');
        } else {
            $this->error("缺少参数", U("Admin/Dealer/depot"));
        }
    }

    // 关闭打开
    public function closeAction()
    {
        $org_id = I('id', 0);
        $status = I('st', 0);
        $where['org_id'] = $org_id;

        //权限判断---地区管理员没有权限管理其他地区的数据
        if($this->_depot_id>0){
            $where["org_parent_id"] = $org_id;
            $where["repertory_id"] = $this->_depot_id;
            $data = M("depot_org")->where($where)->find();

            if($this->_depot_id != $data['repertory_id']){
                echo 0;
                return;
            }
        }

        $this->_mod_dealer->where($where)->setField('is_close', $status);
        echo 1;
    }


}

/*************************** end ************************************/