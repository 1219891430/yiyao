<?php

/*******************************************************************
 ** 文件名称: PlanChangeController.class.php
 ** 功能描述: 经销商PC端预单调换货控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class PlanChangeController extends Controller {

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
            $where .= " AND pc.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"]) + 24*60*60;
            if ($etime > $stime) {
                $where .= " AND pc.create_time >= " . $stime . " AND pc.create_time < ". $etime;
            } else {
                $where .= " AND pc.create_time >= " . $stime;
            }
        }

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $total = M('car_change')->alias('pc')
            ->field('pc.*, au.true_name')
            ->join('left join __ADMIN_USER__ as au on pc.staff_id = au.admin_id')
            ->where($where)->count();

        $list = M('car_change')->alias('pc')
            ->field('pc.*, au.true_name')
            ->join('left join __ADMIN_USER__ as au on pc.staff_id = au.admin_id')
            ->where($where)
            ->page($p, $pnum)
            ->order('pc.change_id desc')
            ->select();


        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/PlanChange',$urlPara) );

        $this->assign("p",$p);

        $this->assign("pnum",$pnum);

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        //print_r($list);die();


        $this->display();
    }

    // 查看
    public function lookAction() {
        $id = I("id");

        $where["change_id"] = $id;

        $order = M('car_change')->alias('pc')
            ->field("pc.*, ci.cust_name, ci.contact, ci.telephone, ci.address, au.true_name, oi.org_name, di.repertory_name")
            ->join('left join __CUSTOMER_INFO__ as ci on pc.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on pc.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on pc.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on pc.org_parent_id = oi.org_id')
            ->where($where)
            ->order('pc.change_id desc')
            ->find();

        $this->assign("order", $order);

        // 商品
        $goods = M("car_change_goods")->alias('pcg')
            ->field('pcg.*, gi.goods_code, gi.goods_name')
            ->join('left join __GOODS_INFO__ as gi on pcg.goods_id = gi.goods_id')
            ->where($where)
            ->order('pcg.change_id desc')
            ->select();

        $this->assign("goods", $goods);

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
            $xmlData .= "<OrderDate>".date("Y-m-d H:i:s",$order['create_time'])."</OrderDate>";
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