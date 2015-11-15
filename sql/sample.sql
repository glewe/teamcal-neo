-- TCNeo SQL Sample

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

DROP TABLE IF EXISTS `absences`;
CREATE TABLE IF NOT EXISTS `absences` (
`id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `symbol` char(1) NOT NULL DEFAULT 'A',
  `icon` varchar(40) NOT NULL,
  `color` varchar(6) NOT NULL,
  `bgcolor` varchar(6) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `absences`
--

INSERT INTO `absences` (`id`, `name`, `symbol`, `icon`, `color`, `bgcolor`, `bgtrans`, `factor`, `allowance`, `counts_as`, `show_in_remainder`, `show_totals`, `approval_required`, `counts_as_present`, `manager_only`, `hide_in_profile`, `confidential`) VALUES
(1, 'Vacation', 'V', 'smile-o', 'FFEE00', 'FC3737', 0, 1, 20, 0, 1, 1, 1, 0, 0, 0, 0),
(2, 'Sick', 'S', 'ambulance', '8C208C', 'FFCCFF', 0, 1, 24, 0, 1, 0, 0, 0, 0, 0, 1),
(3, 'Day Off', 'F', 'coffee', '1A5C00', '00FF00', 0, 1, 12, 1, 1, 1, 0, 0, 0, 1, 0),
(4, 'Duty Trip', 'D', 'phone', '007A14', 'FFDB9E', 0, 1, 20, 0, 1, 0, 0, 0, 0, 0, 0),
(5, 'Home Office', 'H', 'home', '1E00FF', 'D6F5FF', 1, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(6, 'Not Present', 'N', 'times', 'FF0000', 'C0C0C0', 0, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1),
(7, 'Training', 'T', 'book', 'FFFFFF', '6495ED', 0, 1, 10, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'Tentative Absence', 'A', 'question-circle', '5E5E5E', 'EFEFEF', 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0),
(9, 'Half day', 'H', 'star-half-empty', 'A10000', 'FFAAAA', 0, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `absence_group`
--

DROP TABLE IF EXISTS `absence_group`;
CREATE TABLE IF NOT EXISTS `absence_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `absid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `absence_group`
--

INSERT INTO `absence_group` (`id`, `absid`, `groupid`) VALUES
(1, 3, 4),
(2, 3, 2),
(3, 3, 8),
(4, 13, 8),
(5, 3, 3),
(6, 4, 4),
(7, 4, 2),
(8, 13, 3),
(9, 5, 4),
(10, 5, 2),
(11, 5, 3),
(12, 6, 4),
(13, 6, 2),
(14, 2, 4),
(15, 2, 2),
(16, 13, 1),
(17, 8, 4),
(18, 8, 2),
(19, 8, 8),
(20, 8, 3),
(21, 7, 4),
(22, 7, 2),
(23, 7, 8),
(24, 7, 3),
(25, 1, 4),
(26, 1, 2),
(27, 1, 8),
(28, 1, 3),
(29, 3, 1),
(30, 2, 3),
(31, 2, 1),
(32, 13, 2),
(33, 13, 4),
(34, 4, 8),
(35, 4, 3),
(36, 4, 1),
(37, 5, 1),
(38, 6, 8),
(39, 6, 3),
(40, 6, 1),
(41, 8, 1),
(42, 7, 1),
(43, 1, 1),
(44, 9, 4),
(45, 9, 2),
(46, 9, 8),
(47, 9, 3),
(48, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `allowances`
--

DROP TABLE IF EXISTS `allowances`;
CREATE TABLE IF NOT EXISTS `allowances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `absid` int(11) NOT NULL,
  `lastyear` smallint(6) DEFAULT '0',
  `curryear` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archive_allowances`
--

DROP TABLE IF EXISTS `archive_allowances`;
CREATE TABLE IF NOT EXISTS `archive_allowances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `absid` int(11) NOT NULL,
  `lastyear` smallint(6) DEFAULT '0',
  `curryear` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archive_daynotes`
--

DROP TABLE IF EXISTS `archive_daynotes`;
CREATE TABLE IF NOT EXISTS `archive_daynotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yyyymmdd` varchar(8) DEFAULT NULL,
  `daynote` text,
  `username` varchar(40) NOT NULL DEFAULT 'all',
  `region` varchar(40) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `yyyymmdd` (`yyyymmdd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archive_templates`
--

DROP TABLE IF EXISTS `archive_templates`;
CREATE TABLE IF NOT EXISTS `archive_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `month` char(2) DEFAULT NULL,
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
  UNIQUE KEY `template` (`username`,`year`,`month`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archive_users`
--

DROP TABLE IF EXISTS `archive_users`;
CREATE TABLE IF NOT EXISTS `archive_users` (
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(40) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(40) DEFAULT NULL,
  `locked` tinyint(4) DEFAULT '0',
  `hidden` tinyint(4) DEFAULT '0',
  `onhold` tinyint(4) DEFAULT '0',
  `verify` tinyint(4) DEFAULT '0',
  `bad_logins` tinyint(4) DEFAULT '0',
  `grace_start` datetime DEFAULT NULL,
  `last_pw_change` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_group`
--

DROP TABLE IF EXISTS `archive_user_group`;
CREATE TABLE IF NOT EXISTS `archive_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_message`
--

DROP TABLE IF EXISTS `archive_user_message`;
CREATE TABLE IF NOT EXISTS `archive_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `archive_user_option`
--

DROP TABLE IF EXISTS `archive_user_option`;
CREATE TABLE IF NOT EXISTS `archive_user_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `option` varchar(40) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
`id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `value` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'theme', 'bootstrap'),
(2, 'defaultLanguage', 'english'),
(3, 'permissionScheme', 'Default'),
(4, 'appTitle', 'TeamCal Neo'),
(5, 'appFooterCpy', '© 2014 by Lewe.com'),
(6, 'allowUserTheme', '1'),
(7, 'logLanguage', 'english'),
(8, 'showAlerts', 'all'),
(9, 'activateMessages', '1'),
(10, 'homepage', 'home'),
(11, 'welcomeIcon', 'logo-128.png'),
(12, 'welcomeText', 'TeamCal Neo has been completely re-written as a responsive web application based on HTML5 and CSS3.\r\n\r\nThis Alpha release serves basic demonstration purposes only.\r\n\r\nSelect Login from the User menu to login.\r\n\r\nAdmin account: admin/root\r\nUser accounts:\r\n- blightyear/password\r\n- ccarl/password\r\n- dduck/password\r\n- mimouse/password\r\n- mmouse/password\r\n- phead/password\r\n- sgonzales/password\r\n- sman/password '),
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
(45, 'avatarWidth', '0'),
(46, 'avatarHeight', '0'),
(47, 'avatarMaxSize', '0'),
(48, 'menuBarInverse', '1'),
(51, 'logperiod', 'curr_all'),
(50, 'faCDN', '0'),
(52, 'logfrom', '2004-01-01 00:00:00.000000'),
(53, 'logto', '2014-12-17 23:59:59.999999'),
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
(106, 'includeSummary', '0'),
(107, 'showSummary', '0'),
(108, 'supportMobile', '0'),
(109, 'logfilterCalendar', '1'),
(110, 'logfilterCalendar Options', '0'),
(111, 'logCalendarOptions', '1'),
(112, 'logfilterCalendarOptions', '1'),
(113, 'declThreshold', '40'),
(114, 'declBase', 'group'),
(115, 'declBeforeOption', 'date'),
(116, 'declBeforeDate', '2015-02-04');

-- --------------------------------------------------------

--
-- Table structure for table `daynotes`
--

DROP TABLE IF EXISTS `daynotes`;
CREATE TABLE IF NOT EXISTS `daynotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yyyymmdd` varchar(8) DEFAULT NULL,
  `daynote` text,
  `username` varchar(40) NOT NULL DEFAULT 'all',
  `region` varchar(40) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `yyyymmdd` (`yyyymmdd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'Disney', 'Disney Characters'),
(2, 'Marvel', 'Marvel Characters'),
(3, 'Looney', 'Looney Characters'),
(4, 'Pixar', 'Pixar Characters'),
(5, 'LSY', 'Lufthansa Systems');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `color` varchar(6) NOT NULL DEFAULT '000000',
  `bgcolor` varchar(6) NOT NULL DEFAULT 'ffffff',
  `businessday` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `description`, `color`, `bgcolor`, `businessday`) VALUES
