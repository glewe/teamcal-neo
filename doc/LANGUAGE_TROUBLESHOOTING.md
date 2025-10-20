# TeamCal Neo Language System Troubleshooting Guide

## Overview

This guide helps you diagnose and resolve common issues when migrating from the legacy 4-file language system to the new controller-specific structure.

## Quick Diagnosis

### Is the New System Active?

**Check Configuration:**
```php
// In src/config/config.app.php
define('USE_SPLIT_LANGUAGE_FILES', TRUE);  // New system enabled
define('USE_SPLIT_LANGUAGE_FILES', FALSE); // Legacy system active
```

**Test with Debug Output:**
```php
// Add to any controller file temporarily
echo "Language system: " . (USE_SPLIT_LANGUAGE_FILES ? "NEW" : "LEGACY") . "<br>";
echo "Keys loaded: " . count($LANG) . "<br>";
```

### Quick System Status Check
```php
// Check if new structure exists
$newSystem = file_exists('src/languages/english/core.php');
echo "New structure available: " . ($newSystem ? "YES" : "NO") . "<br>";

// Check if legacy files exist
$legacySystem = file_exists('src/languages/english.app.php');
echo "Legacy structure available: " . ($legacySystem ? "YES" : "NO") . "<br>";
```

## Common Issues and Solutions

### 1. Missing Language Keys

#### Problem: "Undefined array key" errors
```
Warning: Undefined array key "cal_selUser" in /path/to/view.php on line 42
```

#### Diagnosis Steps:
1. **Identify the missing key**: Note the key name (e.g., `cal_selUser`)
2. **Find the source**: Determine which view/controller uses this key
3. **Locate the key**: Search legacy files for where this key is defined

#### Solutions:

**Option A: Add to Existing Controller File**
```bash
# Search for the key in legacy files
grep -r "cal_selUser" src/languages/
```

If found in `calendar.php`:
```php
// Add to src/languages/english/calendar.php
$LANG['cal_selUser'] = 'Select User';
```

**Option B: Add Controller Mapping**
Check if the controller is mapped in `src/helpers/language.helper.php`:
```php
// Look for your controller in the mapping
'absum' => ['absence', 'statistics', 'calendar'],  // Example
```

If missing, add the appropriate mapping.

**Option C: Emergency Fix - Add to Core**
```php
// Add to src/languages/english/core.php temporarily
$LANG['cal_selUser'] = 'Select User';
```

#### Prevention:
- Use comprehensive controller mappings
- Test all major pages after migration
- Keep legacy files as backup during transition

### 2. Fallback System Not Working

#### Problem: New system errors but doesn't fall back to legacy files

#### Diagnosis:
```php
// Check file existence
echo "New core file: " . (file_exists('src/languages/english/core.php') ? "EXISTS" : "MISSING") . "<br>";
echo "Legacy app file: " . (file_exists('src/languages/english.app.php') ? "EXISTS" : "MISSING") . "<br>";

// Check loading sequence
if (class_exists('LanguageLoader')) {
    echo "LanguageLoader available<br>";
} else {
    echo "LanguageLoader NOT available<br>";
}
```

#### Solutions:

**Check Configuration:**
```php
// Ensure config is correct
if (!defined('USE_SPLIT_LANGUAGE_FILES')) {
    define('USE_SPLIT_LANGUAGE_FILES', FALSE);
}
```

**Verify File Permissions:**
```bash
# Check if files are readable
ls -la src/languages/english*
ls -la src/languages/english/
```

**Force Fallback:**
```php
// Temporarily disable new system
define('USE_SPLIT_LANGUAGE_FILES', FALSE);
```

### 3. Performance Not Improved

#### Problem: No noticeable performance improvement after migration

#### Diagnosis:
```php
// Add memory usage tracking
$memoryBefore = memory_get_usage();
// Language loading happens here
$memoryAfter = memory_get_usage();
echo "Memory used for languages: " . ($memoryAfter - $memoryBefore) . " bytes<br>";
echo "Total keys loaded: " . count($LANG) . "<br>";
```

#### Possible Causes:

**1. New System Not Active:**
```php
// Verify configuration
if (!USE_SPLIT_LANGUAGE_FILES) {
    echo "New system is DISABLED<br>";
}
```

**2. Loading All Files Instead of Selective:**
```php
// Check which files are being loaded
if (class_exists('LanguageLoader')) {
    $stats = LanguageLoader::getStats();
    print_r($stats);
}
```

**3. Legacy Fallback Always Triggered:**
Check if controller files exist:
```bash
ls -la src/languages/english/core.php
ls -la src/languages/english/calendar.php
```

#### Solutions:

