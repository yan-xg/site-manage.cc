# ************************************************************
# Host: 127.0.0.1 (MySQL 5.7.32-log)
# Database: web_manage
# Generation Time: 2021-01-22 09:01:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table bsa_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_admin`;

CREATE TABLE `bsa_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `admin_name` varchar(55) NOT NULL COMMENT '管理员名字',
  `admin_password` varchar(32) NOT NULL COMMENT '管理员密码',
  `role_id` int(11) DEFAULT NULL COMMENT '所属角色',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`admin_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `bsa_admin` WRITE;
/*!40000 ALTER TABLE `bsa_admin` DISABLE KEYS */;

INSERT INTO `bsa_admin` (`admin_id`, `admin_name`, `admin_password`, `role_id`, `status`, `add_time`, `last_login_time`, `update_time`)
VALUES
	(1,'admin','21232f297a57a5a743894a0e4a801fc3',1,1,'2019-09-03 13:31:20','2021-01-22 16:17:29',NULL),
	(3,'小白','d41d8cd98f00b204e9800998ecf8427e',6,1,'0000-00-00 00:00:00','2019-10-11 10:32:38',NULL);

/*!40000 ALTER TABLE `bsa_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bsa_login_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_login_log`;

CREATE TABLE `bsa_login_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `login_user` varchar(55) NOT NULL COMMENT '登录用户',
  `login_ip` varchar(15) NOT NULL COMMENT '登录ip',
  `login_area` varchar(55) DEFAULT NULL COMMENT '登录地区',
  `login_user_agent` varchar(155) DEFAULT NULL COMMENT '登录设备头',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `login_status` tinyint(1) DEFAULT '1' COMMENT '登录状态 1 成功 2 失败',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table bsa_node
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_node`;

CREATE TABLE `bsa_node` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `node_name` varchar(55) NOT NULL COMMENT '节点名称',
  `node_path` varchar(55) NOT NULL COMMENT '节点路径',
  `node_pid` int(11) NOT NULL COMMENT '所属节点',
  `node_icon` varchar(55) DEFAULT NULL COMMENT '节点图标',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1 不是 2 是',
  `add_time` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`node_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `bsa_node` WRITE;
/*!40000 ALTER TABLE `bsa_node` DISABLE KEYS */;