(1, 'Business Day', 'Regular business day', '000000', 'ffffff', 0),
(2, 'Saturday', 'Regular weekend day (Saturday)', '000000', 'fcfc9a', 0),
(3, 'Sunday', 'Regular weekend day (Sunday)', '000000', 'fcfc9a', 0),
(4, 'Public Holiday', 'Public bank holidays agagag', '000000', 'EBAAAA', 0),
(5, 'School Holiday', 'School holidays', '000000', 'BFFFDF', 1);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(40) DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` varchar(40) DEFAULT NULL,
  `event` text,
  PRIMARY KEY (`id`),
  KEY `idx_occured` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `type` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `months`;
CREATE TABLE IF NOT EXISTS `months` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) DEFAULT NULL,
  `month` char(2) DEFAULT NULL,
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
  PRIMARY KEY (`id`), 
  UNIQUE KEY `template` (`year`,`month`,`region`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `scheme` varchar(80) NOT NULL,
  `permission` varchar(40) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `allowed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`scheme`,`permission`,`role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`scheme`, `permission`, `role`, `allowed`) VALUES
('Default', 'absences', 1, 1),
('Default', 'absences', 4, 0),
('Default', 'absences', 3, 0),
('Default', 'absences', 2, 0),
('Default', 'absenceedit', 1, 1),
('Default', 'absenceedit', 4, 0),
('Default', 'absenceedit', 3, 0),
('Default', 'absenceedit', 2, 0),
('Default', 'absenceicon', 1, 1),
('Default', 'absenceicon', 4, 0),
('Default', 'absenceicon', 3, 0),
('Default', 'absenceicon', 2, 0),
('Default', 'calendarview', 1, 1),
('Default', 'calendarview', 4, 0),
('Default', 'calendarview', 3, 0),
('Default', 'calendarview', 2, 0),
('Default', 'year', 1, 1),
('Default', 'year', 4, 0),
('Default', 'year', 3, 0),
('Default', 'year', 2, 0),
('Default', 'calendaredit', 1, 1),
('Default', 'calendaredit', 4, 0),
('Default', 'calendaredit', 3, 0),
('Default', 'calendaredit', 2, 0),
('Default', 'calendaroptions', 1, 1),
('Default', 'calendaroptions', 4, 0),
('Default', 'calendaroptions', 3, 0),
('Default', 'calendaroptions', 2, 0),
('Default', 'config', 1, 1),
('Default', 'config', 4, 0),
('Default', 'config', 3, 0),
('Default', 'config', 2, 0),
('Default', 'database', 1, 1),
('Default', 'database', 4, 0),
('Default', 'database', 3, 0),
('Default', 'database', 2, 0),
('Default', 'log', 1, 1),
('Default', 'log', 4, 0),
('Default', 'log', 3, 0),
('Default', 'log', 2, 0),
('Default', 'permissions', 1, 1),
('Default', 'permissions', 4, 0),
('Default', 'permissions', 3, 0),
('Default', 'permissions', 2, 0),
('Default', 'phpinfo', 1, 1),
('Default', 'phpinfo', 4, 0),
('Default', 'phpinfo', 3, 0),
('Default', 'phpinfo', 2, 0),
('Default', 'declination', 1, 1),
('Default', 'declination', 4, 1),
('Default', 'declination', 3, 0),
('Default', 'declination', 2, 0),
('Default', 'groups', 1, 1),
('Default', 'groups', 4, 0),
('Default', 'groups', 3, 0),
('Default', 'groups', 2, 0),
('Default', 'groupedit', 1, 1),
('Default', 'groupedit', 4, 0),
('Default', 'groupedit', 3, 0),
('Default', 'groupedit', 2, 0),
('Default', 'holidays', 1, 1),
('Default', 'holidays', 4, 0),
('Default', 'holidays', 3, 0),
('Default', 'holidays', 2, 0),
('Default', 'holidayedit', 1, 1),
('Default', 'holidayedit', 4, 0),
('Default', 'holidayedit', 3, 0),
('Default', 'holidayedit', 2, 0),
('Default', 'messages', 1, 1),
('Default', 'messages', 4, 1),
('Default', 'messages', 3, 0),
('Default', 'messages', 2, 1),
('Default', 'messageedit', 1, 1),
('Default', 'messageedit', 4, 1),
('Default', 'messageedit', 3, 0),
('Default', 'messageedit', 2, 0),
('Default', 'monthedit', 1, 1),
('Default', 'monthedit', 4, 0),
('Default', 'monthedit', 3, 0),
('Default', 'monthedit', 2, 0),
('Default', 'regions', 1, 1),
('Default', 'regions', 4, 0),
('Default', 'regions', 3, 0),
('Default', 'regions', 2, 0),
('Default', 'regionedit', 1, 1),
('Default', 'regionedit', 4, 0),
('Default', 'regionedit', 3, 0),
('Default', 'regionedit', 2, 0),
('Default', 'roles', 1, 1),
('Default', 'roles', 4, 0),
('Default', 'roles', 3, 0),
('Default', 'roles', 2, 0),
('Default', 'roleedit', 1, 1),
('Default', 'roleedit', 4, 0),
('Default', 'roleedit', 3, 0),
('Default', 'roleedit', 2, 0),
('Default', 'users', 1, 1),
('Default', 'users', 4, 0),
('Default', 'users', 3, 0),
('Default', 'users', 2, 0),
('Default', 'useredit', 1, 1),
('Default', 'useredit', 4, 0),
('Default', 'useredit', 3, 0),
('Default', 'useredit', 2, 0),
('Default', 'useradd', 1, 1),
('Default', 'useradd', 4, 0),
('Default', 'useradd', 3, 0),
('Default', 'useradd', 2, 0),
('Default', 'viewprofile', 1, 1),
('Default', 'viewprofile', 4, 1),
('Default', 'viewprofile', 3, 0),
('Default', 'viewprofile', 2, 1),
('Default', 'calendareditown', 1, 1),
('Default', 'calendareditown', 4, 0),
('Default', 'calendareditown', 3, 0),
('Default', 'calendareditown', 2, 0),
('Default', 'calendareditgroup', 1, 1),
('Default', 'calendareditgroup', 4, 0),
('Default', 'calendareditgroup', 3, 0),
('Default', 'calendareditgroup', 2, 0),
('Default', 'calendareditall', 1, 1),
('Default', 'calendareditall', 4, 0),
('Default', 'calendareditall', 3, 0),
('Default', 'calendareditall', 2, 0),
('Default', 'calendarviewgroup', 1, 1),
('Default', 'calendarviewgroup', 4, 1),
('Default', 'calendarviewgroup', 3, 0),
('Default', 'calendarviewgroup', 2, 1),
('Default', 'calendarviewall', 1, 1),
('Default', 'calendarviewall', 4, 1),
('Default', 'calendarviewall', 3, 1),
('Default', 'calendarviewall', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `description`) VALUES
(1, 'Default', 'Default Region'),
(2, 'Canada', 'Canada Region'),
(3, 'Germany', 'Germany Region');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `color` varchar(40) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `color`) VALUES
(1, 'Administrator', 'Application administrator', 'danger'),
(2, 'User', 'Standard role for logged in users', 'primary'),
(3, 'Public', 'All users not logged in', 'default'),
(4, 'Manager', 'Management Team', 'warning');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `month` char(2) DEFAULT NULL,
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
  UNIQUE KEY `template` (`username`,`year`,`month`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(40) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
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
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `firstname`, `lastname`, `email`, `role`, `locked`, `hidden`, `onhold`, `verify`, `bad_logins`, `grace_start`, `last_pw_change`, `last_login`, `created`) VALUES
('blightyear', 's7MuuIoROZfb2', 'Buzz', 'Lightyear', 'blightyear@yourserver.com', 4, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-03-20 20:05:22', '2006-10-06 20:01:18'),
('sman', 's7MuuIoROZfb2', '', 'Spiderman', 'sman@yourserver.com', 2, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-03-26 21:21:53', '2006-10-06 20:01:18'),
('mmouse', 's7MuuIoROZfb2', 'Mickey', 'Mouse', 'mmouse@yourserver.com', 4, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-12-11 19:32:40', '2006-10-06 20:01:18'),
('admin', 's77dWZwOIYXss', 'George', 'Lewe-Admin', 'webmaster@yourserver.com', 1, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2014-04-23 19:01:57', '2014-12-17 17:22:09', '2006-10-06 20:01:18'),
('phead', 's7MuuIoROZfb2', 'Potatoe', 'Head', 'phead@yourserver.com', 2, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-05-11 18:05:03', '2006-10-06 20:01:18'),
('ccarl', 's7MuuIoROZfb2', 'Coyote', 'Carl', 'ccarl@yourserver.com', 2, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2014-04-23 20:01:01', '2014-07-31 19:29:24', '2006-10-06 20:01:18'),
('dduck', 's7MuuIoROZfb2', 'Donald', 'Duck', 'dduck@yourserver.com', 2, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-05-18 20:05:20', '2006-09-04 01:01:50'),
('sgonzales', 's7MuuIoROZfb2', 'Speedy', 'Gonzales', 'sgonzales@yourserver.com', 2, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2012-12-07 22:16:43', '2006-09-04 21:01:14'),
('mimouse', 's7MuuIoROZfb2', 'Minnie', 'Mouse', 'mimouse@yourserver.com', 2, 1, 1, 1, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-03-20 20:05:22', '2006-10-06 20:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `type` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `username`, `groupid`, `type`) VALUES
(1, 'mmouse', 1, 'manager'),
(2, 'dduck', 1, 'member'),
(3, 'ccarl', 3, 'member'),
(4, 'phead', 4, 'member'),
(5, 'blightyear', 4, 'manager'),
(6, 'mimouse', 1, 'member'),
(7, 'sgonzales', 3, 'manager'),
(8, 'sman', 2, 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

DROP TABLE IF EXISTS `user_message`;
CREATE TABLE IF NOT EXISTS `user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `popup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `user_option`
--

DROP TABLE IF EXISTS `user_option`;
CREATE TABLE IF NOT EXISTS `user_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `option` varchar(40) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=128 ;

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
(13, 'admin', 'language', 'default'),
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
(29, 'ccarl', 'theme', 'amelia'),
(30, 'ccarl', 'language', 'default'),
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
(57, 'sgonzales', 'theme', 'amelia'),
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
(71, 'phead', 'theme', 'amelia'),
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
(99, 'mimouse', 'theme', 'amelia'),
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
(113, 'sman', 'theme', 'amelia'),
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
(127, 'mmouse', 'theme', 'amelia');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
