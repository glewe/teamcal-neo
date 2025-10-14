# TeamCal Neo Language System Modernization

## Project Overview

Replace the current file-based language system with a modern, modular, self-contained language management system that eliminates external file dependencies while maintaining 100% functionality and translation accuracy.

## Current System Analysis

### Existing Structure
- **Languages**: 2 languages are maintained: english and deutsch
- **Framework Languages**: `src/languages/english.php`, `src/languages/deutsch.php` (~1,026 keys each)
- **Application Languages**: `src/languages/english.app.php`, `src/languages/deutsch.app.php` (~730 keys each)
- **GDPR Languages**:  `src/languages/english.gdpr.php`, `src/languages/deutsch.gdpr.php` (~13 keys each)
- **Logging Languages**:  `src/languages/english.log.php`, `src/languages/deutsch.log.php` (~103 keys each)
- **Total Keys**: ~1,872 language keys per language (~3,744 total across both English and German)
- **Loading Method**: Direct file inclusion with `require_once` in `src/index.php`
- **Global Variable**: Uses `$LANG` array populated by language files

### Current Issues
1. **File Dependencies**: System breaks if language files are missing or corrupted
2. **Memory Inefficiency**: Loads all 1,872 keys regardless of page needs
3. **Maintenance Complexity**: Keys scattered across multiple files
4. **No Modular Loading**: Cannot load specific language subsets

## Goals and Requirements

### Primary Objectives
1. **Complete Replacement**: Replace file-based system with internal language definitions
2. **Zero File Dependencies**: All language data contained within PHP classes
3. **Modular Loading**: Load only required language keys per controller/page
4. **Memory Efficiency**: Reduce memory usage from 1,872 keys to controller-specific subsets
5. **Maintain Compatibility**: Preserve existing `$LANG` global array interface
6. **Bilingual Support**: Full English and German translation coverage

### Performance Targets
- **Memory Reduction**: From 1,872 keys to 50-200 keys per page (70-90% reduction)
- **Loading Speed**: No file I/O operations for language loading
- **Maintainability**: Single source of truth for all language data

## Proposed Architecture

### Core Components

#### 1. LanguageManager Class
```php
class LanguageManager {
    private static $language = 'english';
    private static $loadedModules = [];
    private static $cache = [];
    
    public static function setLanguage(string $language): void
    public static function loadModule(string $module): array
    public static function loadForController(string $controller): void
    public static function getAllModules(): array
    public static function compareLanguages(string $lang1, string $lang2): array
}
```

#### 2. Module Structure
Organize language keys into logical modules:
- **Core**: Common elements, dates, UI basics (~500 keys)
- **Framework-System**: Configuration, database, maintenance (~250 keys)
- **Framework-UI**: Buttons, alerts, modals, colors (~200 keys)
- **Framework-Auth**: Login, permissions, roles (~150 keys)
- **Gdpr**: GDPR strings (~13 keys)
- **Logging**: Logging strings (~103 keys)
- **Users**: User management, profiles, import (~150 keys)
- **Absences**: Absence management (~100 keys)
- **Calendar**: Calendar functionality (~50 keys)
- **And others**: Groups, holidays, roles, etc.

#### 3. Controller Mapping
Map each controller to its required modules:
```php
private static $controllerModules = [
    'about' => ['core', 'about', 'framework-navigation'],
    'calendar' => ['core', 'calendar', 'absences', 'holidays', 'users'],
    'users' => ['core', 'users', 'groups', 'permissions'],
    // ...
];
```

## Implementation Plan

### Phase 1: Foundation Setup
**Goal**: Create basic modular framework with sample modules

**Steps**:
1. Create `src/helpers/language.helper.php` with LanguageManager class
2. Implement basic module loading infrastructure
3. Create 3-5 sample modules with ~50 keys each
4. Test module loading and caching mechanisms
5. Verify syntax and basic functionality

**Validation**: System can load sample modules and populate `$LANG` array

### Phase 2: Key Extraction and Categorization
**Goal**: Analyze and categorize all existing language keys

**Steps**:
1. Create extraction script to parse existing language files
2. Handle dependencies (globals, constants, function calls)
3. Categorize all 1,872 keys into appropriate modules
4. Generate mapping of key→module assignments
5. Validate categorization logic and coverage
6. **Cleanup**: Remove temporary extraction scripts and validation files

**Validation**: All existing keys are categorized with no orphans

### Phase 3: Full Module Implementation
**Goal**: Implement complete modular system with all keys

**Steps**:
1. Generate PHP code for all modules with English keys
2. Extract and map German translations to same structure
3. Replace sample modules with complete module definitions
4. Update controller mappings for all existing controllers
5. Test complete system functionality
6. **Cleanup**: Remove sample/test modules and temporary mapping files

