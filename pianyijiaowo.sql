# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: pianyijiaowo
# Generation Time: 2018-04-08 13:21:31 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '账户',
  `login_password` varchar(60) NOT NULL COMMENT '密码',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `avatar` varchar(32) DEFAULT NULL COMMENT '头像',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '是否禁用',
  `login_time` int(11) unsigned DEFAULT NULL COMMENT '上次登陆时间',
  `create_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table crontab
# ------------------------------------------------------------

DROP TABLE IF EXISTS `crontab`;

CREATE TABLE `crontab` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(32) NOT NULL,
  `rule` text NOT NULL COMMENT '规则 可以是crontab规则也可以是json类型的精确时间任务',
  `unique` tinyint(5) DEFAULT '0' COMMENT '0 唯一任务 大于0表示同时可并行的任务进程个数',
  `job` varchar(32) NOT NULL COMMENT '运行这个任务的类',
  `data` text COMMENT '任务参数',
  `data_md5` char(32) DEFAULT '' COMMENT '任务参数md5值，用于去重',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 正常  1 暂停  2 删除',
  `create_time` int(11) unsigned DEFAULT NULL,
  `last_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '参数',
  `attempts` int(11) unsigned NOT NULL COMMENT '运行次数',
  `reserved` int(11) unsigned NOT NULL COMMENT '出列标记',
  `reserved_time` int(11) unsigned DEFAULT NULL,
  `available_time` int(11) unsigned NOT NULL COMMENT '可开始执行时间，延迟',
  `sort` int(11) DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL,
  `thread_id` int(11) DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员ID',
  `username` varchar(100) NOT NULL DEFAULT '',
  `login_password` char(60) NOT NULL DEFAULT '',
  `member_lv_id` mediumint(8) unsigned DEFAULT '0' COMMENT '会员等级',
  `avatar` char(32) DEFAULT NULL COMMENT '头像',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `area` varchar(255) DEFAULT NULL COMMENT '地区',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机',
  `tel` varchar(50) DEFAULT NULL COMMENT '固定电话',
  `email` varchar(200) DEFAULT '' COMMENT 'EMAIL',
  `zip` varchar(20) DEFAULT NULL COMMENT '邮编',
  `b_year` smallint(5) unsigned DEFAULT NULL COMMENT '生年',
  `b_month` tinyint(3) unsigned DEFAULT NULL COMMENT '生月',
  `b_day` tinyint(3) unsigned DEFAULT NULL COMMENT '生日',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别,0未知，1男，2女',
  `advance` decimal(20,3) unsigned DEFAULT '0.000' COMMENT '会员账户余额',
  `advance_freeze` decimal(20,3) DEFAULT '0.000' COMMENT '会员冻结金额',
  `reg_ip` varchar(16) DEFAULT NULL COMMENT '注册时IP地址',
  `login_count` int(11) DEFAULT '0' COMMENT '登录次数',
  `reg_time` int(11) unsigned DEFAULT NULL COMMENT '注册时间',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '是否可用',
  `remark` text COMMENT '备注',
  `login_time` int(11) DEFAULT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`id`),
  KEY `ind_regtime` (`reg_time`),
  KEY `ind_disabled` (`disabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text,
  `target` varchar(100) NOT NULL DEFAULT '',
  `member_id` int(11) unsigned DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table pam_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pam_account`;

CREATE TABLE `pam_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '账号ID',
  `login_account` varchar(100) NOT NULL COMMENT '登录账号',
  `login_type` enum('local','mobile','email') NOT NULL DEFAULT 'local' COMMENT '登录账号类型',
  PRIMARY KEY (`id`),
  KEY `ind_account` (`login_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table pam_member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pam_member`;

CREATE TABLE `pam_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `login_account` varchar(100) NOT NULL COMMENT '登录账号',
  `login_type` enum('local','mobile','email','wechat','qq','weibo','wxapp') NOT NULL DEFAULT 'local' COMMENT '登录账号类型',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `ind_account` (`login_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `resources` longtext COMMENT '权限资源',
  `is_super` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table role_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_account`;

CREATE TABLE `role_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table setting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `key` varchar(100) NOT NULL COMMENT 'key',
  `value` longtext COMMENT 'value',
  UNIQUE KEY `unique_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设置';



# Dump of table upload
# ------------------------------------------------------------

DROP TABLE IF EXISTS `upload`;

CREATE TABLE `upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `md5` char(32) DEFAULT '',
  `sha1` char(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `save_path` varchar(255) DEFAULT NULL,
  `save_name` varchar(255) DEFAULT '',
  `size` bigint(20) unsigned DEFAULT NULL,
  `ext` varchar(30) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `driver` varchar(30) DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
