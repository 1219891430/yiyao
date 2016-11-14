<?php

/*******************************************************************
 ** 文件名称: CarsaleBackController.class.php
 ** 功能描述: 经销商PC端车存退库控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CarsaleBackController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		
        $mCp=M("carsale_return_stock");

        $where["org_parent_id"]=session("org_parent_id");
        $p=I("get.p",1);
        $pnum=I("get.pnum",10);
        $staff_id=I("get.staff_id",0);
        $repertory_id=I("get.depot_id",0);
        $start=I("get.start_time",0);
        $end=I("get.end_time",0);
        if($staff_id)
            $where["staff_id"]=$staff_id;
        //if($repertory_id)
            //$where["repertory_id"]=$repertory_id;
        if($start!=0&&$end!=0)
            $where["add_time"]=array(array("egt",strtotime($start)),array("elt",strtotime($end)+24*60*59));
		
        $total=$mCp->field("return_id")
            ->where($where)
            ->count();

        $aReturn=$mCp->field("return_id,return_code,repertory_id,return_status,staff_id,add_time as create_time")
            ->where($where)
            ->order("add_time desc")
            ->page($p,$pnum)->select();
        $page=get_page_code($total,$pnum,$p, $page_code_len = 5);
		
		
		//仓库列表
		$aDepot=M("depot_info")->field("repertory_id,repertory_name")->select();
		//人员列表
		$aStaff=M("org_staff")->where("role_id=3 and org_parent_id=".session("org_parent_id"))->select();
		$newDepot=array();
		foreach($aDepot as $v){
			$newDepot[$v["repertory_id"]]=$v["repertory_name"];
		}
		$newStaff=array();
		foreach($aStaff as $v){
			$newStaff[$v["staff_id"]]=$v["staff_name"];
		}
        if($aReturn)
        {
            for($i=0;$i<count($aReturn);$i++)
            {
            	$aReturn[$i]["repertory_name"]=$newDepot[$aReturn[$i]["repertory_id"]];
            	$aReturn[$i]["staff_name"]=$newStaff[$aReturn[$i]["staff_id"]];
                $aReturn[$i]["time"]=date("Y-m-d H:i:s",$aReturn[$i]["create_time"]);
                $aReturn[$i]["status"]=getCarportReturnStatus($aReturn[$i]["return_status"],false);
            }
        }
		
       //仓库列表
        
        $this->assign("aStaff",$aStaff);
        $this->assign("depotList",$aDepot);
        $this->assign("aReturn",$aReturn);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
//      $this->assign("inType",getDepotInType(0,true));
        $this->assign('urlPara',array("staff_id"=>$staff_id,"start_time"=>$start,"end_time"=>$end,"del"=>$del));
        $this->display();
    }


	/** 其他Action **/
    public function addAction()
    {
        $time=date("Y-m-d H:i");
        $code=create_uniqid_code("CR");

        $aStaff=M("org_staff")->where("role_id=3 and org_parent_id=".session("org_parent_id"))->select();
        $aDepot=M("depot_info")->where("repertory_id= ".$_SESSION['depot_id'] )->field("repertory_id,repertory_name")->select();
        $this->assign("depotList",$aDepot);
        $this->assign("code",$code);
        $this->assign("time",$time);
        $this->assign("aStaff",$aStaff);
        $this->assign("staff_name",session("staff_name"));
        $this->display();
        
        
    }
	
	
	//添加车销退库单 （后台）

    public function doAddAction(){
    	$data["staff_id"]=I("post.staff_id",2);
        $data["repertory_id"]=I("post.depot_id",1);
        $data["org_parent_id"]=session("org_parent_id");
        $data["return_code"]= create_uniqid_code("CR");
        $data["return_status"]  =  1;
        $data["is_admin_order"]  =   1;
        $data["add_id"]=session("staff_id"); 
		$data["add_time"]    =   time();
        $orderGoods=json_decode($_POST["goods_info"],true);
        $data["return_remark"]  =   I("post.goods_remark",'');
        $affect = false;

        if($data["repertory_id"] != $_SESSION['depot_id']){
            $data["repertory_id"] = $_SESSION['depot_id'];
        }


        if (!empty($orderGoods) && count($orderGoods) > 0) {
        	$isHaveGoods=false;
        	while (list($key, $value) = each($orderGoods)) {
        		if($orderGoods[$key]["goods_num"]){
        			$isHaveGoods=true;
					break;
				}
			}
			if($isHaveGoods){
				$affect=M("carsale_return_stock")->data($data)->add();
			}
			
            
		}
		if($affect !== false) {
           $count=count($orderGoods);
           $datadd=array();

            while (list($key, $value) = each($orderGoods)) {

                if($orderGoods[$key]["goods_num"]){

                	$orderGoods[$key]["return_id"]=$affect;

                    $orderGoods[$key]["goods_unit"]=M("OrgGoodsConvert")->where("cv_id={$orderGoods[$key]["cv_id"]}")->getField('goods_unit');

                	$aGoods = M("GoodsInfo")->where("goods_id=%d",array($orderGoods[$key]["goods_id"]))->find();
                	$orderGoods[$key]["goods_name"] = $aGoods["goods_name"];
                	$orderGoods[$key]["goods_spec"] = $aGoods["goods_spec"];

                    $datadd[]=$orderGoods[$key];

                }

            }
            $res=M("carsale_return_stock_goods")->addAll($datadd);

            $org_parent_id = session("org_parent_id");
               
            if($res !== false) {
                $return_stock_num = M("carsale_return_stock")->where("org_parent_id=$org_parent_id AND return_status=1")->count();

                session("return_stock_num", intval($return_stock_num));

                aJsonReturn("1","创建成功");

            }


            else

                aJsonReturn("0","创建失败");

        }else
            aJsonReturn("0","创建失败");

    }


    public function editAction()
    {
        
			
		
        	//仓库列表
			$aDepot=M("depot_info")->where("repertory_id= ".$_SESSION['depot_id'] )->field("repertory_id,repertory_name")->select();
			//人员列表
			$aStaff=M("org_staff")->where("role_id=3 and org_parent_id=".session("org_parent_id"))->select();

            
            $stype1 = M("carsale_return_stock");
            $stype2 = M("carsale_return_stock_goods z2");
            
            $where["return_code"]=I("get.code",'');
            $where["org_parent_id"]=session("org_parent_id");
            $aReturn=$stype1->field("return_id,return_code,return_status,staff_id,repertory_id,return_remark,add_time")->where($where)->find();
            $aReturn['return_status'] = I("status");
            
			
			$aReturn["time"]=date("Y-m-d H:i",$aReturn["add_time"]);
			
            $whereReturnGoods["return_id"]=$aReturn["return_id"];
            $aReturnGoods=$stype2
            ->field("zdb_goods_info.goods_code,zdb_goods_info.goods_name,zdb_goods_info.goods_spec,z2.goods_id,z2.goods_num,z2.cv_id,zdb_goods_info.goods_convert_m,zdb_goods_info.goods_convert_b")
            ->join("zdb_goods_info on z2.goods_id=zdb_goods_info.goods_id")
            ->where($whereReturnGoods)->select();
            for($i=0;$i<count($aReturnGoods);$i++)
            {
                $car_num=M("carsale_stock")->where("org_parent_id=%d and staff_id=%d and goods_id=%d",array(session("org_parent_id"),$aReturn["staff_id"],$aReturnGoods[$i]["goods_id"]))->getField("goods_num");
               
                if(!$car_num){
                	$car_num=0;
                }
                $aCpGoods=M("goods_product")->field("cv_id,goods_unit,goods_unit_type")->where("goods_id=%d",array($aReturnGoods[$i]["goods_id"]))->select();
//
//              
                for($j=0;$j<count($aCpGoods);$j++)
                {
                    $bigNumber=$aReturnGoods[$i]["goods_convert_m"]*$aReturnGoods[$i]["goods_convert_b"];
                    $inNumber=$aReturnGoods[$i]["goods_convert_m"];
                    $arr=$aCpGoods[$j];
                    if($arr["goods_unit_type"]==1)
                        $arr["num"]=$car_num;
                    else if($arr["goods_unit_type"]==2)
                        $arr["num"]=floor($car_num/$inNumber);
                    else if($arr["goods_unit_type"]==3)
                        $arr["num"]=floor($car_num/$bigNumber);
                    $aReturnGoods[$i]["goods_unit"][$j]=$arr;
                    $aReturnGoods[$i]["goods_unit"][$j]["json"]=json_encode($arr);
                    
                   
                   
                    if($aReturnGoods[$i]["cv_id"]==$arr["cv_id"])
                    {
                        $aReturnGoods[$i]["goods_code"]= $aReturnGoods[$i]["goods_code"];
                        $aReturnGoods[$i]["car_num"]=$arr["num"];
                        //llx
                        $aReturnGoods[$i]["goods_unit_name"]=$arr["goods_unit"];
                    }
                }
              
            }
            
           
             
            
            foreach($aStaff as $v){
            
            	$new_aStaff[$v['staff_id']] = $v["staff_name"];
            
            }

            $aReturn['adepot'] = $new_adepot[$aReturn['depot_id']];   //llx   仓库名称
            $aReturn['staff_name'] = $new_aStaff[$aReturn['staff_id']]; //业务员名称
            
            $this->assign("code",$where["return_code"]);
            $this->assign("depotList",$aDepot);
            $this->assign("aStaff",$aStaff);
            $this->assign("aReturn",$aReturn);
            $this->assign("aReturnGoods",$aReturnGoods);
            $this->assign("aCpGoods",$aCpGoods);
            $this->assign("staff_name",session("staff_name"));
 
            $this->display();
            
        
    }

    public function editExAction(){
     	$mCp=M("carsale_return_stock_goods");
        $where["return_id"]=I("post.return_id",5);
        $where["org_parent_id"]=session("org_parent_id");
		//$_POST["goods_info"]='[{"goods_id":1,"cv_id":16,"goods_num":45,"goods_remark":""}]';
        $orderGoods=json_decode($_POST["goods_info"],true);
        $data["return_remark"]=I("post.goods_remark",'');
		$data["check_time"]=time();
        $affect=M("carsale_return_stock")->where($where)->save($data);
        if($affect)
        {
            $mCp->where($where)->delete();
            for($i=0;$i<count($orderGoods);$i++)
            {
                	
                $aConvert=M("goods_info")->field("goods_name,goods_spec")->where("goods_id=%d",array($orderGoods[$i]["goods_id"]))->find();
                
                $aGoods["goods_id"]=$orderGoods[$i]["goods_id"];
                $aGoods["cv_id"]=$orderGoods[$i]["cv_id"];
                $aGoods["goods_num"]=$orderGoods[$i]["goods_num"];
                $aGoods["return_id"]=$where["return_id"];
                $aGoods["goods_unit"]=M("GoodsProduct")->where("cv_id={$orderGoods[$i]["cv_id"]}")->getField('goods_unit');
                $aGoods["goods_name"] = $aConvert["goods_name"];
				$aGoods["goods_spec"] = $aConvert["goods_spec"];

                $temp[] = $aGoods;
  
            }

            $mCp->addAll($temp);
                
            aJsonReturn("1","修改成功");
        }else{
        	aJsonReturn("0","修改失败");
        }
        
     }

     /**
	 *  审核退库操作
	 *  
	 *  @param NULL
	 *  @return json 
	 */
    public function returnPassAction(){

		$mCp = M("carsale_return_stock_goods");
		$where["return_id"] = I("post.return_id", 5);
		$where["org_parent_id"] = session("org_parent_id");
		$orderGoods = json_decode($_POST["goods_info"], true); // 退库商品
		$data["return_remark"] = I("post.goods_remark", '');
		$data["check_time"] = time();
		$data["check_id"] = session("staff_id");
		$affect = false;
		// TODO 获取退库单当前状态
		$returnStatus = M("carsale_return_stock")->where($where)->getField('return_status');

		if (!empty($orderGoods) && count($orderGoods) >= 1 && $returnStatus != 2) {
			$affect = M("carsale_return_stock")->where($where)->save($data);
		}

		if ($affect !== false) {
			// TODO 生产退库商品清单
			foreach ($orderGoods as $value) {
				$aGoods = array();
				$where['goods_id'] = $value['goods_id'];
				$aGoods['goods_num'] = $value['goods_num'];
				$aGoods['cv_id'] = $value['cv_id'];
				$aGoods["goods_unit"] = M("GoodsProduct")->where("cv_id={$value["cv_id"]}")->getField('goods_unit');
				$mCp->where($where)->save($aGoods);
			}
			// TODO 进入退库流程方法
            $org_parent_id = session("org_parent_id");
            $return_stock_num = M("carsale_return_stock")->where("org_parent_id=$org_parent_id AND return_status=1")->count();

            session("return_stock_num", intval($return_stock_num));
			
			$this->returnPassF($where["return_id"]);
			
		} else {

			aJsonReturn("0", "修改失败");
		}
    }

    
	/**
	 *  审核退库 (内部调用)
	 *  
	 *  @param	string  $return_id	string $return_code
	 *  @return json
	 */
    private function returnPassF($return_id)
    {

		$mCp = M("carsale_return_stock");
		$where["return_id"] = $return_id;
		$date["checker_id"] = session("staff_id");
		$date["checker_name"] = session("staff_name");
		$date["check_time"] = time();
		$date["return_status"] = 2;
		// TODO　退库单流向审核通过节点
		$affect = $mCp->where($where)->save($date);

		if ($affect !== false) {
			$aReturn = $mCp->field("staff_id,repertory_id as depot_id,add_id as create_id,return_remark")->where($where)->find();
			$aReturnGoods = M("carsale_return_stock_goods")->field("goods_id,goods_num,cv_id")->where($where)->select();
			//A("Common/CarportInfo")->editCarportNum(session("org_parent_id"),$aReturn["staff_id"],$aReturnGoods,"sub","mobile");
			foreach ($aReturnGoods as $k => $v) {
				$aReturnGoods[$k]["staff_id"] = $aReturn["staff_id"];
				$aReturnGoods[$k]["org_parent_id"] = session("org_parent_id");
		}
			
			// TODO 更新车存
		$CarportInfoModel = new \Common\Model\CarportInfoModel();
		$CarportInfoModel->updateCarInfo($aReturnGoods, "后台车销退库操作", "del",6);
            
//			$dataIn["type_code"] = $where["return_code"];
//			$dataIn["type"] = 4;
//			$dataIn["status"] = 3;
//			$dataIn["remark"] = "";
//			$dataIn["goods_remark"] = $aReturn["return_remark"];
//			$dataIn["time"] = time();
//			$dataIn["depot_id"] = $aReturn["depot_id"];
//			$dataIn["org_parent_id"] = session("org_parent_id");
//			$dataIn["staff_id"] = $aReturn["create_id"];
//			$dataIn["goods_info"] = M("carsale_return_stock_goods")
//				->field("goods_id,goods_money as goods_price,goods_num as goods_num,cv_id")
//				->where("return_id=$return_id ")
//				->select();
//			// TODO 增加车销退库单
//			$res = A("DepotIn")->depotInAdd($dataIn);
//
//			// TODO 修改手机端退库后仓库加库存
//			if ($res !== false) {
//				$data = array();
//				foreach ($dataIn["goods_info"] as $k => $v) {
//					$data[$k]["goods_id"] = $v["goods_id"];
//					$data[$k]["small_stock"] = $v["goods_num"];
//					$data[$k]["cv_id"] = $v["cv_id"];
//					$data[$k]["org_parent_id"] = session("org_parent_id");
//					$data[$k]["depot_id"] = $dataIn["depot_id"];
//				}
//				// TODO 退库操作
//				$DepotStockModel = new DepotStockModel();
//				$DepotStockModel->updateStock($data, "车销退库", "add", $aReturn["staff_id"]);
//          if($res){
				aJsonReturn("1", "审核成功");
//			} else {
//				aJsonReturn("0", "审核失败");
//			}
		} else {
			aJsonReturn("0", "审核失败");
		}

	}

}

/*************************** end ************************************/