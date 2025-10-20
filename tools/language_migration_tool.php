<?php
/**
 * TeamCal Neo Language Migration Tool
 * 
 * Automated tool to help users convert legacy 4-file language system
 * to the new controller-specific structure.
 * 
 * Usage: php language_migration_tool.php [options]
 * 
 * @author TeamCal Neo Development Team
 * @version 1.0
 */

class LanguageMigrationTool {
    
    private $sourceLanguage;
    private $targetLanguage;
    private $backupCreated = false;
    private $verbose = true;
    private $dryRun = false;
    
    // Controller to file mapping - same as in language.helper.php
    private $controllerMapping = [
        'about' => ['about'],
        'absenceedit' => ['absence', 'calendar'],
        'absenceicon' => ['absence'],
        'absences' => ['absence'],
        'absum' => ['absence', 'statistics', 'calendar'],
        'alert' => [],
        'attachments' => ['attachment'],
        'bulkedit' => ['bulkedit', 'calendar', 'profile'],
        'calendaredit' => ['calendar', 'calendaroptions'],
        'calendaroptions' => ['calendaroptions'],
        'calendarview' => ['calendar', 'calendaroptions'],
        'config' => ['config'],
        'database' => ['database'],
        'dataprivacy' => ['gdpr'],
        'daynote' => ['daynote'],
        'declination' => ['declination'],
        'groupcalendaredit' => ['calendar'],
        'groupedit' => ['group'],
        'groups' => ['group'],
        'holidayedit' => ['holiday'],
        'holidays' => ['holiday'],
        'home' => [],
        'imprint' => ['imprint'],
        'log' => ['log'],
        'login' => ['login'],
        'login2fa' => ['login'],
        'logout' => ['login'],
        'logs' => ['log'],
        'maintenance' => ['maintenance'],
        'messageedit' => ['message'],
        'messages' => ['message'],
        'month' => ['month', 'calendar'],
        'monthedit' => ['month'],
        'passwordrequest' => ['password'],
        'passwordreset' => ['password', 'login'],
        'patternadd' => ['pattern'],
        'patternedit' => ['pattern'],
        'patterns' => ['pattern'],
        'permissions' => ['permission'],
        'phpinfo' => [],
        'profile' => ['profile'],
        'register' => ['register', 'password'],
        'regionedit' => ['region'],
        'regions' => ['region'],
        'remainder' => ['remainder', 'calendar', 'absence'],
        'roleedit' => ['role'],
        'roles' => ['role'],
        'setup2fa' => ['login'],
        'statistics' => ['statistics'],
        'statsabsence' => ['statistics', 'absence'],
        'statsabstype' => ['statistics', 'absence'],
        'statspresence' => ['statistics'],
        'statsremainder' => ['statistics', 'remainder'],
        'upload' => ['upload'],
        'useradd' => ['user', 'profile', 'password'],
        'useredit' => ['user', 'profile', 'password'],
        'userimport' => ['user', 'import'],
        'users' => ['user'],
        'verify' => ['login'],
        'viewprofile' => ['profile'],
        'year' => ['year', 'calendar']
    ];
    
    public function __construct($sourceLanguage = 'english', $targetLanguage = null) {
        $this->sourceLanguage = $sourceLanguage;
        $this->targetLanguage = $targetLanguage ?: $sourceLanguage;
    }
    
