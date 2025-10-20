# TeamCal Neo Language Tools

This directory contains automated tools to help with language system migration, validation, and maintenance.

## Tools Overview

### 1. Language Migration Tool (`language_migration_tool.php`)

Automatically converts legacy 4-file language system to the new controller-specific structure.

**Features:**
- Automatically maps keys to appropriate controller files
- Creates backup of original files
- Supports dry-run mode for testing
- Validates migration completeness
- Works with any language

**Usage:**
```bash
# Basic migration (English)
php language_migration_tool.php

# Migrate German language
php language_migration_tool.php -l deutsch

# Dry run to see what would be done
php language_migration_tool.php --dry-run

# Create new language from English
php language_migration_tool.php -l english -t spanish

# See all options
php language_migration_tool.php --help
```

**Example Output:**
```
=== TeamCal Neo Language Migration Tool ===
Source Language: english
Target Language: english
Dry Run: NO

Validating source files...
✓ Found: src/languages/english.php
✓ Found: src/languages/english.app.php
✓ Found: src/languages/english.gdpr.php
✓ Found: src/languages/english.log.php

Creating backup...
✓ Backed up: english.php
✓ Backed up: english.app.php
✓ Backed up: english.gdpr.php
✓ Backed up: english.log.php

Loaded 1870 keys from legacy files

Key Distribution Summary:
--------------------------------------------------
core.php            :  26 keys
absence.php          :  94 keys
calendar.php         :  89 keys
user.php             :  45 keys
config.php           :  67 keys
...
--------------------------------------------------
TOTAL                :1870 keys

Creating controller-specific files...
✓ Created: core.php (26 keys)
✓ Created: absence.php (94 keys)
✓ Created: calendar.php (89 keys)
...

Validating migration...
Original keys: 1870
New keys: 1870
✓ Perfect migration! All keys preserved.

=== Migration Complete ===
Backup created at: src/languages/english.backup/
```

### 2. Language Validation Tool (`language_validation_tool.php`)

Comprehensive validation of language system health, consistency, and performance.

**Features:**
- System status validation
- File structure analysis
- Key consistency checking between languages
- Controller mapping validation
- Performance measurement
- PHP syntax validation
- Comprehensive reporting

**Usage:**
```bash
# Validate default languages (English, German)
php language_validation_tool.php

# Validate specific languages
php language_validation_tool.php -l english,deutsch,spanish

# Quiet mode
php language_validation_tool.php --quiet

# See all options
php language_validation_tool.php --help
```

**Example Output:**
```
=== TeamCal Neo Language Validation Tool ===

1. System Status Validation
----------------------------------------
✓ Modern language system is ENABLED
✓ Language helper found: src/helpers/language.helper.php
✓ Legacy structure available for: english (4/4 files)
✓ Legacy structure available for: deutsch (4/4 files)
✓ Modern structure available for: english (35 files)
✓ Modern structure available for: deutsch (35 files)

2. File Structure Validation
----------------------------------------
Analyzing english structure...
  Legacy system: 1870 keys across 4 files
  Modern system: 1870 keys across 35 files
Analyzing deutsch structure...
  Legacy system: 1870 keys across 4 files
  Modern system: 1870 keys across 35 files

Comparing language structures...
Comparing english vs deutsch:
  ✓ File structure matches

3. Key Consistency Validation
----------------------------------------
Loaded 1870 keys for english
Loaded 1870 keys for deutsch
english vs deutsch consistency: 100.0%

4. Controller Mapping Validation
----------------------------------------
✓ Language helper found with mappings
Found 56 controllers in codebase

5. Performance Validation
----------------------------------------
Measuring performance for english...
  Legacy: 1870 keys, 245,760 bytes, 12.34 ms
  Modern: 115 keys, 15,360 bytes, 2.18 ms
  Memory improvement: 93.7%
Measuring performance for deutsch...
  Legacy: 1870 keys, 245,760 bytes, 12.15 ms
  Modern: 115 keys, 15,360 bytes, 2.21 ms
  Memory improvement: 93.8%

6. Syntax Validation
----------------------------------------
english syntax: 39/39 files valid
deutsch syntax: 39/39 files valid

=== VALIDATION REPORT ===

SYSTEM STATUS:
- Mode: modern
- Helper: Available
- Legacy: Available
- Modern: Available

KEY CONSISTENCY:
- deutsch: 100.0% consistent

PERFORMANCE:
- english: 93.7% memory improvement
- deutsch: 93.8% memory improvement

Validation complete!
```

