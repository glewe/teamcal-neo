-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 05:29 PM
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
VALUES (1, 'Vacation', 'V', 'fa-solid fa-umbrella-beach', 'FFEE00', 'FC3737', 0, 1, 20, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0),
       (2, 'Sick', 'S', 'fa-solid fa-ambulance', '8C208C', 'FFCCFF', 0, 1, 24, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0),
       (3, 'Day Off', 'F', 'fa-solid fa-mug-saucer', '1A5C00', '00FF00', 0, 1, 12, 0, 0, 1, 1, 1, 0, 0, 0, 1, 0, 0),
       (4, 'Duty Trip', 'D', 'fa-solid fa-plane-departure', 'A35D12', 'FFDB9E', 0, 1, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (5, 'Home Office', 'H', 'fa-solid fa-house', '2717B5', 'D6F5FF', 0, 1, 0, 4, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0),
       (6, 'Not Present', 'N', 'fa-solid fa-rectangle-xmark', 'FF0000', 'C0C0C0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0),
       (7, 'Training', 'T', 'fa-solid fa-book-open-reader', 'FFFFFF', '6495ED', 0, 1, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (8, 'Tentative Absence', 'A', 'fa-solid fa-circle-question', '5E5E5E', 'EFEFEF', 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0),
       (9, 'Half day', 'H', 'fa-solid fa-star-half-stroke', 'A10000', 'FFAAAA', 0, 1, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0);

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
  KEY `k_absid` (`absid`),
  KEY `k_groupid` (`groupid`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 37
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_absence_group`
--

INSERT INTO `tcneo_absence_group` (`id`, `absid`, `groupid`)
VALUES (1, 1, 1),
       (2, 1, 2),
       (3, 1, 3),
       (4, 1, 4),
       (5, 2, 1),
       (6, 2, 2),
       (7, 2, 3),
       (8, 2, 4),
       (9, 3, 1),
       (10, 3, 2),
       (11, 3, 3),
       (12, 3, 4),
       (13, 4, 1),
       (14, 4, 2),
       (15, 4, 3),
       (16, 4, 4),
       (17, 5, 1),
       (18, 5, 2),
       (19, 5, 3),
       (20, 5, 4),
       (21, 6, 1),
       (22, 6, 2),
       (23, 6, 3),
       (24, 6, 4),
       (25, 7, 1),
       (26, 7, 2),
       (27, 7, 3),
       (28, 7, 4),
       (29, 8, 1),
       (30, 8, 2),
       (31, 8, 3),
       (32, 8, 4),
       (33, 9, 1),
       (34, 9, 2),
       (35, 9, 3),
       (36, 9, 4);

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
  UNIQUE KEY `uk_username_absid` (`username`, `absid`),
  KEY `k_username` (`username`)
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
  UNIQUE KEY `uk_username_absid` (`username`, `absid`),
  KEY `k_username` (`username`)
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
  UNIQUE KEY `uk_yyyymmdd_username_region` (`yyyymmdd`, `username`, `region`),
  KEY `k_username` (`username`)
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
  UNIQUE KEY `uk_username_year_month` (`username`, `year`, `month`),
  KEY `k_username` (`username`)
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
  `grace_start`    datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  `last_pw_change` datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  `last_login`     datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  `created`        datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`username`),
  KEY `k_firstname` (`firstname`),
  KEY `k_lastname` (`lastname`)
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
  UNIQUE KEY `uk_username_fileid` (`username`, `fileid`),
  KEY `k_username` (`username`)
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
  UNIQUE KEY `uk_username_groupid` (`username`, `groupid`),
  KEY `k_username` (`username`),
  KEY `k_groupid` (`groupid`)
) ENGINE = MyISAM
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
  KEY `k_username` (`username`),
  KEY `k_msgid` (`msgid`)
) ENGINE = MyISAM
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
  UNIQUE KEY `uk_username_option` (`username`, `option`),
  KEY `k_username` (`username`),
  KEY `k_option` (`option`)
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
  UNIQUE KEY `uk_filename` (`filename`)
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
  UNIQUE KEY `uk_name` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_config`
--

INSERT INTO `tcneo_config` (`name`, `value`)
VALUES
  ('activateMessages', '1'),
  ('adminApproval', '1'),
  ('alertAutocloseDanger', '0'),
  ('alertAutocloseDelay', '3000'),
  ('alertAutocloseSuccess', '1'),
  ('alertAutocloseWarning', '0'),
  ('allowRegistration', '1'),
  ('appDescription', 'A day based online calendar to manage team events'),
  ('appKeywords', 'lewe teamcal neo calendar absence event team'),
  ('appTitle', 'TeamCal Neo'),
  ('appURL', '#'),
  ('avatarHeight', '0'),
  ('avatarMaxSize', '0'),
  ('avatarWidth', '0'),
  ('badLogins', '5'),
  ('calendarFontSize', '100'),
  ('cookieConsent', '1'),
  ('cookieConsentCDN', '0'),
  ('cookieLifetime', '80000'),
  ('currYearRoles', '3,2'),
  ('currentYearOnly', '0'),
  ('dbURL', '#'),
  ('debugHide', '0'),
  ('declAbsence', '0'),
  ('declAbsenceEnddate', ''),
  ('declAbsencePeriod', 'nowForever'),
  ('declAbsenceStartdate', ''),
  ('declApplyToAll', '0'),
  ('declBase', 'group'),
  ('declBefore', '0'),
  ('declBeforeDate', '2024-01-01'),
  ('declBeforeEnddate', ''),
  ('declBeforeOption', 'date'),
  ('declBeforePeriod', 'nowForever'),
  ('declBeforeStartdate', ''),
  ('declNotifyAdmin', '0'),
  ('declNotifyDirector', '0'),
  ('declNotifyManager', '1'),
  ('declNotifyUser', '1'),
  ('declPeriod1', '0'),
  ('declPeriod1End', ''),
  ('declPeriod1Enddate', ''),
  ('declPeriod1Message', ''),
  ('declPeriod1Period', ''),
  ('declPeriod1Start', ''),
  ('declPeriod1Startdate', ''),
  ('declPeriod2', '0'),
  ('declPeriod2End', ''),
  ('declPeriod2Enddate', ''),
  ('declPeriod2Message', ''),
  ('declPeriod2Period', ''),
  ('declPeriod2Start', ''),
  ('declPeriod2Startdate', ''),
  ('declPeriod3', '0'),
  ('declPeriod3End', ''),
  ('declPeriod3Enddate', ''),
  ('declPeriod3Message', ''),
  ('declPeriod3Period', ''),
  ('declPeriod3Start', ''),
  ('declPeriod3Startdate', ''),
  ('declScope', '2'),
  ('declThreshold', '40'),
  ('defaultHomepage', 'home'),
  ('defaultLanguage', 'english'),
  ('defaultMenu', 'navbar'),
  ('defgroupfilter', 'all'),
  ('defregion', 'Default'),
  ('disableTfa', '0'),
  ('emailConfirmation', '1'),
  ('emailNoPastNotifications', '0'),
  ('emailNotifications', '0'),
  ('faCDN', '0'),
  ('firstDayOfWeek', '1'),
  ('font', 'default'),
  ('footerCopyright', 'Lewe.com'),
  ('footerCopyrightUrl', 'http://www.lewe.com'),
  ('footerSocialLinks', 'https://www.linkedin.com/in/george-lewe-a9ab6411b'),
  ('footerViewport', '0'),
  ('forceTfa', '0'),
  ('gdprController', 'ACME Inc.\r\n123 Street\r\nHometown, XY 4567\r\nGermany\r\nEmail: info@acme.com'),
  ('gdprFacebook', '0'),
  ('gdprGoogleAnalytics', '0'),
  ('gdprGooglePlus', '0'),
  ('gdprInstagram', '0'),
  ('gdprLinkedin', '1'),
  ('gdprOfficer', 'John Doe\r\nPhone: +49 555 12345\r\nEmail: john.doe@acme.com'),
  ('gdprOrganization', 'ACME Inc.'),
  ('gdprPaypal', '0'),
  ('gdprPinterest', '0'),
  ('gdprPolicyPage', '1'),
  ('gdprSlideshare', '0'),
  ('gdprTumblr', '0'),
  ('gdprTwitter', '0'),
  ('gdprXing', '1'),
  ('gdprYoutube', '0'),
  ('googleAnalytics', '0'),
  ('googleAnalyticsID', ''),
  ('gracePeriod', '300'),
  ('hideDaynotes', '0'),
  ('hideManagerOnlyAbsences', '0'),
  ('hideManagers', '0'),
  ('homepage', 'home'),
  ('includeRemainder', '0'),
  ('includeRemainderTotal', '0'),
  ('includeSummary', '0'),
  ('includeTotals', '0'),
  ('jQueryCDN', '0'),
  ('jqtheme', 'base'),
  ('licExpiryWarning', '30'),
  ('licKey', ''),
  ('logCalendar', '1'),
  ('logCalendarOptions', '1'),
  ('logConfig', '1'),
  ('logDatabase', '1'),
  ('logDaynote', '0'),
  ('logGroup', '1'),
  ('logImport', '1'),
  ('logLanguage', 'english'),
  ('logLog', '1'),
  ('logLogin', '1'),
  ('logMessage', '0'),
  ('logMonth', '0'),
  ('logNews', '1'),
  ('logPermission', '1'),
  ('logRegion', '1'),
  ('logRegistration', '1'),
  ('logRole', '1'),
  ('logUpload', '1'),
  ('logUser', '1'),
  ('logcolorCalendar', 'default'),
  ('logcolorCalendarOptions', 'danger'),
  ('logcolorConfig', 'danger'),
  ('logcolorDatabase', 'warning'),
  ('logcolorDaynote', 'default'),
  ('logcolorGroup', 'primary'),
  ('logcolorImport', 'warning'),
  ('logcolorLog', 'default'),
  ('logcolorLogin', 'success'),
  ('logcolorMessage', 'primary'),
  ('logcolorMonth', 'default'),
  ('logcolorPattern', 'success'),
  ('logcolorPermission', 'warning'),
  ('logcolorRegion', 'success'),
  ('logcolorRegistration', 'success'),
  ('logcolorRole', 'primary'),
  ('logcolorUpload', 'primary'),
  ('logcolorUser', 'primary'),
  ('logfilterCalendar Options', '0'),
  ('logfilterCalendar', '1'),
  ('logfilterCalendarOptions', '1'),
  ('logfilterConfig', '1'),
  ('logfilterDatabase', '1'),
  ('logfilterDaynote', '0'),
  ('logfilterGroup', '1'),
  ('logfilterImport', '1'),
  ('logfilterLog', '1'),
  ('logfilterLogin', '1'),
  ('logfilterMessage', '0'),
  ('logfilterMonth', '0'),
  ('logfilterNews', '1'),
  ('logfilterPattern', '1'),
  ('logfilterPermission', '1'),
  ('logfilterRegion', '1'),
  ('logfilterRegistration', '1'),
  ('logfilterRole', '1'),
  ('logfilterUpload', '1'),
  ('logfilterUser', '1'),
  ('logfrom', '2024-01-01 00:00:00.000000'),
  ('logperiod', 'curr_all'),
  ('logto', '2024-12-31 23:59:59.999999'),
  ('mailFrom', 'TeamCal Neo'),
  ('mailReply', 'webmaster@mysite.com'),
  ('mailSMTP', '0'),
  ('mailSMTPAnonymous', '0'),
  ('mailSMTPSSL', '0'),
  ('mailSMTPhost', ''),
  ('mailSMTPpassword', ''),
  ('mailSMTPport', '0'),
  ('mailSMTPusername', ''),
  ('managerOnlyIncludesAdministrator', '0'),
  ('markConfidential', '0'),
  ('matomoAnalytics', '0'),
  ('matomoSiteId', ''),
  ('matomoUrl', ''),
  ('monitorAbsence', '0'),
  ('noCaching', '1'),
  ('noIndex', '0'),
  ('notificationsAllGroups', '0'),
  ('pageHelp', '1'),
  ('pastDayColor', ''),
  ('permissionScheme', 'Default'),
  ('pwdStrength', 'medium'),
  ('regionalHolidays', '0'),
  ('regionalHolidaysColor', 'CC0000'),
  ('repeatHeaderCount', '0'),
  ('satBusi', '0'),
  ('showAlerts', 'all'),
  ('showAvatars', '1'),
  ('showMonths', '1'),
  ('showRegionButton', '1'),
  ('showRemainder', '0'),
  ('showRoleIcons', '1'),
  ('showSummary', '1'),
  ('showTooltipCount', '0'),
  ('showTwoMonths', '0'),
  ('showUserIcons', '0'),
  ('showUserRegion', '1'),
  ('showWeekNumbers', '1'),
  ('sortByOrderKey', '1'),
  ('statsDefaultColorAbsences', '#ff0000'),
  ('statsDefaultColorAbsencetype', '#0000ff'),
  ('statsDefaultColorPresences', '#00d000'),
  ('statsDefaultColorRemainder', '#ffa500'),
  ('summaryAbsenceTextColor', 'D9534F'),
  ('summaryPresenceTextColor', '5CB85C'),
  ('sunColor', ''),
  ('sunBusi', '0'),
  ('supportMobile', '0'),
  ('symbolAsIcon', '0'),
  ('takeover', '0'),
  ('timeZone', 'Europe/Berlin'),
  ('todayBorderColor', 'FFB300'),
  ('todayBorderSize', '2'),
  ('trustedRoles', '1'),
  ('underMaintenance', '0'),
  ('userCustom1', 'Custom Field 1'),
  ('userCustom2', 'Custom Field 2'),
  ('userCustom3', 'Custom Field 3'),
  ('userCustom4', 'Custom Field 4'),
  ('userCustom5', 'Custom Field 5'),
  ('userManual', 'https%3A%2F%2Flewe.gitbook.io%2Fteamcal-neo%2F'),
  ('userSearch', '0'),
  ('usersPerPage', '0'),
  ('versionCompare', '1'),
  ('welcomeIcon', 'None'),
  ('welcomeText', '<h3><img alt=\"\" src=\"upload/files/logo-128.png\" style=\"float:left; height:128px; margin-bottom:24px; margin-right:24px; width:128px\" />Welcome to TeamCal Neo</h3>\r\n\r\n<p>TeamCal Neo is a day-based online calendar that allows to easily manage your team\'s events and absences and displays them in an intuitive interface. You can manage absence types, holidays, regional calendars and much more.</p>\r\n\r\n<p>TeamCal Neo requires a yearly license subscription for a fee.</p>\r\n\r\n<p>Its little brother \"<a href=\"http://tcneobasic.lewe.com\">TeamCal Neo Basic</a>\" , however, remains free and offers the core features of the calendar.</p>\r\n\r\n<h3>Links:</h3>\r\n\r\n<ul>\r\n  <li><a href=\"https://teamcalneo.lewe.com/\" target=\"_blank\">Product Page</a></li>\r\n  <li><a href=\"https://lewe.gitbook.io/teamcal-neo/\" target=\"_blank\">Documentation</a></li>\r\n</ul>\r\n\r\n<h3>Login</h3>\r\n\r\n<p>Select Login from the User menu to login and use the following accounts to give this demo a test drive:</p>\r\n\r\n<p><strong>Admin account:</strong></p>\r\n\r\n<p>admin/Qwer!1234</p>\r\n\r\n<p><strong>User accounts:</strong></p>\r\n\r\n<p>ccarl/Qwer!1234<br />\r\nblightyear/Qwer!1234<br />\r\ndduck/Qwer!1234<br />\r\neinstein/Qwer!1234<br />\r\nsgonzalez/Qwer!1234<br />\r\nphead/Qwer!1234<br />\r\nmmouse/Qwer!1234<br />\r\nmimouse/Qwer!1234<br />\r\nsman/Qwer!1234</p>\r\n\r\n<p><strong>LDAP test account (when activating the <a href=\"https://lewe.gitbook.io/teamcal-neo/administration/ldap-authentication\">LDAP test configuration</a>):</strong></p>\r\n\r\n<p>einstein/password</p>\r\n'),
  ('welcomeTitle', 'Welcome To TeamCal Neo');

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
  UNIQUE KEY `uk_yyyymmdd_username_region` (`yyyymmdd`, `username`, `region`),
  KEY `k_username` (`username`)
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
  KEY `k_name` (`name`)
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
  KEY `k_name` (`name`)
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
  `timestamp` datetime NOT NULL                                      DEFAULT '2024-01-01 00:00:00',
  `ip`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user`      varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `event`     text CHARACTER SET utf8 COLLATE utf8_general_ci        DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_messages`
--

DROP TABLE IF EXISTS `tcneo_messages`;
CREATE TABLE IF NOT EXISTS `tcneo_messages`
(
  `id`        int(11)                                               NOT NULL AUTO_INCREMENT,
  `timestamp` datetime                                              NOT NULL DEFAULT '2024-01-01 00:00:00',
  `text`      text CHARACTER SET utf8 COLLATE utf8_general_ci       NOT NULL,
  `type`      varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `k_type` (`type`)
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
  UNIQUE KEY `uk_year_month_region` (`year`, `month`, `region`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_months`
--

INSERT INTO `tcneo_months` (`id`, `year`, `month`, `region`, `wday1`, `wday2`, `wday3`, `wday4`, `wday5`, `wday6`, `wday7`, `wday8`, `wday9`, `wday10`, `wday11`, `wday12`, `wday13`, `wday14`, `wday15`, `wday16`, `wday17`, `wday18`, `wday19`, `wday20`, `wday21`, `wday22`, `wday23`, `wday24`, `wday25`, `wday26`, `wday27`, `wday28`, `wday29`, `wday30`, `wday31`, `week1`, `week2`, `week3`, `week4`, `week5`, `week6`, `week7`, `week8`, `week9`, `week10`, `week11`, `week12`,
                            `week13`, `week14`, `week15`, `week16`, `week17`, `week18`, `week19`, `week20`, `week21`, `week22`, `week23`, `week24`, `week25`, `week26`, `week27`, `week28`, `week29`, `week30`, `week31`, `hol1`, `hol2`, `hol3`, `hol4`, `hol5`, `hol6`, `hol7`, `hol8`, `hol9`, `hol10`, `hol11`, `hol12`, `hol13`, `hol14`, `hol15`, `hol16`, `hol17`, `hol18`, `hol19`, `hol20`, `hol21`, `hol22`, `hol23`, `hol24`, `hol25`, `hol26`, `hol27`, `hol28`, `hol29`, `hol30`,
                            `hol31`)
VALUES (1, '2024', '10', 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 40, 40, 40, 40, 40, 40, 41, 41, 41, 41, 41, 41, 41, 42, 42, 42, 42, 42, 42, 42, 43, 43, 43, 43, 43, 43, 43, 44, 44, 44, 44, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (2, '2024', '11', 1, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 0, 44, 44, 44, 45, 45, 45, 45, 45, 45, 45, 46, 46, 46, 46, 46, 46, 46, 47, 47, 47, 47, 47, 47, 47, 48, 48, 48, 48, 48, 48, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (3, '2024', '12', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 48, 49, 49, 49, 49, 49, 49, 49, 50, 50, 50, 50, 50, 50, 50, 51, 51, 51, 51, 51, 51, 51, 52, 52, 52, 52, 52, 52, 52, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (4, '2024', '09', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 0, 35, 36, 36, 36, 36, 36, 36, 36, 37, 37, 37, 37, 37, 37, 37, 38, 38, 38, 38, 38, 38, 38, 39, 39, 39, 39, 39, 39, 39, 40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_patterns`
--

DROP TABLE IF EXISTS `tcneo_patterns`;
CREATE TABLE IF NOT EXISTS `tcneo_patterns`
(
  `id`          int(11)                                                 NOT NULL AUTO_INCREMENT,
  `name`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci           DEFAULT NULL,
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `abs1`        int(11)                                                          DEFAULT NULL,
  `abs2`        int(11)                                                          DEFAULT NULL,
  `abs3`        int(11)                                                          DEFAULT NULL,
  `abs4`        int(11)                                                          DEFAULT NULL,
  `abs5`        int(11)                                                          DEFAULT NULL,
  `abs6`        int(11)                                                          DEFAULT NULL,
  `abs7`        int(11)                                                          DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_patterns`
--

INSERT INTO `tcneo_patterns` (`id`, `name`, `description`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`)
VALUES (1, 'Home Office Mo We Fr', 'The official home office schedule for group A', 5, 0, 5, 0, 5, 0, 0),
       (2, 'Home Office Tu Th', 'The official home office schedule for group B', 0, 5, 0, 5, 0, 0, 0),
       (3, 'Training Week', '4 days of training - 1 day off', 7, 7, 7, 7, 3, 0, 0);

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
  UNIQUE KEY `uk_scheme_permission_role` (`scheme`, `permission`, `role`),
  KEY `k_scheme` (`scheme`),
  KEY `k_permission` (`permission`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 120
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_permissions`
--

INSERT INTO `tcneo_permissions` (`id`, `scheme`, `permission`, `role`, `allowed`) VALUES
(1, 'Default', 'absenceedit', 2, 0),
(2, 'Default', 'absenceedit', 3, 0),
(3, 'Default', 'absenceedit', 1, 1),
(4, 'Default', 'absum', 1, 1),
(5, 'Default', 'absum', 3, 0),
(6, 'Default', 'absum', 2, 0),
(7, 'Default', 'admin', 2, 0),
(8, 'Default', 'admin', 3, 0),
(9, 'Default', 'admin', 1, 1),
(10, 'Default', 'attachments', 2, 1),
(11, 'Default', 'attachments', 3, 0),
(12, 'Default', 'attachments', 1, 1),
(13, 'Default', 'calendaredit', 2, 1),
(14, 'Default', 'calendaredit', 3, 0),
(15, 'Default', 'calendaredit', 1, 1),
(16, 'Default', 'calendareditall', 1, 1),
(17, 'Default', 'calendareditall', 3, 0),
(18, 'Default', 'calendareditall', 2, 0),
(19, 'Default', 'calendareditgroup', 2, 0),
(20, 'Default', 'calendareditgroup', 3, 0),
(21, 'Default', 'calendareditgroup', 1, 1),
(22, 'Default', 'calendareditgroupmanaged', 1, 1),
(23, 'Default', 'calendareditgroupmanaged', 3, 0),
(24, 'Default', 'calendareditgroupmanaged', 2, 0),
(25, 'Default', 'calendareditown', 2, 1),
(26, 'Default', 'calendareditown', 3, 0),
(27, 'Default', 'calendareditown', 1, 1),
(28, 'Default', 'calendaroptions', 2, 0),
(29, 'Default', 'calendaroptions', 3, 0),
(30, 'Default', 'calendaroptions', 1, 1),
(31, 'Default', 'calendarview', 2, 1),
(32, 'Default', 'calendarview', 3, 1),
(33, 'Default', 'calendarview', 1, 1),
(34, 'Default', 'calendarviewall', 1, 1),
(35, 'Default', 'calendarviewall', 3, 0),
(36, 'Default', 'calendarviewall', 2, 0),
(37, 'Default', 'calendarviewgroup', 1, 1),
(38, 'Default', 'calendarviewgroup', 3, 0),
(39, 'Default', 'calendarviewgroup', 2, 1),
(40, 'Default', 'daynote', 1, 1),
(41, 'Default', 'daynote', 3, 0),
(42, 'Default', 'daynote', 2, 1),
(43, 'Default', 'daynoteglobal', 1, 1),
(44, 'Default', 'daynoteglobal', 3, 0),
(45, 'Default', 'daynoteglobal', 2, 0),
(46, 'Default', 'declination', 2, 0),
(47, 'Default', 'declination', 3, 0),
(48, 'Default', 'declination', 1, 1),
(49, 'Default', 'groupcalendaredit', 1, 1),
(50, 'Default', 'groupcalendaredit', 3, 0),
(51, 'Default', 'groupcalendaredit', 2, 0),
(52, 'Default', 'groupmemberships', 1, 1),
(53, 'Default', 'groupmemberships', 3, 0),
(54, 'Default', 'groupmemberships', 2, 0),
(55, 'Default', 'groups', 2, 0),
(56, 'Default', 'groups', 3, 0),
(57, 'Default', 'groups', 1, 1),
(58, 'Default', 'holidays', 2, 0),
(59, 'Default', 'holidays', 3, 0),
(60, 'Default', 'holidays', 1, 1),
(61, 'Default', 'manageronlyabsences', 2, 0),
(62, 'Default', 'manageronlyabsences', 3, 0),
(63, 'Default', 'manageronlyabsences', 1, 1),
(64, 'Default', 'messageedit', 2, 1),
(65, 'Default', 'messageedit', 3, 0),
(66, 'Default', 'messageedit', 1, 1),
(67, 'Default', 'messageview', 2, 1),
(68, 'Default', 'messageview', 3, 0),
(69, 'Default', 'messageview', 1, 1),
(70, 'Default', 'patternedit', 1, 1),
(71, 'Default', 'patternedit', 2, 0),
(72, 'Default', 'patternedit', 3, 0),
(73, 'Default', 'patternedit', 4, 1),
(74, 'Default', 'patternedit', 5, 0),
(75, 'Default', 'regions', 2, 0),
(76, 'Default', 'regions', 3, 0),
(77, 'Default', 'regions', 1, 1),
(78, 'Default', 'remainder', 2, 0),
(79, 'Default', 'remainder', 3, 0),
(80, 'Default', 'remainder', 1, 1),
(81, 'Default', 'roles', 2, 0),
(82, 'Default', 'roles', 3, 0),
(83, 'Default', 'roles', 1, 1),
(84, 'Default', 'statistics', 2, 0),
(85, 'Default', 'statistics', 3, 0),
(86, 'Default', 'statistics', 1, 1),
(87, 'Default', 'upload', 1, 1),
(88, 'Default', 'upload', 3, 0),
(89, 'Default', 'upload', 2, 0),
(90, 'Default', 'userabsences', 2, 0),
(91, 'Default', 'userabsences', 3, 0),
(92, 'Default', 'userabsences', 1, 1),
(93, 'Default', 'useraccount', 1, 1),
(94, 'Default', 'useraccount', 3, 0),
(95, 'Default', 'useraccount', 2, 0),
(96, 'Default', 'userallowance', 1, 1),
(97, 'Default', 'userallowance', 3, 0),
(98, 'Default', 'userallowance', 2, 0),
(99, 'Default', 'useravatar', 2, 1),
(100, 'Default', 'useravatar', 3, 0),
(101, 'Default', 'useravatar', 1, 1),
(102, 'Default', 'usercustom', 2, 0),
(103, 'Default', 'usercustom', 3, 0),
(104, 'Default', 'usercustom', 1, 1),
(105, 'Default', 'useredit', 1, 1),
(106, 'Default', 'useredit', 3, 0),
(107, 'Default', 'useredit', 2, 0),
(108, 'Default', 'usergroups', 2, 1),
(109, 'Default', 'usergroups', 3, 0),
(110, 'Default', 'usergroups', 1, 1),
(111, 'Default', 'usernotifications', 2, 1),
(112, 'Default', 'usernotifications', 3, 0),
(113, 'Default', 'usernotifications', 1, 1),
(114, 'Default', 'useroptions', 2, 1),
(115, 'Default', 'useroptions', 1, 1),
(116, 'Default', 'useroptions', 3, 0),
(117, 'Default', 'viewprofile', 2, 1),
(118, 'Default', 'viewprofile', 3, 0),
(119, 'Default', 'viewprofile', 1, 1),
(120, 'Default', 'absenceedit', 5, 0),
(121, 'Default', 'absenceedit', 4, 0),
(122, 'Default', 'absum', 5, 0),
(123, 'Default', 'absum', 4, 1),
(124, 'Default', 'upload', 5, 0),
(125, 'Default', 'upload', 4, 1),
(126, 'Default', 'admin', 5, 0),
(127, 'Default', 'admin', 4, 0),
(128, 'Default', 'calendaredit', 5, 0),
(129, 'Default', 'calendaredit', 4, 1),
(130, 'Default', 'calendaroptions', 5, 0),
(131, 'Default', 'calendaroptions', 4, 1),
(132, 'Default', 'calendarview', 5, 0),
(133, 'Default', 'calendarview', 4, 1),
(134, 'Default', 'daynote', 5, 0),
(135, 'Default', 'daynote', 4, 1),
(136, 'Default', 'declination', 5, 0),
(137, 'Default', 'declination', 4, 1),
(138, 'Default', 'groupcalendaredit', 5, 0),
(139, 'Default', 'groupcalendaredit', 4, 0),
(140, 'Default', 'groups', 5, 0),
(141, 'Default', 'groups', 4, 0),
(142, 'Default', 'holidays', 5, 0),
(143, 'Default', 'holidays', 4, 1),
(144, 'Default', 'messageedit', 5, 0),
(145, 'Default', 'messageedit', 4, 1),
(146, 'Default', 'messageview', 5, 0),
(147, 'Default', 'messageview', 4, 1),
(148, 'Default', 'regions', 5, 0),
(149, 'Default', 'regions', 4, 1),
(150, 'Default', 'remainder', 5, 0),
(151, 'Default', 'remainder', 4, 1),
(152, 'Default', 'roles', 5, 0),
(153, 'Default', 'roles', 4, 0),
(154, 'Default', 'statistics', 5, 0),
(155, 'Default', 'statistics', 4, 1),
(156, 'Default', 'useredit', 5, 0),
(157, 'Default', 'useredit', 4, 0),
(158, 'Default', 'viewprofile', 5, 0),
(159, 'Default', 'viewprofile', 4, 1),
(160, 'Default', 'calendareditown', 5, 0),
(161, 'Default', 'calendareditown', 4, 1),
(162, 'Default', 'calendareditgroup', 5, 0),
(163, 'Default', 'calendareditgroup', 4, 0),
(164, 'Default', 'calendareditgroupmanaged', 5, 0),
(165, 'Default', 'calendareditgroupmanaged', 4, 1),
(166, 'Default', 'calendareditall', 5, 0),
(167, 'Default', 'calendareditall', 4, 0),
(168, 'Default', 'calendarviewgroup', 5, 0),
(169, 'Default', 'calendarviewgroup', 4, 1),
(170, 'Default', 'calendarviewall', 5, 0),
(171, 'Default', 'calendarviewall', 4, 1),
(172, 'Default', 'daynoteglobal', 5, 0),
(173, 'Default', 'daynoteglobal', 4, 1),
(174, 'Default', 'manageronlyabsences', 5, 0),
(175, 'Default', 'manageronlyabsences', 4, 1),
(176, 'Default', 'useraccount', 5, 0),
(177, 'Default', 'useraccount', 4, 0),
(178, 'Default', 'userabsences', 5, 0),
(179, 'Default', 'userabsences', 4, 1),
(180, 'Default', 'userallowance', 5, 0),
(181, 'Default', 'userallowance', 4, 1),
(182, 'Default', 'useravatar', 5, 0),
(183, 'Default', 'useravatar', 4, 0),
(184, 'Default', 'usercustom', 5, 0),
(185, 'Default', 'usercustom', 4, 0),
(186, 'Default', 'usergroups', 5, 0),
(187, 'Default', 'usergroups', 4, 1),
(188, 'Default', 'groupmemberships', 5, 0),
(189, 'Default', 'groupmemberships', 4, 1),
(190, 'Default', 'usernotifications', 5, 0),
(191, 'Default', 'usernotifications', 4, 0),
(192, 'Default', 'useroptions', 5, 0),
(193, 'Default', 'useroptions', 4, 0);

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
  KEY `k_name` (`name`)
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
  UNIQUE KEY `uk_name` (`name`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_roles`
--

INSERT INTO `tcneo_roles` (`id`, `name`, `description`, `color`, `created`, `updated`)
VALUES (1, 'Administrator', 'Application administrator', 'danger', '2024-02-01 18:11:39', '2024-02-01 18:11:39'),
       (2, 'User', 'Standard role for logged in users', 'primary', '2024-02-01 18:11:39', '2024-02-01 18:11:39'),
       (3, 'Public', 'All users not logged in', 'secondary', '2024-02-01 18:11:39', '2024-02-01 18:11:39'),
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
  UNIQUE KEY `uk_username_year_month` (`username`, `year`, `month`),
  KEY `k_username` (`username`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 33
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_templates`
--

INSERT INTO `tcneo_templates` (`id`, `username`, `year`, `month`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`, `abs8`, `abs9`, `abs10`, `abs11`, `abs12`, `abs13`, `abs14`, `abs15`, `abs16`, `abs17`, `abs18`, `abs19`, `abs20`, `abs21`, `abs22`, `abs23`, `abs24`, `abs25`, `abs26`, `abs27`, `abs28`, `abs29`, `abs30`, `abs31`)
VALUES (4, 'admin', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (5, 'admin', '2024', '11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (6, 'admin', '2024', '12', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (7, 'dduck', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (8, 'sgonzales', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (9, 'phead', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (10, 'mmouse', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (11, 'sman', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (12, 'ccarl', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (13, 'blightyear', '2024', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (14, 'admin', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (15, 'ccarl', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (16, 'admin', '2024', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (17, 'admin', '2024', '02', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (18, 'admin', '2024', '03', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (19, 'admin', '2024', '04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (20, 'admin', '2024', '05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (21, 'admin', '2024', '06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (22, 'admin', '2024', '07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (23, 'admin', '2024', '08', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (24, 'group:3', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (25, 'dduck', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (26, 'einstein', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (27, 'sgonzales', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (28, 'phead', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (29, 'mmouse', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (30, 'sman', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (31, 'blightyear', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
       (32, 'group:1', '2024', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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
  `grace_start`    datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  `last_pw_change` datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  `last_login`     datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  `created`        datetime    NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`username`),
  KEY `k_firstname` (`firstname`),
  KEY `k_lastname` (`lastname`)
) ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_users`
--

INSERT INTO `tcneo_users` (`username`, `password`, `firstname`, `lastname`, `email`, `order_key`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`)
VALUES ('admin', '$2y$10$4E4xGXbIs1ldd.aN/knENOF/YTenqHylHhrErESXfBDIBIF/1FT2.', '', 'Admin', 'webmaster@yourserver.com', '0', 1, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:12:50', '2024-09-19 20:33:29', '2022-01-01 00:00:00'),
       ('blightyear', '$2y$10$1jG7rgdi/5DMd.EliIn7geeyUAGFEaFg.vwS2JunJqzPlugqYttVq', 'Buzz', 'Lightyear', 'blightyear@yourserver.com', '4', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:10:55', '2024-09-13 19:46:59', '2022-01-01 00:00:00'),
       ('ccarl', '$2y$10$TUyhn0BH7IqlKhqfwflsjOtCvaeEC3BaMR7rn3N6YnprXJ37gK9Iq', 'Coyote', 'Carl', 'ccarl@yourserver.com', 'zzz', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:11:07', '2024-09-15 13:23:16', '2022-01-01 00:00:00'),
       ('dduck', '$2y$10$uk6Z5XPZW24vMTjnqY/wJOS2GEO4dDBvioQynKVB0ydpXBt0m8Jzy', 'Donald', 'Duck', 'dduck@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:09:35', '2024-09-07 19:11:40', '2022-01-01 00:00:00'),
       ('einstein', '$2y$10$CiSlhtPF5FnUiwoB2omCoeX55K5CQWWI3BX0fuEGwvdeX20m0NgEa', 'Albert', 'Einstein', 'einstein@mydomain.com', '0', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:01:14', '2024-09-07 18:19:45', '2024-09-07 18:05:30'),
       ('mimouse', '$2y$10$IkWQeduBbmLn4m5rybEf6OCQRRFui0PEJ', 'Minnie', 'Mouse', 'mimouse@yourserver.com', '0', 2, 1, 1, 1, 1, 0, '2024-01-01 00:00:00', '2024-01-01 00:00:00', '2024-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('mmouse', '$2y$10$F5zJ9zNVwzdzuHS9kpser.9.m6BX2mfN8731lLQS/itng0mQ8RmXS', 'Mickey', 'Mouse', 'mmouse@yourserver.com', '0', 4, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:10:24', '2024-07-25 11:04:43', '2022-01-01 00:00:00'),
       ('phead', '$2y$10$lb0jBg3ZALcRp/kn/BDKZu1XAcjaWZHSs/FNuMDgNF01CLS.ZZJ1e', 'Potatoe', 'Head', 'ccarl@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:10:09', '2024-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('sgonzales', '$2y$10$YrHxbqRxuvHiusc/iu41ReU1.cB2edXQswMq/329yJ5LHrkV9ju0C', 'Speedy', 'Gonzales', 'sgonzales@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:09:52', '2024-01-01 00:00:00', '2022-01-01 00:00:00'),
       ('sman', '$2y$10$oLamGsMZIsSKnOexjSq..O9kDgBh8.cCp9zQIFeND9eg76HeM/zVi', '', 'Spiderman', 'sman@yourserver.com', '0', 2, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:10:41', '2024-09-13 19:51:06', '2022-01-01 00:00:00');

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
  UNIQUE KEY `uk_username_fileid` (`username`, `fileid`),
  KEY `k_username` (`username`)
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
  KEY `k_username` (`username`),
  KEY `k_groupid` (`groupid`)
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
  KEY `k_username` (`username`),
  KEY `k_msgid` (`msgid`)
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
  UNIQUE KEY `uk_username_option` (`username`, `option`),
  KEY `k_username` (`username`),
  KEY `k_option` (`option`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 362
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
       (16, 'admin', 'calfilterRegion', '1'),
       (17, 'admin', 'notifyAbsenceEvents', '1'),
       (18, 'admin', 'notifyCalendarEvents', '1'),
       (19, 'admin', 'notifyGroupEvents', '1'),
       (20, 'admin', 'notifyHolidayEvents', '1'),
       (21, 'admin', 'notifyMonthEvents', '1'),
       (22, 'admin', 'notifyRoleEvents', '1'),
       (23, 'admin', 'notifyUserEvents', '1'),
       (24, 'admin', 'notifyUserCalEvents', '1'),
       (25, 'admin', 'width', 'full'),
       (26, 'admin', 'region', '1'),
       (27, 'admin', 'calfilterGroup', 'all'),
       (28, 'admin', 'calendarMonths', 'default'),
       (29, 'admin', 'showMonths', '1'),
       (30, 'admin', 'verifycode', ''),
       (31, 'admin', 'notifyNone', '0'),
       (32, 'admin', 'notifyUserCalEventsOwn', '0'),
       (33, 'admin', 'notifyUserCalGroups', '0'),
       (34, 'admin', 'custom1', ''),
       (35, 'admin', 'custom2', ''),
       (36, 'admin', 'custom3', ''),
       (37, 'admin', 'custom4', ''),
       (38, 'admin', 'custom5', ''),
       (39, 'admin', 'menuBar', 'default'),
       (40, 'blightyear', 'title', ''),
       (41, 'blightyear', 'position', ''),
       (42, 'blightyear', 'id', ''),
       (43, 'blightyear', 'gender', 'male'),
       (44, 'blightyear', 'phone', ''),
       (45, 'blightyear', 'mobile', ''),
       (46, 'blightyear', 'facebook', ''),
       (47, 'blightyear', 'google', ''),
       (48, 'blightyear', 'linkedin', ''),
       (49, 'blightyear', 'skype', ''),
       (50, 'blightyear', 'twitter', ''),
       (51, 'blightyear', 'language', 'default'),
       (52, 'blightyear', 'avatar', 'blightyear.jpg'),
       (53, 'blightyear', 'region', '1'),
       (54, 'blightyear', 'menuBar', 'default'),
       (55, 'blightyear', 'calendarMonths', 'default'),
       (56, 'blightyear', 'showMonths', '1'),
       (57, 'blightyear', 'calfilterGroup', 'all'),
       (58, 'blightyear', 'verifycode', ''),
       (59, 'blightyear', 'notifyNone', '1'),
       (60, 'blightyear', 'notifyAbsenceEvents', '0'),
       (61, 'blightyear', 'notifyCalendarEvents', '0'),
       (62, 'blightyear', 'notifyGroupEvents', '0'),
       (63, 'blightyear', 'notifyHolidayEvents', '0'),
       (64, 'blightyear', 'notifyMonthEvents', '0'),
       (65, 'blightyear', 'notifyRoleEvents', '0'),
       (66, 'blightyear', 'notifyUserEvents', '0'),
       (67, 'blightyear', 'notifyUserCalEvents', '0'),
       (68, 'blightyear', 'notifyUserCalEventsOwn', '0'),
       (69, 'blightyear', 'notifyUserCalGroups', '0'),
       (70, 'blightyear', 'custom1', ''),
       (71, 'blightyear', 'custom2', ''),
       (72, 'blightyear', 'custom3', ''),
       (73, 'blightyear', 'custom4', ''),
       (74, 'blightyear', 'custom5', ''),
       (75, 'ccarl', 'title', 'Dr.'),
       (76, 'ccarl', 'position', 'Roadrunner Hunter'),
       (77, 'ccarl', 'id', 'ID021'),
       (78, 'ccarl', 'gender', 'male'),
       (79, 'ccarl', 'phone', '+1 555 123 4567'),
       (80, 'ccarl', 'mobile', '+1 555 123 4568'),
       (81, 'ccarl', 'facebook', 'fb-ccarl'),
       (82, 'ccarl', 'google', 'g-ccarl'),
       (83, 'ccarl', 'linkedin', 'li-ccarl'),
       (84, 'ccarl', 'skype', 's-ccarl'),
       (85, 'ccarl', 'twitter', 't-ccarl'),
       (86, 'ccarl', 'language', 'english'),
       (87, 'ccarl', 'avatar', 'ccarl.gif'),
       (88, 'ccarl', 'region', '1'),
       (89, 'ccarl', 'calendarMonths', 'default'),
       (90, 'ccarl', 'showMonths', '1'),
       (91, 'ccarl', 'calfilterGroup', 'all'),
       (92, 'ccarl', 'verifycode', ''),
       (93, 'ccarl', 'notifyNone', '1'),
       (94, 'ccarl', 'notifyAbsenceEvents', '0'),
       (95, 'ccarl', 'notifyCalendarEvents', '0'),
       (96, 'ccarl', 'notifyGroupEvents', '0'),
       (97, 'ccarl', 'notifyHolidayEvents', '0'),
       (98, 'ccarl', 'notifyMonthEvents', '0'),
       (99, 'ccarl', 'notifyRoleEvents', '0'),
       (100, 'ccarl', 'notifyUserEvents', '0'),
       (101, 'ccarl', 'notifyUserCalEvents', '0'),
       (102, 'ccarl', 'notifyUserCalGroups', '0'),
       (103, 'ccarl', 'custom1', ''),
       (104, 'ccarl', 'custom2', ''),
       (105, 'ccarl', 'custom3', ''),
       (106, 'ccarl', 'custom4', ''),
       (107, 'ccarl', 'custom5', ''),
       (108, 'ccarl', 'menuBar', 'default'),
       (109, 'ccarl', 'notifyUserCalEventsOwn', '0'),
       (110, 'dduck', 'title', ''),
       (111, 'dduck', 'position', ''),
       (112, 'dduck', 'id', ''),
       (113, 'dduck', 'gender', 'male'),
       (114, 'dduck', 'phone', ''),
       (115, 'dduck', 'mobile', ''),
       (116, 'dduck', 'facebook', ''),
       (117, 'dduck', 'google', ''),
       (118, 'dduck', 'linkedin', ''),
       (119, 'dduck', 'skype', ''),
       (120, 'dduck', 'twitter', ''),
       (121, 'dduck', 'language', 'default'),
       (122, 'dduck', 'avatar', 'dduck.gif'),
       (123, 'dduck', 'width', 'full'),
       (124, 'dduck', 'region', '1'),
       (125, 'dduck', 'menuBar', 'default'),
       (126, 'dduck', 'calendarMonths', 'default'),
       (127, 'dduck', 'showMonths', '1'),
       (128, 'dduck', 'calfilterGroup', 'all'),
       (129, 'dduck', 'verifycode', ''),
       (130, 'dduck', 'notifyNone', '1'),
       (131, 'dduck', 'notifyAbsenceEvents', '0'),
       (132, 'dduck', 'notifyCalendarEvents', '0'),
       (133, 'dduck', 'notifyGroupEvents', '0'),
       (134, 'dduck', 'notifyHolidayEvents', '0'),
       (135, 'dduck', 'notifyMonthEvents', '0'),
       (136, 'dduck', 'notifyRoleEvents', '0'),
       (137, 'dduck', 'notifyUserEvents', '0'),
       (138, 'dduck', 'notifyUserCalEvents', '0'),
       (139, 'dduck', 'notifyUserCalEventsOwn', '0'),
       (140, 'dduck', 'notifyUserCalGroups', '0'),
       (141, 'dduck', 'custom1', ''),
       (142, 'dduck', 'custom2', ''),
       (143, 'dduck', 'custom3', ''),
       (144, 'dduck', 'custom4', ''),
       (145, 'dduck', 'custom5', ''),
       (146, 'einstein', 'gender', 'male'),
       (147, 'einstein', 'avatar', 'einstein.png'),
       (148, 'einstein', 'language', 'default'),
       (149, 'einstein', 'region', '1'),
       (150, 'einstein', 'title', ''),
       (151, 'einstein', 'position', ''),
       (152, 'einstein', 'id', ''),
       (153, 'einstein', 'phone', ''),
       (154, 'einstein', 'mobile', ''),
       (155, 'einstein', 'facebook', ''),
       (156, 'einstein', 'google', ''),
       (157, 'einstein', 'linkedin', ''),
       (158, 'einstein', 'skype', ''),
       (159, 'einstein', 'twitter', ''),
       (160, 'einstein', 'menuBar', 'default'),
       (161, 'einstein', 'calendarMonths', 'default'),
       (162, 'einstein', 'showMonths', '1'),
       (163, 'einstein', 'calfilterGroup', 'all'),
       (164, 'einstein', 'verifycode', ''),
       (165, 'einstein', 'notifyNone', '1'),
       (166, 'einstein', 'notifyAbsenceEvents', '0'),
       (167, 'einstein', 'notifyCalendarEvents', '0'),
       (168, 'einstein', 'notifyGroupEvents', '0'),
       (169, 'einstein', 'notifyHolidayEvents', '0'),
       (170, 'einstein', 'notifyMonthEvents', '0'),
       (171, 'einstein', 'notifyRoleEvents', '0'),
       (172, 'einstein', 'notifyUserEvents', '0'),
       (173, 'einstein', 'notifyUserCalEvents', '0'),
       (174, 'einstein', 'notifyUserCalEventsOwn', '0'),
       (175, 'einstein', 'notifyUserCalGroups', '0'),
       (176, 'einstein', 'custom1', ''),
       (177, 'einstein', 'custom2', ''),
       (178, 'einstein', 'custom3', ''),
       (179, 'einstein', 'custom4', ''),
       (180, 'einstein', 'custom5', ''),
       (181, 'mimouse', 'avatar', 'mimouse.jpg'),
       (182, 'mimouse', 'calendarMonths', 'default'),
       (183, 'mimouse', 'calfilterGroup', 'all'),
       (184, 'mimouse', 'custom1', ''),
       (185, 'mimouse', 'custom2', ''),
       (186, 'mimouse', 'custom3', ''),
       (187, 'mimouse', 'custom4', ''),
       (188, 'mimouse', 'custom5', ''),
       (189, 'mimouse', 'facebook', ''),
       (190, 'mimouse', 'gender', 'male'),
       (191, 'mimouse', 'google', ''),
       (192, 'mimouse', 'id', ''),
       (193, 'mimouse', 'language', 'default'),
       (194, 'mimouse', 'linkedin', ''),
       (195, 'mimouse', 'menuBar', 'default'),
       (196, 'mimouse', 'mobile', ''),
       (197, 'mimouse', 'notifyAbsenceEvents', '0'),
       (198, 'mimouse', 'notifyCalendarEvents', '0'),
       (199, 'mimouse', 'notifyGroupEvents', '0'),
       (200, 'mimouse', 'notifyHolidayEvents', '0'),
       (201, 'mimouse', 'notifyMonthEvents', '0'),
       (202, 'mimouse', 'notifyNone', '1'),
       (203, 'mimouse', 'notifyRoleEvents', '0'),
       (204, 'mimouse', 'notifyUserCalEvents', '0'),
       (205, 'mimouse', 'notifyUserCalEventsOwn', '0'),
       (206, 'mimouse', 'notifyUserCalGroups', '0'),
       (207, 'mimouse', 'notifyUserEvents', '0'),
       (208, 'mimouse', 'phone', ''),
       (209, 'mimouse', 'position', ''),
       (210, 'mimouse', 'region', '1'),
       (211, 'mimouse', 'showMonths', '1'),
       (212, 'mimouse', 'skype', ''),
       (213, 'mimouse', 'title', ''),
       (214, 'mimouse', 'twitter', ''),
       (215, 'mmouse', 'language', 'english'),
       (216, 'mmouse', 'avatar', 'mmouse.jpg'),
       (217, 'mmouse', 'title', ''),
       (218, 'mmouse', 'position', ''),
       (219, 'mmouse', 'id', ''),
       (220, 'mmouse', 'gender', 'male'),
       (221, 'mmouse', 'phone', ''),
       (222, 'mmouse', 'mobile', ''),
       (223, 'mmouse', 'facebook', ''),
       (224, 'mmouse', 'google', ''),
       (225, 'mmouse', 'linkedin', ''),
       (226, 'mmouse', 'skype', ''),
       (227, 'mmouse', 'twitter', ''),
       (228, 'mmouse', 'width', 'full'),
       (229, 'mmouse', 'calfilterMonth', '202211'),
       (230, 'mmouse', 'calfilterRegion', '1'),
       (231, 'mmouse', 'region', '1'),
       (232, 'mmouse', 'menuBar', 'default'),
       (233, 'mmouse', 'calendarMonths', 'default'),
       (234, 'mmouse', 'showMonths', '1'),
       (235, 'mmouse', 'calfilterGroup', 'all'),
       (236, 'mmouse', 'verifycode', ''),
       (237, 'mmouse', 'notifyNone', '1'),
       (238, 'mmouse', 'notifyAbsenceEvents', '0'),
       (239, 'mmouse', 'notifyCalendarEvents', '0'),
       (240, 'mmouse', 'notifyGroupEvents', '0'),
       (241, 'mmouse', 'notifyHolidayEvents', '0'),
       (242, 'mmouse', 'notifyMonthEvents', '0'),
       (243, 'mmouse', 'notifyRoleEvents', '0'),
       (244, 'mmouse', 'notifyUserEvents', '0'),
       (245, 'mmouse', 'notifyUserCalEvents', '0'),
       (246, 'mmouse', 'notifyUserCalEventsOwn', '0'),
       (247, 'mmouse', 'notifyUserCalGroups', '0'),
       (248, 'mmouse', 'custom1', ''),
       (249, 'mmouse', 'custom2', ''),
       (250, 'mmouse', 'custom3', ''),
       (251, 'mmouse', 'custom4', ''),
       (252, 'mmouse', 'custom5', ''),
       (253, 'phead', 'title', ''),
       (254, 'phead', 'position', ''),
       (255, 'phead', 'id', ''),
       (256, 'phead', 'gender', 'male'),
       (257, 'phead', 'phone', ''),
       (258, 'phead', 'mobile', ''),
       (259, 'phead', 'facebook', ''),
       (260, 'phead', 'google', ''),
       (261, 'phead', 'linkedin', ''),
       (262, 'phead', 'skype', ''),
       (263, 'phead', 'twitter', ''),
       (264, 'phead', 'language', 'default'),
       (265, 'phead', 'avatar', 'phead.jpg'),
       (266, 'phead', 'region', '1'),
       (267, 'phead', 'menuBar', 'default'),
       (268, 'phead', 'calendarMonths', 'default'),
       (269, 'phead', 'showMonths', '1'),
       (270, 'phead', 'calfilterGroup', 'all'),
       (271, 'phead', 'verifycode', ''),
       (272, 'phead', 'notifyNone', '1'),
       (273, 'phead', 'notifyAbsenceEvents', '0'),
       (274, 'phead', 'notifyCalendarEvents', '0'),
       (275, 'phead', 'notifyGroupEvents', '0'),
       (276, 'phead', 'notifyHolidayEvents', '0'),
       (277, 'phead', 'notifyMonthEvents', '0'),
       (278, 'phead', 'notifyRoleEvents', '0'),
       (279, 'phead', 'notifyUserEvents', '0'),
       (280, 'phead', 'notifyUserCalEvents', '0'),
       (281, 'phead', 'notifyUserCalEventsOwn', '0'),
       (282, 'phead', 'notifyUserCalGroups', '0'),
       (283, 'phead', 'custom1', ''),
       (284, 'phead', 'custom2', ''),
       (285, 'phead', 'custom3', ''),
       (286, 'phead', 'custom4', ''),
       (287, 'phead', 'custom5', ''),
       (288, 'sgonzales', 'title', ''),
       (289, 'sgonzales', 'position', ''),
       (290, 'sgonzales', 'id', ''),
       (291, 'sgonzales', 'gender', 'male'),
       (292, 'sgonzales', 'phone', ''),
       (293, 'sgonzales', 'mobile', ''),
       (294, 'sgonzales', 'facebook', ''),
       (295, 'sgonzales', 'google', ''),
       (296, 'sgonzales', 'linkedin', ''),
       (297, 'sgonzales', 'skype', ''),
       (298, 'sgonzales', 'twitter', ''),
       (299, 'sgonzales', 'language', 'default'),
       (300, 'sgonzales', 'avatar', 'sgonzales.gif'),
       (301, 'sgonzales', 'region', '1'),
       (302, 'sgonzales', 'menuBar', 'default'),
       (303, 'sgonzales', 'calendarMonths', 'default'),
       (304, 'sgonzales', 'showMonths', '1'),
       (305, 'sgonzales', 'calfilterGroup', 'all'),
       (306, 'sgonzales', 'verifycode', ''),
       (307, 'sgonzales', 'notifyNone', '1'),
       (308, 'sgonzales', 'notifyAbsenceEvents', '0'),
       (309, 'sgonzales', 'notifyCalendarEvents', '0'),
       (310, 'sgonzales', 'notifyGroupEvents', '0'),
       (311, 'sgonzales', 'notifyHolidayEvents', '0'),
       (312, 'sgonzales', 'notifyMonthEvents', '0'),
       (313, 'sgonzales', 'notifyRoleEvents', '0'),
       (314, 'sgonzales', 'notifyUserEvents', '0'),
       (315, 'sgonzales', 'notifyUserCalEvents', '0'),
       (316, 'sgonzales', 'notifyUserCalEventsOwn', '0'),
       (317, 'sgonzales', 'notifyUserCalGroups', '0'),
       (318, 'sgonzales', 'custom1', ''),
       (319, 'sgonzales', 'custom2', ''),
       (320, 'sgonzales', 'custom3', ''),
       (321, 'sgonzales', 'custom4', ''),
       (322, 'sgonzales', 'custom5', ''),
       (323, 'sman', 'title', ''),
       (324, 'sman', 'position', ''),
       (325, 'sman', 'id', ''),
       (326, 'sman', 'gender', 'male'),
       (327, 'sman', 'phone', ''),
       (328, 'sman', 'mobile', ''),
       (329, 'sman', 'facebook', ''),
       (330, 'sman', 'google', ''),
       (331, 'sman', 'linkedin', ''),
       (332, 'sman', 'skype', ''),
       (333, 'sman', 'twitter', ''),
       (334, 'sman', 'language', 'default'),
       (335, 'sman', 'avatar', 'spiderman.jpg'),
       (336, 'sman', 'region', '1'),
       (337, 'sman', 'menuBar', 'default'),
       (338, 'sman', 'calendarMonths', 'default'),
       (339, 'sman', 'showMonths', '1'),
       (340, 'sman', 'calfilterGroup', 'all'),
       (341, 'sman', 'verifycode', ''),
       (342, 'sman', 'notifyNone', '1'),
       (343, 'sman', 'notifyAbsenceEvents', '0'),
       (344, 'sman', 'notifyCalendarEvents', '0'),
       (345, 'sman', 'notifyGroupEvents', '0'),
       (346, 'sman', 'notifyHolidayEvents', '0'),
       (347, 'sman', 'notifyMonthEvents', '0'),
       (348, 'sman', 'notifyRoleEvents', '0'),
       (349, 'sman', 'notifyUserEvents', '0'),
       (350, 'sman', 'notifyUserCalEvents', '0'),
       (351, 'sman', 'notifyUserCalEventsOwn', '0'),
       (352, 'sman', 'notifyUserCalGroups', '0'),
       (353, 'sman', 'custom1', ''),
       (354, 'sman', 'custom2', ''),
       (355, 'sman', 'custom3', ''),
       (356, 'sman', 'custom4', ''),
       (357, 'sman', 'custom5', ''),
       (360, '', 'width', 'full'),
       (361, 'admin', 'defaultMenu', 'navbar');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
