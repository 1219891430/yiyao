<?php
/*******************************************************************
 ** 文件名称: CycleController.class.php
 ** 功能描述: 经销商业务员拜访周期控制器
 ** 创建人员:
 ** 创建日期: 2016-09-26
 *******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CycleController extends Controller {

    public $conf;

    public function __construct()
    {
        parent::__construct();
        $this->conf = CONF_PATH."/cycle.json";
    }

    public function indexAction() {

        //人员列表
        $staffList = M("OrgStaff")->field("staff_id,staff_name")->where("org_parent_id=" . session("org_parent_id")." and role_id =3")->select();

        $this->assign("staff_list", $staffList);

        $org_id = session("org_parent_id");
        $staff_id = I("staffId");

        $where = [];
        $where['org_parent_id'] = $org_id;
        $where["role_id"] = 3;
        if ($staff_id > 0) {
            $where["staff_id"] = $staff_id;
        }

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum=I("get.pnum",10);
        $total= M("org_staff")->where($where)->count(); //总数

        $staff = M("org_staff")->where($where)->page($p, $pnum)->select();

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('pagelist', $page);
        $this->assign('pnum', $pnum);
        $this->assign('staff_id', $_GET["staffId"]);

        $this->assign("staffs", $staff);

        $this->display();
    }

    // 设置线路
    public function settingAction() {
        $org_id = session("org_parent_id");

        $param = [];

        $staff_id = I("staff_id");

        $param["staff_id"] = $staff_id;

        $index = I("index");

        $param["index"] = $index;

        $this->assign("param", $param);

        $custs = $this->get_cust_by_days($staff_id, $index);

        $this->assign("custs", $custs);

        $staff = M("org_staff")->where("staff_id=$staff_id")->find();

        $this->assign("staff", $staff);

        $this->display();
    }

    // 地图
    public function mapAction () {
        $org_id = session("org_parent_id");
        $staff_id = I("staff_id");
        $this->assign('staff_id', $staff_id);
        $index = I("index");
        // 线路商铺
        $shop_list = array();
        $cust_ids = M('cycle')
            ->where("`org_id`=$org_id AND `staff_id`=$staff_id AND `index`=$index")
            ->getField("cust_ids");

        if ($cust_ids) {
            $where = [];
            $where["cust_id"] = array('in', $cust_ids);
            $where["is_close"] = 0;
            //$where["is_check"] = 1;
            $shop_list = M("customer_info")->where($where)->select();
        }



        $this->assign('shops', $shop_list);


        $this->display();
    }

    // 删除
    public function delAction() {
        $custs = I("custs");

        $org_id = session("org_parent_id");

        $staff_id = I("staff_id");
        $index = I("index");

        $where = [];
        $where["org_id"] = $org_id;
        $where["staff_id"] = $staff_id;
        $where["index"] = $index;

        // 获取已经选中的商铺
        $cust_ids = M("cycle")->where($where)->getField("cust_ids");

        $cust_arr = explode(",", $cust_ids);

        $new_arr = array_diff($cust_arr, $custs);

        $new_str = "";
        if (!empty($new_arr)) {
            $new_str = implode(",", $new_arr);
        }

        $new_str = rtrim($new_str, ",");
        $new_str = ltrim($new_str, ",");

        $data["cust_ids"] = $new_str;

        $succ = M("cycle")->where($where)->save($data);

        if ($succ){
            echo 1;
        } else {
            echo 0;
        };



    }

    public function add_custAction() {

        $param = [];

        $org_id = session("org_parent_id");

        $staff_id = I("staff_id");
        $index = I("index");

        $cust_name = I("cust_name");

        $param["staff_id"] = $staff_id;
        $param["index"] = $index;

        $where = [];
        $where["oc.org_parent_id"] = $org_id;
        $where["is_close"] = 0;

        if ($cust_name) {
            $param["cust_name"] = $cust_name;
            $where["c.cust_name"] = array('like', "%$cust_name%");
        }

        // 获取已经选中的商铺
        $cust_ids = M("cycle")->where("org_id=$org_id AND staff_id=$staff_id AND `index`=$index")->getField("cust_ids");

        if (empty($cust_ids)) {
            $cust_ids = "0";
        }

        $where["oc.shop_id"] = array('not in', $cust_ids);

        $where['osc.staff_id'] = $staff_id;


        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum=I("get.pnum",10);
        $total= $custs = M('org_customer')->alias('oc')
            ->join('__CUSTOMER_INFO__ as c on oc.shop_id = c.cust_id')
            ->join("left join __ORG_STAFF_CUSTOMER__ as osc on osc.shop_id=oc.shop_id")
            ->where($where)->count(); //总数

        // 所有商铺
        $custs = M('org_customer')->alias('oc')->field('c.*')
            ->join('__CUSTOMER_INFO__ as c on oc.shop_id = c.cust_id')
            ->join("left join __ORG_STAFF_CUSTOMER__ as osc on osc.shop_id=oc.shop_id")
            ->page($p, $pnum)
            ->where($where)->order('c.cust_id asc')->select();

        $this->assign("custs", $custs);

        //print_r($custs);die;

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('pagelist', $page);
        $this->assign('pnum', $pnum);

        $this->assign("param", $param);


        $this->display();
    }

    public function add_custxAction() {
        $org_id = session("org_parent_id");
        $staff_id = I("post.staff_id");
        $index = I("post.index");

        $custs = I("post.custs");

        $where = [];
        $where["org_id"] = $org_id;
        $where["staff_id"] = $staff_id;
        $where["index"] = $index;

        $cust_ids = M("cycle")->where($where)->getField("cust_ids");

        $new_custs_arr = array_unique(array_merge(explode(",", $cust_ids), $custs));

        $new_cust = implode(",", $new_custs_arr);

        $new_cust = rtrim($new_cust, ",");
        $new_cust = ltrim($new_cust, ",");

        $data["cust_ids"] = $new_cust;

        // 写入
        $succ = M("cycle")->where($where)->save($data);

       if ($succ){
           echo 1;
       } else {
           echo 0;
       };

    }

    // 获取经销商下所有店铺
    public function getShopsAction() {
        $org_id = session("org_parent_id");
        $staff_id = I("staff_id");
        // 所有商铺
        $shop_list = M('org_customer')->alias('oc')->field('c.*')
            ->join('left join __CUSTOMER_INFO__ as c on oc.shop_id = c.cust_id')
            ->join("left join __ORG_STAFF_CUSTOMER__ as osc on osc.shop_id=oc.shop_id")
            ->where("oc.org_parent_id=$org_id AND osc.staff_id=$staff_id")->order('c.cust_id asc')->select();

        $this->ajaxReturn($shop_list);
    }

    //
    public function getShopsByIdsAction() {
        $ids = I("ids",0);

        $where["cust_id"] = array("in", $ids);
        $where["is_close"] = 0;
        $custs = M("customer_info")
            ->field("cust_id, cust_name, contact, telephone, province, city, district, address, longitude, dimension")
            ->where($where)->select();

        $this->ajaxReturn($custs);
    }


    // 查看线路
    public function cycleAction() {

        $org_id = session("org_parent_id");
        $staff_id = I("staff_id");

        $this->assign("staff_id", $staff_id);

        $setting = file_get_contents($this->conf);
        if (!$setting) {
            echo "<script>alert('需要先设置周期天数！');window.location='./';</script>"; exit;
        }
        $set = json_decode($setting, true);

        $cycle_times = $set["cycle_$org_id"];
        if (empty($cycle_times)) {
            echo "<script>alert('需要先设置周期天数！');window.location='./';</script>"; exit;
        }

        $this->assign("cycle_times", $cycle_times);

        $cycle = $this->get_cycle($staff_id, $cycle_times);

        //$cycle = $this->handy_display($cycle,$cycle_times);

        //print_r($cycle);die();

        $this->assign("cycle", $cycle);

        $max = 0;
        foreach ($cycle as $val) {
            if($val["count"] > $max) {
                $max = $val["count"];
            }
        }

        $this->assign("max", $max);

        $custs = $this->custByDay($cycle, $cycle_times);

        $this->assign("custs", $custs);

        $this->display();
    }

    // 设置周期天数
    public function set_cycleAction() {
        $org_id = session("org_parent_id");

        if (IS_GET) {
            // 获取周期
            $setting = file_get_contents($this->conf);
            $set = json_decode($setting, true);

            $org_set = $set["cycle_$org_id"] ? $set["cycle_$org_id"] : 7;


            $this->assign("cycle_times", $org_set);

            $this->display();
        }

        if (IS_POST) {
            $cycle_times = I("post.cycle_times");
            // 写入配置文件

            $this->set_cycle($org_id, $cycle_times);

            $this->redirect("./Cycle");
        }

    }

    private function set_cycle($org_id, $cycle_times) {
        $set = ["cycle_$org_id" => $cycle_times];
        $succ = true;
        if (!file_exists($this->conf)) {

            $succ = file_put_contents($this->conf, json_encode($set));
        } else {
            $setting = file_get_contents($this->conf);

            $set_arr = json_decode($setting, true);

            if (empty($set_arr)) {
                $set_arr = [];
            }

            if (array_key_exists("cycle_$org_id", $set_arr)) {
                $set_arr["cycle_$org_id"] = $cycle_times;
            } else {
                $set_arr = array_merge($set_arr,$set);
            };

            $succ = file_put_contents($this->conf, json_encode($set_arr));
        }

        return $succ;
    }

    // 业务员所有线路及线路店铺信息
    private function get_cycle($staff_id, $cycle_times) {
        $org_id = session("org_parent_id");

        $cycle = M("cycle")->where("`staff_id`=$staff_id AND `org_id`=$org_id")->order("`index` asc")->limit($cycle_times)->select();

        $cust_ids = [];

        foreach ($cycle as $val) {

            $c_ids = explode(",", $val["cust_ids"]);

            $cust_ids = array_merge($cust_ids, $c_ids);

        }

        $cust_ids = array_unique($cust_ids);
        if (empty($cust_ids)) {
            $cust_ids = [0];
        }

        // 获取所有店铺
        $where = [];
        $where["cust_id"] = array("in", $cust_ids);
        $custs = M("customer_info")
            ->field("cust_id,cust_name,contact,telephone")
            ->where($where)
            ->select();

        $cycle_by_index = [];
        foreach ($cycle as &$val) {

            $c_ids = explode(",", $val["cust_ids"]);
            $count = 0;
            if(!empty($val["cust_ids"])){
                $count = sizeof($c_ids);
            }

            $val["count"] = $count;

            foreach ($custs as $cust) {
                if (in_array($cust["cust_id"], $c_ids)) {
                    $val["cust"][] = $cust;
                }
            }

            $cycle_by_index[$val["index"]] = $val;
        }

        return $cycle_by_index;
    }

    // 按天数分类所有店铺和店铺总数
    private function custByDay($cycle, $times) {
        $byday = [];
        $count = [];
        for ($i=1; $i<=$times; $i++) {
            $byday[$i] = [];
            $count[$i] = 0;
        }

        foreach ($cycle as $key => $val) {
            $byday[$val["index"]] = $val["cust"];
            $count[$val["index"]] = $val["count"];
        }

        return ["count"=>$count, "custs"=>$byday];
    }

    // 根据天数获取店铺信息
    private function get_cust_by_days($staff_id, $index) {
        $org_id = session("org_parent_id");

        $where = [];
        $where["org_id"] = $org_id;
        $where["staff_id"] = $staff_id;
        $where["index"] = $index;
        $cust = M('cycle')->where($where)->find();

        if (!$cust) {
            $data = [
                "org_id"=>$org_id,
                "staff_id"=>$staff_id,
                "index"=>$index,
                "cust_ids"=>"",
            ];

            M('cycle')->add($data);
        }

        $where = [];
        $where["is_close"] = 0;
        if (empty($cust['cust_ids'])) {
            $cust['cust_ids'] = "0";
        }
        $where["cust_id"] = array("in", $cust['cust_ids']);
        $custs = M("customer_info")->where($where)->select();

        return $custs;
    }


    public function into_cycleAction() {

        if(IS_POST){
            $staffId1 = I('staffId1');
            $staffId2 = I('staffId2');
            $clearFrom = I('clearFrom');

            if( empty($staffId1) || empty($staffId2)  || ( $staffId1 == $staffId2) ){
                alertToUrl('来源业务员和目标业务员参数错误','./Cycle');
                return;
            }

            $where1['org_id'] = session('org_parent_id');
            $where1['staff_id'] = $staffId1;
            $fromArr = M('cycle')->where($where1)->select();

            //清空目标业务员的数据
            $where2['org_id'] = session('org_parent_id');
            $where2['staff_id'] = $staffId2;
            M('cycle')->where($where2)->delete();


            $intoArr = array();
            foreach($fromArr as $k=>$v){
                $row = $v;
                $row['staff_id'] = $staffId2;
                $intoArr[] = $row;
            }
            M('cycle')->addAll($intoArr);

            //是否需要清空来源业务员的数据
            if($clearFrom == '1'){
                M('cycle')->where($where1)->delete();
            }
            alertToUrl('数据导入成功','./');

        }
        else{
            //人员列表
            $staffList = M("OrgStaff")->field("staff_id,staff_name")->where("org_parent_id=" . session("org_parent_id")." and role_id =3")->select();
            $this->assign("staff_list", $staffList);
            $this->display();
        }
    }

}