<?php

/*******************************************************************
 ** 文件名称: CarsaleDisplayController.class.php
 ** 功能描述: 经销商车销拍陈列接口
 ** 创建人员: richie
 ** 创建日期: 2016-08-16
*******************************************************************/

namespace Mobile\Controller;
use Think\Controller;

class CarsaleDisplayController extends Controller {

    // 拍陈列
    public function addPicAction()
    {
		// 上传陈列照片
        $image = new \Think\Image();
        $upload = new \Think\Upload();
        $upload->maxSize = 3000000;
        $upload->exts = array();
        $upload->savePath = 'display/';
        $upload->rootPath = './Public/Uploads/';
        $info = $upload->upload();
        if(!$info){ echo json_encode(array('error'=>'1', 'msg'=>'请检查图片是否存在')); exit; }

		// 多张不同类型图片
		$img_url = array();
		for($i=0; $i<count($info); $i++){ $img_url[] = $info[$i]['savepath'] . $info[$i]['savename']; }

		// 添加数据
		$temp_data = array();
		$temp['org_parent_id'] = $org_parent_id = $_REQUEST['org_parent_id'];
		$temp["saleman_id"] = $staff_id = $_REQUEST['userId'];
		$temp["shop_id"] = $shop_id = $_REQUEST["shopId"];
		$temp["remark"] = $_REQUEST["remark"];
		$temp["add_time"] = time();

		// 根据陈列类型分别存储
		$displayArray = explode(",", $_REQUEST["typeId"]);
		foreach($img_url as $k=>$imgUrl)
		{
			// 查询类型
			$displayID = intval($displayArray[$k]);
			$dis_name = M("customer_display_type")->field("sdt_name")->where("sdt_id=" . intval($displayID))->getField('sdt_name');
			
			$temp["sdt_id"] = $displayID;
			$temp["sdt_name"] = empty($dis_name) ? '' : $dis_name;
			$temp["display_img"] = $imgUrl;
			
			$image = new \Think\Image(); 
			$image->open("./Public/Uploads/$imgUrl");
			
			$thumbImgUrl="display_thumb".substr($imgUrl,7);
			$temp["display_thumb"]=$thumbImgUrl;
			$mkdir='./Public/Uploads/display_thumb/'.date("Y-m-d");
			if (!file_exists($mkdir)){
				mkdir ($mkdir);
			}
			$image->thumb(200,200)->save("./Public/Uploads/$thumbImgUrl");
			$temp_data[] = $temp;
		}
		$res = M("customer_display")->addAll($temp_data);

		// 添加店铺维护记录
		D('CarsaleShop')->addPosition($org_parent_id, $staff_id, $shop_id, 1);

		// 返回
		if(empty($res))
		{
			echo json_encode(array('error' => 1, 'msg' => '添加失败'));
		}
		else
		{
			echo json_encode(array('error' => -1, 'msg' => '添加成功'));
		}
    }

    //获取店铺各种类型最近N条的拍照记录
    public function indexAction(){
        $shopId = I('shopId');
        $staffId = I('userId');
        $ps = I('ps');  //每种类型返回数据条数据

        if(empty($shopId)){ $shopId =0; }
        if(empty($staffId)){ $staffId =0; }
        if(empty($ps)){$ps = 2; }

        $sql = "SELECT A1.*  
FROM zdb_customer_display AS A1  
     INNER JOIN (SELECT A.sdt_id,A.add_time
                 FROM zdb_customer_display AS A  
                      LEFT JOIN zdb_customer_display AS B  
                        ON A.sdt_id = B.sdt_id  
                           AND A.add_time <= B.add_time  
                 GROUP BY A.sdt_id,A.add_time 
                 HAVING COUNT(B.add_time) <= $ps
    ) AS B1  
    ON A1.sdt_id = B1.sdt_id  
       AND A1.add_time = B1.add_time 
       where A1.saleman_id=$staffId and A1.shop_id=$shopId 
       
ORDER BY A1.sdt_id,A1.add_time DESC";

        $Model = D();
        $list = $Model->query($sql);

        // 返回
        if(!empty($list))
        {
            $sdts = array();
            $res = array();
            foreach($list as $k=>$v){
                if(!in_array($v['sdt_id'],$sdts)){
                    $sdts[] = $v['sdt_id'];
                }
            }

            foreach($sdts as $k1=>$v1){
                $sdt_id = $v1;
                $sdt_name = '';
                $arr = array();
                foreach($list as $k2=>$v2){
                    if($sdt_id == $v2['sdt_id']){
                        $sdt_name = $v2['sdt_name'];
                        $v2['add_time'] = date('Y-m-d H:i:s', $v2['add_time']);
                        $arr[] = $v2;
                    }
                }
                $res[] = array('sdt_id'=>$sdt_id, 'sdt_name'=>$sdt_name, 'rows'=>$arr);
            }
            echo json_encode(array('error' => -1, 'msg' => '查询成功', 'data' =>$res));
        }
        else
        {
            echo json_encode(array('error' => 1, 'msg' => '查询失败'));
        }
    }
}

/*************************** end ************************************/