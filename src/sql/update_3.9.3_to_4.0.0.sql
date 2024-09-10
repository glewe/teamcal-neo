DELETE FROM `tcneo_config` WHERE `name` = "showBanner";
DELETE FROM `tcneo_config` WHERE `name` = "theme";
DELETE FROM `tcneo_config` WHERE `name` = "menuBarBg";
DELETE FROM `tcneo_config` WHERE `name` = "menuBarDark";
DELETE FROM `tcneo_config` WHERE `name` = "allowUserTheme";
DELETE FROM `tcneo_user_option` WHERE `option` = "theme";
DELETE FROM `tcneo_user_option` WHERE `option` = "menuBar";

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

ALTER TABLE `tcneo_log` ADD `ip` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `timestamp`;

INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseDelay', '3000');
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseDanger', '0');
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseSuccess', '1');
INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseWarning', '0');

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
