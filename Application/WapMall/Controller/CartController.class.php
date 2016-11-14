<?php

/*******************************************************************
 ** 文件名称: CartController.class.php
 ** 功能描述: 购物车控制器
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-11
 *******************************************************************/

namespace WapMall\Controller;

use Think\Model;

class CartController extends BaseController {

    public function __construct(){
        parent::__construct();

        if(ACTION_NAME <> 'login'){
            if( empty($_SESSION['cust_id']) ){
                $this->redirect('WapMall/member/login');
            }
        }
    }

    // 购物车
    // 创建人员: wangbo
    // 创建日期: 2016-08-11
    public function indexAction()
    {
    	

        $depot_id = session("depot_id");
        if(!empty($_SESSION['cust_id'])){
            $where['cust_id'] = session('cust_id');
        }

        $cart = M('cart')->alias('c')
            ->join('left join __ORG_INFO__ as org on c.org_parent_id=org.org_id')
            ->where($where)
            ->field('c.*, org.org_name, sum(quantity*price) as total')
            ->group('c.cv_id, c.org_parent_id')
            ->select();

        //print_r($cart);die();

        $carts = array();
        // 总金额
        $total_price = 0;
        // 活动前金额
        $real_price = 0;
        // 总数量
        $total_num = 0;
        // 货品种类
        $cv_num = count($cart);

        // 满减次数
        $_num = 0;
        // 满减金额
        $offer_money = 0;

        foreach ($cart as &$val) {

            $val["act_price"] = $val["price"];
            $val["is_act"] = 0;

            // 获取活动
            $where = [];
            $where["depot_id"] = $depot_id;
            $where["org_parent_id"] = $val["org_parent_id"];
            $where["goods_id"] = $val["goods_id"];
            $where["cv_id"] = $val["cv_id"];
            $where["is_close"] = 0;
            $where["start_time"] = array("ELT", time());
            $where["end_time"] = array("EGT", time());

            $activity = M("activity")->where($where)->find();

            //print_r($activity);die();

            if ($activity) {

                if ($activity["act_type"] == 0) {
                    $val["act_price"] = $activity["act_price"];
                    $val['act'] = $activity;
                }

                if ($activity["act_type"] == 1) {
                    $val["act_money"] = $activity["act_money"];
                    $val["act_offer_money"] = $activity["act_offer_money"];
                    $val['act'] = $activity;

                    $_num = $val['total'] / $activity['act_money'];
                    $val["offer_money"] = $activity["act_offer_money"] * floor($_num);

                    //print_r($val['total']);die();

                }

                if ($activity["act_type"] == 2 && $val['quantity'] >= $activity['goods_num']) {
                    // 获取赠品信息
                    $song_goods = M("goods_info")->alias("gi")
                        ->join("left join __GOODS_PRODUCT__ as gp on gp.goods_id=gi.goods_id")
                        ->join("left join __DEPOT_STOCK__ as ds on ds.goods_id=gi.goods_id")
                        ->field("gi.goods_id, ds.org_parent_id, gi.goods_name, gi.goods_spec, gi.main_img, gp.goods_unit")
                        ->where("gp.cv_id={$activity['song_cv_id']} AND gi.goods_id={$activity['song_goods_id']}")
                        ->find();

                    $val["goods_num"] = $activity['goods_num'];
                    $__num = $val['quantity'] / $activity['goods_num'];
                    $val["song_goods_num"] = $activity['song_goods_num'] * floor($__num);
                    $val["song"] = $song_goods;
                    $val['act'] = $activity;
                }

                /*if ($activity["act_type"] <> 0 && !isset($act["act_id"])) {
                    $act[$activity["act_id"]] = $activity;
                    $val['act'] = $activity;
                }

                if ($activity["act_type"] == 0) {
                    $val["act_price"] = $activity["act_price"];
                    $val["is_act"] = 1;

                }*/

            }

            //print_r($val);die();

            $val["act_total"] = number_format(($val['act_price'] * $val['quantity']),2);


            $carts[$val['org_parent_id']]['org'] = ['id' => $val['org_parent_id'],'name' => $val['org_name']];
            $carts[$val['org_parent_id']]['cart'][] = $val;

            $total_price += $val['act_price'] * $val['quantity'];

            $real_price += $val['price'] * $val['quantity'];
            $total_num += $val['quantity'];
            $offer_money += $val['offer_money'];

        }

        //print_r($total_price);die();
        //print_r($offer_money);die();
        $total_price -= $offer_money;

        //echo $total_price;die();

        $total_price = number_format($total_price, 2);
        $offer_money = number_format($offer_money, 2);

        $this->assign('total_num', $total_num);
        $this->assign('total_price', $total_price);
        $this->assign('cv_num', $cv_num);
        $this->assign('offer_money', $offer_money);

        //print_r($carts);die();

        $this->assign('carts',$carts);



        $this->display();
    }

