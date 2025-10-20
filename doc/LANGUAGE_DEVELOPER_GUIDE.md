# TeamCal Neo Language System Developer Guide

## Overview

This guide provides comprehensive development documentation for maintaining and extending the TeamCal Neo language system. It covers best practices, development workflows, and guidelines for future language additions.

## System Architecture

### Core Components

#### 1. LanguageLoader Class (`src/helpers/language.helper.php`)
**Purpose**: Central language loading and management system
**Key Methods**:
- `loadForController($controller)` - Loads language files for specific controller
- `compareLanguages($lang1, $lang2)` - Compares language file completeness
- `getStats()` - Returns performance statistics
- `hasNewStructure()` - Checks if modern file structure exists

#### 2. Controller Detection (`src/index.php`)
**Purpose**: Automatic controller identification and language loading
**Process**:
1. Determine current controller from URL/config
2. Load core language files
3. Load controller-specific files
4. Fall back to legacy system if needed

#### 3. Configuration Control (`src/config/config.app.php`)
**Purpose**: System-wide language loading behavior
**Key Setting**:
```php
define('USE_SPLIT_LANGUAGE_FILES', TRUE);  // Enable new system
define('USE_SPLIT_LANGUAGE_FILES', FALSE); // Use legacy system
```

### Loading Sequence

```
1. Check USE_SPLIT_LANGUAGE_FILES setting
2. If TRUE:
   a. Check if new structure exists (core.php present)
   b. Load core.php (always)
   c. Load controller-specific files based on mapping
   d. If files missing, fall back to legacy system
3. If FALSE or fallback:
   a. Load traditional 4-file system
   b. Continue with legacy behavior
```

### Memory Usage Optimization

#### Performance Comparison
| System | Files Loaded | Keys Loaded | Memory Usage |
|--------|-------------|-------------|--------------|
| Legacy | 4 large files | ~1,870 keys | 100% baseline |
| Modern | 2-4 small files | 26-355 keys | 80-99% reduction |

#### Loading Patterns
```php
// Efficient loading examples
'home' => 26 keys (core only)
'users' => 71 keys (core + user)
'calendarview' => 145 keys (core + calendar + calendaroptions)
'absenceedit' => 209 keys (core + absence + calendar)
```

## Development Guidelines

### Adding New Controllers

#### 1. Determine Language Requirements
**Analyze Controller Functionality**:
```php
// Example: New 'projects' controller
// 1. What features does it provide?
//    - Project listing, creation, editing
// 2. What language keys are needed?
//    - project_*, btn_*, alert_*
// 3. Which existing files contain related keys?
//    - Check if project keys exist in legacy files
```

#### 2. Create Language File
**File Structure**:
```php
<?php
/**
 * TeamCal Neo Language File: Project Management
 * 
 * @file        project.php
 * @author      Developer Name
 * @copyright   (c) 2014-2025 George Lewe
 * @package     TeamCal Neo
 * @subpackage  Languages
 * 
 * Contains language strings for project management functionality.
 * Used by controllers: projects, projectedit, projectadd
 */

//
// Project Management
//
$LANG['project_list_title'] = 'Project Management';
$LANG['project_add_title'] = 'Add Project';
$LANG['project_edit_title'] = 'Edit Project';

//
// Project Properties
//
$LANG['project_name'] = 'Project Name';
$LANG['project_description'] = 'Description';
$LANG['project_status'] = 'Status';
$LANG['project_start_date'] = 'Start Date';
$LANG['project_end_date'] = 'End Date';

//
// Project Actions
//
$LANG['project_create_success'] = 'Project created successfully';
$LANG['project_update_success'] = 'Project updated successfully';
$LANG['project_delete_success'] = 'Project deleted successfully';
?>
```

#### 3. Update Controller Mapping
**Add to `src/helpers/language.helper.php`**:
```php
private static function getRequiredFiles($controller) {
    // Add your controller to the mapping
    $controllerFileMap = [
        // ... existing mappings ...
        'projects' => ['project'],
        'projectedit' => ['project'],
        'projectadd' => ['project'],
        // ... more mappings ...
    ];
}
```

#### 4. Create for All Languages
**Maintain Language Parity**:
```bash
# Create English version first
# Then create German version with same keys
cp src/languages/english/project.php src/languages/deutsch/project.php
# Translate German content while preserving keys
```

#### 5. Test Integration
**Validation Steps**:
```php
// Test language loading
LanguageLoader::loadForController('projects');
echo "Keys loaded: " . count($LANG);

// Test key access
echo $LANG['project_list_title'];  // Should not error

// Test language switching
$_SESSION['language'] = 'deutsch';
// Load and test German keys
```

### Adding New Languages

#### 1. Create Language Directory
```bash
mkdir src/languages/spanish
mkdir src/languages/french
# etc.
```