INSERT INTO `bsa_node` (`node_id`, `node_name`, `node_path`, `node_pid`, `node_icon`, `is_menu`, `add_time`)
VALUES
	(1,'主页','#',0,'layui-icon layui-icon-home',2,'2019-09-03 14:17:38'),
	(2,'后台首页','index/index',1,'',1,'2019-09-03 14:18:24'),
	(3,'修改密码','index/editpwd',1,'',1,'2019-09-03 14:19:03'),
	(4,'权限管理','#',0,'layui-icon layui-icon-template',2,'2019-09-03 14:19:34'),
	(5,'管理员管理','manager/index',4,'',2,'2019-09-03 14:27:42'),
	(6,'添加管理员','manager/addadmin',5,'',1,'2019-09-03 14:28:26'),
	(7,'编辑管理员','manager/editadmin',5,'',1,'2019-09-03 14:28:43'),
	(8,'删除管理员','manager/deladmin',5,'',1,'2019-09-03 14:29:14'),
	(9,'日志管理','#',0,'layui-icon layui-icon-template-1',2,'2019-10-08 16:07:36'),
	(10,'系统日志','log/system',9,'',2,'2019-10-08 16:24:55'),
	(11,'登录日志','log/login',9,'',2,'2019-10-08 16:26:27'),
	(12,'操作日志','log/operate',9,'',2,'2019-10-08 17:02:10'),
	(13,'角色管理','role/index',4,'',2,'2019-10-09 21:35:54'),
	(14,'添加角色','role/add',13,'',1,'2019-10-09 21:40:06'),
	(15,'编辑角色','role/edit',13,'',1,'2019-10-09 21:40:53'),
	(16,'删除角色','role/delete',13,'',1,'2019-10-09 21:41:07'),
	(17,'权限分配','role/assignauthority',13,'',1,'2019-10-09 21:41:38'),
	(18,'节点管理','node/index',4,'',2,'2019-10-09 21:42:06'),
	(19,'添加节点','node/add',18,'',1,'2019-10-09 21:42:51'),
	(20,'编辑节点','node/edit',18,'',1,'2019-10-09 21:43:29'),
	(21,'删除节点','node/delete',18,'',1,'2019-10-09 21:43:44'),
	(22,'站点管理','#',0,'layui-icon layui-icon-website',2,'2021-01-14 10:36:23'),
	(23,'站点管理','site/index',22,'',2,'2021-01-14 10:53:54'),
	(24,'创建站点','site/add',23,'',1,'2021-01-14 11:00:10'),
	(25,'编辑节点','site/edit',23,'',1,'2021-01-14 11:00:36'),
	(26,'主题管理','#',0,'layui-icon layui-icon-theme',2,'2021-01-15 11:05:37'),
	(27,'主题列表','theme/index',26,'',2,'2021-01-15 11:06:04'),
	(28,'操作日志','theme/log',26,'',2,'2021-01-15 11:06:26'),
	(29,'添加主题','theme/add',27,'',1,'2021-01-15 11:06:42'),
	(30,'更新主题','theme/edit',27,'',1,'2021-01-15 11:07:34'),
	(31,'删除主题','theme/del',27,'',1,'2021-01-15 11:07:54'),
	(32,'查看','site/read',23,'',1,'2021-01-15 11:25:58'),
	(33,'获取域名(配合创建、修改)','site/domain',23,'',1,'2021-01-18 11:10:00'),
	(34,'获取主题（配合创建、修改）','site/theme',23,'',1,'2021-01-18 11:38:07'),
	(35,'查看栏目','column/index',23,'',1,'2021-01-19 15:25:38'),
	(36,'编辑栏目','column/edit',23,'',1,'2021-01-19 15:28:04'),
	(37,'添加栏目','column/create',23,'',1,'2021-01-19 15:28:43'),
	(38,'删除栏目','column/delete',23,'',1,'2021-01-19 15:32:24'),
	(39,'文章内容','archives/index',23,'',1,'2021-01-19 17:59:43');

/*!40000 ALTER TABLE `bsa_node` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bsa_operate_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_operate_log`;

CREATE TABLE `bsa_operate_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '操作日志id',
  `operator` varchar(55) NOT NULL COMMENT '操作用户',
  `operator_ip` varchar(15) NOT NULL COMMENT '操作者ip',
  `operate_method` varchar(100) NOT NULL COMMENT '操作方法',
  `operate_desc` varchar(155) NOT NULL COMMENT '操作简述',
  `operate_time` datetime NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dump of table bsa_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_role`;

CREATE TABLE `bsa_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(55) NOT NULL COMMENT '角色名称',
  `role_node` varchar(255) NOT NULL COMMENT '角色拥有的权限节点',
  `role_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '角色状态 1 启用 2 禁用',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

LOCK TABLES `bsa_role` WRITE;
/*!40000 ALTER TABLE `bsa_role` DISABLE KEYS */;

INSERT INTO `bsa_role` (`role_id`, `role_name`, `role_node`, `role_status`)
VALUES
	(1,'超级管理员','#',1),
	(3,'会计','1,2,3',1),
	(4,'部门经理','1,2,3,4,5,6,7,8',1),
	(5,'DBA','1,2,3',1),
	(6,'研发','1,2,3,4,13,14,15,16,17,18,19,20,21,9,10,11,12',1);

