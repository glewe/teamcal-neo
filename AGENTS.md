# TeamCal Neo - Agent Guide

## Project Overview
**TeamCal Neo** is a legacy PHP-based, day-based online calendar application designed for managing team events and absences. It provides an intuitive interface for tracking absence types, holidays, and regional calendars.

### Key Features
- **Absence Management:** Track and manage team-wide absences.
- **Regional Calendars:** Support for multiple regions and their specific holidays.
- **Split Month View:** Enhanced visualization showing the transitions between consecutive months.
- **Role-Based Access:** Management of users, roles, and permissions.
- **Internationalization:** Multi-language support with controller-specific language loading.

## Technical Stack
- **Backend:** PHP 8.x
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5.3.8, Font Awesome 7.1.0
- **Email:** PHPMailer

## Project Structure
- `src/`: Main application source code.
  - `classes/`: Core application classes.
  - `controller/`: Page-level controllers handling request logic.
  - `helpers/`: Global and specialized helper functions.
  - `languages/`: Translation files.
  - `templates/`: UI templates.
  - `views/`: View files included by controllers.
- `config/`: Configuration files for DB and application settings.

## Coding Standards
All contributions must strictly follow the project's coding standards as defined in `RULES.md`.

### Quick Summary
- **Indentation:** 2 spaces (no tabs).
- **Strict Types:** `declare(strict_types=1);` mandatory in all PHP files.
- **Method DocBlocks:** Use the specific custom header format described in `RULES.md`.
- **Visibility:** Required for all properties and methods.
- **Naming:** `PascalCase` for classes, `camelCase` for methods/properties.
