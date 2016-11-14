<?php

/*******************************************************************
 ** 文件名称: PresaleMallController.class.php
 ** 功能描述: 系统后台预单商城下单控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class PresaleMallController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
	// 控制器默认页, 登录页面
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        $urlPara = array();
        $urlPara["depot_id"] = I("depot_id");
        $urlPara["cust"] = I("cust");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");
        $this->assign('urlPara', $urlPara);

        $where = "1=1";

        if($depotID>0) {
            $where .= " AND co.repertory_id = " . $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where .= " AND co.repertory_id = " . $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["cust"])) {
            $where .= " AND co.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"]) + 24*60*60;
            if ($etime > $stime) {
                $where .= " AND co.add_time >= " . $stime . " AND co.add_time <= " . $etime;
            }else {
                $where .= " AND co.add_time >= " . $stime;
            }
        }

        $total = M('customer_orders')->alias('co')->where($where)->count();

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $list = M('customer_orders')->alias('co')
            ->field('co.*, ci.contact, di.repertory_name, oi.org_name')
            ->join('left join __CUSTOMER_INFO__ as ci on co.cust_id = ci.cust_id')
            ->join('left join __DEPOT_INFO__ as di on co.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on co.org_parent_id = oi.org_id')
            ->where($where)
            ->order('co.order_id desc')
            ->select();

        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/PresaleMall',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->assign('pnum',$pnum);
        $this->assign('p',$p);

        // 仓库
        $this->assign('depotList', queryDepot($depotID));

        $this->display();
    }


    // 查看
    public function lookAction() {
        $id = I("id");

        $where["order_id"] = $id;

        $list = M('customer_orders')->alias('co')
            ->field('co.*, ci.cust_name, ci.contact, di.repertory_name, oi.org_name')
            ->join('left join __CUSTOMER_INFO__ as ci on co.cust_id = ci.cust_id')
            ->join('left join __DEPOT_INFO__ as di on co.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on co.org_parent_id = oi.org_id')
            ->where($where)
            ->order('co.order_id desc')
            ->find();

        $this->assign("list", $list);


        // 商品
        $goods = M("customer_orders_goods")->alias('cog')
            ->field('cog.*, gi.goods_code')
            ->join('left join __GOODS_INFO__ as gi on cog.goods_id = gi.goods_id')
            ->where($where)
            ->order('order_id desc')
            ->select();

        $this->assign("goods", $goods);

        //print_r($goods);

        // 返回页面
        $this->display();
    }

	/** 其他Action **/


}

/*************************** end ************************************/