#### 2. Copy File Structure
```bash
# Start with English as template
cp -r src/languages/english/* src/languages/spanish/
```

#### 3. Translate Content
**Systematic Translation Process**:
```php
// Translate systematically, maintaining key names
// English: src/languages/english/core.php
$LANG['btn_save'] = 'Save';
$LANG['btn_cancel'] = 'Cancel';

// Spanish: src/languages/spanish/core.php
$LANG['btn_save'] = 'Guardar';
$LANG['btn_cancel'] = 'Cancelar';
```

#### 4. Validate Completeness
**Use Comparison Tools**:
```php
// Check all keys exist
$result = LanguageLoader::compareLanguages('english', 'spanish');
if (!empty($result['missing_keys'])) {
    echo "Missing keys in Spanish: " . implode(', ', $result['missing_keys']);
}
```

#### 5. Integration Testing
**Test Language Switching**:
```php
// Test all major controllers with new language
$controllers = ['home', 'users', 'calendar', 'login'];
foreach ($controllers as $controller) {
    $_SESSION['language'] = 'spanish';
    LanguageLoader::loadForController($controller);
    // Verify no missing key errors
}
```

### Maintaining Existing Languages

#### Key Management Best Practices

**1. Consistent Naming**:
```php
// Follow established patterns
$LANG['entity_action_result'] = 'Message';
$LANG['user_create_success'] = 'User created successfully';
$LANG['user_update_success'] = 'User updated successfully';

// Property naming
$LANG['entity_property'] = 'Label';
$LANG['user_username'] = 'Username';
$LANG['user_email'] = 'Email Address';
```

**2. Logical Grouping**:
```php
//
// Entity Management
//
$LANG['entity_list_title'] = 'Entity List';
$LANG['entity_add_title'] = 'Add Entity';
$LANG['entity_edit_title'] = 'Edit Entity';

//
// Entity Properties
//
$LANG['entity_name'] = 'Name';
$LANG['entity_description'] = 'Description';

//
// Entity Actions
//
$LANG['entity_save'] = 'Save Entity';
$LANG['entity_delete'] = 'Delete Entity';
```

**3. Placeholder Management**:
```php
// Use numbered placeholders for complex strings
$LANG['stats_summary'] = 'Showing %1$s to %2$s of %3$s results';

// Use descriptive placeholders in comments
$LANG['user_welcome'] = 'Welcome, %s!';  // %s = username
```

#### File Organization Standards

**1. Header Template**:
```php
<?php
/**
 * TeamCal Neo Language File: [Functionality]
 * 
 * @file        [filename].php
 * @author      [Author Name]
 * @copyright   (c) 2014-2025 George Lewe
 * @package     TeamCal Neo
 * @subpackage  Languages
 * @since       [Version]
 * 
 * Contains language strings for [detailed description].
 * Used by controllers: [controller1, controller2, ...]
 */
```

**2. Section Organization**:
```php
//
// Main Functionality
//
$LANG['main_keys'] = 'Values';

//
// Sub-functionality
//
$LANG['sub_keys'] = 'Values';

//
// Actions and Messages
//
$LANG['action_keys'] = 'Values';

//
// Validation and Errors
//
$LANG['error_keys'] = 'Values';
```

**3. Key Distribution**:
- Keep related functionality together
- Use consistent prefixes within sections
- Group by feature rather than alphabetically
- Separate actions from properties

### Performance Optimization

#### Loading Strategy
**Minimize File Count**:
```php
// Good: Focused loading
'users' => ['user']  // Only user management keys

// Better: Related functionality
'useredit' => ['user', 'profile']  // User + profile keys

// Avoid: Over-loading
'simple' => ['user', 'calendar', 'absence', 'config']  // Too many
```

**Core File Management**:
```php
// Keep core.php minimal - only universal keys
$LANG['btn_save'] = 'Save';        // Used everywhere
$LANG['alert_success'] = 'Success'; // Used everywhere

// Move specific keys to controller files
$LANG['user_add_title'] = 'Add User';  // Only in user.php
```

#### Memory Monitoring
**Performance Tracking**:
```php
class LanguageProfiler {
    private static $startMemory;
    private static $loadTimes = [];
    
    public static function startProfiling() {
        self::$startMemory = memory_get_usage(true);
    }
    
    public static function recordLoad($file, $keys) {
        $memory = memory_get_usage(true) - self::$startMemory;
        self::$loadTimes[] = [
            'file' => $file,
            'keys' => $keys,
            'memory' => $memory,
            'time' => microtime(true)
        ];
    }
    
    public static function getReport() {
        return [
            'total_memory' => memory_get_usage(true) - self::$startMemory,
            'files_loaded' => count(self::$loadTimes),
            'total_keys' => array_sum(array_column(self::$loadTimes, 'keys')),
            'details' => self::$loadTimes
        ];
    }
}
```

