# TeamCal Neo Upgrade Information

## [5.0.x] -> [5.0.6]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 5 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. Adjust your database configuration either in `.env` or `config/config.db.php` (depending on what you use).
6. Delete file installation.php in the root directory.

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
