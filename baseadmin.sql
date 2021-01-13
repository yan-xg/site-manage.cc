SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bsa_admin
-- ----------------------------
DROP TABLE IF EXISTS `bsa_admin`;
CREATE TABLE `bsa_admin`
(
    `admin_id`        int(11)     NOT NULL AUTO_INCREMENT COMMENT '管理员id',
    `admin_name`      varchar(55) NOT NULL COMMENT '管理员名字',
    `admin_password`  varchar(32) NOT NULL COMMENT '管理员密码',
    `role_id`         int(11)              DEFAULT NULL COMMENT '所属角色',
    `status`          tinyint(1)  NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
    `add_time`        datetime    NOT NULL COMMENT '添加时间',
    `last_login_time` datetime             DEFAULT NULL COMMENT '上次登录时间',
    `update_time`     datetime             DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`admin_id`) USING BTREE
) ENGINE = MyISAM
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bsa_admin
-- ----------------------------
INSERT INTO `bsa_admin`
VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1', '1', '2019-09-03 13:31:20', '2019-10-11 16:03:07', null);
INSERT INTO `bsa_admin`
VALUES ('3', '小白', 'd41d8cd98f00b204e9800998ecf8427e', '6', '1', '0000-00-00 00:00:00', '2019-10-11 10:32:38', null);