    // 商品加入购物车
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function addAction(){

        if(session('cust_depot') != session('depot_id')) {
            $this->ajaxReturn( array('status'=>false,'msg'=>'禁止添加非所属仓库商品') ,'JSON');
            return;
        }

        $cv_id = isset($_GET['cv_id']) ? intval($_GET['cv_id']) : 0;
        $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 0;
        if (!$cv_id || !$quantity) {
            $this->ajaxReturn( array('status'=>false,'msg'=>'参数错误,请重试') ,'JSON');
            return;
        }

        $where['cv_id'] = I('cv_id');
        $where['org_parent_id'] = I('org');
        $where['is_close'] = 0;

        $gc_info = M('org_goods_convert')->alias('ogc')->field('ogc.*,gi.main_img')
            ->join('zdb_goods_info gi on ogc.goods_id=gi.goods_id ')
            ->where($where)
            ->find();

        if(!$gc_info){
            $this->ajaxReturn( array('status'=>false,'msg'=>'未发现该商品') ,'JSON');
            return;
        }

        // 检查添加商品是否存在购物车
        $cwhere['cust_id'] = session('cust_id');
        $cwhere['goods_id'] = $gc_info['goods_id'];
        $has = M('cart')->where($cwhere)->find();

        if ($has) {
            if ($has['cv_id'] != $cv_id) {
                $this->ajaxReturn( array('status'=>false,'msg'=>"只能添加一种单位商品") ,'JSON');
                return;
            } else {

                $where['cust_id'] = session('cust_id');
                $res = M('cart');
                $res->quantity = $has['quantity'] + $quantity;

                $res->where($where)->save();

                //echo M('cart')->getLastSql();die();


                session('cart_num', session('cart_num') + $quantity);

                $this->ajaxReturn( array('status'=>true,'msg'=>'ok') ,'JSON');
                return;
            }

        }


        if($gc_info['wholesale_num']>1 && $quantity<$gc_info['wholesale_num']){
            $this->ajaxReturn( array('status'=>false,'msg'=>"商品最小起批量为".$gc_info['wholesale_num']) ,'JSON');
            return;
        }


        //将商品加入购物车
        $cart_item = array(
            'cust_id' => session('cust_id'),
            'org_parent_id'=> $gc_info['org_parent_id'],
            'cv_id' => $cv_id,
            'goods_id' => $gc_info['goods_id'],
            'goods_name' => $gc_info['goods_name'],
            'goods_image' => $gc_info['main_img'],
            'goods_spec' => $gc_info['goods_spec'],
            'goods_unit' => $gc_info['goods_unit'],

            'price' => $gc_info['goods_base_price'],
            'quantity' => $quantity,
        );

        $res = M('cart')->add($cart_item);
        if($res){

            session('cart_num', session('cart_num') + $quantity);

            $data = $this->_get_cart_status();
            $data['subtotal'] = $quantity * $gc_info['goods_base_price'];

            $this->ajaxReturn( array('status'=>true,'msg'=>'ok','rows'=>$data) ,'JSON');
        }
        else{
            $this->ajaxReturn( array('status'=>false,'msg'=>'加入购物车失败,请重试') ,'JSON');
        }
    }

