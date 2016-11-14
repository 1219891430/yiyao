<?php

/*******************************************************************
 ** 文件名称: SearchController.class.php
 ** 功能描述: B2B商城搜索筛选控制器
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-11
 *******************************************************************/

namespace WapMall\Controller;


class SearchController extends BaseController {

    // 商城搜索页
    // 创建人员: wangbo
    // 创建日期: 2016-08-11
    public function indexAction(){

        $cust_id = session('cust_id');
        $depot_id = session("depot_id");

        /*// 所有品类
        $class = queryCategory($depot_id);

        $this->assign('class', $class);*/

        //$jump_url = "?search";
        $url_p = array();

        // 搜索框内容
        $search_word = I('get.word');

        if (!empty($search_word)) {
            $url_p["word"] = $search_word;
        }

        // 价格
        $search_price_min = I('get.price_min');
        if (!empty($search_price_min)) {
            //$jump_url .= '&price_min=' . $search_price_min;
            $url_p["price_min"] = $search_price_min;
        }

        $search_price_max = I('get.price_max');
        if (!empty($search_price_max)) {
            //$jump_url .= '&price_max' . $search_price_max;
            $url_p["price_max"] = $search_price_max;
        }

        $price = [
            "min" => $search_price_min,
            "max" => $search_price_max,
        ];

        // 分类
        $search_cat = I('get.cat');
        if (!empty($search_cat)) {
            //$jump_url .= '&cat=' . $search_cat;
            $url_p["cat"] = $search_cat;
        }

        // 品牌
        $search_brand = I('get.brand');
        if (!empty($search_brand)) {
            //$jump_url .= '&brand=' . $search_brand;
            $url_p["brand"] = $search_brand;
        }

        // 最小起批
        $sale_num = I('get.snum');
        if (!empty($sale_num)) {
            //$jump_url .= '&snum=' . $sale_num;
            $url_p['snum'] = $sale_num;
        }

        //if (trim($search_word) == "" && $search_cat <= 0 && $search_brand <= 0 && $sale_num <= 0) {
        //    return redirect('index');
        //}

        // 排序
        $order = '';

        if (isset($_GET['order']) && $_GET['order'] == 'comp') {
            $order = 'goods_id';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'price') {
            $order = 'goods_base_price';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'sales') {
            $order = 'sales';
        }

        if (!empty($order)) {
            //$jump_url .= 'order=' . $_GET['order'];
            $url_p["order"] = $_GET['order'];
        }

        //$jump_url = "/index.php/Mall/Search?search";

        //print_r($url_p);die();

        $paramStr = '';
        if (count($url_p) > 0) {
            $paramStr = "?";
            $counts = count($url_p);
            $i = 0;
            foreach ($url_p as $k=>$v) {
                if (false !== $v) {
                    $paramStr.=$k."=".$v;
                    if ($i != $counts - 1) {
                        $paramStr .= '&';
                    }
                }
                ++$i;
            }
            $strLen = strlen($paramStr) - 1;
            if ($paramStr[$strLen] == '&') {
                $paramStr = substr($paramStr, 0, $strLen);
            }
        }
        $jump_url = '/index.php/Mall/Search'.$paramStr;


        /*foreach($url_p as $key => $val) {
            $jump_url .= '&' . $key . '=' . $val;
        }*/

        $this->assign('jump_url', $jump_url);

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 20);

        $this->assign("p", $p);
        $this->assign("pnum", $pnum);


        $total = $this->getTotal($depot_id, $search_word, $search_cat, $search_brand, $sale_num, $price);

		$sort = I("sort",'asc');
		
        $goods = $this->getAboutSearch($depot_id, $search_word,$_GET['order'],$sort, $search_cat, $search_brand, $sale_num, $price, $p, $pnum);
        
        // 排序
        if (trim($order) != "") {

            session('search_order_'.$_GET['order'].$cust_id, $sort);

            //$goods = $this->search_order($goods, $order, $sort);

        }
        
        $this->assign("sort",$sort);
        // 商品
        $this->assign('goods', $goods);

        // 品类
        $cats = $this->getClass($goods);
        $this->assign('cats', $cats);
        
        // 品牌
        $brand = $this->getBrand($goods);
        $this->assign('brands', $brand);
        
        //echo M('goods_info')->getLastSql();

        // 计算数量
        $this->assign('count', $total);

        $page = get_page_code($total, $pnum, $p, $page_code_len = 30);

