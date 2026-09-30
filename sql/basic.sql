-- TeamCal Neo database schema (6.0.0)
--
-- Redesigned per .agents/db-improvement.md. Conventions:
--   * ENGINE=InnoDB, utf8mb4 / utf8mb4_unicode_ci everywhere (was MyISAM,
--     mixed utf8/utf8mb4) - real transactions, row-level locking, FK support.
--   * Every implicit relationship found in the PHP model layer is now a
--     declared FOREIGN KEY. Deletion policy applied consistently:
--       - Ownership FKs (row belongs to exactly one user): ON DELETE CASCADE.
--       - Definitional FKs still in active use on LIVE tables (absence type,
--         holiday, role assigned to a user): ON DELETE RESTRICT - protects
--         reference data that's actually referenced.
--       - Soft/optional links and all archive-table references to
--         definitional data: nullable column + ON DELETE SET NULL - archived/
--         historical rows outlive the thing they once pointed to.
--       - Pure many-to-many join tables (both sides are just a pairing):
--         ON DELETE CASCADE on both FKs.
--   * tcneo_users gets a surrogate `id` PK; `username` stays UNIQUE NOT NULL
--     as the business key (login, search). Every other table's user FK is
--     `user_id INT UNSIGNED` referencing tcneo_users(id) - except
--     tcneo_log.user, kept as a denormalized VARCHAR(40) snapshot on purpose
--     (audit rows must stay readable after a user is renamed/deleted).
--   * tcneo_templates/tcneo_archive_templates (abs1..31 per user/month) and
--     tcneo_months (wday1..31/week1..31/hol1..31 per region/month) are
--     replaced by one-row-per-day tables: tcneo_absence_days,
--     tcneo_archive_absence_days, tcneo_calendar_days. wday/week are dropped
--     entirely (100% derivable from the `day` DATE column). tcneo_patterns'
--     abs1..7 columns move to a child table, tcneo_pattern_days.
--   * Archive tables (`tcneo_archive_*`) keep the same column shape as their
--     live counterpart so UserModel/TemplateModel-style `INSERT ... SELECT`
--     archive/restore logic keeps working; their `user_id` FK points at
--     tcneo_archive_users(id), never at the live tcneo_users table.
--   * `is_system` flags (tcneo_users, tcneo_holidays) replace the previous
--     hardcoded `username = 'admin'` / holiday `id > 3` checks scattered
--     through the model layer.
--   * 0-as-"none" sentinels (e.g. tcneo_absences.counts_as) become real
--     NULL, consistent with how absence/calendar days now only get a row
--     when something is actually assigned.
--
-- Floor: MariaDB 10.4+ / MySQL 8.0+ (CHECK constraints enforced, utf8mb4
-- cross-compatible collation).

SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */
;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */
;

/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */
;

/*!40101 SET NAMES utf8mb4 */
;

--
-- Database: `tcneo`
--
-- --------------------------------------------------------
--
-- Table structure for table `tcneo_roles`
--
DROP TABLE IF EXISTS `tcneo_roles`;

CREATE TABLE `tcneo_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `color` varchar(40) NOT NULL DEFAULT 'default',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci AUTO_INCREMENT = 6;

--
-- Dumping data for table `tcneo_roles`
--
-- Roles 4/5 (Manager, Instructor) are seeded here to match the permission
-- matrix below, which already grants them specific permissions - previously
-- basic.sql shipped those permission rows without the roles existing at all
-- (no FK to catch it); sample.sql already seeded both roles.
--
INSERT INTO
  `tcneo_roles` (`id`, `name`, `description`, `color`, `created`, `updated`)
VALUES
  (1, 'Administrator', 'Application administrator', 'danger', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (2, 'User', 'Standard role for logged in users', 'primary', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (3, 'Public', 'All users not logged in', 'secondary', '2026-02-01 18:11:39', '2026-02-01 18:11:39'),
  (4, 'Manager', 'Group manager role', 'warning', '2026-08-01 06:32:01', '2026-08-01 06:32:10'),
  (5, 'Instructor', 'Instructor role', 'success', '2026-08-01 06:34:15', '2026-08-01 06:34:15');

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_regions`
--
DROP TABLE IF EXISTS `tcneo_regions`;

CREATE TABLE `tcneo_regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci AUTO_INCREMENT = 2;

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

CREATE TABLE `tcneo_region_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `access` varchar(4) NOT NULL DEFAULT 'edit',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_region_role` (`region_id`, `role_id`),
  CONSTRAINT `fk_rr_region` FOREIGN KEY (`region_id`) REFERENCES `tcneo_regions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rr_role` FOREIGN KEY (`role_id`) REFERENCES `tcneo_roles` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_holidays`
