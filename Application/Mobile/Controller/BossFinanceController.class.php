<?php

/*******************************************************************
 ** 文件名称: BossFinanceController.class.php
 ** 功能描述: 经销商老板端财务接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-25
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class BossFinanceController extends Controller {

	// 销售额统计柱状图
	public function salesStatusAction()
	{
		// 经销商ID和查询类型
		$org_parent_id = I('companyId', 0);
		$type = intval($_REQUEST['type']);

		// 返回数据
		$list = array();

		// 按天查询, 显示最近5天的销售情况
		if($type==1)
		{
			$time=time();
			for($i=0; $i<=4; $i++)
			{
				// X轴是日期
				$tmp_day = date('Y-m-d', $time-(4-$i)*24*3600);
				$list['x'][$i] = date('j', $time-(4-$i)*24*3600)."日";
				
				// Y轴是该日期下的销售额		
				$start_time = strtotime($tmp_day);
				$end_time = strtotime($tmp_day)+24*60*60-1;
				$where = array();
				$where['org_parent_id'] = $org_parent_id;
				$where['create_time'] = array(array('gt',$start_time),array('lt',$end_time));
				$total = M('carsale_orders')->where($where)->sum('order_total_money');
				$list['y'][$i] = floatval($total);		
			}
   		}

		// 按周查询, 显示最近5周的销售情况
		if($type==2)
		{
			// 本周第一天, 从星期一开始
			$this_week_begin_date = date('Y-m-d', strtotime('this week'));
			$this_week_begin_time = strtotime($this_week_begin_date . " 00:00:00");

			// 倒计时从上5周开始到本周
			$m=4;
			for($i=0; $i<5; $i++)
			{
				// 计算周第一天和最后一天
				$week_begin_time =  $this_week_begin_time - ($m * 7*24*60*60);
				$week_end_time = $week_begin_time + (7*24*60*60);

				// X轴标示周
				if($m == 0)
				{
					$list['x'][$i] = "本周";
				}
				else
				{
					$list['x'][$i] = "上".$m."周";
				}
   				
				// 查询周销售额
				$where = array();
				$where['org_parent_id'] = $org_parent_id;
				$where['create_time'] = array(array('gt',$week_begin_time),array('lt', $week_end_time));
				$total = M('carsale_orders')->where($where)->sum('order_total_money');
				$list['y'][$i] = floatval($total);
				$m--;
			}		  			 			
   		}
		
		// 按月查询, 显示最近5月的销售情况
		if($type==3)
		{
			for($j=0; $j<=4; $j++)
			{
				// X轴表示月份
   				$m = 4 - $j;				
   				$list['x'][$j] = date('n' ,strtotime('-'.$m.' month'))."月";
				
				// 月份开始时间
				$month_begin_date = date('Y-m-01' ,strtotime('-'.$m.' month'));
				$month_end_date = date('Y-m-01' ,strtotime('-'.($m-1).' month'));
				$month_begin_time = strtotime($month_begin_date . '00:00:00');
				$month_end_time = strtotime($month_end_date . '00:00:00');
				
				// 查询月销售额
				$where = array();
				$where['org_parent_id'] = $org_parent_id;
				$where['create_time'] = array(array('gt',$month_begin_time),array('lt', $month_end_time));
				$total = M('carsale_orders')->where($where)->sum('order_total_money');
				$list['y'][$j] = floatval($total);
			}		
   		}

		// 返回
		if(empty($list))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $list));
		}
	}

	// 统计整月销售情况，销售额，促销费用，退货费用，欠款情况，利润
	public function gaikuangAction()
	{
		// 经销商ID和时间段
		$org_parent_id = I("companyId");
		$start_time = I("start_time"); // 2016-08-01
		$end_time = I("end_time"); // 2016-08-31
		$start_time = strtotime($start_time);
		$end_time = strtotime($end_time) + 24 * 60 * 60;
		
		// 返回数据
		$list = array();
		$list["tuihuo"] = 0;
		$list["xiaoshou"] = 0;
		$list["qiankuan"] = 0;
		$list["shouyi"] = 0;
		$list["cuxiao"] = 0;

		// 退货
		$where = array();
		$where["is_cancel"] = 0;	
		$where["org_parent_id"] = $org_parent_id;
		$where["create_time"] = array(array("gt",$start_time), array("lt",$end_time));
		$tuihuo = M("carsales_return")->where($where)->sum('total_money');
		if(!empty($tuihuo)){ $list["tuihuo"]= floatval($tuihuo); }
		
		// 销售额和欠款
		$where = array();
		$where["o.is_cancel"] = 0;	
		$where["o.org_parent_id"] = $org_parent_id;
		$where["o.create_time"] = array(array("gt",$start_time), array("lt",$end_time));
		$result = M('carsale_orders')->alias('o')->field('o.order_total_money, o.order_real_money, sum(q.price) as qingqian')
		->join('left join __CARSALE_ORDERS_QIANKUAN__ as q on o.order_id = q.orderid')
		->group('o.order_id')->where($where)->select();
		foreach($result as $val)
		{
			$order_total_money = $val['order_total_money'];
			$order_real_money = $val['order_real_money'];
			$qingqian = floatval($val['qingqian']);
			$list["xiaoshou"] += $order_total_money;
			$list["qiankuan"] += ($order_total_money - $order_real_money - $qingqian);
		}
        if(!empty($list["xiaoshou"])){ $list["xiaoshou"]= floatval($list["xiaoshou"]); }
        if(!empty($list["qiankuan"])){ $list["qiankuan"]= floatval($list["qiankuan"]); }


	    // 收益
		$where = array();
		$where["o.is_cancel"] = 0;	
		$where["o.org_parent_id"] = $org_parent_id;
		$where["o.create_time"] = array(array("gt",$start_time), array("lt",$end_time));
		$results = M('carsale_orders')->alias('o')->field("((og.singleprice - oc.goods_jin_price) * sum(og.number)) as shouyi")
		->join('__CARSALE_ORDERS_GOODS__ as og on o.order_id = og.order_id')
		->join('__ORG_GOODS_CONVERT__ as oc on og.cv_id = oc.cv_id')
		->where($where)->find();	
			
		if(empty($results)){
			 $list["shouyi"] = 0; 
		}else{
			$list["shouyi"] = floatval($results['shouyi']);
		}

		// 促销
		$map = array();
		$map["od.is_cancel"] = 0;
		$map["od.create_time"] = array (array ('gt',$start_time),array ('lt',$end_time));
		$map['od.org_parent_id'] = $org_parent_id;
		$map['oi.cuxiao'] = 1;
		$cuxiao = M("carsale_orders")->alias("od")
		->join("__CARSALE_ORDERS_GOODS__ as oi on od.order_id = oi.order_id")
		->join("__ORG_GOODS_CONVERT__ as gc on gc.cv_id = oi.cv_id")
		->where($map)->sum('gc.goods_jin_price * oi.number');
		if(!empty($cuxiao)){ $list["cuxiao"] = floatval($cuxiao); }

		// 返回
		if(empty($list))
		{
			echo json_encode(array('error' => 1, 'msg' => '查询失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' => $list));
		}
	}

}

/*************************** end ************************************/