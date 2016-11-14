<?php

/*******************************************************************
 ** 文件名称: CarsaleApplyController.class.php
 ** 功能描述: 经销商PC端车存申请控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Dealer\Controller;
use Think\Controller;

class CarsaleApplyController extends Controller {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
        $p = I("get.p",1);
        $pnum = I("get.pnum",10);
        $staff_id = I("get.staff_id",0);
        $start_time = I("get.start_time",0);
        $end_time = I("get.end_time",0);

		$where['org_parent_id'] = $_SESSION['org_parent_id'];

        if($staff_id!=0)
            $where["staff_id"]=$staff_id;

        if($start_time!=0&&$end_time!=0)
            $where["add_time"]=array(array("egt",strtotime($start_time)),array("elt",strtotime($end_time)+24*60*59));

        $typel = M('carsale_apply');

        $total = $typel->where($where)->order("add_time desc")->count();

        $aApply = $typel->where($where)->order("apply_status asc,add_time desc")->page($p,$pnum)->select();

        $page = get_page_code($total,$pnum,$p, $page_code_len = 5);

        foreach($aApply as &$v){
            $v['time']=date("Y-m-d H:i",$v["add_time"]);
			$v['staff_name']=M("org_staff")->where("staff_id=".$v["staff_id"])->getField("staff_name");
            $v['add_name']=M("org_staff")->where("staff_id=".$v["add_id"])->getField("staff_name");
			$v['status']=getCarsaleApplyStatus($v["apply_status"]);
			$v["depot_name"]=M("depot_info")->where("repertory_id=".$v["repertory_id"])->getField("repertory_name");
        }

        //仓库列表
        $aStaff = queryOrgStaff(session("org_parent_id"),'3');
        $this->assign("aStaff",$aStaff);
        $this->assign("depot_in",$aApply);
        $this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("staff_id"=>$staff_id,"start_time"=>$start_time,"end_time"=>$end_time));
        $this->display();
    }

    public function addAction(){
        $time=date("Y-m-d H:i");
        $code=create_uniqid_code("CA", $_SESSION['staff_id']);
        
        if(IS_GET)
        {
        	$aDepot=M("depot_info")->where("repertory_id= ".$_SESSION['depot_id'] )->select();

            $this->assign("depotList",$aDepot);
			$org_parent_id=session("org_parent_id");
            $aStaff = queryOrgStaff($org_parent_id,'3');

            $mBrand = new \Common\Model\GoodsBrandModel();
            $aBrand=QueryBrandByOrgId($org_parent_id);
            $this->assign("brand",$aBrand);
            $this->assign("code",$code);
            $this->assign("time",$time);
            $this->assign("aStaff",$aStaff);
            $this->assign("staff_name",session("staff_name"));
            $this->display();
        }

       
        
    }
    
	
	public function addexAction(){
		$org_parent_id     =   session("org_parent_id");//经销商id

        $depot_id          =   I("post.depot_id",1);//仓库id

        if($depot_id != $_SESSION['depot_id']){
            $depot_id = $_SESSION['depot_id'];
        }

        $ddGoods_info      =   json_decode($_POST["goods_info"],true);//商品数据

        $ischeck           =   true;//标识

        for($i=0;$i<count($ddGoods_info);$i++){
        	
        	$goods_num    =   $ddGoods_info[$i]["goods_num"];//商品数量

        	$cv_id        =   $ddGoods_info[$i]["cv_id"];//单位id

        	$goods_id     =   $ddGoods_info[$i]["goods_id"];//商品id
        	
        	$result       =   checkStockFunction($goods_id,$depot_id,$cv_id,$goods_num);//检查库存是否充足
            
            if(!$result){

                $ischeck=false;
                    break;

            }

        }

        if($ischeck){

                // kxf add 验证单号 数据库是否存在 begin

            	$post_code = I("post.code");

           		if(!$post_code){

                     aJsonReturn("0","创建失败");

            	}

                // kxf add 验证单号 数据库是否存在 end

        		$data["staff_id"]     = I("post.staff_id",1);//业务员id
				$data["repertory_id"]     = $depot_id;//仓库id
				$data["org_parent_id"]= session("org_parent_id");
				$data["add_id"]    = session("staff_id");
            	$data["code"]         = $post_code;//订单code
				$data["status"]       = 1;
				$data["remark"]       = I("post.remark",'');
				$data["goods_info"]   = json_decode($_POST["goods_info"],true);

                //内部调用
        		if($this->addData($data)){
                    $apply_num = M("carsale_apply")->where("org_parent_id=$org_parent_id AND apply_status=1")->count();

                    session('apply_num', $apply_num);


                    aJsonReturn("1","创建成功");
                }

        		else

        			aJsonReturn("0","创建失败");

        	}else{

        		aJsonReturn("0","库存不足");

        	}
	}
	/**
     * 添加申请单数据(内部调用)
     */
    public function addData($data)
    {
        if(array_key_exists("code",$data)||!empty($data["code"]))

            $inData["apply_code"]=$data["code"];

        else

            $inData["apply_code"]=create_uniqid_code("CA", $data["staff_id"]);

        if(array_key_exists("time",$data)||!empty($data["time"]))

            $inData["add_time"]=strtotime($data["time"]);

        else

            $inData["add_time"]=time();


            $inData["staff_id"]=$data["staff_id"];

            $inData["add_id"]=$data["add_id"];

            

            $inData["org_parent_id"]=$data["org_parent_id"];

            $inData["repertory_id"]=$data["repertory_id"];

            //$inData["in_type"]=$data["type"];

            $inData["apply_status"]=$data["status"];

            $inData["apply_remark"]=$data["remark"];


            $inData["apply_total_money"]=0.00;

            $inData["is_admin_order"]=1;

        for($i=0;$i<count($data["goods_info"]);$i++)
        {

            $data["goods_info"][$i]["goods_num"]=!empty($data["goods_info"][$i]["goods_num"])?$data["goods_info"][$i]["goods_num"]:0;

            $data["goods_info"][$i]["goods_price"]=!empty($data["goods_info"][$i]["goods_price"])?$data["goods_info"][$i]["goods_price"]:0.00;

            $inData["apply_total_money"]+=$data["goods_info"][$i]["goods_num"]*$data["goods_info"][$i]["goods_price"];

        }

        $inAffect=M("carsale_apply")->data($inData)->add();
		
        if($inAffect)
        {

            $mGoods=M("goods_info");

            $mConvert=M("org_goods_convert");

            for($i=0;$i<count($data["goods_info"]);$i++)
            {
                $gdData[$i]["apply_id"]=$inAffect;

                $gdData[$i]["goods_id"]=$data["goods_info"][$i]["goods_id"];

                $aGoods=$mGoods->field("goods_name,goods_spec")->where("goods_id=%d",array($data["goods_info"][$i]["goods_id"]))->find();

                $gdData[$i]["goods_name"]=$aGoods["goods_name"];

				$gdData[$i]["goods_spec"]=$aGoods["goods_spec"];

                $gdData[$i]["cv_id"]=$data["goods_info"][$i]["cv_id"];

                $gdData[$i]["goods_unit"]=$mConvert->where("cv_id={$gdData[$i]['cv_id']}")->getField("goods_unit");

                $gdData[$i]["apply_price"]=$data["goods_info"][$i]["goods_price"];

                $gdData[$i]["apply_num"]=$data["goods_info"][$i]["goods_num"];

            }

            $gAffect=M("carsale_apply_goods")->addAll($gdData);

            if($gAffect)

                $bReturn=true;

            else

                $bReturn=false;

        }

        else

            $bReturn=false;

        return $bReturn;

    }
	
	
	
	
	
	
	
	
	 public function editAction()
    {
        if(IS_GET)
        {

            
            $aDepot=M("depot_info")->where("repertory_id= ".$_SESSION['depot_id'] )->select();
            
            $aStaff=queryOrgStaff(session("org_parent_id"),'3');
            $where["apply_id"]=I("get.apply_id",'9');
            $where["org_parent_id"]=session("org_parent_id");

           
          
            $type1  = M("carsale_apply");
            
            $aApply=$type1->where($where)->find();
            $aApply["time"]=date("Y-m-d H:i",$aApply["create_time"]);
           
            $add_name=M("org_staff")->where("staff_id=".$aApply["add_id"])->getField("staff_name");
            $aApply["add_name"]=$add_name;

            $aApplyGoods=M("carsale_apply_goods")->where("apply_id=".$where["apply_id"])->select();

            
			
			// 仓库ID
			$depot_id = intval($aApply['repertory_id']);
			
			// 循环查询商品单位信息
            for($i=0;$i<count($aApplyGoods);$i++)
            {
				// 查询商品单位转换系数
				$convert_where["goods_id"] = $aApplyGoods[$i]["goods_id"];
				$convert_info = M("goods_info")->field('goods_code,goods_convert_m,goods_convert_b')->where($convert_where)->find();
				$mid = $convert_info['goods_convert_m'];
				$big = $convert_info['goods_convert_b']*$convert_info['goods_convert_m'];

				// 查询商品库存
				$small_stock = M("depot_stock")->where($convert_where)->where("depot_id = $depot_id")->getField("small_stock");

                $aApplyGoods[$i]["goods_unit"]=M("org_goods_convert")->where("org_parent_id=%d and goods_id=%d",array(session("org_parent_id"),$aApplyGoods[$i]["goods_id"]))->select();
                for($j=0;$j<count($aApplyGoods[$i]["goods_unit"]);$j++)
                {
                	$aApplyGoods[$i]["goods_code"]=$convert_info["goods_code"];
					// 小单位库存
					if($aApplyGoods[$i]["goods_unit"][$j]['goods_unit_type'] == 1)
					{
						$aApplyGoods[$i]["goods_unit"][$j]['num'] = $small_stock; 
						if($aApplyGoods[$i]['cv_id'] == $aApplyGoods[$i]["goods_unit"][$j]['cv_id']){
							$aApplyGoods[$i]["goods_stock_num"] = $aApplyGoods[$i]["goods_unit"][$j]['num'];
                        }
					}
					
					// 中单位库存
					if($aApplyGoods[$i]["goods_unit"][$j]['goods_unit_type'] == 2)
					{
						$aApplyGoods[$i]["goods_unit"][$j]['num'] = floor($small_stock / $mid);
						if($aApplyGoods[$i]['cv_id'] == $aApplyGoods[$i]["goods_unit"][$j]['cv_id']){
							$aApplyGoods[$i]["goods_stock_num"] = $aApplyGoods[$i]["goods_unit"][$j]['num'];
						}
					}
					
					// 大单位库存
					if($aApplyGoods[$i]["goods_unit"][$j]['goods_unit_type'] == 3)
					{
						$aApplyGoods[$i]["goods_unit"][$j]['num'] = floor($small_stock / $big);
						if($aApplyGoods[$i]['cv_id'] == $aApplyGoods[$i]["goods_unit"][$j]['cv_id']){
							$aApplyGoods[$i]["goods_stock_num"] = $aApplyGoods[$i]["goods_unit"][$j]['num']; 
						}
					}
						
					// 转换成JSON格式
                    $aApplyGoods[$i]["goods_unit"][$j]["json"]=json_encode($aApplyGoods[$i]["goods_unit"][$j]);
                }
            }
            
			$mBrand = new \Common\Model\GoodsBrandModel();
            $aBrand=$mBrand->getAllBrand(session("org_parent_id"),0);
            $this->assign("brand",$aBrand);
            
			
            $this->assign("aApplyGoods",$aApplyGoods);
            $this->assign("aStaff",$aStaff);
            $this->assign("depotList",$aDepot);
            $this->assign("aApply",$aApply);
            $this->assign("status",I("get.status",0));
           
            $this->display();
        }
        
           
    }
	
	
	
	public function editexAction(){
		  $data["staff_id"]=I("post.staff_id",2);
       
          $data["depot_id"]=I("post.depot_id",1);
            $where["org_parent_id"]=session("org_parent_id");
//          $where["apply_code"]=I("post.apply_code",'CA000120160816332113');
            $where["apply_id"]=I("post.apply_id",11);
            $data["apply_remark"]=I("post.remark",'gfhgfh');
			//$_POST["goods_info"]='[{"goods_id":1,"cv_id":16,"goods_num":56,"goods_price":554.00,"remark":""}]';
            $goodsData=json_decode($_POST["goods_info"],true);
            //
            $goods_Data = $goodsData;


        if($data["depot_id"] != $_SESSION['depot_id']){
            $data["depot_id"] = $_SESSION['depot_id'];
        }


            $info = array();

            foreach ($goods_Data as $v) {

                $temp = getTransUnit($v['cv_id'],$v['goods_num']);

                $v['goods_num'] = $temp['good_num'];

                $temp_where['depot_id'] = $data['depot_id'];

                $temp_where['goods_id'] = $v['goods_id'];


                $temp_good_info = M("depot_stock")->where($temp_where)->field('small_stock')->find();

                if($v['goods_num']>$temp_good_info['small_stock']){

                    $info[] = $v;

                }

            }

            if(count($info)>0){
                aJsonReturn("0","库存不足,不允许修改");die;
            }

            
            $data["apply_goods_remark"]=I("post.goods_remark",'');
            $aStaff=M("org_staff")->where("staff_id=%d",array($data["staff_id"]))->find();
            $data["staff_name"]=!empty($aStaff["staff_name"])?$aStaff["staff_name"]:'';
            $data["apply_total_money"]=0.00;
            for($i=0;$i<count($goodsData);$i++)
            {
                $goodsData[$i]["goods_num"]=!empty($goodsData[$i]["goods_num"])?$goodsData[$i]["goods_num"]:0;
                $goodsData[$i]["goods_price"]=!empty($goodsData[$i]["goods_price"])?$goodsData[$i]["goods_price"]:0.00;
                $data["apply_total_money"]+=$goodsData[$i]["goods_num"]*$goodsData[$i]["goods_price"];
            }
            $aAffect=M("carsale_apply")->where($where)->save($data);

                M("carsale_apply_goods")->where($where)->delete();
                $mGoods=M("goods_info");
                for($i=0;$i<count($goodsData);$i++)
                {
                    $gdData[$i]["apply_id"]= $where["apply_id"];
                    $gdData[$i]["goods_id"]=$goodsData[$i]["goods_id"];
                    $aGoods=$mGoods->field("goods_name,goods_spec")->where("goods_id=%d",array($goodsData[$i]["goods_id"]))->find();
                    $gdData[$i]["goods_name"]=$aGoods["goods_name"];
                    $gdData[$i]["goods_spec"]=$aGoods["goods_spec"];
                   
                    $gdData[$i]["cv_id"]=$goodsData[$i]["cv_id"];
                    $gdData[$i]["apply_price"]=$goodsData[$i]["goods_price"];
                    $gdData[$i]["apply_num"]=$goodsData[$i]["goods_num"];
                    $gdData[$i]["staff_id"]=$data["staff_id"];
                    $goods_unit=M("org_goods_convert")->where("cv_id=".$gdData[$i]["cv_id"])->getField("goods_unit");
                    $gdData[$i]["goods_unit"]=$goods_unit;
                    
                }

                $gAffect=M("carsale_apply_goods")->addAll($gdData);
                if($gAffect)
                    aJsonReturn("1","修改成功");
                else
                    aJsonReturn("0","修改失败");

        
	}
	
	
	
	
	/**
     * 车存申请单 通过审核操作
     */

    public function applyPassAction()
    {
        $arError = array('rs' => true, 'msg' => '');

        $where["apply_id"]      =   I("post.apply_id",8);//申请单 id
        $where["org_parent_id"] =   session("org_parent_id");
        
        
        // kxf add 验证是否已经审核通过 数据库是否存在 begin

        $check = "carsale_apply";

        $check_where['apply_id']   = $where["apply_id"];

        $check_where['apply_status'] = 2;//审核通过状态

        $status = checkFieldExist($check,$check_where);

        if($status){

            aJsonReturn("0","数据错误");
            return;
        }

        // kxf add 验证是否已经审核通过 数据库是否存在 end
        $data["apply_status"]       =   2;
        $data["check_id"]         =   session("staff_id");
        $data["check_time"]       =   time();

        $mApply = M("carsale_apply");

        $aApply = $mApply->where($where)->find();

//      $data1["out_type"] = 3;
//
//      $data1["staff_id"] = $aApply["staff_id"];
//
//      $data1["out_status"] = 3;
//
        $data1["depot_id"] = $aApply["repertory_id"];
//
//      $data1["out_total_price"] = $aApply["apply_total_money"];
//
//      $data1["out_remark"] = I("post.remark", '');
//
//
//      $data1["org_parent_id"] = session("org_parent_id");
//
//      $data1["create_id"] = session("staff_id");
//
//      $data1["out_type_code"] = $aApply["apply_code"];
//
//      $staff_name = M("org_staff")->where("staff_id=" . $data1["create_id"])->getField("staff_name");
//
//      $data1["create_name"] = $staff_name;

        $data1["goods_info"] = M("carsale_apply_goods")->field("goods_id,apply_price as goods_price,apply_num as goods_num,cv_id")->where("apply_id='%s'", array( $where["apply_id"]))->select();

        if (!empty($data1["goods_info"])) {
            
            foreach ($data1["goods_info"] as $item) {
                $goodDepotStock =M("depot_stock")->where("depot_id=".$data1["depot_id"]." and goods_id= ".$item["goods_id"])->getField("small_stock");
                $resd=getTransUnit($item['cv_id'], $item['goods_num']);
                // 库存中以最小单位为准，检查库存
                if ($resd['good_num'] > $goodDepotStock) {
                    $arError['rs'] = false;
                    $arError['msg'] .= $item['goods_name']."\n";
                }
            }

            
        }
        
        $arError['rs'] = M("carsale_apply")->where($where)->save($data);
            
        $org_parent_id = session("org_parent_id");

        if ($arError['rs'] != false) {
            $apply_num = M("carsale_apply")->where("org_parent_id=$org_parent_id AND apply_status=1")->count();

            session('apply_num', $apply_num);
            aJsonReturn(1, "审核成功");

        } else {
            aJsonReturn(0, $arError['msg']);
        }
            

    }
	
	
	
	
	
	
	
	
     // 检查库存是否充足, 0代表充足, 可以出库
	// 检查人员: richie 
	// 检查日期: 2016-06-06
    public function checkStock()
	{
		// 请求参数
		$pageNum		= I("post.pageNum");
		$goods_id		= I("post.goods_id");
		$depot_id		= I("post.repertory_id");
		$org_parent_id	= session("org_parent_id");
		$cv_id			= I("post.cv_id");

		// 检查库存
		$flag = D("DepotStock")->checkStockFunction($goods_id,$depot_id,$org_parent_id,$cv_id,$pageNum);

		// 返回
		if($flag)
		{
			die("0"); // 可以出库
		}
		else
		{
			die("1"); // 不能出库
		}
    }
    
	/** 其他Action **/


}

/*************************** end ************************************/