--
DROP TABLE IF EXISTS `tcneo_holidays`;

CREATE TABLE `tcneo_holidays` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `color` varchar(6) NOT NULL DEFAULT '000000',
  `bgcolor` varchar(6) NOT NULL DEFAULT 'ffffff',
  `businessday` tinyint(1) NOT NULL DEFAULT 0,
  `noabsence` tinyint(1) NOT NULL DEFAULT 0,
  `keepweekendcolor` tinyint(1) NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci AUTO_INCREMENT = 6;

--
-- Dumping data for table `tcneo_holidays`
--
-- ids 1-3 (Business Day, Saturday, Sunday) are the built-ins previously
-- protected via a hardcoded `id > 3` check; is_system=1 replaces that.
--
INSERT INTO
  `tcneo_holidays` (
    `id`,
    `name`,
    `description`,
    `color`,
    `bgcolor`,
    `businessday`,
    `noabsence`,
    `keepweekendcolor`,
    `is_system`
  )
VALUES
  (1, 'Business Day', 'Regular business day', '000000', 'ffffff', 1, 0, 0, 1),
  (2, 'Saturday', 'Regular weekend day (Saturday)', '000000', 'fcfc9a', 1, 0, 0, 1),
  (3, 'Sunday', 'Regular weekend day (Sunday)', '000000', 'fcfc9a', 0, 0, 0, 1),
  (4, 'Public Holiday', 'Public bank holidays', '000000', 'EBAAAA', 0, 0, 0, 0),
  (5, 'School Holiday', 'School holidays', '000000', 'BFFFDF', 1, 0, 1, 0);

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_absences`
--
DROP TABLE IF EXISTS `tcneo_absences`;

CREATE TABLE `tcneo_absences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `symbol` char(1) NOT NULL DEFAULT 'A',
  `icon` varchar(40) NOT NULL,
  `color` varchar(6) NOT NULL,
  `bgcolor` varchar(6) NOT NULL,
  `bgtrans` tinyint(1) NOT NULL DEFAULT 0,
  `factor` float NOT NULL,
  `allowance` float NOT NULL,
  `allowmonth` float NOT NULL,
  `allowweek` float NOT NULL,
  `counts_as` int(10) unsigned DEFAULT NULL,
  `show_in_remainder` tinyint(1) NOT NULL,
  `show_totals` tinyint(1) NOT NULL,
  `approval_required` tinyint(1) NOT NULL,
  `counts_as_present` tinyint(1) NOT NULL,
  `manager_only` tinyint(1) NOT NULL,
  `hide_in_profile` tinyint(1) NOT NULL,
  `confidential` tinyint(1) NOT NULL,
  `takeover` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_counts_as` (`counts_as`),
  CONSTRAINT `fk_absences_counts_as` FOREIGN KEY (`counts_as`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_groups`
--
DROP TABLE IF EXISTS `tcneo_groups`;

CREATE TABLE `tcneo_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT 'default_group.png',
  `minpresent` smallint(6) NOT NULL DEFAULT 0,
  `maxabsent` smallint(6) NOT NULL DEFAULT 9999,
  `minpresentwe` smallint(6) NOT NULL DEFAULT 0,
  `maxabsentwe` smallint(6) NOT NULL DEFAULT 9999,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_absence_group`
--
DROP TABLE IF EXISTS `tcneo_absence_group`;

CREATE TABLE `tcneo_absence_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `absence_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_absence_group` (`absence_id`, `group_id`),
  CONSTRAINT `fk_ag_absence` FOREIGN KEY (`absence_id`) REFERENCES `tcneo_absences` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ag_group` FOREIGN KEY (`group_id`) REFERENCES `tcneo_groups` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_patterns`
--
-- abs1..abs7 columns removed - replaced by tcneo_pattern_days.
--
DROP TABLE IF EXISTS `tcneo_patterns`;

CREATE TABLE `tcneo_patterns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_pattern_days`
--
-- Replaces tcneo_patterns.abs1..abs7. One row per weekday actually
-- assigned in the pattern instead of 7 mostly-empty columns.
--
DROP TABLE IF EXISTS `tcneo_pattern_days`;

CREATE TABLE `tcneo_pattern_days` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pattern_id` int(10) unsigned NOT NULL,
  `weekday` tinyint(3) unsigned NOT NULL COMMENT '1=Monday..7=Sunday (ISO-8601)',
  `absence_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pattern_weekday` (`pattern_id`, `weekday`),
  CONSTRAINT `fk_pd_pattern` FOREIGN KEY (`pattern_id`) REFERENCES `tcneo_patterns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pd_absence` FOREIGN KEY (`absence_id`) REFERENCES `tcneo_absences` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_pd_weekday` CHECK (`weekday` BETWEEN 1 AND 7)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_users`
