<?php
/**
 * TeamCal Neo Language Validation Tool
 * 
 * Validates language file completeness, consistency, and performance
 * for both legacy and modern language systems.
 * 
 * Usage: php language_validation_tool.php [options]
 * 
 * @author TeamCal Neo Development Team
 * @version 1.0
 */

class LanguageValidationTool {
    
    private $verbose = true;
    private $languages = ['english', 'deutsch'];
    private $controllers = [];
    
    public function __construct($options = []) {
        $this->parseOptions($options);
        $this->loadControllers();
    }
    
    /**
     * Run comprehensive validation
     */
    public function validate() {
        $this->output("=== TeamCal Neo Language Validation Tool ===\n");
        
        $results = [
            'system_status' => $this->validateSystemStatus(),
            'file_structure' => $this->validateFileStructure(),
            'key_consistency' => $this->validateKeyConsistency(),
            'controller_mapping' => $this->validateControllerMapping(),
            'performance' => $this->validatePerformance(),
            'syntax' => $this->validateSyntax()
        ];
        
        $this->generateReport($results);
        return $results;
    }
    
    /**
     * Validate overall system status
     */
    private function validateSystemStatus() {
        $this->output("1. System Status Validation");
        $this->output(str_repeat("-", 40));
        
        $status = [];
        
        // Check configuration
        $configFile = 'src/config/config.app.php';
        if (file_exists($configFile)) {
            $content = file_get_contents($configFile);
            if (strpos($content, 'USE_SPLIT_LANGUAGE_FILES') !== false) {
                if (strpos($content, "define('USE_SPLIT_LANGUAGE_FILES', TRUE)") !== false) {
                    $status['system_mode'] = 'modern';
                    $this->output("✓ Modern language system is ENABLED");
                } else {
                    $status['system_mode'] = 'legacy';
                    $this->output("✓ Legacy language system is ENABLED");
                }
            } else {
                $status['system_mode'] = 'unknown';
                $this->output("⚠ USE_SPLIT_LANGUAGE_FILES not found in config");
            }
        } else {
            $status['system_mode'] = 'config_missing';
            $this->output("✗ Config file not found: $configFile");
        }
        
        // Check language helper
        $helperFile = 'src/helpers/language.helper.php';
        if (file_exists($helperFile)) {
            $status['helper_available'] = true;
            $this->output("✓ Language helper found: $helperFile");
        } else {
            $status['helper_available'] = false;
            $this->output("✗ Language helper missing: $helperFile");
        }
        
        // Check both legacy and modern structures
        $status['legacy_available'] = $this->checkLegacyStructure();
        $status['modern_available'] = $this->checkModernStructure();
        
        $this->output("");
        return $status;
    }
    
    /**
     * Check if legacy structure exists
     */
    private function checkLegacyStructure() {
        $legacyFiles = [];
        foreach ($this->languages as $lang) {
            $files = [
                "src/languages/{$lang}.php",
                "src/languages/{$lang}.app.php",
                "src/languages/{$lang}.gdpr.php",
                "src/languages/{$lang}.log.php"
            ];
            
            $exists = 0;
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $exists++;
                }
            }
            