        $this->assign('pagelist',$page);//分页显示

        $this->assign('urlParam', $url_p);

        //print_r($goods);die();

        $this->display();
    }

    public function searchAction(){

        $cust_id = session('cust_id');
        $depot_id = session("depot_id");

        // 所有品类
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

        /*foreach ($class as $a1) {
            $i = strInArray(2, 'class_id', $a1['child']);

            var_dump($i);

        }die;*/

        //print_r($class);die;

        $this->assign('class', $class);

        //$jump_url = "?search";
        $url_p = array();

        // 搜索框内容
        $search_word = I('get.word');

        if (!empty($search_word)) {
            $url_p["word"] = $search_word;
        }

        // 价格
        $search_price_min = I('get.price_min');
        if (!empty($search_price_min)) {
            //$jump_url .= '&price_min=' . $search_price_min;
            $url_p["price_min"] = $search_price_min;
        }

        $search_price_max = I('get.price_max');
        if (!empty($search_price_max)) {
            //$jump_url .= '&price_max' . $search_price_max;
            $url_p["price_max"] = $search_price_max;
        }

        $price = [
            "min" => $search_price_min,
            "max" => $search_price_max,
        ];

        // 分类
        $search_cat = I('get.cat');
        if (!empty($search_cat)) {
            //$jump_url .= '&cat=' . $search_cat;
            $url_p["cat"] = $search_cat;
        }

        // 品牌
        $search_brand = I('get.brand');
        if (!empty($search_brand)) {
            //$jump_url .= '&brand=' . $search_brand;
            $url_p["brand"] = $search_brand;
        }

        // 最小起批
        $sale_num = I('get.snum');
        if (!empty($sale_num)) {
            //$jump_url .= '&snum=' . $sale_num;
            $url_p['snum'] = $sale_num;
        }

        //if (trim($search_word) == "" && $search_cat <= 0 && $search_brand <= 0 && $sale_num <= 0) {
        //    return redirect('index');
        //}

        // 排序
        $order = '';

        if (isset($_GET['order']) && $_GET['order'] == 'comp') {
            $order = 'goods_id';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'price') {
            $order = 'goods_base_price';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'sales') {
            $order = 'sales';
        }

        if (!empty($order)) {
            //$jump_url .= 'order=' . $_GET['order'];
            $url_p["order"] = $_GET['order'];
        }

        //$jump_url = "/index.php/Mall/Search?search";

        //print_r($url_p);die();

        $paramStr = '';
        if (count($url_p) > 0) {
            $paramStr = "?";
            $counts = count($url_p);
            $i = 0;
            foreach ($url_p as $k=>$v) {
                if (false !== $v) {
                    $paramStr.=$k."=".$v;
                    if ($i != $counts - 1) {
                        $paramStr .= '&';
                    }
                }
                ++$i;
            }
            $strLen = strlen($paramStr) - 1;
            if ($paramStr[$strLen] == '&') {
                $paramStr = substr($paramStr, 0, $strLen);
            }
        }
        $jump_url = '/index.php/Mall/Search'.$paramStr;


        /*foreach($url_p as $key => $val) {
            $jump_url .= '&' . $key . '=' . $val;
        }*/

        $this->assign('jump_url', $jump_url);

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 20);

        $this->assign("p", $p);
        $this->assign("pnum", $pnum);

        $total = $this->getTotal($depot_id, $search_word, $search_cat, $search_brand, $sale_num, $price);

		$sort = I("sort", 'asc');
        $goods = $this->getAboutSearch($depot_id, $search_word,$_GET['order'],$sort, $search_cat, $search_brand, $sale_num, $price, $p, $pnum);

        
        
        // 排序
        if (trim($order) != "") {

            $url_p['sort'] = $sort;

            session('search_order_'.$_GET['order'].$cust_id, $sort);

            //$goods = $this->search_order($goods, $order, $sort);

        }
        
        $this->assign("sort",$sort);
        // 商品
        $this->assign('goods', $goods);

        //print_r($goods);die;

        // 品类
        $cats = $this->getClass($goods);
        $this->assign('cats', $cats);

        $search_class = "";
        if (!empty($search_cat)) {
            // 获取搜索的分类
            $search_class = M("goods_class")->where("class_id=$search_cat")->field("class_name")->find();
        }

        $this->assign('urlParam', $url_p);

        $this->assign('search_class', $search_class);

        // 品牌
        $brand = $this->getBrand($goods);
        $this->assign('brands', $brand);

        //echo M('goods_info')->getLastSql();

        // 计算数量
        $this->assign('count', $total);

        $page = get_page_code($total, $pnum, $p, $page_code_len = 30);

        $this->assign('pagelist',$page);//分页显示

        //print_r($goods);die();

        $this->display();
    }
    
    
     public function searchJsonAction(){

        $cust_id = session('cust_id');
        $depot_id = session("depot_id");

        // 所有品类
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

        /*foreach ($class as $a1) {
            $i = strInArray(2, 'class_id', $a1['child']);

            var_dump($i);

        }die;*/

        //print_r($class);die;

        $this->assign('class', $class);

        //$jump_url = "?search";
        $url_p = array();

        // 搜索框内容
        $search_word = I('get.word');

        if (!empty($search_word)) {
            $url_p["word"] = $search_word;
        }

        // 价格
        $search_price_min = I('get.price_min');
        if (!empty($search_price_min)) {
            //$jump_url .= '&price_min=' . $search_price_min;
            $url_p["price_min"] = $search_price_min;
        }

        $search_price_max = I('get.price_max');
        if (!empty($search_price_max)) {
            //$jump_url .= '&price_max' . $search_price_max;
            $url_p["price_max"] = $search_price_max;
        }

        $price = [
            "min" => $search_price_min,
            "max" => $search_price_max,
        ];

        // 分类
        $search_cat = I('get.cat');
        if (!empty($search_cat)) {
            //$jump_url .= '&cat=' . $search_cat;
            $url_p["cat"] = $search_cat;
        }

        // 品牌
        $search_brand = I('get.brand');
        if (!empty($search_brand)) {
            //$jump_url .= '&brand=' . $search_brand;
            $url_p["brand"] = $search_brand;
        }

        // 最小起批
        $sale_num = I('get.snum');
        if (!empty($sale_num)) {
            //$jump_url .= '&snum=' . $sale_num;
            $url_p['snum'] = $sale_num;
        }

        //if (trim($search_word) == "" && $search_cat <= 0 && $search_brand <= 0 && $sale_num <= 0) {
        //    return redirect('index');
        //}

        // 排序
        $order = '';

        if (isset($_GET['order']) && $_GET['order'] == 'comp') {
            $order = 'goods_id';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'price') {
            $order = 'goods_base_price';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'sales') {
            $order = 'sales';
        }

        if (!empty($order)) {
            //$jump_url .= 'order=' . $_GET['order'];
            $url_p["order"] = $_GET['order'];
        }

        //$jump_url = "/index.php/Mall/Search?search";

        //print_r($url_p);die();

        $paramStr = '';
        if (count($url_p) > 0) {
            $paramStr = "?";
            $counts = count($url_p);
            $i = 0;
            foreach ($url_p as $k=>$v) {
                if (false !== $v) {
                    $paramStr.=$k."=".$v;
                    if ($i != $counts - 1) {
                        $paramStr .= '&';
                    }
                }
                ++$i;
            }
            $strLen = strlen($paramStr) - 1;
            if ($paramStr[$strLen] == '&') {
                $paramStr = substr($paramStr, 0, $strLen);
            }
        }
        $jump_url = '/index.php/Mall/Search'.$paramStr;


        /*foreach($url_p as $key => $val) {
            $jump_url .= '&' . $key . '=' . $val;
        }*/

        $this->assign('jump_url', $jump_url);

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 20);

        $this->assign("p", $p);
        $this->assign("pnum", $pnum);

        $total = $this->getTotal($depot_id, $search_word, $search_cat, $search_brand, $sale_num, $price);

        $sort = I("sort", 'asc');
        $goods = $this->getAboutSearch($depot_id, $search_word,$_GET['order'],$sort,$search_cat, $search_brand, $sale_num, $price, $p, $pnum);

       
        
        // 排序
        if (trim($order) != "") {

            $url_p['sort'] = $sort;

            session('search_order_'.$_GET['order'].$cust_id, $sort);

            //$goods = $this->search_order($goods, $order, $sort);

        }
        
        

        //print_r($goods);die;

        // 品类
        $cats = $this->getClass($goods);
        

        $search_class = "";
        if (!empty($search_cat)) {
            // 获取搜索的分类
            $search_class = M("goods_class")->where("class_id=$search_cat")->field("class_name")->find();
        }

        
        // 品牌
        $brand = $this->getBrand($goods);
        
        

        $page = get_page_code($total, $pnum, $p, $page_code_len = 30);

        

        $this->assign("goods",$goods);

        $this->display();
    }
    
    
    public function indexJsonAction(){

        $cust_id = session('cust_id');
        $depot_id = session("depot_id");

        /*// 所有品类
        $class = queryCategory($depot_id);

        $this->assign('class', $class);*/

        //$jump_url = "?search";
        $url_p = array();

        // 搜索框内容
        $search_word = I('get.word');

        if (!empty($search_word)) {
            $url_p["word"] = $search_word;
        }

        // 价格
        $search_price_min = I('get.price_min');
        if (!empty($search_price_min)) {
            //$jump_url .= '&price_min=' . $search_price_min;
            $url_p["price_min"] = $search_price_min;
        }

        $search_price_max = I('get.price_max');
        if (!empty($search_price_max)) {
            //$jump_url .= '&price_max' . $search_price_max;
            $url_p["price_max"] = $search_price_max;
        }

        $price = [
            "min" => $search_price_min,
            "max" => $search_price_max,
        ];

        // 分类
        $search_cat = I('get.cat');
        if (!empty($search_cat)) {
            //$jump_url .= '&cat=' . $search_cat;
            $url_p["cat"] = $search_cat;
        }

        // 品牌
        $search_brand = I('get.brand');
        if (!empty($search_brand)) {
            //$jump_url .= '&brand=' . $search_brand;
            $url_p["brand"] = $search_brand;
        }

        // 最小起批
        $sale_num = I('get.snum');
        if (!empty($sale_num)) {
            //$jump_url .= '&snum=' . $sale_num;
            $url_p['snum'] = $sale_num;
        }

        //if (trim($search_word) == "" && $search_cat <= 0 && $search_brand <= 0 && $sale_num <= 0) {
        //    return redirect('index');
        //}

        // 排序
        $order = '';

        if (isset($_GET['order']) && $_GET['order'] == 'comp') {
            $order = 'goods_id';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'price') {
            $order = 'goods_base_price';
        }

        if (isset($_GET['order']) && $_GET['order'] == 'sales') {
            $order = 'sales';
        }

        if (!empty($order)) {
            //$jump_url .= 'order=' . $_GET['order'];
            $url_p["order"] = $_GET['order'];
        }

        //$jump_url = "/index.php/Mall/Search?search";

        //print_r($url_p);die();

        $paramStr = '';
        if (count($url_p) > 0) {
            $paramStr = "?";
            $counts = count($url_p);
            $i = 0;
            foreach ($url_p as $k=>$v) {
                if (false !== $v) {
                    $paramStr.=$k."=".$v;
                    if ($i != $counts - 1) {
                        $paramStr .= '&';
                    }
                }
                ++$i;
            }
            $strLen = strlen($paramStr) - 1;
            if ($paramStr[$strLen] == '&') {
                $paramStr = substr($paramStr, 0, $strLen);
            }
        }
        $jump_url = '/index.php/Mall/Search'.$paramStr;


        /*foreach($url_p as $key => $val) {
            $jump_url .= '&' . $key . '=' . $val;
        }*/

        $this->assign('jump_url', $jump_url);

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 20);

        $this->assign("p", $p);
        $this->assign("pnum", $pnum);

        $this->assign('urlParam', $url_p);


        $total = $this->getTotal($depot_id, $search_word, $search_cat, $search_brand, $sale_num, $price);
        $sort = I("sort",'asc');
        
        $goods = $this->getAboutSearch($depot_id, $search_word,$_GET['order'],$sort, $search_cat, $search_brand, $sale_num, $price, $p, $pnum);

        
        // 排序
        if (trim($order) != "") {

            

            session('search_order_'.$_GET['order'].$cust_id, $sort);

            //$goods = $this->search_order($goods, $order, $sort);

        }
        
        // 商品
        echo json_encode($goods);

        // 品类
