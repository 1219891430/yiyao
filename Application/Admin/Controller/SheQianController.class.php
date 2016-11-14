<?php

/*******************************************************************
 ** 文件名称: SheQianController.class.php
 ** 功能描述: 系统后台车销配送赊欠控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;

class SheQianController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    // 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{

        //内勤人员标识
        $depotID = $this->_depot_id;
        $aStaff = queryAdminStaff($depotID,5);

        if($_REQUEST['shopid']){
        	$where['cust_name'] = array("like",'%'.$_REQUEST['shopid'].'%');
        }
        if($_REQUEST['staff_id']){
        	$where['staff_id'] = $_REQUEST['staff_id'];
        }

        if($_REQUEST['start_time'] && $_REQUEST['end_time']){
        	$timespan = strtotime(urldecode($_REQUEST['start_time'])).",".strtotime(urldecode($_REQUEST['end_time'].'23:59:59'));
        	$where['create_time'] = array("between",$timespan);
        }
		
		if(!$_REQUEST['is_cancel']){
			$where["is_cancel"]=0;
		}else{
			
			$where["is_cancel"]=1;
		}
		
		if(!$_REQUEST['is_full_pay']){
			$where["is_full_pay"]=0;
		}else{
			
			$where["is_full_pay"]=1;
		}

		if($depotID>0){
		    $where['repertory_id'] = $depotID;
        }
      
        
	    $p=I("get.p",1);
	    $pnum=I("get.pnum",10);
		
		$list=M("car_orders")->field("order_id,order_code,cust_name,order_total_money,order_total_money-order_real_money as qiankuan,create_time,true_name,is_cancel,is_full_pay")
            ->where($where)
            ->where("order_total_money-order_real_money>0")
            ->join("zdb_admin_user on zdb_admin_user.admin_id=zdb_car_orders.staff_id" )
            ->page($p,$pnum)
			->order("create_time desc")
            ->select();

		$total=M("car_orders")->field("order_id,cust_name,order_total_money,order_total_money-order_real_money as qiankuan,create_time,true_name,is_cancel,is_full_pay")
            ->where($where)
            ->where("order_total_money-order_real_money>0")
            ->join("zdb_admin_user on zdb_admin_user.admin_id=zdb_car_orders.staff_id" )
            ->count();

		$qianKuanData=array();
		foreach($list as $k=>$v){
			$where1["orderid"]=$v["order_id"];
			$qingqianmoney=M("car_orders_qiankuan")->where($where1)->getField("sum(price)");
			if($qingqianmoney){
				$v["qiankuan"]=$v["qiankuan"]-$qingqianmoney;
			}
			
			$v["qiankuan"]=number_format($v["qiankuan"],2);
			$qianKuanData[$k]=$v;
			$qianKuanData[$k]["create_time"]=date("Y-m-d H:i:s",$v["create_time"]);	
		}

		
		$page=get_page_code($total, $pnum, $p,5);
		
		
		$this->assign("pnum",$pnum);
		$this->assign("pagelist",$page);
		
		$this->assign('list',$qianKuanData);
		
		if($_GET["explode"]=="explode"){
			$this->BuildEXCEL($qianKuanData);
			exit;
		}
		$this->assign("aStaff",$aStaff);
		
		$this->assign('urlPara',array("is_full_pay"=>$_REQUEST['is_full_pay'],"is_cancel"=>$_REQUEST['is_cancel'],"shopid"=>$_REQUEST['shopid'],"staff_id"=>$_REQUEST['staff_id'],"start_time"=>$_REQUEST['start_time'] ,"end_time"=>$_REQUEST['end_time']));
		$this->display();
    }

	// Excel导出数据
	private function BuildEXCEL($data)
	{
		$qianKuanData=$data;
		$EXCELBuilder=EXCELBuilder::getInstance();
		$i=1;
		$v="赊款订单";
		$EXCELBuilder->setCellValue(0, "A".$i, $v);
		$EXCELBuilder->mergeCells(0, "A$i:F$i");

		$i++;
		$d_start=$i;
		$EXCELBuilder->setCellValue(0, "A".$i, "行号");
		$EXCELBuilder->setCellValue(0, "B".$i, "赊款店铺");
		$EXCELBuilder->setCellValue(0, "C".$i, "订单金额");
		$EXCELBuilder->setCellValue(0, "D".$i, "赊款金额");
		$EXCELBuilder->setCellValue(0, "E".$i, "赊款订单时间");		
		$EXCELBuilder->setCellValue(0, "F".$i, "业务员");
		
		
		$i++;
		$n=1;
		
		
		foreach($qianKuanData as $v){
			$EXCELBuilder->setCellValue(0, "A".$i, $n);
			$EXCELBuilder->setCellValueExplicit(0, "B".$i,$v['cust_name']);
			$EXCELBuilder->setCellValue(0, "C".$i, $v['order_total_money']);
			
			$EXCELBuilder->setCellValue(0, "D".$i, $v['qiankuan']);
			$EXCELBuilder->setCellValue(0, "E".$i, $v['create_time']);
			$EXCELBuilder->setCellValue(0, "F".$i, $v['true_name']);
			
			
			$i++;
			$n++;
		}
		
		$d_end=$i-1;

		$EXCELBuilder->setOutlineBorder(0, "A2:F$d_end","thick");
		$EXCELBuilder->setInsideBorder(0, "A2:F$d_end","thin");
		$EXCELBuilder->setHorizontal(0, "A1:H1", "center");
		$EXCELBuilder->setHorizontal(0, "A$d_start:F$d_end", "center");
		$EXCELBuilder->setFontSize(0, "A1:F1", 20);
		$EXCELBuilder->setFontSize(0, "A2:F$i", 14);
		
		$EXCELBuilder->setColumnWidth(0, A, 15);
		$EXCELBuilder->setColumnWidth(0, B, 30);
		$EXCELBuilder->setColumnWidth(0, C, 30);
		$EXCELBuilder->setColumnWidth(0, D, 40);
		$EXCELBuilder->setColumnWidth(0, E, 15);
		$EXCELBuilder->setColumnWidth(0, F, 15);
		
		

		$EXCELBuilder->FileOutput(0,"赊款订单");
	}
	/** 其他Action **/

	
	public function detailAction()
    {

    	$order_id=I("get.order_id");
		$where["order_id"]=$order_id;

        // 检查车销单权限
        $depot_id = $this->_depot_id;

    	$res=M("car_orders")->where($where)->find();

        if ($depot_id > 0) {
            if (!$res["repertory_id"] == $depot_id) {
                echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
            }
        }

		$goods=M("car_orders_goods")->where($where)->select();
		$this->assign("aOrder",$res);
		$this->assign("aGoods",$goods);
        $this->display();
    }
	


	public function QDetailsAction(){

		$p=I("get.p",1);

		$pnum=I("get.pnum",10);

		$order_id = I("get.order_id");
		$where["orderid"]=$order_id;
		$list=M("car_orders_qiankuan")
		->field("zdb_car_orders_qiankuan.orderid,zdb_car_orders.cust_name,zdb_car_orders_qiankuan.price,zdb_car_orders_qiankuan.addtime,zdb_car_orders.staff_id as order_staff_id,zdb_car_orders_qiankuan.staff_id as qiankuan_staff_id,mark")
		->join("zdb_car_orders on zdb_car_orders.order_id=zdb_car_orders_qiankuan.orderid")
		->where($where)->page($p,$pnum)->select();
		$total=M("car_orders_qiankuan")
		->field("zdb_car_orders_qiankuan.orderid,zdb_car_orders.cust_name,zdb_car_orders_qiankuan.price,zdb_car_orders_qiankuan.addtime,zdb_car_orders.staff_id as order_staff_id,zdb_car_orders_qiankuan.staff_id as qiankuan_staff_id,mark")
		->join("zdb_car_orders on zdb_car_orders.order_id=zdb_car_orders_qiankuan.orderid")
		->where($where)->count();
		
		
		$userList=M("admin_user")->select();
		$goods=array();
		foreach($userList as $k=>$v){
			$goods[$v["admin_id"]]=$v["true_name"];
		}
		foreach($list as $k=>$v){
			$list[$k]["order_staff_name"]=$goods[$v["order_staff_id"]];
			$list[$k]["qian_staff_name"]=$goods[$v["qiankuan_staff_id"]];
		}
			
		$page=get_page_code($total,$pnum,$p,$page_code_len=5);
		$this->assign('order_id',$order_id);
		$this->assign("pagelist",$page);
		$this->assign("pnum",$pnum);
		$this->assign("list",$list);
		$this->assign('urlPara',array("order_id"=>$order_id));
		if($_GET["export"]=="export"){
			$this->BuildEXCELDetail($list);
		}
		$this->display();
	}
	
	private function BuildEXCELDetail($data)
	{
		$list=$data;
		$EXCELBuilder=EXCELBuilder::getInstance();
		$i=1;
		$v="赊款订单";
		$EXCELBuilder->setCellValue(0, "A".$i, $v);
		$EXCELBuilder->mergeCells(0, "A$i:G$i");

		$i++;
		$d_start=$i;
		$EXCELBuilder->setCellValue(0, "A".$i, "行号");
		$EXCELBuilder->setCellValue(0, "B".$i, "赊款店铺");
		$EXCELBuilder->setCellValue(0, "C".$i, "清欠金额");
		$EXCELBuilder->setCellValue(0, "D".$i, "业务员");
		$EXCELBuilder->setCellValue(0, "E".$i, "清欠操作人");		
		$EXCELBuilder->setCellValue(0, "F".$i, "清欠时间");
		$EXCELBuilder->setCellValue(0, "G".$i, "备注");
		
		
		$i++;
		$n=1;
		
		
		foreach($list as $v){
			$EXCELBuilder->setCellValue(0, "A".$i, $n);
			$EXCELBuilder->setCellValueExplicit(0, "B".$i,$v['cust_name']);
			$EXCELBuilder->setCellValue(0, "C".$i, $v['price']);
			
			$EXCELBuilder->setCellValue(0, "D".$i, $v['order_staff_name']);
			$EXCELBuilder->setCellValue(0, "E".$i, $v['qian_staff_name']);
			$EXCELBuilder->setCellValue(0, "F".$i, date("Y-m-d H:i:s",$v['addtime']));
			$EXCELBuilder->setCellValue(0, "G".$i, $v['mark']);
			
			
			$i++;
			$n++;
		}
		
		$d_end=$i-1;

		$EXCELBuilder->setOutlineBorder(0, "A2:G$d_end","thick");
		$EXCELBuilder->setInsideBorder(0, "A2:G$d_end","thin");
		$EXCELBuilder->setHorizontal(0, "A1:G1", "center");
		$EXCELBuilder->setHorizontal(0, "A$d_start:G$d_end", "center");
		$EXCELBuilder->setFontSize(0, "A1:G1", 20);
		$EXCELBuilder->setFontSize(0, "A2:G$i", 14);
		
		$EXCELBuilder->setColumnWidth(0, A, 15);
		$EXCELBuilder->setColumnWidth(0, B, 30);
		$EXCELBuilder->setColumnWidth(0, C, 30);
		$EXCELBuilder->setColumnWidth(0, D, 40);
		$EXCELBuilder->setColumnWidth(0, E, 15);
		$EXCELBuilder->setColumnWidth(0, F, 30);
		$EXCELBuilder->setColumnWidth(0, G, 30);
		
		

		$EXCELBuilder->FileOutput(0,"赊款订单");
	}
	//撤销操作
	public function chexiaoAction(){
		$order_id=I("post.order_id");
		$where["order_id"]=$order_id;

        // 检查车销单权限
        $depot_id = $this->_depot_id;

        if ($depot_id > 0) {

            $where["repertory_id"] = $depot_id;
        }
		
		$data["cancel_time"]=time();
		$data["is_cancel"]=1;
		
		$res=M("car_orders")->where($where)->save($data);
		if($res){
			echo 1;
		}else{
			echo 0;
		}
	}
	/*
	 *清欠页面 
	 */
	public function qingqianAction(){
		$id=I("get.id");
		$where["order_id"]=$id;

        // 检查车销单权限
        $depot_id = $this->_depot_id;

        if ($depot_id > 0) {

            $where["repertory_id"] = $depot_id;
        }

		$res=M("car_orders")->field("order_id,order_code,order_total_money,(order_total_money-order_real_money) as order_qian_money,cust_name")->where($where)->find();

        if (!$res) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }
		
		$where1["orderid"]=$id;
		$resQ=M("car_orders_qiankuan")->field("sum(price) as qingqianMoney")->where($where1)->find();
		
		
		$res["order_qian_money"]=$res["order_qian_money"] -$resQ["qingqianmoney"];
		
		$this->assign("order_id",$id);
		$this->assign("res",$res);
		$this->display();
	}
	/*
	 * 清欠方法
	 * llx  对清欠算法做调整
	 */
	public function qingqianExAction(){
		
		$order_id=I("post.order_id","1");
		$remark=I("post.remark","1");
		$money=I("post.money","50");
		$qiankuanMoney=I("post.qiankuanMoney","50");
		
		if($money<=0){
			echo json_encode(array("code"=>"5","msg"=>"清欠金额要大于0"));
			exit;
		}
		$where["order_id"]=$order_id;

        // 检查车销单权限
        $depot_id = $this->_depot_id;

        if ($depot_id > 0) {

            $where["repertory_id"] = $depot_id;
        }
		
		
		//$qiankuan=M("st_orders")->where($where)->getField("(order_total_money-order_real_money) as qiankuan");
		
		
		if($money>$qiankuanMoney){
			echo json_encode(array("code"=>"4","msg"=>"清欠金额不能大于欠款金额"));
			exit;
		}else{
			
			
			$res=M("car_orders")->where($where)->field('staff_id,cust_id,order_total_money,order_real_money')->find();
			if($res){
				$data["orderid"]=$order_id;
				$data["cust_id"]=$res['cust_id'];
				$data["price"]=$money;
				$data["staff_id"]=session("admin_id");
				$data['mark']=$remark;
				$data['addtime']=time();
				$data["qk_type"] = '1';
				
		    	$res1=M("car_orders_qiankuan")->add($data);

				if($res1){
					
					
					
					
					if($money==$qiankuanMoney){
						M("car_orders")->where($where)->save(array("is_full_pay"=>1));
						echo json_encode(array("code"=>"1","msg"=>"清欠成功,本订单清欠完成"));
						exit;
					}
					echo json_encode(array("code"=>"1","msg"=>"清欠成功"));
				}else{
					echo json_encode(array("code"=>"2","msg"=>"清欠记录插入错误"));
				}
			}else{
				echo json_encode(array("code"=>"3","msg"=>"清欠错误"));
			}
		}
		
	}
	
    
	
}

/*************************** end ************************************/