<?php

/*******************************************************************
 ** 文件名称: ShopModel.class.php
 ** 功能描述: 终端店操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;

class ShopModel extends Model {

	// 数据主表: 终端店数据表
	protected $tableName = 'customer_info';

    //添加
    public function addShop($data){
        $res=$this->add($data);
        return $res?true:false;
    }
    //修改
    public function editShop($id,$data){
        $res=$this->where("cust_id={$id}")->save($data);
        return $res>=1?true:false;
    }
    //删除
    public function delShop($id){
        if($this->delete($id)){
            //删除关联数据

            return true;
        }else{
            return false;
        }
    }
    //设置是否审核
    public function setCheck($id,$val){
        $data = array('is_check'=>$val );
        $return= $this->where('cust_id='.$id)->save($data);
        return $return?true:false;
    }
    //设置是否关闭
    public function setClose($id,$val){
        $data = array('is_close'=>$val );
        $return=$this->where('cust_id='.$id)->save($data);
        return $return?true:false;
    }

    //终端店  电话，是否重复存在
    public function isCheckTel($id,$tel){
        $where=array(
            'telephone'=>$tel
        );
        $row = $this->where($where)->find();
        if($row){
            if($id>0 && $id == $row['cust_id']){
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }

    //终端店  名称，联系人，电话，确定商铺是否存在
    public function isHavedShop($id,$cust_name,$contact,$tel){
        $where=array(
            'cust_name'=>$cust_name,
            'contact'=>$contact,
            'telephone'=>$tel
        );
        $row = $this->where($where)->find();

        if($row){
            if($id>0 && $id == $row['cust_id']){
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }


}

/****************************** end *******************************/