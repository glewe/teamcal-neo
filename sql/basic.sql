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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_absence_group`
--
DROP TABLE IF EXISTS `tcneo_absence_group`;

CREATE TABLE IF NOT EXISTS `tcneo_absence_group` (`id` int(11) NOT NULL AUTO_INCREMENT, `absid` int(11) DEFAULT NULL, `groupid` int(11) DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `absgroup` (`absid`, `groupid`), KEY `k_absid` (`absid`), KEY `k_groupid` (`groupid`)) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
    `avatar` varchar(255) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default_group.png',
    `minpresent` smallint(6) NOT NULL DEFAULT 0,
    `maxabsent` smallint(6) NOT NULL DEFAULT 9999,
    `minpresentwe` smallint(6) NOT NULL DEFAULT 0,
    `maxabsentwe` smallint(6) NOT NULL DEFAULT 9999,
    PRIMARY KEY (`id`),
    KEY `k_name` (`name`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_regions`
--
INSERT INTO
  `tcneo_regions` (`id`, `name`, `description`)
VALUES
  (1, 'Default', 'Default Region');

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
) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

--
-- Dumping data for table `tcneo_roles`
--
INSERT INTO
  `tcneo_roles` (`id`, `name`, `description`, `color`, `created`, `updated`)
VALUES
  (1, 'Administrator', 'Application administrator', 'danger', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (2, 'User', 'Standard role for logged in users', 'primary', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (3, 'Public', 'All users not logged in', 'secondary', '2026-02-01 18:11:39', '2026-02-01 18:11:39');

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
  ('admin', '$2y$10$4E4xGXbIs1ldd.aN/knENOF/YTenqHylHhrErESXfBDIBIF/1FT2.', '', 'Admin', 'webmaster@yourserver.com', '0', 1, 0, 0, 0, 0, 0, '2024-01-01 00:00:00', '2024-09-07 19:12:50', '2024-09-19 20:33:29', '2022-01-01 00:00:00');

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

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
  ('admin', 'width', 'full');

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