<?php

/*******************************************************************
 ** 文件名称: PlanOrderController.class.php
 ** 功能描述: 经销商PC端预单控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class PlanOrderController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

        $urlPara = array();
        $urlPara["cust"] = I("cust");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $this->assign('urlPara', $urlPara);

	    //预单订单列表
        $where = "org_parent_id=".$_SESSION["org_parent_id"];


        if (!empty($urlPara["cust"])) {
            $where .= " AND po.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"]) + 24*60*60;
            if ($etime > $stime) {
                $where .= " AND po.create_time >= " . $stime . " AND po.create_time <= ". $etime;
            } else {
                $where .= " AND po.create_time >= " . $stime;
            }
        }

        $total = M("car_orders")->alias("po")->where($where)->count();

        $order = M("car_orders")->alias("po")->where($where)->page($p,$pnum)->order("po.order_id desc")->select();

        foreach($order as $k=>$v){
        	$order[$k]["staff_name"]=M("admin_user")->where("admin_id=".$v["staff_id"])->getField("true_name");
        }
        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);

        $this->assign("p",$p);

        $this->assign("pnum",$pnum);

        $this->assign('pagelist',$page);//分页显示

        $this->assign("order", $order);

        //print_r($order);die();

		$this->display();
    }


    // 查看
    public function lookAction() {
        $id = I("id");

        $where["order_id"] = $id;

        $order = M('car_orders')->alias('po')
            ->field("po.*, au.true_name, di.repertory_name")
            ->join('left join __ADMIN_USER__ as au on po.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on po.repertory_id = di.repertory_id')
            ->where($where)
            ->order('po.order_id desc')
            ->find();
        
        $this->assign("order", $order);

        // 商品
        $goods = M("car_orders_goods")->alias('pog')
            ->field('pog.*, gi.goods_code, gi.goods_name')
            ->join('left join __GOODS_INFO__ as gi on pog.goods_id = gi.goods_id')
            ->where($where)
            ->order('order_id desc')
            ->select();

        $this->assign("goods", $goods);
//      var_dump($order);
//      var_dump($goods);
        // 返回页面
        $this->display();
    }


	/** 其他Action **/


}

/*************************** end ************************************/