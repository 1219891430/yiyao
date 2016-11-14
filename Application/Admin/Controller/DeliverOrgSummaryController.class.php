<?php
namespace Admin\Controller;
use Think\Controller;
class DeliverOrgSummaryController extends BaseController{

    public function __construct()
    {
        parent::__construct();
    }


    public function indexAction(){
        //内勤人员标识
        $depotID = $this->_depot_id;

		$start_time=I("get.start_time");
		$end_time=I("get.end_time");
		
		
		$start=strtotime($start_time);
		$end=strtotime($end_time);
		$end=$end+24*3600;
		$this->assign("start",$start_time);
		$this->assign("end",$end_time);
		$org_parent_id=I("get.org_parent_id",0);
		if($depotID){
			$repertory_id=$depotID;
		}else{
			$repertory_id=I("get.repertory_id",0);
		}
		
		
		$org_name=M("org_info")->where("org_id=$org_parent_id")->getField("org_name");
		$this->assign("org_name",$org_name);
		$this->assign("org_parent_id",$org_parent_id);
		$this->assign("repertory_id",$repertory_id);
		
		if($org_parent_id){
			$where["zdb_org_info.org_id"]=$org_parent_id;
		}
		if($repertory_id){
			$where["zdb_depot_org.repertory_id"]=$repertory_id;
		}
		
		if($start&&$end){
			$whereXS["create_time"]=array(array("gt",$start),array("lt",$end));
		}else{
			$whereXS["create_time"]=array(array("gt",0),array("lt",0));
		}
		if($org_parent_id){
			$whereXS["zdb_car_orders.org_parent_id"]=$org_parent_id;
		}
		if($repertory_id){
			$whereXS["zdb_car_orders.repertory_id"]=$repertory_id;
		}
		$GoodsCV=$this->getGoodsCv();
		//销售 // 金额
		$listXS=M("car_orders")->
		field("goods_id,org_parent_id,sum(singleprice*number) as salemoney")
		->join("zdb_car_orders_goods on zdb_car_orders_goods.order_id=zdb_car_orders.order_id")
		->where($whereXS)
		->group("zdb_car_orders.org_parent_id,goods_id")
		->select();
		$newlistXS=array();
		foreach($listXS as $v){
			$newlistXS[$v["goods_id"]]=$v['salemoney'];
		}
		//销售数量
		$listCount=M("car_orders")
		->field("goods_id,org_parent_id,sum(number) as number,cv_id")
		->join("zdb_car_orders_goods on zdb_car_orders_goods.order_id=zdb_car_orders.order_id")
		->where($whereXS)->group("zdb_car_orders.org_parent_id,goods_id,cv_id")->select();
		
		$goodNums=array();
		foreach($listCount as $v){
			$goodNums[$v["goods_id"]]+=$this->getSamllUnit($v["goods_id"], $v["cv_id"], $v["number"], $GoodsCV);
			
		}
        
		$XSStrings=array();
		$i=0;
		foreach($goodNums as $goods_id =>$goods_num){
			$cv=$this->getCV($goods_id, $GoodsCV);
			$XSStrings[$goods_id]["numberString"]=$this->getNumberString($goods_num, $cv);
			$XSStrings[$goods_id]['number']=$goods_num;
			$i++;
		}
		
		
		
		//退货
		
		
		if($start&&$end){
			$whereTH["create_time"]=array(array("gt",$start),array("lt",$end));
		}else{
			$whereTH["create_time"]=array(array("gt",0),array("lt",0));
		}
		if($org_parent_id){
			$whereTH["zdb_car_return.org_parent_id"]=$org_parent_id;
		}
		if($repertory_id){
			$whereTH["zdb_car_return.repertory_id"]=$repertory_id;
		}
		
		$listTH=M("car_return")
		->field("goods_id,org_parent_id,sum(goods_money*goods_num) as salemoney")
		->join("zdb_car_return_goods on zdb_car_return_goods.return_id=zdb_car_return.return_id")
		->where($whereTH)
		->group("zdb_car_return.org_parent_id,goods_id")
		->select();
		$newlistTH=array();
		foreach($listTH as $v){
			$newlistTH[$v["goods_id"]]=$v['salemoney'];
		}
		
		
		
		$listCount=M("car_return")
		->field("goods_id,cv_id,org_parent_id,sum(goods_num) as number")
		->join("zdb_car_return_goods on zdb_car_return_goods.return_id=zdb_car_return.return_id")
		->where($whereTH)
		->group("zdb_car_return.org_parent_id,goods_id,cv_id")
		->select();
		$goodNums=array();
		foreach($listCount as $v){
			$goodNums[$v["goods_id"]]+=$this->getSamllUnit($v["goods_id"], $v["cv_id"], $v["number"], $GoodsCV);	
		}
		$THStrings=array();
		$i=0;
		foreach($goodNums as $goods_id =>$goods_num){
			
			$cv=$this->getCV($goods_id, $GoodsCV);
			$THStrings[$goods_id]["numberString"]=$this->getNumberString($goods_num, $cv);
			$THStrings[$goods_id]["number"]=$goods_num;
			$i++;
		}
		
		//调出货
		
		if($start&&$end){
			$whereTC["create_time"]=array(array("gt",$start),array("lt",$end));
		}else{
			$whereTC["create_time"]=array(array("gt",0),array("lt",0));
		}
		if($org_parent_id){
			$whereTC["zdb_car_change.org_parent_id"]=$org_parent_id;
		}
		if($repertory_id){
			$whereTC["zdb_car_change.repertory_id"]=$repertory_id;
		}
		
		$whereTC["zdb_car_change_goods.is_change_in"]=0;
		$listTC=M("car_change")
		->field("goods_id,org_parent_id,sum(singleprice*number) as salemoney")
		->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")
		->where($whereTC)
		->group("zdb_car_change.org_parent_id,goods_id")
		->select();
		$newlistTC=array();
		foreach($listTC as $v){
			$newlistTC[$v["goods_id"]]=$v['salemoney'];
		}
		
		
		
		$listCount=M("car_change")
		->field("goods_id,cv_id,org_parent_id,sum(number) as number")
		->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")
		->where($whereTC)
		->group("zdb_car_change.org_parent_id,goods_id")
		->select();
		
		$goodNums=array();
		foreach($listCount as $v){
			$goodNums[$v["goods_id"]]+=$this->getSamllUnit($v["goods_id"], $v["cv_id"], $v["number"], $GoodsCV);
			
		}
		$TCStrings=array();
		$i=0;
		foreach($goodNums as $goods_id =>$goods_num){
			
			$cv=$this->getCV($goods_id, $GoodsCV);
			$TCStrings[$goods_id]["numberString"]=$this->getNumberString($goods_num, $cv);
		    $TCStrings[$goods_id]["number"]=$goods_num;
			$i++;
		}
		
		//换回货
		
		if($start&&$end){
			$whereHH["create_time"]=array(array("gt",$start),array("lt",$end));
		}else{
			$whereHH["create_time"]=array(array("gt",0),array("lt",0));
		}
		if($org_parent_id){
			$whereHH["zdb_car_change.org_parent_id"]=$org_parent_id;
		}
		if($repertory_id){
			$whereHH["zdb_car_change.repertory_id"]=$repertory_id;
		}
		
		$whereHH["zdb_car_change_goods.is_change_in"]=1;
		$listHH=M("car_change")
		->field("goods_id,cv_id,org_parent_id,sum(singleprice*number) as salemoney")
		->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")
		->where($whereHH)
		->group("zdb_car_change.org_parent_id,goods_id")
		->select();
		
		$newlistHH=array();
		foreach($listHH as $v){
			$newlistHH[$v["goods_id"]]=$v['salemoney'];
		}
		
		$listCount=M("car_change")
		->field("goods_id,cv_id,org_parent_id,sum(number) as number")
		->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")
		->where($whereHH)
		->group("zdb_car_change.org_parent_id,goods_id")
		->select();
		
		$goodNums=array();
		foreach($listCount as $v){
			$goodNums[$v["goods_id"]]+=$this->getSamllUnit($v["goods_id"], $v["cv_id"], $v["number"], $GoodsCV);
			
		}
		$HHStrings=array();
		$i=0;
		foreach($goodNums as $goods_id =>$goods_num){
			$cv=$this->getCV($goods_id, $GoodsCV);
			$HHStrings[$goods_id]["numberString"]=$this->getNumberString($goods_num, $cv);
			$HHStrings[$goods_id]["number"]=$goods_num;
			$i++;
		}
		
		$goodlists=M("goods_info")->field("goods_id,goods_name,goods_spec")->select();
		$data=array();
		$zongji=0;
		foreach($goodlists as $k=>$v){
			//销售
			if($newlistXS[$v["goods_id"]]){
				$goodlists[$k]["xiaoshoumoney"]=$newlistXS[$v["goods_id"]];
			}else{
				$goodlists[$k]["xiaoshoumoney"]="0.00";
			}
			if($XSStrings[$v["goods_id"]]){
				$goodlists[$k]["xiaoshounumberString"]=$XSStrings[$v["goods_id"]]["numberString"];
				$goodlists[$k]["xiaoshounumber"]=$XSStrings[$v["goods_id"]]["number"];
			}else{
				$goodlists[$k]["xiaoshounumberString"]=0;
				$goodlists[$k]["xiaoshounumber"]=0;
			}
			//退货
			if($newlistTH[$v["goods_id"]]){
				$goodlists[$k]["tuihuomoney"]=$newlistTH[$v["goods_id"]];
			}else{
				$goodlists[$k]["tuihuomoney"]="0.00";
			}
			if($THStrings[$v["goods_id"]]){
				$goodlists[$k]["tuihuonumberString"]=$THStrings[$v["goods_id"]]["numberString"];
				$goodlists[$k]["tuihuonumber"]=$THStrings[$v["goods_id"]]["number"];
			}else{
				$goodlists[$k]["tuihuonumberString"]=0;
				$goodlists[$k]["tuihuonumber"]=0;
			}
			
			//调出货
			if($newlistTC[$v["goods_id"]]){
				$goodlists[$k]["tiaochumoney"]=$newlistTC[$v["goods_id"]];
			}else{
				$goodlists[$k]["tiaochumoney"]="0.00";
			}
			if($TCStrings[$v["goods_id"]]){
				$goodlists[$k]["tiaochunumberString"]=$TCStrings[$v["goods_id"]]["numberString"];
				$goodlists[$k]["tiaochunumber"]=$TCStrings[$v["goods_id"]]["number"];
			}else{
				$goodlists[$k]["tiaochunumberString"]=0;
				$goodlists[$k]["tiaochunumber"]=0;
			}
			
			//换回活
			if($newlistHH[$v["goods_id"]]){
				$goodlists[$k]["huanhuimoney"]=$newlistHH[$v["goods_id"]];
			}else{
				$goodlists[$k]["huanhuimoney"]="0.00";
			}
			if($HHStrings[$v["goods_id"]]){
				$goodlists[$k]["huanhuinumberString"]=$HHStrings[$v["goods_id"]]["numberString"];
				$goodlists[$k]["huanhuinumber"]=$HHStrings[$v["goods_id"]]["number"];
			}else{
				$goodlists[$k]["huanhuinumberString"]=0;
				$goodlists[$k]["huanhuinumber"]=0;
			}
			
			$chuhuo=$goodlists[$k]["xiaoshounumber"]-$goodlists[$k]["tuihuonumber"]+$goodlists[$k]["tiaochunumber"]-$goodlists[$k]["huanhuinumber"];
			$cv=$this->getCV($v["goods_id"], $GoodsCV);
			$goodlists[$k]["chuhuo"]=$this->getNumberString($chuhuo, $cv);
			$goodlists[$k]["xiaoji"]=$goodlists[$k]["xiaoshoumoney"]-$goodlists[$k]["tuihuomoney"]+$goodlists[$k]["tiaochumoney"]-$goodlists[$k]["huanhuimoney"];
			if($goodlists[$k]["xiaoshounumber"]||$goodlists[$k]["tuihuonumber"]||$goodlists[$k]["tiaochunumber"]||$goodlists[$k]["huanhuinumber"]){
				
				$zongji+=$goodlists[$k]["xiaoji"];
				$data[]=$goodlists[$k];
			}
			
		}
        $this->assign("data",$data);
		$this->assign("zongji",$zongji);
		//
		$orglist = queryDealer($depotID);// M("org_info")->field("org_id,org_name")->select();
		$repertoryList = queryDepot($depotID); // M("depot_info")->field("repertory_id,repertory_name")->select();
		$this->assign("repertoryList",$repertoryList);
		$this->assign("orglist",$orglist);
		$this->display();
	}
	
	
	private function getGoodsCv(){
			$list=M("goods_info")->field("zdb_goods_info.goods_id,cv_id,goods_unit,goods_convert_s,goods_convert_m,goods_convert_b,goods_unit_type")->join("zdb_goods_product on zdb_goods_product.goods_id=zdb_goods_info.goods_id")->select();
			$GoodsCV=array();
			foreach($list as $k=>$v){
				$GoodsCV[$v['goods_id']][$k]['cv_id']=$v['cv_id'];
    			$GoodsCV[$v['goods_id']][$k]['goods_unit']=$v['goods_unit'];
    			$GoodsCV[$v['goods_id']][$k]['goods_convert_s']=$v['goods_convert_s'];
				$GoodsCV[$v['goods_id']][$k]['goods_unit_type']=$v['goods_unit_type'];
				$GoodsCV[$v['goods_id']][$k]['goods_convert_m']=$v['goods_convert_m'];
				$GoodsCV[$v['goods_id']][$k]['goods_convert_b']=$v['goods_convert_b'];
			}
		
			return $GoodsCV;
	}
	
