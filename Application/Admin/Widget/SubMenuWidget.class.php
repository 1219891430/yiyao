<?php
namespace Dealer\Widget;
use Think\Controller;
class SubMenuWidget extends Controller
{
	
	
	public function subMenu($data=array(),$actiItem)
	{
		$this->assign("data",$data);
		$this->assign("actiItem",$actiItem);			
		$this->display('Public:sub_menu');
	}
	
	
	
	public function subMenudata(){
		
	}
}