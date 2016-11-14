<?php

/*******************************************************************
 ** 文件名称: PresaleReturnController.class.php
 ** 功能描述: 系统后台预单退货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class PresaleReturnController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        $urlPara = array();
        $urlPara["depot_id"] = I("depot_id");
        $urlPara["staff_id"] = I("staff_id");
        $urlPara["cust"] = I("cust");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");

        $this->assign('urlPara', $urlPara);

        $where = "1=1";

        if($depotID>0) {
            $where .= " AND pr.repertory_id = " . $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where .= " AND pr.repertory_id = " . $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["staff_id"])) {
            $where .= " AND pr.staff_id = " . $urlPara["staff_id"];
        }

        if (!empty($urlPara["cust"])) {
            $where .= " AND pr.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"]) + 24*60*60;
            if ($etime > $stime) {
                $where .= " AND pr.add_time >= " . $stime . " AND pr.add_time <= " . $etime;
            } else {
                $where .= " AND pr.add_time >= " . $stime;
            }
        }
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);
        $total = M('presale_return')->table("zdb_presale_return as pr")->where($where)->count();

        $list = M('presale_return')->alias('pr')
            ->field('pr.*, au.true_name, di.repertory_name')
            ->join('left join __ADMIN_USER__ as au on pr.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pr.repertory_id = di.repertory_id')
            ->where($where)
            ->page($p,$pnum)
            ->order('pr.return_id desc')
            ->select();
            
        foreach($list as $k=>$v){
        	if($v["order_from"]==3){
        		$list[$k]["true_name"]="商城下单";
        	}elseif($v["order_from"]==2){
        		$staff_name=M("org_staff")->where("staff_id=".$v["staff_id"])->getField("staff_name");
        		$list[$k]["true_name"]=$staff_name;
        	}
        	
        	
        }

        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/PresaleChange',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->assign('p', $p);
        $this->assign('pnum', $pnum);

        // 仓库
        $this->assign('depotList', queryDepot($depotID));

        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID,4));

		$this->display();
    }

    // 查看
    public function lookAction() {
        $id = I("id");

        $where["return_id"] = $id;

        if($_SESSION["depot_id"]){
			$where["pr.repertory_id"] = $_SESSION["depot_id"];
		}
        $return = M('presale_return')->alias('pr')
            ->field("pr.*, ci.cust_name, ci.contact, ci.telephone as cust_tel, ci.address as cust_address, au.true_name, oi.org_name, di.repertory_name")
            ->join('left join __CUSTOMER_INFO__ as ci on pr.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on pr.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pr.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on pr.org_parent_id = oi.org_id')
            ->where($where)
            ->order('pr.return_id desc')
            ->find();

        $this->assign("return", $return);
 		if($_SESSION["depot_id"]){
			unset($where["pr.repertory_id"]);
		}
        // 商品
        $goods = M("presale_return_goods")->alias('prg')
            ->field('prg.*, gi.goods_code, gi.goods_name')
            ->join('left join __GOODS_INFO__ as gi on prg.goods_id = gi.goods_id')
            ->where($where)
            ->order('return_id desc')
            ->select();
        if(!$return){
        	unset($goods);
        }
        $this->assign("goods", $goods);


        // 返回页面
        $this->display();
    }

	
	/** 其他Action **/


}

/*************************** end ************************************/