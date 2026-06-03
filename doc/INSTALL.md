# TeamCal Neo Installation Guide

## Requirements

TeamCal Neo requires the following on the web server:

- PHP 8.1 or later
- MySQL or MariaDB
- The following PHP extensions (all bundled with most PHP distributions, but on minimal installs such as Debian's `libapache2-mod-php` they may need to be installed separately):
  - `curl` (e.g. `apt install php-curl`) — required for license server communication
  - `pdo` and `pdo_mysql` — required for database access
  - `mbstring` — required for multi-byte string handling
  - `json` — required for license server communication and configuration

If a required extension is missing, TeamCal Neo will display a clear error message on startup with installation instructions instead of an HTTP 500.

## Version >= 5.0.0

1. Create a new database for TeamCal Neo.
2. Download the release files from [GitHub Releases](https://github.com/glewe/teamcal-neo/releases)
3. Unzip the files into the installation directory on your web server.
4. Direct your browser to the installation directory.
5. Follow the installation script instructions on the screen (e.g providing the credentials for the new database).
6. Click the "Install" button to start the installation.
7. Delete file installation.php from your installation directory.

## Version <= 4.x.x

1. Create a new database for TeamCal Neo.
2. Download the release files from [GitHub Releases](https://github.com/glewe/teamcal-neo/releases)
3. Unzip the files and copy the content of the `src` folder into the installation directory on your web server.
4. Direct your browser to the installation directory.
5. Follow the installation script instructions on the screen (e.g providing the credentials for the new database).
6. Click the "Install" button to start the installation.
7. Delete file installation.php from your installation directory.
