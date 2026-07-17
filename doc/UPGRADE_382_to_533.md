# Upgrade guide: 3.2.8 -> 5.3.3

This document describes a safe upgrade path from TeamCal Neo **3.2.8** to **5.3.3**.

## Summary

- Backup everything (files + database).
- Preserve a copy of your legacy configuration files from the 3.2.8 installation: `config/config.db.php` and `config/config.app.php`. `.env` file does not exist yet in 3.2.8.
- Overwrite the installation with the TeamCal Neo 5.3.3 release files.
- Run the required SQL upgrade script (see below).
- Adjust configuration files and remove `installation.php`.

## Prerequisites

- Ensure you have a recent filesystem backup and a database dump.
- Have access to a database tool (phpMyAdmin, mysql CLI, or similar).
- PHP and server requirements: verify PHP version compatibility (TeamCal Neo 5 recommends PHP 8.x — check the 5.3.3 release notes).

## Recommended upgrade steps

1. Backup
   - Export a full database dump and copy your installation directory to a safe location.
2. Preserve configuration
   - Keep a copy of `config/config.db.php` and `config/config.app.php` from your current 3.2.8 installation.
3. Prepare new files
   - Download the TeamCal Neo **5.3.3** release and unzip it to a temporary folder.
4. Replace files
   - From your current installation directory, keep your saved `config/*` files.
   - Copy the contents of the 5.3.3 release into your installation directory.
5. Database schema updates (important)
    - Apply the SQL statements below in the given order. You can run them with phpMyAdmin, the `mysql` CLI, or any database tool. These statements are the same as those shipped in the release SQL files and archive.

    ```sql
    -- === upgrade_3.9.3_to_4.0.0.sql (apply first) ===
    -- New tables
    CREATE TABLE `tcneo_patterns`
    (
       `id`          int(11) NOT NULL AUTO_INCREMENT,
       `name`        varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
       `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
       `abs1`        int(11) DEFAULT NULL,
       `abs2`        int(11) DEFAULT NULL,
       `abs3`        int(11) DEFAULT NULL,
       `abs4`        int(11) DEFAULT NULL,
       `abs5`        int(11) DEFAULT NULL,
       `abs6`        int(11) DEFAULT NULL,
       `abs7`        int(11) DEFAULT NULL,
       PRIMARY KEY (`id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

    -- Record deletions
    DELETE FROM `tcneo_config` WHERE `name` = "showBanner";
    DELETE FROM `tcneo_config` WHERE `name` = "theme";
    DELETE FROM `tcneo_config` WHERE `name` = "menuBarBg";
    DELETE FROM `tcneo_config` WHERE `name` = "menuBarDark";
    DELETE FROM `tcneo_config` WHERE `name` = "allowUserTheme";
    DELETE FROM `tcneo_user_option` WHERE `option` = "theme";
    DELETE FROM `tcneo_user_option` WHERE `option` = "menuBar";

    -- Record inserts
    INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseDelay', '3000');
    INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseDanger', '0');
    INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseSuccess', '1');
    INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'alertAutocloseWarning', '0');
    INSERT INTO `tcneo_config` (`id`, `name`, `value`) VALUES (NULL, 'defaultMenu', 'navbar');

    INSERT INTO `tcneo_permissions` (`id`, `scheme`, `permission`, `role`, `allowed`) VALUES (NULL, 'default', 'patternedit', 1, 1);

    -- Added columns and indexes
    ALTER TABLE `tcneo_log` ADD `ip` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `timestamp`;

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

    -- === update_4_to_5.0.0.sql (apply next) ===
    -- Table updates
    ALTER TABLE `tcneo_groups` ADD COLUMN `avatar` VARCHAR(255) NOT NULL DEFAULT 'default_group.png' AFTER `description`;
    ALTER TABLE `tcneo_log` ADD COLUMN `ip` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL AFTER `timestamp`;

    -- Record deletions
    DELETE FROM `tcneo_config` WHERE `name` = "cookieConsentCDN";
    DELETE FROM `tcneo_config` WHERE `name` = "faCDN";
    DELETE FROM `tcneo_config` WHERE `name` = "footerViewport";
    DELETE FROM `tcneo_config` WHERE `name` = "jQueryCDN";
    DELETE FROM `tcneo_config` WHERE `name` = "useCaptcha";

    -- Record inserts
    INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('productionMode', '1');
    INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorPresencetype', 'magenta');
    INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorRemainder', 'orange');
    INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorTrends', 'red');
    INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorDayofweek', 'purple');
    INSERT INTO `tcneo_config` (`name`, `value`) VALUES ('statsDefaultColorDuration', 'orange');

    -- Record updates
    UPDATE `tcneo_config` SET `value` = 'sidebar' WHERE `name` = 'defaultMenu';
    UPDATE `tcneo_config` SET `value` = 'red' WHERE `name` = 'statsDefaultColorAbsences';
    UPDATE `tcneo_config` SET `value` = 'cyan' WHERE `name` = 'statsDefaultColorAbsencetype';
    UPDATE `tcneo_config` SET `value` = 'green' WHERE `name` = 'statsDefaultColorPresences';
    UPDATE `tcneo_config` SET `value` = 'magenta' WHERE `name` = 'statsDefaultColorRemainder';

    -- === update_5.2.0_to_5.3.0.sql (apply last) ===
    -- Adds the oidc_sub column to tcneo_users and tcneo_archive_users
    ALTER TABLE `tcneo_users`
       ADD COLUMN `oidc_sub` VARCHAR(255) DEFAULT NULL AFTER `created`,
       ADD UNIQUE KEY `uk_oidc_sub` (`oidc_sub`);

    ALTER TABLE `tcneo_archive_users`
       ADD COLUMN `oidc_sub` VARCHAR(255) DEFAULT NULL AFTER `created`,
       ADD UNIQUE KEY `uk_oidc_sub` (`oidc_sub`);
    ```

    - Run the statements in the order shown. Some statements (index/add-column) may be fast; others may take time on large datasets — run during maintenance windows.
6. Configuration changes
   - Migrate to TeamCal Neo 5.x configuration style: Rename `.env.example` in the root directory to `.env` and enter your credentials, e.g. `APP_INSTALLED`, database credentials, and optional LDAP/OIDC settings.
   - Verify `APP_INSTALLED` is present/appropriate. Older installs might require setting `define('APP_INSTALLED',"1");` in `config/config.app.php` for very old upgrade steps, but TeamCal Neo 5 prefers `.env`.
   - If you use LDAP, migrate LDAP keys from your old `config/config.app.php` into `.env` (see `.env.example` and the 5.x docs).
   - If you want to enable OIDC (SSO) after upgrading to 5.3.x, add the `OIDC_*` keys to `.env` (see `.env.example`).
7. Final cleanup
   - Remove `installation.php` from the installation root if present.
   - Clear caches if applicable (e.g., delete contents of `temp/twig` and `cache` folders).
8. Verify
   - Log in as an administrator and review Administration → Framework Configuration.
   - If upgrading across major versions, open Database Management → Repair and run the structure check / repair.
   - Test authentication (local, LDAP, or OIDC) and core workflows.

## Rollback strategy

- If anything goes wrong: restore the filesystem backup and import the database dump you created in step 1.

## References

- See `doc/UPGRADEINFO.md` for the original, per-version change log and notes.
