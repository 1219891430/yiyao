<?php

/*******************************************************************
 ** 文件名称: CarsaleLineController.class.php
 ** 功能描述: 业务员端线路接口
 ** 创建人员:
 ** 创建日期: 2016-09-28
 *******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleLineController extends Controller {

    public $conf;

    public function __construct(){
        header('Access-Control-Allow-Origin:*');
        parent::__construct();
        $this->conf = CONF_PATH."/cycle.json";
    }

    /**
     * @name 线路列表
     * @method post
     * @uri /index.php/Mobile/CarsaleLine/lineList
     * @param org_id int 经销商ID
     * @param staff_id int 业务员ID
     * @response visit_time string 线路最后设置时间
     * @response cust_num int 线路上店铺数量
     * @response index int 线路索引
     * @response dif_days int 线路没有拜访天数
     */
    public function lineListAction() {

        $res = [
            'error' => 1,
            'msg' => '失败',
            'data' =>array()
        ];

        $staff_id = I("staff_id");
        $org_id = I("org_id");

        $setting = file_get_contents($this->conf);
        if (!$setting) {
            $res['msg']="未找到线路";
            $res['error']= 1001;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }
        $set = json_decode($setting, true);

        $cycle_times = $set["cycle_$org_id"];
        if (empty($cycle_times)) {
            $res['msg']="未找到线路";
            $res['error']= 1002;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }

        $where = [];
        $where["org_id"] = $org_id;
        $where["staff_id"] = $staff_id;

        // 获取线路列表
        $cycle = M("cycle")->where($where)->order("`index` asc")->limit($cycle_times)->select();

        if (!$cycle) {
            $res['msg']="未找到线路";
            $res['error']= 1003;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }

        $data = [];
        foreach ($cycle as $val) {

            if ($val["cust_ids"]) {
                $c_ids = explode(",", $val["cust_ids"]);
                $cust_num = count($c_ids);
            } else {
                $cust_num = 0;
            }



            // 计算多少天没拜访
            $today = strtotime(date("Y-m-d"));
            if(empty($val["visit_time"])){
                $dif_days = -1;
            } else {

                $dif_time = $today - $val["visit_time"];

                if ($dif_time < 0) {
                    $dif_days = 0;
                } else {
                    $dif_days = ceil($dif_time / 86400);
                }
            }

            $data[] = [
                "visit_time" => date("Y-m-d H:i:s", $val["visit_time"]),
                "cust_num" => $cust_num,
                "index" => $val["index"],
                "dif_days" => $dif_days
            ];
        }


        $res = [
            'error' => -1,
            'msg' => '成功',
            'data' =>$data,
        ];

        $this->ajaxReturn($res, "JSON");

    }


    /**
     * @name 获取线路下的店铺列表
     * @method post
     * @uri /index.php/Mobile/CarsaleLine/shopList
     * @param index int 线路索引
     * @param org_id int 经销商ID
     * @param staff_id int 业务员ID
     * @response id int 店铺ID
     * @response name string 店铺名称
     * @response boss string 店主姓名
     * @response tel string 店铺电话
     * @response address string 店铺地址
     * @response longitude string 店铺经度
     * @response latitude string 店铺纬度
     * @response signTime string 上次拜访时间
     * @response notTo int 多少天未拜访
     * @response typeName string 店铺类型
     * @response picture string 店铺门头图片
     */
    public function shopListAction() {
        $index = I("index");
        $org_id = I("org_id");
        $staff_id = I("staff_id");

        $setting = file_get_contents($this->conf);
        if (!$setting) {
            $res['msg']="未找到线路";
            $res['error']= 2001;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }
        $set = json_decode($setting, true);

        $cycle_times = $set["cycle_$org_id"];

        if (empty($cycle_times)) {
            $res['msg']="未找到线路";
            $res['error']= 2002;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }

        $where = [];
        $where["org_id"] = $org_id;
        $where["index"] = $index;
        $where["staff_id"] = $staff_id;

        // 获取线路列表
        $cycle = M("cycle")->where($where)->find();
        if (!$cycle) {
            $res['msg']="未找到线路";
            $res['error']= 2003;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }

        $cust_ids = explode(",", $cycle["cust_ids"]);

        // 获取所有店铺
        $where = [];
        $where["cust_id"] = array("in", $cust_ids);
        $custs = M("customer_info")->where($where)->select();

        if (!$custs) {
            $res['msg']="未找到店铺";
            $res['error']= 2004;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }

        // 格式化店铺列表
        $shopList = array();

        for($i=0; $i<count($custs); $i++)
        {
            // 店铺基本信息
            $shopList[$i]['id'] = $shopID = $custs[$i]["cust_id"];
            $shopList[$i]['name'] = $custs[$i]["cust_name"];
            $shopList[$i]['boss'] = $custs[$i]["contact"];
            $shopList[$i]['tel'] = $custs[$i]["telephone"];
            $shopList[$i]['address'] = $custs[$i]["address"];
            $shopList[$i]['longitude'] = empty($custs[$i]["longitude"]) ? "0" : $custs[$i]["longitude"];
            $shopList[$i]['latitude'] = empty($custs[$i]["dimension"]) ? "0" : $custs[$i]["dimension"];

            // 拜访记录
            $signTime = M('customer_weihu')->where("saleman_id = $staff_id and shop_id = $shopID")->order("add_time desc")->getField('add_time');
            if(empty($signTime))
            {
                $shopList[$i]['signTime']="暂无拜访记录";
                $shopList[$i]['notTo'] = -1; //
            }
            else
            {
                $shopList[$i]['signTime'] = date("Y-m-d H:i:s", $signTime);
                $shopList[$i]['notTo'] = (int)floor((time()-strtotime(date('Y-m-d 00:00:00', $signTime)))/(24*60*60));
            }

            // 店铺类型
            $typeName = M("customer_type")->field("ct_name")->where("ct_id=" . intval($custs[$i]["shop_type"]))->find();
            $shopList[$i]["typeName"] = !empty($typeName["ct_name"]) ? $typeName["ct_name"]  : '';

            // 门头照片
            $link = DOMAIN . "Public/Uploads/" . $custs[$i]['head_pic'];
            $shopList[$i]['picture'] = empty($custs[$i]["head_pic"]) ? "" : $link;
        }

        $res = [
            'error' => -1,
            'msg' => '成功',
            'data' =>$shopList,
        ];

        $this->ajaxReturn($res);

    }


    /**
     * @name 店铺搜索
     * @method post
     * @uri /index.php/Mobile/CarsaleLine/shopSearch
     * @param staff_id int 业务员ID
     * @param org_id int 经销商ID
     * @param keyword string 搜索关键词（店铺名称或手机号）
     * @response id int 店铺ID
     * @response name string 店铺名称
     * @response boss string 店主姓名
     * @response tel string 店铺电话
     * @response address string 店铺地址
     * @response longitude string 店铺经度
     * @response latitude string 店铺纬度
     * @response signTime string 上次拜访时间
     * @response notTo int 多少天未拜访
     * @response typeName string 店铺类型
     * @response picture string 店铺门头图片
     **/
    public function shopSearchAction() {
        $org_id = I("org_id");
        $staff_id = I("staff_id");
        $keyword = I("keyword");

        $setting = file_get_contents($this->conf);
        if (!$setting) {
            $res['msg']="未找到线路";
            $res['error']= 3001;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }
        $set = json_decode($setting, true);

        $cycle_times = $set["cycle_$org_id"];
        if (empty($cycle_times)) {
            $res['msg']="未找到线路";
            $res['error']= 3002;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }

        $cycle = M("cycle")->where("`staff_id`=$staff_id AND `org_id`=$org_id")->order("`index` asc")->limit($cycle_times)->select();

        $cust_ids = [];

        foreach ($cycle as $val) {

            $c_ids = explode(",", $val["cust_ids"]);

            $cust_ids = array_merge($cust_ids, $c_ids);

        }

        $cust_ids = array_unique($cust_ids);

        $_ids = implode(",", $cust_ids);

        $_ids = rtrim($_ids, ",");
        $_ids = ltrim($_ids, ",");

        // 获取所有店铺
        $where = "cust_id in ($_ids) AND (cust_name like '%$keyword%' OR telephone like '%$keyword%')";

        $custs = M("customer_info")->where($where)->select();

        //print_r($custs);die();

        if (!$custs) {
            $res['msg']="未找到店铺";
            $res['error']= 3003;
            $this->ajaxReturn($res, 'JSON');
            exit();
        }


        // 格式化店铺列表
        $shopList = array();

        for($i=0; $i<count($custs); $i++)
        {
            // 店铺基本信息
            $shopList[$i]['id'] = $shopID = $custs[$i]["cust_id"];
            $shopList[$i]['name'] = $custs[$i]["cust_name"];
            $shopList[$i]['boss'] = $custs[$i]["contact"];
            $shopList[$i]['tel'] = $custs[$i]["telephone"];
            $shopList[$i]['address'] = $custs[$i]["address"];
            $shopList[$i]['longitude'] = empty($custs[$i]["longitude"]) ? "0" : $custs[$i]["longitude"];
            $shopList[$i]['latitude'] = empty($custs[$i]["dimension"]) ? "0" : $custs[$i]["dimension"];

            // 拜访记录
            $signTime = M('customer_weihu')->where("saleman_id = $staff_id and shop_id = $shopID")->order("add_time desc")->getField('add_time');
            if(empty($signTime))
            {
                $shopList[$i]['signTime']="暂无拜访记录";
                $shopList[$i]['notTo'] = -1; //
            }
            else
            {
                $shopList[$i]['signTime'] = date("Y-m-d H:i:s", $signTime);
                $shopList[$i]['notTo'] = (int)floor((time()-strtotime(date('Y-m-d 00:00:00', $signTime)))/(24*60*60));
            }

            // 店铺类型
            $typeName = M("customer_type")->field("ct_name")->where("ct_id=" . intval($custs[$i]["shop_type"]))->find();
            $shopList[$i]["typeName"] = !empty($typeName["ct_name"]) ? $typeName["ct_name"]  : '';

            // 门头照片
            $link = DOMAIN . "Public/Uploads/" . $custs[$i]['head_pic'];
            $shopList[$i]['picture'] = empty($custs[$i]["head_pic"]) ? "" : $link;
        }

        $res = [
            'error' => -1,
            'msg' => '成功',
            'data' =>$shopList,
        ];

        $this->ajaxReturn($res);

    }


    /**
     * @name 设置线路拜访时间
     * @method post
     * @uri /index.php/Mobile/CarsaleLine/setVisitTime
     * @param org_id int 经销商ID
     * @param staff_id int 业务员ID
     * @param index int 线路索引
     * @response index int 线路索引
     * @response visit_time string 设置的时间
     */
    public function setVisitTimeAction() {
        $staff_id = I("staff_id");
        $org_id = I("org_id");
        $index = I("index");

        // 检查今天是否已经设置
        $start = strtotime(date("Y-m-d"));

        $where = [];
        $where["org_id"] = $org_id;
        $where["staff_id"] = $staff_id;
        $where["visit_time"] = array("gt", $start);

        // 获取线路列表
        $cycle = M("cycle")->where($where)->order("`index` asc")->find();

        if ($cycle) {
            $res = [
                'error' => 4001,
                'msg' => '禁止重复设置线路',
                'data' => [
                    'index'=>$cycle["index"],
                    'visit_time'=>date("Y-m-d H:i:s", $cycle["visit_time"])
                ]
            ];

            $this->ajaxReturn($res, "JSON");
            exit();
        } else {
            $data["visit_time"] = time();
            $succ = M("cycle")->where("org_id=$org_id AND staff_id=$staff_id AND `index`=$index")->save($data);
            if ($succ === false) {
                $res = [
                    'error' => 4002,
                    'msg' => '设置失败',
                    'data' => []
                ];

                $this->ajaxReturn($res, "JSON");
                exit();
            }

            $res = [
                'error' => -1,
                'msg' => '设置成功',
                'data' => [
                    'index'=>$index,
                    'visit_time'=>date("Y-m-d H:i:s", $data["visit_time"])
                ]
            ];

            $this->ajaxReturn($res, "JSON");
        }

    }
}