# TeamCal Neo Upgrade Information

## [5.3.1] -> [5.3.2]

1. Backup your current files and database!
2. Keep a copy of your `.env` file.
3. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. Delete `installation.php` from the root directory.

> **No database changes** — no SQL upgrade script needs to be run.

## [5.3.0] -> [5.3.1]

1. Backup your current files and database!
2. Keep a copy of your `.env` file.
3. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. Delete `installation.php` from the root directory.

> **No database changes** — no SQL upgrade script needs to be run.

## [5.2.x] -> [5.3.0]

> **New feature:** OIDC / Single Sign-On authentication via OpenID Connect.
> See the [OIDC Authentication admin guide](https://lewe.gitbook.io/teamcal-neo/administration/oidc-authentication) for full configuration instructions.

1. Backup your current files and database!
2. Keep a copy of your `.env` file.
3. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. Run the database upgrade script using phpMyAdmin or any other database management tool:
   ```
   sql/update_5.2.0_to_5.3.0.sql
   ```
   This adds the `oidc_sub` column to `tcneo_users` and `tcneo_archive_users`. Existing rows are unaffected (`oidc_sub` defaults to `NULL`).
6. **(OIDC users only)** If you want to enable SSO login, add the following keys to your `.env` file (see `.env.example` for descriptions):
   ```
   OIDC_YES=1
   OIDC_PROVIDER_URL=https://your-idp.example.com/realms/your-realm
   OIDC_CLIENT_ID=teamcal-neo
   OIDC_CLIENT_SECRET=your-client-secret
   OIDC_REDIRECT_URI=https://your-teamcal-server.com/index.php?action=oidccallback
   ```
   Leave `OIDC_YES=0` (or omit the variable) to keep the existing login behaviour unchanged.
7. Delete `installation.php` from the root directory.

## [5.1.x] -> [5.2.x]

> **Architecture change:** `APP_INSTALLED` and LDAP settings have moved from
> `config/config.app.php` into `.env`. You no longer need to edit
> `config/config.app.php` after an upgrade.

1. Backup your current files and database!
2. Keep a copy of your `.env` file (it holds your database credentials and will
   be preserved, but a backup is always good practice).
3. Delete all files and folders from your TeamCal Neo installation directory,
   **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. **(LDAP users only)** If you use LDAP authentication, migrate your settings
   from the old `config/config.app.php` backup into `.env`. Add the following
   keys (see `.env.example` for reference and descriptions):
   ```
   LDAP_YES=1
   LDAP_ADS=0
   LDAP_HOST=your-ldap-host
   LDAP_PORT=389
   LDAP_PASS=your-ldap-password
   LDAP_DIT=cn=admin,dc=example,dc=com
   LDAP_SBASE=dc=example,dc=com
   LDAP_TLS=0
   LDAP_CHECK_ANONYMOUS_BIND=0
   LDAP_SEARCH_BIND=0
   ```
   Once in `.env`, these settings survive all future upgrades automatically.
6. Your database configuration remains in `.env` — no changes needed there.
7. `APP_INSTALLED` is now detected automatically from the presence of your
   `.env` file. No manual edit of `config/config.app.php` is required.
8. Delete `installation.php` from the root directory.

## [5.1.0] -> [5.1.x]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 5 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. Adjust your database configuration either in `.env` or `config/config.db.php` (depending on what you use).
6. Delete file installation.php in the root directory.

## [5.0.9] -> [5.1.0]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 5 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. Adjust your database configuration either in `.env` or `config/config.db.php` (depending on what you use).
6. Delete file installation.php in the root directory.

## [5.0.x] -> [5.0.9]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 5 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. Adjust your database configuration either in `.env` or `config/config.db.php` (depending on what you use).
6. Delete file installation.php in the root directory.

## [4.3.x] -> [5.1.x]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 4 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. **Configuration:**
   - **Option A (Recommended):** Rename `.env.example` in the root directory to `.env` and enter your database credentials there.
   - **Option B (Legacy):** Edit `config/config.db.php` and enter your database credentials directly.
6. Delete file installation.php in the root directory.
7. Login as admin and open the Database Management page
8. Check the 'Database Structure' box and click 'Repair'. Confirm the dialog with the findings.

## [4.3.x] -> [5.0.x]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 4 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. **Configuration:**
   - **Option A (Recommended):** Rename `.env.example` in the root directory to `.env` and enter your database credentials there.
   - **Option B (Legacy):** Edit `config/config.db.php` and enter your database credentials directly.
6. Run the database upgrade script `sql/update_4_to_5.0.0.sql` using phpMyAdmin or any other database management tool
7. Delete file installation.php in the root directory.

## [4.3.3] -> [4.3.4]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.3.2] -> [4.3.3]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.3.1] -> [4.3.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.2.0] -> [4.3.1]

1. Backup your current files and database!
2. From your 4.2.0 installation directory, delete the following folders:
   - fonts/font-awesome/6.7.2
   - themes/bootstrap
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: ``define('APP_INSTALLED',"1");``
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.
6. Login as admin,
   - open the Framework Configuration page and click Save (to add new settings to your database)
   - open the Calendar Options page and click Apply (to add new settings to your database)
7. A new language architecture is active when you install the new files. If you have
   created your own language files based on the old architecture, you can switch to
   the old architecture by editing config/config.app.php and set: `define('USE_SPLIT_LANGUAGE_FILES', false);`

## [4.1.5] -> [4.2.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.1.4] -> [4.1.5]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.1.3] -> [4.1.4]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.1.3] -> [4.1.3]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.1.1] -> [4.1.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.1.0] -> [4.1.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [4.0.0] -> [4.1.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.9.3] -> [4.0.0]

1. Backup your current files and database!
2. Delete the following folders:
   - /addons/google-code-prettify
   - /addons/select2
   - /addons/x-editable
   - /themes/(all folders but 'bootstrap')
   - /images/icons/logo-*.png
3. Download the new release and overwrite all files.
4. Change in your database:
   Run the SQL statements from the file:
   - /sql/upgrade_3.9.3_to_4.0.0.sql
5. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
6. Edit config/config.db.php and update your database settings from your backup.
7. Delete file installation.php in the root directory.

## [3.9.2] -> [3.9.3]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.9.1] -> [3.9.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

##[ 3.9.0] -> [3.9.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.8.2] -> [3.9.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.8.1] -> [3.8.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.8.0] -> [3.8.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.7.5] -> [3.8.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.
6. Delete obsolete folders:
   - /fonts/font-awesome/6.2.1

## [3.7.4] -> [3.7.5]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Change in your database:
   ```ALTER TABLE `tcneo_archive_users` ADD `order_key` VARCHAR(80) NOT NULL DEFAULT '0' AFTER `email`;```
4. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
5. Edit config/config.db.php and update your database settings from your backup.
6. Delete file installation.php in the root directory.

## [3.7.3] -> [3.7.4]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.7.2] -> [3.7.3]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.7.1] -> [3.7.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Change in your database:
   ```ALTER TABLE `tcneo_users` ADD `order_key` VARCHAR(80) NOT NULL DEFAULT '0' AFTER `email`;```
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.7.0] -> [3.7.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.6.0] -> [3.7.0]

