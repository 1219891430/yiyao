<?php

/*******************************************************************
 ** 文件名称: MemberController.class.php
 ** 功能描述: 用户中心控制器
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-11
 *******************************************************************/

namespace Mall\Controller;
use Common\Model\CustomerInfoModel;
use Think\Page;

class MemberController extends BaseController {

    public function __construct(){
        parent::__construct();

        if(ACTION_NAME <> 'login'){
            if( empty($_SESSION['cust_id']) ){
                $this->redirect('mall/member/login');
            }
        }
    }

    // 用户中心
    // 创建人员: wangbo
    // 创建日期: 2016-08-11
    public function indexAction()
    {
        $where['cust_id'] = session('cust_id');
        $cust_info = M('customer_info')->where($where)->find();
        $this->assign('cust_info',$cust_info);
        $this->display();
    }

    // 用户资料
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function myAction(){
        $where['cust_id'] = session('cust_id');
        if(IS_POST) {

            //print_r($_POST);die();

            $m = M('customer_info');

            //保存用户资料
            $data['cust_name'] = I('post.cust_name');

            // 检测终端店名称是否重复 ？

            $data['contact'] = I('post.contact');
            $data['telephone'] = I('post.telephone');

            $data['province'] = I('post.province');
            $data['city'] = I('post.city');
            $data['district'] = I('post.district');
            $data['address'] = I('post.address');

            $succ = $m->where($where)->save($data);

            if (!$succ) {
                alertToUrl('修改信息失败，请重试', '/index.php/Mall/Member/index' );
            } else {
                alertToUrl('修改信息成功', '/index.php/Mall/Member/index' );
            }


        }
        else{
            $cust_info = M('customer_info')->where($where)->find();
            $this->assign('cust_info',$cust_info);
            $this->display('Member:index');
        }

    }

    // 用户订单
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function orderAction(){
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 20);

        $month = I('month', 3);

        $where = [];

        if ($month > 0) {
            $start = strtotime(date("Y-m", strtotime("-$month month")));
            $end = time();

            $where['add_time'] = array('between',array($start, $end));
        }


        $where['cust_id'] = session('cust_id');
        $where['staff_id'] = 0;

        $total = M('presale_orders')->where($where)->count();

        $order_list = M('presale_orders')
            ->where($where)
            ->order('add_time desc')
            ->page($p,$pnum)->select();

        $orders = array();

        //echo M('presale_orders')->getLastSql();die;

        foreach ($order_list as $key => $val) {
            $goods = M('presale_orders_goods')->alias('cog')
                ->join('left join __GOODS_INFO__ as gi on cog.goods_id=gi.goods_id')
                ->field('cog.*, gi.main_img')
                ->where('order_id='.$val['order_id'])
                ->select();

            $val['goods'] = $goods;

            $orders[$key] = $val;
        }

        $this->assign('orders',$orders);

