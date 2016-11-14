<?php

/*******************************************************************
 ** 文件名称: AreaController.class.php
 ** 功能描述: 系统后台仓库区域
 ** 创建人员: wangbo
 ** 创建日期: 2016-09-02
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class AreaController extends BaseController {

    var $_mod_area;

    public function __construct(){
        parent::__construct();
        $this->_mod_area = M('depot_area');
    }

    // 控制器默认页
    // 创建人员:
    // 创建日期:
    public function indexAction()
    {
        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $query['depot_id'] = I('get.depot_id');

        $this->assign('query', $query);


        if($this->_depot_id > 0) {
            $where['depot_id'] = $this->_depot_id;
        }
        else {
            if (!empty($query['depot_id'])) {
                $where['depot_id'] = array("eq", $query['depot_id']);
            }
        }


        //列表数据
        $total = $this->_mod_area->where($where)->count("area_id");//总数
        $list = $this->_mod_area->where($where)->order('area_id desc')->page($p,$pnum)->select();//列表

        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Area/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->assign('pnum',$pnum);

        //仓库select
        $depost_list = queryDepot($this->_depot_id);
        $this->assign('depotList', $depost_list);


        $this->display();
    }

    public function addAction()
    {

        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }
        if(IS_POST){
            $data=array();

            $data['area_name'] = I('area_name');
            $data['area_note'] = I('area_note');
            $data['area_code'] = I('area_code');
            $data['depot_id'] = I('depot_id');

            //权限判断
            if($this->_depot_id>0){
                $data['depot_id'] = $this->_depot_id;
            }
            $res = $this->_mod_area->add($data);
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
            $this->display();
        }

    }

    public function editAction()
    {
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        $id = I('area_id');
        if(empty($id)){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        if(IS_POST){
            $data=array();

            $data['area_name'] = I('area_name');
            $data['area_note'] = I('area_note');
            $data['area_code'] = I('area_code');
            $data['depot_id'] = I('depot_id');

            //权限判断
            if($this->_depot_id>0){
                $data['depot_id'] = $this->_depot_id;
            }
            $res = $this->_mod_area->where('area_id='.$id)->save($data);
            if($res){
                alertToUrl('修改成功', $jump_url);
            }
            else{
                alertToUrl('修改失败', $jump_url);
            }
        }
        else {
            $data = $this->_mod_area->find($id);
            $this->assign('data',$data);

            $depotList = queryDepot($this->_depot_id);
            $this->assign('depotList', $depotList);
            $this->display();
        }
    }

    public function delAction(){
        $id = I('area_id');
        if(empty($id)){
            echo 0;
            return;
        }

        $where = array("area_id"=>$id);
        //判断是否有商品在这个区域，否则不允许删除
        $area_info = $this->_mod_area->where($where)->find();
        if(!$area_info){
            echo 0;
            return;
        }
        //权限判断
        if($this->_depot_id>0){
            if($area_info['depot_id'] != $this->_depot_id){
                echo 0;
                return;
            }
        }

        //开启事务删除关联表数据
        $res = $this->_mod_area->where($where)->delete();
        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }


    public function detailAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }


        $id = I('area_id');
        $where['area_id'] = $id;
        $area_info = $this->_mod_area->where($where)->find();

        if(!$area_info){
            alertToUrl('非法操作',$jump_url);
            return;
        }
        //权限判断
        if($this->_depot_id>0){
            if($area_info['depot_id'] != $this->_depot_id){
                alertToUrl('非法操作',$jump_url);
                return;
            }
        }
        $where = array();
		$where["zdb_depot_area_goods.area_id"]=$id;
        $list=M("depot_area_goods")->field("goods_code,goods_name,goods_spec")->join("zdb_goods_info on zdb_goods_info.goods_id=zdb_depot_area_goods.goods_id")->where($where)->select();
		$this->assign("list",$list);
        $this->display();

    }
}

/*************************** end ************************************/