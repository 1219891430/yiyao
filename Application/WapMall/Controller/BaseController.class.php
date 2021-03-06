<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/24
 * Time: 13:42
 */

namespace WapMall\Controller;


use Think\Controller;

class BaseController extends Controller{

    public function __construct(){
        parent::__construct();

        $depot = queryDepot();

        $this->assign('depot', $depot);

        $depot_id = session('depot_id');
        // 仓库下所有经销商
        $orgs = queryDealer($depot_id);

        $this->assign('orgs', $orgs);

        // 所有品类
        $where['parent_class'] = 0;

        $class=M("goods_class")->where($where)->select();

        foreach($class as $key=>$val) {
            $childs = M('goods_class')->where('parent_class = ' . intval($val['class_id']))->select();

            $class[$key]["child"] = $childs;
        }

        $this->assign('class', $class);

        //print_r($class);die();

        $brand = queryBrand($depot_id);

        $this->assign("brand", $brand);

    }

}