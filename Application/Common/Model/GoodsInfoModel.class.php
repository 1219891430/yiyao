<?php
/**
 * 商品model.
 * User: zy
 * Date: 15-1-13
 * Time: 下午5:15
 */

namespace Common\Model;

use Think\Model;

class GoodsInfoModel extends Model
{
    protected $tableName = "goods_info";
    //根据商品名称跟品牌id获取数据,模糊查询
    //$isC=1要得到包括封存的;$isC=0不包括封存的
    public function selGoods( $brand_id, $class_id,$goods_name, $isC = 1, $depot_id=0, $org_id=0){

        if ($isC == 0) {
        	 $where["is_close"] = 0;
		}
        
        if ($brand_id != 0) {
        	$where["brand_id"] = $brand_id;
        }

		if ($class_id != 0) {
        	$where["class_id"] = $class_id;
        }
           
		if (!empty($goods_name)) {
			$where["goods_name"] = array("like", "%$goods_name%");
		}

        if ($org_id > 0) {
            $where["ds.org_parent_id"] = $org_id;
        }

        if ($depot_id > 0) {
            $where["ds.depot_id"] = $depot_id;
        }

        $list=$this->field("zdb_goods_info.*,zdb_depot_area.area_name as goods_area")
            ->where($where)
            ->join("left join zdb_depot_area_goods on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")
            ->join("left join zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
            ->join("left join __DEPOT_STOCK__ as ds on zdb_goods_info.goods_id=ds.goods_id")
            ->select();
		foreach($list as $k=>$v){
			if($v["goods_area"]==null){
				$list[$k]["goods_area"]="";
			}
		}
		
		return $list;
    }


    public function selGoodsId($brand_id, $goods_name, $isC = 1)
    {
        if ($isC == 0) {
            $where["is_close"] = 0;
        }
        
        if ($brand_id != 0){
        	$where["brand_id"] = $brand_id;
        }
        if (!empty($goods_name)) {
         	$where["goods_name"] = array("like", "%$goods_name%");
		}   
        
        $temp = $this->where($where)->field("goods_id")->select();
        if ($temp) {
            foreach ($temp as &$v) {
                $v = $v['goods_id'];
            }
        }
        return $temp;
    }

   
    
    

    //检查商品和规格名称重复
    public function check_type($data)
    {
        $where['goods_name'] = $data['goods_name'];
        $where['goods_spec'] = $data['goods_spec'];
        
        $info = $this->where($where)->select();
        if (empty($info)) {
            return true;
        } else {
            return false;
        }

    }

    public function getGoods($org_parent_id)
    {
        $goodsList = M("goods_info")->select();
        foreach ($goodsList as $k => $v) {
            $goodsListNew[$v["goods_id"]] = $v;
        }
        return $goodsListNew;
    }

    public function ajax_get_goods($where, $field)
    {
        $info = $this->where($where)->field($field)->select();
        return $info;
    }
	
	//
	public function addGoods($data){
		$good=$data["good"];
		$product=$data["product"];
		$goods_id=M("goods_info")->add($good);
		foreach($product as $k=>$p){
			$product[$k]["goods_id"]=$goods_id;
		}
		$resp=M("goods_product")->addAll($product);
		if($goods_id&&$resp){
			return true;
		}else{
			return false;
		}
		
	}
	
	public function deleteGoods($goods_id){

	    // 查询是否商品是否有库存
        $has = M('depot_stock')->where("goods_id=$goods_id")->count();

        if ($has > 0) {
            $data = [
                'status' => false,
                'msg' => '商品有库存，禁止删除'
            ];

            return $data;
        }

		$where["goods_id"]=$goods_id;
		$resinfo=M("goods_info")->where($where)->delete();
		$respro=M("goods_product")->where($where)->delete();
		if($resinfo&&$respro){
            $data = [
                'status' => true,
                'msg' => '删除成功'
            ];

            return $data;
		}
		else{
            $data = [
                'status' => false,
                'msg' => '删除失败'
            ];

            return $data;
		}
		
	}
	
	public function editGoods($data){
		$good=$data["good"];
		$product=$data["product"];
		
		$goods_id=$good["goods_id"];
		unset($good["goods_id"]);
		$where["goods_id"]=$goods_id;
		$res=M("goods_info")->where($where)->save($good);
		
		//M("goods_product")->where($where)->delete();
		
		$data1["goods_spec"]=$product['goods_spec'];
		$data1["goods_name"]=$product['goods_name'];
		$data1["goods_unit"]=$product["goods_unit_s"];
		
		$data2["goods_spec"]=$product['goods_spec'];
		$data2["goods_name"]=$product['goods_name'];
		$data2["goods_unit"]=$product["goods_unit_m"];
		
		$data3["goods_spec"]=$product['goods_spec'];
		$data3["goods_name"]=$product['goods_name'];
		$data3["goods_unit"]=$product["goods_unit_b"];
		
		$where1["goods_id"]=$goods_id;
		$where2["goods_id"]=$goods_id;
		$where3["goods_id"]=$goods_id;
		
		$where1["goods_unit_type"]=1;
		$where2["goods_unit_type"]=2;
		$where3["goods_unit_type"]=3;
		
		$res1=M("goods_product")->where($where1)->save($data1);
		
		//检查中单位
		$row2 = M("goods_product")->where($where2)->find();
		if(!$row2 && !empty($data2["goods_unit"])){
			$data2["goods_id"]=$goods_id;
			$data2["goods_unit_type"]=2;
			$res2=M("goods_product")->add($data2);
		}
		else{
			$res2=M("goods_product")->where($where2)->save($data2);
		}
		//更新经销商货品价格表的单位名称
		M('org_goods_convert')->where($where2)->save( array('goods_unit'=>$data2["goods_unit"]) );

		//检查大单位
		$row3 = M("goods_product")->where($where3)->find();
		if(!$row3 && !empty($data3["goods_unit"])){
			$data3["goods_id"]=$goods_id;
			$data3["goods_unit_type"]=3;
			$res3=M("goods_product")->add($data3);
		}
		else{
			$res3=M("goods_product")->where($where3)->save($data3);
		}
        //更新经销商货品价格表的单位名称
        M('org_goods_convert')->where($where3)->save( array('goods_unit'=>$data3["goods_unit"]) );
		
		if($res||$res1||$res2||$res3){
			return true;
		}else{
			return false;
		}
		
	}

    
} 