<?php

/******************************************************************
 ** 文件名称: function.php
 ** 功能描述: 项目基础公共函数库
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

/*****************************************************************
 ** 函数名称: checkStockFunction
 ** 功能描述: 出库检查货品库存
 ** 输入: goods_id, repertory_id, org_parent_id, cv_id, num
 ** goods_id, 整型, 商品ID
 ** repertory_id, 整型, 仓库ID
 ** cv_id, 整型, 货品ID
 ** num, 整型, 货品数量
 ** 返回: 库存是否充足, ture代表充足, 可以出库
 ** 调用模块: Home/DepotOutController,
 ** 检查人员: richie
 ** 检查日期: 2016-05-19
 ****************************************************************/
function checkStockFunction($goods_id,$repertory_id,$cv_id,$num)
{
    // 查询仓库实时库存, 最小单位
    $where["goods_id"]=$goods_id;
    $where["depot_id"]=$repertory_id;
    $res=M("depot_stock")->field("small_stock")->where($where)->find();

    // 出库货品转化最小单位数量
    $resd=getTransUnit($cv_id,$num);

    // 库存是否充足
    if($resd["good_num"]>$res["small_stock"]) return false;
    else return true;
}

//检查表字段数据是否存在
function checkFieldExist($check,$check_where){
    $status = M($check)->where($check_where)->select();
    return $status;
}

//换算最小单位数量，返回最小单位数量
function getGoodsTransferUnit($org_parent_id, $goods_id, $num, $cv_id){
    $newNum = 0;
    $key = "data_aGoods_$goods_id";
    if(C('GLOBAL_CACHE') == true){
        $aGoods= S($key);
    }
    if ($aGoods == false) {
        $mGoods = new \Common\Model\GoodsInfoModel();
        $aGoods = $mGoods->getGoodsByCvId($org_parent_id, $goods_id, $cv_id);
        S($key,$aGoods, 86400);
    }

    $bigNumber = eval("return {$aGoods["goods_convert"]};");
    $inNumber = eval("return {$aGoods["goods_convert_m"]};");
    if ($aGoods["goods_unit_type"] == 1)
        $newNum = $num;
    else if ($aGoods["goods_unit_type"] == 2)
        $newNum = $inNumber * $num;
    else if ($aGoods["goods_unit_type"] == 3)
        $newNum = $bigNumber * $num;
    return $newNum;
}

//返回js，几秒后跳转到URL
//创建人员：wangbo
//创建日期：2016-08-04
function jumpToUrl($url,$sec=3){
    echo '<script>setTimeout(function (){location.href="'.$url.'";},'.($sec*1000).');</script>';
}
//返回js，提示框并跳转到URL
//创建人员：wangbo
//创建日期：2016-08-04
function alertToUrl($msg,$url){
    echo '<script>alert("' .$msg. '");location.href="' .$url .'";</script>';
}

//返回js，确认提示框并跳转到URL
//创建人员：wangbo
//创建日期：2016-08-04
function confirmToUrl($msg,$okUrl,$cancelUrl){
    echo '<script>if(confirm("' .$msg. '")){location.href="' .$okUrl. '"}else{location.href="' .$cancelUrl. '"}</script>';
}

// 入库类型
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepotInType($typeID = 0)
{
	$typeList = array(1=>'经销商入库',2=>'经销商退库',3=>'配送退库',4=>'盘赢入库');
	if($typeID > 0)
	{
		return $typeList[$typeID];
	}
	else
	{
		return $typeList;
	}
}

// 入库状态
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepotInState($stateID = 0)
{
	$stateList = array(1=>'已提交',2=>'已审核');
	if($stateID > 0)
	{
		return $stateList[$stateID];
	}
	else
	{
		return $stateList;
	}
}

// 出库类型
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepotOutType($typeID = 0)
{
	$typeList = array(1=>'经销商出库',2=>'配送出库',3=>'盘亏出库',4=>'报损出库');
	if($typeID > 0)
	{
		return $typeList[$typeID];
	}
	else
	{
		return $typeList;
	}
}

