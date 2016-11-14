<?php

/*******************************************************************
 ** 文件名称: DeliverSummaryController.class.php
 ** 功能描述: 系统后台配送汇总控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DeliverSummaryController extends BaseController {

	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
        //内勤人员标识
        $depotID = $this->_depot_id;

		$where["role_id"]=5;//角色是业务员
        if($depotID>0) {
            $where['depot_id'] = $depotID;
        }

		$staffList=M("admin_user")
		     ->field("admin_id as staff_id,true_name as staff_name")
		     ->where($where)->select();

		foreach($staffList as $k=>$vo){
			$res=M('car_duizhang')->field("cd_id,start_time,end_time")->where("staff_id=".$vo["staff_id"])->order("cd_id desc")->find();
			if($res){
				$staffList[$k]["lastCheck"]=$res["end_time"];
				$staffList[$k]["duizhang_id"]=$res["cd_id"];
				$staffList[$k]["start"]=strtotime($res["end_time"]);
			}else{
				$staffList[$k]["lastCheck"]="无对账";
				$staffList[$k]["duizhang_id"]=0;
				$staffList[$k]["start"]=0;
			}
			
		}
		$this->assign("staffList",$staffList);
		$this->display();
    }
	
	
	
	
	private function duiZhangHuiZongNew($staff_id,$start_date,$end_date){
    	
    	
		/*
		 *
		 * 数据生成
		 *
		 */
    	/*商品*/
    	
		//$where["zstb_goods_info.is_close"]=0;

		$goodData = M("goods_brand")
			->field("zdb_goods_brand.brand_id,zdb_goods_brand.brand_name,zdb_goods_info.goods_id,
			zdb_goods_info.goods_name,zdb_goods_info.goods_spec,zdb_goods_product.cv_id,zdb_goods_product.goods_unit,
			zdb_goods_info.goods_convert_s,zdb_goods_info.goods_convert_m,zdb_goods_info.goods_convert_b,
			zdb_goods_product.goods_unit_type
			")
			->join("zdb_goods_info on zdb_goods_info.brand_id=zdb_goods_brand.brand_id")
			->join("zdb_goods_product on zdb_goods_product.goods_id=zdb_goods_info.goods_id")
			
			->select();
    	
         
		/*
		 * 上次对账车存
		 */
		$SCDZList=M()->query("
        select goods_id,cxzh.cd_id,carport,carport_int from zdb_car_duizhang_goods as cxzh
		where EXISTS (
		select max(zdb_car_duizhang_goods.cd_id) from zdb_car_duizhang_goods 
		inner join zdb_car_duizhang  on  zdb_car_duizhang.cd_id=zdb_car_duizhang_goods.cd_id where staff_id=$staff_id 
		and cxzh.cd_id=zdb_car_duizhang_goods.cd_id
		group by goods_id 
		)");
		
       
		/*
		 * 今日出库, 按照收货时间
		 */
		$whereB["zdb_car_apply.staff_id"]=$staff_id;
		$whereB["apply_status"]=3;
		$whereB["zdb_car_apply.accept_time"]=array(array("gt",$start_date),array("lt",$end_date));
		$chukuList=M("car_apply")
			->field("zdb_car_apply_goods.goods_id,cv_id,goods_unit,sum(apply_num) as number")
			->join("zdb_car_apply_goods on zdb_car_apply_goods.apply_id=zdb_car_apply.apply_id")
			->where($whereB)
			->group("cv_id")
			->order("cv_id desc")
			->select();
       
		
		
		/*
		 * 今日退库
		 */
		$whereTK["staff_id"]=$staff_id;
		$whereTK["return_status"]=2;
		$whereTK["zdb_car_return_stock.check_time"] = array(
			array("gt", $start_date),
			array("lt", $end_date)
		);
		$tuikulist = M("car_return_stock")
			->field("goods_unit,sum(goods_num) as number,cv_id,zdb_car_return_stock_goods.goods_id")
			->join("zdb_car_return_stock_goods on zdb_car_return_stock_goods.return_id=zdb_car_return_stock.return_id")
			->where($whereTK)
			->group("cv_id")
			->order("cv_id desc")
			->select();
		
		
		/*
		 * 销售数量
		 */	
		$whereC["staff_id"]=$staff_id;
		$whereC["cuxiao"]=0;
		//$whereC["addtime"]=array(array("gt",$start_date),array("lt",$end_date));
		$whereC['zdb_car_orders.create_time']=array(array("gt",$start_date),array("lt",$end_date));		
		$xiaoshouList=M("car_orders")
			->field("goods_id,cv_id,unit_name as goods_unit,sum(number) as number")
			->join("zdb_car_orders_goods on zdb_car_orders_goods.order_id=zdb_car_orders.order_id")
			->where($whereC)
			->group("cv_id")
			->order("cv_id desc")
			->select();
		
		//促销品出货量
				
		$whereD["staff_id"]=$staff_id;
		$whereD["create_time"]=array(array("gt",$start_date),array("lt",$end_date));
		
		$whereD["cuxiao"]=1;
		
		
		
		$cuxiaoList=M("car_orders")
			->field("goods_id,cv_id,unit_name as goods_unit,sum(number) as number")
			->join("zdb_car_orders_goods on zdb_car_orders_goods.order_id=zdb_car_orders.order_id")
			->where($whereD)
			->group("cv_id")
			->order("cv_id desc")
			->select();
		
		//调换货(调出)
		$whereTHH["staff_id"]=$staff_id;
		$whereTHH["create_time"]=array(array("gt",$start_date),array("lt",$end_date));
		$whereTHH["is_change_in"]=0;
		$TCHList=M("car_change")
			->field("goods_id,cv_id,unit_name as goods_unit,sum(number) as number")
			->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")
			->where($whereTHH)->group("zdb_car_change_goods.cv_id")
			->select();
			
		
		//调换货(换回)
		$whereTHH["staff_id"]=$staff_id;
		$whereTHH["create_time"]=array(array("gt",$start_date),array("lt",$end_date));
		$whereTHH["is_change_in"]=1;
		$HHHList=M("car_change")
			->field("goods_id,cv_id,unit_name as goods_unit,sum(number) as number")
			->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")
			->where($whereTHH)->group("zdb_car_change_goods.cv_id")
			->select();

		//退货数量
		$whereG["zdb_car_return.staff_id"]=$staff_id;
		$whereG["zdb_car_return.create_time"]=array(array("gt",$start_date),array("lt",$end_date));
		$tuihuoList=M("car_return")
			->field("goods_id,cv_id,goods_unit,sum(goods_num) as number")
			->join("zdb_car_return_goods on zdb_car_return_goods.return_id=zdb_car_return.return_id")
			->where($whereG)
			->group("cv_id")
			->order("cv_id desc")
			->select();
        
		//单价/数量
		$wheredj["staff_id"]=$staff_id;
		$wheredj["cuxiao"]=0;
		
		
		$wheredj["zdb_car_orders.create_time"]=array(array("gt",$start_date),array("lt",$end_date));
		$danjiaList=M("car_orders_goods")
			->join("zdb_car_orders on zdb_car_orders.order_id=zdb_car_orders_goods.order_id")
			->field("goods_id,cv_id,singleprice,sum(number) as number,unit_name")
			->where($wheredj)
			->group("singleprice,cv_id")
			->select();
    	
    	 // 数据序列化处理
    	$brandList=array(); //最终页面数据
    	$GoodsCV=array();//转换系数
    	foreach($goodData as $k=>$v) {
    		$GoodsCV[$v['goods_id']][$k]['cv_id']=$v['cv_id'];
    		$GoodsCV[$v['goods_id']][$k]['goods_unit']=$v['goods_unit'];
    		$GoodsCV[$v['goods_id']][$k]['goods_convert_s']=$v['goods_convert_s'];
			$GoodsCV[$v['goods_id']][$k]['goods_unit_type']=$v['goods_unit_type'];
			$GoodsCV[$v['goods_id']][$k]['goods_convert_m']=$v['goods_convert_m'];
			$GoodsCV[$v['goods_id']][$k]['goods_convert_b']=$v['goods_convert_b'];
			
			
			
    		$brandList[$v["brand_id"]]['brand_id']=$v['brand_id'];
    		$brandList[$v["brand_id"]]['brand_name']=$v['brand_name'];
    		$brandList[$v["brand_id"]]['data'][$v['goods_id']]['goods_id']=$v['goods_id'];
    		$brandList[$v["brand_id"]]['data'][$v['goods_id']]['goods_name']=$v['goods_name'];
			$brandList[$v["brand_id"]]['data'][$v['goods_id']]['goods_spec']=$v['goods_spec'];	
    	}
        //上次对账车存
		foreach($SCDZList as $k=>$v){
			foreach($brandList as $kk=>$vv){
				if($brandList[$kk]['data'][$v["goods_id"]]){
            		$brandList[$kk]['data'][$v["goods_id"]]['today_carport']=$v["carport"];
					$brandList[$kk]['data'][$v["goods_id"]]['today_carport_int']=$v["carport_int"];
                }
			}
		}
//		//出库数量
		$chukuListNew=array();
		foreach($chukuList as $k=>$v){
			$chukuListNew[$v['goods_id']][]=$v;
		}
		unset($chukuList);

		foreach($chukuListNew as $k=>$v){
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['CKNumber']=$small;
                }
			}
		}
