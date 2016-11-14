<?php

/*******************************************************************
 ** 文件名称: GoodsController.class.php
 ** 功能描述: B2B商品详情页控制器
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-11
 *******************************************************************/

namespace WapMall\Controller;


class GoodsController extends BaseController {

    // 商品详情页
    // 创建人员: wangbo
    // 创建日期: 2016-08-1
    public function indexAction()
    {

        $depot_id = session("depot_id");
        $goods_id = I('goods_id');
        $org_id = I('org_id');

        $where['goods_id'] = $goods_id;

        //商品基本信息
        $goods_info =  M('goods_info')->alias('gi')
            // 品类
            ->join('left join __GOODS_CLASS__ as gc on gi.class_id=gc.class_id')
            // 品牌
            ->join('left join __GOODS_BRAND__ as gb on gi.brand_id=gb.brand_id')

            ->field('gi.*, gc.class_name, gb.brand_name')
            ->where($where)
            ->find();

        if(!$goods_info) {
            $this->error('未找到该商品');
            return;
        }

        $where['org_parent_id'] = $org_id;

        //货品价格
        $goods_price = M("org_goods_convert")
            ->where($where)
            ->select();

        // 查询活动
        $where["is_close"] = 0;
        $where["depot_id"] = $depot_id;
        $where["start_time"] = array("ELT", time());
        $where["end_time"] = array("EGT", time());
        $activity = M("activity")->where($where)->select();

        //print_r($activity);die();

        $act = [];

        foreach ($goods_price as &$item) {
            $item["act_price"] = $item["goods_base_price"];
            $item["is_act"] = 0;
            if ($activity) {
                foreach ($activity as $val) {

                    if ($val["cv_id"] == $item["cv_id"]) {

                        if ($val["act_type"] == 0) {
                            $item["act_price"] = $val["act_price"];
                            $item["is_act"] = 1;

                            $val["goods_name"] = $item["goods_name"];
                            $val["goods_unit"] = $item["goods_unit"];
                            $val["goods_base_price"] = $item["goods_base_price"];
                            $act[$val["act_id"]] = $val;
                        }

                        // 满减活动
                        if ($val["act_type"] == 1 && !isset($act[$val["act_id"]])) {

                            $val["goods_name"] = $item["goods_name"];
                            $val["goods_unit"] = $item["goods_unit"];
                            $act[$val["act_id"]] = $val;
                        }

                        // 赠品活动
                        if ($val["act_type"] == 2 && !isset($act[$val["act_id"]])) {
                            $val["goods_name"] = $item["goods_name"];
                            $val["goods_unit"] = $item["goods_unit"];
                            // 获取赠品信息
                            $song_goods = M("goods_info")->alias("gi")
                                ->join("left join __ORG_GOODS_CONVERT__ as ogc on ogc.goods_id=gi.goods_id")
                                ->field("gi.goods_name, gi.goods_spec, gi.main_img, ogc.goods_unit")
                                ->where("ogc.cv_id={$val['song_cv_id']} AND ogc.goods_id={$val['song_goods_id']}")
                                ->find();
                            $val["song_goods"] = $song_goods;
                            $act[$val["act_id"]] = $val;
                        }
                    }
                }
            }

        }

        //print_r($goods_price);die();
        //print_r($act);die();

        $this->assign("acts", $act);
        
        //热门商品
        $goods_hot = getNewestGoods(session('depot_id'), 5);
        //$goods_hot = getHotGoods(session('depot_id'), 5);

        //print_r($goods_hot);die();

        $this->assign('goods_hot', $goods_hot);
        //print_r($goods_hot);die();

        
        $this->assign('goods_info',$goods_info);
        
        $this->assign('goods_price',$goods_price);
       
        
        $this->display();
    }

    public function infoAction() {
        $depot_id = session("depot_id");
        $goods_id = I('goods_id');
        $org_id = I('org_id');

        $where['goods_id'] = $goods_id;

        //商品基本信息
        $goods_info =  M('goods_info')->alias('gi')
            // 品类
            ->join('left join __GOODS_CLASS__ as gc on gi.class_id=gc.class_id')
            // 品牌
            ->join('left join __GOODS_BRAND__ as gb on gi.brand_id=gb.brand_id')

            ->field('gi.*, gc.class_name, gb.brand_name')
            ->where($where)
            ->find();

        if(!$goods_info) {
            $this->error('未找到该商品');
            return;
        }

        $where['org_parent_id'] = $org_id;

        //货品价格
        $goods_price = M("org_goods_convert")
            ->where($where)
            ->select();

        // 查询活动
        $where["is_close"] = 0;
        $where["depot_id"] = $depot_id;
        $where["start_time"] = array("ELT", time());
        $where["end_time"] = array("EGT", time());
        $activity = M("activity")->where($where)->select();

        //print_r($activity);die();

        $act = [];

        foreach ($goods_price as &$item) {
            $item["act_price"] = $item["goods_base_price"];
            $item["is_act"] = 0;
            if ($activity) {
                foreach ($activity as $val) {

                    if ($val["cv_id"] == $item["cv_id"]) {

                        if ($val["act_type"] == 0) {
                            $item["act_price"] = $val["act_price"];
                            $item["is_act"] = 1;

                            $val["goods_name"] = $item["goods_name"];
                            $val["goods_unit"] = $item["goods_unit"];
                            $val["goods_base_price"] = $item["goods_base_price"];
                            $act[$val["act_id"]] = $val;
                        }

                        // 满减活动
                        if ($val["act_type"] == 1 && !isset($act[$val["act_id"]])) {

                            $val["goods_name"] = $item["goods_name"];
                            $val["goods_unit"] = $item["goods_unit"];
                            $act[$val["act_id"]] = $val;
                        }

                        // 赠品活动
                        if ($val["act_type"] == 2 && !isset($act[$val["act_id"]])) {
                            $val["goods_name"] = $item["goods_name"];
                            $val["goods_unit"] = $item["goods_unit"];
                            // 获取赠品信息
                            $song_goods = M("goods_info")->alias("gi")
                                ->join("left join __ORG_GOODS_CONVERT__ as ogc on ogc.goods_id=gi.goods_id")
                                ->field("gi.goods_name, gi.goods_spec, gi.main_img, ogc.goods_unit")
                                ->where("ogc.cv_id={$val['song_cv_id']} AND ogc.goods_id={$val['song_goods_id']}")
                                ->find();
                            $val["song_goods"] = $song_goods;
                            $act[$val["act_id"]] = $val;
                        }
                    }
                }
            }

        }

        // 输出

        $data = array();
        $data['act'] = $act;
        $data['info'] = $goods_info;
        $data['price'] = $goods_price;

        $this->ajaxReturn($data);
        
    }


    /** 其他Action **/


}

/*************************** end ************************************/