// 出库状态
// 创建人员: richie
// 创建日期: 2016-08-03
function queryDepotOutState($stateID = 0)
{
	$stateList = array(1=>'已提交',2=>'已审核');
	if($stateID > 0)
	{
		return $stateList[$stateID];
	}
	else
	{
		return $stateList;
	}
}

function queryCustWeihuType($typeID = 0)
{
	$typeList = array(1=>'陈列拍照',2=>'下车销单',3=>'调换货',4=>'退货',5=>'清欠',6=>'店铺日志');
	if($typeID > 0)
	{
		return $typeList[$typeID];
	}
	else
	{
		return $typeList;
	}
}

//分页页码

function get_page_code($record_total, $page_size, $page_current, $page_code_len = 10)
{
    // 页码数组
    $page_code = array();
    $page_num = array();

    // 无数据判断
    if ($record_total == 0) {
        return NULL;
    }

    // 存放总记录数和当前页
    $page_code['record_total'] = $record_total;
    $page_code['page_current'] = $page_current;

    // 计算分页大小
    $page_total = ceil($record_total / $page_size);
    $page_code['page_total'] = $page_total;

    // 前后页码
    $page_prev = $page_current - 1;
    if ($page_prev <= 0) {
        $page_prev = 1;
    }
    $page_next = $page_current + 1;
    if ($page_next > $page_total) {
        $page_next = $page_total;
    }
    $page_code['page_prev'] = $page_prev;
    $page_code['page_next'] = $page_next;

    // 计算数字页码
    if ($page_total <= $page_code_len) {
        for ($i = 1; $i <= $page_total; $i++) {
            $page_num[$i] = $i;
        }
    } else {
        $page_num_left = floor($page_code_len / 2);
        $page_num_right = $page_total - $page_code_len;

        if ($page_current <= $page_num_left) {
            for ($i = 1; $i <= $page_code_len; $i++) {
                $page_num[$i] = $i;
            }
        } elseif ($page_current > $page_num_right) {
            for ($i = ($page_num_right + 1); $i <= $page_total; $i++) {
                $page_num[$i] = $i;
            }
        } else {
            for ($i = ($page_current - $page_num_left); $i <= ($page_current + $page_num_left); $i++) {
                $page_num[$i] = $i;
            }
        }
    }
    $page_code['page_num'] = $page_num;

    // 返回页码数组
    return $page_code;
}

// 编码生成规则, 共19位
function create_uniqid_code($code_prefix = 'O', $staff_id = 0)
{
	// 编号前缀
	$code_prefix = strtoupper($code_prefix); // 1位
	
	// 业务员ID
	$num_prefix = (intval($staff_id) > 0) ? sprintf("%04d", $staff_id) : rand(1000, 9999);  // 4位

    // 当前日期
    $now_date = date('Ymd'); // 8位

    // 时间戳后四位
    $now_time = rand(100000, 999999);; // 6位

    // 返回编号
    return $code_prefix . $num_prefix . $now_date . $now_time;
}

// 格式化数组
function format_array($data, $key, $value)
{
	$new_data = array();
	foreach($data as $item)
	{
		$new_key = $item[$key];
		$new_value = $item[$value];
		$new_data[$new_key] = $new_value;
	}
	return $new_data;
}
//ajax返回json消息，js弹出框消息
function aJsonReturn($type, $mess)
{
    echo json_encode(array("res" => $type, "info" => $mess));
}

