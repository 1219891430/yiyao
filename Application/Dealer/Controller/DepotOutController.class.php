<?php

/*******************************************************************
 ** 文件名称: DepotOutController.class.php
 ** 功能描述: 经销商PC端出库展示控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class DepotOutController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
        $p = I("get.p",1);
        $pnum = I("get.pnum",10);
        $urlPara['out_type'] = I("get.out_type",0);
        $urlPara['out_status']=I("get.out_status",0);
        $urlPara['start_time']=I("get.start_time",0);
        $urlPara['end_time']=I("get.end_time",0);

        if($urlPara['out_type']!=0) $where["out_type"] = $urlPara['out_type'];
        if($urlPara['out_status']!=0) $where["out_status"] = $urlPara['out_status'];
        if($urlPara['start_time']!=0&&$urlPara['end_time']!=0) $where["p1.create_time"]=array(array("egt",strtotime($urlPara['start_time'])),array("elt",strtotime($urlPara['end_time'])+24*60*59));
        $where['org_parent_id'] = session('org_parent_id');

        // 查询出货单数量和列表
        $total=M("depot_out")->field("depot_out_id")->table("zdb_depot_out p1")->where($where)->order("p1.create_time desc")->count();
        $aOut=M("depot_out")->field("depot_out_id,depot_out_code,depot_id,out_type,out_total_price,out_status,p1.create_time")->table("zdb_depot_out p1")->where($where)->order("p1.create_time desc")->page($p,$pnum)->select();
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
        for($i=0;$i<count($aOut);$i++)
        {
            $aOut[$i]["time"]=date("Y-m-d H:i:s",$aOut[$i]["create_time"]);
            $aOut[$i]["type"]=queryDepotOutType($aOut[$i]["out_type"]);
            $aOut[$i]["status"]=queryDepotOutState($aOut[$i]["out_status"],false);
            $aOut[$i]["out_name"]=D('Depot')->getDepotName($aOut[$i]["depot_id"]);
        }

        // 仓库列表
        $this->assign('urlPara',$urlPara);
        $this->assign("depot_in",$aOut);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign("outType",queryDepotOutType(0));//出库类型
        $this->display();
    }


    public function showAction(){


        $psStafflist=M("admin_user")->where("role_id=5")->select();
        $this->assign("psStafflist",$psStafflist);

        $depot_out_id=I("get.depot_out_id");
        $where["depot_out_id"]=$depot_out_id;
        $res=M("depot_out")->where($where)->find();
        $res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);


        $create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");

        $this->assign("create_name",$create_name);
        $resGoods=M("depot_out_goods")->field("zdb_goods_info.goods_code,zdb_depot_out_goods.*")->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_out_goods.goods_id")->where($where)->select();


        foreach($resGoods as $k=>$v){
            $where["goods_id"]=$v["goods_id"];
            $list=M("goods_product")->where($where)->select();
            $resGoods[$k]["goods_unit"]=$list;
        }



        $this->assign("depot_out_id",$depot_out_id);
        $this->assign("res",$res);
        $this->assign("resGoods",$resGoods);

        $org_list=M("org_info")->field("org_id,org_name")->select();
        $this->assign("org_list",$org_list);
        $aBrand = D('GoodsBrand')->field("brand_id,brand_name")->select();
        $this->assign("brand",$aBrand);
        $aDepot = D('Depot')->getDeoptAllList();
        $this->assign("depotList",$aDepot);
        $this->display();
    }

	/** 其他Action **/


}

/*************************** end ************************************/