        //print_r($orders);die();

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);

        $this->assign('pagelist',$page);//分页显示

        $this->assign('p', $p);
        $this->assign('pnum', $pnum);

        $query['p'] = $p;
        $query['pnum'] = $pnum;

        $query['month'] = $month;

        $this->assign('query', $query);

        //print_r($query);die;

        session('jump_url', U('Mall/Member/order',$query) );

        $this->display();
    }

    // 成交订单
    public function carOrderAction() {

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $month = I('month', 3);

        $where = [];

        if ($month > 0) {
            $start = strtotime(date("Y-m", strtotime("-$month month")));
            $end = time();

            $where['add_time'] = array('between',array($start, $end));
        }

        $cust_id = session('cust_id');
        $order_id=I("get.order",0);

        $where['cust_id'] = $cust_id;
        if($order_id){
        	$where['order_id'] = $order_id;
        }
        

        $total = M('car_orders')->where($where)->count();

        $order_list = M('car_orders')
            ->where($where)
            ->order('create_time desc')
            ->page($p,$pnum)
            ->select();

        $orders = array();

        foreach ($order_list as $key => $val) {
            $goods = M('car_orders_goods')->alias('cog')
                ->join('left join __GOODS_INFO__ as gi on cog.goods_id=gi.goods_id')
                ->field('cog.*, gi.main_img, (cog.singleprice * cog.number) as total_price')
                ->where('order_id='.$val['order_id'])
                ->select();

            $val['goods'] = $goods;

            $orders[$key] = $val;
        }

        //print_r($orders);die;

        $this->assign('orders', $orders);

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);

        $this->assign('pagelist',$page);//分页显示

        $this->assign('p', $p);
        $this->assign('pnum', $pnum);

        $query['month'] = $month;
        $query['order']=$order_id;
        $this->assign('query', $query);

        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Mall/Member/order',$query) );

        $this->display();

    }

    // 取消订单
    public function ordercancelAction(){
        $where['order_id'] = I('order_id');

        $order_info = M('presale_orders')->where($where)->find();
        if(!$order_info){
            alertToUrl('获取订单失败，请重试', session('jump_url') );
            return;
        }

        if($order_info['order_status'] ==2){
            alertToUrl('订单已经派送，无法取消', session('jump_url') );
            return;
        }

        $data = array(
            'is_cancel' => 1,
            'cancel_time' => time()
        );
        M('presale_orders')->where($where)->save($data);
        alertToUrl('订单取消成功', session('jump_url') );
    }

    // 用户经常购买商品
    public function oftenbuyAction(){

        $where['cog.cust_id'] = session('cust_id');
        $where['po.staff_id'] = 0;
        $where['gi.is_close'] = 0;
        $where['cog.goods_total_money'] = array("gt", 0);

        // 经销商订单
        $cust_order_goods = M('presale_orders_goods')->alias('cog')
            ->join('left join __PRESALE_ORDERS__ as po on cog.order_id=po.order_id')
            ->join('left join __GOODS_INFO__ as gi on cog.goods_id=gi.goods_id')
            ->join('left join __ORG_GOODS_CONVERT__ as ogc on gi.goods_id=ogc.goods_id AND cog.cv_id=ogc.cv_id AND cog.org_parent_id=ogc.org_parent_id')
            ->join('left join __ORG_INFO__ as oi on ogc.org_parent_id=oi.org_id')
            ->where($where)
            ->group('cog.goods_id, cog.cv_id, cog.org_parent_id')
            ->field('count(*) as time, gi.*,ogc.org_parent_id, oi.org_name, ogc.goods_base_price, ogc.wholesale_num, ogc.cv_id, cog.unit_name')
            ->order('time desc')
            ->limit(30)
            ->select();

        $this->assign('oftenbuy', $cust_order_goods);

        //print_r($cust_order_goods);die();

        $this->display();
    }

    // 修改密码
    public function modifyPwdAction() {
        if (IS_POST) {
            $where['cust_id'] = I('post.cust_id');

            $m = new CustomerInfoModel();

            $password = I('post.password');

            if (mb_strlen($password,'utf8') < 6) {
                alertToUrl('密码长度必须大于6位', 'Mall/Member');
                return;
            }

            $password = md5($password);

            $m->loginpwd = $password;

            $m->where($where)->save();

            alertToUrl('密码修改成功', 'Mall/Member');

        } else {
            $this->display();
        }

    }

    // 用户登录
    // 创建人员: wangbo
    // 创建日期: 2016-08-11
    public function loginAction(){
        if(IS_POST) {

            /*$login_cookie =  cookie('nlh_user_login');

            print_r($login_cookie);die();*/

            $ret_url = htmlspecialchars_decode(I('ret_url'));
            $user_name = I('user_name');
            $user_pass = I('user_pass');

            if (empty($user_name) || empty($user_name)) {
                alertToUrl('请输入账户密码，重试', $ret_url);
                return;
            }
            $where['loginname'] = $user_name;
            $where['loginpwd'] = md5($user_pass);
            $where['is_close'] = 0;
            //$where['is_check'] = 1;

            $cust_info = M('customer_info')->where($where)->find();

            if (!$cust_info) {
                alertToUrl('账户或密码错误，请重试', $ret_url);
                return;
            }

            // 获取购物车数量
            $cart_num = M('cart')->where('cust_id = ' . $cust_info['cust_id'])->sum('quantity');

            session('cust_id', $cust_info['cust_id']);
            session('cust_name', $cust_info['cust_name']);
            session('mall_depot_id', $cust_info['repertory_id']);
            session('cust_depot', $cust_info['repertory_id']);
            session('staff_id', $cust_info['staff_id']);
            session('cart_num', $cart_num);

            if (empty($ret_url)) {
                $ret_url = U('Mall/index');
            }
            jumpToUrl($ret_url, 0);
        }
        else{
            $this->display();
        }
    }

    // 用户退出
    // 创建人员: wangbo
    // 创建日期: 2016-08-11
    public function logoutAction(){
        session('cust_id',null);
        session('cust_name',null);
        session('mall_depot_id',null);
        session('cust_depot', null);
        session('staff_id',null);
        session('cart_num', null);
        $this->redirect('index');
    }
    /** 其他Action **/


    public function creditAction() {

        // 商铺ID
        $p=I("p",1);
        $pnum=I("pnum",10);
        $shop_id = intval($_SESSION['cust_id']);

        $orderList = $this->carqiankuan($shop_id,$p,$pnum);

        //$carsale_order = $this->carsaleqiankuan($shop_id);

        //print_r($orderList);die;

        $where['cust_id'] = $shop_id;
        $where['is_full_pay'] = 0;
        $where['is_cancel'] = 0;
        $total = M('car_orders')->alias('co')
            ->join('left join __ADMIN_USER__ as au on co.staff_id = au.admin_id')
            ->field('co.order_id, co.order_code, co.order_total_money, co.order_real_money, co.create_time, au.admin_id as staff_id, au.true_name as staff_name')
            ->where($where)
            
            ->order('order_id desc')
            ->count();
            
        $page = get_page_code($total, $pnum, $p, $page_code_len = 30);
        
        $this->assign("p", $p);
        $this->assign("pnum", $pnum);

        $this->assign('pagelist',$page);//分页显示

        $this->assign('orderList', $orderList);

        $this->display();
    }

    // 欠款详情
    public function creditDetailAction() {
        $order_id = I('order_id');

        $detail = $this->carqiankuanDetail($order_id);

        $this->assign('detail', $detail);

        //print_r($detail);die;

        $this->display();
    }

    // 预单配送欠款
    private function carqiankuan($shop_id,$p,$pnum) {
        $where['cust_id'] = $shop_id;
        $where['is_full_pay'] = 0;
        $where['is_cancel'] = 0;
        $orderList = M('car_orders')->alias('co')
            ->join('left join __ADMIN_USER__ as au on co.staff_id = au.admin_id')
            ->field('co.order_id, co.order_code, co.order_total_money, co.order_real_money, co.create_time, au.admin_id as staff_id, au.true_name as staff_name')
            ->where($where)
            ->page($p,$pnum)
            ->order('order_id desc')
            ->select();

        // 查询历史清欠
        foreach($orderList as $key => $item)
        {
            $order_id = intval($item['order_id']);
            $qingPrice = M('car_orders_qiankuan')->where("orderid = $order_id")->sum('price');
            $orderList[$key]['banlance_money'] = $item['order_total_money'] - $item['order_real_money'] - floatval($qingPrice);

            $orderList[$key]['banlance_money'] = number_format($orderList[$key]['banlance_money'], 2);

            $orderList[$key]['create_time'] = date('Y-m-d H:i:s', $item['create_time']);
            $orderList[$key]['type'] = 1;
        }

        return $orderList;
    }

    // 预单配送欠款详情
    private function carqiankuanDetail($order_id) {

        // 查询订单详细
        $orderInfo = array();
        $result = M("car_orders")->alias('co')
            ->join('left join __ADMIN_USER__ as au on co.staff_id = au.admin_id')
            ->where("order_id = $order_id")
            ->find();
        $orderInfo['order_id'] = $result['order_id'];
        $orderInfo['order_code'] = $result['order_code'];
        $orderInfo['order_total_money'] = $result['order_total_money'];
        $orderInfo['order_real_money'] = $result['order_real_money'];
        $orderInfo['create_time'] = date('Y-m-d H:i:s', $result['create_time']);
        $orderInfo['staff_id'] = $result['admin_id'];
        $orderInfo['staff_name'] = $result['true_name'];

        // 历史清欠记录
        $qingPrice = 0;
        $qingList = M('car_orders_qiankuan')->field('price, addtime, mark')->where("orderid = $order_id")->order("oq_id asc")->select();
        foreach($qingList as $key=>$val)
        {
            $qingList[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
            $qingPrice += floatval($val['price']);
        }

        // 历史清欠总额
        $orderInfo['qingPrice'] = $qingPrice;
        $orderInfo['banlance_money'] = $orderInfo['order_total_money'] - $orderInfo['order_real_money'] - floatval($qingPrice);


        // 查询订单商品列表
        $goodsList = array();
        $results = M("car_orders_goods")->where("order_id = $order_id")->order('cv_id')->select();
        foreach($results as $item)
        {
            $temp = array();
            $temp['cv_id'] = $item['cv_id'];
            $temp['goods_id'] = $item['goods_id'];
            $temp['good_name'] = $item['good_name'];
            $temp['good_spec'] = $item['good_spec'];
            $temp['singleprice'] = $item['singleprice'];
            $temp['number'] = $item['number'];
            $temp['unit_name'] = $item['unit_name'];
            $goodsList[] = $item;
        }

        // 返回
        $data['orderInfo'] = $orderInfo;
        $data['qingList'] = $qingList;
        $data['goodsList'] = $goodsList;

        return $data;

    }


}

/*************************** end ************************************/