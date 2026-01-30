# Refactoring Plan for TeamCal Neo

Based on the goals outlined in `REFACTORING_PROMPT.md`, this document outlines a strategic plan to refactor the legacy PHP application.

## Goals
1.  **Security**: Enhance application security.
2.  **OOP**: Transition to modern Object-Oriented patterns.
3.  **MVC**: Improve separation of concerns (Model-View-Controller).
4.  **Performance**: Optimize for speed.
5.  **Frontend**: Preserve existing design and UX.

## Phase 1: Analysis & Setup
*   [x] **Code Audit**: Identify global variables, raw SQL queries, and mixed logic (PHP/HTML).
*   [x] **Environment**: Ensure PHP 8.x compatibility and strict typing (`declare(strict_types=1);`).
*   [x] **Version Control**: Ensure a clean git state before starting.
*   [x] **Testing Infrastructure**: Set up PHPUnit for regression testing.

## Phase 2: Security Hardening (High Priority)
*   [x] **Database Abstraction**: Replace any remaining `mysql_*` or raw `mysqli` calls with a PDO wrapper or a lightweight ORM/Query Builder. Ensure *all* queries use prepared statements to prevent SQL Injection.
*   [x] **Input Validation**: Implement a central request handler to sanitize and validate all `$_GET`, `$_POST`, and `$_REQUEST` data.
*   [x] **XSS Protection**: Ensure all output in views is properly escaped (e.g., using `htmlspecialchars` or a template engine's auto-escaping).
*   [x] **CSRF Protection**: Implement anti-CSRF tokens on all forms.
*   [x] **Session Management**: Review session handling for security (HttpOnly, Secure flags).

## Phase 3: Architecture & OOP Refactoring
*   [x] **Dependency Injection**: Introduce a Dependency Injection (DI) Container to manage class dependencies and remove reliance on `global $obj`.
*   [x] **Controller Refactoring**:
    *   Convert procedural controller scripts into proper Controller classes.
    *   Ensure controllers only handle input and delegate logic to Services/Models.
    *   **Target Controllers**:
        *   [x] Login (`controller/login.php` -> `classes/LoginController.class.php`)
        *   [x] Core: `home`, `logout`, `about`, `imprint`, `dataprivacy`, `maintenance`, `alert`
        *   [x] Users: `users`, `viewprofile`, `useradd`, `useredit`, `register`, `verify`, `passwordrequest`, `passwordreset`, `userimport`
        *   [x] Calendar: `calendarview`, `calendaredit`, `calendaroptions`, `year`, `absum`, `groupcalendaredit`
        *   [x] Absences: `absences`, `absenceedit`, `absenceicon`
        *   [x] Administration: `config`, `database`, `log`, `phpinfo`, `permissions`
        *   [x] Groups/Regions: `groups`, `groupedit`, `regions`, `regionedit`
        *   [x] Holidays/Patterns: `holidays`, `holidayedit`, `patterns`, `patternedit`, `patternadd`
        *   [x] Messages: `messages`, `messageedit`
        *   [x] Statistics: `statsabsence`, `statsabstype`, `statspresence`, `statsremainder`
        *   [x] Others: `attachments`, `bulkedit`, `daynote`, `declination`, `remainder`, `roles`, `roleedit`, `login2fa`, `setup2fa`
*   [x] **Model Extraction**:
    *   Move business logic and database interactions from Views/Controllers into dedicated Model/Service classes.
    *   Create entities for core domain objects (User, Absence, Calendar).
    *   **Target Classes**:
        *   [x] Users (`classes/Users.class.php`)
        *   [x] Core (Modernized): `Config`, `DB`, `Router`, `Request`, `Container`, `BaseController`, `Log`, `Login`, `Upload`, `XML`
        *   [x] Domain (Modernized): `Absences`, `AbsenceGroup`, `Allowances`, `Attachment`, `Avatar`, `Daynotes`, `Groups`, `Holidays`, `Messages`, `Months`, `Patterns`, `Permissions`, `Regions`, `Roles`, `Templates`, `UserAttachment`, `UserGroup`, `UserMessage`, `UserOption`, `License`
*   [x] **Rename Models**: Rename all model classes to start with a singular name (e.g., `User` instead of `Users`). Update all references accordingly.
*   [x] **Remove Globals**: Systematically replace global variable usage with injected dependencies.

## Phase 4: PSR-4 Directory structure
*   [x] **Rearrange Files and Folders**: Rearrange the files and folders as per PSR-4 recommendation:
```
project-root/
├── src/                # Your application logic
│   ├── Models/         # App\Models\User.php
│   ├── Core/           # App\Core\Container.php
│   ├── Helpers/        # (If applicable)
│   └── Controllers/    # App\Controllers\UserController.php
├── tests/              # Your test suite
│   ├── Unit/           # App\Tests\Unit\ExampleTest.php
│   └── Integration/
├── vendor/             # Managed by Composer
├── composer.json       # Defines the mapping
└── RULES.md            # Your agent rules
```

## Phase 5: MVC Separation
*   [x] **View Logic**: Remove complex PHP logic from `views/`. Views should only display data provided by the Controller.
*   [/] **Target Views**: All files in `views/` directory.
    *   [x] `login`, `about`, `imprint`, `dataprivacy`, `home`, `logout`, `alert`, `error`
    *   [x] `absences`, `absenceedit`, `absenceicon`, `absum`
    *   [x] `attachments`, `bulkedit`, `calendaredit`, `calendaroptions`, `calendarview`
    *   [x] `config`, `database`, `daynote`, `declination`
    *   [x] `footer`, `header`
    *   [x] `groupcalendaredit`, `groupedit`, `groups`
    *   [x] `holidayedit`, `holidays`
    *   [x] `log`, `login2fa`
    *   [x] `maintenance`, `menu` (inc. `navbar`, `sidebar`)
    *   [x] `messages`, `messageedit`
    *   [x] `monthedit`
    *   [x] `passwordrequest`, `passwordreset`
    *   [x] `patternadd`, `patternedit`, `patterns`
    *   [x] `phpinfo`, `permissions`
    *   [x] `regions`, `regionedit`
    *   [x] `register`, `remainder`, `roleedit`, `roles`
    *   [x] `setup2fa`
    *   [x] `statsabsence`, `statsabstype`, `statspresence`, `statsremainder`
    *   [x] `useradd`, `useredit`, `userimport`, `users`
    *   [x] `verify`, `viewprofile`, `year`
*   [x] **Templating**: Install and adopt template engine Twig.
*   [x] **Routing**: Implement a central Router to handle URL mapping to Controllers, replacing direct file access (e.g., `index.php?action=...`).

## Phase 6: Performance Optimization
*   [x] **Database Indexing**: Analyze slow queries and add appropriate indexes.
*   [x] **Asset Management**: Minify and combine CSS/JS files where possible (while keeping the design intact).
*   [x] **Caching**: Implement object caching (e.g., for configuration or heavy read operations) and opcode caching.
*   [x] **Lazy Loading**: Load resources only when needed.

## Phase 7: Quality Assurance
*   [x] **Static Analysis**: Run tools like PHPStan or Psalm to detect type errors and potential bugs.
    *   [x] Installed and configured PHPStan (Level 1).
    *   [x] Run analysis (legacy code excluded).
*   [x] **Unit Tests**: Setup complete. Initial test created.
    *   [x] Configure PHPUnit.
    *   [x] Create initial test.

## Phase 8: Cleanup
*   [x] **Remove Legacy Code**: Remove all legacy code (e.g., procedural code, global variables, etc.)
*   [x] **Organize Folders**: Reorganized the remaining folders to better reflect the application's PSR-4 structure.
    *   [x] `addons`: Moved to `public/addons`
    *   [x] `css`: Moved to `public/css`
    *   [x] `fonts`: Moved to `public/fonts`
    *   [x] `images`: Moved to `public/images`
    *   [x] `js`: Moved to `public/js`
    *   [x] `languages`: Moved to `resources/languages`
    *   [x] `templates`: Moved to `resources/templates`
    *   [x] `themes`: Moved to `public/themes`
    *   [x] `uploads`: Moved to `public/upload`
    *   [x] `docs`: Kept in root (Documentation)
    *   [x] `sql`: Kept in root (Installation assets)
    *   [x] `config`: Kept in root (Configuration)
*   [x] **Obsolete Files**: Identify all obsolete files and folders that are not needed anymore and delete (backup exists).
    *   [x] `verify_migrations.php`: Deleted
    *   [x] `installation.php`: Updated for new structure
    *   [x] `classes/`, `helpers/`, `controller/`: Confirmed removed

## Phase 9: Confirm Completion
*   [x] **Confirm phase completion**: Review and confirm that all refacturing phases 1 to 8 are completed.

## Phase 10: Create Repository
*   [x] **Create Repository**: Create a new repository for the refactored code.
*   [x] **Push to Repository**: Push the refactored code to the new repository ().

## Phase 11: Manual Tests
*   [x] **Manual tests**: eveary page and functionality. When encountering problems, converse with agent for a solution. The old code is available for comparison in folder `_old_code`.

## Phase 12: Code Optimizations
*   [x] **Obsolete Code**: After the refactoring and testing, is there any obsolete code that can be removed?
*   [ ] **Obsolete Files**: After the refactoring and testing, is there any obsolete files that can be removed?
*   [x] **Helper Functions**: Are there any helper functions that are solely used by one controller? If so, could they be moved to the controller or a service?
    *   [x] Moved `countAbsence`, `countAbsenceRequestedMonth`, `countAbsenceRequestedWeek` to `AbsenceService`.
    *   [x] Removed unused `getClientIp`, `ipInRange`, `writeConfig` from `global.helper.php`.
*   [x] **Services**: Create and utilize Services for business logic.
    *   [x] `AbsenceService`: Created and refactored to handle absence calculations and logic.
    *   [x] `UserService`: Created to handle user-related operations.
*   [x] **Code Optimization**: Is there any code that can be optimized, e.g. made more secure or performing better or better integrate in MVC architecture?
*   [x] **Routing**: Evaluated modernizing routing. Decided to stick with current `Router` implementation (Query Parameter based) as it is robust and compatibility.
*   [x] **Code Rules**: All code is formatted and documented as per RULES.md. Verified strict types and docblocks (Controllers, Models, Router, Services).

## Phase 13: PHPstan
*   [x] **Run and fix**: Run phpstan and fix errors.

## Phase 14: PHPunit
*   [x] **Create tests**: Create a useful set og PHPunit tests.

## Phase 15: Code Documentation
*   [x] **Setup**: Setup a code documentation module PHPDocumentor and document the code.
*   [x] **Code Formatting**: Adjust coding rules to properly support PHPDocumentor.

## Phase 16: Build and Deployment
*   [x] **Build Script for Production**: Create `tools/build.php` to automate the distribution process. Exit upon failure of any step:
    *   [x] **Pre-build Checks**:
        *   [x] Run `composer phpstan` (Static analysis)
        *   [x] Run `composer phpunit` (Tests)
        *   [x] Run `composer docs` (Full documentation update)
        *   [x] Run `composer minify` (Asset optimization)
        *   [x] Run `composer sbom` (Generate Software Bill of Materials)
    *   [x] **Assemble Distribution (`/dist`)**:
        *   [x] Clean/Create `/dist` workspace
        *   [x] Copy necessary folders: `cache/`, `config/`, `doc/`, `public/`, `resources/`, `sql/`, `src/`, `temp/`, `vendor/`, `views/`
        *   [x] Copy necessary files: `index.php`, `LICENSE`, `README.md`, `composer.json`, `composer.lock`
        *   [x] Rename `installation.org.php` to `installation.php` in `/dist`
        *   [x] Update `/dist/config/config.app.php`:
            *   [x] Set `APP_INSTALLED` to `0`
            *   [x] Set `APP_VER` to version from `composer.json`
            *   [x] Set `APP_DATE` to current build date
        *   [x] Wipe contents of `cache/*` and `temp/*` (preserve directory structure)
        *   [x] Strip development-only files (e.g., `.git`, `tests/`, `tools/`, configuration templates)
    *   [x] **Production Preparation**:
        *   [x] Run `composer install --no-dev --optimize-autoloader` inside `/dist`
    *   [x] **Packaging**:
        *   [x] Create production ZIP archive (e.g., `tcneo-v5.x.x.zip`)

## Phase 17: Update README
*   [x] **Update**: Update the README to reflect the new structure and functionality.

