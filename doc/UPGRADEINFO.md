# TeamCal Neo Upgrade Information

## [5.3.7] -> [6.0.0]

> **!! Major release with breaking changes. !!**
>
> The database was restructured (InnoDB with
> foreign keys, numeric user ids, one row per absence day instead of one row per month).
> 6.0.0 is **not compatible with a 5.x database**, and the database cannot be upgraded with
> an SQL script in phpMyAdmin. Your data is carried over by an import instead.
> You must be on **5.3.7** first. Older installations: upgrade to 5.3.7, then to 6.0.0.

**Requirements:** MariaDB 10.4 or newer, or MySQL 8.0 or newer, for the new database.
No command line access is needed.

### Upgrade by import (recommended)

#### Basic steps

1. Install a fresh 6.0.0 instance with Basic data next to your existing 5.3.7 one
2. Login as admin in 6.0.0 and go to Database Administration, tab "Import from 5.3.7"
3. Enter the information from 5.3.7 and start the import
4. That's it. Check functionality

#### Detailed steps

You install 6.0.0 **next to** your old installation, in its own folder and with its own
database, and then import the data of the old installation from within the new one.
Your old installation and its database are only read, never changed, so you can go back
to it at any time.

1. **Backup your current files and database.** The import does not change them, but a
   backup is always good practice. In phpMyAdmin: select your database, click **Export**,
   choose **Custom** and save the `.sql` file.
2. Download the new release and unzip all files into a **new folder** on your server,
   next to the old one (for example `tcneo6` next to `tcneo5`).
3. Create a **new, empty database** for it (or use another table prefix in the same database).
4. Open the new folder in your browser. The installation script starts. Enter the
   connection settings of the **new** database and choose **Basic data**.
5. Log in to the new installation as `admin` and open **Administration -> Database**.
6. Open the **Import from 5.3.7** tab:
   - Enter the folder of your old installation, either the full path or relative to the
     new installation (for example `../tcneo5`). The database connection is read from the
     old `.env` file. If the old installation has no `.env` file, open *Database connection
     of the old installation* and fill in the old database settings.
   - Click **Check old installation**. It reports whether the old database can be imported
     and shows what it contains. If it finds a problem, it tells you how to fix it in the
     old installation; then check again.
   - Type `IMPORT` and click **Start import**. Keep the page open until it is finished.
     Large calendars can take a few minutes.
7. When it is finished, check your calendar, users, groups and reports. The report shows
   how many rows were carried over and lists anything that could not be, because it
   referenced data that no longer exists.
8. Delete `installation.php` from the root directory of the new installation.
9. Copy the settings that live in the `.env` file (LDAP, OIDC, `APP_SECRET`,
   `APPLICATION_URL`) from the old `.env` file, if you use them. They are not part of the
   database. Point your users to the new address, or swap the folders.
10. When you are satisfied, you can remove the old installation and its database.

**What the import carries over**

- All data: users, groups, absences, calendar, patterns, permissions, announcements,
  the log and the archive.
- **Avatars and attachments**, copied from `public/upload/avatars` and
  `public/upload/files` of the old folder. Files that already exist in the new
  installation are not overwritten, and script files (for example `.php`) are not copied.
  If the server does not allow the new installation to read the old folder, leave the
  folder field empty, import the database only, and copy the contents of those two folders
  yourself (for example with FTP).
- **Settings** (Framework Configuration, Calendar Options and so on) wherever they still
  exist in 6.0.0. Settings that no longer exist are listed in the report. Two settings
  belong to the installation itself and are not carried over: *Application URL* and
  *Under maintenance*.

The administrator password of the new installation becomes the one from your old
installation. Other passwords and all user options are carried over as they are.

If the import fails, the new installation is set back to its fresh state automatically and
you can start again. You can also cancel an interrupted import on the same tab.

The new installation has to be fresh (installed with **Basic data**, nothing added yet).
Otherwise use the **Reset database** tab with the basic data set first.

### Upgrade in place via command line (alternative)

If you have command line access (`php` on the server, for example via SSH), you can also
migrate the old database in place. This keeps the old tables as `<prefix>v537_*` until you
remove them.

1. **Backup your current files and database!** Do not skip this. The script keeps your
   old tables, but a full backup is the only complete safety net.
