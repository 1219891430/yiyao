<?php

/*******************************************************************
 ** 文件名称: GoodsInfoController.class.php
 ** 功能描述: 系统后台商品管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;
use Common\Utils\EXCELBuilder;
use Common\Utils\BarCode128;

class GoodsInfoController extends BaseController {

    public function __construct(){
        parent::__construct();
	}

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction(){
		

		$mGoods=M("goods_info");
		
		$p = isset($_GET['p']) ? $_GET['p'] : 1;
        $bid = I("get.bid",0);
        $cid = I("get.cid",0);
        $gid = I("get.gid",'');
        $pnum = I("get.pnum",10);
		
		
        if($bid!=0) $where["p1.brand_id"]=$bid;
        if($cid!=0) $where["p1.class_id"]=$cid;
        if($gid!="") $where["p1.goods_name"]= array('LIKE','%'.$gid.'%');
		
		$total=$mGoods->table("zdb_goods_info p1")
            ->join("zdb_goods_class p2 on p2.class_id=p1.class_id")
            ->join("zdb_goods_brand p3 on p3.brand_id=p1.brand_id")
            ->where($where)
            ->count();
		$aGoods=$mGoods->field("p1.goods_id,p1.goods_name,p1.goods_spec,p3.brand_name,p2.class_name,p1.goods_convert_s,p1.goods_convert_m,p1.goods_convert_b,p1.is_close")
            ->table("zdb_goods_info p1")
            ->join("zdb_goods_class p2 on p2.class_id=p1.class_id")
            ->join("zdb_goods_brand p3 on p3.brand_id=p1.brand_id")
            ->where($where)
            ->page($p, $pnum)
            ->order("p1.brand_id")
            ->select();
		$page = get_page_code($total, $pnum, $p, $page_code_len = 10);
		
		foreach($aGoods as $k=>$v){
			$whereP["goods_id"]=$v["goods_id"];
			$product=M("goods_product")
                ->field("goods_unit,goods_unit_type")
                ->where($whereP)
                ->order("goods_unit_type")
                ->select();
            // 检测商品是否设置区域
            $depot_id = $this->_depot_id;
            $whereA["da.depot_id"] = $depot_id;
            $whereA["dag.goods_id"] = $v["goods_id"];
            $area = M("depot_area")->alias("da")
                ->join("left join __DEPOT_AREA_GOODS__ as dag on da.area_id=dag.area_id")
                ->where($whereA)
                ->find();

            $aGoods[$k]["area"] = $area["area_id"];

            // 检测是否设置经销商
            $whereD["depot_id"] = $depot_id;
            $whereD["goods_id"] = $v["goods_id"];
            $org = M("depot_stock")->where($whereD)->find();

            $aGoods[$k]["org_parent_id"] = $org["org_parent_id"];
            //->join("left join __DEPOT_STOCK__ as ds on p1.goods_id=ds.goods_id")

			foreach($product as $p){
				if($p["goods_unit_type"]==1){
					$aGoods[$k]["goods_unit_s"]=$p["goods_unit"];
				}elseif($p["goods_unit_type"]==2){
					$aGoods[$k]["goods_unit_m"]=$p["goods_unit"];
				}elseif($p["goods_unit_type"]==3){
					$aGoods[$k]["goods_unit_b"]=$p["goods_unit"];
				}
				
			}
		}
		
		
		$brandRes=M("goods_brand")->select();
		$this->assign("brandRes",$brandRes);
		$this->assign("classRes",getCatTree());
		$this->assign('urlPara', array("bid"=>$bid,"cid"=>$cid, 'gid' => $gid));
		$this->assign('pagelist', $page);
		$this->assign("pnum",$pnum);
		
        $this->assign("depot_id",$this->_depot_id);
        
        if($_GET["explode"]=="explode"){
        	
        	$this->BuildEXCEL($aGoods);
        }
		$this->assign("goods",$aGoods);

        //print_r($aGoods);die();

		$this->display();
    }
    
    // Excel导出数据
	private function BuildEXCEL($data)
	{
		$goods=$data;
		$EXCELBuilder=EXCELBuilder::getInstance();
		$i=1;
		$v="商品总库";
		$EXCELBuilder->setCellValue(0, "A".$i, $v);
		$EXCELBuilder->mergeCells(0, "A$i:H$i");

		$i++;
		$d_start=$i;
		$EXCELBuilder->setCellValue(0, "A".$i, "行号");
		$EXCELBuilder->setCellValue(0, "B".$i, "产品名称");
		$EXCELBuilder->setCellValue(0, "C".$i, "品类");
		$EXCELBuilder->setCellValue(0, "D".$i, "品牌");
		$EXCELBuilder->setCellValue(0, "E".$i, "小包装");		
		$EXCELBuilder->setCellValue(0, "F".$i, "中包装");
		$EXCELBuilder->setCellValue(0, "G".$i, "大包装");
		$EXCELBuilder->setCellValue(0, "H".$i, "转换系数");
		
		$i++;
		$n=1;
		
		
		foreach($goods as $v){
			$EXCELBuilder->setCellValue(0, "A".$i, $n);
			$EXCELBuilder->setCellValueExplicit(0, "B".$i,$v['goods_name']."/".$v['goods_spec']);
			$EXCELBuilder->setCellValue(0, "C".$i, $v['class_name']);
			
			$EXCELBuilder->setCellValue(0, "D".$i, $v['brand_name']);
			$EXCELBuilder->setCellValue(0, "E".$i, $v['goods_unit_s']);
			$EXCELBuilder->setCellValue(0, "F".$i, $v['goods_unit_m']);
			$EXCELBuilder->setCellValue(0, "G".$i, $v['goods_unit_b']);

			$EXCELBuilder->setCellValue(0, "H".$i,  $v['goods_convert_s']."*".$v['goods_convert_m']."*".$v["goods_convert_b"]);
			
			$i++;
			$n++;
		}
		
		$d_end=$i-1;

		$EXCELBuilder->setOutlineBorder(0, "A2:H$d_end","thick");
		$EXCELBuilder->setInsideBorder(0, "A2:H$d_end","thin");
		$EXCELBuilder->setHorizontal(0, "A1:H1", "center");
		$EXCELBuilder->setHorizontal(0, "A$d_start:H$d_end", "center");
		$EXCELBuilder->setFontSize(0, "A1:H1", 20);
		$EXCELBuilder->setFontSize(0, "A2:H$i", 14);
		
		$EXCELBuilder->setColumnWidth(0, A, 15);
		$EXCELBuilder->setColumnWidth(0, B, 30);
		$EXCELBuilder->setColumnWidth(0, C, 30);
		$EXCELBuilder->setColumnWidth(0, D, 40);
		$EXCELBuilder->setColumnWidth(0, E, 15);
		$EXCELBuilder->setColumnWidth(0, F, 15);
		$EXCELBuilder->setColumnWidth(0, G, 15);
		$EXCELBuilder->setColumnWidth(0, H, 30);
		

		$EXCELBuilder->FileOutput(0,"商品总库");
	}
	
    public function delAction(){
    	if($this->_depot_id){
    		$aReturn=array("res"=>0,"info"=>"非法操作");
			echo json_encode($aReturn); 
    		return;
		}
		$goods_id=I("post.gid");
    	$res=D("GoodsInfo")->deleteGoods($goods_id);
		if($res['status']){
            $aReturn=array("res"=>1,"info"=>"删除成功");   
		}
		else{
			$aReturn=array("res"=>0,"info"=>"删除失败, ".$res['msg']);
		}
		echo json_encode($aReturn);
    }
	
	
	public function addAction(){
		$brandRes=M("goods_brand")->select();
		$this->assign("brandRes",$brandRes);
		$this->assign("classRes",getCatTree());
		$this->display();
	}
	
	
	public function addexAction(){

        if(!empty($_FILES['main_pic'][name])) {

            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 3145728;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/Uploads/goods/'; // 设置附件上传根目录
            $upload->savePath = ''; // 设置附件上传（子）目录
            // 上传文件
            $info = $upload->upload();

            $main_img = $info['main_pic']['savepath'] . $info['main_pic']['savename'];

            $data["good"]["main_img"]=$main_img;
        }


		$goods_name=I("post.goods_name");
		$goods_spec=I("post.goods_spec");
		$goods_code=I("post.goods_code");
		$goods_brand=I("post.goods_brand");
		$goods_class=I("post.goods_class");
        $goods_desc = I("post.editorValue");
		
		$goods_convert_s=I("post.goods_convert_s");
		$goods_convert_m=I("post.goods_convert_m");
		$goods_convert_b=I("post.goods_convert_b");
		$goods_unit_s=I("post.goods_unit_s");
		$goods_unit_m=I("post.goods_unit_m");
		$goods_unit_b=I("post.goods_unit_b");
		
		
		$data["product"][0]["goods_name"]=$goods_name;

		$data["product"][0]["goods_spec"]=$goods_spec;

		$data["product"][0]["goods_unit"]=$goods_unit_s;

		$data["product"][0]["goods_unit_type"]=1;
		


        if (!empty($goods_unit_b)) {
            $data["product"][2]["goods_name"]=$goods_name;
            $data["product"][2]["goods_spec"]=$goods_spec;
            $data["product"][2]["goods_unit"]=$goods_unit_b;
            $data["product"][2]["goods_unit_type"]=3;
            if(empty($goods_unit_m)){
            	alertToUrl('请添加中单位','index');
            	return;
            }
        }
        if (!empty($goods_unit_m)){
            $data["product"][1]["goods_name"]=$goods_name;
            $data["product"][1]["goods_spec"]=$goods_spec;
            $data["product"][1]["goods_unit"]=$goods_unit_m;
            $data["product"][1]["goods_unit_type"]=2;
        }
		
		
		
		$data["good"]["goods_code"]=$goods_code;
		$data["good"]["goods_name"]=$goods_name;
		$data["good"]["goods_spec"]=$goods_spec;
		$data["good"]["brand_id"]=$goods_brand;
		$data["good"]["class_id"]=$goods_class;
		$data["good"]["goods_desc"]=$goods_desc;
		
		if($main_img){
			$data["good"]["main_img"]=$main_img;
		}
		

		$data["good"]["goods_convert_s"]=$goods_convert_s;
		
        if (empty($goods_convert_b) || empty($goods_unit_b)) {
            $goods_convert_b = 1;
        }
        if (empty($goods_convert_m) || empty($goods_unit_m)) {
            $goods_convert_m = 1;
        }
        
        $data["good"]["goods_convert_m"]=$goods_convert_m;
		$data["good"]["goods_convert_b"]=$goods_convert_b;
		$depot_id=session("depot_id");
		
		if($depot_id){
			$data["good"]["is_close"]=1;
		}else{
			$data["good"]["is_close"]=0;
		}
		
		$res=D("GoodsInfo")->addGoods($data);
		if($res){

            alertToUrl('添加成功','index');
            //$aReturn=array("res"=>1,"info"=>"添加成功");
		}else{
            alertToUrl('添加失败','index');
			//$aReturn=array("res"=>0,"info"=>"添加失败");
		}
		//echo json_encode($aReturn);
		
	}

    public function areaAction(){
    	$goods_id=I("get.goods_id");
		$where["goods_id"]=$goods_id;
    	$res=M("goods_info")->where($where)->find();
		$depot_id=session("depot_id");
		
		unset($where);
		$where["goods_id"]=$goods_id;
		$area_id=M("depot_area_goods")->where($where)->getField("area_id");
		if($area_id){
			$this->assign("area_id",$area_id);
		}else{
			$this->assign("area_id",0);
		}
		unset($where);
		
		$where["depot_id"]=$depot_id;
		
		
		$arealist=M("depot_area")->field("area_id,area_name")->where($where)->select();
		$this->assign("arealist",$arealist);
		
		$this->assign("res",$res);
    	$this->display();
    }
	
	public function areaExAction(){
		$goods_id=I("post.goods_id",2);
		$area_id=I("post.area_id",3);
		
		if($area_id==0){
			$where1["goods_id"]=$goods_id;
			M("depot_area_goods")->where($where1)->delete();
			echo json_encode(array("res"=>1,"msg"=>"设置成功"));
			return;
		}
		
		
		$data["goods_id"]=$goods_id;
		$data["area_id"]=$area_id;
		$where["goods_id"]=$goods_id;
		
		$res=M("depot_area_goods")->where($where)->count();
		if($res){
			$where["area_id"]=$area_id;
			$res1=M("depot_area_goods")->where($where)->count();
			if($res1){
				echo json_encode(array("res"=>0,"msg"=>"该商品设置子在该仓库区域"));
			}else{
				unset($data["goods_id"]);
				unset($where["area_id"]);
				$res2=M("depot_area_goods")->where($where)->save($data);
				if($res2){
					echo json_encode(array("res"=>1,"msg"=>"设置成功"));
				}else{
					echo json_encode(array("res"=>1,"msg"=>"设置失败"));
				}
			}
			
			
		}else{
			$res1=M("depot_area_goods")->add($data);
			if($res1){
				echo json_encode(array("res"=>1,"msg"=>"设置成功"));
			}else{
				echo json_encode(array("res"=>1,"msg"=>"设置失败"));
			}
			
		}
		
		
		
	}

	public function barcodeAction() {
	    $code = I('code',"");
        $id = I('id',0);

        if ($code == "") {
            $this->ajaxReturn(array("status"=>false, "msg"=>"请输入正确的条码信息"));
            return;
        }

        if($id > 0){
            $where['goods_id'] = $id;
        }
        $where['goods_code'] = $code;
        // 检测条码是否存在
        $data = M('goods_info')->where($where)->find();

        if($data){
            if($data['goods_id'] != $id){
                $this->ajaxReturn(array("status"=>false, "msg"=>"该商品条码已经存在"));
                return;
            }
        }
        else{

        }


        $codeimg = "Public/Uploads/barcode/".$code.".png";
        $codeimgl="Uploads/barcode/".$code.".png";

        if(!file_exists(__ROOT__ . $codeimg)){
            $barcode = new BarCode128($code);
            $barcode->createBarCode("png", $codeimg);
        }


        $this->ajaxReturn(array("status"=>true, "msg"=>"生成成功", "path"=>$codeimgl));

    }
	
	
	public function editexAction(){
		$id=I("post.goods_id");
		
		$where["goods_id"]=$id;
		
		if($this->_depot_id){
			$res=M("goods_info")->where($where)->find();
			if($res["is_close"]==0){

//				$aReturn=array("res"=>0,"info"=>"非法操作");
//				echo json_encode($aReturn);
//				return;
			}
		}

        if(!empty($_FILES['main_pic'][name])) {

            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 3145728;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/Uploads/goods/'; // 设置附件上传根目录
            $upload->savePath = ''; // 设置附件上传（子）目录
            // 上传文件
            $info = $upload->upload();

            $main_img = $info['main_pic']['savepath'] . $info['main_pic']['savename'];

            $data["good"]["main_img"]=$main_img;
        }
		
		$goods_id=I("post.goods_id");		
		$goods_name=I("post.goods_name");
		$goods_spec=I("post.goods_spec");
		$goods_code=I("post.goods_code");
		$goods_brand=I("post.goods_brand");
		$goods_class=I("post.goods_class");
        $goods_desc = I("post.editorValue");
        $goods_desc = stripslashes($_POST['editorValue']); //  $_POST['editorValue'];

		$goods_convert_s=I("post.goods_convert_s");
		$goods_convert_m=I("post.goods_convert_m");
		$goods_convert_b=I("post.goods_convert_b");
		$goods_unit_s=I("post.goods_unit_s");
		$goods_unit_m=I("post.goods_unit_m");
		$goods_unit_b=I("post.goods_unit_b");
		
		
//		$data["product"][0]["goods_name"]=$goods_name;
//		$data["product"][1]["goods_name"]=$goods_name;
//		$data["product"][2]["goods_name"]=$goods_name;
//		
//		$data["product"][0]["goods_spec"]=$goods_spec;
//		$data["product"][1]["goods_spec"]=$goods_spec;
//		$data["product"][2]["goods_spec"]=$goods_spec;
		$data["product"]['goods_spec']=$goods_spec;
		$data["product"]['goods_name']=$goods_name;
		$data["product"]["goods_unit_s"]=$goods_unit_s;
		$data["product"]["goods_unit_m"]=$goods_unit_m;
		$data["product"]["goods_unit_b"]=$goods_unit_b;
		
//		$data["product"][0]["goods_unit_type"]=1;
//		$data["product"][1]["goods_unit_type"]=2;
//		$data["product"][2]["goods_unit_type"]=3;
		
		
		$data["good"]["goods_id"]=$goods_id;
		$data["good"]["goods_code"]=$goods_code;
		$data["good"]["goods_name"]=$goods_name;
		$data["good"]["goods_spec"]=$goods_spec;
		$data["good"]["brand_id"]=$goods_brand;
		$data["good"]["class_id"]=$goods_class;
        $data["good"]["goods_desc"]=$goods_desc;

		$data["good"]["goods_convert_s"]=$goods_convert_s;
		
        
        if (empty($goods_convert_b) || empty($goods_unit_b)) {
            $goods_convert_b = 1;
        }
        if (empty($goods_convert_m) || empty($goods_unit_m)) {
            $goods_convert_m = 1;
        }
        
        $data["good"]["goods_convert_m"]=$goods_convert_m;
		$data["good"]["goods_convert_b"]=$goods_convert_b;
		//$data["good"]["is_close"]=0;


        //dump($data['good']);die;
		$res=D("GoodsInfo")->editGoods($data);
        if($res){

            alertToUrl('修改成功','index');
            //$aReturn=array("res"=>1,"info"=>"添加成功");
        }else{
            alertToUrl('修改失败','index');
            //$aReturn=array("res"=>0,"info"=>"添加失败");
        }
		
		
	}
	
	
	public function editAction(){
			
			
		$brandRes=M("goods_brand")->select();
		$classRes=M("goods_class")->select();
		
		
	    $goods_id= I("get.gid");
		
		$where["goods_id"]=$goods_id;
		$goods=M("goods_info")->where($where)->find();
		$product=M("goods_product")->where($where)->select();
		
		foreach($product as $p){
			
			if($p["goods_unit_type"]==1){
				$goods["goods_unit_s"]=$p["goods_unit"];
			}elseif($p["goods_unit_type"]==2){
				$goods["goods_unit_m"]=$p["goods_unit"];
			}elseif($p["goods_unit_type"]==3){
				$goods["goods_unit_b"]=$p["goods_unit"];
			}
		}
			
		$this->assign("brandRes",$brandRes);
		$this->assign("classRes",getCatTree());
        
		$this->assign("res",$goods);
		
		$this->display();
	}
	
	//根据类别、品牌、商品名称查询数据，返回json
    public function selGoodsAction(){

    	/*$iBrandId=I("get.brand",0);

    	$iClassId=I("get.class_id",0);

    	$sGoods=I("get.goods","");

        $org_id = I("get.org_id");

        $depot_id = I("get.depot_id");*/

        $iBrandId=I("post.brand",0);

    	$iClassId=I("post.class_id",0);

    	$sGoods=I("post.goods","");

        $org_id = I("post.org_id",1);

        $depot_id = I("post.depot_id",0);

    	$mGoods=new \Common\Model\GoodsInfoModel();

    	$mConvert=M("goods_product");

    	$aGoods=$mGoods->selGoods($iBrandId,$iClassId,$sGoods,0, $depot_id,$org_id);
        
    	for($i=0;$i<count($aGoods);$i++) {
    		$convertList=$mConvert->field("cv_id,goods_unit,goods_unit_type")->where("goods_id=".$aGoods[$i]["goods_id"])->select();
			
    	    $aGoods[$i]["convert_data"]=$convertList;
    	}

    	
        
        if($aGoods){
            $this->aReturn=array("res"=>1,"data"=>$aGoods,"count"=>count($aGoods));
        } else {
            $this->aReturn=array("res"=>0);
        }

    	echo $this->ajaxReturn($this->aReturn,"json");
    }
    
	public function setPassAction(){
		if($this->_depot_id){
    		//临时放开 仓库管理的权限
    		//$aReturn=array("res"=>0,"info"=>"非法操作");
			//echo json_encode($aReturn); 
    		//return;
		}
		
		$goods_id=I("post.goods_id",1);
		$data["is_close"]=0;
		$where["goods_id"]=$goods_id;
		$res=M("goods_info")->where($where)->save($data);
		if($res){
			$data1['msg']="审核成功";
			$data1['res']=1;
			echo json_encode($data1);
		}else{
			$data1['msg']="审核失败";
			$data1['res']=0;
			echo json_encode($data1);
		}
		
	}
	public function setOffPassAction(){
		
		if($this->_depot_id){
    		//临时放开 仓库管理的权限
    		//$aReturn=array("res"=>0,"info"=>"非法操作");
			//echo json_encode($aReturn); 
    		//return;
		}
		
		$goods_id=I("post.goods_id",1);
		$data["is_close"]=1;
		$where["goods_id"]=$goods_id;
		$res=M("goods_info")->where($where)->save($data);
		if($res){
			$data1['msg']="设置未审核成功";
			$data1['res']=1;
			echo json_encode($data1);
		}else{
			$data1['msg']="设置未审核失败";
			$data1['res']=0;
			echo json_encode($data1);
		}
		
	}
    
	public function selectGoodsByCodeAction(){
		$goods_code=I("post.goods_code","000301");
		$org_parent_id=I("post.org_id",4);
		$where["goods_code"]=$goods_code;
		$where["zdb_depot_stock.org_parent_id"]=$org_parent_id;
		$res=M("goods_info")
		->field("zdb_goods_info.*,zdb_depot_area.area_name as goods_area")
		->join("zdb_depot_area_goods on zdb_depot_area_goods.goods_id=zdb_goods_info.goods_id")
		->join("zdb_depot_stock on zdb_depot_stock.goods_id=zdb_depot_area_goods.goods_id")
		->join("zdb_depot_area on zdb_depot_area.area_id=zdb_depot_area_goods.area_id")
		->where($where)->find();
		$Goods=M("goods_product")->where("goods_id = " . intval($res['goods_id']))->order("goods_unit_type")->select();
		
		if($res){
			$data["code"]=1;
			$data["res"]=$res;
			$data["goods"]=$Goods;
		}else{
			$data["code"]=0;
		}
		echo json_encode($data);
		
	}

	// 更换经销商
    public function setorgAction() {
        $goods_id = I("gid");

        $depot_id = $this->_depot_id;

        if (IS_GET) {
            // 获取所有经销商

            if ($depot_id > 0) {
                // 如果为区域管理  获取当前区域经销商
                $where["do.repertory_id"] = $depot_id;
                $where["oi.is_close"] = 0;
                $orgs = M("org_info")->alias("oi")
                    ->join("left join __DEPOT_ORG__ as do on oi.org_id=do.org_parent_id")
                    ->where($where)
                    ->select();
                $this->assign("orgs", $orgs);

            } else {
                // 否则根据选择仓库获取经销商
                $depots = M("depot_info")->where("repertory_close=0")->select();

                $this->assign("depots", $depots);
            }

            // 检查是否已经设置经销商
            $has = M("depot_stock")->where("goods_id=$goods_id AND depot_id=$depot_id")->count();

            $whereG["gi.goods_id"] = $goods_id;
            if ($has > 0) {
                $whereG["ds.depot_id"] = $depot_id;
            }

            $goods = M("goods_info")->alias("gi")
                ->join("left join __DEPOT_STOCK__ as ds on ds.goods_id=gi.goods_id")
                ->field("gi.*, ds.org_parent_id, ds.depot_id")
                ->where($whereG)
                ->find();

            //echo M("goods_info")->getLastSql();die();

            $this->assign("goods", $goods);

            $this->display();
        }

        if (IS_POST) {
            if ($depot_id <= 0) {
                $depot_id = I("post.depot_id");
            }

            $org_id = I("post.org_id");

            $model = M("depot_stock");

            $where["depot_id"] = $depot_id;

            $where["goods_id"] = $goods_id;

            $has = $model->where($where)->count();

            if ($has > 0) {
                $res = $model->where($where)->setField("org_parent_id", $org_id);

            } else {
                $data["depot_id"] = $depot_id;
                $data["goods_id"] = $goods_id;
                $data["org_parent_id"] = $org_id;
                $res = $model->where($where)->add($data);
            }

            if (!empty($org_id)) {
                $this->addGoodsPrice($goods_id,$org_id);
            }

            if ($res) {
                // 删除之前的仓库预警
                M("depot_warning")->where($where)->delete();
                //echo M("depot_stock")->getLastSql();die();
                alertToUrl('修改成功','index');
            } else {
                //echo M("depot_stock")->getLastSql();die();
                alertToUrl('修改失败','index');
            }

        }

    }

    private function addGoodsPrice($goods_id,$org_parent_id)
    {
        $where["goods_id"] = $goods_id;
        $product = M("goods_product")->where($where)->select();


        foreach ($product as $k => $v) {
            $where['cv_id'] = $v['cv_id'];
            $res = M("org_goods_convert")->where($where)->where("org_parent_id=$org_parent_id")->count();

            if (!$res) {
                $row = $v;
                $row['org_parent_id'] = $org_parent_id;

                $product_into[] = $row;
            }
        }
        M("org_goods_convert")->addAll($product_into);
    }

    // 根据仓库ID获取仓库下经销商
    public function getOrgByDepotAction() {
        $depot_id = I("depot_id");

        $where["do.repertory_id"] = $depot_id;

        $where["oi.is_close"] = 0;

        $orgs = M("org_info")->alias("oi")
            ->join("left join __DEPOT_ORG__ as do on oi.org_id=do.org_parent_id")
            ->where($where)
            ->select();

        $this->ajaxReturn($orgs, "JSON");
    }
	
	

}

/*************************** end ************************************/