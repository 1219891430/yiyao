<?php

/*******************************************************************
 ** 文件名称: GoodsBrandController.class.php
 ** 功能描述: 经销商PC端品牌展示控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class GoodsBrandController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

	    // 查询经销商所有商品
        $brand = M("goods_brand")->alias("gb")
            ->join("__GOODS_INFO__ as gi on gb.brand_id=gi.brand_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->where(array("org_parent_id"=>$_SESSION["org_parent_id"]))
            ->field("DISTINCT gb.brand_id,gb.brand_name,gb.remark,brand_logo")
            ->select();
        
	    $this->assign("brand", $brand);

		$this->display();
    }


	/** 其他Action **/
	


}

/*************************** end ************************************/