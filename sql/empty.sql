-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2015 at 10:05 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tcneo`
--

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

DROP TABLE IF EXISTS `absences`;
CREATE TABLE `absences` (
  `id` int(11) NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `symbol` char(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A',
  `icon` varchar(40) CHARACTER SET utf8 NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 NOT NULL,
  `bgcolor` varchar(6) CHARACTER SET utf8 NOT NULL,
  `bgtrans` tinyint(1) NOT NULL DEFAULT '0',
  `factor` float NOT NULL,
  `allowance` float NOT NULL,
  `counts_as` int(11) NOT NULL,
  `show_in_remainder` tinyint(1) NOT NULL,
  `show_totals` tinyint(1) NOT NULL,
  `approval_required` tinyint(1) NOT NULL,
  `counts_as_present` tinyint(1) NOT NULL,
  `manager_only` tinyint(1) NOT NULL,
  `hide_in_profile` tinyint(1) NOT NULL,
  `confidential` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `absence_group`
--

DROP TABLE IF EXISTS `absence_group`;
CREATE TABLE `absence_group` (
  `id` int(11) NOT NULL,
  `absid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `allowances`
--

DROP TABLE IF EXISTS `allowances`;
CREATE TABLE `allowances` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL,
  `absid` int(11) NOT NULL,
  `lastyear` smallint(6) DEFAULT '0',
  `curryear` smallint(6) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_allowances`
--

DROP TABLE IF EXISTS `archive_allowances`;
CREATE TABLE `archive_allowances` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL,
  `absid` int(11) NOT NULL,
  `lastyear` smallint(6) DEFAULT '0',
  `curryear` smallint(6) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_daynotes`
--

