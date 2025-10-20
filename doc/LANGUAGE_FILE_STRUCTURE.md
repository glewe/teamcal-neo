# TeamCal Neo Language File Structure Documentation

## Overview

This document provides complete technical specifications for the new controller-specific language file structure, including naming conventions, file organization, and implementation details.

## Directory Structure

### Standard Layout
```
src/languages/
├── english/                    # English language directory
│   ├── core.php               # Global strings (buttons, alerts, etc.)
│   ├── about.php              # About page strings
│   ├── absence.php            # Absence management
│   ├── attachment.php         # File attachments
│   ├── bulkedit.php          # Bulk editing features
│   ├── calendar.php          # Calendar functionality
│   ├── calendaroptions.php   # Calendar options/settings
│   ├── config.php            # Configuration management
│   ├── database.php          # Database management
│   ├── daynote.php           # Day notes functionality
│   ├── declination.php       # Declination management
│   ├── gdpr.php              # GDPR compliance
│   ├── group.php             # Group management
│   ├── holiday.php           # Holiday management
│   ├── imprint.php           # Legal imprint
│   ├── import.php            # Data import features
│   ├── log.php               # Logging functionality
│   ├── login.php             # Authentication
│   ├── maintenance.php       # System maintenance
│   ├── message.php           # Messaging system
│   ├── month.php             # Monthly views
│   ├── password.php          # Password management
│   ├── pattern.php           # Pattern management
│   ├── permission.php        # Permission management
│   ├── profile.php           # User profiles
│   ├── region.php            # Region management
│   ├── register.php          # User registration
│   ├── remainder.php         # Remainder calculations
│   ├── role.php              # Role management
│   ├── statistics.php        # Reports and analytics
│   ├── upload.php            # File upload functionality
│   ├── user.php              # User management
│   └── year.php              # Yearly views
└── deutsch/                   # German language directory
    ├── core.php              # Same structure as English
    ├── about.php
    ├── absence.php
    └── ...                   # Mirror of English structure
```

### File Count and Distribution
- **Total Files**: 35 controller files + 1 core file = 36 files per language
- **English**: Complete implementation (36 files)
- **German**: Complete implementation (36 files)
- **Other Languages**: Follow same structure

## Naming Conventions

### File Naming Rules
1. **Lowercase Only**: All filenames use lowercase letters
2. **Controller Match**: Filename matches controller name exactly
3. **PHP Extension**: All files end with `.php`
4. **No Prefixes**: No language prefix in filename (handled by directory)
5. **Descriptive**: Names clearly indicate functionality

### Examples
```php
// Controller files -> Language files
src/controller/users.php        -> src/languages/english/user.php
src/controller/absenceedit.php  -> src/languages/english/absence.php  
src/controller/calendarview.php -> src/languages/english/calendar.php
src/controller/config.php       -> src/languages/english/config.php
```

### Special Cases
| Controller | Language File | Reason |
|------------|---------------|---------|
| absenceedit | absence.php | Multiple controllers share absence functionality |
| absenceicon | absence.php | Icon management is part of absence system |
| absences | absence.php | Listing and management use same strings |
| calendarview | calendar.php | View and edit share calendar strings |
| calendaredit | calendar.php | Edit uses same base calendar functionality |
| users | user.php | Plural controller, singular language file |
| useredit | user.php | Edit shares user management strings |
| useradd | user.php | Add functionality uses user strings |

## File Structure Standards

### File Header Template
```php
<?php
/**
 * TeamCal Neo Language File: [FUNCTIONALITY]
 * 
 * @file        [filename].php
 * @author      [Author Name]
 * @copyright   (c) 2014-2025 George Lewe
 * @package     TeamCal Neo
 * @subpackage  Languages
 * @since       [Version]
 * 
 * Contains language strings for [functionality description].
 * Used by controllers: [list of controllers that use this file]
 */
```

### Key Organization Within Files
```php
<?php
/**
 * Core Language File
 * Contains essential strings used across multiple controllers
 */

// Alert messages
$LANG['alert_success_title'] = 'Success';
$LANG['alert_error_title'] = 'Error';
$LANG['alert_warning_title'] = 'Warning';

// Button labels  
$LANG['btn_save'] = 'Save';
$LANG['btn_cancel'] = 'Cancel';
$LANG['btn_delete'] = 'Delete';

// Common labels
$LANG['name'] = 'Name';
$LANG['description'] = 'Description';
$LANG['all'] = 'All';

// Validation messages
$LANG['required_field'] = 'This field is required';
$LANG['invalid_email'] = 'Please enter a valid email address';
?>
```

