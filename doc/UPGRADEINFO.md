# TeamCal Neo Upgrade Information

## [4.3.x] -> [5.0.x]

1. Backup your current files and database!
2. Delete all files and folders from your current TeamCal Neo 4 installation directory
3. Download the new release and unzip all files into the same directory
4. Edit `config/config.app.php` and set `APP_INSTALLED` to "1"
5. Edit `config/config.db.php` and enter your database credentials
6. Run the database upgrade script `sql/update_4_to_5.0.0.sql` using phpMyAdmin or any other database management tool
7. Delete file installation.php in the root directory.
