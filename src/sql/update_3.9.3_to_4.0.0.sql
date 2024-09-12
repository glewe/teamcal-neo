--
-- New tables
--
CREATE TABLE `tcneo_patterns`
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
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

--
-- Record deletions
--
DELETE FROM `tcneo_config` WHERE `name` = "showBanner";
DELETE FROM `tcneo_config` WHERE `name` = "theme";
DELETE FROM `tcneo_config` WHERE `name` = "menuBarBg";
DELETE FROM `tcneo_config` WHERE `name` = "menuBarDark";
DELETE FROM `tcneo_config` WHERE `name` = "allowUserTheme";
DELETE FROM `tcneo_user_option` WHERE `option` = "theme";
DELETE FROM `tcneo_user_option` WHERE `option` = "menuBar";

--
-- Record inserts
--
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseDelay', '3000');
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseDanger', '0');
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseSuccess', '1');
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseWarning', '0');

INSERT INTO `tcneo_permissions` (`id`, `scheme`, `permission`, `role`, `allowed`) VALUES (NULL, 'default', 'patternedit', 1, 1);

--
-- Added columns
--
ALTER TABLE `tcneo_log` ADD `ip` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `timestamp`;

--
-- Added indexes
--
ALTER TABLE `tcneo_absence_group` ADD INDEX `absence_group_absid` (`absid`);
ALTER TABLE `tcneo_absence_group` ADD INDEX `absence_group_groupid` (`groupid`);
ALTER TABLE `tcneo_allowances` ADD INDEX `allowance_username` (`username`);
ALTER TABLE `tcneo_archive_allowances` ADD INDEX `allowance_username` (`username`);
ALTER TABLE `tcneo_archive_daynotes` DROP INDEX `yyyymmdd`, ADD UNIQUE `daynote_daynote` (`yyyymmdd`,`username`,`region`);
ALTER TABLE `tcneo_archive_daynotes` ADD INDEX `daynote_username` (`username`);
ALTER TABLE `tcneo_archive_templates` ADD INDEX `template_username` (`username`);
ALTER TABLE `tcneo_archive_users` ADD INDEX `user_firstname` (`firstname`);
ALTER TABLE `tcneo_archive_users` ADD INDEX `user_lastname` (`lastname`);
ALTER TABLE `tcneo_archive_user_attachment` DROP INDEX `userAttachment`, ADD UNIQUE `user_attachment` (`username`,`fileid`);
ALTER TABLE `tcneo_archive_user_attachment` ADD INDEX `user_attachment_username` (`username`);
ALTER TABLE `tcneo_archive_user_group` ADD INDEX `user_group_username` (`username`);
ALTER TABLE `tcneo_archive_user_group` ADD INDEX `user_group_groupid` (`groupid`);
ALTER TABLE `tcneo_archive_user_message` ADD INDEX `user_message_username` (`username`);
ALTER TABLE `tcneo_archive_user_message` ADD INDEX `user_message_msgid` (`msgid`);
ALTER TABLE `tcneo_archive_user_option` ADD INDEX `user_option_username` (`username`);
ALTER TABLE `tcneo_archive_user_option` ADD INDEX `user_option_option` (`option`);
ALTER TABLE `tcneo_attachments` ADD INDEX `attachment_filename` (`filename`);
ALTER TABLE `tcneo_daynotes` DROP INDEX `yyyymmdd`, ADD UNIQUE `daynote_daynote` (`yyyymmdd`,`username`,`region`);
ALTER TABLE `tcneo_daynotes` ADD INDEX `daynote_username` (`username`);
ALTER TABLE `tcneo_groups` ADD INDEX `group_name` (`name`);
ALTER TABLE `tcneo_holidays` ADD INDEX `holiday_name` (`name`);
ALTER TABLE `tcneo_messages` ADD INDEX `message_type` (`type`);
ALTER TABLE `tcneo_months` DROP INDEX `month`, ADD UNIQUE `month_month` (`year`,`month`, `region`);
ALTER TABLE `tcneo_permissions` DROP INDEX `permission`, ADD UNIQUE `permission_permission` (`scheme`,`permission`, `role`);
ALTER TABLE `tcneo_permissions` ADD INDEX `permission_scheme` (`scheme`);
ALTER TABLE `tcneo_permissions` ADD INDEX `permission_name` (`permission`);
ALTER TABLE `tcneo_regions` ADD INDEX `region_name` (`name`);
ALTER TABLE `tcneo_roles` DROP INDEX `name`, ADD UNIQUE `role_name` (`name`);
ALTER TABLE `tcneo_templates` ADD INDEX `template_username` (`username`);
ALTER TABLE `tcneo_users` ADD INDEX `user_firstname` (`firstname`);
ALTER TABLE `tcneo_users` ADD INDEX `user_lastname` (`lastname`);
ALTER TABLE `tcneo_user_attachment` DROP INDEX `userAttachment`, ADD UNIQUE `user_attachment` (`username`,`fileid`);
ALTER TABLE `tcneo_user_attachment` ADD INDEX `user_attachment_username` (`username`);
ALTER TABLE `tcneo_user_group` ADD INDEX `user_group_username` (`username`);
ALTER TABLE `tcneo_user_group` ADD INDEX `user_group_groupid` (`groupid`);
ALTER TABLE `tcneo_user_message` ADD INDEX `user_message_username` (`username`);
ALTER TABLE `tcneo_user_message` ADD INDEX `user_message_msgid` (`msgid`);
ALTER TABLE `tcneo_user_option` ADD INDEX `user_option_username` (`username`);
ALTER TABLE `tcneo_user_option` ADD INDEX `user_option_option` (`option`);