### Key Naming Patterns
1. **Prefix-based**: Related keys share common prefixes
2. **Hierarchical**: Use underscores to create logical groupings
3. **Descriptive**: Key names clearly indicate purpose
4. **Consistent**: Follow established patterns

## Controller-to-File Mapping

### Comprehensive Mapping Table
| Controller | Language File(s) | Key Prefixes | Purpose |
|------------|------------------|--------------|---------|
| about | about.php | about_ | About page content |
| absenceedit | absence.php, calendar.php | abs_, cal_ | Absence editing with calendar |
| absenceicon | absence.php | abs_ | Absence icon management |
| absences | absence.php | abs_ | Absence type listing |
| absum | absence.php, statistics.php, calendar.php | absum_, abs_, stats_, caledit_ | Absence summaries |
| alert | core.php | alert_ | Alert display (uses core only) |
| attachments | attachment.php | att_ | File attachment management |
| bulkedit | bulkedit.php, calendar.php, profile.php | bulk_, cal_, profile_abs_ | Bulk editing operations |
| calendaredit | calendar.php, calendaroptions.php | cal_, caledit_, calopt_ | Calendar editing |
| calendaroptions | calendaroptions.php | calopt_ | Calendar display options |
| calendarview | calendar.php, calendaroptions.php | cal_, calopt_ | Calendar display |
| config | config.php | config_ | System configuration |
| database | database.php | db_ | Database management |
| dataprivacy | gdpr.php | gdpr_ | Privacy/GDPR compliance |
| daynote | daynote.php | dn_ | Day notes functionality |
| declination | declination.php | decl_ | Absence declinations |
| groupcalendaredit | calendar.php | cal_ | Group calendar editing |
| groupedit | group.php | group_ | Group editing |
| groups | group.php | group_ | Group management |
| holidayedit | holiday.php | holiday_ | Holiday editing |
| holidays | holiday.php | holiday_ | Holiday management |
| home | core.php | (various) | Homepage (minimal language needs) |
| imprint | imprint.php | imprint_ | Legal imprint |
| log | log.php | log_ | Individual log viewing |
| login | login.php | login_ | User authentication |
| login2fa | login.php | login_ | Two-factor authentication |
| logout | login.php | login_ | User logout |
| logs | log.php | log_ | Log management |
| maintenance | maintenance.php | maint_ | System maintenance |
| messageedit | message.php | msg_ | Message editing |
| messages | message.php | msg_ | Message management |
| month | month.php, calendar.php | month_, cal_ | Monthly calendar views |
| monthedit | month.php | month_ | Month editing |
| passwordrequest | password.php | pwd_, pwdreq_ | Password reset requests |
| passwordreset | password.php, login.php | pwd_, login_ | Password reset processing |
| patternadd | pattern.php | pattern_ | Pattern creation |
| patternedit | pattern.php | pattern_ | Pattern editing |
| patterns | pattern.php | pattern_ | Pattern management |
| permissions | permission.php | perm_ | Permission management |
| phpinfo | core.php | (minimal) | System information |
| profile | profile.php | profile_ | User profile viewing |
| register | register.php, password.php | reg_, pwd_ | User registration |
| regionedit | region.php | region_ | Region editing |
| regions | region.php | region_ | Region management |
| remainder | remainder.php, calendar.php, absence.php | rem_, cal_, absum_ | Remainder calculations |
| roleedit | role.php | role_ | Role editing |
| roles | role.php | role_ | Role management |
| setup2fa | login.php | login_ | Two-factor setup |
| statistics | statistics.php | stats_ | Statistics display |
| statsabsence | statistics.php, absence.php | stats_, abs_ | Absence statistics |
| statsabstype | statistics.php, absence.php | stats_, abs_ | Absence type statistics |
| statspresence | statistics.php | stats_ | Presence statistics |
| statsremainder | statistics.php, remainder.php | stats_, rem_ | Remainder statistics |
| upload | upload.php | upload_ | File upload functionality |
| useradd | user.php, profile.php, password.php | user_, profile_, pwd_ | User creation |
| useredit | user.php, profile.php, password.php | user_, profile_, pwd_ | User editing |
| userimport | user.php, import.php | user_, import_ | User import functionality |
| users | user.php | user_ | User management |
| verify | login.php | login_ | Account verification |
| viewprofile | profile.php | profile_ | Profile viewing |
| year | year.php, calendar.php | year_, cal_ | Yearly calendar views |