-- ----------------------------
-- Table structure for bsa_login_log
-- ----------------------------
DROP TABLE IF EXISTS `bsa_login_log`;
CREATE TABLE `bsa_login_log`
(
    `log_id`           int(11)     NOT NULL AUTO_INCREMENT COMMENT '日志id',
    `login_user`       varchar(55) NOT NULL COMMENT '登录用户',
    `login_ip`         varchar(15) NOT NULL COMMENT '登录ip',
    `login_area`       varchar(55)  DEFAULT NULL COMMENT '登录地区',
    `login_user_agent` varchar(155) DEFAULT NULL COMMENT '登录设备头',
    `login_time`       datetime     DEFAULT NULL COMMENT '登录时间',
    `login_status`     tinyint(1)   DEFAULT '1' COMMENT '登录状态 1 成功 2 失败',
    PRIMARY KEY (`log_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8;

-- ----------------------------
-- Records of bsa_login_log
-- ----------------------------
INSERT INTO `bsa_login_log`
VALUES ('1', 'admin', '127.0.0.1', '内网IP-内网IP',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36',
        '2019-10-11 16:03:07', '1');

-- ----------------------------
-- Table structure for bsa_node
-- ----------------------------
DROP TABLE IF EXISTS `bsa_node`;
CREATE TABLE `bsa_node`
(
    `node_id`   int(11)     NOT NULL AUTO_INCREMENT COMMENT '角色id',
    `node_name` varchar(55) NOT NULL COMMENT '节点名称',
    `node_path` varchar(55) NOT NULL COMMENT '节点路径',
    `node_pid`  int(11)     NOT NULL COMMENT '所属节点',
    `node_icon` varchar(55)          DEFAULT NULL COMMENT '节点图标',
    `is_menu`   tinyint(1)  NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1 不是 2 是',
    `add_time`  datetime             DEFAULT NULL COMMENT '添加时间',
    PRIMARY KEY (`node_id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 22
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bsa_node
-- ----------------------------
INSERT INTO `bsa_node`
VALUES ('1', '主页', '#', '0', 'layui-icon layui-icon-home', '2', '2019-09-03 14:17:38');
INSERT INTO `bsa_node`
VALUES ('2', '后台首页', 'index/index', '1', '', '1', '2019-09-03 14:18:24');
INSERT INTO `bsa_node`
VALUES ('3', '修改密码', 'index/editpwd', '1', '', '1', '2019-09-03 14:19:03');
INSERT INTO `bsa_node`
VALUES ('4', '权限管理', '#', '0', 'layui-icon layui-icon-template', '2', '2019-09-03 14:19:34');
INSERT INTO `bsa_node`
VALUES ('5', '管理员管理', 'manager/index', '4', '', '2', '2019-09-03 14:27:42');
INSERT INTO `bsa_node`
VALUES ('6', '添加管理员', 'manager/addadmin', '5', '', '1', '2019-09-03 14:28:26');
INSERT INTO `bsa_node`
VALUES ('7', '编辑管理员', 'manager/editadmin', '5', '', '1', '2019-09-03 14:28:43');
INSERT INTO `bsa_node`
VALUES ('8', '删除管理员', 'manager/deladmin', '5', '', '1', '2019-09-03 14:29:14');
INSERT INTO `bsa_node`
VALUES ('9', '日志管理', '#', '0', 'layui-icon layui-icon-template-1', '2', '2019-10-08 16:07:36');
INSERT INTO `bsa_node`
VALUES ('10', '系统日志', 'log/system', '9', '', '2', '2019-10-08 16:24:55');
INSERT INTO `bsa_node`
VALUES ('11', '登录日志', 'log/login', '9', '', '2', '2019-10-08 16:26:27');
INSERT INTO `bsa_node`
VALUES ('12', '操作日志', 'log/operate', '9', '', '2', '2019-10-08 17:02:10');
INSERT INTO `bsa_node`
VALUES ('13', '角色管理', 'role/index', '4', '', '2', '2019-10-09 21:35:54');
INSERT INTO `bsa_node`
VALUES ('14', '添加角色', 'role/add', '13', '', '1', '2019-10-09 21:40:06');
INSERT INTO `bsa_node`
VALUES ('15', '编辑角色', 'role/edit', '13', '', '1', '2019-10-09 21:40:53');
INSERT INTO `bsa_node`
VALUES ('16', '删除角色', 'role/delete', '13', '', '1', '2019-10-09 21:41:07');
INSERT INTO `bsa_node`
VALUES ('17', '权限分配', 'role/assignauthority', '13', '', '1', '2019-10-09 21:41:38');
INSERT INTO `bsa_node`
VALUES ('18', '节点管理', 'node/index', '4', '', '2', '2019-10-09 21:42:06');
INSERT INTO `bsa_node`
VALUES ('19', '添加节点', 'node/add', '18', '', '1', '2019-10-09 21:42:51');
INSERT INTO `bsa_node`
VALUES ('20', '编辑节点', 'node/edit', '18', '', '1', '2019-10-09 21:43:29');
INSERT INTO `bsa_node`
VALUES ('21', '删除节点', 'node/delete', '18', '', '1', '2019-10-09 21:43:44');

-- ----------------------------
-- Table structure for bsa_operate_log
-- ----------------------------
DROP TABLE IF EXISTS `bsa_operate_log`;
CREATE TABLE `bsa_operate_log`
(
    `log_id`         int(11)      NOT NULL AUTO_INCREMENT COMMENT '操作日志id',
    `operator`       varchar(55)  NOT NULL COMMENT '操作用户',
    `operator_ip`    varchar(15)  NOT NULL COMMENT '操作者ip',
    `operate_method` varchar(100) NOT NULL COMMENT '操作方法',
    `operate_desc`   varchar(155) NOT NULL COMMENT '操作简述',
    `operate_time`   datetime     NOT NULL COMMENT '操作时间',
    PRIMARY KEY (`log_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8;

-- ----------------------------
-- Records of bsa_operate_log
-- ----------------------------
INSERT INTO `bsa_operate_log`
VALUES ('1', 'admin', '127.0.0.1', 'manager/editadmin', '编辑管理员小白', '2019-10-11 14:19:21');
INSERT INTO `bsa_operate_log`
VALUES ('2', 'admin', '127.0.0.1', 'role/assignauthority', '分配权限6', '2019-10-11 14:19:37');
INSERT INTO `bsa_operate_log`
VALUES ('3', 'admin', '127.0.0.1', 'role/edit', '编辑角色研发', '2019-10-11 14:19:40');
INSERT INTO `bsa_operate_log`
VALUES ('4', 'admin', '127.0.0.1', 'role/edit', '编辑角色：部门经理', '2019-10-11 14:21:33');
INSERT INTO `bsa_operate_log`
VALUES ('5', 'admin', '127.0.0.1', 'node/edit', '编辑节点：主页', '2019-10-11 14:22:18');

-- ----------------------------
-- Table structure for bsa_role
-- ----------------------------
DROP TABLE IF EXISTS `bsa_role`;
CREATE TABLE `bsa_role`
(
    `role_id`     int(11)      NOT NULL AUTO_INCREMENT COMMENT '角色id',
    `role_name`   varchar(55)  NOT NULL COMMENT '角色名称',
    `role_node`   varchar(255) NOT NULL COMMENT '角色拥有的权限节点',
    `role_status` tinyint(1)   NOT NULL DEFAULT '1' COMMENT '角色状态 1 启用 2 禁用',
    PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 7
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bsa_role
-- ----------------------------
INSERT INTO `bsa_role`
VALUES ('1', '超级管理员', '#', '1');
INSERT INTO `bsa_role`
VALUES ('3', '会计', '1,2,3', '1');
INSERT INTO `bsa_role`
VALUES ('4', '部门经理', '1,2,3,4,5,6,7,8', '1');
INSERT INTO `bsa_role`
VALUES ('5', 'DBA', '1,2,3', '1');
INSERT INTO `bsa_role`
VALUES ('6', '研发', '1,2,3,4,13,14,15,16,17,18,19,20,21,9,10,11,12', '1');

-- ----------------------------
-- Table structure for bsa_theme
-- ----------------------------
DROP TABLE IF EXISTS `bsa_theme`;
CREATE TABLE `bsa_theme`
(
    `theme_id`  int(11)      NOT NULL AUTO_INCREMENT COMMENT 'id',
    `fzid`      varchar(55)  NOT NULL COMMENT '模版分支,先建立字段，暂时用不上，类型不能确定',
    `temp_name` varchar(255) NOT NULL COMMENT '模版名称',
    `temp_desc` text                  DEFAULT NULL COMMENT '模版描述',
    `temp_url`  varchar(255) not null comment '放一个线上网址或者本地服务器网址',
    `temp_src`  varchar(255) not null comment '模版源码的存放位置',
    `cat_id`    tinyint(1)   not null default 1 comment '1 品牌站 2 综合站',
    `color`     varchar(32)  not null default '0' comment '主题颜色 0 无 1 红 2 黄 ....',
    `is_h5`     tinyint(1)   not null default 0 comment '是否是响应式页面 0 否 1 是',
    `thumb`     varchar(255) comment '主题缩略图',
    `yulantu`   text comment '多行文本，存放预览图路径和各页面名称（预览图数量不确定，所以需要自由控制添加图片数量）',
    `count`     int(11)      not null default 0 comment '当前模版创建了多少站点',
    `shoulu`    varchar(255)          default '' comment '当前模版所有站点的收录情况.',
    `paiming`   varchar(255)          default '' comment '当前模板所有站点的排名情况',
    `zhuanhua`  varchar(255)          default '' comment '当前模板所有站点的转化情况',
    `status`    tinyint(1)   NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
    `add_time`  datetime     NOT NULL COMMENT '添加时间',
    `edit_time` datetime              DEFAULT NULL COMMENT '修改时间',
    PRIMARY KEY (`theme_id`) USING BTREE
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
    comment = '主题信息';


-- ----------------------------
-- Table structure for bsa_site
-- ----------------------------
DROP TABLE IF EXISTS `bsa_site`;
CREATE TABLE `bsa_site`
(
    `site_id`      int(11)      NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name`         varchar(255) not null comment '网站名称',
    `web_domain`   varchar(255) not null comment '域名',
    `m_domain`     varchar(255) comment '自动将pc端域名换成m，并且如果是自适应模板，则无效',
    `temp_id`      int(11)      not null comment '模版ID bsa_site的ID',
    `is_rewrite`   tinyint(1)            default 1 comment '伪静态或静态 1 静态 2 伪静态',
    `rewrite_rule` text comment '伪静态规则',
    `xiangmu`      varchar(32) comment '留言用的项目名',
    `laiyuan`      varchar(100) comment '留言来源',
    `dianhua`      char(11) comment '留言电话',
    `tongji`       text comment '百度统计代码',
    `head_code`    text comment '<head> 标签代码',
    `footer_code`  text comment '<body> 标签前面的代码',
    `page_views`   int(11)               default 0 comment '站点pv数',
    `page_count`   int(11)               default 0 comment '页面数量',
    `shoulu`       int(11)               default 0 comment '收录量',
    `guanjianci`   varchar(255)          default '' comment '关键词情况',
    `hj_site_id`   int(11) comment 'PC端汇聚通站点id',
    `hj_m_site_id` int(11) comment '移动端汇聚通站点id',
    `status`       tinyint(1)   NOT NULL DEFAULT '1' COMMENT '0 禁用 1 启用',
    `add_time`     datetime     NOT NULL COMMENT '添加时间',
    `edit_time`    datetime              DEFAULT NULL COMMENT '修改时间',
    PRIMARY KEY (`site_id`) USING BTREE
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
    comment = '站点信息';