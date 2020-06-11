/**
 * Sample MySQL Database
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Database
 * @since 3.0.0
 */

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_absences`
--

DROP TABLE IF EXISTS `tcneo_absences`;
CREATE TABLE `tcneo_absences` (
  `id` int(11) NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `symbol` char(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A',
  `icon` varchar(40) CHARACTER SET utf8 NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 NOT NULL,
  `bgcolor` varchar(6) CHARACTER SET utf8 NOT NULL,
  `bgtrans` tinyint(1) NOT NULL DEFAULT 0,
  `factor` float NOT NULL,
  `allowance` float NOT NULL,
  `allowmonth` float NOT NULL,
  `allowweek` float NOT NULL,
  `counts_as` int(11) NOT NULL,
  `show_in_remainder` tinyint(1) NOT NULL,
  `show_totals` tinyint(1) NOT NULL,
  `approval_required` tinyint(1) NOT NULL,
  `counts_as_present` tinyint(1) NOT NULL,
  `manager_only` tinyint(1) NOT NULL,
  `hide_in_profile` tinyint(1) NOT NULL,
  `confidential` tinyint(1) NOT NULL,
  `takeover` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_absences`
--

INSERT INTO `tcneo_absences` (`id`, `name`, `symbol`, `icon`, `color`, `bgcolor`, `bgtrans`, `factor`, `allowance`, `allowmonth`, `allowweek`, `counts_as`, `show_in_remainder`, `show_totals`, `approval_required`, `counts_as_present`, `manager_only`, `hide_in_profile`, `confidential`, `takeover`) VALUES
(1, 'Vacation', 'V', 'fas fa-umbrella-beach', 'FFEE00', 'FC3737', 0, 1, 20, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0),
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
CREATE TABLE `tcneo_absence_group` (
  `id` int(11) NOT NULL,
  `absid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_absence_group`
--

INSERT INTO `tcneo_absence_group` (`id`, `absid`, `groupid`) VALUES
(1, 3, 4),
(2, 3, 2),
(3, 3, 8),
(4, 13, 8),
(5, 3, 3),
(6, 4, 4),
(7, 13, 3),
(8, 5, 4),
(9, 5, 2),
(10, 5, 3),
(11, 6, 4),
(12, 6, 2),
(13, 2, 4),
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
(30, 2, 1),
(31, 13, 2),
(32, 13, 4),
(33, 4, 2),
(34, 4, 3),
(35, 4, 1),
(36, 5, 1),
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
CREATE TABLE `tcneo_allowances` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL,
  `absid` int(11) NOT NULL,
  `carryover` smallint(6) DEFAULT 0,
  `allowance` smallint(6) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_allowances`
--

INSERT INTO `tcneo_allowances` (`id`, `username`, `absid`, `carryover`, `allowance`) VALUES
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
(18, 'ccarl', 1, 0, 20);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_allowances`
--

DROP TABLE IF EXISTS `tcneo_archive_allowances`;
CREATE TABLE `tcneo_archive_allowances` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL,
  `absid` int(11) NOT NULL,
  `carryover` smallint(6) DEFAULT 0,
  `allowance` smallint(6) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_daynotes`
--

DROP TABLE IF EXISTS `tcneo_archive_daynotes`;
CREATE TABLE `tcneo_archive_daynotes` (
  `id` int(11) NOT NULL,
  `yyyymmdd` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'all',
  `region` int(11) NOT NULL DEFAULT 1,
  `daynote` text CHARACTER SET utf8 DEFAULT NULL,
  `color` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT 'default',
  `confidential` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_templates`
--

DROP TABLE IF EXISTS `tcneo_archive_templates`;
CREATE TABLE `tcneo_archive_templates` (
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
-- Table structure for table `tcneo_archive_users`
--

DROP TABLE IF EXISTS `tcneo_archive_users`;
CREATE TABLE `tcneo_archive_users` (
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `password` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `firstname` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `role` int(11) DEFAULT 2,
  `locked` tinyint(4) DEFAULT 0,
  `hidden` tinyint(4) DEFAULT 0,
  `onhold` tinyint(4) DEFAULT 0,
  `verify` tinyint(4) DEFAULT 0,
  `bad_logins` tinyint(4) DEFAULT 0,
  `grace_start` datetime DEFAULT NULL,
  `last_pw_change` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_attachment`
--

DROP TABLE IF EXISTS `tcneo_archive_user_attachment`;
CREATE TABLE `tcneo_archive_user_attachment` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `fileid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_group`
--

DROP TABLE IF EXISTS `tcneo_archive_user_group`;
CREATE TABLE `tcneo_archive_user_group` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_message`
--

DROP TABLE IF EXISTS `tcneo_archive_user_message`;
CREATE TABLE `tcneo_archive_user_message` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_archive_user_option`
--

DROP TABLE IF EXISTS `tcneo_archive_user_option`;
CREATE TABLE `tcneo_archive_user_option` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `option` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `value` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_attachments`
--

DROP TABLE IF EXISTS `tcneo_attachments`;
CREATE TABLE `tcneo_attachments` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `uploader` varchar(40) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_attachments`
--

INSERT INTO `tcneo_attachments` (`id`, `filename`, `uploader`) VALUES
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
CREATE TABLE `tcneo_config` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_config`
--

INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES
(1, 'theme', 'bootstrap'),
(2, 'defaultLanguage', 'english'),
(3, 'permissionScheme', 'Default'),
(4, 'appTitle', 'TeamCal Neo'),
(5, 'footerCopyright', 'Lewe.com'),
(6, 'allowUserTheme', '1'),
(7, 'logLanguage', 'english'),
(8, 'showAlerts', 'all'),
(9, 'activateMessages', '1'),
(10, 'homepage', 'home'),
(11, 'welcomeIcon', 'None'),
(12, 'welcomeText', '<h3><img alt=\"\" src=\"upload/files/logo-128.png\" style=\"float:left; height:128px; margin-bottom:24px; margin-right:24px; width:128px\" />Welcome to TeamCal Neo 3</h3>\r\n\r\n<p>TeamCal Neo is a day-based online calendar that allows to easily manage your team&#39;s absences and displays them in an intuitive interface. You can manage absence types, holidays, regional calendars and much more.</p>\r\n\r\n<p>TeamCal Neo 3 requires a proper license now for a small yearly fee.</p>\r\n\r\n<p>Its little brother &quot;<a href=\"http://tcneobasic.lewe.com\">TeamCal Neo Basic</a>&quot; , however, remains free and offers the core features of the calendar.</p>\r\n\r\n<h3>Links:</h3>\r\n\r\n<ul>\r\n	<li><a href=\"http://www.lewe.com/teamcal-neo/\" target=\"_blank\">Product Page</a></li>\r\n	<li><a href=\"https://support.lewe.com/docs/teamcal-neo-manual/\" target=\"_blank\">Documentation</a></li>\r\n</ul>\r\n\r\n<h3>Login</h3>\r\n\r\n<p>Select Login from the User menu to login and use the following accounts to give this demo a test drive:</p>\r\n\r\n<h4>Admin account:</h4>\r\n\r\n<p>admin/root</p>\r\n\r\n<h4>User accounts:</h4>\r\n\r\n<p>ccarl/password<br />\r\nblightyear/password<br />\r\ndduck/password<br />\r\nsgonzalez/password<br />\r\nphead/password<br />\r\nmmouse/password<br />\r\nmimouse/password<br />\r\nsman/password</p>\r\n'),
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
(48, 'menuBarDark', '1'),
(51, 'logperiod', 'curr_all'),
(50, 'faCDN', '0'),
(52, 'logfrom', '2014-01-01 00:00:00.000000'),
(53, 'logto', '2020-01-02 23:59:59.999999'),
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
(116, 'declBeforeDate', '2016-01-01'),
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
(138, 'showBanner', '0'),
(139, 'showRoleIcons', '1'),
(140, 'logImport', '1'),
(141, 'logcolorImport', 'warning'),
(142, 'logfilterImport', '1'),
(143, 'appKeywords', 'Lewe TeamCal Neo'),
(144, 'userManual', 'https%3A%2F%2Fsupport.lewe.com%2Fdocs%2Fteamcal-neo-manual%2F'),
(145, 'footerCopyrightUrl', 'http://www.lewe.com'),
(146, 'footerSocialLinks', 'https://www.linkedin.com/in/george-lewe-a9ab6411b;https://www.xing.com/profile/George_Lewe'),
(147, 'footerViewport', '0'),
(148, 'cookieConsent', '1'),
(149, 'noIndex', '1'),
(150, 'showTooltipCount', '0'),
(151, 'symbolAsIcon', '0'),
(152, 'showRegionButton', '1'),
(153, 'statsDefaultColorAbsences', 'red'),
(154, 'statsDefaultColorPresences', 'green'),
(155, 'statsDefaultColorAbsencetype', 'cyan'),
(156, 'statsDefaultColorRemainder', 'orange'),
(157, 'logDaynote', '0'),
(158, 'logfilterDaynote', '0'),
(159, 'logcolorDaynote', 'default'),
(160, 'logRegion', '1'),
(161, 'logfilterRegion', '1'),
(162, 'logcolorRegion', 'success'),
(163, 'defaultHomepage', 'home'),
(164, 'trustedRoles', '1'),
(165, 'noCaching', '0'),
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
(196, 'licKey', 'TCN-5e0f5aa8026d5'),
(197, 'licExpiryWarning', '30'),
(198, 'menuBarBg', 'dark');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_daynotes`
--

DROP TABLE IF EXISTS `tcneo_daynotes`;
CREATE TABLE `tcneo_daynotes` (
  `id` int(11) NOT NULL,
  `yyyymmdd` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'all',
  `region` int(11) NOT NULL DEFAULT 1,
  `daynote` text CHARACTER SET utf8 DEFAULT NULL,
  `color` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT 'default',
  `confidential` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_daynotes`
--

INSERT INTO `tcneo_daynotes` (`id`, `yyyymmdd`, `username`, `region`, `daynote`, `color`, `confidential`) VALUES
(1, '20200114', 'phead', 1, 'Here is a daynote', 'info', 0),
(2, '20200116', 'phead', 1, 'Anopther daynote', 'info', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_groups`
--

DROP TABLE IF EXISTS `tcneo_groups`;
CREATE TABLE `tcneo_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `minpresent` smallint(6) NOT NULL DEFAULT 0,
  `maxabsent` smallint(6) NOT NULL DEFAULT 9999,
  `minpresentwe` smallint(6) NOT NULL DEFAULT 0,
  `maxabsentwe` smallint(6) NOT NULL DEFAULT 9999
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_groups`
--

INSERT INTO `tcneo_groups` (`id`, `name`, `description`, `minpresent`, `maxabsent`, `minpresentwe`, `maxabsentwe`) VALUES
(1, 'Disney', 'Disney Characters', 0, 9999, 0, 9999),
(2, 'Marvel', 'Marvel Characters', 0, 9999, 0, 9999),
(3, 'Looney', 'Looney Characters', 0, 9999, 0, 9999),
(4, 'Pixar', 'Pixar Characters', 0, 9999, 0, 9999);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_holidays`
--

DROP TABLE IF EXISTS `tcneo_holidays`;
CREATE TABLE `tcneo_holidays` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `color` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT '000000',
  `bgcolor` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT 'ffffff',
  `businessday` tinyint(1) NOT NULL DEFAULT 0,
  `noabsence` tinyint(1) NOT NULL DEFAULT 0,
  `keepweekendcolor` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_holidays`
--

INSERT INTO `tcneo_holidays` (`id`, `name`, `description`, `color`, `bgcolor`, `businessday`, `noabsence`, `keepweekendcolor`) VALUES
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
CREATE TABLE `tcneo_log` (
  `id` int(11) NOT NULL,
  `type` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `user` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `event` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_log`
--

INSERT INTO `tcneo_log` (`id`, `type`, `timestamp`, `user`, `event`) VALUES
(1, 'logDatabase', '2020-01-03 00:08:10', 'admin', 'Database delete: Log records cleared');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_messages`
--

DROP TABLE IF EXISTS `tcneo_messages`;
CREATE TABLE `tcneo_messages` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `text` text CHARACTER SET utf8 NOT NULL,
  `type` varchar(8) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_months`
--

DROP TABLE IF EXISTS `tcneo_months`;
CREATE TABLE `tcneo_months` (
  `id` int(11) NOT NULL,
  `year` varchar(4) CHARACTER SET utf8 DEFAULT NULL,
  `month` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `region` int(11) DEFAULT 1,
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
-- Dumping data for table `tcneo_months`
--

INSERT INTO `tcneo_months` (`id`, `year`, `month`, `region`, `wday1`, `wday2`, `wday3`, `wday4`, `wday5`, `wday6`, `wday7`, `wday8`, `wday9`, `wday10`, `wday11`, `wday12`, `wday13`, `wday14`, `wday15`, `wday16`, `wday17`, `wday18`, `wday19`, `wday20`, `wday21`, `wday22`, `wday23`, `wday24`, `wday25`, `wday26`, `wday27`, `wday28`, `wday29`, `wday30`, `wday31`, `week1`, `week2`, `week3`, `week4`, `week5`, `week6`, `week7`, `week8`, `week9`, `week10`, `week11`, `week12`, `week13`, `week14`, `week15`, `week16`, `week17`, `week18`, `week19`, `week20`, `week21`, `week22`, `week23`, `week24`, `week25`, `week26`, `week27`, `week28`, `week29`, `week30`, `week31`, `hol1`, `hol2`, `hol3`, `hol4`, `hol5`, `hol6`, `hol7`, `hol8`, `hol9`, `hol10`, `hol11`, `hol12`, `hol13`, `hol14`, `hol15`, `hol16`, `hol17`, `hol18`, `hol19`, `hol20`, `hol21`, `hol22`, `hol23`, `hol24`, `hol25`, `hol26`, `hol27`, `hol28`, `hol29`, `hol30`, `hol31`) VALUES
(14, '2020', '01', 1, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, '2020', '02', 1, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 0, 0, 5, 5, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8, 9, 9, 9, 9, 9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, '2020', '03', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 9, 10, 10, 10, 10, 10, 10, 10, 11, 11, 11, 11, 11, 11, 11, 12, 12, 12, 12, 12, 12, 12, 13, 13, 13, 13, 13, 13, 13, 14, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, '2020', '04', 1, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 0, 14, 14, 14, 14, 14, 15, 15, 15, 15, 15, 15, 15, 16, 16, 16, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, '2020', '05', 1, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, '2020', '06', 1, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 0, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 25, 25, 25, 25, 25, 25, 25, 26, 26, 26, 26, 26, 26, 26, 27, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, '2020', '07', 1, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 27, 27, 27, 27, 27, 28, 28, 28, 28, 28, 28, 28, 29, 29, 29, 29, 29, 29, 29, 30, 30, 30, 30, 30, 30, 30, 31, 31, 31, 31, 31, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(21, '2020', '08', 1, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 31, 31, 32, 32, 32, 32, 32, 32, 32, 33, 33, 33, 33, 33, 33, 33, 34, 34, 34, 34, 34, 34, 34, 35, 35, 35, 35, 35, 35, 35, 36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, '2020', '09', 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 0, 36, 36, 36, 36, 36, 36, 37, 37, 37, 37, 37, 37, 37, 38, 38, 38, 38, 38, 38, 38, 39, 39, 39, 39, 39, 39, 39, 40, 40, 40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(23, '2020', '10', 1, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 40, 40, 40, 40, 41, 41, 41, 41, 41, 41, 41, 42, 42, 42, 42, 42, 42, 42, 43, 43, 43, 43, 43, 43, 43, 44, 44, 44, 44, 44, 44, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(24, '2020', '11', 1, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 0, 44, 45, 45, 45, 45, 45, 45, 45, 46, 46, 46, 46, 46, 46, 46, 47, 47, 47, 47, 47, 47, 47, 48, 48, 48, 48, 48, 48, 48, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(25, '2020', '12', 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 49, 49, 49, 49, 49, 49, 50, 50, 50, 50, 50, 50, 50, 51, 51, 51, 51, 51, 51, 51, 52, 52, 52, 52, 52, 52, 52, 53, 53, 53, 53, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, '2020', '01', 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 6, 7, 1, 2, 3, 4, 5, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_permissions`
--

DROP TABLE IF EXISTS `tcneo_permissions`;
CREATE TABLE `tcneo_permissions` (
  `id` int(11) NOT NULL,
  `scheme` varchar(80) CHARACTER SET utf8 NOT NULL,
  `permission` varchar(40) CHARACTER SET utf8 NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  `allowed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_permissions`
--

INSERT INTO `tcneo_permissions` (`id`, `scheme`, `permission`, `role`, `allowed`) VALUES
(1, 'Default', 'calendareditall', 1, 1),
(2, 'Default', 'calendareditgroup', 2, 0),
(3, 'Default', 'calendareditgroup', 3, 0),
(4, 'Default', 'calendareditgroup', 4, 0),
(5, 'Default', 'calendareditgroup', 1, 1),
(6, 'Default', 'calendareditown', 2, 1),
(7, 'Default', 'calendareditown', 3, 0),
(8, 'Default', 'calendareditown', 4, 1),
(9, 'Default', 'calendareditown', 1, 1),
(10, 'Default', 'statistics', 2, 0),
(11, 'Default', 'statistics', 3, 0),
(12, 'Default', 'statistics', 4, 1),
(13, 'Default', 'statistics', 1, 1),
(14, 'Default', 'regions', 2, 0),
(15, 'Default', 'regions', 3, 0),
(16, 'Default', 'regions', 4, 0),
(17, 'Default', 'regions', 1, 1),
(18, 'Default', 'holidays', 2, 0),
(19, 'Default', 'holidays', 3, 0),
(20, 'Default', 'holidays', 4, 1),
(21, 'Default', 'holidays', 1, 1),
(22, 'Default', 'declination', 2, 0),
(23, 'Default', 'declination', 3, 0),
(24, 'Default', 'declination', 4, 1),
(25, 'Default', 'declination', 1, 1),
(26, 'Default', 'calendaroptions', 2, 0),
(27, 'Default', 'calendaroptions', 3, 0),
(28, 'Default', 'calendaroptions', 4, 0),
(29, 'Default', 'calendaroptions', 1, 1),
(30, 'Default', 'calendaredit', 2, 1),
(31, 'Default', 'calendaredit', 3, 0),
(32, 'Default', 'calendaredit', 4, 1),
(33, 'Default', 'calendaredit', 1, 1),
(34, 'Default', 'calendarview', 2, 1),
(35, 'Default', 'calendarview', 3, 1),
(36, 'Default', 'calendarview', 4, 1),
(37, 'Default', 'calendarview', 1, 1),
(38, 'Default', 'absenceedit', 2, 0),
(39, 'Default', 'absenceedit', 3, 0),
(40, 'Default', 'absenceedit', 4, 0),
(41, 'Default', 'absenceedit', 1, 1),
(42, 'Default', 'viewprofile', 2, 1),
(43, 'Default', 'viewprofile', 3, 0),
(44, 'Default', 'viewprofile', 4, 1),
(45, 'Default', 'viewprofile', 1, 1),
(46, 'Default', 'attachments', 2, 1),
(47, 'Default', 'attachments', 3, 0),
(48, 'Default', 'attachments', 4, 1),
(49, 'Default', 'attachments', 1, 1),
(50, 'Default', 'roles', 2, 0),
(51, 'Default', 'roles', 3, 0),
(52, 'Default', 'roles', 4, 0),
(53, 'Default', 'roles', 1, 1),
(54, 'Default', 'messageedit', 2, 1),
(55, 'Default', 'messageedit', 3, 0),
(56, 'Default', 'messageedit', 4, 1),
(57, 'Default', 'messageedit', 1, 1),
(58, 'Default', 'messageview', 2, 1),
(59, 'Default', 'messageview', 3, 0),
(60, 'Default', 'messageview', 4, 1),
(61, 'Default', 'messageview', 1, 1),
(62, 'Default', 'groups', 2, 0),
(63, 'Default', 'groups', 3, 0),
(64, 'Default', 'groups', 4, 0),
(65, 'Default', 'groups', 1, 1),
(66, 'Default', 'admin', 2, 0),
(67, 'Default', 'admin', 3, 0),
(68, 'Default', 'admin', 4, 0),
(69, 'Default', 'admin', 1, 1),
(70, 'Default', 'calendareditall', 4, 0),
(71, 'Default', 'calendareditall', 3, 0),
(72, 'Default', 'calendareditall', 2, 0),
(73, 'Default', 'calendarviewgroup', 1, 1),
(74, 'Default', 'calendarviewgroup', 4, 1),
(75, 'Default', 'calendarviewgroup', 3, 0),
(76, 'Default', 'calendarviewgroup', 2, 1),
(77, 'Default', 'calendarviewall', 1, 1),
(78, 'Default', 'calendarviewall', 4, 1),
(79, 'Default', 'calendarviewall', 3, 0),
(80, 'Default', 'calendarviewall', 2, 0),
(81, 'Default', 'useraccount', 1, 1),
(82, 'Default', 'useraccount', 4, 0),
(83, 'Default', 'useraccount', 3, 0),
(84, 'Default', 'useraccount', 2, 0),
(85, 'Default', 'groupmemberships', 1, 1),
(86, 'Default', 'groupmemberships', 4, 0),
(87, 'Default', 'groupmemberships', 3, 0),
(88, 'Default', 'groupmemberships', 2, 0),
(89, 'Default', 'absum', 1, 1),
(90, 'Default', 'absum', 4, 0),
(91, 'Default', 'absum', 3, 0),
(92, 'Default', 'absum', 2, 0),
(93, 'Default', 'daynote', 1, 1),
(94, 'Default', 'daynote', 4, 1),
(95, 'Default', 'daynote', 3, 0),
(96, 'Default', 'daynote', 2, 1),
(97, 'Default', 'daynoteglobal', 1, 1),
(98, 'Default', 'daynoteglobal', 4, 1),
(99, 'Default', 'daynoteglobal', 3, 0),
(100, 'Default', 'daynoteglobal', 2, 0),
(101, 'Default', 'userallowance', 1, 1),
(102, 'Default', 'userallowance', 4, 0),
(103, 'Default', 'userallowance', 3, 0),
(104, 'Default', 'userallowance', 2, 0),
(105, 'Default', 'upload', 1, 1),
(106, 'Default', 'upload', 4, 0),
(107, 'Default', 'upload', 3, 0),
(108, 'Default', 'upload', 2, 0),
(109, 'Default', 'groupcalendaredit', 1, 1),
(110, 'Default', 'groupcalendaredit', 4, 0),
(111, 'Default', 'groupcalendaredit', 3, 0),
(112, 'Default', 'groupcalendaredit', 2, 0),
(113, 'Default', 'useredit', 1, 1),
(114, 'Default', 'useredit', 4, 0),
(115, 'Default', 'useredit', 3, 0),
(116, 'Default', 'useredit', 2, 0),
(117, 'Default', 'useroptions', 1, 1),
(118, 'Default', 'useroptions', 4, 1),
(119, 'Default', 'useroptions', 3, 0),
(120, 'Default', 'useroptions', 2, 1),
(121, 'Default', 'userabsences', 1, 1),
(122, 'Default', 'userabsences', 4, 1),
(123, 'Default', 'userabsences', 3, 0),
(124, 'Default', 'userabsences', 2, 0),
(125, 'Default', 'useravatar', 1, 1),
(126, 'Default', 'useravatar', 4, 1),
(127, 'Default', 'useravatar', 3, 0),
(128, 'Default', 'useravatar', 2, 1),
(129, 'Default', 'usercustom', 1, 1),
(130, 'Default', 'usercustom', 4, 1),
(131, 'Default', 'usercustom', 3, 0),
(132, 'Default', 'usercustom', 2, 0),
(133, 'Default', 'usergroups', 1, 1),
(134, 'Default', 'usergroups', 4, 1),
(135, 'Default', 'usergroups', 3, 0),
(136, 'Default', 'usergroups', 2, 1),
(137, 'Default', 'usernotifications', 1, 1),
(138, 'Default', 'usernotifications', 4, 1),
(139, 'Default', 'usernotifications', 3, 0),
(140, 'Default', 'usernotifications', 2, 1),
(141, 'Default', 'remainder', 1, 1),
(142, 'Default', 'remainder', 4, 1),
(143, 'Default', 'remainder', 3, 0),
(144, 'Default', 'remainder', 2, 0),
(145, 'Default', 'manageronlyabsences', 1, 1),
(146, 'Default', 'manageronlyabsences', 4, 0),
(147, 'Default', 'manageronlyabsences', 3, 0),
(148, 'Default', 'manageronlyabsences', 2, 0),
(149, 'Default', 'calendareditgroupmanaged', 2, 0),
(150, 'Default', 'calendareditgroupmanaged', 3, 0),
(151, 'Default', 'calendareditgroupmanaged', 4, 1),
(152, 'Default', 'calendareditgroupmanaged', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_regions`
--

DROP TABLE IF EXISTS `tcneo_regions`;
CREATE TABLE `tcneo_regions` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_regions`
--

INSERT INTO `tcneo_regions` (`id`, `name`, `description`) VALUES
(1, 'Default', 'Default Region'),
(2, 'Canada', 'Canada Region'),
(3, 'Germany', 'Germany Region');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_region_role`
--

DROP TABLE IF EXISTS `tcneo_region_role`;
CREATE TABLE `tcneo_region_role` (
  `id` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `access` varchar(4) CHARACTER SET utf8 DEFAULT 'edit'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_roles`
--

DROP TABLE IF EXISTS `tcneo_roles`;
CREATE TABLE `tcneo_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `color` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'default',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_roles`
--

INSERT INTO `tcneo_roles` (`id`, `name`, `description`, `color`, `created`, `updated`) VALUES
(1, 'Administrator', 'Application administrator', 'danger', '0000-00-00 00:00:00', '2019-12-29 22:20:53'),
(2, 'User', 'Standard role for logged in users', 'primary', '0000-00-00 00:00:00', '2019-12-29 22:20:53'),
(3, 'Public', 'All users not logged in', 'secondary', '0000-00-00 00:00:00', '2019-12-29 22:20:53'),
(4, 'Manager', 'Management Team', 'warning', '0000-00-00 00:00:00', '2019-12-29 22:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_templates`
--

DROP TABLE IF EXISTS `tcneo_templates`;
CREATE TABLE `tcneo_templates` (
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

--
-- Dumping data for table `tcneo_templates`
--

INSERT INTO `tcneo_templates` (`id`, `username`, `year`, `month`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`, `abs8`, `abs9`, `abs10`, `abs11`, `abs12`, `abs13`, `abs14`, `abs15`, `abs16`, `abs17`, `abs18`, `abs19`, `abs20`, `abs21`, `abs22`, `abs23`, `abs24`, `abs25`, `abs26`, `abs27`, `abs28`, `abs29`, `abs30`, `abs31`) VALUES
(21, 'ccarl', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 'dduck', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(23, 'sgonzales', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(24, 'phead', '2020', '01', 0, 0, 5, 0, 0, 6, 0, 0, 9, 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 8, 0, 1, 0, 0, 0, 0, 0, 0, 7),
(25, 'blightyear', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, 'mmouse', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27, 'sman', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(28, 'admin', '2020', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 'admin', '2020', '02', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(30, 'admin', '2020', '03', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 'admin', '2020', '04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(32, 'admin', '2020', '05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 'admin', '2020', '06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 'admin', '2020', '07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 'admin', '2020', '08', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 'admin', '2020', '09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 'admin', '2020', '10', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 'admin', '2020', '11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 'admin', '2020', '12', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_users`
--

DROP TABLE IF EXISTS `tcneo_users`;
CREATE TABLE `tcneo_users` (
  `username` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `firstname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `lastname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `role` int(11) DEFAULT 2,
  `locked` tinyint(4) DEFAULT 0,
  `hidden` tinyint(4) DEFAULT 0,
  `onhold` tinyint(4) DEFAULT 0,
  `verify` tinyint(4) DEFAULT 0,
  `bad_logins` tinyint(4) DEFAULT 0,
  `grace_start` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `last_pw_change` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_users`
--

INSERT INTO `tcneo_users` (`username`, `password`, `firstname`, `lastname`, `email`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`) VALUES
('blightyear', '$2y$10$Vtw.3Vz.k7q0wWMRd48F6uW5QJ8R4z6r.ru7tL.aR.duXf4ynQgGm', 'Buzz', 'Lightyear', 'blightyear@yourserver.com', 4, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('sman', '$2y$10$xGRcN3kjQRcOaNFZHU1Oo.XqmTuiTNX0YzFdWZGFY/zbrfSw1HoR2', '', 'Spiderman', 'sman@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('mmouse', '$2y$10$UXHMBWNOPsohn0IxEfrkhOykPp/iO3JZSsYk3aywy9yXGmGrVU.CS', 'Mickey', 'Mouse', 'mmouse@yourserver.com', 4, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('admin', '$2y$10$Kt4r.PZIdqa.RU1qgPQP2ecN6pqDHG3qLifAu9IMMyLntlhnIMhaS', '', 'Admin', 'webmaster@yourserver.com', 1, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2020-01-02 23:26:53', '2016-04-23 00:00:00'),
('phead', '$2y$10$qvuiMeWbC0lAZdl7EnlRZe2PF6PRjsd5cRT0IhOjPdzAvnupWwFMq', 'Potatoe', 'Head', 'ccarl@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('ccarl', '$2y$10$r/8VfjUT9DDCF8efXd2yvOu/JiT.cBwSTaI8qVTFMcbBirdQ7JSSe', 'Coyote', 'Carl', 'ccarl@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('dduck', '$2y$10$xGRcN3kjQRcOaNFZHU1Oo.XqmTuiTNX0YzFdWZGFY/zbrfSw1HoR2', 'Donald', 'Duck', 'dduck@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('sgonzales', '$2y$10$J.Px4e14DNX6.indHyBZOeCEV6EuNAUZTmnnaHB4CDF5JWypexrvS', 'Speedy', 'Gonzales', 'sgonzales@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00'),
('mimouse', '$2y$10$IkWQeduBbmLn4m5rybEf6OCQRRFui0PEJ5bMWndCzlWMmpznXe3Xy', 'Minnie', 'Mouse', 'mimouse@yourserver.com', 2, 1, 1, 1, 1, 0, '1000-01-01 00:00:00', '2018-09-29 00:00:00', '2018-09-29 00:00:00', '2016-04-23 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_attachment`
--

DROP TABLE IF EXISTS `tcneo_user_attachment`;
CREATE TABLE `tcneo_user_attachment` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `fileid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_user_attachment`
--

INSERT INTO `tcneo_user_attachment` (`id`, `username`, `fileid`) VALUES
(1, 'admin', 10),
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
CREATE TABLE `tcneo_user_group` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_user_group`
--

INSERT INTO `tcneo_user_group` (`id`, `username`, `groupid`, `type`) VALUES
(1, 'mmouse', 1, 'manager'),
(2, 'dduck', 1, 'member'),
(3, 'ccarl', 3, 'member'),
(4, 'phead', 4, 'member'),
(5, 'blightyear', 4, 'manager'),
(6, 'mimouse', 1, 'member'),
(7, 'sgonzales', 3, 'member'),
(8, 'sman', 2, 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_message`
--

DROP TABLE IF EXISTS `tcneo_user_message`;
CREATE TABLE `tcneo_user_message` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tcneo_user_option`
--

DROP TABLE IF EXISTS `tcneo_user_option`;
CREATE TABLE `tcneo_user_option` (
  `id` int(11) NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `option` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `value` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tcneo_user_option`
--

INSERT INTO `tcneo_user_option` (`id`, `username`, `option`, `value`) VALUES
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
(15, 'admin', 'avatar', 'is_administrator.png'),
(16, 'mmouse', 'language', 'deutsch'),
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
(29, 'ccarl', 'theme', 'bootstrap'),
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
(43, 'dduck', 'theme', 'default'),
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
(57, 'sgonzales', 'theme', 'slate'),
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
(71, 'phead', 'theme', 'cerulean'),
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
(85, 'blightyear', 'theme', 'journal'),
(86, 'blightyear', 'language', 'default'),
(87, 'blightyear', 'avatar', 'blightyear.jpg'),
(88, 'mimouse', 'title', ''),
(89, 'mimouse', 'position', ''),
(90, 'mimouse', 'id', ''),
(91, 'mimouse', 'gender', 'male'),
(92, 'mimouse', 'phone', ''),
(93, 'mimouse', 'mobile', ''),
(94, 'mimouse', 'facebook', ''),
(95, 'mimouse', 'google', ''),
(96, 'mimouse', 'linkedin', ''),
(97, 'mimouse', 'skype', ''),
(98, 'mimouse', 'twitter', ''),
(99, 'mimouse', 'theme', 'cosmo'),
(100, 'mimouse', 'language', 'default'),
(101, 'mimouse', 'avatar', 'mimouse.jpg'),
(102, 'sman', 'title', ''),
(103, 'sman', 'position', ''),
(104, 'sman', 'id', ''),
(105, 'sman', 'gender', 'male'),
(106, 'sman', 'phone', ''),
(107, 'sman', 'mobile', ''),
(108, 'sman', 'facebook', ''),
(109, 'sman', 'google', ''),
(110, 'sman', 'linkedin', ''),
(111, 'sman', 'skype', ''),
(112, 'sman', 'twitter', ''),
(113, 'sman', 'theme', 'cyborg'),
(114, 'sman', 'language', 'default'),
(115, 'sman', 'avatar', 'spiderman.jpg'),
(116, 'mmouse', 'title', ''),
(117, 'mmouse', 'position', ''),
(118, 'mmouse', 'id', ''),
(119, 'mmouse', 'gender', 'male'),
(120, 'mmouse', 'phone', ''),
(121, 'mmouse', 'mobile', ''),
(122, 'mmouse', 'facebook', ''),
(123, 'mmouse', 'google', ''),
(124, 'mmouse', 'linkedin', ''),
(125, 'mmouse', 'skype', ''),
(126, 'mmouse', 'twitter', ''),
(127, 'mmouse', 'theme', 'darkly'),
(128, 'admin', 'notifyAbsenceEvents', '1'),
(129, 'admin', 'notifyCalendarEvents', '1'),
(130, 'admin', 'notifyGroupEvents', '1'),
(131, 'admin', 'notifyHolidayEvents', '1'),
(132, 'admin', 'notifyMonthEvents', '1'),
(133, 'admin', 'notifyRoleEvents', '1'),
(134, 'admin', 'notifyUserEvents', '1'),
(135, 'admin', 'notifyUserCalEvents', '1'),
(136, 'admin', 'menuBar', 'default'),
(139, 'admin', 'width', 'full'),
(140, 'admin', 'region', '1'),
(141, 'ccarl', 'region', '1'),
(147, 'admin', 'calfilterRegion', '1'),
(146, 'admin', 'calfilterMonth', '202001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tcneo_absences`
--
ALTER TABLE `tcneo_absences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_absence_group`
--
ALTER TABLE `tcneo_absence_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absgroup` (`absid`,`groupid`);

--
-- Indexes for table `tcneo_allowances`
--
ALTER TABLE `tcneo_allowances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `allowance` (`username`,`absid`);

--
-- Indexes for table `tcneo_archive_allowances`
--
ALTER TABLE `tcneo_archive_allowances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `allowance` (`username`,`absid`);

--
-- Indexes for table `tcneo_archive_daynotes`
--
ALTER TABLE `tcneo_archive_daynotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yyyymmdd` (`yyyymmdd`,`username`,`region`);

--
-- Indexes for table `tcneo_archive_templates`
--
ALTER TABLE `tcneo_archive_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template` (`username`,`year`,`month`);

--
-- Indexes for table `tcneo_archive_users`
--
ALTER TABLE `tcneo_archive_users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tcneo_archive_user_attachment`
--
ALTER TABLE `tcneo_archive_user_attachment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userAttachment` (`username`,`fileid`);

--
-- Indexes for table `tcneo_archive_user_group`
--
ALTER TABLE `tcneo_archive_user_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usergroup` (`username`,`groupid`);

--
-- Indexes for table `tcneo_archive_user_message`
--
ALTER TABLE `tcneo_archive_user_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_archive_user_option`
--
ALTER TABLE `tcneo_archive_user_option`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `useroption` (`username`,`option`);

--
-- Indexes for table `tcneo_attachments`
--
ALTER TABLE `tcneo_attachments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attachment` (`filename`);

--
-- Indexes for table `tcneo_config`
--
ALTER TABLE `tcneo_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config` (`name`);

--
-- Indexes for table `tcneo_daynotes`
--
ALTER TABLE `tcneo_daynotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yyyymmdd` (`yyyymmdd`,`username`,`region`);

--
-- Indexes for table `tcneo_groups`
--
ALTER TABLE `tcneo_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_holidays`
--
ALTER TABLE `tcneo_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_log`
--
ALTER TABLE `tcneo_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_messages`
--
ALTER TABLE `tcneo_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_months`
--
ALTER TABLE `tcneo_months`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `month` (`year`,`month`,`region`);

--
-- Indexes for table `tcneo_permissions`
--
ALTER TABLE `tcneo_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission` (`scheme`,`permission`,`role`);

--
-- Indexes for table `tcneo_regions`
--
ALTER TABLE `tcneo_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_region_role`
--
ALTER TABLE `tcneo_region_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_roles`
--
ALTER TABLE `tcneo_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tcneo_templates`
--
ALTER TABLE `tcneo_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template` (`username`,`year`,`month`);

--
-- Indexes for table `tcneo_users`
--
ALTER TABLE `tcneo_users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tcneo_user_attachment`
--
ALTER TABLE `tcneo_user_attachment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userAttachment` (`username`,`fileid`);

--
-- Indexes for table `tcneo_user_group`
--
ALTER TABLE `tcneo_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_user_message`
--
ALTER TABLE `tcneo_user_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tcneo_user_option`
--
ALTER TABLE `tcneo_user_option`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `useroption` (`username`,`option`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tcneo_absences`
--
ALTER TABLE `tcneo_absences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tcneo_absence_group`
--
ALTER TABLE `tcneo_absence_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tcneo_allowances`
--
ALTER TABLE `tcneo_allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tcneo_archive_allowances`
--
ALTER TABLE `tcneo_archive_allowances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_archive_daynotes`
--
ALTER TABLE `tcneo_archive_daynotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_archive_templates`
--
ALTER TABLE `tcneo_archive_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_archive_user_attachment`
--
ALTER TABLE `tcneo_archive_user_attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_archive_user_group`
--
ALTER TABLE `tcneo_archive_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_archive_user_message`
--
ALTER TABLE `tcneo_archive_user_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_archive_user_option`
--
ALTER TABLE `tcneo_archive_user_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_attachments`
--
ALTER TABLE `tcneo_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tcneo_config`
--
ALTER TABLE `tcneo_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `tcneo_daynotes`
--
ALTER TABLE `tcneo_daynotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tcneo_groups`
--
ALTER TABLE `tcneo_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tcneo_holidays`
--
ALTER TABLE `tcneo_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tcneo_log`
--
ALTER TABLE `tcneo_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tcneo_messages`
--
ALTER TABLE `tcneo_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_months`
--
ALTER TABLE `tcneo_months`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tcneo_permissions`
--
ALTER TABLE `tcneo_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `tcneo_regions`
--
ALTER TABLE `tcneo_regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tcneo_region_role`
--
ALTER TABLE `tcneo_region_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_roles`
--
ALTER TABLE `tcneo_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tcneo_templates`
--
ALTER TABLE `tcneo_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tcneo_user_attachment`
--
ALTER TABLE `tcneo_user_attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tcneo_user_group`
--
ALTER TABLE `tcneo_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tcneo_user_message`
--
ALTER TABLE `tcneo_user_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcneo_user_option`
--
ALTER TABLE `tcneo_user_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
