<?php

/*******************************************************************
 ** 文件名称: JoinController.class.php
 ** 功能描述: B2B商城加盟信息控制器
 ** 创建人员: wangbo
 ** 创建日期: 2016-08-19
 *******************************************************************/

namespace WapMall\Controller;


class JoinController extends BaseController {

    // 加盟首页
    // 创建人员: wangbo
    // 创建日期: 2016-08-19
    public function indexAction()
    {
        $this->display();
    }



    // 加盟信息提交
    // 创建人员: wangbo
    // 创建日期: 2016-08-19
    public function sendAction()
    {
        if(IS_POST){
            $data = array(
                'type' => I('type'),
                'realname' => I('realname'),
                'mobile' => I('mobile'),
                'email' => I('email'),
                'address' => I('province') . I('city') . I('district') . I('address'),
                'message' => I('message'),
                'add_time' => time(),
                'depot_id' => session('depot_id')
            );

            $res = M('msg')->add($data);
            if($res){
                alertToUrl('信息提交成功，请等待客服联系', U('Mall/Join') );
            }
            else{
                alertToUrl('信息提交失败，请重试', U('Mall/Join') );
            }
        }
    }


    /** 其他Action **/


}

/*************************** end ************************************/