### Quality Assurance

#### Automated Testing
**Language Validation Script**:
```php
<?php
/**
 * Language System Validation
 */

class LanguageValidator {
    
    public function validateAllLanguages() {
        $languages = ['english', 'deutsch'];
        $controllers = $this->getAllControllers();
        
        $results = [];
        
        foreach ($languages as $lang) {
            foreach ($controllers as $controller) {
                $result = $this->validateController($lang, $controller);
                $results[$lang][$controller] = $result;
            }
        }
        
        return $results;
    }
    
    private function validateController($language, $controller) {
        try {
            $LANG = [];
            LanguageLoader::loadForController($controller, $language);
            
            return [
                'status' => 'success',
                'keys_loaded' => count($LANG),
                'memory_used' => memory_get_usage(true)
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getAllControllers() {
        $controllerFiles = glob('src/controller/*.php');
        return array_map(function($file) {
            return basename($file, '.php');
        }, $controllerFiles);
    }
}
```

#### Key Consistency Validation
**Cross-Language Validation**:
```php
function validateKeyConsistency() {
    $english = $this->getAllKeys('english');
    $deutsch = $this->getAllKeys('deutsch');
    
    $missingInGerman = array_diff($english, $deutsch);
    $missingInEnglish = array_diff($deutsch, $english);
    
    return [
        'english_only' => $missingInGerman,
        'deutsch_only' => $missingInEnglish,
        'total_english' => count($english),
        'total_deutsch' => count($deutsch),
        'consistency' => count($missingInGerman) === 0 && count($missingInEnglish) === 0
    ];
}
```

### Debugging and Troubleshooting

#### Development Debug Mode
**Enable Detailed Logging**:
```php
class LanguageDebugger {
    private static $debug = true;
    private static $log = [];
    
    public static function log($message, $data = null) {
        if (!self::$debug) return;
        
        self::$log[] = [
            'time' => microtime(true),
            'message' => $message,
            'data' => $data,
            'memory' => memory_get_usage(true),
            'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)
        ];
    }
    
    public static function dump() {
        if (!self::$debug) return;
        
        echo "<h3>Language System Debug Log</h3>";
        echo "<pre>" . print_r(self::$log, true) . "</pre>";
    }
}

// Usage in LanguageLoader
LanguageDebugger::log('Loading controller', $controller);
LanguageDebugger::log('Files to load', $requiredFiles);
LanguageDebugger::log('Keys loaded', count($LANG));
```

#### Common Development Issues
**1. Missing Controller Mapping**:
```php
// Problem: New controller not mapped
// Solution: Add to getRequiredFiles()
'newcontroller' => ['appropriate', 'language', 'files'],
```

**2. Key Conflicts**:
```php
// Problem: Same key in multiple files
// Solution: Use specific prefixes
// Instead of: $LANG['title'] in multiple files
// Use: $LANG['user_title'], $LANG['calendar_title']
```

**3. Performance Regression**:
```php
// Problem: Loading too many files
// Solution: Audit controller mappings
'overcomplicated' => ['file1', 'file2', 'file3', 'file4'],  // Too many
'simplified' => ['main', 'specific'],  // Better
```

### Release Management

#### Version Control Practices
**Language File Changes**:
```bash
# Commit language changes separately
git add src/languages/english/new_feature.php
git add src/languages/deutsch/new_feature.php
git commit -m "Add language support for new feature"

# Update mappings in separate commit
git add src/helpers/language.helper.php
git commit -m "Add controller mapping for new feature"
```

#### Migration Documentation
**For Each Release**:
1. Document new language files added
2. Note any key changes or removals
3. Provide migration instructions for custom languages
4. Update performance benchmarks

#### Backward Compatibility
**Ensure Compatibility**:
```php
// Always maintain fallback support
if (!class_exists('LanguageLoader')) {
    // Fallback to legacy system
    require_once 'languages/english.app.php';
}

// Preserve existing key names
// Don't rename keys in minor releases
// Deprecate gracefully in major releases
```

### Future Development Considerations

#### Scalability
**Supporting More Languages**:
- Maintain consistent file structure across all languages
- Use automated validation to ensure key parity
- Consider lazy loading for rarely used languages
- Implement caching for production environments

#### Enhanced Features
**Potential Improvements**:
- Pluralization support
- Context-aware translations
- Dynamic key loading based on user permissions
- Real-time language switching without page reload

#### Integration Points
**External System Integration**:
- Translation management systems
- Automated translation services
- Content management integration
- API-based language loading

---

This developer guide ensures consistent, maintainable, and performant language system development while preserving the user-friendly nature of the PHP array-based approach.