    /**
     * Main migration process
     */
    public function migrate($options = []) {
        $this->parseOptions($options);
        
        $this->output("=== TeamCal Neo Language Migration Tool ===\n");
        $this->output("Source Language: {$this->sourceLanguage}");
        $this->output("Target Language: {$this->targetLanguage}");
        $this->output("Dry Run: " . ($this->dryRun ? "YES" : "NO"));
        $this->output("");
        
        // Step 1: Validate source files
        if (!$this->validateSourceFiles()) {
            $this->output("ERROR: Source validation failed. Aborting migration.");
            return false;
        }
        
        // Step 2: Create backup
        if (!$this->dryRun && !$this->createBackup()) {
            $this->output("ERROR: Backup creation failed. Aborting migration.");
            return false;
        }
        
        // Step 3: Load legacy keys
        $legacyKeys = $this->loadLegacyKeys();
        if (empty($legacyKeys)) {
            $this->output("ERROR: No legacy keys found. Aborting migration.");
            return false;
        }
        
        $this->output("Loaded " . count($legacyKeys) . " keys from legacy files");
        
        // Step 4: Analyze key distribution
        $keyDistribution = $this->analyzeKeyDistribution($legacyKeys);
        $this->displayKeyDistribution($keyDistribution);
        
        // Step 5: Create controller-specific files
        $this->createControllerFiles($keyDistribution);
        
        // Step 6: Validate migration
        $this->validateMigration($legacyKeys);
        
        $this->output("\n=== Migration Complete ===");
        if ($this->backupCreated) {
            $this->output("Backup created at: src/languages/{$this->sourceLanguage}.backup/");
        }
        
        return true;
    }
    
    /**
     * Validate source legacy files exist
     */
    private function validateSourceFiles() {
        $required = [
            "src/languages/{$this->sourceLanguage}.php",
            "src/languages/{$this->sourceLanguage}.app.php"
        ];
        
        $optional = [
            "src/languages/{$this->sourceLanguage}.gdpr.php",
            "src/languages/{$this->sourceLanguage}.log.php"
        ];
        
        $this->output("Validating source files...");
        
        foreach ($required as $file) {
            if (!file_exists($file)) {
                $this->output("ERROR: Required file missing: $file");
                return false;
            }
            $this->output("✓ Found: $file");
        }
        
        foreach ($optional as $file) {
            if (file_exists($file)) {
                $this->output("✓ Found: $file");
            } else {
                $this->output("- Optional file missing: $file");
            }
        }
        
        return true;
    }
    
    /**
     * Create backup of existing files
     */
    private function createBackup() {
        $backupDir = "src/languages/{$this->sourceLanguage}.backup";
        
        if (is_dir($backupDir)) {
            $this->output("Backup directory already exists: $backupDir");
            return true;
        }
        
        $this->output("Creating backup...");
        
        if (!mkdir($backupDir, 0755, true)) {
            $this->output("ERROR: Could not create backup directory");
            return false;
        }
        
        // Copy legacy files
        $legacyFiles = [
            "src/languages/{$this->sourceLanguage}.php",
            "src/languages/{$this->sourceLanguage}.app.php",
            "src/languages/{$this->sourceLanguage}.gdpr.php",
            "src/languages/{$this->sourceLanguage}.log.php"
        ];
        
        foreach ($legacyFiles as $file) {
            if (file_exists($file)) {
                $backupFile = $backupDir . '/' . basename($file);
                if (copy($file, $backupFile)) {
                    $this->output("✓ Backed up: " . basename($file));
                } else {
                    $this->output("ERROR: Could not backup: $file");
                    return false;
                }
            }
        }
        
        $this->backupCreated = true;
        return true;
    }
    
    /**
     * Load all keys from legacy files
     */
    private function loadLegacyKeys() {
        $LANG = [];
        
        $legacyFiles = [
            "src/languages/{$this->sourceLanguage}.php",
            "src/languages/{$this->sourceLanguage}.app.php",
            "src/languages/{$this->sourceLanguage}.gdpr.php",
            "src/languages/{$this->sourceLanguage}.log.php"
        ];
        
        foreach ($legacyFiles as $file) {
            if (file_exists($file)) {
                $this->output("Loading keys from: " . basename($file));
                require $file;
            }
        }
        
        return $LANG;
    }
    