	private function getSamllUnit($goods_id,$cv_id,$number,$GoodsCV){

		$cv=$this->getCV($goods_id, $GoodsCV);
       
		
		
		if($cv_id==$cv['small_cv_id']){
			$number=$number;
		}elseif($cv_id==$cv['mid_cv_id']){
			$number=$number*$cv['mid_cv'];
		}elseif($cv_id==$cv['big_cv_id']){
			$number=$number*$cv['mid_cv']*$cv['big_cv'];
		}
		return $number;
	}
    /*
	 * 根据数量和系数转换成默认单位
	 */
    private function getNumberString($number,$cv){

		
    	$numberString="";

    	if($cv["big_cv_id"]){
    		if($number>0){
				$small=$number%$cv['mid_cv'];
		   	 	$mid=floor(($number%($cv['big_cv']*$cv['mid_cv']))/$cv['mid_cv']);
				$big=floor($number/($cv['big_cv']*$cv['mid_cv']));
			}else{
				$small=$number%$cv['mid_cv'];
		    	$mid=ceil(($number%($cv['big_cv']*$cv['mid_cv']))/$cv['mid_cv']);
				$big=ceil($number/($cv['big_cv']*$cv['mid_cv']));
			}
    	}elseif($cv["mid_cv_id"]){
    		if($number>0){
    			$small=$number%$cv['mid_cv'];
			    $mid=floor($number/$cv['mid_cv']);
			}else{
				$small=$number%$cv['mid_cv'];
			    $mid=ceil($number/$cv['mid_cv']);
			}
    		
    	}else{
    		$small=$number;
    	}

        
		if($big){
			$numberString.=$big.$cv['big_unit'];
		}
		if($mid){
			$numberString.=$mid.$cv['mid_unit'];
		}
		if($small){
			$numberString.=$small.$cv['small_unit'];
		}
		if(!$numberString){
			$numberString=0;
		}
		return $numberString;
    }
    //得到Goods的转换系数
    private function getCV($goods_id,$GoodsCV){
    	$cv=array();
		foreach($GoodsCV[$goods_id] as $k=>$v){
			
			if($v["goods_unit_type"]==1){
				$cv['small_cv_id']=$v['cv_id'];
				$cv['small_cv']=$v['goods_convert_s'];
				$cv['small_unit']=$v['goods_unit'];
				//$cv['small_is_close']=$v['is_close'];
				$cv['small_is_close']=0;
			}elseif($v["goods_unit_type"]==2){
				$cv['mid_cv_id']=$v['cv_id'];
				$cv['mid_cv']=$v['goods_convert_m'];
				$cv['mid_unit']=$v['goods_unit'];
				//$cv['mid_is_close']=$v['is_close'];
				$cv['mid_is_close']=0;
			}elseif($v["goods_unit_type"]==3){
				$cv['big_cv_id']=$v['cv_id'];
				$cv['big_cv']=$v['goods_convert_b'];
				$cv['big_unit']=$v['goods_unit'];
				//$cv['big_is_close']=$v['is_close'];
				$cv['big_is_close']=0;
				
			}
		}
		return $cv;
    }
}
