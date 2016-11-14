<?php

/*******************************************************************
 ** 文件名称: PresaleChangeController.class.php
 ** 功能描述: 系统后台预单调换货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class PresaleChangeController extends BaseController {

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

        $where = " 1=1 ";


        if($depotID>0) {
            $where .= " AND pc.repertory_id = " . $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where .= " AND pc.repertory_id = " . $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["staff_id"])) {
            $where .= " AND pc.staff_id = " . $urlPara["staff_id"];
        }

        if (!empty($urlPara["cust"])) {
            $where .= " AND ci.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"]) + 24*60*60;
            if ($etime > $stime) {
                $where .= " AND pc.add_time >= " . $stime . " AND pc.add_time <= " . $etime;
            }else {
                $where .= " AND pc.add_time >= " . $stime;
            }
        }

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $total =  M('presale_change')->alias('pc')
            ->field('pc.*, ci.cust_name, ci.contact, au.true_name')
            ->join('left join __CUSTOMER_INFO__ as ci on pc.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on pc.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pc.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on pc.org_parent_id = oi.org_id')
            ->where($where)->count();

        $list = M('presale_change')->alias('pc')
            ->field('pc.*, ci.cust_name, ci.contact, au.true_name')
            ->join('left join __CUSTOMER_INFO__ as ci on pc.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on pc.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pc.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on pc.org_parent_id = oi.org_id')
            ->where($where)
            ->page($p, $pnum)
            ->order('pc.change_id desc')
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

        $this->assign('pnum',$pnum);
        $this->assign('p',$p);

        // 仓库
        $this->assign('depotList', queryDepot($depotID));

        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID,4));

        $this->display();
    }

    // 查看
    public function lookAction() {
        $id = I("id");

        if($_SESSION["depot_id"]){
			$where["pc.repertory_id"] = $_SESSION["depot_id"];
		}
        
        $where["change_id"] = $id;

        $order = M('presale_change')->alias('pc')
            ->field("pc.*, ci.cust_name, ci.contact, ci.telephone, ci.address, au.true_name, oi.org_name, di.repertory_name")
            ->join('left join __CUSTOMER_INFO__ as ci on pc.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on pc.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pc.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on pc.org_parent_id = oi.org_id')
            ->where($where)
            ->order('pc.change_id desc')
            ->find();

        $this->assign("order", $order);

         if($_SESSION["depot_id"]){
			unset($where["pc.repertory_id"]);
		}
        // 商品
        $goods = M("presale_change_goods")->alias('pcg')
            ->field('pcg.*, gi.goods_code, gi.goods_name')
            ->join('left join __GOODS_INFO__ as gi on pcg.goods_id = gi.goods_id')
            ->where($where)
            ->select();
        $goods_in=array();
        $goods_out=array();
        foreach ($goods as $k=> $v) {
        	if($v["is_change_in"]){
        		$goods_in[]=$v;
        	}else{
        		$goods_out[]=$v;
        	}
        	
        }
        if(!$order){
        	unset($goods_in);
        	unset($goods_out);
        }
        $this->assign("goods_in", $goods_in);
        $this->assign("goods_out", $goods_out);

        // 打印单据
        if(I("get.print") == 1) {
            $xmlData = "<xml>";
            //	将时间格式"Y-m-d H:i:s" 改为'Y-m-d' auth:llx
            $this->assign('ptime',date("Y-m-d"));

            /**********************************************************************/
            $xmlData .= "<Parameter>";
            $xmlData .= "<Dealer>".$order['org_name']."</Dealer>";
            $xmlData .= "<HotLine>".$order['telephone']."</HotLine>";
            $xmlData .= "<ContactMan>".$order['contact']."</ContactMan>";
            $xmlData .= "<OrderDate>".date("Y-m-d H:i:s",$order['add_time'])."</OrderDate>";
            $xmlData .= "<OrderCode>".$order['change_code']."</OrderCode>";
            $xmlData .= "<Creater>".$_SESSION['true_name']."</Creater>";
            $xmlData .= "<ShopName>".$order['cust_name']."</ShopName>";
            $xmlData .= "<Contact>".$order['contacts']."</Contact>";
            $xmlData .= "<Telephone>".$order['cust_tel']."</Telephone>";
            $xmlData .= "<Address>".$order['cust_address']."</Address>";
            $xmlData .= "<Repository>".$order['store']."</Repository>";
            $xmlData .= "<CRemark>".$order['order_remark']."</CRemark>";
            $xmlData .= "<PayStyle>".$order['order_way']."</PayStyle>";
            $xmlData .= "<OrderTotal>".number_format($order["order_total_money"],2,'.','')."</OrderTotal>";
            $xmlData .= "<Saleman>".$order['true_name']."</Saleman>";
            $xmlData .= "<SendDate>".date("Y-m-d H:i:s")."</SendDate>";
            $xmlData .= "<Consignee></Consignee>";
            //$xmlData .= "<Message>".$message."</Message>";
            $xmlData .= "</Parameter>";
            $xmlData .= "</xml>";
            /**********************************************************************/

            if($order['order_type'] == 3) {
                $this->assign('templateFile', __ROOT__ . '/Public/js/print/template/PlanOrder2.grf');
            }
            else {
                $this->assign('templateFile', __ROOT__ . '/Public/js/print/template/PlanOrder.grf');
            }
            $this->assign('xmlData', $xmlData);

            $this->assign('puser',session("staff_name"));
            $this->display("prints");
            exit;
        }

        // 返回页面
        $this->display();
    }


	
	/** 其他Action **/


}

/*************************** end ************************************/