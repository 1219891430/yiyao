<?php
/**
 * 文件名称：ActionConfigModel.class.php.
 * 功能：业务员的行动设置
 * 创建人: zy
 * Time:14-10-27 10:02
 */

namespace Common\Model;


use Think\Model;

class ActionConfigModel extends Model{

    protected $tableName = 'org_action_config';


    public function selectActionConfig($org_parent_id)
    {
    	$where["zdb_org_staff.role_id"]=3;
    	$where["zdb_org_staff.org_parent_id"]=$org_parent_id;
        $res=M("org_staff")
        	->field("staff_id,staff_name,mobile,login_user,begin_time,end_time,interval")
            ->join("left join zdb_org_action_config on zdb_org_staff.staff_id=zdb_org_action_config.saleman_id")
            ->where($where)->select();
        return $res;
    }
    //修改人员行动轨迹
    public function updateStaffRoute($route,$org_parent_id)
    {
        $j=0;
        for($i=0;$i<count($route);$i++)
        {

            if($this->checkAction($route[$i]["staff"],$org_parent_id))//存在
            {
                $udata["begin_time"]=$route[$i]["start"];
                $udata["end_time"]=$route[$i]["end"];
                $udata["interval"]=$route[$i]["interval"];

                $umess=$this->where("saleman_id=%d and org_parent_id=%d",array($route[$i]["staff"],$org_parent_id))->save($udata);
                $umess>=0?"":$j++;
            }
            else{
                $data["saleman_id"]=$route[$i]["staff"];
                $data["begin_time"]=$route[$i]["start"];
                $data["end_time"]=$route[$i]["end"];
                $data["interval"]=$route[$i]["interval"];
                $data["org_parent_id"]=$org_parent_id;
                $amess=$this->data($data)->add();
                $amess>=0?"":$j++;
            }

        }

        return $j>0?false:true; //true为没有错误
    }
    //查看人员设置是否存在
    public function checkAction($staff_id,$org_parent_id)
    {
        $count=$this->field("count(saleman_id) as count")->where("saleman_id=%d and org_parent_id=%d",array($staff_id,$org_parent_id))->find();
        return $count["count"]>0?true:false;
    }
    //查看某个人员设置
    public function selStaffConfig($staff_id,$org_parent_id)
    {
      $where["saleman_id"]=$staff_id;
      $where["org_parent_id"]=$org_parent_id;
      return  $this->field("begin_time,end_time,interval")->where($where)->find();
    }

} 