**Verify Controller Mapping:**
```php
// Check if your controller is mapped
$controller = 'calendarview';  // Your controller
// Look in src/helpers/language.helper.php for mapping
```

**Create Missing Files:**
```php
// Create basic controller file
<?php
$LANG['your_key'] = 'Your Value';
?>
```

**Monitor Loading:**
```php
// Add debugging to see what's loaded
echo "Controller: " . $controller . "<br>";
echo "Files to load: " . print_r($requiredFiles, true) . "<br>";
```

### 4. Language Switching Broken

#### Problem: Switching between English and German doesn't work

#### Diagnosis:
```php
// Check current language
echo "Current language: " . (isset($_SESSION['language']) ? $_SESSION['language'] : 'not set') . "<br>";
echo "Language from config: " . L_LANG . "<br>";

// Check if both language directories exist
echo "English dir: " . (is_dir('src/languages/english') ? "EXISTS" : "MISSING") . "<br>";
echo "German dir: " . (is_dir('src/languages/deutsch') ? "EXISTS" : "MISSING") . "<br>";
```

#### Common Causes:

**1. Missing German Files:**
```bash
# Check if German files exist
ls -la src/languages/deutsch/
```

**2. Inconsistent File Structure:**
```bash
# Compare file counts
echo "English files: $(ls src/languages/english/*.php | wc -l)"
echo "German files: $(ls src/languages/deutsch/*.php | wc -l)"
```

**3. Key Mismatches:**
```php
// Check if same keys exist in both languages
$englishKeys = array_keys($LANG_EN);  // Load English
$germanKeys = array_keys($LANG_DE);   // Load German
$missing = array_diff($englishKeys, $germanKeys);
if (!empty($missing)) {
    echo "Missing German keys: " . implode(', ', $missing);
}
```

#### Solutions:

**Create Missing German Files:**
```bash
# Copy English structure to German
cp -r src/languages/english src/languages/deutsch
# Then translate the content
```

**Fix Key Mismatches:**
```php
// Ensure same keys in both files
// src/languages/english/calendar.php
$LANG['cal_title'] = 'Calendar';

// src/languages/deutsch/calendar.php
$LANG['cal_title'] = 'Kalender';
```

**Reset Language Session:**
```php
// Clear language session
unset($_SESSION['language']);
// Or force specific language
$_SESSION['language'] = 'deutsch';
```

### 5. Custom Language Files Not Working

#### Problem: Your custom language files aren't being loaded

#### Diagnosis:
```php
// Check custom language directory
$customLang = 'spanish';  // Your custom language
echo "Custom dir exists: " . (is_dir("src/languages/$customLang") ? "YES" : "NO") . "<br>";

// Check file structure
if (is_dir("src/languages/$customLang")) {
    $files = glob("src/languages/$customLang/*.php");
    echo "Custom files: " . count($files) . "<br>";
}
```

#### Solutions:

**Create Complete File Structure:**
```bash
# Copy English structure
cp -r src/languages/english src/languages/spanish
```

**Update Language Configuration:**
```php
// In your controller or config
$LANG = [];
require_once "src/languages/spanish/core.php";
require_once "src/languages/spanish/calendar.php";  // etc.
```

**Verify File Naming:**
- Files must be lowercase: `calendar.php` not `Calendar.php`
- Extension must be `.php`
- Directory name should match your language identifier

### 6. Syntax Errors in Language Files

#### Problem: PHP syntax errors break language loading

#### Diagnosis:
```bash
# Check syntax of language files
php -l src/languages/english/core.php
php -l src/languages/english/calendar.php
```

#### Common Syntax Issues:

**1. Missing PHP Tags:**
```php
// WRONG - missing opening tag
$LANG['key'] = 'value';

// CORRECT
<?php
$LANG['key'] = 'value';
```

**2. Quote Escaping:**
```php
// WRONG - unescaped quotes
$LANG['message'] = 'Don't forget to save';

// CORRECT - escaped quotes
$LANG['message'] = 'Don\'t forget to save';
// OR use double quotes
$LANG['message'] = "Don't forget to save";
```

**3. Array Syntax:**
```php
// WRONG - incorrect array syntax
$LANG('key') = 'value';

// CORRECT
$LANG['key'] = 'value';
```

#### Solutions:

**Validate All Files:**
```bash
# Check all English files
for file in src/languages/english/*.php; do
    echo "Checking $file"
    php -l "$file"
done
```

**Fix Common Issues:**
```php
// Use consistent escaping
$LANG['text'] = 'Use \' for single quotes';
$LANG['text'] = "Use \" for double quotes";

// Handle special characters
$LANG['euro'] = 'Price: €100';  // Should work fine
$LANG['newline'] = "Line 1\nLine 2";  // Use double quotes for escapes
```