//
//		// 未确认收货数量
//		/*
//		$wshListNew = array();
//		while (list($key,$value) = each($wshList)) {			
//			$wshListNew[$value['goods_id']][] = $value;
//		}
//		foreach ($wshListNew as $k => $v) {
//			$small=$this->getSamllUnit($k,$GoodsCV,$v);	
//			foreach($brandList as $kk=>$vv){
//			
//				if($brandList[$kk]['data'][$k]){
//					$brandList[$kk]['data'][$k]['WSNumber']=$small;
//				}
//			}			
//		}
//		*/
//
//      //退库数量
        $tuikulistNew=array();
		foreach($tuikulist as $k=>$v){
			$tuikulistNew[$v['goods_id']][]=$v;
		}
        unset($tuikulist);
        foreach($tuikulistNew as $k=>$v){
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['TKNumber']=$small;
                }
			}
		}

		//销售数量
		$xiaoshouListNew=array();
		foreach($xiaoshouList as $k=>$v){
			$xiaoshouListNew[$v['goods_id']][]=$v;
		}
        unset($xiaoshouList);
		
        foreach($xiaoshouListNew as $k=>$v){
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['XSNumber']=$small;
                }
			}
		}
		
		//促销品
		$cuxiaoListNew=array();
		foreach($cuxiaoList as $k=>$v){
			$cuxiaoListNew[$v['goods_id']][]=$v;
		}
        unset($cuxiaoList);
        foreach($cuxiaoListNew as $k=>$v){
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['CXNumber']=$small;
                }
			}
		}
		


        //调换货(调出)
		$TCHListNew=array();
		foreach($TCHList as $k=>$v){
			$TCHListNew[$v['goods_id']][]=$v;
		}
		
        unset($TCHList);
		
		foreach($TCHListNew as $k=>$v){
			
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['TCHNumber']=$small;
                }
			} 
			
		}
		
		//调换货(换回)
		$HHHListNew=array();
		foreach($HHHList as $k=>$v){
			$HHHListNew[$v['goods_id']][]=$v;
		}
        unset($HHHList);
		foreach($HHHListNew as $k=>$v){
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['HHHNumber']=$small;
                }
			}
		}

       
		
		//退货数量
		$tuihuoListNew=array();
		foreach($tuihuoList as $k=>$v){
			$tuihuoListNew[$v['goods_id']][]=$v;
		}
        unset($tuihuoList);
        foreach($tuihuoListNew as $k=>$v){
			$small=$this->getSamllUnit($k,$GoodsCV,$v);
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['THNumber']=$small;
                }
			}
		}
		
		//单价*数量
		$danjiaListNew=array();
		foreach($danjiaList as $k=>$v){
			$danjiaListNew[$v['goods_id']][]=$v;
		}
        unset($danjiaList);
		
		foreach($danjiaListNew as $k=>$v){
			foreach($brandList as $kk=>$vv){
				
				if($brandList[$kk]['data'][$k]){
            		$brandList[$kk]['data'][$k]['data']=$v;
					
					foreach($v as $vvv){
						
						$brandList[$kk]['data'][$k]['xiaoji']+=$vvv['singleprice']*$vvv["number"];
						
					}
					
                }
			} 
			
		}
		
		//筛选不都为null的数据
		$brandListNew=array();
		foreach($brandList as $k=>$v){
			
			
			foreach($v['data'] as $kk=>$vv){
				
				if($vv['today_carport']||$vv['CKNumber']||$vv['TKNumber']||$vv['XSNumber']||$vv['CXNumber']||$vv['TCHNumber']||$vv['HHHNumber']||$vv['THNumber']){
					$brandListNew[$k]['brand_id']=$v['brand_id'];
					$brandListNew[$k]['brand_name']=$v['brand_name'];
					$brandListNew[$k]['data'][$kk]['goods_id']=$vv['goods_id'];
					$brandListNew[$k]['data'][$kk]['goods_name']=$vv['goods_name'];
					$brandListNew[$k]['data'][$kk]['goods_spec']=$vv['goods_spec'];
					if($vv['data']){
						$brandListNew[$k]['data'][$kk]['data']=$vv['data'];
					}else{
						$brandListNew[$k]['data'][$kk]['data']=array(array('singleprice'=>0,'number'=>0));
					}
					
					if($vv['xiaoji']){
						$brandListNew[$k]['data'][$kk]['xiaoji']=$vv['xiaoji'];
					}else{
						$brandListNew[$k]['data'][$kk]['xiaoji']=0;
					}
					
					if($vv['today_carport']){
						$brandListNew[$k]['data'][$kk]['today_carport']=$vv['today_carport'];
					}else{
						$brandListNew[$k]['data'][$kk]['today_carport']='0';
					}
                    if($vv['CKNumber']){
						$brandListNew[$k]['data'][$kk]['CKNumber']=$vv['CKNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['CKNumber']='0';
					}
								
					if($vv['TKNumber']){
						$brandListNew[$k]['data'][$kk]['TKNumber']=$vv['TKNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['TKNumber']='0';
					}
					if($vv['XSNumber']){
						$brandListNew[$k]['data'][$kk]['XSNumber']=$vv['XSNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['XSNumber']='0';
					}
					if($vv['CXNumber']){
						$brandListNew[$k]['data'][$kk]['CXNumber']=$vv['CXNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['CXNumber']='0';
					}
					if($vv['TCHNumber']){
						$brandListNew[$k]['data'][$kk]['TCHNumber']=$vv['TCHNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['TCHNumber']='0';
					}
					if($vv['HHHNumber']){
						$brandListNew[$k]['data'][$kk]['HHHNumber']=$vv['HHHNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['HHHNumber']='0';
					}
					
					if($vv['THNumber']){
						$brandListNew[$k]['data'][$kk]['THNumber']=$vv['THNumber']['numberString'];
					}else{
						$brandListNew[$k]['data'][$kk]['THNumber']='0';
					}
					/*实时车存计算*/
					$cv=$this->getCV($kk, $GoodsCV);
					// 对账周期剩余车存计算
					$ssSmallNum = $vv['today_carport_int'] // 上次对账车存
						+$vv['THNumber']['number'] // 退货数量
						+$vv['HHHNumber']['number'] // 调换货(换回)
						-$vv['TCHNumber']['number'] // 调换货(调出)
						-$vv['CXNumber']['number'] // 促销品
						-$vv['XSNumber']['number'] // 销售数量
						-$vv['TKNumber']['number'] // 审核通过退库数量
						+$vv['CKNumber']['number']; // 已收货出库数量
					$numberString=$this->getNumberString($ssSmallNum, $cv);
                    if($numberString){
                    	$brandListNew[$k]['data'][$kk]['SSNumber']=$numberString;
						$brandListNew[$k]['data'][$kk]['SSNumber_int']=$ssSmallNum;
                    }else{
                    	$brandListNew[$k]['data'][$kk]['SSNumber']='0';
                    }
				}
			}
		}

		return $brandListNew;
    }
	
	
	/*
	 * 得到最小单位                                                                                      |
	 * $data=array(                         |
	 *     
             '0' =>array(
	 *         'goods_id' => '56', 
               'cv_id' => '152' ,
               'goods_unit' =>'浠�', 
               'number' => '2', 
	 * 
	 *       ) ,
	 *       '1' =>array(
	 *         'goods_id' => '56', 
               'cv_id' => '151' ,
               'goods_unit' =>'浠�', 
               'number' => '2', 
	 * 
	 *       ) 
              
          
	 * )
	 * $goods_cv,系数元数据
	 */
	private function getSamllUnit($goods_id,$GoodsCV,$data){
		

		$cv=$this->getCV($goods_id, $GoodsCV);

		$number=0;
		foreach($data as $v){
			if($v["cv_id"]==$cv['small_cv_id']){
			  	  $number+=$v['number'];
			}elseif($v["cv_id"]==$cv['mid_cv_id']){
				  $number+=$v['number']*$cv['mid_cv'];
			}elseif($v["cv_id"]==$cv['big_cv_id']){
				  $number+=$v['number']*$cv['mid_cv']*$cv['big_cv'];
			}
		}
		
		
		
		$numberString=$this->getNumberString($number, $cv);
		
		return array(
		'number'=>$number,
		'numberString'=>$numberString
		);
	}
    /*
	 * 根据数量和系数转换成默认单位
	 */
    private function getNumberString($number,$cv){
//  	if($cv['big_unit']=="无"){
//  		$cv['big_is_close']=1;
//  	}
//		if($cv['mid_unit']=="无"){
//  		$cv['mid_is_close']=1;
//  	}
//		if($cv['small_unit']=="无"){
//  		$cv['small_is_close']=1;
//  	}
		
		
    	$numberString="";
//  	if($cv['small_is_close']==0&&$cv['mid_is_close']==0&&$cv['big_is_close']==0){
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
			
//  	}elseif($cv['small_is_close']==0&&$cv['mid_is_close']==0&&$cv['big_is_close']==1){
//  		
//  		
//  	}elseif($cv['small_is_close']==0&&$cv['mid_is_close']==1&&$cv['big_is_close']==1){
//  		$small=$number;
//		}else{
//			if($number>0){
//				$small=$number%$cv['mid_cv'];
//		   	 	$mid=floor(($number%($cv['big_cv']*$cv['mid_cv']))/$cv['mid_cv']);
//				$big=floor($number/($cv['big_cv']*$cv['mid_cv']));
//			}else{
//				$small=$number%$cv['mid_cv'];
//		    	$mid=ceil(($number%($cv['big_cv']*$cv['mid_cv']))/$cv['mid_cv']);
//				$big=ceil($number/($cv['big_cv']*$cv['mid_cv']));
//			}
//		}

        
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
	 /*
	 *车销对账赊欠统计 
	 */
	private function sheQianCount($staff_id, $start_date, $end_date)
	{
		$where['create_time'] = array(array("gt",$start_date),array("lt",$end_date));
		$where['staff_id'] = $staff_id;
		$where['is_full_pay']=0;
		$list=M("car_orders")
		->field("cust_id,cust_name,cust_contact,sum(order_total_money) as total_money,sum(order_real_money) as real_money")
		->where($where)->group("cust_id")->select();
		
		foreach($list as $k=>$v){
			
			$res=M("car_orders_qiankuan")->field("sum(price) as qingqianmoney")->where("cust_id=".$v["cust_id"])->find();
			$list[$k]["real_money"]=$v["real_money"]+$res["qingqianmoney"];
			$list[$k]["qiankuan"]=$list[$k]["total_money"]-$list[$k]["real_money"];
		}
		
        
		return $list;
	}
	
	
	public function duizhangAction(){
		
		//公共数据
		$staff_id=$_GET["staff"];
		$whereJC["admin_id"]=$staff_id;
		$depot_id=M("admin_user")->where($whereJC)->getField("depot_id");
		if($_SESSION["depot_id"]){
			if($depot_id!=$_SESSION["depot_id"]){
				echo "<script>alert('非法操作');<script>";
				return;
			}
		}
		
		
		
		$whereTou['staff_id']=$staff_id;
		$resTou=M('car_duizhang')->field("end_time")->where($whereTou)->order("cd_id desc")->find();
		if($resTou){
			$date1=$resTou["end_time"];//上次对账时间
//			$time1=date("H:i:s",$date1);
//
//			if($time1=="00:00:00"){
//				$date1=$date1+24*3600-1;
//			}
		}else{
			$date1=0;
		}

		$time=time();
		$date2=I("get.end_date",time()); // 截至对账周期时间
		if($date2>$time||$date2<$date1){
			//$this->error("您的电脑时间不是标准时间,请校准时间后再更新数据！");
			$this->assign("staff_id",$staff_id);
			$this->display("errors");
			exit;
		} 

		$this->assign("staff_id",$staff_id);
		// 表头
		$wherebt["admin_id"]=$staff_id;
		$staff=M("admin_user")->field("true_name as staff_name")->where($wherebt)->find();


		
		$this->assign("staff_id",$staff_id);
		$this->assign("staff_name",$staff["staff_name"]);
		$start_date = $date1 ? date("Y-m-d H:i:s",$date1) : '';
		$this->assign("start_date",$start_date);
		$this->assign("end_date",date("Y-m-d H:i:s",$date2));
		$this->assign("start_time_int",$date1);
		
		//数据汇总
		$brandData=$this->duiZhangHuiZongNew($staff_id, $date1, $date2);
		
		
		
		
		foreach($brandData as $k=>$v){
			$countNum=0;
			foreach($v['data'] as $kk=>$vv){
				foreach($vv['data'] as $kkk=>$vvv){
					$countNum++;
				}
			}
			$brandData[$k]['count']=$countNum;
		}
		//赊欠
		$sheqianData=$this->sheQianCount($staff_id, $date1, $date2);
				$this->assign("sheqianData",$sheqianData);
		
		//订单总额//订单实收
		$whereOrder['create_time'] = array(array("gt",$date1),array("lt",$date2));
		$whereOrder["staff_id"]=$staff_id;
		$resSum=M("car_orders")->field("sum(order_total_money) as totalMoney,sum(order_real_money) as realMoney")->where($whereOrder)->find();
		$this->assign("resSum",$resSum);
		//赊欠金额
		$whereQian["zdb_car_orders.staff_id"]=$staff_id;
		$whereQian["zdb_car_orders.create_time"]=array(array("gt",$date1),array("lt",$date2));
		$resQian=M("car_orders_qiankuan")->field("sum(price) as total_price")->join("zdb_car_orders on zdb_car_orders.order_id=zdb_car_orders_qiankuan.orderid")->where($whereQian)->find();
		$qianKuanMoney=$resSum['totalmoney']-$resSum['realmoney']-$resQian['total_price'];
        $this->assign("qianKuanMoney",$qianKuanMoney);
		//退货退款
		$whereTH["staff_id"]=$staff_id;
		$whereTH["create_time"]=array(array("gt",$date1),array("lt",$date2));
		$resTH=M("car_return")->field("sum(total_money) as tui_total_money")->where($whereTH)->find();
		$this->assign("tui_total_money",$resTH["tui_total_money"]);
		//清欠
		$whereQK["addtime"]=array(array("gt",$date1),array("lt",$date2));
		$whereQK["staff_id"]=$staff_id;
		$whereQK["qk_type"]=0;//业务员清欠
		$resQK=M("car_orders_qiankuan")->field("sum(price) as total_staff_qkmoney")->where($whereQK)->find();
		$total_staff_qkmoney=$resQK["total_staff_qkmoney"];
		$whereQK["qk_type"]=1;//业务员清欠
		$resQK=M("car_orders_qiankuan")->field("sum(price) as total_admin_qkmoney")->where($whereQK)->find();
		$total_admin_qkmoney=$resQK["total_admin_qkmoney"];
		$this->assign("total_staff_qkmoney",$total_staff_qkmoney);
		$this->assign("total_admin_qkmoney",$total_admin_qkmoney);
		//欠款撤销金额
		$whereQKCX["is_cancel"]=1;
		$whereQKCX["cancel_time"]=array(array("gt",$date1),array("lt",$date2));
		$whereQKCX["staff_id"]=$staff_id;
		$listQKCX=M("car_orders")->field("order_id,order_total_money,order_real_money")->where($whereQKCX)->select();
		
		$orderids=array();
		$total_money=0;
		$real_money=0;
		
		foreach($listQKCX as $v){
			 $total_money+=$v["order_total_money"];
			 $real_money+=$v["order_real_money"];
	         $orderids[]=$v["order_id"];
		}
		if($orderids){
			$whereQKCXId["orderid"]=array("in",$orderids);
		}
		
		$resQkO=M("car_orders_qiankuan")->field("sum(price) as qingqianmoney")->where($whereQKCXId)->find();
		
		if($listQKCX){
			$qiankuanChexiao=$total_money-$resQkO["qingqianmoney"]-$real_money;
		}else{
			$qiankuanChexiao="0.00";
		}
		
		$this->assign("qiankuanChexiao",$qiankuanChexiao);
		//调换货差价
		$whereChange["zdb_car_change.staff_id"]=$staff_id;
		$whereChange["create_time"]= array(array("gt",$date1),array("lt",$date2));
		$whereChange["is_change_in"]=0;
		$ChangeOutRes=M("car_change")->field("sum(singleprice*number) as total_in_money")->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")->where($whereChange)->find();
		$whereChange["is_change_in"]=1;
		$ChangeInRes=M("car_change")->field("sum(singleprice*number) as total_out_money")->join("zdb_car_change_goods on zdb_car_change_goods.change_id=zdb_car_change.change_id")->where($whereChange)->find();
		$changeMoney=$ChangeOutRes["total_in_money"]-$ChangeInRes["total_out_money"];
		//对账金额
		$duizhangMoney=$resSum['realmoney']-$resTH["tui_total_money"]+$total_staff_qkmoney+$changeMoney;
		$this->assign("duizhangMoney",$duizhangMoney);
		$this->assign("BrandData",$brandData);
		
		
		$this->display();
	}
	
	
	public function setDuizhangAction(){
	
		$start=I("post.end_date");
		$time=strtotime($start);
       
        $data["staff_id"]=I("post.staff_id");

        $wherebt["admin_id"] = I("post.staff_id");

        $depot_id = $this->_depot_id;
        if ($depot_id > 0) {
            $wherebt["depot_id"] = $depot_id;
        }

        $staff=M("admin_user")->field("true_name as staff_name")->where($wherebt)->find();

        if(!$staff) {
            echo 0;
            //echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }

		$data["remark"]=I("post.remark");
		$data["changemoney"]=I("post.changeMoney");
		$data["tuihuomoney"]=I("post.tuihuotuikuan");
		
		$data["tuihuomoney"]=I("post.tuihuotuikuan");
		$data["sheqianmoney"]=I("post.qiankuan");
		$data["qingqianmoney"]=I("post.qingqian");
		$data["qiankuanchexiao"]=I("post.qiankuanChexiao");
		$data["shishoumoney"]=I("post.order_real_money");
		$data["totalmoney"]=I("post.order_total_money");
		$data["end_time"]=$time;
		$data["start_time"]=I("post.start_time_int");
		$data["status"] = 1;

		$cd_id=M("car_duizhang")->add($data);
		
		$goods=I("post.goods");	
		     
		foreach ($goods as $k=>$good){
			$dataGood[$k]["goods_id"]=$good["goods_id"];
			$dataGood[$k]["goods_name"]=$good["goods_name"];
			$dataGood[$k]["goods_spec"]=$good["goods_spec"];
			$dataGood[$k]["brand_name"]=$good["brand_name"];
			$dataGood[$k]["brand_id"]=$good["brand_id"];
			$dataGood[$k]["last_carport"]=$good["lastNumber"];
			$dataGood[$k]["depot_out"]=$good["CKNumber"];
			$dataGood[$k]["tui_depot"]=$good["TKNumber"];
			$dataGood[$k]["sales_num"]=$good["XSNumber"];
			$dataGood[$k]["org_parent_id"]=0;
			$dataGood[$k]["org_parent_name"]=" ";	
			$dataGood[$k]["cuxiao_num"]=$good["CXNumber"];
			$dataGood[$k]["tui_num"]=$good["THNumber"];
			$dataGood[$k]["carport"]=$good["SSNumber"];
			$dataGood[$k]["carport_int"]=$good["SSNumber_int"];
			$dataGood[$k]["change_in_num"]=$good["HHHNumber"];
			$dataGood[$k]["change_out_num"]=$good["TCHNumber"];
			$dataGood[$k]["cd_id"]=$cd_id;
//			$dataGood[$k]["goods_price"]=$good["singleprice"];
//			$dataGood[$k]["goods_num"]=$good["number"];
			$dataGood[$k]["xiaoji"]=$good["xiaoji"];
								
		}
		$res=M("car_duizhang_goods")->addAll($dataGood);	
			
			
			$sheqians=I("post.sheqians");
			foreach ($sheqians as $k=>$sheqian){
				$dataSheqian[$k]["shop_id"]=$sheqian["cust_id"];
				$dataSheqian[$k]["cust_name"]=$sheqian["cust_name"];
				$dataSheqian[$k]["cust_boss"]=$sheqian["cust_contact"];
				$dataSheqian[$k]["total_money"]=$sheqian["order_total_money"];
				$dataSheqian[$k]["yifu_money"]=$sheqian["order_real_money"];
				$dataSheqian[$k]["sheqian_money"]=$sheqian["qiankuan"];
				$dataSheqian[$k]["history_money"]=0;
				
				$dataSheqian[$k]["cd_id"]=$cd_id;
			
			}
			M("car_duizhang_sheqian")->addAll($dataSheqian);

			if($cd_id){
				echo 1;
			}else{
				echo 0;
			}
        
	}
	
	
	/** 其他Action **/
   public function historyAction(){
		$p=I("get.p",1);
        $pnum=I("get.pnum",10);
		$staff_id=I("get.staff");
		$where['staff_id']=$staff_id;
		$total=M("car_duizhang")->where($where)->count();
		$list=M("car_duizhang")->field("cd_id as id,end_time")->where($where)->order("end_time desc")->page($p,$pnum)->select();
		$page=get_page_code($total,$pnum,$p, $page_code_len = 5);
		$whereA["admin_id"]=$staff_id;
		$staff=M("admin_user")->field("true_name as staff_name")->where($whereA)->find();
		$this->assign("staff_name",$staff["staff_name"]);
		$this->assign("list",$list);
		$this->assign('pagelist',$page);
        $this->assign('pnum',$pnum);
        $this->assign('urlPara',array("staff"=>$staff_id));
		$this->display();
	}
   
    public function showHistoryDuizhangAction(){
    	$id=I("get.id");
		$this->assign("id",$id);
		$where["cd_id"]=$id;
		$resDuizhang=M("car_duizhang")->where($where)->order("cd_id desc")->find();


		
		$this->assign("resDuizhang",$resDuizhang);
		
		/*
		 * 表头
		 */
		$wherebt["admin_id"]=$resDuizhang['staff_id'];

        $depot_id = $this->_depot_id;
        if ($depot_id > 0) {
            $wherebt["depot_id"] = $depot_id;
        }

		$staff=M("admin_user")->field("true_name as staff_name")->where($wherebt)->find();

        if(!$staff) {
            echo "<script>alert('非法操作！');window.location='./';</script>"; exit;
        }
		
		$this->assign("staff_id",$wherebt["admin_id"]);
		$this->assign("staff_name",$staff["staff_name"]);
		if($resDuizhang['start_time']){
			$start_date=date("Y-m-d H:i:s",$resDuizhang['start_time']);
		}else{
			$start_date="";
		}
		$end_date=date("Y-m-d H:i:s",$resDuizhang['end_time']);
		
		$this->assign("start_date",$start_date);
		$this->assign("end_date",$end_date);
		
		
		
		//汇总
		$where2["cd_id"]=$resDuizhang["cd_id"];
		
		$huizongList=M("car_duizhang_goods")
		->field("brand_id,brand_name")
		->where($where2)->group("brand_id")->select();
		foreach ($huizongList as $kk=>$vv){
			$where3["cd_id"]=$resDuizhang["cd_id"];
			$where3["brand_id"]=$vv["brand_id"];
			$huizongGoods=M("car_duizhang_goods")
			//->field("goods_name,goods_spec,last_carport,today_depot_out,sales_num,cuxiao_num,tui_num,today_carport,fufu_num,chenlie_num")
			->where($where3)->group("goods_id")->select();
			
			
			foreach($huizongGoods as $k=>$v){
				if(!$v["chenlie_num"]){
					$huizongGoods[$k]["chenlie_num"]="0";
				}
				if(!$v["change_in_num"]){
					$huizongGoods[$k]["change_in_num"]="0";
				}
				if(!$v["change_out_num"]){
					$huizongGoods[$k]["change_out_num"]="0";
				}
				$where1["cd_id"]=$resDuizhang["cd_id"];
				$where1["goods_id"]=$v["goods_id"];
			}
			$huizongList[$kk]["Goods"]=$huizongGoods;
			
		}
		
		$this->assign("huizongList",$huizongList);
		//赊欠
		$sheqianList=M("car_duizhang_sheqian")->where($where2)->select();
		$this->assign("sheqianList",$sheqianList);
		
		
		
		
		$this->display();
    }

}

/*************************** end ************************************/