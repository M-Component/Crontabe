# Crontabe
 基于Phalcon框架的 swoole多线程 + PHP 的定时任务和队列

Swoole 官网文档：https://wiki.swoole.com/wiki/page/1.html
Swoole 1.0文档：http://swoole.readthedocs.io/zh_CN/latest/index.html#

计划任务需要的数据库：
```
CREATE TABLE `crontab` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(100) NOT NULL DEFAULT '',
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
```

队列需要的数据库：
```
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

```
