# Refactoring Results

## Phase 1: Analysis & Setup
- [x] **Code Audit**:
    - Identified extensive use of global variables (`$C`, `$CONF`, `$DB`, etc.).
    - Confirmed that `PDO` and prepared statements are already in use.
    - Confirmed mixed logic in views and procedural controllers.
- [x] **Environment**:
    - Verified `composer.json` exists.
    - Added `phpunit/phpunit` to `composer.json` as a dev dependency.
- [x] **Testing Infrastructure**:
    - Created `phpunit.xml` configuration file.
    - Created `tests/Unit/ExampleTest.php` to verify the setup.

## Phase 2: Security Hardening
- [x] **Database Abstraction**:
    - Verified that `PDO` and prepared statements are widely used.
    - Fixed SQL injection vulnerabilities in `classes/Holidays.class.php` and `classes/Users.class.php` by whitelisting dynamic column names in `ORDER BY` clauses.
- [x] **Input Validation**:
    - Created `classes/Request.class.php` to handle input retrieval and sanitization.
- [x] **XSS Protection**:
    - Reviewed `helpers/global.helper.php` and confirmed robust sanitization functions (`sanitize`, `cleanInput`).
- [x] **CSRF Protection**:
    - Verified that CSRF protection (token generation and validation) is implemented across controllers and views.
- [x] **Session Management**:
    - Hardened session security in `index.php` by enforcing `HttpOnly`, `Secure` (if HTTPS), and `SameSite=Strict` cookie parameters.

## Phase 3: Architecture & OOP Refactoring
- [x] **Dependency Injection**:
    - Created `classes/Container.class.php` as a simple DI Container.
    - Initialized the container in `index.php` and registered the `Request` service.
- [x] **Controller Refactoring**:
    - Created `classes/BaseController.class.php` as an abstract base for controllers.
    - Created `classes/Router.class.php` to handle request dispatching, supporting both new Controller classes and legacy procedural scripts.
    - Integrated `Router` into `index.php`.
    - Refactored `controller/login.php` to use a new `LoginController` class (`classes/LoginController.class.php`) which extends `BaseController`.
    - Refactored Core controllers: `home`, `logout`, `about`, `imprint`, `dataprivacy`, `maintenance`, `alert` to their respective Controller classes.
    - Refactored Users controllers: `users`, `viewprofile`, `useradd`, `useredit`, `register`, `verify`, `passwordrequest`, `passwordreset`, `userimport` to their respective Controller classes.
    - Refactored Calendar controllers: `calendarview`, `calendaredit`, `calendaroptions`, `year`, `absum`, `groupcalendaredit` to their respective Controller classes.
    - Refactored Absences controllers: `absences`, `absenceedit`, `absenceicon` to their respective Controller classes.
    - Refactored Administration controllers: `config`, `database`, `log`, `phpinfo`, `permissions` to their respective Controller classes.
    - Refactored Groups/Regions controllers: `groups`, `groupedit`, `regions`, `regionedit` to their respective Controller classes.
    - Refactored Holidays/Patterns: `holidays`, `holidayedit`, `patterns`, `patternedit`, `patternadd` to their respective Controller classes.
    - Refactored Messagaes: `messages`, `messageedit` to their respective Controller classes.
    - Refactored Statistics controllers: `statsabsence`, `statsabstype`, `statspresence`, `statsremainder` to their respective Controller classes.
    - Refactored Others controllers: `attachments`, `bulkedit`, `daynote`, `declination`, `remainder`, `roles`, `roleedit`, `login2fa`, `setup2fa` to their respective Controller classes.
- [x] **Model Extraction**:
    - Refactored `classes/Users.class.php` to `classes/UsersModel.class.php`.
    - Modernized Core models: `Config`, `DB`, `LogModel`, `LoginModel`, `UploadModel`, `XMLModel`.
    - Modernized Domain models: `AbsenceModel`, `AbsenceGroupModel`, `AllowanceModel`, `AttachmentModel`, `AvatarModel`, `DaynoteModel`, `GroupModel`, `HolidayModel`, `MessageModel`, `MonthModel`, `PatternModel`, `PermissionModel`, `RegionModel`, `RoleModel`, `TemplateModel`, `UserAttachmentModel`, `UserGroupModel`, `UserMessageModel`, `UserOptionModel`, `UserModel`, `LicenseModel`.
    - All modernized models now support constructor-based dependency injection and include strict property and return type hints.
- [x] **Rename Models**:
    - Renamed all plural-named model classes to their singular forms (e.g., `UsersModel` to `UserModel`).
    - Renamed `Config.class.php` to `ConfigModel.class.php` and `DB.class.php` to `DbModel.class.php`.
    - Updated all file names, class definitions, instantiations, and type hints across the codebase.
