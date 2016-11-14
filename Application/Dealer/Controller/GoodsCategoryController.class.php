<?php

/*******************************************************************
 ** 文件名称: GoodsCategoryController.class.php
 ** 功能描述: 经销商PC端品类展示控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class GoodsCategoryController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

        $class = M("goods_class")->alias("gc")
            ->join("__GOODS_INFO__ as gi on gc.class_id=gi.class_id")
            ->join("__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id")
            ->where(array("org_parent_id"=>$_SESSION["org_parent_id"]))
            ->field("distinct gc.class_id,class_name,remark")
            ->select();

        $this->assign("class", $class);

		$this->display();
    }


	/** 其他Action **/


}

/*************************** end ************************************/