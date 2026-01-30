![TeamCal Neo](https://github.com/glewe/teamcal-neo/blob/master/src/images/icons/tcn-icon-80.png)

# TeamCal Neo 5

[![Framework](https://img.shields.io/badge/Framework-Bootstrap-563d7c.svg)](https://getbootstrap.com/)
[![Database](https://img.shields.io/badge/Database-MySQL-00758f.svg)](https://www.mysql.com/)

[![PHP](https://img.shields.io/badge/Language-PHP-8892BF.svg)](https://www.php.net/)
[![JS](https://img.shields.io/badge/Language-JavaScript-f1e05a.svg)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![HTML](https://img.shields.io/badge/Language-HTML5-e34c26.svg)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS](https://img.shields.io/badge/Language-CSS3-563d7c.svg)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![Responsive](https://img.shields.io/badge/Responsive-Yes-44cc11.svg)](https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=glewe_teamcal-neo&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=glewe_teamcal-neo)
[![Dependabot](https://img.shields.io/badge/dependabot-enabled-025e86.svg?logo=dependabot)](https://github.com/glewe/teamcal-neo/network/updates)

TeamCal Neo is a day-based online calendar that allows to easily manage your team's events and absences and displays them in an intuitive interface. You can manage absence types, holidays, regional calendars and much more.

## New in Version 5

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
- Several minor bugfixes

### Features

- Added new optional font "Poppins"
- Added new languages Spanish and French

### Improvements

- The installation script now allows to select whether to only add core data or an additional set of sample data
- Updated Bootstrap Icons 1.11.3 to 1.13.1
- Updated Datatables 2.2.0 to 2.3.6
- Replaced Securimage CAPTCHA with new internal CaptchaService (Math + Honeypot)

## Demo
[TeamCal Neo Demo](https://tcneo.lewe.com/)

## Product Page
[TeamCal Neo](https://teamcalneo.lewe.com/)

## User Manual
[TeamCal Neo Manual](https://lewe.gitbook.io/teamcal-neo/)

## Support

[Lewe Service Desk](https://georgelewe.atlassian.net/servicedesk/customer/portal/5) (e.g. License requests, support requests)

[GitHub Issues](https://github.com/glewe/teamcal-neo/issues) (e.g. Bug reports, feature and improvement requests)
  
<br/>

I hope you enjoy TeamCal Neo!  
George Lewe