/*!40000 ALTER TABLE `bsa_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bsa_site
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_site`;

CREATE TABLE `bsa_site` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '网站名称',
  `web_domain` varchar(255) NOT NULL COMMENT '域名',
  `m_domain` varchar(255) DEFAULT NULL COMMENT '自动将pc端域名换成m，并且如果是自适应模板，则无效',
  `temp_id` int(11) NOT NULL COMMENT '模版ID bsa_site的ID',
  `cat_id` tinyint(1) DEFAULT '1' COMMENT '分类 1 品牌站 2 综合站',
  `is_rewrite` tinyint(1) DEFAULT '1' COMMENT '是否伪静态 0 否  1  是 ',
  `rewrite_rule` text COMMENT '伪静态规则',
  `xiangmu` varchar(32) DEFAULT NULL COMMENT '留言用的项目名',
  `ip` char(16) DEFAULT NULL COMMENT 'IP地址',
  `laiyuan` varchar(100) DEFAULT NULL COMMENT '留言来源',
  `dianhua` char(11) DEFAULT NULL COMMENT '留言电话',
  `tongji` text COMMENT '百度统计代码',
  `head_code` text COMMENT '<head> 标签代码',
  `footer_code` text COMMENT '<body> 标签前面的代码',
  `page_views` int(11) DEFAULT '0' COMMENT '站点pv数',
  `page_count` int(11) DEFAULT '0' COMMENT '页面数量',
  `shoulu` int(11) DEFAULT '0' COMMENT '收录量',
  `guanjianci` varchar(255) DEFAULT '' COMMENT '关键词情况',
  `hj_site_id` int(11) DEFAULT NULL COMMENT 'PC端汇聚通站点id',
  `hj_m_site_id` int(11) DEFAULT NULL COMMENT '移动端汇聚通站点id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
  `create_status` tinyint(4) DEFAULT '0' COMMENT '站点创建状态 0 未创建 1 创建中 2 创建成功 3 创建失败  默认 0',
  `create_result` json DEFAULT NULL COMMENT '创建站点结果',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `edit_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`site_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站点信息';

LOCK TABLES `bsa_site` WRITE;
/*!40000 ALTER TABLE `bsa_site` DISABLE KEYS */;

INSERT INTO `bsa_site` (`site_id`, `name`, `web_domain`, `m_domain`, `temp_id`, `cat_id`, `is_rewrite`, `rewrite_rule`, `xiangmu`, `ip`, `laiyuan`, `dianhua`, `tongji`, `head_code`, `footer_code`, `page_views`, `page_count`, `shoulu`, `guanjianci`, `hj_site_id`, `hj_m_site_id`, `status`, `create_status`, `create_result`, `add_time`, `edit_time`)
VALUES
	(1,'黑白豆','192.168.9.10','m.baiduozi88.com',24,1,0,'','adf',NULL,'adf','adf','1','2','3',0,0,0,'',123123,123123,1,1,'{\"a\": 1}','2021-01-18 13:53:21','2021-01-22 12:54:20');

/*!40000 ALTER TABLE `bsa_site` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bsa_theme
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bsa_theme`;

CREATE TABLE `bsa_theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fzid` varchar(55) NOT NULL COMMENT '模版分支,先建立字段，暂时用不上，类型不能确定',
  `temp_name` varchar(255) NOT NULL COMMENT '模版名称',
  `temp_desc` text COMMENT '模版描述',
  `temp_url` varchar(255) NOT NULL COMMENT '放一个线上网址或者本地服务器网址',
  `temp_src` varchar(255) NOT NULL COMMENT 'pc模版源码的存放位置',
  `m_temp_src` varchar(255) DEFAULT NULL COMMENT 'm模版源码的存放位置',
  `cat_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 品牌站 2 综合站',
  `color` varchar(32) NOT NULL DEFAULT '0' COMMENT '主题颜色 0 无 1 红 2 黄 ....',
  `is_h5` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是响应式页面 0 否 1 是',
  `thumb` varchar(255) DEFAULT NULL COMMENT '主题缩略图',
  `yulantu` text COMMENT '多行文本，存放预览图路径和各页面名称（预览图数量不确定，所以需要自由控制添加图片数量）',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '当前模版创建了多少站点',
  `shoulu` varchar(255) DEFAULT '' COMMENT '当前模版所有站点的收录情况.',
  `paiming` varchar(255) DEFAULT '' COMMENT '当前模板所有站点的排名情况',
  `zhuanhua` varchar(255) DEFAULT '' COMMENT '当前模板所有站点的转化情况',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `edit_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`theme_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='主题信息';

LOCK TABLES `bsa_theme` WRITE;
/*!40000 ALTER TABLE `bsa_theme` DISABLE KEYS */;