**Validation**: All 1,872 keys accessible through modular system

### Phase 4: Integration and Testing
**Goal**: Integrate with existing codebase and validate

**Steps**:
1. Update language loading in `src/index.php`
2. Update controllers to use `loadForController()` method
3. Test language comparison functionality
4. Validate memory usage improvements
5. Test both English and German language switching
6. **Cleanup**: Remove temporary test files and validation scripts

**Validation**: System works identically to original with better performance

### Phase 5: Cleanup and Documentation
**Goal**: Remove old system and document new approach

**Steps**:
1. **Cleanup**: Remove old language files (`src/languages/*.php`)
2. **Cleanup**: Remove temporary helper scripts and validation tools
3. **Cleanup**: Remove backup files and test data
4. Update documentation and code comments
5. Create migration guide for future developers
6. Final testing and validation
7. Commit and merge to master

**Validation**: Clean, self-contained language system ready for production

## Technical Specifications

### Module Definition Format
```php
case 'core':
    if (self::$language === 'deutsch') {
        $moduleKeys = [
            'locale' => 'de_DE',
            'action' => 'Aktion',
            'monthnames' => [
                1 => 'Januar', 2 => 'Februar', // ...
            ],
            // ...
        ];
    } else {
        $moduleKeys = [
            'locale' => 'en_US',
            'action' => 'Action', 
            'monthnames' => [
                1 => 'January', 2 => 'February', // ...
            ],
            // ...
        ];
    }
    break;
```

### Code Formatting Standards
- **EditorConfig**: Follow project's `.editorconfig` for consistent formatting
- **Indentation**: 2 spaces for PHP code (as defined in `.editorconfig`)
- **Line Endings**: LF (Unix-style) line endings
- **Character Encoding**: UTF-8
- **Trailing Whitespace**: Remove all trailing whitespace
- **PHPDoc method header exmaple**:
```php
/**
 * --------------------------------------------------------------------------
 * <title>
 * --------------------------------------------------------------------------
 * 
 * <description>
 *
 * @global object $UL User login object.
 * @global object $UO User options object.
 *
 * @param string $permission The permission to check.
 * @param int    $userid     The user ID to check.
 *
 * @return boolean True if the user is allowed, false otherwise.
 */
```

### Integration Interface
```php
// In controller files:
$lm = new LanguageManager();
$lm->setLanguage($userLanguage);
$lm->loadForController($controller); // Loads only required modules

// Maintains existing global interface:
global $LANG;
echo $LANG['action']; // Works exactly as before
```

### Error Handling
- Graceful degradation for undefined modules
- Fallback to English for missing German translations
- Clear error messages for development
- No fatal errors for missing keys

## Migration Strategy

### Backward Compatibility
- Maintain existing `$LANG` global array interface
- No changes required to existing view/template files
- Preserve all existing language keys and translations
- Keep same language switching mechanisms

### Risk Mitigation
- Develop on separate branch with easy rollback
- Maintain original files as backup during development
- Extensive testing before removing old system
- Incremental rollout with validation at each step

## Expected Benefits

### Performance Improvements
- **Memory Usage**: 70-90% reduction in loaded language keys
- **Loading Speed**: Eliminate file I/O operations
- **Caching**: Built-in module caching for repeated access

### Maintainability Improvements
- **Single Source**: All language data in one location
- **Type Safety**: Consistent array structures
- **Modularity**: Easier to extend and modify
- **Testing**: Simpler unit testing without file dependencies

### Reliability Improvements
- **No File Dependencies**: Eliminates "file not found" errors
- **Consistent Structure**: Prevents malformed language data
- **Built-in Fallbacks**: Graceful handling of missing translations

## Success Criteria

### Functional Requirements
- [ ] All 1,872 existing keys accessible through new system
- [ ] Both English and German translations preserved
- [ ] Language comparison functionality working
- [ ] No changes required to existing templates/views
- [ ] Language switching works identically to current system

### Performance Requirements
- [ ] Memory usage reduced by 70-90% for typical pages
- [ ] No file I/O operations for language loading
- [ ] Page load time improved or maintained
- [ ] Caching reduces repeated module loading overhead

### Quality Requirements
- [ ] Zero syntax errors in generated code
- [ ] 100% test coverage for new LanguageManager class
- [ ] Clean, readable, maintainable code structure
- [ ] Comprehensive documentation and examples

## Next Steps

1. **Review and Approve Concept**: Discuss this document and refine approach
2. **Begin Phase 1**: Create basic framework with sample modules
3. **Iterative Development**: Complete each phase with validation
4. **Continuous Testing**: Verify functionality at each step
5. **Final Integration**: Replace old system when new system is proven

---

*This document serves as the blueprint for the TeamCal Neo language system modernization project. All implementation should follow this plan with validation at each phase.*