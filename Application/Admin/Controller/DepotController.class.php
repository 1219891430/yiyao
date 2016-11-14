<?php

/*******************************************************************
 ** 文件名称: DepotController.class.php
 ** 功能描述: 系统后台仓库管理控制器
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
*******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class DepotController extends BaseController {

    var $_mod_depot;

    public function __construct(){
        parent::__construct();
        $this->_mod_depot = new \Common\Model\DepotModel();
    }

	// 仓库管理
	// 创建人员:
	// 创建日期:
	public function indexAction()
	{
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);
        $where = array();
		$where['repertory_parent'] = 0;

		// 内勤人员仓库所属
		if($this->_depot_id > 0) {
		    $where["repertory_id"] = $this->_depot_id;
        }

        //列表数据
        $total = $this->_mod_depot->table("zdb_depot_info as ci")->where($where)->count("repertory_id");
        $list = $this->_mod_depot->table("zdb_depot_info as ci")->where($where)->order('repertory_id desc')->page($p,$pnum)->select();
		foreach($list as $key=>$value)
		{
			$list[$key]['depot_list'] = M('depot_info')->where("repertory_parent = " . $value['repertory_id'])->order('repertory_id asc')->select();
		}

        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Depot/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示
        $this->assign('pnum',$pnum);
        $this->assign('p',$p);
        $this->assign('depotID',$this->_depot_id);

		$this->display();
    }
    // 仓库-添加
    public function addDepotAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        if(IS_POST){
            $data=array();
            $data['repertory_code'] = create_uniqid_code("CK");
			$data['repertory_name'] = I('repertory_name');
            $data['repertory_parent'] = I('repertory_parent');
            $data['repertory_info'] = I('repertory_info');
            $data['repertory_address'] = I('repertory_address');
            $data['repertory_user'] = I('repertory_user');
            $data['repertory_tel'] = I('repertory_tel');
            $data['repertory_close'] = 0;

            //权限判断
            if($this->_depot_id>0){
                if($data['repertory_parent'] == 0){
                    alertToUrl('非法操作', $jump_url);
                    return;
                }
                if($data['repertory_parent']>0 && $data['repertory_parent'] != $this->_depot_id) {
                    alertToUrl('非法操作', $jump_url);
                    return;
                }
            }

            //是否重复
            if($this->_mod_depot->checkDepotData(0,'repertory_name',$data['repertory_name'])){
                alertToUrl('已存在仓库名称信息，请查询',$jump_url);
                return;
            }

            $res = $this->_mod_depot->addDepot($data);
            if($res){
                alertToUrl('添加成功',$jump_url);
            }
            else{
                alertToUrl('添加失败',$jump_url);
            }
        }
        else{
			// 内勤人员仓库所属
			$depost_list = queryDepot($this->_depot_id);
			$this->assign('depost_list', $depost_list);
			$this->assign('depotID', $this->_depot_id);
            $this->display('depot_add');
        }
    }
    // 仓库-编辑
    public function editDepotAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        $id = I('repertory_id');
        if(empty($id)){
            alertToUrl('参数错误',$jump_url);
            return;
        }

        if(IS_POST){
            $data=array();
			$data['repertory_parent'] = I('repertory_parent');
            $data['repertory_info'] = I('repertory_info');
            $data['repertory_address'] = I('repertory_address');
            $data['repertory_user'] = I('repertory_user');
            $data['repertory_tel'] = I('repertory_tel');
            $data['repertory_close'] = I('repertory_close');

            //权限判断
            if($this->_depot_id>0){
                if($data['repertory_parent'] == 0){
                    alertToUrl('非法操作', $jump_url);
                    return;
                }
                if($data['repertory_parent']>0 && $data['repertory_parent'] != $this->_depot_id) {
                    alertToUrl('非法操作', $jump_url);
                    return;
                }
            }

            //判断重复
            /*
            if($this->_mod_depot->checkDepotData($id,'repertory_code',$data['repertory_code'])){
                alertToUrl('已存在仓库编码信息，请查询',$jump_url);
                return;
            }
            if($this->_mod_depot->checkDepotData($id,'repertory_name',$data['repertory_name'])){
                alertToUrl('已存在仓库名称信息，请查询',$jump_url);
                return;
            }
            */

            $res = $this->_mod_depot->editDepot($id,$data);
            if($res){
                alertToUrl('修改成功',$jump_url);
            }
            else{
                alertToUrl('修改失败',$jump_url);
            }

        }
        else{
			$depost_list = queryDepot($this->_depot_id);
			$this->assign('depost_list', $depost_list);
            $data = $this->_mod_depot->find($id);
            $this->assign('data',$data);
            $this->assign('depotID', $this->_depot_id);
            $this->display('depot_edit');
        }
    }
    // 仓库-删除
    public function delDepotAction(){
        $id = I('repertory_id');
        if(empty($id)){
            echo 0;
            return;
        }
        $where = array("repertory_id"=>$id);

        $depot_info = $this->_mod_depot->where($where)->find();

        if(!$depot_info){
            echo 0;
            return;
        }

        //判断权限
        if($this->_depot_id>0){
            if($id == $this->_depot_id){
                echo 0;
                return;
            }

            if($depot_info['repertory_parent']>0 && $depot_info['repertory_parent'] != $this->_depot_id){
                echo 0;
                return;
            }
        }

        // 查询是否有库存
        $has = M('depot_stock')
            ->where("depot_id=$id AND small_stock>0")
            ->count();



        if ($has > 0) {
            echo 2;
            return;
        }


        $res = $this->_mod_depot->where($where)->delete();

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    // 仓库编码是否存在
    public function checkDepotDataAction(){
        $id = I('repertory_id');
        $val = I('val');
        $col = I('col');

        $res = $this->_mod_depot->checkDepotData($id,$col,$val);
        if($res){
            echo 'false';
        }
        else{
            echo 'true';
        }
    }
    //仓库名称是否存在


	// 仓库品牌
	public function brandAction()
	{
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);
        $query['repertory_id'] = I('get.repertory_id');

        $this->assign('query',$query);

        if($this->_depot_id > 0) {
            $where['db.repertory_id'] = $this->_depot_id;
        }
        else {
                if (!empty($query['repertory_id'])) {
                    $where['db.repertory_id'] = array("eq", $query['repertory_id']);
                }
        }

        //列表数据
        $total = M('depot_brand')->table("zdb_depot_brand as db")->where($where)->count();//总数
        $list = M('goods_brand')->field("gb.brand_id,gb.brand_name,gb.remark,db.is_close, db.repertory_id")
            ->table("zdb_goods_brand as gb")
            ->join("inner join zdb_depot_brand db on gb.brand_id = db.brand_id ")
            ->page($p,$pnum)
            -> where($where)
            ->order('gb.brand_id desc')->select();


        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Depot/brand',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->assign('pnum',$pnum);
        $this->assign('p',$p);


		// 内勤人员仓库所属
		$depost_list = queryDepot($this->_depot_id);
		$this->assign('depotList', $depost_list);

		$this->display();
	}
    // 仓库品牌-添加
	public function addBrandAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        /*
        $id = I('repertory_id');
        $name = I('repertory_name');
        if(empty($id)){
            alertToUrl('参数错误',$jump_url);
            return;
        }
        */

        if(IS_POST){
            $ids = I('post.brand_id');
            if(empty($ids)){
                alertToUrl('参数错误',$jump_url);
                return;
            }

            $arr = $ids;
            $data = array();
            foreach($arr as $a){
                $row = array();
                $row['repertory_id'] = $id;
                $row['brand_id'] = $a;
                $row['is_close'] =0;
                //权限判断
                if($this->_depot_id>0){
                    $row['repertory_id'] = $this->_depot_id;
                }
                $data[] = $row;
            }
            $res = M('depot_brand')->addAll($data);

            if($res){
                alertToUrl('添加成功', $jump_url);
            }
            else{
                alertToUrl('添加失败', $jump_url);
            }

        }
        else{
            $depotList = queryDepot($this->_depot_id);
            $this->assign('depotList',$depotList);
            $this->assign('depotID',$this->_depot_id);

            $this->display('brand_add');
        }
    }

    public function getBrandListAction(){
        $brand_name = I('brand_name');
        $repertory_id = I('repertory_id');

        if(!empty($brand_name)) {
            $where['brand_name'] = array("like", "%{$brand_name}%");
        }

        //权限判断
        if($this->_depot_id>0){
            $repertory_id = $this->_depot_id;
        }

        $where['gb.is_close'] =0;

        $list = M('goods_brand')->field("gb.brand_id,gb.brand_name,gb.remark,gb.is_close, db.repertory_id")->
        table("zdb_goods_brand as gb")->
        join("left join zdb_depot_brand db on gb.brand_id = db.brand_id  AND db.repertory_id = ".$repertory_id)->
        where($where)->order('gb.brand_id desc')->select();

        $res = array(
           'status'=>true,
            'rows'=>$list
        );
        echo $this->ajaxReturn($res,"json");

    }

    // 仓库品牌-删除
    public function delBrandAction(){

        $brand_id = I('brand_id');
        $depot_id = I('depot_id');

        if(empty($brand_id)){
            echo 0;
            return;
        }

        //权限检查
        if($this->_depot_id>0){
            $depot_id = $this->_depot_id;
        }

        // 检查仓库库存下该品类是否存在
        $has = M('depot_stock')->alias("ds")
            ->join('left join __GOODS_INFO__ as gi on ds.goods_id=gi.goods_id')
            ->where("gi.brand_id=$brand_id AND ds.depot_id=$depot_id")
            ->count();

        if ($has > 0) {
            echo 3;
            die();
        }

        $where["brand_id"] = $brand_id;
        $where["repertory_id"] = $depot_id;
        $res = M('depot_brand')->where($where)->delete();

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }
    // 仓库品牌-状态
    public function setBrandDataAction(){
        $col = I('col');
        $val = I('val');
        $id = I('id');

        $where['brand_id'] = $id;
        $data[$col] = $val;

        $brand_info = M('depot_brand')->where($where)->find();
        if(!$brand_info){
            echo 0;
            return;
        }
        //权限判断
        if($this->_depot_id>0){
            if($brand_info['repertory_id'] != $this->_depot_id){
                echo 0;
                return;
            }
        }

        $result= M('depot_brand')->where($where)->save($data);
        echo $result==true ?1:0;
    }

    // 仓库品类
	public function categoryAction()
    {
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);
        $query['repertory_id'] = I('get.repertory_id');

        if($this->_depot_id > 0) {
            $where['repertory_id'] = $this->_depot_id;
        }
        else {
            if (!empty($query['repertory_id'])) {
                $where['repertory_id'] = array("eq", $query['repertory_id']);
            }
        }
        $where["parent_class"]=0;

        $list = M('goods_class')->alias('gc')
            ->join('__DEPOT_CLASS__ as dc on gc.class_id = dc.class_id')
            ->field("gc.class_id,gc.class_name,gc.remark,dc.is_close, dc.repertory_id")
            ->where($where)
			
            ->order('gc.class_id desc')->select();
        
		foreach($list as $k=>$v){
			$where["parent_class"]=$v["class_id"];
			$list[$k]["class_list"]=M('goods_class')->alias('gc')
            ->join('__DEPOT_CLASS__ as dc on gc.class_id = dc.class_id')
            ->field("gc.class_id,gc.class_name,gc.remark,dc.is_close, dc.repertory_id")
            ->where($where)
            ->select();
			
			
		}

        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Depot/category',$query) );


        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->assign('pnum',$pnum);
        $this->assign('p',$p);

		// 内勤人员仓库所属
		$depost_list = queryDepot($this->_depot_id);
		$this->assign('depotList', $depost_list);

		$this->display();
	}
    // 仓库品类-添加
    public function addCategoryAction(){

        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        /*
        $id = I('repertory_id');
        $name = I('repertory_name');
        if(empty($id)){
            alertToUrl('参数错误', $jump_url);
            return;
        }
        */

        if (IS_POST){
            $ids = I('post.class_id');
            if(empty($ids)){
                alertToUrl('参数错误', $jump_url);
                return;
            }

            $arr = $ids;
            $data = array();
            foreach($arr as $a){
                $row = array();
                $row['repertory_id'] = I('depot_id');
                $row['class_id'] = $a;
                $row['is_close'] =0;
                //权限判断
                if($this->_depot_id>0){
                    $row['repertory_id'] = $this->_depot_id;
                }
                $data[] = $row;
            }
            $res = M('depot_class')->addAll($data);

            if($res){
                alertToUrl('添加成功', $jump_url);
            }
            else{
                alertToUrl('添加失败', $jump_url);
            }

        }
        else{
            $depotList = queryDepot($this->_depot_id);
            $this->assign('depotList',$depotList);
            $this->assign('depotID',$this->_depot_id);
            $this->display('category_add');
        }
    }

    public function getCategoryListAction(){
        $cate_name = I('cate_name',"");
        $repertory_id = I('repertory_id',1);

        if(!empty($cate_name)) {
            $where['class_name'] = array("like", "%{$cate_name}%");
        }

        //权限判断
        if($this->_depot_id>0){
            $repertory_id = $this->_depot_id;
        }

        $where['gc.is_close'] =0;
		$where['gc.parent_class']=0;

        $list = M('goods_class')->alias('gc')
            ->join('left join __DEPOT_CLASS__ as dc on gc.class_id = dc.class_id AND dc.repertory_id = '.$repertory_id)
            ->field("gc.class_id,gc.class_name,gc.remark,dc.is_close, dc.repertory_id,gc.parent_class")
            ->where($where)->order('gc.class_id desc')->select();
			
		
		foreach($list as $k=>$v){
			//$list[$k]["class_list"]=M("goods_class")->where('parent_class = ' . intval($v['class_id']))->select();
			$list[$k]["class_list"]=M('goods_class')->alias('gc')
            ->join('left join __DEPOT_CLASS__ as dc on gc.class_id = dc.class_id AND dc.repertory_id = '.$repertory_id)
            ->field("gc.class_id,gc.class_name,gc.remark,dc.is_close, dc.repertory_id,gc.parent_class")
            ->where('gc.is_close=0 AND parent_class = ' . intval($v['class_id']))->order('gc.class_id desc')->select();
		}
        

        $res = array(
            'status'=>true,
            'rows'=>$list
        );
        echo $this->ajaxReturn($res,"json");

    }

    // 仓库品类-状态
    public function setCateDataAction() {
        $col = I('col');
        $val = I('val');
        $id = I('id');

        $where['class_id'] = $id;
        $data[$col] = $val;

        $class_info = M('depot_class')->where($where)->find();
        if(!$class_info){
            echo 0;
            return;
        }

        //判断权限
        if($this->_depot_id>0){
            if($class_info['repertory_id'] != $this->_depot_id){
                echo 0;
                return;
            }
        }

        $result= M('depot_class')->where($where)->save($data);
        echo $result==true ?1:0;
    }

    // 仓库品类-删除
    public function delCategoryAction(){

        $class_id = I('class_id');
        $depot_id = I('depot_id');

        if(empty($class_id)){
            echo 0;
            return;
        }

        //权限检查
        if($this->_depot_id>0){
            $depot_id = $this->_depot_id;
        }

        // 检查仓库库存下该品类是否存在
        $has = M('depot_stock')->alias("ds")
            ->join('left join __GOODS_INFO__ as gi on ds.goods_id=gi.goods_id')
            ->where("gi.class_id=$class_id AND ds.depot_id=$depot_id")
            ->count();

        if ($has > 0) {
            echo 3;
            die();
        }
        $parent_class=M("goods_class")->where("class_id=$class_id")->getField("parent_class");
		if($parent_class==0){
			$list=M("goods_class")->where("parent_class=$class_id")->getField("class_id",true);
		    $class_ids=array();
			foreach($list as $v){
				$class_ids[]=$v["class_id"];
			}
			$where["class_id"]=array("in",$class_ids);
			$res1=M("depot_class")->where($where)->count();
			if($res1){
				echo 4;
				return;
			}else{
				$where["class_id"] = $class_id;
        		$where["repertory_id"] = $depot_id;
        		$res = M('depot_class')->where($where)->delete();
			}
		}else{
			$where["class_id"] = $class_id;
        	$where["repertory_id"] = $depot_id;
        	$res = M('depot_class')->where($where)->delete();
		}
        

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    // 经销商列表
	public function dealerAction(){
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);
        $query['depot_id'] = I('get.depot_id');
        $query['org_id'] = I('get.org_id');

        // 经销商列表
        //$this->assign('orglist', queryDealer());

		// 内勤人员仓库所属
		$depost_list = queryDepot($this->_depot_id);
		$this->assign('depot', $depost_list);

        $this->assign('query', $query);
        // 查询
        $where = array();
        if (!empty($query['org_id']) ) { //经销商名称
            $where['do.org_parent_id'] = $query['org_id'];
        }

        if($this->_depot_id>0){
            $where['do.repertory_id'] = $this->_depot_id ;
        }
        else {
            if (!empty($query["depot_id"])) { //经销商名称
                $where['do.repertory_id'] = $query['depot_id'];
            }
        }

        $total = M('depot_org')->alias('do')->field('d.*, o.org_name, o.org_id')
            ->join('__DEPOT_INFO__ as d on do.repertory_id = d.repertory_id')
            ->join('__ORG_INFO__ as o on do.org_parent_id = o.org_id')
            ->where($where)->count();


        $dolist = M('depot_org')->alias('do')->field('d.*, o.org_name, o.org_id')
            ->join('__DEPOT_INFO__ as d on do.repertory_id = d.repertory_id')
            ->join('__ORG_INFO__ as o on do.org_parent_id = o.org_id')
            ->where($where)
            ->page($p,$pnum)
            ->order('d.repertory_id asc')
            ->select();

        $this->assign('dolist', $dolist);


        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Depot/dealer',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);

        $this->assign('pagelist',$page);//分页显示

        $this->assign('pnum',$pnum);
        $this->assign('p',$p);

        $this->display('Depot:dealer');
	}
    // 经销商-添加
    public function addDealerAction()
    {
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        if (IS_POST) {
            $data["org_parent_id"] = $_POST["org"];
            $data["repertory_id"] = $_POST['depot'];
            //权限检查
            if($this->_depot_id>0){
                $data['repertory_id'] = $this->_depot_id;
            }

            // 检测是否重复
            $w = array(
                "org_parent_id" => $data["org_parent_id"],
                "repertory_id" => $data["repertory_id"],
            );
            $has = M("depot_org")->where($w)->count();
            if ($has > 0) {
                alertToUrl('重复添加',$jump_url);
                return;
            }

            $succ = M("depot_org")->data($data)->add();
            if ($succ) {
                alertToUrl('添加成功',$jump_url);
            } else {
                alertToUrl('添加失败',$jump_url);
            }
        }
        else {
            // 内勤人员仓库所属
            $depost_list = queryDepot($this->_depot_id);
            $this->assign('depot', $depost_list);
            $this->assign('depotID',$this->_depot_id);

            // 经销商列表
            $org_list = queryDealer($this->_depot_id);
            $this->assign('orglist',$org_list);

            $this->display();
        }
    }
    // 经销商-编辑
    public function editDealerAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        // 写入数据库
        if (IS_POST) {
            M("depot_org")->create();
            if($_POST['is_close'] == "on"){
                $_POST['is_close'] = 1;
            }else{
                $_POST['is_close'] = 0;
            }

            $where = array("org_parent_id"=>I("old_org_id"), "repertory_id"=>I("old_depot_id"));
            if (I('org') <= 0) {
                alertToUrl('经销商不能为空',$jump_url);
                return;
            }
            //权限检查
            if (I('depot') <= 0) {
                alertToUrl('仓库不能为空',$jump_url);
                return;
            }

            $data["org_parent_id"] = I("org");
            $data["repertory_id"] = I("depot");
            //权限检查
            if($this->_depot_id>0){
                $data['repertory_id'] = $this->_depot_id;
            }
            $succ = M("depot_org")->where($where)->data($data)->save();
            //echo  M("org_info")->getLastSql();die();
            //$succ = M('org_info')->save($_POST, $where);

            if ($succ) {
                alertToUrl('修改成功',$jump_url);
            }
            else {
                alertToUrl('修改失败',$jump_url);
            }
        }
        else{
            //$org_id = I('org_id');
            //$org = M('org_info')->where('org_id = ' . $org_id)->find();
            $this->assign('orglist', queryDealer($this->_depot_id));

            //$depot_id = I('depot_id');
            //$depot = M('depot_info')->where('repertory_id = ' . $depot_id)->find();
            $this->assign('depot', queryDepot($this->_depot_id));
            $this->display();
        }
    }
    // 经销商-删除
    public function delDealerAction(){

        $org_id = I('org_id');
        $depot_id = I('depot_id');

        //权限检查
        if($this->_depot_id>0){
            $depot_id = $this->_depot_id;
        }

        // 检查经销商在此仓库下有无商品
        $has = M('depot_stock')->where("depot_id = $depot_id AND org_parent_id = $org_id")->count();

        if ($has > 0) {
            echo 3;
            return;
        }

        if (!empty($org_id) && !empty($depot_id)) {
            $where = array("org_parent_id"=>$org_id, "repertory_id" => $depot_id);
            M("depot_org")->where($where)->delete();
            echo 1;
        }
        else {
            echo 0;
        }
    }
	/** 其他Action **/

}

/*************************** end ************************************/