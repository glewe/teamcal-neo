# Build Number Tracking System

## Overview
TeamCal Neo now includes an automatic build number tracking system that increments with each build.

## Implementation Details

### 1. Storage Location
The build number is stored in `composer.json` as a custom field:
```json
{
  "name": "glewe/teamcal-neo",
  "version": "5.0.0-beta2",
  "build": "0",
  ...
}
```

### 2. Build Script Integration
The `tools/build.php` script automatically:
- Reads the current build number from `composer.json`
- Increments it by 1
- Saves the new build number back to `composer.json`
- Updates both source and distribution `config/config.app.php` files

### 3. Application Constants
Three constants are maintained in `config/config.app.php`:
- `APP_VER` - Version number (e.g., "5.0.0-beta2")
- `APP_BUILD` - Build number (e.g., "42")
- `APP_DATE` - Build date (e.g., "2026-02-05")

### 4. Display
The build number is displayed in:
- **Build script output**: Shows version, build, and date in the header
- **Application footer**: "Powered by TeamCal Neo 5.0.0-beta2 (Build 42) © ..."

## Usage

### Running a Build
Simply run the build command as usual:
```bash
composer build
```

The build number will automatically increment from the previous value.

### Manual Build Number Management
If you need to manually adjust the build number:

1. Edit `composer.json` and change the `"build"` value
2. Edit `config/config.app.php` and change `APP_BUILD` to match

### Resetting Build Number
To reset the build number (e.g., for a new major version):

1. Set `"build": "0"` in `composer.json`
2. Set `define('APP_BUILD', "0");` in `config/config.app.php`

## Version Control
The build number in `composer.json` is tracked in Git. This means:
- Each build creates a commit showing the incremented build number
- You can see build history in Git log
- Team members will see consistent build numbers

## Benefits
- ✅ Automatic tracking - no manual intervention needed
- ✅ Unique identifier for each build
- ✅ Helps with debugging and support (users can report their build number)
- ✅ Visible in application footer for easy reference
- ✅ Integrated with existing build workflow
