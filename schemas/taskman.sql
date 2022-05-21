-- Adminer 4.8.1 MySQL 8.0.28-0ubuntu0.21.10.3 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `t_files`;
CREATE TABLE `t_files` (
  `id` bigint unsigned NOT NULL,
  `id_task` bigint unsigned NOT NULL DEFAULT '0',
  `fileName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `filePath` varchar(512) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fileMime` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fileSize` int unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_task` (`id_task`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_project_members`;
CREATE TABLE `t_project_members` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_project` bigint unsigned NOT NULL DEFAULT '0',
  `id_account` bigint unsigned NOT NULL DEFAULT '0',
  `role` enum('owner','admin','contributor','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'guest',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_project` (`id_project`),
  KEY `id_account` (`id_account`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_projects`;
CREATE TABLE `t_projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` char(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_workspace` bigint unsigned NOT NULL,
  `id_account` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'project owner',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `id_workspace` (`id_workspace`),
  KEY `created_by` (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_section_tasks`;
CREATE TABLE `t_section_tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_project` bigint unsigned NOT NULL DEFAULT '0',
  `id_section` int unsigned NOT NULL DEFAULT '0',
  `id_task` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_task` (`id_task`),
  KEY `id_section_id_task` (`id_section`,`id_task`),
  KEY `id_project_id_section` (`id_project`,`id_section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_sections`;
CREATE TABLE `t_sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_project` bigint unsigned NOT NULL DEFAULT '0',
  `id_account` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'section creator',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`id_account`),
  KEY `id_project_id_account` (`id_project`,`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_tags`;
CREATE TABLE `t_tags` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_project` bigint unsigned NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_task_assignees`;
CREATE TABLE `t_task_assignees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_task` bigint unsigned NOT NULL,
  `id_account` bigint NOT NULL,
  `assigned_at` datetime DEFAULT NULL,
  `assigned_by` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_task` (`id_task`),
  KEY `id_account` (`id_account`),
  KEY `assigned_by` (`assigned_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_task_comments`;
CREATE TABLE `t_task_comments` (
  `id` bigint unsigned NOT NULL,
  `id_task` bigint unsigned NOT NULL DEFAULT '0',
  `comment` varchar(1024) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_task` (`id_task`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_task_tags`;
CREATE TABLE `t_task_tags` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_task` bigint unsigned NOT NULL,
  `id_tag` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_task` (`id_task`),
  KEY `id_tag` (`id_tag`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_tasks`;
CREATE TABLE `t_tasks` (
  `id` bigint unsigned NOT NULL,
  `pid` bigint unsigned NOT NULL DEFAULT '0',
  `id_project` bigint unsigned NOT NULL DEFAULT '0',
  `id_section` int unsigned NOT NULL DEFAULT '0',
  `id_account` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'task creator',
  `id_assignee` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'task assignment ',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0 = Todo, 2 = InProgress,  1 = Completed',
  `priority` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 = Normal, 0 = Low, 2 = High, 3 = Urgent',
  `due_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_project` (`id_project`),
  KEY `id_section` (`id_section`),
  KEY `due_date` (`due_date`),
  KEY `status` (`status`),
  KEY `priority` (`priority`),
  KEY `created_by` (`id_account`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_workspace_members`;
CREATE TABLE `t_workspace_members` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_workspace` bigint unsigned NOT NULL DEFAULT '0',
  `id_account` bigint unsigned NOT NULL DEFAULT '0',
  `role` enum('owner','admin','contributor','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'guest',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_account` (`id_account`),
  KEY `created_by` (`created_by`),
  KEY `id_workspace_id_account` (`id_workspace`,`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `t_workspaces`;
CREATE TABLE `t_workspaces` (
  `id` bigint unsigned NOT NULL,
  `slug` char(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_account` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'workspace owner',
  `id_client` bigint unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `id_client` (`id_client`),
  KEY `created_by` (`id_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2022-03-28 03:28:32
