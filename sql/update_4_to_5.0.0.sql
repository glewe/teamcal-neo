--
-- Table updates
--
ALTER TABLE `tcneo_groups` ADD COLUMN `avatar` VARCHAR(255) NOT NULL DEFAULT 'default_group.png' AFTER `description`;

--
-- Record deletions
--
DELETE FROM `tcneo_config` WHERE `name` = "cookieConsentCDN";
DELETE FROM `tcneo_config` WHERE `name` = "faCDN";
DELETE FROM `tcneo_config` WHERE `name` = "footerViewport";
DELETE FROM `tcneo_config` WHERE `name` = "jQueryCDN";
DELETE FROM `tcneo_config` WHERE `name` = "useCaptcha";

--
-- Record inserts
--
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('productionMode', '1');
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorPresenceType', 'magenta');
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorRemainder', 'orange');
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorTrends', 'red');
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorDayofweek', 'purple');
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorDuration', 'orange');

--
-- Record updates
--
UPDATE `tcneo_config` SET `value` = 'sidebar' WHERE `name` = 'defaultMenu';
UPDATE `tcneo_config` SET `value` = 'red' WHERE `name` = 'statsDefaultColorAbsences';
UPDATE `tcneo_config` SET `value` = 'cyan' WHERE `name` = 'statsDefaultColorAbsencetype';
UPDATE `tcneo_config` SET `value` = 'green' WHERE `name` = 'statsDefaultColorPresences';
UPDATE `tcneo_config` SET `value` = 'magenta' WHERE `name` = 'statsDefaultColorRemainder';