DROP TABLE IF EXISTS `archive_daynotes`;
CREATE TABLE `archive_daynotes` (
  `id` int(11) NOT NULL,
  `yyyymmdd` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `daynote` text CHARACTER SET utf8,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'all',
  `region` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'default'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_templates`
--

DROP TABLE IF EXISTS `archive_templates`;
CREATE TABLE `archive_templates` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `year` varchar(4) CHARACTER SET utf8 DEFAULT NULL,
  `month` char(2) CHARACTER SET utf8 DEFAULT NULL,
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
  `abs31` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_users`
--

DROP TABLE IF EXISTS `archive_users`;
CREATE TABLE `archive_users` (
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `password` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `firstname` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `role` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `locked` tinyint(4) DEFAULT '0',
  `hidden` tinyint(4) DEFAULT '0',
  `onhold` tinyint(4) DEFAULT '0',
  `verify` tinyint(4) DEFAULT '0',
  `bad_logins` tinyint(4) DEFAULT '0',
  `grace_start` datetime DEFAULT NULL,
  `last_pw_change` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_group`
--

DROP TABLE IF EXISTS `archive_user_group`;
CREATE TABLE `archive_user_group` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_message`
--

DROP TABLE IF EXISTS `archive_user_message`;
CREATE TABLE `archive_user_message` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_option`
--

DROP TABLE IF EXISTS `archive_user_option`;
CREATE TABLE `archive_user_option` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `option` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `value` text CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'theme', 'bootstrap'),
(2, 'defaultLanguage', 'english'),
(3, 'permissionScheme', 'Default'),
(4, 'appTitle', 'TeamCal Neo'),
(5, 'appFooterCpy', 'Lewe.com'),
(6, 'allowUserTheme', '1'),
(7, 'logLanguage', 'english'),
(8, 'showAlerts', 'all'),
(9, 'activateMessages', '0'),
(10, 'homepage', 'home'),
(11, 'welcomeIcon', 'None'),
(12, 'welcomeText', '<h1><img alt="" src="upload/images/logo-128.png" style="float:left; height:128px; margin-bottom:12px; margin-right:12px; width:128px" />Welcome to TeamCal Neo</h1>\r\n\r\n<p>TeamCal Neo, successor to TeamCal Pro, has been completely re-written as a responsive web application based on HTML5 and CSS3. This demo release serves basic demonstration purposes only.</p>\r\n\r\n<p>Select Login from the User menu to login and use the following accounts to test:</p>\r\n\r\n<h2>Admin account:</h2>\r\n\r\n<p>admin/root</p>\r\n\r\n<h2>User accounts:</h2>\r\n\r\n<p>ccarl/password<br />\r\nblightyear/password<br />\r\ndduck/password<br />\r\nsgonzalez/password<br />\r\nphead/password<br />\r\nmmouse/password<br />\r\nmimouse/password<br />\r\nsman/password</p>\r\n'),
(13, 'welcomeTitle', 'Welcome To TeamCal Neo'),
(14, 'userCustom1', ''),
(15, 'userCustom2', ''),
(16, 'userCustom3', ''),
(17, 'userCustom4', ''),
(18, 'userCustom5', ''),
(19, 'emailNotifications', '1'),
(20, 'emailNoPastNotifications', '0'),
(21, 'mailFrom', 'webmaster@mysite.com'),
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
(37, 'jqtheme', 'black-tie'),
(38, 'debugHide', '0'),
(39, 'timeZone', 'Europe/Berlin'),
(40, 'googleAnalytics', '0'),
(41, 'googleAnalyticsID', ''),
(42, 'underMaintenance', '0'),
(43, 'showUserIcons', '1'),
(44, 'showAvatars', '1'),
(45, 'appURL', '#'),
(46, 'appDescription', ''),
(47, 'showBanner', '0'),
(48, 'menuBarInverse', '1'),
(49, 'showSize', '0'),
(50, 'faCDN', '0'),
(51, 'logperiod', 'curr_all'),
(52, 'logfrom', '2015-01-01 00:00:00.000000'),
(53, 'logto', '2015-12-29 23:59:59.999999'),
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
(87, 'pastDayColor', 'BABABA'),
(88, 'showWeekNumbers', '1'),
(89, 'repeatHeaderCount', '0'),
(90, 'usersPerPage', '0'),
(91, 'userSearch', '1'),
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
(106, 'includeSummary', '1'),
(107, 'showSummary', '1'),
(108, 'supportMobile', '0'),
(109, 'logfilterCalendar', '1'),
(110, 'logfilterCalendar Options', '0'),
(111, 'logCalendarOptions', '1'),
(112, 'logfilterCalendarOptions', '1'),
(113, 'declThreshold', '40'),
(114, 'declBase', 'group'),
(115, 'declBeforeOption', 'date'),
(116, 'declBeforeDate', '2015-02-04'),
(117, 'statsBaseColor1', '96,96,255'),
(118, 'statsBaseColor2', '96,200,255'),
(119, 'statsBaseColor3', '96,192,96'),
(120, 'statsBaseColor4', '200,96,200'),
(121, 'statsBaseColor5', '255,179,0'),
(122, 'statsBaseColor6', '255,96,96'),
(123, 'statsScale', 'smart'),
(124, 'statsSmartValue', '2'),
(125, 'dbURL', ''),
(126, 'logcolorConfig', 'danger'),
(127, 'logcolorDatabase', 'warning'),
(128, 'logcolorGroup', 'primary'),
(129, 'logcolorLogin', 'success'),
(130, 'logcolorLog', 'default'),
(131, 'logMessage', '0'),
(132, 'logfilterMessage', '0'),
(133, 'logcolorMessage', 'primary'),
(134, 'logcolorPermission', 'warning'),
(135, 'logRegistration', '1'),
(136, 'logfilterRegistration', '1'),
(137, 'logcolorRegistration', 'success'),
(138, 'logcolorRole', 'primary'),
(139, 'logUpload', '1'),
(140, 'logfilterUpload', '1'),
(141, 'logcolorUpload', 'primary'),
(142, 'logcolorUser', 'primary'),
(143, 'logcolorCalendarOptions', 'danger');

-- --------------------------------------------------------

--
-- Table structure for table `daynotes`
--

DROP TABLE IF EXISTS `daynotes`;
CREATE TABLE `daynotes` (
  `id` int(11) NOT NULL,
  `yyyymmdd` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `daynote` text CHARACTER SET utf8,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'all',
  `region` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'default'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `color` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT '000000',
  `bgcolor` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT 'ffffff',
  `businessday` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `description`, `color`, `bgcolor`, `businessday`) VALUES
(1, 'Business Day', 'Regular business day', '000000', 'ffffff', 0),
(2, 'Saturday', 'Regular weekend day (Saturday)', '000000', 'fcfc9a', 0),
(3, 'Sunday', 'Regular weekend day (Sunday)', '000000', 'fcfc9a', 0);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `type` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `event` text CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text CHARACTER SET utf8 NOT NULL,
  `type` varchar(8) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `months`;
CREATE TABLE `months` (
  `id` int(11) NOT NULL,
  `year` varchar(4) CHARACTER SET utf8 DEFAULT NULL,
  `month` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `region` int(11) DEFAULT '1',
  `wday1` tinyint(1) DEFAULT NULL,
  `wday2` tinyint(1) DEFAULT NULL,
  `wday3` tinyint(1) DEFAULT NULL,
  `wday4` tinyint(1) DEFAULT NULL,
  `wday5` tinyint(1) DEFAULT NULL,
  `wday6` tinyint(1) DEFAULT NULL,
  `wday7` tinyint(1) DEFAULT NULL,
  `wday8` tinyint(1) DEFAULT NULL,
  `wday9` tinyint(1) DEFAULT NULL,
  `wday10` tinyint(1) DEFAULT NULL,
  `wday11` tinyint(1) DEFAULT NULL,
  `wday12` tinyint(1) DEFAULT NULL,
  `wday13` tinyint(1) DEFAULT NULL,
  `wday14` tinyint(1) DEFAULT NULL,
  `wday15` tinyint(1) DEFAULT NULL,
  `wday16` tinyint(1) DEFAULT NULL,
  `wday17` tinyint(1) DEFAULT NULL,
  `wday18` tinyint(1) DEFAULT NULL,
  `wday19` tinyint(1) DEFAULT NULL,
  `wday20` tinyint(1) DEFAULT NULL,
  `wday21` tinyint(1) DEFAULT NULL,
  `wday22` tinyint(1) DEFAULT NULL,
  `wday23` tinyint(1) DEFAULT NULL,
  `wday24` tinyint(1) DEFAULT NULL,
  `wday25` tinyint(1) DEFAULT NULL,
  `wday26` tinyint(1) DEFAULT NULL,
  `wday27` tinyint(1) DEFAULT NULL,
  `wday28` tinyint(1) DEFAULT NULL,
  `wday29` tinyint(1) DEFAULT NULL,
  `wday30` tinyint(1) DEFAULT NULL,
  `wday31` tinyint(1) DEFAULT NULL,
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
  `hol31` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`id`, `year`, `month`, `region`, `wday1`, `wday2`, `wday3`, `wday4`, `wday5`, `wday6`, `wday7`, `wday8`, `wday9`, `wday10`, `wday11`, `wday12`, `wday13`, `wday14`, `wday15`, `wday16`, `wday17`, `wday18`, `wday19`, `wday20`, `wday21`, `wday22`, `wday23`, `wday24`, `wday25`, `wday26`, `wday27`, `wday28`, `wday29`, `wday30`, `wday31`, `week1`, `week2`, `week3`, `week4`, `week5`, `week6`, `week7`, `week8`, `week9`, `week10`, `week11`, `week12`, `week13`, `week14`, `week15`, `week16`, `week17`, `week18`, `week19`, `week20`, `week21`, `week22`, `week23`, `week24`, `week25`, `week26`, `week27`, `week28`, `week29`, `week30`, `week31`, `hol1`, `hol2`, `hol3`, `hol4`, `hol5`, `hol6`, `hol7`, `hol8`, `hol9`, `hol10`, `hol11`, `hol12`, `hol13`, `hol14`, `hol15`, `hol16`, `hol17`, `hol18`, `hol19`, `hol20`, `hol21`, `hol22`, `hol23`, `hol24`, `hol25`, `hol26`, `hol27`, `hol28`, `hol29`, `hol30`, `hol31`) VALUES
(1, '2014', '12', 1, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 49, 49, 49, 49, 49, 49, 49, 50, 50, 50, 50, 50, 50, 50, 51, 51, 51, 51, 51, 51, 51, 52, 52, 52, 52, 52, 52, 52, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 5, 5, 4, 4, 0, 0, 5, 5, 5),
(2, '2015', '01', 1, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, '2015', '02', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 0, 0, 0, 5, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8, 9, 9, 9, 9, 9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, '2015', '03', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 9, 10, 10, 10, 10, 10, 10, 10, 11, 11, 11, 11, 11, 11, 11, 12, 12, 12, 12, 12, 12, 12, 13, 13, 13, 13, 13, 13, 13, 14, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, '2015', '04', 1, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 0, 14, 14, 14, 14, 14, 15, 15, 15, 15, 15, 15, 15, 16, 16, 16, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, '2015', '05', 1, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, '2015', '06', 1, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 0, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 25, 25, 25, 25, 25, 25, 25, 26, 26, 26, 26, 26, 26, 26, 27, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, '2015', '07', 1, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 27, 27, 27, 27, 27, 28, 28, 28, 28, 28, 28, 28, 29, 29, 29, 29, 29, 29, 29, 30, 30, 30, 30, 30, 30, 30, 31, 31, 31, 31, 31, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, '2015', '08', 1, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 31, 31, 32, 32, 32, 32, 32, 32, 32, 33, 33, 33, 33, 33, 33, 33, 34, 34, 34, 34, 34, 34, 34, 35, 35, 35, 35, 35, 35, 35, 36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, '2015', '09', 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 0, 36, 36, 36, 36, 36, 36, 37, 37, 37, 37, 37, 37, 37, 38, 38, 38, 38, 38, 38, 38, 39, 39, 39, 39, 39, 39, 39, 40, 40, 40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, '2015', '10', 1, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 40, 40, 40, 40, 41, 41, 41, 41, 41, 41, 41, 42, 42, 42, 42, 42, 42, 42, 43, 43, 43, 43, 43, 43, 43, 44, 44, 44, 44, 44, 44, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, '2015', '11', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 0, 44, 45, 45, 45, 45, 45, 45, 45, 46, 46, 46, 46, 46, 46, 46, 47, 47, 47, 47, 47, 47, 47, 48, 48, 48, 48, 48, 48, 48, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, '2015', '11', 3, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 0, 44, 45, 45, 45, 45, 45, 45, 45, 46, 46, 46, 46, 46, 46, 46, 47, 47, 47, 47, 47, 47, 47, 48, 48, 48, 48, 48, 48, 48, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, '2015', '12', 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 49, 49, 49, 49, 49, 49, 50, 50, 50, 50, 50, 50, 50, 51, 51, 51, 51, 51, 51, 51, 52, 52, 52, 52, 52, 52, 52, 53, 53, 53, 53, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `scheme` varchar(80) CHARACTER SET utf8 NOT NULL,
  `permission` varchar(40) CHARACTER SET utf8 NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `allowed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`scheme`, `permission`, `role`, `allowed`) VALUES
('Default', 'calendareditall', 1, 1),
('Default', 'calendareditgroup', 2, 0),
('Default', 'calendareditgroup', 3, 0),
('Default', 'calendareditgroup', 4, 0),
('Default', 'calendareditgroup', 1, 1),
('Default', 'calendareditown', 2, 0),
('Default', 'calendareditown', 3, 0),
('Default', 'calendareditown', 4, 0),
('Default', 'calendareditown', 1, 1),
('Default', 'statistics', 2, 0),
('Default', 'statistics', 3, 0),
('Default', 'statistics', 4, 1),
('Default', 'statistics', 1, 1),
('Default', 'regions', 2, 0),
('Default', 'regions', 3, 0),
('Default', 'regions', 4, 0),
('Default', 'regions', 1, 1),
('Default', 'holidays', 2, 0),
('Default', 'holidays', 3, 0),
('Default', 'holidays', 4, 0),
('Default', 'holidays', 1, 1),
('Default', 'declination', 2, 0),
('Default', 'declination', 3, 0),
('Default', 'declination', 4, 1),
('Default', 'declination', 1, 1),
('Default', 'calendaroptions', 2, 0),
('Default', 'calendaroptions', 3, 0),
('Default', 'calendaroptions', 4, 0),
('Default', 'calendaroptions', 1, 1),
('Default', 'calendaredit', 2, 0),
('Default', 'calendaredit', 3, 0),
('Default', 'calendaredit', 4, 0),
('Default', 'calendaredit', 1, 1),
('Default', 'calendarview', 2, 1),
('Default', 'calendarview', 3, 1),
('Default', 'calendarview', 4, 1),
('Default', 'calendarview', 1, 1),
('Default', 'absenceedit', 2, 0),
('Default', 'absenceedit', 3, 0),
('Default', 'absenceedit', 4, 0),
('Default', 'absenceedit', 1, 1),
('Default', 'viewprofile', 2, 1),
('Default', 'viewprofile', 3, 0),
('Default', 'viewprofile', 4, 1),
('Default', 'viewprofile', 1, 1),
('Default', 'upload', 2, 0),
('Default', 'upload', 3, 0),
('Default', 'upload', 4, 1),
('Default', 'upload', 1, 1),
('Default', 'roles', 2, 0),
('Default', 'roles', 3, 0),
('Default', 'roles', 4, 0),
('Default', 'roles', 1, 1),
('Default', 'messageedit', 2, 1),
('Default', 'messageedit', 3, 0),
('Default', 'messageedit', 4, 1),
('Default', 'messageedit', 1, 1),
('Default', 'messageview', 2, 0),
('Default', 'messageview', 3, 0),
('Default', 'messageview', 4, 0),
('Default', 'messageview', 1, 1),
('Default', 'groups', 2, 0),
('Default', 'groups', 3, 0),
('Default', 'groups', 4, 0),
('Default', 'groups', 1, 1),
('Default', 'admin', 2, 0),
('Default', 'admin', 3, 0),
('Default', 'admin', 4, 0),
('Default', 'admin', 1, 1),
('Default', 'calendareditall', 4, 0),
('Default', 'calendareditall', 3, 0),
('Default', 'calendareditall', 2, 0),
('Default', 'calendarviewgroup', 1, 1),
('Default', 'calendarviewgroup', 4, 0),
('Default', 'calendarviewgroup', 3, 0),
('Default', 'calendarviewgroup', 2, 0),
('Default', 'calendarviewall', 1, 1),
('Default', 'calendarviewall', 4, 0),
('Default', 'calendarviewall', 3, 0),
('Default', 'calendarviewall', 2, 0),
('Default', 'useraccount', 1, 1),
('Default', 'useraccount', 4, 0),
('Default', 'useraccount', 3, 0),
('Default', 'useraccount', 2, 0),
('Default', 'groupmemberships', 1, 1),
('Default', 'groupmemberships', 4, 0),
('Default', 'groupmemberships', 3, 0),
('Default', 'groupmemberships', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `description`) VALUES
(1, 'Default', 'Default Region');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `color` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'default'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `color`) VALUES
(1, 'Administrator', 'Application administrator', 'danger'),
(2, 'User', 'Standard role for logged in users', 'primary'),
(3, 'Public', 'All users not logged in', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `year` varchar(4) CHARACTER SET utf8 DEFAULT NULL,
  `month` char(2) CHARACTER SET utf8 DEFAULT NULL,
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
  `abs31` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `password` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `firstname` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `role` int(11) DEFAULT '2',
  `locked` tinyint(4) DEFAULT '0',
  `hidden` tinyint(4) DEFAULT '0',
  `onhold` tinyint(4) DEFAULT '0',
  `verify` tinyint(4) DEFAULT '0',
  `bad_logins` tinyint(4) DEFAULT '0',
  `grace_start` datetime DEFAULT NULL,
  `last_pw_change` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `firstname`, `lastname`, `email`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`) VALUES
('admin', 's77dWZwOIYXss', 'George', 'Lewe-Admin', 'webmaster@yourserver.com', 1, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2014-04-23 19:01:57', '2015-12-29 18:18:43', '2006-10-06 20:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

DROP TABLE IF EXISTS `user_message`;
CREATE TABLE `user_message` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_option`
--

DROP TABLE IF EXISTS `user_option`;
CREATE TABLE `user_option` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `option` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `value` text CHARACTER SET utf8
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_option`
--

INSERT INTO `user_option` (`id`, `username`, `option`, `value`) VALUES
(1, 'admin', 'title', ''),
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
(12, 'admin', 'theme', 'default'),
(13, 'admin', 'language', 'english'),
(14, 'admin', 'avatar', 'is_administrator.png'),
(15, 'admin', 'menuBarInverse', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `absence_group`
--
ALTER TABLE `absence_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowances`
--
ALTER TABLE `allowances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archive_allowances`
--
ALTER TABLE `archive_allowances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archive_daynotes`
--
ALTER TABLE `archive_daynotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yyyymmdd` (`yyyymmdd`);

--
-- Indexes for table `archive_templates`
--
ALTER TABLE `archive_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template` (`username`,`year`,`month`);

--
-- Indexes for table `archive_users`
--
ALTER TABLE `archive_users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `archive_user_group`
--
ALTER TABLE `archive_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archive_user_message`
--
ALTER TABLE `archive_user_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archive_user_option`
--
ALTER TABLE `archive_user_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `daynotes`
--
ALTER TABLE `daynotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yyyymmdd` (`yyyymmdd`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_occured` (`timestamp`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template` (`year`,`month`,`region`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`scheme`,`permission`,`role`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template` (`username`,`year`,`month`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_message`
--
ALTER TABLE `user_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_option`
--
ALTER TABLE `user_option`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `absence_group`
--
ALTER TABLE `absence_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `allowances`
--
ALTER TABLE `allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive_allowances`
--
ALTER TABLE `archive_allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive_daynotes`
--
ALTER TABLE `archive_daynotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive_templates`
--
ALTER TABLE `archive_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive_user_group`
--
ALTER TABLE `archive_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive_user_message`
--
ALTER TABLE `archive_user_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive_user_option`
--
ALTER TABLE `archive_user_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- AUTO_INCREMENT for table `daynotes`
--
ALTER TABLE `daynotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `user_message`
--
ALTER TABLE `user_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_option`
--
ALTER TABLE `user_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
  
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
