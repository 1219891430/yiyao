<?php

/*******************************************************************
 ** 文件名称: DeliverApplyController.class.php
 ** 功能描述: 系统后台配送申请控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;

class DeliverApplyController extends BaseController {

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
            $where .= " AND ca.repertory_id = " . $depotID;
        }
        else{
            if (!empty($urlPara["depot_id"])) {
                $where .= " AND ca.repertory_id = " . $urlPara["depot_id"];
            }
        }

        if (!empty($urlPara["staff_id"])) {
            $where .= " AND ca.staff_id = " . $urlPara["staff_id"];
        }

        if (!empty($urlPara["end"]) && !empty($urlPara["start"])) {
            $stime = strtotime($urlPara["start"]);
            $etime = strtotime($urlPara["end"] + 24*60*60);
            if ($etime > $stime) {
                $where .= " ca.add_time >= " . $stime . " AND ca.add_time < " . $etime;
            }
        }
        
		$p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);
		
        $list = M('car_apply')->alias('ca')
            ->field('ca.*, au.true_name, di.repertory_name')
            ->join('left join __DEPOT_INFO__ as di on ca.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on ca.staff_id = au.admin_id')
            ->where($where)
			->page($p,$pnum)
            ->order('ca.apply_id desc')
            ->select();

        $this->assign("list", $list);

        $total = M('car_apply')->alias('ca')
            ->field('ca.*, au.true_name, di.repertory_name')
            ->join('left join __DEPOT_INFO__ as di on ca.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on ca.staff_id = au.admin_id')
            ->where($where)->count();

        

        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/DeliverApply',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示
        // 仓库
        $this->assign('pnum',$pnum);
        $this->assign('depotList', queryDepot($depotID));

        // 业务员
        $this->assign('staffList', queryAdminStaff($depotID, 5));

		$this->display();
    }

    // 创建配送申请单
    public function addAction() {

        //内勤人员标识
        $depotID = $this->_depot_id;

        $time=date("Y-m-d H:i");

        $code=create_uniqid_code("CA", $_SESSION['admin_id']);

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

            $depot_id          =   I("post.depot_id", 0);//仓库id

            // 角色权限
            if ($depotID != 0 && $depot_id != $depotID) {
                echo "<script>alert('禁止选择权限范围外的仓库！');window.location='__MODULE__';</script>"; exit;
                return;
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

                    aJsonReturn("0","单号为空，操作失败");
                    return;

                }

                $check_where['apply_code'] = $post_code;

                $status = checkFieldExist("car_apply",$check_where);

                if($status){

                    aJsonReturn("0","存在单号，创建失败");

                }

                // kxf add 验证单号 数据库是否存在 end

                $data["staff_id"]     = I("post.staff_id",0);//业务员id

                $data["repertory_id"]     = I("post.depot_id",0);//仓库id

                $data["add_id"]       = session("admin_id");// 创建人ID

                $data["apply_code"]   = $post_code;//订单code

                $data["apply_status"] = 1;

                $data["time"]         = $time;

                $data["apply_remark"]       = I("post.remark",'');

                $data["goods_info"]   = json_decode($_POST["goods_info"],true);

                $daModel = new \Common\Model\DeliverApplyModel();
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


            }else{

                aJsonReturn("0","库存不足");
                return;
            }
        }
    }

    // 检查库存是否充足, 0代表充足, 可以出库
    // 检查人员: richie
    // 检查日期: 2016-06-06
    public function checkStockAction(){

        // 请求参数
        $pageNum		= I("post.pageNum");
        $goods_id		= I("post.goods_id");
        $depot_id		= I("post.repertory_id");
        $cv_id			= I("post.cv_id");

        // 查询仓库实时库存, 最小单位
        $where["goods_id"]=$goods_id;
        $where["depot_id"]=$depot_id;
        $res=M("depot_stock")->field("small_stock")->where($where)->find();

        // 出库货品转化最小单位数量
        $resd=getTransUnit($cv_id,$pageNum);

        // 库存是否充足
        if($resd["good_num"]>$res["small_stock"]) {
            die("1");
        } else {
            die("0");
        }

    }

    // 申请单作废
    public function delAction() {
        $id = I("id");

        $where = array("apply_id"=>$id);

        // 检查申请单单是否存在
        $apply = M("car_apply")->where($where)->find();
        if(!$apply) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

        // 角色权限
        if ($this->_depot_id > 0 && $this->_depot_id != $apply['repertory_id']) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }



        M("car_apply")->create();
        $data["is_cancel"] = 1;
        $data["cancel_time"] = time();

        $succ = M("car_apply")->where($where)->data($data)->save();

        if ($succ) {
            $this->success("操作成功", U("Admin/DeliverApply/index"));

        } else {
            $this->error("作废申请单失败，检查数据库连接是否正常", U("Admin/DeliverApply/index"));

        }
    }

    // 查看
    public function lookAction() {

        $id = I("id");

        $where["apply_id"] = $id;

        // 检查申请单单是否存在
        $apply = M("car_apply")->where($where)->find();

        if(!$apply) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

        // 角色权限
        if ($this->_depot_id > 0 && $this->_depot_id != $apply['repertory_id']) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }


        $apply = M('car_apply')->alias('ca')
            ->field('ca.*, di.repertory_name, au.true_name as staff_name')
            ->join('left join __DEPOT_INFO__ as di on ca.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on ca.staff_id = au.admin_id')
            ->where($where)
            ->order('ca.apply_id desc')
            ->find();

        $this->assign("apply", $apply);


        // 商品
        $goods = M("car_apply_goods")->alias('cag')
            ->field('cag.*, gi.goods_code')
            ->join('left join __GOODS_INFO__ as gi on cag.goods_id = gi.goods_id')
            ->where($where)
            ->order('apply_id desc')
            ->select();

        /*foreach($goods as $k=>$v){
            // 单位
            $where["goods_id"]=$v["goods_id"];

            $unit=M("goods_product")->where($where)->select();

            $goods[$k]["goods_unit"]=$unit;
        }*/

        $this->assign("goods", $goods);

        if (isset($_GET["excel"]) && $_GET["excel"] = "excel") {
            $data = $apply;
            $data["goods"] = $goods;

            //print_r($data);die();

            $this->BuildEXCEL($data);

            return;
        }

        //print_r($goods);

        // 返回页面
        $this->display();
    }

    // 审核
    public function checkAction() {
        $id = I("id");

        $where["apply_id"] = $id;

        $depot_id = $this->_depot_id;

        if($depot_id > 0) {
            $where["ca.repertory_id"] = $depot_id;
        }

        $apply = M('car_apply')->alias('ca')
            ->field('ca.*, di.repertory_name, au.true_name as staff_name')
            ->join('left join __DEPOT_INFO__ as di on ca.repertory_id = di.repertory_id')
            ->join('left join __ADMIN_USER__ as au on ca.staff_id = au.admin_id')
            ->where($where)
            ->order('ca.apply_id desc')
            ->find();

        if(!$apply) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

        // 角色权限
        if ($apply['apply_status'] != 1) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

        $this->assign("apply", $apply);

        unset($where["ca.repertory_id"]);

        // 商品
        $goods = M("car_apply_goods")->alias('cag')
            ->field('cag.*, gi.goods_code')
            ->join('left join __GOODS_INFO__ as gi on cag.goods_id = gi.goods_id')
            ->where($where)
            ->order('apply_id desc')
            ->select();

        $this->assign("goods", $goods);

        $this->assign("depotList", queryDepot($depot_id));

        $this->assign("aStaff",queryAdminStaff($depot_id, 5));

        //print_r($goods);

        // 返回页面
        $this->display();
    }

    public function checkExAction() {

        $id = I("id",19);

        $where["apply_id"] = $id;

        // 检查申请单单是否存在
        $apply = M("car_apply")->where($where)->find();

        if(!$apply) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

        // 角色权限
        if ($this->_depot_id > 0 && $this->_depot_id != $apply['repertory_id'] || $apply['apply_status'] != 1) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

        M("car_apply")->create();

        $data["apply_status"] = 2;
        $data["check_id"] = $_SESSION['admin_id'];
        $data["check_time"] = time();

        $succ = M("car_apply")->where($where)->data($data)->save();

        if ($succ) {
        	$res=M("car_apply")->where($where)->find();
			$data["send_staff_id"]=0;
        	$data["out_type"]=2; // 入库类型
        	$data["out_status"]=2;
        	$data["out_remark"]=$res["apply_remark"];
        	$data["depot_id"]=$res["repertory_id"];
			$data["org_parent_id"]=$res["org_parent_id"];
        	//$_POST["goods_info"]='[{"goods_id":1,"cv_id":7,"goods_num":5}]';
        	$goods_info=M("car_apply_goods")->field("goods_id,cv_id,apply_num as goods_num")->where($where)->select();
			
        	$data["goods_info"]=$goods_info;
		
       
			$data["create_id"]=session("admin_id");

	    	$res = D("DepotOut")->addDepotOutOrder($data);
			
			foreach($goods_info as $k=>$v){
				$stockData[$k]["cv_id"]=$v["cv_id"];
				$stockData[$k]["goods_id"]=$v["goods_id"];
				$stockData[$k]["small_stock"]=$v["goods_num"];
				$stockData[$k]["org_parent_id"]=$data["org_parent_id"];
				$stockData[$k]["depot_id"]=$data["depot_id"];
			}
			
			$msg = queryDepotOutType($data['out_type']);
					
			D("DepotStock")->updateStock($stockData, $msg, "del", $data['out_type']);
            $this->success("操作成功", U("Admin/DeliverApply"));
            //$this->redirect(U("Admin/DeliverApply/index"));
        } else {
            $this->error("审核失败，检查数据库连接是否正常", U("Admin/DeliverApply"));
        }
    }

    // 修改
    public function editAction() {
        $id = I("id");

        $depot_id = $this->_depot_id;
        if ($depot_id > 0) {
            $where["ca.repertory_id"] = $depot_id;
        }

        $where["apply_id"] = $id;

        if (IS_GET) {

            $apply = M('car_apply')->alias('ca')
                ->field('ca.*, di.repertory_name, au.true_name as staff_name')
                ->join('left join __DEPOT_INFO__ as di on ca.repertory_id = di.repertory_id')
                ->join('left join __ADMIN_USER__ as au on ca.staff_id = au.admin_id')
                ->where($where)
                ->order('ca.apply_id desc')
                ->find();

            if(!$apply) {
                echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
                return;
            }

            if ($apply['apply_status'] != 1) {
                echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
            }

            $this->assign("apply", $apply);


            $where = array();
            $where["apply_id"] = $id;

            // 商品
            $goods = M("car_apply_goods")->alias('cag')
                ->field('cag.*, gi.goods_code')
                ->join('left join __GOODS_INFO__ as gi on cag.goods_id = gi.goods_id')
                ->where($where)
                ->order('apply_id desc')
                ->select();

            foreach($goods as $k=>$v){
                // 单位
                $where["goods_id"]=$v["goods_id"];

                $unit=M("goods_product")->where($where)->select();
                //echo M("goods_product")->getLastSql();

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
            if ($depot_id <= 0) {
                $depot_id          =   I("post.depot_id", 0);//仓库id
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
                $where = array();

                $where["apply_id"] = $id;

                $status = checkFieldExist("car_apply",$where);

                if(!$status){

                    aJsonReturn("0","申请单不存在");
                    return;

                }

                $data["staff_id"]     = I("post.staff_id",0);//业务员id

                $data["repertory_id"] = I("post.depot_id",0);//仓库id

                $data["apply_remark"] = I("post.remark",'');

                $data["goods_info"]   = json_decode($_POST["goods_info"],true);


                $daModel = new \Common\Model\DeliverApplyModel();

                if($daModel->editData($id, $data)){
                    aJsonReturn("1","修改成功");
                    return;
                }
                else
                {
                    aJsonReturn("0","修改失败");
                    return;
                }


            }else{

                aJsonReturn("0","库存不足");
                return;

            }
        }
    }

    private function BuildEXCEL($data){

        $EXCELBuilder=EXCELBuilder::getInstance();
        $i=1;
        $v="配送申请单";
        $EXCELBuilder->setCellValue(0, "A".$i, $v);
        $EXCELBuilder->mergeCells(0, "A$i:H$i");

        $i++;
        $EXCELBuilder->setCellValue(0, "A".$i, "单据日期:".date("Y-m-d",$data['add_time']));
        $EXCELBuilder->mergeCells(0, "A$i:B$i");
        $EXCELBuilder->setCellValue(0, "C".$i, "单据编号:".$data['apply_code']);
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
            $EXCELBuilder->setCellValue(0, "H".$i, $v['apply_num']);
            $i++;
        }

        $d_end=$i;

        $i++;
        $EXCELBuilder->setCellValue(0, "A".$i, "备注：".$data['apply_remark']);
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

        $EXCELBuilder->FileOutput(0,"配送申请-".$data['apply_code']);
    }

		
	/** 其他Action **/


}

/*************************** end ************************************/