// 根据商品ID和最小数量获取--箱--提--瓶--格式
function getGoodsUnitString($goods_id, $num)
{
	// 查询大中小单位名称
    $pKey = "product_list_$goods_id";
    if(C('GLOBAL_CACHE') == true){
        $product_list = S($pKey);
    }

    if ($product_list == false) {
        $product_list = M('goods_product')->where("goods_id = $goods_id")->order("goods_unit_type asc")->select();

        S($pKey, $product_list, 86400);
    }


	$goods_unit_small = !empty($product_list[0]) ? $product_list[0]['goods_unit'] : '';
	$goods_unit_mid = !empty($product_list[1]) ? $product_list[1]['goods_unit'] : '';
	$goods_unit_big = !empty($product_list[2]) ? $product_list[2]['goods_unit'] : '';

	// 获取大小中单位转换系数
    $cKey = "convert_res_$goods_id";
    if(C('GLOBAL_CACHE') == true){
        $convert_res = S($cKey);
    }
    if ($convert_res == false) {
        $convert_res = M("goods_info")->field("goods_convert_s, goods_convert_m, goods_convert_b")->where("goods_id = $goods_id")->find();

        S($cKey, $convert_res, 86400);
    }


	$goods_cv_small = intval($convert_res["goods_convert_s"]); // 默认是1不予理睬
	$goods_cv_mid = intval($convert_res["goods_convert_m"]);
	$goods_cv_big = intval($convert_res["goods_convert_b"]);
	
	// 转换后字符串格式
	$numString = '';

    if($num>0){
    	// 从最大单位开始转换, 首先存在最大单位系数, 其次满足最大单位的数量
		if($goods_cv_big > 1 && $num >= ($goods_cv_mid*$goods_cv_big))
		{
			$big_num = floor($num / ($goods_cv_mid*$goods_cv_big));
			$big_num=getGoodsNum($big_num);
			$numString .= "$big_num $goods_unit_big ";
			$num = $num - ($big_num*($goods_cv_mid*$goods_cv_big));
		}

		// 转换中单位
		if($goods_cv_mid > 1 && $num >= $goods_cv_mid)
		{
			$mid_num = floor($num / $goods_cv_mid);
			$mid_num=getGoodsNum($mid_num);
			$numString .= "$mid_num $goods_unit_mid ";
			$num = $num - ($mid_num*$goods_cv_mid);
		}

	// 最小单位
		if($num > 0)
		{
			$num=getGoodsNum($num);
			$numString .= "$num $goods_unit_small";
		}
    }elseif($num==0){
    	$numString="0";
    }else{
    	$znum=abs($num);
    	if($goods_cv_big>1 && $znum >= ($goods_cv_mid*$goods_cv_big)){
    		$big_num = floor($znum / ($goods_cv_mid*$goods_cv_big));
			$zbig_num=-$big_num;
			
			$numString .= "$zbig_num $goods_unit_big ";
			$znum = $znum - ($big_num*($goods_cv_mid*$goods_cv_big));
    	}
        if($goods_cv_mid > 1 && $znum >= $goods_cv_mid){
        	$mid_num = floor($znum / $goods_cv_mid);
			$zmid_num=-$mid_num;
			
			$numString .= "$zmid_num $goods_unit_mid ";
			$znum = $znum - ($mid_num*$goods_cv_mid);
        }
		if($znum > 0)
		{
			$znum=-$znum; 
			$numString .= "$znum $goods_unit_small";
		}
		
    }
	

	// 返回
	return $numString;
}

// 根据货品ID计算最小单位数量
function getSmallNumber($cv_id = 0, $num = 0)
{
	// 查询cv_id是那个单位
    $gpKey = "goods_product_$cv_id";
    if(C('GLOBAL_CACHE') == true){
        $result = S($gpKey);
    }
    if ($result == false) {
        $result = M("goods_product")->where("cv_id = " . $cv_id)->find();

        S($gpKey, $result, 86400);
    }

	$goods_unit_type = intval($result['goods_unit_type']);
	$goods_id = intval($result['goods_id']);
    $where["goods_id"]=$goods_id;
	// 查询转换系数
    $giKey = "goods_info_$goods_id";
    if(C('GLOBAL_CACHE') == true){
        $info = S($giKey);
    }
    if ($info == false) {
        $info = M("goods_info")->where($where)->find();

        S($giKey, $info, 86400);
    }

	
    $small = intval($info["goods_convert_s"]);
	$mid = intval($info["goods_convert_m"]);
	$big = intval($info["goods_convert_b"]);
	
	// 如果是最小单位, 不需要转换
	if($goods_unit_type == 1){ return $num; }
	
	// 如果是中单位的话, 
	if($goods_unit_type == 2){ return $num * $mid; }
	 
	// 如果是大单位的话, 
	if($goods_unit_type == 3){ return $num * $mid * $big; }
	
	// 啥都不是的话，
	return 0;
}

