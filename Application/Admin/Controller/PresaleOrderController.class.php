<?php

/*******************************************************************
 ** 文件名称: PresaleOrderController.class.php
 ** 功能描述: 系统后台预单销售控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class PresaleOrderController extends BaseController {

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
        $urlPara["staff_id"] = I("staff_id");
        $urlPara["cust"] = I("cust");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");
        $this->assign('urlPara', $urlPara);

        $where = "1=1";

        if($depotID>0) {
            $where .= " AND po.repertory_id = " . $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where .= " AND po.repertory_id = " . $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["staff_id"])) {
            $where .= " AND po.staff_id = " . $urlPara["staff_id"];
        }

        if (!empty($urlPara["cust"])) {
            $where .= " AND po.cust_name like '%" . $urlPara["cust"] . "%' ";
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"] + 24*60*60);
            if ($etime > $stime) {
                $where .= $stime . " <= po.add_time < " . $etime;
            }
        }

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $this->assign("p", $p);
        $this->assign("pnum", $pnum);

        $total = M('presale_orders')->table("zdb_presale_orders as po")->where($where)->count();


        $list = M('presale_orders')->alias('po')
            ->field('po.*, ci.cust_name, au.true_name')
            ->join('left join __CUSTOMER_INFO__ as ci on po.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on po.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on po.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on po.org_parent_id = oi.org_id')
            ->where($where)
            ->page($p, $pnum)
            ->order('po.order_id desc')
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

        session('jump_url', U('Admin/PresaleOrder',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        //print_r($list);die();

	    // 仓库
	    $this->assign('depotList', queryDepot($depotID));

        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID, 4));

		$this->display();
    }

	public function addAction(){
        $time=date("Y-m-d H:i");
        $code=create_uniqid_code("PO");
        
        if(IS_GET)
        {
        	$aDepot=M("depot_info")->where("repertory_id= ".$_SESSION['depot_id'] )->select();

            $this->assign("depotList",$aDepot);
//			$org_parent_id=session("org_parent_id");
//          $aStaff = queryOrgStaff($org_parent_id,'3');

            $mBrand = new \Common\Model\GoodsBrandModel();
            $aBrand=$mBrand->getGoodsBrandList();
            $this->assign("brand",$aBrand);
            $this->assign("code",$code);
            $this->assign("time",$time);
            
            $orglist=M("org_info")->where("is_close=0")->select();
            
            $this->assign("orglist",$orglist);
            //$this->assign("staff_name",session("staff_name"));
            $this->display();
        }

       
        
    }
    // 查看
    public function lookAction() {
    	
		
        $id = I("id");
		
		
		if($_SESSION["depot_id"]){
			$where["po.repertory_id"] = $_SESSION["depot_id"];
		}

        $where["order_id"] = $id;

        $order = M('presale_orders')->alias('po')
            ->field("po.*, ci.cust_name, au.true_name, oi.org_name, di.repertory_name")
            ->join('left join __CUSTOMER_INFO__ as ci on po.cust_id = ci.cust_id')
            ->join('left join __ADMIN_USER__ as au on po.staff_id = au.admin_id')
            ->join('left join __DEPOT_INFO__ as di on po.repertory_id = di.repertory_id')
            ->join('left join __ORG_INFO__ as oi on po.org_parent_id = oi.org_id')
            ->where($where)
            ->order('po.order_id desc')
            ->find();

        $this->assign("order", $order);

        if($_SESSION["depot_id"]){
			unset($where["po.repertory_id"]);
		}
        // 商品
        $goods = M("presale_orders_goods")->alias('pog')
            ->field('pog.*, gi.goods_code, gi.goods_name')
            ->join('left join __GOODS_INFO__ as gi on pog.goods_id = gi.goods_id')
            ->where($where)
            ->order('order_id desc')
            ->select();
        if(!$order){
        	unset($goods);
        }
        $this->assign("goods", $goods);

        // 返回页面
        $this->display();
    }
	
	/** 其他Action **/
    public function selGoodsAndStockAction()
    {
    	$iBrandId=I("post.brand",1);
    	$iClassId=I("post.class_id",0);
    	$depot_id=I("post.depot_id",1);
    	$sGoods=I("post.goods","");
		$org_parent_id=I("post.org_id");
		

    	$mGoods=new \Common\Model\GoodsInfoModel();
    	$mConvert=M("goods_product");
    	$aGoods=$mGoods->selGoods($iBrandId,$iClassId,$sGoods,0,0,$org_parent_id);
//  	$aFiled=array("cv_id,goods_id,goods_cv,goods_code,goods_unit,goods_unit_type,goods_jin_price,goods_base_price,goods_min_price,goods_max_price,goods_last_price");
    	for($i=0;$i<count($aGoods);$i++) {
			
			
			$where["goods_id"]=$aGoods[$i]["goods_id"];
			$where["org_parent_id"]=$org_parent_id;
			$orgConvertlist=M("org_goods_convert")->field("cv_id,goods_id,goods_unit,unit_default,goods_unit_type,goods_jin_price,goods_base_price")->where($where)->select();
			if($orgConvertlist){
				$where1["goods_id"]=$aGoods[$i]["goods_id"];
				$goodsRes=M("goods_info")->where($where1)->find();
			
				$where2["goods_id"]=$aGoods[$i]["goods_id"];
				$where2["depot_id"]=$depot_id;
				$depotStockRes=M("depot_stock")->where($where2)->find();
			
				foreach($orgConvertlist as $k=>$v){
					if($v["goods_unit_type"]==1){
						$orgConvertlist[$k]["num"]=$depotStockRes["small_stock"];
					}elseif($v["goods_unit_type"]==2){
						$orgConvertlist[$k]["num"]=floor($depotStockRes["small_stock"]/$goodsRes['goods_convert_m']);
					}elseif($v["goods_unit_type"]==3){
						$orgConvertlist[$k]["num"]=floor($depotStockRes["small_stock"]/($goodsRes['goods_convert_m']*$goodsRes['goods_convert_b']));
					}
				
				}
				if($orgConvertlist[$k]["num"]==null){
					$orgConvertlist[$k]["num"]=0;
				}
    	    	$aGoods[$i]["convert_data"]=$orgConvertlist;
			}else{
				unset($aGoods[$i]);
			}
			
    	}
        
        if($aGoods)
    	   $this->aReturn=array("res"=>1,"data"=>$aGoods,"count"=>count($aGoods));
    	else
    	   $this->aReturn=array("res"=>0);
    	echo $this->ajaxReturn($this->aReturn,"json");
    }

}

/*************************** end ************************************/