--
DROP TABLE IF EXISTS `tcneo_users`;

CREATE TABLE `tcneo_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `order_key` varchar(80) NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT 2,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `onhold` tinyint(1) NOT NULL DEFAULT 0,
  `verify` tinyint(1) NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `bad_logins` tinyint(4) NOT NULL DEFAULT 0,
  `bad_logins_start` int(10) unsigned NOT NULL DEFAULT 0,
  `grace_start` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `last_pw_change` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `oidc_sub` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_oidc_sub` (`oidc_sub`),
  KEY `idx_lastname_firstname` (`lastname`, `firstname`),
  KEY `idx_role_lastname_firstname` (`role_id`, `lastname`, `firstname`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `tcneo_roles` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `tcneo_users`
--
INSERT INTO
  `tcneo_users` (
    `id`,
    `username`,
    `password`,
    `firstname`,
    `lastname`,
    `email`,
    `order_key`,
    `role_id`,
    `locked`,
    `hidden`,
    `onhold`,
    `verify`,
    `is_system`,
    `bad_logins`,
    `grace_start`,
    `last_pw_change`,
    `last_login`,
    `created`,
    `oidc_sub`
  )
VALUES
  (
    1,
    'admin',
    '$2y$10$4E4xGXbIs1ldd.aN/knENOF/YTenqHylHhrErESXfBDIBIF/1FT2.',
    '',
    'Admin',
    'webmaster@yourserver.com',
    '0',
    1,
    0,
    0,
    0,
    0,
    1,
    0,
    '2024-01-01 00:00:00',
    '2024-09-07 19:12:50',
    '2024-09-19 20:33:29',
    '2022-01-01 00:00:00',
    NULL
  );

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_calendar_days`
--
-- Replaces tcneo_months (wday1..31/week1..31/hol1..31 per region/month).
-- wday/week are dropped entirely - both are 100% derivable from `day`
-- (DAYOFWEEK(day), WEEK(day, 3) for ISO weeks). Only the holiday override
-- is real per-day state.
--
DROP TABLE IF EXISTS `tcneo_calendar_days`;

CREATE TABLE `tcneo_calendar_days` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `holiday_id` int(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_region_day` (`region_id`, `day`),
  KEY `idx_day` (`day`),
  CONSTRAINT `fk_cd_region` FOREIGN KEY (`region_id`) REFERENCES `tcneo_regions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cd_holiday` FOREIGN KEY (`holiday_id`) REFERENCES `tcneo_holidays` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_absence_days`
--
-- Replaces tcneo_templates (abs1..31 per user/month). One row per assigned
-- day instead of 31 mostly-empty columns; UNIQUE(user_id, day) replaces the
-- whole class of "which absN column is this" bugs.
--
DROP TABLE IF EXISTS `tcneo_absence_days`;

CREATE TABLE `tcneo_absence_days` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `absence_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_day` (`user_id`, `day`),
  KEY `idx_day` (`day`),
  KEY `idx_absence` (`absence_id`),
  CONSTRAINT `fk_ad_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ad_absence` FOREIGN KEY (`absence_id`) REFERENCES `tcneo_absences` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_allowances`
--
DROP TABLE IF EXISTS `tcneo_allowances`;

CREATE TABLE `tcneo_allowances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `absence_id` int(10) unsigned NOT NULL,
  `carryover` smallint(6) NOT NULL DEFAULT 0,
  `allowance` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_absence` (`user_id`, `absence_id`),
  CONSTRAINT `fk_al_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_al_absence` FOREIGN KEY (`absence_id`) REFERENCES `tcneo_absences` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_attachments`
--
DROP TABLE IF EXISTS `tcneo_attachments`;

CREATE TABLE `tcneo_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `uploader_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_filename` (`filename`),
  CONSTRAINT `fk_att_uploader` FOREIGN KEY (`uploader_id`) REFERENCES `tcneo_users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_user_attachment`
--
DROP TABLE IF EXISTS `tcneo_user_attachment`;

CREATE TABLE `tcneo_user_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `attachment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_attachment` (`user_id`, `attachment_id`),
  CONSTRAINT `fk_ua_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ua_attachment` FOREIGN KEY (`attachment_id`) REFERENCES `tcneo_attachments` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_config`
--
DROP TABLE IF EXISTS `tcneo_config`;

CREATE TABLE `tcneo_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
  ('firstDayOfWeek', '1'),
  ('font', 'default'),
  ('footerCopyright', 'Lewe.com'),
  ('footerCopyrightUrl', 'https://www.lewe.com'),
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
  ('productionMode', '1'),
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
  ('statsDefaultColorTrends', 'red'),
  ('statsDefaultColorDayofweek', 'purple'),
  ('statsDefaultColorDuration', 'orange'),
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

CREATE TABLE `tcneo_daynotes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'NULL = global note (all users), was username = ''all'' sentinel',
  `region_id` int(10) unsigned DEFAULT NULL COMMENT 'NULL = regionless',
  `daynote` text DEFAULT NULL,
  `color` varchar(16) NOT NULL DEFAULT 'default',
  `confidential` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_day_user_region` (`day`, `user_id`, `region_id`),
  CONSTRAINT `fk_dn_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dn_region` FOREIGN KEY (`region_id`) REFERENCES `tcneo_regions` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_log`
--
DROP TABLE IF EXISTS `tcneo_log`;

CREATE TABLE `tcneo_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(40) DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `ip` varchar(40) DEFAULT NULL,
  `user` varchar(40) DEFAULT NULL COMMENT 'Denormalized username snapshot, no FK - log rows survive user rename/delete',
  `event` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_log_timestamp` (`timestamp`),
  KEY `idx_log_user` (`user`),
  KEY `idx_log_type` (`type`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_messages`
--
DROP TABLE IF EXISTS `tcneo_messages`;

CREATE TABLE `tcneo_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `text` text NOT NULL,
  `type` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_user_message`
--
DROP TABLE IF EXISTS `tcneo_user_message`;

CREATE TABLE `tcneo_user_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  `popup` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_message` (`user_id`, `message_id`),
  CONSTRAINT `fk_um_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_um_message` FOREIGN KEY (`message_id`) REFERENCES `tcneo_messages` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_permissions`
--
DROP TABLE IF EXISTS `tcneo_permissions`;

CREATE TABLE `tcneo_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scheme` varchar(80) NOT NULL,
  `permission` varchar(40) NOT NULL,
  `role_id` int(10) unsigned NOT NULL DEFAULT 1,
  `allowed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_scheme_permission_role` (`scheme`, `permission`, `role_id`),
  KEY `idx_scheme` (`scheme`),
  KEY `idx_permission` (`permission`),
  CONSTRAINT `fk_perm_role` FOREIGN KEY (`role_id`) REFERENCES `tcneo_roles` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `tcneo_permissions`
--
INSERT INTO
  `tcneo_permissions` (`scheme`, `permission`, `role_id`, `allowed`)
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
-- Table structure for table `tcneo_user_group`
--
DROP TABLE IF EXISTS `tcneo_user_group`;

CREATE TABLE `tcneo_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `type` enum('member', 'manager', 'guest') NOT NULL DEFAULT 'member',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_group` (`user_id`, `group_id`),
  KEY `idx_group_user` (`group_id`, `user_id`),
  CONSTRAINT `fk_ug_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ug_group` FOREIGN KEY (`group_id`) REFERENCES `tcneo_groups` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_user_option`
--
DROP TABLE IF EXISTS `tcneo_user_option`;

CREATE TABLE `tcneo_user_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `option` varchar(40) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_option` (`user_id`, `option`),
  KEY `idx_option` (`option`),
  CONSTRAINT `fk_uo_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `tcneo_user_option`
--
INSERT INTO
  `tcneo_user_option` (`user_id`, `option`, `value`)
VALUES
  (1, 'avatar', 'is_administrator.png'),
  (1, 'calendarMonths', 'default'),
  (1, 'calfilterAbs', 'all'),
  (1, 'calfilterGroup', 'all'),
  (1, 'calfilterRegion', '1'),
  (1, 'calViewMode', 'fullmonth'),
  (1, 'custom1', ''),
  (1, 'custom2', ''),
  (1, 'custom3', ''),
  (1, 'custom4', ''),
  (1, 'custom5', ''),
  (1, 'defaultMenu', 'sidebar'),
  (1, 'facebook', ''),
  (1, 'gender', 'male'),
  (1, 'google', ''),
  (1, 'id', ''),
  (1, 'language', 'english'),
  (1, 'linkedin', ''),
  (1, 'menuBar', 'default'),
  (1, 'mobile', ''),
  (1, 'notifyAbsenceEvents', '1'),
  (1, 'notifyCalendarEvents', '1'),
  (1, 'notifyGroupEvents', '1'),
  (1, 'notifyHolidayEvents', '1'),
  (1, 'notifyMonthEvents', '1'),
  (1, 'notifyNone', '0'),
  (1, 'notifyRoleEvents', '1'),
  (1, 'notifyUserCalEvents', '1'),
  (1, 'notifyUserCalEventsOwn', '0'),
  (1, 'notifyUserCalGroups', '0'),
  (1, 'notifyUserEvents', '1'),
  (1, 'phone', ''),
  (1, 'position', 'Administrator'),
  (1, 'region', '1'),
  (1, 'showMonths', '1'),
  (1, 'skype', ''),
  (1, 'title', ''),
  (1, 'twitter', ''),
  (1, 'verifycode', ''),
  (1, 'width', 'full');

-- --------------------------------------------------------
--
-- Archive tables. Same column shape as their live counterpart so
-- UserModel/AbsenceDayModel-style `INSERT ... SELECT` archive/restore
-- logic keeps working. `user_id` FKs point at tcneo_archive_users(id), not
-- at the live tcneo_users table. References to definitional data (roles,
-- absences, groups, messages, attachments, regions) use ON DELETE SET NULL
-- rather than RESTRICT/CASCADE - historical rows should outlive the thing
-- they once pointed to, never block its deletion or silently disappear
-- with it.
--
-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_users`
--
DROP TABLE IF EXISTS `tcneo_archive_users`;

CREATE TABLE `tcneo_archive_users` (
  `id` int(10) unsigned NOT NULL,
  `username` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `order_key` varchar(80) NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `onhold` tinyint(1) NOT NULL DEFAULT 0,
  `verify` tinyint(1) NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `bad_logins` tinyint(4) NOT NULL DEFAULT 0,
  `bad_logins_start` int(10) unsigned NOT NULL DEFAULT 0,
  `grace_start` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `last_pw_change` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `oidc_sub` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_firstname` (`firstname`),
  KEY `idx_lastname` (`lastname`),
  CONSTRAINT `fk_ausers_role` FOREIGN KEY (`role_id`) REFERENCES `tcneo_roles` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_absence_days`
--
DROP TABLE IF EXISTS `tcneo_archive_absence_days`;

CREATE TABLE `tcneo_archive_absence_days` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `absence_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_day` (`user_id`, `day`),
  CONSTRAINT `fk_aad_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aad_absence` FOREIGN KEY (`absence_id`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_allowances`
--
DROP TABLE IF EXISTS `tcneo_archive_allowances`;

CREATE TABLE `tcneo_archive_allowances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `absence_id` int(10) unsigned DEFAULT NULL,
  `carryover` smallint(6) DEFAULT 0,
  `allowance` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_aal_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aal_absence` FOREIGN KEY (`absence_id`) REFERENCES `tcneo_absences` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_daynotes`
--
DROP TABLE IF EXISTS `tcneo_archive_daynotes`;

CREATE TABLE `tcneo_archive_daynotes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `daynote` text DEFAULT NULL,
  `color` varchar(16) NOT NULL DEFAULT 'default',
  `confidential` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_adn_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_adn_region` FOREIGN KEY (`region_id`) REFERENCES `tcneo_regions` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_attachment`
--
DROP TABLE IF EXISTS `tcneo_archive_user_attachment`;

CREATE TABLE `tcneo_archive_user_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `attachment_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_aua_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aua_attachment` FOREIGN KEY (`attachment_id`) REFERENCES `tcneo_attachments` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_group`
--
DROP TABLE IF EXISTS `tcneo_archive_user_group`;

CREATE TABLE `tcneo_archive_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `type` enum('member', 'manager', 'guest') NOT NULL DEFAULT 'member',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_aug_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aug_group` FOREIGN KEY (`group_id`) REFERENCES `tcneo_groups` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_message`
--
DROP TABLE IF EXISTS `tcneo_archive_user_message`;

CREATE TABLE `tcneo_archive_user_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message_id` int(10) unsigned DEFAULT NULL,
  `popup` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_aum_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aum_message` FOREIGN KEY (`message_id`) REFERENCES `tcneo_messages` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `tcneo_archive_user_option`
--
DROP TABLE IF EXISTS `tcneo_archive_user_option`;

CREATE TABLE `tcneo_archive_user_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `option` varchar(40) DEFAULT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_option` (`option`),
  CONSTRAINT `fk_auo_user` FOREIGN KEY (`user_id`) REFERENCES `tcneo_archive_users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */
;

/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */
;

/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */
;
