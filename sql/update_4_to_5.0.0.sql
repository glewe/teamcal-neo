--
-- Record deletions
--
DELETE FROM `tcneo_config` WHERE `name` = "footerViewport";
DELETE FROM `tcneo_config` WHERE `name` = "useCaptcha";

--
-- Record inserts
--
INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorRemainder', 'orange');

--
-- Record updates
--
UPDATE `tcneo_config` SET `value` = 'red' WHERE `name` = 'statsDefaultColorAbsences';
UPDATE `tcneo_config` SET `value` = 'cyan' WHERE `name` = 'statsDefaultColorAbsencetype';
UPDATE `tcneo_config` SET `value` = 'green' WHERE `name` = 'statsDefaultColorPresences';
UPDATE `tcneo_config` SET `value` = 'magenta' WHERE `name` = 'statsDefaultColorRemainder';