2. Make sure nobody is using TeamCal Neo while you upgrade.
3. Keep a copy of your `.env` file.
4. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
5. Download the new release and unzip all files into the same directory.
6. From the installation directory, check whether your database can be migrated. This changes nothing:
   ```
   php sql/migrate_5.3.7_to_6.0.0.php --dry-run
   ```
   If it reports problems, fix them as described and run it again.
7. Migrate the database:
   ```
   php sql/migrate_5.3.7_to_6.0.0.php --confirm-backup
   ```
   The flag confirms that you made the backup from step 1. The script renames your old tables
   to `<prefix>v537_*`, creates the new ones and copies your data across. If anything goes
   wrong it undoes its changes and leaves your database as it was.
   It ends with a table of row counts and a list of any rows that could not be carried over
   because they referenced data that no longer exists.
8. Log in and check your calendar, users, groups and reports.
9. When you are satisfied, remove the old tables:
   ```
   php sql/migrate_5.3.7_to_6.0.0.php --drop-backup --confirm-drop
   ```
10. Delete `installation.php` from the root directory.

**What changed for you**

- **Direct database access.** Anything that reads the database directly (reports, exports,
  other tools) must be updated. The main changes: `tcneo_templates` is now `tcneo_absence_days`
  (one row per user and day), `tcneo_months` is now `tcneo_calendar_days` (one row per region
  and holiday override), `tcneo_patterns.abs1..abs7` moved to `tcneo_pattern_days`, and tables
  that referenced users by `username` now use `user_id` (see `tcneo_users.id`).
- **Group calendar edit** no longer pre-fills the form with the last applied pattern.
- **"Business Day" overrides** on calendar days are now the default state and are no longer stored.
- The system account (`admin`) and the built-in holiday types are now flagged in the database
  (`is_system`) instead of being recognised by name or id.

## [5.3.6] -> [5.3.7]

> **Security release.** This version fixes an authentication bypass and restores
> brute force protection on the login form. Updating is strongly recommended for
> every installation.

1. Backup your current files and database!
2. Keep a copy of your `.env` file.
3. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. Run the database upgrade script using phpMyAdmin or any other database management tool:
   ```
   sql/update_5.3.6_to_5.3.7.sql
   ```
   This adds the `bad_logins_start` column to `tcneo_users` and `tcneo_archive_users`.
   Existing rows are unaffected (the column defaults to `0`).
6. **(Optional)** Add an application secret to your `.env` file (see `.env.example`):
   ```
   APP_SECRET=<at least 32 characters of random data>
   ```
   This key signs the login cookie. If you leave it unset, TeamCal Neo generates one
   automatically and stores it in the database, so no action is required. Setting it in
   `.env` keeps the same key across reinstalls and keeps it out of database backups.
7. Delete `installation.php` from the root directory.

> **Everyone has to log in again.** Login cookies issued by earlier versions are no
> longer accepted, so all active sessions end when you upgrade. This is intentional.

> **Check your locked accounts.** Earlier versions could lock an account permanently
> after repeated failed logins, using the same flag an administrator uses to disable
> an account. The upgrade cannot tell the two apart, so any affected account stays
> locked. Review them in **Admin -> Users**, or with:
> ```sql
> SELECT username, locked, bad_logins FROM `tcneo_users` WHERE locked = 1;
> ```
> From this version on, too many failed logins only throttles an account for the
> configured grace period and then clears by itself. The `locked` flag is reserved
> for administrators.

## [5.3.5] -> [5.3.6]

1. Backup your current files and database!
2. Keep a copy of your `.env` file.
3. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. Delete `installation.php` from the root directory.

> **No database changes** — no SQL upgrade script needs to be run.

## [5.3.4] -> [5.3.5]

> **Architecture change:** `APPLICATION_URL` has moved from
> `config/config.app.php` into `.env`. You no longer need to edit
> `config/config.app.php` after an upgrade to set this value.

1. Backup your current files and database!
2. Keep a copy of your `.env` file.
3. Delete all files and folders from your TeamCal Neo installation directory, **except the `.env` file**.
4. Download the new release and unzip all files into the same directory.
5. **(Only if you previously set `APPLICATION_URL` in `config/config.app.php`)** Add it to your `.env` file instead (see `.env.example` for reference):
   ```
   APPLICATION_URL=http://your-domain.com/tcneo/
   ```
   Leave it unset (or empty) to keep using auto-detection, as before.
6. Delete `installation.php` from the root directory.

> **No database changes** — no SQL upgrade script needs to be run.

## [5.3.x] -> [5.3.4]

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
