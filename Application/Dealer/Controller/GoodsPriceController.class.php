<?php

/*******************************************************************
 ** 文件名称: GoodsPriceController.class.php
 ** 功能描述: 经销商PC端商品价格控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class GoodsPriceController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

        $urlPara = array();
        $urlPara["brand_id"] = I("brand_id");
        $urlPara["class_id"] = I("class_id");
        $this->assign('urlPara', $urlPara);

        $where = "ogc.org_parent_id = " . $_SESSION["org_parent_id"];

        if (!empty($urlPara["brand_id"]) && $urlPara["brand_id"] != 0) {
            $where .= " AND gi.brand_id = " . $urlPara["brand_id"];
        }

        if (!empty($urlPara["class_id"]) && $urlPara["class_id"] != 0) {
            $where .= " AND gi.class_id = " . $urlPara["class_id"];
        }


	    $price = M("org_goods_convert")->alias("ogc")
            ->join("left join __GOODS_INFO__ as gi on ogc.goods_id=gi.goods_id")
            ->where($where)
            ->select();

        $this->assign("price", $price);

        // 经销商全部品牌
        $brand = M("goods_brand")->alias("gb")
            ->join("__GOODS_INFO__ as gi on gb.brand_id=gi.brand_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->join("__ORG_INFO__ as oi on ds.org_parent_id=oi.org_id")
            ->where(array("oi.org_id"=>$_SESSION["org_parent_id"]))
            ->field("gb.*")
            ->select();

        $this->assign("brand", $brand);

        $class = M("goods_class")->alias("gc")
            ->join("__GOODS_INFO__ as gi on gc.class_id=gi.class_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->join("__ORG_INFO__ as oi on ds.org_parent_id=oi.org_id")
            ->where(array("oi.org_id"=>$_SESSION["org_parent_id"]))
            ->field("gc.*")
            ->select();

        $this->assign("class", $class);

		$this->display();
    }


    // 修改价格
    public function changePriceAction() {
        $cv_id = I("post.id");
        $price = I("post.price");
        $flag = I('post.flag');

        if (empty($cv_id) || empty($price) || empty($flag)) {
            aJsonReturn(0, "参数错误");
            return;
        }

        $where["cv_id"] = $cv_id;
        $gc_info = M('org_goods_convert')->where($where)->find();
        if(!$gc_info){
            aJsonReturn(0, "获取商品信息失败");
            return;
        }

        $jin_price = $gc_info['goods_jin_price'];
        $base_price = $gc_info['goods_base_price'];

        if($flag==1){//进价
            $filed = 'goods_jin_price';
            if( $price > $base_price){
                aJsonReturn(0, "进价不能高于售价");
                return;
            }
        }
        else{//售价
            $filed = 'goods_base_price';
            if( $price < $jin_price){
                aJsonReturn(0, "售价不能低于进价");
                return;
            }
        }

        $data[$filed] = $price;

        $res = M("org_goods_convert")->where($where)->save($data);
        if ($res) {
            aJsonReturn(1, $price);
        }
        else {
            aJsonReturn(0, "更改失败");
        }
    }

	// 修改默认包装
	public function setDefaultUnitAction()
	{
		$org_parent_id = $_SESSION["org_parent_id"];
		$goods_id = intval($_GET['gid']);
		$goods_unit_type = intval($_GET['uid']);
		$where['org_parent_id'] = $org_parent_id;
		$where['goods_id'] = $goods_id;
		M('org_goods_convert')->where($where)->setField('unit_default', 0);
		$where['goods_unit_type'] = $goods_unit_type;
		M('org_goods_convert')->where($where)->setField('unit_default', 1);
		die("1");		
	}

	/** 其他Action **/


}

/*************************** end ************************************/