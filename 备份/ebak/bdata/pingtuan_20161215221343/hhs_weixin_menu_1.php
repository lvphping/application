<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `hhs_weixin_menu`;");
E_C("CREATE TABLE `hhs_weixin_menu` (
  `cat_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `weixin_key` varchar(255) NOT NULL DEFAULT '',
  `links` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `weixin_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `cat_type` (`cat_type`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8");
E_D("replace into `hhs_weixin_menu` values('59','线上商城','1','','','http://wfx.hostadmin.com.cn/tuan.php','2','1','0');");
E_D("replace into `hhs_weixin_menu` values('70','首页','1','','','http://wfx.hostadmin.com.cn/index.php','1','1','0');");
E_D("replace into `hhs_weixin_menu` values('78','快递查询','1','','','http://www.kuaidi100.com/?from=openv','50','1','77');");
E_D("replace into `hhs_weixin_menu` values('77','查询相关','1','','查询相关','','50','0','0');");
E_D("replace into `hhs_weixin_menu` values('75','在线客服','1','','','http://www.aikf.com/ask/h5/hhwl.htm','50','1','77');");

require("../../inc/footer.php");
?>