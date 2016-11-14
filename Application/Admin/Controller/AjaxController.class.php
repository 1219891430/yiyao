<?php

/*******************************************************************
 ** 文件名称: AreaController.class.php
 ** 功能描述: 系统后台仓库区域
 ** 创建人员: wangbo
 ** 创建日期: 2016-09-02
 *******************************************************************/

namespace Admin\Controller;
use Think\Controller;

class AjaxController extends Controller {


    //获取仓库下的经销商
    public function getDepotOrgAction(){
        $depot_id = I('depot_id');

        if($depot_id ==0 ){
            $this->ajaxReturn(array('status'=>false,'rows'=>array()));
        }

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        if($depotID>0){
            $depot_id = $depotID;
        }

        $data = queryDealer($depot_id);
        $this->ajaxReturn(array('status'=>true,'rows'=>$data));
    }

    //获取仓库下的业务员
    public function getRoleStaffAction()
    {
        $depot_id = intval(I('depot_id'));
        $role_id = I('role_id');

        if($depot_id ==0 ){
            $this->ajaxReturn(array('status'=>false,'rows'=>array()));
        }
        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        //$role_id = 5; //配送人员
        if($depotID>0){
            $depot_id = $depotID;
        }

        $data = queryAdminStaff($depot_id,$role_id);
        $this->ajaxReturn(array('status'=>true,'rows'=>$data));

    }

    //获得仓库下的配送线路
    public function getShippingLineAction(){
        $depot_id = intval(I('depot_id'));
        if($depot_id ==0 ){
            $this->ajaxReturn(array('status'=>false,'rows'=>array()));
        }

        //内勤人员标识
        $depotID = intval($_SESSION['depot_id']);

        //$role_id = 5; //配送人员
        if($depotID>0){
            $depot_id = $depotID;
        }
        $data = M("shipping_line")->where("depot_id=$depot_id")->select();
        $this->ajaxReturn(array('status'=>true,'rows'=>$data));
    }
    
    

}