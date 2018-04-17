# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: pianyijiaowo
# Generation Time: 2018-04-17 07:16:03 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table subscribe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subscribe`;

CREATE TABLE `subscribe` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned NOT NULL,
  `goods_id` bigint(20) unsigned NOT NULL,
  `rule` varchar(50) NOT NULL DEFAULT '',
  `value` decimal(10,3) NOT NULL,
  `mobile` bigint(15) DEFAULT NULL,
  `email` tinyint(200) DEFAULT NULL,
  `current_price` decimal(10,3) NOT NULL DEFAULT '0.000',
  `notice_price` decimal(10,3) NOT NULL DEFAULT '0.000',
  `last_price` decimal(10,3) NOT NULL DEFAULT '0.000',
  `app_notice` tinyint(1) NOT NULL DEFAULT '0',
  `sms_notice` tinyint(1) NOT NULL DEFAULT '0',
  `email_notice` tinyint(1) NOT NULL DEFAULT '0',
  `wechat_notice` tinyint(1) NOT NULL DEFAULT '0',
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
