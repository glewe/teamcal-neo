<!-- Copilot instructions for TeamCal Neo -->

# TeamCal Neo — Quick AI contributor guide

This repository is a PHP-based single-repository web application (not a framework app). The goal of these pages is to give an AI coding agent the specific, actionable context needed to make safe, useful changes.

High-level architecture
- Entry point: `src/index.php` — boots the app: loads config (`src/config/*.php`), registers class autoloading (`src/classes/*.class.php`), instantiates core domain classes and dispatches controllers from `src/controller/`.
- Controllers: `src/controller/<name>.php` are thin: they set up view rendering and include view templates in `src/views/`.
- Views/templates: `src/views/` and `src/templates/` contain HTML/PHP UI fragments. Prefer small, safe changes here (avoid breaking language keys).
- Domain classes: `src/classes/*.class.php` hold major business logic (e.g. `DB.class.php`, `Users.class.php`). They are instantiated in `src/index.php` and used globally (e.g. $DB, $C, $U).
- Helpers: `src/helpers/*.php` provide utility functions used across controllers and views (not a framework DI system). Example: `src/helpers/global.helper.php`.

Important patterns and conventions
- Global single-file bootstrap: many parts rely on globals (e.g. $CONF, $C, $DB, L_USER). New code should prefer existing class usage and avoid introducing new global variables.
- Autoloading: classes are loaded via a simple spl_autoload_register in `src/index.php` which expects files named `<ClassName>.class.php` in `src/classes/`.
- Routing: `src/index.php` sets `$controller` from config or `$_GET['action']` and includes `src/controller/<controller>.php`. Add controllers by creating a new file there and a matching view in `src/views/`.
- Configuration: runtime constants and options live in `src/config/config.app.php` and `src/config/config.db.php`. Don't hard-code credentials — use/extend these files.
- Internationalization: language files are in `src/languages/<language>.php` and `<language>.app.php`. Use existing keys in `$LANG` when adding text.

Developer workflows (discoverable)
- Build and minify: `composer run build` runs CSS lint and `php minify.php` (see `composer.json` scripts). For production: `composer run build:prod`.
- Tests: `composer test` is mapped to `phpunit` but this repo contains few automated tests. Run phpunit only if present and configured.

Integration & external dependencies
- Composer-managed PHP packages (see `composer.json`). PHP 8.1+ required per `composer.json`.
- MySQL via PDO (see `src/classes/DB.class.php`). `src/config/config.db.php` contains DB credentials. Prefer prepared statements and existing DB wrapper methods.
- Optional LDAP support toggled in `src/config/config.app.php` (constants like `LDAP_YES`).

Safe-change checklist for agents
- Prefer small, localized changes. Update only one controller/view/class per PR.
- Preserve language keys: when adding user-facing messages, add keys in `src/languages/*` pairs and reference `$LANG['key']`.
- Follow file naming: classes => `Name.class.php`; controllers => `name.php` in `src/controller`; views => `name.php` in `src/views`.
- Avoid changing `src/index.php` unless fixing boot/dispatch bugs; it's the canonical bootstrap.

Examples to reference
- Bootstrapping and globals: `src/index.php`
- Database usage: `src/classes/DB.class.php`
- Helpers and validators: `src/helpers/global.helper.php` (form validation, sanitize, date helpers)
- App config & feature toggles: `src/config/config.app.php`
- Composer scripts and build: `composer.json`

If anything above is unclear or you need extra examples (e.g., sample controller that reads/saves users), ask for specific files to inspect and I will extract minimal, safe change examples.
