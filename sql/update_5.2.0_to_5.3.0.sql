--
-- TeamCal Neo update script: 5.2.0 → 5.3.0
--
-- Adds the oidc_sub column to tcneo_users and tcneo_archive_users.
-- This column stores the permanent subject identifier (sub claim) issued
-- by the Identity Provider, used for OIDC authentication.
--
-- Run once against your existing database before upgrading the application.
--

ALTER TABLE `tcneo_users`
  ADD COLUMN `oidc_sub` VARCHAR(255) DEFAULT NULL AFTER `created`,
  ADD UNIQUE KEY `uk_oidc_sub` (`oidc_sub`);

ALTER TABLE `tcneo_archive_users`
  ADD COLUMN `oidc_sub` VARCHAR(255) DEFAULT NULL AFTER `created`,
  ADD UNIQUE KEY `uk_oidc_sub` (`oidc_sub`);
