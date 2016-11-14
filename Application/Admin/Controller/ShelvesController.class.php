<?php

/*******************************************************************
 ** 文件名称: ShelvesController.class.php
 ** 功能描述: 系统后台仓库库位管理
 ** 创建人员: richie
 ** 创建日期: 2016-08-02
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class ShelvesController extends BaseController {

    var $_mod_shelves;
    var $_mod_shelves_details;

    public function __construct(){
        parent::__construct();
        $this->_mod_shelves = M('depot_shelves');
        $this->_mod_shelves_details = M('depot_shelves_details');
    }

    // 控制器默认页
    // 创建人员:
    // 创建日期:
    public function indexAction()
    {
        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        //dump($depotID);die;

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $query['depot_id'] = I('get.depot_id');

        $this->assign('query', $query);


        if($depotID > 0) {
            $where['depot_id'] = $depotID;
        }
        else {
            if (!empty($query['depot_id'])) {
                $where['depot_id'] = array("eq", $query['depot_id']);
            }
        }


        //列表数据
        $total = $this->_mod_shelves->where($where)->count("shelves_id");//总数
        $list = $this->_mod_shelves->where($where)->order('shelves_id desc')->page($p,$pnum)->select();//列表

        //当前完整url
        $query['p'] = $p;
        $query['pnum'] = $pnum;
        session('jump_url', U('Admin/Shelves/index',$query) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 5);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $this->assign('pnum',$pnum);

        //仓库select
        $depost_list = queryDepot($depotID);
        $this->assign('depotList', $depost_list);


        $this->display();
    }

    public function addAction(){
        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }
        if(IS_POST){
            $data=array();

            $data['shelves_name'] = I('shelves_name');
            $data['shelves_note'] = I('shelves_note');
            $data['shelves_y'] = I('shelves_y');
            $data['shelves_x'] = I('shelves_x');
            $data['depot_id'] = I('depot_id');

            $res = $this->_mod_shelves->add($data);
            if($res){
                //添加库位表数据
                $dataList = array();
                for($i=1;$i<=$data['shelves_y'];$i++){
                    for($j=1;$j<=$data['shelves_x'];$j++){
                        $row = array(
                          'shelves_id' => $res,
                            'display_x' => $j,
                            'display_y' => $i,
                            'display_no' => ($i*10) + $j
                        );
                        $dataList[] = $row;
                    }
                }

                $this->_mod_shelves_details->addAll($dataList);

                alertToUrl('添加成功', $jump_url);
            }
            else{
                alertToUrl('添加失败', $jump_url);
            }
        }
        else{
            $depotList = queryDepot($depotID);
            $this->assign('depotList',$depotList);
            $this->display();
        }

    }

    public function editAction(){
        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);


        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        $id = I('shelves_id');
        if(empty($id)){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        if(IS_POST){
            $data=array();

            $data['shelves_name'] = I('shelves_name');
            $data['shelves_note'] = I('shelves_note');
            //$data['shelves_y'] = I('shelves_y');
            //$data['shelves_x'] = I('shelves_x');
            $data['depot_id'] = I('depot_id');

            $res = $this->_mod_shelves->where('shelves_id='.$id)->save($data);
            if($res){
                alertToUrl('修改成功', $jump_url);
            }
            else{
                alertToUrl('修改失败', $jump_url);
            }
        }
        else {
            $data = $this->_mod_shelves->find($id);
            $this->assign('data',$data);

            $depotList = queryDepot($depotID);
            $this->assign('depotList', $depotList);
            $this->display();
        }
    }

    public function delAction(){
        $id = I('shelves_id');
        if(empty($id)){
            echo 0;
            return;
        }

        $where = array("shelves_id"=>$id);
        //判断是否有商品占用库位，否则不允许删除


        //开启事务删除关联表数据
        $this->_mod_shelves->startTrans();
        $res = $this->_mod_shelves_details->where($where)->delete();
        $res = $this->_mod_shelves->where($where)->delete();
        $this->_mod_shelves->commit();

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    public function detailAction(){
        $id = I('shelves_id');
        if(empty($id)){
            echo 0;
            return;
        }

        $where['shelves_id'] = $id;
        $shelvesInfo = $this->_mod_shelves->where($where)->find();
        //获取仓位数据
        $dataList = $this->_mod_shelves_details->where($where)->select();

        //---- 待定功能  取得仓位商品和数量


        //重组仓位数据
        $displayList = array();
        foreach($dataList as $k=>$v){
            $displayList[$v['display_y']][] = $v;
        }

        krsort($displayList);

        //dump($displayList);die;
        $this->assign('shelvesInfo',$shelvesInfo);
        $this->assign('displayList',$displayList);
        $this->display();
    }
}

/*************************** end ************************************/