<?php

/*******************************************************************
 ** 文件名称: PresaleSummaryController.class.php
 ** 功能描述: 系统后台预单汇总控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;

class PresaleSummaryController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){

	    $type = I('get.type');
        $this->assign("type", $type);

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);
         
	    // 搜索条件
		//$queryDepot = intval($_GET['did']);
		if($depotID){
			$queryDepot = $depotID;
		}else{
			$queryDepot = intval($_GET['did']);
		}
		
		$beginTime = !empty($_GET['st']) ? $_GET['st'] : date("Y-m-d 00:00:00");
		$entTime = !empty($_GET['et']) ? $_GET['et'] : date("Y-m-d 23:59:59");
		$this->assign('queryDepot', $queryDepot);
		$this->assign('queryBeginTime', $beginTime);
		$this->assign('queryEndTime', $entTime);



		// 汇总大数据
		$summaryData = array();
		$shop_ids = array();

		// 搜索条件
		if($queryDepot > 0 && !empty($beginTime) && !empty($entTime))
		{
			// 拼接Where条件
			$where['o.is_cancel'] = 0;
			$where['o.repertory_id'] = $queryDepot;
			$beginTime = strtotime($beginTime);
			$entTime = strtotime($entTime);
			$where['o.add_time'] = array(array('gt', $beginTime),array('lt', $entTime));

			// 查询销售预单
			$salesResult = M('presale_orders_goods')->alias('og')->field("b.brand_id, b.brand_name, o.org_parent_id as org_id, c.class_id, c.class_name, og.*")
			->join('__PRESALE_ORDERS__ as o on og.order_id = o.order_id')
			->join('__GOODS_INFO__ as g on og.goods_id = g.goods_id')
			->join('__GOODS_BRAND__ as b on g.brand_id = b.brand_id')
			->join('__GOODS_CLASS__ as c on g.class_id = c.class_id')
			->where($where)->order('o.org_parent_id asc, o.order_id asc')->select();
			
			// 添加销售记录到 汇总大数据 $summaryData
			foreach($salesResult as $item){
			    $this->addSaleOrder($item, $summaryData);
                array_push($shop_ids, $item['cust_id']);

                //print_r($item);
			}

			// 查询终端退货
    			$returnResult = M('presale_return_goods')->alias('og')->field("b.brand_id, b.brand_name, o.org_parent_id as org_id, c.class_id, c.class_name, og.*, o.cust_id")
			->join('__PRESALE_RETURN__ as o on og.return_id = o.return_id')
			->join('__GOODS_INFO__ as g on og.goods_id = g.goods_id')
			->join('__GOODS_BRAND__ as b on g.brand_id = b.brand_id')
                ->join('__GOODS_CLASS__ as c on g.class_id = c.class_id')
			->where($where)->order('o.org_parent_id asc, o.return_id asc')->select();
			
			// 添加终端退货记录到 汇总大数据 $summaryData
			foreach($returnResult as $item){
			    $this->addReturnOrder($item, $summaryData);
                array_push($shop_ids, $item['cust_id']);
			}

			// 查询调换货
			$changeResult = M('presale_change_goods')->alias('og')->field("b.brand_id, b.brand_name, o.org_parent_id as org_id, c.class_id, c.class_name, og.*, o.cust_id")
			->join('__PRESALE_CHANGE__ as o on og.change_id = o.change_id')
			->join('__GOODS_INFO__ as g on og.goods_id = g.goods_id')
			->join('__GOODS_BRAND__ as b on g.brand_id = b.brand_id')
                ->join('__GOODS_CLASS__ as c on g.class_id = c.class_id')
			->where($where)->order('o.org_parent_id asc, o.change_id asc')->select();
			
			// 添加调换货记录到 汇总大数据 $summaryData
			foreach($changeResult as $item){
			    $this->addChangeOrder($item, $summaryData);
                array_push($shop_ids, $item['cust_id']);
			}
		}

        $shop_ids = array_unique($shop_ids);

        if (!empty($shop_ids)) {
            $where = [];
            $where['cust_id'] = ["in", $shop_ids];

            $shops = M("customer_info")->where($where)->select();

            $this->assign('shops', $shops);
        }

		$this->assign('summaryData', $summaryData);

		if($_GET["export"]=="export"){
			$this->BuildEXCEL($summaryData); 
			exit;
		}

		$this->assign('depot_list', format_array(queryDepot($depotID),'repertory_id','repertory_name'));
		$this->display();
    }
    
    public function selectShopAction(){
    	
    	$where["repertory_id"]=$_SESSION['depot_id'];
    	$shops=M("customer_info")->where($where)->select();
    	
    	$this->assign("shops",$shops);
    	$this->display();
    }
    
    public function settingAction(){
    	
    	
    	
    	$p=I("p",1);
    	$pnum=I("pnum",10);
    	$custName=I("custName");
    	$where["cust_name"]=array("like","%$custName%");
    	$where["repertory_id"]=$_SESSION['depot_id'];
    	$cust_ids=I("cust_ids");
    	if($cust_ids){
    		$cust_ids = explode(',',$cust_ids);
    		$where["cust_id"]=array("not in",$cust_ids); 

    	}
    	
    	
    	$total=M("customer_info")->where($where)->count();
    	$shops=M("customer_info")->where($where)->page($p,$pnum)->select();
    	$page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        
        $this->assign('pagelist',$page);//分页显示
    	$this->assign("shops",$shops);
    	$this->assign("urlPara",array("custName"=>$custName));
    	$this->assign("pnum",$pnum);
    	$this->display();
    }

    //按店铺汇总
    public function shopAction(){
        $type = I('get.type');
        $this->assign("type", $type);

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        // 搜索条件
        //$queryDepot = intval($_GET['did']);
        if($depotID){
            $queryDepot = $depotID;
        }else{
            $queryDepot = intval($_GET['did']);
        }
        //经销商
        $queryOrg = intval($_GET['oid']);
        //过滤的品类
        $filterClass = I('class');

        $beginTime = !empty($_GET['st']) ? $_GET['st'] : date("Y-m-d 00:00:00");
        $entTime = !empty($_GET['et']) ? $_GET['et'] : date("Y-m-d 23:59:59");
        $this->assign('queryDepot', $queryDepot);
        $this->assign('queryBeginTime', $beginTime);
        $this->assign('queryEndTime', $entTime);
        $this->assign('queryOrg', $queryOrg);

        $shopIds=I("shopIds");
        $shopIds = explode(',',$shopIds);
        // 汇总大数据
        $summaryData = array();
        $shop_ids = array();

        // 搜索条件
        if($queryDepot > 0 && !empty($beginTime) && !empty($entTime))
        {
            // 拼接Where条件
            $where['o.is_cancel'] = 0;
            $where['o.repertory_id'] = $queryDepot;
            $beginTime = strtotime($beginTime);
            $entTime = strtotime($entTime);
            $where['o.add_time'] = array(array('gt', $beginTime),array('lt', $entTime));
            if(I("shopIds")){
            	$where["o.cust_id"]=array("in",$shopIds);
            }
            
            if(!empty($filterClass)){
                $where['g.class_id'] = array('in',$filterClass);
            }
            
            // 查询销售预单
            $salesResult = M('presale_orders_goods')->alias('og')->field("b.brand_id, b.brand_name, o.org_parent_id as org_id, c.class_id, c.class_name, og.*")
                ->join('__PRESALE_ORDERS__ as o on og.order_id = o.order_id')
                ->join('__GOODS_INFO__ as g on og.goods_id = g.goods_id')
                ->join('__GOODS_BRAND__ as b on g.brand_id = b.brand_id')
                ->join('__GOODS_CLASS__ as c on g.class_id = c.class_id')
                ->where($where)->order('o.org_parent_id asc, o.order_id asc')->select();

            // 添加销售记录到 汇总大数据 $summaryData
            foreach($salesResult as $item){
                $this->addSaleOrder($item, $summaryData);
                array_push($shop_ids, $item['cust_id']);

                //print_r($item);
            }

            // 查询终端退货
            $returnResult = M('presale_return_goods')->alias('og')->field("b.brand_id, b.brand_name, o.org_parent_id as org_id, c.class_id, c.class_name, og.*, o.cust_id")
                ->join('__PRESALE_RETURN__ as o on og.return_id = o.return_id')
                ->join('__GOODS_INFO__ as g on og.goods_id = g.goods_id')
                ->join('__GOODS_BRAND__ as b on g.brand_id = b.brand_id')
                ->join('__GOODS_CLASS__ as c on g.class_id = c.class_id')
                ->where($where)->order('o.org_parent_id asc, o.return_id asc')->select();

            // 添加终端退货记录到 汇总大数据 $summaryData
            foreach($returnResult as $item){
                $this->addReturnOrder($item, $summaryData);
                array_push($shop_ids, $item['cust_id']);
            }

            // 查询调换货
            $changeResult = M('presale_change_goods')->alias('og')->field("b.brand_id, b.brand_name, o.org_parent_id as org_id, c.class_id, c.class_name, og.*, o.cust_id")
                ->join('__PRESALE_CHANGE__ as o on og.change_id = o.change_id')
                ->join('__GOODS_INFO__ as g on og.goods_id = g.goods_id')
                ->join('__GOODS_BRAND__ as b on g.brand_id = b.brand_id')
                ->join('__GOODS_CLASS__ as c on g.class_id = c.class_id')
                ->where($where)->order('o.org_parent_id asc, o.change_id asc')->select();

            // 添加调换货记录到 汇总大数据 $summaryData
            foreach($changeResult as $item){
                $this->addChangeOrder($item, $summaryData);
                array_push($shop_ids, $item['cust_id']);
            }
        }

        $shop_ids = array_unique($shop_ids);
        if(I("shopIds")){
        	$shop_ids=$shopIds;
        }
        
        
        if (!empty($shop_ids)) {
            $where = [];
            $where['cust_id'] = ["in", $shop_ids];

            $shops = M("customer_info")->where($where)->select();

            $this->assign('shops', $shops);
        }

        $this->assign('summaryData', $summaryData);

        if($_GET["exportS"]=="exportS"){
            $this->BuildEXCELS($summaryData, $shop_ids);
            exit;
        }

        if($_GET["markPurchase"]=="markPurchase"){

            $parData = array(
                'repertory_id' => $queryDepot,
                'start_time' => $beginTime,
                'end_time' => $entTime,
                'order_data' => $summaryData,
                'shop_ids' => $shop_ids,
            );

            $this->createPurchaseOrder($parData);
            exit;
        }

        //仓库
        $this->assign('depot_list', queryDepot($depotID));
        //经销商
        $this->assign('org_list', queryDealer($depotID) );

        $this->display();
    }

    //创建采购单
    private function  createPurchaseOrder($parData){

        $summaryData = $parData['order_data'];

        foreach($summaryData as $k1=>$v1){
            $class_list = $v1['class_list'];

            foreach($class_list as $k2=>$v2){
                $data = $parData;
                $order_data =  array('shop_ids'=>$parData['shop_ids'],'data'=> $v2['goods_list'] );
                $data['class_name'] = $v2['class_name'];
                $data['order_data'] = json_encode($order_data);
                $data['add_time'] = time();
                $data['order_code'] = create_uniqid_code('PE',0);

                M('purchase_orders')->add($data);

            }
        }

        alertToUrl('成功生成采购单','./shop');

    }

    // 根据店铺信息导出数据
    private function BuildEXCELS($data, $shop_ids) {

        if (empty($shop_ids)) {
            $shop_ids = [0];
        }

        $summaryData=$data;
        $EXCELBuilder=EXCELBuilder::getInstance();
        $i=1;

        $where = [];
        $where['cust_id'] = ["in", $shop_ids];

        $shops = M("customer_info")->where($where)->select();

        $ci = "";

        foreach($summaryData as $data){
            $i++;
            $v=$data["org_name"];
            $EXCELBuilder->setCellValue(0, "A".$i, $v);
            $EXCELBuilder->mergeCells(0, "A$i:F$i");

            $i++;
            $d_start=$i;
            $EXCELBuilder->setCellValue(0, "A".$i, "分类");
            $EXCELBuilder->setCellValue(0, "B".$i, "名称");
            $EXCELBuilder->setCellValue(0, "C".$i, "合计");
            $j = 'D';
            foreach ($shops as $val) {
                $EXCELBuilder->setCellValue(0, $j.$i, $val['cust_name']);
                $ci = $j;
                $j++;

            }


            $i++;

            foreach ($data["class_list"] as $class) {
                $EXCELBuilder->setCellValue(0, "A".$i, $class["class_name"]."");
                $count=count($class["goods_list"]);
                $mergeCellsEnd=$i+$count-1;
                if($mergeCellsEnd>1){
                    $EXCELBuilder->mergeCells(0, "A".$i.":A".$mergeCellsEnd);
                }

                foreach($class["goods_list"] as $goods){

                    //print_r($goods);die();

                    $EXCELBuilder->setCellValueExplicit(0, "B".$i,$goods['goods_name']."");
                    $EXCELBuilder->setCellValue(0, "C".$i, $goods['total_numstring']."");

                    $ji = 'D';
                    //print_r($goods);die;
                    foreach ($shops as $val) {

                        $EXCELBuilder->setCellValue(0, $ji.$i, $goods[$val['cust_id']]['total_numstring']);
                        $ji++;
                    }

                    $i++;

                }
            }


            $i++;
        }

        $d_end=$i-1;

        $EXCELBuilder->setOutlineBorder(0, "A2:$ci$d_end","thick");
        $EXCELBuilder->setInsideBorder(0, "A2:$ci$d_end","thin");
        $EXCELBuilder->setHorizontal(0, "A1:$ci"."1", "center");
        $EXCELBuilder->setHorizontal(0, "A$d_start:$ci$d_end", "center");
        $EXCELBuilder->setFontSize(0, "A1:$ci"."1", 20);
        $EXCELBuilder->setFontSize(0, "A2:$ci$i", 14);

        $EXCELBuilder->setColumnWidth(0, A, 15);
        $EXCELBuilder->setColumnWidth(0, B, 20);
        $EXCELBuilder->setColumnWidth(0, C, 20);

        $jj = 'D';
        foreach ($shops as $val) {
            //echo $jj;
            $EXCELBuilder->setColumnWidth(0, "$jj", 20);
            $jj++;
        }


        $EXCELBuilder->FileOutput(0,"店铺预单汇总");
    }


    // Excel导出数据
	private function BuildEXCEL($data)
	{
		$summaryData=$data;
		$EXCELBuilder=EXCELBuilder::getInstance();
		$i=1;
		
        foreach($summaryData as $data){
        	$i++;
        	$v=$data["org_name"];
		    $EXCELBuilder->setCellValue(0, "A".$i, $v);
		    $EXCELBuilder->mergeCells(0, "A$i:F$i");

			$i++;
			$d_start=$i;
			$EXCELBuilder->setCellValue(0, "A".$i, "品牌");
			$EXCELBuilder->setCellValue(0, "B".$i, "产品");
			$EXCELBuilder->setCellValue(0, "C".$i, "规格");
			$EXCELBuilder->setCellValue(0, "D".$i, "销售");
			$EXCELBuilder->setCellValue(0, "E".$i, "退货");		
			$EXCELBuilder->setCellValue(0, "F".$i, "调出");
			$EXCELBuilder->setCellValue(0, "G".$i, "换回");
			$EXCELBuilder->setCellValue(0, "H".$i, "小计");
			$EXCELBuilder->setCellValue(0, "I".$i, "出货量");
		
		
			$i++;
			$n=1;
		
		
			foreach($data["brand_list"] as $brand){
			
			
				$EXCELBuilder->setCellValue(0, "A".$i, $brand["brand_name"]."");
				$count=count($brand["goods_list"]);
				$mergeCellsEnd=$i+$count-1;
				if($mergeCellsEnd>1){
					$EXCELBuilder->mergeCells(0, "A".$i.":A".$mergeCellsEnd);
				}
			
				foreach($brand["goods_list"] as $goods){
				
					$EXCELBuilder->setCellValueExplicit(0, "B".$i,$goods['goods_name']."");
					$EXCELBuilder->setCellValue(0, "C".$i, $goods['goods_spec']."");
			
					$EXCELBuilder->setCellValue(0, "D".$i, $goods['sales_numstring']."");
					$EXCELBuilder->setCellValue(0, "E".$i, $goods['return_numstring']."");
					$EXCELBuilder->setCellValue(0, "F".$i, $goods['change_out_numstring']."");
					$EXCELBuilder->setCellValue(0, "G".$i, $goods['change_in_numstring']."");
					$EXCELBuilder->setCellValue(0, "H".$i, sprintf("%.2f", $goods['total']) ."元");
					$EXCELBuilder->setCellValue(0, "I".$i, $goods['total_numstring']."");
			
			
					$i++;
					$n++;
				}
			
			}
			$i++;
		}

		$d_end=$i-1;

		$EXCELBuilder->setOutlineBorder(0, "A2:I$d_end","thick");
		$EXCELBuilder->setInsideBorder(0, "A2:I$d_end","thin");
		$EXCELBuilder->setHorizontal(0, "A1:I1", "center");
		$EXCELBuilder->setHorizontal(0, "A$d_start:I$d_end", "center");
		$EXCELBuilder->setFontSize(0, "A1:I1", 20);
		$EXCELBuilder->setFontSize(0, "A2:I$i", 14);
		
		$EXCELBuilder->setColumnWidth(0, A, 15);
		$EXCELBuilder->setColumnWidth(0, B, 30);
		$EXCELBuilder->setColumnWidth(0, C, 30);
		$EXCELBuilder->setColumnWidth(0, D, 40);
		$EXCELBuilder->setColumnWidth(0, E, 15);
		$EXCELBuilder->setColumnWidth(0, F, 15);
		$EXCELBuilder->setColumnWidth(0, G, 40);
		$EXCELBuilder->setColumnWidth(0, H, 15);
		$EXCELBuilder->setColumnWidth(0, I, 15);
		
		$EXCELBuilder->FileOutput(0,"预单汇总");
	}
	
	// 添加销售预单
	public function addSaleOrder($item, &$summaryData)
	{
		// 获取经销商, 品牌ID, 商品ID, 货品ID
		$org_parent_id = $item['org_id'];
		$brand_id = $item['brand_id'];
		$brand_name = $item['brand_name'];
        $class_id = $item['class_id'];
        $class_name = $item['class_name'];
		$goods_id = $item['goods_id'];
		$goods_name = $item['goods_name'];
		$goods_spec = $item['goods_spec'];
		$cv_id = $item['cv_id'];
		$singleprice = $item['singleprice'];
		$number = $item['number'];
        $shop_id = $item['cust_id'];

		// 不存在经销商
		if(empty($summaryData[$org_parent_id]))
		{
		    $orgKey = "org_$org_parent_id";
		    $orgInfo = S($orgKey);
            if ($orgInfo == false) {
                $orgInfo = M('org_info')->where("org_id = $org_parent_id")->field('org_id, org_name, contacts, mobile')->find();
                S($orgKey, $orgInfo, 86400);
            }

			$summaryData[$org_parent_id] = array('org_name'=>$orgInfo['org_name'], 'contacts'=>$orgInfo['contacts'], 'mobile'=>$orgInfo['mobile'], 'brand_list'=>array());
		}
		
		// 不存在品牌
		if(empty($summaryData[$org_parent_id]['brand_list'][$brand_id]))
		{
			$summaryData[$org_parent_id]['brand_list'][$brand_id] = array('brand_id'=>$brand_id, 'brand_name'=>$brand_name, 'goods_list'=>array());
		}

		// 不存在分类
        if(empty($summaryData[$org_parent_id]['class_list'][$class_id])) {
            $summaryData[$org_parent_id]['class_list'][$class_id] = array('class_id'=>$class_id, 'class_name'=>$class_name, 'goods_list'=>array());
        }
		
		// 不存在商品
		if(empty($summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]))
		{
			$tempArray = array();
			$tempArray['goods_name'] = $goods_name;
			$tempArray['goods_spec'] = $goods_spec;
			$tempArray['sales_numstring'] = '';
			$tempArray['sales_number'] = 0;
			$tempArray['return_numstring'] = '';
			$tempArray['return_number'] = 0;
			$tempArray['change_in_numstring'] = '';
			$tempArray['change_in_number'] = 0;
			$tempArray['change_out_numstring'] = '';
			$tempArray['change_out_number'] = 0;
			$tempArray['total'] = 0.00;
			$tempArray['total_num'] = 0;
			$tempArray['total_numstring'] = 0;

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id] = $tempArray;
		}
		if(empty($summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]))
		{
			$tempArray = array();
			$tempArray['goods_name'] = $goods_name;
			$tempArray['goods_spec'] = $goods_spec;
			$tempArray['sales_numstring'] = '';
			$tempArray['sales_number'] = 0;
			$tempArray['return_numstring'] = '';
			$tempArray['return_number'] = 0;
			$tempArray['change_in_numstring'] = '';
			$tempArray['change_in_number'] = 0;
			$tempArray['change_out_numstring'] = '';
			$tempArray['change_out_number'] = 0;
			$tempArray['total'] = 0.00;
			$tempArray['total_num'] = 0;
			$tempArray['total_numstring'] = 0;

            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id] = $tempArray;
		}

        // 店铺
        if(empty($summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id])) {

            $custKey = "cust_$shop_id";
            $shop = S($custKey);
            if ($shop == false) {
                $shop = M('customer_info')->where("cust_id = $shop_id")->field('cust_id, cust_name, contact, telephone')->find();
                S($custKey, $shop, 86400);
            }


            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id] = array(
                'cust_name'=>$shop['cust_name'],
                'contact'=>$shop['contact'],
                'telephone'=>$shop['telephone'],
                'total'=>0.00,
                'total_num'=>0,
                'total_numstring'=>0,
            );
        }

		// 增加货品包装数量（最小单位数量, 整件字符串数量）
		$add_small_number = getSmallNumber($cv_id, $number);
		$old_small_number = $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['sales_number'];
		$old_class_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['sales_number'];
		$old_shop_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['sales_number'];

        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['sales_number'] = ($add_small_number + $old_class_small_number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['sales_number'] = ($add_small_number + $old_shop_small_number);
        // 总共
		$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['sales_number'] = ($add_small_number + $old_small_number);

        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['sales_numstring'] = getGoodsUnitString($goods_id, $add_small_number + $old_class_small_number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['sales_numstring'] = getGoodsUnitString($goods_id, $add_small_number + $old_shop_small_number);
        // 总共
        $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['sales_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_small_number);

        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total'] += ($singleprice * $number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total'] += ($singleprice * $number);
        $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total'] += ($singleprice * $number);
		
		// 累计出货量, 销售加上调出
		$total_num = $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total_num'];
		$total_shop_num = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total_num'];
		$total_class_num = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total_num'];
        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total_num'] = ($total_class_num + $add_small_number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total_num'] = ($total_shop_num + $add_small_number);

		$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total_num'] = ($total_num + $add_small_number);
        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total_numstring'] = getGoodsUnitString($goods_id, $add_small_number + $total_class_num);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total_numstring'] = getGoodsUnitString($goods_id, $add_small_number + $total_shop_num);


		$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$total_num);

        //print_r($summaryData);die;

	}

	// 添加终端退货单
	public function addReturnOrder($item, &$summaryData)
	{
		// 获取经销商, 品牌ID, 商品ID, 货品ID
		$org_parent_id = $item['org_id'];
		$brand_id = $item['brand_id'];
		$brand_name = $item['brand_name'];
        $class_id = $item['class_id'];
        $class_name = $item['class_name'];
		$goods_id = $item['goods_id'];
		$goods_name = $item['goods_name'];
		$goods_spec = $item['goods_spec'];
		$cv_id = $item['cv_id'];
		$singleprice = $item['goods_money'];
		$number = $item['goods_num'];
        $shop_id = $item['cust_id'];

		// 不存在经销商
		if(empty($summaryData[$org_parent_id]))
		{
            $orgKey = "org_$org_parent_id";
            $orgInfo = S($orgKey);
            if ($orgInfo == false) {
                $orgInfo = M('org_info')->where("org_id = $org_parent_id")->field('org_id, org_name, contacts, mobile')->find();
                S($orgKey, $orgInfo, 86400);
            }

			$summaryData[$org_parent_id] = array('org_name'=>$orgInfo['org_name'], 'contacts'=>$orgInfo['contacts'], 'mobile'=>$orgInfo['mobile'], 'brand_list'=>array());
		}
		
		// 不存在品牌
		if(empty($summaryData[$org_parent_id]['brand_list'][$brand_id]))
		{
			$summaryData[$org_parent_id]['brand_list'][$brand_id] = array('brand_id'=>$brand_id, 'brand_name'=>$brand_name, 'goods_list'=>array());
		}

        // 不存在分类
        if(empty($summaryData[$org_parent_id]['class_list'][$class_id])) {
            $summaryData[$org_parent_id]['class_list'][$class_id] = array('class_id'=>$class_id, 'class_name'=>$class_name, 'goods_list'=>array());
        }
		
		// 不存在商品
		if(empty($summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]))
		{
			$tempArray = array();
			$tempArray['goods_name'] = $goods_name;
			$tempArray['goods_spec'] = $goods_spec;
			$tempArray['sales_numstring'] = '';
			$tempArray['sales_number'] = 0;
			$tempArray['return_numstring'] = '';
			$tempArray['return_number'] = 0;
			$tempArray['change_in_numstring'] = '';
			$tempArray['change_in_number'] = 0;
			$tempArray['change_out_numstring'] = '';
			$tempArray['change_out_number'] = 0;
			$tempArray['total'] = 0.00;
			$tempArray['total_num'] = 0;
			$tempArray['total_numstring'] = 0;
			
			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id] = $tempArray;
		}

		if(empty($summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]))
		{
			$tempArray = array();
			$tempArray['goods_name'] = $goods_name;
			$tempArray['goods_spec'] = $goods_spec;
			$tempArray['sales_numstring'] = '';
			$tempArray['sales_number'] = 0;
			$tempArray['return_numstring'] = '';
			$tempArray['return_number'] = 0;
			$tempArray['change_in_numstring'] = '';
			$tempArray['change_in_number'] = 0;
			$tempArray['change_out_numstring'] = '';
			$tempArray['change_out_number'] = 0;
			$tempArray['total'] = 0.00;
			$tempArray['total_num'] = 0;
			$tempArray['total_numstring'] = 0;

            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id] = $tempArray;
		}

        // 店铺
        if(empty($summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id])) {
            $custKey = "cust_$shop_id";
            $shop = S($custKey);
            if ($shop == false) {
                $shop = M('customer_info')->where("cust_id = $shop_id")->field('cust_id, cust_name, contact, telephone')->find();
                S($custKey, $shop, 86400);
            }

            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id] = array(
                'cust_name'=>$shop['cust_name'],
                'contact'=>$shop['contact'],
                'telephone'=>$shop['telephone'],
                'total'=>0.00,
                'total_num'=>0,
                'total_numstring'=>0,
            );
        }

		// 增加货品包装数量（最小单位数量, 整件字符串数量）
		$add_small_number = getSmallNumber($cv_id, $number);
		$old_small_number = $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['return_number'];
        $old_class_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['return_number'];
        $old_shop_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['return_number'];
        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['return_number'] = ($add_small_number + $old_class_small_number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['return_number'] = ($add_small_number + $old_shop_small_number);

		$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['return_number'] = ($add_small_number + $old_small_number);
        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['return_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_shop_small_number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['return_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_class_small_number);

		$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['return_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_small_number);
        // 店铺
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total'] -= ($singleprice * $number);
        $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total'] -= ($singleprice * $number);

		$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total'] -= ($singleprice * $number);
	}
	
	// 添加调换货单
	public function addChangeOrder($item, &$summaryData)
	{
		// 获取经销商, 品牌ID, 商品ID, 货品ID
		$org_parent_id = $item['org_id'];
		$brand_id = $item['brand_id'];
		$brand_name = $item['brand_name'];
        $class_id = $item['class_id'];
        $class_name = $item['class_name'];
		$goods_id = $item['goods_id'];
		$goods_name = $item['goods_name'];
		$goods_spec = $item['goods_spec'];
		$cv_id = $item['cv_id'];
		$singleprice = $item['singleprice'];
		$number = $item['number'];
		$is_change_in = $item['is_change_in'];
        $shop_id = $item['cust_id'];

		// 不存在经销商
		if(empty($summaryData[$org_parent_id]))
		{
            $orgKey = "org_$org_parent_id";
            $orgInfo = S($orgKey);
            if ($orgInfo == false) {
                $orgInfo = M('org_info')->where("org_id = $org_parent_id")->field('org_id, org_name, contacts, mobile')->find();
                S($orgKey, $orgInfo, 86400);
            }

			$summaryData[$org_parent_id] = array('org_name'=>$orgInfo['org_name'], 'contacts'=>$orgInfo['contacts'], 'mobile'=>$orgInfo['mobile'], 'brand_list'=>array());
		}
		
		// 不存在品牌
		if(empty($summaryData[$org_parent_id]['brand_list'][$brand_id]))
		{
			$summaryData[$org_parent_id]['brand_list'][$brand_id] = array('brand_id'=>$brand_id, 'brand_name'=>$brand_name, 'goods_list'=>array());
		}

        // 不存在分类
        if(empty($summaryData[$org_parent_id]['class_list'][$class_id])) {
            $summaryData[$org_parent_id]['class_list'][$class_id] = array('class_id'=>$class_id, 'class_name'=>$class_name, 'goods_list'=>array());
        }
		
		// 不存在商品
		if(empty($summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]))
		{
			$tempArray = array();
			$tempArray['goods_name'] = $goods_name;
			$tempArray['goods_spec'] = $goods_spec;
			$tempArray['sales_numstring'] = '';
			$tempArray['sales_number'] = 0;
			$tempArray['return_numstring'] = '';
			$tempArray['return_number'] = 0;
			$tempArray['change_in_numstring'] = '';
			$tempArray['change_in_number'] = 0;
			$tempArray['change_out_numstring'] = '';
			$tempArray['change_out_number'] = 0;
			$tempArray['total'] = 0.00;
			$tempArray['total_num'] = 0;
			$tempArray['total_numstring'] = 0;
			
			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id] = $tempArray;
		}
		if(empty($summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]))
		{
			$tempArray = array();
			$tempArray['goods_name'] = $goods_name;
			$tempArray['goods_spec'] = $goods_spec;
			$tempArray['sales_numstring'] = '';
			$tempArray['sales_number'] = 0;
			$tempArray['return_numstring'] = '';
			$tempArray['return_number'] = 0;
			$tempArray['change_in_numstring'] = '';
			$tempArray['change_in_number'] = 0;
			$tempArray['change_out_numstring'] = '';
			$tempArray['change_out_number'] = 0;
			$tempArray['total'] = 0.00;
			$tempArray['total_num'] = 0;
			$tempArray['total_numstring'] = 0;

            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id] = $tempArray;
		}

        // 店铺
        if(empty($summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id])) {
            $custKey = "cust_$shop_id";
            $shop = S($custKey);
            if ($shop == false) {
                $shop = M('customer_info')->where("cust_id = $shop_id")->field('cust_id, cust_name, contact, telephone')->find();
                S($custKey, $shop, 86400);
            }

            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id] = array(
                'cust_name'=>$shop['cust_name'],
                'contact'=>$shop['contact'],
                'telephone'=>$shop['telephone'],
                'total'=>0.00,
                'total_num'=>0,
                'total_numstring'=>0,
            );
        }

		// 增加货品包装数量（最小单位数量, 整件字符串数量）
		if($is_change_in == 1)
		{
			$add_small_number = getSmallNumber($cv_id, $number);
			$old_small_number = $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['change_in_number'];
            $old_shop_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_in_number'];
            $old_class_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['change_in_number'];
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_in_number'] = ($add_small_number + $old_shop_small_number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['change_in_number'] = ($add_small_number + $old_class_small_number);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['change_in_number'] = ($add_small_number + $old_small_number);
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_in_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_shop_small_number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['change_in_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_class_small_number);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['change_in_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_small_number);
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total'] += ($singleprice * $number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total'] += ($singleprice * $number);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total'] += ($singleprice * $number);
		}
		else
		{
			$add_small_number = getSmallNumber($cv_id, $number);
			$old_small_number = $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['change_out_number'];
            $old_shop_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_in_number'];
            $old_class_small_number = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_in_number'];
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_out_number'] = ($getGoodsUnitString + $old_shop_small_number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['change_out_number'] = ($getGoodsUnitString + $old_class_small_number);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['change_out_number'] = ($getGoodsUnitString + $old_small_number);
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['change_out_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_shop_small_number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['change_out_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_class_small_number);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['change_out_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$old_small_number);
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total'] += ($singleprice * $number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total'] += ($singleprice * $number);
			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total'] += ($singleprice * $number);

			// 累计出货量, 销售加上调出
			$total_num = $summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total_num'];
			$total_shop_num = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total_num'];
			$total_class_num = $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total_num'];
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total_num'] = ($total_class_num + $add_small_number);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total_num'] = ($total_shop_num + $add_small_number);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total_num'] = ($total_num + $add_small_number);
            // 店铺
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id]['total_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$total_class_num);
            $summaryData[$org_parent_id]['class_list'][$class_id]['goods_list'][$goods_id][$shop_id]['total_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$total_shop_num);

			$summaryData[$org_parent_id]['brand_list'][$brand_id]['goods_list'][$goods_id]['total_numstring'] = getGoodsUnitString($goods_id, $add_small_number+$total_num);
		}
	}




	/** 其他Action **/


}

/*************************** end ************************************/