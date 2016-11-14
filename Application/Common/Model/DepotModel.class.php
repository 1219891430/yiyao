<?php

/*******************************************************************
 ** 文件名称: DepotModel.class.php
 ** 功能描述: 仓库操作公共模型类
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
*******************************************************************/

namespace Common\Model;
use Think\Model\RelationModel;

class DepotModel extends RelationModel {

	// 数据主表: 平台管理员数据表
	protected $tableName = 'depot_info';

    // 关系
    protected $_link = array(
        'Dealer' => array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'class_name'        =>  'Dealer',
            'mapping_name'      =>  'dealer',
            'foreign_key'       =>  'repertory_id',
            'relation_foreign_key'  =>  'org_parent_id',
            'relation_table'    =>  'zdb_depot_org'
        )
    );
	
	
	public function getDepotName($repertory_id){
		$res=$this->field("repertory_name")->where("repertory_id=$repertory_id")->find();
		return $res["repertory_name"];
		
	} 
	
	public function getDeoptAllList(){
		$list=$this->field("repertory_id,repertory_name")->select();
		return $list;
	}
	
	// 其他操作
    //添加
    public function addDepot($data){
        $res=$this->add($data);
        return $res?true:false;
    }
	
	
	
    //修改
    public function editDepot($id,$data){
        $res=$this->where("repertory_id={$id}")->save($data);
        return $res>=1?true:false;
    }
    //删除
    public function delDepot($id){
        if($this->delete($id)){
            //删除关联数据

            return true;
        }else{
            return false;
        }
    }
    //设置是否关闭
    public function setDepotClose($id,$val){
        $data = array('is_close'=>$val );
        $return=$this->where('repertory_id='.$id)->save($data);
        return $return?true:false;
    }
    //编码是否存在
    public function checkDepotData($id,$col,$val){
        $where=array(
            $col=>$val
        );
        $row = $this->where($where)->find();

        if($row){
            if($id>0 && $id == $row['repertory_id']){
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