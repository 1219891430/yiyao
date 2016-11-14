<?php

/*******************************************************************
 ** 文件名称: DepotStockModel.class.php
 ** 功能描述: 经销商仓库库存公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class DepotStockModel extends Model {

	// 数据主表: 仓库库存表
	protected $tableName = 'depot_stock';
	
	// 其他操作
	
	/*****************************************************************
	** 函数名称: updateStock
	** 功能描述: 更新仓库实时库存
	** 输入: data, in_or_out_type(出库类型、入库类型), $type, staff_id
	** data[depot_id], 整型, 仓库ID
	** data[goods_id], 整型, 商品ID
	** data[small_stock], 整型, 货品数量
	** data[org_parent_id], 整型, 经销商ID
	** data[cv_id], 整型, 货品ID
	** remark, 字符, 仓库库存修改备注
	** type, 字符, 'add'代表增加库存, 'del'代表减库存
	** staff_id, 整型, 操作人ID
	** 返回: true
	** 调用模块: Admin/DepotInController,
	** 检查人员: sudi
	** 检查日期: 2016-05-19
	****************************************************************/
	public function updateStock($data, $remark, $type="add", $in_out_type)
	{
		
		$time=time();
		foreach($data as $k=>$v)
		{
			// 查询仓库是否存在该商品
			$where["depot_id"]=$v["depot_id"];
			$where["goods_id"]=$v["goods_id"];
//			if($v["org_parent_id"]){
//				$where["org_parent_id"]=$v["org_parent_id"];
//			}
				
			
			
			$count=M("depot_stock")->where($where)->count();

			// 货品数量转换成最小单位下
			$resd = getTransUnit($v["cv_id"],$v["small_stock"]);
            
    		if($count)
			{
				//当前库存数据
				$resl=M("depot_stock")->where($where)->find();
				// 增加或减少库存
				if($type=="add")
				{
    				$res=M("depot_stock")->where($where)->setInc("small_stock",$resd['good_num']);
					
					
    			}
				else
				{
    				$res=M("depot_stock")->where($where)->setDec("small_stock",$resd['good_num']);
    			}

				// 增加库存日志
    			if($res)
				{
					$resDepotGoods=M("depot_stock")->field("goods_id,small_stock")->where($where)->find();
					$numString=getGoodsUnitString($resDepotGoods["goods_id"],$resDepotGoods['small_stock']);
					$dataNumString["stock_string"]=$numString;
					M("depot_stock")->where($where)->save($dataNumString);
    				
    				$data1["goods_id"]=$resl["goods_id"];
    				$data1["depot_id"]=$resl["depot_id"];
                    $data1["org_parent_id"]=$v["org_parent_id"];
    				
    				$data1["datetime"]=$time;
    				
                    $data1["inout_type"]=$in_out_type;
    				if($type=="add"){
    					$data1["is_in_out"]=1; 
    					 $data1["bianhua"]=$remark."+".$resd['good_num'];
    					 $data1["small_stock"]=$resl["small_stock"]+$resd['good_num'];
    				}
    				else {
    					$data1["is_in_out"]=2;
    					$data1["bianhua"]=$remark."-".$resd['good_num'];
    					$data1["small_stock"]=$resl["small_stock"]-$resd['good_num'];
    				}

    				$red=M("depot_stock_log")->add($data1);
    			}
    		}
			else
			{
				// 添加商品和数量
				$data2["org_parent_id"]=$v["org_parent_id"];
    			$data2["depot_id"]=$v["depot_id"];
    			$data2["goods_id"]=$v["goods_id"];
    			$data2["small_stock"]=$resd['good_num'];
    			
    			$numString=getGoodsUnitString($v["goods_id"],$resd['good_num']);
				$data2["stock_string"]=$numString;
    			$res=M("depot_stock")->add($data2);
				

				// 添加日志
    			if($res){
					if($type=="add"){
						$data1["goods_id"]=$v["goods_id"];
						$data1["depot_id"]=$v["depot_id"];
						$data1["small_stock"]=$resd['good_num'];
					
						$data1["org_parent_id"]=$v["org_parent_id"];
    				
    				
    					$data1["is_in_out"]=1;
                    	$data1["inout_type"]=$in_out_type;
					
						$data1["datetime"]=$time;
						
						$data1["bianhua"]=$remark."+".$resd['good_num'];
						M("depot_stock_log")->add($data1);
					}
				}
    		}
    	}
    }


	/*****************************************************************
	** 函数名称: checkStockFunction
	** 功能描述: 出库检查货品库存
	** 输入: goods_id, repertory_id, org_parent_id, cv_id, num
	** goods_id, 整型, 商品ID
	** repertory_id, 整型, 仓库ID
	** org_parent_id, 整型, 经销商ID
	** cv_id, 整型, 货品ID
	** num, 整型, 货品数量
	** 返回: 库存是否充足, ture代表充足, 可以出库
	** 调用模块: Home/DepotOutController,  
	** 检查人员: richie
	** 检查日期: 2016-05-19
	****************************************************************/
	public function checkStockFunction($goods_id,$repertory_id,$org_parent_id,$cv_id,$num)
	{
		// 查询仓库实时库存, 最小单位
		$where["goods_id"]=$goods_id;
		$where["depot_id"]=$repertory_id;
		$where["org_parent_id"]=$org_parent_id;
		$res=M("depot_stock")->field("small_stock")->where($where)->find();

		// 出库货品转化最小单位数量
		$resd=getTransUnit($cv_id,$num);
	
		// 库存是否充足
		if($resd["good_num"]>$res["small_stock"]) 
		     return false;
		else
			 return true;
}
	
	
	
}

/****************************** end *******************************/