<?php
namespace Dealer\Widget;
use Think\Controller;
class PageWidget extends Controller
{
	/**
	 * Func page
	 * Editor tianxiaolong
	 * @param $url
	 * @param $pnum
	 * @param $page
	 * @param array $param 分页条件
	 */
	public function pageAction($url, $pnum, $page, $param=array())
	{
		$paramStr = '';
		if (count($param) > 0) {
			$paramStr = "?";
			$counts = count($param);
			$i = 0;
			foreach ($param as $k=>$v) {
				if (false !== $v) {
					$paramStr.=$k."=".$v;
					if ($i != $counts - 1) {
						$paramStr .= '&';
					}
				}
				++$i;
			}
			$strLen = strlen($paramStr) - 1;
			if ($paramStr[$strLen] == '&') {
				$paramStr = substr($paramStr, 0, $strLen);
			}
		}

		$this->assign("param",$paramStr);
		$this->assign("total",$page['record_total']);
		$this->assign("url",$url);
		$this->assign("pnum",$pnum);
		$this->assign("page",$page);
		$this->display('Page:page');
	}
}