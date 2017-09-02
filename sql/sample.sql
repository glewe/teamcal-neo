/**
 * sample.sql
 * 
 * Sample MySQL database
 *
 * @category TeamCal Neo 
 * @version 1.5.005
 * @author George Lewe
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tcneo`
--

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

DROP TABLE IF EXISTS `tcneo_absences`;
CREATE TABLE `tcneo_absences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `symbol` char(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A',
  `icon` varchar(40) CHARACTER SET utf8 NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 NOT NULL,
  `bgcolor` varchar(6) CHARACTER SET utf8 NOT NULL,
  `bgtrans` tinyint(1) NOT NULL DEFAULT '0',
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
  `takeover` tinyint(1) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `absences`
--

INSERT INTO `tcneo_absences` (`id`, `name`, `symbol`, `icon`, `color`, `bgcolor`, `bgtrans`, `factor`, `allowance`, `allowmonth`, `allowweek`, `counts_as`, `show_in_remainder`, `show_totals`, `approval_required`, `counts_as_present`, `manager_only`, `hide_in_profile`, `confidential`, `takeover`) VALUES
(1, 'Vacation', 'V', 'smile-o', 'FFEE00', 'FC3737', 0, 1, 20, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0),
(2, 'Sick', 'S', 'ambulance', '8C208C', 'FFCCFF', 0, 1, 24, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0),
(3, 'Day Off', 'F', 'coffee', '1A5C00', '00FF00', 0, 1, 12, 0, 0, 1, 1, 1, 0, 0, 0, 1, 0, 0),
(4, 'Duty Trip', 'D', 'phone', '007A14', 'FFDB9E', 0, 1, 20, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(5, 'Home Office', 'H', 'home', '1E00FF', 'D6F5FF', 0, 1, 0, 4, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0),
(6, 'Not Present', 'N', 'times', 'FF0000', 'C0C0C0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0),
(7, 'Training', 'T', 'book', 'FFFFFF', '6495ED', 0, 1, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'Tentative Absence', 'A', 'question-circle', '5E5E5E', 'EFEFEF', 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0),
(9, 'Half day', 'H', 'star-half-empty', 'A10000', 'FFAAAA', 0, 1, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `absence_group`
--

DROP TABLE IF EXISTS `tcneo_absence_group`;
CREATE TABLE `tcneo_absence_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `absid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE absgroup (absid,groupid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `absence_group`
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
-- Table structure for table `allowances`
--

DROP TABLE IF EXISTS `tcneo_allowances`;
CREATE TABLE `tcneo_allowances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL,
  `absid` int(11) NOT NULL,
  `carryover` smallint(6) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE allowance (username,absid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_allowances`
--

DROP TABLE IF EXISTS `tcneo_archive_allowances`;
CREATE TABLE `tcneo_archive_allowances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL,
  `absid` int(11) NOT NULL,
  `carryover` smallint(6) DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE allowance (username,absid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_daynotes`
--

DROP TABLE IF EXISTS `tcneo_archive_daynotes`;
CREATE TABLE `tcneo_archive_daynotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yyyymmdd` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'all',
  `region` int(11) NOT NULL DEFAULT '1',
  `daynote` text CHARACTER SET utf8,
  `color` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT 'default',
  `confidential` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE( `yyyymmdd`, `username`, `region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_templates`
--

DROP TABLE IF EXISTS `tcneo_archive_templates`;
CREATE TABLE `tcneo_archive_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `abs31` int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE template (username,year,month)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_users`
--

DROP TABLE IF EXISTS `tcneo_archive_users`;
CREATE TABLE `tcneo_archive_users` (
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
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (username)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_group`
--

DROP TABLE IF EXISTS `tcneo_archive_user_group`;
CREATE TABLE `tcneo_archive_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext CHARACTER SET utf8,
  PRIMARY KEY (id),
  UNIQUE usergroup (username,groupid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_message`
--

DROP TABLE IF EXISTS `tcneo_archive_user_message`;
CREATE TABLE `tcneo_archive_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_option`
--

DROP TABLE IF EXISTS `tcneo_archive_user_option`;
CREATE TABLE `tcneo_archive_user_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `option` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `value` text CHARACTER SET utf8,
  PRIMARY KEY (id),
  UNIQUE useroption (username,`option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `tcneo_config`;
CREATE TABLE `tcneo_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (id),
  UNIQUE config (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `config`
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
(10, 'homepage', 'calendarview'),
(11, 'welcomeIcon', 'None'),
(12, 'welcomeText', '<h1><img alt="" src="upload/files/logo-128.png" style="float:left; height:128px; margin-bottom:12px; margin-right:12px; width:128px" />Welcome to TeamCal Neo</h1>\n\n<p>TeamCal Neo, successor to the popular TeamCal Pro, is a day-based online calendar that allows to easily manage your team&#39;s absences and displays them in a very intuitive interface. You can manage absence types, holidays, regional calendars and much more. Read more about it here:<br />\n<a href="http://www.lewe.com/teamcal-neo/" target="_blank">http://www.lewe.com/teamcal-neo/</a></p>\n\n<p>Select Login from the User menu to login and use the following accounts to test:</p>\n\n<h2>Admin account:</h2>\n\n<p>admin/root</p>\n\n<h2>User accounts:</h2>\n\n<p>ccarl/password<br />\nblightyear/password<br />\ndduck/password<br />\nsgonzalez/password<br />\nphead/password<br />\nmmouse/password<br />\nmimouse/password<br />\nsman/password</p>\n'),
(13, 'welcomeTitle', 'Welcome To TeamCal Neo'),
(14, 'userCustom1', 'Custom Field 1'),
(15, 'userCustom2', 'Custom Field 2'),
(16, 'userCustom3', 'Custom Field 3'),
(17, 'userCustom4', 'Custom Field 4'),
(18, 'userCustom5', 'Custom Field 5'),
(19, 'emailNotifications', '1'),
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
(37, 'jqtheme', 'black-tie'),
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
(48, 'menuBarInverse', '1'),
(51, 'logperiod', 'curr_all'),
(50, 'faCDN', '0'),
(52, 'logfrom', '2014-01-01 00:00:00.000000'),
(53, 'logto', '2016-12-31 23:59:59.999999'),
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
(117, 'dbURL', 'http://localhost/phpmyadmin/db_structure.php?server=1&db=tcneo'),
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
(139, 'showRoleIcons', '0'),
(140, 'logImport', '1'),
(141, 'logcolorImport', 'warning'),
(142, 'logfilterImport', '1'),
(143, 'appKeywords', 'Lewe TeamCal Neo'),
(144, 'userManual', 'https%3A%2F%2Fgeorgelewe.atlassian.net%2Fwiki%2Fdisplay%2FTCNEO%2FTeamCal%2BNeo%2BDocumentation%2F'),
(145, 'footerCopyrightUrl', 'http://www.lewe.com'),
(146, 'footerSocialLinks', 'https://plus.google.com/u/0/+GeorgeLewe;https://www.linkedin.com/in/george-lewe-a9ab6411b;https://twitter.com/gekale;https://www.xing.com/profile/George_Lewe;https://www.paypal.me/GeorgeLewe'),
(147, 'footerViewport', '0'),
(148, 'cookieConsent', '0'),
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
(165, 'noCaching', '0');


-- --------------------------------------------------------

--
-- Table structure for table `daynotes`
--

DROP TABLE IF EXISTS `tcneo_daynotes`;
CREATE TABLE `tcneo_daynotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yyyymmdd` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'all',
  `region` int(11) NOT NULL DEFAULT '1',
  `daynote` text CHARACTER SET utf8,
  `color` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT 'default',
  `confidential` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE( `yyyymmdd`, `username`, `region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `tcneo_groups`;
CREATE TABLE `tcneo_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `groups`
--

INSERT INTO `tcneo_groups` (`id`, `name`, `description`) VALUES
(1, 'Disney', 'Disney Characters'),
(2, 'Marvel', 'Marvel Characters'),
(3, 'Looney', 'Looney Characters'),
(4, 'Pixar', 'Pixar Characters');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `tcneo_holidays`;
CREATE TABLE `tcneo_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `color` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT '000000',
  `bgcolor` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT 'ffffff',
  `businessday` tinyint(1) NOT NULL DEFAULT '0',
  `noabsence` tinyint(1) NOT NULL DEFAULT '0',
  `keepweekendcolor` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `holidays`
--

INSERT INTO `tcneo_holidays` (`id`, `name`, `description`, `color`, `bgcolor`, `businessday`, `noabsence`, `keepweekendcolor`) VALUES
(1, 'Business Day', 'Regular business day', '000000', 'ffffff', 1, 0, 0),
(2, 'Saturday', 'Regular weekend day (Saturday)', '000000', 'fcfc9a', 1, 0, 0),
(3, 'Sunday', 'Regular weekend day (Sunday)', '000000', 'fcfc9a', 0, 0, 0),
(4, 'Public Holiday', 'Public bank holidays', '000000', 'EBAAAA', 0, 0, 0),
(5, 'School Holiday', 'School holidays', '000000', 'BFFFDF', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `tcneo_log`;
CREATE TABLE `tcneo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `user` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `event` text CHARACTER SET utf8,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `tcneo_messages`;
CREATE TABLE `tcneo_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `text` text CHARACTER SET utf8 NOT NULL,
  `type` varchar(8) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `tcneo_months`;
CREATE TABLE `tcneo_months` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `hol31` int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE month (year,month,region)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `tcneo_permissions`;
CREATE TABLE `tcneo_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scheme` varchar(80) CHARACTER SET utf8 NOT NULL,
  `permission` varchar(40) CHARACTER SET utf8 NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `allowed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE permission (scheme,permission,role)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `permissions`
--

INSERT INTO `tcneo_permissions` (`id`, `scheme`, `permission`, `role`, `allowed`) VALUES
(1, 'Default', 'calendareditall', 1, 1),
(2, 'Default', 'calendareditgroup', 2, 0),
(3, 'Default', 'calendareditgroup', 3, 0),
(4, 'Default', 'calendareditgroup', 4, 1),
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
(100, 'Default', 'daynoteglobal', 2, 0);


-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `tcneo_regions`;
CREATE TABLE `tcneo_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `regions`
--

INSERT INTO `tcneo_regions` (`id`, `name`, `description`) VALUES
(1, 'Default', 'Default Region'),
(2, 'Canada', 'Canada Region'),
(3, 'Germany', 'Germany Region');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `tcneo_roles`;
CREATE TABLE `tcneo_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `color` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT 'default',
  PRIMARY KEY (id),
  UNIQUE (name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `roles`
--

INSERT INTO `tcneo_roles` (`id`, `name`, `description`, `color`) VALUES
(1, 'Administrator', 'Application administrator', 'danger'),
(2, 'User', 'Standard role for logged in users', 'primary'),
(3, 'Public', 'All users not logged in', 'default'),
(4, 'Manager', 'Management Team', 'warning');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `tcneo_templates`;
CREATE TABLE `tcneo_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `abs31` int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE template (username,year,month)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `templates`
--

INSERT INTO `tcneo_templates` (`id`, `username`, `year`, `month`, `abs1`, `abs2`, `abs3`, `abs4`, `abs5`, `abs6`, `abs7`, `abs8`, `abs9`, `abs10`, `abs11`, `abs12`, `abs13`, `abs14`, `abs15`, `abs16`, `abs17`, `abs18`, `abs19`, `abs20`, `abs21`, `abs22`, `abs23`, `abs24`, `abs25`, `abs26`, `abs27`, `abs28`, `abs29`, `abs30`, `abs31`) VALUES
(1, 'ccarl', '2016', '01', 0, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 5, 0, 0),
(2, 'dduck', '2016', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'sgonzales', '2016', '01', 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'phead', '2016', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'blightyear', '2016', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'mmouse', '2016', '01', 0, 0, 0, 6, 0, 0, 0, 2, 0, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 7, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'sman', '2016', '01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `tcneo_users`;
CREATE TABLE `tcneo_users` (
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
  `grace_start` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `last_pw_change` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (username)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `tcneo_users` (`username`, `password`, `firstname`, `lastname`, `email`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`) VALUES
('blightyear', 's7MuuIoROZfb2', 'Buzz', 'Lightyear', 'blightyear@yourserver.com', 4, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('sman', 's7MuuIoROZfb2', '', 'Spiderman', 'sman@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('mmouse', 's7MuuIoROZfb2', 'Mickey', 'Mouse', 'mmouse@yourserver.com', 4, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('admin', 's77dWZwOIYXss', '', 'Admin', 'webmaster@yourserver.com', 1, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('phead', 's7MuuIoROZfb2', 'Potatoe', 'Head', 'phead@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('ccarl', 's7MuuIoROZfb2', 'Coyote', 'Carl', 'ccarl@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('dduck', 's7MuuIoROZfb2', 'Donald', 'Duck', 'dduck@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('sgonzales', 's7MuuIoROZfb2', 'Speedy', 'Gonzales', 'sgonzales@yourserver.com', 2, 0, 0, 0, 0, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00'),
('mimouse', 's7MuuIoROZfb2', 'Minnie', 'Mouse', 'mimouse@yourserver.com', 2, 1, 1, 1, 1, 0, '1000-01-01 00:00:00', '1000-01-01 00:00:00', '1000-01-01 00:00:00', '2016-04-23 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `tcneo_user_group`;
CREATE TABLE `tcneo_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext CHARACTER SET utf8,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_group`
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
-- Table structure for table `user_message`
--

DROP TABLE IF EXISTS `tcneo_user_message`;
CREATE TABLE `tcneo_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_option`
--

DROP TABLE IF EXISTS `tcneo_user_option`;
CREATE TABLE `tcneo_user_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `option` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `value` text CHARACTER SET utf8,
  PRIMARY KEY (id),
  UNIQUE useroption (username,`option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_option`
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
(43, 'dduck', 'theme', 'amelia'),
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
(85, 'blightyear', 'theme', 'amelia'),
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
(135, 'admin', 'notifyUserCalEvents', '1');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `tcneo_attachments`;
CREATE TABLE `tcneo_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `uploader` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE attachment (filename)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `tcneo_attachments` (`id`, `filename`, `uploader`) VALUES
(1, 'logo-16.png', 'admin'),
(2, 'logo-22.png', 'admin'),
(3, 'logo-32.png', 'admin'),
(4, 'logo-48.png', 'admin'),
(5, 'logo-64.png', 'admin'),
(6, 'logo-72.png', 'admin'),
(7, 'logo-96.png', 'admin'),
(8, 'logo-128.png', 'admin'),
(9, 'logo-200.png', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_attachment`
--

DROP TABLE IF EXISTS `tcneo_user_attachment`;
CREATE TABLE `tcneo_user_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `fileid` int(11) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE userAttachment (username,fileid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_attachment`
--

DROP TABLE IF EXISTS `tcneo_archive_user_attachment`;
CREATE TABLE `tcneo_archive_user_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `fileid` int(11) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE userAttachment (username,fileid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `region_role`
--

DROP TABLE IF EXISTS `tcneo_region_role`;
CREATE TABLE `tcneo_region_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regionid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `access` varchar(4) CHARACTER SET utf8 DEFAULT "edit",
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