INSERT INTO `bsa_theme` (`theme_id`, `fzid`, `temp_name`, `temp_desc`, `temp_url`, `temp_src`, `m_temp_src`, `cat_id`, `color`, `is_h5`, `thumb`, `yulantu`, `count`, `shoulu`, `paiming`, `zhuanhua`, `status`, `add_time`, `edit_time`)
VALUES
	(2,'0','主题2','描述描述描述描述描述描述描述描述','/theme/2/','/theme/2/',NULL,1,'0,2,4',0,'/upload/images/20210115/ff7e8d11692ffd299bef2c956d9cba9c.jpg','[{\"thumb\":\"\\/upload\\/images\\/20210115\\/c29ce8b7f56fb9bf563759163eefa937.jpg\",\"title\":\"1111\"},{\"thumb\":\"\\/upload\\/images\\/20210115\\/3d8ef57fda04220ab465472f2d7e0a5c.jpg\",\"title\":\"3333\"},{\"thumb\":\"\\/upload\\/images\\/20210115\\/f3d850ac3df4b762a13902f31cafad31.jpg\",\"title\":\"4444\"}]',0,'','','',1,'2021-01-15 09:56:03',NULL),
	(3,'0','主题3','主题描述主题描述主题描述主题描述主题描述主题描述主题描述主题描述主题描述','//127.0.0.1/theme/3/','/theme/3/',NULL,2,'0,1,2',1,'/upload/images/20210115/e78aea029a9a5c4247a35bee01d8b4db.jpg','[{\"thumb\":\"\\/upload\\/images\\/20210115\\/4cec05dc87786cb85942126a37525930.jpg\",\"title\":\"预览图1\"},{\"thumb\":\"\\/upload\\/images\\/20210115\\/4525cb2f32d28fb9244882c2d7954b93.jpg\",\"title\":\"预览图2\"},{\"thumb\":\"\\/upload\\/images\\/20210115\\/8ed939ac6a4be754e91251bcda0307bc.jpg\",\"title\":\"预览图3\"}]',0,'','','',1,'2021-01-15 10:06:31',NULL),
	(23,'0','主题测试','描述描述描述描述描述描述描述','','/ceshi/',NULL,1,'0',0,'/upload/images/20210118/fe8bb52ebbb8660d7c89596dbea14155.jpg','[{\"thumb\":\"\\/upload\\/images\\/20210118\\/800a407446208faf42c4d23ba0b59484.jpg\",\"title\":\"预览11\"},{\"thumb\":\"\\/upload\\/images\\/20210118\\/ecec3fe37794ca67910e6f961eae86be.jpg\",\"title\":\"\"},{\"thumb\":\"\\/upload\\/images\\/20210118\\/6eb48d3b582d0dc1bb09ead105faac46.jpg\",\"title\":\"\"}]',1,'','','',1,'2021-01-18 11:28:10','2021-01-18 18:42:37'),
	(10,'0','主题4','','//127.0.0.1/sss','/sss',NULL,1,'0,2',0,'','[{\"thumb\":\"\",\"title\":\"\"}]',0,'','','',1,'2021-01-15 10:23:18',NULL),
	(11,'0','主题11','','//127.0.0.1/11/','/11/',NULL,1,'1,4',0,'/upload/images/20210115/a024317a14fd4493925c08ccb40866fb.jpg','[{\"thumb\":\"\",\"title\":\"\"}]',0,'','','',0,'2021-01-15 11:12:02',NULL),
	(12,'0','主题12','','//192.168.9.119/12/','/12/',NULL,1,'1,2,3',0,'','[{\"thumb\":\"\\/upload\\/images\\/20210115\\/fbda94f6936b0362d73e9d2cd6646109.jpg\",\"title\":\"3432\"}]',0,'','','',1,'2021-01-15 11:17:36',NULL),
	(16,'0','主题13','','','/13/',NULL,1,'1,2',0,'/upload/images/20210115/ab6bb2fedd46dd830b0956c029d75846.jpg','[{\"thumb\":\"\\/upload\\/images\\/20210115\\/76c4bb3e51996bdd5c73d7a2dc65487e.jpg\",\"title\":\"\"},{\"thumb\":\"\\/upload\\/images\\/20210115\\/51f66f2dc3a006f1a9532bd27e1489ab.jpg\",\"title\":\"\"}]',0,'','','',1,'2021-01-15 13:06:27',NULL),
	(17,'0','主题14','','','/13/',NULL,1,'1,2',0,'/upload/images/20210115/ab6bb2fedd46dd830b0956c029d75846.jpg','[{\"thumb\":\"\\/upload\\/images\\/20210115\\/76c4bb3e51996bdd5c73d7a2dc65487e.jpg\",\"title\":\"\"},{\"thumb\":\"\\/upload\\/images\\/20210115\\/51f66f2dc3a006f1a9532bd27e1489ab.jpg\",\"title\":\"\"}]',0,'','','',1,'2021-01-15 13:07:07',NULL),
	(20,'0','theme111','描述描述描述','//www.5ucy.com','/111/',NULL,1,'0',0,'/upload/images/20210115/9767784465ca9f9d32fa082d36b42619.jpg','[{\"thumb\":\"  \\/upload\\/images\\/20210115\\/ea145cfe3aeea21204e451e43a134e41.jpg  \",\"title\":\"  1  \"},{\"thumb\":\"  \\/upload\\/images\\/20210115\\/367dcfc136b224577c833dc904f09592.jpg  \",\"title\":\"  2  \"},{\"thumb\":\"  \\/upload\\/images\\/20210115\\/b1cb0e1f7a6acb7dbc2376f647afc17f.jpg  \",\"title\":\"  3  \"}]',0,'','','',0,'2021-01-15 13:23:54','2021-01-18 09:28:13'),
	(21,'0','theme222更新','描述','//www.canin37.com','/22/',NULL,2,'2,4',0,'/upload/images/20210115/05d359131fda8b957b438de9ff6d4f8e.jpg','[{\"thumb\":\" \\/upload\\/images\\/20210115\\/c4e2e254b1f0e7cb8683d96a432fa845.jpg \",\"title\":\" 33说明 \"},{\"thumb\":\" \\/upload\\/images\\/20210115\\/66d59b06d3b8f5ab49182dd1bbcf0d07.jpg \",\"title\":\" 44说明 \"}]',0,'','','',1,'2021-01-15 13:33:03','2021-01-18 09:27:45'),
	(22,'0','更改一个主题','名字改不了啦啦啦啦','//www.baidu.com','/theme/pate/',NULL,2,'1,3',0,'/upload/images/20210115/9581d855c386aa7a581fbe12b006a769.jpg','[{\"thumb\":\"  \\/upload\\/images\\/20210115\\/4b38df7e6ee4f6999f698e7f0f36640a.jpg  \",\"title\":\"  预览图1  \"},{\"thumb\":\"  \\/upload\\/images\\/20210115\\/c2f54c815c13e1e6d846bee7fcfdfbe5.jpg  \",\"title\":\"  预览图3  \"},{\"thumb\":\"  \\/upload\\/images\\/20210115\\/e409b3c7d184fc59c731bbc83f90b19c.jpg  \",\"title\":\"  预览图5  \"},{\"thumb\":\"  \\/upload\\/images\\/20210115\\/1fd9e0f6e5b3fa49b86ce5c1c57eb11e.jpg  \",\"title\":\"  预览图6  \"}]',1,'','','',1,'2021-01-15 17:43:43','2021-01-18 14:39:21'),
	(24,'0','空白预览图主题测试','','','/cc/',NULL,1,'1,2',0,'/upload/images/20210118/cecc123a0763c5936d2d6136a501724e.jpg','[{\"thumb\":\"\\/upload\\/images\\/20210118\\/1bc54279a4e3f14e95cde7ca137fdd7e.jpg\",\"title\":\"空格&nbsp;\"},{\"thumb\":\"\\/upload\\/images\\/20210118\\/af936660dcee3e8e3c7748531cc09382.jpg\",\"title\":\"空格2&nbsp;&nbsp;\"}]',0,'','','',1,'2021-01-18 15:51:57','2021-01-18 16:13:44'),
	(27,'0','带移动的主题','','','E:\\phpstudy_pro\\WWW\\5ucy\\ad','E:\\phpstudy_pro\\WWW\\5ucy\\mobile',1,'0',0,'/upload/images/20210119/04921260a4bab8718226780b7ca70b0f.jpg','[{\"thumb\":\"\",\"title\":\"\"}]',0,'','','',1,'2021-01-19 16:36:21','2021-01-19 16:51:06');

/*!40000 ALTER TABLE `bsa_theme` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
