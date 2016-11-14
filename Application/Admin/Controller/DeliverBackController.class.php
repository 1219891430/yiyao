<?php

/*******************************************************************
 ** 文件名称: DeliverBackController.class.php
 ** 功能描述: 系统后台配送车存退库控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;

class DeliverBackController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    // 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

        //内勤人员标识
        $depotID = $this->_depot_id;

        $urlPara = array();
        $urlPara["depot_id"] = I("depot_id");
        $urlPara["staff_id"] = I("staff_id");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");
        $this->assign('urlPara', $urlPara);

        $where = "is_cancel != 1 ";

        if($depotID>0) {
            $where .= " AND crs.repertory_id = " . $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where .= " AND crs.repertory_id = " . $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["staff_id"])) {
            $where .= " AND crs.staff_id = " . $urlPara["staff_id"];
        }

        
        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"] + 24*60*60);
            if ($etime > $stime) {
                $where .= " crs.add_time >= " . $stime . " AND crs.add_time < " . $etime;
            }
        }
		
		$p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $list = M('car_return_stock')->alias('crs')
            ->field('crs.*, au.true_name as staff_name, di.repertory_name as depot_name')
            ->join('left join __DEPOT_INFO__ as di on crs.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on crs.staff_id = au.admin_id')
            ->where($where)->page($p,$pnum)
            ->order('crs.return_id desc')
            ->select();
		
        $this->assign("list", $list);

        $total = M('car_return_stock')->alias('crs')
            ->field('crs.*, au.true_name as staff_name, di.repertory_name as depot_name')
            ->join('left join __DEPOT_INFO__ as di on crs.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on crs.staff_id = au.admin_id')
            ->where($where)->count();

        

        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/DeliverApply',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示
        // 仓库
        $this->assign('depotList', queryDepot($depotID));
        $this->assign('pnum',$pnum);
        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID, 5));

        $this->display();
    }

    public function addAction() {
        //内勤人员标识
        $depotID = $this->_depot_id;

        $time=date("Y-m-d H:i");

        $code=create_uniqid_code("CRS", $_SESSION['admin_id']);

        if(IS_GET) {

            $aDepot = queryDepot($depotID);

            $aBrand = queryBrand();

            $aStaff = queryAdminStaff($depotID, 5);

            $this->assign("depotList",$aDepot);

            $this->assign("brand",$aBrand);

            $this->assign("code",$code);

            $this->assign("time",$time);

            $this->assign("aStaff",$aStaff);

            $this->assign("staff_name",session("true_name"));

            $this->display();

        }

        if(IS_POST) {

            $code = I("post.code");

            if(!$code){

                aJsonReturn("0","单号为空，操作失败");
                return;

            }

            $check_where['return_code'] = $code;

            $status = checkFieldExist("car_return_stock",$check_where);

            if($status){

                aJsonReturn("0","单号重复，创建失败");

            }

            $data["staff_id"]     = I("post.staff_id",0);//业务员id

            $data["repertory_id"]     = I("post.depot_id",0);//仓库id

            $data["add_id"]       = session("admin_id");// 创建人ID

            $data["return_code"]   = $code;//订单code

            $data["return_status"] = 1;

            $data["time"]         = $time;

            $data["return_remark"]       = I("post.remark",'');

            $data["goods_info"]   = json_decode($_POST["goods_info"],true);

            //print_r($data);die();

            $daModel = new \Common\Model\DeliverBackModel();
            if($daModel->addData($data))
            {
                aJsonReturn("1","创建成功");
                return;
            }

            else
            {
                aJsonReturn("0","创建失败");
                return;
            }
        }
    }

    // 查看
    public function lookAction() {
        $id = I("id");
        if($this->_depot_id){
        	$depot_id = $this->_depot_id;

        	$crs = M('car_return_stock')->where("return_id=$id AND repertory_id=$depot_id")->find();
        	if (!$crs) {
            	echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
       		}
        }
        


        $where["return_id"] = $id;

        /*if ($depot_id > 0) {
            $where["crs.repertory_id"] = $depot_id;
        }*/

        $return = M('car_return_stock')->alias('crs')
            ->field('crs.*, di.repertory_name, au.true_name as staff_name')
            ->join('left join __DEPOT_INFO__ as di on crs.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on crs.staff_id = au.admin_id')
            ->where($where)
            ->order('return_id desc')
            ->find();

        $this->assign("return", $return);


        // 商品
        $goods = M("car_return_stock_goods")->alias('crsg')
            ->field('crsg.*, gi.goods_code')
            ->join('left join __GOODS_INFO__ as gi on crsg.goods_id = gi.goods_id')
            ->where($where)
            ->order('return_id desc')
            ->select();


        $this->assign("goods", $goods);

        if (isset($_GET["excel"]) && $_GET["excel"] = "excel") {
            $data = $return;
            $data["goods"] = $goods;

            //print_r($data);die();

            $this->BuildEXCEL($data);

            return;
        }

        //print_r($goods);

        // 返回页面
        $this->display();
    }

    public function editAction() {
        $id = I("id");

        if($this->_depot_id){
        	$depot_id = $this->_depot_id;
        
        	$crs = M('car_return_stock')->where("return_id=$id AND repertory_id=$depot_id")->find();
        	if (!$crs) {
            	echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        	}
        }
        

        $where["return_id"] = $id;

        if (IS_GET) {

            $return = M('car_return_stock')->alias('crs')
                ->field('crs.*, di.repertory_name, au.true_name as staff_name')
                ->join('left join __DEPOT_INFO__ as di on crs.repertory_id = di.repertory_id')
                ->join('left join __ADMIN_USER__ as au on crs.staff_id = au.admin_id')
                ->where($where)
                ->order('return_id desc')
                ->find();

            $this->assign("return", $return);


            // 商品
            $goods = M("car_return_stock_goods")->alias('crsg')
                ->field('crsg.*, gi.goods_code')
                ->join('left join __GOODS_INFO__ as gi on crsg.goods_id = gi.goods_id')
                ->where($where)
                ->order('return_id desc')
                ->select();

            foreach($goods as $k=>$v){
                // 单位
                $where["goods_id"]=$v["goods_id"];

                $unit=M("goods_product")->where($where)->select();

                $goods[$k]["goods_unit"]=$unit;

                // 库存
                $stock=M("depot_stock")->where($where)->find();
                $goods[$k]["depot_stock"]=$stock;
            }


            $this->assign("goods_info", $goods);

            $this->assign("depotList", queryDepot($depot_id));

            $this->assign("staffList",queryAdminStaff($depot_id, 5));

            $this->assign("brand",queryBrand($depot_id));

            $this->display();
        }

        if (IS_POST) {

            $status = checkFieldExist("car_return_stock",$where);

            if(!$status){

                aJsonReturn("0","退库单不存在");
                return;

            }

            $data["staff_id"]     = I("post.staff_id",0);//业务员id

            $data["repertory_id"] = I("post.depot_id",0);//仓库id

            $data["return_remark"] = I("post.remark",'');

            $data["goods_info"]   = json_decode($_POST["goods_info"],true);


            $daModel = new \Common\Model\DeliverBackModel();

            if($daModel->editData($id, $data)){
                aJsonReturn("1","修改成功");
                return;
            }
            else
            {
                aJsonReturn("0","修改失败");
                return;
            }

        }
    }

    private function BuildEXCEL($data){

        $EXCELBuilder=EXCELBuilder::getInstance();
        $i=1;
        $v="配送退货单";
        $EXCELBuilder->setCellValue(0, "A".$i, $v);
        $EXCELBuilder->mergeCells(0, "A$i:H$i");

        $i++;
        $EXCELBuilder->setCellValue(0, "A".$i, "单据日期:".date("Y-m-d",$data['add_time']));
        $EXCELBuilder->mergeCells(0, "A$i:B$i");
        $EXCELBuilder->setCellValue(0, "C".$i, "单据编号:".$data['return_code']);
        $EXCELBuilder->mergeCells(0, "C$i:F$i");
        $EXCELBuilder->setCellValue(0, "G".$i, "制单人:".session("true_name"));
        $EXCELBuilder->mergeCells(0, "G$i:H$i");

        $i++;
        $EXCELBuilder->setCellValue(0, "A".$i, "出库仓库:".$data['repertory_name']);
        $EXCELBuilder->mergeCells(0, "A$i:F$i");
        $EXCELBuilder->setCellValue(0, "G".$i, "业务员:".$data['staff_name']);
        $EXCELBuilder->mergeCells(0, "G$i:H$i");

        $i++;
        $EXCELBuilder->setCellValue(0, "A".$i, "");
        $EXCELBuilder->mergeCells(0, "A$i:H$i");

        $i++;
        $d_start=$i;
        $EXCELBuilder->setCellValue(0, "A".$i, "商品编码");
        $EXCELBuilder->mergeCells(0, "A$i:C$i");
        $EXCELBuilder->setCellValue(0, "D".$i, "商品名称");
        $EXCELBuilder->mergeCells(0, "D$i:F$i");
        $EXCELBuilder->setCellValue(0, "G".$i, "单位");
        $EXCELBuilder->setCellValue(0, "H".$i, "数量");
        $i++;

        foreach($data["goods"] as $v){
            $EXCELBuilder->setCellValueExplicit(0, "A".$i,$v['goods_code']);
            $EXCELBuilder->mergeCells(0, "A$i:C$i");
            $EXCELBuilder->setCellValue(0, "D".$i, $v['goods_name'].$v['goods_spec']);
            $EXCELBuilder->mergeCells(0, "D$i:F$i");
            $goods_unit=$v["goods_unit"];
            foreach($goods_unit as $vv){
                if($vv['cv_id']==$v['cv_id']){
                    $v['goods_unit']=$vv['goods_unit'];
                }
            }
            $EXCELBuilder->setCellValue(0, "G".$i, $v['goods_unit']);
            $EXCELBuilder->setCellValue(0, "H".$i, $v['goods_num']);
            $i++;
        }

        $d_end=$i;

        $i++;
        $EXCELBuilder->setCellValue(0, "A".$i, "备注：".$data['return_remark']);
        $EXCELBuilder->mergeCells(0, "A$i:H$i");


        $EXCELBuilder->setOutlineBorder(0, "A2:H$i","thick");
        $EXCELBuilder->setInsideBorder(0, "A2:H$i","thin");
        $EXCELBuilder->setHorizontal(0, "A1:H1", "center");
        $EXCELBuilder->setHorizontal(0, "A$d_start:H$d_end", "center");
        $EXCELBuilder->setFontSize(0, "A1:H1", 20);
        $EXCELBuilder->setFontSize(0, "A2:H$i", 14);

        $EXCELBuilder->setColumnWidth(0, A, 20);
        $EXCELBuilder->setColumnWidth(0, B, 20);
        $EXCELBuilder->setColumnWidth(0, C, 15);
        $EXCELBuilder->setColumnWidth(0, D, 15);
        $EXCELBuilder->setColumnWidth(0, E, 15);
        $EXCELBuilder->setColumnWidth(0, F, 15);
        $EXCELBuilder->setColumnWidth(0, G, 15);
        $EXCELBuilder->setColumnWidth(0, H, 15);

        $EXCELBuilder->FileOutput(0,"配送退货单-".$data['return_code']);
    }
	
	/** 其他Action **/
	
	// 查看
    public function checkAction() {
        $id = I("id");
        if($this->_depot_id){
        	$depot_id = $this->_depot_id;

        	$crs = M('car_return_stock')->where("return_id=$id AND repertory_id=$depot_id")->find();
        	if (!$crs) {
            	echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        	}
        }
        

        $where["return_id"] = $id;

        $return = M('car_return_stock')->alias('crs')
            ->field('crs.*, di.repertory_name, au.true_name as staff_name')
            ->join('left join __DEPOT_INFO__ as di on crs.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on crs.staff_id = au.admin_id')
            ->where($where)
            ->order('return_id desc')
            ->find();

        $this->assign("return", $return);


        // 商品
        $goods = M("car_return_stock_goods")->alias('crsg')
            ->field('crsg.*, gi.goods_code')
            ->join('left join __GOODS_INFO__ as gi on crsg.goods_id = gi.goods_id')
            ->where($where)
            ->order('return_id desc')
            ->select();


        $this->assign("goods", $goods);

        

      
        // 返回页面
        $this->display();
    }
	
	
    /**
	 *  审核退库操作
	 *  
	 *  @param NULL
	 *  @return json 
	 */
    public function returnPassAction(){

        $retuen_id = I("post.return_id", 1);
		if($this->_depot_id){
        	$depot_id = $this->_depot_id;

        	// 检查是否超权限
        	$crs = M('car_return_stock')->where("return_id=$retuen_id AND repertory_id=$depot_id")->find();
        	if (!$crs) {
            	echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        	}
		}

		
		$where["return_id"] = $retuen_id;
		
			
		$this->returnPassF($where["return_id"]);
			
		
    }

    
	/**
	 *  审核退库 (内部调用)
	 *  
	 *  @param	string  $return_id	string $return_code
	 *  @return json
	 */
    private function returnPassF($return_id)
    {

		$mCp = M("car_return_stock");
		$where["return_id"] = $return_id;
		$date["checker_id"] = session("admin_id");
		$date["checker_name"] = session("true_name");
		$date["check_time"] = time();
		$date["return_status"] = 2;
		// TODO　退库单流向审核通过节点
		$affect = $mCp->where($where)->save($date);

		if ($affect !== false) {
			$aReturn = $mCp->field("staff_id,repertory_id as depot_id,add_id as create_id,org_parent_id,return_remark")->where($where)->find();
			$aReturnGoods = M("car_return_stock_goods")->field("goods_id,goods_num,cv_id")->where($where)->select();
			//A("Common/CarportInfo")->editCarportNum(session("org_parent_id"),$aReturn["staff_id"],$aReturnGoods,"sub","mobile");
			foreach ($aReturnGoods as $k => $v) {
				$aReturnGoods[$k]["staff_id"] = $aReturn["staff_id"];
				$aReturnGoods[$k]["org_parent_id"] = session("org_parent_id");
			}
			
			// TODO 更新车存
			$DeliverStockModel = new \Common\Model\DeliverStockModel();
			$DeliverStockModel->updateCarInfo($aReturn["staff_id"], $aReturnGoods,6);
            

			// TODO 增加车销退库单

            $data["in_type"]=3; // 入库类型
        	$data["in_remark"]=$aReturn['return_remark'];
        	$data["depot_id"]=$aReturn['depot_id'];
			$data["org_parent_id"]=$aReturn['org_parent_id'];
        
        	$data["goods_info"]=$aReturnGoods;
		
        	$data["staff_id"]=session("admin_id");
			$data["create_id"]=session("admin_id");
		    $data["in_status"]=2;
	   	 	$res = D("DepotIn")->addDepotInOrder($data);
			

//
//			// TODO 修改手机端退库后仓库加库存
			$data=array();
			foreach ($aReturnGoods as $k=>&$v)
			{
				$data[$k]["goods_id"]=$v["goods_id"];
				$data[$k]["cv_id"]=$v["cv_id"];
				$data[$k]["small_stock"]=$v["goods_num"];
				$data[$k]["org_parent_id"]=$aReturn['org_parent_id'];
				$data[$k]["depot_id"]=$aReturn["depot_id"];
			}
			
			// 备注日志说明
			$in_type =3;
			$inType = queryDepotInType(3);
			
			// 修改库存并添加日志
			
			D("DepotStock")->updateStock($data, $inType, "add",$in_type);


			aJsonReturn("1", "审核成功");

		} else {
			aJsonReturn("0", "审核失败");
		}

	}

    

}

/*************************** end ************************************/