    // 获取购物车总状态
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function _get_cart_status() {
        /* 默认的返回格式 */
        $data = array(
            'amount' => 0,
            'goods' => array(), //购物车列表，包含每个购物车的状态
            'total_num' => 0,
            'cv_num' => 0
        );

        $depot_id = session("depot_id");

        /* 获取所有购物车 */
        $where['cust_id'] = session('cust_id');

        //$cart_items = M('cart')->where($where)->select();

        $cart_items = M('cart')->alias('c')
            ->join('left join __ORG_INFO__ as org on c.org_parent_id=org.org_id')
            ->where($where)
            ->field('c.*, org.org_name, sum(quantity*price) as total')
            ->group('c.cv_id, c.org_parent_id')
            ->select();

        if (empty($cart_items)) {
            session('cart_num', 0);
            return $data;
        }

        $amount = 0;
        $total_num = 0;
        $goods = array();

        // 满减次数
        $_num = 0;
        // 满减金额
        $offer_money = 0;

        foreach ($cart_items as &$item) {

            $song_goods = array();
            $song_num = 0;

            $item["act_price"] = $item["price"];
            $item["is_act"] = 0;

            // 获取活动
            $where = [];
            $where["depot_id"] = $depot_id;
            $where["org_parent_id"] = $item["org_parent_id"];
            $where["goods_id"] = $item["goods_id"];
            $where["cv_id"] = $item["cv_id"];
            $where["is_close"] = 0;
            $where["start_time"] = array("ELT", time());
            $where["end_time"] = array("EGT", time());


            $activity = M("activity")->where($where)->find();

            if ($activity) {
                if ($activity["act_type"] == 2 && $item['quantity'] >= $activity['goods_num']) {
                    $act[$activity["act_id"]] = $activity;
                    // 获取赠品信息
                    $song_goods = M("goods_info")->alias("gi")
                        ->join("left join __GOODS_PRODUCT__ as gp on gp.goods_id=gi.goods_id")
                        ->join("left join __DEPOT_STOCK__ as ds on ds.goods_id=gi.goods_id")
                        ->field("gi.goods_id, ds.org_parent_id, gi.goods_name, gi.goods_spec, gi.main_img, gp.goods_unit")
                        ->where("gp.cv_id={$activity['song_cv_id']} AND gi.goods_id={$activity['song_goods_id']}")
                        ->find();

                    $item["song_goods"] = $song_goods;
                    $__num = $item['quantity'] / $activity['goods_num'];

                    $item["song_num"] = $activity["song_goods_num"] * floor($__num);

                }

                if ($activity['act_type'] == 1) {
                    $_num = $item['total'] / $activity['act_money'];
                    $item["offer_money"] = $activity["act_offer_money"] * floor($_num);

                    $act[$activity["act_id"]] = $activity;
                }

                // 活动优惠
                if ($activity["act_type"] == 0) {
                    $item["act_price"] = $activity["act_price"];
                    $item["is_act"] = 1;
                    $act[$activity["act_id"]] = $activity;
                }
            }


            //$act[$item["goods_id"]] = M("activity")->getLastSql();
            //$act[$item["goods_id"]] = $activity;


            $subtotal = $item['act_price'] * $item['quantity'];
            $amount += $subtotal;
            $offer_money += $item['offer_money'];
            $total_num += $item['quantity'];
            $goods[$item['cv_id']] = $item;
            $goods[$item['cv_id']]["subtotal"] = number_format($subtotal,2);


        }

        //echo $subtotal;die();
        $amount -= $offer_money;

        $carts['goods'] = $goods;
        $carts['amount'] = number_format($amount, 2);
        $carts['offer_money'] = number_format($offer_money, 2);
        $carts['total_num'] += $total_num;
        $carts['cv_num'] = count($cart_items);

        session('cart_num', $total_num);

        return $carts;
    }

