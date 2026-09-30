# TeamCal Neo — Quick AI contributor guide

This repository is a PHP-based single-repository web application (TeamCal Neo v5). The goal of these pages is to give an AI coding agent the specific, actionable context needed to make safe, useful changes.

## High-level architecture
- **Entry point**: `index.php` — boots the app, loads config, initializes the DI Container, and dispatches requests via the Router.
- **Dependency Injection**: `src/Core/Container.php` handles dependency injection. All Models and Services are registered here.
- **Routing**: `src/Core/Router.php` maps the `action` parameter to Controllers.
- **Controllers**: `src/Controllers/*.php` (Namespace `App\Controllers`). They extend `App\Core\BaseController` and handle business logic before rendering views.
- **Models**: `src/Models/*.php` (Namespace `App\Models`). They handle database interactions.
- **Views**: `views/*.twig`. The application uses a Template Engine (likely Twig-based or custom wrapper) located in `src/Core/TemplateEngine.php`.
- **Helpers**: `src/Helpers/*.helper.php` provide utility functions.

## Developer workflows (discoverable)
- **Build**: `composer run build` (or `php tools/build.php`) compiles assets and prepares the `dist` folder.
- **Tests**: `composer test`.
- **Static Analysis**: `composer phpstan`.

## Coding conventions
**CRITICAL:** Please refer to [RULES.md](../RULES.md) in the root directory for all coding standards, variable naming conventions, and documentation requirements.

## Safe-change checklist for agents
- **Prefer small, localized changes.**
- **Preserve language keys:** When adding user-facing messages, add keys in `resources/languages/*` (or `src/languages` depending on structure).
- **Follow file naming:** Classes => `PascalCase.php`; Methods => `camelCase`.
- **Use the Container:** Avoid `new ClassName()`. Use dependency injection or the container where possible.

## Examples to reference
- **Bootstrapping**: `index.php`
- **Controller**: `src/Controllers/HomeController.php`
- **Model**: `src/Models/UserModel.php`
- **Routing**: `index.php` (Route registration)
