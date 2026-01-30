-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2026 at 05:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

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

CREATE TABLE IF NOT EXISTS `tcneo_absences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `symbol` char(1) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A',
    `icon` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `color` varchar(6) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `bgcolor` varchar(6) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `bgtrans` tinyint (1) NOT NULL DEFAULT 0,
    `factor` float NOT NULL,
    `allowance` float NOT NULL,
    `allowmonth` float NOT NULL,
    `allowweek` float NOT NULL,
    `counts_as` int(11) NOT NULL,
    `show_in_remainder` tinyint (1) NOT NULL,
    `show_totals` tinyint (1) NOT NULL,
    `approval_required` tinyint (1) NOT NULL,
    `counts_as_present` tinyint (1) NOT NULL,
    `manager_only` tinyint (1) NOT NULL,
    `hide_in_profile` tinyint (1) NOT NULL,
    `confidential` tinyint (1) NOT NULL,
    `takeover` tinyint (1) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_absences`
--
INSERT INTO
  `tcneo_absences` (`id`, `name`, `symbol`, `icon`, `color`, `bgcolor`, `bgtrans`, `factor`, `allowance`, `allowmonth`, `allowweek`, `counts_as`, `show_in_remainder`, `show_totals`, `approval_required`, `counts_as_present`, `manager_only`, `hide_in_profile`, `confidential`, `takeover`)
VALUES
  (1, 'Vacation', 'V', 'fa-solid fa-umbrella-beach', 'FFEE00', 'FC3737', 0, 1, 20, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0),
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

CREATE TABLE IF NOT EXISTS `tcneo_absence_group` (`id` int(11) NOT NULL AUTO_INCREMENT, `absid` int(11) DEFAULT NULL, `groupid` int(11) DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `absgroup` (`absid`, `groupid`), KEY `k_absid` (`absid`), KEY `k_groupid` (`groupid`)) ENGINE = MyISAM AUTO_INCREMENT = 37 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_absence_group`
--
INSERT INTO
  `tcneo_absence_group` (`id`, `absid`, `groupid`)
VALUES
  (1, 1, 1),
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

CREATE TABLE IF NOT EXISTS `tcneo_allowances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `absid` int(11) NOT NULL,
    `carryover` smallint(6) DEFAULT 0,
    `allowance` smallint(6) DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_absid` (`username`, `absid`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM AUTO_INCREMENT = 92 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_allowances`
--
INSERT INTO
  `tcneo_allowances` (`id`, `username`, `absid`, `carryover`, `allowance`)
VALUES
  (1, 'admin', 3, 0, 12),
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