    /**
     * Analyze which keys belong to which controller files
     */
    private function analyzeKeyDistribution($legacyKeys) {
        $this->output("\nAnalyzing key distribution...");
        
        // Load English reference files to understand key patterns
        $englishPath = 'src/languages/english/';
        $distribution = [];
        
        // Initialize all known controller files
        $knownFiles = array_unique(array_merge(...array_values($this->controllerMapping)));
        $knownFiles[] = 'core';  // Always include core
        
        foreach ($knownFiles as $file) {
            $distribution[$file] = [];
        }
        
        // If English reference files exist, use them as guide
        if (is_dir($englishPath)) {
            $this->output("Using English reference files for key distribution...");
            foreach ($knownFiles as $file) {
                $englishFile = $englishPath . $file . '.php';
                if (file_exists($englishFile)) {
                    $englishKeys = $this->loadKeysFromFile($englishFile);
                    foreach ($englishKeys as $key) {
                        if (isset($legacyKeys[$key])) {
                            $distribution[$file][$key] = $legacyKeys[$key];
                        }
                    }
                }
            }
        } else {
            // Fallback: Use key prefixes to guess distribution
            $this->output("Using key prefix analysis for distribution...");
            $distribution = $this->analyzeByPrefix($legacyKeys);
        }
        
        // Handle unassigned keys - put them in core
        $assignedKeys = [];
        foreach ($distribution as $file => $keys) {
            $assignedKeys = array_merge($assignedKeys, array_keys($keys));
        }
        
        $unassigned = array_diff(array_keys($legacyKeys), $assignedKeys);
        if (!empty($unassigned)) {
            $this->output("Found " . count($unassigned) . " unassigned keys, adding to core.php");
            foreach ($unassigned as $key) {
                $distribution['core'][$key] = $legacyKeys[$key];
            }
        }
        
        return $distribution;
    }
    
    /**
     * Load keys from a specific file
     */
    private function loadKeysFromFile($file) {
        $LANG = [];
        if (file_exists($file)) {
            require $file;
        }
        return array_keys($LANG);
    }
    
    /**
     * Analyze keys by prefix patterns
     */
    private function analyzeByPrefix($legacyKeys) {
        $prefixMapping = [
            'core' => ['alert_', 'btn_', 'name', 'description', 'all', 'none', 'yes', 'no', 'active', 'today', 'year'],
            'absence' => ['abs_', 'absum_'],
            'calendar' => ['cal_', 'caledit_'],
            'user' => ['user_'],
            'login' => ['login_', 'logout_', 'mnu_user_'],
            'config' => ['config_'],
            'statistics' => ['stats_'],
            'message' => ['msg_'],
            'holiday' => ['holiday_'],
            'profile' => ['profile_'],
            'region' => ['region_'],
            'role' => ['role_'],
            'group' => ['group_'],
            'permission' => ['perm_'],
            'pattern' => ['pattern_'],
            'database' => ['db_'],
            'maintenance' => ['maint_'],
            'log' => ['log_'],
            'upload' => ['upload_'],
            'attachment' => ['att_'],
            'daynote' => ['dn_'],
            'declination' => ['decl_'],
            'gdpr' => ['gdpr_'],
            'imprint' => ['imprint_'],
            'password' => ['pwd_', 'pwdreq_'],
            'register' => ['reg_'],
            'remainder' => ['rem_'],
            'month' => ['month_'],
            'year' => ['year_'],
            'about' => ['about_'],
            'import' => ['import_'],
            'bulkedit' => ['bulk_'],
            'calendaroptions' => ['calopt_']
        ];
        
        $distribution = [];
        foreach ($prefixMapping as $file => $prefixes) {
            $distribution[$file] = [];
            foreach ($legacyKeys as $key => $value) {
                foreach ($prefixes as $prefix) {
                    if (strpos($key, $prefix) === 0 || $key === $prefix) {
                        $distribution[$file][$key] = $value;
                        break;
                    }
                }
            }
        }
        
        return $distribution;
    }
    
