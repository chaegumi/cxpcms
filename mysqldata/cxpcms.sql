/*
Navicat MySQL Data Transfer

Source Server         : 1
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : cxpcms

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2015-11-28 21:26:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `addtime` int(11) DEFAULT '0',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `backgroundColor` varchar(255) DEFAULT NULL,
  `borderColor` varchar(255) DEFAULT NULL,
  `allDay` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of events
-- ----------------------------
INSERT INTO `events` VALUES ('3', 'sdfsdfsdf', '23207', '1447208656', '2015-11-12 00:00:00', '2015-11-13 00:00:00', '', 'rgb(221, 75, 57)', 'rgb(221, 75, 57)', '1');
INSERT INTO `events` VALUES ('4', 'sdfvsdfsdf', '23207', '1447208700', '2015-11-14 00:00:00', '2015-11-15 00:00:00', '', 'rgb(255, 133, 27)', '', '1');
INSERT INTO `events` VALUES ('16', 'sdfdsfgdf', '23207', '1447210716', '2015-07-28 00:00:00', '2015-07-29 00:00:00', null, '#3c8dbc', '#3c8dbc', '1');
INSERT INTO `events` VALUES ('11', 'xvfvdfv', '23207', '1447209687', '2015-11-10 20:00:00', '2015-11-11 00:00:00', null, '#3c8dbc', '#3c8dbc', '0');
INSERT INTO `events` VALUES ('13', 'sdfsdfsdfsdf', '23207', '1447209888', '2015-11-11 02:00:00', '2015-11-11 06:30:00', null, '#3c8dbc', '#3c8dbc', '0');
INSERT INTO `events` VALUES ('14', 'zdcsdvsgfbvsdfgb', '23207', '1447209899', '2015-11-14 00:00:00', '2015-11-14 03:30:00', null, 'rgb(96, 92, 168)', 'rgb(96, 92, 168)', '0');
INSERT INTO `events` VALUES ('15', 'sdvsdgsfg', '23207', '1447209906', '2015-11-13 19:00:00', '2015-11-14 00:00:00', null, '#3c8dbc', '#3c8dbc', '0');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `permKey` varchar(255) NOT NULL COMMENT '权限key',
  `permName` varchar(255) NOT NULL COMMENT '权限名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限编号',
  `lft` int(11) DEFAULT '0',
  `rgt` int(11) DEFAULT '0',
  `root_id` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `issys` int(11) NOT NULL DEFAULT '0' COMMENT '系统的',
  `permType` int(11) DEFAULT '0' COMMENT '权限类型',
  `rel_id` int(11) DEFAULT '0' COMMENT '权限对应类型对应记录的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permKey` (`permKey`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `lft` (`lft`) USING BTREE,
  KEY `rgt` (`rgt`) USING BTREE,
  KEY `root_id` (`root_id`) USING BTREE,
  KEY `issys` (`issys`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=858 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('125', 'admin_manage', 'Admin Manage', '0', '0', '0', '0', '2015-11-21 15:12:58', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('782', 'admin-permissions', 'Permissions', '125', '0', '0', '0', '2015-11-23 08:46:27', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('783', 'admin-add-permission', 'Add Permission', '782', '0', '0', '0', '2015-11-23 08:46:40', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('784', 'admin-del-permission', 'Delete Permission', '782', '0', '0', '0', '2015-11-23 08:46:55', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('785', 'admin-edit-permission', 'Edit Permission', '782', '0', '0', '0', '2015-11-23 08:47:07', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('821', 'admin-events', 'Calendar', '125', '0', '0', '0', '2015-11-24 09:41:40', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('822', 'admin-add-event', 'Add Event', '821', '0', '0', '0', '2015-11-24 09:41:54', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('823', 'admin-del-event', 'Delete Event', '821', '0', '0', '0', '2015-11-24 09:42:08', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('824', 'admin-edit-event', 'Edit Event', '821', '0', '0', '0', '2015-11-24 09:42:23', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('833', 'admin-users', 'Users', '125', '0', '0', '0', '2015-11-24 09:49:07', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('834', 'admin-add-user', 'Add User', '833', '0', '0', '0', '2015-11-24 09:49:22', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('835', 'admin-del-user', 'Delete User', '833', '0', '0', '0', '2015-11-24 09:49:37', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('836', 'admin-edit-user', 'Edit User', '833', '0', '0', '0', '2015-11-24 09:49:50', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('837', 'admin-roles', 'Roles', '125', '0', '0', '0', '2015-11-24 09:50:07', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('838', 'admin-add-role', 'Add Role', '837', '0', '0', '0', '2015-11-24 09:50:20', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('839', 'admin-del-role', 'Delete Role', '837', '0', '0', '0', '2015-11-24 09:50:39', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('840', 'admin-edit-role', 'Edit Role', '837', '0', '0', '0', '2015-11-24 09:50:54', '0', '0', '0', '0');
INSERT INTO `permissions` VALUES ('841', 'admin-login-log', 'Login Log', '125', '0', '0', '0', '2015-11-24 09:51:28', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色编号',
  `roleName` varchar(20) NOT NULL COMMENT '角色名称',
  `issys` int(11) NOT NULL DEFAULT '0' COMMENT '系统的',
  `company_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `issys` (`issys`) USING BTREE,
  KEY `roleName` (`roleName`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('3', 'Admin', '1', '0');
INSERT INTO `roles` VALUES ('4', 'Register User', '1', '0');
INSERT INTO `roles` VALUES ('5', 'Super Admin', '1', '0');

-- ----------------------------
-- Table structure for role_perms
-- ----------------------------
DROP TABLE IF EXISTS `role_perms`;
CREATE TABLE `role_perms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `roleID` bigint(20) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `permID` bigint(20) NOT NULL DEFAULT '0' COMMENT '权限编号',
  `value` tinyint(1) NOT NULL DEFAULT '0' COMMENT '值',
  `addDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roleID_2` (`roleID`,`permID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5323 DEFAULT CHARSET=utf8 COMMENT='角色权限表';

-- ----------------------------
-- Records of role_perms
-- ----------------------------
INSERT INTO `role_perms` VALUES ('5287', '5', '125', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5288', '5', '782', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5289', '5', '783', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5290', '5', '784', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5291', '5', '785', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5292', '5', '821', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5293', '5', '822', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5294', '5', '823', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5295', '5', '824', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5296', '5', '833', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5297', '5', '834', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5298', '5', '835', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5299', '5', '836', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5300', '5', '837', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5301', '5', '838', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5302', '5', '839', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5303', '5', '840', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5304', '5', '841', '1', '2015-11-28 20:53:18');
INSERT INTO `role_perms` VALUES ('5305', '4', '125', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5306', '4', '782', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5307', '4', '783', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5308', '4', '784', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5309', '4', '785', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5310', '4', '821', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5311', '4', '822', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5312', '4', '823', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5313', '4', '824', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5314', '4', '833', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5315', '4', '834', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5316', '4', '835', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5317', '4', '836', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5318', '4', '837', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5319', '4', '838', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5320', '4', '839', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5321', '4', '840', '0', '2015-11-28 20:53:40');
INSERT INTO `role_perms` VALUES ('5322', '4', '841', '0', '2015-11-28 20:53:40');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `username` varchar(255) NOT NULL COMMENT '登录名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `cur_login_time` datetime DEFAULT NULL COMMENT '当前登录时间',
  `cur_login_ip` varchar(255) DEFAULT NULL COMMENT '当前登录ip',
  `cur_login_area` varchar(255) DEFAULT NULL COMMENT '当前登录地区',
  `last_login_ip` varchar(255) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_area` varchar(255) DEFAULT NULL COMMENT '最后登录地区',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `reg_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `reg_ip` varchar(255) NOT NULL COMMENT '注册ip',
  `reg_area` varchar(255) NOT NULL COMMENT '注册地区',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `owner_sites` text,
  `parent_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `company_id` int(11) unsigned NOT NULL DEFAULT '0',
  `photo` varchar(255) DEFAULT NULL,
  `user_type` tinyint(1) unsigned DEFAULT '0',
  `issys` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `company_id` (`company_id`) USING BTREE,
  KEY `user_type` (`user_type`) USING BTREE,
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=23214 DEFAULT CHARSET=utf8 COMMENT='用户基本信息表';

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('3', 'admin', '$2y$10$xHMQKNYwEkhGwGIDM9rKo.lu2ZDUypqgv4oOi2DF2cTzH5n5sR2r.', 'chaegumi@qq.com', '2015-11-28 14:25:49', '127.0.0.1', '', '127.0.0.1', '', '2015-11-28 14:24:29', '2013-09-18 12:33:48', '::1', '', '1', '786', 'a:6:{i:0;s:2:\"52\";i:1;s:2:\"53\";i:2;s:2:\"55\";i:3;s:2:\"56\";i:4;s:2:\"57\";i:5;s:2:\"58\";}', '0', '0', null, '1', '1');

-- ----------------------------
-- Table structure for user_perms
-- ----------------------------
DROP TABLE IF EXISTS `user_perms`;
CREATE TABLE `user_perms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `userID` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  `permID` bigint(20) NOT NULL DEFAULT '0' COMMENT '权限编号',
  `value` tinyint(1) NOT NULL DEFAULT '0' COMMENT '值',
  `addDate` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userID` (`userID`,`permID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=849 DEFAULT CHARSET=utf8 COMMENT='用户权限表';

-- ----------------------------
-- Records of user_perms
-- ----------------------------

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `userID` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  `roleID` bigint(20) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `addDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userID` (`userID`,`roleID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of user_roles
-- ----------------------------
INSERT INTO `user_roles` VALUES ('3', '5', '2015-11-28 21:09:33', '79');
