-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2024 at 07:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tcneo`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_absences`
--

DROP TABLE IF EXISTS `tcneo_absences`;
CREATE TABLE IF NOT EXISTS `tcneo_absences`
(
  `id`                int(11)                                                NOT NULL AUTO_INCREMENT,
  `name`              varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `symbol`            char(1) CHARACTER SET utf8 COLLATE utf8_general_ci     NOT NULL DEFAULT 'A',
  `icon`              varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color`             varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL,
  `bgcolor`           varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL,
  `bgtrans`           tinyint(1)                                             NOT NULL DEFAULT 0,
  `factor`            float                                                  NOT NULL,
  `allowance`         float                                                  NOT NULL,
  `allowmonth`        float                                                  NOT NULL,
  `allowweek`         float                                                  NOT NULL,
  `counts_as`         int(11)                                                NOT NULL,
  `show_in_remainder` tinyint(1)                                             NOT NULL,
  `show_totals`       tinyint(1)                                             NOT NULL,
  `approval_required` tinyint(1)                                             NOT NULL,
  `counts_as_present` tinyint(1)                                             NOT NULL,
  `manager_only`      tinyint(1)                                             NOT NULL,
  `hide_in_profile`   tinyint(1)                                             NOT NULL,
  `confidential`      tinyint(1)                                             NOT NULL,
  `takeover`          tinyint(1)                                             NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 10
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_absences`
--

INSERT INTO `tcneo_absences` (`id`, `name`, `symbol`, `icon`, `color`, `bgcolor`, `bgtrans`, `factor`, `allowance`, `allowmonth`, `allowweek`, `counts_as`, `show_in_remainder`, `show_totals`, `approval_required`, `counts_as_present`, `manager_only`, `hide_in_profile`, `confidential`, `takeover`)
VALUES (1, 'Vacation', 'V', 'fas fa-umbrella-beach', 'FFEE00', 'FC3737', 0, 1, 20, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0),
       (2, 'Sick', 'S', 'fas fa-ambulance', '8C208C', 'FFCCFF', 0, 1, 24, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0),
       (3, 'Day Off', 'F', 'fas fa-coffee', '1A5C00', '00FF00', 0, 1, 12, 0, 0, 1, 1, 1, 0, 0, 0, 1, 0, 0),
       (4, 'Duty Trip', 'D', 'fas fa-plane-departure', 'A35D12', 'FFDB9E', 0, 1, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (5, 'Home Office', 'H', 'fas fa-home', '2717B5', 'D6F5FF', 0, 1, 0, 4, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0),
       (6, 'Not Present', 'N', 'fas fa-window-close', 'FF0000', 'C0C0C0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0),
       (7, 'Training', 'T', 'fas fa-book-reader', 'FFFFFF', '6495ED', 0, 1, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (8, 'Tentative Absence', 'A', 'fas fa-question-circle', '5E5E5E', 'EFEFEF', 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0),
       (9, 'Half day', 'H', 'fas fa-star-half-alt', 'A10000', 'FFAAAA', 0, 1, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_absence_group`
--

DROP TABLE IF EXISTS `tcneo_absence_group`;
CREATE TABLE IF NOT EXISTS `tcneo_absence_group`
(
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `absid`   int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `absgroup` (`absid`, `groupid`),
  KEY `absence_group_absid` (`absid`),
  KEY `absence_group_groupid` (`groupid`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 116
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_absence_group`
--

INSERT INTO `tcneo_absence_group` (`id`, `absid`, `groupid`)
VALUES (1, 3, 4),
       (2, 3, 2),
       (3, 3, 8),
       (4, 13, 8),
       (5, 3, 3),
       (115, 4, 4),
       (7, 13, 3),
       (59, 5, 4),
       (58, 5, 2),
       (57, 5, 3),
       (11, 6, 4),
       (12, 6, 2),
       (13, 2, 1),
       (14, 2, 2),
       (15, 13, 1),
       (16, 8, 4),
       (17, 8, 2),
       (18, 8, 8),
       (19, 8, 3),
       (20, 7, 4),
       (21, 7, 2),
       (22, 7, 8),
       (23, 7, 3),
       (24, 1, 4),
       (25, 1, 2),
       (26, 1, 8),
       (27, 1, 3),
       (28, 3, 1),
       (29, 2, 3),
       (30, 2, 4),
       (31, 13, 2),
       (32, 13, 4),
       (114, 4, 2),
       (113, 4, 3),
       (112, 4, 1),
       (56, 5, 1),
       (37, 6, 8),
       (38, 6, 3),
       (39, 6, 1),
       (40, 8, 1),
       (41, 7, 1),
       (42, 1, 1),
       (43, 9, 4),
       (44, 9, 2),
       (45, 9, 8),
       (46, 9, 3),
       (47, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_allowances`
--

DROP TABLE IF EXISTS `tcneo_allowances`;
CREATE TABLE IF NOT EXISTS `tcneo_allowances`
(
  `id`        int(11)                                                NOT NULL AUTO_INCREMENT,
  `username`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `absid`     int(11)                                                NOT NULL,
  `carryover` smallint(6) DEFAULT 0,
  `allowance` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `allowance` (`username`, `absid`),
  KEY `allowance_username` (`username`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 92
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_allowances`
--

INSERT INTO `tcneo_allowances` (`id`, `username`, `absid`, `carryover`, `allowance`)
VALUES (1, 'admin', 3, 0, 12),
       (2, 'admin', 4, 0, 20),
       (3, 'admin', 9, 0, 365),
       (4, 'admin', 5, 0, 365),
       (5, 'admin', 6, 0, 365),
       (6, 'admin', 2, 0, 24),
       (7, 'admin', 8, 0, 365),
       (8, 'admin', 7, 0, 10),
       (9, 'admin', 1, 0, 20),
       (10, 'ccarl', 3, 0, 12),
       (11, 'ccarl', 4, 0, 20),
       (12, 'ccarl', 9, 0, 365),
       (13, 'ccarl', 5, 0, 365),
       (14, 'ccarl', 6, 0, 365),
       (15, 'ccarl', 2, 0, 24),
       (16, 'ccarl', 8, 0, 365),
       (17, 'ccarl', 7, 0, 10),
       (18, 'ccarl', 1, 0, 20),
       (19, 'dduck', 3, 0, 12),
       (20, 'sgonzales', 3, 0, 12),
       (21, 'phead', 3, 0, 12),
       (22, 'blightyear', 3, 0, 12),
       (23, 'mmouse', 3, 0, 12),
       (25, 'sman', 3, 0, 12),
       (26, 'dduck', 4, 0, 20),
       (27, 'dduck', 9, 0, 365),
       (28, 'dduck', 5, 0, 365),
       (29, 'dduck', 6, 0, 365),
       (30, 'dduck', 2, 0, 24),
       (31, 'dduck', 8, 0, 365),
       (32, 'dduck', 7, 0, 10),
       (33, 'dduck', 1, 0, 20),
       (34, 'phead', 4, 0, 20),
       (35, 'phead', 9, 0, 365),
       (36, 'phead', 5, 0, 365),
       (37, 'phead', 6, 0, 365),
       (38, 'phead', 2, 0, 24),
       (39, 'phead', 8, 0, 365),
       (40, 'phead', 7, 0, 10),
       (41, 'phead', 1, 0, 20),
       (42, 'mmouse', 4, 0, 20),
       (43, 'mmouse', 9, 0, 365),
       (44, 'mmouse', 5, 0, 365),
       (45, 'mmouse', 6, 0, 365),
       (46, 'mmouse', 2, 0, 24),
       (47, 'mmouse', 8, 0, 365),
       (48, 'mmouse', 7, 0, 10),
       (49, 'mmouse', 1, 0, 20),
       (50, 'einstein', 3, 0, 12),
       (51, 'einstein', 4, 0, 20),
       (52, 'einstein', 9, 0, 365),
       (53, 'einstein', 5, 0, 365),
       (54, 'einstein', 6, 0, 365),
       (55, 'einstein', 2, 0, 24),
       (56, 'einstein', 8, 0, 365),
       (57, 'einstein', 7, 0, 10),
       (58, 'einstein', 1, 0, 20),
       (59, 'sgonzales', 4, 0, 20),
       (60, 'sgonzales', 9, 0, 365),
       (61, 'sgonzales', 5, 0, 365),
       (62, 'sgonzales', 6, 0, 365),
       (63, 'sgonzales', 2, 0, 24),
       (64, 'sgonzales', 8, 0, 365),
       (65, 'sgonzales', 7, 0, 10),
       (66, 'sgonzales', 1, 0, 20),
       (67, 'sman', 4, 0, 20),
       (68, 'sman', 9, 0, 365),
       (69, 'sman', 5, 0, 365),
       (70, 'sman', 6, 0, 365),
       (71, 'sman', 2, 0, 24),
       (72, 'sman', 8, 0, 365),
       (73, 'sman', 7, 0, 10),
       (74, 'sman', 1, 0, 20),
       (75, 'blightyear', 4, 0, 20),
       (76, 'blightyear', 9, 0, 365),
       (77, 'blightyear', 5, 0, 365),
       (78, 'blightyear', 6, 0, 365),
       (79, 'blightyear', 2, 0, 24),
       (80, 'blightyear', 8, 0, 365),
       (81, 'blightyear', 7, 0, 10),
       (82, 'blightyear', 1, 0, 20),
       (83, 'mimouse', 1, 0, 20),
       (84, 'mimouse', 2, 0, 24),
       (85, 'mimouse', 3, 0, 14),
       (86, 'mimouse', 4, 0, 20),
       (87, 'mimouse', 5, 0, 365),
       (88, 'mimouse', 6, 0, 365),
       (89, 'mimouse', 7, 0, 10),
       (90, 'mimouse', 8, 0, 365),
       (91, 'mimouse', 9, 0, 365);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_allowances`
--

DROP TABLE IF EXISTS `tcneo_archive_allowances`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_allowances`
(
  `id`        int(11)                                                NOT NULL AUTO_INCREMENT,
  `username`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `absid`     int(11)                                                NOT NULL,
  `carryover` smallint(6) DEFAULT 0,
  `allowance` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `allowance` (`username`, `absid`),
  KEY `allowance_username` (`username`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_daynotes`
--

DROP TABLE IF EXISTS `tcneo_archive_daynotes`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_daynotes`
(
  `id`           int(11)                                                NOT NULL AUTO_INCREMENT,
  `yyyymmdd`     varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci           DEFAULT NULL,
  `username`     varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'all',
  `region`       int(11)                                                NOT NULL DEFAULT 1,
  `daynote`      text CHARACTER SET utf8 COLLATE utf8_general_ci                 DEFAULT NULL,
  `color`        varchar(16)                                            NOT NULL DEFAULT 'default',
  `confidential` tinyint(1)                                             NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daynote_daynote` (`yyyymmdd`, `username`, `region`),
  KEY `daynote_username` (`username`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_templates`
--

DROP TABLE IF EXISTS `tcneo_archive_templates`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_templates`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `year`     varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci  DEFAULT NULL,
  `month`    char(2) CHARACTER SET utf8 COLLATE utf8_general_ci     DEFAULT NULL,
  `abs1`     int(11)                                                DEFAULT NULL,
  `abs2`     int(11)                                                DEFAULT NULL,
  `abs3`     int(11)                                                DEFAULT NULL,
  `abs4`     int(11)                                                DEFAULT NULL,
  `abs5`     int(11)                                                DEFAULT NULL,
  `abs6`     int(11)                                                DEFAULT NULL,
  `abs7`     int(11)                                                DEFAULT NULL,
  `abs8`     int(11)                                                DEFAULT NULL,
  `abs9`     int(11)                                                DEFAULT NULL,
  `abs10`    int(11)                                                DEFAULT NULL,
  `abs11`    int(11)                                                DEFAULT NULL,
  `abs12`    int(11)                                                DEFAULT NULL,
  `abs13`    int(11)                                                DEFAULT NULL,
  `abs14`    int(11)                                                DEFAULT NULL,
  `abs15`    int(11)                                                DEFAULT NULL,
  `abs16`    int(11)                                                DEFAULT NULL,
  `abs17`    int(11)                                                DEFAULT NULL,
  `abs18`    int(11)                                                DEFAULT NULL,
  `abs19`    int(11)                                                DEFAULT NULL,
  `abs20`    int(11)                                                DEFAULT NULL,
  `abs21`    int(11)                                                DEFAULT NULL,
  `abs22`    int(11)                                                DEFAULT NULL,
  `abs23`    int(11)                                                DEFAULT NULL,
  `abs24`    int(11)                                                DEFAULT NULL,
  `abs25`    int(11)                                                DEFAULT NULL,
  `abs26`    int(11)                                                DEFAULT NULL,
  `abs27`    int(11)                                                DEFAULT NULL,
  `abs28`    int(11)                                                DEFAULT NULL,
  `abs29`    int(11)                                                DEFAULT NULL,
  `abs30`    int(11)                                                DEFAULT NULL,
  `abs31`    int(11)                                                DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template` (`username`, `year`, `month`),
  KEY `template_username` (`username`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_users`
--

DROP TABLE IF EXISTS `tcneo_archive_users`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_users`
(
  `username`       varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password`       varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci          DEFAULT NULL,
  `firstname`      varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci          DEFAULT NULL,
  `lastname`       varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci          DEFAULT NULL,
  `email`          varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci         DEFAULT NULL,
  `order_key`      varchar(80)                                            NOT NULL DEFAULT '0',
  `role`           int(11)                                                         DEFAULT 2,
  `locked`         tinyint(4)                                                      DEFAULT 0,
  `hidden`         tinyint(4)                                                      DEFAULT 0,
  `onhold`         tinyint(4)                                                      DEFAULT 0,
  `verify`         tinyint(4)                                                      DEFAULT 0,
  `bad_logins`     tinyint(4)                                                      DEFAULT 0,
  `grace_start`    datetime                                                        DEFAULT NULL,
  `last_pw_change` datetime                                                        DEFAULT NULL,
  `last_login`     datetime                                                        DEFAULT NULL,
  `created`        datetime                                                        DEFAULT NULL,
  PRIMARY KEY (`username`),
  KEY `user_firstname` (`firstname`),
  KEY `user_lastname` (`lastname`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_attachment`
--

DROP TABLE IF EXISTS `tcneo_archive_user_attachment`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_user_attachment`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `fileid`   int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_attachment` (`username`, `fileid`),
  KEY `user_attachment_username` (`username`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_group`
--

DROP TABLE IF EXISTS `tcneo_archive_user_group`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_user_group`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `groupid`  int(11)                                                DEFAULT NULL,
  `type`     tinytext CHARACTER SET utf8 COLLATE utf8_general_ci    DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usergroup` (`username`, `groupid`),
  KEY `user_group_username` (`username`),
  KEY `user_group_groupid` (`groupid`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_message`
--

DROP TABLE IF EXISTS `tcneo_archive_user_message`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_user_message`
(
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `msgid`    int(11)                                                DEFAULT NULL,
  `popup`    tinyint(4) NOT NULL                                    DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_message_username` (`username`),
  KEY `user_message_msgid` (`msgid`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_option`
--

DROP TABLE IF EXISTS `tcneo_archive_user_option`;
CREATE TABLE IF NOT EXISTS `tcneo_archive_user_option`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `option`   varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `value`    text CHARACTER SET utf8 COLLATE utf8_general_ci        DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `useroption` (`username`, `option`),
  KEY `user_option_username` (`username`),
  KEY `user_option_option` (`option`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_attachments`
--

DROP TABLE IF EXISTS `tcneo_attachments`;
CREATE TABLE IF NOT EXISTS `tcneo_attachments`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `uploader` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attachment` (`filename`),
  KEY `attachment_filename` (`filename`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 13
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_attachments`
--

INSERT INTO `tcneo_attachments` (`id`, `filename`, `uploader`)
VALUES (1, 'logo-16.png', 'admin'),
       (2, 'logo-22.png', 'admin'),
       (3, 'logo-32.png', 'admin'),
       (4, 'logo-48.png', 'admin'),
       (5, 'logo-64.png', 'admin'),
       (6, 'logo-72.png', 'admin'),
       (7, 'logo-96.png', 'admin'),
       (8, 'logo-128.png', 'admin'),
       (9, 'logo-200.png', 'admin'),
       (10, 'logo-80.png', 'admin'),
       (11, 'logo-256.png', 'admin'),
       (12, 'logo-512.png', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_config`
--

DROP TABLE IF EXISTS `tcneo_config`;
CREATE TABLE IF NOT EXISTS `tcneo_config`
(
  `id`    int(11)                                                NOT NULL AUTO_INCREMENT,
  `name`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci        NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 211
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_config`
--

INSERT INTO `tcneo_config` (`id`, `name`, `value`)
VALUES (1, 'forceTfa', '0'),
       (2, 'defaultLanguage', 'english'),
       (3, 'permissionScheme', 'Default'),
       (4, 'appTitle', 'TeamCal Neo'),
       (5, 'footerCopyright', 'Lewe.com'),
       (7, 'logLanguage', 'english'),
       (8, 'showAlerts', 'all'),
       (9, 'activateMessages', '1'),
       (10, 'homepage', 'home'),
       (11, 'welcomeIcon', 'None'),
       (12, 'welcomeText',
        '<h3><img alt=\"\" src=\"upload/files/logo-128.png\" style=\"float:left; height:128px; margin-bottom:24px; margin-right:24px; width:128px\" />Welcome to TeamCal Neo</h3>\r\n\r\n<p>TeamCal Neo is a day-based online calendar that allows to easily manage your team&#39;s absences and displays them in an intuitive interface. You can manage absence types, holidays, regional calendars and much more.</p>\r\n\r\n<p>TeamCal Neo requires a license for a small yearly fee.</p>\r\n\r\n<p>Its little brother &quot;<a href=\"http://tcneobasic.lewe.com\">TeamCal Neo Basic</a>&quot; , however, remains free and offers the core features of the calendar.</p>\r\n\r\n<h3>Links:</h3>\r\n\r\n<ul>\r\n	<li><a href=\"http://www.lewe.com/teamcal-neo/\" target=\"_blank\">Product Page</a></li>\r\n	<li><a href=\"https://lewe.gitbook.io/teamcal-neo/\" target=\"_blank\">Documentation</a></li>\r\n</ul>\r\n\r\n<h3>Login</h3>\r\n\r\n<p>Select Login from the User menu to login and use the following accounts to give this demo a test drive:</p>\r\n\r\n<p><strong>Admin account:</strong></p>\r\n\r\n<p>admin/Qwer!1234</p>\r\n\r\n<p><strong>User accounts:</strong></p>\r\n\r\n<p>ccarl/Qwer!1234<br />\r\nblightyear/Qwer!1234<br />\r\ndduck/Qwer!1234<br />\r\neinstein/Qwer!1234<br />\r\nsgonzalez/Qwer!1234<br />\r\nphead/Qwer!1234<br />\r\nmmouse/Qwer!1234<br />\r\nmimouse/Qwer!1234<br />\r\nsman/Qwer!1234</p>\r\n\r\n<p><strong>LDAP test account (when activating the <a href=\"https://lewe.gitbook.io/teamcal-neo/administration/ldap-authentication\">LDAP test configuration</a>):</strong></p>\r\n\r\n<p>einstein/password</p>\r\n'),
       (13, 'welcomeTitle', 'Welcome To TeamCal Neo'),
       (14, 'userCustom1', 'Custom Field 1'),
       (15, 'userCustom2', 'Custom Field 2'),
       (16, 'userCustom3', 'Custom Field 3'),
       (17, 'userCustom4', 'Custom Field 4'),
       (18, 'userCustom5', 'Custom Field 5'),
       (19, 'emailNotifications', '0'),
       (20, 'emailNoPastNotifications', '0'),
       (21, 'mailFrom', 'TeamCal Neo'),
       (22, 'mailReply', 'webmaster@mysite.com'),
       (23, 'mailSMTP', '0'),
       (24, 'mailSMTPhost', ''),
       (25, 'mailSMTPport', '0'),
       (26, 'mailSMTPusername', ''),
       (27, 'mailSMTPpassword', ''),
       (28, 'mailSMTPSSL', '0'),
       (29, 'pwdStrength', 'medium'),
       (30, 'badLogins', '5'),
       (31, 'gracePeriod', '300'),
       (32, 'cookieLifetime', '80000'),
       (33, 'allowRegistration', '1'),
       (34, 'emailConfirmation', '1'),
       (35, 'adminApproval', '1'),
       (36, 'jQueryCDN', '0'),
       (37, 'jqtheme', 'base'),
       (38, 'debugHide', '0'),
       (39, 'timeZone', 'Europe/Berlin'),
       (40, 'googleAnalytics', '0'),
       (41, 'googleAnalyticsID', ''),
       (42, 'underMaintenance', '0'),
       (43, 'showUserIcons', '0'),
       (44, 'showAvatars', '1'),
       (45, 'avatarWidth', '0'),
       (46, 'avatarHeight', '0'),
       (47, 'avatarMaxSize', '0'),
       (48, 'declBeforeStartdate', ''),
       (49, 'declBeforeEnddate', ''),
       (50, 'faCDN', '0'),
       (51, 'logperiod', 'curr_all'),
       (52, 'logfrom', '2022-01-01 00:00:00.000000'),
       (53, 'logto', '2024-09-06 23:59:59.999999'),
       (54, 'logConfig', '1'),
       (55, 'logfilterConfig', '1'),
       (56, 'logDatabase', '1'),
       (57, 'logfilterDatabase', '1'),
       (58, 'logGroup', '1'),
       (59, 'logfilterGroup', '1'),
       (60, 'logLogin', '1'),
       (61, 'logfilterLogin', '1'),
       (62, 'logLog', '1'),
       (63, 'logfilterLog', '1'),
       (64, 'logNews', '1'),
       (65, 'logfilterNews', '1'),
       (66, 'logPermission', '1'),
       (67, 'logfilterPermission', '1'),
       (68, 'logRole', '1'),
       (69, 'logfilterRole', '1'),
       (70, 'logUser', '1'),
       (71, 'logfilterUser', '1'),
       (72, 'declAbsence', '0'),
       (73, 'declBefore', '0'),
       (74, 'declPeriod1', '0'),
       (75, 'declPeriod1Start', ''),
       (76, 'declPeriod1End', ''),
       (77, 'declPeriod2', '0'),
       (78, 'declPeriod2Start', ''),
       (79, 'declPeriod2End', ''),
       (80, 'declPeriod3', '0'),
       (81, 'declPeriod3Start', ''),
       (82, 'declPeriod3End', ''),
       (83, 'declScope', '2'),
       (84, 'showMonths', '1'),
       (85, 'todayBorderColor', 'FFB300'),
       (86, 'todayBorderSize', '2'),
       (87, 'pastDayColor', 'FAFAFA'),
       (88, 'showWeekNumbers', '1'),
       (89, 'repeatHeaderCount', '0'),
       (90, 'usersPerPage', '0'),
       (91, 'userSearch', '0'),
       (92, 'hideDaynotes', '0'),
       (93, 'hideManagers', '0'),
       (94, 'hideManagerOnlyAbsences', '0'),
       (95, 'showUserRegion', '1'),
       (96, 'markConfidential', '0'),
       (97, 'firstDayOfWeek', '1'),
       (98, 'satBusi', '0'),
       (99, 'sunBusi', '0'),
       (100, 'defregion', 'Default'),
       (101, 'defgroupfilter', 'all'),
       (102, 'includeRemainder', '0'),
       (103, 'includeRemainderTotal', '0'),
       (104, 'includeTotals', '0'),
       (105, 'showRemainder', '0'),
       (106, 'includeSummary', '0'),
       (107, 'showSummary', '1'),
       (108, 'supportMobile', '0'),
       (109, 'logfilterCalendar', '1'),
       (110, 'logfilterCalendar Options', '0'),
       (111, 'logCalendarOptions', '1'),
       (112, 'logfilterCalendarOptions', '1'),
       (113, 'declThreshold', '40'),
       (114, 'declBase', 'group'),
       (115, 'declBeforeOption', 'date'),
       (116, 'declBeforeDate', '2024-01-01'),
       (117, 'dbURL', '#'),
       (118, 'logcolorConfig', 'danger'),
       (119, 'logcolorDatabase', 'warning'),
       (120, 'logcolorGroup', 'primary'),
       (121, 'logcolorLogin', 'success'),
       (122, 'logcolorLog', 'default'),
       (123, 'logMessage', '0'),
       (124, 'logfilterMessage', '0'),
       (125, 'logcolorMessage', 'primary'),
       (126, 'logcolorPermission', 'warning'),
       (127, 'logRegistration', '1'),
       (128, 'logfilterRegistration', '1'),
       (129, 'logcolorRegistration', 'success'),
       (130, 'logcolorRole', 'primary'),
       (131, 'logUpload', '1'),
       (132, 'logfilterUpload', '1'),
       (133, 'logcolorUpload', 'primary'),
       (134, 'logcolorUser', 'primary'),
       (135, 'logcolorCalendarOptions', 'danger'),
       (136, 'appURL', '#'),
       (137, 'appDescription', 'Lewe TeamCal Neo. The Online Team Calendar!'),
       (138, 'declBeforePeriod', 'nowForever'),
       (139, 'showRoleIcons', '1'),
       (140, 'logImport', '1'),
       (141, 'logcolorImport', 'warning'),
       (142, 'logfilterImport', '1'),
       (143, 'appKeywords', 'Lewe TeamCal Neo'),
       (144, 'userManual', 'https%3A%2F%2Flewe.gitbook.io%2Fteamcal-neo%2F'),
       (145, 'footerCopyrightUrl', 'http://www.lewe.com'),
       (146, 'footerSocialLinks', 'https://www.linkedin.com/in/george-lewe-a9ab6411b;https://www.xing.com/profile/George_Lewe'),
       (147, 'footerViewport', '0'),
       (148, 'cookieConsent', '1'),
       (149, 'noIndex', '1'),
       (150, 'showTooltipCount', '0'),
       (151, 'symbolAsIcon', '0'),
       (152, 'showRegionButton', '1'),
       (153, 'statsDefaultColorAbsences', '#ff0000'),
       (154, 'statsDefaultColorPresences', '#00d000'),
       (155, 'statsDefaultColorAbsencetype', '#0000ff'),
       (156, 'statsDefaultColorRemainder', '#ffa500'),
       (157, 'logDaynote', '0'),
       (158, 'logfilterDaynote', '0'),
       (159, 'logcolorDaynote', 'default'),
       (160, 'logRegion', '1'),
       (161, 'logfilterRegion', '1'),
       (162, 'logcolorRegion', 'success'),
       (163, 'defaultHomepage', 'home'),
       (164, 'trustedRoles', '1'),
       (165, 'noCaching', '1'),
       (166, 'currYearRoles', '3,2'),
       (167, 'versionCompare', '1'),
       (168, 'currentYearOnly', '0'),
       (169, 'takeover', '0'),
       (170, 'notificationsAllGroups', '0'),
       (171, 'showTwoMonths', '0'),
       (172, 'monitorAbsence', '0'),
       (173, 'pageHelp', '1'),
       (174, 'regionalHolidays', '0'),
       (175, 'regionalHolidaysColor', 'CC0000'),
       (176, 'calendarFontSize', '100'),
       (177, 'managerOnlyIncludesAdministrator', '0'),
       (178, 'declAbsencePeriod', 'nowForever'),
       (179, 'mailSMTPAnonymous', '0'),
       (180, 'gdprPolicyPage', '1'),
       (181, 'gdprOrganization', 'ACME Inc.'),
       (182, 'gdprController', 'ACME Inc.\r\n123 Street\r\nHometown, XY 4567\r\nGermany\r\nEmail: info@acme.com'),
       (183, 'gdprOfficer', 'John Doe\r\nPhone: +49 555 12345\r\nEmail: john.doe@acme.com'),
       (184, 'gdprFacebook', '0'),
       (185, 'gdprGoogleAnalytics', '0'),
       (186, 'gdprGooglePlus', '0'),
       (187, 'gdprInstagram', '0'),
       (188, 'gdprLinkedin', '1'),
       (189, 'gdprPaypal', '0'),
       (190, 'gdprPinterest', '0'),
       (191, 'gdprSlideshare', '0'),
       (192, 'gdprTumblr', '0'),
       (193, 'gdprTwitter', '0'),
       (194, 'gdprXing', '1'),
       (195, 'gdprYoutube', '0'),
       (196, 'licKey', 'TCN-5e5d657266352'),
       (197, 'licExpiryWarning', '30'),
       (198, 'cookieConsentCDN', '0'),
       (199, 'logCalendar', '1'),
       (200, 'logcolorCalendar', 'default'),
       (201, 'logMonth', '0'),
       (202, 'logfilterMonth', '0'),
       (203, 'logcolorMonth', 'default'),
       (204, 'font', 'default'),
       (205, 'sortByOrderKey', '1'),
       (206, 'alertAutocloseDelay', '3000'),
       (207, 'alertAutocloseDanger', '0'),
       (208, 'alertAutocloseSuccess', '1'),
       (209, 'alertAutocloseWarning', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_daynotes`
--

DROP TABLE IF EXISTS `tcneo_daynotes`;
CREATE TABLE IF NOT EXISTS `tcneo_daynotes`
(
  `id`           int(11)                                                NOT NULL AUTO_INCREMENT,
  `yyyymmdd`     varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci           DEFAULT NULL,
  `username`     varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'all',
  `region`       int(11)                                                NOT NULL DEFAULT 1,
  `daynote`      text CHARACTER SET utf8 COLLATE utf8_general_ci                 DEFAULT NULL,
  `color`        varchar(16)                                            NOT NULL DEFAULT 'default',
  `confidential` tinyint(1)                                             NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daynote_daynote` (`yyyymmdd`, `username`, `region`),
  KEY `daynote_username` (`username`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_groups`
--

DROP TABLE IF EXISTS `tcneo_groups`;
CREATE TABLE IF NOT EXISTS `tcneo_groups`
(
  `id`           int(11)                                                 NOT NULL AUTO_INCREMENT,
  `name`         varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
  `description`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `minpresent`   smallint(6)                                             NOT NULL DEFAULT 0,
  `maxabsent`    smallint(6)                                             NOT NULL DEFAULT 9999,
  `minpresentwe` smallint(6)                                             NOT NULL DEFAULT 0,
  `maxabsentwe`  smallint(6)                                             NOT NULL DEFAULT 9999,
  PRIMARY KEY (`id`),
  KEY `group_name` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_groups`
--

INSERT INTO `tcneo_groups` (`id`, `name`, `description`, `minpresent`, `maxabsent`, `minpresentwe`, `maxabsentwe`)
VALUES (1, 'Disney', 'Disney Characters', 0, 9999, 0, 9999),
       (2, 'Marvel', 'Marvel Characters', 0, 9999, 0, 9999),
       (3, 'Looney', 'Looney Characters', 0, 9999, 0, 9999),
       (4, 'Pixar', 'Pixar Characters', 0, 9999, 0, 9999);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_holidays`
--

DROP TABLE IF EXISTS `tcneo_holidays`;
CREATE TABLE IF NOT EXISTS `tcneo_holidays`
(
  `id`               int(11)                                                 NOT NULL AUTO_INCREMENT,
  `name`             varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
  `description`      varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `color`            varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci   NOT NULL DEFAULT '000000',
  `bgcolor`          varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci   NOT NULL DEFAULT 'ffffff',
  `businessday`      tinyint(1)                                              NOT NULL DEFAULT 0,
  `noabsence`        tinyint(1)                                              NOT NULL DEFAULT 0,
  `keepweekendcolor` tinyint(1)                                              NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `holiday_name` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_holidays`
--

INSERT INTO `tcneo_holidays` (`id`, `name`, `description`, `color`, `bgcolor`, `businessday`, `noabsence`, `keepweekendcolor`)
VALUES (1, 'Business Day', 'Regular business day', '000000', 'ffffff', 1, 0, 0),
       (2, 'Saturday', 'Regular weekend day (Saturday)', '000000', 'fcfc9a', 1, 0, 0),
       (3, 'Sunday', 'Regular weekend day (Sunday)', '000000', 'fcfc9a', 0, 0, 0),
       (4, 'Public Holiday', 'Public bank holidays', '000000', 'EBAAAA', 0, 0, 0),
       (5, 'School Holiday', 'School holidays', '000000', 'BFFFDF', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_log`
--

DROP TABLE IF EXISTS `tcneo_log`;
CREATE TABLE IF NOT EXISTS `tcneo_log`
(
  `id`        int(11)  NOT NULL AUTO_INCREMENT,
  `type`      varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `timestamp` datetime NOT NULL                                      DEFAULT '1900-01-01 00:00:00',
  `ip`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user`      varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `event`     text CHARACTER SET utf8 COLLATE utf8_general_ci        DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 41
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_log`
--

INSERT INTO `tcneo_log` (`id`, `type`, `timestamp`, `ip`, `user`, `event`)
VALUES (1, 'logDatabase', '2024-09-06 17:29:20', '127.0.0.1', 'admin', 'Database reset'),
       (2, 'logLogin', '2024-09-07 15:01:58', '127.0.0.1', 'admin', 'Login successful'),
       (3, 'logConfig', '2024-09-07 16:57:04', '127.0.0.1', 'admin', 'System configuration changed'),
       (4, 'logConfig', '2024-09-07 16:57:22', '127.0.0.1', 'admin', 'System configuration changed'),
       (5, 'logConfig', '2024-09-07 17:04:10', '127.0.0.1', 'admin', 'System configuration changed'),
       (6, 'logConfig', '2024-09-07 17:04:36', '127.0.0.1', 'admin', 'System configuration changed'),
       (7, 'logConfig', '2024-09-07 17:05:00', '127.0.0.1', 'admin', 'System configuration changed'),
       (8, 'logConfig', '2024-09-07 17:05:36', '127.0.0.1', 'admin', 'System configuration changed'),
       (9, 'logConfig', '2024-09-07 17:05:47', '127.0.0.1', 'admin', 'System configuration changed'),
       (10, 'logConfig', '2024-09-07 17:07:11', '127.0.0.1', 'admin', 'System configuration changed'),
       (11, 'logUser', '2024-09-07 18:05:30', '127.0.0.1', 'admin', 'User added: einstein'),
       (12, 'logUser', '2024-09-07 18:18:37', '127.0.0.1', 'admin', 'User profile updated: einstein'),
       (13, 'logUser', '2024-09-07 18:18:46', '127.0.0.1', 'admin', 'User profile updated: einstein'),
       (14, 'logLogin', '2024-09-07 18:19:34', '127.0.0.1', 'admin', 'Logout'),
       (15, 'logLogin', '2024-09-07 18:19:45', '127.0.0.1', 'einstein', 'Login successful'),
       (16, 'logLogin', '2024-09-07 18:59:18', '127.0.0.1', 'einstein', 'Logout'),
       (17, 'logLogin', '2024-09-07 18:59:26', '127.0.0.1', 'admin', 'Login successful'),
       (18, 'logConfig', '2024-09-07 19:02:15', '127.0.0.1', 'admin', 'System configuration changed'),
       (19, 'logUser', '2024-09-07 19:03:14', '127.0.0.1', 'admin', 'User password reset: dduck'),
       (20, 'logUser', '2024-09-07 19:03:16', '127.0.0.1', 'admin', 'User password reset: einstein'),
       (21, 'logUser', '2024-09-07 19:03:18', '127.0.0.1', 'admin', 'User password reset: sgonzales'),
       (22, 'logUser', '2024-09-07 19:03:20', '127.0.0.1', 'admin', 'User password reset: phead'),
       (23, 'logUser', '2024-09-07 19:03:22', '127.0.0.1', 'admin', 'User password reset: mmouse'),
       (24, 'logUser', '2024-09-07 19:03:24', '127.0.0.1', 'admin', 'User password reset: sman'),
       (25, 'logUser', '2024-09-07 19:03:26', '127.0.0.1', 'admin', 'User password reset: blightyear'),
       (26, 'logUser', '2024-09-07 19:03:28', '127.0.0.1', 'admin', 'User password reset: ccarl'),
       (27, 'logUser', '2024-09-07 19:04:21', '127.0.0.1', 'admin', 'User password reset: ccarl'),
       (28, 'logUser', '2024-09-07 19:08:59', '127.0.0.1', 'admin', 'User profile updated: ccarl'),
       (29, 'logUser', '2024-09-07 19:09:35', '127.0.0.1', 'admin', 'User profile updated: dduck'),
       (30, 'logUser', '2024-09-07 19:09:52', '127.0.0.1', 'admin', 'User profile updated: sgonzales'),
       (31, 'logUser', '2024-09-07 19:10:09', '127.0.0.1', 'admin', 'User profile updated: phead'),
       (32, 'logUser', '2024-09-07 19:10:24', '127.0.0.1', 'admin', 'User profile updated: mmouse'),
       (33, 'logUser', '2024-09-07 19:10:41', '127.0.0.1', 'admin', 'User profile updated: sman'),
       (34, 'logUser', '2024-09-07 19:10:55', '127.0.0.1', 'admin', 'User profile updated: blightyear'),
       (35, 'logUser', '2024-09-07 19:11:07', '127.0.0.1', 'admin', 'User profile updated: ccarl'),
       (36, 'logLogin', '2024-09-07 19:11:31', '127.0.0.1', 'admin', 'Logout'),
       (37, 'logLogin', '2024-09-07 19:11:40', '127.0.0.1', 'dduck', 'Login successful'),
       (38, 'logLogin', '2024-09-07 19:11:43', '127.0.0.1', 'dduck', 'Logout'),
       (39, 'logLogin', '2024-09-07 19:12:40', '127.0.0.1', 'admin', 'Login successful'),
       (40, 'logUser', '2024-09-07 19:12:50', '127.0.0.1', 'admin', 'User profile updated: admin');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_messages`
--

DROP TABLE IF EXISTS `tcneo_messages`;
CREATE TABLE IF NOT EXISTS `tcneo_messages`
(
  `id`        int(11)                                               NOT NULL AUTO_INCREMENT,
  `timestamp` datetime                                              NOT NULL DEFAULT '1900-01-01 00:00:00',
  `text`      text CHARACTER SET utf8 COLLATE utf8_general_ci       NOT NULL,
  `type`      varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_type` (`type`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_months`
--

DROP TABLE IF EXISTS `tcneo_months`;
CREATE TABLE IF NOT EXISTS `tcneo_months`
(
  `id`     int(11) NOT NULL AUTO_INCREMENT,
  `year`   varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `month`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci    DEFAULT NULL,
  `region` int(11)                                               DEFAULT 1,
  `wday1`  tinyint(1)                                            DEFAULT NULL,
  `wday2`  tinyint(1)                                            DEFAULT NULL,
  `wday3`  tinyint(1)                                            DEFAULT NULL,
  `wday4`  tinyint(1)                                            DEFAULT NULL,
  `wday5`  tinyint(1)                                            DEFAULT NULL,
  `wday6`  tinyint(1)                                            DEFAULT NULL,
  `wday7`  tinyint(1)                                            DEFAULT NULL,
  `wday8`  tinyint(1)                                            DEFAULT NULL,
  `wday9`  tinyint(1)                                            DEFAULT NULL,
  `wday10` tinyint(1)                                            DEFAULT NULL,
  `wday11` tinyint(1)                                            DEFAULT NULL,
  `wday12` tinyint(1)                                            DEFAULT NULL,
  `wday13` tinyint(1)                                            DEFAULT NULL,
  `wday14` tinyint(1)                                            DEFAULT NULL,
  `wday15` tinyint(1)                                            DEFAULT NULL,
  `wday16` tinyint(1)                                            DEFAULT NULL,
  `wday17` tinyint(1)                                            DEFAULT NULL,
  `wday18` tinyint(1)                                            DEFAULT NULL,
  `wday19` tinyint(1)                                            DEFAULT NULL,
  `wday20` tinyint(1)                                            DEFAULT NULL,
  `wday21` tinyint(1)                                            DEFAULT NULL,
  `wday22` tinyint(1)                                            DEFAULT NULL,
  `wday23` tinyint(1)                                            DEFAULT NULL,
  `wday24` tinyint(1)                                            DEFAULT NULL,
  `wday25` tinyint(1)                                            DEFAULT NULL,
  `wday26` tinyint(1)                                            DEFAULT NULL,
  `wday27` tinyint(1)                                            DEFAULT NULL,
  `wday28` tinyint(1)                                            DEFAULT NULL,
  `wday29` tinyint(1)                                            DEFAULT NULL,
  `wday30` tinyint(1)                                            DEFAULT NULL,
  `wday31` tinyint(1)                                            DEFAULT NULL,
  `week1`  smallint(6)                                           DEFAULT NULL,
  `week2`  smallint(6)                                           DEFAULT NULL,
  `week3`  smallint(6)                                           DEFAULT NULL,
  `week4`  smallint(6)                                           DEFAULT NULL,
  `week5`  smallint(6)                                           DEFAULT NULL,
  `week6`  smallint(6)                                           DEFAULT NULL,
  `week7`  smallint(6)                                           DEFAULT NULL,
  `week8`  smallint(6)                                           DEFAULT NULL,
  `week9`  smallint(6)                                           DEFAULT NULL,
  `week10` smallint(6)                                           DEFAULT NULL,
  `week11` smallint(6)                                           DEFAULT NULL,
  `week12` smallint(6)                                           DEFAULT NULL,
  `week13` smallint(6)                                           DEFAULT NULL,
  `week14` smallint(6)                                           DEFAULT NULL,
  `week15` smallint(6)                                           DEFAULT NULL,
  `week16` smallint(6)                                           DEFAULT NULL,
  `week17` smallint(6)                                           DEFAULT NULL,
  `week18` smallint(6)                                           DEFAULT NULL,
  `week19` smallint(6)                                           DEFAULT NULL,
  `week20` smallint(6)                                           DEFAULT NULL,
  `week21` smallint(6)                                           DEFAULT NULL,
  `week22` smallint(6)                                           DEFAULT NULL,
  `week23` smallint(6)                                           DEFAULT NULL,
  `week24` smallint(6)                                           DEFAULT NULL,
  `week25` smallint(6)                                           DEFAULT NULL,
  `week26` smallint(6)                                           DEFAULT NULL,
  `week27` smallint(6)                                           DEFAULT NULL,
  `week28` smallint(6)                                           DEFAULT NULL,
  `week29` smallint(6)                                           DEFAULT NULL,
  `week30` smallint(6)                                           DEFAULT NULL,
  `week31` smallint(6)                                           DEFAULT NULL,
  `hol1`   int(11)                                               DEFAULT NULL,
  `hol2`   int(11)                                               DEFAULT NULL,
  `hol3`   int(11)                                               DEFAULT NULL,
  `hol4`   int(11)                                               DEFAULT NULL,
  `hol5`   int(11)                                               DEFAULT NULL,
  `hol6`   int(11)                                               DEFAULT NULL,
  `hol7`   int(11)                                               DEFAULT NULL,
  `hol8`   int(11)                                               DEFAULT NULL,
  `hol9`   int(11)                                               DEFAULT NULL,
  `hol10`  int(11)                                               DEFAULT NULL,
  `hol11`  int(11)                                               DEFAULT NULL,
  `hol12`  int(11)                                               DEFAULT NULL,
  `hol13`  int(11)                                               DEFAULT NULL,
  `hol14`  int(11)                                               DEFAULT NULL,
  `hol15`  int(11)                                               DEFAULT NULL,
  `hol16`  int(11)                                               DEFAULT NULL,
  `hol17`  int(11)                                               DEFAULT NULL,
  `hol18`  int(11)                                               DEFAULT NULL,
  `hol19`  int(11)                                               DEFAULT NULL,
  `hol20`  int(11)                                               DEFAULT NULL,
  `hol21`  int(11)                                               DEFAULT NULL,
  `hol22`  int(11)                                               DEFAULT NULL,
  `hol23`  int(11)                                               DEFAULT NULL,
  `hol24`  int(11)                                               DEFAULT NULL,
  `hol25`  int(11)                                               DEFAULT NULL,
  `hol26`  int(11)                                               DEFAULT NULL,
  `hol27`  int(11)                                               DEFAULT NULL,
  `hol28`  int(11)                                               DEFAULT NULL,
  `hol29`  int(11)                                               DEFAULT NULL,
  `hol30`  int(11)                                               DEFAULT NULL,
  `hol31`  int(11)                                               DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `month_month` (`year`, `month`, `region`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 30
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_months`
--

INSERT INTO `tcneo_months` (`id`, `year`, `month`, `region`, `wday1`, `wday2`, `wday3`, `wday4`, `wday5`, `wday6`, `wday7`, `wday8`, `wday9`, `wday10`, `wday11`, `wday12`, `wday13`, `wday14`, `wday15`, `wday16`, `wday17`, `wday18`, `wday19`, `wday20`, `wday21`, `wday22`, `wday23`, `wday24`, `wday25`, `wday26`, `wday27`, `wday28`, `wday29`, `wday30`, `wday31`, `week1`, `week2`, `week3`, `week4`, `week5`, `week6`, `week7`, `week8`, `week9`, `week10`, `week11`, `week12`,
                            `week13`, `week14`, `week15`, `week16`, `week17`, `week18`, `week19`, `week20`, `week21`, `week22`, `week23`, `week24`, `week25`, `week26`, `week27`, `week28`, `week29`, `week30`, `week31`, `hol1`, `hol2`, `hol3`, `hol4`, `hol5`, `hol6`, `hol7`, `hol8`, `hol9`, `hol10`, `hol11`, `hol12`, `hol13`, `hol14`, `hol15`, `hol16`, `hol17`, `hol18`, `hol19`, `hol20`, `hol21`, `hol22`, `hol23`, `hol24`, `hol25`, `hol26`, `hol27`, `hol28`, `hol29`, `hol30`,
                            `hol31`)
VALUES (24, '2024', '10', 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 40, 40, 40, 40, 40, 40, 41, 41, 41, 41, 41, 41, 41, 42, 42, 42, 42, 42, 42, 42, 43, 43, 43, 43, 43, 43, 43, 44, 44, 44, 44, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (25, '2024', '11', 1, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 0, 44, 44, 44, 45, 45, 45, 45, 45, 45, 45, 46, 46, 46, 46, 46, 46, 46, 47, 47, 47, 47, 47, 47, 47, 48, 48, 48, 48, 48, 48, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (26, '2024', '12', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 48, 49, 49, 49, 49, 49, 49, 49, 50, 50, 50, 50, 50, 50, 50, 51, 51, 51, 51, 51, 51, 51, 52, 52, 52, 52, 52, 52, 52, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_permissions`
--

DROP TABLE IF EXISTS `tcneo_permissions`;
CREATE TABLE IF NOT EXISTS `tcneo_permissions`
(
  `id`         int(11)                                                NOT NULL AUTO_INCREMENT,
  `scheme`     varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `permission` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `role`       int(11)                                                NOT NULL DEFAULT 1,
  `allowed`    tinyint(1)                                             NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_permission` (`scheme`, `permission`, `role`),
  KEY `permission_scheme` (`scheme`),
  KEY `permission_name` (`permission`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 119
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_permissions`
--

INSERT INTO `tcneo_permissions` (`id`, `scheme`, `permission`, `role`, `allowed`)
VALUES (1, 'Default', 'calendareditall', 1, 1),
       (2, 'Default', 'calendareditgroup', 2, 0),
       (3, 'Default', 'calendareditgroup', 3, 0),
       (5, 'Default', 'calendareditgroup', 1, 1),
       (6, 'Default', 'calendareditown', 2, 1),
       (7, 'Default', 'calendareditown', 3, 0),
       (9, 'Default', 'calendareditown', 1, 1),
       (10, 'Default', 'statistics', 2, 0),
       (11, 'Default', 'statistics', 3, 0),
       (12, 'Default', 'calendareditgroupmanaged', 1, 1),
       (13, 'Default', 'statistics', 1, 1),
       (14, 'Default', 'regions', 2, 0),
       (15, 'Default', 'regions', 3, 0),
       (17, 'Default', 'regions', 1, 1),
       (18, 'Default', 'holidays', 2, 0),
       (19, 'Default', 'holidays', 3, 0),
       (20, 'Default', 'calendareditgroupmanaged', 3, 0),
       (21, 'Default', 'holidays', 1, 1),
       (22, 'Default', 'declination', 2, 0),
       (23, 'Default', 'declination', 3, 0),
       (24, 'Default', 'calendareditgroupmanaged', 2, 0),
       (25, 'Default', 'declination', 1, 1),
       (26, 'Default', 'calendaroptions', 2, 0),
       (27, 'Default', 'calendaroptions', 3, 0),
       (29, 'Default', 'calendaroptions', 1, 1),
       (30, 'Default', 'calendaredit', 2, 1),
       (31, 'Default', 'calendaredit', 3, 0),
       (32, 'Default', 'manageronlyabsences', 2, 0),
       (33, 'Default', 'calendaredit', 1, 1),
       (34, 'Default', 'calendarview', 2, 1),
       (35, 'Default', 'calendarview', 3, 1),
       (36, 'Default', 'manageronlyabsences', 3, 0),
       (37, 'Default', 'calendarview', 1, 1),
       (38, 'Default', 'absenceedit', 2, 0),
       (39, 'Default', 'absenceedit', 3, 0),
       (40, 'Default', 'manageronlyabsences', 1, 1),
       (41, 'Default', 'absenceedit', 1, 1),
       (42, 'Default', 'viewprofile', 2, 1),
       (43, 'Default', 'viewprofile', 3, 0),
       (44, 'Default', 'remainder', 2, 0),
       (45, 'Default', 'viewprofile', 1, 1),
       (46, 'Default', 'attachments', 2, 1),
       (47, 'Default', 'attachments', 3, 0),
       (48, 'Default', 'remainder', 3, 0),
       (49, 'Default', 'attachments', 1, 1),
       (50, 'Default', 'roles', 2, 0),
       (51, 'Default', 'roles', 3, 0),
       (52, 'Default', 'remainder', 1, 1),
       (53, 'Default', 'roles', 1, 1),
       (54, 'Default', 'messageedit', 2, 1),
       (55, 'Default', 'messageedit', 3, 0),
       (56, 'Default', 'usernotifications', 2, 1),
       (57, 'Default', 'messageedit', 1, 1),
       (58, 'Default', 'messageview', 2, 1),
       (59, 'Default', 'messageview', 3, 0),
       (60, 'Default', 'usernotifications', 3, 0),
       (61, 'Default', 'messageview', 1, 1),
       (62, 'Default', 'groups', 2, 0),
       (63, 'Default', 'groups', 3, 0),
       (64, 'Default', 'usernotifications', 1, 1),
       (65, 'Default', 'groups', 1, 1),
       (66, 'Default', 'admin', 2, 0),
       (67, 'Default', 'admin', 3, 0),
       (68, 'Default', 'usergroups', 2, 1),
       (69, 'Default', 'admin', 1, 1),
       (70, 'Default', 'usergroups', 3, 0),
       (71, 'Default', 'calendareditall', 3, 0),
       (72, 'Default', 'calendareditall', 2, 0),
       (73, 'Default', 'calendarviewgroup', 1, 1),
       (74, 'Default', 'usergroups', 1, 1),
       (75, 'Default', 'calendarviewgroup', 3, 0),
       (76, 'Default', 'calendarviewgroup', 2, 1),
       (77, 'Default', 'calendarviewall', 1, 1),
       (78, 'Default', 'usercustom', 2, 0),
       (79, 'Default', 'calendarviewall', 3, 0),
       (80, 'Default', 'calendarviewall', 2, 0),
       (81, 'Default', 'useraccount', 1, 1),
       (82, 'Default', 'usercustom', 3, 0),
       (83, 'Default', 'useraccount', 3, 0),
       (84, 'Default', 'useraccount', 2, 0),
       (85, 'Default', 'groupmemberships', 1, 1),
       (86, 'Default', 'usercustom', 1, 1),
       (87, 'Default', 'groupmemberships', 3, 0),
       (88, 'Default', 'groupmemberships', 2, 0),
       (89, 'Default', 'absum', 1, 1),
       (90, 'Default', 'useravatar', 2, 1),
       (91, 'Default', 'absum', 3, 0),
       (92, 'Default', 'absum', 2, 0),
       (93, 'Default', 'daynote', 1, 1),
       (94, 'Default', 'useravatar', 3, 0),
       (95, 'Default', 'daynote', 3, 0),
       (96, 'Default', 'daynote', 2, 1),
       (97, 'Default', 'daynoteglobal', 1, 1),
       (98, 'Default', 'useravatar', 1, 1),
       (99, 'Default', 'daynoteglobal', 3, 0),
       (100, 'Default', 'daynoteglobal', 2, 0),
       (101, 'Default', 'userallowance', 1, 1),
       (102, 'Default', 'userabsences', 2, 0),
       (103, 'Default', 'userallowance', 3, 0),
       (104, 'Default', 'userallowance', 2, 0),
       (105, 'Default', 'upload', 1, 1),
       (106, 'Default', 'userabsences', 3, 0),
       (107, 'Default', 'upload', 3, 0),
       (108, 'Default', 'upload', 2, 0),
       (109, 'Default', 'groupcalendaredit', 1, 1),
       (110, 'Default', 'userabsences', 1, 1),
       (111, 'Default', 'groupcalendaredit', 3, 0),
       (112, 'Default', 'groupcalendaredit', 2, 0),
       (113, 'Default', 'useredit', 1, 1),
       (114, 'Default', 'useroptions', 2, 1),
       (115, 'Default', 'useredit', 3, 0),
       (116, 'Default', 'useredit', 2, 0),
       (117, 'Default', 'useroptions', 1, 1),
       (118, 'Default', 'useroptions', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_regions`
--

DROP TABLE IF EXISTS `tcneo_regions`;
CREATE TABLE IF NOT EXISTS `tcneo_regions`
(
  `id`          int(11)                                                 NOT NULL AUTO_INCREMENT,
  `name`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `region_name` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_regions`
--

INSERT INTO `tcneo_regions` (`id`, `name`, `description`)
VALUES (1, 'Default', 'Default Region'),
       (2, 'Canada', 'Canada Region'),
       (3, 'Germany', 'Germany Region');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_region_role`
--

DROP TABLE IF EXISTS `tcneo_region_role`;
CREATE TABLE IF NOT EXISTS `tcneo_region_role`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `regionid` int(11) NOT NULL,
  `roleid`   int(11) NOT NULL,
  `access`   varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'edit',
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_roles`
--

DROP TABLE IF EXISTS `tcneo_roles`;
CREATE TABLE IF NOT EXISTS `tcneo_roles`
(
  `id`          int(11)                                                 NOT NULL AUTO_INCREMENT,
  `name`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `color`       varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL DEFAULT 'default',
  `created`     timestamp                                               NOT NULL DEFAULT current_timestamp(),
  `updated`     timestamp                                               NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_roles`
--

INSERT INTO `tcneo_roles` (`id`, `name`, `description`, `color`, `created`, `updated`)
VALUES (1, 'Administrator', 'Application administrator', 'danger', '2022-02-01 18:11:39', '2022-02-01 18:11:39'),
       (2, 'User', 'Standard role for logged in users', 'primary', '2022-02-01 18:11:39', '2022-02-01 18:11:39'),
       (3, 'Public', 'All users not logged in', 'secondary', '2022-02-01 18:11:39', '2022-02-01 18:11:39'),
       (4, 'Manager', 'Group manager role', 'warning', '2024-08-01 06:32:01', '2024-08-01 06:32:10'),
       (5, 'Consultant', 'Consultant role', 'default', '2024-08-01 06:34:15', '2024-08-01 06:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_templates`
--

DROP TABLE IF EXISTS `tcneo_templates`;
CREATE TABLE IF NOT EXISTS `tcneo_templates`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `year`     varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci  DEFAULT NULL,
  `month`    char(2) CHARACTER SET utf8 COLLATE utf8_general_ci     DEFAULT NULL,
  `abs1`     int(11)                                                DEFAULT NULL,
  `abs2`     int(11)                                                DEFAULT NULL,
  `abs3`     int(11)                                                DEFAULT NULL,
  `abs4`     int(11)                                                DEFAULT NULL,
  `abs5`     int(11)                                                DEFAULT NULL,
  `abs6`     int(11)                                                DEFAULT NULL,
  `abs7`     int(11)                                                DEFAULT NULL,
  `abs8`     int(11)                                                DEFAULT NULL,
  `abs9`     int(11)                                                DEFAULT NULL,
  `abs10`    int(11)                                                DEFAULT NULL,
  `abs11`    int(11)                                                DEFAULT NULL,
  `abs12`    int(11)                                                DEFAULT NULL,
  `abs13`    int(11)                                                DEFAULT NULL,
  `abs14`    int(11)                                                DEFAULT NULL,
  `abs15`    int(11)                                                DEFAULT NULL,
  `abs16`    int(11)                                                DEFAULT NULL,
  `abs17`    int(11)                                                DEFAULT NULL,
  `abs18`    int(11)                                                DEFAULT NULL,
  `abs19`    int(11)                                                DEFAULT NULL,
  `abs20`    int(11)                                                DEFAULT NULL,
  `abs21`    int(11)                                                DEFAULT NULL,
  `abs22`    int(11)                                                DEFAULT NULL,
  `abs23`    int(11)                                                DEFAULT NULL,
  `abs24`    int(11)                                                DEFAULT NULL,
  `abs25`    int(11)                                                DEFAULT NULL,
  `abs26`    int(11)                                                DEFAULT NULL,
  `abs27`    int(11)                                                DEFAULT NULL,
  `abs28`    int(11)                                                DEFAULT NULL,
  `abs29`    int(11)                                                DEFAULT NULL,
  `abs30`    int(11)                                                DEFAULT NULL,
  `abs31`    int(11)                                                DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template` (`username`, `year`, `month`),
  KEY `template_username` (`username`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 14
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_templates`
--

INSERT INTO `tcneo_templates` (`id`, `username`, `year`, `month`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`, `abs8`, `abs9`, `abs10`, `abs11`, `abs12`, `abs13`, `abs14`, `abs15`, `abs16`, `abs17`, `abs18`, `abs19`, `abs20`, `abs21`, `abs22`, `abs23`, `abs24`, `abs25`, `abs26`, `abs27`, `abs28`, `abs29`, `abs30`, `abs31`)
VALUES (1, '', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (2, '', '2024', '11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (3, '', '2024', '12', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (4, 'admin', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (5, 'admin', '2024', '11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (6, 'admin', '2024', '12', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (7, 'dduck', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (8, 'sgonzales', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (9, 'phead', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (10, 'mmouse', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (11, 'sman', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (12, 'ccarl', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (13, 'blightyear', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_users`
--

DROP TABLE IF EXISTS `tcneo_users`;
CREATE TABLE IF NOT EXISTS `tcneo_users`
(
  `username`       varchar(40) NOT NULL DEFAULT '',
  `password`       varchar(255)         DEFAULT NULL,
  `firstname`      varchar(80)          DEFAULT NULL,
  `lastname`       varchar(80)          DEFAULT NULL,
  `email`          varchar(100)         DEFAULT NULL,
  `order_key`      varchar(80) NOT NULL DEFAULT '0',
  `role`           int(11)              DEFAULT 2,
  `locked`         tinyint(4)           DEFAULT 0,
  `hidden`         tinyint(4)           DEFAULT 0,
  `onhold`         tinyint(4)           DEFAULT 0,
  `verify`         tinyint(4)           DEFAULT 0,
  `bad_logins`     tinyint(4)           DEFAULT 0,
  `grace_start`    datetime    NOT NULL DEFAULT '1900-01-01 00:00:00',
  `last_pw_change` datetime    NOT NULL DEFAULT '1900-01-01 00:00:00',
  `last_login`     datetime    NOT NULL DEFAULT '1900-01-01 00:00:00',
  `created`        datetime    NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (`username`),
  KEY `user_firstname` (`firstname`),
  KEY `user_lastname` (`lastname`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_users`
--

INSERT INTO `tcneo_users` (`username`, `password`, `firstname`, `lastname`, `email`, `order_key`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`)
VALUES ('admin', '$2y$10$4E4xGXbIs1ldd.aN/knENOF/YTenqHylHhrErESXfBDIBIF/1FT2.', '', 'Admin', 'webmaster@yourserver.com', '0', 1, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:12:50', '2024-09-07 19:12:40', '2022-01-01 00:00:00'),
       ('blightyear', '$2y$10$1jG7rgdi/5DMd.EliIn7geeyUAGFEaFg.vwS2JunJqzPlugqYttVq', 'Buzz', 'Lightyear', 'blightyear@yourserver.com', '4', 1, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:10:55', '2022-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('ccarl', '$2y$10$TUyhn0BH7IqlKhqfwflsjOtCvaeEC3BaMR7rn3N6YnprXJ37gK9Iq', 'Coyote', 'Carl', 'ccarl@yourserver.com', 'zzz', 2, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:11:07', '1900-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('dduck', '$2y$10$uk6Z5XPZW24vMTjnqY/wJOS2GEO4dDBvioQynKVB0ydpXBt0m8Jzy', 'Donald', 'Duck', 'dduck@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:09:35', '2024-09-07 19:11:40', '2022-01-01 00:00:00'),
       ('einstein', '$2y$10$CiSlhtPF5FnUiwoB2omCoeX55K5CQWWI3BX0fuEGwvdeX20m0NgEa', 'Albert', 'Einstein', 'einstein@mydomain.com', '0', 2, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:01:14', '2024-09-07 18:19:45', '2024-09-07 18:05:30'),
       ('mimouse', '$2y$10$IkWQeduBbmLn4m5rybEf6OCQRRFui0PEJ', 'Minnie', 'Mouse', 'mimouse@yourserver.com', '0', 2, 1, 1, 1, 1, 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('mmouse', '$2y$10$F5zJ9zNVwzdzuHS9kpser.9.m6BX2mfN8731lLQS/itng0mQ8RmXS', 'Mickey', 'Mouse', 'mmouse@yourserver.com', '0', 4, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:10:24', '2024-07-25 11:04:43', '2022-01-01 00:00:00'),
       ('phead', '$2y$10$lb0jBg3ZALcRp/kn/BDKZu1XAcjaWZHSs/FNuMDgNF01CLS.ZZJ1e', 'Potatoe', 'Head', 'ccarl@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:10:09', '1900-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('sgonzales', '$2y$10$YrHxbqRxuvHiusc/iu41ReU1.cB2edXQswMq/329yJ5LHrkV9ju0C', 'Speedy', 'Gonzales', 'sgonzales@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:09:52', '1900-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('sman', '$2y$10$oLamGsMZIsSKnOexjSq..O9kDgBh8.cCp9zQIFeND9eg76HeM/zVi', '', 'Spiderman', 'sman@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '1900-01-01 00:00:00', '2024-09-07 19:10:41', '1900-01-01 00:00:00', '2022-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_attachment`
--

DROP TABLE IF EXISTS `tcneo_user_attachment`;
CREATE TABLE IF NOT EXISTS `tcneo_user_attachment`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `fileid`   int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_attachment` (`username`, `fileid`),
  KEY `user_attachment_username` (`username`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 28
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_user_attachment`
--

INSERT INTO `tcneo_user_attachment` (`id`, `username`, `fileid`)
VALUES (1, 'admin', 10),
       (2, 'ccarl', 10),
       (3, 'dduck', 10),
       (4, 'sgonzales', 10),
       (5, 'phead', 10),
       (6, 'blightyear', 10),
       (7, 'mmouse', 10),
       (8, 'mimouse', 10),
       (9, 'sman', 10),
       (10, 'admin', 11),
       (11, 'ccarl', 11),
       (12, 'dduck', 11),
       (13, 'sgonzales', 11),
       (14, 'phead', 11),
       (15, 'blightyear', 11),
       (16, 'mmouse', 11),
       (17, 'mimouse', 11),
       (18, 'sman', 11),
       (19, 'admin', 12),
       (20, 'ccarl', 12),
       (21, 'dduck', 12),
       (22, 'sgonzales', 12),
       (23, 'phead', 12),
       (24, 'blightyear', 12),
       (25, 'mmouse', 12),
       (26, 'mimouse', 12),
       (27, 'sman', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_group`
--

DROP TABLE IF EXISTS `tcneo_user_group`;
CREATE TABLE IF NOT EXISTS `tcneo_user_group`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `groupid`  int(11)                                                DEFAULT NULL,
  `type`     tinytext CHARACTER SET utf8 COLLATE utf8_general_ci    DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_group_username` (`username`),
  KEY `user_group_groupid` (`groupid`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 9
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_user_group`
--

INSERT INTO `tcneo_user_group` (`id`, `username`, `groupid`, `type`)
VALUES (1, 'mmouse', 1, 'manager'),
       (2, 'dduck', 1, 'member'),
       (3, 'ccarl', 3, 'member'),
       (4, 'phead', 4, 'member'),
       (5, 'blightyear', 4, 'manager'),
       (6, 'sgonzales', 3, 'member'),
       (7, 'sman', 2, 'manager'),
       (8, 'mimouse', 1, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_message`
--

DROP TABLE IF EXISTS `tcneo_user_message`;
CREATE TABLE IF NOT EXISTS `tcneo_user_message`
(
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `msgid`    int(11)                                                DEFAULT NULL,
  `popup`    tinyint(4) NOT NULL                                    DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_message_username` (`username`),
  KEY `user_message_msgid` (`msgid`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_option`
--

DROP TABLE IF EXISTS `tcneo_user_option`;
CREATE TABLE IF NOT EXISTS `tcneo_user_option`
(
  `id`       int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `option`   varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `value`    text CHARACTER SET utf8 COLLATE utf8_general_ci        DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `useroption` (`username`, `option`),
  KEY `user_option_username` (`username`),
  KEY `user_option_option` (`option`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 360
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_user_option`
--

INSERT INTO `tcneo_user_option` (`id`, `username`, `option`, `value`)
VALUES (1, 'admin', 'title', ''),
       (2, 'admin', 'position', 'Administrator'),
       (3, 'admin', 'id', ''),
       (4, 'admin', 'gender', 'male'),
       (5, 'admin', 'phone', ''),
       (6, 'admin', 'mobile', ''),
       (7, 'admin', 'facebook', ''),
       (8, 'admin', 'google', ''),
       (9, 'admin', 'linkedin', ''),
       (10, 'admin', 'skype', ''),
       (11, 'admin', 'twitter', ''),
       (12, 'admin', 'language', 'english'),
       (13, 'admin', 'avatar', 'is_administrator.png'),
       (14, 'admin', 'calfilterMonth', '202409'),
       (15, 'admin', 'calfilterAbs', 'all'),
       (16, 'mmouse', 'language', 'english'),
       (17, 'mmouse', 'avatar', 'mmouse.jpg'),
       (18, 'ccarl', 'title', 'Dr.'),
       (19, 'ccarl', 'position', 'Roadrunner Hunter'),
       (20, 'ccarl', 'id', 'ID021'),
       (21, 'ccarl', 'gender', 'male'),
       (22, 'ccarl', 'phone', '+1 555 123 4567'),
       (23, 'ccarl', 'mobile', '+1 555 123 4568'),
       (24, 'ccarl', 'facebook', 'fb-ccarl'),
       (25, 'ccarl', 'google', 'g-ccarl'),
       (26, 'ccarl', 'linkedin', 'li-ccarl'),
       (27, 'ccarl', 'skype', 's-ccarl'),
       (28, 'ccarl', 'twitter', 't-ccarl'),
       (29, 'sman', 'title', ''),
       (30, 'ccarl', 'language', 'english'),
       (31, 'ccarl', 'avatar', 'ccarl.gif'),
       (32, 'dduck', 'title', ''),
       (33, 'dduck', 'position', ''),
       (34, 'dduck', 'id', ''),
       (35, 'dduck', 'gender', 'male'),
       (36, 'dduck', 'phone', ''),
       (37, 'dduck', 'mobile', ''),
       (38, 'dduck', 'facebook', ''),
       (39, 'dduck', 'google', ''),
       (40, 'dduck', 'linkedin', ''),
       (41, 'dduck', 'skype', ''),
       (42, 'dduck', 'twitter', ''),
       (43, 'sman', 'position', ''),
       (44, 'dduck', 'language', 'default'),
       (45, 'dduck', 'avatar', 'dduck.gif'),
       (46, 'sgonzales', 'title', ''),
       (47, 'sgonzales', 'position', ''),
       (48, 'sgonzales', 'id', ''),
       (49, 'sgonzales', 'gender', 'male'),
       (50, 'sgonzales', 'phone', ''),
       (51, 'sgonzales', 'mobile', ''),
       (52, 'sgonzales', 'facebook', ''),
       (53, 'sgonzales', 'google', ''),
       (54, 'sgonzales', 'linkedin', ''),
       (55, 'sgonzales', 'skype', ''),
       (56, 'sgonzales', 'twitter', ''),
       (57, 'sman', 'id', ''),
       (58, 'sgonzales', 'language', 'default'),
       (59, 'sgonzales', 'avatar', 'sgonzales.gif'),
       (60, 'phead', 'title', ''),
       (61, 'phead', 'position', ''),
       (62, 'phead', 'id', ''),
       (63, 'phead', 'gender', 'male'),
       (64, 'phead', 'phone', ''),
       (65, 'phead', 'mobile', ''),
       (66, 'phead', 'facebook', ''),
       (67, 'phead', 'google', ''),
       (68, 'phead', 'linkedin', ''),
       (69, 'phead', 'skype', ''),
       (70, 'phead', 'twitter', ''),
       (71, 'sman', 'gender', 'male'),
       (72, 'phead', 'language', 'default'),
       (73, 'phead', 'avatar', 'phead.jpg'),
       (74, 'blightyear', 'title', ''),
       (75, 'blightyear', 'position', ''),
       (76, 'blightyear', 'id', ''),
       (77, 'blightyear', 'gender', 'male'),
       (78, 'blightyear', 'phone', ''),
       (79, 'blightyear', 'mobile', ''),
       (80, 'blightyear', 'facebook', ''),
       (81, 'blightyear', 'google', ''),
       (82, 'blightyear', 'linkedin', ''),
       (83, 'blightyear', 'skype', ''),
       (84, 'blightyear', 'twitter', ''),
       (85, 'sman', 'phone', ''),
       (86, 'blightyear', 'language', 'default'),
       (87, 'blightyear', 'avatar', 'blightyear.jpg'),
       (88, 'sman', 'mobile', ''),
       (89, 'sman', 'facebook', ''),
       (90, 'sman', 'google', ''),
       (91, 'sman', 'linkedin', ''),
       (92, 'sman', 'skype', ''),
       (93, 'sman', 'twitter', ''),
       (94, 'sman', 'language', 'default'),
       (95, 'sman', 'avatar', 'spiderman.jpg'),
       (96, 'mmouse', 'title', ''),
       (97, 'mmouse', 'position', ''),
       (98, 'mmouse', 'id', ''),
       (99, 'mmouse', 'gender', 'male'),
       (100, 'mmouse', 'phone', ''),
       (101, 'mmouse', 'mobile', ''),
       (102, 'mmouse', 'facebook', ''),
       (103, 'mmouse', 'google', ''),
       (104, 'mmouse', 'linkedin', ''),
       (105, 'mmouse', 'skype', ''),
       (106, 'mmouse', 'twitter', ''),
       (107, 'admin', 'calfilterRegion', '1'),
       (108, 'admin', 'notifyAbsenceEvents', '1'),
       (109, 'admin', 'notifyCalendarEvents', '1'),
       (110, 'admin', 'notifyGroupEvents', '1'),
       (111, 'admin', 'notifyHolidayEvents', '1'),
       (112, 'admin', 'notifyMonthEvents', '1'),
       (113, 'admin', 'notifyRoleEvents', '1'),
       (114, 'admin', 'notifyUserEvents', '1'),
       (115, 'admin', 'notifyUserCalEvents', '1'),
       (116, 'admin', 'width', 'full'),
       (117, 'admin', 'region', '1'),
       (118, 'ccarl', 'region', '1'),
       (119, 'dduck', 'width', 'full'),
       (120, 'mmouse', 'width', 'full'),
       (121, 'mmouse', 'calfilterMonth', '202211'),
       (122, 'mmouse', 'calfilterRegion', '1'),
       (123, 'ccarl', 'calendarMonths', 'default'),
       (124, 'ccarl', 'showMonths', '1'),
       (125, 'ccarl', 'calfilterGroup', 'all'),
       (126, 'ccarl', 'verifycode', ''),
       (127, 'ccarl', 'notifyNone', '1'),
       (128, 'ccarl', 'notifyAbsenceEvents', '0'),
       (129, 'ccarl', 'notifyCalendarEvents', '0'),
       (130, 'ccarl', 'notifyGroupEvents', '0'),
       (131, 'ccarl', 'notifyHolidayEvents', '0'),
       (132, 'ccarl', 'notifyMonthEvents', '0'),
       (133, 'ccarl', 'notifyRoleEvents', '0'),
       (134, 'ccarl', 'notifyUserEvents', '0'),
       (135, 'ccarl', 'notifyUserCalEvents', '0'),
       (136, 'ccarl', 'notifyUserCalGroups', '0'),
       (137, 'ccarl', 'custom1', ''),
       (138, 'ccarl', 'custom2', ''),
       (139, 'ccarl', 'custom3', ''),
       (140, 'ccarl', 'custom4', ''),
       (141, 'ccarl', 'custom5', ''),
       (142, 'admin', 'calfilterGroup', 'all'),
       (143, 'dduck', 'region', '1'),
       (144, 'admin', 'calendarMonths', 'default'),
       (145, 'admin', 'showMonths', '2'),
       (146, 'admin', 'verifycode', ''),
       (147, 'admin', 'notifyNone', '0'),
       (148, 'admin', 'notifyUserCalEventsOwn', '0'),
       (149, 'admin', 'notifyUserCalGroups', '0'),
       (150, 'admin', 'custom1', ''),
       (151, 'admin', 'custom2', ''),
       (152, 'admin', 'custom3', ''),
       (153, 'admin', 'custom4', ''),
       (154, 'admin', 'custom5', ''),
       (155, 'phead', 'region', '1'),
       (156, 'mmouse', 'region', '1'),
       (157, 'einstein', 'gender', 'male'),
       (158, 'einstein', 'avatar', 'einstein.png'),
       (159, 'einstein', 'language', 'default'),
       (160, 'einstein', 'region', '1'),
       (161, 'einstein', 'title', ''),
       (162, 'einstein', 'position', ''),
       (163, 'einstein', 'id', ''),
       (164, 'einstein', 'phone', ''),
       (165, 'einstein', 'mobile', ''),
       (166, 'einstein', 'facebook', ''),
       (167, 'einstein', 'google', ''),
       (168, 'einstein', 'linkedin', ''),
       (169, 'einstein', 'skype', ''),
       (170, 'einstein', 'twitter', ''),
       (171, 'einstein', 'menuBar', 'default'),
       (172, 'einstein', 'calendarMonths', 'default'),
       (173, 'einstein', 'showMonths', '1'),
       (174, 'einstein', 'calfilterGroup', 'all'),
       (175, 'einstein', 'verifycode', ''),
       (176, 'einstein', 'notifyNone', '1'),
       (177, 'einstein', 'notifyAbsenceEvents', '0'),
       (178, 'einstein', 'notifyCalendarEvents', '0'),
       (179, 'einstein', 'notifyGroupEvents', '0'),
       (180, 'einstein', 'notifyHolidayEvents', '0'),
       (181, 'einstein', 'notifyMonthEvents', '0'),
       (182, 'einstein', 'notifyRoleEvents', '0'),
       (183, 'einstein', 'notifyUserEvents', '0'),
       (184, 'einstein', 'notifyUserCalEvents', '0'),
       (185, 'einstein', 'notifyUserCalEventsOwn', '0'),
       (186, 'einstein', 'notifyUserCalGroups', '0'),
       (187, 'einstein', 'custom1', ''),
       (188, 'einstein', 'custom2', ''),
       (189, 'einstein', 'custom3', ''),
       (190, 'einstein', 'custom4', ''),
       (191, 'einstein', 'custom5', ''),
       (192, 'ccarl', 'menuBar', 'default'),
       (193, 'ccarl', 'notifyUserCalEventsOwn', '0'),
       (194, 'dduck', 'menuBar', 'default'),
       (195, 'dduck', 'calendarMonths', 'default'),
       (196, 'dduck', 'showMonths', '1'),
       (197, 'dduck', 'calfilterGroup', 'all'),
       (198, 'dduck', 'verifycode', ''),
       (199, 'dduck', 'notifyNone', '1'),
       (200, 'dduck', 'notifyAbsenceEvents', '0'),
       (201, 'dduck', 'notifyCalendarEvents', '0'),
       (202, 'dduck', 'notifyGroupEvents', '0'),
       (203, 'dduck', 'notifyHolidayEvents', '0'),
       (204, 'dduck', 'notifyMonthEvents', '0'),
       (205, 'dduck', 'notifyRoleEvents', '0'),
       (206, 'dduck', 'notifyUserEvents', '0'),
       (207, 'dduck', 'notifyUserCalEvents', '0'),
       (208, 'dduck', 'notifyUserCalEventsOwn', '0'),
       (209, 'dduck', 'notifyUserCalGroups', '0'),
       (210, 'dduck', 'custom1', ''),
       (211, 'dduck', 'custom2', ''),
       (212, 'dduck', 'custom3', ''),
       (213, 'dduck', 'custom4', ''),
       (214, 'dduck', 'custom5', ''),
       (215, 'sgonzales', 'region', '1'),
       (216, 'sgonzales', 'menuBar', 'default'),
       (217, 'sgonzales', 'calendarMonths', 'default'),
       (218, 'sgonzales', 'showMonths', '1'),
       (219, 'sgonzales', 'calfilterGroup', 'all'),
       (220, 'sgonzales', 'verifycode', ''),
       (221, 'sgonzales', 'notifyNone', '1'),
       (222, 'sgonzales', 'notifyAbsenceEvents', '0'),
       (223, 'sgonzales', 'notifyCalendarEvents', '0'),
       (224, 'sgonzales', 'notifyGroupEvents', '0'),
       (225, 'sgonzales', 'notifyHolidayEvents', '0'),
       (226, 'sgonzales', 'notifyMonthEvents', '0'),
       (227, 'sgonzales', 'notifyRoleEvents', '0'),
       (228, 'sgonzales', 'notifyUserEvents', '0'),
       (229, 'sgonzales', 'notifyUserCalEvents', '0'),
       (230, 'sgonzales', 'notifyUserCalEventsOwn', '0'),
       (231, 'sgonzales', 'notifyUserCalGroups', '0'),
       (232, 'sgonzales', 'custom1', ''),
       (233, 'sgonzales', 'custom2', ''),
       (234, 'sgonzales', 'custom3', ''),
       (235, 'sgonzales', 'custom4', ''),
       (236, 'sgonzales', 'custom5', ''),
       (237, 'phead', 'menuBar', 'default'),
       (238, 'phead', 'calendarMonths', 'default'),
       (239, 'phead', 'showMonths', '1'),
       (240, 'phead', 'calfilterGroup', 'all'),
       (241, 'phead', 'verifycode', ''),
       (242, 'phead', 'notifyNone', '1'),
       (243, 'phead', 'notifyAbsenceEvents', '0'),
       (244, 'phead', 'notifyCalendarEvents', '0'),
       (245, 'phead', 'notifyGroupEvents', '0'),
       (246, 'phead', 'notifyHolidayEvents', '0'),
       (247, 'phead', 'notifyMonthEvents', '0'),
       (248, 'phead', 'notifyRoleEvents', '0'),
       (249, 'phead', 'notifyUserEvents', '0'),
       (250, 'phead', 'notifyUserCalEvents', '0'),
       (251, 'phead', 'notifyUserCalEventsOwn', '0'),
       (252, 'phead', 'notifyUserCalGroups', '0'),
       (253, 'phead', 'custom1', ''),
       (254, 'phead', 'custom2', ''),
       (255, 'phead', 'custom3', ''),
       (256, 'phead', 'custom4', ''),
       (257, 'phead', 'custom5', ''),
       (258, 'mmouse', 'menuBar', 'default'),
       (259, 'mmouse', 'calendarMonths', 'default'),
       (260, 'mmouse', 'showMonths', '1'),
       (261, 'mmouse', 'calfilterGroup', 'all'),
       (262, 'mmouse', 'verifycode', ''),
       (263, 'mmouse', 'notifyNone', '1'),
       (264, 'mmouse', 'notifyAbsenceEvents', '0'),
       (265, 'mmouse', 'notifyCalendarEvents', '0'),
       (266, 'mmouse', 'notifyGroupEvents', '0'),
       (267, 'mmouse', 'notifyHolidayEvents', '0'),
       (268, 'mmouse', 'notifyMonthEvents', '0'),
       (269, 'mmouse', 'notifyRoleEvents', '0'),
       (270, 'mmouse', 'notifyUserEvents', '0'),
       (271, 'mmouse', 'notifyUserCalEvents', '0'),
       (272, 'mmouse', 'notifyUserCalEventsOwn', '0'),
       (273, 'mmouse', 'notifyUserCalGroups', '0'),
       (274, 'mmouse', 'custom1', ''),
       (275, 'mmouse', 'custom2', ''),
       (276, 'mmouse', 'custom3', ''),
       (277, 'mmouse', 'custom4', ''),
       (278, 'mmouse', 'custom5', ''),
       (279, 'sman', 'region', '1'),
       (280, 'sman', 'menuBar', 'default'),
       (281, 'sman', 'calendarMonths', 'default'),
       (282, 'sman', 'showMonths', '1'),
       (283, 'sman', 'calfilterGroup', 'all'),
       (284, 'sman', 'verifycode', ''),
       (285, 'sman', 'notifyNone', '1'),
       (286, 'sman', 'notifyAbsenceEvents', '0'),
       (287, 'sman', 'notifyCalendarEvents', '0'),
       (288, 'sman', 'notifyGroupEvents', '0'),
       (289, 'sman', 'notifyHolidayEvents', '0'),
       (290, 'sman', 'notifyMonthEvents', '0'),
       (291, 'sman', 'notifyRoleEvents', '0'),
       (292, 'sman', 'notifyUserEvents', '0'),
       (293, 'sman', 'notifyUserCalEvents', '0'),
       (294, 'sman', 'notifyUserCalEventsOwn', '0'),
       (295, 'sman', 'notifyUserCalGroups', '0'),
       (296, 'sman', 'custom1', ''),
       (297, 'sman', 'custom2', ''),
       (298, 'sman', 'custom3', ''),
       (299, 'sman', 'custom4', ''),
       (300, 'sman', 'custom5', ''),
       (301, 'blightyear', 'region', '1'),
       (302, 'blightyear', 'menuBar', 'default'),
       (303, 'blightyear', 'calendarMonths', 'default'),
       (304, 'blightyear', 'showMonths', '1'),
       (305, 'blightyear', 'calfilterGroup', 'all'),
       (306, 'blightyear', 'verifycode', ''),
       (307, 'blightyear', 'notifyNone', '1'),
       (308, 'blightyear', 'notifyAbsenceEvents', '0'),
       (309, 'blightyear', 'notifyCalendarEvents', '0'),
       (310, 'blightyear', 'notifyGroupEvents', '0'),
       (311, 'blightyear', 'notifyHolidayEvents', '0'),
       (312, 'blightyear', 'notifyMonthEvents', '0'),
       (313, 'blightyear', 'notifyRoleEvents', '0'),
       (314, 'blightyear', 'notifyUserEvents', '0'),
       (315, 'blightyear', 'notifyUserCalEvents', '0'),
       (316, 'blightyear', 'notifyUserCalEventsOwn', '0'),
       (317, 'blightyear', 'notifyUserCalGroups', '0'),
       (318, 'blightyear', 'custom1', ''),
       (319, 'blightyear', 'custom2', ''),
       (320, 'blightyear', 'custom3', ''),
       (321, 'blightyear', 'custom4', ''),
       (322, 'blightyear', 'custom5', ''),
       (323, 'admin', 'menuBar', 'default'),
       (324, 'mimouse', 'avatar', 'mimouse.jpg'),
       (325, 'mimouse', 'calendarMonths', 'default'),
       (326, 'mimouse', 'calfilterGroup', 'all'),
       (327, 'mimouse', 'custom1', ''),
       (328, 'mimouse', 'custom2', ''),
       (329, 'mimouse', 'custom3', ''),
       (330, 'mimouse', 'custom4', ''),
       (331, 'mimouse', 'custom5', ''),
       (332, 'mimouse', 'facebook', ''),
       (333, 'mimouse', 'gender', 'male'),
       (334, 'mimouse', 'google', ''),
       (335, 'mimouse', 'id', ''),
       (336, 'mimouse', 'language', 'default'),
       (337, 'mimouse', 'linkedin', ''),
       (338, 'mimouse', 'menuBar', 'default'),
       (339, 'mimouse', 'mobile', ''),
       (340, 'mimouse', 'notifyAbsenceEvents', '0'),
       (341, 'mimouse', 'notifyCalendarEvents', '0'),
       (342, 'mimouse', 'notifyGroupEvents', '0'),
       (343, 'mimouse', 'notifyHolidayEvents', '0'),
       (344, 'mimouse', 'notifyMonthEvents', '0'),
       (345, 'mimouse', 'notifyNone', '1'),
       (346, 'mimouse', 'notifyRoleEvents', '0'),
       (347, 'mimouse', 'notifyUserCalEvents', '0'),
       (348, 'mimouse', 'notifyUserCalEventsOwn', '0'),
       (349, 'mimouse', 'notifyUserCalGroups', '0'),
       (350, 'mimouse', 'notifyUserEvents', '0'),
       (351, 'mimouse', 'phone', ''),
       (352, 'mimouse', 'position', ''),
       (353, 'mimouse', 'region', '1'),
       (354, 'mimouse', 'showMonths', '1'),
       (356, 'mimouse', 'skype', ''),
       (357, 'mimouse', 'theme', 'cosmo'),
       (358, 'mimouse', 'title', ''),
       (359, 'mimouse', 'twitter', '');
COMMIT;


--
-- Foreign keys
--

ALTER TABLE `tcneo_absence_group`
  ADD CONSTRAINT `fk_absence` FOREIGN KEY (`absid`) REFERENCES `tcneo_absences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_group` FOREIGN KEY (`groupid`) REFERENCES `tcneo_groups` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_allowances`
  ADD CONSTRAINT `fk_absence` FOREIGN KEY (`absid`) REFERENCES `tcneo_absences` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_allowances`
  ADD CONSTRAINT `fk_absence` FOREIGN KEY (`absid`) REFERENCES `tcneo_absences` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_daynotes`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_daynotes`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE;

ALTER TABLE `tcneo_months`
  ADD CONSTRAINT `fk_holiday1` FOREIGN KEY (`hol1`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday2` FOREIGN KEY (`hol2`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday3` FOREIGN KEY (`hol3`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday4` FOREIGN KEY (`hol4`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday5` FOREIGN KEY (`hol5`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday6` FOREIGN KEY (`hol6`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday7` FOREIGN KEY (`hol7`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday8` FOREIGN KEY (`hol8`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday9` FOREIGN KEY (`hol9`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday10` FOREIGN KEY (`hol10`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday11` FOREIGN KEY (`hol11`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday12` FOREIGN KEY (`hol12`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday13` FOREIGN KEY (`hol13`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday14` FOREIGN KEY (`hol14`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday15` FOREIGN KEY (`hol15`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday16` FOREIGN KEY (`hol16`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday17` FOREIGN KEY (`hol17`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday18` FOREIGN KEY (`hol18`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday19` FOREIGN KEY (`hol19`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday20` FOREIGN KEY (`hol20`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday21` FOREIGN KEY (`hol21`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday22` FOREIGN KEY (`hol22`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday23` FOREIGN KEY (`hol23`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday24` FOREIGN KEY (`hol24`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday25` FOREIGN KEY (`hol25`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday26` FOREIGN KEY (`hol26`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday27` FOREIGN KEY (`hol27`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday28` FOREIGN KEY (`hol28`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday29` FOREIGN KEY (`hol29`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday30` FOREIGN KEY (`hol30`) REFERENCES `tcneo_holidays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_holiday31` FOREIGN KEY (`hol31`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_region` FOREIGN KEY (`region`) REFERENCES `tcneo_regions` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_permissions`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role`) REFERENCES `tcneo_roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_region_role`
  ADD CONSTRAINT `fk_region` FOREIGN KEY (`regionid`) REFERENCES `tcneo_regions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`roleid`) REFERENCES `tcneo_roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_templates`
  ADD CONSTRAINT `fk_absence1` FOREIGN KEY (`abs1`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence2` FOREIGN KEY (`abs2`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence3` FOREIGN KEY (`abs3`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence4` FOREIGN KEY (`abs4`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence5` FOREIGN KEY (`abs5`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence6` FOREIGN KEY (`abs6`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence7` FOREIGN KEY (`abs7`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence8` FOREIGN KEY (`abs8`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence9` FOREIGN KEY (`abs9`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence10` FOREIGN KEY (`abs10`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence11` FOREIGN KEY (`abs11`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence12` FOREIGN KEY (`abs12`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence13` FOREIGN KEY (`abs13`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence14` FOREIGN KEY (`abs14`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence15` FOREIGN KEY (`abs15`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence16` FOREIGN KEY (`abs16`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence17` FOREIGN KEY (`abs17`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence18` FOREIGN KEY (`abs18`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence19` FOREIGN KEY (`abs19`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence20` FOREIGN KEY (`abs20`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence21` FOREIGN KEY (`abs21`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence22` FOREIGN KEY (`abs22`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence23` FOREIGN KEY (`abs23`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence24` FOREIGN KEY (`abs24`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence25` FOREIGN KEY (`abs25`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence26` FOREIGN KEY (`abs26`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence27` FOREIGN KEY (`abs27`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence28` FOREIGN KEY (`abs28`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence29` FOREIGN KEY (`abs29`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence30` FOREIGN KEY (`abs30`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence31` FOREIGN KEY (`abs31`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_templates`
  ADD CONSTRAINT `fk_absence1` FOREIGN KEY (`abs1`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence2` FOREIGN KEY (`abs2`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence3` FOREIGN KEY (`abs3`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence4` FOREIGN KEY (`abs4`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence5` FOREIGN KEY (`abs5`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence6` FOREIGN KEY (`abs6`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence7` FOREIGN KEY (`abs7`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence8` FOREIGN KEY (`abs8`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence9` FOREIGN KEY (`abs9`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence10` FOREIGN KEY (`abs10`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence11` FOREIGN KEY (`abs11`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence12` FOREIGN KEY (`abs12`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence13` FOREIGN KEY (`abs13`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence14` FOREIGN KEY (`abs14`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence15` FOREIGN KEY (`abs15`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence16` FOREIGN KEY (`abs16`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence17` FOREIGN KEY (`abs17`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence18` FOREIGN KEY (`abs18`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence19` FOREIGN KEY (`abs19`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence20` FOREIGN KEY (`abs20`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence21` FOREIGN KEY (`abs21`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence22` FOREIGN KEY (`abs22`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence23` FOREIGN KEY (`abs23`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence24` FOREIGN KEY (`abs24`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence25` FOREIGN KEY (`abs25`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence26` FOREIGN KEY (`abs26`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence27` FOREIGN KEY (`abs27`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence28` FOREIGN KEY (`abs28`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence29` FOREIGN KEY (`abs29`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence30` FOREIGN KEY (`abs30`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_absence31` FOREIGN KEY (`abs31`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE;

ALTER TABLE `tcneo_user_attachment`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attachment` FOREIGN KEY (`fileid`) REFERENCES `tcneo_attachments` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_user_attachment`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attachment` FOREIGN KEY (`fileid`) REFERENCES `tcneo_attachments` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_user_group`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_group` FOREIGN KEY (`groupid`) REFERENCES `tcneo_groups` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_user_group`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_group` FOREIGN KEY (`groupid`) REFERENCES `tcneo_groups` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_user_message`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message` FOREIGN KEY (`msgid`) REFERENCES `tcneo_messages` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_user_message`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message` FOREIGN KEY (`msgid`) REFERENCES `tcneo_messages` (`id`) ON DELETE CASCADE;

ALTER TABLE `tcneo_user_option`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE;

ALTER TABLE `tcneo_archive_user_option`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `tcneo_users` (`username`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