    // 从购物车中删除
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function delAction(){
        $cart_id = isset($_GET['cart_id']) ? $_GET['cart_id'] : 0;

        if (!$cart_id) {
            $this->ajaxReturn( array('status'=>false,'msg'=>'参数错误，请重试') ,'JSON');
            return;
        }
        //$where['cart_id'] = $cart_id;
        $res = M('cart')->delete($cart_id);
        if($res){
            $data = $this->_get_cart_status();
            $this->ajaxReturn( array('status'=>true,'msg'=>'删除成功','rows'=>$data) ,'JSON');
        }
        else{
            $this->ajaxReturn( array('status'=>false,'msg'=>'删除失败，请重试') ,'JSON');
        }
    }

    // 更新购物车中商品
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function updateAction(){
        $cust_id = session("cust_id");
        $cv_id = isset($_GET['cv_id']) ? intval($_GET['cv_id']) : 0;
        $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 0;
        if (!$cv_id || !$quantity) {
            $this->ajaxReturn( array('status'=>false,'msg'=>'参数错误,请重试') ,'JSON');
            return;
        }

        $where['ogc.cv_id'] = I('cv_id');
        $where['gi.is_close'] = 0;

        $gc_info = M('org_goods_convert')->alias('ogc')->field('ogc.*,gi.main_img,act.act_price')
            ->join('left join __ACTIVITY__ as act on act.goods_id=ogc.goods_id AND act.cv_id=ogc.cv_id AND act.act_type=0')
            ->join('zdb_goods_info gi on ogc.goods_id=gi.goods_id ')
            ->where($where)
            ->find();

        if(!$gc_info){
            $this->ajaxReturn( array('status'=>false,'msg'=>'未发现该商品') ,'JSON');
            return;
        }

        if (empty($gc_info["act_price"])) {
            $gc_info["act_price"] = $gc_info['goods_base_price'];
        }

        if($gc_info['wholesale_num']>1 && $quantity<$gc_info['wholesale_num']){
            $this->ajaxReturn( array('status'=>false,'msg'=>"商品最小起批量为".$gc_info['wholesale_num']) ,'JSON');
            return;
        }

        $cart_item = array(
            'quantity' => $quantity,
        );

        $where = [];
        $where['cv_id'] = I('cv_id');
        $where['cust_id'] = $cust_id;
        $where['is_close'] = 0;
        $res = M('cart')->where($where)->save($cart_item);

        if($res){
            $data = $this->_get_cart_status();

            $data['subtotal'] = $quantity * $gc_info['act_price'];

            $this->ajaxReturn( array('status'=>true,'msg'=>'ok','rows'=>$data) ,'JSON');
        }
        else{
            $this->ajaxReturn( array('status'=>false,'msg'=>'修改购物车失败,请重试') ,'JSON');
        }

    }

    // 清空购物车中商品
    // 创建人员: wangbo
    // 创建日期: 2016-08-15
    public function clearAction(){
        $where['cust_id'] = session('cust_id');
        $res = M('cart')->where($where)->delete();
        if($res){
            $this->ajaxReturn( array('status'=>true,'msg'=>'清空购物车成功') ,'JSON');
        }
        else{
            $this->ajaxReturn( array('status'=>false,'msg'=>'清空购物车失败,请重试') ,'JSON');
        }
    }

