<?php

/** ****************************************************************
 ** 文件名称:GoodsBrandModel.class.php
 ** 功能描述:商品品牌数据库操作。
 ** 创建人员:sudi
 ** 创建日期:2016-08-02
 ******************************************************************/
namespace Common\Model;
use Think\Model;
class GoodsBrandModel extends Model{
	
	protected $tableName="goods_brand";
	
	public function getGoodsBrandList(){
		$where['is_close']=0;
		$list=$this->where($where)->field("brand_id,brand_name")->select();
		return $list;
	}
	
	/*****************************************************************
	** 函数名称: deleteBrand
	** 功能描述: 删除品牌
	** 输入: $brand_id,$is_close
	** 返回:
	** 检查人员: sd
	** 检查日期: 2016-08-02
	****************************************************************/
	public function closeBrand($brand_id,$is_close){
		$where["brand_id"]=$brand_id;
		if($is_close){
			$data["is_close"]=1;
			$this->where($where)->save($data);
		}else{
			$data["is_close"]=0;
			$this->where($where)->save($data);
		}
		
	}
	/*****************************************************************
	** 函数名称: deleteBrand
	** 功能描述: 删除品牌
	** 输入: $brand_id
	** 返回: true or false
	** 检查人员: sd
	** 检查日期: 2016-08-02
	****************************************************************/
	public function deleteBrand($brand_id){

        $has = M('goods_info')->where("brand_id=$brand_id")->count();

        if ($has > 0) {
            $data = array(
                'msg'=>"该品牌存在商品，不能删除",
                'status'=>false
            );
            return $data;
        }

		$where["brand_id"]=$brand_id;
		$res=$this->where($where)->delete();
		if($res){
            $data = array(
                'msg'=>"删除成功",
                'status'=>true
            );

		}else{
            $data = array(
                'msg'=>"删除失败，请重试",
                'status'=>false
            );
		}
        return $data;
	}

    /*****************************************************************
     ** 函数名称: getAllBrand
     ** 功能描述: 根据参数获取品牌集合数组
     ** 输入: org_parent_id, is_close, field
     ** org_parent_id, 整型, 经销商ID
     ** is_close, 整型, is_close=all所有品牌；is_close=1已封存；is_close=0未封存
     ** field, 字符型, 查询的表字段
     ** 返回: 品牌集合数组
     ** 调用模块: Home/DepotInController,
     ** 完成作者:
     ** 完成日期:
     ** 检查人员:
     ** 检查日期:
     ** 备注：该方法中的is_close应该用不到, 封存概念应该没有用到
     ****************************************************************/
    public function getAllBrand($org_parent_id, $is_close="all", $field="*")
    {
        $where["org_parent_id"]=$org_parent_id;
        if(!$is_close=="all")
        {
            if($is_close==1) $where["is_close"]=1;
            if($is_close==0) $where["is_close"]=0;
        }
        if($field=="*") return $this->where($where)->select();
        else return $this->field($field)->where($where)->select();
    }

    //品牌是否重复存在
    public function isCheckName($id,$name){
        $where=array(
            'brand_name' => $name
        );
        $row = $this->where($where)->find();
        if($row){
            if($id>0 && $id == $row['brand_id']){
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }
}
?>