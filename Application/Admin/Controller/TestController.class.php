<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/13
 * Time: 9:10
 */

namespace Admin\Controller;


class TestController extends BaseController {

    public $start;
    public $end;
    public $_token;

    public function __construct() {
        parent::__construct();

        $this->start = microtime();

        // 判断权限
        /*if ($this->_depot_id > 0) {
            echo "<script>alert('非法操作！!!');window.location='/index.php/Admin/Index/';</script>"; exit;
        }*/
    }

    public function __destruct() {
        $this->end = microtime();

        $used = $this->end - $this->start;

        //echo "<br/>";
        //echo "执行用时： <span style='color: tomato'>".$used."</span>";
    }

    // 测试页面

    public function indexAction() {

        $token = $this->createRandomStr(12);
        session("_token", $token);
        $this->assign("_token", $token);

        // 所有仓库
        $depots = M("depot_info")->select();
        $this->assign("depots", $depots);

        $this->display();
    }

    // 随机获取仓库
    private function getDepot() {
        $depot_id = M("depot_info")->order("rand()")->getField("repertory_id");
        if (!$depot_id) {
            $this->getDepot();
        }

        return $depot_id;
    }

    // 随机获取一个经销商
    private function getOrg($depot_id) {
        // 随机获取仓库下一个经销商
        $org = M("org_info")->alias("oi")
            ->join("left join __DEPOT_ORG__ as do on do.org_parent_id = oi.org_id")
            ->where("do.repertory_id=$depot_id")
            ->order('rand()')
            ->find();

        return $org;
    }

    // 随机获取经销商下的产品
    private function getGoods($org_id) {
        // 经销商下所有商品
        $gs = M("org_goods_convert")->where("org_parent_id=$org_id")/*->order("rand()")*/->select();

        // 随机获取商品
        $randgs = $this->cut_array($gs);

        $goods = [];
        foreach ($randgs as $val) {
            $num = mt_rand(1,100);
            $val["num"] = $num;
            $goods[] = $val;
        }

        return $goods;
    }

    // 随机获取采单人员
    private function getCollect($depot_id){
        // 随机获取仓库下的采单人员
        $collect_user = M("admin_user")->where("depot_id=$depot_id AND role_id=4")->order("rand()")->find();

        return $collect_user;
    }

    // 随机获取配送人员
    private function getDist($depot_id) {
        $dist_user = M("admin_user")->where("depot_id=$depot_id AND role_id=5")->order("rand()")->find();

        return $dist_user;
    }

    // 随机获取线路
    private function getLine($depot_id){
        // 随机获取仓库下的线路和线路下的店铺
        $line = M("shipping_line")->alias("sl")
            ->join("left join __SHIPPING_SHOP__ as ss on ss.line_id=sl.line_id")
            ->join("left join __CUSTOMER_INFO__ as ci on ss.shop_id=ci.cust_id")
            ->where("sl.depot_id=$depot_id")->order("rand()")->find();

        return $line;
    }

    // 随机获取经销商下业务员
    private function getOrgStaff($org_id) {
        $staff = M("org_staff")->where("org_parent_id=$org_id AND role_id=3")->order("rand()")->find();

        return $staff;
    }

    // 时间随机
    private function getTime($start, $end) {
        return mt_rand($start, $end);
    }





    // 经销商业务员
    public function addOrgOrderAction() {
        // 生成数量
        $create_num = I("post.add_num",1);

        // 生成订单
        $type = I("post.type");
        switch ($type) {
            case 2:
                $str = "退货单";
                $data = $this->addOrgReturn($create_num);
                break;
            case 3:
                $str = "调换货单";
                $data = $this->addOrgChange($create_num);
                break;
            default:
                $str = "定单";
                $data = $this->addOrgOrder($create_num);
        }


        echo "所有 $str 生成成功！共生成<code>{$data["count"]}</code>单, 跳过<code>{$data["skip"]}</code>";
    }

