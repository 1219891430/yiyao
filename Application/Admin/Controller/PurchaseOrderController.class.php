<?php

/*******************************************************************
 ** 文件名称: PurchaseOrderController.class.php
 ** 功能描述: 采购单管理
 ** 创建人员: wangbo
 ** 创建日期: 2016-10-12
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class PurchaseOrderController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
    // 控制器默认页
    // 创建人员:
    // 创建日期:
    public function indexAction()
    {

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        $urlPara = array();
        $urlPara["depot_id"] = I("depot_id");
        $urlPara["staff_id"] = I("staff_id");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");
        $this->assign('urlPara', $urlPara);


        if($depotID>0) {
            $where['repertory_id'] = $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where['repertory_id'] = $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["staff_id"])) {
            $where['staff_id'] = $urlPara["staff_id"];
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"] + 24*60*60);
            if ($etime > $stime) {
                $where['add_time'] = array(array('gt', $stime),array('lt', $etime));
            }
        }

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $this->assign("p", $p);
        $this->assign("pnum", $pnum);


        $total = M('purchase_orders')->where($where)->count();

        $list = M('purchase_orders')
            ->alias('po')
            ->field('order_id,order_code,order_remark,add_time,is_cancel,cancel_time,staff_id,class_name,au.true_name as staff_name')
            ->join('left join __ADMIN_USER__ au on au.admin_id = po.staff_id')
            ->where($where)
            ->order('order_id desc')
            ->select();

        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/PresaleOrder',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示



        // 仓库
        $this->assign('depotList', queryDepot($depotID));

        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID, 6));


        $this->display();
    }

    //查看订单,可修改备注
    public function lookAction(){
        $id = I("id");
        if($_SESSION["depot_id"]){
            $where["repertory_id"] = $_SESSION["depot_id"];
        }
        $where["order_id"] = $id;

        if(IS_POST){
            $order_remark = I('order_remark');

            $data['order_remark'] = $order_remark;

            M('purchase_orders')->where($where)->save($data);

            alertToUrl('保存成功,','../PurchaseOrder/index');
        }
        else{
            $order = M('purchase_orders')->where($where)->find();

            $repertory_id = $order['repertory_id'];
            $repertory_name = M('depot_info')->where('repertory_id='.$repertory_id)->getField('repertory_name');
            $order['repertory_name'] = $repertory_name;



            $order_data = json_decode($order['order_data'],true);
            $shop_ids = $order_data['shop_ids'];
            $class_list = $order_data['data'];

            if (!empty($shop_ids)) {
                $where = [];
                $where['cust_id'] = ["in", $shop_ids];
                $shops = M("customer_info")->where($where)->select();
                $this->assign('shops', $shops);
            }

            //dump($class_list);die;
            $this->assign("class_list", $class_list);


            $this->assign("order", $order);
            $this->display();
        }
    }

    public function set_staffAction(){

        $id = I("id");
        if($_SESSION["depot_id"]){
            $where["repertory_id"] = $_SESSION["depot_id"];
        }
        $where["order_id"] = $id;
        $order = M('purchase_orders')->where($where)->find();

        if(empty($order)){
            alertToUrl('参数错误','../PurchaseOrder/index');
            return;
        }

        if(IS_POST){
            $staff_id = I('staff_id');
            $data['staff_id'] = $staff_id;
            M('purchase_orders')->where($where)->save($data);
            alertToUrl('设置成功','../PurchaseOrder/index');
        }
        else{

            //人员列表
            $staffList = M("admin_user")->field("admin_id as staff_id,true_name as staff_name")->where("depot_id=" . $order['repertory_id']." and role_id =6")->select();

            $this->assign("staff_list", $staffList);

            $this->assign('order',$order);

            $this->display();
        }
    }

    // 关闭打开人员登录
    public function closeAction()
    {
        $id = I('id', 0);
        $status = I('st', 0);
        if($_SESSION["depot_id"]){
            $where["repertory_id"] = $_SESSION["depot_id"];
        }
        $where['order_id'] = $id;
        $data['is_cancel'] = $status;
        $data['cancel_time'] = time();
        M('purchase_orders')->where($where)->save($data);
        die("1");
    }

}

/*************************** end ************************************/