function getTransUnit($cv_id, $good_num){

    $gpKey = "goods_product_$cv_id";
    if(C('GLOBAL_CACHE') == true){
        $res = S($gpKey);
    }
    if ($res == false) {
        $res = M("goods_product")->where("cv_id=$cv_id")->find();

        S($gpKey, $res, 86400);
    }

    $giKey = "goods_info_".$res["goods_id"];
    if(C('GLOBAL_CACHE') == true){
        $resGoods = S($giKey);
    }
    if ($resGoods == false) {
        $resGoods=M("goods_info")->where("goods_id=".$res["goods_id"])->find();

        S($giKey, $resGoods, 86400);
    }

	// 小单位,不需要转换
	if($res["goods_unit_type"]==1){
        $data["cv_id"]=$cv_id;
        $data["good_num"]=$good_num;
        $data["goods_unit"]=$res["goods_unit"];
    }
	
	// 中单位转小单位, 中单位包装数量 * 中单位转换系数
	elseif($res["goods_unit_type"]==2)
	{
        $goods_cv=$resGoods["goods_convert_m"];
        $good_num=$good_num*$goods_cv;

        $goods_id=$resGoods["goods_id"];
        $result1=M("goods_product")->field("cv_id,goods_unit")->where("goods_id=$goods_id and goods_unit_type=1")->find();

        $data["cv_id"]=$result1["cv_id"];
        $data["good_num"]=$good_num;
        $data["goods_unit"]=$result1["goods_unit"];
    }
	
	// 大单位转小单位, 大单位包装数量*大单位转换系数*中单位转换系数
	elseif($res["goods_unit_type"]==3)
	{
		
        $goods_cv=$resGoods["goods_convert_b"]*$resGoods["goods_convert_m"];
		$good_num=$good_num*$goods_cv;
		$goods_id=$resGoods["goods_id"];

        $result1=M("goods_product")->field("cv_id,goods_unit")->where("goods_id=$goods_id and goods_unit_type=1")->find();

        $data["cv_id"]=$result1["cv_id"];
        $data["good_num"]=$good_num;
        $data["goods_unit"]=$result1["goods_unit"];
    }
    return $data;
}


//商品单位转换（转换成大单位）
function getTransUnitToBig($cv_id,$good_num){

    $gpKey = "goods_product_$cv_id";
    if(C('GLOBAL_CACHE') == true){
        $res = S($gpKey);
    }
    if ($res == false) {
        $res = M("goods_product")->where("cv_id=$cv_id")->find();

        S($gpKey, $res, 86400);
    }

    $giKey = "goods_info_".$res["goods_id"];
    if(C('GLOBAL_CACHE') == true){
        $resGoods = S($giKey);
    }
    if ($resGoods == false) {
        $resGoods=M("goods_info")->where("goods_id=".$res["goods_id"])->find();

        S($giKey, $resGoods, 86400);
    }

	if($res["goods_unit_type"]==3){
		$data["cv_id"]=$cv_id;
		$data["good_num"]=$good_num;
		$data["goods_unit"]=$res['goods_unit'];
	}elseif($res["goods_unit_type"]==2){

		$goods_id=$res["goods_id"];
		$result=M("goods_product")->field("cv_id,goods_unit")->where("goods_id=$goods_id and goods_unit_type=3")->find();

		$goods_cv=$resGoods["goods_convert_b"];
		$good_num=floor($good_num/$goods_cv);
		$data["cv_id"]=$result["cv_id"];
		$data["good_num"]=$good_num;
		$data["goods_unit"]=$result["goods_unit"];
	}elseif($res["goods_unit_type"]==1){
		$goods_id=$res["goods_id"];
		$result=M("goods_product")->field("cv_id,goods_unit")->where("goods_id=$goods_id and goods_unit_type=3")->find();
		
		$goods_cv=$result["goods_cv"];
		$good_num=floor($good_num/($resGoods["goods_convert_m"]*$resGoods["goods_convert_b"]));
		$data["cv_id"]=$result["cv_id"];
		$data["good_num"]=$good_num;
		$data["goods_unit"]=$result["goods_unit"];
	}
	return $data;
}

