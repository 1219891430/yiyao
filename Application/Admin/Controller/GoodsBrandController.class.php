<?php

/*******************************************************************
 ** 文件名称: GoodsBrandController.class.php
 ** 功能描述: 系统后台品牌管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;


class GoodsBrandController extends BaseController {
	
    public function __construct()
    {
        parent::__construct();
	}
	// 控制器默认页
	// 创建人员: 
	// 创建日期: 
	public function indexAction()
	{
		
		$p = I("get.p",1);
    	$pnum=I("get.pnum",10);
		$list=D("GoodsBrand")->page($p,$pnum)->select();
		$total=D("GoodsBrand")->count(); 
        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign("pagelist",$page);
        $this->assign("pnum",$pnum);
        $this->assign("total",$total);
		$this->assign("depot_id",$this->_depot_id);
		
		$this->assign("list",$list);
		$this->display();
    }
	
	/*
	 * 
	 */
	public function addexAction(){

        if(!empty($_FILES['logo'][name])) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Public/Uploads/brand/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload($_FILES);
            $logo_img = $info['logo']['savepath'].$info['logo']['savename'];
            $data["brand_logo"]=$logo_img;
        }

        $depot_id=session("depot_id");
        
		$data["brand_name"]=I("post.brand_name");
		$data["remark"]=I("post.remark");
		
		if($depot_id){
			$data["is_close"]=1;
		}else{
			$data["is_close"]=0;
		}
		

        //是否重复
        if( D("GoodsBrand")->isCheckName(0,$data['brand_name'])){
            alertToUrl('已存在品牌名称，请重新输入', U('/Admin/GoodsBrand/index'));
            return;
        }


		$res=D("GoodsBrand")->add($data);

        if ($res) {
            //aJsonReturn("1","创建成功");
            alertToUrl('添加成功', U('/Admin/GoodsBrand/index'));
        } else {
            //aJsonReturn("0","添加成功");
            alertToUrl('添加失败', U('/Admin/GoodsBrand/index'));
        }
	}
	
	public function delAction(){
		if($this->_depot_id){
    		$aReturn=array("res"=>0,"info"=>"非法操作");
			echo json_encode($aReturn); 
    		return;
		}
		
		$brand_id=I("post.bid");

		$res=D("GoodsBrand")->deleteBrand($brand_id);

        if($res['status']){
            $aReturn=array("res"=>1,"info"=>"删除成功");
        }else{
            $aReturn=array("res"=>0,"info"=>"删除失败, " . $res['msg']);
        }
        echo json_encode($aReturn);
	}
	
	public function editAction(){
		$id=I("get.id");
		$where["brand_id"]=$id;
		$res=D("GoodsBrand")->where($where)->find();
		
		$this->assign("res",$res);
		$this->assign("id",$id);
		$this->display();
	}
	
	public function editexAction(){

		$id=I("post.id");
		
		
		
		$where["brand_id"]=$id;
		/*if($this->_depot_id){
			$res=M("goods_brand")->where($where)->find();
			if($res["is_close"]==0){
				$aReturn=array("res"=>0,"info"=>"非法操作");
				echo json_encode($aReturn);
				return;
			}
		}*/

        if(!empty($_FILES['logo'][name])) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Public/Uploads/brand/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload($_FILES);

            $logo_img = $info['logo']['savepath'].$info['logo']['savename'];

            $data["brand_logo"]=$logo_img;
        }



		
		$data["brand_name"]=I("post.brand_name");
		$data["remark"]=I("post.remark");
		
		$where["brand_id"]=$id;

        //是否重复
        if( D("GoodsBrand")->isCheckName($id,$data['brand_name'])){
            alertToUrl('已存在品牌名称，请重新输入', U('/Admin/GoodsBrand/index'));
            return;
        }

		$res=D("goods_brand")->where($where)->save($data);

        if ($res) {
            alertToUrl('修改成功', U('/Admin/GoodsBrand/index'));
        } else {
            alertToUrl('修改失败', U('/Admin/GoodsBrand/index'));
        }
		
	}
	public function setPassAction(){
		if($this->_depot_id){
    		//临时放开 仓库管理的权限
    		//$aReturn=array("res"=>0,"info"=>"非法操作");
			//echo json_encode($aReturn); 
    		//return;
		}
		$brand_id=I("post.brand_id",1);
		$data["is_close"]=0;
		$where["brand_id"]=$brand_id;
		$res=M("goods_brand")->where($where)->save($data);
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
		
		
		$brand_id=I("post.brand_id",1);
		$data["is_close"]=1;
		$where["brand_id"]=$brand_id;
		$res=M("goods_brand")->where($where)->save($data);
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


}

/*************************** end ************************************/