/*
SQLyog Professional v12.09 (64 bit)
MySQL - 5.6.24 : Database - yiyao
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`yiyao` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `yiyao`;

/*Table structure for table `zdb_activity` */

DROP TABLE IF EXISTS `zdb_activity`;

CREATE TABLE `zdb_activity` (
  `act_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(64) NOT NULL DEFAULT '' COMMENT '活动名称',
  `act_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '活动类型0售价1满减2赠品',
  `act_note` varchar(255) NOT NULL DEFAULT '' COMMENT '活动备注',
  `act_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动商品价格',
  `act_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动满减要求达到金额',
  `act_offer_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动满减金额',
  `depot_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '仓库ID',
  `org_parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '经销商ID',
  `brand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `cv_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '货品ID',
  `goods_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品数量',
  `song_brand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赠送商品品牌',
  `song_goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赠送商品ID',
  `song_cv_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赠送商品货品ID',
  `song_goods_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赠送商品数量',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否关闭',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`act_id`),
  KEY `act_type` (`act_type`),
  KEY `depot_id` (`depot_id`),
  KEY `org_parent_id` (`org_parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_activity` */

insert  into `zdb_activity`(`act_id`,`act_name`,`act_type`,`act_note`,`act_price`,`act_money`,`act_offer_money`,`depot_id`,`org_parent_id`,`brand_id`,`goods_id`,`cv_id`,`goods_num`,`song_brand_id`,`song_goods_id`,`song_cv_id`,`song_goods_num`,`is_close`,`start_time`,`end_time`) values (1,'单价促销1',0,'','4.50','0.00','0.00',1,1,1,1,1,0,0,0,0,0,0,1475078400,1477929599),(2,'满减促销1',1,'','0.00','100.00','10.00',1,1,2,45,45,0,0,0,0,0,0,1475078400,1477929599),(3,'满减促销2',1,'','0.00','50.00','5.00',1,1,2,46,46,0,0,0,0,0,0,1475078400,1477929599),(4,'红洋葱限时特价',0,'限时特价','0.88','0.00','0.00',1,1,1,43,43,0,0,0,0,0,0,1474473600,1477929599),(6,'生菜满减',1,'','0.00','100.00','20.00',1,1,1,41,41,0,0,0,0,0,0,1474214400,1477929599),(8,'麻山药',2,'10','0.00','0.00','0.00',1,1,1,2,2,10,1,2,2,1,0,1475942400,1477929599),(9,'红洋葱满减',1,'满减','0.00','100.00','20.00',1,1,1,43,60,0,0,0,0,0,0,1475337600,1477929599);

/*Table structure for table `zdb_admin_access` */

DROP TABLE IF EXISTS `zdb_admin_access`;

CREATE TABLE `zdb_admin_access` (
  `depot_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `menus` text,
  PRIMARY KEY (`depot_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_admin_access` */

insert  into `zdb_admin_access`(`depot_id`,`role_id`,`menus`) values (1,1,'{\"1\":{\"id\":\"1\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-base\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"GoodsCategory\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"GoodsBrand\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"GoodsInfo\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"14\",\"title\":\"\\u5546\\u54c1\\u9884\\u8b66\",\"g\":\"Admin\",\"m\":\"GoodsWarning\",\"a\":\"warning_view\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"2\":{\"id\":\"2\",\"title\":\"\\u5ba2\\u6237\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-customer\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"21\",\"title\":\"\\u7ecf\\u9500\\u5546\",\"g\":\"Admin\",\"m\":\"Dealer\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"2\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"22\",\"title\":\"\\u7ec8\\u7aef\\u5e97\",\"g\":\"Admin\",\"m\":\"Shops\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"2\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"3\":{\"id\":\"3\",\"title\":\"\\u4eba\\u5458\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-action\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"31\",\"title\":\"\\u89d2\\u8272\\u5217\\u8868\",\"g\":\"Admin\",\"m\":\"Role\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"32\",\"title\":\"\\u4eba\\u5458\\u5217\\u8868\",\"g\":\"Admin\",\"m\":\"Staff\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"33\",\"title\":\"\\u91c7\\u5355\\u5e97\\u94fa\",\"g\":\"Admin\",\"m\":\"CollectShop\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"34\",\"title\":\"\\u914d\\u9001\\u7ebf\\u8def\",\"g\":\"Admin\",\"m\":\"ShippingLine\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"4\":{\"id\":\"4\",\"title\":\"\\u4ed3\\u5e93\\u8bbe\\u7f6e\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-depot\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"41\",\"title\":\"\\u4ed3\\u5e93\\u5217\\u8868\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"42\",\"title\":\"\\u4ed3\\u5e93\\u533a\\u57df\",\"g\":\"Admin\",\"m\":\"Area\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"43\",\"title\":\"\\u4ed3\\u5e93\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"category\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"44\",\"title\":\"\\u4ed3\\u5e93\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"brand\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"45\",\"title\":\"\\u4ed3\\u5e93\\u7ecf\\u9500\\u5546\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"dealer\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"5\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"5\":{\"id\":\"5\",\"title\":\"\\u5546\\u54c1\\u5e93\\u5b58\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-goods\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"5\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"51\",\"title\":\"\\u5546\\u54c1\\u5165\\u5e93\",\"g\":\"Admin\",\"m\":\"DepotIn\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"5\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"52\",\"title\":\"\\u5546\\u54c1\\u51fa\\u5e93\",\"g\":\"Admin\",\"m\":\"DepotOut\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"5\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"53\",\"title\":\"\\u5546\\u54c1\\u5e93\\u5b58\",\"g\":\"Admin\",\"m\":\"DepotStock\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"5\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"54\",\"title\":\"\\u4ed3\\u5e93\\u65e5\\u5fd7\",\"g\":\"Admin\",\"m\":\"DepotLog\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"5\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"6\":{\"id\":\"6\",\"title\":\"\\u9884\\u5355\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-order\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"6\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"61\",\"title\":\"\\u9884\\u5355\\u9500\\u552e\",\"g\":\"Admin\",\"m\":\"PresaleOrder\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"62\",\"title\":\"\\u9884\\u5355\\u9000\\u8d27\",\"g\":\"Admin\",\"m\":\"PresaleReturn\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"63\",\"title\":\"\\u9884\\u5355\\u8c03\\u6362\\u8d27\",\"g\":\"Admin\",\"m\":\"PresaleChange\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"64\",\"title\":\"\\u9884\\u5355\\u6c47\\u603b\\uff08\\u7c7b\\u578b\\uff09\",\"g\":\"Admin\",\"m\":\"PresaleSummary\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"65\",\"title\":\"\\u9884\\u5355\\u6c47\\u603b\\uff08\\u5e97\\u94fa\\uff09\",\"g\":\"Admin\",\"m\":\"PresaleSummary\",\"a\":\"shop\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"5\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"66\",\"title\":\"\\u91c7\\u8d2d\\u5355\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"PurchaseOrder\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"6\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"7\":{\"id\":\"7\",\"title\":\"\\u914d\\u9001\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-deliver\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"7\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"71\",\"title\":\"\\u914d\\u9001\\u9884\\u5355\",\"g\":\"Admin\",\"m\":\"DeliverPlan\",\"a\":\"list\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"72\",\"title\":\"\\u914d\\u9001\\u7533\\u8bf7\",\"g\":\"Admin\",\"m\":\"DeliverApply\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"73\",\"title\":\"\\u914d\\u9001\\u9000\\u5e93\",\"g\":\"Admin\",\"m\":\"DeliverBack\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"74\",\"title\":\"\\u914d\\u9001\\u8f66\\u5b58\",\"g\":\"Admin\",\"m\":\"DeliverStock\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"75\",\"title\":\"\\u914d\\u9001\\u8f66\\u9500\",\"g\":\"Admin\",\"m\":\"DeliverOrder\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"5\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"76\",\"title\":\"\\u914d\\u9001\\u9000\\u8d27\",\"g\":\"Admin\",\"m\":\"DeliverReturn\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"6\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"77\",\"title\":\"\\u914d\\u9001\\u8c03\\u6362\\u8d27\",\"g\":\"Admin\",\"m\":\"DeliverChange\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"7\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"78\",\"title\":\"\\u914d\\u9001\\u5bf9\\u8d26\",\"g\":\"Admin\",\"m\":\"DeliverSummary\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"8\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"79\",\"title\":\"\\u914d\\u9001\\u6c47\\u603b\",\"g\":\"Admin\",\"m\":\"DeliverOrgSummary\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"9\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"8\":{\"id\":\"8\",\"title\":\"\\u8d4a\\u6b3e\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-sheqian\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"8\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"81\",\"title\":\"\\u8d4a\\u6b3e\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"SheQian\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"8\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"ids\":[\"1\",\"11\",\"12\",\"13\",\"14\",\"2\",\"21\",\"22\",\"3\",\"31\",\"32\",\"33\",\"34\",\"4\",\"41\",\"42\",\"43\",\"44\",\"45\",\"5\",\"51\",\"52\",\"53\",\"54\",\"6\",\"61\",\"62\",\"63\",\"64\",\"65\",\"66\",\"7\",\"71\",\"72\",\"73\",\"74\",\"75\",\"76\",\"77\",\"78\",\"79\",\"8\",\"81\"]}'),(1,2,'{\"1\":{\"id\":\"1\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-base\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"GoodsCategory\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"GoodsBrand\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"GoodsInfo\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"14\",\"title\":\"\\u5546\\u54c1\\u9884\\u8b66\",\"g\":\"Admin\",\"m\":\"GoodsWarning\",\"a\":\"warning_view\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"2\":{\"id\":\"2\",\"title\":\"\\u5ba2\\u6237\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-customer\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"21\",\"title\":\"\\u7ecf\\u9500\\u5546\",\"g\":\"Admin\",\"m\":\"Dealer\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"2\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"22\",\"title\":\"\\u7ec8\\u7aef\\u5e97\",\"g\":\"Admin\",\"m\":\"Shops\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"2\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"3\":{\"id\":\"3\",\"title\":\"\\u4eba\\u5458\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-action\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"31\",\"title\":\"\\u89d2\\u8272\\u5217\\u8868\",\"g\":\"Admin\",\"m\":\"Role\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"32\",\"title\":\"\\u4eba\\u5458\\u5217\\u8868\",\"g\":\"Admin\",\"m\":\"Staff\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"33\",\"title\":\"\\u91c7\\u5355\\u5e97\\u94fa\",\"g\":\"Admin\",\"m\":\"CollectShop\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"34\",\"title\":\"\\u914d\\u9001\\u7ebf\\u8def\",\"g\":\"Admin\",\"m\":\"ShippingLine\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"3\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"4\":{\"id\":\"4\",\"title\":\"\\u4ed3\\u5e93\\u8bbe\\u7f6e\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-depot\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"41\",\"title\":\"\\u4ed3\\u5e93\\u5217\\u8868\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"42\",\"title\":\"\\u4ed3\\u5e93\\u533a\\u57df\",\"g\":\"Admin\",\"m\":\"Area\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"43\",\"title\":\"\\u4ed3\\u5e93\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"category\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"44\",\"title\":\"\\u4ed3\\u5e93\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"Depot\",\"a\":\"brand\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"45\",\"title\":\"\\u4ed3\\u5e93\\u7ecf\\u9500\\u5546\",\"g\":\"Admin\",\"m\":\"ShoDepotps\",\"a\":\"dealer\",\"ico\":\"\",\"pid\":\"4\",\"level\":\"2\",\"sort\":\"5\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"ids\":[\"1\",\"11\",\"12\",\"13\",\"14\",\"2\",\"21\",\"22\",\"3\",\"31\",\"32\",\"33\",\"34\",\"4\",\"41\",\"42\",\"43\",\"44\",\"45\"]}'),(1,3,'{\"1\":{\"id\":\"1\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-base\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"GoodsCategory\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"GoodsBrand\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"GoodsInfo\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"14\",\"title\":\"\\u5546\\u54c1\\u9884\\u8b66\",\"g\":\"Admin\",\"m\":\"GoodsWarning\",\"a\":\"warning_view\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"4\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"6\":{\"id\":\"6\",\"title\":\"\\u9884\\u5355\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-order\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"6\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"61\",\"title\":\"\\u9884\\u5355\\u9500\\u552e\",\"g\":\"Admin\",\"m\":\"PresaleOrder\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"6\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"7\":{\"id\":\"7\",\"title\":\"\\u914d\\u9001\\u7ba1\\u7406\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-deliver\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"7\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"71\",\"title\":\"\\u914d\\u9001\\u9884\\u5355\",\"g\":\"Admin\",\"m\":\"DeliverPlan\",\"a\":\"list\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"72\",\"title\":\"\\u914d\\u9001\\u7533\\u8bf7\",\"g\":\"Admin\",\"m\":\"DeliverApply\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"7\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"ids\":[\"1\",\"11\",\"12\",\"13\",\"14\",\"6\",\"61\",\"7\",\"71\",\"72\"]}'),(2,1,'{\"1\":{\"id\":\"1\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-base\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"GoodsCategory\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"GoodsBrand\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"ids\":[\"1\",\"11\",\"12\"]}'),(2,2,'{\"1\":{\"id\":\"1\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-base\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u54c1\\u7c7b\",\"g\":\"Admin\",\"m\":\"GoodsCategory\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"GoodsBrand\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"ids\":[\"1\",\"11\",\"12\"]}'),(2,3,'{\"1\":{\"id\":\"1\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"\",\"a\":\"\",\"ico\":\"left-bg-base\",\"pid\":\"0\",\"level\":\"1\",\"sort\":\"1\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\",\"submenu\":[{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u54c1\\u724c\",\"g\":\"Admin\",\"m\":\"GoodsBrand\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"2\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u603b\\u5e93\",\"g\":\"Admin\",\"m\":\"GoodsInfo\",\"a\":\"index\",\"ico\":\"\",\"pid\":\"1\",\"level\":\"2\",\"sort\":\"3\",\"remark\":\"\",\"status\":\"1\",\"flag\":\"0\"}]},\"ids\":[\"1\",\"12\",\"13\"]}');

/*Table structure for table `zdb_admin_order` */

DROP TABLE IF EXISTS `zdb_admin_order`;

CREATE TABLE `zdb_admin_order` (
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`,`order_type`,`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_admin_order` */

insert  into `zdb_admin_order`(`admin_id`,`order_type`,`order_id`,`order_state`,`shop_id`) values (6,1,111,3,3),(6,1,110,3,2);

/*Table structure for table `zdb_admin_shop` */

DROP TABLE IF EXISTS `zdb_admin_shop`;

CREATE TABLE `zdb_admin_shop` (
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`,`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_admin_shop` */

insert  into `zdb_admin_shop`(`admin_id`,`shop_id`) values (3,126),(3,127),(3,128),(3,130),(5,1),(5,2),(5,3),(5,4),(5,131),(5,132),(5,133);

/*Table structure for table `zdb_admin_user` */

DROP TABLE IF EXISTS `zdb_admin_user`;

CREATE TABLE `zdb_admin_user` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login_account` varchar(30) NOT NULL DEFAULT '',
  `login_pwd` char(32) NOT NULL DEFAULT '',
  `true_name` varchar(30) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `age` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `role_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `login_account` (`login_account`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_admin_user` */

insert  into `zdb_admin_user`(`admin_id`,`login_account`,`login_pwd`,`true_name`,`sex`,`age`,`mobile`,`role_id`,`depot_id`,`is_admin`,`is_close`) values (1,'admin121','e10adc3949ba59abbe56e057f20f883e','管理员',1,25,'18800000000',0,0,1,0),(2,'13111112222','e10adc3949ba59abbe56e057f20f883e','新乐内勤',1,40,'13111112222',1,1,0,0),(3,'13111113333','e10adc3949ba59abbe56e057f20f883e','新乐库管',1,35,'13111113333',2,1,0,0),(4,'13111114444','e10adc3949ba59abbe56e057f20f883e','新乐财务',1,28,'13111114444',3,1,0,0),(5,'15111112222','e10adc3949ba59abbe56e057f20f883e','新乐采单人员',1,30,'15111112222',4,1,0,0),(6,'15111113333','e10adc3949ba59abbe56e057f20f883e','新乐配送人员',1,40,'15111113333',5,1,0,0),(7,'13111119999','e10adc3949ba59abbe56e057f20f883e','新乐采购',1,11,'13111119999',6,1,0,0),(8,'14111112222','e10adc3949ba59abbe56e057f20f883e','藁城内勤',1,11,'14111112222',1,2,0,0);

/*Table structure for table `zdb_car_apply` */

DROP TABLE IF EXISTS `zdb_car_apply`;

CREATE TABLE `zdb_car_apply` (
  `apply_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apply_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apply_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `apply_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `apply_remark` varchar(100) NOT NULL DEFAULT '',
  `apply_flag` varchar(32) NOT NULL DEFAULT '',
  `add_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `check_id` int(10) unsigned NOT NULL DEFAULT '0',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0',
  `accept_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`apply_id`),
  UNIQUE KEY `apply_code` (`apply_code`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_apply` */

/*Table structure for table `zdb_car_apply_goods` */

DROP TABLE IF EXISTS `zdb_car_apply_goods`;

CREATE TABLE `zdb_car_apply_goods` (
  `apply_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_sepc` varchar(30) NOT NULL DEFAULT '',
  `apply_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `apply_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`apply_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_apply_goods` */

/*Table structure for table `zdb_car_change` */

DROP TABLE IF EXISTS `zdb_car_change`;

CREATE TABLE `zdb_car_change` (
  `change_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `change_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `pay_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `pay_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `change_remark` varchar(50) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`change_id`),
  UNIQUE KEY `change_code` (`change_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_change` */

/*Table structure for table `zdb_car_change_goods` */

DROP TABLE IF EXISTS `zdb_car_change_goods`;

CREATE TABLE `zdb_car_change_goods` (
  `change_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_change_in` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `singleprice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `number` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`change_id`,`cv_id`,`is_change_in`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_change_goods` */

/*Table structure for table `zdb_car_duizhang` */

DROP TABLE IF EXISTS `zdb_car_duizhang`;

CREATE TABLE `zdb_car_duizhang` (
  `cd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `totalmoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `shishoumoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sheqianmoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tuihuomoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `changemoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `qingqianmoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `qiankuanchexiao` decimal(8,2) NOT NULL DEFAULT '0.00',
  `remark` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`cd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_duizhang` */

/*Table structure for table `zdb_car_duizhang_goods` */

DROP TABLE IF EXISTS `zdb_car_duizhang_goods`;

CREATE TABLE `zdb_car_duizhang_goods` (
  `cd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `last_carport` varchar(30) NOT NULL DEFAULT '',
  `depot_out` varchar(30) NOT NULL DEFAULT '',
  `tui_depot` varchar(30) NOT NULL DEFAULT '',
  `carport` varchar(30) NOT NULL DEFAULT '',
  `carport_int` int(10) NOT NULL DEFAULT '0',
  `sales_num` varchar(30) NOT NULL DEFAULT '',
  `cuxiao_num` varchar(30) NOT NULL DEFAULT '',
  `tui_num` varchar(30) NOT NULL DEFAULT '',
  `change_in_num` varchar(30) NOT NULL DEFAULT '',
  `change_out_num` varchar(30) NOT NULL DEFAULT '',
  `goods_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `xiaoji` decimal(8,2) NOT NULL DEFAULT '0.00',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_name` varchar(30) NOT NULL DEFAULT '',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_name` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`cd_id`,`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_duizhang_goods` */

/*Table structure for table `zdb_car_duizhang_sheqian` */

DROP TABLE IF EXISTS `zdb_car_duizhang_sheqian`;

CREATE TABLE `zdb_car_duizhang_sheqian` (
  `cd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_boss` varchar(30) NOT NULL DEFAULT '',
  `total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `history_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `yifu_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sheqian_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`cd_id`,`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_duizhang_sheqian` */

/*Table structure for table `zdb_car_orders` */

DROP TABLE IF EXISTS `zdb_car_orders`;

CREATE TABLE `zdb_car_orders` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `order_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_real_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_full_pay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `order_remark` varchar(50) NOT NULL DEFAULT '',
  `order_ticket` varchar(100) NOT NULL DEFAULT '',
  `order_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  `presale_order` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_code` (`order_code`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_orders` */

insert  into `zdb_car_orders`(`order_id`,`order_code`,`staff_id`,`org_parent_id`,`repertory_id`,`cust_id`,`cust_name`,`cust_contact`,`cust_tel`,`cust_address`,`order_total_money`,`order_real_money`,`is_full_pay`,`create_time`,`order_remark`,`order_ticket`,`order_way`,`is_cancel`,`cancel_time`,`presale_order`) values (26,'CO000620161111709892',6,1,1,3,'小陈商铺','陈六子','13077770000','贾村','2200.00','0.00',0,1478845939,'','',1,0,0,111),(25,'CO000620161111285835',6,1,1,2,'红霞超市','吕红霞','13099990000','小张村','2900.00','0.00',0,1478845921,'','',1,0,0,110);

/*Table structure for table `zdb_car_orders_goods` */

DROP TABLE IF EXISTS `zdb_car_orders_goods`;

CREATE TABLE `zdb_car_orders_goods` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cuxiao` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `good_name` varchar(30) NOT NULL DEFAULT '',
  `good_spec` varchar(30) NOT NULL DEFAULT '',
  `singleprice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `number` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_id`,`cv_id`,`cuxiao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_orders_goods` */

insert  into `zdb_car_orders_goods`(`order_id`,`cv_id`,`cuxiao`,`cust_id`,`goods_id`,`good_name`,`good_spec`,`singleprice`,`number`,`unit_name`) values (26,27,0,3,11,'麦冬','1kg','100.00','1.00','件'),(26,25,0,3,10,'牡丹皮','1kg(安徽)','100.00','3.00','件'),(26,21,0,3,8,'酒白芍(酒炙)','酒炙1kg(安徽)','100.00','6.00','件'),(26,15,0,3,5,'利多卡因氯已定气雾剂','60g','100.00','1.00','包'),(26,12,0,3,4,'灵芝孢子粉（破壁）','2g*60包','100.00','1.00','包'),(26,4,0,3,2,'阿莫西林胶囊','0.25g*24s','100.00','4.00','盒'),(26,2,0,3,1,'注射用头孢唑啉钠','1g','100.00','6.00','件'),(25,12,0,2,4,'灵芝孢子粉（破壁）','2g*60包','100.00','9.00','包'),(25,9,0,2,3,'注射用盐酸大观霉素(卓青)','2g','100.00','8.00','包'),(25,4,0,2,2,'阿莫西林胶囊','0.25g*24s','100.00','6.00','盒'),(25,2,0,2,1,'注射用头孢唑啉钠','1g','100.00','6.00','件');

/*Table structure for table `zdb_car_orders_qiankuan` */

DROP TABLE IF EXISTS `zdb_car_orders_qiankuan`;

CREATE TABLE `zdb_car_orders_qiankuan` (
  `oq_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `mark` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `qk_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`oq_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_orders_qiankuan` */

/*Table structure for table `zdb_car_return` */

DROP TABLE IF EXISTS `zdb_car_return`;

CREATE TABLE `zdb_car_return` (
  `return_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `pay_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `return_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `return_remark` varchar(50) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`return_id`),
  UNIQUE KEY `return_code` (`return_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_return` */

/*Table structure for table `zdb_car_return_goods` */

DROP TABLE IF EXISTS `zdb_car_return_goods`;

CREATE TABLE `zdb_car_return_goods` (
  `return_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`return_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_return_goods` */

/*Table structure for table `zdb_car_return_stock` */

DROP TABLE IF EXISTS `zdb_car_return_stock`;

CREATE TABLE `zdb_car_return_stock` (
  `return_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `return_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `return_remark` varchar(50) NOT NULL DEFAULT '',
  `is_admin_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `check_id` int(10) unsigned NOT NULL DEFAULT '0',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`return_id`),
  UNIQUE KEY `return_code` (`return_code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_return_stock` */

/*Table structure for table `zdb_car_return_stock_goods` */

DROP TABLE IF EXISTS `zdb_car_return_stock_goods`;

CREATE TABLE `zdb_car_return_stock_goods` (
  `return_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`return_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_return_stock_goods` */

/*Table structure for table `zdb_car_stock` */

DROP TABLE IF EXISTS `zdb_car_stock`;

CREATE TABLE `zdb_car_stock` (
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `num_string` varchar(30) NOT NULL DEFAULT '',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`staff_id`,`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_stock` */

insert  into `zdb_car_stock`(`staff_id`,`goods_id`,`goods_name`,`goods_spec`,`goods_num`,`num_string`,`org_parent_id`) values (6,4,'灵芝孢子粉（破壁）','2g*60包','-100.00','-10 包 ',0),(6,3,'注射用盐酸大观霉素(卓青)','2g','-80.00','-8 包 ',0),(6,2,'阿莫西林胶囊','0.25g*24s','-10.00','-1 中包 ',0),(6,1,'注射用头孢唑啉钠','1g','-30600.00','-12 件 ',0),(6,5,'利多卡因氯已定气雾剂','60g','-12.00','-1 包 ',0),(6,8,'酒白芍(酒炙)','酒炙1kg(安徽)','-90.00','-6 件 ',0),(6,10,'牡丹皮','1kg(安徽)','-45.00','-3 件 ',0),(6,11,'麦冬','1kg','-12.00','-1 件 ',0);

/*Table structure for table `zdb_car_stock_log` */

DROP TABLE IF EXISTS `zdb_car_stock_log`;

CREATE TABLE `zdb_car_stock_log` (
  `record_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `stock_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_string` varchar(30) NOT NULL DEFAULT '',
  `stock_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `stock_string` varchar(30) NOT NULL DEFAULT '',
  `datetime` int(10) NOT NULL DEFAULT '0',
  `bianhua` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_car_stock_log` */

insert  into `zdb_car_stock_log`(`record_id`,`staff_id`,`org_parent_id`,`goods_id`,`stock_type`,`goods_num`,`goods_string`,`stock_num`,`stock_string`,`datetime`,`bianhua`) values (48,6,0,11,4,'12.00','1 件 ','-12.00','-1 件 ',1478845939,'车销订单 - -1 件 '),(47,6,0,10,4,'45.00','3 件 ','-45.00','-3 件 ',1478845939,'车销订单 - -3 件 '),(46,6,0,8,4,'90.00','6 件 ','-90.00','-6 件 ',1478845939,'车销订单 - -6 件 '),(45,6,0,5,4,'12.00','1 包 ','-12.00','-1 包 ',1478845939,'车销订单 - -1 包 '),(44,6,0,4,4,'10.00','1 包 ','-100.00','-10 包 ',1478845939,'车销订单 - -10 包 '),(43,6,0,2,4,'4.00','4 盒','-10.00','-1 中包 ',1478845939,'车销订单 - -1 中包 '),(42,6,0,1,4,'15300.00','6 件 ','-30600.00','-12 件 ',1478845939,'车销订单 - -12 件 '),(41,6,0,4,4,'90.00','9 包 ','-90.00','-9 包 ',1478845921,'车销订单 - -9 包 '),(40,6,0,3,4,'80.00','8 包 ','-80.00','-8 包 ',1478845921,'车销订单 - -8 包 '),(39,6,0,2,4,'6.00','6 盒','-6.00','-6 盒',1478845921,'车销订单 - -6 盒'),(38,6,0,1,4,'15300.00','6 件 ','-15300.00','-6 件 ',1478845921,'车销订单 - -6 件 ');

/*Table structure for table `zdb_carsale_apply` */

DROP TABLE IF EXISTS `zdb_carsale_apply`;

CREATE TABLE `zdb_carsale_apply` (
  `apply_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apply_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `apply_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `apply_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `apply_remark` varchar(50) NOT NULL DEFAULT '',
  `is_admin_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `check_id` int(10) unsigned NOT NULL DEFAULT '0',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0',
  `accept_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`apply_id`),
  UNIQUE KEY `apply_code` (`apply_code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_apply` */

/*Table structure for table `zdb_carsale_apply_goods` */

DROP TABLE IF EXISTS `zdb_carsale_apply_goods`;

CREATE TABLE `zdb_carsale_apply_goods` (
  `apply_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `apply_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `apply_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`apply_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_apply_goods` */

/*Table structure for table `zdb_carsale_orders` */

DROP TABLE IF EXISTS `zdb_carsale_orders`;

CREATE TABLE `zdb_carsale_orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `order_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_real_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_full_pay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `order_remark` varchar(50) NOT NULL DEFAULT '',
  `order_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_code` (`order_code`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_orders` */

insert  into `zdb_carsale_orders`(`order_id`,`order_code`,`staff_id`,`org_parent_id`,`cust_id`,`cust_name`,`cust_contact`,`cust_tel`,`cust_address`,`order_total_money`,`order_real_money`,`is_full_pay`,`create_time`,`order_remark`,`order_way`,`is_cancel`,`cancel_time`) values (81,'O000320161111910025',3,1,4,'老张超市','张小军','13864122789','和平东路300号','600.00','600.00',1,1478843053,'',1,0,0),(80,'O000320161111528291',3,1,1,'隆昌超市','王建民','13088880000','东城北街29号','600.00','600.00',1,1478843037,'',1,0,0),(79,'O000320161111367766',3,1,1,'隆昌超市','王建民','13088880000','东城北街29号','600.00','600.00',1,1478843026,'',1,0,0),(78,'O000320161111358316',3,1,1,'隆昌超市','王建民','13088880000','东城北街29号','300.00','300.00',1,1478843016,'',1,0,0),(77,'O000320161111950148',3,1,1,'隆昌超市','王建民','13088880000','东城北街29号','1000.00','1000.00',1,1478843003,'',1,0,0);

/*Table structure for table `zdb_carsale_orders_goods` */

DROP TABLE IF EXISTS `zdb_carsale_orders_goods`;

CREATE TABLE `zdb_carsale_orders_goods` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cuxiao` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `good_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `singleprice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `number` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_remark` varchar(100) NOT NULL DEFAULT '',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_id`,`cv_id`,`cuxiao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_orders_goods` */

insert  into `zdb_carsale_orders_goods`(`order_id`,`cv_id`,`cuxiao`,`cust_id`,`org_parent_id`,`goods_id`,`good_name`,`goods_spec`,`singleprice`,`number`,`goods_total_money`,`goods_remark`,`unit_name`) values (81,9,0,4,1,3,'注射用盐酸大观霉素(卓青)','','100.00','6.00','600.00','','包'),(80,4,0,1,1,2,'阿莫西林胶囊','','100.00','6.00','600.00','','盒'),(79,2,0,1,1,1,'注射用头孢唑啉钠','','100.00','6.00','600.00','','件'),(78,12,0,1,1,4,'灵芝孢子粉（破壁）','','100.00','3.00','300.00','','包'),(77,10,0,1,1,4,'灵芝孢子粉（破壁）','','100.00','10.00','1000.00','','盒');

/*Table structure for table `zdb_carsale_orders_qiankuan` */

DROP TABLE IF EXISTS `zdb_carsale_orders_qiankuan`;

CREATE TABLE `zdb_carsale_orders_qiankuan` (
  `oq_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `mark` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `qk_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`oq_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_orders_qiankuan` */

/*Table structure for table `zdb_carsale_return_stock` */

DROP TABLE IF EXISTS `zdb_carsale_return_stock`;

CREATE TABLE `zdb_carsale_return_stock` (
  `return_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `return_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `return_remark` varchar(50) NOT NULL DEFAULT '',
  `is_admin_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `check_id` int(10) unsigned NOT NULL DEFAULT '0',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0',
  `accept_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`return_id`),
  UNIQUE KEY `return_code` (`return_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_return_stock` */

/*Table structure for table `zdb_carsale_return_stock_goods` */

DROP TABLE IF EXISTS `zdb_carsale_return_stock_goods`;

CREATE TABLE `zdb_carsale_return_stock_goods` (
  `return_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`return_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_return_stock_goods` */

/*Table structure for table `zdb_carsale_stock` */

DROP TABLE IF EXISTS `zdb_carsale_stock`;

CREATE TABLE `zdb_carsale_stock` (
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `num_string` varchar(30) NOT NULL DEFAULT '',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`staff_id`,`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_stock` */

insert  into `zdb_carsale_stock`(`staff_id`,`goods_id`,`goods_name`,`goods_spec`,`goods_num`,`num_string`,`org_parent_id`) values (3,3,'注射用盐酸大观霉素(卓青)','2g','-60.00','-6 包 ',1),(3,2,'阿莫西林胶囊','0.25g*24s','-6.00','-6 盒',1),(3,1,'注射用头孢唑啉钠','1g','-15300.00','-6 件 ',1),(3,4,'灵芝孢子粉（破壁）','2g*60包','-40.00','-4 包 ',1);

/*Table structure for table `zdb_carsale_stock_log` */

DROP TABLE IF EXISTS `zdb_carsale_stock_log`;

CREATE TABLE `zdb_carsale_stock_log` (
  `record_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `stock_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_string` varchar(30) NOT NULL DEFAULT '',
  `stock_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `stock_string` varchar(30) NOT NULL DEFAULT '',
  `datetime` int(10) unsigned NOT NULL DEFAULT '0',
  `bianhua` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsale_stock_log` */

insert  into `zdb_carsale_stock_log`(`record_id`,`staff_id`,`org_parent_id`,`goods_id`,`stock_type`,`goods_num`,`goods_string`,`stock_num`,`stock_string`,`datetime`,`bianhua`) values (76,3,1,3,4,'-60.00','-6 包 ','60.00','-6 包 ',1478843053,'业务员车销下单-60'),(75,3,1,2,4,'-6.00','-6 盒','6.00','-6 盒',1478843037,'业务员车销下单-6'),(74,3,1,1,4,'-15300.00','-6 件 ','15300.00','-6 件 ',1478843026,'业务员车销下单-15300'),(73,3,1,4,4,'30.00','3 包 ','-40.00','-4 包 ',1478843016,'业务员车销下单-30'),(72,3,1,4,4,'-10.00','-1 包 ','10.00','-1 包 ',1478843003,'业务员车销下单-10');

/*Table structure for table `zdb_carsales_change` */

DROP TABLE IF EXISTS `zdb_carsales_change`;

CREATE TABLE `zdb_carsales_change` (
  `change_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `change_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `real_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `pay_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `change_remark` varchar(50) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`change_id`),
  UNIQUE KEY `change_code` (`change_code`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_change` */

/*Table structure for table `zdb_carsales_change_goods` */

DROP TABLE IF EXISTS `zdb_carsales_change_goods`;

CREATE TABLE `zdb_carsales_change_goods` (
  `change_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_change_in` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `singleprice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `number` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`change_id`,`cv_id`,`is_change_in`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_change_goods` */

/*Table structure for table `zdb_carsales_duizhang` */

DROP TABLE IF EXISTS `zdb_carsales_duizhang`;

CREATE TABLE `zdb_carsales_duizhang` (
  `cd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `totalmoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `shishoumoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sheqianmoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tuihuomoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `changemoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `qingqianmoney` decimal(8,2) NOT NULL DEFAULT '0.00',
  `qiankuanchexiao` decimal(8,2) NOT NULL DEFAULT '0.00',
  `remark` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`cd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_duizhang` */

/*Table structure for table `zdb_carsales_duizhang_goods` */

DROP TABLE IF EXISTS `zdb_carsales_duizhang_goods`;

CREATE TABLE `zdb_carsales_duizhang_goods` (
  `cd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `last_carport` varchar(30) NOT NULL DEFAULT '',
  `depot_out` varchar(30) NOT NULL DEFAULT '',
  `tui_depot` varchar(30) NOT NULL DEFAULT '',
  `carport` varchar(30) NOT NULL DEFAULT '',
  `carport_int` int(10) NOT NULL DEFAULT '0',
  `sales_num` varchar(30) NOT NULL DEFAULT '',
  `cuxiao_num` varchar(30) NOT NULL DEFAULT '',
  `tui_num` varchar(30) NOT NULL DEFAULT '',
  `change_in_num` varchar(30) NOT NULL DEFAULT '',
  `change_out_num` varchar(30) NOT NULL DEFAULT '',
  `xiaoji` decimal(8,2) NOT NULL DEFAULT '0.00',
  `brand_id` int(10) NOT NULL DEFAULT '0',
  `brand_name` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`cd_id`,`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_duizhang_goods` */

/*Table structure for table `zdb_carsales_duizhang_sheqian` */

DROP TABLE IF EXISTS `zdb_carsales_duizhang_sheqian`;

CREATE TABLE `zdb_carsales_duizhang_sheqian` (
  `cd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_boss` varchar(30) NOT NULL DEFAULT '',
  `total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `history_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `yifu_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sheqian_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`cd_id`,`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_duizhang_sheqian` */

/*Table structure for table `zdb_carsales_return` */

DROP TABLE IF EXISTS `zdb_carsales_return`;

CREATE TABLE `zdb_carsales_return` (
  `return_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `real_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `return_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `return_remark` varchar(50) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`return_id`),
  UNIQUE KEY `return_code` (`return_code`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_return` */

/*Table structure for table `zdb_carsales_return_goods` */

DROP TABLE IF EXISTS `zdb_carsales_return_goods`;

CREATE TABLE `zdb_carsales_return_goods` (
  `return_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`return_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_carsales_return_goods` */

/*Table structure for table `zdb_cart` */

DROP TABLE IF EXISTS `zdb_cart`;

CREATE TABLE `zdb_cart` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `goods_image` varchar(255) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`cust_id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_cart` */

/*Table structure for table `zdb_customer_display` */

DROP TABLE IF EXISTS `zdb_customer_display`;

CREATE TABLE `zdb_customer_display` (
  `sd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `saleman_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sdt_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sdt_name` varchar(30) NOT NULL DEFAULT '',
  `display_img` varchar(255) NOT NULL DEFAULT '',
  `display_thumb` varchar(255) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_customer_display` */

insert  into `zdb_customer_display`(`sd_id`,`org_parent_id`,`saleman_id`,`shop_id`,`sdt_id`,`sdt_name`,`display_img`,`display_thumb`,`remark`,`add_time`) values (1,1,3,1,1,'门头','display/2016-10-08/57f87d8fac746.jpg','','啊啊啊吧',1475902863),(2,1,3,1,2,'货架1','display/2016-10-08/57f87db51c1b3.jpg','','得得得',1475902901),(3,1,3,1,3,'陈列1','display/2016-10-08/57f87db51c565.jpg','','得得得',1475902901),(4,1,3,1,1,'门头','display/2016-10-08/57f87e03946b0.jpg','','摸摸你',1475902979),(5,1,3,1,2,'货架1','display/2016-10-08/57f87e0394aae.jpg','','摸摸你',1475902979),(6,1,3,1,1,'门头','display/2016-10-08/57f87e3c1082f.jpg','','',1475903036),(7,1,3,1,1,'门头','display/2016-10-08/57f87fd3eb668.jpg','','',1475903443),(8,1,3,1,1,'门头','display/2016-10-08/57f87fe382e25.jpg','','',1475903459),(9,1,3,1,2,'货架1','display/2016-10-08/57f87fe383156.jpg','','',1475903459),(10,1,3,1,2,'货架1','display/2016-10-08/57f87ff7de102.jpg','','',1475903479),(11,1,3,1,3,'陈列1','display/2016-10-08/57f87ff7de4df.jpg','','',1475903479),(12,1,3,1,4,'陈列2','display/2016-10-08/57f87ff7de7c5.jpg','','',1475903479),(13,1,3,1,1,'门头','display/2016-10-08/57f89638726b1.jpg','display_thumb/2016-10-08/57f89638726b1.jpg','',1475909176),(14,1,3,1,1,'门头','display/2016-10-08/57f89a4bc553d.jpg','display_thumb/2016-10-08/57f89a4bc553d.jpg','',1475910219),(15,1,3,1,1,'门头','display/2016-10-08/57f89b57793ea.jpg','display_thumb/2016-10-08/57f89b57793ea.jpg','',1475910487),(16,1,3,1,1,'门头','display/2016-10-08/57f89d4f6e6d1.jpg','display_thumb/2016-10-08/57f89d4f6e6d1.jpg','可以吗',1475910991),(18,1,3,1,1,'门头','display/2016-10-08/57f8a7601c315.jpg','display_thumb/2016-10-08/57f8a7601c315.jpg','',1475913568),(19,1,3,18,1,'门头','display/2016-10-09/57f9fa6856b1e.jpg','display_thumb/2016-10-09/57f9fa6856b1e.jpg','',1476000360),(20,1,3,18,2,'货架1','display/2016-10-09/57f9fa6856f5f.jpg','display_thumb/2016-10-09/57f9fa6856f5f.jpg','',1476000360),(21,1,3,1,1,'门头','display/2016-10-10/57fb00ad430b1.jpg','display_thumb/2016-10-10/57fb00ad430b1.jpg','',1476067501),(22,1,5,1,4,'陈列2','display/2016-10-10/57fb03ca01ea4.jpg','display_thumb/2016-10-10/57fb03ca01ea4.jpg','11111111',1476068298),(23,1,5,1,4,'陈列2','display/2016-10-10/57fb03e52cfbd.jpg','display_thumb/2016-10-10/57fb03e52cfbd.jpg','空军建军节',1476068325),(24,1,3,3,1,'门头','display/2016-10-10/57fb069d28a94.jpg','display_thumb/2016-10-10/57fb069d28a94.jpg','',1476069021),(25,1,3,5,1,'门头','display/2016-10-10/57fb07e320d71.jpg','display_thumb/2016-10-10/57fb07e320d71.jpg','',1476069347),(26,1,3,5,2,'货架1','display/2016-10-10/57fb07e3210ee.jpg','display_thumb/2016-10-10/57fb07e3210ee.jpg','',1476069347),(28,1,3,1,4,'陈列2','display/2016-10-11/57fc5a628807e.jpg','display_thumb/2016-10-11/57fc5a628807e.jpg','11111111',1476156002);

/*Table structure for table `zdb_customer_display_type` */

DROP TABLE IF EXISTS `zdb_customer_display_type`;

CREATE TABLE `zdb_customer_display_type` (
  `sdt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sdt_name` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`sdt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_customer_display_type` */

insert  into `zdb_customer_display_type`(`sdt_id`,`org_parent_id`,`sdt_name`) values (1,1,'门头'),(2,1,'货架1'),(3,1,'陈列1'),(4,1,'陈列2');

/*Table structure for table `zdb_customer_info` */

DROP TABLE IF EXISTS `zdb_customer_info`;

CREATE TABLE `zdb_customer_info` (
  `cust_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `loginname` varchar(30) NOT NULL DEFAULT '',
  `loginpwd` char(32) NOT NULL DEFAULT '',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `head_pic` varchar(100) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `city` varchar(30) NOT NULL DEFAULT '',
  `district` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `dimension` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_check` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `loginname` (`loginname`)
) ENGINE=MyISAM AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_customer_info` */

insert  into `zdb_customer_info`(`cust_id`,`cust_name`,`contact`,`telephone`,`loginname`,`loginpwd`,`repertory_id`,`head_pic`,`province`,`city`,`district`,`address`,`longitude`,`dimension`,`staff_id`,`reg_time`,`is_check`,`is_close`) values (1,'隆昌超市','王建民','13088880000','13088880000','e10adc3949ba59abbe56e057f20f883e',1,'','河北省','石家庄市','新乐市','东城北街29号','114.687898','38.349248',0,1470365389,1,0),(2,'红霞超市','吕红霞','13099990000','13099990000','e10adc3949ba59abbe56e057f20f883e',1,'','河北省','石家庄市','新乐市','小张村','114.685323','38.347363',0,1470365827,1,0),(3,'小陈商铺','陈六子','13077770000','13077770000','e10adc3949ba59abbe56e057f20f883e',1,'','河北省','石家庄市','新乐市','贾村','114.685452','38.352748',0,1470365976,1,0),(4,'老张超市','张小军','13864122789','13864122789','e10adc3949ba59abbe56e057f20f883e',1,'','河北省','石家庄市','新乐市','和平东路300号','114.682276','38.352816',0,1470968719,0,0),(5,'店铺1','人1','1401000001','1401000001','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','123','115.648472','38.80288',0,1475111360,0,0),(6,'老赵超市','老赵','15111111111','15111111111','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','老赵','114.686267','38.344166',0,1475111396,0,0),(7,'老钱店铺','老钱','15122222222','15122222222','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','qee','114.681129','38.346912',0,1475111477,0,0),(8,'老孙店铺','老孙','15133333333','15133333333','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','大大','114.686381','38.343909',0,1475111563,0,0),(9,'店铺2','人2','14010000002','14010000002','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','12321','114.680989','38.350493',0,1475111578,0,0),(10,'店铺3','人3','14010000003','14010000003','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','2222','114.682577','38.346387',0,1475111618,0,0),(11,'店铺4','人4','14010000004','14010000004','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','456666','114.687645','38.343022',0,1475111658,0,0),(12,'老李超市','老李','15144444444','15144444444','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','大大改善','114.670777','38.344812',0,1475111702,0,0),(13,'店铺5','人5','14010000005','14010000005','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','888888','114.675831','38.348928',0,1475111742,0,0),(14,'老周超市','老周','15155555555','15155555555','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','sbdgf','114.685625','38.339982',0,1475111811,0,0),(15,'老吴店铺','老吴','15166666666','15166666666','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','交流看法','114.682537','38.335549',0,1475111936,0,0),(16,'老郑商店','老郑','15177777777','15177777777','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','恢复','114.674637','38.351974',0,1475111992,0,0),(17,'老王超市','老王','15188888888','15188888888','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','新乐市','好地方','114.714045','38.339344',0,1475112078,0,0),(18,'店铺6','人6','14010000006','14010000006','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','797897878','114.68365','38.349147',0,1475113109,0,0),(19,'店铺7','人7','14010000007','14010000007','e10adc3949ba59abbe56e057f20f883e',0,'','山西省','太原市','市辖区','4675465465','114.691609','38.35047',0,1475113142,0,0),(20,'店铺8','人8','14010000008','14010000008','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','534544','114.688628','38.344842',0,1475113174,0,0),(21,'店铺10','人10','14010000010','14010000010','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','678876','114.6841','38.340437',0,1475113229,0,0),(22,'店铺100','人100','14010000100','14010000100','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','100','114.684307','38.340298',0,1475113229,0,0),(23,'店铺101','人101','14010000101','14010000101','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','101','114.684995','38.340243',0,1475113229,0,0),(24,'店铺102','人102','14010000102','14010000102','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','102','114.684599','38.340246',0,1475113229,0,0),(25,'店铺103','人103','14010000103','14010000103','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','103','114.684151','38.340457',0,1475113229,0,0),(26,'店铺104','人104','14010000104','14010000104','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','104','114.684341','38.340635',0,1475113229,0,0),(27,'店铺105','人105','14010000105','14010000105','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','105','114.684517','38.340845',0,1475113229,0,0),(28,'店铺106','人106','14010000106','14010000106','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','106','114.684797','38.340504',0,1475113229,0,0),(29,'店铺107','人107','14010000107','14010000107','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','107','114.684524','38.340996',0,1475113229,0,0),(30,'店铺108','人108','14010000108','14010000108','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','108','114.684969','38.340962',0,1475113229,0,0),(31,'店铺109','人109','14010000109','14010000109','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','109','114.684254','38.340799',0,1475113229,0,0),(32,'店铺110','人110','14010000110','14010000110','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','110','114.684363','38.340153',0,1475113229,0,0),(33,'店铺111','人111','14010000111','14010000111','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','111','114.684451','38.340348',0,1475113229,0,0),(34,'店铺112','人112','14010000112','14010000112','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','112','114.684249','38.340829',0,1475113229,0,0),(35,'店铺113','人113','14010000113','14010000113','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','113','114.684611','38.340848',0,1475113229,0,0),(36,'店铺114','人114','14010000114','14010000114','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','114','114.684190','38.340635',0,1475113229,0,0),(37,'店铺115','人115','14010000115','14010000115','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','115','114.684337','38.340210',0,1475113229,0,0),(38,'店铺116','人116','14010000116','14010000116','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','116','114.684545','38.340468',0,1475113229,0,0),(39,'店铺117','人117','14010000117','14010000117','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','117','114.684494','38.340944',0,1475113229,0,0),(40,'店铺118','人118','14010000118','14010000118','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','118','114.684513','38.340986',0,1475113229,0,0),(41,'店铺119','人119','14010000119','14010000119','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','119','114.684266','38.340659',0,1475113229,0,0),(42,'店铺120','人120','14010000120','14010000120','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','120','114.684645','38.340159',0,1475113229,0,0),(43,'店铺121','人121','14010000121','14010000121','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','121','114.684142','38.340756',0,1475113229,0,0),(44,'店铺122','人122','14010000122','14010000122','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','122','114.684274','38.340625',0,1475113229,0,0),(45,'店铺123','人123','14010000123','14010000123','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','123','114.684635','38.340943',0,1475113229,0,0),(46,'店铺124','人124','14010000124','14010000124','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','124','114.684588','38.340141',0,1475113229,0,0),(47,'店铺125','人125','14010000125','14010000125','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','125','114.684466','38.340743',0,1475113229,0,0),(48,'店铺126','人126','14010000126','14010000126','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','126','114.684211','38.340903',0,1475113229,0,0),(49,'店铺127','人127','14010000127','14010000127','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','127','114.684204','38.340834',0,1475113229,0,0),(50,'店铺128','人128','14010000128','14010000128','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','128','114.684302','38.340201',0,1475113229,0,0),(51,'店铺129','人129','14010000129','14010000129','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','129','114.684708','38.340188',0,1475113229,0,0),(52,'店铺130','人130','14010000130','14010000130','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','130','114.684647','38.340919',0,1475113229,0,0),(53,'店铺131','人131','14010000131','14010000131','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','131','114.684556','38.340745',0,1475113229,0,0),(54,'店铺132','人132','14010000132','14010000132','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','132','114.684154','38.340712',0,1475113229,0,0),(55,'店铺133','人133','14010000133','14010000133','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','133','114.684707','38.340297',0,1475113229,0,0),(56,'店铺134','人134','14010000134','14010000134','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','134','114.684306','38.340192',0,1475113229,0,0),(57,'店铺135','人135','14010000135','14010000135','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','135','114.684775','38.340608',0,1475113229,0,0),(58,'店铺136','人136','14010000136','14010000136','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','136','114.684719','38.340643',0,1475113229,0,0),(59,'店铺137','人137','14010000137','14010000137','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','137','114.684577','38.340655',0,1475113229,0,0),(60,'店铺138','人138','14010000138','14010000138','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','138','114.684628','38.340102',0,1475113229,0,0),(61,'店铺139','人139','14010000139','14010000139','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','139','114.684325','38.340213',0,1475113229,0,0),(62,'店铺140','人140','14010000140','14010000140','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','140','114.684855','38.340628',0,1475113229,0,0),(63,'店铺141','人141','14010000141','14010000141','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','141','114.684505','38.340755',0,1475113229,0,0),(64,'店铺142','人142','14010000142','14010000142','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','142','114.684302','38.340487',0,1475113229,0,0),(65,'店铺143','人143','14010000143','14010000143','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','143','114.684256','38.340116',0,1475113229,0,0),(66,'店铺144','人144','14010000144','14010000144','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','144','114.684391','38.340294',0,1475113229,0,0),(67,'店铺145','人145','14010000145','14010000145','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','145','114.684650','38.340275',0,1475113229,0,0),(68,'店铺146','人146','14010000146','14010000146','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','146','114.684717','38.340410',0,1475113229,0,0),(69,'店铺147','人147','14010000147','14010000147','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','147','114.684910','38.340401',0,1475113229,0,0),(70,'店铺148','人148','14010000148','14010000148','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','148','114.684231','38.340663',0,1475113229,0,0),(71,'店铺149','人149','14010000149','14010000149','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','149','114.684263','38.340830',0,1475113229,0,0),(72,'店铺150','人150','14010000150','14010000150','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','150','114.684715','38.340380',0,1475113229,0,0),(73,'店铺151','人151','14010000151','14010000151','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','151','114.684651','38.340910',0,1475113229,0,0),(74,'店铺152','人152','14010000152','14010000152','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','152','114.684767','38.340799',0,1475113229,0,0),(75,'店铺153','人153','14010000153','14010000153','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','153','114.684519','38.340182',0,1475113229,0,0),(76,'店铺154','人154','14010000154','14010000154','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','154','114.684463','38.340643',0,1475113229,0,0),(77,'店铺155','人155','14010000155','14010000155','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','155','114.684993','38.340235',0,1475113229,0,0),(78,'店铺156','人156','14010000156','14010000156','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','156','114.684407','38.340435',0,1475113229,0,0),(79,'店铺157','人157','14010000157','14010000157','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','157','114.684257','38.340508',0,1475113229,0,0),(80,'店铺158','人158','14010000158','14010000158','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','158','114.684938','38.340931',0,1475113229,0,0),(81,'店铺159','人159','14010000159','14010000159','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','159','114.684156','38.340669',0,1475113229,0,0),(82,'店铺160','人160','14010000160','14010000160','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','160','114.684512','38.340450',0,1475113229,0,0),(83,'店铺161','人161','14010000161','14010000161','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','161','114.684835','38.340142',0,1475113229,0,0),(84,'店铺162','人162','14010000162','14010000162','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','162','114.684820','38.340776',0,1475113229,0,0),(85,'店铺163','人163','14010000163','14010000163','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','163','114.684224','38.340225',0,1475113229,0,0),(86,'店铺164','人164','14010000164','14010000164','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','164','114.684554','38.340315',0,1475113229,0,0),(87,'店铺165','人165','14010000165','14010000165','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','165','114.684300','38.340264',0,1475113229,0,0),(88,'店铺166','人166','14010000166','14010000166','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','166','114.684703','38.340384',0,1475113229,0,0),(89,'店铺167','人167','14010000167','14010000167','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','167','114.684920','38.340513',0,1475113229,0,0),(90,'店铺168','人168','14010000168','14010000168','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','168','114.684573','38.340600',0,1475113229,0,0),(91,'店铺169','人169','14010000169','14010000169','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','169','114.684266','38.340466',0,1475113229,0,0),(92,'店铺170','人170','14010000170','14010000170','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','170','114.684214','38.340273',0,1475113229,0,0),(93,'店铺171','人171','14010000171','14010000171','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','171','114.684690','38.340779',0,1475113229,0,0),(94,'店铺172','人172','14010000172','14010000172','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','172','114.684158','38.340642',0,1475113229,0,0),(95,'店铺173','人173','14010000173','14010000173','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','173','114.684306','38.340270',0,1475113229,0,0),(96,'店铺174','人174','14010000174','14010000174','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','174','114.684645','38.340576',0,1475113229,0,0),(97,'店铺175','人175','14010000175','14010000175','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','175','114.684607','38.340613',0,1475113229,0,0),(98,'店铺176','人176','14010000176','14010000176','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','176','114.684237','38.340377',0,1475113229,0,0),(99,'店铺177','人177','14010000177','14010000177','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','177','114.684562','38.340317',0,1475113229,0,0),(100,'店铺178','人178','14010000178','14010000178','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','178','114.684503','38.340899',0,1475113229,0,0),(101,'店铺179','人179','14010000179','14010000179','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','179','114.684276','38.340510',0,1475113229,0,0),(102,'店铺180','人180','14010000180','14010000180','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','180','114.684476','38.340933',0,1475113229,0,0),(103,'店铺181','人181','14010000181','14010000181','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','181','114.684854','38.340510',0,1475113229,0,0),(104,'店铺182','人182','14010000182','14010000182','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','182','114.684162','38.340752',0,1475113229,0,0),(105,'店铺183','人183','14010000183','14010000183','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','183','114.684161','38.340305',0,1475113229,0,0),(106,'店铺184','人184','14010000184','14010000184','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','184','114.684397','38.340390',0,1475113229,0,0),(107,'店铺185','人185','14010000185','14010000185','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','185','114.684817','38.340528',0,1475113229,0,0),(108,'店铺186','人186','14010000186','14010000186','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','186','114.684341','38.340532',0,1475113229,0,0),(109,'店铺187','人187','14010000187','14010000187','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','187','114.684422','38.340856',0,1475113229,0,0),(110,'店铺188','人188','14010000188','14010000188','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','188','114.684603','38.340895',0,1475113229,0,0),(111,'店铺189','人189','14010000189','14010000189','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','189','114.684144','38.340895',0,1475113229,0,0),(112,'店铺190','人190','14010000190','14010000190','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','190','114.684680','38.340377',0,1475113229,0,0),(113,'店铺191','人191','14010000191','14010000191','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','191','114.684765','38.340904',0,1475113229,0,0),(114,'店铺192','人192','14010000192','14010000192','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','192','114.684515','38.340149',0,1475113229,0,0),(115,'店铺193','人193','14010000193','14010000193','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','193','114.684733','38.340115',0,1475113229,0,0),(116,'店铺194','人194','14010000194','14010000194','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','194','114.684241','38.340477',0,1475113229,0,0),(117,'店铺195','人195','14010000195','14010000195','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','195','114.684396','38.340786',0,1475113229,0,0),(118,'店铺196','人196','14010000196','14010000196','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','196','114.684730','38.340543',0,1475113229,0,0),(119,'店铺197','人197','14010000197','14010000197','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','197','114.684866','38.340470',0,1475113229,0,0),(120,'店铺198','人198','14010000198','14010000198','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','198','114.684813','38.340149',0,1475113229,0,0),(121,'店铺199','人199','14010000199','14010000199','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','199','114.684806','38.340414',0,1475113229,0,0),(122,'店铺200','人200','14010000200','14010000200','e10adc3949ba59abbe56e057f20f883e',0,'','河北省','石家庄市','市辖区','200','114.684980','38.340873',0,1475113229,0,0),(123,'123','1425','1360001111','1360001111','87c0f15977ab48f5bf4266feda6ed065',1,'','北京市','北京市','东城区','','116.42599224471228','39.91047135503608',0,1475232386,1,0),(124,'佝偻','偷塔','15022221111','15022221111','47d37960e69e303e74d1fbaf3b2a50a1',1,'','河北省','石家庄市','新华区','','114.48935821088921','38.061607659769265',0,1475246930,1,0),(125,'咯菌腈','楼梯','15011442222','15011442222','49d32274b0d873a8f3754560952744ba',1,'','河北省','石家庄市','长安区','','114.51072602900531','38.07174481031291',0,1475247953,1,0),(126,'扣了','咯破','13055550001','13055550001','91488a9814ba5b7ecd2da739984f80d4',1,'','北京市','北京市','丰台区','豆腐干豆腐干豆腐','116.24609514498779','39.82884447261976',3,1475398247,1,0),(127,'咯去了','统计','15011110002','15011110002','899e5b70d0ad9dd9aef2224cf79488c8',1,'','河北省','石家庄市','栾城区','','114.75686966808087','37.87844900532996',3,1475400120,1,0),(128,'扣了','楼梯','15011110003','15011110003','ab6666980401ac570c86376e3c3da225',1,'','河北省','石家庄市','赵县','法规和规范','114.82379614741718','37.87408022340407',3,1475403143,1,0),(129,'姥姥家了了','口红','15011110004','15011110004','705121344c81ab592f0d9a5245fa6243',1,'','北京市','北京市','丰台区','','116.27705856585418','39.88155182426699',0,1475403530,1,0),(130,'科目欧诺','乐透','15011110005','15011110005','62e7a4b956d2b1f5ee46f311ce38e4e3',1,'','北京市','北京市','海淀区','','116.25125169181865','40.066333710060064',3,1475403729,1,0),(131,'呵呵哒','嗯啊','18633332222','18633332222','506b67e10b4ec1b154c6f594b7c1f8c2',1,'','河北省','石家庄市','长安区','','114.52108472022032','38.04734400778866',5,1478675244,1,0),(132,'乐仁堂','黄河','1555555555','1555555555','5b1b68a9abf4d2cd155c81a9225fd158',1,'','北京市','北京市','西城区','','114.52080442937827','38.04738519654071',5,1478676551,1,0),(133,'外汇部','米','18633055093','18633055093','0f47bbe81ff1583a4f53e2fa0689ea0e',1,'','北京市','北京市','西城区','','114.5207373741529','38.04743483423675',5,1478676693,1,0);

/*Table structure for table `zdb_customer_log` */

DROP TABLE IF EXISTS `zdb_customer_log`;

CREATE TABLE `zdb_customer_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `saleman_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_content` varchar(50) NOT NULL DEFAULT '',
  `file_path` varchar(100) NOT NULL DEFAULT '',
  `record` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(30) NOT NULL DEFAULT '',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  `remind_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_customer_log` */

insert  into `zdb_customer_log`(`log_id`,`org_parent_id`,`saleman_id`,`shop_id`,`log_content`,`file_path`,`record`,`file_name`,`log_time`,`remind_time`,`is_read`) values (1,1,1,1,'','',1,'',1475896922,0,0),(2,1,1,1,'','',1,'',1475896945,0,0),(3,1,1,1,'','',1,'',1475896949,0,0),(4,1,1,1,'','',1,'',1475897049,0,0),(5,1,1,1,'','',1,'',1475897464,0,0),(6,1,1,1,'','',1,'',1475897594,0,0),(7,1,1,1,'','',1,'',1475897599,0,0),(8,1,1,1,'1111122233','',0,'',1475897897,0,0),(9,1,1,1,'444444455555555','',0,'',1475897905,0,0),(10,1,1,1,'66666666666666','',0,'',1475897910,0,0),(11,1,1,1,'','',1,'',1475897914,0,0),(12,1,1,1,'','',1,'',1475897916,0,0),(13,1,1,1,'','',1,'',1475897917,0,0),(14,1,1,1,'','',1,'',1475897918,0,0),(15,1,1,1,'','shoplog/2016-10-08/1475898710814.amr',1,'1475898710814.amr',1475898726,0,0),(16,0,1,0,'','',0,'',1475915383,1475915340,0),(17,1,1,1,'66666666666666','',0,'',1475960050,0,0),(18,1,1,1,'','',1,'',1475960050,0,0),(19,1,1,1,'66666666666666','',0,'',1475960080,0,0),(20,1,1,1,'','',1,'',1475960081,0,0),(21,1,1,1,'66666666666666','',0,'',1475960111,0,0),(22,1,1,1,'','',1,'',1475960112,0,0),(23,1,0,1,'66666666666666','',0,'',1475960128,0,0),(24,0,1,1,'66666666666666','',0,'',1475960131,0,0),(25,1,1,1,'PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==','',0,'',1475960133,0,0),(26,1,1,1,'66666666666666','',0,'',1475960135,0,0),(27,1,1,0,'66666666666666','',0,'',1475960138,0,0),(28,1,0,1,'66666666666666','',0,'',1475960140,0,0),(29,0,1,1,'66666666666666','',0,'',1475960143,0,0),(30,1,1,1,'','',1,'',1475960143,0,0),(31,1,1,1,'amF2YXNjcmlwdDpwcm9tcHQoMTExKTt4','',0,'',1475960145,0,0),(32,1,1,1,'66666666666666','',0,'',1475960148,0,0),(33,1,1,0,'66666666666666','',0,'',1475960150,0,0),(34,1,1,1,'66666666666666','',0,'',1475960153,0,0),(35,1,1,1,'66666666666666','',0,'',1475960155,0,0),(36,1,1,1,'66666666666666','',0,'',1475960158,0,0),(37,1,1,1,'66666666666666','',0,'',1475960160,0,0),(38,1,1,1,'66666666666666','',0,'',1475960163,0,0),(39,1,1,1,'66666666666666\'&quot;&gt;','',0,'',1475960165,0,0),(40,1,1,1,'66666666666666','',0,'',1475960168,0,0),(41,1,1,1,'66666666666666','',0,'',1475960170,0,0),(42,1,1,1,'66666666666666','',0,'',1475960173,0,0),(43,1,0,1,'','',1,'',1475960174,0,0),(44,1,1,1,'66666666666666','',0,'',1475960175,0,0),(45,1,1,1,'66666666666666\'&quot;&gt;','',0,'',1475960178,0,0),(46,1,1,1,'66666666666666','',0,'',1475960180,0,0),(47,1,1,1,'66666666666666','',0,'',1475960183,0,0),(48,1,1,1,'66666666666666','',0,'',1475960185,0,0),(49,1,1,1,'66666666666666','',0,'',1475960188,0,0),(50,1,1,1,'66666666666666\'&quot;&gt;','',0,'',1475960190,0,0),(51,1,1,1,'66666666666666','',0,'',1475960193,0,0),(52,1,1,1,'66666666666666','',0,'',1475960196,0,0),(53,1,1,1,'66666666666666','',0,'',1475960198,0,0),(54,0,1,1,'','',1,'',1475960204,0,0),(55,1,1,1,'66666666666666','',0,'',1475960215,0,0),(56,1,1,0,'','',1,'',1475960222,0,0),(57,1,0,1,'','',1,'',1475960225,0,0),(58,0,1,1,'','',1,'',1475960227,0,0),(59,1,1,0,'','',1,'',1475960230,0,0),(60,1,1,1,'','',1,'',1475960232,0,0),(61,1,1,1,'','',1,'',1475960235,0,0),(62,1,1,1,'','',1,'',1475960237,0,0),(63,1,1,1,'','',1,'',1475960240,0,0),(64,1,1,1,'','',1,'',1475960243,0,0),(65,1,1,1,'','',1,'',1475960245,0,0),(66,1,1,1,'','',1,'',1475960247,0,0),(67,1,1,1,'','',1,'',1475960249,0,0),(68,1,1,1,'','',1,'',1475960252,0,0),(69,1,1,1,'','',1,'',1475960254,0,0),(70,1,1,1,'','',1,'',1475960257,0,0),(71,1,1,1,'','',1,'',1475960259,0,0),(72,1,1,1,'CDs123','',1,'',1475960261,1475978640,0),(73,1,1,1,'Thug1dsfdgfhgjkgfhfh','',1,'',1475960275,1475983080,0),(74,1,1,0,'Bob','',0,'',1475981940,1475981880,0),(75,1,1,0,'Big','',0,'',1475982544,1475982540,0),(76,1,1,0,'000','',0,'',1475982706,1475982660,0),(77,1,1,0,'','',0,'',1475983449,0,0),(78,1,1,0,'Nvcn','',0,'',1475983464,1475983440,0),(79,1,1,0,'CNBC','',0,'',1475983575,1475983740,0),(80,1,1,0,'Dads','',0,'',1475983665,1475998020,0),(81,1,1,1,'','shoplog/2016-10-09/1475993710737.amr',1,'1475993710737.amr',1475993724,0,0),(82,1,1,1,'','shoplog/2016-10-09/1475993811162.amr',1,'1475993811162.amr',1475993843,0,0),(83,1,3,1,'测试测试','',0,'',1475996852,4294967295,0),(84,1,3,3,'11111111','',0,'',1476007598,0,0),(85,1,1,1,'','',1,'',1476008049,0,0),(86,1,1,1,'','',1,'',1476008186,0,0),(87,1,1,1,'','',1,'',1476008329,0,0),(88,1,1,1,'','',1,'',1476046515,0,0),(89,1,1,1,'','',1,'',1476046576,0,0),(90,1,1,1,'','',1,'',1476046577,0,0),(91,1,0,1,'','',1,'',1476046638,0,0),(92,0,1,1,'','',1,'',1476046666,0,0),(93,1,1,0,'','',1,'',1476046668,0,0),(94,1,0,1,'','',1,'',1476046670,0,0),(95,0,1,1,'','',1,'',1476046672,0,0),(96,1,1,0,'','',1,'',1476046676,0,0),(97,1,1,1,'','',1,'',1476046690,0,0),(98,1,1,1,'','',1,'',1476046700,0,0),(99,1,1,1,'','',1,'',1476046701,0,0),(100,1,1,1,'','',1,'',1476046703,0,0),(101,1,1,1,'','',1,'',1476046705,0,0),(102,1,1,1,'','',1,'',1476046706,0,0),(103,1,1,1,'','',1,'',1476046709,0,0),(104,1,1,1,'','',1,'',1476046710,0,0),(105,1,1,1,'','',1,'',1476046712,0,0),(106,1,1,1,'','',1,'',1476046716,0,0),(107,1,1,1,'','',1,'',1476046729,0,0),(108,1,1,1,'','',1,'',1476046730,0,0),(109,1,1,1,'','',1,'',1476046731,0,0),(110,1,1,1,'','',1,'',1476046738,0,0),(111,1,1,1,'','',1,'',1476060496,0,0),(112,1,1,1,'','',1,'',1476060637,0,0),(113,1,1,2,'','',1,'',1476060718,0,0),(114,1,1,1,'','',1,'',1476060756,0,0),(115,1,1,1,'','',1,'',1476060819,0,0),(116,1,1,1,'1','',1,'',1476061475,1476073260,0),(117,1,1,0,'Crash','',0,'',1476063011,1476063000,0),(118,1,1,0,'Vivid dreams','',0,'',1476063056,1476066600,0),(119,1,1,0,'Nvgnvng','',0,'',1476063950,1476063900,0),(120,1,1,1,'2222','',0,'',1476066840,0,0),(121,1,1,0,'','',0,'',1476079447,0,0),(122,1,1,0,'Gfgdffg','',0,'',1476080192,1476080160,0),(123,1,1,1,'Fudge','',1,'',1476081697,1476086100,0),(124,1,1,0,'We\'re','',0,'',1476081969,1476081900,0),(125,1,1,0,'Fads','',0,'',1476083699,1476083640,0),(126,1,1,0,'','',0,'',1476084553,0,0),(127,1,1,0,'Sss','',0,'',1476084634,1476084540,0),(128,1,1,0,'Dfdfdf','',0,'',1476084858,1476084780,0),(129,1,1,0,'Nvncn','',0,'',1476085755,1476085560,0),(130,1,1,0,'Bgfhgfh','',0,'',1476086073,1476085980,0),(131,1,1,0,'Qqqqq','',0,'',1476086165,1476089700,0),(132,1,1,1,'Fatal','',0,'',1476086511,1476086460,0),(133,1,1,1,'123','',0,'',1476086524,0,0),(134,1,1,0,'Cvs cv','',0,'',1476086667,1476086640,0),(135,1,1,1,'Fsdgsgsgs123','',0,'',1476086833,0,0),(136,1,1,1,'','',1,'',1476090658,0,0),(137,1,1,1,'','',1,'',1476150955,0,0),(138,1,3,1,'11111111','',0,'',1476156388,0,0),(139,1,1,1,'','',1,'',1476176030,0,0),(140,1,1,1,'','',1,'',1476176086,0,0),(141,1,1,1,'','',1,'',1476176541,0,0),(142,1,1,1,'','',1,'',1476176858,0,0),(143,1,1,1,'','',1,'',1476178258,0,0),(144,1,1,1,'Tutu','',1,'',1476178940,1476251460,0),(145,1,1,1,'','',1,'',1476235476,0,0),(146,1,1,1,'','',1,'',1476237009,0,0),(147,1,1,1,'Fandom','',0,'',1476237200,1476237180,0),(148,1,1,1,'','',1,'',1476240468,0,0),(149,1,1,1,'','',1,'',1476241036,0,0),(150,1,1,1,'','',1,'',1476241333,0,0),(151,1,1,1,'Yet','',0,'',1476251477,1476251460,0),(152,1,1,1,'This','',0,'',1476251519,1476251460,0),(153,1,1,1,'Yeah','',0,'',1476251535,1476262320,0),(154,1,1,1,'','',1,'',1476252011,0,0),(155,1,1,1,'','',1,'',1476252597,0,0),(156,1,3,1,'。。。','',0,'',1478673466,0,0),(157,1,3,1,'hhh','',0,'',1478673509,0,0);

/*Table structure for table `zdb_customer_type` */

DROP TABLE IF EXISTS `zdb_customer_type`;

CREATE TABLE `zdb_customer_type` (
  `ct_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ct_name` varchar(30) NOT NULL DEFAULT '',
  `ct_remark` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`ct_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_customer_type` */

insert  into `zdb_customer_type`(`ct_id`,`org_parent_id`,`ct_name`,`ct_remark`) values (1,1,'小客户',' '),(2,1,'大客户',' ');

/*Table structure for table `zdb_customer_weihu` */

DROP TABLE IF EXISTS `zdb_customer_weihu`;

CREATE TABLE `zdb_customer_weihu` (
  `cw_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `saleman_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cw_id`)
) ENGINE=MyISAM AUTO_INCREMENT=264 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_customer_weihu` */

insert  into `zdb_customer_weihu`(`cw_id`,`saleman_id`,`shop_id`,`org_parent_id`,`type`,`remark`,`add_time`) values (1,3,129,1,2,'下车销单',1475405401),(2,3,1,1,2,'下车销单',1475806528),(3,3,1,1,4,'退货',1475889742),(4,1,1,1,6,'店铺日志',1475896922),(5,1,1,1,6,'店铺日志',1475896945),(6,1,1,1,6,'店铺日志',1475896949),(7,1,1,1,6,'店铺日志',1475897049),(8,1,1,1,6,'店铺日志',1475897464),(9,1,1,1,6,'店铺日志',1475897594),(10,1,1,1,6,'店铺日志',1475897599),(11,1,1,1,6,'店铺日志',1475897898),(12,1,1,1,6,'店铺日志',1475897905),(13,1,1,1,6,'店铺日志',1475897910),(14,1,1,1,6,'店铺日志',1475897914),(15,1,1,1,6,'店铺日志',1475897916),(16,1,1,1,6,'店铺日志',1475897917),(17,1,1,1,6,'店铺日志',1475897918),(18,1,1,1,6,'店铺日志',1475898726),(19,3,1,1,4,'退货',1475901759),(20,3,1,1,1,'陈列拍照',1475902863),(21,3,1,1,1,'陈列拍照',1475902901),(22,3,1,1,1,'陈列拍照',1475902979),(23,3,1,1,1,'陈列拍照',1475903036),(24,3,1,1,1,'陈列拍照',1475903443),(25,3,1,1,1,'陈列拍照',1475903459),(26,3,1,1,1,'陈列拍照',1475903479),(27,3,1,1,2,'下车销单',1475903581),(28,3,1,1,4,'退货',1475903592),(29,3,1,1,1,'陈列拍照',1475909176),(30,3,1,1,1,'陈列拍照',1475910219),(31,3,1,1,1,'陈列拍照',1475910487),(32,3,1,1,1,'陈列拍照',1475910991),(33,3,1,1,1,'陈列拍照',1475913263),(34,3,1,1,1,'陈列拍照',1475913568),(35,1,0,0,6,'店铺日志',1475915383),(36,1,1,1,6,'店铺日志',1475960050),(37,1,1,1,6,'店铺日志',1475960050),(38,1,1,1,6,'店铺日志',1475960080),(39,1,1,1,6,'店铺日志',1475960081),(40,1,1,1,6,'店铺日志',1475960111),(41,1,1,1,6,'店铺日志',1475960112),(42,0,1,1,6,'店铺日志',1475960128),(43,1,1,0,6,'店铺日志',1475960131),(44,1,1,1,6,'店铺日志',1475960133),(45,1,1,1,6,'店铺日志',1475960135),(46,1,0,1,6,'店铺日志',1475960138),(47,0,1,1,6,'店铺日志',1475960140),(48,1,1,0,6,'店铺日志',1475960143),(49,1,1,1,6,'店铺日志',1475960144),(50,1,1,1,6,'店铺日志',1475960145),(51,1,1,1,6,'店铺日志',1475960148),(52,1,0,1,6,'店铺日志',1475960150),(53,1,1,1,6,'店铺日志',1475960153),(54,1,1,1,6,'店铺日志',1475960155),(55,1,1,1,6,'店铺日志',1475960158),(56,1,1,1,6,'店铺日志',1475960161),(57,1,1,1,6,'店铺日志',1475960163),(58,1,1,1,6,'店铺日志',1475960165),(59,1,1,1,6,'店铺日志',1475960168),(60,1,1,1,6,'店铺日志',1475960171),(61,1,1,1,6,'店铺日志',1475960173),(62,0,1,1,6,'店铺日志',1475960174),(63,1,1,1,6,'店铺日志',1475960175),(64,1,1,1,6,'店铺日志',1475960178),(65,1,1,1,6,'店铺日志',1475960181),(66,1,1,1,6,'店铺日志',1475960183),(67,1,1,1,6,'店铺日志',1475960186),(68,1,1,1,6,'店铺日志',1475960188),(69,1,1,1,6,'店铺日志',1475960191),(70,1,1,1,6,'店铺日志',1475960193),(71,1,1,1,6,'店铺日志',1475960196),(72,1,1,1,6,'店铺日志',1475960198),(73,1,1,0,6,'店铺日志',1475960205),(74,1,1,1,6,'店铺日志',1475960215),(75,1,0,1,6,'店铺日志',1475960222),(76,0,1,1,6,'店铺日志',1475960225),(77,1,1,0,6,'店铺日志',1475960227),(78,1,0,1,6,'店铺日志',1475960230),(79,1,1,1,6,'店铺日志',1475960232),(80,1,1,1,6,'店铺日志',1475960235),(81,1,1,1,6,'店铺日志',1475960237),(82,1,1,1,6,'店铺日志',1475960240),(83,1,1,1,6,'店铺日志',1475960243),(84,1,1,1,6,'店铺日志',1475960245),(85,1,1,1,6,'店铺日志',1475960247),(86,1,1,1,6,'店铺日志',1475960249),(87,1,1,1,6,'店铺日志',1475960252),(88,1,1,1,6,'店铺日志',1475960254),(89,1,1,1,6,'店铺日志',1475960257),(90,1,1,1,6,'店铺日志',1475960259),(91,1,1,1,6,'店铺日志',1475960262),(92,1,1,1,6,'店铺日志',1475960275),(93,1,0,1,6,'店铺日志',1475981940),(94,1,0,1,6,'店铺日志',1475982544),(95,1,0,1,6,'店铺日志',1475982706),(96,1,0,1,6,'店铺日志',1475983449),(97,1,0,1,6,'店铺日志',1475983464),(98,1,0,1,6,'店铺日志',1475983575),(99,1,0,1,6,'店铺日志',1475983665),(100,3,1,1,2,'下车销单',1475993617),(101,3,1,1,4,'退货',1475993627),(102,1,1,1,6,'店铺日志',1475993724),(103,1,1,1,6,'店铺日志',1475993843),(104,3,1,1,3,'调换货',1475994154),(105,5,10,1,2,'下车销单',1475996142),(106,3,1,1,6,'店铺日志',1475996852),(107,3,1,1,3,'调换货',1475998116),(108,3,1,1,2,'下车销单',1475998665),(109,3,17,1,2,'下车销单',1475999653),(110,3,1,1,2,'下车销单',1476000061),(111,3,18,1,1,'陈列拍照',1476000360),(112,3,1,1,2,'下车销单',1476000896),(113,3,1,1,2,'下车销单',1476000955),(114,3,1,1,3,'调换货',1476005513),(115,3,1,1,2,'下车销单',1476006082),(116,3,2,1,2,'下车销单',1476006673),(117,3,2,1,2,'下车销单',1476006858),(118,3,2,1,2,'下车销单',1476006861),(119,3,2,1,2,'下车销单',1476006965),(120,3,2,1,2,'下车销单',1476007209),(121,3,3,1,6,'店铺日志',1476007598),(122,3,2,1,2,'下车销单',1476007600),(123,3,1,1,2,'下车销单',1476007759),(124,3,2,1,2,'下车销单',1476007781),(125,3,2,1,2,'下车销单',1476007836),(126,1,1,1,6,'店铺日志',1476008050),(127,1,1,1,6,'店铺日志',1476008186),(128,1,1,1,6,'店铺日志',1476008329),(129,3,2,1,2,'下车销单',1476008552),(130,1,1,1,6,'店铺日志',1476046515),(131,1,1,1,6,'店铺日志',1476046576),(132,1,1,1,6,'店铺日志',1476046577),(133,0,1,1,6,'店铺日志',1476046638),(134,1,1,0,6,'店铺日志',1476046666),(135,1,0,1,6,'店铺日志',1476046668),(136,0,1,1,6,'店铺日志',1476046670),(137,1,1,0,6,'店铺日志',1476046672),(138,1,0,1,6,'店铺日志',1476046676),(139,1,1,1,6,'店铺日志',1476046690),(140,1,1,1,6,'店铺日志',1476046700),(141,1,1,1,6,'店铺日志',1476046701),(142,1,1,1,6,'店铺日志',1476046703),(143,1,1,1,6,'店铺日志',1476046705),(144,1,1,1,6,'店铺日志',1476046706),(145,1,1,1,6,'店铺日志',1476046709),(146,1,1,1,6,'店铺日志',1476046711),(147,1,1,1,6,'店铺日志',1476046712),(148,1,1,1,6,'店铺日志',1476046716),(149,1,1,1,6,'店铺日志',1476046729),(150,1,1,1,6,'店铺日志',1476046730),(151,1,1,1,6,'店铺日志',1476046732),(152,1,1,1,6,'店铺日志',1476046738),(153,3,2,1,2,'下车销单',1476059787),(154,3,2,1,2,'下车销单',1476060054),(155,3,2,1,2,'下车销单',1476060224),(156,1,1,1,6,'店铺日志',1476060496),(157,1,1,1,6,'店铺日志',1476060637),(158,1,2,1,6,'店铺日志',1476060718),(159,1,1,1,6,'店铺日志',1476060756),(160,1,1,1,6,'店铺日志',1476060819),(161,3,1,1,2,'下车销单',1476061023),(162,3,1,1,2,'下车销单',1476061284),(163,3,2,1,2,'下车销单',1476061349),(164,1,1,1,6,'店铺日志',1476061475),(165,3,2,1,2,'下车销单',1476061536),(166,3,4,1,2,'下车销单',1476061582),(167,3,3,1,2,'下车销单',1476061662),(168,3,3,1,2,'下车销单',1476062075),(169,3,2,1,2,'下车销单',1476062149),(170,1,0,1,6,'店铺日志',1476063011),(171,1,0,1,6,'店铺日志',1476063056),(172,5,1,1,2,'下车销单',1476063302),(173,5,1,1,2,'下车销单',1476063377),(174,1,0,1,6,'店铺日志',1476063950),(175,3,2,1,2,'下车销单',1476064092),(176,3,1,1,2,'下车销单',1476064176),(177,3,1,1,2,'下车销单',1476064331),(178,3,1,1,2,'下车销单',1476064396),(179,3,28,1,2,'下车销单',1476064554),(180,3,3,1,2,'下车销单',1476065451),(181,3,5,1,2,'下车销单',1476065829),(182,3,3,1,2,'下车销单',1476065995),(183,3,4,1,4,'退货',1476066708),(184,1,1,1,6,'店铺日志',1476066840),(185,3,4,1,3,'调换货',1476066843),(186,3,6,1,2,'下车销单',1476067296),(187,3,1,1,1,'陈列拍照',1476067501),(188,5,1,1,1,'陈列拍照',1476068298),(189,5,1,1,1,'陈列拍照',1476068325),(190,3,3,1,1,'陈列拍照',1476069021),(191,3,5,1,1,'陈列拍照',1476069347),(192,3,5,1,1,'陈列拍照',1476069377),(193,5,1,1,2,'下车销单',1476070462),(194,5,2,1,2,'下车销单',1476078075),(195,1,0,1,6,'店铺日志',1476079447),(196,1,0,1,6,'店铺日志',1476080192),(197,1,1,1,6,'店铺日志',1476081697),(198,1,0,1,6,'店铺日志',1476081969),(199,5,2,1,4,'退货',1476082206),(200,1,0,1,6,'店铺日志',1476083699),(201,1,0,1,6,'店铺日志',1476084553),(202,1,0,1,6,'店铺日志',1476084634),(203,1,0,1,6,'店铺日志',1476084858),(204,1,0,1,6,'店铺日志',1476085755),(205,1,0,1,6,'店铺日志',1476086073),(206,1,0,1,6,'店铺日志',1476086165),(207,1,1,1,6,'店铺日志',1476086511),(208,1,1,1,6,'店铺日志',1476086524),(209,1,0,1,6,'店铺日志',1476086667),(210,1,1,1,6,'店铺日志',1476086833),(211,1,1,1,6,'店铺日志',1476090658),(212,3,17,1,2,'下车销单',1476091272),(213,1,1,1,6,'店铺日志',1476150955),(214,3,1,1,1,'陈列拍照',1476156002),(215,3,1,1,6,'店铺日志',1476156388),(216,3,1,1,2,'下车销单',1476156610),(217,1,1,1,6,'店铺日志',1476176030),(218,1,1,1,6,'店铺日志',1476176086),(219,1,1,1,6,'店铺日志',1476176541),(220,1,1,1,6,'店铺日志',1476176858),(221,1,1,1,6,'店铺日志',1476178258),(222,1,1,1,6,'店铺日志',1476178940),(223,1,1,1,6,'店铺日志',1476235476),(224,1,1,1,6,'店铺日志',1476237009),(225,1,1,1,6,'店铺日志',1476237200),(226,1,1,1,6,'店铺日志',1476240468),(227,1,1,1,6,'店铺日志',1476241036),(228,1,1,1,6,'店铺日志',1476241333),(229,1,1,1,6,'店铺日志',1476251477),(230,1,1,1,6,'店铺日志',1476251519),(231,1,1,1,6,'店铺日志',1476251535),(232,5,1,1,2,'下车销单',1476251567),(233,5,1,1,2,'下车销单',1476251588),(234,5,1,1,5,'清欠',1476251602),(235,5,1,1,2,'下车销单',1476251618),(236,1,1,1,6,'店铺日志',1476252011),(237,1,1,1,6,'店铺日志',1476252597),(238,3,1,1,6,'店铺日志',1478673466),(239,3,1,1,6,'店铺日志',1478673509),(240,3,1,1,5,'清欠',1478673641),(241,3,1,1,5,'清欠',1478673648),(242,3,1,1,5,'清欠',1478673651),(243,3,1,1,2,'下车销单',1478673726),(244,3,1,1,2,'下车销单',1478673735),(245,3,1,1,2,'下车销单',1478673740),(246,3,1,1,2,'下车销单',1478673743),(247,0,1,0,2,'下车销单',1478673842),(248,0,1,0,2,'下车销单',1478673852),(249,0,1,0,2,'下车销单',1478673858),(250,3,1,1,2,'下车销单',1478673908),(251,0,1,0,4,'退货',1478674189),(252,0,1,0,4,'退货',1478674199),(253,0,1,0,3,'调换货',1478674237),(254,0,1,0,2,'下车销单',1478674249),(255,0,1,0,3,'调换货',1478674251),(256,0,1,0,3,'调换货',1478674416),(257,0,1,0,3,'调换货',1478674447),(258,3,1,1,2,'下车销单',1478674462),(259,3,1,1,2,'下车销单',1478843003),(260,3,1,1,2,'下车销单',1478843016),(261,3,1,1,2,'下车销单',1478843026),(262,3,1,1,2,'下车销单',1478843037),(263,3,4,1,2,'下车销单',1478843053);

/*Table structure for table `zdb_cycle` */

DROP TABLE IF EXISTS `zdb_cycle`;

CREATE TABLE `zdb_cycle` (
  `org_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `index` tinyint(3) unsigned NOT NULL,
  `cust_ids` text,
  `visit_time` int(11) DEFAULT '0',
  PRIMARY KEY (`org_id`,`staff_id`,`index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_cycle` */

insert  into `zdb_cycle`(`org_id`,`staff_id`,`index`,`cust_ids`,`visit_time`) values (1,3,1,'2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,84,85,37,40,38,39,41,42,43,44,45,46,47,48,119,110,1,49',1476928078),(1,3,2,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30',0),(1,3,3,'15,16,18,20,21,14,17,19,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56',0),(1,3,4,'11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9,10,21,22,23,24,25,26,27,28,29,30',0),(1,3,5,'21,12,13,18,19,2,3,4,5,6,7,8,10,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53',0),(1,3,6,'3,4,5,6,7,14,15,18,19,20,21,1,2,8,9,10,11,12,13,16,17,22,23,24,25,26,27,28,29,30,31',0),(1,3,7,'1,2,3,14,15,16,17,18,19,20,21,4,5,6,7,8,9,10,11,12,13,22,23,24,25,26,27,28,29,30,31',0),(1,3,8,'7,8,9,10,15,16,17,18,19,20,21,1,2,3,4,5,6,11,12,13,14,22,23,24,25,26,27,28,29,30,31',0),(1,3,9,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40',0),(1,3,10,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20',0),(1,5,1,'2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,84,85,37,40,38,39,41,42,43,44,45,46,47,48,119,110,1,49',1476928092),(1,5,2,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30',0),(1,5,3,'15,16,18,20,21,14,17,19,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56',0),(1,5,4,'11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9,10,21,22,23,24,25,26,27,28,29,30',0),(1,5,5,'21,12,13,18,19,2,3,4,5,6,7,8,10,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53',0),(1,5,6,'3,4,5,6,7,14,15,18,19,20,21,1,2,8,9,10,11,12,13,16,17,22,23,24,25,26,27,28,29,30,31',0),(1,5,7,'1,2,3,14,15,16,17,18,19,20,21,4,5,6,7,8,9,10,11,12,13,22,23,24,25,26,27,28,29,30,31',0),(1,5,8,'7,8,9,10,15,16,17,18,19,20,21,1,2,3,4,5,6,11,12,13,14,22,23,24,25,26,27,28,29,30,31',0),(1,5,9,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40',0),(1,5,10,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20',0);

/*Table structure for table `zdb_depot_area` */

DROP TABLE IF EXISTS `zdb_depot_area`;

CREATE TABLE `zdb_depot_area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_name` varchar(30) NOT NULL DEFAULT '',
  `area_code` varchar(30) NOT NULL DEFAULT '',
  `area_note` varchar(255) NOT NULL DEFAULT '',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `area_parent` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`),
  KEY `depot_id` (`depot_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_area` */

/*Table structure for table `zdb_depot_area_goods` */

DROP TABLE IF EXISTS `zdb_depot_area_goods`;

CREATE TABLE `zdb_depot_area_goods` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`,`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_area_goods` */

/*Table structure for table `zdb_depot_brand` */

DROP TABLE IF EXISTS `zdb_depot_brand`;

CREATE TABLE `zdb_depot_brand` (
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`repertory_id`,`brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_brand` */

insert  into `zdb_depot_brand`(`repertory_id`,`brand_id`,`is_close`) values (0,16,0),(1,5,0),(1,6,0),(1,7,0),(1,8,0),(0,15,0),(0,14,0),(0,13,0),(0,12,0),(0,11,0),(0,10,0),(0,9,0),(1,16,0),(1,15,0),(1,14,0),(1,13,0),(1,12,0),(1,11,0),(1,10,0),(1,9,0);

/*Table structure for table `zdb_depot_class` */

DROP TABLE IF EXISTS `zdb_depot_class`;

CREATE TABLE `zdb_depot_class` (
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`repertory_id`,`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_class` */

insert  into `zdb_depot_class`(`repertory_id`,`class_id`,`is_close`) values (1,158,0),(1,185,0),(1,180,0),(1,157,0),(1,181,0),(1,179,0),(1,178,0),(1,177,0),(1,156,0),(1,184,0),(1,183,0),(1,182,0),(1,155,0),(1,176,0),(1,175,0),(1,174,0),(1,154,0),(1,167,0),(1,166,0),(1,165,0),(1,164,0),(1,153,0),(1,173,0),(1,172,0),(1,171,0),(1,170,0),(1,169,0),(1,163,0),(1,162,0),(1,161,0),(1,160,0),(1,159,0);

/*Table structure for table `zdb_depot_in` */

DROP TABLE IF EXISTS `zdb_depot_in`;

CREATE TABLE `zdb_depot_in` (
  `depot_in_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depot_in_code` varchar(30) NOT NULL DEFAULT '',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `in_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `in_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `in_total_price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `in_remark` varchar(100) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `checker_id` int(10) unsigned NOT NULL DEFAULT '0',
  `checker_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`depot_in_id`),
  UNIQUE KEY `depot_in_code` (`depot_in_code`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_in` */

insert  into `zdb_depot_in`(`depot_in_id`,`depot_in_code`,`depot_id`,`org_parent_id`,`in_type`,`in_status`,`in_total_price`,`in_remark`,`staff_id`,`create_id`,`create_time`,`checker_id`,`checker_time`) values (18,'I776420161111163881',1,1,1,2,'0.00','',2,2,1478847522,2,1478847525),(17,'I789220161111977989',1,1,1,2,'0.00','',2,2,1478847506,2,1478847529),(16,'I338920161111729112',1,1,1,2,'0.00','',2,2,1478847488,2,1478847532),(15,'I652320161111197934',1,1,1,2,'0.00','',2,2,1478847473,2,1478847536),(14,'I210420161111861106',1,1,1,2,'0.00','',2,2,1478846843,2,1478847023),(13,'I435920161111486406',1,1,1,2,'0.00','',2,2,1478846768,2,1478846774),(19,'I146520161114566506',1,1,1,2,'0.00','',2,2,1479088036,2,1479088040),(20,'I617220161114792825',1,1,1,2,'0.00','',2,2,1479088055,2,1479088060),(21,'I784320161114152130',1,1,1,2,'0.00','',2,2,1479088079,2,1479088109),(22,'I993620161114307009',1,1,1,2,'0.00','',2,2,1479088123,2,1479088154),(23,'I355620161114809936',1,1,1,2,'0.00','',2,2,1479088134,2,1479088151),(24,'I199220161114711691',1,1,1,2,'0.00','',2,2,1479088145,2,1479088148);

/*Table structure for table `zdb_depot_in_goods` */

DROP TABLE IF EXISTS `zdb_depot_in_goods`;

CREATE TABLE `zdb_depot_in_goods` (
  `depot_in_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `in_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `in_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`depot_in_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_in_goods` */

insert  into `zdb_depot_in_goods`(`depot_in_id`,`cv_id`,`org_parent_id`,`goods_id`,`goods_name`,`goods_spec`,`in_price`,`in_num`,`unit_name`) values (18,28,1,12,'黄连片','1kg','0.00','19.00','包'),(18,22,1,9,'川芎','1kg','0.00','10.00','包'),(18,20,1,8,'酒白芍(酒炙)','酒炙1kg(安徽)','0.00','19.00','包'),(17,26,1,11,'麦冬','1kg','0.00','19.00','包'),(17,7,1,3,'注射用盐酸大观霉素(卓青)','2g','0.00','19.00','盒'),(17,4,1,2,'阿莫西林胶囊','0.25g*24s','0.00','19.00','盒'),(17,1,1,1,'注射用头孢唑啉钠','1g','0.00','19.00','瓶'),(16,24,1,10,'牡丹皮','1kg(安徽)','0.00','10.00','包'),(16,10,1,4,'灵芝孢子粉（破壁）','2g*60包','0.00','10.00','盒'),(15,18,1,7,'当归片','1kg','0.00','10.00','包'),(15,16,1,6,'炙黄芪','1kg','0.00','10.00','包'),(15,13,1,5,'利多卡因氯已定气雾剂','60g','0.00','10.00','瓶'),(14,10,1,4,'灵芝孢子粉（破壁）','2g*60包','0.00','19.00','盒'),(13,7,1,3,'注射用盐酸大观霉素(卓青)','2g','0.00','1000.00','盒'),(13,4,1,2,'阿莫西林胶囊','0.25g*24s','0.00','1000.00','盒'),(13,1,1,1,'注射用头孢唑啉钠','1g','0.00','1000.00','瓶'),(1,32,1,32,'娃娃菜','600g','0.00','1.00','袋'),(1,33,1,33,'白萝卜','500g','0.00','1.00','斤'),(1,34,1,34,'蒜台','500g','0.00','1.00','斤'),(1,35,1,35,'荷兰土豆','500g','0.00','1.00','斤'),(1,36,1,36,'金针菇','150g','0.00','1.00','袋'),(1,37,1,37,'西红柿','500g','0.00','1.00','斤'),(1,38,1,38,'西葫芦','500g','0.00','1.00','斤'),(1,39,1,39,'韭菜','500g','0.00','1.00','斤'),(1,40,1,40,'油麦','500g','0.00','1.00','斤'),(1,41,1,41,'生菜','500g','0.00','1.00','斤'),(1,42,1,42,'空心菜','500g','0.00','1.00','斤'),(1,43,1,43,'红洋葱','500g','0.00','1.00','斤'),(2,1,1,1,'紫薯','500g','0.00','1.00','斤'),(2,45,3,45,'新疆大枣','500g','0.00','10000.00','斤'),(3,1,1,1,'紫薯','500g','0.00','10.00','斤'),(3,19,1,19,'油菜','500g','0.00','1.00','斤'),(4,2,1,2,'麻山药','500g','0.00','100.00','斤'),(4,3,1,3,'绿甘兰','500g','0.00','6.00','斤'),(4,4,1,4,'金瓜','500g','0.00','6.00','斤'),(4,5,1,5,'地瓜','500g','0.00','6.00','斤'),(4,6,1,6,'香菇','500g','0.00','5.00','斤'),(4,7,1,7,'青椒','500g','0.00','5.00','斤'),(4,8,1,8,'生姜','500g','0.00','5.00','斤'),(4,9,1,9,'木耳菜','500g','0.00','5.00','斤'),(5,1,1,1,'紫薯','500g','0.00','200.00','斤'),(6,1,1,1,'紫薯','500g','0.00','1000.00','斤'),(7,60,1,43,'红洋葱','500g','0.00','10.00','袋'),(8,1,1,1,'紫薯','500g','0.00','4.60','斤'),(9,1,0,1,'紫薯','500g','0.00','4.80','斤'),(10,64,1,60,'热水壶','2L','0.00','10.00','个'),(10,65,1,61,'豆浆机','1.5L','0.00','15.00','个'),(11,1,1,1,'紫薯','500g','0.00','3.60','斤'),(12,1,1,1,'紫薯','500g','0.00','5.66','斤'),(12,2,1,2,'麻山药','500g','0.00','5.70','斤'),(19,39,1,17,'通心络胶囊','0.26g*40s','0.00','17.00','瓶'),(20,37,1,16,'维胺酯胶囊','25mg*24s','0.00','565.00','盒'),(21,35,1,15,'保济丸','3.7g*20','0.00','56.00','盒'),(22,10,1,4,'灵芝孢子粉（破壁）','2g*60包','0.00','7.00','盒'),(22,24,1,10,'牡丹皮','1kg(安徽)','0.00','8.00','包'),(23,35,1,15,'保济丸','3.7g*20','0.00','88.00','盒'),(24,33,1,14,'舒肝解郁胶囊','0.36g*28s','0.00','476.00','盒');

/*Table structure for table `zdb_depot_info` */

DROP TABLE IF EXISTS `zdb_depot_info`;

CREATE TABLE `zdb_depot_info` (
  `repertory_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repertory_code` varchar(30) NOT NULL DEFAULT '',
  `repertory_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_name` varchar(30) NOT NULL DEFAULT '',
  `repertory_info` varchar(100) NOT NULL DEFAULT '',
  `repertory_address` varchar(100) NOT NULL DEFAULT '',
  `repertory_user` varchar(30) NOT NULL DEFAULT '',
  `repertory_tel` varchar(30) NOT NULL DEFAULT '',
  `repertory_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`repertory_id`),
  UNIQUE KEY `repertory_code` (`repertory_code`),
  UNIQUE KEY `repertory_name` (`repertory_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_info` */

insert  into `zdb_depot_info`(`repertory_id`,`repertory_code`,`repertory_parent`,`repertory_name`,`repertory_info`,`repertory_address`,`repertory_user`,`repertory_tel`,`repertory_close`) values (1,'001',0,'新乐仓库','测试专用','新乐市','张三','031188562362',0),(2,'002',0,'藁城仓库','测试专用','藁城市','李四','031156236326',1);

/*Table structure for table `zdb_depot_org` */

DROP TABLE IF EXISTS `zdb_depot_org`;

CREATE TABLE `zdb_depot_org` (
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`repertory_id`,`org_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_org` */

insert  into `zdb_depot_org`(`repertory_id`,`org_parent_id`,`is_close`) values (1,1,0),(1,3,0);

/*Table structure for table `zdb_depot_out` */

DROP TABLE IF EXISTS `zdb_depot_out`;

CREATE TABLE `zdb_depot_out` (
  `depot_out_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depot_out_code` varchar(30) NOT NULL DEFAULT '',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `out_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `out_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `out_total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `out_remark` varchar(100) NOT NULL DEFAULT '',
  `send_staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `checker_id` int(10) unsigned NOT NULL DEFAULT '0',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`depot_out_id`),
  UNIQUE KEY `depot_out_code` (`depot_out_code`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_out` */

insert  into `zdb_depot_out`(`depot_out_id`,`depot_out_code`,`depot_id`,`org_parent_id`,`out_status`,`out_type`,`out_total_price`,`out_remark`,`send_staff_id`,`create_id`,`create_time`,`checker_id`,`check_time`) values (6,'O521720161111567082',1,1,1,1,'0.00','',0,2,1478846811,0,0);

/*Table structure for table `zdb_depot_out_goods` */

DROP TABLE IF EXISTS `zdb_depot_out_goods`;

CREATE TABLE `zdb_depot_out_goods` (
  `depot_out_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `out_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `out_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`depot_out_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_out_goods` */

insert  into `zdb_depot_out_goods`(`depot_out_id`,`cv_id`,`org_parent_id`,`goods_id`,`goods_name`,`goods_spec`,`out_price`,`out_num`,`unit_name`) values (6,8,1,3,'注射用盐酸大观霉素(卓青)','2g','0.00','33.00','件'),(6,5,1,2,'阿莫西林胶囊','0.25g*24s','0.00','188.00','件'),(6,2,1,1,'注射用头孢唑啉钠','1g','0.00','17.00','件');

/*Table structure for table `zdb_depot_stock` */

DROP TABLE IF EXISTS `zdb_depot_stock`;

CREATE TABLE `zdb_depot_stock` (
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `small_stock` decimal(8,2) NOT NULL DEFAULT '0.00',
  `stock_string` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`depot_id`,`goods_id`,`org_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_stock` */

insert  into `zdb_depot_stock`(`depot_id`,`goods_id`,`org_parent_id`,`small_stock`,`stock_string`) values (1,13,1,'0.00',''),(1,5,1,'10.00','10 瓶'),(1,7,1,'10.00','10 包'),(1,6,1,'10.00','1 件 '),(1,4,1,'36.00','3 包 6 盒'),(1,10,1,'18.00','1 件 3 包'),(1,9,1,'10.00','10 包'),(1,12,1,'19.00','19 包'),(1,8,1,'19.00','1 件 4 包'),(1,3,1,'1019.00','101 包 9 盒'),(1,1,1,'1019.00','101 包 9 瓶'),(1,11,1,'19.00','1 件 7 包'),(1,2,1,'1019.00','101 中包 9 盒'),(1,15,1,'144.00','1 件 44 盒'),(1,16,1,'565.00','5 包 65 盒'),(1,17,1,'17.00','2 包 1 瓶'),(1,14,1,'476.00','79 包 2 盒');

/*Table structure for table `zdb_depot_stock_log` */

DROP TABLE IF EXISTS `zdb_depot_stock_log`;

CREATE TABLE `zdb_depot_stock_log` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_in_out` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `inout_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `datetime` int(10) unsigned NOT NULL DEFAULT '0',
  `small_stock` decimal(8,2) NOT NULL DEFAULT '0.00',
  `bianhua` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`rec_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_stock_log` */

insert  into `zdb_depot_stock_log`(`rec_id`,`depot_id`,`goods_id`,`org_parent_id`,`is_in_out`,`inout_type`,`datetime`,`small_stock`,`bianhua`) values (48,1,7,1,1,1,1478847536,'10.00','经销商入库+10.00'),(47,1,6,1,1,1,1478847536,'10.00','经销商入库+10.00'),(46,1,5,1,1,1,1478847536,'10.00','经销商入库+10.00'),(45,1,10,1,1,1,1478847532,'10.00','经销商入库+10.00'),(44,1,4,1,1,1,1478847532,'29.00','经销商入库+10.00'),(43,1,11,1,1,1,1478847529,'19.00','经销商入库+19.00'),(42,1,3,1,1,1,1478847529,'1019.00','经销商入库+19.00'),(41,1,2,1,1,1,1478847529,'1019.00','经销商入库+19.00'),(40,1,1,1,1,1,1478847529,'1019.00','经销商入库+19.00'),(39,1,12,1,1,1,1478847525,'19.00','经销商入库+19.00'),(38,1,9,1,1,1,1478847525,'10.00','经销商入库+10.00'),(37,1,8,1,1,1,1478847525,'19.00','经销商入库+19.00'),(36,1,4,1,1,1,1478847023,'19.00','经销商入库+19.00'),(35,1,3,1,1,1,1478846774,'1000.00','经销商入库+1000.00'),(34,1,2,1,1,1,1478846774,'1000.00','经销商入库+1000.00'),(33,1,1,1,1,1,1478846774,'1000.00','经销商入库+1000.00'),(49,1,17,1,1,1,1479088040,'17.00','经销商入库+17.00'),(50,1,16,1,1,1,1479088060,'565.00','经销商入库+565.00'),(51,1,15,1,1,1,1479088109,'56.00','经销商入库+56.00'),(52,1,14,1,1,1,1479088148,'476.00','经销商入库+476.00'),(53,1,15,1,1,1,1479088151,'144.00','经销商入库+88.00'),(54,1,4,1,1,1,1479088154,'36.00','经销商入库+7.00'),(55,1,10,1,1,1,1479088154,'18.00','经销商入库+8.00');

/*Table structure for table `zdb_depot_warning` */

DROP TABLE IF EXISTS `zdb_depot_warning`;

CREATE TABLE `zdb_depot_warning` (
  `warning_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `warning_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1固定值2自动',
  `warning_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '固定预警值',
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`warning_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_depot_warning` */

/*Table structure for table `zdb_goods_brand` */

DROP TABLE IF EXISTS `zdb_goods_brand`;

CREATE TABLE `zdb_goods_brand` (
  `brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(30) NOT NULL DEFAULT '',
  `brand_logo` varchar(100) NOT NULL DEFAULT '',
  `remark` varchar(100) NOT NULL DEFAULT '',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `brand_name` (`brand_name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_goods_brand` */

insert  into `zdb_goods_brand`(`brand_id`,`brand_name`,`brand_logo`,`remark`,`is_close`,`depot_id`) values (6,'白敬宇制药','2016-11-09/5822cf3760f8d.jpg','',0,0),(5,'山东鲁抗医药','2016-11-09/5822cf0d170dc.jpg','',0,0),(7,'九芝堂','2016-11-09/5822cf65e9b1e.jpg','',0,0),(8,'武汉健民','2016-11-09/5822cf8f214f9.jpg','',0,0),(9,'王老吉','2016-11-11/58258c2d2377c.png','',0,0),(10,'华邦制药','2016-11-11/58258c7d072c3.png','',0,0),(11,'以岭药业','2016-11-11/58258cd277780.png','',0,0),(12,'云南白药','2016-11-11/58258d369d048.png','',0,0),(13,'石药集团','2016-11-11/58258d7f574e9.png','',0,0),(14,'康恩贝','2016-11-11/58258df8c1440.png','',0,0),(15,'三金药业','2016-11-11/58258e30df878.png','',0,0),(16,'康弘药业','2016-11-11/58258e7d8cf8d.png','',0,0);

/*Table structure for table `zdb_goods_class` */

DROP TABLE IF EXISTS `zdb_goods_class`;

CREATE TABLE `zdb_goods_class` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(30) NOT NULL DEFAULT '',
  `parent_class` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(100) NOT NULL DEFAULT '',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=MyISAM AUTO_INCREMENT=186 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_goods_class` */

insert  into `zdb_goods_class`(`class_id`,`class_name`,`parent_class`,`remark`,`is_close`,`depot_id`) values (185,'保健品',158,'',0,0),(184,'一次性用品',156,'',0,0),(183,'手术器械',156,'',0,0),(182,'医疗设备',156,'',0,0),(181,'计生药品',157,'',0,0),(180,'消毒用品',158,'',0,0),(179,'进口计生用品',157,'',0,0),(178,'计生器械',157,'',0,0),(177,'计生试剂',157,'',0,0),(176,'参茸贵细类',155,'',0,0),(175,'精制中药饮片',155,'',0,0),(174,'中药饮片',155,'',0,0),(173,'酰胺醇类',153,'',0,0),(172,'氨基糖苷类',153,'',0,0),(171,'大环内脂类',153,'',0,0),(170,'头孢菌素类',153,'',0,0),(169,'青霉素类',153,'',0,0),(167,'外用药',154,'',0,0),(166,'安神药',154,'',0,0),(165,'泻下药',154,'',0,0),(164,'解表药',154,'',0,0),(163,'心血管系统药',153,'',0,0),(162,'神经系统用药',153,'',0,0),(161,'解热镇痛药',153,'',0,0),(160,'非抗生素类抗感染药',153,'',0,0),(159,'抗生素类抗感染药',153,'',0,0),(158,'非药用品',0,'',0,0),(157,'计生用品',0,'',0,0),(156,'医疗器械',0,'',0,0),(155,'中药',0,'',0,0),(154,'中成药',0,'',0,0),(153,'西药',0,'',0,0);

/*Table structure for table `zdb_goods_info` */

DROP TABLE IF EXISTS `zdb_goods_info`;

CREATE TABLE `zdb_goods_info` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_code` varchar(30) NOT NULL DEFAULT '',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_id` int(10) unsigned NOT NULL DEFAULT '0',
  `main_img` varchar(100) NOT NULL DEFAULT '',
  `goods_desc` text,
  `goods_convert_s` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `goods_convert_m` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `goods_convert_b` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`),
  UNIQUE KEY `goods_code` (`goods_code`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_goods_info` */

insert  into `zdb_goods_info`(`goods_id`,`goods_code`,`goods_name`,`goods_spec`,`brand_id`,`class_id`,`main_img`,`goods_desc`,`goods_convert_s`,`goods_convert_m`,`goods_convert_b`,`is_close`,`depot_id`) values (1,'AAB00000002','注射用头孢唑啉钠','1g',5,153,'2016-11-11/58257da5dd946.jpg','<p>药物成分本品主要成分为头孢唑林钠，其化学名为(6R,7R)-3-[[(5-甲基-1,3,4-噻二唑-2-基)硫]甲基]-7-[(1H-四唑-1-基)乙酰氨基]-8-氧代-5-硫杂-1-氮杂双环[4.2.0]辛-2-烯-2-甲酸钠盐。分子式：C14H13N8NaO4S3分子量：476.50&amp;lt;性状本品为白色或类白色粉末。&amp;lt;肌内、静脉注射或静脉滴注，临用前加灭菌注射用水适量使溶解。成人一次0.5-1g，一日3-4次给药。小儿每日每公斤20-40mg，分3-4次给药。适应症（或功能主治）本品可用于治疗敏感所致的呼吸道感染、尿路感染、皮肤软组织感染、骨髓炎、败血症、感染性心内膜用法用量肌内、静脉注射或静脉滴注，临用前加灭菌注射用水适量使溶解。成人一次0.5-1g，一日3-4次给药。小儿每日每公斤20-40mg，分3-4次给药不良反应1静脉注射发生的血栓性静脉炎和肌内注射区疼痛均较头孢噻吩少而轻。2药疹发生率为1.1%，嗜酸粒细胞增高的发生率为1.7%，偶有药物热。3个别病人可出现暂时性血清氨基转移酶、碱性磷酸酶升高。4肾功能减退病人应用高剂量(每日12g)的本品时可出现脑病反应。5白念珠菌二重感染偶见。注意事项1对青霉素过敏，肝、肾功能不全者慎用。2本品乳汁中含量低，但哺乳期妇女用药时仍宜暂停哺乳。3本品在老年人中T1/2较年轻人明显延长，应按肾功能适当减量或延长给药间期。4早产儿及1个月以下的新生儿不推荐应用本品。[展开]1对青霉素过敏，肝、肾功能不全者慎用。2本品乳汁中含量低，但哺乳期妇女用药时仍宜暂停哺乳。3本品在老年人中T1/2较年轻人明显延长，应按肾功能适当减量或延长给药间期。4早产儿及1个月以下的新生儿不推荐应用本品。禁忌禁忌：对头孢菌素过敏者及有青霉素过敏性休克或即刻反应史者禁用本品。药物相互作用1本品与下列药物有配伍禁忌，不可同瓶滴注：硫酸阿米卡星、硫酸卡那霉素、盐酸金霉素、盐酸土霉素、盐酸四环素、葡萄糖酸红霉素、硫酸多粘菌素B、粘菌素甲磺酸钠，戊巴比妥、葡萄糖酸钙、葡萄糖酸钙。2本品与庆大霉素或阿米卡星联合应用，在体外能增强抗菌作用。3本品与强利尿药合用有增加肾毒性的可能，与氨基糖苷抗生素合用可能增加后者的肾毒性。4丙磺舒可使本品血药浓度提高，血半衰期延长贮存条件阴凉孕妇及哺乳期妇女用药在医师指导下使用</p>',1,10,255,0,0),(2,'BAA00000014','阿莫西林胶囊','0.25g*24s',5,159,'2016-11-11/58257e9352e4c.jpg','<p>药物成分本品主要成分为阿莫西林。性状本品为胶囊剂。适应症（或功能主治）1.溶血链球菌、肺炎链球菌、葡萄球菌或流感嗜血杆菌所致中耳炎、鼻窦炎、咽炎、扁桃体炎等上呼吸道感染。 2.大肠埃希菌、奇异变形杆菌或粪肠球菌所致的泌尿生殖道感染。 3.溶血链球菌、葡萄球菌或大肠埃希菌所致的皮肤软组织感染。 4.溶血链球菌、肺炎链球菌、葡萄球菌或流感嗜血杆菌所致急性支气管炎、肺炎等下呼吸道感染。 5.急性单纯性淋病。 6.本品尚可用于治疗伤寒、伤寒带菌者及钩端螺旋体病；阿莫西林亦可与克拉霉素、兰索拉唑三联用药根除胃、十二指肠幽门螺杆菌，降低消化道溃疡复发率。用法用量口服。成人一次0.5g，每6～8小时1次，一日剂量不超过4g。小儿一日剂量按体重20～40mg/Kg，每8小时1次；3个月以下婴儿一日剂量按体重30mg/Kg，每12小时1次。肾功能严重损害患者需调整给药剂量，其中内生肌酐清除率为10～30ml/分钟的患者每12小时0.25～0.5g；内生肌酐清除率小于10ml/分钟的患者每24小时0.25～0.5g.不良反应1．恶心、呕吐、腹泻及假膜性肠炎等胃肠道反应。 2．皮疹、药物热和哮喘等过敏反应。 3．贫血、血小板减少、嗜酸性粒细胞增多等。 4．血清氨基转移酶可轻度增高。 5．由念珠菌或耐药菌引起的二重感染。 6．偶见兴奋、焦虑、失眠、头晕以及行为异常等中枢神经系统症状。注意事项1．青霉素类口服药物偶可引起过敏性休克，尤多见于有青霉素或头孢菌素过敏史的患者。用药前必须详细询问药物过敏史并作青霉素皮肤试验。如发生过敏性休克，应就地抢救，予以保持气道畅通、吸氧及应用肾上腺素、糖皮质激素等治疗措施。 2．传染性单核细胞增多症患者应用本品易发生皮疹，应避免使用。 3．疗程较长患者应检查肝、肾功能和血常规。 4．阿莫西林可导致采用Benedit或Fehling试剂的尿糖试验出现假阳性。 5．下列情况应慎用：(1)有哮喘、枯草热等过敏性疾病史者。(2)老年人和肾功能严重损害时可能须调整剂量.禁忌青霉素过敏及青霉素皮肤试验阳性患者禁用药物相互作用1.丙磺舒竞争性地减少本品的肾小管分泌，两者同时应用可引起阿莫西林血浓度升高、半衰期延长。 2．氯霉素、大环内酯类、磺胺类和四环素类药物在体外干扰阿莫西林的抗菌作用，但其临床意义不明.贮存条件常温孕妇及哺乳期妇女用药无</p>',1,10,255,0,0),(3,'AAE00000010','注射用盐酸大观霉素(卓青)','2g',5,159,'2016-11-11/58257e452e82c.jpg','<p>药物成分本品主要成分为大观霉素&amp;lt;性状本品为白色或类白色结晶性粉末适应症（或功能主治）本品主要用于淋病奈瑟菌所致的尿道炎、前列腺炎、宫颈炎和直肠感染，以及对青霉素、四环用法用量仅供肌内注射。不良反应个别患者偶可出现注射部位疼痛，短暂眩晕，恶心，呕吐及失眠等；偶见发热，皮疹等过敏反应和血红蛋白，红细胞压积减少&amp;lt;br&amp;lt; td=&amp;quot;&amp;quot;&amp;gt;注意事项注意事项：1本品不得静脉给药。应在臀部肌肉外上方作深部肌内注射，注射部位一次注射量不超过2g(5ml)。2本品与青霉素类无交叉过敏性。3发生不良反应时，对严重过敏反应者可给予肾上腺素、皮质激素及（或）抗组胺药物，保持气道通畅，给氧等。4孕妇禁用。5哺乳期妇女用药尚不明确。若使用本品，应暂停哺乳。6由于本品的稀释液中含0.9%的苯甲醇，可能引起新生儿产生致命性喘息综合征，故新生儿禁用。7小儿淋病患者对青霉素类或头胞菌素类过敏者可应用本品。&amp;lt;贮藏条件：密封，在干燥处保存。&amp;lt;包装：2克(200万单位)(按C14H24N207计)&amp;lt;有效期：36个月禁忌对氨基糖苷类抗生素过敏者禁用。药物相互作用据文献资料报道，本品与碳酸锂合用，可使碳酸锂在个别患者身上出现毒性作用。贮存条件常温孕妇及哺乳期妇女用药在医师指导下使用</p>',1,10,255,0,0),(4,'ZGQ00000009','灵芝孢子粉（破壁）','2g*60包',7,176,'2016-11-11/58257af8ad8a4.jpg','<p>《本草纲目》对灵芝所处地理记载：赤芝生霍山，青芝生泰山，黄芝生嵩山，白芝生华山，黑芝生常山，紫芝生高山夏峪。</p><p>灵芝孢子是灵芝在生长成熟期，从灵芝菌褶中弹射出来的极其微小的卵形生殖细胞即灵芝的种子。每个灵芝孢子只有4－6个微米，是活体生物体，双壁结构，外被坚硬的几丁质纤维素所包围，人体很难充分吸收。破壁后更适合人体肠胃直接吸收。它凝聚了是灵芝的精华，具有灵芝的全部遗传物质和保健作用。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h2 class=\"title-text\" style=\"margin: 0px; padding: 0px 8px 0px 18px; font-size: 22px; color: rgb(0, 0, 0); float: left; line-height: 24px; font-weight: 400; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\">破壁技术</h2><p><br/></p><p><br/></p><p><br/></p><p>灵芝孢子有二层由几丁质和葡聚糖构成的孢壁(多醣壁),且具有同心圆的层网结构, 质地坚韧, 耐酸碱, 极难氧化分解, 因此限制了人们对孢内有效物质的消化吸收。为了充分利用灵芝孢子内的有效物质, 必需对孢子粉进行破壁, 以便于人们对其有效物质的利用。<span style=\"font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: default; padding: 0px 2px;\">[3]</span><a class=\"sup-anchor\" style=\"color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;\">&nbsp;</a>&nbsp;科学实验证实，服用未破壁的孢子，只有10%~20%的有效成分能被人体吸收，而破壁之后有效成分吸收率在90%以上。因此，选择灵芝孢子粉要看配方和破壁工艺，<span style=\"font-weight: 700;\">选择综合法破壁技术（如抗氧化低温物理复合破壁技术）的灵芝孢子粉可以保证破壁率。</span><br/></p><p>灵芝抱子粉的破壁工艺大致可分为5类:</p><p>一、生物法</p><p>二、化学法</p><p>三、物理法</p><p>四、机械法</p><p>五、综合法：多种新型的方法联用，&nbsp;比如抗氧化低温物理复合破壁技术，可以使破壁率达到98%以上。</p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">处理方法</h3><p>由于灵芝孢子孢壁独特的结构，必须采用先进的破壁技术和恰当的处理方法，否则灵芝孢子不容易破壁，破壁后灵芝孢子内的活性物质也易被高温、酸碱等破环。灵芝孢子破壁方式或者存在破壁率不高，或者容易破坏灵芝孢子的活性成分等问题，而低温破壁技术首先在适宜的条件下激活孢子的活性，然后在低温条件下对孢子进行破壁，破壁率可达98%以上，显著提高了人体的吸收率，且能保持有效成份不被破坏。因此抗氧化技术也尤其重要。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h2 class=\"title-text\" style=\"margin: 0px; padding: 0px 8px 0px 18px; font-size: 22px; color: rgb(0, 0, 0); float: left; line-height: 24px; font-weight: 400; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\">主要成分作用</h2><p><br/></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\"><br/></h3><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">灵芝多糖<br/></h3><p><br/></p><p>可增强免疫系统的机能；降低血压，预防心血管疫病的产生；加速血液微循环 , 提高血液供氧能力 , 降低机体静止状态下的无效耗氧量等。<span style=\"font-weight: 700;\">灵芝的多种药理活性大多和灵芝多糖有关。</span></p><p><span style=\"font-weight: 700;\"><br/></span></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">三萜类灵芝酸</h3><p><br/></p><p>改善微循环，降低胆固醇，避免血管硬化；强化肝脏、脾脏及肠胃功能、健全消化器官的运作。</p><p>天然有机锗：</p><p>能增强人体血液供养量，促进血液新陈代谢，消除体内自由基，防止细胞老化；可以从癌细胞中夺取电子，使其电位下降，从而抑制癌细胞恶化和扩散。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">腺嘌呤核苷</h3><p><br/></p><p>抑制血小板凝集、防止血栓形成。</p><p>微量元素硒：</p><p>微量元素有机硒：预防癌症，减轻疼痛，预防前列腺病变，与维生素C并用，可预防心脏病，增强性机能，硒元素是中国政府公开支持的第一个微量元素。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h2 class=\"title-text\" style=\"margin: 0px; padding: 0px 8px 0px 18px; font-size: 22px; color: rgb(0, 0, 0); float: left; line-height: 24px; font-weight: 400; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\">部分临床效果</h2><p><br/></p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\"><br/></h3><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">免疫作用</h3><p><br/></p><p><br/></p><p>灵芝孢子粉能激活巨噬细胞的吞噬功能，对糖皮质激素有拮抗作用。灵芝孢子粉水提取物能提高小鼠腹腔巨噬细胞酸性磷酸酶、&quot;β -葡萄糖醛酸酶活性及过氧化氢含量，并能对抗糖皮质激素对小鼠脾脏DNA合成的抑制作用及促进肝脏脂肪堆积的作用。给小鼠服食灵芝孢子粉对小鼠腹腔巨噬细胞吞噬和血清凝集功能有增强作用。小鼠脾指数和酸性非特异性酯酶染色也相应增加。<span style=\"font-weight: 700;\">说明灵芝孢子粉能增强非特异性免疫功能，提高体液免疫水平和细胞免疫水平G。</span></p><p><span style=\"font-weight: 700;\"><br/></span></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">保肝作用</h3><p><br/></p><p>灵芝孢子粉对D-氨基半乳糖所致肝损害的保护作用：试验小鼠灌胃（g）及腹腔注射（p）两种途径给药，单用组小鼠肝功能ALT活性明显升高，细胞出现气球样肿胀及点状坏死，肝脏受损严重，受试小鼠死亡率高，与生理盐水对照组有极显著的差异。而D-氨基半乳糖与灵芝孢子粉同时使用时与生理盐水对照组相比小鼠已无死亡现象，半数小鼠肝脏病理学检查趋于正常。ALT 活性与对照组相比虽仍有差异，但较之单用组已显著降低（P&lt;0.05）。<span style=\"font-weight: 700;\">说明灵芝孢子粉对D-氨基半乳糖所致肝损伤具有明显的拮抗作用。</span></p><p><span style=\"font-weight: 700;\"><br/></span></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">抗癌作用</h3><p><br/></p><p>灵芝孢子粉有明显的抑制移植性动物肿瘤小鼠肝癌肿瘤、小鼠肉瘤—180（S-180）及小鼠网细胞肉瘤（L-II）生长的作用。在2-8g/（Kg.d）的剂量范围内灌胃给药7d 后对小鼠肝癌、S-180和L-II的抑瘤率分在52.2-86.1%，44.9-82.0%和54.0%-79.6%之间，并且有明显的量效关系。&nbsp;灵芝孢子粉的醇提取物在体外具有直接抑制癌细胞生长的作用。灵芝孢子粉醇提取物在1mg/mL时对人宫颈癌细胞Hela、人肝癌细胞HepG2、人胃癌细胞SGC7901、人白血病细胞HL60和来源于小鼠的白血病细胞L1210 均具有较强的杀伤能力<span style=\"font-weight: 700;\">。</span></p><p><span style=\"font-weight: 700;\"><br/></span></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">降血脂作用</h3><p><br/></p><p>口服灵芝孢子粉对正常大鼠血清总胆固醇（TCHO）和甘油三酯（TG）无明显影响，而能明显降低高血脂大鼠的血清总胆固醇和甘油三酯水平。对高血脂大鼠ig灵芝孢子粉（1.0-5.0g/Kg） 后，TCHO降低43.9%-56.1%，TG 降低了36.5%-52.5%<span style=\"font-weight: 700;\">其作用强度比氯贝丁脂500mg/Kg 高</span>。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">高血压的功效</h3><p><br/></p><p>灵芝孢子粉对降低血压有明显功效，高血压患者除血液中血脂含量偏高外，还伴有神疲、乏力、头晕目眩、气短、胸闷气憋、食欲不良、腰酸腿软等症状。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">对神经衰弱的效果</h3><p><br/></p><p>据北京、武汉等有关医院用灵芝孢子粉治疗<a target=\"_blank\" href=\"http://baike.baidu.com/view/47715.htm\" style=\"color: rgb(19, 110, 194); text-decoration: none;\">神经衰弱</a>患者108例，30天为1个疗程，连服2-3个疗程。结果：患者的失眠多梦、心悸健忘、腰腿酸软、神疲乏力、烦躁等各种症状有明显改善，有效率为90%以上。</p><p><br/></p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><h3 class=\"title-text\" style=\"margin: 0px; padding: 0px; font-size: 18px; font-weight: 400;\">对糖尿病的效果</h3><p><br/></p><p>灵芝孢子粉具有改善胰脏血液循环，提高胰脏生理功能，降低血糖，改善糖尿病人症状等。据协和等医院用灵芝孢子粉治疗气阴两虚症状的糖尿病人。结果表明：应试病人用灵芝孢子粉治疗后，总有效率为88.5%。</p><p><br/></p>',1,10,255,0,0),(5,'HQS00000001','利多卡因氯已定气雾剂','60g',8,153,'2016-11-11/582578a679a0a.jpg','<p>药物成分利多卡因1.2g与醋酸氯己定0.3g性状本品为无色或微黄色的澄清液体；贮存于密闭的耐压容器中，揿压阀门，药液即呈雾粒喷出。适应症（或功能主治）本品用于一般割伤、擦伤、软组织损伤、蚊虫叮咬、热痱瘙痒、灼伤、晒伤等用法用量外用。距离患处10～20cm喷至患处，一日喷1～3次或视需要喷数次。不良反应偶见皮肤刺激如烧灼感，或过敏反应如皮疹、瘙痒等。注意事项1 避免撞击、受热和防火。 2 避免接触眼睛、口腔和鼻内。 3 当本品性状发生改变时禁用。 4 儿童必须在成人监护下使用。 5 请将此药品放在儿童不能接触的地方。禁忌对本品过敏者禁用药物相互作用1 本品不应与肥皂、高锰酸钾（灰锰氧）以及磺胺粉同用。 2 如正在使用其他药品，使用本品前请咨询医师或药师。贮存条件阴凉孕妇及哺乳期妇女用药尚不明确</p>',1,12,12,0,0),(6,'ZAQ00000021','炙黄芪','1kg',8,174,'2016-11-11/58257710530bc.jpg','<p>炙黄芪重在补气升阳，在黄芪的诸炮制品中应用最多，由于饮片的临床<a class=\"lemma-album layout-right nslog:10000206\" title=\"炙黄芪\" href=\"http://baike.baidu.com/pic/%E7%82%99%E9%BB%84%E8%8A%AA/5102088/1432820/14ce36d3d539b6005b80c029e950352ac75cb7e6?fr=lemma&ct=cover\" target=\"_blank\" style=\"color: rgb(19, 110, 194); text-decoration: none; display: block; width: 222px; border-bottom: 0px; margin: 10px 0px; position: relative; float: right; clear: right;\"></a></p><p><a class=\"lemma-album layout-right nslog:10000206\" title=\"炙黄芪\" href=\"http://baike.baidu.com/pic/%E7%82%99%E9%BB%84%E8%8A%AA/5102088/1432820/14ce36d3d539b6005b80c029e950352ac75cb7e6?fr=lemma&ct=cover\" target=\"_blank\" style=\"color: rgb(19, 110, 194); text-decoration: none; display: block; width: 222px; border-bottom: 0px; margin: 10px 0px; position: relative; float: right; clear: right;\"><img class=\"picture\" alt=\"炙黄芪\" src=\"http://b.hiphotos.baidu.com/baike/s%3D220/sign=c89f70bd632762d0843ea3bd90ed0849/14ce36d3d539b6005b80c029e950352ac75cb7e6.jpg\"/></a></p><p><br/></p><p>&nbsp;需求量大，炮制研究也就尤受重视。如传统方法的改进、炮制温度、炮制时间、辅料用量、成品质量分析等研究报道较多。</p><p><br/></p><p>传统黄芪炮制以蜜炙为主，是将生黄芪切片，加蜂蜜炒制而成，炮制较为粗糙，劳动强度大。有文献报道认为，CY—电动炒药机蜜炙黄芪可降低劳动强度。也有文献认为，电烘烤蜜炙黄芪尤佳，并对三种不同炙法(烘炙法、炒炙法、先闷润后炒炙法)所得成品进行比较，结果表明，烘炙法制的黄芪质量好，色泽鲜艳，贮藏不易吸潮，可延长保存期，不生虫，不霉变；炒炙品摊放20小时就吸潮回润，粘手，久放易酸败；先闷润后炒炙所得制品，品质次于烘炙品。因此认为，三法中以烘法为优l真。关于炮制时间及温度的问题，有人研究认为，70V或80C烘制2小时，与传统蜜炙黄芪的药理作用无显著差异I引：另有文献认为，用蜜量为30%、温度10012、烘制时间30分钟，所得成品黄芪甲苷的含量为生黄芪的三倍，因而作者提出，此为最佳炮制条件。</p><p>上述研究，以蜜炙黄芪的外在质量和内在质量为指标，探讨了蜜炙黄芪的新工艺，这对于我们改进黄芪炮制工艺很有参考价值，《中国药典》仍采用传统制法，所以我们姑且遵《中国药典》法而行。至于黄芪蜜炙工艺的改进与优化，尚待进一步研究。在蜜炙黄芪成品质量研究方面，学者重视内在质量的控制。如研究了蜜炙黄芪中黄芪甲苷(astragaloside)、芒柄花素(formononetin)、毛蕊异花酮(ealy·cosin)、总磷脂等的含量，其中对黄芪甲苷含量的研究方法多种，结果可靠，可作为控制黄芪内在质量标准的指标。</p><p>另外，就蜜炙黄芪蜂蜜的含量是否合乎要求，也有人做了研究。方法是</p><p><a class=\"image-link\" href=\"http://baike.baidu.com/pic/%E7%82%99%E9%BB%84%E8%8A%AA/5102088/0/8644ebf81a4c510ff33bf49a6059252dd52aa5b6?fr=lemma&ct=single\" target=\"_blank\" title=\"炙黄芪\" style=\"color: rgb(19, 110, 194); text-decoration: none; display: block; width: 220px; height: 165px;\"><img class=\"\" src=\"http://c.hiphotos.baidu.com/baike/s%3D220/sign=c3e69b29a9014c081d3b2fa73a7a025b/8644ebf81a4c510ff33bf49a6059252dd52aa5b6.jpg\" alt=\"炙黄芪\"/></a><span class=\"description\" style=\"display: block; color: rgb(85, 85, 85); font-size: 12px; text-indent: 0px; font-family: 宋体; word-wrap: break-word; word-break: break-all; line-height: 15px; padding: 8px 7px; min-height: 12px; border-top: 1px solid rgb(224, 224, 224);\">炙黄芪</span></p><p>取蜜炙黄芪饮片1克，稍碎，置有盖试管中加蒸馏水10毫升，于温水中40C的超声清洗器内提取30分钟，过滤。然后将尿糖试纸投入黄芪提取液中3秒钟，取出，3分钟后、7分钟内观察试纸颜色，颜色应为“黄70品红60青30一棕70紫40&#39;(颜色偏绿者为含糖量不足，偏棕者为含糖量过高)，此法可以考察加蜜量、闷润时间，以防止检查前加入蜂蜜(引：这种立意，有可取之处，但观察颜色的客观性略差，有待进一步研究。至于蜜炙黄芪的显微特征、理化特征这些关系黄芪品质真伪研究，尚未见报道。</p><p><span style=\"font-weight: 700;\">炮制工艺</span></p><p>(1)取生黄芪片。</p><p>(2)炼蜜。将蜂蜜置锅内，加热至徐徐沸腾后，改用文火保持微沸，捞去浮在表面的泡沫和蜡质，然后用或纱布滤去死蜂等杂质；对于浓稠的蜂蜜可酌加开水稀释，过滤后继续炼制。一般以起龟眼泡，</p><p><a class=\"image-link\" href=\"http://baike.baidu.com/pic/%E7%82%99%E9%BB%84%E8%8A%AA/5102088/0/d009b3de9c82d15849314f31800a19d8bd3e42fa?fr=lemma&ct=single\" target=\"_blank\" title=\"炙黄芪\" style=\"color: rgb(19, 110, 194); text-decoration: none; display: block; width: 220px; height: 220px;\"><img class=\"\" src=\"http://f.hiphotos.baidu.com/baike/s%3D220/sign=29210d6da3cc7cd9fe2d33db09002104/d009b3de9c82d15849314f31800a19d8bd3e42fa.jpg\" alt=\"炙黄芪\"/></a><span class=\"description\" style=\"display: block; color: rgb(85, 85, 85); font-size: 12px; text-indent: 0px; font-family: 宋体; word-wrap: break-word; word-break: break-all; line-height: 15px; padding: 8px 7px; min-height: 12px; border-top: 1px solid rgb(224, 224, 224);\">炙黄芪</span></p><p>手试之粘性较牛蜜略强，颜色稍微加深，温度105t，比重1．30即可。</p><p>(3)取炼蜜，用适量开水稀释后，淋人黄芪片中，拌匀，闷润4—6小时(令蜂蜜吸尽)。</p><p>(4)将黄芪片置炒锅中，用文火炒制，炒制时翻动要勤，炒至：①颜色深黄均匀，略带焦斑，有光泽；②饮片不粘手，炒动时手感由重滞转为轻松；③饮片起锅晾凉后，用手翻动有轻微沙沙声。结块疏松，轻轻搓动即散，饮片之间无粘连。</p><p>(5)取出，放凉。每100此黄芪，用炼蜜25kg。</p><p><br/></p>',1,10,1,0,0),(7,'ZAQ00000015','当归片','1kg',8,174,'2016-11-11/5825780842511.jpg','<p><span style=\"color: rgb(51, 51, 51); font-family: arial, 宋体, sans-serif; font-size: 14px; text-indent: 28px; background-color: rgb(255, 255, 255);\">当归片，补血活血，调经止痛。用于血虚引起的面色萎黄，眩晕心悸，月经不调，痛经。</span></p><p><span style=\"color: rgb(51, 51, 51); font-family: arial, 宋体, sans-serif; font-size: 14px; text-indent: 28px; background-color: rgb(255, 255, 255);\"><br/></span></p><p><span style=\"color: rgb(51, 51, 51); font-family: arial, 宋体, sans-serif; font-size: 14px; text-indent: 28px; background-color: rgb(255, 255, 255);\"></span></p><p><br/></p><p>1.忌食寒凉、生冷食物。<br/></p><p>2.孕妇服用时请向医师咨询。<br/>3.感冒时不宜服用本药。<br/>4.月经过多者不宜服用本药。<br/>5.平素月经正常，突然出现月经量少，或月经错后，或阴道不规则出血应去医院就诊。<br/>6.按照用法用量服用，长期服用应向医师咨询。<br/>7.服药二周症状无改善，应去医院就诊。<br/>8.对本品过敏者禁用，过敏体质者慎用。<br/>9.本品性状发生改变时禁止使用。<br/>10.请将本品放在儿童不能接触的地方。<br/>11.如正在使用其他药品，使用本品前请咨询医师或药师。</p><p><br/></p><p><span style=\"color: rgb(51, 51, 51); font-family: arial, 宋体, sans-serif; font-size: 14px; text-indent: 28px; background-color: rgb(255, 255, 255);\"><br/></span><br/></p>',1,15,1,0,0),(8,'ZRW00000012','酒白芍(酒炙)','酒炙1kg(安徽)',6,174,'2016-11-11/582584d8197eb.jpg','',1,15,1,0,0),(9,'ZBL00000001','川芎','1kg',6,174,'2016-11-11/5825878babef7.jpg','<p><span style=\"font-weight: 700;\">性味</span><br/>　　辛，温。<br/>　　①《本经》：味辛，温。<br/>　　②《吴普本草》：黄帝、岐伯、雷公：辛，无毒，香。扁鹊：酸，无毒。李氏：生温，熟寒。<br/>　　③《唐本草》：味苦辛。</p><p><a class=\"image-link\" href=\"http://baike.baidu.com/pic/%E5%B7%9D%E8%8A%8E/727831/0/810a19d8bc3eb13546eab4d1a51ea8d3fd1f44ae?fr=lemma&ct=single\" target=\"_blank\" title=\"川芎\" style=\"color: rgb(19, 110, 194); text-decoration: none; display: block; width: 220px; height: 150px;\"><img class=\"\" src=\"/Public/Uploads/goods/20161111/1478854516423211.jpg\" alt=\"川芎\"/></a><span class=\"description\" style=\"display: block; color: rgb(85, 85, 85); font-size: 12px; text-indent: 0px; font-family: 宋体; word-wrap: break-word; word-break: break-all; line-height: 15px; padding: 8px 7px; min-height: 12px; border-top: 1px solid rgb(224, 224, 224);\">川芎</span></p><p><br/>　　④《本草正》：味辛微甘，气温。<span style=\"font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: default; padding: 0px 2px;\">[4]</span><a class=\"sup-anchor\" style=\"color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;\">&nbsp;</a>&nbsp;<br/>　　<span style=\"font-weight: 700;\">归经</span><br/>　　入肝、胆经。<br/>　　①《汤液本草》：入手足厥阴经、少阳经。<br/>　　②《药品化义》：入肝、脾、三焦三经。<br/>　　<span style=\"font-weight: 700;\">功能主治</span><br/>　　行气开郁，法风燥湿，活血止痛。治风冷头痛旋晕，胁痛腹疼，寒痹筋挛，经闭，难产，产后瘀阻块痛，痈疽疮疡。用于月经不调，经闭痛经，瘕腹痛，胸胁刺痛，跌扑肿痛，头痛，风湿痹痛。<br/>　　①《本经》：主中风入脑头痛，寒痹，筋挛缓急，金创，妇人血闭无子。<br/>　　②《别录》：除脑中冷动，面上游风去来，目泪出，多涕唾，忽忽如醉，诸寒冷气，心腹坚痛，中恶，卒急肿痛，胁风痛，温中内寒。<br/>　　③陶弘景：齿根出血者，含之多瘥。<br/>　　④《药性论》：治腰脚软弱，半身不遂，主胞衣不出，治腹内冷痛。<br/>　　⑤《日华子本草》：治一切风，一切气，一切劳损，一切血，补五劳，壮筋骨，调众脉，破症结宿血，养新血，长肉，鼻洪，吐血及溺血，痔瘘，脑痈发背，瘰疬瘿赘，疮疥，及排脓消瘀血。<br/>　　⑥《医学启源》：补血，治血虚头痛。<br/>　　⑦王好古：搜肝气，补肝血，润肝燥，补风虚。<br/>　　⑧《纲目》：燥湿，止泻痢，行气开郁。<span style=\"font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: default; padding: 0px 2px;\">[4]</span><a class=\"sup-anchor\" style=\"color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;\">&nbsp;</a>&nbsp;<br/>　　<span style=\"font-weight: 700;\">用法用量</span></p><p>内服：煎汤，1～2钱；或入丸、散，外用：研末撒或调敷。<br/>　　<span style=\"font-weight: 700;\">注意</span><br/>　　阴虚火旺，上盛下虚及气弱之人忌服。<br/>　　①《本草经集注》：白芷为之使。恶黄连。<br/>　　②《品汇精要》：久服则走散真气。<br/>　　③《本草蒙筌》：恶黄芪、山茱、狼毒。畏硝石、滑石、黄连。反藜芦。<br/>　　④《本草经疏》：凡病人上盛下虚，虚火炎上，呕吐咳嗽，自汗、易汗、盗汗，咽干口燥，发热作渴烦躁，法并忌之。<br/>　　⑤《本草从新》：气升痰喘不宜用。<br/>　　⑥《得配本草》：火剧中满，脾虚食少，火郁头痛皆禁用。</p><p><br/></p>',1,15,1,0,0),(10,'ZRW00000021','牡丹皮','1kg(安徽)',7,174,'2016-11-11/5825799e7a349.jpg','<p><br/></p><p><br/></p><p><br/></p><p>1.主产湖南、湖北、安徽、四川、甘肃、陕西、山东、贵州等地。此外，云南、浙江亦产。以湖南、安徽产量最大。安徽铜陵凤凰山所产的质量最佳，称为凤丹皮；安徽南陵所产称瑶丹皮；重庆垫江、四川灌县所产称川丹皮；甘肃、陕西及四川康定、泸定所产称西丹皮；四川西昌所产的称西昌丹皮，质量较次。</p><p><a class=\"lemma-anchor para-title\" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a><a class=\"lemma-anchor \" style=\"color: rgb(19, 110, 194); position: absolute; top: -50px;\"></a></p><p><br/>2.《本草经疏》：“牡丹皮，其味苦而微辛，其气寒而无毒，辛以散结聚，苦寒除血热，入血分，凉血热之要药也。寒热者，阴虚血热之候也。中风瘛疭、痉、惊痫，皆阴虚内热，营血不足之故。热去则血凉，凉则新血生、阴气复，阴气复则火不炎而无因热生风之证矣，故悉主之。痈疮者，热壅血瘀而成也。凉血行血，故疗痈疮。辛能散血，苦能泻热，故能除血分邪气，及症坚瘀血留舍肠胃。脏属阴而藏精，喜清而恶热，热除则五脏自安矣。《别录》并主时气头痛客热，五劳劳气，头腰痛者，泄热凉血之功也。甄权又主经脉不通，血沥腰痛，此皆血因热而枯之候也。血中伏火，非此不除，故治骨蒸无汗，及小儿天行痘疮，血热。东垣谓心虚肠胃积热，心火炽甚，心气不足者，以牡丹皮为君，亦此意也。”1.《本草纲目》：“牡丹皮，治手足少阴、厥阴四经血分伏火。盖伏火即阴火也，阴火即相火也，古方惟以此治相火，故仲景肾气丸用之。后人乃专以黄蘖治相火，不知丹皮之功更胜也。赤花者利，白花者补，人亦罕悟，宜分别之。”<a style=\"color: rgb(19, 110, 194);\"></a></p><p>3.《本草汇言》：“沈拜可先生曰：按《深师方》用牡丹皮，同当归、熟地则补血；同莪术、桃仁则破血；同生地、芩、连则凉血；同肉桂、炮姜则暖血；同川芎、白芍药则调血；同牛膝、红花则活血；同枸杞、阿胶则生血；同香附、牛膝、归、芎，又能调气而和血。若夫阴中之火，非配知母、白芍药不能去；产后诸疾，非配归、芎、益母不能行。又欲顺气疏肝，和以青皮、柴胡；达痰开郁，和以贝母、半夏。若用于疡科排脓、托毒、凉血之际，必协乳香、没药、白芷、羌活、连翘、金银花辈，乃有济也。牡丹皮，清心，养肾，和肝，利包络，并治四经血分伏火。血中气药也。善治女人经脉不通，及产后恶血不止。又治衄血吐血，崩漏淋血，跌扑瘀血，凡一切血气为病，统能治之。盖其气香，香可以调气而行血；其味苦，苦可以下气而止血；其性凉，凉可以和血而生血；其味又辛，辛可以推陈血，而致新血也。故甄权方治女人血因热而将枯，腰脊疼痛，夜热烦渴，用四物重加牡丹皮最验。又古方用此以治相火攻冲，阴虚发热。又按《本经》主寒热，中风瘛疭、痉、惊痫邪气诸症，总属血分为眚。然寒热，中风，此指伤寒热入血室之中风，非指老人气虚痰厥之中风也。其文先之以寒热二字，继之以瘛疭惊痫可知已，况瘛疭、惊痫，正血得热而变现，寒热又属少阳所主者也。”</p><p>4.《得配本草》：“丹皮、川柏，皆除水中之火，然一清燥火，一降邪火，判不相合。盖肾恶燥，燥则水不归元，宜用辛以润之，凉以清之，丹皮为力；肾欲坚，以火伤之则不坚，宜从其性以补之，川柏为使。”</p><p>5.《本草求真》：“世人专以黄柏治相火，而不知丹皮之功更胜。盖黄柏苦寒而燥，初则伤胃，久则伤阳，苦燥之性徒存，而补阴之功绝少，丹皮能泻阴中之火，使火退而阴生，所以入足少阴而佐滋补之用，较之黄柏不啻霄壤矣。”</p><p>6.《本经疏证》：“牡丹皮入心，通血脉中壅滞与桂枝颇同，特桂枝气温，故所通者血脉中寒滞，牡丹皮气寒，故所通者血脉中热结。”</p><p>7《神农本草经》：“主寒热，中风瘛疭、痉、惊痫邪气，除症坚瘀血留舍肠胃，安五脏，疗痈疮。”</p><p>8.《名医别录》：“除时气头痛，客热五劳，劳气头腰痛，风噤，癫疾。”</p><p>9.《药性论》：“治冷气，散诸痛，治女子经脉不通，血沥腰疼。”</p><p>10.《日华子本草》：“除邪气，悦色，通关腠血脉，排脓，通月经，消扑损瘀血，续筋骨，除风痹，落胎下胞，产后一切冷热血气。”</p><p>11.《滇南本草》：“破血，行（血）消症瘕之疾，除血分之热。”</p><p>12.《医学入门》：“泻伏火，养真血气，破结蓄。”</p><p>13.《本草纲目》：“和血，生血，凉血。治血中伏火，除烦热。”</p><p><br/></p>',1,15,1,0,0),(11,'ZAQ00000007','麦冬','1kg',5,174,'2016-11-11/582589cce2f58.jpg','<p>《神农本草经》将麦冬列为养阴润肺的上品，言其“久服轻身，不老不饥”。</p><p>《本草分经》称麦冬“甘、微苦，微寒。润肺清心、泻热生津、化痰止呕、治嗽行水”。</p><p>《医学衷中参西录》言其：“能入胃以养胃液，开胃进食，更能入脾以助脾散精于肺，定喘宁嗽。”中医认为，麦冬味甘、微苦，性微寒，归胃、肺、心经，有养阴润肺、益胃生津、清心除烦的功效，用于肺燥干咳、阴虚痨嗽、喉痹咽痛、津伤口渴、内热消渴、心烦失眠、肠燥便秘等症。</p><p><a class=\"image-link\" href=\"http://baike.baidu.com/pic/%E9%BA%A6%E5%86%AC/396276/0/d8f9d72a6059252dfda00aa3329b033b5ab5b9e5?fr=lemma&ct=single\" target=\"_blank\" title=\"麦冬中药\" style=\"color: rgb(19, 110, 194); text-decoration: none; display: block; width: 220px; height: 145px;\"><img class=\"\" src=\"/Public/Uploads/goods/20161111/1478855039773665.jpg\" alt=\"麦冬中药\"/></a><span class=\"description\" style=\"display: block; color: rgb(85, 85, 85); font-size: 12px; text-indent: 0px; font-family: 宋体; word-wrap: break-word; word-break: break-all; line-height: 15px; padding: 8px 7px; min-height: 12px; border-top: 1px solid rgb(224, 224, 224);\">麦冬中药</span></p><p>现代药理研究也表明，麦冬主要含沿阶草苷、甾体皂苷、生物碱、谷甾醇、葡萄糖、氨基酸、维生素等，具有抗疲劳、清除自由基、提高细胞免疫功能以及降血糖的作用。另外，麦冬有镇静、催眠、抗心肌缺血、抗心律失常、抗肿瘤等作用，尤其对增进老年人健康具有多方面功效。<span style=\"font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: default; padding: 0px 2px;\">[6]</span><a class=\"sup-anchor\" style=\"color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;\">&nbsp;</a></p><p>此外，麦冬还有促进胰岛细胞功能恢复、增加肝糖原、降低血糖的作用，是糖友处方中的常用品。麦冬可代茶饮。取适量麦冬，开水浸泡，每天多服几次，能有效缓解口干渴的症状。部分糖尿病患者气阴两虚，因此饮用麦冬水时，可搭配一点党参，更能起到补气的作用。<span style=\"font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: default; padding: 0px 2px;\">[7]</span><a class=\"sup-anchor\" style=\"color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;\">&nbsp;</a></p><p>麦冬不宜长期服用，尤其在没有医生指导的情况下，否则可能生痰生湿，适得其反。另外，麦冬并非人人适合，脾胃虚寒、感冒的人，最好不要随便食用麦冬，否则会加重病情。<span style=\"font-size: 12px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em; margin-left: 2px; color: rgb(51, 102, 204); cursor: default; padding: 0px 2px;\">[7]</span><a class=\"sup-anchor\" style=\"color: rgb(19, 110, 194); position: relative; top: -50px; font-size: 0px; line-height: 0;\">&nbsp;</a></p><p><br/></p>',1,12,1,0,0),(12,'ZBB00000010','黄连片','1kg',6,174,'2016-11-11/582585970ad67.jpg','<h2 style=\"font-family: &quot;Microsoft YaHei&quot;, simsun, &quot;Helvetica Neue&quot;, Arial, Helvetica, sans-serif; font-size: 1.286em; text-indent: 1.55em; white-space: normal; background-color: rgb(255, 255, 255);\">《中国药典》：黄连</h2><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【药材名称】黄连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【拼音】Huánɡ Lián</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【英文名】RHIZOMA COPTIDIS</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【别名】云连、雅连、川连、味连、鸡爪连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【来源】本品为毛茛科植物<strong>黄连</strong>Coptis chinensis Franch.、<strong>三角叶黄连</strong>Coptis deltoidea C. Y. Cheng et Hsiao或<strong>云连</strong>Coptis teeta Wall.的干燥<strong>根茎</strong>。以上三种分别习称“味连”、“雅连”、“云连”。秋季采挖，除去须根及泥沙，干燥，撞去残留须根。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【性状】味连：多集聚成簇，常弯曲，形如鸡爪，单枝根茎长3～6cm，直径0.3～0.8cm。表面灰黄色或黄褐色，粗糙，有不规则结节状隆起、须根及须根残基，有的节间表面平滑如茎杆，习称“过桥”。上部多残留褐色鳞叶，顶端常留有残余的茎或叶柄。质硬，断面不整齐，皮部橙红色或暗棕色，木部鲜黄色或橙黄色，呈放射状排列，髓部有的中空。气微，味极苦。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">雅连：多为单枝，略呈圆柱形，微弯曲，长4～8cm，直径0.5～1cm。“过桥”较长。顶端有少许残茎。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">云连：弯曲呈钩状，多为单枝，较细小。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【鉴别】（1） 本品横切面：味连 木栓层为数列细胞。皮层较宽，石细胞单个或成群散在。中柱鞘纤维成束，或伴有少数石细胞，均显黄色。维管束外韧型，环列。束间形成层不明显。木质部黄色，均木化，木纤维较发达。髓部均为薄壁细胞，无石细胞。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">雅连 髓部有石细胞。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">云连 皮层、中柱鞘及髓部均无石细胞。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">（2） 取本品粗粉约1g，加乙醇10ml，加热至沸腾，放冷，滤过。取滤液5 滴，加稀盐酸1ml 与含氯石灰少量，即显樱红色；另取滤液5 滴，加5％ 没食子酸乙醇溶液 2～3 滴，蒸干，趁热加硫酸数滴，即显深绿色。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">（3） 取本品粉末50mg，加甲醇5ml ，加热回流15分钟，滤过，滤液补加甲醇使成5ml，作为供试品溶液。另取黄连对照药材，同法制成对照药材溶液。再取盐酸小檗碱对照品，加甲醇制成每1ml 含0。5mg 的溶液，作为对照品溶液。照薄层色谱法（附录Ⅵ B）试验，吸取上述三种溶液各1μl，分别点于同一硅胶Ｇ薄层板上，以苯－醋酸乙酯－异丙醇-甲醇－水（6:3:1。5:1。5:0。3） 为展开剂，置氨蒸气饱和的展开缸内，展开，取出，晾干，置紫外光灯（365nm） 下检视。供试品色谱中，在与对照药材色谱相应的位置上，显相同的黄色荧光斑点；在与对照品色谱相应的位置上，显相同的一个黄色荧光斑点。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【炮制】<strong>黄连</strong>：除去杂质，润透后切薄片，晾干，或用时捣碎。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><strong>酒黄连</strong>：取净黄连，照酒炙法（附录Ⅱ D）炒干。每100kg黄连 ，用黄酒12.5kg。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><strong>姜黄连</strong>：取净黄连，照姜汁炙法（附录Ⅱ D）炒干。每100kg黄连 ，用生姜12.5kg。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><strong>萸黄连</strong>：取吴茱萸加适量水煎煮，煎液与净黄连拌匀，待液吸尽，炒干。每100kg黄连 ，用吴茱萸10kg。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">酒黄连、姜黄连、萸黄连照上述总灰分的方法测定，均不得过 4.0％。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【性味】苦，寒。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【归经】归心、脾、胃、肝、胆、大肠经。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【功能主治】清热燥湿，泻火解毒。用于湿热痞满，呕吐吞酸，泻痢，黄疸，高热神昏，心火亢盛，心烦不寐，血热吐衄，目赤，牙痛，消渴，痈肿疔疮；外治湿疹，湿疮，耳道流脓。酒黄连善清上焦火热。用于目赤，口疮。姜黄连清胃和胃止呕。用于寒热互结，湿热中阻，痞满呕吐。萸黄连舒肝和胃止呕。用于肝胃不和，呕吐吞酸。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【用法用量】2～5g。外用适量。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【贮藏】置通风干燥处。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【备注】（1）配黄芩、大黄等，能治湿热内蕴之症。对湿热留恋肠胃，常配合半夏、竹茹以止呕，配木香、黄芩、葛根等以治泻痢。对热病高热、心火亢盛，有良好疗效，常配合山栀、连翘等同用；对于血热妄行，可配伍黄芩、大黄等同用；对热毒疮疡，可配伍赤芍、丹皮等药同用。用于胃火炽盛的中消证，可配合天花粉、知母、生地等同用。外用以黄连汁点眼，可治火盛目赤；涂口。可治口舌生疮。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【摘录】《中国药典》</p><p></p><p><a title=\"我也要申请橱窗推广\" target=\"_blank\" href=\"http://pro.taobao.com/index.htm?spm=a2320.7388781.1998051528.5.JOvNkN\" style=\"color: rgb(0, 0, 238); text-decoration: none; background-color: transparent; overflow: hidden; width: 15px; height: 13px; right: 20px; bottom: 0px; display: block; position: absolute; cursor: pointer; z-index: 250;\"><span style=\"border-right: 2px solid rgb(238, 238, 238); float: none; width: 13px; display: block; height: 13px; box-sizing: content-box;\"><img src=\"/Public/Uploads/goods/20161111/1478854031547440.png\"/></span></a><a style=\"color: rgb(0, 0, 238); background-color: transparent; width: 20px; height: 13px; right: 0px; bottom: 0px; display: block; position: absolute; cursor: pointer; z-index: 250; margin: 0px;\"><span style=\"float: none; width: 20px; display: block; height: 13px;\"><img src=\"/Public/Uploads/goods/20161111/1478854031814050.png\"/></span></a></p><p></p><hr/><h2 style=\"font-family: &quot;Microsoft YaHei&quot;, simsun, &quot;Helvetica Neue&quot;, Arial, Helvetica, sans-serif; font-size: 1.286em; text-indent: 1.55em; white-space: normal; background-color: rgb(255, 255, 255);\">《中药大辞典》：黄连</h2><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【药材名称】黄连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【拼音】Huánɡ Lián</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【别名】王连（《本经》），灾连（《药性论》）。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【出处】《本经》</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【来源】为毛茛科植物<strong>黄连</strong>、<strong>三角叶黄连</strong>、<strong>峨嵋野连</strong>或<strong>云南黄连</strong>的<strong>根茎</strong>。以立冬后（11月）采收为宜。掘出后除去茎叶、须根及泥土，晒干或烘干，撞去粗皮。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【原形态】①黄连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">多年生草本，高15～25厘米。根茎黄色，常分枝，密生须根。叶基生，叶柄长6～16厘米，无毛；叶片稍带革质，卵状三角形，宽达10厘米，3全裂；中央裂片稍呈菱形，墓部急遽下延成长1～1.8厘米的细柄，裂片再作羽状深裂，深裂片4～5对，近长圆形，先端急尖，彼此相距2～6毫米，边缘具针刺状锯齿；两侧裂片斜卵形，比中央效片短，不等2深裂或罕2全裂，裂片常再作羽状深裂；上面沿脉被短柔毛，下面无毛。花茎1～2，与叶等长或更长；二歧或多歧聚伞花序，生花3～8朵；苞片披针形，3～5羽状深裂；萼片5，黄绿色，长椭圆状卵形至披针形，长9～12.5毫米，宽2～3毫米；花瓣线形或线状拉针形，长5～6.5毫米，先端尖，中央有蜜槽；雄蕊多数，外轮雄蕊比花瓣略短或近等长，花药广椭圆形，黄色；心皮8～12。蓇葖6～12，具柄，长6～7毫米。种子7～8，长椭圆形，长约2毫米，褐色。花期2～4月。果期3～6月。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">野生或栽培。分布四川、贵州、湖北、陕西等地。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">②三角叶黄连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">多年生草本。形态与黄连相似，主要特征为根茎不分枝或少分枝。叶片纸质，卵形，长达16厘米，宽达15厘米，3全裂，裂片均具明显的柄；中央裂片三角状卵形，基部急缩成长达2.5厘米的细柄，羽状深裂4～6对，两侧裂片斜卵状三角形，不等的2深裂或半裂，小裂片彼此邻接。苞片线状披针形，近中部3裂或栉状羽状深裂。花萼狭卵形；花瓣近倒披针形，均较宽；雄蕊约20，长仅为花瓣的1/2左右；心皮9～12。种子不育。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">栽培于四川，野生种已不多见。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">③峨嵋野连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">多年生草本，高15～30厘米。根茎较少分枝，节间短而密。叶基生，4～11枚，叶柄长5～16厘米；叶片披针形或窄卵形，约与叶柄等长，宽3.5～6.5厘米，3全裂；中央裂片三角状披针形，长达14厘米，宽达6厘米，先端渐尖，基部急缩成小柄，羽状深裂，7～10对，小裂片长椭圆状卵形，缘具尖锯齿；两侧裂片斜卵形，长仅为中央裂片的1/3～1/4，2深裂或偶2全裂，小裂片再作羽状分裂或2深裂；上面沿脉被微柔毛外余均无毛。花茎多单一，多歧聚伞花序，有花3～6朵；萼片线形，长7.5～10毫米，宽0.7～1.2毫米；花瓣9～12，狭线形，长约为花萼的1/2或较短；雄蕊多数，长约4毫米；心皮9～14。蓇葖长约8毫米。种子长圆形，黄褐色。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">野生于阴湿丛林中。分布四川。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">④云南黄连</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">多年生草本。形态与黄连很近似，主要区别为：根茎较少分枝，节间密。中央裂片卵状菱形或长菱形，羽状深裂3～6对，小裂片彼此的距离稀疏。多歧聚伞花序，有花3～5朵；苞片椭圆形，3深裂或羽状深裂；花萼卵形或椭圆形，长6～8毫米，宽2～3毫米；花瓣匙形或卵状匙形，长4.5～6毫米，宽0.5～1毫米，先端圆或钝，中部以下变狭成细长的爪，中央有蜜槽；心皮8～15。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">生于高山寒湿林荫下。分布云南、西藏昌都地区，云南有栽培。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【性状】黄连商品，因原植物与产地的不同，大致可分如下几种：</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">①味连，又名：川连（《本草蒙筌》），鸡爪连、鸡爪黄连、光连。为植物黄连的干燥根茎，多分枝，常3～6枝成束，稍弯曲，形如鸡爪，长约3～7厘米，单枝直径3～8毫米。外表黄褐色，栓皮剥落处呈红棕色；分枝上有间断横纹，结节膨大，形如连珠，着生多数坚硬的细须根及须根痕，有的表面无横纹而平滑如茎杆，习称&quot;过江枝&quot;或&quot;过桥杆&quot;；上部多有褐色鳞片残留，顶端有未去净的残茎或叶柄。质坚实而硬，断面不整齐，皮部暗棕色，木部金黄色，射线有裂隙，中央髓部红黄色，偶有空心。无臭，味极苦，嚼之唾液可染为红黄色。以条肥壮、连珠形、质坚实、断面红黄色、无残茎及须根者为佳。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">均属栽培品，主产四川、湖北。陕西（平利）亦产。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">②雅连（《本草从新》），又名：峨嵋连、嘉定连、刺盖连。为植物三角叶黄连的干燥根茎。多为单枝，少有分枝，略呈圆柱形，微弯曲呈蚕状，长约4～8厘米，直径约3～9毫米。外表褐色或黄棕色，间断横纹多，结节明显，有多数须根残痕、叶柄残基及鳞片，&quot;过江枝&quot;较味连为少。质坚实，断面不齐，皮部暗棕色，木部深黄色，射线明显，髓部时有空心。无臭，味极苦。以条肥壮、连珠形、质坚实、断面黄色、无残茎及须根者为佳。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">均属栽培品，主产四川（峨嵋、洪雅）。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">③野黄连，又名：凤尾连。为植物峨嵋野连的干燥根茎。外形与雅连相近，惟顶端多留有长6～10厘米的叶柄，作为野生的标记；根茎多单枝或有2分枝，略弯曲，长约5～6厘米，直径4～6毫米，外表呈黑褐色，结节紧密成连珠状，无&quot;过江枝&quot;；残留的鳞片较多，须根较硬。断面木部鲜黄色。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">均属野生。主产四川（峨嵋、洪雅、峨边），产量极小，但一般认为品质最优。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">④云连（《本草从新》）主要为植物云南黄连的干燥根茎。较细小，多弯曲，拘挛，多为单枝，形如蝎尾。长约1.5～8厘米，直径约2～4毫米。外皮黄绿色或灰黄色。其余特征与以上品种大致相同。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">栽培或野生。主产云南（德钦、维西、腾冲、碧江）。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">【化学成分】黄连含小檗碱7～9％、黄连碱，甲基黄连碱、掌叶防已碱、非洲防己碱等生物碱，尚含黄柏酮、黄柏内酯。</p><p style=\"text-indent: 2em; margin-top: 0px; margin-bottom: 0.5em; line-height: 1.85em; font-family: Arial, Helvetica, sans-serif; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">峨嵋野连中分离出小檗碱、甲基黄连碱、药根碱、掌叶防己碱，以及两种非酚性生物碱，两种酚性生物碱。</p><p><br/></p>',1,20,1,0,0),(13,'KJS00000023','秏牌玛咖酒','盒',6,185,'2016-11-11/58257fe096eca.jpg','药物成分水、清香型白酒、玛卡粉、枸杞子、玉竹、葛根、果葡糖浆性状金黄色澄明液体适应症（或功能主治）激活肾脏自我修复能力，温润肾虚，改善肾疲劳。排尿便利，增强体力，提高免疫力，提升睡眠质量用法用量每日30-50ml，直接饮用不良反应无注意事项婴幼儿、哺乳期妇女、孕妇慎用禁忌对酒精过敏者慎用贮存条件阴凉孕妇及哺乳期妇女用药慎用',1,1,24,0,0),(14,'CBH00000016','舒肝解郁胶囊','0.36g*28s',16,166,'2016-11-11/58258fbe00f8d.jpg','<table class=\"infotable\" width=\"898\" style=\"width: 771px;\"><tbody><tr class=\"firstRow\"><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">药物成分</td><td style=\"margin: 0px; padding: 8px 15px; line-height: 18px; color: rgb(102, 102, 102);\">贯叶金丝桃、刺五加</td></tr><tr class=\"odd\"><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">性状</td><td style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; color: rgb(102, 102, 102);\">本品为硬胶囊，内容物为棕褐色至褐色的粉末；气香，味微苦</td></tr><tr><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">适应症（或功能主治）</td><td style=\"margin: 0px; padding: 8px 15px; line-height: 18px; color: rgb(102, 102, 102);\">舒肝解郁，健脾安神。适用于轻、中度单相抑郁症属肝郁脾虚证者，症见情绪低落、兴趣下降、迟滞、入睡困难、早醒、多梦、紧张不安、急躁易怒、食少纳呆、胸闷、疲乏无力、多汗、疼痛、舌苔白或腻，脉弦或细</td></tr><tr class=\"odd\"><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">用法用量</td><td style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; color: rgb(102, 102, 102);\">口服。一次2粒，一日2次，早晚各一次。疗程为6周</td></tr><tr><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">不良反应</td><td style=\"margin: 0px; padding: 8px 15px; line-height: 18px; color: rgb(102, 102, 102);\">口服。一次2粒，一日2次，早晚各一次。疗程为6周</td></tr><tr class=\"odd\"><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">注意事项</td><td style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; color: rgb(102, 102, 102);\">肝功能不全的患者慎用</td></tr><tr><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">禁忌</td><td style=\"margin: 0px; padding: 8px 15px; line-height: 18px; color: rgb(102, 102, 102);\">尚不明确</td></tr><tr class=\"odd\"><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">药物相互作用</td><td style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; color: rgb(102, 102, 102);\">非临床药效学试验结果显示：本品能缩短大鼠强迫性游泳不动时间和小鼠悬尾不动时间；能增强小鼠5-HTP甩头行为；能增强阿朴吗啡的降温作用；能减少利血平致小鼠眼睑下垂的动物数，降低小鼠脑组织5-HT及其代谢物5-HIAA的含量</td></tr><tr><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">贮存条件</td><td style=\"margin: 0px; padding: 8px 15px; line-height: 18px; color: rgb(102, 102, 102);\">常温</td></tr><tr class=\"odd\"><td class=\"lb\" style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; border-right-color: rgb(231, 231, 231);\" width=\"194\">孕妇及哺乳期妇女用药</td><td style=\"margin: 0px; padding: 8px 15px; background: rgb(242, 242, 242); line-height: 18px; color: rgb(102, 102, 102);\">无</td></tr></tbody></table><p><br/></p>',1,6,1,0,0),(15,'CCA00000001','保济丸','3.7g*20',9,164,'2016-11-11/582594127df18.jpg','药物成分钩藤、菊花、蒺藜、厚朴、木香、苍术、天花粉、广藿香、葛根、化橘红、白芷、薏苡仁、稻芽、薄荷、茯苓、广东神曲。辅料为胭脂红、滑石粉、三氧化二铁、糊精。性状本品为朱红色的水丸；气芳香，味微苦、辛。适应症（或功能主治）解表，祛湿，和中。用于暑湿感冒，症见发热头痛、腹痛腹泻、恶心呕吐、肠胃不适；亦可用于晕车晕船用法用量口服。一次1.85-3.7克，一日3次。不良反应尚不明确注意事项1.忌烟、酒及辛辣、生冷、油腻食物。2.不宜在服药期间同时服用滋补性中药。3.外感燥热者不宜服用。4.有高血压、心脏病、肝病、糖尿病、肾病等慢性病严重者应在医师指导下服用。5.儿童、孕妇、哺乳期妇女、年老体弱者应在医师指导下服用。6.发热体温超过38.5℃的患者，应去医院就诊。7.吐泻严重者应及时去医院就诊。8.服药3天症状无缓解，应去医院就诊。9.对本品过敏者禁用，过敏体质者慎用。10.本品性状发生改变时禁止使用。11.儿童必须在成人监护下使用。12.请将本品放在儿童不能接触的地方禁忌尚不明确药物相互作用如与其他药物同时使用可能会发生药物相互作用，详情请咨询医师或药师。贮存条件常温',1,100,1,0,0),(16,'BSB00000001','维胺酯胶囊','25mg*24s',10,153,'2016-11-11/58259510d3c80.jpg','药物成分化学名称：N--（4--乙氧碳基苯基）维甲酰胺。性状本品为胶囊剂，内容物为黄色颗粒或粉末。适应症（或功能主治）适应症：本品用于治疗重、中度痤疮，对鱼鳞病、银屑病、苔藓类皮肤病、及某些角化异常性皮肤病也有一定疗效。用法用量口服。按1-2.0mg/(kg·天)计算，成人每次1-2粒(25-50mg),每日服2-3次。疗程疗效痤疮为6周，脂溢性皮炎为4周。或遵医嘱。不良反应不良反应：1.本药的副作用与维生素A过量的临床表现相似，常见的副作用包括皮肤干燥、脱屑、瘙痒、皮疹、脆性增加、掌跖脱皮、瘀斑、继发感染等；口腔黏膜干燥、疼痛、结合膜炎、严重者角膜浑浊、视力障碍、视乳头水肿，头痛、头晕、精神症状、抑郁、良性脑压增高。2.骨质疏松、肌肉无力、疼痛、胃肠道症状、鼻出血等；3.妊娠服药可导致自发性流产及胎儿发育畸形；4.实验室检查可引起血沉快、肝酶升高、血脂升高、血糖升高、血小板下降等。5.上述副作用与异维A酸引起的副作用相似，但相对较轻，且大多为可逆性，停药后可逐渐得到恢复。副作注意事项注意事项：1.女性患者服药期间及停药后半年内应采取严格避孕措施。2.肝肾功能严重不全者慎用。3.重症糖尿病、脂质代谢障碍者禁用。4.禁与维生素A同服。5.酗酒者慎用。6.避免强烈日光或紫外光过度照射。禁忌禁忌：孕妇及哺乳期妇女禁用。药物相互作用药物相互作用：1.于异维A酸与四环素类抗生素合用时，可导致“假性脑瘤”引起脑压增高，头痛和视力障碍; 2.与维生素A合用时，可产生维生素A过量的相似症状; 3.与胺甲蝶喋合用时可使胺甲蝶喋的血药浓度增加而加重肝脏的毒性，维胺脂与异维A酸结构近似，亦应避免与上述药物同时使用。贮存条件阴凉孕妇及哺乳期妇女用药尚不明确',1,100,1,0,0),(17,'CBL00000023','通心络胶囊','0.26g*40s',11,154,'2016-11-11/58259624287a5.jpg','药物成分人参、水蛭、全蝎、赤芍、蝉蜕、土鳖虫、蜈蚣、檀香、降香、乳香（制）、酸枣仁（炒）、冰片性状本品为硬胶囊，内容物为灰棕色至灰褐色颗粒和粉末；气香、微腥，味微咸、苦适应症（或功能主治）益气活血，通络止痛。用于冠心病心绞痛属心气虚乏、血瘀络阻证，症见胸部憋闷，刺痛、绞痛，固定不移，心悸自汗，气短乏力，舌质紫暗或有瘀斑，脉细涩或结代。亦用于气虚血瘀络阻型中风病，症见半身不遂或偏身麻木，口舌歪斜，言语不利用法用量口服。一次2mdash；4粒，一日3次不良反应个别患者用药后可出现胃部不适注意事项服药后胃部不适者宜改为饭后服用禁忌出血性疾患，孕妇及妇女经期及阴虚火旺型中风禁用药物相互作用如与其他药物同时使用可能会发生药物相互作用，详情请咨询医师或药师贮存条件常温孕妇及哺乳期妇女用药无',1,8,40,0,0);

/*Table structure for table `zdb_goods_product` */

DROP TABLE IF EXISTS `zdb_goods_product`;

CREATE TABLE `zdb_goods_product` (
  `cv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  `goods_unit_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_goods_product` */

insert  into `zdb_goods_product`(`cv_id`,`goods_id`,`goods_name`,`goods_spec`,`goods_unit`,`goods_unit_type`) values (1,1,'注射用头孢唑啉钠','1g','瓶',1),(2,1,'注射用头孢唑啉钠','1g','件',3),(3,1,'注射用头孢唑啉钠','1g','包',2),(4,2,'阿莫西林胶囊','0.25g*24s','盒',1),(5,2,'阿莫西林胶囊','0.25g*24s','件',3),(6,2,'阿莫西林胶囊','0.25g*24s','中包',2),(7,3,'注射用盐酸大观霉素(卓青)','2g','盒',1),(8,3,'注射用盐酸大观霉素(卓青)','2g','件',3),(9,3,'注射用盐酸大观霉素(卓青)','2g','包',2),(10,4,'灵芝孢子粉（破壁）','2g*60包','盒',1),(11,4,'灵芝孢子粉（破壁）','2g*60包','件',3),(12,4,'灵芝孢子粉（破壁）','2g*60包','包',2),(13,5,'利多卡因氯已定气雾剂','60g','瓶',1),(14,5,'利多卡因氯已定气雾剂','60g','件',3),(15,5,'利多卡因氯已定气雾剂','60g','包',2),(16,6,'炙黄芪','1kg','包',1),(17,6,'炙黄芪','1kg','件',2),(18,7,'当归片','1kg','包',1),(19,7,'当归片','1kg','件',2),(20,8,'酒白芍(酒炙)','酒炙1kg(安徽)','包',1),(21,8,'酒白芍(酒炙)','酒炙1kg(安徽)','件',2),(22,9,'川芎','1kg','包',1),(23,9,'川芎','1kg','件',2),(24,10,'牡丹皮','1kg(安徽)','包',1),(25,10,'牡丹皮','1kg(安徽)','件',2),(26,11,'麦冬','1kg','包',1),(27,11,'麦冬','1kg','件',2),(28,12,'黄连片','1kg','包',1),(29,12,'黄连片','1kg','件',2),(30,13,'秏牌玛咖酒','盒','盒',1),(31,13,'秏牌玛咖酒','盒','件',3),(32,13,'秏牌玛咖酒','盒','包',2),(33,14,'舒肝解郁胶囊','0.36g*28s','盒',1),(34,14,'舒肝解郁胶囊','0.36g*28s','包',2),(35,15,'保济丸','3.7g*20','盒',1),(36,15,'保济丸','3.7g*20','件',2),(37,16,'维胺酯胶囊','25mg*24s','盒',1),(38,16,'维胺酯胶囊','25mg*24s','包',2),(39,17,'通心络胶囊','0.26g*40s','瓶',1),(40,17,'通心络胶囊','0.26g*40s','件',3),(41,17,'通心络胶囊','0.26g*40s','包',2);

/*Table structure for table `zdb_menu` */

DROP TABLE IF EXISTS `zdb_menu`;

CREATE TABLE `zdb_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT '' COMMENT '名称',
  `g` varchar(32) DEFAULT '' COMMENT '分组名称',
  `m` varchar(32) DEFAULT '' COMMENT '模块名称',
  `a` varchar(32) DEFAULT '' COMMENT 'action 名称',
  `ico` varchar(32) NOT NULL DEFAULT '' COMMENT '图标',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父节点id',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '层级 1,2,3',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0 停用 1 启用',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0页面1按钮',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_menu` */

insert  into `zdb_menu`(`id`,`title`,`g`,`m`,`a`,`ico`,`pid`,`level`,`sort`,`remark`,`status`,`flag`) values (1,'商品总库','Admin','','','left-bg-base',0,1,1,'',1,0),(2,'客户管理','Admin','','','left-bg-customer',0,1,2,'',1,0),(3,'人员管理','Admin','','','left-bg-action',0,1,3,'',1,0),(4,'仓库设置','Admin','','','left-bg-depot',0,1,4,'',1,0),(5,'商品库存','Admin','','','left-bg-goods',0,1,5,'',1,0),(6,'预单管理','Admin','','','left-bg-order',0,1,6,'',1,0),(7,'配送管理','Admin','','','left-bg-deliver',0,1,7,'',1,0),(8,'赊款管理','Admin','','','left-bg-sheqian',0,1,8,'',1,0),(9,'站点管理','Admin','','','left-bg-goods',0,1,9,'',1,0),(11,'商品品类','Admin','GoodsCategory','index','',1,2,1,'',1,0),(12,'商品品牌','Admin','GoodsBrand','index','',1,2,2,'',1,0),(13,'商品总库','Admin','GoodsInfo','index','',1,2,3,'',1,0),(14,'商品预警','Admin','GoodsWarning','warning_view','',1,2,4,'',1,0),(21,'经销商','Admin','Dealer','index','',2,2,1,'',1,0),(22,'终端店','Admin','Shops','index','',2,2,2,'',1,0),(31,'角色列表','Admin','Role','index','',3,2,1,'',1,0),(32,'人员列表','Admin','Staff','index','',3,2,2,'',1,0),(33,'采单店铺','Admin','CollectShop','index','',3,2,3,'',1,0),(34,'配送线路','Admin','ShippingLine','index','',3,2,4,'',1,0),(41,'仓库列表','Admin','Depot','index','',4,2,1,'',1,0),(42,'仓库区域','Admin','Area','index','',4,2,2,'',1,0),(43,'仓库品类','Admin','Depot','category','',4,2,3,'',1,0),(44,'仓库品牌','Admin','Depot','brand','',4,2,4,'',1,0),(45,'仓库经销商','Admin','Depot','dealer','',4,2,5,'',1,0),(51,'商品入库','Admin','DepotIn','index','',5,2,1,'',1,0),(52,'商品出库','Admin','DepotOut','index','',5,2,2,'',1,0),(53,'商品库存','Admin','DepotStock','index','',5,2,3,'',1,0),(54,'仓库日志','Admin','DepotLog','index','',5,2,4,'',1,0),(61,'预单销售','Admin','PresaleOrder','index','',6,2,1,'',1,0),(62,'预单退货','Admin','PresaleReturn','index','',6,2,2,'',1,0),(63,'预单调换货','Admin','PresaleChange','index','',6,2,3,'',1,0),(64,'预单汇总（类型）','Admin','PresaleSummary','index','',6,2,4,'',1,0),(65,'预单汇总（店铺）','Admin','PresaleSummary','shop','',6,2,5,'',1,0),(66,'采购单管理','Admin','PurchaseOrder','index','',6,2,6,'',1,0),(71,'配送预单','Admin','DeliverPlan','list','',7,2,1,'',1,0),(72,'配送申请','Admin','DeliverApply','index','',7,2,2,'',1,0),(73,'配送退库','Admin','DeliverBack','index','',7,2,3,'',1,0),(74,'配送车存','Admin','DeliverStock','index','',7,2,4,'',1,0),(75,'配送车销','Admin','DeliverOrder','index','',7,2,5,'',1,0),(76,'配送退货','Admin','DeliverReturn','index','',7,2,6,'',1,0),(77,'配送调换货','Admin','DeliverChange','index','',7,2,7,'',1,0),(78,'配送对账','Admin','DeliverSummary','index','',7,2,8,'',1,0),(79,'配送汇总','Admin','DeliverOrgSummary','index','',7,2,9,'',1,0),(81,'赊款管理','Admin','SheQian','index','',8,2,1,'',1,0),(91,'信息管理','Admin','Msg','index','',9,2,1,'',1,0);

/*Table structure for table `zdb_msg` */

DROP TABLE IF EXISTS `zdb_msg`;

CREATE TABLE `zdb_msg` (
  `msg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1供货商加盟，2终端店加盟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未处理 1已处理',
  `realname` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `message` varchar(255) NOT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_msg` */

/*Table structure for table `zdb_org_action_config` */

DROP TABLE IF EXISTS `zdb_org_action_config`;

CREATE TABLE `zdb_org_action_config` (
  `saleman_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(10) NOT NULL DEFAULT '',
  `begin_time` varchar(10) NOT NULL DEFAULT '08:00',
  `end_time` varchar(10) NOT NULL DEFAULT '18:00',
  `interval` tinyint(3) unsigned NOT NULL DEFAULT '30',
  PRIMARY KEY (`saleman_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_action_config` */

insert  into `zdb_org_action_config`(`saleman_id`,`org_parent_id`,`truename`,`begin_time`,`end_time`,`interval`) values (3,1,'','7:00','18:00',6),(5,1,'','7:00','18:00',7),(6,1,'','0','0',7),(7,1,'','7:00','17:00',0);

/*Table structure for table `zdb_org_action_position` */

DROP TABLE IF EXISTS `zdb_org_action_position`;

CREATE TABLE `zdb_org_action_position` (
  `pos_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `saleman_id` int(10) unsigned NOT NULL DEFAULT '0',
  `today` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `dimension` varchar(30) NOT NULL DEFAULT '',
  `now_time` int(10) unsigned NOT NULL DEFAULT '0',
  `img` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`pos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_action_position` */

insert  into `zdb_org_action_position`(`pos_id`,`saleman_id`,`today`,`org_parent_id`,`longitude`,`dimension`,`now_time`,`img`) values (1,1,20161008,1,'0.0','0.0',1475898726,''),(2,1,20161009,1,'0.0','0.0',1475993724,''),(3,1,20161009,1,'0.0','0.0',1475993843,''),(4,3,20161009,1,'0.0','0.0',1475996852,''),(5,3,20161009,1,'0.0','0.0',1475996852,''),(6,3,20161009,1,'0.0','0.0',1476000360,''),(7,3,20161009,1,'0.0','0.0',1476007598,''),(8,3,20161009,1,'0.0','0.0',1476007598,''),(9,1,20161010,1,'0.0','0.0',1476066849,''),(10,3,20161011,1,'0.0','0.0',1476156002,''),(11,3,20161011,1,'0.0','0.0',1476156387,''),(12,0,20161103,1,'0','0',1478154557,''),(13,3,20161109,1,'0.0','0.0',1478673446,''),(14,3,20161109,1,'0.0','0.0',1478673466,''),(15,3,20161109,1,'0.0','0.0',1478673466,''),(16,3,20161109,1,'0.0','0.0',1478673477,''),(17,3,20161109,1,'0.0','0.0',1478673509,''),(18,3,20161109,1,'0.0','0.0',1478673937,'');

/*Table structure for table `zdb_org_customer` */

DROP TABLE IF EXISTS `zdb_org_customer`;

CREATE TABLE `zdb_org_customer` (
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_type` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shop_id`,`org_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_customer` */

insert  into `zdb_org_customer`(`shop_id`,`org_parent_id`,`shop_type`,`staff_id`,`add_time`) values (1,1,0,0,1471412826),(2,1,0,0,1471412826),(3,1,0,0,1471412826),(4,1,0,0,1471412826),(5,1,1,3,1475111360),(6,1,1,5,1475111396),(7,1,1,3,1475111477),(8,1,1,3,1475111563),(9,1,2,3,1475111578),(10,1,2,3,1475111618),(11,1,2,3,1475111658),(12,1,1,3,1475111702),(13,1,2,3,1475111742),(14,1,2,3,1475111811),(15,1,2,3,1475111936),(16,1,1,3,1475111992),(17,1,1,3,1475112078),(18,1,1,3,1475113109),(19,1,1,3,1475113142),(20,1,1,3,1475113174),(21,1,1,3,1475113229),(22,1,1,3,1475113863),(23,1,1,3,1475113864),(24,1,1,3,1475113865),(25,1,1,3,1475113866),(26,1,1,3,1475113867),(27,1,1,3,1475113868),(28,1,1,3,1475113869),(29,1,1,3,1475113870),(30,1,1,3,1475113871),(31,1,1,3,1475113872),(32,1,1,3,1475113873),(33,1,1,3,1475113874),(34,1,1,3,1475113875),(35,1,1,3,1475113876),(36,1,1,3,1475113877),(37,1,1,3,1475113878),(38,1,1,3,1475113879),(39,1,1,3,1475113880),(40,1,1,3,1475113881),(41,1,1,3,1475113882),(42,1,1,3,1475113883),(43,1,1,3,1475113884),(44,1,1,3,1475113885),(45,1,1,3,1475113886),(46,1,1,3,1475113887),(47,1,1,3,1475113888),(48,1,1,3,1475113889),(49,1,1,3,1475113890),(50,1,1,3,1475113891),(51,1,1,3,1475113892),(52,1,1,3,1475113893),(53,1,1,3,1475113894),(54,1,1,3,1475113895),(55,1,1,3,1475113896),(56,1,1,3,1475113897),(57,1,1,3,1475113898),(58,1,1,3,1475113899),(59,1,1,3,1475113900),(60,1,1,3,1475113901),(61,1,1,3,1475113902),(62,1,1,3,1475113903),(63,1,1,3,1475113904),(64,1,1,3,1475113905),(65,1,1,3,1475113906),(66,1,1,3,1475113907),(67,1,1,3,1475113908),(68,1,1,3,1475113909),(69,1,1,3,1475113910),(70,1,1,3,1475113911),(71,1,1,3,1475113912),(72,1,1,3,1475113913),(73,1,1,3,1475113914),(74,1,1,3,1475113915),(75,1,1,3,1475113916),(76,1,1,3,1475113917),(77,1,1,3,1475113918),(78,1,1,3,1475113919),(79,1,1,3,1475113920),(80,1,1,3,1475113921),(81,1,1,3,1475113922),(82,1,1,3,1475113923),(83,1,1,3,1475113924),(84,1,1,3,1475113925),(85,1,1,3,1475113926),(86,1,1,3,1475113927),(87,1,1,3,1475113928),(88,1,1,3,1475113929),(89,1,1,3,1475113930),(90,1,1,3,1475113931),(91,1,1,3,1475113932),(92,1,1,3,1475113933),(93,1,1,3,1475113934),(94,1,1,3,1475113935),(95,1,1,3,1475113936),(96,1,1,3,1475113937),(97,1,1,3,1475113938),(98,1,1,3,1475113939),(99,1,1,3,1475113940),(100,1,1,3,1475113941),(101,1,1,3,1475113942),(102,1,1,3,1475113943),(103,1,1,3,1475113944),(104,1,1,3,1475113945),(105,1,1,3,1475113946),(106,1,1,3,1475113947),(107,1,1,3,1475113948),(108,1,1,3,1475113949),(109,1,1,3,1475113950),(110,1,1,3,1475113951),(111,1,1,3,1475113952),(112,1,1,3,1475113953),(113,1,1,3,1475113954),(114,1,1,3,1475113955),(115,1,1,3,1475113956),(116,1,1,3,1475113957),(117,1,1,3,1475113958),(118,1,1,3,1475113959),(119,1,1,3,1475113960),(120,1,1,3,1475113961),(121,1,1,3,1475113962),(122,1,1,3,1475113963),(123,1,0,3,1475232386),(124,1,0,3,1475246930),(125,1,0,3,1475247953),(129,1,0,3,1475403530);

/*Table structure for table `zdb_org_dep` */

DROP TABLE IF EXISTS `zdb_org_dep`;

CREATE TABLE `zdb_org_dep` (
  `dep_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dep_name` varchar(30) NOT NULL DEFAULT '',
  `dep_header` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`dep_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_dep` */

insert  into `zdb_org_dep`(`dep_id`,`org_parent_id`,`dep_name`,`dep_header`,`remark`) values (1,1,'业务部','丽丽','');

/*Table structure for table `zdb_org_goods_convert` */

DROP TABLE IF EXISTS `zdb_org_goods_convert`;

CREATE TABLE `zdb_org_goods_convert` (
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  `unit_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_unit_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_jin_price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_base_price` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `wholesale_num` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cv_id`,`org_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_goods_convert` */

insert  into `zdb_org_goods_convert`(`cv_id`,`org_parent_id`,`goods_id`,`goods_name`,`goods_spec`,`goods_unit`,`unit_default`,`goods_unit_type`,`goods_jin_price`,`goods_base_price`,`wholesale_num`) values (15,3,5,'利多卡因氯已定气雾剂','60g','包',0,2,'0.00','0.00',0),(14,3,5,'利多卡因氯已定气雾剂','60g','件',0,3,'0.00','0.00',0),(13,3,5,'利多卡因氯已定气雾剂','60g','瓶',0,1,'0.00','0.00',0),(3,1,1,'注射用头孢唑啉钠','1g','包',0,2,'10.00','100.00',0),(2,1,1,'注射用头孢唑啉钠','1g','件',1,3,'10.00','100.00',0),(1,1,1,'注射用头孢唑啉钠','1g','瓶',0,1,'10.00','100.00',0),(12,1,4,'灵芝孢子粉（破壁）','2g*60包','包',1,2,'10.00','100.00',0),(11,1,4,'灵芝孢子粉（破壁）','2g*60包','件',0,3,'10.00','100.00',0),(10,1,4,'灵芝孢子粉（破壁）','2g*60包','盒',0,1,'10.00','100.00',0),(12,3,4,'灵芝孢子粉（破壁）','2g*60包','包',0,2,'0.00','0.00',0),(11,3,4,'灵芝孢子粉（破壁）','2g*60包','件',0,3,'0.00','0.00',0),(10,3,4,'灵芝孢子粉（破壁）','2g*60包','盒',0,1,'0.00','0.00',0),(9,1,3,'注射用盐酸大观霉素(卓青)','2g','包',1,2,'10.00','100.00',0),(8,1,3,'注射用盐酸大观霉素(卓青)','2g','件',0,3,'10.00','100.00',0),(7,1,3,'注射用盐酸大观霉素(卓青)','2g','盒',0,1,'10.00','100.00',0),(9,3,3,'注射用盐酸大观霉素(卓青)','2g','包',0,2,'0.00','0.00',0),(8,3,3,'注射用盐酸大观霉素(卓青)','2g','件',0,3,'0.00','0.00',0),(7,3,3,'注射用盐酸大观霉素(卓青)','2g','盒',0,1,'0.00','0.00',0),(3,3,1,'注射用头孢唑啉钠','1g','包',0,2,'0.00','0.00',0),(2,3,1,'注射用头孢唑啉钠','1g','件',0,3,'0.00','0.00',0),(1,3,1,'注射用头孢唑啉钠','1g','瓶',0,1,'0.00','0.00',0),(6,1,2,'阿莫西林胶囊','0.25g*24s','中包',0,2,'10.00','100.00',0),(5,1,2,'阿莫西林胶囊','0.25g*24s','件',0,3,'10.00','100.00',0),(4,1,2,'阿莫西林胶囊','0.25g*24s','盒',1,1,'10.00','100.00',0),(6,3,2,'阿莫西林胶囊','0.25g*24s','中包',0,2,'0.00','0.00',0),(5,3,2,'阿莫西林胶囊','0.25g*24s','件',0,3,'0.00','0.00',0),(4,3,2,'阿莫西林胶囊','0.25g*24s','盒',0,1,'0.00','0.00',0),(13,1,5,'利多卡因氯已定气雾剂','60g','瓶',0,1,'10.00','100.00',0),(14,1,5,'利多卡因氯已定气雾剂','60g','件',0,3,'10.00','100.00',0),(15,1,5,'利多卡因氯已定气雾剂','60g','包',1,2,'10.00','100.00',0),(26,3,11,'麦冬','1kg','包',0,1,'0.00','0.00',0),(27,3,11,'麦冬','1kg','件',0,2,'0.00','0.00',0),(26,1,11,'麦冬','1kg','包',0,1,'10.00','100.00',0),(27,1,11,'麦冬','1kg','件',1,2,'10.00','100.00',0),(20,1,8,'酒白芍(酒炙)','酒炙1kg(安徽)','包',0,1,'10.00','100.00',0),(21,1,8,'酒白芍(酒炙)','酒炙1kg(安徽)','件',1,2,'10.00','100.00',0),(22,3,9,'川芎','1kg','包',0,1,'0.00','0.00',0),(23,3,9,'川芎','1kg','件',0,2,'0.00','0.00',0),(22,1,9,'川芎','1kg','包',0,1,'10.00','100.00',0),(23,1,9,'川芎','1kg','件',1,2,'10.00','100.00',0),(24,3,10,'牡丹皮','1kg(安徽)','包',0,1,'0.00','0.00',0),(25,3,10,'牡丹皮','1kg(安徽)','件',0,2,'0.00','0.00',0),(24,1,10,'牡丹皮','1kg(安徽)','包',0,1,'10.00','100.00',0),(25,1,10,'牡丹皮','1kg(安徽)','件',1,2,'10.00','100.00',0),(28,1,12,'黄连片','1kg','包',0,1,'10.00','100.00',0),(29,1,12,'黄连片','1kg','件',1,2,'10.00','100.00',0),(16,1,6,'炙黄芪','1kg','包',0,1,'10.00','100.00',0),(17,1,6,'炙黄芪','1kg','件',1,2,'10.00','100.00',0),(18,1,7,'当归片','1kg','包',0,1,'10.00','100.00',0),(19,1,7,'当归片','1kg','件',1,2,'10.00','100.00',0),(16,3,6,'炙黄芪','1kg','包',0,1,'0.00','0.00',0),(17,3,6,'炙黄芪','1kg','件',0,2,'0.00','0.00',0),(30,1,13,'秏牌玛咖酒','盒','盒',1,1,'10.00','100.00',0),(31,1,13,'秏牌玛咖酒','盒','件',0,3,'10.00','100.00',0),(32,1,13,'秏牌玛咖酒','盒','包',0,2,'10.00','100.00',0),(35,1,15,'保济丸','3.7g*20','盒',0,1,'10.00','100.00',0),(36,1,15,'保济丸','3.7g*20','件',1,2,'10.00','100.00',0),(37,1,16,'维胺酯胶囊','25mg*24s','盒',0,1,'10.00','100.00',0),(38,1,16,'维胺酯胶囊','25mg*24s','包',0,2,'10.00','100.00',0),(39,1,17,'通心络胶囊','0.26g*40s','瓶',0,1,'10.00','100.00',0),(40,1,17,'通心络胶囊','0.26g*40s','件',0,3,'10.00','100.00',0),(41,1,17,'通心络胶囊','0.26g*40s','包',1,2,'10.00','100.00',0),(33,1,14,'舒肝解郁胶囊','0.36g*28s','盒',0,1,'10.00','100.00',0),(34,1,14,'舒肝解郁胶囊','0.36g*28s','包',1,2,'10.00','100.00',0);

/*Table structure for table `zdb_org_info` */

DROP TABLE IF EXISTS `zdb_org_info`;

CREATE TABLE `zdb_org_info` (
  `org_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_name` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `city` varchar(30) NOT NULL DEFAULT '',
  `district` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `contacts` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `zip_code` varchar(10) NOT NULL DEFAULT '',
  `remark` varchar(100) NOT NULL DEFAULT '',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `boss_num` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `saleman_num` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `work_num` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`org_id`),
  UNIQUE KEY `org_name` (`org_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_info` */

insert  into `zdb_org_info`(`org_id`,`org_name`,`province`,`city`,`district`,`address`,`contacts`,`telephone`,`mobile`,`zip_code`,`remark`,`reg_time`,`is_close`,`boss_num`,`saleman_num`,`work_num`) values (1,'DD药业','河北省','石家庄市','市辖区','石家庄市开发区','XXX','','13033330000','','',1474700440,0,1,10,2),(3,'新乐海鑫商贸','河北省','石家庄市','市辖区','13123','李四','','17033330000','','',1475055026,0,2,10,2);

/*Table structure for table `zdb_org_staff` */

DROP TABLE IF EXISTS `zdb_org_staff`;

CREATE TABLE `zdb_org_staff` (
  `staff_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dep_id` int(10) unsigned NOT NULL DEFAULT '0',
  `login_user` varchar(30) NOT NULL DEFAULT '',
  `login_pwd` char(32) NOT NULL DEFAULT '',
  `staff_name` varchar(30) NOT NULL DEFAULT '',
  `role_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `birth` varchar(10) NOT NULL DEFAULT '',
  `job_post` varchar(10) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(100) NOT NULL DEFAULT '',
  `pushid` varchar(50) NOT NULL DEFAULT '',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `login_user` (`login_user`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_staff` */

insert  into `zdb_org_staff`(`staff_id`,`org_parent_id`,`dep_id`,`login_user`,`login_pwd`,`staff_name`,`role_id`,`gender`,`birth`,`job_post`,`mobile`,`email`,`is_admin`,`remark`,`pushid`,`is_close`) values (1,1,1,'13033330000','e10adc3949ba59abbe56e057f20f883e','周建平',1,1,'','总经理','13033330000','',1,'','',0),(2,1,2,'13044440000','e10adc3949ba59abbe56e057f20f883e','白珍珍',2,1,'','财务主管','13044440000','',0,'','',0),(3,1,1,'13055550000','e10adc3949ba59abbe56e057f20f883e','杨二虎',3,1,'','销售总监','13055550000','',0,'','',0),(4,3,0,'17033330000','4911239f226f8e2c0bbb812bbea2ce2e','李四',1,0,'','','17033330000','',1,'','',0),(5,1,1,'13066660000','e10adc3949ba59abbe56e057f20f883e','张三',3,1,'','','13066660000','',0,'','',0),(6,1,1,'13077770000','e10adc3949ba59abbe56e057f20f883e','李四',3,1,'','','13077770000','',0,'','',0),(7,1,1,'13088880000','e10adc3949ba59abbe56e057f20f883e','孙五',3,1,'','','13088880000','',0,'','',0);

/*Table structure for table `zdb_org_staff_customer` */

DROP TABLE IF EXISTS `zdb_org_staff_customer`;

CREATE TABLE `zdb_org_staff_customer` (
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shop_id`,`staff_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_staff_customer` */

insert  into `zdb_org_staff_customer`(`shop_id`,`staff_id`,`org_parent_id`) values (1,3,1),(2,3,1),(3,3,1),(4,3,1),(5,3,1),(6,3,1),(6,5,1),(7,3,1),(8,3,1),(9,3,1),(10,3,1),(11,3,1),(12,3,1),(13,3,1),(14,3,1),(15,3,1),(16,3,1),(17,3,1),(18,3,1),(19,3,1),(20,3,1),(21,3,1),(22,3,1),(23,3,1),(23,5,1),(24,3,1),(25,3,1),(26,3,1),(27,3,1),(28,3,1),(29,3,1),(30,3,1),(31,3,1),(32,3,1),(33,3,1),(34,3,1),(35,3,1),(36,3,1),(37,3,1),(38,3,1),(39,3,1),(40,3,1),(41,3,1),(42,3,1),(43,3,1),(44,3,1),(45,3,1),(46,3,1),(47,3,1),(48,3,1),(49,3,1),(50,3,1),(51,3,1),(52,3,1),(53,3,1),(54,3,1),(55,3,1),(55,5,1),(56,3,1),(57,3,1),(58,3,1),(59,3,1),(60,3,1),(61,3,1),(62,3,1),(62,5,1),(63,3,1),(64,3,1),(65,3,1),(66,3,1),(67,3,1),(68,3,1),(69,3,1),(70,3,1),(71,3,1),(72,3,1),(73,3,1),(74,3,1),(75,3,1),(76,3,1),(77,3,1),(78,3,1),(79,3,1),(80,3,1),(81,3,1),(82,3,1),(83,3,1),(84,3,1),(85,3,1),(86,3,1),(87,3,1),(87,5,1),(88,3,1),(89,3,1),(90,3,1),(91,3,1),(92,3,1),(93,3,1),(94,3,1),(94,5,1),(95,3,1),(96,3,1),(97,3,1),(98,3,1),(99,3,1),(100,3,1),(101,3,1),(102,3,1),(103,3,1),(104,3,1),(105,3,1),(106,3,1),(107,3,1),(108,3,1),(109,3,1),(110,3,1),(111,3,1),(112,3,1),(113,3,1),(114,3,1),(115,3,1),(116,3,1),(117,3,1),(118,3,1),(119,3,1),(119,5,1),(120,3,1),(121,3,1),(122,3,1),(123,3,1),(124,3,1),(125,3,1),(129,3,1);

/*Table structure for table `zdb_org_staff_signin` */

DROP TABLE IF EXISTS `zdb_org_staff_signin`;

CREATE TABLE `zdb_org_staff_signin` (
  `signin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `today` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `dimension` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(300) NOT NULL DEFAULT '',
  `now_time` int(10) unsigned NOT NULL DEFAULT '0',
  `img` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`signin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_org_staff_signin` */

insert  into `zdb_org_staff_signin`(`signin_id`,`staff_id`,`today`,`org_parent_id`,`longitude`,`dimension`,`address`,`now_time`,`img`) values (1,5,1476071186,1,'114.520854','38.047397','河北省石家庄市长安区谈南路靠近长安区人民检察院检务公开大厅',1476071186,'signin/2016-10-10/57fb0f12e47ca.jpg');

/*Table structure for table `zdb_presale_change` */

DROP TABLE IF EXISTS `zdb_presale_change`;

CREATE TABLE `zdb_presale_change` (
  `change_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `change_code` varchar(30) NOT NULL DEFAULT '',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `order_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_remark` varchar(50) NOT NULL DEFAULT '',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_from` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`change_id`),
  UNIQUE KEY `change_code` (`change_code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_presale_change` */

/*Table structure for table `zdb_presale_change_goods` */

DROP TABLE IF EXISTS `zdb_presale_change_goods`;

CREATE TABLE `zdb_presale_change_goods` (
  `change_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_change_in` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_sepc` varchar(30) NOT NULL DEFAULT '',
  `singleprice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `number` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`change_id`,`cv_id`,`is_change_in`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_presale_change_goods` */

/*Table structure for table `zdb_presale_orders` */

DROP TABLE IF EXISTS `zdb_presale_orders`;

CREATE TABLE `zdb_presale_orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) NOT NULL DEFAULT '',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `order_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_from` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_remark` varchar(50) NOT NULL DEFAULT '',
  `order_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_code` (`order_code`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_presale_orders` */

insert  into `zdb_presale_orders`(`order_id`,`order_code`,`staff_id`,`org_parent_id`,`cust_id`,`repertory_id`,`cust_name`,`cust_contact`,`cust_address`,`cust_tel`,`order_total_money`,`order_status`,`order_from`,`order_remark`,`order_way`,`add_time`,`is_cancel`,`cancel_time`) values (113,'PO000520161111618760',5,1,4,1,'老张超市','张小军','和平东路300号','13864122789','600.00',1,1,'',1,1478852886,0,0),(112,'PO000520161111965098',5,1,2,1,'红霞超市','吕红霞','小张村','13099990000','600.00',1,1,'',1,1478852828,0,0),(111,'PO000320161111135328',3,1,3,1,'小陈商铺','陈六子','贾村','13077770000','2200.00',2,2,'',1,1478845264,0,0),(110,'PO000320161111857161',3,1,2,1,'红霞超市','吕红霞','小张村','13099990000','2900.00',2,2,'',1,1478843119,0,0);

/*Table structure for table `zdb_presale_orders_goods` */

DROP TABLE IF EXISTS `zdb_presale_orders_goods`;

CREATE TABLE `zdb_presale_orders_goods` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cuxiao` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_spec` varchar(30) NOT NULL DEFAULT '',
  `singleprice` decimal(8,2) NOT NULL DEFAULT '0.00',
  `number` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_total_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_remark` varchar(100) NOT NULL DEFAULT '',
  `unit_name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_id`,`cv_id`,`cuxiao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_presale_orders_goods` */

insert  into `zdb_presale_orders_goods`(`order_id`,`cv_id`,`cuxiao`,`cust_id`,`org_parent_id`,`goods_id`,`goods_name`,`goods_spec`,`singleprice`,`number`,`goods_total_money`,`goods_remark`,`unit_name`) values (113,21,0,4,1,8,'酒白芍(酒炙)','酒炙1kg(安徽)','100.00','6.00','600.00','','件'),(112,2,0,2,1,1,'注射用头孢唑啉钠','1g','100.00','6.00','600.00','','件'),(111,21,0,3,1,8,'酒白芍(酒炙)','酒炙1kg(安徽)','100.00','6.00','600.00','','件'),(111,27,0,3,1,11,'麦冬','1kg','100.00','1.00','100.00','','件'),(111,4,0,3,1,2,'阿莫西林胶囊','0.25g*24s','100.00','4.00','400.00','','盒'),(111,2,0,3,1,1,'注射用头孢唑啉钠','1g','100.00','6.00','600.00','','件'),(111,25,0,3,1,10,'牡丹皮','1kg(安徽)','100.00','3.00','300.00','','件'),(111,12,0,3,1,4,'灵芝孢子粉（破壁）','2g*60包','100.00','1.00','100.00','','包'),(111,15,0,3,1,5,'利多卡因氯已定气雾剂','60g','100.00','1.00','100.00','','包'),(110,12,0,2,1,4,'灵芝孢子粉（破壁）','2g*60包','100.00','9.00','900.00','','包'),(110,9,0,2,1,3,'注射用盐酸大观霉素(卓青)','2g','100.00','8.00','800.00','','包'),(110,4,0,2,1,2,'阿莫西林胶囊','0.25g*24s','100.00','6.00','600.00','','盒'),(110,2,0,2,1,1,'注射用头孢唑啉钠','1g','100.00','6.00','600.00','','件'),(106,45,1,1,1,45,'新疆大枣','500g','10.00','17.55','165.50','满减优惠，满100.00减20.00','斤'),(106,60,1,1,1,43,'红洋葱','500g','10.00','39.00','330.00','满减优惠，满100.00减20.00','袋'),(108,2,1,1,1,2,'麻山药','500g','4.28','10.00','42.80','限时赠品，每购买10斤，赠送麻山药/500g * 1斤','斤'),(108,2,2,1,1,2,'麻山药','500g','0.00','1.00','0.00','','斤'),(109,2,1,1,1,2,'麻山药','500g','4.28','10.00','42.80','限时赠品，每购买0斤，赠送/ * 0','斤'),(109,2,2,1,1,2,'麻山药','500g','0.00','1.00','0.00','','斤'),(109,9,0,1,1,9,'木耳菜','500g','4.99','12.00','59.88','','斤'),(109,12,0,1,1,12,'圣女果','500g','3.99','1.00','3.99','','斤'),(109,38,0,1,1,38,'西葫芦','500g','2.28','1.00','2.28','','斤'),(109,40,0,1,1,40,'油麦','500g','1.99','1.00','1.99','','斤'),(109,42,0,1,1,42,'空心菜','500g','4.49','1.00','4.49','','斤'),(109,43,1,1,1,43,'红洋葱','500g','0.88','1.00','0.88','限时特价，原价0.99, 现价0.88','斤'),(109,44,0,1,1,44,'圆茄子','500g','2.00','1.00','2.00','','斤'),(109,45,1,1,1,45,'新疆大枣','500g','10.00','22.00','200.00','满减优惠，满0减0','斤');

/*Table structure for table `zdb_presale_return` */

DROP TABLE IF EXISTS `zdb_presale_return`;

CREATE TABLE `zdb_presale_return` (
  `return_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_code` varchar(30) NOT NULL DEFAULT '',
  `cust_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `org_parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cust_name` varchar(30) NOT NULL DEFAULT '',
  `cust_contact` varchar(30) NOT NULL DEFAULT '',
  `cust_tel` varchar(30) NOT NULL DEFAULT '',
  `cust_address` varchar(100) NOT NULL DEFAULT '',
  `return_real_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_remark` varchar(50) NOT NULL DEFAULT '',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_from` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order_way` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`return_id`),
  UNIQUE KEY `return_code` (`return_code`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_presale_return` */

/*Table structure for table `zdb_presale_return_goods` */

DROP TABLE IF EXISTS `zdb_presale_return_goods`;

CREATE TABLE `zdb_presale_return_goods` (
  `return_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(30) NOT NULL DEFAULT '',
  `goods_sepc` varchar(30) NOT NULL DEFAULT '',
  `goods_money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00',
  `goods_unit` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`return_id`,`cv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_presale_return_goods` */

/*Table structure for table `zdb_purchase_orders` */

DROP TABLE IF EXISTS `zdb_purchase_orders`;

CREATE TABLE `zdb_purchase_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) NOT NULL DEFAULT '',
  `order_remark` varchar(50) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_cancel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0',
  `repertory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_name` varchar(30) NOT NULL DEFAULT '',
  `order_data` text,
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_purchase_orders` */

insert  into `zdb_purchase_orders`(`order_id`,`order_code`,`order_remark`,`add_time`,`is_cancel`,`cancel_time`,`repertory_id`,`staff_id`,`class_name`,`order_data`,`start_time`,`end_time`) values (6,'PE588220161012378942','11111111111111111',1476262010,0,0,1,7,'蔬菜','{\"shop_ids\":[\"6\"],\"data\":{\"1\":{\"goods_name\":\"\\u7d2b\\u85af\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1000 \\u65a4\",\"sales_number\":1000,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":4500,\"total_num\":1000,\"total_numstring\":\"1000 \\u65a4\",\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":4500,\"total_num\":1000,\"total_numstring\":\"1000 \\u65a4\",\"sales_number\":1000,\"sales_numstring\":\"1000 \\u65a4\"}}}}',1476201600,1476287999),(7,'PE732220161012963662','',1476262139,0,0,1,7,'蔬菜','{\"shop_ids\":{\"0\":\"1\",\"13\":\"126\",\"23\":\"16\",\"25\":\"2\",\"34\":\"6\",\"35\":\"3\",\"48\":\"4\",\"49\":\"5\",\"58\":\"129\",\"59\":\"130\",\"65\":\"8\"},\"data\":{\"1\":{\"goods_name\":\"\\u7d2b\\u85af\",\"goods_spec\":\"500g\",\"sales_numstring\":\"101071 \\u65a4\",\"sales_number\":101071,\"return_numstring\":\"2 \\u65a4\",\"return_number\":2,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":4784.79,\"total_num\":101071,\"total_numstring\":\"101071 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":139.81,\"total_num\":100034,\"total_numstring\":\"100034 \\u65a4\",\"sales_number\":100034,\"sales_numstring\":\"100034 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":9.98,\"total_num\":7,\"total_numstring\":\"7 \\u65a4\",\"sales_number\":7,\"sales_numstring\":\"7 \\u65a4\",\"return_number\":2,\"return_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":4545,\"total_num\":1010,\"total_numstring\":\"1010 \\u65a4\",\"sales_number\":1010,\"sales_numstring\":\"1010 \\u65a4\"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"2\":{\"goods_name\":\"\\u9ebb\\u5c71\\u836f\",\"goods_spec\":\"500g\",\"sales_numstring\":\"316 \\u65a4\",\"sales_number\":316,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_out_numstring\":\"2 \\u65a4\",\"change_out_number\":0,\"total\":1322.52,\"total_num\":318,\"total_numstring\":\"318 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":21.4,\"total_num\":15,\"total_numstring\":\"15 \\u65a4\",\"sales_number\":15,\"sales_numstring\":\"15 \\u65a4\",\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":17.12,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":1,\"sales_numstring\":\"1 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_in_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":1284,\"total_num\":300,\"total_numstring\":\"300 \\u65a4\",\"sales_number\":300,\"sales_numstring\":\"300 \\u65a4\"}},\"9\":{\"goods_name\":\"\\u6728\\u8033\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1900 \\u65a4\",\"sales_number\":1900,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":8383.2,\"total_num\":1900,\"total_numstring\":\"1900 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2445.1,\"total_num\":600,\"total_numstring\":\"600 \\u65a4\",\"sales_number\":600,\"sales_numstring\":\"600 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":5489,\"total_num\":1100,\"total_numstring\":\"1100 \\u65a4\",\"sales_number\":1100,\"sales_numstring\":\"1100 \\u65a4\"},\"5\":{\"cust_name\":\"\\u5e97\\u94fa1\",\"contact\":\"\\u4eba1\",\"telephone\":\"1401000001\",\"total\":499,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-49.9,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}},\"33\":{\"goods_name\":\"\\u767d\\u841d\\u535c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"200 \\u65a4\",\"sales_number\":200,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":296,\"total_num\":200,\"total_numstring\":\"200 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"4\":{\"cust_name\":\"\\u8001\\u5f20\\u8d85\\u5e02\",\"contact\":\"\\u5f20\\u5c0f\\u519b\",\"telephone\":\"13864122789\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"}},\"5\":{\"goods_name\":\"\\u5730\\u74dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"145 \\u65a4\",\"sales_number\":145,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":105.3,\"total_num\":145,\"total_numstring\":\"145 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":89.7,\"total_num\":125,\"total_numstring\":\"125 \\u65a4\",\"sales_number\":125,\"sales_numstring\":\"125 \\u65a4\"},\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"21\":{\"goods_name\":\"\\u767d\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"41\":{\"goods_name\":\"\\u751f\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"total\":496,\"total_num\":200,\"total_numstring\":\"200 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":248,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":248,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"100 \\u65a4\"}},\"43\":{\"goods_name\":\"\\u7ea2\\u6d0b\\u8471\",\"goods_spec\":\"500g\",\"sales_numstring\":\"3 \\u7bb1 2 \\u888b 9 \\u65a4\",\"sales_number\":329,\"return_numstring\":\"1 \\u7bb1 \",\"return_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":301.62,\"total_num\":329,\"total_numstring\":\"3 \\u7bb1 2 \\u888b 9 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":293.7,\"total_num\":320,\"total_numstring\":\"3 \\u7bb1 2 \\u888b \",\"sales_number\":320,\"sales_numstring\":\"3 \\u7bb1 2 \\u888b \"},\"129\":{\"cust_name\":\"\\u59e5\\u59e5\\u5bb6\\u4e86\\u4e86\",\"contact\":\"\\u53e3\\u7ea2\",\"telephone\":\"15011110004\",\"total\":4.4,\"total_num\":5,\"total_numstring\":\"5 \\u65a4\",\"sales_number\":5,\"sales_numstring\":\"5 \\u65a4\"},\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":3.52,\"total_num\":4,\"total_numstring\":\"4 \\u65a4\",\"sales_number\":4,\"sales_numstring\":\"4 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":0,\"total_num\":0,\"total_numstring\":0,\"return_number\":100,\"return_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \"}},\"3\":{\"goods_name\":\"\\u7eff\\u7518\\u5170\",\"goods_spec\":\"500g\",\"sales_numstring\":\"20 \\u65a4\",\"sales_number\":20,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":28.12,\"total_num\":20,\"total_numstring\":\"20 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":-1.48,\"total_num\":0,\"total_numstring\":0,\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"}},\"14\":{\"goods_name\":\"\\u957f\\u8c46\\u89d2\",\"goods_spec\":\"500g\",\"sales_numstring\":\"100000000 \\u65a4\",\"sales_number\":100000000,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":148000000,\"total_num\":100000000,\"total_numstring\":\"100000000 \\u65a4\",\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":148000000,\"total_num\":100000000,\"total_numstring\":\"100000000 \\u65a4\",\"sales_number\":100000000,\"sales_numstring\":\"100000000 \\u65a4\"}},\"42\":{\"goods_name\":\"\\u7a7a\\u5fc3\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"3 \\u65a4\",\"sales_number\":3,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":13.47,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":13.47,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":3,\"sales_numstring\":\"3 \\u65a4\"}},\"6\":{\"goods_name\":\"\\u9999\\u83c7\",\"goods_spec\":null,\"sales_numstring\":\"\",\"sales_number\":0,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}}}}',1475251200,1476287999),(8,'PE844120161012982806','',1476262139,0,0,1,0,'水果','{\"shop_ids\":{\"0\":\"1\",\"13\":\"126\",\"23\":\"16\",\"25\":\"2\",\"34\":\"6\",\"35\":\"3\",\"48\":\"4\",\"49\":\"5\",\"58\":\"129\",\"59\":\"130\",\"65\":\"8\"},\"data\":{\"45\":{\"goods_name\":\"\\u65b0\\u7586\\u5927\\u67a3\",\"goods_spec\":\"500g\",\"sales_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":100,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":100,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}}}}',1475251200,1476287999),(9,'PE231420161012729406','',1476263756,0,0,1,0,'蔬菜','{\"shop_ids\":[\"6\"],\"data\":{\"1\":{\"goods_name\":\"\\u7d2b\\u85af\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1000 \\u65a4\",\"sales_number\":1000,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":4500,\"total_num\":1000,\"total_numstring\":\"1000 \\u65a4\",\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":4500,\"total_num\":1000,\"total_numstring\":\"1000 \\u65a4\",\"sales_number\":1000,\"sales_numstring\":\"1000 \\u65a4\"}}}}',1476201600,1476287999),(10,'PE607420161017480429','',1476674171,0,0,1,0,'水果','{\"shop_ids\":[\"1\"],\"data\":{\"45\":{\"goods_name\":\"\\u65b0\\u7586\\u5927\\u67a3\",\"goods_spec\":\"500g\",\"sales_numstring\":\"23.25 \\u65a4\",\"sales_number\":23.25,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":232.5,\"total_num\":23.25,\"total_numstring\":\"23.25 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":232.5,\"total_num\":23.25,\"total_numstring\":\"23.25 \\u65a4\",\"sales_number\":23.25,\"sales_numstring\":\"23.25 \\u65a4\"}}}}',1476633600,1476719999),(11,'PE290820161020483560','',1476945856,0,0,1,0,'蔬菜','{\"shop_ids\":{\"0\":\"1\",\"13\":\"126\",\"23\":\"16\",\"25\":\"2\",\"34\":\"6\",\"35\":\"3\",\"47\":\"4\",\"48\":\"5\",\"56\":\"129\",\"57\":\"130\",\"84\":\"8\"},\"data\":{\"1\":{\"goods_name\":\"\\u7d2b\\u85af\",\"goods_spec\":\"500g\",\"sales_numstring\":\"101071 \\u65a4\",\"sales_number\":101071,\"return_numstring\":\"2 \\u65a4\",\"return_number\":2,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":4784.79,\"total_num\":101071,\"total_numstring\":\"101071 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":139.81,\"total_num\":100034,\"total_numstring\":\"100034 \\u65a4\",\"sales_number\":100034,\"sales_numstring\":\"100034 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":9.98,\"total_num\":7,\"total_numstring\":\"7 \\u65a4\",\"sales_number\":7,\"sales_numstring\":\"7 \\u65a4\",\"return_number\":2,\"return_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":4545,\"total_num\":1010,\"total_numstring\":\"1010 \\u65a4\",\"sales_number\":1010,\"sales_numstring\":\"1010 \\u65a4\"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"2\":{\"goods_name\":\"\\u9ebb\\u5c71\\u836f\",\"goods_spec\":\"500g\",\"sales_numstring\":\"338 \\u65a4\",\"sales_number\":338,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_out_numstring\":\"2 \\u65a4\",\"change_out_number\":0,\"total\":1408.12,\"total_num\":340,\"total_numstring\":\"340 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":107,\"total_num\":37,\"total_numstring\":\"37 \\u65a4\",\"sales_number\":37,\"sales_numstring\":\"37 \\u65a4\",\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":17.12,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":1,\"sales_numstring\":\"1 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_in_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":1284,\"total_num\":300,\"total_numstring\":\"300 \\u65a4\",\"sales_number\":300,\"sales_numstring\":\"300 \\u65a4\"}},\"9\":{\"goods_name\":\"\\u6728\\u8033\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1913 \\u65a4\",\"sales_number\":1913,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":8448.07,\"total_num\":1913,\"total_numstring\":\"1913 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2509.97,\"total_num\":613,\"total_numstring\":\"613 \\u65a4\",\"sales_number\":613,\"sales_numstring\":\"613 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":5489,\"total_num\":1100,\"total_numstring\":\"1100 \\u65a4\",\"sales_number\":1100,\"sales_numstring\":\"1100 \\u65a4\"},\"5\":{\"cust_name\":\"\\u5e97\\u94fa1\",\"contact\":\"\\u4eba1\",\"telephone\":\"1401000001\",\"total\":499,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-49.9,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}},\"33\":{\"goods_name\":\"\\u767d\\u841d\\u535c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"200 \\u65a4\",\"sales_number\":200,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":296,\"total_num\":200,\"total_numstring\":\"200 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"4\":{\"cust_name\":\"\\u8001\\u5f20\\u8d85\\u5e02\",\"contact\":\"\\u5f20\\u5c0f\\u519b\",\"telephone\":\"13864122789\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"}},\"5\":{\"goods_name\":\"\\u5730\\u74dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"145 \\u65a4\",\"sales_number\":145,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":105.3,\"total_num\":145,\"total_numstring\":\"145 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":89.7,\"total_num\":125,\"total_numstring\":\"125 \\u65a4\",\"sales_number\":125,\"sales_numstring\":\"125 \\u65a4\"},\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"21\":{\"goods_name\":\"\\u767d\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"41\":{\"goods_name\":\"\\u751f\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1204.36 \\u65a4\",\"sales_number\":1204.36,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"total\":3234.8128,\"total_num\":1304.36,\"total_numstring\":\"1304.36 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2986.8128,\"total_num\":1204.36,\"total_numstring\":\"1204.36 \\u65a4\",\"sales_number\":1204.36,\"sales_numstring\":\"1204.36 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":248,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"100 \\u65a4\"}},\"43\":{\"goods_name\":\"\\u7ea2\\u6d0b\\u8471\",\"goods_spec\":\"500g\",\"sales_numstring\":\"7 \\u7bb1 2 \\u888b \",\"sales_number\":720,\"return_numstring\":\"1 \\u7bb1 \",\"return_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":692.5,\"total_num\":720,\"total_numstring\":\"7 \\u7bb1 2 \\u888b \",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":684.58,\"total_num\":711,\"total_numstring\":\"7 \\u7bb1 1 \\u888b 1 \\u65a4\",\"sales_number\":711,\"sales_numstring\":\"7 \\u7bb1 1 \\u888b 1 \\u65a4\"},\"129\":{\"cust_name\":\"\\u59e5\\u59e5\\u5bb6\\u4e86\\u4e86\",\"contact\":\"\\u53e3\\u7ea2\",\"telephone\":\"15011110004\",\"total\":4.4,\"total_num\":5,\"total_numstring\":\"5 \\u65a4\",\"sales_number\":5,\"sales_numstring\":\"5 \\u65a4\"},\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":3.52,\"total_num\":4,\"total_numstring\":\"4 \\u65a4\",\"sales_number\":4,\"sales_numstring\":\"4 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":0,\"total_num\":0,\"total_numstring\":0,\"return_number\":100,\"return_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \"}},\"3\":{\"goods_name\":\"\\u7eff\\u7518\\u5170\",\"goods_spec\":\"500g\",\"sales_numstring\":\"20 \\u65a4\",\"sales_number\":20,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":28.12,\"total_num\":20,\"total_numstring\":\"20 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":-1.48,\"total_num\":0,\"total_numstring\":0,\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"}},\"42\":{\"goods_name\":\"\\u7a7a\\u5fc3\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"13 \\u65a4\",\"sales_number\":13,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":58.37,\"total_num\":13,\"total_numstring\":\"13 \\u65a4\",\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":13.47,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":3,\"sales_numstring\":\"3 \\u65a4\"},\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":44.9,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"40\":{\"goods_name\":\"\\u6cb9\\u9ea6\",\"goods_spec\":\"500g\",\"sales_numstring\":\"130 \\u65a4\",\"sales_number\":130,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":258.7,\"total_num\":130,\"total_numstring\":\"130 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":258.7,\"total_num\":130,\"total_numstring\":\"130 \\u65a4\",\"sales_number\":130,\"sales_numstring\":\"130 \\u65a4\"}},\"12\":{\"goods_name\":\"\\u5723\\u5973\\u679c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"2 \\u65a4\",\"sales_number\":2,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":7.98,\"total_num\":2,\"total_numstring\":\"2 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":7.98,\"total_num\":2,\"total_numstring\":\"2 \\u65a4\",\"sales_number\":2,\"sales_numstring\":\"2 \\u65a4\"}},\"44\":{\"goods_name\":\"\\u5706\\u8304\\u5b50\",\"goods_spec\":\"500g\",\"sales_numstring\":\"7 \\u5305 1 \\u65a4\",\"sales_number\":15,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":30,\"total_num\":15,\"total_numstring\":\"7 \\u5305 1 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":30,\"total_num\":15,\"total_numstring\":\"7 \\u5305 1 \\u65a4\",\"sales_number\":15,\"sales_numstring\":\"7 \\u5305 1 \\u65a4\"}},\"38\":{\"goods_name\":\"\\u897f\\u846b\\u82a6\",\"goods_spec\":\"500g\",\"sales_numstring\":\"9.25 \\u65a4\",\"sales_number\":9.25,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":21.09,\"total_num\":9.25,\"total_numstring\":\"9.25 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":21.09,\"total_num\":9.25,\"total_numstring\":\"9.25 \\u65a4\",\"sales_number\":9.25,\"sales_numstring\":\"9.25 \\u65a4\"}},\"6\":{\"goods_name\":\"\\u9999\\u83c7\",\"goods_spec\":null,\"sales_numstring\":\"\",\"sales_number\":0,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}}}}',1475251200,1476979199),(12,'PE776820161020608529','',1476945919,0,0,1,0,'蔬菜','{\"shop_ids\":{\"0\":\"1\",\"13\":\"126\",\"23\":\"16\",\"25\":\"2\",\"34\":\"6\",\"35\":\"3\",\"47\":\"4\",\"48\":\"5\",\"57\":\"129\",\"58\":\"130\",\"89\":\"8\"},\"data\":{\"1\":{\"goods_name\":\"\\u7d2b\\u85af\",\"goods_spec\":\"500g\",\"sales_numstring\":\"101071 \\u65a4\",\"sales_number\":101071,\"return_numstring\":\"2 \\u65a4\",\"return_number\":2,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":4784.79,\"total_num\":101071,\"total_numstring\":\"101071 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":139.81,\"total_num\":100034,\"total_numstring\":\"100034 \\u65a4\",\"sales_number\":100034,\"sales_numstring\":\"100034 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":9.98,\"total_num\":7,\"total_numstring\":\"7 \\u65a4\",\"sales_number\":7,\"sales_numstring\":\"7 \\u65a4\",\"return_number\":2,\"return_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":4545,\"total_num\":1010,\"total_numstring\":\"1010 \\u65a4\",\"sales_number\":1010,\"sales_numstring\":\"1010 \\u65a4\"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"2\":{\"goods_name\":\"\\u9ebb\\u5c71\\u836f\",\"goods_spec\":\"500g\",\"sales_numstring\":\"338 \\u65a4\",\"sales_number\":338,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_out_numstring\":\"2 \\u65a4\",\"change_out_number\":0,\"total\":1408.12,\"total_num\":340,\"total_numstring\":\"340 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":107,\"total_num\":37,\"total_numstring\":\"37 \\u65a4\",\"sales_number\":37,\"sales_numstring\":\"37 \\u65a4\",\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":17.12,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":1,\"sales_numstring\":\"1 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_in_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":1284,\"total_num\":300,\"total_numstring\":\"300 \\u65a4\",\"sales_number\":300,\"sales_numstring\":\"300 \\u65a4\"}},\"9\":{\"goods_name\":\"\\u6728\\u8033\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1913 \\u65a4\",\"sales_number\":1913,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":8448.07,\"total_num\":1913,\"total_numstring\":\"1913 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2509.97,\"total_num\":613,\"total_numstring\":\"613 \\u65a4\",\"sales_number\":613,\"sales_numstring\":\"613 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":5489,\"total_num\":1100,\"total_numstring\":\"1100 \\u65a4\",\"sales_number\":1100,\"sales_numstring\":\"1100 \\u65a4\"},\"5\":{\"cust_name\":\"\\u5e97\\u94fa1\",\"contact\":\"\\u4eba1\",\"telephone\":\"1401000001\",\"total\":499,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-49.9,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}},\"33\":{\"goods_name\":\"\\u767d\\u841d\\u535c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"200 \\u65a4\",\"sales_number\":200,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":296,\"total_num\":200,\"total_numstring\":\"200 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"4\":{\"cust_name\":\"\\u8001\\u5f20\\u8d85\\u5e02\",\"contact\":\"\\u5f20\\u5c0f\\u519b\",\"telephone\":\"13864122789\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"}},\"5\":{\"goods_name\":\"\\u5730\\u74dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"145 \\u65a4\",\"sales_number\":145,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":105.3,\"total_num\":145,\"total_numstring\":\"145 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":89.7,\"total_num\":125,\"total_numstring\":\"125 \\u65a4\",\"sales_number\":125,\"sales_numstring\":\"125 \\u65a4\"},\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"21\":{\"goods_name\":\"\\u767d\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"41\":{\"goods_name\":\"\\u751f\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1204.36 \\u65a4\",\"sales_number\":1204.36,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"total\":3234.8128,\"total_num\":1304.36,\"total_numstring\":\"1304.36 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2986.8128,\"total_num\":1204.36,\"total_numstring\":\"1204.36 \\u65a4\",\"sales_number\":1204.36,\"sales_numstring\":\"1204.36 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":248,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"100 \\u65a4\"}},\"43\":{\"goods_name\":\"\\u7ea2\\u6d0b\\u8471\",\"goods_spec\":\"500g\",\"sales_numstring\":\"7 \\u7bb1 2 \\u888b \",\"sales_number\":720,\"return_numstring\":\"1 \\u7bb1 \",\"return_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":692.5,\"total_num\":720,\"total_numstring\":\"7 \\u7bb1 2 \\u888b \",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":684.58,\"total_num\":711,\"total_numstring\":\"7 \\u7bb1 1 \\u888b 1 \\u65a4\",\"sales_number\":711,\"sales_numstring\":\"7 \\u7bb1 1 \\u888b 1 \\u65a4\"},\"129\":{\"cust_name\":\"\\u59e5\\u59e5\\u5bb6\\u4e86\\u4e86\",\"contact\":\"\\u53e3\\u7ea2\",\"telephone\":\"15011110004\",\"total\":4.4,\"total_num\":5,\"total_numstring\":\"5 \\u65a4\",\"sales_number\":5,\"sales_numstring\":\"5 \\u65a4\"},\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":3.52,\"total_num\":4,\"total_numstring\":\"4 \\u65a4\",\"sales_number\":4,\"sales_numstring\":\"4 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":0,\"total_num\":0,\"total_numstring\":0,\"return_number\":100,\"return_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \"}},\"3\":{\"goods_name\":\"\\u7eff\\u7518\\u5170\",\"goods_spec\":\"500g\",\"sales_numstring\":\"20 \\u65a4\",\"sales_number\":20,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":28.12,\"total_num\":20,\"total_numstring\":\"20 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":-1.48,\"total_num\":0,\"total_numstring\":0,\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"}},\"42\":{\"goods_name\":\"\\u7a7a\\u5fc3\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"13 \\u65a4\",\"sales_number\":13,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":58.37,\"total_num\":13,\"total_numstring\":\"13 \\u65a4\",\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":13.47,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":3,\"sales_numstring\":\"3 \\u65a4\"},\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":44.9,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"40\":{\"goods_name\":\"\\u6cb9\\u9ea6\",\"goods_spec\":\"500g\",\"sales_numstring\":\"130 \\u65a4\",\"sales_number\":130,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":258.7,\"total_num\":130,\"total_numstring\":\"130 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":258.7,\"total_num\":130,\"total_numstring\":\"130 \\u65a4\",\"sales_number\":130,\"sales_numstring\":\"130 \\u65a4\"}},\"12\":{\"goods_name\":\"\\u5723\\u5973\\u679c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"2 \\u65a4\",\"sales_number\":2,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":7.98,\"total_num\":2,\"total_numstring\":\"2 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":7.98,\"total_num\":2,\"total_numstring\":\"2 \\u65a4\",\"sales_number\":2,\"sales_numstring\":\"2 \\u65a4\"}},\"44\":{\"goods_name\":\"\\u5706\\u8304\\u5b50\",\"goods_spec\":\"500g\",\"sales_numstring\":\"7 \\u5305 1 \\u65a4\",\"sales_number\":15,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":30,\"total_num\":15,\"total_numstring\":\"7 \\u5305 1 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":30,\"total_num\":15,\"total_numstring\":\"7 \\u5305 1 \\u65a4\",\"sales_number\":15,\"sales_numstring\":\"7 \\u5305 1 \\u65a4\"}},\"38\":{\"goods_name\":\"\\u897f\\u846b\\u82a6\",\"goods_spec\":\"500g\",\"sales_numstring\":\"9.25 \\u65a4\",\"sales_number\":9.25,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":21.09,\"total_num\":9.25,\"total_numstring\":\"9.25 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":21.09,\"total_num\":9.25,\"total_numstring\":\"9.25 \\u65a4\",\"sales_number\":9.25,\"sales_numstring\":\"9.25 \\u65a4\"}},\"6\":{\"goods_name\":\"\\u9999\\u83c7\",\"goods_spec\":null,\"sales_numstring\":\"\",\"sales_number\":0,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}}}}',1475251200,1476979199),(13,'PE589120161020427200','',1476945919,0,0,1,0,'水果','{\"shop_ids\":{\"0\":\"1\",\"13\":\"126\",\"23\":\"16\",\"25\":\"2\",\"34\":\"6\",\"35\":\"3\",\"47\":\"4\",\"48\":\"5\",\"57\":\"129\",\"58\":\"130\",\"89\":\"8\"},\"data\":{\"45\":{\"goods_name\":\"\\u65b0\\u7586\\u5927\\u67a3\",\"goods_spec\":\"500g\",\"sales_numstring\":\"73.8 \\u65a4\",\"sales_number\":73.8,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":738,\"total_num\":73.8,\"total_numstring\":\"73.8 \\u65a4\",\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":100,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":638,\"total_num\":63.8,\"total_numstring\":\"63.8 \\u65a4\",\"sales_number\":63.8,\"sales_numstring\":\"63.8 \\u65a4\"}}}}',1475251200,1476979199),(14,'PE539820161020259494','',1476945983,0,0,1,7,'蔬菜','{\"shop_ids\":{\"0\":\"1\",\"13\":\"126\",\"23\":\"16\",\"25\":\"2\",\"34\":\"6\",\"35\":\"3\",\"47\":\"4\",\"48\":\"5\",\"56\":\"129\",\"57\":\"130\",\"84\":\"8\"},\"data\":{\"1\":{\"goods_name\":\"\\u7d2b\\u85af\",\"goods_spec\":\"500g\",\"sales_numstring\":\"101071 \\u65a4\",\"sales_number\":101071,\"return_numstring\":\"2 \\u65a4\",\"return_number\":2,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":4784.79,\"total_num\":101071,\"total_numstring\":\"101071 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":139.81,\"total_num\":100034,\"total_numstring\":\"100034 \\u65a4\",\"sales_number\":100034,\"sales_numstring\":\"100034 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":9.98,\"total_num\":7,\"total_numstring\":\"7 \\u65a4\",\"sales_number\":7,\"sales_numstring\":\"7 \\u65a4\",\"return_number\":2,\"return_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":4545,\"total_num\":1010,\"total_numstring\":\"1010 \\u65a4\",\"sales_number\":1010,\"sales_numstring\":\"1010 \\u65a4\"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":45,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"2\":{\"goods_name\":\"\\u9ebb\\u5c71\\u836f\",\"goods_spec\":\"500g\",\"sales_numstring\":\"338 \\u65a4\",\"sales_number\":338,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_out_numstring\":\"2 \\u65a4\",\"change_out_number\":0,\"total\":1408.12,\"total_num\":340,\"total_numstring\":\"340 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":107,\"total_num\":37,\"total_numstring\":\"37 \\u65a4\",\"sales_number\":37,\"sales_numstring\":\"37 \\u65a4\",\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":17.12,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":1,\"sales_numstring\":\"1 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"2 \\u65a4\",\"change_in_number\":2,\"change_in_numstring\":\"2 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":1284,\"total_num\":300,\"total_numstring\":\"300 \\u65a4\",\"sales_number\":300,\"sales_numstring\":\"300 \\u65a4\"}},\"9\":{\"goods_name\":\"\\u6728\\u8033\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1913 \\u65a4\",\"sales_number\":1913,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":8448.07,\"total_num\":1913,\"total_numstring\":\"1913 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2509.97,\"total_num\":613,\"total_numstring\":\"613 \\u65a4\",\"sales_number\":613,\"sales_numstring\":\"613 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":5489,\"total_num\":1100,\"total_numstring\":\"1100 \\u65a4\",\"sales_number\":1100,\"sales_numstring\":\"1100 \\u65a4\"},\"5\":{\"cust_name\":\"\\u5e97\\u94fa1\",\"contact\":\"\\u4eba1\",\"telephone\":\"1401000001\",\"total\":499,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-49.9,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\",\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}},\"33\":{\"goods_name\":\"\\u767d\\u841d\\u535c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"200 \\u65a4\",\"sales_number\":200,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":296,\"total_num\":200,\"total_numstring\":\"200 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"},\"4\":{\"cust_name\":\"\\u8001\\u5f20\\u8d85\\u5e02\",\"contact\":\"\\u5f20\\u5c0f\\u519b\",\"telephone\":\"13864122789\",\"total\":148,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"sales_number\":100,\"sales_numstring\":\"100 \\u65a4\"}},\"5\":{\"goods_name\":\"\\u5730\\u74dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"145 \\u65a4\",\"sales_number\":145,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":105.3,\"total_num\":145,\"total_numstring\":\"145 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":89.7,\"total_num\":125,\"total_numstring\":\"125 \\u65a4\",\"sales_number\":125,\"sales_numstring\":\"125 \\u65a4\"},\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":7.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"21\":{\"goods_name\":\"\\u767d\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"16\":{\"cust_name\":\"\\u8001\\u90d1\\u5546\\u5e97\",\"contact\":\"\\u8001\\u90d1\",\"telephone\":\"15177777777\",\"total\":0,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"41\":{\"goods_name\":\"\\u751f\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"1204.36 \\u65a4\",\"sales_number\":1204.36,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"total\":3234.8128,\"total_num\":1304.36,\"total_numstring\":\"1304.36 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":2986.8128,\"total_num\":1204.36,\"total_numstring\":\"1204.36 \\u65a4\",\"sales_number\":1204.36,\"sales_numstring\":\"1204.36 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":248,\"total_num\":100,\"total_numstring\":\"100 \\u65a4\",\"change_out_number\":0,\"change_out_numstring\":\"100 \\u65a4\"}},\"43\":{\"goods_name\":\"\\u7ea2\\u6d0b\\u8471\",\"goods_spec\":\"500g\",\"sales_numstring\":\"7 \\u7bb1 2 \\u888b \",\"sales_number\":720,\"return_numstring\":\"1 \\u7bb1 \",\"return_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":692.5,\"total_num\":720,\"total_numstring\":\"7 \\u7bb1 2 \\u888b \",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":684.58,\"total_num\":711,\"total_numstring\":\"7 \\u7bb1 1 \\u888b 1 \\u65a4\",\"sales_number\":711,\"sales_numstring\":\"7 \\u7bb1 1 \\u888b 1 \\u65a4\"},\"129\":{\"cust_name\":\"\\u59e5\\u59e5\\u5bb6\\u4e86\\u4e86\",\"contact\":\"\\u53e3\\u7ea2\",\"telephone\":\"15011110004\",\"total\":4.4,\"total_num\":5,\"total_numstring\":\"5 \\u65a4\",\"sales_number\":5,\"sales_numstring\":\"5 \\u65a4\"},\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":3.52,\"total_num\":4,\"total_numstring\":\"4 \\u65a4\",\"sales_number\":4,\"sales_numstring\":\"4 \\u65a4\"},\"8\":{\"cust_name\":\"\\u8001\\u5b59\\u5e97\\u94fa\",\"contact\":\"\\u8001\\u5b59\",\"telephone\":\"15133333333\",\"total\":0,\"total_num\":0,\"total_numstring\":0,\"return_number\":100,\"return_numstring\":\"1 \\u7bb1 \",\"change_in_number\":100,\"change_in_numstring\":\"1 \\u7bb1 \"}},\"3\":{\"goods_name\":\"\\u7eff\\u7518\\u5170\",\"goods_spec\":\"500g\",\"sales_numstring\":\"20 \\u65a4\",\"sales_number\":20,\"return_numstring\":\"1 \\u65a4\",\"return_number\":1,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":28.12,\"total_num\":20,\"total_numstring\":\"20 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":14.8,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"},\"126\":{\"cust_name\":\"\\u6263\\u4e86\",\"contact\":\"\\u54af\\u7834\",\"telephone\":\"13055550001\",\"total\":-1.48,\"total_num\":0,\"total_numstring\":0,\"return_number\":1,\"return_numstring\":\"1 \\u65a4\"}},\"42\":{\"goods_name\":\"\\u7a7a\\u5fc3\\u83dc\",\"goods_spec\":\"500g\",\"sales_numstring\":\"13 \\u65a4\",\"sales_number\":13,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":58.37,\"total_num\":13,\"total_numstring\":\"13 \\u65a4\",\"130\":{\"cust_name\":\"\\u79d1\\u76ee\\u6b27\\u8bfa\",\"contact\":\"\\u4e50\\u900f\",\"telephone\":\"15011110005\",\"total\":13.47,\"total_num\":3,\"total_numstring\":\"3 \\u65a4\",\"sales_number\":3,\"sales_numstring\":\"3 \\u65a4\"},\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":44.9,\"total_num\":10,\"total_numstring\":\"10 \\u65a4\",\"sales_number\":10,\"sales_numstring\":\"10 \\u65a4\"}},\"40\":{\"goods_name\":\"\\u6cb9\\u9ea6\",\"goods_spec\":\"500g\",\"sales_numstring\":\"130 \\u65a4\",\"sales_number\":130,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":258.7,\"total_num\":130,\"total_numstring\":\"130 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":258.7,\"total_num\":130,\"total_numstring\":\"130 \\u65a4\",\"sales_number\":130,\"sales_numstring\":\"130 \\u65a4\"}},\"12\":{\"goods_name\":\"\\u5723\\u5973\\u679c\",\"goods_spec\":\"500g\",\"sales_numstring\":\"2 \\u65a4\",\"sales_number\":2,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":7.98,\"total_num\":2,\"total_numstring\":\"2 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":7.98,\"total_num\":2,\"total_numstring\":\"2 \\u65a4\",\"sales_number\":2,\"sales_numstring\":\"2 \\u65a4\"}},\"44\":{\"goods_name\":\"\\u5706\\u8304\\u5b50\",\"goods_spec\":\"500g\",\"sales_numstring\":\"7 \\u5305 1 \\u65a4\",\"sales_number\":15,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":30,\"total_num\":15,\"total_numstring\":\"7 \\u5305 1 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":30,\"total_num\":15,\"total_numstring\":\"7 \\u5305 1 \\u65a4\",\"sales_number\":15,\"sales_numstring\":\"7 \\u5305 1 \\u65a4\"}},\"38\":{\"goods_name\":\"\\u897f\\u846b\\u82a6\",\"goods_spec\":\"500g\",\"sales_numstring\":\"9.25 \\u65a4\",\"sales_number\":9.25,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":21.09,\"total_num\":9.25,\"total_numstring\":\"9.25 \\u65a4\",\"1\":{\"cust_name\":\"\\u9686\\u660c\\u8d85\\u5e02\",\"contact\":\"\\u738b\\u5efa\\u6c11\",\"telephone\":\"13088880000\",\"total\":21.09,\"total_num\":9.25,\"total_numstring\":\"9.25 \\u65a4\",\"sales_number\":9.25,\"sales_numstring\":\"9.25 \\u65a4\"}},\"6\":{\"goods_name\":\"\\u9999\\u83c7\",\"goods_spec\":null,\"sales_numstring\":\"\",\"sales_number\":0,\"return_numstring\":\"10 \\u65a4\",\"return_number\":10,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"6\":{\"cust_name\":\"\\u8001\\u8d75\\u8d85\\u5e02\",\"contact\":\"\\u8001\\u8d75\",\"telephone\":\"15111111111\",\"total\":-55,\"total_num\":0,\"total_numstring\":0,\"return_number\":10,\"return_numstring\":\"10 \\u65a4\"}}}}',1475251200,1476979199),(15,'PE425720161111338317','',1478853068,0,0,1,7,'参茸贵细类','{\"shop_ids\":{\"0\":\"2\",\"4\":\"3\",\"12\":\"4\"},\"data\":{\"4\":{\"goods_name\":\"\\u7075\\u829d\\u5b62\\u5b50\\u7c89\\uff08\\u7834\\u58c1\\uff09\",\"goods_spec\":\"2g*60\\u5305\",\"sales_numstring\":\"10 \\u5305 \",\"sales_number\":100,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":1000,\"total_num\":100,\"total_numstring\":\"10 \\u5305 \",\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":900,\"total_num\":90,\"total_numstring\":\"9 \\u5305 \",\"sales_number\":90,\"sales_numstring\":\"9 \\u5305 \"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":100,\"total_num\":10,\"total_numstring\":\"1 \\u5305 \",\"sales_number\":10,\"sales_numstring\":\"1 \\u5305 \"}}}}',1478793600,1478879999),(16,'PE412020161111375991','',1478853068,0,0,1,7,'抗生素类抗感染药','{\"shop_ids\":{\"0\":\"2\",\"4\":\"3\",\"12\":\"4\"},\"data\":{\"3\":{\"goods_name\":\"\\u6ce8\\u5c04\\u7528\\u76d0\\u9178\\u5927\\u89c2\\u9709\\u7d20(\\u5353\\u9752)\",\"goods_spec\":\"2g\",\"sales_numstring\":\"8 \\u5305 \",\"sales_number\":80,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":800,\"total_num\":80,\"total_numstring\":\"8 \\u5305 \",\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":800,\"total_num\":80,\"total_numstring\":\"8 \\u5305 \",\"sales_number\":80,\"sales_numstring\":\"8 \\u5305 \"}},\"2\":{\"goods_name\":\"\\u963f\\u83ab\\u897f\\u6797\\u80f6\\u56ca\",\"goods_spec\":\"0.25g*24s\",\"sales_numstring\":\"1 \\u4e2d\\u5305 \",\"sales_number\":10,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":1000,\"total_num\":10,\"total_numstring\":\"1 \\u4e2d\\u5305 \",\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":600,\"total_num\":6,\"total_numstring\":\"6 \\u76d2\",\"sales_number\":6,\"sales_numstring\":\"6 \\u76d2\"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":400,\"total_num\":4,\"total_numstring\":\"4 \\u76d2\",\"sales_number\":4,\"sales_numstring\":\"4 \\u76d2\"}}}}',1478793600,1478879999),(17,'PE593420161111275895','',1478853068,0,0,1,7,'西药','{\"shop_ids\":{\"0\":\"2\",\"4\":\"3\",\"12\":\"4\"},\"data\":{\"1\":{\"goods_name\":\"\\u6ce8\\u5c04\\u7528\\u5934\\u5b62\\u5511\\u5549\\u94a0\",\"goods_spec\":\"1g\",\"sales_numstring\":\"18 \\u4ef6 \",\"sales_number\":45900,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":1800,\"total_num\":45900,\"total_numstring\":\"18 \\u4ef6 \",\"2\":{\"cust_name\":\"\\u7ea2\\u971e\\u8d85\\u5e02\",\"contact\":\"\\u5415\\u7ea2\\u971e\",\"telephone\":\"13099990000\",\"total\":1200,\"total_num\":30600,\"total_numstring\":\"12 \\u4ef6 \",\"sales_number\":30600,\"sales_numstring\":\"12 \\u4ef6 \"},\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":600,\"total_num\":15300,\"total_numstring\":\"6 \\u4ef6 \",\"sales_number\":15300,\"sales_numstring\":\"6 \\u4ef6 \"}},\"5\":{\"goods_name\":\"\\u5229\\u591a\\u5361\\u56e0\\u6c2f\\u5df2\\u5b9a\\u6c14\\u96fe\\u5242\",\"goods_spec\":\"60g\",\"sales_numstring\":\"1 \\u5305 \",\"sales_number\":12,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":100,\"total_num\":12,\"total_numstring\":\"1 \\u5305 \",\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":100,\"total_num\":12,\"total_numstring\":\"1 \\u5305 \",\"sales_number\":12,\"sales_numstring\":\"1 \\u5305 \"}}}}',1478793600,1478879999),(18,'PE873820161111440290','',1478853068,0,0,1,7,'中药饮片','{\"shop_ids\":{\"0\":\"2\",\"4\":\"3\",\"12\":\"4\"},\"data\":{\"8\":{\"goods_name\":\"\\u9152\\u767d\\u828d(\\u9152\\u7099)\",\"goods_spec\":\"\\u9152\\u70991kg(\\u5b89\\u5fbd)\",\"sales_numstring\":\"12 \\u4ef6 \",\"sales_number\":180,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":1200,\"total_num\":180,\"total_numstring\":\"12 \\u4ef6 \",\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":600,\"total_num\":90,\"total_numstring\":\"6 \\u4ef6 \",\"sales_number\":90,\"sales_numstring\":\"6 \\u4ef6 \"},\"4\":{\"cust_name\":\"\\u8001\\u5f20\\u8d85\\u5e02\",\"contact\":\"\\u5f20\\u5c0f\\u519b\",\"telephone\":\"13864122789\",\"total\":600,\"total_num\":90,\"total_numstring\":\"6 \\u4ef6 \",\"sales_number\":90,\"sales_numstring\":\"6 \\u4ef6 \"}},\"10\":{\"goods_name\":\"\\u7261\\u4e39\\u76ae\",\"goods_spec\":\"1kg(\\u5b89\\u5fbd)\",\"sales_numstring\":\"3 \\u4ef6 \",\"sales_number\":45,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":300,\"total_num\":45,\"total_numstring\":\"3 \\u4ef6 \",\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":300,\"total_num\":45,\"total_numstring\":\"3 \\u4ef6 \",\"sales_number\":45,\"sales_numstring\":\"3 \\u4ef6 \"}},\"11\":{\"goods_name\":\"\\u9ea6\\u51ac\",\"goods_spec\":\"1kg\",\"sales_numstring\":\"1 \\u4ef6 \",\"sales_number\":12,\"return_numstring\":\"\",\"return_number\":0,\"change_in_numstring\":\"\",\"change_in_number\":0,\"change_out_numstring\":\"\",\"change_out_number\":0,\"total\":100,\"total_num\":12,\"total_numstring\":\"1 \\u4ef6 \",\"3\":{\"cust_name\":\"\\u5c0f\\u9648\\u5546\\u94fa\",\"contact\":\"\\u9648\\u516d\\u5b50\",\"telephone\":\"13077770000\",\"total\":100,\"total_num\":12,\"total_numstring\":\"1 \\u4ef6 \",\"sales_number\":12,\"sales_numstring\":\"1 \\u4ef6 \"}}}}',1478793600,1478879999);

/*Table structure for table `zdb_shipping_line` */

DROP TABLE IF EXISTS `zdb_shipping_line`;

CREATE TABLE `zdb_shipping_line` (
  `line_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `line_name` varchar(30) NOT NULL DEFAULT '',
  `line_desc` varchar(100) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `depot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`line_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `zdb_shipping_line` */

insert  into `zdb_shipping_line`(`line_id`,`line_name`,`line_desc`,`add_time`,`depot_id`) values (1,'新乐北线','测试专用',1471412826,1),(2,'新乐南线','测试专用',1471412826,1);

/*Table structure for table `zdb_shipping_shop` */

DROP TABLE IF EXISTS `zdb_shipping_shop`;

CREATE TABLE `zdb_shipping_shop` (
  `line_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`line_id`,`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_shipping_shop` */

insert  into `zdb_shipping_shop`(`line_id`,`shop_id`) values (1,1),(1,2),(1,3),(1,4),(2,1),(2,2),(2,4),(2,124),(2,125),(2,127),(2,128);

/*Table structure for table `zdb_shipping_user` */

DROP TABLE IF EXISTS `zdb_shipping_user`;

CREATE TABLE `zdb_shipping_user` (
  `line_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`line_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zdb_shipping_user` */

insert  into `zdb_shipping_user`(`line_id`,`user_id`,`add_time`) values (1,6,1478845836);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
