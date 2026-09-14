--
-- TeamCal Neo update script: 5.3.6 → 5.3.7
--
-- Security release. Adds the bad_logins_start column to tcneo_users and
-- tcneo_archive_users. It records when the current bad login window began,
-- as seconds since the UNIX epoch, so that login throttling expires on its
-- own instead of relying on the administrative 'locked' flag.
--
-- The column is added to tcneo_archive_users as well because UserModel::archive()
-- copies rows with INSERT INTO ... SELECT u.*, which requires both tables to
-- have the same column count.
--
-- Run once against your existing database before upgrading the application.
--

ALTER TABLE `tcneo_users`
  ADD COLUMN `bad_logins_start` INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `bad_logins`;

ALTER TABLE `tcneo_archive_users`
  ADD COLUMN `bad_logins_start` INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `bad_logins`;

--
-- Accounts locked by the bad login bug in 5.3.6 and earlier cannot be told
-- apart from accounts an administrator disabled deliberately, because both
-- states share the 'locked' flag. They are therefore left as they are and an
-- administrator clears them. Review locked accounts after upgrading:
--
--   SELECT username, locked, bad_logins FROM `tcneo_users` WHERE locked = 1;
--
