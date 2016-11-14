<?php

/*******************************************************************
 ** 文件名称: IndexController.class.php
 ** 功能描述: B2B商城默认控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace WapMall\Controller;


class IndexController extends BaseController {

    private $class = array();

	// 商城仓库选择界面
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function indexAction(){

	    $this->redirect('nav');

	    $depot_id = session("depot_id");
	    
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
        //$goods = getHotGoods($depot_id, 3);
        
        
        
        $goods = getNewestGoods($depot_id, 3);
        
        
        
        $this->assign('goods', $goods);

        //echo M('goods_info')->getLastSql();die();

        //print_r($goods);die();

        // 参考零售比例
        $this->assign('per_price', 1.2);

        // 热销经销商
        $orgs = getHotOrg($depot_id, 4);

        $this->assign('orgs', $orgs);

        //热销品类
        //$classes = getHotClass($depot_id, 6);
        $classes = getClass($depot_id, 100);
        
        $sub_class_id=I("sub_class_id",0);
        $where["class_id"]=$sub_class_id;
        $class_id=M("goods_class")->where($where)->getField("parent_class"); 
        if(!$class_id){
        	$class_id=$classes[0]["class_id"];
        }
        $class_id=I("class_id",$class_id);
        
        $this->assign("class_id",$class_id);
        
        $subClasses = getClass($depot_id, 100,$class_id);
        $this->assign('classes', $classes);
        $this->assign('subClasses', $subClasses);
        if($sub_class_id==0){
        	$sub_class_id=$subClasses[0]["class_id"];
        }
        
        $this->assign("sub_class_id",$sub_class_id);
        
        
        $classGoods = getNewestGoods($depot_id, 4,$sub_class_id);
        $this->assign("classGoods",$classGoods);
        
        //热销品牌
        $brands = getHotBrand($depot_id,6);
        $this->assign('brands', $brands);

        //print_r($brands);die();

		$this->display();
    }

    // 临时切换仓库
    public function choiceDepotAction() {
        $depot_id = I('depot_id');

        session('depot_id', $depot_id);

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




    public function navAction() {
        $depot_id = session("depot_id");

        $allClass = queryCategory($depot_id);

        $class = array();

        foreach ($allClass as $val) {
            if ($val['parent_class'] == 0) {
                $class[$val["class_id"]] = $val;
            }

        }

        foreach ($allClass as $val2) {
            foreach ($class as $k=>&$c) {
                if ($val2['parent_class'] == $k) {
                    $c['child'][] = $val2;
                }
            }
        }

        $this->assign('class', $class);

        $first_class = reset($class);

        $this->assign('first_class', $first_class);

        // 获取热门品牌
        $brand = getHotBrand($depot_id, 6);

        $this->assign('brand', $brand);

        $this->display();
    }




	/** 其他Action **/


}

/*************************** end ************************************/