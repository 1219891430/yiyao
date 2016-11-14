<?php
/** ****************************************************************
 ** 文件名称:GoodsClassModel.class.php
 ** 功能描述:商品品类数据库操作。
 ** 创建人员:sudi
 ** 创建日期:2016-08-02
 ******************************************************************/
namespace Common\Model;
use Think\Model;
class GoodsClassModel extends Model{
	protected $tableName="goods_class";
	
	
	public function getGoodsClassList($where){
		$list=$this->where($where)->select();
		return $list;
	}
	
	/*****************************************************************
	** 函数名称: setClassClose
	** 功能描述: 封存或解封类别
	** 输入: $class_id, $isClose
	** 返回: $data
	** 检查人员: sd
	** 检查日期: 2016-08-02
	****************************************************************/
	
	public function setClassClose($class_id,$isClose){
		if($isClose){
			$subcount=$this->where("parent_class=$class_id")->count();
			if($subcount){
				$data['msg']="有子类未封存，不能封存";
				$data['code']=2;
				return $data; 
			}else{
				$data["is_close"]=1;
				$where["class_id"]=$class_id;
				$this->where($where)->save($data);
				$data['msg']="封存";
				$data['code']=1;
				return $data;
			}
			
		}else{
			$data["is_close"]=0;
			$where["class_id"]=$class_id;
			$this->where($where)->save($data);
			$data['msg']="解封";
			$data['code']=0;
			return $data;
		}
		
	}
	/*****************************************************************
	** 函数名称: deleteClass
	** 功能描述: 删除类别
	** 输入: $class_id
	** 返回: $data
	** 检查人员: sd
	** 检查日期: 2016-08-02
	****************************************************************/
	public function deleteClass($class_id){
	    $has = M('goods_info')->where("class_id=$class_id")->count();

        if ($has > 0) {
            $data = array(
                'msg'=>"该类存在商品，不能删除",
                'status'=>false
            );
            return $data;
        }

		$subcount=$this->where("parent_class=$class_id")->count();
		if($subcount){
            $data = array(
                'msg'=>"该品类存在子类，不能删除",
                'status'=>false
            );
			return $data;
		}
		else{
			$res = $this->where("class_id=$class_id")->delete();
            if($res){
                $data = array(
                    'msg'=>"删除成功",
                    'status'=>true
                );
            }
            else{
                $data = array(
                    'msg'=>"删除失败，请重试",
                    'status'=>false
                );
            }
			return $data;
		}
	}


    //分类是否重复存在
    public function isCheckName($id,$name){
        $where=array(
            'class_name' => $name
        );
        $row = $this->where($where)->find();
        if($row){
            if($id>0 && $id == $row['class_id']){
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }
}
?>