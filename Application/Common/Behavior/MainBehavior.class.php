<?php

/*******************************************************************
 ** 文件名称: MainBehavior.class.php
 ** 功能描述: 抓单宝默认主行为类
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Common\Behavior;
use Think\Behavior;

class MainBehavior extends Behavior {

	// 行为扩展的执行入口必须是run
	public function run(&$params)
	{
		return true;
	}

}

/*************************** end ************************************/