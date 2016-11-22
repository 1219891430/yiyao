<?php

/*******************************************************************
 ** 文件名称: IndexController.class.php
 ** 功能描述: B2B商城默认控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Mall\Controller;


class IndexController extends BaseController {

    private $class = array();

	// 商城仓库选择界面
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function indexAction(){
		

	    $depot_id = $this->depot_id;
	    //$depot_id = 2;
	    /*// 仓库下所有经销商
        $orgs = queryDealer($depot_id);

        $this->assign('orgs', $orgs);*/

        // 所有品牌
        /*$where['b.is_close'] = 0;
        $where['db.is_close'] = 0;
        $where['db.repertory_id'] = intval($depot_id);
        $brand = M('depot_brand')->alias('db')->field('b.*')
            ->join('__GOODS_BRAND__ as b on db.brand_id = b.brand_id')
            ->where($where)->order('b.brand_id asc')->limit(15)->select();*/

        // 所有商品
        //$goods = $this->getGoodsByDepot($depot_id);

        // 热销商品
        //$goods = getHotGoods($depot_id, 4);

        $goods = getNewestGoods($depot_id, 20);

        $this->assign('goods', $goods);

        //echo M('goods_info')->getLastSql();die();

        //print_r($goods);die();

        // 参考零售比例
        $this->assign('per_price', 1.2);

        // 热销经销商
        $orgs = getHotOrg($depot_id, 4);

        $this->assign('orgs', $orgs);

        //热销品类
        $classes = getHotClass($depot_id, 6);
        $this->assign('classes', $classes);

        //热销品牌
        $brands = getHotBrand($depot_id, 15);
        $brands2=getHotBrand($depot_id, 9);
        
        $this->assign('brands2', $brands2);
        $this->assign('brands', $brands);

        //print_r($brands);die();

		$this->display();
    }

    // 临时切换仓库
    public function choiceDepotAction() {
        $depot_id = I('depot_id');

        session('mall_depot_id', $depot_id);

        $this->redirect('index');
    }

    private function getGoodsByDepot($depot_id,$limit=1000) {
        $where["ds.depot_id"] = $depot_id;
        $goods = M('goods_info')->alias('gi')
            ->join('__DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id')
            ->where($where)
            ->order('gi.goods_id desc')
            ->limit($limit)
            ->select();

        return $goods;
    }






	/** 其他Action **/


}

/*************************** end ************************************/