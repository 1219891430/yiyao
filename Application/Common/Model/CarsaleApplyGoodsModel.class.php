<?php
/**
 * 申请车存产品
 * User: zy
 * Date: 15-1-29
 * Time: 下午3:50
 */

namespace Common\Model;


use Think\Model;

class CarsaleApplyGoodsModel extends Model{

    protected $tableName="carsale_apply_goods";
    //获取入库商品的资料
    public function getApplyGoods($apply_code,$org_parent_id)
    {
        $where["ca.org_parent_id"]=$org_parent_id;
        $where["ca.apply_code"]=$apply_code;

        $res = $this->field("apply_code,p1.goods_name,goods_code,p1.cv_id,p1.goods_id,apply_num,apply_price,(apply_num*apply_price) as apply_total_price,apply_remark")
            ->table("zdb_carsale_apply_goods p1")
            ->join("zdb_carsale_apply ca on ca.apply_id=p1.apply_id")
            ->join("zdb_goods_info p2 on  p1.goods_id=p2.goods_id")
            ->join("zdb_org_goods_convert p3 on p3.goods_id=p2.goods_id and p1.cv_id=p3.cv_id")->where($where)->select();
        return $res;
    }
    
} 