- [x] **Remove Globals**:
    - Systematically replaced global variable usage in `index.php` and refactored controllers with dependency injection via the DI Container.

## Phase 4: PSR-4 Directory Structure Migration
- [x] **Directory Structure Setup**:
    - Created `src/Controllers`, `src/Models`, `src/Core`, and `src/Helpers` directories.
    - Updated `composer.json` to map `App\\` namespace to `src/`.
- [x] **Core Migration**:
    - Moved and namespaced core classes (`BaseController`, `Container`, `Router`, `Request`, `DbModel`, `ConfigModel`) to `src/Core`.
- [x] **Model Migration**:
    - Moved all domain models to `src/Models`.
    - Applied `App\Models` namespace and removed `.class.php` suffix.
- [x] **Controller Migration**:
    - Moved all controllers to `src/Controllers`.
    - Applied `App\Controllers` namespace and removed `.class.php` suffix.
    - Updated imports and dependencies in all controller files.
- [x] **Entry Point Refactoring**:
    - Updated `index.php` to use Composer autoloader exclusively.
    - Updated `helpers/*.php` to use namespaced classes (e.g., `App\Models\UserModel`).
- [x] **Autoloading**:
    - Successfully ran `composer dump-autoload` to generate the new class map.

## Phase 5: MVC Separation
- [x] **View Logic**:
    - Removed complex PHP logic from several view files.
    - Successfully migrated the following views to Twig templates:
        - `login`, `about`, `imprint`, `dataprivacy`, `home`, `logout`, `alert`, `error`
        - `absences`, `absenceedit`, `absenceicon`, `absum`
        - `attachments`, `bulkedit`, `calendaredit`, `calendaroptions`, `calendarview`
        - `config`, `database`, `daynote`, `declination`
        - `footer`, `header`
        - `groupcalendaredit`, `groupedit`, `groups`
        - `holidayedit`, `holidays`
        - `log`, `login2fa`
        - `maintenance`, `menu` (partially via layout includes)
        - `messages`, `messageedit`
        - `monthedit` (Created missing Controller and Twig view)
        - `passwordrequest`, `passwordreset`
        - `patterns`, `patternedit`, `patternadd`
        - `phpinfo`, `permissions`
        - `regions`, `regionedit`
        - `register`, `remainder`
        - `roles`, `roleedit`
        - `setup2fa`
        - `statsabsence`, `statsabstype`, `statspresence`, `statsremainder`
    - Removed matching `.php` view files from `views/`.
- [x] **Templating**:
    - Installed Twig via Composer.
    - Created `App\Core\TemplateEngine` as a wrapper for Twig.
    - Updated `BaseController` to use `TemplateEngine` for view rendering.
    - Registered legacy helper functions (`createAlertBox`, `createFormGroup`, etc.) as Twig functions for UI consistency.
    - Consolidated global logic from `header.php` and `footer.php` into `layout.twig` (SEO, Analytics, libraries, Javascript initializers).
- [x] **Controller Completion**:
    - Completed missing logic in `DeclinationController` and `DaynoteController` during the migration process.
    - Completed `PasswordrequestController`.

## Phase 6: Performance Optimization
- [x] **Database Indexing**:
    - Added indexes to `tcneo_log` (severity, timestamp), `tcneo_users` (username, group_id), and `tcneo_user_group` (group_id) to optimize query performance.
- [x] **Lazy Loading**:
    - Updated `BaseController` and `Container` to implement lazy loading for services and models. Dependencies are now instantated only when accessed via `__get`, reducing memory footprint and startup time.

## Phase 7: Quality Assurance
- [x] **Static Analysis**:
    - Installed `phpstan/phpstan`.
    - Configured `phpstan.neon` and `tests/phpstan-bootstrap.php`.
    - Ran analysis and fixed critical issues (e.g., missing namespaces).
- [x] **Unit Testing**:
    - Installed `phpunit/phpunit`.
    - Configured `phpunit.xml`.
    - Created and ran initial unit tests (e.g., `ConfigModelTest`).

## Phase 8: Cleanup and Folder Organization
- [x] **Folder Organization**:
    - Created `resources/` for `languages` and `templates`.
    - Created `public/` for all public assets (`css`, `js`, `images`, `fonts`, `themes`, `addons`, `upload`).
    - Updated configuration (`config.app.php`) and view files (`layout.twig`, etc.) to reflect new paths.
- [x] **Obsolete Files**:
    - Deleted `verify_migrations.php`.
    - Removed legacy directories: `classes/`, `helpers/`, `controller/`.
    - Preserved and updated `installation.php` to align with the new folder structure.
