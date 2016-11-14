<?php

/*******************************************************************
 ** 文件名称: DepotInController.class.php
 ** 功能描述: 经销商PC端入库展示控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class DepotInController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
        $p = I("get.p",1);
        $pnum = I("get.pnum",10);

        $urlPara['in_type'] = I("get.in_type",0);
        $urlPara['in_status'] = I("get.in_status",0);
        $urlPara['start_time'] = I("get.start_time",0);
        $urlPara['end_time'] = I("get.end_time",0);

        if($urlPara['in_type']!=0) $where["in_type"] = $urlPara['in_type'];
        if($urlPara['in_status']!=0) $where["in_status"] = $urlPara['in_status'];
        if($urlPara['start_time'] !=0 && $urlPara['end_time']!=0) $where["create_time"]=array(array("egt",strtotime($urlPara['start_time'])),array("elt",strtotime($urlPara['end_time'])+24*60*59));
        $where['org_parent_id'] = session('org_parent_id');

        // 查询入库单
        $total = M("depot_in")->where($where)->count();
        $aIn = M("depot_in")->field("depot_in_id,depot_in_code,depot_id,in_type,in_status,create_time,create_id")->where($where)->order("create_time desc")->page($p,$pnum)->select();
        $page = get_page_code($total,$pnum,$p, $page_code_len = 5);

        for($i=0;$i<count($aIn);$i++)
        {
            $aIn[$i]["time"]=date("Y-m-d H:i:s",$aIn[$i]["create_time"]);
            $aIn[$i]["type"]=queryDepotInType($aIn[$i]["in_type"]);
            $aIn[$i]["status"]=queryDepotInState($aIn[$i]["in_status"]);
            $aIn[$i]["in_name"]=D('Depot')->getDepotName($aIn[$i]["depot_id"]);
            $aIn[$i]["create_name"]=M('admin_user')->where("admin_id=".$aIn[$i]["create_id"])->getField("true_name");

        }

        $this->assign("urlPara",$urlPara);
        $this->assign("depot_in",$aIn);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign("inType",queryDepotInType(0)); // 入库单所有类型
		$this->display();
    }

    public function showAction(){
        $depot_in_id=I("get.depot_in_id");
        $where["depot_in_id"]=$depot_in_id;
        $res=M("depot_in")->where($where)->find();
        $res["create_time"]=date("Y-m-d H:i:s",$res["create_time"]);


        $create_name=M("admin_user")->where("admin_id=".$res["create_id"])->getField("true_name");
        $this->assign("create_name",$create_name);
        $resGoods=M("depot_in_goods")->field("zdb_goods_info.goods_code,zdb_depot_in_goods.*")->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_in_goods.goods_id")->where($where)->select();


        foreach($resGoods as $k=>$v){
            $where["goods_id"]=$v["goods_id"];
            $list=M("goods_product")->where($where)->select();
            $resGoods[$k]["goods_unit"]=$list;
        }
        $this->assign("depot_in_id",$depot_in_id);
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