    // 提交订单
    public function orderAction(){

        $depot_id = session("depot_id");
        $cust_id = session('cust_id');

        if(IS_POST){

            $cv_id = I('cv_id');
            $remark = I('remark');

            // 查询购物车商品是否存在
            $where['ogc.cv_id'] = array ('in',$cv_id);
            $where['c.cust_id'] = $cust_id;

            $cv_list = M('cart')->alias('c')
                ->join('left join __ORG_GOODS_CONVERT__ as ogc on ogc.cv_id=c.cv_id AND ogc.org_parent_id=c.org_parent_id')
                ->field('ogc.*,c.quantity, c.cart_id, c.org_parent_id')
                ->where($where)
                ->select();

            if(!$cv_list){
                $this->error('订单数据错误，请重试');
                return;
            }

            $cust = M('customer_info')->find($cust_id);

            //print_r($cv_list);die();

            //echo M('cart')->getLastSql();die;


            //$cuxiao_goods = array();

            foreach ($cv_list as $key=>&$val) {

                $val["act_price"] = $val["goods_base_price"];
                $val["act_money"] = 0;
                $val["act_offer_money"] = 0;
                $val["goods_num"] = 0;
                $val["song_goods"] = array();
                $val["song_num"] = 0;
                $val["song_goods_num"] = 0;

                $val["cuxiao"] = 0;

                // 查询活动
                $where = [];
                $where["goods_id"] = $val["goods_id"];
                $where["cv_id"] = $val["cv_id"];
                $where["depot_id"] = $depot_id;
                $where["is_close"] = 0;
                $where["start_time"] = array("ELT", time());
                $where["end_time"] = array("EGT", time());
                $activity = M("activity")->where($where)->select();

                foreach ($activity as $act) {
                    if ($act["act_type"] == 0) {
                        $val["act_price"] = $act["act_price"];
                        $val["act_type"] = $act["act_type"];
                        $val["cuxiao"] = 1;
                    }

                    if ($act["act_type"] == 1) {
                        $val["act_money"] = $act["act_money"];
                        $val["act_offer_money"] = $act["act_offer_money"];

                        $_num = $val['goods_base_price'] * $val["quantity"] / $act['act_money'];
                        $val['offer_money'] = $act["act_offer_money"] * floor($_num);

                        $val["act_type"] = $act["act_type"];

                        $val["cuxiao"] = 1;

                    }

                    if ($act["act_type"] == 2  && $val['quantity'] >= $act['goods_num']) {
                        // 获取赠品信息
                        $song_goods = M("goods_info")->alias("gi")
                            ->join("left join __GOODS_PRODUCT__ as gp on gp.goods_id=gi.goods_id")
                            ->join("left join __DEPOT_STOCK__ as ds on ds.goods_id=gi.goods_id")
                            ->where("gp.cv_id={$act['song_cv_id']} AND gi.goods_id={$act['song_goods_id']}")
                            ->find();

                        //print_r($song_goods);die();

                        $val["goods_num"] = $act['goods_num'];
                        $val["song_goods_num"] = $act['song_goods_num'];

                        $__num = $val['quantity'] / $act['goods_num'];
                        $val["song_num"] = $act["song_goods_num"] * floor($__num);

                        $val["song_goods"] = $song_goods;
                        $val["act_type"] = $act["act_type"];

                        $val["cuxiao"] = 1;

                        //$cuxiao_goods = $song_goods;
                    }
                }

            }

            //print_r($cv_list);die();

            $order_cv = array();
            $quantity = 0;
            foreach($cv_list as $k=>$v){
                $quantity += $v['quantity'];

                // 活动价（没有活动为原价）
                $order_cv[$v['org_parent_id']]['amount'] += $v['act_price'] * $v['quantity'];
                // 原价
                $order_cv[$v['org_parent_id']]['original_amount'] += $v['goods_base_price'] * $v['quantity'];
                $order_cv[$v['org_parent_id']]['goods'][] = $v;

                if ($v['act_type'] == 1) {

                    // 活动价
                    $order_cv[$v['org_parent_id']]['amount'] -= $v['offer_money'];
                }
            }


            //开启事务
            $model = new Model();
            $model->startTrans();

            $success = true;

            //print_r($cust);die();
            //自动分单每个经销商的数据
            foreach($order_cv as $k1=>$v1){
                $order_id =0;
                //插入订单
                $data = array(
                    'order_code' => create_uniqid_code(),
                    'staff_id' => 0,
                    'org_parent_id' => $k1,
                    'cust_id' => $cust_id,
                    'repertory_id' => session('depot_id'),
                    'cust_name' => $cust['cust_name'],
                    'cust_contact' => $cust['contact'],
                    'cust_address' => $cust['province'].$cust['city'].$cust['district'].$cust['address'],
                    'cust_tel' => $cust['telephone'],
                    'order_total_money' => $v1['amount'],
                    'order_status' => 1,
                    'order_remark' => $remark,
                    'order_way' => 1,
                    'add_time' => time(),
                    'is_cancel' => 0,
                    'cancel_time' =>0,
                    'order_from' =>3,

                );

                $order_id = M('presale_orders')->add($data);
                //插入商品
                if($order_id){
                    foreach($order_cv[$k1]['goods'] as $k2=>$v2){

                        $goods_remark = "";
                        if (isset($v2["act_type"])) {
                            switch ($v2["act_type"]) {
                                case 0:
                                    $goods_remark = "限时特价，原价{$v2['goods_base_price']}, 现价{$v2['act_price']}";
                                    break;
                                case 1:
                                    $goods_remark = "满减优惠，满{$val["act_money"]}减{$val["act_offer_money"]}";
                                    break;
                                case 2:
                                    $goods_remark = "限时赠品，每购买{$val["goods_num"]}{$v2['goods_unit']}，赠送{$val['song_goods']['goods_name']}/{$val["song_goods"]["goods_spec"]} * {$val["song_goods_num"]}{$val["song_goods"]["goods_unit"]}";
                                    break;
                            }
                        }

                        $goods_total_money = $v2['act_price'] * $v2['quantity'] - $v2["offer_money"];
                        if ($goods_total_money <= 0) {
                            $success = false;
                        }

                        $data = array(
                            'order_id' => $order_id,
                            'cv_id' => $v2['cv_id'],
                            'cuxiao' => $v2["cuxiao"],
                            'cust_id' => $cust_id,
                            'org_parent_id' => $k1,
                            'goods_id' => $v2['goods_id'],
                            'goods_name' => $v2['goods_name'],
                            'goods_spec' => $v2['goods_spec'],
                            'singleprice' => $v2['act_price'],
                            'number' => $v2['quantity'],
                            'unit_name' => $v2['goods_unit'],
                            'goods_total_money'=> $goods_total_money,
                            "goods_remark" => $goods_remark
                        );

                        $og_id = M('presale_orders_goods')->add($data);
                        if(!$og_id){
                            $success = false;
                            break 2;
                        }

                        if (!empty($v2['song_goods'])) {
                            // 插入促销商品
                            $cuxiao = array(
                                'order_id' => $order_id,
                                'cv_id' => $v2['song_goods']['cv_id'],
                                'cuxiao' => 2,
                                'cust_id' => $cust_id,
                                'org_parent_id' => $k1,
                                'goods_id' => $v2['song_goods']['goods_id'],
                                'goods_name' => $v2['song_goods']['goods_name'],
                                'goods_spec' => $v2['song_goods']['goods_spec'],
                                'singleprice' => 0,
                                'number' => $v2['song_num'],
                                'unit_name' => $v2['song_goods']['goods_unit'],
                            );

                            $og_id = M('presale_orders_goods')->add($cuxiao);
                            if(!$og_id){
                                $success = false;
                                break 2;
                            }
                        }


                    }
                }
                else{
                    $success = false;
                    break;
                }
            }

            if($success){
                $model->commit();

                // 删除购物车中已下单商品
                $dwhere['cv_id'] = ['in', $cv_id];
                $dwhere['cust_id'] = $cust_id;
                M('cart')->where($dwhere)->delete();

                // 更新购物车中商品数量
                $cart_num = session('cart_num') - $quantity;

                if ($cart_num < 0) {
                    $cart_num = 0;
                }

                session('cart_num', $cart_num );

                $this->ajaxReturn( array('status'=>true,'msg'=>'订单提交成功，可以在用户中心查看') ,'JSON');
                //alertToUrl('订单提交成功，可以在用户中心查看', U('Mall/Member/Order') );
            }
            else{
                $model->rollback();
                $this->ajaxReturn( array('status'=>false,'msg'=>'订单提交失败，请重试') ,'JSON');
                //alertToUrl('订单提交失败，请重试');
            }

        }
    }
    /** 其他Action **/


}

/*************************** end ************************************/