            $legacyFiles[$lang] = [
                'total' => count($files),
                'exists' => $exists,
                'complete' => $exists >= 2  // At least .php and .app.php
            ];
        }
        
        $hasLegacy = false;
        foreach ($legacyFiles as $lang => $data) {
            if ($data['complete']) {
                $this->output("✓ Legacy structure available for: $lang ({$data['exists']}/{$data['total']} files)");
                $hasLegacy = true;
            } else {
                $this->output("⚠ Incomplete legacy structure for: $lang ({$data['exists']}/{$data['total']} files)");
            }
        }
        
        return $hasLegacy;
    }
    
    /**
     * Check if modern structure exists
     */
    private function checkModernStructure() {
        $modernDirs = [];
        foreach ($this->languages as $lang) {
            $dir = "src/languages/$lang";
            if (is_dir($dir)) {
                $files = glob("$dir/*.php");
                $coreExists = file_exists("$dir/core.php");
                
                $modernDirs[$lang] = [
                    'directory_exists' => true,
                    'file_count' => count($files),
                    'core_exists' => $coreExists,
                    'complete' => $coreExists && count($files) > 5
                ];
                
                if ($modernDirs[$lang]['complete']) {
                    $this->output("✓ Modern structure available for: $lang ({$modernDirs[$lang]['file_count']} files)");
                } else {
                    $this->output("⚠ Incomplete modern structure for: $lang ({$modernDirs[$lang]['file_count']} files, core: " . ($coreExists ? 'yes' : 'no') . ")");
                }
            } else {
                $modernDirs[$lang] = [
                    'directory_exists' => false,
                    'file_count' => 0,
                    'core_exists' => false,
                    'complete' => false
                ];
                $this->output("✗ Modern structure missing for: $lang");
            }
        }
        
        return array_filter($modernDirs, function($data) {
            return $data['complete'];
        });
    }
    
    /**
     * Validate file structure consistency
     */
    private function validateFileStructure() {
        $this->output("2. File Structure Validation");
        $this->output(str_repeat("-", 40));
        
        $structure = [];
        
        foreach ($this->languages as $lang) {
            $structure[$lang] = $this->analyzeLanguageStructure($lang);
        }
        
        // Compare structures between languages
        if (count($this->languages) > 1) {
            $this->compareLanguageStructures($structure);
        }
        
        $this->output("");
        return $structure;
    }
    
    /**
     * Analyze structure for a specific language
     */
    private function analyzeLanguageStructure($language) {
        $this->output("Analyzing $language structure...");
        
        $structure = [
            'legacy' => [],
            'modern' => []
        ];
        
        // Check legacy files
        $legacyFiles = [
            "src/languages/{$language}.php",
            "src/languages/{$language}.app.php",
            "src/languages/{$language}.gdpr.php",
            "src/languages/{$language}.log.php"
        ];
        
        foreach ($legacyFiles as $file) {
            if (file_exists($file)) {
                $LANG = [];
                require $file;
                $structure['legacy'][basename($file)] = [
                    'exists' => true,
                    'keys' => count($LANG),
                    'size' => filesize($file)
                ];
            } else {
                $structure['legacy'][basename($file)] = [
                    'exists' => false,
                    'keys' => 0,
                    'size' => 0
                ];
            }
        }
        
        // Check modern files
        $modernDir = "src/languages/$language";
        if (is_dir($modernDir)) {
            $modernFiles = glob("$modernDir/*.php");
            foreach ($modernFiles as $file) {
                $LANG = [];
                require $file;
                $structure['modern'][basename($file)] = [
                    'exists' => true,
                    'keys' => count($LANG),
                    'size' => filesize($file)
                ];
            }
        }
        
        // Summary
        $legacyTotal = array_sum(array_column($structure['legacy'], 'keys'));
        $modernTotal = array_sum(array_column($structure['modern'], 'keys'));
        
        $this->output("  Legacy system: $legacyTotal keys across " . count(array_filter($structure['legacy'], function($f) { return $f['exists']; })) . " files");
        $this->output("  Modern system: $modernTotal keys across " . count($structure['modern']) . " files");
        
        return $structure;
    }
    
    /**
     * Compare structures between languages
     */
    private function compareLanguageStructures($structures) {
        $this->output("\nComparing language structures...");
        
        $languages = array_keys($structures);
        if (count($languages) < 2) return;
        
        $baseLang = $languages[0];
        
        foreach (array_slice($languages, 1) as $lang) {
            $this->output("Comparing $baseLang vs $lang:");
            
            // Compare modern structure
            $baseModern = array_keys($structures[$baseLang]['modern']);
            $compareModern = array_keys($structures[$lang]['modern']);
            
            $missingFiles = array_diff($baseModern, $compareModern);
            $extraFiles = array_diff($compareModern, $baseModern);
            
            if (empty($missingFiles) && empty($extraFiles)) {
                $this->output("  ✓ File structure matches");
            } else {
                if (!empty($missingFiles)) {
                    $this->output("  ⚠ Missing files in $lang: " . implode(', ', $missingFiles));
                }
                if (!empty($extraFiles)) {
                    $this->output("  ⚠ Extra files in $lang: " . implode(', ', $extraFiles));
                }
            }
        }
    }
    
    /**
     * Validate key consistency between languages
     */
    private function validateKeyConsistency() {
        $this->output("3. Key Consistency Validation");
        $this->output(str_repeat("-", 40));
        
        $consistency = [];
        
        // Load all keys for each language
        $languageKeys = [];
        foreach ($this->languages as $lang) {
            $languageKeys[$lang] = $this->loadAllKeys($lang);
            $this->output("Loaded " . count($languageKeys[$lang]) . " keys for $lang");
        }
        
        // Compare key sets
        if (count($this->languages) > 1) {
            $baseLang = $this->languages[0];
            $baseKeys = array_keys($languageKeys[$baseLang]);
            
            foreach (array_slice($this->languages, 1) as $lang) {
                $compareKeys = array_keys($languageKeys[$lang]);
                
                $missing = array_diff($baseKeys, $compareKeys);
                $extra = array_diff($compareKeys, $baseKeys);
                
                $consistency[$lang] = [
                    'missing_keys' => $missing,
                    'extra_keys' => $extra,
                    'total_keys' => count($compareKeys),
                    'consistency_rate' => count($compareKeys) > 0 ? 
                        (count($baseKeys) - count($missing) - count($extra)) / count($baseKeys) * 100 : 0
                ];
                
                $this->output("$baseLang vs $lang consistency: " . 
                    round($consistency[$lang]['consistency_rate'], 1) . "%");
                
                if (!empty($missing)) {
                    $this->output("  Missing keys: " . count($missing) . 
                        " (first 5: " . implode(', ', array_slice($missing, 0, 5)) . ")");
                }
                if (!empty($extra)) {
                    $this->output("  Extra keys: " . count($extra) . 
                        " (first 5: " . implode(', ', array_slice($extra, 0, 5)) . ")");
                }
            }
        }
        
        $this->output("");
        return $consistency;
    }
    
    /**
     * Load all keys for a language (modern or legacy)
     */
    private function loadAllKeys($language) {
        $LANG = [];
        
        // Try modern structure first
        $modernDir = "src/languages/$language";
        if (is_dir($modernDir)) {
            $files = glob("$modernDir/*.php");
            if (!empty($files)) {
                foreach ($files as $file) {
                    require $file;
                }
                return $LANG;
            }
        }
        
        // Fallback to legacy structure
        $legacyFiles = [
            "src/languages/{$language}.php",
            "src/languages/{$language}.app.php",
            "src/languages/{$language}.gdpr.php",
            "src/languages/{$language}.log.php"
        ];
        
        foreach ($legacyFiles as $file) {
            if (file_exists($file)) {
                require $file;
            }
        }
        
        return $LANG;
    }
    
    /**
     * Validate controller mapping completeness
     */
    private function validateControllerMapping() {
        $this->output("4. Controller Mapping Validation");
        $this->output(str_repeat("-", 40));
        
        $mapping = [];
        
        // Check if language helper exists and has mappings
        $helperFile = 'src/helpers/language.helper.php';
        if (!file_exists($helperFile)) {
            $this->output("✗ Language helper not found");
            return ['error' => 'Language helper not found'];
        }
        
        // Extract controller mappings from helper
        $helperContent = file_get_contents($helperFile);
        if (strpos($helperContent, 'getRequiredFiles') === false) {
            $this->output("✗ Controller mappings not found in helper");
            return ['error' => 'Controller mappings not found'];
        }
        
        $this->output("✓ Language helper found with mappings");
        
        // Check coverage of actual controllers
        $controllerFiles = glob('src/controller/*.php');
        $actualControllers = array_map(function($file) {
            return basename($file, '.php');
        }, $controllerFiles);
        
        $this->output("Found " . count($actualControllers) . " controllers in codebase");
        
        // This is a simplified check - in practice, you'd parse the actual mappings
        $mapping['total_controllers'] = count($actualControllers);
        $mapping['controllers'] = $actualControllers;
        
        $this->output("");
        return $mapping;
    }
    
    /**
     * Validate performance characteristics
     */
    private function validatePerformance() {
        $this->output("5. Performance Validation");
        $this->output(str_repeat("-", 40));
        
        $performance = [];
        
        foreach ($this->languages as $lang) {
            $performance[$lang] = $this->measureLanguagePerformance($lang);
        }
        
        $this->output("");
        return $performance;
    }
    
    /**
     * Measure performance for a specific language
     */
    private function measureLanguagePerformance($language) {
        $this->output("Measuring performance for $language...");
        
        $results = [
            'legacy' => $this->measureLegacyPerformance($language),
            'modern' => $this->measureModernPerformance($language)
        ];
        
        if ($results['legacy'] && $results['modern']) {
            $improvement = (($results['legacy']['memory'] - $results['modern']['memory']) / $results['legacy']['memory']) * 100;
            $this->output("  Memory improvement: " . round($improvement, 1) . "%");
            $results['improvement'] = $improvement;
        }
        
        return $results;
    }
    
    /**
     * Measure legacy system performance
     */
    private function measureLegacyPerformance($language) {
        $startMemory = memory_get_usage(true);
        $startTime = microtime(true);
        
        $LANG = [];
        $legacyFiles = [
            "src/languages/{$language}.php",
            "src/languages/{$language}.app.php",
            "src/languages/{$language}.gdpr.php",
            "src/languages/{$language}.log.php"
        ];
        
        $filesLoaded = 0;
        foreach ($legacyFiles as $file) {
            if (file_exists($file)) {
                require $file;
                $filesLoaded++;
            }
        }
        
        if ($filesLoaded === 0) {
            return null;
        }
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $result = [
            'files_loaded' => $filesLoaded,
            'keys_loaded' => count($LANG),
            'memory' => $endMemory - $startMemory,
            'time' => $endTime - $startTime
        ];
        
        $this->output("  Legacy: {$result['keys_loaded']} keys, " . 
            number_format($result['memory']) . " bytes, " . 
            round($result['time'] * 1000, 2) . " ms");
        
        return $result;
    }
    
    /**
     * Measure modern system performance (simulate core + calendar)
     */
    private function measureModernPerformance($language) {
        $modernDir = "src/languages/$language";
        if (!is_dir($modernDir)) {
            return null;
        }
        
        $startMemory = memory_get_usage(true);
        $startTime = microtime(true);
        
        $LANG = [];
        $testFiles = ['core.php', 'calendar.php'];  // Typical controller loading
        $filesLoaded = 0;
        
        foreach ($testFiles as $file) {
            $fullPath = "$modernDir/$file";
            if (file_exists($fullPath)) {
                require $fullPath;
                $filesLoaded++;
            }
        }
        
        if ($filesLoaded === 0) {
            return null;
        }
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $result = [
            'files_loaded' => $filesLoaded,
            'keys_loaded' => count($LANG),
            'memory' => $endMemory - $startMemory,
            'time' => $endTime - $startTime
        ];
        
        $this->output("  Modern: {$result['keys_loaded']} keys, " . 
            number_format($result['memory']) . " bytes, " . 
            round($result['time'] * 1000, 2) . " ms");
        
        return $result;
    }
    
    /**
     * Validate PHP syntax of all language files
     */
    private function validateSyntax() {
        $this->output("6. Syntax Validation");
        $this->output(str_repeat("-", 40));
        
        $syntax = [];
        
        foreach ($this->languages as $lang) {
            $syntax[$lang] = $this->checkLanguageSyntax($lang);
        }
        
        $this->output("");
        return $syntax;
    }
    
    /**
     * Check syntax for all files in a language
     */
    private function checkLanguageSyntax($language) {
        $results = [];
        
        // Check legacy files
        $legacyFiles = [
            "src/languages/{$language}.php",
            "src/languages/{$language}.app.php",
            "src/languages/{$language}.gdpr.php",
            "src/languages/{$language}.log.php"
        ];
        
        foreach ($legacyFiles as $file) {
            if (file_exists($file)) {
                $results['legacy'][basename($file)] = $this->checkFileSyntax($file);
            }
        }
        
        // Check modern files
        $modernDir = "src/languages/$language";
        if (is_dir($modernDir)) {
            $modernFiles = glob("$modernDir/*.php");
            foreach ($modernFiles as $file) {
                $results['modern'][basename($file)] = $this->checkFileSyntax($file);
            }
        }
        
        // Summary
        $allValid = true;
        $totalFiles = 0;
        $validFiles = 0;
        
        foreach (['legacy', 'modern'] as $type) {
            if (isset($results[$type])) {
                foreach ($results[$type] as $file => $valid) {
                    $totalFiles++;
                    if ($valid) {
                        $validFiles++;
                    } else {
                        $allValid = false;
                    }
                }
            }
        }
        
        $this->output("$language syntax: $validFiles/$totalFiles files valid");
        
        return $results;
    }
    
    /**
     * Check syntax of a single file
     */
    private function checkFileSyntax($file) {
        $output = [];
        $returnCode = 0;
        
        exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
        
        $valid = $returnCode === 0;
        if (!$valid) {
            $this->output("  ✗ Syntax error in " . basename($file) . ": " . implode(' ', $output));
        }
        
        return $valid;
    }
    
    /**
     * Generate comprehensive report
     */
    private function generateReport($results) {
        $this->output("=== VALIDATION REPORT ===\n");
        
        // Overall status
        $this->output("SYSTEM STATUS:");
        $this->output("- Mode: " . ($results['system_status']['system_mode'] ?? 'unknown'));
        $this->output("- Helper: " . ($results['system_status']['helper_available'] ? 'Available' : 'Missing'));
        $this->output("- Legacy: " . ($results['system_status']['legacy_available'] ? 'Available' : 'Missing'));
        $this->output("- Modern: " . (count($results['system_status']['modern_available'] ?? []) > 0 ? 'Available' : 'Missing'));
        
        // Key consistency summary
        if (!empty($results['key_consistency'])) {
            $this->output("\nKEY CONSISTENCY:");
            foreach ($results['key_consistency'] as $lang => $data) {
                $this->output("- $lang: " . round($data['consistency_rate'], 1) . "% consistent");
            }
        }
        
        // Performance summary
        if (!empty($results['performance'])) {
            $this->output("\nPERFORMANCE:");
            foreach ($results['performance'] as $lang => $data) {
                if (isset($data['improvement'])) {
                    $this->output("- $lang: " . round($data['improvement'], 1) . "% memory improvement");
                }
            }
        }
        
        $this->output("\nValidation complete!");
    }
    
    /**
     * Load all controllers from filesystem
     */
    private function loadControllers() {
        $controllerFiles = glob('src/controller/*.php');
        $this->controllers = array_map(function($file) {
            return basename($file, '.php');
        }, $controllerFiles);
    }
    
    /**
     * Parse command line options
     */
    private function parseOptions($options) {
        if (isset($options['verbose'])) {
            $this->verbose = $options['verbose'];
        }
        if (isset($options['languages'])) {
            $this->languages = $options['languages'];
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
    $options = getopt('l:hvq', ['languages:', 'help', 'verbose', 'quiet']);
    
    if (isset($options['h']) || isset($options['help'])) {
        echo "TeamCal Neo Language Validation Tool\n\n";
        echo "Usage: php language_validation_tool.php [options]\n\n";
        echo "Options:\n";
        echo "  -l, --languages LANGS  Languages to validate (comma-separated, default: english,deutsch)\n";
        echo "  -v, --verbose          Enable verbose output (default: enabled)\n";
        echo "  -q, --quiet            Disable verbose output\n";
        echo "  -h, --help             Show this help message\n\n";
        echo "Examples:\n";
        echo "  php language_validation_tool.php\n";
        echo "  php language_validation_tool.php -l english,deutsch,spanish\n";
        echo "  php language_validation_tool.php --quiet\n\n";
        exit(0);
    }
    
    $languages = isset($options['l']) || isset($options['languages']) ? 
        explode(',', $options['l'] ?? $options['languages']) : 
        ['english', 'deutsch'];
    
    $verbose = !isset($options['q']) && !isset($options['quiet']);
    
    $validator = new LanguageValidationTool([
        'verbose' => $verbose,
        'languages' => $languages
    ]);
    
    $results = $validator->validate();
    
    // Exit with error code if validation fails
    $hasErrors = false;
    foreach ($results as $section => $data) {
        if (isset($data['error']) || 
            (isset($data['consistency_rate']) && $data['consistency_rate'] < 95)) {
            $hasErrors = true;
            break;
        }
    }
    
    exit($hasErrors ? 1 : 0);
}