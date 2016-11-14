<?php

/*******************************************************************
 ** 文件名称: MsgController.class.php
 ** 功能描述: 系统后台信息管理控制器
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-19
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class MsgController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }
    // 控制器默认页
    // 创建人员:
    // 创建日期:
    public function indexAction(){

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        $p = isset($_GET['p']) ? $_GET['p'] : 1;
        $pnum = I("get.pnum", 10);

        $urlPara["type"] = I("type");
        $urlPara["start"] = I("start");
        $urlPara["end"] = I("end");
        $urlPara["wd"] = I("wd");
        $urlPara['depot_id'] = I('depot_id');

        if($depotID > 0) {
            $where['depot_id'] = $depotID;
        }
        else {
            if (!empty($urlPara['depot_id'])) {
                $where['depot_id'] = array("eq", $urlPara['depot_id']);
            }
        }


        if (!empty($urlPara["type"])) {
            $where['type'] = $urlPara['type'];
        }
        if (!empty($urlPara["start"])) {
            $where["add_time"] = array("egt",strtotime($urlPara["start"]));
        }
        if (!empty($urlPara["end"])) {
            $where["add_time"] = array("elt", strtotime($urlPara["end"]) + 24 * 60 * 59);
        }
        if (!empty($urlPara["wd"])) {
            $where["message"] = array("like","%{$urlPara["wd"]}%");
        }


        $total = M('msg')->where($where)->count();

        $list = M('msg')->where($where)
            ->page($p, $pnum)
            ->order('msg_id desc')
            ->select();


        //当前完整url
        $urlPara['p'] = $p;
        $urlPara['pnum'] = $pnum;

        session('jump_url', U('Admin/Msg',$urlPara) );

        $page = get_page_code($total, $pnum, $p, $page_code_len = 10);
        $this->assign('list', $list);//列表数据
        $this->assign('pagelist',$page);//分页显示

        $typeList = array(
            array('id'=>1,'name'=>'供货商加盟'),
            array('id'=>2,'name'=>'终端店加盟'),
        );

        //仓库select
        $depost_list = queryDepot($depotID);
        $this->assign('depotList', $depost_list);


        $this->assign('urlPara', $urlPara);
        $this->assign('typeList',$typeList);
        $this->display();
    }

    public function viewAction(){
        $jump_url = session('jump_url');
        if(empty($jump_url)){
            $jump_url = './';
        }

        $id = I('id');
        if(empty($id)){
            alertToUrl('参数错误', $jump_url);
            return;
        }

        $data = M('msg')->find($id);
        $this->assign('data',$data);
        $this->display();


    }

    public function statusAction(){
        $id = I('id');
        if(empty($id)){
            echo 0;
            return;
        }
        $data = array(
            'status' => 1
        );
        $where['msg_id'] = $id;
        $res = M('Msg')->where($where)->save($data);
        echo $res;
    }

    public function delAction(){
        $id = I('id');
        if(empty($id)){
            echo 0;
            return;
        }
        $where = array("msg_id"=>$id);
        $res = M('Msg')->where($where)->delete();

        if($res){
            echo 1;
        }
        else{
            echo 0;
        }
    }
}
?>