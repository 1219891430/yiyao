<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/2
 * Time: 13:41
 */

namespace Admin\Controller;

use Think\Controller;

class GoodsWarningController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function warningAction() {
        if(IS_GET) {
            $depot_id = intval($_SESSION['depot_id']);
            $goods_id = I("get.gid");

            $where['ds.depot_id'] = $depot_id;
            $where['ds.goods_id'] = $goods_id;

            // 获取经销商
            $orgs = M("org_info")->alias('oi')
                ->join("left join __DEPOT_STOCK__ as ds on oi.org_id=ds.org_parent_id")
                ->join("left join __DEPOT_WARNING__ as dw on ds.goods_id=dw.goods_id AND oi.org_id=dw.org_id")
                ->where($where)
                ->field("oi.org_id, oi.org_name, ds.*, dw.warning_type, dw.warning_value")
                ->select();

            //echo M("org_info")->getLastSql();die();

            $this->assign('orgs', $orgs);

            $goods_name = I("get.gname");

            $goods = [
                "id" => $goods_id,
                "name" => $goods_name,
            ];
            $this->assign("goods", $goods);

            //print_r($orgs);die();
            $this->display("GoodsInfo:setwarning");
        }
    }

    public function setWarningTypeAction() {
        $depot_id = $this->_depot_id;
        if ($depot_id == 0) {

            $this->error("区域管理员才能设置库存", "/");

            return false;
        }

        if(IS_POST) {
            $org_id = I("post.org_id");
            $goods_id = I("post.goods_id");
            $warning_type = I("warning_type");

            $data = [
                "warning_type" => $warning_type
            ];

            $where["org_id"] = $org_id;
            $where["goods_id"] = $goods_id;

            $has = M("depot_warning")->where($where)->count();
            if ($has > 0) {
                $succ = M("depot_warning")->where($where)->save($data);
                if ($succ) {
                    $this->ajaxReturn(["status"=>true, "type"=>$warning_type, "msg"=>"设置成功"]);
                } else {
                    $this->ajaxReturn(["status"=>true, "msg"=>"设置失败"]);
                }
            } else {
                $data["org_id"] = $org_id;
                $data["goods_id"] = $goods_id;
                $data["depot_id"] = $depot_id;

                if(M("depot_warning")->add($data)){
                    $this->ajaxReturn(["status"=>true, "type"=>$warning_type, "msg"=>"插入成功"]);
                }else{
                    $this->ajaxReturn(["status"=>false, "msg"=>"插入失败"]);
                };
            }
        }
    }

    public function setWarningValueAction() {
        $depot_id = $this->_depot_id;
        if ($depot_id == 0) {

            $this->error("区域管理员才能设置库存", "/");

            return false;
        }

        if(IS_POST) {
            $org_id = I("post.org_id");
            $goods_id = I("post.goods_id");
            $warning_value = I("warning_value");

            $data = [
                "warning_value" => $warning_value
            ];

            //print_r($data);die();

            $where["org_id"] = $org_id;
            $where["goods_id"] = $goods_id;

            $has = M("depot_warning")->where($where)->count();

            if ($has > 0) {
                $succ = M("depot_warning")->where($where)->save($data);

                if ($succ) {
                    $this->ajaxReturn(["status"=>true, "value"=>$warning_value, "msg"=>"设置成功"]);
                } else {
                    $this->ajaxReturn(["status"=>false, "msg"=>"设置失败"]);
                }
            } else {
                $data["org_id"] = $org_id;
                $data["goods_id"] = $goods_id;
                $data["depot_id"] = $depot_id;

                if(M("depot_warning")->add($data)){
                    $this->ajaxReturn(["status"=>true, "value"=>$warning_value, "msg"=>"插入成功"]);
                }else{
                    $this->ajaxReturn(["status"=>false, "msg"=>"插入失败"]);
                };

            }

        }
    }

    // 报警界面
    public function warning_viewAction() {

        $warning_mid = 500;

        $urlParam["warning_mid"] = $warning_mid;


        if(!empty($_GET['org_id'])) {
            $urlParam['org_id'] = intval($_GET['org_id']);
            $where["oi.org_id"] = intval($_GET['org_id']);
        }

        $depot_id = intval($_SESSION['depot_id']);

        if ($depot_id > 0) {
            $where['ds.depot_id'] = $depot_id;
            $where['do.repertory_id'] = $depot_id;
        }

        $where['gi.is_close'] = array("eq", 0);

        $goods = M("depot_warning")->alias("dw")
            ->join("left join __GOODS_INFO__ as gi on dw.goods_id=gi.goods_id")
            ->join("left join __ORG_INFO__ as oi on dw.org_id=oi.org_id")
            ->join("left join __DEPOT_STOCK__ as ds on dw.goods_id=ds.goods_id AND oi.org_id=ds.org_parent_id")
            ->join("left join __DEPOT_ORG__ as do on oi.org_id=do.org_parent_id AND ds.depot_id=do.repertory_id")
            ->field("ds.*, dw.*, gi.goods_name, oi.org_name")
            ->where($where)
            ->select();

        //print_r($goods);die();

        $res = [];

        // 自动报警设置
        foreach ($goods as $key => $val) {
            if ($val["warning_type"] == 2) {
                $val["warning_value"] = autoWarning($val["goods_id"], $val["org_id"], 3);
                $val["warning_num"] = $val["small_stock"] - $val["warning_value"];
            } else {
                $val["warning_num"] = $val["small_stock"] - $val["warning_value"];
            }

            $res[$key] = $val;
        }

        $res = array_sort($res, "warning_num", "asc");


        //print_r($res);die();



        $this->assign('goods', $res);

        // 经销商列表
        $orgs = M("org_info")->alias("oi")
            ->join("__DEPOT_STOCK__ as ds on oi.org_id=ds.org_parent_id")
            ->join("__DEPOT_ORG__ as do on oi.org_id=do.org_parent_id")
            ->where("ds.depot_id=$depot_id AND do.repertory_id=$depot_id")
            ->group("oi.org_id")
            ->select();

        $this->assign("orgs", $orgs);

        $this->assign("urlParam", $urlParam);

        //print_r($orgs);die();
        $this->display("GoodsInfo:warning");
    }

}