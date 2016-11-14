<?php

/*******************************************************************
 ** 文件名称: BaseController.class.php
 ** 功能描述: 系统后台控制器基类
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-31
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller
{

    var $_depot_id;
    var $_depot_list;
    var $_depot_parent;

    public function __construct(){
        parent::__construct();

        if( empty($_SESSION['admin_id']) ){
            $this->redirect('Index/index');
            return;
        }
        
        $path=explode("/",$_SERVER['PATH_INFO']);
        
        $controller=$path[0];
        if($path[1]){
        	$action=$path[1];
        }else{
        	$action="index";
        }
        
        
        $menus=S("menu");
        $ischecked=false;
        foreach($menus as $menu){
        	if($controller==$menu["m"]&&$action==$menu["a"]){
        		$ischecked=true;
        		break;
        	}
        }
        
        if($ischecked){
        	$ischecked=false;
        	foreach($_SESSION["menu"] as $v){
        		foreach($v["subclass"] as $vv){
        			if($controller==$vv["controller"]&&$action==$vv["action"]){
        				$ischecked=true;
        				break;
        			}
        		}
        	}
        	if(!$ischecked){
        		$this->redirect('Index/index');
           	 	return;
        	}
        }
        
        
        
        
        
        
        $this->_depot_id = intval($_SESSION['depot_id']);
        $this->_depot_list = $_SESSION['depot_list'];
        $this->_depot_parent = $_SESSION['depot_parent'];

    }

}