<?php

/*******************************************************************
 ** 文件名称: GoodsConvertModel.class.php
 ** 功能描述: 产品价格转换系数。
 ** 创建人员: zy
 ** 创建日期: 15-1-14 下午4:00
 ** 检查人员：richie
 ** 检查时间：2016-07-19
*******************************************************************/

namespace Common\Model;
use Think\Model;

class GoodsConvertModel extends Model {

    protected $tableName="org_goods_convert";
    protected $field="";
	
	// 根据参数goods_id获取产品参数
	// 检查人员：richie
	// 检查时间：2016-07-19
	public function getGoodsConvert($arr_field, $org_parent_id, $goods_id, $depot_id, $goods_convert_s = '', $goods_convert_m = '',$goods_convert_b = '')
	{
		// 查询商品大中小单位转换系数
		$where["org_parent_id"] = $org_parent_id;
		$where["goods_id"] = $goods_id;
		if(empty($goods_convert_m) || empty($goods_convert))
		{
			$convert_info = M("goods_info")->field('goods_convert_s,goods_convert_m,goods_convert_b')->where($where)->find();

            $sid = eval("return {$convert_info['goods_convert_s']};");
            $mid = eval("return {$convert_info['goods_convert_m']};");
			$big = eval("return {$convert_info['goods_convert_b']};");

		}
		else
		{
            $sid = eval("return {$goods_convert_s};");
			$mid = eval("return {$goods_convert_m};");
			$big = eval("return {$goods_convert_b};");
		}

		// 查询商品库存
		$small_stock = 0;
		if(intval($depot_id) > 0)
		{
			$where['depot_id'] = $depot_id;
			$small_stock = M("depot_stock")->where($where)->getField("small_stock");
			unset($where['depot_id']);
		}
		
		// 查询货品表, 按单位大小排序
		$where["is_close"] = 0;
		$info = $this->where($where)->order('goods_unit_type asc')->select();
		
		// 计算大小中单位下的库存
		foreach ($info as &$v)
		{
			// 小单位库存
			if($v['goods_unit_type'] == 1){ $v['num'] = floor($small_stock / $sid); }
			
			// 中单位库存
			if($v['goods_unit_type'] == 2){ $v['num'] = floor($small_stock / $mid); }
			
			// 大单位库存
			if($v['goods_unit_type'] == 3){ $v['num'] = floor($small_stock / $big); }
        }
		
        return $info;
    }

    // 通过cv_id获得name
    public function get_name($cv_id)
	{
        $where['cv_id'] = $cv_id;
        $name = $this->where($where)->getField("goods_unit");
        return $name;
    }

    /**
     * 商品单位查询
     *
     * @param $goodId,$orgId
     * @return array
     */
    public function getGoodsConvertFromId($goodId,$orgId)
    {
        $where = array();
        $where['goods_id'] = $goodId;
        $where['org_parent_id'] = $orgId;
        $where['is_close'] = 0;
        $convertData =  $this->field('goods_unit,unit_default,goods_unit_type,goods_base_price')->where($where)->select();

        $defaultUnit = '无';
        $rsUnit = array('1' => array(),'2' => array(),'3' => array()); // 单位类别 1-小/2-中/3-大
        foreach ($convertData as $value) {
            $key = $value['goods_unit_type'];
            $rsUnit[$key] = $value;
        }
        return $rsUnit;
    }
} 
