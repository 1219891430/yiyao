<?php
/*******************************************************************
 ** 文件名称: CustomerOrderModel.class.php
 ** 功能描述: 商城下单
 ** 创建人员: richie
 ** 创建日期: 2016-08-03
 *******************************************************************/

namespace Common\Model;

use Think\Model\RelationModel;

class CustomerOrdersModel extends RelationModel{
    // 数据主表: 商城下单数据表
    protected $tableName = 'customer_orders';


    protected $_link = array(
        'order_goods'  =>  array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'CustomerOrderGoodsModel',
            'foreign_key'   => 'order_id',
            'parent_key'    => 'order_id',
            //'mapping_name'  => 'order_goods',
        ),
    );

}