![TeamCal Neo](https://github.com/glewe/teamcal-neo/blob/master/public/images/icons/tcn-icon-80.png)

# TeamCal Neo 5

[![PHP](https://img.shields.io/badge/Language-PHP-8892BF.svg)](https://www.php.net/)
[![JS](https://img.shields.io/badge/Language-JavaScript-f1e05a.svg)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![HTML](https://img.shields.io/badge/Language-HTML5-e34c26.svg)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS](https://img.shields.io/badge/Language-CSS3-563d7c.svg)](https://developer.mozilla.org/en-US/docs/Web/CSS)

[![Framework](https://img.shields.io/badge/Framework-Bootstrap-563d7c.svg)](https://getbootstrap.com/)
[![Database](https://img.shields.io/badge/Database-MySQL-00758f.svg)](https://www.mysql.com/)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=glewe_teamcal-neo&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=glewe_teamcal-neo)
[![Dependency Status](https://img.shields.io/librariesio/github/glewe/teamcal-neo)](https://libraries.io/github/glewe/teamcal-neo)

TeamCal Neo is a day-based online calendar that allows to easily manage your team's events and absences and displays them in an intuitive interface. You can manage absence types, holidays, regional calendars and much more.

## New in Version 5 (currently in beta)

TeamCal Neo 5.0.0 is a new major release. The application has been completely refactored to make it faster and more secure, e.g.

- the file and folder structure has been changed
- the legacy language files are removed and have been replaced by the new language architecture (separate files for each controller)
- the application follows strict PSR-4 autoloading
- the application uses Twig for templating
- the application uses Composer for dependency management
- the application uses PHPStan for static analysis
- the application uses PHPUnit for testing
- the application uses phpDocumentor for documentation

### Bugfixes

- Fixed admin users not seeing all groups on Notification tab

### Features

- Added new languages Spanish and French (via AI translators so forgive any mistakes)
- Added new demo groups and users
- Added three new statistics: Trends, Day of Week, Duration
- Added Summernote WYSIWYG editor

### Improvements

- Updated sample database (split in core and demo data)
- Updated Bootstrap Icons 1.11.3 to 1.13.1
- Updated Datatables 2.2.0 to 2.3.6
- Updated ChartJs 4.4.7 to 4.5.1
- Updated jQuery 3.7.1 to 4.0.0
- Updated jQuery UI 1.14.1 to 1.14.2
- Replaced Securimage CAPTCHA with new internal CaptchaService (Math + Honeypot)
- Set focus on search input in calendar user search dialog
- Allow multiple absence types in absence monitoring
- Implemented a much nicer email template for notifications
- Added "default" to the user profile option "Menu Position" (global setting)
- Tooltip Counter for absences in the calendar now shows (taken current month/taken current year)

### Removals

- Removed obsolete Securimage module
- Removed option to display viewport info in the footer
- Removed Google+ and Skype fields from user profile (added Instagram, TikTok and Xing)
- Removed the old demo groups and users

## Resources

[TeamCal Neo Demo](https://tcneo.lewe.com/)

[TeamCal Neo Product Page](https://teamcalneo.lewe.com/)

[TeamCal Neo User Manual](https://lewe.gitbook.io/teamcal-neo/)

## Support

[Lewe Service Desk](https://georgelewe.atlassian.net/servicedesk/customer/portal/5) (e.g. License requests, support requests)

[GitHub Issues](https://github.com/glewe/teamcal-neo/issues) (e.g. Bug reports, feature and improvement requests)

## Releases

[TeamCal Neo Releases](https://github.com/glewe/teamcal-neo/releases)

## Installation and Update Guide

[TeamCal Neo Installation Guide](https://github.com/glewe/teamcal-neo/blob/master/doc/INSTALL.md)  

[TeamCal Neo Update Guide](https://github.com/glewe/teamcal-neo/blob/master/doc/UPDATE.md)  
  
## Developer Info

[Development Guide](https://github.com/glewe/teamcal-neo/blob/master/AGENTS.md)
  
<br/>

I hope you enjoy TeamCal Neo!  
George Lewe