0. Recommended PHP version: 8.1
1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.5.2] -> [3.6.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory.

## [3.5.1] -> [3.5.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.5.0] -> [3.5.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.4.1] -> [3.5.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.4.0] -> [3.4.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.3.0] -> [3.4.0]

1. Backup your current files and database!
2. From your 3.3.0 installation directory, delete the following folders:
   - fonts/font-awesome/5.12.0
   - themes/bootstrap
3. Download the new release and overwrite all files.
4. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
5. Edit config/config.db.php and update your database settings from your backup.
6. Delete file installation.php in the root directory

## [3.2.8] -> [3.3.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.7] -> [3.2.8]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

# [3.2.6] -> [3.2.7]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.5] -> [3.2.6]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.4] -> [3.2.5]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.3] -> [3.2.4]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.2] -> [3.2.3]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.1] -> [3.2.2]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.2.0] -> [3.2.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.1.0] -> [3.2.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory
6. Database Changes:
```
ALTER TABLE `tcneo_groups` ADD `minpresentwe` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `maxabsent`, ADD `maxabsentwe` SMALLINT(6) NOT NULL DEFAULT '9999' AFTER `minpresentwe`;
```

## [3.0.1] -> [3.1.0]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [3.0.0] -> [3.0.1]

1. Backup your current files and database!
2. Download the new release and overwrite all files.
3. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
4. Edit config/config.db.php and update your database settings from your backup.
5. Delete file installation.php in the root directory

## [2.2.3] -> [3.0.0]

ATTENTION !
TeamCal Neo 3 now requires a proper license and is not free anymore.
However, a separate free version with restricted features, TeamCal Neo Basic,
is available too. Read all about it here:
https://www.lewe.com/teamcal-neo

Only update to TeamCal Neo 3 after you obtained a valid license. You can then
activate and register your license from within your installation.
This process is documented here:
https://lewe.gitbook.io/teamcal-neo/readme/teamcal-neo-license

1. Go to Administration -> Framework Configuration -> Theme
   - Select the 'bootstrap' theme
   - Uncheck 'Allow User Theme'
     This is a precautionary measure because the following themes are not
     available anymore in TeamCal Neo 3+:
     - paper
     - readable
     Make sure that no user has selected this theme before you switch it
     back on.
2. Backup your current files and database!
3. Download the new release and overwrite all files.
4. Edit config/config.app.php and change line 39 to: `define('APP_INSTALLED',"1");`
5. Edit config/config.db.php and update your database settings from your backup.
6. Delete file installation.php in the root directory
7. Go to Administration -> Framework Configuration -> License tab
   - Activate and register your license