    // 经销商业务员下单
    private function addOrgOrder($create_num) {

        // 随机获取仓库
        $depot_id = I("depot_id", 0);

        if($depot_id <= 0) {
            $depot_id = $this->getDepot();
        }

        $rand_time = I("rand_time", 0);

        $succ = true;

        $skip = 0;
        $count = 0;

        // 启动事务
        M()->startTrans();

        for($i=1; $i<=$create_num; $i++) {

            $time = time();

            switch ($rand_time) {
                case 1:
                    $s = strtotime(date('Y-m-d 00:00:00' ,strtotime('-1 days')));
                    $e = strtotime(date('Y-m-d 23:59:59' ,strtotime('-1 days')));

                    $time = $this->getTime($s, $e);
                    break;
                case 2:
                    $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
                    $e = time();

                    $time = $this->getTime($s, $e);
                    break;
                default:
                    $time = $time;

            }

            // 随机获取经销商
            $org = $this->getOrg($depot_id);
            if (empty($org)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 随机获取经销商下业务员
            $staff = $this->getOrgStaff($org["org_id"]);
            if (empty($staff)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 随机获取店铺
            $cust_id = M("org_staff_customer")->where("staff_id={$staff['staff_id']} AND org_parent_id={$org['org_id']}")->order("rand()")->getField("shop_id");


            // 随机获取商品
            $goods = $this->getGoods($org["org_id"]);
            if (empty($goods)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 总价格
            $total = 0;
            foreach ($goods as $g) {
                $total += $g["num"] * $g["goods_base_price"];
            }

            // 通过cust_id查找商铺的信息
            $shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

            // 商品数组
            $goodsArray = array();
            foreach($goods as $value)
            {
                $temp = array();
                $temp['order_id'] = 0;
                $temp['cv_id'] = $value['cv_id'];
                $temp['cuxiao'] = 0; // 1代表促销商品
                $temp['cust_id'] = $cust_id;
                $temp['org_parent_id'] = $org["org_id"];
                $temp['goods_id'] = $value['goods_id'];
                $temp['good_name'] = $value['goods_name'];
                $temp['good_spec'] = $value['goods_spec'];
                $temp['singleprice'] = $value['goods_base_price'];
                $temp['number'] = $value['num'];
                $temp['unit_name'] = $value['goods_unit'];
                $goodsArray[] = $temp;
            }

            // 订单信息
            $orderArray = array();
            $orderArray['order_code'] = create_uniqid_code('O', $staff['staff_id']).$i;;
            $orderArray['staff_id'] = $staff['staff_id'];
            $orderArray['org_parent_id'] = $org["org_id"];
            $orderArray['cust_id'] = $cust_id;
            $orderArray['cust_name'] = $shopInfo['cust_name'];
            $orderArray['cust_contact'] = $shopInfo['contact'];
            $orderArray['cust_address'] = $shopInfo['address'];
            $orderArray['cust_tel'] = $shopInfo['telephone'];
            $orderArray['order_total_money'] = $total;
            $orderArray['order_real_money'] = mt_rand(0,$total-1);
            $orderArray['is_full_pay'] = 0;
            $orderArray['create_time'] = $time;
            //$orderArray['create_time'] = time();
            $orderArray['order_remark'] = "测试数据";
            $orderArray['order_way'] = 1;
            $orderArray['is_cancel'] = 0;
            $orderArray['cancel_time'] = 0;

            // 添加订单
            $order_id = M('carsale_orders')->add($orderArray);
            if(empty($order_id)){
                $succ = false;
                M()->rollback();
                exit(10001);
            }

            // 添加订单商品
            foreach($goodsArray as &$value){ $value['order_id'] = intval($order_id); }
            $flag = M('carsale_orders_goods')->addAll($goodsArray);

            // 提交事务
            if(empty($flag)) {
                $succ = false;
                M()->rollback();
                exit(10002);
            }



            // 添加店铺维护记录
            D('CarsaleShop')->addPosition($org['org_id'], $staff['staff_id'], $cust_id, 2);

            $count += 1;

        }

        if ($succ) {
            M()->commit();
        } else {
            M()->rollback();
        }

        return ["count"=>$count, "skip"=>$skip];
    }

    // 终端退货
    private function addOrgReturn($create_num) {

        // 随机获取仓库
        $depot_id = I("depot_id", 0);

        if($depot_id <= 0) {
            $depot_id = $this->getDepot();
        }

        $rand_time = I("rand_time", 0);

        $succ = true;

        $skip = 0;
        $count = 0;

        // 启动事务
        M()->startTrans();

        for($i=1; $i<=$create_num; $i++) {

            $time = time();

            switch ($rand_time) {
                case 1:
                    $s = strtotime(date('Y-m-d 00:00:00' ,strtotime('-1 days')));
                    $e = strtotime(date('Y-m-d 23:59:59' ,strtotime('-1 days')));

                    $time = $this->getTime($s, $e);
                    break;
                case 2:
                    $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
                    $e = time();

                    $time = $this->getTime($s, $e);
                    break;
                default:
                    $time = $time;

            }

            // 随机获取经销商
            $org = $this->getOrg($depot_id);
            if (empty($org)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 随机获取经销商下业务员
            $staff = $this->getOrgStaff($org["org_id"]);
            if (empty($staff)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 随机获取店铺
            $cust_id = M("org_staff_customer")->where("staff_id={$staff['staff_id']} AND org_parent_id={$org['org_id']}")->getField("shop_id");


            // 随机获取商品
            $goods = $this->getGoods($org["org_id"]);
            if (empty($goods)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 总价格
            $total = 0;
            foreach ($goods as $g) {
                $total += $g["num"] * $g["goods_base_price"];
            }

            // 通过cust_id查找商铺的信息
            $shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();


            // 商品数组
            $goodsArray = array();
            foreach($goods as $value)
            {
                $temp = array();
                $temp['return_id'] = 0;
                $temp['cv_id'] = $value['cv_id'];
                $temp['goods_id'] = $value['goods_id'];
                $temp['goods_name'] = $value['goods_name'];
                $temp['goods_spec'] = $value['goods_spec'];
                $temp['goods_money'] = $value['goods_base_price'];
                $temp['goods_num'] = $value['num'];
                $temp['goods_unit'] = $value['goods_unit'];
                $goodsArray[] = $temp;
            }

            $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
            $e = time();

            // 订单信息
            $orderArray = array();
            $orderArray['return_code'] = create_uniqid_code('R', $staff["staff_id"]).$i;
            $orderArray['staff_id'] = $staff["staff_id"];
            $orderArray['org_parent_id'] = $org["org_id"];
            $orderArray['cust_id'] = $cust_id;
            $orderArray['cust_name'] = $shopInfo['cust_name'];
            $orderArray['cust_contact'] = $shopInfo['contact'];
            $orderArray['cust_address'] = $shopInfo['address'];
            $orderArray['cust_tel'] = $shopInfo['telephone'];
            $orderArray['real_money'] = $total;
            $orderArray['total_money'] = $total;
            $orderArray['return_way'] = 1;
            $orderArray['return_remark'] = "测试数据";
            $orderArray['create_time'] = $time;
            //$orderArray['create_time'] = time();
            $orderArray['is_cancel'] = 0;
            $orderArray['cancel_time'] = 0;



            // 添加订单
            $return_id = M('carsales_return')->add($orderArray);
            if(empty($return_id)){
                $succ = false;
                M()->rollback();
                exit(10001);
            }

            // 添加订单商品
            foreach($goodsArray as &$value){ $value['return_id'] = intval($return_id); }
            $flag = M('carsales_return_goods')->addAll($goodsArray);

            // 提交事务
            if(empty($flag)) {
                $succ = false;
                M()->rollback();
                exit(10002);
            }

            $count++;


        }

        if ($succ) {
            M()->commit();
        } else {
            M()->rollback();
        }

        return ["count"=>$count, "skip"=>$skip];
    }

    // 经销商调换货
    private function addOrgChange($create_num) {

        // 随机获取仓库
        $depot_id = I("depot_id", 0);

        if($depot_id <= 0) {
            $depot_id = $this->getDepot();
        }

        $rand_time = I("rand_time", 0);

        $succ = true;

        $skip = 0;
        $count = 0;

        // 启动事务
        M()->startTrans();

        for($i=1; $i<=$create_num; $i++) {

            $time = time();

            switch ($rand_time) {
                case 1:
                    $s = strtotime(date('Y-m-d 00:00:00' ,strtotime('-1 days')));
                    $e = strtotime(date('Y-m-d 23:59:59' ,strtotime('-1 days')));

                    $time = $this->getTime($s, $e);
                    break;
                case 2:
                    $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
                    $e = time();

                    $time = $this->getTime($s, $e);
                    break;
                default:
                    $time = $time;

            }

            // 随机获取经销商
            $org = $this->getOrg($depot_id);
            if (empty($org)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 随机获取经销商下业务员
            $staff = $this->getOrgStaff($org["org_id"]);
            if (empty($staff)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 随机获取店铺
            $cust_id = M("org_staff_customer")->where("staff_id={$staff['staff_id']} AND org_parent_id={$org['org_id']}")->getField("shop_id");


            // 随机获取商品
            $goods = $this->getGoods($org["org_id"]);
            if (empty($goods)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }

            // 总价格
            $total = 0;
            foreach ($goods as $g) {
                $total += $g["num"] * $g["goods_base_price"];
            }

            // 通过cust_id查找商铺的信息
            $shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();


            // 商品数组
            $goodsArray = array();
            foreach($goods as $value)
            {
                $temp = array();
                $temp['cv_id'] = $value['cv_id'];
                $temp['is_change_in'] = mt_rand(0,1);
                $temp['goods_id'] = $value['goods_id'];
                $temp['goods_name'] = $value['goods_name'];
                $temp['goods_spec'] = $value['goods_spec'];
                $temp['singleprice'] = $value['goods_base_price'];
                $temp['number'] = $value['num'];
                $temp['unit_name'] = $value['goods_unit'];
                $goodsArray[] = $temp;
            }

            $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
            $e = time();

            // 订单信息
            $orderArray = array();
            $orderArray['change_code'] = create_uniqid_code('C', $staff["staff_id"]).mt_rand(0, $time);
            $orderArray['staff_id'] = $staff["staff_id"];
            $orderArray['org_parent_id'] = $org["org_id"];
            $orderArray['cust_id'] = $cust_id;
            $orderArray['cust_name'] = $shopInfo['cust_name'];
            $orderArray['cust_contact'] = $shopInfo['contact'];
            $orderArray['cust_address'] = $shopInfo['address'];
            $orderArray['cust_tel'] = $shopInfo['telephone'];
            $orderArray['real_money'] = $total;
            $orderArray['total_money'] = $total;
            $orderArray['pay_way'] = 1;
            $orderArray['change_remark'] = "测试数据";
            $orderArray['create_time'] = $time;
            //$orderArray['create_time'] = time();
            $orderArray['is_cancel'] = 0;
            $orderArray['cancel_time'] = 0;



            // 添加订单
            $change_id = M('carsales_change')->add($orderArray);
            if(empty($change_id)){
                $succ = false;
                M()->rollback();
                exit(10001);
            }

            // 添加订单商品
            foreach($goodsArray as &$value){ $value['change_id'] = intval($change_id); }
            $flag = M('carsales_change_goods')->addAll($goodsArray);

            // 提交事务
            if(empty($flag)) {
                $succ = false;
                M()->rollback();
                exit(10002);
            }

            $count++;


        }

        if ($succ) {
            M()->commit();
        } else {
            M()->rollback();
        }

        return ["count"=>$count, "skip"=>$skip];

    }





    public function createOrderAction() {

        // 生成数量
        $create_num = I("post.add_num",1);

        // 生成订单
        $type = I("post.type");
        switch ($type) {
            case 2:
                $str = "退货单";
                $data = $this->createReturn($create_num);
                break;
            case 3:
                $str = "调换货单";
                $data = $this->createChange($create_num);
                break;
            default:
                $str = "预单";
                $data = $this->createPresale($create_num);
        }


        echo "所有 $str 生成成功！生成预单<code>{$data["count"]}</code>, 跳过<code>{$data["skip"]}</code>";
    }

    // 生成预单
    private function createPresale($create_num){


        $order = M("presale_orders");
        $order->startTrans();

        $pog = M("presale_orders_goods");
        $pog->startTrans();

        $depot_id = I("post.depot_id", 0);

        $rand_time = I("rand_time", 0);

        $succ = true;

        $skip = 0;
        $count = 0;
        for($i=1; $i<=$create_num; $i++) {

            $time = time();

            switch ($rand_time) {
                case 1:
                    $s = strtotime(date('Y-m-d 00:00:00' ,strtotime('-1 days')));
                    $e = strtotime(date('Y-m-d 23:59:59' ,strtotime('-1 days')));

                    $time = $this->getTime($s, $e);
                    break;
                case 2:
                    $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
                    $e = time();

                    $time = $this->getTime($s, $e);
                    break;
                default:
                    $time = $time;

            }

            $data = [];

            if ($depot_id == 0) {
                $depot_id = $this->getDepot();
            }
            $data["depot_id"] = $depot_id;

            // 随机获取仓库下一个经销商
            $org = $this->getOrg($depot_id);
            if (empty($org)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["org"] = $org;
            //echo "获取经销商成功：<span style='color: tomato'>".$org['org_name']."</span>";

            $goods = $this->getGoods($org["org_id"]);
            if (empty($goods)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["goods"] = $goods;

            $collect_user = $this->getCollect($depot_id);
            if (empty($collect_user)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["collect_user"] = $collect_user;

            $line = $this->getLine($depot_id);
            if (empty($line)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["line"] = $line;


            /**********************************************************/
            /*************************** 添加 开始 **********************/
            /**********************************************************/

            $temp = array();
            $temp['order_code'] = create_uniqid_code("PO");
            $temp['staff_id'] = $data["collect_user"]["admin_id"];
            $temp['org_parent_id'] = $data["org"]['org_id'];
            $temp['cust_id'] = $data["line"]['cust_id'];
            $temp['cust_name'] = $data["line"]['cust_name'];
            $temp['cust_contact'] = $data["line"]['contact'];
            $temp['cust_address'] = $data["line"]['address'];
            $temp['cust_tel'] = $data["line"]['telephone'];
            $temp['repertory_id'] = $data["depot_id"];

            // 总价格
            $total = 0;
            foreach ($data["goods"] as $goods) {
                $total += $goods["num"] * $goods["goods_base_price"];
            }

            $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
            $e = time();

            $temp["order_total_money"] = $total;
            $temp["order_status"] = 1;
            $temp["order_remark"] = "测试数据";
            $temp["order_way"] = 1;
            $temp["add_time"] = $time;
            //$temp["add_time"] = time();


            $orderId = $order->add($temp);

            if (!$orderId) {

                $succ = false;
            }

            if($order) {

                foreach ($data["goods"] as $goods) {
                    // 写入预单商品
                    $gtmp["order_id"] = $orderId;
                    $gtmp["cust_id"] = $data["line"]['cust_id'];
                    $gtmp["org_parent_id"] = $data["org"]['org_id'];
                    $gtmp["cv_id"] = $goods["cv_id"];
                    $gtmp["goods_id"] = $goods["goods_id"];
                    $gtmp["goods_name"] = $goods["goods_name"];
                    $gtmp["goods_spec"] = $goods["goods_spec"];
                    $gtmp["singleprice"] = $goods["goods_base_price"];
                    $gtmp["number"] = $goods["num"];
                    $gtmp["unit_name"] = $goods["goods_unit"];

                    $pogsucc = $pog->add($gtmp);
                    if (!$pogsucc) {
                        $succ = false;
                    }
                }

                $count += 1;

            }

            /**********************************************************/
            /*************************** 添加 结束 **********************/
            /**********************************************************/

        }

        if ($succ) {
            $order->commit();
            $pog->commit();
        } else {
            $order->rollback();
            $pog->rollback();
        }

        return ["count"=>$count, "skip"=>$skip];
    }

    // 生成退货单
    private function createReturn($create_num)
    {

        $order = M("presale_return");
        $order->startTrans();

        $prg = M("presale_return_goods");
        $prg->startTrans();

        $depot_id = I("depot_id", 0);
        $rand_time = I("rand_time", 0);

        $succ = true;


        $skip = 0;
        $count = 0;
        for ($i = 1; $i <= $create_num; $i++) {

            $time = time();

            switch ($rand_time) {
                case 1:
                    $s = strtotime(date('Y-m-d 00:00:00' ,strtotime('-1 days')));
                    $e = strtotime(date('Y-m-d 23:59:59' ,strtotime('-1 days')));

                    $time = $this->getTime($s, $e);
                    break;
                case 2:
                    $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
                    $e = time();

                    $time = $this->getTime($s, $e);
                    break;
                default:
                    $time = $time;

            }

            $data = [];

            if ($depot_id == 0) {
                $depot_id = $this->getDepot();
            }
            $data["depot_id"] = $depot_id;

            // 随机获取仓库下一个经销商
            $org = $this->getOrg($depot_id);
            if (empty($org)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["org"] = $org;
            //echo "获取经销商成功：<span style='color: tomato'>".$org['org_name']."</span>";

            $goods = $this->getGoods($org["org_id"]);
            if (empty($goods)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["goods"] = $goods;

            $collect_user = $this->getCollect($depot_id);
            if (empty($collect_user)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["collect_user"] = $collect_user;

            $line = $this->getLine($depot_id);
            if (empty($line)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["line"] = $line;


            /**********************************************************/
            /*************************** 添加 开始 **********************/
            /**********************************************************/

            $temp = array();
            $temp['return_code'] = create_uniqid_code("PR");
            $temp['staff_id'] = $data["collect_user"]["admin_id"];
            $temp['org_parent_id'] = $data["org"]['org_id'];
            $temp['cust_id'] = $data["line"]['cust_id'];
            $temp['cust_name'] = $data["line"]['cust_name'];
            $temp['cust_contact'] = $data["line"]['contact'];
            $temp['cust_address'] = $data["line"]['address'];
            $temp['cust_tel'] = $data["line"]['telephone'];
            $temp['repertory_id'] = $data["depot_id"];

            // 总价格
            $total = 0;
            foreach ($data["goods"] as $goods) {
                $total += $goods["num"] * $goods["goods_base_price"];
            }

            $s = strtotime(date('Y-m-01 00:00:00', strtotime('-3 month')));
            $e = time();

            $temp["return_real_money"] = $total;
            $temp["order_status"] = 1;
            $temp["order_remark"] = "测试数据";
            $temp["order_way"] = 1;
            $temp["add_time"] = $time;


            $returnId = $order->add($temp);

            if (!$returnId) {

                $succ = false;
            }

            if ($order) {

                foreach ($data["goods"] as $goods) {
                    // 写入退货商品
                    $gtmp["return_id"] = $returnId;
                    $gtmp["cv_id"] = $goods["cv_id"];
                    $gtmp["goods_id"] = $goods["goods_id"];
                    $gtmp["goods_name"] = $goods["goods_name"];
                    $gtmp["goods_spec"] = $goods["goods_spec"];
                    $gtmp["goods_money"] = $goods["goods_base_price"];
                    $gtmp["goods_num"] = $goods["num"];
                    $gtmp["goods_unit"] = $goods["goods_unit"];

                    $prgsucc = $prg->add($gtmp);
                    if (!$prgsucc) {
                        $succ = false;
                    }
                }

                $count += 1;

            }

            /**********************************************************/
            /*************************** 添加 结束 **********************/
            /**********************************************************/

        }

        if ($succ) {
            $order->commit();
            $prg->commit();
        } else {
            $order->rollback();
            $prg->rollback();
        }

        return ["count"=>$count, "skip"=>$skip];
    }

    // 生成换货单
    private function createChange($create_num) {

        $order = M("presale_change");
        $order->startTrans();

        $prg = M("presale_change_goods");
        $prg->startTrans();

        $depot_id = I("depot_id", 0);
        $rand_time = I("rand_time", 0);

        $succ = true;


        $skip = 0;
        $count = 0;
        for ($i = 1; $i <= $create_num; $i++) {

            $time = time();

            switch ($rand_time) {
                case 1:
                    $s = strtotime(date('Y-m-d 00:00:00' ,strtotime('-1 days')));
                    $e = strtotime(date('Y-m-d 23:59:59' ,strtotime('-1 days')));

                    $time = $this->getTime($s, $e);
                    break;
                case 2:
                    $s = strtotime(date('Y-m-01 00:00:00' ,strtotime('-3 month')));
                    $e = time();

                    $time = $this->getTime($s, $e);
                    break;
                default:
                    $time = $time;

            }

            $data = [];

            if ($depot_id == 0) {
                $depot_id = $this->getDepot();
            }
            $data["depot_id"] = $depot_id;

            // 随机获取仓库下一个经销商
            $org = $this->getOrg($depot_id);
            if (empty($org)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["org"] = $org;
            //echo "获取经销商成功：<span style='color: tomato'>".$org['org_name']."</span>";

            $goods = $this->getGoods($org["org_id"]);
            if (empty($goods)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["goods"] = $goods;

            $collect_user = $this->getCollect($depot_id);
            if (empty($collect_user)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["collect_user"] = $collect_user;

            $line = $this->getLine($depot_id);
            if (empty($line)) {
                $skip += 1;
                $create_num += 1;
                continue;
            }
            $data["line"] = $line;


            /**********************************************************/
            /*************************** 添加 开始 **********************/
            /**********************************************************/

            $temp = array();
            $temp['change_code'] = create_uniqid_code("PR").$i;
            $temp['staff_id'] = $data["collect_user"]["admin_id"];
            $temp['org_parent_id'] = $data["org"]['org_id'];
            $temp['cust_id'] = $data["line"]['cust_id'];
            $temp['cust_name'] = $data["line"]['cust_name'];
            $temp['cust_contact'] = $data["line"]['contact'];
            $temp['cust_address'] = $data["line"]['address'];
            $temp['cust_tel'] = $data["line"]['telephone'];
            $temp['repertory_id'] = $data["depot_id"];


            $is_change_in = mt_rand(0, 1);

            // 总价格
            $total = 0;
            foreach ($data["goods"] as $goods) {
                $total += $goods["num"] * $goods["goods_base_price"];
            }

            $temp["order_total_money"] = $total;
            $temp["order_status"] = 1;
            $temp["order_remark"] = "测试数据";
            $temp["order_way"] = 1;
            $temp["add_time"] = $time;


            $changeId = $order->add($temp);

            if (!$changeId) {

                $succ = false;
            }

            if ($order) {

                foreach ($data["goods"] as $goods) {
                    // 写入退货商品
                    $gtmp["change_id"] = $changeId;
                    $gtmp["cv_id"] = $goods["cv_id"];
                    $gtmp["is_change_in"] = mt_rand(0,1);
                    $gtmp["goods_id"] = $goods["goods_id"];
                    $gtmp["goods_name"] = $goods["goods_name"];
                    $gtmp["goods_sepc"] = $goods["goods_spec"];
                    $gtmp["singleprice"] = $goods["goods_base_price"];
                    $gtmp["number"] = $goods["num"];
                    $gtmp["unit_name"] = $goods["goods_unit"];

                    $prgsucc = $prg->add($gtmp);
                    if (!$prgsucc) {
                        $succ = false;
                    }
                }

                $count += 1;

            }

            /**********************************************************/
            /*************************** 添加 结束 **********************/
            /**********************************************************/

        }

        if ($succ) {
            $order->commit();
            $prg->commit();
        } else {
            $order->rollback();
            $prg->rollback();
        }

        return ["count"=>$count, "skip"=>$skip];
    }

    // 指派预单
    public function assignPresaleAction() {
        //获取所有仓库
        $depots = M("depot_info")->select();

        $car_apply = M('car_apply');
        $car_apply->startTrans();
        $car_apply_goods = M('car_apply_goods');
        $car_apply_goods->startTrans();

        // 未找到线路
        $line_skip = 0;
        // 线路上未找到店铺
        $shop_skip = 0;
        // 未找到配送员
        $dist_skip = 0;
        // 未找到预单
        $order_skip = 0;
        // 未找到商品
        $goods_skip = 0;
        // 未找到货品
        $product_skip = 0;

        // 订单数量
        $order_count = 0;

        $succ = true;
        foreach($depots as $val) {

            $depot_id = $val["repertory_id"];

            // 随机获取线路
            $line = $this->getLine($depot_id);

            //echo "仓库ID：".$val["repertory_id"] ."== 线路ID ".$line["line_id"]."\r\n";

            if(!$line) {
                $line_skip += 1;
                continue;
            }

            // 配送线路中的店铺
            $shop_id = M('shipping_shop')->where("line_id = " . $line["line_id"])->getField('shop_id',true);

            if(!$shop_id) {
                $shop_skip += 1;
                continue;
            }

            // 随机获取配送人员
            $dist_user = $this->getDist($depot_id);
            $dist_id = $dist_user["admin_id"];

            //print_r($dist_user);

            if(!$dist_user) {
                $dist_skip += 1;
                continue;
            }


            // 拼接where
            $where = [];
            $where['order_status'] = 1;
            $where['cust_id'] = array('in', $shop_id);
            $where['repertory_id'] = intval($depot_id);

            // 获取仓库下所有预单
            $orders = M("presale_orders")->where($where)->select();
            $order_count += count($orders);

            if(!$orders) {
                $order_skip += 1;
                continue;
            }

            // 订单列表, 按照终端店归类
            $presale_id_list = array();

            $goodsIdNumber = array();

            foreach($orders as $item) {

                // 预单下所有商品
                $goods = M("presale_orders_goods")->where("order_id=".$item['order_id'])->select();

                if(!$goods) {
                    $goods_skip += 1;
                    continue;
                }

                //print_r($goods);

                foreach ($goods as $val) {
                    if (empty($goodsList[$val["goods_id"]])) {
                        $goodsIdNumber[$val["goods_id"]] = 0;
                    }

                    $num = getTransUnit($val["cv_id"], $val["number"]);

                    //print_r($num);

                    $goodsIdNumber[$val["goods_id"]] += $num["goods_num"];
                }

                $goodsIdArray = array();

                foreach ($goodsIdNumber as $key => $val1) {
                    $goodsIdArray[] = $key;
                }

                $where['goods_id'] = array('in', implode(',', $goodsIdArray));
                $where['goods_unit_type'] = 1; // 最小单位
                $goodsList = M('goods_product')->where($where)->order('goods_id asc')->select();

                if(!$goodsList) {
                    $product_skip += 1;
                    continue;
                }

                //print_r($goodsList);


                // 申请商品信息
                $goodsData = array();
                foreach($goodsList as $item1)
                {
                    $temp = array();
                    $temp['apply_id'] = 0;
                    $temp['cv_id'] = $item1['cv_id'];
                    $temp['goods_id'] = $gid = $item1['goods_id'];
                    $temp['goods_name'] = $item1['goods_name'];
                    $temp['goods_sepc'] = $item1['goods_spec'];
                    $temp['apply_price'] = 0;
                    $temp['apply_num'] = $goodsIdNumber[$gid];
                    $temp['goods_unit'] = $item1['goods_unit'];
                    $goodsData[] = $temp;

                }


                /*// 车存申请
                $adminIdArray[] = $dist_id;

                $carApplyFlag = implode(",", $adminIdArray);
                $carApplyFlag = md5($carApplyFlag);


                // 添加申请单信息
                $data['apply_code'] = create_uniqid_code('CA', $dist_user["admin_id"]);
                $data['staff_id'] = $dist_user["admin_id"];
                $data['org_parent_id'] = 0; // 没有经销商
                $data['repertory_id'] = $depot_id; // 配送员所在的仓库
                $data['apply_status'] = 2; // 已审核
                $data['apply_total_money'] = 0;
                $data['apply_remark'] = '';
                $data['apply_flag'] = $carApplyFlag; // 配送预单标示
                $data['add_id'] = 1;
                $data['add_time'] = time();
                $data['check_id'] = 1;
                $data['check_time'] = time();
                $data['accept_time'] = 0;
                $data['is_cancel'] = 0;
                $data['cancel_time'] = 0;
                $apply_id = $car_apply->add($data);

                if (!$apply_id) {
                    $succ = false;
                }

                // 添加车申商品
                foreach($goodsData as &$item2){
                    $item2['apply_id'] = $apply_id;
                }

                $cagsave = $car_apply_goods->addAll($goodsData);

                if (!$cagsave) {
                    $succ = false;
                }

                $this->addDepotOutOrder($apply_id);*/


                //配送业务员收货
                // 修改为收货状态
                /*$data["apply_status"] = 3;
                $data["accept_time"] = time();
                $flag = M('car_apply')->where("apply_id = $apply_id")->save($data);

                // 添加车存库存
                $goodsList = array();
                $results = M('car_apply_goods')->where("apply_id = $apply_id")->order("cv_id asc")->select();
                foreach($results as $val)
                {
                    $temp = array();
                    $temp['cv_id'] = $val['cv_id'];
                    $temp['goods_id'] = $val['goods_id'];
                    $temp['goods_num'] = $val['apply_num'];
                    $goodsList[] = $temp;
                }
                D('DeliverStock')->updateCarInfo($dist_id, $goodsList, 1);*/

                $aoData = array();
                $aoData["admin_id"] = $dist_id;
                $aoData["order_type"] = 1;
                $aoData["order_id"] = $item['order_id'];
                $aoData["order_state"] = 2;
                $aoData["shop_id"] = $item["cust_id"];

                $where = array();
                $where["order_state"] = 3;
                $where['order_type'] = 1;
                $where['order_id'] = $item['order_id'];

                $ao = M("admin_order")->where($where)->select();

                if ($ao) {
                    echo "还有未配送的订单";die();
                }

                // 删除旧单据
                //M("admin_order")->where("admin_id=$dist_id AND order_id=".$item['order_id'])->delete();

                M("admin_order")->add($aoData);


                $presale_id_list[] = $item['order_id'];

            }
            // 修改预单状态为已派送
            if(!empty($presale_id_list)){
                $where = [];
                $where["order_id"] = ["in", $presale_id_list];
                M('presale_orders')->where($where)->setField('order_status', 2);
            }
        }

        if ($succ) {
            $car_apply->commit();
            $car_apply_goods->commit();

            /*echo "共找到 $order_count 预单<br> 未找到线路，跳过 $line_skip 单<br>线路上未找到店铺，跳过 $shop_skip 单<br>未找到配送员，跳过 $dist_skip 单<br>未找到退货单，跳过 $order_skip 单<br>未找到商品， 跳过 $goods_skip 单<br>未找到货品，跳过 $product_skip 单";*/

        } else {
            $car_apply->rollback();
            $car_apply_goods->rollback();
        }
    }

    // 指派退货单
    public function assignReturnAction() {
        //获取所有仓库
        $depots = M("depot_info")->select();

        $car_apply = M('car_return');
        $car_apply->startTrans();
        $car_apply_goods = M('car_return_goods');
        $car_apply_goods->startTrans();

        // 未找到线路
        $line_skip = 0;
        // 线路上未找到店铺
        $shop_skip = 0;
        // 未找到配送员
        $dist_skip = 0;
        // 未找到退货单
        $return_skip = 0;
        // 未找到商品
        $goods_skip = 0;
        // 未找到货品
        $product_skip = 0;

        // 单据数量
        $order_count = 0;

        $succ = true;
        foreach($depots as $val) {

            $depot_id = $val["repertory_id"];

            // 随机获取线路
            $line = $this->getLine($depot_id);

            //echo "仓库ID：".$val["repertory_id"] ."== 线路ID ".$line["line_id"]."\r\n";

            if(!$line) {
                $line_skip += 1;
                continue;
            }

            // 配送线路中的店铺
            $shop_id = M('shipping_shop')->where("line_id = " . $line["line_id"])->getField('shop_id',true);

            if(!$shop_id) {
                $shop_skip += 1;
                continue;
            }

            // 随机获取配送人员
            $dist_user = $this->getDist($depot_id);
            $dist_id = $dist_user["admin_id"];

            //print_r($dist_user);

            if(!$dist_user) {
                $dist_skip += 1;
                continue;
            }


            // 拼接where
            $where = [];
            $where['order_status'] = 1;
            $where['cust_id'] = array('in', $shop_id);
            $where['repertory_id'] = intval($depot_id);

            // 获取仓库下所有退货单
            $orders = M("presale_return")->where($where)->select();

            $order_count += count($orders);

            if(!$orders) {
                // 未找到退货单
                $return_skip += 1;
                continue;
            }

            // 订单列表, 按照终端店归类
            $presale_id_list = array();

            $goodsIdNumber = array();


            foreach($orders as $item) {

                // 预单下所有商品
                $goods = M("presale_return_goods")->where("return_id=".$item['return_id'])->select();

                if(!$goods) {
                    $goods_skip += 1;
                    continue;
                }

                //print_r($goods);

                foreach ($goods as $val) {
                    if (empty($goodsList[$val["goods_id"]])) {
                        $goodsIdNumber[$val["goods_id"]] = 0;
                    }

                    $num = getTransUnit($val["cv_id"], $val["number"]);

                    //print_r($num);

                    $goodsIdNumber[$val["goods_id"]] += $num["goods_num"];
                }

                $goodsIdArray = array();

                foreach ($goodsIdNumber as $key => $val1) {
                    $goodsIdArray[] = $key;
                }

                $where['goods_id'] = array('in', implode(',', $goodsIdArray));
                $where['goods_unit_type'] = 1; // 最小单位
                $goodsList = M('goods_product')->where($where)->order('goods_id asc')->select();

                if(!$goodsList) {
                    $product_skip += 1;
                    continue;
                }


                $aoData = array();
                $aoData["admin_id"] = $dist_id;
                $aoData["order_type"] = 2;
                $aoData["order_id"] = $item['return_id'];
                $aoData["order_state"] = 2;
                $aoData["shop_id"] = $item["cust_id"];

                $where = array();
                $where["order_state"] = 3;
                $where['order_type'] = 1;
                $where['order_id'] = $item['order_id'];

                $ao = M("admin_order")->where($where)->select();

                if ($ao) {
                    echo "还有未配送的订单";die();
                }

                // 删除旧单据
                //M("admin_order")->where("admin_id=$dist_id AND order_id=".$item['return_id'])->delete();

                M("admin_order")->add($aoData);


                $presale_id_list[] = $item['return_id'];

            }
            // 修改预单状态为已派送
            if(!empty($presale_id_list)){
                $where = [];
                $where["order_id"] = ["in", $presale_id_list];
                M('presale_return')->where($where)->setField('order_status', 2);
                $succ=true;
            }
        }

        if ($succ) {
            $car_apply->commit();
            $car_apply_goods->commit();

            /*echo "共找到 $order_count 退货单<br> 未找到线路，跳过 $line_skip 单<br>线路上未找到店铺，跳过 $shop_skip 单<br>未找到配送员，跳过 $dist_skip 单<br>未找到退货单，跳过 $return_skip 单<br>未找到商品， 跳过 $goods_skip 单<br>未找到货品，跳过 $product_skip 单";*/
        } else {
            $car_apply->rollback();
            $car_apply_goods->rollback();
        }
    }

    // 指派调换货
    public function assignChangeAction() {
        //获取所有仓库
        $depots = M("depot_info")->select();

        $car_apply = M('car_change');
        $car_apply->startTrans();
        $car_apply_goods = M('car_change_goods');
        $car_apply_goods->startTrans();

        $skip = 0;
        $succ = true;

        foreach($depots as $val) {

            $depot_id = $val["repertory_id"];

            // 随机获取线路
            $line = $this->getLine($depot_id);

            //echo "仓库ID：".$val["repertory_id"] ."== 线路ID ".$line["line_id"]."\r\n";

            if(!$line) {
                $skip += 1;
                continue;
            }

            // 随机获取配送人员
            $dist_user = $this->getDist($depot_id);
            $dist_id = $dist_user["admin_id"];

            //print_r($dist_user);

            if(!$dist_user) {
                $skip += 1;
                continue;
            }

            // 配送线路中的店铺
            $shop_ids = M('shipping_shop')->where("line_id = " . $line["line_id"])->getField("shop_id");

            if(!$shop_ids) {
                $skip += 1;
                continue;
            }

            foreach ($shop_ids as $shop_id) {

                // 拼接where
                $where = [];
                $where['order_status'] = 1;
                $where['cust_id'] = array('in', $shop_id);
                $where['repertory_id'] = intval($depot_id);

                // 获取仓库下所有预单
                $orders = M("presale_change")->where($where)->select();

                //print_r($orders);

                if(!$orders) {
                    $skip += 1;
                    continue;
                }

                // 订单列表, 按照终端店归类
                $presale_id_list = array();

                $goodsIdNumber = array();

                foreach($orders as $item) {

                    // 预单下所有商品
                    $goods = M("presale_change_goods")->where("change_id=".$item['change_id'])->select();

                    if(!$goods) {
                        $skip += 1;
                        continue;
                    }

                    //print_r($goods);

                    foreach ($goods as $val) {
                        if (empty($goodsList[$val["goods_id"]])) {
                            $goodsIdNumber[$val["goods_id"]] = 0;
                        }

                        $num = getTransUnit($val["cv_id"], $val["number"]);

                        //print_r($num);

                        $goodsIdNumber[$val["goods_id"]] += $num["goods_num"];
                    }

                    $goodsIdArray = array();

                    foreach ($goodsIdNumber as $key => $val1) {
                        $goodsIdArray[] = $key;
                    }

                    $where['goods_id'] = array('in', implode(',', $goodsIdArray));
                    $where['goods_unit_type'] = 1; // 最小单位
                    $goodsList = M('goods_product')->where($where)->order('goods_id asc')->select();

                    if(!$goodsList) {
                        $skip += 1;
                        continue;
                    }

                    //print_r($goodsList);
                    $aoData = array();
                    $aoData["admin_id"] = $dist_id;
                    $aoData["order_type"] = 3;
                    $aoData["order_id"] = $item['change_id'];
                    $aoData["order_state"] = 2;
                    $aoData["shop_id"] = $item["cust_id"];

                    $where = array();
                    $where["order_state"] = 3;
                    $where['order_type'] = 3;
                    $where['order_id'] = $item['order_id'];

                    $ao = M("admin_order")->where($where)->select();

                    if ($ao) {
                        echo "还有未配送的订单";die();
                    }

                    // 删除旧单据
                    //M("admin_order")->where("admin_id=$dist_id")->delete();

                    M("admin_order")->add($aoData);


                    // 申请商品信息
                    $presale_id_list[] = $item['change_id'];

                }

            }



            // 修改预单状态为已派送
            if(!empty($presale_id_list)){
                $where = [];
                $where["order_id"] = ["in", $presale_id_list];
                $where["order_status"] = 1;
                M('presale_change')->where($where)->setField('order_status', 2);

            }
        }

        if ($succ) {
            $car_apply->commit();
            $car_apply_goods->commit();

            echo "skip: $skip";
        } else {
            $car_apply->rollback();
            $car_apply_goods->rollback();
        }



    }


    // 店铺确认下单
    public function addCarOrderAction() {

        $order = M("admin_order")->where("order_state=2 AND order_type=1")->select();

        foreach ($order as $k=>$val) {

            $presale = M("presale_orders")->where("order_id=".$val["order_id"])->find();

            if(!$presale) {
                continue;
            }

            $cust_id = $presale["cust_id"];
            $staff_id = $presale["staff_id"];

            $org_parent_id = $presale["org_parent_id"];

            $orderId = $presale["order_id"];

            $order_real_money = $presale["order_total_money"];

            $goods_list = M("presale_orders_goods")->where("order_id=".$orderId)->select();

            // 采单员所在仓库
            $depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');


            // 通过cust_id查找商铺的信息
            $shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

            // 添加订单
            $order_info = array();
            $order_info['order_code'] = $k.create_uniqid_code('CO', $staff_id);

            $order_info['repertory_id'] = intval($depot_id);
            $order_info['org_parent_id'] = $org_parent_id; // 经销商
            $order_info['staff_id'] = $val["admin_id"];
            $order_info['cust_id'] = $cust_id;
            $order_info['cust_name'] = $shopInfo['cust_name'];
            $order_info['cust_contact'] = $shopInfo['contact'];
            $order_info['cust_address'] = $shopInfo['address'];
            $order_info['cust_tel'] = $shopInfo['telephone'];
            $order_info['order_real_money'] = mt_rand(0, $order_real_money-1)/*$order_real_money*/; // 订单实收金额
            $order_info['order_remark'] = "测试数据";
            $order_info['order_way'] = 1;
            $order_info['order_id'] = $orderId; // 预单ID

            $flag = D('DeliverOrder')->addOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);

            // 减少车销库存


            // 设置订单已完成
            $where = array();
            $where['order_type'] = 1;
            $where['order_id'] = $orderId;

            M("admin_order")->where($where)->setField('order_state', 3);

            M("admin_order")->where($where)->delete();

            $presale_id_list[] = $orderId;

        }

        // 修改预单状态为已派送
        if(!empty($presale_id_list)){
            $where = [];
            $where["order_id"] = ["in", $presale_id_list];
            M('presale_orders')->where($where)->setField('order_status', 3);
        }
    }

    // 店铺确认下单(退货)
    public function addCarReturnOrderAction() {
        $order = M("admin_order")->where("order_state=2 AND order_type=2")->select();

        $i=0;
        foreach ($order as $k=>$val) {
            $i++;

            $return = M("presale_return")->where("return_id={$val['order_id']}")->find();

            $cust_id = $return["cust_id"];
            $staff_id = $return["staff_id"];

            $org_parent_id = $return["org_parent_id"];

            $orderId = $return["return_id"];

            $order_real_money = $return["order_total_money"];

            $goods_list = M("presale_return_goods")->where("return_id=".$orderId)->select();

            foreach($goods_list as $k=>$v){

                $goods_list[$k]["goods_spec"]=$v["goods_sepc"];
                $goods_list[$k]["singleprice"]=$v["goods_money"];
                $goods_list[$k]["number"]=$v["goods_num"];
                $goods_list[$k]["unit_name"]=$v["goods_unit"];
            }


            // 采单员所在仓库
            $depot_id = M('admin_user')->where("admin_id = $staff_id")->getField('depot_id');


            // 通过cust_id查找商铺的信息
            $shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

            $where = [];
            $where["order_type"] = 2;
            $where["order_id"] = $orderId;
            $where["order_state"] = 2;
            $ao = M("admin_order")->where($where)->find();

            if (!$ao) {
                continue;
            }

            // 添加订单
            $order_info = array();
            $order_info['return_code'] = create_uniqid_code('CO', $staff_id).$i;

            $order_info['repertory_id'] = intval($depot_id);
            $order_info['org_parent_id'] = $org_parent_id; // 经销商
            $order_info['staff_id'] = $ao["admin_id"];
            $order_info['cust_id'] = $cust_id;
            $order_info['cust_name'] = $shopInfo['cust_name'];
            $order_info['cust_contact'] = $shopInfo['contact'];
            $order_info['cust_address'] = $shopInfo['address'];
            $order_info['cust_tel'] = $shopInfo['telephone'];
            $order_info['order_real_money'] = $order_real_money; // 订单实收金额
            $order_info['order_remark'] = "测试数据";
            $order_info['order_way'] = 1;
            $order_info['return_id'] = $orderId; // 预单ID

            $flag = D('DeliverOrder')->addReturnOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_list);

            // 减少车销库存


            // 设置订单已完成
            $where = array();
            $where['order_type'] = 2;
            $where['order_id'] = $orderId;

            M("admin_order")->where($where)->setField('order_state', 3);

            //M("admin_order")->where($where)->delete();

            $presale_id_list[] = $orderId;

        }

        // 修改预单状态为已派送
        if(!empty($presale_id_list)){
            $where = [];
            $where["return_id"] = ["in", $presale_id_list];
            M('presale_return')->where($where)->setField('order_status', 3);
        }
    }

    // 店铺确认下单(调换货)
    public function addCarChangeOrderAction() {


        $order = M("admin_order")->where("order_state=2 AND order_type=3")->select();

        $i=0;
        foreach ($order as $k=>$val) {

            $change = M("presale_change")->where("change_id={$val['order_id']}")->find();

            $cust_id = $change["cust_id"];
            $staff_id = $change["staff_id"];

            $org_parent_id = $change["org_parent_id"];

            $orderId = $change["change_id"];

            $order_real_money = $change["order_total_money"];

            $goods_list = M("presale_change_goods")->where("change_id=".$orderId)->select();

            $goods_in=array();
            $goods_out=array();
            foreach($goods_list as $k=>$v){

                $v["goods_spec"]=$v["goods_sepc"];
                if($v["is_change_in"]){
                    $goods_in[]=$v;
                }else{
                    $goods_out[]=$v;
                }

            }

            // 采单员所在仓库
            $depot_id = M('admin_user')->where('admin_id = ' . $staff_id)->getField('depot_id');


            // 通过cust_id查找商铺的信息
            $shopInfo = M('customer_info')->where("cust_id={$cust_id}")->field('cust_name,contact,telephone,address')->find();

            $where = [];
            $where["order_type"] = 3;
            $where["order_id"] = $orderId;
            $where["order_state"] = 2;
            $ao = M("admin_order")->where($where)->find();

            if (!$ao) {
                continue;
            }

            // 添加订单
            $order_info = array();
            $order_info['change_code'] = create_uniqid_code('CO', $staff_id).$i;
            $i++;

            $order_info['repertory_id'] = intval($depot_id);
            $order_info['org_parent_id'] = $org_parent_id; // 经销商
            $order_info['staff_id'] = $ao["admin_id"];
            $order_info['cust_id'] = $cust_id;
            $order_info['cust_name'] = $shopInfo['cust_name'];
            $order_info['cust_contact'] = $shopInfo['contact'];
            $order_info['cust_address'] = $shopInfo['address'];
            $order_info['cust_tel'] = $shopInfo['telephone'];
            $order_info['order_real_money'] = $order_real_money; // 订单实收金额
            $order_info['order_remark'] = "测试数据";
            $order_info['order_way'] = 1;
            $order_info['change_id'] = $orderId; // 预单ID

            $flag = D('DeliverOrder')->addChangeOrder($staff_id, $org_parent_id, $cust_id, $order_info, $goods_in,$goods_out);


            // 减少车销库存


            // 设置订单已完成
            $where = array();
            $where['order_type'] = 3;
            $where['order_id'] = $orderId;

            M("admin_order")->where($where)->setField('order_state', 3);

            M("admin_order")->where($where)->delete();

            $presale_id_list[] = $orderId;

        }

        // 修改预单状态为已派送
        if(!empty($presale_id_list)){
            $where = [];
            $where["change_id"] = ["in", $presale_id_list];
            M('presale_change')->where($where)->setField('order_status', 3);
        }
    }


    // 添加出库
    private function addDepotOutOrder($id){
        $where["apply_id"] = $id;

        $res=M("car_apply")->where($where)->find();
        $data["send_staff_id"]=0;
        $data["out_type"]=2; // 入库类型
        $data["out_status"]=2;
        $data["out_remark"]=$res["apply_remark"];
        $data["depot_id"]=$res["repertory_id"];
        $data["org_parent_id"]=$res["org_parent_id"];
        //$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":5}]';
        $goods_info=M("car_apply_goods")->field("goods_id,cv_id,apply_num as goods_num")->where($where)->select();

        $data["goods_info"]=$goods_info;


        $data["create_id"]=session("admin_id");

        $res = D("DepotOut")->addDepotOutOrder($data);

        foreach($goods_info as $k=>$v){
            $stockData[$k]["cv_id"]=$v["cv_id"];
            $stockData[$k]["goods_id"]=$v["goods_id"];
            $stockData[$k]["small_stock"]=$v["goods_num"];
            $stockData[$k]["org_parent_id"]=$data["org_parent_id"];
            $stockData[$k]["depot_id"]=$data["depot_id"];
        }

        $msg = queryDepotOutType($data['out_type']);

        D("DepotStock")->updateStock($stockData, $msg, "del", $data['out_type']);
    }


    // 增加库存
    public function addStockGoodsAction() {

        $_num = I("post._num");

        // 获取库存中所有商品
        $goods = M("depot_stock")->select();

        foreach ($goods as $k=>$val) {
            $where = [];
            $where["depot_id"] = $val["depot_id"];
            $where["goods_id"] = $val["goods_id"];
            //$where["org_parent_id"] = $val["goods_id"];

            M("depot_stock")->where($where)->setInc("small_stock",$_num);
        }

    }

    // 二维数组随机取元素
    private function cut_array($arr) {
        $len = mt_rand(1, count($arr)-1);

        // 将数组打乱
        shuffle($arr);

        // 截取数组
        $new_arr = array_slice($arr, -$len);

        return $new_arr;
    }


    private function post_curl($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    private function get_curl($url, $data) {

        $url = $url.'?'.http_bulid_query($data);

        $ch = curl_init();

        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        return $output;
    }



    private function createRandomStr($length){
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
        $strlen = 62;
        while($length > $strlen){
            $str .= $str;
            $strlen += 62;
        }
        $str = str_shuffle($str);
        return substr($str,0,$length);


    }


}