//商品单位转换（转换成中单位）
function getTransUnitToMid($cv_id,$good_num){

    $gpKey = "goods_product_$cv_id";
    if(C('GLOBAL_CACHE') == true){
        $res = S($gpKey);
    }
    if ($res == false) {
        $res = M("goods_product")->where("cv_id=$cv_id")->find();

        S($gpKey, $res, 86400);
    }

    $giKey = "goods_info_".$res["goods_id"];
    if(C('GLOBAL_CACHE') == true){
        $resGoods = S($giKey);
    }
    if ($resGoods == false) {
        $resGoods=M("goods_info")->where("goods_id=".$res["goods_id"])->find();

        S($giKey, $resGoods, 86400);
    }

	if($res["goods_unit_type"]==2){
		$data["cv_id"]=$cv_id;
		$data["good_num"]=$good_num;
		$data["goods_unit"]=$res["goods_unit"];
	}elseif($res["goods_unit_type"]==1){
		$goods_id=$res["goods_id"];
		$result=M("goods_product")->field("cv_id,goods_unit")->where("goods_id=$goods_id and goods_unit_type=2")->find();
		$goods_cv=$resGoods["goods_convert_m"];
		$good_num=floor($good_num/$goods_cv);
		$data["cv_id"]=$result["cv_id"];
		$data["good_num"]=$good_num;
		$data["goods_unit"]=$result["goods_unit"];
	}elseif($res["goods_unit_type"]==3){
		$goods_cv=$res["goods_convert_b"];
		$good_num1=$good_num*$goods_cv;
		$goods_id=$res["goods_id"];
		$result=M("goods_convert")->field("cv_id,goods_unit")->where("goods_id=$goods_id and goods_unit_type=2")->find();
		$data["cv_id"]=$result["cv_id"];
		$data["good_num"]=$good_num1;
		$data["goods_unit"]=$result["goods_unit"];
	}
	return $data;
}


//获得字符串首字符的首字母
function getFirstCharter($str)
{
    if (empty($str)) { return 'Z'; }
    
    $fchar = ord($str{0});
    
    if ($fchar >= ord('A') && $fchar <= ord('z'))
        return strtoupper($str{0});
    
    $s1 = iconv('UTF-8', 'gb2312', $str);
    
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    
    $s = $s2 == $str ? $s1 : $str;
    
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    
    if ($asc >= -20319 && $asc <= -20284)
        return 'A';
    
    if ($asc >= -20283 && $asc <= -19776)
        return 'B';
    
    if ($asc >= -19775 && $asc <= -19219)
        return 'C';
    
    if ($asc >= -19218 && $asc <= -18711)
        return 'D';
    
    if ($asc >= -18710 && $asc <= -18527)
        return 'E';
    
    if ($asc >= -18526 && $asc <= -18240)
        return 'F';
    
    if ($asc >= -18239 && $asc <= -17923)
        return 'G';
    
    if ($asc >= -17922 && $asc <= -17418)
        return 'H';
    
    if ($asc >= -17417 && $asc <= -16475)
        return 'J';
    
    if ($asc >= -16474 && $asc <= -16213)
        return 'K';
    
    if ($asc >= -16212 && $asc <= -15641)
        return 'L';
    
    if ($asc >= -15640 && $asc <= -15166)
        return 'M';
    
    if ($asc >= -15165 && $asc <= -14923)
        return 'N';
    
    if ($asc >= -14922 && $asc <= -14915)
        return 'O';
    
    if ($asc >= -14914 && $asc <= -14631)
        return 'P';
    
    if ($asc >= -14630 && $asc <= -14150)
        return 'Q';
    
    if ($asc >= -14149 && $asc <= -14091)
        return 'R';
    
    if ($asc >= -14090 && $asc <= -13319)
        return 'S';
    
    if ($asc >= -13318 && $asc <= -12839)
        return 'T';
    
    if ($asc >= -12838 && $asc <= -12557)
        return 'W';
    
    if ($asc >= -12556 && $asc <= -11848)
        return 'X';
    
    if ($asc >= -11847 && $asc <= -11056)
        return 'Y';
    
    if ($asc >= -11055 && $asc <= -10247)
        return 'Z';

    return 'Z';

}

function getGoodsNum($num){
	
	return floatval($num);
}


/*************************** end **********************************/