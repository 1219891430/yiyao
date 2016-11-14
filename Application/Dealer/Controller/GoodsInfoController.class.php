<?php

/*******************************************************************
 ** 文件名称: GoodsInfoController.class.php
 ** 功能描述: 经销商PC端商品展示控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class GoodsInfoController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

		$where['ds.org_parent_id'] = $_SESSION['org_parent_id'];

	    $goods = M("depot_stock")->alias("ds")
            ->join("__GOODS_INFO__ as gi on ds.goods_id=gi.goods_id")
            ->join("__GOODS_CLASS__ as gc on gi.class_id=gc.class_id")
            ->join("__GOODS_BRAND__ as gb on gi.brand_id=gb.brand_id")
            //->join("__GOODS_PRODUCT__ as gp on gi.goods_id=gp.goods_id")
            ->field("ds.*, gi.*, gc.class_name, gb.brand_name")
			->where($where)
            ->order('ds.goods_id desc')
            ->select();
        
        foreach ($goods as $k => $v) {
            $unit = M("goods_product")->where(array("goods_id"=>$v["goods_id"]))->select();
            foreach ($unit as $u) {
                switch ($u["goods_unit_type"]) {
                    case 3:
                        $goods[$k]["big_unit"] = $u["goods_unit"];
                        break;
                    case 2:
                        $goods[$k]["mid_unit"] = $u["goods_unit"];
                        break;
                    case 1:
                        $goods[$k]["sm_unit"] = $u["goods_unit"];
                        break;
                    default:
                        break;
                }
            }
        }

        $this->assign("goods", $goods);

		$this->display();
    }

     // 根据品牌、商品名称查询数据，返回json
    // 检查人员：richie
    // 检查时间：2016-07-19
    


	public function selGoodsAction()
    {
    	$iBrandId=I("post.brand",0);
    	$iClassId=I("post.class_id",0);
    	$sGoods=I("post.goods","");
		$org_parent_id=session("org_parent_id");
		

    	$mGoods=new \Common\Model\GoodsInfoModel();
    	$mConvert=M("goods_product");
    	$aGoods=$mGoods->selGoods($iBrandId,$iClassId,$sGoods,0);
//  	$aFiled=array("cv_id,goods_id,goods_cv,goods_code,goods_unit,goods_unit_type,goods_jin_price,goods_base_price,goods_min_price,goods_max_price,goods_last_price");
    	for($i=0;$i<count($aGoods);$i++) {
			
			
			$where["goods_id"]=$aGoods[$i]["goods_id"];
			$where["org_parent_id"]=$org_parent_id;
			$orgConvertlist=M("org_goods_convert")->field("cv_id,goods_id,goods_unit,unit_default,goods_unit_type,goods_jin_price,goods_base_price")->where($where)->select();
			
			if($orgConvertlist){
				$aGoods[$i]["convert_data"]=$orgConvertlist;
			}else{
				unset($aGoods[$i]);
			}
			
    	    
    	}
        
        if($aGoods)
    	$this->aReturn=array("res"=>1,"data"=>$aGoods,"count"=>count($aGoods));
    			else
    		$this->aReturn=array("res"=>0);
    	echo $this->ajaxReturn($this->aReturn,"json");
    }
	/** 其他Action **/
    public function selGoodsAndStockAction()
    {
    	$iBrandId=I("post.brand",1);
    	$iClassId=I("post.class_id",0);
    	$depot_id=I("post.depot_id",1);
    	$sGoods=I("post.goods","");
		$org_parent_id=session("org_parent_id");
		

    	$mGoods=new \Common\Model\GoodsInfoModel();
    	$mConvert=M("goods_product");
    	$aGoods=$mGoods->selGoods($iBrandId,$iClassId,$sGoods,0,0,$org_parent_id);
//  	$aFiled=array("cv_id,goods_id,goods_cv,goods_code,goods_unit,goods_unit_type,goods_jin_price,goods_base_price,goods_min_price,goods_max_price,goods_last_price");
    	for($i=0;$i<count($aGoods);$i++) {
			
			
			$where["goods_id"]=$aGoods[$i]["goods_id"];
			$where["org_parent_id"]=$org_parent_id;
			$orgConvertlist=M("org_goods_convert")->field("cv_id,goods_id,goods_unit,unit_default,goods_unit_type,goods_jin_price,goods_base_price")->where($where)->select();
			if($orgConvertlist){
				$where1["goods_id"]=$aGoods[$i]["goods_id"];
				$goodsRes=M("goods_info")->where($where1)->find();
			
				$where2["goods_id"]=$aGoods[$i]["goods_id"];
				$where2["depot_id"]=$depot_id;
				$depotStockRes=M("depot_stock")->where($where2)->find();
			
				foreach($orgConvertlist as $k=>$v){
					if($v["goods_unit_type"]==1){
						$orgConvertlist[$k]["num"]=$depotStockRes["small_stock"];
					}elseif($v["goods_unit_type"]==2){
						$orgConvertlist[$k]["num"]=floor($depotStockRes["small_stock"]/$goodsRes['goods_convert_m']);
					}elseif($v["goods_unit_type"]==3){
						$orgConvertlist[$k]["num"]=floor($depotStockRes["small_stock"]/($goodsRes['goods_convert_m']*$goodsRes['goods_convert_b']));
					}
				
				}
				if($orgConvertlist[$k]["num"]==null){
					$orgConvertlist[$k]["num"]=0;
				}
    	    	$aGoods[$i]["convert_data"]=$orgConvertlist;
			}else{
				unset($aGoods[$i]);
			}
			
    	}
        
        if($aGoods)
    	   $this->aReturn=array("res"=>1,"data"=>$aGoods,"count"=>count($aGoods));
    	else
    	   $this->aReturn=array("res"=>0);
    	echo $this->ajaxReturn($this->aReturn,"json");
    }
	/** 其他Action **/


}

/*************************** end ************************************/