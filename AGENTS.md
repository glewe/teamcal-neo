# TeamCal Neo - Development Guide

## Project Overview
**TeamCal Neo** is a modern PHP-based web application designed for managing team events, absences, and regional calendars. It has evolved from a legacy codebase into a structured MVC application using strict typing and Twig templating.

## Technical Stack
- **Backend:** PHP 8.x (Strict Types Enabled)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Font Awesome 6 (Solid)
- **Templating:** Twig 3.x
- **Visualization:** Chart.js
- **Email:** PHPMailer

## Project Structure
The application follows a custom MVC pattern:

- **`src/`**: Application source code (PSR-4 Autoloaded).
  - **`Controllers/`**: Handle HTTP requests, input validation, and view rendering.
  - **`Core/`**: Core framework components (App, Container, Database, View).
  - **`Helpers/`**: Global helper functions and utility classes.
  - **`Models/`**: Data access layer interactions with the database.
  - **`Services/`**: Business logic encapsulation.
- **`views/`**: Twig template files.
  - **`layouts/`**: Base templates (e.g., `layout.twig`).
  - **`fragments/`**: Reusable UI components (e.g., statistics panels, forms).
- **`resources/`**: content and configuration resources.
  - **`languages/`**: Internationalization files (en, de, es, fr).
- **`public/`**: Web root directory containing static assets.
- **`.agents/`**: Agent-specific documentation and workflow tracking.

## Development Tools & Scripts
The project uses **Composer** to manage dependencies and execute common development tasks.

| Command | Description |
| :--- | :--- |
| `composer build` | Execute the build script (`tools/build.php`) to compile assets and prepare for deployment. |
| `composer docs` | Generate API documentation using phpDocumentor (output in `php_doc/`). |
| `composer minify` | Minify CSS and JS assets using `tools/minify.php`. |
| `composer phpunit` | Run the project's unit test suite. |
| `composer phpstan` | Perform static analysis with PHPStan. |
| `composer sbom` | Generate a Software Bill of Materials (SBOM) in `bom.json`. |
| `composer release` | Interactive release script: creates git tags and pushes to remote. |
| `composer release:force` | Force run the release script non-interactively. |

## Development Rules & Standards

### coding Standards
Refer to **`RULES.md`** for the complete and authoritative coding standards.
*   **Indentation:** 2 spaces (Soft tabs).
*   **Strict Types:** `declare(strict_types=1);` is **MANDATORY** at the top of every PHP file.
*   **DocBlocks:** Mandatory for all classes and methods. Headers must follow the specific format defined in `RULES.md`.

### Architecture Guidelines
1.  **MVC Pattern:** All new logic should reside in Controllers (flow), Models (data), or Services (business logic). Avoid putting logic in Views.
2.  **Templating:** Use **Twig** for all new views. Do not create new raw PHP views (`.php`) unless strictly necessary for legacy compatibility.
3.  **UI Components:**
    *   Use **Bootstrap 5** for layout and components.
    *   **Avoid Modals** for complex forms or filters where possible; use **Inline Cards** to prevent UI freezing issues (see Statistics Refactoring).
    *   Use **Font Awesome 6** (`fa-solid`) for icons.
4.  **Language/i18n:**
    *   Use the `LANG` global variable or Twig `LANG` object.
    *   Always provide a fallback if a key might be missing: `{{ LANG.key|default('Default') }}`.
    *   Update **all 4 language files** (English, German, Spanish, French) when adding new keys.

### Workflow
*   **Check State:** Always verify the file content before editing.
*   **Refactoring:** Check `.agents/` for active refactoring plans (e.g., `REFACTOR_STATISTICS.md`) before starting major tasks.
*   **Clean Code:** Remove unused imports and legacy code artifacts during refactoring.