## Advanced Troubleshooting

### Memory Usage Analysis

**Track Memory Usage:**
```php
function trackLanguageMemory() {
    $before = memory_get_usage(true);
    
    // Language loading happens here
    LanguageLoader::loadForController($controller);
    
    $after = memory_get_usage(true);
    $used = $after - $before;
    $keys = count($LANG);
    
    echo "Memory used: " . number_format($used) . " bytes<br>";
    echo "Keys loaded: $keys<br>";
    echo "Bytes per key: " . round($used / $keys, 2) . "<br>";
}
```

**Compare Systems:**
```php
// Test legacy system
define('USE_SPLIT_LANGUAGE_FILES', FALSE);
$memoryLegacy = testMemoryUsage();

// Test new system  
define('USE_SPLIT_LANGUAGE_FILES', TRUE);
$memoryNew = testMemoryUsage();

$improvement = (($memoryLegacy - $memoryNew) / $memoryLegacy) * 100;
echo "Memory improvement: " . round($improvement, 1) . "%<br>";
```

### File Loading Debug

**Track File Loading:**
```php
// Add to LanguageLoader class
private static $loadedFiles = [];

public static function debugLoadedFiles() {
    echo "Files loaded this request:<br>";
    foreach (self::$loadedFiles as $file) {
        echo "- $file<br>";
    }
}
```

**Monitor Loading Time:**
```php
$start = microtime(true);
LanguageLoader::loadForController($controller);
$end = microtime(true);
echo "Loading time: " . round(($end - $start) * 1000, 2) . " ms<br>";
```

### Key Distribution Analysis

**Analyze Key Usage:**
```php
function analyzeKeyUsage($controller) {
    $loaded = array_keys($LANG);
    
    // This would require more sophisticated tracking
    // to see which keys are actually used vs loaded
    
    echo "Controller: $controller<br>";
    echo "Keys available: " . count($loaded) . "<br>";
    
    // Could track usage with custom $LANG wrapper
}
```

## Prevention Strategies

### 1. Gradual Migration

**Start Small:**
```php
// Begin with high-traffic controllers
$priorityControllers = ['home', 'login', 'calendarview', 'users'];

// Migrate one at a time
foreach ($priorityControllers as $controller) {
    // Create language file
    // Test thoroughly
    // Move to next
}
```

### 2. Automated Testing

**Create Test Script:**
```php
function testLanguageSystem() {
    $controllers = ['home', 'users', 'calendar', 'login'];
    
    foreach ($controllers as $controller) {
        echo "Testing $controller...<br>";
        
        // Test key loading
        LanguageLoader::loadForController($controller);
        
        // Check for common keys
        $requiredKeys = ['btn_save', 'btn_cancel'];
        foreach ($requiredKeys as $key) {
            if (!isset($LANG[$key])) {
                echo "ERROR: Missing $key in $controller<br>";
            }
        }
    }
}
```

### 3. Backup Strategy

**Always Keep Backups:**
```bash
# Before migration
cp -r src/languages src/languages.backup

# Test new system
# If issues arise:
# rm -r src/languages
# mv src/languages.backup src/languages
```

### 4. Monitoring

**Set Up Logging:**
```php
function logLanguageIssues($message) {
    $log = date('Y-m-d H:i:s') . " - $message\n";
    file_put_contents('language_errors.log', $log, FILE_APPEND);
}

// Use in error handlers
if (!isset($LANG[$key])) {
    logLanguageIssues("Missing key: $key in controller: $controller");
}
```

## Getting Help

### Debug Information to Collect

When reporting issues, include:

1. **System Configuration:**
   ```php
   echo "USE_SPLIT_LANGUAGE_FILES: " . (USE_SPLIT_LANGUAGE_FILES ? "TRUE" : "FALSE") . "<br>";
   echo "Current language: " . L_LANG . "<br>";
   echo "Controller: " . $controller . "<br>";
   ```

2. **File Structure:**
   ```bash
   ls -la src/languages/
   ls -la src/languages/english/
   ls -la src/languages/deutsch/
   ```

3. **Error Messages:**
   - Complete error message
   - File and line number
   - Controller being accessed

4. **Browser Console:**
   - JavaScript errors
   - Network requests
   - Console warnings

### Support Resources

- **Language Helper Status:** Use `LanguageLoader::getStats()`
- **File Validation:** Check PHP syntax with `php -l filename.php`
- **Key Comparison:** Use built-in comparison tools
- **Performance Monitor:** Track memory usage improvements

Remember: The fallback system ensures your site keeps working even if migration encounters issues. Take your time and test thoroughly.