//      $cats = $this->getClass($goods);
//      $this->assign('cats', $cats);
//      
//      // 品牌
//      $brand = $this->getBrand($goods);
//      $this->assign('brands', $brand);
//      
//      //echo M('goods_info')->getLastSql();
//
//      // 计算数量
//      $this->assign('count', $total);
//
//      $page = get_page_code($total, $pnum, $p, $page_code_len = 30);
//
//      $this->assign('pagelist',$page);//分页显示

        
    }
    
    

    // 获取总数
    public function getTotal($depot_id, $word, $class='', $brand='', $snum = 0, $price = []) {

        $warr = explode(' ', $word);

        if (count($warr) > 1) {

            $search = array();

            for ($i = 0; $i < count($warr); $i++) {
                array_push($search, array('like', "%".$warr[$i]."%"));
            }

            array_push($search, 'or');

        } else {

            $search = array('like', "%".$word."%");

        }

        $where['gi.goods_name'] = $search;

        $where['gi.is_close'] = 0;

        $where["ds.depot_id"] = $depot_id;

        $where["ogc.unit_default"] = 1;

        if (!empty($price) && $price['min'] > 0 && $price['max'] >= $price['min']) {

            $where['ogc.goods_base_price'] = array("between", $price['min'] .",". $price['max']);

        }

        if (trim($class) != "") {

            $where['gc.class_id'] = $class;

        }

        if (trim($brand) != "") {

            $where['gb.brand_id'] = $brand;

        }

        if (!empty($snum)) {
            $where['ogc.wholesale_num'] = array('egt', $snum);
        }


        $total =  M('goods_info')->alias('gi')
            // 库存
            ->join('left join __DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id')
            // 经销商价格
            ->join('left join __ORG_GOODS_CONVERT__ as ogc on ds.org_parent_id=ogc.org_parent_id AND gi.goods_id=ogc.goods_id')
            ->join('left join __ORG_INFO__ as oi on ogc.org_parent_id=oi.org_id')
            // 品类
            ->join('left join __GOODS_CLASS__ as gc on gi.class_id=gc.class_id')
            // 品牌
            ->join('left join __GOODS_BRAND__ as gb on gi.brand_id=gb.brand_id')
            ->where($where)
            ->count();

        //echo M('goods_info')->getLastSql();die();

        return $total;
        //return $search;
    }

    // 获得商品信息
    public function getAboutSearch($depot_id, $word, $order,$sort,$class='', $brand='', $snum = 0, $price = [], $p = 1, $pnum = 20) {

        $warr = explode(' ', $word);

        if (count($warr) > 1) {

            $search = array();

            for ($i = 0; $i < count($warr); $i++) {
                array_push($search, array('like', "%".$warr[$i]."%"));
            }

            array_push($search, 'or');

        } else {

            $search = array('like', "%".$word."%");

        }

        $where['gi.goods_name'] = $search;

        $where['gi.is_close'] = 0;

        $where["ds.depot_id"] = $depot_id;

        $where["ogc.unit_default"] = 1;

        if (!empty($price) && $price['min'] > 0 && $price['max'] >= $price['min']) {

            $where['ogc.goods_base_price'] = array("between", $price['min'] .",". $price['max']);

        }

        if (trim($class) != "") {

            $where['gc.class_id'] = $class;

        }

        if (trim($brand) != "") {

            $where['gb.brand_id'] = $brand;

        }

        if (!empty($snum)) {
            $where['ogc.wholesale_num'] = array('elt', $snum);
        }
        $orderString="";
        if($order=="sales"){
        	if($sort=="desc"){
        		$orderString='sales desc';
        	}else{
        		$orderString='sales asc';
        	}
        	
        }elseif($order=="price"){
        	if($sort=="desc"){
        		$orderString="ogc.goods_base_price desc";
        	}else{
        		$orderString="ogc.goods_base_price asc";
        	}
        }else{
        	
        	$orderString='gi.goods_id desc';
        }
        
        	$search =  M('goods_info')->alias('gi')
            // 库存
            ->join('left join __DEPOT_STOCK__ as ds on gi.goods_id=ds.goods_id')
            // 经销商价格
            ->join('left join __ORG_GOODS_CONVERT__ as ogc on ds.org_parent_id=ogc.org_parent_id AND gi.goods_id=ogc.goods_id')
            ->join('left join __ORG_INFO__ as oi on ogc.org_parent_id=oi.org_id')
            // 品类
            ->join('left join __GOODS_CLASS__ as gc on gi.class_id=gc.class_id')
            // 品牌
            ->join('left join __GOODS_BRAND__ as gb on gi.brand_id=gb.brand_id')
            ->join("left join __CAR_ORDERS_GOODS__ as cog on ogc.cv_id = cog.cv_id")

            ->field('gi.*, gc.class_name, gb.brand_name, ogc.*,oi.org_id, oi.org_name, oi.reg_time as org_reg_time ,count(cog.order_id) as sales')
            ->where($where)
            ->page($p, $pnum)
            ->order($orderString)
            ->group("cv_id")
            ->select();
        	
        
        
        
        //print_r($search);die();

        //echo M('goods_info')->getLastSql();die();

        foreach ($search as &$val) {
        	if(!$val['sales']){
        		$val['sales']=0;
        	}
            $val["is_act"] = 0;
            // 查询活动
            $actwhere["is_close"] = 0;
            $actwhere["depot_id"] = $depot_id;
            $actwhere["goods_id"] = $val['goods_id'];
            $actwhere["start_time"] = array("ELT", time());
            $actwhere["end_time"] = array("EGT", time());
            $activity = M("activity")->where($actwhere)->select();

            if ($activity) {
                foreach ($activity as $act) {
                    if ($act["act_type"] == 0) {
                        $val["is_act"] = 1;
                        $val['act'][] = $act;
                    }

                    // 满减活动
                    if ($act["act_type"] == 1) {
                        $val["is_act"] = 1;
                        $val['act'][] = $act;

                    }

                    // 赠品活动
                    if ($act["act_type"] == 2) {
                        $val["is_act"] = 1;

                        // 获取赠品信息
                        $song_goods = M("goods_info")->alias("gi")
                            ->join("left join __ORG_GOODS_CONVERT__ as ogc on ogc.goods_id=gi.goods_id")
                            ->field("gi.goods_name, gi.goods_spec, gi.main_img, ogc.goods_unit")
                            ->where("ogc.cv_id={$act['song_cv_id']} AND ogc.goods_id={$act['song_goods_id']}")
                            ->find();
                        $act["song_goods"] = $song_goods;

                        $val['act'][] = $act;
                    }
                }

            }

        }

        //print_r($search);die;


        //return $this->countSales($search);
        return $search;
    }


    // 成交量
    private function countSales($data) {
        // 出库
        $out_where['do.out_type'] = array('in','1, 2');

        // 退库
        //$in_where['di.in_type'] = array('in', '2, 3');

        $goods_count = array();

        foreach ($data as $key => $val) {
            $out_where['gi.goods_id'] = $val['goods_id'];
            $out_where['dog.cv_id'] = $val['cv_id'];

            $out_count = M('goods_info')->alias('gi')
                // 出库
                ->join('left join __DEPOT_OUT_GOODS__ as dog on gi.goods_id=dog.goods_id')
                ->join('left join __DEPOT_OUT__ as do on dog.depot_out_id=do.depot_out_id')
                ->where($out_where)
                ->count();

            /*$return_count = M('goods_info')->alias('gi')
                // 退库
                ->join('__DEPOT_IN_GOODS__ as dig on gi.goods_id=dig.goods_id')
                ->join('__DEPOT_IN__ as di on dig.depot_in_id=di.depot_in_id')
                ->where($in_where)
                ->count();*/

            //echo $out_count."\r\n";
            //echo $return_count."\r\n";

            //$count = $out_count - $return_count;

            //echo M('goods_info')->getLastSql();

            $val['sales'] = $out_count;

            //计算年限
            $reg_y = date('Y', $val['org_reg_time']);
            $now_y = date('Y');
            $val['reg_year'] = $now_y - $reg_y + 1;

            $goods_count[$key] = $val;
        }

        return $goods_count;
    }

    // $data 需要排序的数据
    // $rule 规则 desc asc
    private function search_order($data, $field='goods_id', $sort='desc') {

        switch ($sort) {
            case 'asc':
                $direction = 'SORT_ASC';
                break;
            case 'desc':
                $direction = 'SORT_DESC';
                break;
            default:
                $direction = 'SORT_ASC';
        }

        $sort = array(
            'direction' => $direction, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => $field,       //排序字段
        );

        $arrSort = array();

        foreach($data as $uniqid => $row){

            foreach($row as $key=>$value){

                $arrSort[$key][$uniqid] = $value;

            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
        }

        return $data;


    }

    // 获取商品品类
    public function getClass($data) {

        $class = array();

        foreach ($data as $v) {

            if (trim($v['class_name']) != "") {

                $class[$v['class_id']] = $v['class_name'];
            }

        }

        return $class;

    }

    // 商品品牌
    public function getBrand($data) {

        $brand = array();

        foreach ($data as $v) {

            if (trim($v['brand_name']) != "") {

                $brand[$v['brand_id']] = $v['brand_name'];

            }

        }

        return $brand;

    }

    //


    /** 其他Action **/


}

/*************************** end ************************************/