    /**
     * Display key distribution summary
     */
    private function displayKeyDistribution($distribution) {
        $this->output("\nKey Distribution Summary:");
        $this->output(str_repeat("-", 50));
        
        $totalKeys = 0;
        foreach ($distribution as $file => $keys) {
            $count = count($keys);
            $totalKeys += $count;
            if ($count > 0) {
                $this->output(sprintf("%-20s: %3d keys", $file . '.php', $count));
            }
        }
        
        $this->output(str_repeat("-", 50));
        $this->output(sprintf("%-20s: %3d keys", "TOTAL", $totalKeys));
        $this->output("");
    }
    
    /**
     * Create controller-specific files
     */
    private function createControllerFiles($distribution) {
        $targetDir = "src/languages/{$this->targetLanguage}";
        
        if (!$this->dryRun) {
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
                $this->output("Created directory: $targetDir");
            }
        }
        
        $this->output("Creating controller-specific files...");
        
        foreach ($distribution as $file => $keys) {
            if (empty($keys)) continue;
            
            $targetFile = $targetDir . '/' . $file . '.php';
            
            if ($this->dryRun) {
                $this->output("Would create: $targetFile (" . count($keys) . " keys)");
                continue;
            }
            
            $content = $this->generateFileContent($file, $keys);
            
            if (file_put_contents($targetFile, $content)) {
                $this->output("✓ Created: $file.php (" . count($keys) . " keys)");
            } else {
                $this->output("ERROR: Could not create: $targetFile");
            }
        }
    }
    
    /**
     * Generate content for a controller file
     */
    private function generateFileContent($file, $keys) {
        $functionality = ucfirst(str_replace('_', ' ', $file));
        $controllers = $this->getControllersForFile($file);
        
        $content = "<?php\n";
        $content .= "/**\n";
        $content .= " * TeamCal Neo Language File: $functionality\n";
        $content .= " * \n";
        $content .= " * @file        $file.php\n";
        $content .= " * @author      Migration Tool\n";
        $content .= " * @copyright   (c) 2014-2025 George Lewe\n";
        $content .= " * @package     TeamCal Neo\n";
        $content .= " * @subpackage  Languages\n";
        $content .= " * \n";
        $content .= " * Contains language strings for $functionality functionality.\n";
        if (!empty($controllers)) {
            $content .= " * Used by controllers: " . implode(', ', $controllers) . "\n";
        }
        $content .= " * \n";
        $content .= " * Generated by Language Migration Tool\n";
        $content .= " */\n\n";
        
        // Group keys by prefix for better organization
        $groupedKeys = $this->groupKeysByPrefix($keys);
        
        foreach ($groupedKeys as $prefix => $prefixKeys) {
            if ($prefix !== 'other') {
                $content .= "//\n";
                $content .= "// " . ucfirst(str_replace('_', ' ', $prefix)) . "\n";
                $content .= "//\n";
            }
            
            foreach ($prefixKeys as $key => $value) {
                $content .= "\$LANG['" . addslashes($key) . "'] = '" . addslashes($value) . "';\n";
            }
            $content .= "\n";
        }
        
        $content .= "?>";
        
        return $content;
    }
    
    /**
     * Group keys by common prefixes for better organization
     */
    private function groupKeysByPrefix($keys) {
        $groups = [];
        
        foreach ($keys as $key => $value) {
            $prefix = 'other';
            
            // Extract prefix (everything before first underscore)
            if (strpos($key, '_') !== false) {
                $prefix = substr($key, 0, strpos($key, '_'));
            }
            
            if (!isset($groups[$prefix])) {
                $groups[$prefix] = [];
            }
            $groups[$prefix][$key] = $value;
        }
        
        // Sort groups, putting 'other' last
        uksort($groups, function($a, $b) {
            if ($a === 'other') return 1;
            if ($b === 'other') return -1;
            return strcmp($a, $b);
        });
        
        return $groups;
    }
    
    /**
     * Get controllers that use a specific language file
     */
    private function getControllersForFile($file) {
        $controllers = [];
        
        foreach ($this->controllerMapping as $controller => $files) {
            if (in_array($file, $files)) {
                $controllers[] = $controller;
            }
        }
        
        return $controllers;
    }
    
    /**
     * Validate migration completeness
     */
    private function validateMigration($originalKeys) {
        $this->output("\nValidating migration...");
        
        if ($this->dryRun) {
            $this->output("Skipping validation (dry run mode)");
            return;
        }
        
        // Load all keys from new files
        $newKeys = [];
        $targetDir = "src/languages/{$this->targetLanguage}";
        
        $files = glob($targetDir . '/*.php');
        foreach ($files as $file) {
            $LANG = [];
            require $file;
            $newKeys = array_merge($newKeys, $LANG);
        }
        
        $originalCount = count($originalKeys);
        $newCount = count($newKeys);
        
        $missing = array_diff(array_keys($originalKeys), array_keys($newKeys));
        $extra = array_diff(array_keys($newKeys), array_keys($originalKeys));
        
        $this->output("Original keys: $originalCount");
        $this->output("New keys: $newCount");
        
        if (empty($missing) && empty($extra)) {
            $this->output("✓ Perfect migration! All keys preserved.");
        } else {
            if (!empty($missing)) {
                $this->output("⚠ Missing keys (" . count($missing) . "): " . implode(', ', array_slice($missing, 0, 5)) . (count($missing) > 5 ? '...' : ''));
            }
            if (!empty($extra)) {
                $this->output("⚠ Extra keys (" . count($extra) . "): " . implode(', ', array_slice($extra, 0, 5)) . (count($extra) > 5 ? '...' : ''));
            }
        }
    }
    
    /**
     * Parse command line options
     */
    private function parseOptions($options) {
        if (isset($options['verbose'])) {
            $this->verbose = $options['verbose'];
        }
        if (isset($options['dry-run'])) {
            $this->dryRun = $options['dry-run'];
        }
    }
    
    /**
     * Output message if verbose mode is enabled
     */
    private function output($message) {
        if ($this->verbose) {
            echo $message . "\n";
        }
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    $options = getopt('l:t:hvd', ['language:', 'target:', 'help', 'verbose', 'dry-run']);
    
    if (isset($options['h']) || isset($options['help'])) {
        echo "TeamCal Neo Language Migration Tool\n\n";
        echo "Usage: php language_migration_tool.php [options]\n\n";
        echo "Options:\n";
        echo "  -l, --language LANG    Source language to migrate (default: english)\n";
        echo "  -t, --target LANG      Target language name (default: same as source)\n";
        echo "  -d, --dry-run          Show what would be done without making changes\n";
        echo "  -v, --verbose          Enable verbose output (default: enabled)\n";
        echo "  -h, --help             Show this help message\n\n";
        echo "Examples:\n";
        echo "  php language_migration_tool.php\n";
        echo "  php language_migration_tool.php -l deutsch\n";
        echo "  php language_migration_tool.php -l english -t spanish\n";
        echo "  php language_migration_tool.php --dry-run\n\n";
        exit(0);
    }
    
    $sourceLanguage = $options['l'] ?? $options['language'] ?? 'english';
    $targetLanguage = $options['t'] ?? $options['target'] ?? $sourceLanguage;
    $dryRun = isset($options['d']) || isset($options['dry-run']);
    $verbose = !isset($options['q']) && !isset($options['quiet']);
    
    $migrator = new LanguageMigrationTool($sourceLanguage, $targetLanguage);
    $success = $migrator->migrate([
        'dry-run' => $dryRun,
        'verbose' => $verbose
    ]);
    
    exit($success ? 0 : 1);
} else {
    // Web interface usage example
    /*
    $migrator = new LanguageMigrationTool('english');
    $result = $migrator->migrate(['verbose' => true]);
    */
}