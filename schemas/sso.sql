-- Adminer 4.8.1 MySQL 8.0.28-0ubuntu0.21.10.3 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `application_id` bigint unsigned NOT NULL,
  `access_level_id` tinyint unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_id_application_id` (`account_id`,`application_id`),
  KEY `account_id` (`account_id`),
  KEY `application_id` (`application_id`),
  KEY `access_level_id` (`access_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `access_devices`;
CREATE TABLE `access_devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `access_id` bigint unsigned NOT NULL DEFAULT '0',
  `access_token` varchar(255) DEFAULT NULL,
  `device_name` varchar(255) NOT NULL,
  `device_version` varchar(50) DEFAULT NULL,
  `device_id` varchar(64) DEFAULT NULL,
  `device_os` varchar(25) DEFAULT NULL,
  `device_os_version` varchar(25) DEFAULT NULL,
  `device_ua` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_status` tinyint unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `access_id` (`access_id`),
  KEY `access_token` (`access_token`),
  KEY `device_id_access_id` (`device_id`,`access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `access_levels`;
CREATE TABLE `access_levels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` char(20) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `access_levels`;
INSERT INTO `access_levels` (`id`, `slug`, `name`, `description`) VALUES
(1,	'admin',	'Administrator',	NULL),
(10,	'user',	'User',	NULL);

DROP TABLE IF EXISTS `access_logs`;
CREATE TABLE `access_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `access_id` bigint unsigned NOT NULL DEFAULT '0',
  `access_device_id` bigint unsigned NOT NULL DEFAULT '0',
  `type` enum('login','logout','update','recovery','register','unregister') NOT NULL,
  `date_access` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `access_id` (`access_id`),
  KEY `access_device_id` (`access_device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `account_contacts`;
CREATE TABLE `account_contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `token` varchar(20) NOT NULL,
  `type` enum('email','phone') NOT NULL DEFAULT 'email',
  `value` varchar(50) NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `account_otps`;
CREATE TABLE `account_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `access_id` bigint unsigned NOT NULL DEFAULT '0',
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `counter` int unsigned NOT NULL DEFAULT '1',
  `expired` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`otp_code`),
  KEY `account_id` (`account_id`),
  KEY `access_id_otp_code_status_expired` (`access_id`,`otp_code`,`status`,`expired`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `account_recoveries`;
CREATE TABLE `account_recoveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `access_id` bigint unsigned NOT NULL DEFAULT '0',
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `expired` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `account_id` (`account_id`),
  KEY `access_id` (`access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `account_roles`;
CREATE TABLE `account_roles` (
  `account_id` bigint unsigned NOT NULL,
  `role_id` int unsigned NOT NULL,
  `object_id` char(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`account_id`,`role_id`),
  KEY `role_id_object_id` (`role_id`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `account_roles`;
INSERT INTO `account_roles` (`account_id`, `role_id`, `object_id`, `status`) VALUES
(19101110739372,	1,	NULL,	1),
(21032303313462,	1,	NULL,	1),
(21032303313462,	2,	'22022806510325',	1),
(21092001156248,	1,	NULL,	1);

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` bigint unsigned NOT NULL,
  `uid` char(36) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(128) NOT NULL,
  `secret` char(60) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('P','L') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `google_id` varchar(50) DEFAULT NULL,
  `google_account` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `child_account` tinyint unsigned NOT NULL DEFAULT '0',
  `use_otp` tinyint unsigned NOT NULL DEFAULT '0',
  `otp_media` enum('none','email','telegram','oauth') NOT NULL DEFAULT 'none',
  `telegram_id` varchar(50) DEFAULT NULL,
  `date_joined` datetime DEFAULT NULL,
  `created_by` int unsigned NOT NULL DEFAULT '0',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `accounts`;
INSERT INTO `accounts` (`id`, `uid`, `name`, `username`, `password`, `secret`, `email`, `phone`, `dob`, `gender`, `address`, `avatar`, `google_id`, `google_account`, `status`, `child_account`, `use_otp`, `otp_media`, `telegram_id`, `date_joined`, `created_by`, `modified`) VALUES
(19101110739372,	'9d5e8718-4e5a-11ea-b42f-04013d452f01',	'Tes123',	'tes123',	'7868038f8544a13c746238443ce1f1c0',	NULL,	'tes@tes123.id',	'6282114566413',	'1977-10-31',	'L',	NULL,	'account/avatar_9d5e8718-4e5a-11ea-b42f-04013d452f01.jpg',	NULL,	NULL,	1,	0,	1,	'telegram',	NULL,	'2019-10-11 10:14:33',	0,	'2022-03-26 00:49:11'),
(21032303313462,	'7fc8654d-45e7-4db8-8f99-d74ccf7871d4',	'demo',	'demo123',	'5f4dcc3b5aa765d61d8327deb882cf99',	NULL,	'demo@tes123.my.id',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	0,	1,	'email',	NULL,	'2021-03-23 15:03:51',	4294967295,	'2021-03-23 08:03:51'),
(21092001156248,	'23f32fb2-e190-4933-a5d7-3e08af04b4b2',	'coba',	'coba',	'5f4dcc3b5aa765d61d8327deb882cf99',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	0,	0,	'none',	NULL,	'2021-09-20 13:18:35',	4294967295,	'2021-09-20 06:18:35');

DROP TABLE IF EXISTS `application_access_levels`;
CREATE TABLE `application_access_levels` (
  `application_id` bigint unsigned NOT NULL,
  `access_level_id` int unsigned NOT NULL,
  PRIMARY KEY (`application_id`,`access_level_id`),
  KEY `access_level_id` (`access_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `application_access_levels`;
INSERT INTO `application_access_levels` (`application_id`, `access_level_id`) VALUES
(2,	1),
(4,	1),
(5,	1),
(1,	10),
(2,	10),
(4,	10),
(5,	10);

DROP TABLE IF EXISTS `application_roles`;
CREATE TABLE `application_roles` (
  `application_id` bigint unsigned NOT NULL,
  `role_id` int unsigned NOT NULL,
  PRIMARY KEY (`application_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `application_roles`;
INSERT INTO `application_roles` (`application_id`, `role_id`) VALUES
(1,	1),
(2,	2),
(4,	2),
(5,	2);

DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appid` char(40) NOT NULL,
  `appkey` varchar(128) NOT NULL,
  `type` enum('app','web','app_web') NOT NULL DEFAULT 'app',
  `use_otp` tinyint unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `tag` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`appid`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `applications`;
INSERT INTO `applications` (`id`, `appid`, `appkey`, `type`, `use_otp`, `name`, `url`, `description`, `status`, `tag`, `created`, `modified`) VALUES
(1,	'9f111f5d2eeca8cd4a72612d3eeb8a141ca15121',	'$2y$10$CoOpmwbMp7LJBmHwkcOexO57J0Rn471vTo67kaAsGIEs2fbQGXeiC',	'web',	0,	'Account',	'http://account.tes123.me',	'SSO Account',	1,	'account',	'2021-03-22 21:27:13',	'2022-02-25 03:59:04'),
(2,	'13cc87277aa3fa46c0a023a69a75b1083ce70364',	'$2y$10$VA89PUV0V2Qf2VZ9uDfBW.up6RGPWbQCSwVihSK0M2ht2H5qDwkH6',	'web',	1,	'Admin Console',	'http://manage.tes123.me',	'SSO Management',	1,	NULL,	'2021-03-22 21:27:17',	'2022-02-25 03:59:04'),
(4,	'bcdf8ef53d1e174f91bcabde308368abbc2d2fa1',	'$2y$10$cotpXx2oKciwLkp9Ao744Or2pCqqngQjjLhz5CTURjLKeiWeR8CTK',	'web',	0,	'TaskMan',	'http://taskman.tes123.me',	'Task Manager',	1,	NULL,	'2021-05-27 06:56:14',	'2022-02-25 04:00:01'),
(5,	'5396f976bf67c159d34ddf31613049a23683139f',	'$2y$10$blDxbXKzy1Wr2caf.P6CyuD4WjMM7298VDIwuT.QDDvFDszsNBEB2',	'app_web',	0,	'Office',	'http://office.tes123.id',	'Office Manager',	1,	NULL,	'2022-02-27 16:52:36',	'2022-02-27 09:52:36');

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `type` enum('user','system') NOT NULL DEFAULT 'user',
  `subject` varchar(255) NOT NULL,
  `content` text,
  `read` tinyint unsigned NOT NULL DEFAULT '0',
  `tag` varchar(30) DEFAULT NULL,
  `data` text,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `account_id_type_read` (`account_id`,`type`,`read`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` char(20) NOT NULL,
  `name` varchar(25) NOT NULL,
  `object_name` varchar(25) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `selectable` tinyint unsigned NOT NULL DEFAULT '0',
  `icon` varchar(20) DEFAULT 'fa fa-user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `roles`;
INSERT INTO `roles` (`id`, `slug`, `name`, `object_name`, `description`, `selectable`, `icon`) VALUES
(1,	'generic',	'Generic User',	'',	'User umum',	0,	'fa fa-user'),
(2,	'staff',	'Staff',	'id_pegawai',	'Staff',	0,	'fa fa-user'),
(3,	'client',	'Client',	'id_client',	'Client',	0,	'fa fa-user');

-- 2022-03-28 03:26:06
