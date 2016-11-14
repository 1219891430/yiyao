<?php

/*******************************************************************
 ** 文件名称: ShopsController.class.php
 ** 功能描述: 系统后台终端店控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class ShopsController extends BaseController {

    var $_mod_shop;

    public function __construct(){
        parent::__construct();
        $this->_mod_shop = new \Common\Model\ShopModel();
    }

	// 控制器默认页, 终端店管理
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
    {
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $query['repertory_id'] = I('get.repertory_id');
        $query['dealer_id'] = I('get.dealer_id');
        $query['cust_name'] = I('get.cust_name');

        if ($this->_depot_id > 0) {
            $query['repertory_id'] = $this->_depot_id;
        }
        $this->assign('query', $query);

        if (!empty($query['repertory_id'])) {
            $where['repertory_id'] = array("eq", $query['repertory_id']);
        }
        if (!empty($query["dealer_id"])) {
            $where["dealer_id"] = array("eq", $query["dealer_id"]);
        }
        if (!empty($query["cust_name"])) {
            $where["cust_name"] = array("like", "%{$query["cust_name"]}%");
        }


        //列表数据
        $total = $this->_mod_shop->table("zdb_customer_info as ci")->where($where)->count("cust_id");//总数
        $list = $this->_mod_shop->table("zdb_customer_info as ci")->where($where)->order('cust_id desc')->page($p, $pnum)->select();//列表


        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Shops/index', $query));

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist', $page);//分页显示

        $this->assign('pnum', $pnum);


        //仓库select
        $depost_list = queryDepot($this->_depot_id);
        $this->assign('depotList', $depost_list);


        //经销商select
        if ($query['repertory_id'] > 0) {
            $dealerList = queryDealer($query['repertory_id']);
            $this->assign('dealerList',$dealerList);
        }

		$this->display();
    }

    //添加终端店
    public function addAction(){
        // 禁止添加
        return false;

        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        if(IS_POST){
            $mod_shop = new \Common\Model\ShopModel();
            $data=array();
            $data['staff_id'] = 0; //后台添加置为0
            $data['reg_time'] = time();

            $data['cust_name'] = I('cust_name');
            $data['contact'] = I('contact');
            $data['telephone'] = I('telephone');
            $data['loginname'] = I('telephone');
            $data['loginpwd'] = md5(substr(I('telephone'),-6));
            $data['repertory_id'] = I('repertory_id');
            //权限判断
            if ($this->_depot_id > 0) {
                $data['repertory_id'] = $this->_depot_id;
            }
            $data['province'] = I('province');
            $data['city'] = I('city');
            $data['district'] = I('district');
            $data['address'] = I('address');

            $jwd = explode(",",I("jwd")); //经纬度处理
            $data["longitude"] = $jwd[0];
            $data["dimension"] = $jwd[1];

            //$data['is_check'] = I('is_check');
            $data['is_close'] = I('is_close');

            //判断数据格式是否正确


            //是否重复
            if($this->_mod_shop->isCheckTel(0,$data['telephone'])){
                alertToUrl('已存在手机号码信息，请查询', $jump_url );
                return;
            }

            $res = $this->_mod_shop->addShop($data);
            if($res){
                alertToUrl('添加成功', $jump_url);
            }
            else{
                alertToUrl('添加失败', $jump_url);
            }
        }
        else {
            $depotList = queryDepot($this->_depot_id);
            $this->assign('depotList',$depotList);

            $this->display();
        }
    }

    //编辑终端店
    public function editAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        $id = I('cust_id');
        if(empty($id)){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        //判断数据里有没有数据
        $data = $this->_mod_shop->find($id);
        if(!$data){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        //判断地区管理，有没有编辑权限
        if($this->_depot_id>0){
            if($data['repertory_id'] != $this->_depot_id) {
                alertToUrl('非法操作', $jump_url);
                return;
            }
        }


        if(IS_POST){
            $data = array();

            $data['cust_name'] = I('cust_name');
            $data['contact'] = I('contact');
            $data['telephone'] = I('telephone');
            $data['loginname'] = I('telephone');
            $data['loginpwd'] = md5(substr(I('telephone'),-6));

            $data['repertory_id'] = I('repertory_id');
            if($this->_depot_id>0){
                $data['repertory_id'] = $this->_depot_id;
            }
            $data['province'] = I('province');
            $data['city'] = I('city');
            $data['district'] = I('district');
            $data['address'] = I('address');

            $jwd = explode(",",I("jwd")); //经纬度处理
            $data["longitude"] = $jwd[0];
            $data["dimension"] = $jwd[1];

            //$data['is_check'] = I('is_check');
            $data['is_close'] = I('is_close');

            //判断数据格式是否正确

            //是否重复
            if($this->_mod_shop->isCheckTel($id,$data['telephone'])){
                alertToUrl('已存在该手机号码信息，请查询', $jump_url);
                return;
            }

            $res = $this->_mod_shop->editShop($id,$data);
            if($res){
                alertToUrl('修改成功', $jump_url);
            }
            else{
                alertToUrl('修改失败', $jump_url);
            }
        }
        else {
            $this->assign('data',$data);

            $depotList = queryDepot($this->_depot_id);
            $this->assign('depotList',$depotList);

            $this->display();
        }
    }

    //删除终端店
    public function delAction(){
        $id = I('cust_id');
        if(empty($id)){
            echo 0;
            return;
        }

        $where = array("cust_id"=>$id);
        //权限判断---地区管理员没有权限管理其他地区的数据
        if($this->_depot_id>0){
            $data = $this->_mod_shop->find($id);
            if($this->_depot_id != $data['repertory_id']){
                echo 0;
                return;
            }
        }
        $res = $this->_mod_shop->where($where)->delete();

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    // 关闭打开
    public function closeAction()
    {
        $id = I('id', 0);
        $status = I('st', 0);
        $where = array("cust_id"=>$id);
        //权限判断---地区管理员没有权限管理其他地区的数据
        if($this->_depot_id>0){
            $data = $this->_mod_shop->find($id);
            if($this->_depot_id != $data['repertory_id']){
                echo 0;
                return;
            }
        }
        $this->_mod_shop->where($where)->setField('is_close', $status);
        echo 1;
    }

    public function checkAction(){
        $id = I('id', 0);
        $status = I('st', 0);
        $where = array("cust_id"=>$id);

        if($this->_depot_id>0){
            $data = $this->_mod_shop->find($id);
            if($this->_depot_id != $data['repertory_id']){
                echo 0;
                return;
            }
        }
        $this->_mod_shop->where($where)->setField('is_check', $status);
        echo 1;
    }

}

/*************************** end ************************************/