### Core File Always Loaded
The `core.php` file is automatically loaded for every controller and contains:
- Alert messages (`alert_*`)
- Button labels (`btn_*`)
- Common form labels (`name`, `description`, etc.)
- General validation messages
- Universal interface elements

## Key Distribution Analysis

### File Size and Key Counts
Based on English language files:

| File | Keys | Percentage | Main Functionality |
|------|------|------------|-------------------|
| core.php | 26 | 1.4% | Essential global strings |
| absence.php | 94 | 5.0% | Absence management |
| calendar.php | 89 | 4.8% | Calendar functionality |
| user.php | 45 | 2.4% | User management |
| config.php | 67 | 3.6% | Configuration |
| login.php | 38 | 2.0% | Authentication |
| statistics.php | 24 | 1.3% | Reports |
| message.php | 31 | 1.7% | Messaging |
| holiday.php | 19 | 1.0% | Holiday management |
| profile.php | 68 | 3.6% | User profiles |
| Others | 1,369 | 73.2% | Remaining functionality |

### Memory Usage by Controller
Typical controller combinations and their memory footprint:

| Controller | Files Loaded | Keys Loaded | Memory vs Legacy |
|------------|--------------|-------------|------------------|
| home | core | 26 | 98.6% reduction |
| users | core + user | 71 | 96.2% reduction |
| calendarview | core + calendar + calendaroptions | 145 | 92.3% reduction |
| absenceedit | core + absence + calendar | 209 | 88.8% reduction |
| config | core + config | 93 | 95.0% reduction |
| statistics | core + statistics | 50 | 97.3% reduction |

## Implementation Requirements

### File Format Standards
1. **PHP Tags**: Use `<?php` opening tag, no closing tag
2. **Character Encoding**: UTF-8 without BOM
3. **Line Endings**: Unix LF line endings
4. **Indentation**: 2 spaces (follow .editorconfig)
5. **No Trailing Whitespace**: Clean line endings

### Code Standards
```php
<?php
/**
 * File header with description
 */

// Section comments for key groups
$LANG['key_name'] = 'Translation text';
$LANG['another_key'] = 'Another translation';

// Related keys grouped together
$LANG['form_title'] = 'Form Title';
$LANG['form_subtitle'] = 'Form Subtitle';
$LANG['form_help'] = 'Help text for this form';
```

### Validation Requirements
1. **Syntax Validation**: All files must be valid PHP
2. **Key Consistency**: Same keys across all languages
3. **No Duplicates**: Each key appears only once per file
4. **Proper Escaping**: Handle quotes and special characters correctly

## Loading System Integration

### Automatic Loading
The language loading system automatically:
1. Determines current controller
2. Loads `core.php` first
3. Loads controller-specific files based on mapping
4. Falls back to legacy files if modern structure unavailable

### Configuration Control
```php
// In config.app.php
define('USE_SPLIT_LANGUAGE_FILES', TRUE);  // Enable new system
define('USE_SPLIT_LANGUAGE_FILES', FALSE); // Use legacy system
```

### Performance Characteristics
- **Memory Reduction**: 80-99% fewer keys loaded per request
- **File Count**: 2-4 files instead of 4 large files
- **Loading Speed**: Faster parsing of smaller files
- **Caching**: Loaded files cached within request

## Development Guidelines

### Adding New Controllers
1. Create language file with controller name
2. Add controller mapping in `language.helper.php`
3. Test loading and fallback behavior
4. Document key prefixes and usage

### Adding New Languages
1. Copy English file structure
2. Translate all keys maintaining same structure
3. Test with language switching
4. Validate key parity with comparison tools

### Maintenance Best Practices
1. **Keep files synchronized**: All languages should have same keys
2. **Use descriptive keys**: Make purpose clear from key name
3. **Group related functionality**: Keep similar keys together
4. **Document changes**: Update mapping documentation
5. **Test thoroughly**: Verify all affected controllers work

---

This structure provides optimal performance while maintaining the familiar PHP array-based language system that users expect.