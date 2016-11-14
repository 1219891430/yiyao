<?php
/**
 * 车存Model
 * User: sudi
 */

namespace Common\Model;


use Think\Model;

class CarportInfoModel extends Model{

    protected $tableName="carsale_stock";
    //获取某个业务员的实时车存
    public function getCarportGoods($org_parent_id,$staff_id)
    {
        $where["zdb_carsale_stock.org_parent_id"]=$org_parent_id;
        $where["zdb_carsale_stock.staff_id"]=$staff_id;
        //$where["zdb_carsale_stock.goods_num"]=array("neq",0);
        $list= $this->field("zdb_goods_info.goods_id,zdb_goods_info.goods_spec,zdb_goods_info.goods_name,num_string,zdb_carsale_stock.goods_num,zdb_carsale_stock.staff_id,zdb_carsale_stock.org_parent_id")
        ->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_carsale_stock.goods_id")->where($where)->select();
        foreach($list as $k=>$v){
        	$list[$k]["goods_name"]=$v["goods_name"]."/".$v["goods_spec"];
        }
        return $list;
    }
	
	
	/**
	 * 更新车存
	 *
	 * @param array  $data[] =  array('staff_id' => '', 'goods_id' => '', 'goods_num' => '', 'org_parent_id' => '', 'cv_id' => '')
	 * @param string $remark 更新车存日志备注
	 * @param string $type   add增加 delete减少
	 * @return bool
	 * stock_type= 1车销申请 +
					/2终端退货 +
					/3调换退货 +
					/4 车销 -
					//5调换出货 -
					//6车销退库
	 */
	public function updateCarInfo($data, $remark = "", $type = "add",$stock_type)
	{
		$time = time();

		foreach ($data as $k => $v) {
			$where["staff_id"] = $v["staff_id"];
			$where["goods_id"] = $v["goods_id"];
			$count = M("carsale_stock")->where($where)->find();

			$resd = getTransUnit($v["cv_id"], $v["goods_num"]);

			if ($count) {
				//当前车存的数据
				$resl = M("carsale_stock")->where($where)->find();

				if ($type == "add") {
					$res = M("carsale_stock")->where($where)->setInc("goods_num", $resd['good_num']);
				} else {
					$res = M("carsale_stock")->where($where)->setDec("goods_num", $resd['good_num']);
				}
				$resstock=M("carsale_stock")->where($where)->find();
				$numString=getGoodsUnitString($resstock["goods_id"],$resstock["goods_num"]);
				$data2["num_string"]=$numString;
				M("carsale_stock")->where($where)->save($data2);
				unset($data2);

				if ($res) {
					
					$data1["goods_id"] = $resl["goods_id"];
					$data1["staff_id"] = $resl["staff_id"];
					
					$data1["org_parent_id"] = $resl["org_parent_id"];
					$data1["remark"] = $remark;
					$data1["datetime"] = $time;
					if ($type == "add") {
						$data1["bianhua"] = $remark."+" . $resd['good_num'];
					} else {
						$data1["bianhua"] = $remark."-" . $resd['good_num'];
					}
					$data1["goods_num"]=$resd['good_num'];
					$goods_string=getGoodsUnitString($where["goods_id"],$data1["goods_num"]);
					$data1["goods_string"] = $goods_string;
					$data1["stock_num"]=$resstock["goods_num"];
					$data1["stock_string"] = $numString;
					$data1["stock_type"] =$stock_type;
					$result = M("carsale_stock_log")->add($data1);

					unset($data1);
				}

			} else {
				$resa = M("goods_info")->where("goods_id=" . $v["goods_id"])->find();
				if ($type == "add") {
					$
					$data2["goods_name"] = $resa["goods_name"] ;
					$data2["goods_spec"] =  $resa["goods_spec"];
					$data2["org_parent_id"] = $v["org_parent_id"];
					$data2["staff_id"] = $v["staff_id"];
					$data2["goods_id"] = $v["goods_id"];
					$data2["goods_num"] = $resd['good_num'];
					$numString=getGoodsUnitString($data2["goods_id"],$data2["goods_num"]);
				    $data2["num_string"]=$numString;
					
					$res = M("carsale_stock")->add($data2);

					unset($data2);
					if ($res) {
						
						
						$data1["org_parent_id"] = $v["org_parent_id"];
						$data1["goods_id"] = $v["goods_id"];
						$data1["goods_num"] = $resd['good_num'];
						$data1["goods_string"] = $numString;
						$data1["stock_num"]=$resd['good_num'];
						$data1["stock_string"] = $numString;
						$data1["remark"] = $remark;
						$data1["datetime"] = $time;
						$data1["staff_id"] = $v["staff_id"];
						$data1["bianhua"] = $remark."+" . $resd['good_num'];
						$data1["stock_type"] =$stock_type;
						$result = M("carsale_stock_log")->add($data1);

					} 

					unset($data1);
				} else {
					
					$data2["goods_name"] = $resa["goods_name"] ;
					$data2["goods_spec"] = $resa["goods_spec"] ;
					$data2["org_parent_id"] = $v["org_parent_id"];
					$data2["staff_id"] = $v["staff_id"];
					$data2["goods_id"] = $v["goods_id"];
					
					
					$data2["goods_num"] = ($resd['good_num'] - 2 * $resd['good_num']);
					$numString=getGoodsUnitString($data2["goods_id"],$data2["goods_num"]);
				    $data2["num_string"]=$numString;
					$res = M("carsale_stock")->add($data2);
					unset($data2);
					if ($res) {
						$data1["org_parent_id"] = $v["org_parent_id"];
						$data1["goods_id"] = $v["goods_id"];
						$data1["goods_num"] = "-" . $resd['good_num'];
						$data1["goods_string"] = $numString;
						$data1["stock_num"]=$resd['good_num'];
						$data1["stock_string"] = $numString;
						$data1["remark"] = $remark;
						$data1["datetime"] = $time;
						$data1["staff_id"] = $v["staff_id"];
						$data1["bianhua"] =$remark. "-" . $resd['good_num'];
						$data1["stock_type"] =$stock_type;
						$result = M("carsale_stock_log")->add($data1);

					}
					unset($data1);
				}
			}
		}
	}
    

} 