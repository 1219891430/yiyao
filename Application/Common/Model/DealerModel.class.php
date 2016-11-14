<?php

/*******************************************************************
 ** 文件名称: DealerModel.class.php
 ** 功能描述: 经销商操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;

class DealerModel extends RelationModel {

	// 数据主表: 经销商数据表
    protected $tableName = 'org_info';

    protected $_link = array(
        'Depot' => array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'class_name'        =>  'DepotModel',
            'mapping_name'      =>  'depot',
            'foreign_key'       =>  'org_parent_id',
            'relation_foreign_key'  =>  'repertory_id',
            'relation_table'    =>  'zdb_depot_org'
        )
    );

	// 其他操作

    // 经销商列表
    public function listOrg($stmt) {

    }

    // 添加经销商
    public function addOrg($data) {
        $resu = $this->add($data);
        return $resu ? true : false;
    }

    // 修改经销商
    public function editOrg($data, $where) {
        $resu = $this->where($where)->save($data);
        return $resu ? true : false;
    }

    //开启用户
    public function open($id, $data){
        $return = $this->where('org_id=' . $id)->save($data);
        return $return ? true : false;
    }

    //禁用用户
    public function close($id, $data){
        $return = $this->where('org_id=' . $id)->save($data);
        return $return ? true : false;
    }

    //终端店  电话，是否重复存在
    public function isCheckTel($id,$tel){
        $where=array(
            'mobile'=>$tel
        );
        $row = $this->where($where)->find();
        if($row){
            if($id>0 && $id == $row['org_id']){
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }

    /*************************仓库<==>经销商*****************************/

    // 添加仓库-经销商关联
    /*public function doAdd() {
        $resu = $this->add($data);
    }*/

	
	
}

/****************************** end *******************************/