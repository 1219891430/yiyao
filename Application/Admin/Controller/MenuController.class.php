<?php

/*******************************************************************
 ** 文件名称: AreaController.class.php
 ** 功能描述: 系统后台仓库区域
 ** 创建人员: wangbo
 ** 创建日期: 2016-09-02
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class MenuController extends BaseController {


    public function indexAction(){
    	
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	
    	$pnum=I("pnum",10);
    	$p=I("p",1);
    	$list=M("menu")->page($p,$pnum)->select();
    	$total=M("menu")->count();
    	$page = get_page_code($total, $pnum, $p, $page_code_len = 5);
    	
    	
    	foreach($list as &$v){
    		$v["ptitle"]=$this->getPtitle($v["pid"]);
    		
    	}
       
        $this->assign('pagelist',$page);//分页显示

        $this->assign('p', $p);
        $this->assign('pnum', $pnum);
    	$this->assign("list",$list);
    	$this->display();
    }
    
    public function addAction(){
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	$where["pid"]=0;
        $pmenuList=M("menu")->where($where)->select();
        foreach($pmenuList as $k=>$v){
        	$where["pid"]=$v["id"];
        	$menuList=M("menu")->where($where)->select();
        	$pmenuList[$k]["submenu"]=$menuList;
        }
        $this->assign("pmenuList",$pmenuList);
    	
    	$this->display();
    }
    
    public function editAction(){
    	
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	$id=I("get.id");
    	$where["id"]=$id;
    	$res=M("menu")->where($where)->find();
    	$this->assign("res",$res);
    	
    	$where1["pid"]=0;
    	$where1["id"]=array("neq",$id);
        $pmenuList=M("menu")->where($where1)->select();
        foreach($pmenuList as $k=>$v){
        	$where1["pid"]=$v["id"];
        	$menuList=M("menu")->where($where1)->select();
        	$pmenuList[$k]["submenu"]=$menuList;
        }
        $this->assign("pmenuList",$pmenuList);
    	
    	$this->display();
    }
    
    public function addexAction(){
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	
    	$_POST["a"]=$_POST["a1"];
    	$res=M("menu")->add($_POST);
        if($res){
        	$this->setCacheMenu();
        	echo json_encode(array("res"=>1,"info"=>"添加成功"));
        }else{
        	echo json_encode(array("res"=>1,"info"=>"添加失败"));
        }
        
        
    }
    
    
    public function editexAction(){
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	$id=I("id");
    	$where["id"]=$id;
    	$_POST["a"]=$_POST["a1"];
        unset($_POST['a1']);
        unset($_POST['id']);
    	$res=M("menu")->where($where)->save($_POST);
        if($res){
        	$this->setCacheMenu();
        	echo json_encode(array("res"=>1,"info"=>"修改成功"));
        }else{
        	echo json_encode(array("res"=>1,"info"=>"修改失败"));
        }
        
        
    }
    
    public function delAction(){
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	$where["id"]=I("post.id");
    	$res=M("menu")->where($where)->delete();
    	if($res){
    		$this->setCacheMenu();
    		echo json_encode(array("res"=>1,"info"=>"删除成功"));
    	}else{
    		echo json_encode(array("res"=>0,"info"=>"删除失败"));
    	}
    }
    
    private function setCacheMenu(){
    	$where["status"]=1;
    	$list=M("menu")->where($where)->select();
    	S("menu",$list);
    }    
    
    private function getPtitle($pid){
    	$depot_id=session("depot_id");
    	if($depot_id!=0){
    		return;
    	}
    	if($pid){
    		$where["id"]=$pid;
    		$ptitle=M("menu")->where($where)->getField("title");
    	}else{
    		$ptitle="顶级菜单";
    	}
    	
    	return $ptitle;
    }
}