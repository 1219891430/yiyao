<?php

/*******************************************************************
 ** 文件名称: PlanReturnController.class.php
 ** 功能描述: 经销商PC端预单终端退货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class PlanReturnController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
    public function indexAction(){
        $urlPara = array();
        $urlPara["cust"] = I("cust");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");

        $this->assign('urlPara', $urlPara);

        $where = "org_parent_id=".$_SESSION["org_parent_id"];

        if (!empty($urlPara["cust"])) {
            $where .= " AND pr.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"]) + 24*60*60;
            if ($etime > $stime) {
                $where .= " AND pr.create_time >= " . $stime . " AND pr.create_time <= " . $etime;
            } else {
                $where .= " AND pr.create_time >= " . $stime;
            }
        }

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $total =  M('car_return')->alias('pr')
            ->field('pr.*, au.true_name, di.repertory_name')
            ->join('left join __ADMIN_USER__ as au on pr.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pr.repertory_id = di.repertory_id')
            ->where($where)->count();

        $list = M('car_return')->alias('pr')
            ->field('pr.*, au.true_name, di.repertory_name')
            ->join('left join __ADMIN_USER__ as au on pr.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pr.repertory_id = di.repertory_id')
            ->where($where)
            ->page($p,$pnum)
            ->order('pr.return_id desc')
            ->select();

        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/PlanReturn',$urlPara) );

        $this->assign("p",$p);

        $this->assign("pnum",$pnum);

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->display();
    }

    // 查看
    public function lookAction() {
        $id = I("id");

        $where["return_id"] = $id;

        $return = M('car_return')->alias('pr')
            ->field("pr.*, au.true_name, di.repertory_name")
            ->join('left join __ADMIN_USER__ as au on pr.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pr.repertory_id = di.repertory_id')
            ->where($where)
            ->order('pr.return_id desc')
            ->find();

        $this->assign("return", $return);

        // 商品
        $goods = M("car_return_goods")->alias('prg')
            ->field('prg.*, gi.goods_code, gi.goods_name')
            ->join('left join __GOODS_INFO__ as gi on prg.goods_id = gi.goods_id')
            ->where($where)
            ->order('return_id desc')
            ->select();

        $this->assign("goods", $goods);


        // 返回页面
        $this->display();
    }


	/** 其他Action **/


}

/*************************** end ************************************/