## Quick Start Guide

### 1. For New Installations
If you're setting up TeamCal Neo with the modern language system:

```bash
# 1. Validate your system
php tools/language_validation_tool.php

# 2. If you have custom languages, migrate them
php tools/language_migration_tool.php -l your_custom_language

# 3. Enable modern system in config
# Set USE_SPLIT_LANGUAGE_FILES = TRUE in src/config/config.app.php
```

### 2. For Existing Installations
If you're upgrading from the legacy system:

```bash
# 1. Check current system status
php tools/language_validation_tool.php

# 2. Backup and migrate your languages
php tools/language_migration_tool.php -l english
php tools/language_migration_tool.php -l deutsch
# ... repeat for any custom languages

# 3. Validate migration
php tools/language_validation_tool.php

# 4. Enable modern system
# Set USE_SPLIT_LANGUAGE_FILES = TRUE in src/config/config.app.php

# 5. Test your application thoroughly
```

### 3. For Custom Language Development
If you're creating new languages:

```bash
# 1. Create new language from English template
php tools/language_migration_tool.php -l english -t your_language

# 2. Translate the generated files manually
# Edit files in src/languages/your_language/

# 3. Validate your translation
php tools/language_validation_tool.php -l english,your_language
```

## Tool Configuration

### Migration Tool Options

| Option | Description | Example |
|--------|-------------|---------|
| `-l, --language` | Source language to migrate | `-l deutsch` |
| `-t, --target` | Target language name | `-t spanish` |
| `-d, --dry-run` | Show changes without applying | `--dry-run` |
| `-v, --verbose` | Enable detailed output | `--verbose` |
| `-h, --help` | Show help message | `--help` |

### Validation Tool Options

| Option | Description | Example |
|--------|-------------|---------|
| `-l, --languages` | Languages to validate (comma-separated) | `-l english,deutsch,spanish` |
| `-v, --verbose` | Enable detailed output | `--verbose` |
| `-q, --quiet` | Suppress detailed output | `--quiet` |
| `-h, --help` | Show help message | `--help` |

## Integration with Development Workflow

### Pre-commit Validation
Add to your git pre-commit hook:
```bash
#!/bin/bash
echo "Validating language files..."
php tools/language_validation_tool.php --quiet
if [ $? -ne 0 ]; then
    echo "Language validation failed!"
    exit 1
fi
```

### Continuous Integration
Add to your CI pipeline:
```yaml
- name: Validate Language System
  run: php tools/language_validation_tool.php
```

### Development Testing
Before making language changes:
```bash
# Test current state
php tools/language_validation_tool.php

# Make your changes
# ...

# Validate changes
php tools/language_validation_tool.php

# Check syntax of specific language
php -l src/languages/english/new_file.php
```

## Troubleshooting

### Common Issues

**Migration fails with "Source validation failed":**
- Ensure legacy language files exist in `src/languages/`
- Check file permissions
- Verify PHP syntax in legacy files

**Validation shows inconsistencies:**
- Use migration tool to create missing files
- Check for typos in key names
- Ensure all languages have same file structure

**Performance improvements not showing:**
- Verify `USE_SPLIT_LANGUAGE_FILES = TRUE` in config
- Check that modern files are being loaded
- Test with actual page requests, not just tool output

### Getting Help

1. **Check documentation**: Review the guides in `doc/` directory
2. **Run validation**: Use the validation tool to identify issues
3. **Dry run migration**: Test migration with `--dry-run` first
4. **Check logs**: Look for PHP errors in your web server logs
5. **Backup first**: Always create backups before making changes

## File Structure

```
tools/
├── language_migration_tool.php     # Converts legacy to modern structure
├── language_validation_tool.php    # Validates system health
└── README.md                       # This file

Generated during migration:
src/languages/
├── english.backup/                 # Backup of original files
├── english/                        # Modern structure
│   ├── core.php
│   ├── absence.php
│   └── ...
└── deutsch/                        # Modern structure
    ├── core.php
    ├── absence.php
    └── ...
```

## Best Practices

1. **Always backup**: Tools create backups, but make your own too
2. **Test thoroughly**: Use dry-run mode before real migration
3. **Validate frequently**: Run validation after any language changes
4. **Maintain consistency**: Keep all languages synchronized
5. **Document custom changes**: Note any custom modifications
6. **Use version control**: Commit language changes separately

These tools make language system migration and maintenance safe, automated, and reliable.