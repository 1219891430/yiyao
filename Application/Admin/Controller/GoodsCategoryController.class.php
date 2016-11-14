<?php

/*******************************************************************
 ** 文件名称: GoodsCategoryController.class.php
 ** 功能描述: 系统后台品类管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;


class GoodsCategoryController extends BaseController {

	// 控制器默认页
	// 创建人员: 
	// 创建日期:
	
	public function __construct(){
		parent::__construct();
		
	}
	public function indexAction()
	{
		$p = I("get.p",1);
    	$pnum=I("get.pnum",10);
		$where['parent_class'] = 0;
		$list=M("goods_class")->where($where)->page($p,$pnum)->select();
		$total=M("goods_class")->where($where)->count();
		foreach($list as $key=>$value)
		{
			$list[$key]['class_list'] = M('goods_class')->where('parent_class = ' . intval($value['class_id']))->select();
		}
		
        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign("pagelist",$page);
        $this->assign("pnum",$pnum);
        $this->assign("total",$total);
		$this->assign("depot_id",$this->_depot_id);
		$this->assign("list",$list);
		$this->display();
    }
	
	public function addAction(){
		
		$where['parent_class'] = 0;
		$list=M("goods_class")->where($where)->page($p,$pnum)->select();
		$this->assign("list",$list);
		$this->display();
	}
	
	public function addexAction(){
		$depot_id=session("depot_id");
		$data["class_name"]=I("post.class_name");
		$data["parent_class"]=I('post.parent_class');
		$data["remark"]=I("post.remark");
		if($depot_id){
			$data["is_close"]=1;
		}else{
			$data["is_close"]=0;
		}
		

        //是否重复
        if( D("GoodsClass")->isCheckName(0,$data['class_name'])){
            $aReturn=array("res"=>0,"info"=>"已存在品类名称，请重新输入");
            echo json_encode($aReturn);
            return;
        }

		$res = D("GoodsClass")->add($data);
		if($res){
            $aReturn=array("res"=>1,"info"=>"添加成功");   
		}else{
			$aReturn=array("res"=>0,"info"=>"添加失败");
		}
		echo json_encode($aReturn);
		
	}
		
	public function editAction(){
		$id=I("get.id");
		$where["class_id"]=$id;
		$res=D("GoodsClass")->where($where)->find();
		
		$this->assign("res",$res);
		$this->assign("id",$id);
		$this->display();
	}
	
	public function editexAction(){
		$id=I("post.id");
		$where["class_id"]=$id;
		if($this->_depot_id){
			$res=M("goods_class")->where($where)->find();
			if($res["is_close"]==0){
				$aReturn=array("res"=>0,"info"=>"非法操作");
				echo json_encode($aReturn);
				return;
			}
		}
		
		
		
		$data["class_name"]=I("post.class_name");
		$data["remark"]=I("post.remark");
		

        //是否重复
        if( D("GoodsClass")->isCheckName($id,$data['class_name'])){
            $aReturn=array("res"=>0,"info"=>"已存在品类名称，请重新输入");
            echo json_encode($aReturn);
            return;
        }

		$res=D("goods_class")->where($where)->save($data);
		
		if($res){
            $aReturn=array("res"=>1,"info"=>"修改成功");   
		}else{
			$aReturn=array("res"=>0,"info"=>"修改失败");
		}
		echo json_encode($aReturn);
		
	}
	
	
	public function setPassAction(){
		if($this->_depot_id){
    		//临时放开 仓库管理的权限
    		//$aReturn=array("res"=>0,"info"=>"非法操作");
			//echo json_encode($aReturn); 
    		//return;
		}
		$class_id=I("post.class_id",1);
		$data["is_close"]=0;
		$where["class_id"]=$class_id;
		$res=M("goods_class")->where($where)->save($data);
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
		$class_id=I("post.class_id",1);
		$data["is_close"]=1;
		$where["class_id"]=$class_id;
		$res=M("goods_class")->where($where)->save($data);
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

	public function delAction(){
		if($this->_depot_id){
    		$aReturn=array("res"=>0,"info"=>"非法操作");
			echo json_encode($aReturn); 
    		return;
		}
		$class_id=I("post.cid");


        $res=D("GoodsClass")->deleteClass($class_id);


		if($res['status']){
			$aReturn=array("res"=>1,"info"=>"删除成功");
		}else{
			$aReturn=array("res"=>0,"info"=>"删除失败, " . $res['msg']);
		}
        echo json_encode($aReturn);
	}

}

/*************************** end ************************************/