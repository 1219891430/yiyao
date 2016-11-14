<?php

/*******************************************************************
 ** 文件名称: DeliverBackModel.class.php
 ** 功能描述: 平台车销配送退库公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DeliverBackModel extends Model {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'car_return_stock';

    // 创建配送申请单
    public function addData($data) {
        // 检查数据完整性
        if (!array_key_exists("return_code", $data) || empty($data['return_code'])) {
            $data["return_code"] = create_uniqid_code("CRS", $_SESSION['admin_id']);
        }

        if (!array_key_exists("add_id", $data) || empty($data['add_id'])) {
            $data['add_id'] = session("admin_id");
        }

        if (!array_key_exists("time") || empty($data['time'])) {
            $data['add_time'] = time();
        } else {
            $data['add_time'] = strtotime($data['time']);
        }


        $apply_id = $this->add($data);

        foreach ($data["goods_info"] as $k=>$v) {
            // 查找 goods 信息
            $goods = M('goods_info')->where(array("goods_id"=>$v['goods_id']))->find();

            $goodsdata[$k]['return_id'] = $apply_id;
            $goodsdata[$k]['cv_id'] = $v['cv_id'];
            $goodsdata[$k]['goods_id'] = $v['goods_id'];
            $goodsdata[$k]['goods_num'] = $v['goods_num'];
            $goodsdata[$k]['goods_name'] = $goods['goods_name'];
            $goodsdata[$k]['goods_spec'] = $goods['goods_spec'];

            // 单位
            $where["cv_id"]=$v["cv_id"];

            $unit=M("goods_product")->where($where)->find();

            $goodsdata[$k]["goods_unit"]=$unit["goods_unit"];

        }

        $res=M("car_return_stock_goods")->addAll($goodsdata);
        if($res){
            return true;
        }else{
            return false;
        }

    }

    public function editData($id,$data) {

        $where['return_id'] = $id;

        M("car_return_stock")->where($where)->save($data);

        $CRSG = M("car_return_stock_goods");

        // 删除申请单下所有商品
        $CRSG->where($where)->delete();

        foreach ($data["goods_info"] as $k=>$v) {
            // 查找 goods 信息
            $goods = M('goods_info')->where(array("goods_id"=>$v['goods_id']))->find();

            $goodsdata[$k]['return_id'] = $id;
            $goodsdata[$k]['cv_id'] = $v['cv_id'];
            $goodsdata[$k]['goods_id'] = $v['goods_id'];
            $goodsdata[$k]['goods_num'] = $v['goods_num'];
            $goodsdata[$k]['goods_name'] = $goods['goods_name'];
            $goodsdata[$k]['goods_spec'] = $goods['goods_spec'];
            // 单位
            $where["cv_id"]=$v["cv_id"];

            $unit=M("goods_product")->where($where)->find();

            $goodsdata[$k]["goods_unit"]=$unit["goods_unit"];
        }

        $res=$CRSG->addAll($goodsdata);
        if($res){
            return true;
        }else{
            return false;
        }
    }

	// 其他操作
	
	
}

/****************************** end *******************************/