CREATE TABLE IF NOT EXISTS `tcneo_archive_allowances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `absid` int(11) NOT NULL,
    `carryover` smallint(6) DEFAULT 0,
    `allowance` smallint(6) DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_absid` (`username`, `absid`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_daynotes`
--
DROP TABLE IF EXISTS `tcneo_archive_daynotes`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_daynotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yyyymmdd` varchar(8) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'all',
    `region` int(11) NOT NULL DEFAULT 1,
    `daynote` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `color` varchar(16) NOT NULL DEFAULT 'default',
    `confidential` tinyint (1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_yyyymmdd_username_region` (`yyyymmdd`, `username`, `region`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_templates`
--
DROP TABLE IF EXISTS `tcneo_archive_templates`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `year` varchar(4) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `month` char(2) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `abs1` int(11) DEFAULT NULL,
    `abs2` int(11) DEFAULT NULL,
    `abs3` int(11) DEFAULT NULL,
    `abs4` int(11) DEFAULT NULL,
    `abs5` int(11) DEFAULT NULL,
    `abs6` int(11) DEFAULT NULL,
    `abs7` int(11) DEFAULT NULL,
    `abs8` int(11) DEFAULT NULL,
    `abs9` int(11) DEFAULT NULL,
    `abs10` int(11) DEFAULT NULL,
    `abs11` int(11) DEFAULT NULL,
    `abs12` int(11) DEFAULT NULL,
    `abs13` int(11) DEFAULT NULL,
    `abs14` int(11) DEFAULT NULL,
    `abs15` int(11) DEFAULT NULL,
    `abs16` int(11) DEFAULT NULL,
    `abs17` int(11) DEFAULT NULL,
    `abs18` int(11) DEFAULT NULL,
    `abs19` int(11) DEFAULT NULL,
    `abs20` int(11) DEFAULT NULL,
    `abs21` int(11) DEFAULT NULL,
    `abs22` int(11) DEFAULT NULL,
    `abs23` int(11) DEFAULT NULL,
    `abs24` int(11) DEFAULT NULL,
    `abs25` int(11) DEFAULT NULL,
    `abs26` int(11) DEFAULT NULL,
    `abs27` int(11) DEFAULT NULL,
    `abs28` int(11) DEFAULT NULL,
    `abs29` int(11) DEFAULT NULL,
    `abs30` int(11) DEFAULT NULL,
    `abs31` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_year_month` (`username`, `year`, `month`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_users`
--
DROP TABLE IF EXISTS `tcneo_archive_users`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_users` (
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `order_key` varchar(80) NOT NULL DEFAULT '0',
  `role` int(11) DEFAULT 2,
  `locked` tinyint (4) DEFAULT 0,
  `hidden` tinyint (4) DEFAULT 0,
  `onhold` tinyint (4) DEFAULT 0,
  `verify` tinyint (4) DEFAULT 0,
  `bad_logins` tinyint (4) DEFAULT 0,
  `grace_start` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `last_pw_change` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  PRIMARY KEY (`username`),
  KEY `k_firstname` (`firstname`),
  KEY `k_lastname` (`lastname`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_attachment`
--
DROP TABLE IF EXISTS `tcneo_archive_user_attachment`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_user_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `fileid` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_fileid` (`username`, `fileid`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_group`
--
DROP TABLE IF EXISTS `tcneo_archive_user_group`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `groupid` int(11) DEFAULT NULL,
    `type` tinytext CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_groupid` (`username`, `groupid`),
    KEY `k_username` (`username`),
    KEY `k_groupid` (`groupid`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_message`
--
DROP TABLE IF EXISTS `tcneo_archive_user_message`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `msgid` int(11) DEFAULT NULL,
    `popup` tinyint (4) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `k_username` (`username`),
    KEY `k_msgid` (`msgid`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_option`
--
DROP TABLE IF EXISTS `tcneo_archive_user_option`;

CREATE TABLE IF NOT EXISTS `tcneo_archive_user_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `option` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `value` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_option` (`username`, `option`),
    KEY `k_username` (`username`),
    KEY `k_option` (`option`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_attachments`
--
DROP TABLE IF EXISTS `tcneo_attachments`;

CREATE TABLE IF NOT EXISTS `tcneo_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `uploader` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_filename` (`filename`)
) ENGINE = MyISAM AUTO_INCREMENT = 13 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_attachments`
--
INSERT INTO
  `tcneo_attachments` (`id`, `filename`, `uploader`)
VALUES
  (1, 'logo-16.png', 'admin'),
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

CREATE TABLE IF NOT EXISTS `tcneo_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `value` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_name` (`name`)
) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_config`
--
INSERT INTO
  `tcneo_config` (`name`, `value`)
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
  ('declBeforeDate', '2026-01-01'),
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
  ('defaultMenu', 'sidebar'),
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
  ('logPattern', '1'),
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
  ('logfrom', '2026-01-01 00:00:00.000000'),
  ('logperiod', 'curr_all'),
  ('logto', '2026-12-31 23:59:59.999999'),
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
  ('statsDefaultColorAbsences', 'red'),
  ('statsDefaultColorAbsencetype', 'cyan'),
  ('statsDefaultColorPresences', 'green'),
  ('statsDefaultColorPresencetype', 'magenta'),
  ('statsDefaultColorRemainder', 'orange'),
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
  (
    'welcomeText',
    '<h3><img alt=\"\" src=\"public/upload/files/logo-128.png\" style=\"float:left; height:128px; margin-bottom:24px; margin-right:24px; width:128px\" />Welcome to TeamCal Neo 5</h3>\r\n\r\n<p>TeamCal Neo is a day-based online calendar that allows to easily manage your team\'s events and absences and displays them in an intuitive interface. You can manage absence types, holidays, regional calendars and much more.</p>\r\n\r\n<p>TeamCal Neo requires a yearly license subscription for a fee.</p>\r\n\r\n<p>Its little brother \"<a href=\"http://tcneobasic.lewe.com\">TeamCal Neo Basic</a>\" , however, remains free and offers the core features of the calendar.</p>\r\n\r\n<h3>Links:</h3>\r\n\r\n<ul>\r\n  <li><a href=\"https://teamcalneo.lewe.com/\" target=\"_blank\">Product Page</a></li>\r\n  <li><a href=\"https://lewe.gitbook.io/teamcal-neo/\" target=\"_blank\">Documentation</a></li>\r\n</ul>\r\n\r\n<h3>Login</h3>\r\n\r\n<p>Select Login from the User menu to login and use the following accounts to give this demo a test drive:</p>\r\n\r\n<p><strong>Admin account:</strong></p>\r\n\r\n<p>admin/Qwer!1234</p>\r\n\r\n<p><strong>User accounts:</strong></p>\r\n\r\n<p>ccarl/Qwer!1234<br />\r\nblightyear/Qwer!1234<br />\r\ndduck/Qwer!1234<br />\r\neinstein/Qwer!1234<br />\r\nsgonzalez/Qwer!1234<br />\r\nphead/Qwer!1234<br />\r\nmmouse/Qwer!1234<br />\r\nmimouse/Qwer!1234<br />\r\nsman/Qwer!1234</p>\r\n\r\n<p><strong>LDAP test account (when activating the <a href=\"https://lewe.gitbook.io/teamcal-neo/administration/ldap-authentication\">LDAP test configuration</a>):</strong></p>\r\n\r\n<p>einstein/password</p>\r\n'
  ),
  ('welcomeTitle', 'Welcome To TeamCal Neo');

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_daynotes`
--
DROP TABLE IF EXISTS `tcneo_daynotes`;

CREATE TABLE IF NOT EXISTS `tcneo_daynotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yyyymmdd` varchar(8) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'all',
    `region` int(11) NOT NULL DEFAULT 1,
    `daynote` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `color` varchar(16) NOT NULL DEFAULT 'default',
    `confidential` tinyint (1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_yyyymmdd_username_region` (`yyyymmdd`, `username`, `region`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_groups`
--
DROP TABLE IF EXISTS `tcneo_groups`;

CREATE TABLE IF NOT EXISTS `tcneo_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `description` varchar(100) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `minpresent` smallint(6) NOT NULL DEFAULT 0,
    `maxabsent` smallint(6) NOT NULL DEFAULT 9999,
    `minpresentwe` smallint(6) NOT NULL DEFAULT 0,
    `maxabsentwe` smallint(6) NOT NULL DEFAULT 9999,
    PRIMARY KEY (`id`),
    KEY `k_name` (`name`)
) ENGINE = MyISAM AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_groups`
--
INSERT INTO
  `tcneo_groups` (`id`, `name`, `description`, `minpresent`, `maxabsent`, `minpresentwe`, `maxabsentwe`)
VALUES
  (1, 'Disney', 'Disney Characters', 0, 9999, 0, 9999),
  (2, 'Marvel', 'Marvel Characters', 0, 9999, 0, 9999),
  (3, 'Looney', 'Looney Characters', 0, 9999, 0, 9999),
  (4, 'Pixar', 'Pixar Characters', 0, 9999, 0, 9999);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_holidays`
--
DROP TABLE IF EXISTS `tcneo_holidays`;

CREATE TABLE IF NOT EXISTS `tcneo_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `description` varchar(100) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `color` varchar(6) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '000000',
    `bgcolor` varchar(6) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ffffff',
    `businessday` tinyint (1) NOT NULL DEFAULT 0,
    `noabsence` tinyint (1) NOT NULL DEFAULT 0,
    `keepweekendcolor` tinyint (1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `k_name` (`name`)
) ENGINE = MyISAM AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_holidays`
--
INSERT INTO
  `tcneo_holidays` (`id`, `name`, `description`, `color`, `bgcolor`, `businessday`, `noabsence`, `keepweekendcolor`)
VALUES
  (1, 'Business Day', 'Regular business day', '000000', 'ffffff', 1, 0, 0),
  (2, 'Saturday', 'Regular weekend day (Saturday)', '000000', 'fcfc9a', 1, 0, 0),
  (3, 'Sunday', 'Regular weekend day (Sunday)', '000000', 'fcfc9a', 0, 0, 0),
  (4, 'Public Holiday', 'Public bank holidays', '000000', 'EBAAAA', 0, 0, 0),
  (5, 'School Holiday', 'School holidays', '000000', 'BFFFDF', 1, 0, 1);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_log`
--
DROP TABLE IF EXISTS `tcneo_log`;

CREATE TABLE IF NOT EXISTS `tcneo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `timestamp` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
    `ip` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `user` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `event` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_messages`
--
DROP TABLE IF EXISTS `tcneo_messages`;

CREATE TABLE IF NOT EXISTS `tcneo_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `text` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `type` varchar(8) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`id`),
    KEY `k_type` (`type`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_months`
--
DROP TABLE IF EXISTS `tcneo_months`;

CREATE TABLE IF NOT EXISTS `tcneo_months` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `month` char(2) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `region` int(11) DEFAULT 1,
    `wday1` tinyint (1) DEFAULT NULL,
    `wday2` tinyint (1) DEFAULT NULL,
    `wday3` tinyint (1) DEFAULT NULL,
    `wday4` tinyint (1) DEFAULT NULL,
    `wday5` tinyint (1) DEFAULT NULL,
    `wday6` tinyint (1) DEFAULT NULL,
    `wday7` tinyint (1) DEFAULT NULL,
    `wday8` tinyint (1) DEFAULT NULL,
    `wday9` tinyint (1) DEFAULT NULL,
    `wday10` tinyint (1) DEFAULT NULL,
    `wday11` tinyint (1) DEFAULT NULL,
    `wday12` tinyint (1) DEFAULT NULL,
    `wday13` tinyint (1) DEFAULT NULL,
    `wday14` tinyint (1) DEFAULT NULL,
    `wday15` tinyint (1) DEFAULT NULL,
    `wday16` tinyint (1) DEFAULT NULL,
    `wday17` tinyint (1) DEFAULT NULL,
    `wday18` tinyint (1) DEFAULT NULL,
    `wday19` tinyint (1) DEFAULT NULL,
    `wday20` tinyint (1) DEFAULT NULL,
    `wday21` tinyint (1) DEFAULT NULL,
    `wday22` tinyint (1) DEFAULT NULL,
    `wday23` tinyint (1) DEFAULT NULL,
    `wday24` tinyint (1) DEFAULT NULL,
    `wday25` tinyint (1) DEFAULT NULL,
    `wday26` tinyint (1) DEFAULT NULL,
    `wday27` tinyint (1) DEFAULT NULL,
    `wday28` tinyint (1) DEFAULT NULL,
    `wday29` tinyint (1) DEFAULT NULL,
    `wday30` tinyint (1) DEFAULT NULL,
    `wday31` tinyint (1) DEFAULT NULL,
    `week1` smallint(6) DEFAULT NULL,
    `week2` smallint(6) DEFAULT NULL,
    `week3` smallint(6) DEFAULT NULL,
    `week4` smallint(6) DEFAULT NULL,
    `week5` smallint(6) DEFAULT NULL,
    `week6` smallint(6) DEFAULT NULL,
    `week7` smallint(6) DEFAULT NULL,
    `week8` smallint(6) DEFAULT NULL,
    `week9` smallint(6) DEFAULT NULL,
    `week10` smallint(6) DEFAULT NULL,
    `week11` smallint(6) DEFAULT NULL,
    `week12` smallint(6) DEFAULT NULL,
    `week13` smallint(6) DEFAULT NULL,
    `week14` smallint(6) DEFAULT NULL,
    `week15` smallint(6) DEFAULT NULL,
    `week16` smallint(6) DEFAULT NULL,
    `week17` smallint(6) DEFAULT NULL,
    `week18` smallint(6) DEFAULT NULL,
    `week19` smallint(6) DEFAULT NULL,
    `week20` smallint(6) DEFAULT NULL,
    `week21` smallint(6) DEFAULT NULL,
    `week22` smallint(6) DEFAULT NULL,
    `week23` smallint(6) DEFAULT NULL,
    `week24` smallint(6) DEFAULT NULL,
    `week25` smallint(6) DEFAULT NULL,
    `week26` smallint(6) DEFAULT NULL,
    `week27` smallint(6) DEFAULT NULL,
    `week28` smallint(6) DEFAULT NULL,
    `week29` smallint(6) DEFAULT NULL,
    `week30` smallint(6) DEFAULT NULL,
    `week31` smallint(6) DEFAULT NULL,
    `hol1` int(11) DEFAULT NULL,
    `hol2` int(11) DEFAULT NULL,
    `hol3` int(11) DEFAULT NULL,
    `hol4` int(11) DEFAULT NULL,
    `hol5` int(11) DEFAULT NULL,
    `hol6` int(11) DEFAULT NULL,
    `hol7` int(11) DEFAULT NULL,
    `hol8` int(11) DEFAULT NULL,
    `hol9` int(11) DEFAULT NULL,
    `hol10` int(11) DEFAULT NULL,
    `hol11` int(11) DEFAULT NULL,
    `hol12` int(11) DEFAULT NULL,
    `hol13` int(11) DEFAULT NULL,
    `hol14` int(11) DEFAULT NULL,
    `hol15` int(11) DEFAULT NULL,
    `hol16` int(11) DEFAULT NULL,
    `hol17` int(11) DEFAULT NULL,
    `hol18` int(11) DEFAULT NULL,
    `hol19` int(11) DEFAULT NULL,
    `hol20` int(11) DEFAULT NULL,
    `hol21` int(11) DEFAULT NULL,
    `hol22` int(11) DEFAULT NULL,
    `hol23` int(11) DEFAULT NULL,
    `hol24` int(11) DEFAULT NULL,
    `hol25` int(11) DEFAULT NULL,
    `hol26` int(11) DEFAULT NULL,
    `hol27` int(11) DEFAULT NULL,
    `hol28` int(11) DEFAULT NULL,
    `hol29` int(11) DEFAULT NULL,
    `hol30` int(11) DEFAULT NULL,
    `hol31` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_year_month_region` (`year`, `month`, `region`)
) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_months`
--
INSERT INTO
  `tcneo_months` (
    `id`,
    `year`,
    `month`,
    `region`,
    `wday1`,
    `wday2`,
    `wday3`,
    `wday4`,
    `wday5`,
    `wday6`,
    `wday7`,
    `wday8`,
    `wday9`,
    `wday10`,
    `wday11`,
    `wday12`,
    `wday13`,
    `wday14`,
    `wday15`,
    `wday16`,
    `wday17`,
    `wday18`,
    `wday19`,
    `wday20`,
    `wday21`,
    `wday22`,
    `wday23`,
    `wday24`,
    `wday25`,
    `wday26`,
    `wday27`,
    `wday28`,
    `wday29`,
    `wday30`,
    `wday31`,
    `week1`,
    `week2`,
    `week3`,
    `week4`,
    `week5`,
    `week6`,
    `week7`,
    `week8`,
    `week9`,
    `week10`,
    `week11`,
    `week12`,
    `week13`,
    `week14`,
    `week15`,
    `week16`,
    `week17`,
    `week18`,
    `week19`,
    `week20`,
    `week21`,
    `week22`,
    `week23`,
    `week24`,
    `week25`,
    `week26`,
    `week27`,
    `week28`,
    `week29`,
    `week30`,
    `week31`,
    `hol1`,
    `hol2`,
    `hol3`,
    `hol4`,
    `hol5`,
    `hol6`,
    `hol7`,
    `hol8`,
    `hol9`,
    `hol10`,
    `hol11`,
    `hol12`,
    `hol13`,
    `hol14`,
    `hol15`,
    `hol16`,
    `hol17`,
    `hol18`,
    `hol19`,
    `hol20`,
    `hol21`,
    `hol22`,
    `hol23`,
    `hol24`,
    `hol25`,
    `hol26`,
    `hol27`,
    `hol28`,
    `hol29`,
    `hol30`,
    `hol31`
  )
VALUES
  (1, '2026', '01', 1, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_patterns`
--
DROP TABLE IF EXISTS `tcneo_patterns`;

CREATE TABLE IF NOT EXISTS `tcneo_patterns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `description` varchar(100) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `abs1` int(11) DEFAULT NULL,
    `abs2` int(11) DEFAULT NULL,
    `abs3` int(11) DEFAULT NULL,
    `abs4` int(11) DEFAULT NULL,
    `abs5` int(11) DEFAULT NULL,
    `abs6` int(11) DEFAULT NULL,
    `abs7` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_patterns`
--
INSERT INTO
  `tcneo_patterns` (`id`, `name`, `description`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`)
VALUES
  (1, 'Home Office Mo We Fr', 'The official home office schedule for group A', 5, 0, 5, 0, 5, 0, 0),
  (2, 'Home Office Tu Th', 'The official home office schedule for group B', 0, 5, 0, 5, 0, 0, 0),
  (3, 'Training Week', '4 days of training - 1 day off', 7, 7, 7, 7, 3, 0, 0);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_permissions`
--
DROP TABLE IF EXISTS `tcneo_permissions`;

CREATE TABLE IF NOT EXISTS `tcneo_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scheme` varchar(80) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `permission` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL,
    `role` int(11) NOT NULL DEFAULT 1,
    `allowed` tinyint (1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_scheme_permission_role` (`scheme`, `permission`, `role`),
    KEY `k_scheme` (`scheme`),
    KEY `k_permission` (`permission`)
) ENGINE = MyISAM AUTO_INCREMENT = 194 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_permissions`
--
INSERT INTO
  `tcneo_permissions` (`scheme`, `permission`, `role`, `allowed`)
VALUES
  ('Default', 'absenceedit', 2, 0),
  ('Default', 'absenceedit', 3, 0),
  ('Default', 'absenceedit', 1, 1),
  ('Default', 'absum', 1, 1),
  ('Default', 'absum', 3, 0),
  ('Default', 'absum', 2, 0),
  ('Default', 'admin', 2, 0),
  ('Default', 'admin', 3, 0),
  ('Default', 'admin', 1, 1),
  ('Default', 'attachments', 2, 1),
  ('Default', 'attachments', 3, 0),
  ('Default', 'attachments', 1, 1),
  ('Default', 'calendaredit', 2, 1),
  ('Default', 'calendaredit', 3, 0),
  ('Default', 'calendaredit', 1, 1),
  ('Default', 'calendareditall', 1, 1),
  ('Default', 'calendareditall', 3, 0),
  ('Default', 'calendareditall', 2, 0),
  ('Default', 'calendareditgroup', 2, 0),
  ('Default', 'calendareditgroup', 3, 0),
  ('Default', 'calendareditgroup', 1, 1),
  ('Default', 'calendareditgroupmanaged', 1, 1),
  ('Default', 'calendareditgroupmanaged', 3, 0),
  ('Default', 'calendareditgroupmanaged', 2, 0),
  ('Default', 'calendareditown', 2, 1),
  ('Default', 'calendareditown', 3, 0),
  ('Default', 'calendareditown', 1, 1),
  ('Default', 'calendaroptions', 2, 0),
  ('Default', 'calendaroptions', 3, 0),
  ('Default', 'calendaroptions', 1, 1),
  ('Default', 'calendarview', 2, 1),
  ('Default', 'calendarview', 3, 1),
  ('Default', 'calendarview', 1, 1),
  ('Default', 'calendarviewall', 1, 1),
  ('Default', 'calendarviewall', 3, 0),
  ('Default', 'calendarviewall', 2, 0),
  ('Default', 'calendarviewgroup', 1, 1),
  ('Default', 'calendarviewgroup', 3, 0),
  ('Default', 'calendarviewgroup', 2, 1),
  ('Default', 'daynote', 1, 1),
  ('Default', 'daynote', 3, 0),
  ('Default', 'daynote', 2, 1),
  ('Default', 'daynoteglobal', 1, 1),
  ('Default', 'daynoteglobal', 3, 0),
  ('Default', 'daynoteglobal', 2, 0),
  ('Default', 'declination', 2, 0),
  ('Default', 'declination', 3, 0),
  ('Default', 'declination', 1, 1),
  ('Default', 'groupcalendaredit', 1, 1),
  ('Default', 'groupcalendaredit', 3, 0),
  ('Default', 'groupcalendaredit', 2, 0),
  ('Default', 'groupmemberships', 1, 1),
  ('Default', 'groupmemberships', 3, 0),
  ('Default', 'groupmemberships', 2, 0),
  ('Default', 'groups', 2, 0),
  ('Default', 'groups', 3, 0),
  ('Default', 'groups', 1, 1),
  ('Default', 'holidays', 2, 0),
  ('Default', 'holidays', 3, 0),
  ('Default', 'holidays', 1, 1),
  ('Default', 'manageronlyabsences', 2, 0),
  ('Default', 'manageronlyabsences', 3, 0),
  ('Default', 'manageronlyabsences', 1, 1),
  ('Default', 'messageedit', 2, 1),
  ('Default', 'messageedit', 3, 0),
  ('Default', 'messageedit', 1, 1),
  ('Default', 'messageview', 2, 1),
  ('Default', 'messageview', 3, 0),
  ('Default', 'messageview', 1, 1),
  ('Default', 'patternedit', 1, 1),
  ('Default', 'patternedit', 2, 0),
  ('Default', 'patternedit', 3, 0),
  ('Default', 'patternedit', 4, 1),
  ('Default', 'patternedit', 5, 0),
  ('Default', 'regions', 2, 0),
  ('Default', 'regions', 3, 0),
  ('Default', 'regions', 1, 1),
  ('Default', 'remainder', 2, 0),
  ('Default', 'remainder', 3, 0),
  ('Default', 'remainder', 1, 1),
  ('Default', 'roles', 2, 0),
  ('Default', 'roles', 3, 0),
  ('Default', 'roles', 1, 1),
  ('Default', 'statistics', 2, 0),
  ('Default', 'statistics', 3, 0),
  ('Default', 'statistics', 1, 1),
  ('Default', 'upload', 1, 1),
  ('Default', 'upload', 3, 0),
  ('Default', 'upload', 2, 0),
  ('Default', 'userabsences', 2, 0),
  ('Default', 'userabsences', 3, 0),
  ('Default', 'userabsences', 1, 1),
  ('Default', 'useraccount', 1, 1),
  ('Default', 'useraccount', 3, 0),
  ('Default', 'useraccount', 2, 0),
  ('Default', 'userallowance', 1, 1),
  ('Default', 'userallowance', 3, 0),
  ('Default', 'userallowance', 2, 0),
  ('Default', 'useravatar', 2, 1),
  ('Default', 'useravatar', 3, 0),
  ('Default', 'useravatar', 1, 1),
  ('Default', 'usercustom', 2, 0),
  ('Default', 'usercustom', 3, 0),
  ('Default', 'usercustom', 1, 1),
  ('Default', 'useredit', 1, 1),
  ('Default', 'useredit', 3, 0),
  ('Default', 'useredit', 2, 0),
  ('Default', 'usergroups', 2, 1),
  ('Default', 'usergroups', 3, 0),
  ('Default', 'usergroups', 1, 1),
  ('Default', 'usernotifications', 2, 1),
  ('Default', 'usernotifications', 3, 0),
  ('Default', 'usernotifications', 1, 1),
  ('Default', 'useroptions', 2, 1),
  ('Default', 'useroptions', 1, 1),
  ('Default', 'useroptions', 3, 0),
  ('Default', 'viewprofile', 2, 1),
  ('Default', 'viewprofile', 3, 0),
  ('Default', 'viewprofile', 1, 1),
  ('Default', 'absenceedit', 5, 0),
  ('Default', 'absenceedit', 4, 0),
  ('Default', 'absum', 5, 0),
  ('Default', 'absum', 4, 1),
  ('Default', 'upload', 5, 0),
  ('Default', 'upload', 4, 1),
  ('Default', 'admin', 5, 0),
  ('Default', 'admin', 4, 0),
  ('Default', 'calendaredit', 5, 0),
  ('Default', 'calendaredit', 4, 1),
  ('Default', 'calendaroptions', 5, 0),
  ('Default', 'calendaroptions', 4, 1),
  ('Default', 'calendarview', 5, 0),
  ('Default', 'calendarview', 4, 1),
  ('Default', 'daynote', 5, 0),
  ('Default', 'daynote', 4, 1),
  ('Default', 'declination', 5, 0),
  ('Default', 'declination', 4, 1),
  ('Default', 'groupcalendaredit', 5, 0),
  ('Default', 'groupcalendaredit', 4, 0),
  ('Default', 'groups', 5, 0),
  ('Default', 'groups', 4, 0),
  ('Default', 'holidays', 5, 0),
  ('Default', 'holidays', 4, 1),
  ('Default', 'messageedit', 5, 0),
  ('Default', 'messageedit', 4, 1),
  ('Default', 'messageview', 5, 0),
  ('Default', 'messageview', 4, 1),
  ('Default', 'regions', 5, 0),
  ('Default', 'regions', 4, 1),
  ('Default', 'remainder', 5, 0),
  ('Default', 'remainder', 4, 1),
  ('Default', 'roles', 5, 0),
  ('Default', 'roles', 4, 0),
  ('Default', 'statistics', 5, 0),
  ('Default', 'statistics', 4, 1),
  ('Default', 'useredit', 5, 0),
  ('Default', 'useredit', 4, 0),
  ('Default', 'viewprofile', 5, 0),
  ('Default', 'viewprofile', 4, 1),
  ('Default', 'calendareditown', 5, 0),
  ('Default', 'calendareditown', 4, 1),
  ('Default', 'calendareditgroup', 5, 0),
  ('Default', 'calendareditgroup', 4, 0),
  ('Default', 'calendareditgroupmanaged', 5, 0),
  ('Default', 'calendareditgroupmanaged', 4, 1),
  ('Default', 'calendareditall', 5, 0),
  ('Default', 'calendareditall', 4, 0),
  ('Default', 'calendarviewgroup', 5, 0),
  ('Default', 'calendarviewgroup', 4, 1),
  ('Default', 'calendarviewall', 5, 0),
  ('Default', 'calendarviewall', 4, 1),
  ('Default', 'daynoteglobal', 5, 0),
  ('Default', 'daynoteglobal', 4, 1),
  ('Default', 'manageronlyabsences', 5, 0),
  ('Default', 'manageronlyabsences', 4, 1),
  ('Default', 'useraccount', 5, 0),
  ('Default', 'useraccount', 4, 0),
  ('Default', 'userabsences', 5, 0),
  ('Default', 'userabsences', 4, 1),
  ('Default', 'userallowance', 5, 0),
  ('Default', 'userallowance', 4, 1),
  ('Default', 'useravatar', 5, 0),
  ('Default', 'useravatar', 4, 0),
  ('Default', 'usercustom', 5, 0),
  ('Default', 'usercustom', 4, 0),
  ('Default', 'usergroups', 5, 0),
  ('Default', 'usergroups', 4, 1),
  ('Default', 'groupmemberships', 5, 0),
  ('Default', 'groupmemberships', 4, 1),
  ('Default', 'usernotifications', 5, 0),
  ('Default', 'usernotifications', 4, 0),
  ('Default', 'useroptions', 5, 0),
  ('Default', 'useroptions', 4, 0);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_regions`
--
DROP TABLE IF EXISTS `tcneo_regions`;

CREATE TABLE IF NOT EXISTS `tcneo_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `description` varchar(100) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `k_name` (`name`)
) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_regions`
--
INSERT INTO
  `tcneo_regions` (`id`, `name`, `description`)
VALUES
  (1, 'Default', 'Default Region'),
  (2, 'Canada', 'Canada Region'),
  (3, 'Germany', 'Germany Region');

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_region_role`
--
DROP TABLE IF EXISTS `tcneo_region_role`;

CREATE TABLE IF NOT EXISTS `tcneo_region_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regionid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `access` varchar(4) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT 'edit',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_roles`
--
DROP TABLE IF EXISTS `tcneo_roles`;

CREATE TABLE IF NOT EXISTS `tcneo_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `description` varchar(100) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
    `color` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default',
    `created` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_name` (`name`)
) ENGINE = MyISAM AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_roles`
--
INSERT INTO
  `tcneo_roles` (`id`, `name`, `description`, `color`, `created`, `updated`)
VALUES
  (1, 'Administrator', 'Application administrator', 'danger', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (2, 'User', 'Standard role for logged in users', 'primary', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (3, 'Public', 'All users not logged in', 'secondary', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (4, 'Manager', 'Group manager role', 'warning', '2026-08-01 06:32:01', '2026-08-01 06:32:10'),
  (5, 'Consultant', 'Consultant role', 'default', '2026-08-01 06:34:15', '2026-08-01 06:34:15');

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_templates`
--
DROP TABLE IF EXISTS `tcneo_templates`;

CREATE TABLE IF NOT EXISTS `tcneo_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `year` varchar(4) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `month` char(2) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `abs1` int(11) DEFAULT NULL,
    `abs2` int(11) DEFAULT NULL,
    `abs3` int(11) DEFAULT NULL,
    `abs4` int(11) DEFAULT NULL,
    `abs5` int(11) DEFAULT NULL,
    `abs6` int(11) DEFAULT NULL,
    `abs7` int(11) DEFAULT NULL,
    `abs8` int(11) DEFAULT NULL,
    `abs9` int(11) DEFAULT NULL,
    `abs10` int(11) DEFAULT NULL,
    `abs11` int(11) DEFAULT NULL,
    `abs12` int(11) DEFAULT NULL,
    `abs13` int(11) DEFAULT NULL,
    `abs14` int(11) DEFAULT NULL,
    `abs15` int(11) DEFAULT NULL,
    `abs16` int(11) DEFAULT NULL,
    `abs17` int(11) DEFAULT NULL,
    `abs18` int(11) DEFAULT NULL,
    `abs19` int(11) DEFAULT NULL,
    `abs20` int(11) DEFAULT NULL,
    `abs21` int(11) DEFAULT NULL,
    `abs22` int(11) DEFAULT NULL,
    `abs23` int(11) DEFAULT NULL,
    `abs24` int(11) DEFAULT NULL,
    `abs25` int(11) DEFAULT NULL,
    `abs26` int(11) DEFAULT NULL,
    `abs27` int(11) DEFAULT NULL,
    `abs28` int(11) DEFAULT NULL,
    `abs29` int(11) DEFAULT NULL,
    `abs30` int(11) DEFAULT NULL,
    `abs31` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_year_month` (`username`, `year`, `month`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_templates`
--
INSERT INTO
  `tcneo_templates` (`username`, `year`, `month`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`, `abs8`, `abs9`, `abs10`, `abs11`, `abs12`, `abs13`, `abs14`, `abs15`, `abs16`, `abs17`, `abs18`, `abs19`, `abs20`, `abs21`, `abs22`, `abs23`, `abs24`, `abs25`, `abs26`, `abs27`, `abs28`, `abs29`, `abs30`, `abs31`)
VALUES
  ('blightyear', '2026', '01', 5, 4, 7, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 3, NULL, 7, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('blightyear', '2026', '02', NULL, NULL, NULL, 2, NULL, NULL, NULL, 4, 8, NULL, NULL, 3, NULL, 4, 2, NULL, NULL, NULL, NULL, NULL, 2, 8, NULL, 2, NULL, 2, NULL, 8, NULL, NULL, NULL),
  ('blightyear', '2026', '03', NULL, 9, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, 2, NULL, 6, NULL, NULL, NULL),
  ('blightyear', '2026', '04', 6, NULL, 9, NULL, NULL, 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 7, 4, NULL, NULL, NULL, 8, NULL, NULL, NULL, 7, NULL, NULL, 4, NULL, 6, NULL),
  ('blightyear', '2026', '05', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 7, NULL),
  ('blightyear', '2026', '06', 8, NULL, 7, NULL, NULL, 7, NULL, 1, NULL, 6, NULL, 5, 5, NULL, NULL, 5, NULL, NULL, 6, NULL, NULL, 4, 2, NULL, 4, NULL, 6, 3, NULL, NULL, NULL),
  ('blightyear', '2026', '07', NULL, 3, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 3, NULL, 8, NULL, NULL, NULL, 4, 4, NULL, 4, NULL, NULL, 2, NULL, NULL, NULL, 6),
  ('blightyear', '2026', '08', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, NULL, NULL, NULL, NULL, 1, NULL, 5, 6, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, 9, 5),
  ('blightyear', '2026', '09', NULL, 3, NULL, 1, 9, 5, 2, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 4, 8, 5, NULL, NULL, 3, 4, NULL, NULL, NULL, NULL, 1, 8, 3, NULL),
  ('blightyear', '2026', '10', NULL, NULL, 1, NULL, 5, NULL, 6, NULL, NULL, 9, 6, NULL, 1, NULL, NULL, 1, NULL, NULL, 4, 3, 7, NULL, NULL, NULL, 6, NULL, 7, NULL, NULL, NULL, NULL),
  ('blightyear', '2026', '11', NULL, 2, 8, NULL, 5, NULL, 9, NULL, NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('blightyear', '2026', '12', NULL, NULL, 1, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL),
  ('ccarl', '2026', '01', NULL, 6, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, 4, NULL, NULL, 3, NULL, NULL),
  ('ccarl', '2026', '02', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, 5, NULL, NULL, 7, NULL, NULL, 5, 9, NULL, NULL, NULL, 6, NULL, 6, NULL, NULL, NULL, NULL, NULL),
  ('ccarl', '2026', '03', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 8, 2, NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, NULL),
  ('ccarl', '2026', '04', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL),
  ('ccarl', '2026', '05', NULL, NULL, 6, 6, NULL, NULL, 3, NULL, NULL, 4, 9, NULL, 1, NULL, 5, 8, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, 4, NULL, NULL, NULL),
  ('ccarl', '2026', '06', 8, NULL, NULL, 6, NULL, NULL, NULL, 5, NULL, NULL, 4, 4, 8, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 8, NULL),
  ('ccarl', '2026', '07', 1, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 9, 8, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL),
  ('ccarl', '2026', '08', 1, NULL, NULL, 1, 9, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, 7, 5, NULL, NULL, 5, 8, NULL),
  ('ccarl', '2026', '09', NULL, 2, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL),
  ('ccarl', '2026', '10', NULL, NULL, 3, NULL, 5, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 4, 1, 2, NULL, 1, 6, NULL, NULL, 8, 3, NULL, NULL, NULL, 8, 9, 3, NULL, 2),
  ('ccarl', '2026', '11', NULL, 7, 9, NULL, 3, 2, NULL, NULL, NULL, 5, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 3, 3, NULL, NULL),
  ('ccarl', '2026', '12', NULL, NULL, NULL, 9, NULL, 5, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 8, NULL, NULL, 8, NULL, NULL, 2, 3, NULL),
  ('dduck', '2026', '01', NULL, NULL, 1, 2, 1, NULL, NULL, 6, 6, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 7, 6, NULL, NULL, NULL, 6, 8, 5, NULL, NULL, 5, NULL),
  ('dduck', '2026', '02', NULL, NULL, NULL, NULL, 9, 4, 9, NULL, 7, NULL, 2, 3, 2, 2, NULL, 4, NULL, NULL, 1, NULL, NULL, NULL, NULL, 5, NULL, 9, 6, NULL, NULL, NULL, NULL),
  ('dduck', '2026', '03', 2, 3, NULL, 1, NULL, NULL, 5, NULL, 6, NULL, 6, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, NULL, NULL, NULL),
  ('dduck', '2026', '04', 9, NULL, NULL, 5, 7, 9, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, 2, NULL, 6, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('dduck', '2026', '05', 2, 3, NULL, NULL, NULL, 7, 7, NULL, NULL, 4, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, 7, 1, NULL, 7, NULL, 7, 3, NULL, 8),
  ('dduck', '2026', '06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, NULL, NULL, NULL, 2, 6, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL),
  ('dduck', '2026', '07', NULL, NULL, NULL, NULL, NULL, 1, NULL, 9, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL),
  ('dduck', '2026', '08', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, 9, NULL, NULL, 6, NULL, 9, 1, 4, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('dduck', '2026', '09', 9, 7, NULL, NULL, 3, NULL, NULL, 9, NULL, NULL, NULL, 2, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, 3, 8, 8, 8, NULL, NULL),
  ('dduck', '2026', '10', NULL, 2, 6, NULL, NULL, NULL, NULL, 2, NULL, 6, NULL, 6, 4, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL, 4, NULL, 6, 6, NULL, NULL),
  ('dduck', '2026', '11', NULL, 1, NULL, NULL, NULL, 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL, 8, 6, 5, NULL, NULL, NULL, 5, 7, 6, 1, NULL, NULL),
  ('dduck', '2026', '12', NULL, NULL, 5, NULL, 5, 1, NULL, NULL, 2, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 9, NULL, 8, NULL, 8, NULL, NULL, NULL, NULL),
  ('einstein', '2026', '01', NULL, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, 8, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
  ('einstein', '2026', '02', 7, NULL, NULL, NULL, 5, NULL, 3, 4, NULL, NULL, NULL, 7, 4, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 1, 8, NULL, NULL, NULL, NULL),
  ('einstein', '2026', '03', NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 3, 8, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9),
  ('einstein', '2026', '04', NULL, 6, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 4, NULL, NULL, NULL, NULL, 3, NULL, 5, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL),
  ('einstein', '2026', '05', NULL, NULL, 6, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, 2, 3, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 8),
  ('einstein', '2026', '06', NULL, NULL, 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, 1, 5, NULL, 6, NULL, NULL, NULL, 4, NULL),
  ('einstein', '2026', '07', NULL, 3, NULL, 1, NULL, NULL, NULL, 5, 2, NULL, NULL, NULL, 3, NULL, 7, 3, 6, 3, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('einstein', '2026', '08', NULL, 3, NULL, 6, NULL, 6, NULL, 5, NULL, NULL, 1, NULL, NULL, NULL, 2, NULL, NULL, 7, NULL, NULL, NULL, NULL, 6, 5, NULL, NULL, 2, NULL, NULL, NULL, 8),
  ('einstein', '2026', '09', NULL, 4, 4, 1, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, 5, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
  ('einstein', '2026', '10', 5, NULL, NULL, 6, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, 5, 1, 7, NULL, NULL, NULL, 5, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('einstein', '2026', '11', NULL, 1, NULL, 2, NULL, NULL, 5, 8, NULL, 9, 1, NULL, 3, 2, NULL, 1, 3, NULL, 5, NULL, NULL, NULL, NULL, 3, NULL, NULL, 1, NULL, NULL, 2, NULL),
  ('einstein', '2026', '12', NULL, NULL, 4, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, 7, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL),
  ('mimouse', '2026', '01', NULL, 5, NULL, NULL, NULL, NULL, NULL, 4, NULL, 6, 3, NULL, NULL, 7, 7, 2, 6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 4, NULL),
  ('mimouse', '2026', '02', 2, NULL, 7, 1, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 1, NULL, NULL, 4, NULL, NULL, NULL, 6, 8, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('mimouse', '2026', '03', NULL, NULL, 5, 8, 3, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL),
  ('mimouse', '2026', '04', NULL, NULL, NULL, NULL, 8, NULL, NULL, NULL, 5, 5, NULL, NULL, NULL, 1, NULL, 9, NULL, 5, NULL, NULL, 4, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('mimouse', '2026', '05', NULL, NULL, 5, NULL, 4, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8),
  ('mimouse', '2026', '06', 9, NULL, NULL, 2, NULL, 2, NULL, NULL, NULL, 2, 6, NULL, NULL, NULL, 4, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 1, NULL, NULL, 7, 2, NULL, NULL, NULL),
  ('mimouse', '2026', '07', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, 6, NULL, NULL, NULL, NULL, 5, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('mimouse', '2026', '08', NULL, NULL, 9, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 4, NULL, NULL, NULL, 4, 3, NULL, NULL, NULL),
  ('mimouse', '2026', '09', 5, NULL, NULL, 3, 3, 1, NULL, 8, NULL, 1, NULL, 3, NULL, NULL, NULL, NULL, 7, 1, 2, NULL, NULL, 8, 6, NULL, NULL, NULL, NULL, 5, NULL, 9, NULL),
  ('mimouse', '2026', '10', 3, NULL, 3, NULL, 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 4, 8, NULL, NULL, NULL, 9, 1, NULL, NULL, NULL, NULL, NULL, 2, 7),
  ('mimouse', '2026', '11', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 3, 9, 1, NULL, 8, NULL),
  ('mimouse', '2026', '12', NULL, NULL, 2, NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, 3, 7, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL),
  ('mmouse', '2026', '01', 5, 4, NULL, NULL, NULL, NULL, NULL, 2, 5, 9, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, 5, NULL, NULL),
  ('mmouse', '2026', '02', NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, 3, 8, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 7, 8, NULL, 8, NULL, NULL, NULL, NULL, NULL),
  ('mmouse', '2026', '03', NULL, NULL, NULL, NULL, NULL, 4, NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 8, 2, 9, NULL, 3, 5, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, 7),
  ('mmouse', '2026', '04', 5, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 9, 8, NULL, NULL, NULL, 1, NULL, NULL, 5, NULL, NULL),
  ('mmouse', '2026', '05', NULL, NULL, NULL, 4, NULL, NULL, 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, 5, 2, 2, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL),
  ('mmouse', '2026', '06', 1, NULL, 2, 7, 8, NULL, 8, NULL, 1, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, 1, NULL, NULL, 2, NULL, NULL, NULL, NULL, 7, NULL),
  ('mmouse', '2026', '07', NULL, 8, NULL, NULL, NULL, NULL, 8, 4, 9, NULL, NULL, 5, NULL, NULL, NULL, NULL, 5, 2, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL),
  ('mmouse', '2026', '08', NULL, 7, NULL, NULL, 9, NULL, NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 5, 6, NULL, NULL, NULL, NULL, NULL, NULL, 1, 3, NULL),
  ('mmouse', '2026', '09', NULL, 8, 1, 8, NULL, 1, NULL, 6, 2, NULL, NULL, NULL, 4, 8, NULL, NULL, 3, 1, 1, NULL, NULL, 7, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
  ('mmouse', '2026', '10', 3, 9, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 8, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 7, NULL, NULL, NULL),
  ('mmouse', '2026', '11', NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, NULL, 8, NULL, NULL, NULL, 2, NULL, NULL, 8, NULL, 3, 9, NULL, 5, NULL, NULL, 4, NULL, NULL),
  ('mmouse', '2026', '12', 5, 2, 7, 7, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 1, NULL, NULL, 1, 2, NULL, 8, 5, NULL, 1, NULL, NULL, NULL, 4, NULL, NULL, NULL, 1),
  ('phead', '2026', '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, NULL, 9, 1, NULL, NULL, NULL, NULL, 8, NULL, NULL),
  ('phead', '2026', '02', 9, NULL, NULL, 3, NULL, 9, NULL, 3, NULL, NULL, NULL, 4, 5, 2, 1, NULL, NULL, NULL, 8, NULL, 8, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL),
  ('phead', '2026', '03', NULL, 4, NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 7, 6, 3, NULL, NULL, NULL, 6, 9, NULL, NULL, NULL, 3, NULL, 8, NULL, NULL, NULL),
  ('phead', '2026', '04', NULL, NULL, NULL, 5, NULL, 7, 2, 5, NULL, 4, NULL, NULL, NULL, 7, 1, 6, 8, NULL, NULL, NULL, 9, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL),
  ('phead', '2026', '05', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4),
  ('phead', '2026', '06', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 3, NULL, NULL, 3, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL),
  ('phead', '2026', '07', NULL, 9, NULL, 9, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, 4, 5, 9, NULL, 7, NULL, NULL, NULL, 4, NULL, 3, NULL, NULL, 7, NULL, NULL, 1),
  ('phead', '2026', '08', NULL, NULL, NULL, NULL, 1, 9, NULL, 2, 3, NULL, 8, 4, 9, NULL, NULL, NULL, NULL, 5, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 7, 4, 4, 2),
  ('phead', '2026', '09', 1, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, 6, NULL, NULL, NULL, 8, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 2, NULL, NULL, NULL),
  ('phead', '2026', '10', NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, 9, 1, 8, 2, NULL, 7, 6, 6, 5, NULL, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 1),
  ('phead', '2026', '11', NULL, NULL, NULL, NULL, NULL, 9, 2, NULL, 8, NULL, NULL, 3, NULL, NULL, NULL, 9, 1, 6, 6, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('phead', '2026', '12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 6, 2, NULL),
  ('sgonzales', '2026', '01', NULL, NULL, NULL, NULL, 9, 5, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 7, 5, NULL, NULL, NULL, NULL, 9),
  ('sgonzales', '2026', '02', NULL, NULL, NULL, NULL, 5, 3, NULL, 5, NULL, NULL, 2, NULL, NULL, NULL, NULL, 2, 6, 1, NULL, 7, NULL, NULL, 7, NULL, NULL, 6, 1, NULL, NULL, NULL, NULL),
  ('sgonzales', '2026', '03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 7, 5, NULL, NULL, 5, 9, 8, NULL, 7, NULL, NULL, 4, NULL, NULL, 7, NULL, 2, NULL, NULL, 9, NULL, NULL),
  ('sgonzales', '2026', '04', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 9, 7, NULL, NULL, NULL, NULL, NULL, 2, 5, NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, 1, NULL, 6, NULL),
  ('sgonzales', '2026', '05', 6, NULL, 9, NULL, 1, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, 5, 4, NULL, NULL),
  ('sgonzales', '2026', '06', 9, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL, 9, NULL, 1, NULL, NULL, NULL, NULL, NULL, 7, 7, NULL),
  ('sgonzales', '2026', '07', NULL, NULL, NULL, 2, NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, 7, NULL, NULL, 8, NULL, NULL, NULL, 1, NULL, NULL, 9, NULL, 1, 4, NULL, 2, NULL, 3, NULL),
  ('sgonzales', '2026', '08', NULL, NULL, NULL, NULL, 7, NULL, 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
  ('sgonzales', '2026', '09', NULL, NULL, NULL, 7, NULL, NULL, NULL, 9, 8, NULL, 8, NULL, NULL, 8, 9, NULL, 6, NULL, NULL, 8, NULL, NULL, 2, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL),
  ('sgonzales', '2026', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 7, 4, NULL, NULL, 2, NULL, NULL, 7, NULL, NULL, 7, NULL),
  ('sgonzales', '2026', '11', NULL, 7, 2, NULL, NULL, 7, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, 6, NULL, NULL, 7, NULL, 2, 1, NULL, NULL, NULL, 3, NULL, NULL),
  ('sgonzales', '2026', '12', NULL, NULL, NULL, 4, NULL, 9, 9, NULL, NULL, 6, NULL, NULL, NULL, 2, 1, NULL, 1, NULL, 7, NULL, 8, 8, 3, NULL, NULL, NULL, 4, NULL, NULL, 7, 1),
  ('sman', '2026', '01', NULL, NULL, NULL, NULL, 7, 9, 9, NULL, 1, NULL, NULL, NULL, 3, NULL, NULL, 9, NULL, NULL, 3, NULL, 2, NULL, 3, NULL, 4, 2, 4, NULL, NULL, 6, NULL),
  ('sman', '2026', '02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 4, 4, NULL, NULL, 4, NULL, 2, NULL, 9, NULL, NULL, NULL),
  ('sman', '2026', '03', NULL, NULL, 7, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, 5, NULL, 7, NULL, 7, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL),
  ('sman', '2026', '04', 2, NULL, NULL, NULL, 5, NULL, 8, 9, NULL, NULL, NULL, NULL, NULL, 8, 1, NULL, 9, 5, 9, NULL, 1, NULL, 3, NULL, NULL, 7, NULL, NULL, 2, NULL, NULL),
  ('sman', '2026', '05', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
  ('sman', '2026', '06', NULL, 6, NULL, NULL, NULL, NULL, 3, 9, NULL, NULL, NULL, 3, NULL, 6, NULL, NULL, NULL, 3, NULL, NULL, 7, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('sman', '2026', '07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, 2, 8, 9, NULL, NULL, NULL, 1, NULL, 9, NULL, NULL, NULL, NULL, 1, 7, 7, NULL, NULL, NULL, NULL),
  ('sman', '2026', '08', 4, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, 2, NULL, NULL, NULL, 2),
  ('sman', '2026', '09', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 3, NULL, NULL, 2, NULL, NULL, 5, 2, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  ('sman', '2026', '10', NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, 9, 3, NULL, 4, 9, NULL, NULL, 5, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 7, 4, 8, NULL, NULL, NULL, NULL),
  ('sman', '2026', '11', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, 7, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 6, NULL, NULL, NULL),
  ('sman', '2026', '12', NULL, 8, NULL, NULL, 5, NULL, 9, NULL, NULL, NULL, NULL, 6, 7, NULL, 7, 4, 5, NULL, NULL, 4, 6, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 8, NULL);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_users`
--
DROP TABLE IF EXISTS `tcneo_users`;

CREATE TABLE IF NOT EXISTS `tcneo_users` (
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `order_key` varchar(80) NOT NULL DEFAULT '0',
  `role` int(11) DEFAULT 2,
  `locked` tinyint (4) DEFAULT 0,
  `hidden` tinyint (4) DEFAULT 0,
  `onhold` tinyint (4) DEFAULT 0,
  `verify` tinyint (4) DEFAULT 0,
  `bad_logins` tinyint (4) DEFAULT 0,
  `grace_start` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `last_pw_change` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '2026-01-01 00:00:00',
  PRIMARY KEY (`username`),
  KEY `k_firstname` (`firstname`),
  KEY `k_lastname` (`lastname`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_users`
--
INSERT INTO
  `tcneo_users` (`username`, `password`, `firstname`, `lastname`, `email`, `order_key`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`)
VALUES
  ('admin', '$2y$10$4E4xGXbIs1ldd.aN/knENOF/YTenqHylHhrErESXfBDIBIF/1FT2.', '', 'Admin', 'webmaster@yourserver.com', '0', 1, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:12:50', '2024-09-19 20:33:29', '2022-01-01 00:00:00'),
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

CREATE TABLE IF NOT EXISTS `tcneo_user_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `fileid` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_fileid` (`username`, `fileid`),
    KEY `k_username` (`username`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_user_attachment`
--
INSERT INTO
  `tcneo_user_attachment` (`username`, `fileid`)
VALUES
  ('admin', 10),
  ('ccarl', 10),
  ('dduck', 10),
  ('sgonzales', 10),
  ('phead', 10),
  ('blightyear', 10),
  ('mmouse', 10),
  ('mimouse', 10),
  ('sman', 10),
  ('admin', 11),
  ('ccarl', 11),
  ('dduck', 11),
  ('sgonzales', 11),
  ('phead', 11),
  ('blightyear', 11),
  ('mmouse', 11),
  ('mimouse', 11),
  ('sman', 11),
  ('admin', 12),
  ('ccarl', 12),
  ('dduck', 12),
  ('sgonzales', 12),
  ('phead', 12),
  ('blightyear', 12),
  ('mmouse', 12),
  ('mimouse', 12),
  ('sman', 12);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_user_group`
--
DROP TABLE IF EXISTS `tcneo_user_group`;

CREATE TABLE IF NOT EXISTS `tcneo_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `groupid` int(11) DEFAULT NULL,
    `type` tinytext CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `k_username` (`username`),
    KEY `k_groupid` (`groupid`)
) ENGINE = MyISAM AUTO_INCREMENT = 9 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_user_group`
--
INSERT INTO
  `tcneo_user_group` (`id`, `username`, `groupid`, `type`)
VALUES
  (1, 'mmouse', 1, 'manager'),
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

CREATE TABLE IF NOT EXISTS `tcneo_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `msgid` int(11) DEFAULT NULL,
    `popup` tinyint (4) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `k_username` (`username`),
    KEY `k_msgid` (`msgid`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_user_option`
--
DROP TABLE IF EXISTS `tcneo_user_option`;

CREATE TABLE IF NOT EXISTS `tcneo_user_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `option` varchar(40) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `value` text CHARACTER
  SET
    utf8 COLLATE utf8_general_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username_option` (`username`, `option`),
    KEY `k_username` (`username`),
    KEY `k_option` (`option`)
) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_user_option`
--
INSERT INTO
  `tcneo_user_option` (`username`, `option`, `value`)
VALUES
  ('admin', 'avatar', 'is_administrator.png'),
  ('admin', 'calendarMonths', 'default'),
  ('admin', 'calfilterAbs', 'all'),
  ('admin', 'calfilterGroup', 'all'),
  ('admin', 'calfilterRegion', '1'),
  ('admin', 'calViewMode', 'fullmonth'),
  ('admin', 'custom1', ''),
  ('admin', 'custom2', ''),
  ('admin', 'custom3', ''),
  ('admin', 'custom4', ''),
  ('admin', 'custom5', ''),
  ('admin', 'defaultMenu', 'sidebar'),
  ('admin', 'facebook', ''),
  ('admin', 'gender', 'male'),
  ('admin', 'google', ''),
  ('admin', 'id', ''),
  ('admin', 'language', 'english'),
  ('admin', 'linkedin', ''),
  ('admin', 'menuBar', 'default'),
  ('admin', 'mobile', ''),
  ('admin', 'notifyAbsenceEvents', '1'),
  ('admin', 'notifyCalendarEvents', '1'),
  ('admin', 'notifyGroupEvents', '1'),
  ('admin', 'notifyHolidayEvents', '1'),
  ('admin', 'notifyMonthEvents', '1'),
  ('admin', 'notifyNone', '0'),
  ('admin', 'notifyRoleEvents', '1'),
  ('admin', 'notifyUserCalEvents', '1'),
  ('admin', 'notifyUserCalEventsOwn', '0'),
  ('admin', 'notifyUserCalGroups', '0'),
  ('admin', 'notifyUserEvents', '1'),
  ('admin', 'phone', ''),
  ('admin', 'position', 'Administrator'),
  ('admin', 'region', '1'),
  ('admin', 'showMonths', '1'),
  ('admin', 'skype', ''),
  ('admin', 'title', ''),
  ('admin', 'twitter', ''),
  ('admin', 'verifycode', ''),
  ('admin', 'width', 'full'),
  ('blightyear', 'avatar', 'blightyear.png'),
  ('blightyear', 'calendarMonths', 'default'),
  ('blightyear', 'calfilterGroup', 'all'),
  ('blightyear', 'calViewMode', 'fullmonth'),
  ('blightyear', 'custom1', ''),
  ('blightyear', 'custom2', ''),
  ('blightyear', 'custom3', ''),
  ('blightyear', 'custom4', ''),
  ('blightyear', 'custom5', ''),
  ('blightyear', 'facebook', ''),
  ('blightyear', 'gender', 'male'),
  ('blightyear', 'google', ''),
  ('blightyear', 'id', ''),
  ('blightyear', 'language', 'default'),
  ('blightyear', 'linkedin', ''),
  ('blightyear', 'menuBar', 'default'),
  ('blightyear', 'mobile', ''),
  ('blightyear', 'notifyAbsenceEvents', '0'),
  ('blightyear', 'notifyCalendarEvents', '0'),
  ('blightyear', 'notifyGroupEvents', '0'),
  ('blightyear', 'notifyHolidayEvents', '0'),
  ('blightyear', 'notifyMonthEvents', '0'),
  ('blightyear', 'notifyNone', '1'),
  ('blightyear', 'notifyRoleEvents', '0'),
  ('blightyear', 'notifyUserCalEvents', '0'),
  ('blightyear', 'notifyUserCalEventsOwn', '0'),
  ('blightyear', 'notifyUserCalGroups', '0'),
  ('blightyear', 'notifyUserEvents', '0'),
  ('blightyear', 'phone', ''),
  ('blightyear', 'position', ''),
  ('blightyear', 'region', '1'),
  ('blightyear', 'showMonths', '1'),
  ('blightyear', 'skype', ''),
  ('blightyear', 'title', ''),
  ('blightyear', 'twitter', ''),
  ('blightyear', 'verifycode', ''),
  ('ccarl', 'avatar', 'ccarl.png'),
  ('ccarl', 'calendarMonths', 'default'),
  ('ccarl', 'calfilterGroup', 'all'),
  ('ccarl', 'calViewMode', 'fullmonth'),
  ('ccarl', 'custom1', ''),
  ('ccarl', 'custom2', ''),
  ('ccarl', 'custom3', ''),
  ('ccarl', 'custom4', ''),
  ('ccarl', 'custom5', ''),
  ('ccarl', 'facebook', 'fb-ccarl'),
  ('ccarl', 'gender', 'male'),
  ('ccarl', 'google', 'g-ccarl'),
  ('ccarl', 'id', 'ID021'),
  ('ccarl', 'language', 'english'),
  ('ccarl', 'linkedin', 'li-ccarl'),
  ('ccarl', 'menuBar', 'default'),
  ('ccarl', 'mobile', '+1 555 123 4568'),
  ('ccarl', 'notifyAbsenceEvents', '0'),
  ('ccarl', 'notifyCalendarEvents', '0'),
  ('ccarl', 'notifyGroupEvents', '0'),
  ('ccarl', 'notifyHolidayEvents', '0'),
  ('ccarl', 'notifyMonthEvents', '0'),
  ('ccarl', 'notifyNone', '1'),
  ('ccarl', 'notifyRoleEvents', '0'),
  ('ccarl', 'notifyUserCalEvents', '0'),
  ('ccarl', 'notifyUserCalEventsOwn', '0'),
  ('ccarl', 'notifyUserCalGroups', '0'),
  ('ccarl', 'notifyUserEvents', '0'),
  ('ccarl', 'phone', '+1 555 123 4567'),
  ('ccarl', 'position', 'Roadrunner Hunter'),
  ('ccarl', 'region', '1'),
  ('ccarl', 'showMonths', '1'),
  ('ccarl', 'skype', 's-ccarl'),
  ('ccarl', 'title', 'Dr.'),
  ('ccarl', 'twitter', 't-ccarl'),
  ('ccarl', 'verifycode', ''),
  ('dduck', 'avatar', 'dduck.png'),
  ('dduck', 'calendarMonths', 'default'),
  ('dduck', 'calfilterGroup', 'all'),
  ('dduck', 'calViewMode', 'fullmonth'),
  ('dduck', 'custom1', ''),
  ('dduck', 'custom2', ''),
  ('dduck', 'custom3', ''),
  ('dduck', 'custom4', ''),
  ('dduck', 'custom5', ''),
  ('dduck', 'facebook', ''),
  ('dduck', 'gender', 'male'),
  ('dduck', 'google', ''),
  ('dduck', 'id', ''),
  ('dduck', 'language', 'default'),
  ('dduck', 'linkedin', ''),
  ('dduck', 'menuBar', 'default'),
  ('dduck', 'mobile', ''),
  ('dduck', 'notifyAbsenceEvents', '0'),
  ('dduck', 'notifyCalendarEvents', '0'),
  ('dduck', 'notifyGroupEvents', '0'),
  ('dduck', 'notifyHolidayEvents', '0'),
  ('dduck', 'notifyMonthEvents', '0'),
  ('dduck', 'notifyNone', '1'),
  ('dduck', 'notifyRoleEvents', '0'),
  ('dduck', 'notifyUserCalEvents', '0'),
  ('dduck', 'notifyUserCalEventsOwn', '0'),
  ('dduck', 'notifyUserCalGroups', '0'),
  ('dduck', 'notifyUserEvents', '0'),
  ('dduck', 'phone', ''),
  ('dduck', 'position', ''),
  ('dduck', 'region', '1'),
  ('dduck', 'showMonths', '1'),
  ('dduck', 'skype', ''),
  ('dduck', 'title', ''),
  ('dduck', 'twitter', ''),
  ('dduck', 'verifycode', ''),
  ('dduck', 'width', 'full'),
  ('einstein', 'avatar', 'einstein.png'),
  ('einstein', 'calendarMonths', 'default'),
  ('einstein', 'calfilterGroup', 'all'),
  ('einstein', 'calViewMode', 'fullmonth'),
  ('einstein', 'custom1', ''),
  ('einstein', 'custom2', ''),
  ('einstein', 'custom3', ''),
  ('einstein', 'custom4', ''),
  ('einstein', 'custom5', ''),
  ('einstein', 'facebook', ''),
  ('einstein', 'gender', 'male'),
  ('einstein', 'google', ''),
  ('einstein', 'id', ''),
  ('einstein', 'language', 'default'),
  ('einstein', 'linkedin', ''),
  ('einstein', 'menuBar', 'default'),
  ('einstein', 'mobile', ''),
  ('einstein', 'notifyAbsenceEvents', '0'),
  ('einstein', 'notifyCalendarEvents', '0'),
  ('einstein', 'notifyGroupEvents', '0'),
  ('einstein', 'notifyHolidayEvents', '0'),
  ('einstein', 'notifyMonthEvents', '0'),
  ('einstein', 'notifyNone', '1'),
  ('einstein', 'notifyRoleEvents', '0'),
  ('einstein', 'notifyUserCalEvents', '0'),
  ('einstein', 'notifyUserCalEventsOwn', '0'),
  ('einstein', 'notifyUserCalGroups', '0'),
  ('einstein', 'notifyUserEvents', '0'),
  ('einstein', 'phone', ''),
  ('einstein', 'position', ''),
  ('einstein', 'region', '1'),
  ('einstein', 'showMonths', '1'),
  ('einstein', 'skype', ''),
  ('einstein', 'title', ''),
  ('einstein', 'twitter', ''),
  ('einstein', 'verifycode', ''),
  ('mimouse', 'avatar', 'mimouse.png'),
  ('mimouse', 'calendarMonths', 'default'),
  ('mimouse', 'calfilterGroup', 'all'),
  ('mimouse', 'calViewMode', 'fullmonth'),
  ('mimouse', 'custom1', ''),
  ('mimouse', 'custom2', ''),
  ('mimouse', 'custom3', ''),
  ('mimouse', 'custom4', ''),
  ('mimouse', 'custom5', ''),
  ('mimouse', 'facebook', ''),
  ('mimouse', 'gender', 'male'),
  ('mimouse', 'google', ''),
  ('mimouse', 'id', ''),
  ('mimouse', 'language', 'default'),
  ('mimouse', 'linkedin', ''),
  ('mimouse', 'menuBar', 'default'),
  ('mimouse', 'mobile', ''),
  ('mimouse', 'notifyAbsenceEvents', '0'),
  ('mimouse', 'notifyCalendarEvents', '0'),
  ('mimouse', 'notifyGroupEvents', '0'),
  ('mimouse', 'notifyHolidayEvents', '0'),
  ('mimouse', 'notifyMonthEvents', '0'),
  ('mimouse', 'notifyNone', '1'),
  ('mimouse', 'notifyRoleEvents', '0'),
  ('mimouse', 'notifyUserCalEvents', '0'),
  ('mimouse', 'notifyUserCalEventsOwn', '0'),
  ('mimouse', 'notifyUserCalGroups', '0'),
  ('mimouse', 'notifyUserEvents', '0'),
  ('mimouse', 'phone', ''),
  ('mimouse', 'position', ''),
  ('mimouse', 'region', '1'),
  ('mimouse', 'showMonths', '1'),
  ('mimouse', 'skype', ''),
  ('mimouse', 'title', ''),
  ('mimouse', 'twitter', ''),
  ('mmouse', 'avatar', 'mmouse.png'),
  ('mmouse', 'calendarMonths', 'default'),
  ('mmouse', 'calfilterGroup', 'all'),
  ('mmouse', 'calfilterMonth', '202211'),
  ('mmouse', 'calfilterRegion', '1'),
  ('mmouse', 'calViewMode', 'fullmonth'),
  ('mmouse', 'custom1', ''),
  ('mmouse', 'custom2', ''),
  ('mmouse', 'custom3', ''),
  ('mmouse', 'custom4', ''),
  ('mmouse', 'custom5', ''),
  ('mmouse', 'facebook', ''),
  ('mmouse', 'gender', 'male'),
  ('mmouse', 'google', ''),
  ('mmouse', 'id', ''),
  ('mmouse', 'language', 'english'),
  ('mmouse', 'linkedin', ''),
  ('mmouse', 'menuBar', 'default'),
  ('mmouse', 'mobile', ''),
  ('mmouse', 'notifyAbsenceEvents', '0'),
  ('mmouse', 'notifyCalendarEvents', '0'),
  ('mmouse', 'notifyGroupEvents', '0'),
  ('mmouse', 'notifyHolidayEvents', '0'),
  ('mmouse', 'notifyMonthEvents', '0'),
  ('mmouse', 'notifyNone', '1'),
  ('mmouse', 'notifyRoleEvents', '0'),
  ('mmouse', 'notifyUserCalEvents', '0'),
  ('mmouse', 'notifyUserCalEventsOwn', '0'),
  ('mmouse', 'notifyUserCalGroups', '0'),
  ('mmouse', 'notifyUserEvents', '0'),
  ('mmouse', 'phone', ''),
  ('mmouse', 'position', ''),
  ('mmouse', 'region', '1'),
  ('mmouse', 'showMonths', '1'),
  ('mmouse', 'skype', ''),
  ('mmouse', 'title', ''),
  ('mmouse', 'twitter', ''),
  ('mmouse', 'verifycode', ''),
  ('mmouse', 'width', 'full'),
  ('phead', 'avatar', 'phead.png'),
  ('phead', 'calendarMonths', 'default'),
  ('phead', 'calfilterGroup', 'all'),
  ('phead', 'calViewMode', 'fullmonth'),
  ('phead', 'custom1', ''),
  ('phead', 'custom2', ''),
  ('phead', 'custom3', ''),
  ('phead', 'custom4', ''),
  ('phead', 'custom5', ''),
  ('phead', 'facebook', ''),
  ('phead', 'gender', 'male'),
  ('phead', 'google', ''),
  ('phead', 'id', ''),
  ('phead', 'language', 'default'),
  ('phead', 'linkedin', ''),
  ('phead', 'menuBar', 'default'),
  ('phead', 'mobile', ''),
  ('phead', 'notifyAbsenceEvents', '0'),
  ('phead', 'notifyCalendarEvents', '0'),
  ('phead', 'notifyGroupEvents', '0'),
  ('phead', 'notifyHolidayEvents', '0'),
  ('phead', 'notifyMonthEvents', '0'),
  ('phead', 'notifyNone', '1'),
  ('phead', 'notifyRoleEvents', '0'),
  ('phead', 'notifyUserCalEvents', '0'),
  ('phead', 'notifyUserCalEventsOwn', '0'),
  ('phead', 'notifyUserCalGroups', '0'),
  ('phead', 'notifyUserEvents', '0'),
  ('phead', 'phone', ''),
  ('phead', 'position', ''),
  ('phead', 'region', '1'),
  ('phead', 'showMonths', '1'),
  ('phead', 'skype', ''),
  ('phead', 'title', ''),
  ('phead', 'twitter', ''),
  ('phead', 'verifycode', ''),
  ('sgonzales', 'avatar', 'sgonzales.png'),
  ('sgonzales', 'calendarMonths', 'default'),
  ('sgonzales', 'calfilterGroup', 'all'),
  ('sgonzales', 'calViewMode', 'fullmonth'),
  ('sgonzales', 'custom1', ''),
  ('sgonzales', 'custom2', ''),
  ('sgonzales', 'custom3', ''),
  ('sgonzales', 'custom4', ''),
  ('sgonzales', 'custom5', ''),
  ('sgonzales', 'facebook', ''),
  ('sgonzales', 'gender', 'male'),
  ('sgonzales', 'google', ''),
  ('sgonzales', 'id', ''),
  ('sgonzales', 'language', 'default'),
  ('sgonzales', 'linkedin', ''),
  ('sgonzales', 'menuBar', 'default'),
  ('sgonzales', 'mobile', ''),
  ('sgonzales', 'notifyAbsenceEvents', '0'),
  ('sgonzales', 'notifyCalendarEvents', '0'),
  ('sgonzales', 'notifyGroupEvents', '0'),
  ('sgonzales', 'notifyHolidayEvents', '0'),
  ('sgonzales', 'notifyMonthEvents', '0'),
  ('sgonzales', 'notifyNone', '1'),
  ('sgonzales', 'notifyRoleEvents', '0'),
  ('sgonzales', 'notifyUserCalEvents', '0'),
  ('sgonzales', 'notifyUserCalEventsOwn', '0'),
  ('sgonzales', 'notifyUserCalGroups', '0'),
  ('sgonzales', 'notifyUserEvents', '0'),
  ('sgonzales', 'phone', ''),
  ('sgonzales', 'position', ''),
  ('sgonzales', 'region', '1'),
  ('sgonzales', 'showMonths', '1'),
  ('sgonzales', 'skype', ''),
  ('sgonzales', 'title', ''),
  ('sgonzales', 'twitter', ''),
  ('sgonzales', 'verifycode', ''),
  ('sman', 'avatar', 'sman.png'),
  ('sman', 'calendarMonths', 'default'),
  ('sman', 'calfilterGroup', 'all'),
  ('sman', 'calViewMode', 'fullmonth'),
  ('sman', 'custom1', ''),
  ('sman', 'custom2', ''),
  ('sman', 'custom3', ''),
  ('sman', 'custom4', ''),
  ('sman', 'custom5', ''),
  ('sman', 'facebook', ''),
  ('sman', 'gender', 'male'),
  ('sman', 'google', ''),
  ('sman', 'id', ''),
  ('sman', 'language', 'default'),
  ('sman', 'linkedin', ''),
  ('sman', 'menuBar', 'default'),
  ('sman', 'mobile', ''),
  ('sman', 'notifyAbsenceEvents', '0'),
  ('sman', 'notifyCalendarEvents', '0'),
  ('sman', 'notifyGroupEvents', '0'),
  ('sman', 'notifyHolidayEvents', '0'),
  ('sman', 'notifyMonthEvents', '0'),
  ('sman', 'notifyNone', '1'),
  ('sman', 'notifyRoleEvents', '0'),
  ('sman', 'notifyUserCalEvents', '0'),
  ('sman', 'notifyUserCalEventsOwn', '0'),
  ('sman', 'notifyUserCalGroups', '0'),
  ('sman', 'notifyUserEvents', '0'),
  ('sman', 'phone', ''),
  ('sman', 'position', ''),
  ('sman', 'region', '1'),
  ('sman', 'showMonths', '1'),
  ('sman', 'skype', ''),
  ('sman', 'title', ''),
  ('sman', 'twitter', ''),
  ('sman', 'verifycode', '');

--
-- tcneo_log
-- Filter by type, user, timestamp and sort by timestamp are frequent operations
--
ALTER TABLE `tcneo_log` ADD INDEX `idx_log_timestamp` (`timestamp`);

ALTER TABLE `tcneo_log` ADD INDEX `idx_log_user` (`user`);

ALTER TABLE `tcneo_log` ADD INDEX `idx_log_type` (`type`);

--
-- tcneo_users
-- Optimize sorting by Name (lastname, firstname) which is the default sort
-- Optimize sorting by Name within Role
--
ALTER TABLE `tcneo_users` ADD INDEX `idx_lastname_firstname` (`lastname`, `firstname`);

ALTER TABLE `tcneo_users` ADD INDEX `idx_role_lastname_firstname` (`role`, `lastname`, `firstname`);

--
-- tcneo_user_group
-- Optimize retrieving group members sorted by username
--
ALTER TABLE `tcneo_user_group` ADD INDEX `idx_groupid_username` (`groupid`, `username`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
