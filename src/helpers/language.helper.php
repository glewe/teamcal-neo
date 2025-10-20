<?php

/**
 * --------------------------------------------------------------------------
 * Language Loading Helper
 * --------------------------------------------------------------------------
 *
 * Provides intelligent language loading system that loads only the language
 * keys required for the current controller, significantly reducing memory
 * usage compared to loading all language files at once.
 *
 * Features:
 * - Controller-specific language file loading
 * - Automatic fallback to legacy language files
 * - Smart caching within session
 * - Bilingual support (English/German)
 * - Performance optimization through selective loading
 */

class LanguageLoader {
  private static $language = 'english';
  private static $loadedFiles = [];
  private static $languageKeys = [];
  private static $hasNewStructure = null;
  private static $loadingStats = [
    'filesLoaded' => 0,
    'keysLoaded' => 0,
    'memoryReduction' => 0
  ];

  /**
   * --------------------------------------------------------------------------
   * Initialize Language Loader
   * --------------------------------------------------------------------------
   *
   * Sets the current language and prepares the loader.
   *
   * @param string $language The language to load (e.g., 'english', 'deutsch')
   */
  public static function initialize($language = 'english') {
    self::$language = $language;
    self::$loadedFiles = [];
    self::$languageKeys = [];
    self::$hasNewStructure = null;
    self::$loadingStats = [
      'filesLoaded' => 0,
      'keysLoaded' => 0,
      'memoryReduction' => 0
    ];
  }

  /**
   * --------------------------------------------------------------------------
   * Calculate Performance Statistics
   * --------------------------------------------------------------------------
   *
   * Calculates memory usage improvements compared to legacy loading.
   */
  private static function calculatePerformanceStats() {
    // Estimate legacy system would load ~1,870 keys
    $legacyKeyCount = 1870;
    $currentKeyCount = self::$loadingStats['keysLoaded'];

    if ($legacyKeyCount > 0) {
      self::$loadingStats['memoryReduction'] =
        round((($legacyKeyCount - $currentKeyCount) / $legacyKeyCount) * 100, 1);
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Compare All Languages Against English
   * --------------------------------------------------------------------------
   *
   * Automatically detects all available languages and compares them against
   * English as the reference language. No need to specify languages manually.
   *
   * @param string $title Optional title for the report
   * @param string $subject Optional subject for the report
   *
   * @return array Formatted error data array ready for display
   */
  public static function compareAllLanguages($title = 'Language Comparison Report', $subject = '<h4>All Languages vs English Comparison</h4>') {
    $referenceLanguage = 'english';
    $availableLanguages = self::getAvailableLanguages();

    // Remove English from the list since it's the reference
    $languagesToCompare = array_filter($availableLanguages, function ($lang) use ($referenceLanguage) {
      return $lang !== $referenceLanguage;
    });

    if (empty($languagesToCompare)) {
      return [
        'title' => $title,
        'subject' => $subject,
        'text' => '<div class="panel panel-warning">
          <div class="panel-heading"><strong>No Languages to Compare</strong></div>
          <div class="panel-body">
            <p>Only English language files were found. No other languages available for comparison.</p>
            <p>Available languages: ' . implode(', ', $availableLanguages) . '</p>
          </div>
        </div>'
      ];
    }

    // Generate comprehensive comparison report
    $totalComparisons = count($languagesToCompare);
    $allResults = [];
    $referenceKeyCount = 0; // Track reference language key count
    $overallStats = [
      'total_languages' => $totalComparisons + 1, // +1 for English
      'total_issues' => 0,
      'languages_with_issues' => [],
      'perfect_languages' => []
    ];

    foreach ($languagesToCompare as $language) {
      $comparison = self::compareLanguages($referenceLanguage, $language);
      $allResults[$language] = $comparison;

      // Capture reference language key count from first comparison
      if ($referenceKeyCount === 0 && isset($comparison['lang1_total'])) {
        $referenceKeyCount = $comparison['lang1_total'];
      }

      $hasIssues = !empty($comparison['lang1_missing']) || !empty($comparison['lang2_missing']) || !empty($comparison['errors']);

      if ($hasIssues) {
        $overallStats['total_issues'] += count($comparison['lang1_missing']) + count($comparison['lang2_missing']);
        $overallStats['languages_with_issues'][] = $language;
      } else {
        $overallStats['perfect_languages'][] = $language;
      }
    }

    // Add reference key count to overall stats
    $overallStats['reference_key_count'] = $referenceKeyCount;

    return self::generateAllLanguagesReport($allResults, $overallStats, $referenceLanguage, $title, $subject);
  }

  /**
   * --------------------------------------------------------------------------
   * Compare and Display Languages
   * --------------------------------------------------------------------------
   *
   * Convenience method that combines comparison and report generation.
   * 
   * @param string $lang1 Reference language
   * @param string $lang2 Language to compare
   * @param string $title Optional title for the report
   * @param string $subject Optional subject for the report
   *
   * @return array Formatted error data array ready for display
   */
  public static function compareAndDisplay($lang1, $lang2, $title = 'Debug Info', $subject = '<h4>Language File Comparison</h4>') {
    $comparison = self::compareLanguages($lang1, $lang2);
    return self::generateComparisonReport($comparison, $title, $subject);
  }

  /**
   * --------------------------------------------------------------------------
   * Compare Languages
   * --------------------------------------------------------------------------
   *
   * Compares language files between two languages to identify missing keys.
   * Supports both legacy and split file structures based on USE_SPLIT_LANGUAGE_FILES.
   *
   * @param string $lang1 Reference language (e.g., 'english')
   * @param string $lang2 Language to compare against (e.g., 'deutsch')
   *
   * @return array Comparison results with statistics and missing keys
   */
  public static function compareLanguages($lang1, $lang2) {
    $result = [
      'lang1' => $lang1,
      'lang2' => $lang2,
      'lang1_total' => 0,
      'lang2_total' => 0,
      'lang1_missing' => [], // Keys missing in lang2
      'lang2_missing' => [], // Keys missing in lang1
      'errors' => [],
      'use_split_files' => defined('USE_SPLIT_LANGUAGE_FILES') && USE_SPLIT_LANGUAGE_FILES,
      'files_compared' => []
    ];

    try {
      if ($result['use_split_files']) {
        $result = self::compareSplitLanguages($lang1, $lang2, $result);
      } else {
        $result = self::compareLegacyLanguages($lang1, $lang2, $result);
      }
    } catch (Exception $e) {
      $result['errors'][] = "Comparison failed: " . $e->getMessage();
    }

    return $result;
  }

  /**
   * --------------------------------------------------------------------------
   * Compare Legacy Language Files
   * --------------------------------------------------------------------------
   *
   * Compares legacy 4-file language structure between two languages.
   *
   * @param string $lang1 Reference language
   * @param string $lang2 Language to compare
   * @param array $result Result array to populate
   *
   * @return array Updated result array
   */
  private static function compareLegacyLanguages($lang1, $lang2, $result) {
    $legacyFiles = [
      '.php',      // Main framework file
      '.app.php',  // Application file
      '.gdpr.php', // GDPR file
      '.log.php'   // Log file
    ];

    $lang1AllKeys = [];
    $lang2AllKeys = [];

    foreach ($legacyFiles as $suffix) {
      $lang1File = WEBSITE_ROOT . "/languages/$lang1$suffix";
      $lang2File = WEBSITE_ROOT . "/languages/$lang2$suffix";

      $fileName = "$lang1$suffix";
      $result['files_compared'][] = $fileName;

      // Check if files exist
      if (!file_exists($lang1File)) {
        $result['errors'][] = "File '$lang1File' not found";
        continue;
      }
      if (!file_exists($lang2File)) {
        $result['errors'][] = "File '$lang2File' not found";
        continue;
      }

      // Load keys from each file
      $lang1Keys = self::loadKeysFromFile($lang1File);
      $lang2Keys = self::loadKeysFromFile($lang2File);

      // Merge into total key arrays
      $lang1AllKeys = array_merge($lang1AllKeys, $lang1Keys);
      $lang2AllKeys = array_merge($lang2AllKeys, $lang2Keys);

      // Check for missing keys in this specific file
      $missingInLang2File = array_diff($lang1Keys, $lang2Keys);
      $missingInLang1File = array_diff($lang2Keys, $lang1Keys);

      foreach ($missingInLang2File as $key) {
        $result['lang1_missing'][] = "$key (in $fileName)";
      }

      foreach ($missingInLang1File as $key) {
        $result['lang2_missing'][] = "$key (in $fileName)";
      }
    }

    // Remove duplicates from merged arrays before counting
    $lang1AllKeys = array_unique($lang1AllKeys);
    $lang2AllKeys = array_unique($lang2AllKeys);

    $result['lang1_total'] = count($lang1AllKeys);
    $result['lang2_total'] = count($lang2AllKeys);

    return $result;
  }

  /**
   * --------------------------------------------------------------------------
   * Compare Split Language Files
   * --------------------------------------------------------------------------
   *
   * Compares split language file structure between two languages.
   *
   * @param string $lang1 Reference language
   * @param string $lang2 Language to compare
   * @param array $result Result array to populate
   *
   * @return array Updated result array
   */
  private static function compareSplitLanguages($lang1, $lang2, $result) {
    $lang1Dir = WEBSITE_ROOT . "/languages/$lang1";
    $lang2Dir = WEBSITE_ROOT . "/languages/$lang2";

    // Check if directories exist
    if (!is_dir($lang1Dir)) {
      $result['errors'][] = "Language directory '$lang1Dir' not found";
      return $result;
    }
    if (!is_dir($lang2Dir)) {
      $result['errors'][] = "Language directory '$lang2Dir' not found";
      return $result;
    }

    // Get all language files from both directories
    $lang1Files = glob("$lang1Dir/*.php");
    $lang2Files = glob("$lang2Dir/*.php");

    $lang1FileNames = array_map('basename', $lang1Files);
    $lang2FileNames = array_map('basename', $lang2Files);

    // Check for missing files
    $missingInLang2 = array_diff($lang1FileNames, $lang2FileNames);
    $missingInLang1 = array_diff($lang2FileNames, $lang1FileNames);

    if (!empty($missingInLang2)) {
      foreach ($missingInLang2 as $file) {
        $result['errors'][] = "File '$file' exists in $lang1 but missing in $lang2";
      }
    }

    if (!empty($missingInLang1)) {
      foreach ($missingInLang1 as $file) {
        $result['errors'][] = "File '$file' exists in $lang2 but missing in $lang1";
      }
    }

    // Compare common files
    $commonFiles = array_intersect($lang1FileNames, $lang2FileNames);
    $lang1AllKeys = [];
    $lang2AllKeys = [];

    foreach ($commonFiles as $fileName) {
      $lang1File = "$lang1Dir/$fileName";
      $lang2File = "$lang2Dir/$fileName";

      $result['files_compared'][] = $fileName;

      // Load keys from each file
      $lang1Keys = self::loadKeysFromFile($lang1File);
      $lang2Keys = self::loadKeysFromFile($lang2File);

      // Merge into total key arrays
      $lang1AllKeys = array_merge($lang1AllKeys, $lang1Keys);
      $lang2AllKeys = array_merge($lang2AllKeys, $lang2Keys);

      // Check for missing keys in this specific file
      $missingInLang2File = array_diff($lang1Keys, $lang2Keys);
      $missingInLang1File = array_diff($lang2Keys, $lang1Keys);

      foreach ($missingInLang2File as $key) {
        $result['lang1_missing'][] = "$key (in $fileName)";
      }

      foreach ($missingInLang1File as $key) {
        $result['lang2_missing'][] = "$key (in $fileName)";
      }
    }

    $result['lang1_total'] = count($lang1AllKeys);
    $result['lang2_total'] = count($lang2AllKeys);

    return $result;
  }

  /**
   * --------------------------------------------------------------------------
   * Force Reload
   * --------------------------------------------------------------------------
   *
   * Clears the loaded files cache and forces a reload on next request.
   */
  public static function forceReload() {
    self::$loadedFiles = [];
    self::$languageKeys = [];
    self::$hasNewStructure = null;
  }

  /**
   * --------------------------------------------------------------------------
   * Generate All Languages Report
   * --------------------------------------------------------------------------
   *
   * Generates a comprehensive HTML report for all language comparisons.
   *
   * @param array $allResults All comparison results
   * @param array $overallStats Overall statistics
   * @param string $referenceLanguage Reference language name
   * @param string $title Report title
   * @param string $subject Report subject
   *
   * @return array Formatted error data array
   */
  private static function generateAllLanguagesReport($allResults, $overallStats, $referenceLanguage, $title, $subject) {
    $errorData = [
      'title' => $title,
      'subject' => $subject,
      'text' => ''
    ];

    // Overall statistics panel
    $fileType = (defined('USE_SPLIT_LANGUAGE_FILES') && USE_SPLIT_LANGUAGE_FILES) ? 'Split Files' : 'Legacy Files';
    $referenceKeyInfo = isset($overallStats['reference_key_count']) && $overallStats['reference_key_count'] > 0
      ? ' (' . $overallStats['reference_key_count'] . ' keys)'
      : '';

    $errorData['text'] = '<div class="panel panel-info">
      <div class="panel-heading"><strong>Overall Statistics</strong></div>
      <div class="panel-body">
        <p><strong>File Structure:</strong> ' . $fileType . '</p>
        <p><strong>Reference Language:</strong> ' . $referenceLanguage . $referenceKeyInfo . '</p>
        <p><strong>Total Languages:</strong> ' . $overallStats['total_languages'] . '</p>
        <p><strong>Languages Compared:</strong> ' . (count($allResults)) . '</p>
        <p><strong>Perfect Matches:</strong> ' . count($overallStats['perfect_languages']) . '</p>
        <p><strong>Languages with Issues:</strong> ' . count($overallStats['languages_with_issues']) . '</p>
        <p><strong>Total Missing Keys:</strong> ' . $overallStats['total_issues'] . '</p>
      </div>
    </div>';

    // Perfect languages panel
    if (!empty($overallStats['perfect_languages'])) {
      $errorData['text'] .= '<div class="panel panel-success">
        <div class="panel-heading"><strong>Perfect Language Matches</strong></div>
        <div class="panel-body">
          <p>These languages have perfect parity with English:</p>
          <ul>';
      foreach ($overallStats['perfect_languages'] as $language) {
        $keyCount = $allResults[$language]['lang2_total'];
        $errorData['text'] .= '<li><strong>' . $language . '</strong> (' . $keyCount . ' keys)</li>';
      }
      $errorData['text'] .= '</ul>
        </div>
      </div>';
    }

    // Languages with issues
    if (!empty($overallStats['languages_with_issues'])) {
      $errorData['text'] .= '<div class="panel panel-warning">
        <div class="panel-heading"><strong>Languages with Missing Keys</strong></div>
        <div class="panel-body">';

      foreach ($overallStats['languages_with_issues'] as $language) {
        $comparison = $allResults[$language];
        $missingInLang = count($comparison['lang1_missing']);
        $extraInLang = count($comparison['lang2_missing']);

        $errorData['text'] .= '<div class="panel panel-default" style="margin-top: 10px;">
          <div class="panel-heading"><strong>' . $language . '</strong></div>
          <div class="panel-body">
            <p><strong>Total Keys:</strong> ' . $comparison['lang2_total'] . ' (English has ' . $comparison['lang1_total'] . ')</p>';

        if ($missingInLang > 0) {
          $errorData['text'] .= '<p><strong>Missing in ' . $language . ':</strong> ' . $missingInLang . ' keys</p>
            <div style="max-height: 150px; overflow-y: auto; background: #f5f5f5; padding: 5px; border: 1px solid #ddd;">';
          foreach (array_slice($comparison['lang1_missing'], 0, 20) as $key) {
            $errorData['text'] .= '<code style="display: block;">' . htmlspecialchars($key) . '</code>';
          }
          if (count($comparison['lang1_missing']) > 20) {
            $errorData['text'] .= '<small>... and ' . (count($comparison['lang1_missing']) - 20) . ' more</small>';
          }
          $errorData['text'] .= '</div>';
        }

        if ($extraInLang > 0) {
          $errorData['text'] .= '<p><strong>Extra in ' . $language . ':</strong> ' . $extraInLang . ' keys</p>
            <div style="max-height: 150px; overflow-y: auto; background: #f0f8ff; padding: 5px; border: 1px solid #ddd;">';
          foreach (array_slice($comparison['lang2_missing'], 0, 20) as $key) {
            $errorData['text'] .= '<code style="display: block;">' . htmlspecialchars($key) . '</code>';
          }
          if (count($comparison['lang2_missing']) > 20) {
            $errorData['text'] .= '<small>... and ' . (count($comparison['lang2_missing']) - 20) . ' more</small>';
          }
          $errorData['text'] .= '</div>';
        }

        if (!empty($comparison['errors'])) {
          $errorData['text'] .= '<p><strong>Errors:</strong></p><ul>';
          foreach ($comparison['errors'] as $error) {
            $errorData['text'] .= '<li class="text-danger">' . htmlspecialchars($error) . '</li>';
          }
          $errorData['text'] .= '</ul>';
        }

        $errorData['text'] .= '</div></div>';
      }

      $errorData['text'] .= '</div></div>';
    }

    // Summary and recommendations
    if (empty($overallStats['languages_with_issues'])) {
      $errorData['text'] .= '<div class="panel panel-success">
        <div class="panel-heading"><strong>Excellent!</strong></div>
        <div class="panel-body">
          <p>🎉 All languages have perfect parity with English. No missing translations found!</p>
        </div>
      </div>';
    } else {
      $errorData['text'] .= '<div class="panel panel-info">
        <div class="panel-heading"><strong>Recommendations</strong></div>
        <div class="panel-body">
          <ul>
            <li>Review missing keys in languages with issues</li>
            <li>Add missing translations to achieve perfect parity</li>
            <li>Consider removing extra keys that don\'t exist in English</li>
            <li>Run this comparison after language updates to verify completeness</li>
          </ul>
        </div>
      </div>';
    }

    return $errorData;
  }

  /**
   * --------------------------------------------------------------------------
   * Generate Comparison Report HTML
   * --------------------------------------------------------------------------
   *
   * Generates a formatted HTML report from language comparison results.
   *
   * @param array $comparison Comparison result from compareLanguages()
   * @param string $title Optional title for the report
   * @param string $subject Optional subject for the report
   *
   * @return array Formatted error data array for display
   */
  public static function generateComparisonReport($comparison, $title = 'Debug Info', $subject = '<h4>Language File Comparison</h4>') {
    $errorData = [
      'title' => $title,
      'subject' => $subject,
      'text' => ''
    ];

    // Configuration info
    $fileType = $comparison['use_split_files'] ? 'Split Files' : 'Legacy Files';
    $errorData['text'] = '<div class="panel panel-info">
      <div class="panel-heading"><strong>Configuration</strong></div>
      <div class="panel-body">
        <p><strong>File Structure:</strong> ' . $fileType . '</p>
        <p><strong>Files Compared:</strong> ' . implode(', ', $comparison['files_compared']) . '</p>
      </div>
    </div>';

    // Statistics
    $errorData['text'] .= '<div class="panel panel-info">
      <div class="panel-heading"><strong>Statistics</strong></div>
      <div class="panel-body">
        <p><strong>' . $comparison['lang1'] . ':</strong> ' . $comparison['lang1_total'] . ' keys</p>
        <p><strong>' . $comparison['lang2'] . ':</strong> ' . $comparison['lang2_total'] . ' keys</p>
        <p><strong>Missing in ' . $comparison['lang2'] . ':</strong> ' . count($comparison['lang1_missing']) . ' keys</p>
        <p><strong>Missing in ' . $comparison['lang1'] . ':</strong> ' . count($comparison['lang2_missing']) . ' keys</p>
      </div>
    </div>';

    // Errors
    if (!empty($comparison['errors'])) {
      $errorData['text'] .= '<div class="panel panel-danger">
        <div class="panel-heading"><strong>Errors</strong></div>
        <div class="panel-body">';
      foreach ($comparison['errors'] as $error) {
        $errorData['text'] .= '<p class="text-danger">' . htmlspecialchars($error) . '</p>';
      }
      $errorData['text'] .= '</div></div>';
    }

    // Missing in lang2
    if (!empty($comparison['lang1_missing'])) {
      $errorData['text'] .= '<div class="panel panel-warning">
        <div class="panel-heading"><strong>Keys missing in "' . $comparison['lang2'] . '"</strong></div>
        <div class="panel-body">
          <div style="max-height: 300px; overflow-y: auto;">';
      foreach ($comparison['lang1_missing'] as $key) {
        $errorData['text'] .= '<code>' . htmlspecialchars($key) . '</code><br>';
      }
      $errorData['text'] .= '</div></div></div>';
    }

    // Missing in lang1
    if (!empty($comparison['lang2_missing'])) {
      $errorData['text'] .= '<div class="panel panel-warning">
        <div class="panel-heading"><strong>Keys missing in "' . $comparison['lang1'] . '"</strong></div>
        <div class="panel-body">
          <div style="max-height: 300px; overflow-y: auto;">';
      foreach ($comparison['lang2_missing'] as $key) {
        $errorData['text'] .= '<code>' . htmlspecialchars($key) . '</code><br>';
      }
      $errorData['text'] .= '</div></div></div>';
    }

    // Success message if no issues
    if (empty($comparison['lang1_missing']) && empty($comparison['lang2_missing']) && empty($comparison['errors'])) {
      $errorData['text'] .= '<div class="panel panel-success">
        <div class="panel-heading"><strong>Perfect Match!</strong></div>
        <div class="panel-body">
          <p>Both language files have exactly the same keys. No missing translations found.</p>
        </div>
      </div>';
    }

    return $errorData;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Available Languages
   * --------------------------------------------------------------------------
   *
   * Discovers all available languages in the system based on file structure.
   * For split files: checks for directories with core.php
   * For legacy files: checks for .php files with complete 4-file set
   *
   * @return array Array of available language codes
   */
  public static function getAvailableLanguages() {
    $languages = [];

    if (defined('USE_SPLIT_LANGUAGE_FILES') && USE_SPLIT_LANGUAGE_FILES) {
      // Split file mode: scan for directories with core.php
      $languageDir = WEBSITE_ROOT . '/languages';
      if (is_dir($languageDir)) {
        $dirs = glob("$languageDir/*", GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
          $langCode = basename($dir);
          // Verify it's a valid language directory with core.php
          if (file_exists("$dir/core.php")) {
            $languages[] = $langCode;
          }
        }
      }
    } else {
      // Legacy mode: scan for complete 4-file sets
      $languageDir = WEBSITE_ROOT . '/languages';
      if (is_dir($languageDir)) {
        $mainFiles = glob("$languageDir/*.php");

        foreach ($mainFiles as $file) {
          $fileName = basename($file, '.php');

          // Skip files with dots (like .app.php, .gdpr.php, .log.php)
          if (strpos($fileName, '.') !== false) {
            continue;
          }

          // Check if this language has all 4 required files
          $requiredFiles = [
            "$languageDir/$fileName.php",       // Main file
            "$languageDir/$fileName.app.php",   // Application file
            "$languageDir/$fileName.gdpr.php",  // GDPR file
            "$languageDir/$fileName.log.php"    // Log file
          ];

          $allFilesExist = true;
          foreach ($requiredFiles as $requiredFile) {
            if (!file_exists($requiredFile)) {
              $allFilesExist = false;
              break;
            }
          }

          if ($allFilesExist) {
            $languages[] = $fileName;
          }
        }
      }
    }

    return array_unique($languages);
  }

  /**
   * --------------------------------------------------------------------------
   * Get Current Language
   * --------------------------------------------------------------------------
   *
   * Returns the currently configured language.
   *
   * @return string Current language code
   */
  public static function getCurrentLanguage() {
    return self::$language;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Loaded Files
   * --------------------------------------------------------------------------
   *
   * Returns list of language files that have been loaded.
   *
   * @return array Array of loaded file names
   */
  public static function getLoadedFiles() {
    return self::$loadedFiles;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Required Files for Controller
   * --------------------------------------------------------------------------
   *
   * Determines which language files are needed for a specific controller.
   *
   * @param string $controller The controller name
   *
   * @return array Array of required file names (without .php extension)
   */
  private static function getRequiredFiles($controller) {
    // Special case: core_only for initial loading
    if ($controller === 'core_only') {
      return []; // Core, alert, and upload are already loaded in loadModernStructure
    }

    // Controller-specific file mapping
    $controllerFileMap = [
      'home' => [],
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
      'gdpr' => ['gdpr'],
      'groupcalendaredit' => ['calendar'],
      'groupedit' => ['group'],
      'groups' => ['group'],
      'holidayedit' => ['holiday'],
      'holidays' => ['holiday'],
      'imprint' => ['imprint'],
      'import' => ['import'],
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

    // Get files for this controller, or default to empty array
    $files = isset($controllerFileMap[$controller])
      ? $controllerFileMap[$controller]
      : [];

    return $files;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Loading Statistics
   * --------------------------------------------------------------------------
   *
   * Returns performance statistics for the current loading session.
   *
   * @return array Loading statistics
   */
  public static function getStats() {
    return self::$loadingStats;
  }

  /**
   * --------------------------------------------------------------------------
   * Check for New Structure
   * --------------------------------------------------------------------------
   *
   * Determines if the new split file structure is available.
   *
   * @return bool True if new structure exists, false otherwise
   */
  private static function hasNewStructure() {
    if (self::$hasNewStructure === null) {
      $coreFile = WEBSITE_ROOT . '/languages/' . self::$language . '/core.php';
      self::$hasNewStructure = file_exists($coreFile);
    }

    return self::$hasNewStructure;
  }

  /**
   * --------------------------------------------------------------------------
   * Load Language for Controller
   * --------------------------------------------------------------------------
   *
   * Loads the required language files for a specific controller.
   * Always loads core.php plus controller-specific files as needed.
   *
   * @param string $controller The controller name (e.g., 'home', 'users', 'calendar')
   *
   * @return array Performance statistics
   */
  public static function loadForController($controller) {
    global $LANG;

    if (!isset($LANG)) {
      $LANG = [];
    }

    // Check if we have the new split file structure
    if (self::hasNewStructure()) {
      self::loadModernStructure($controller);
    } else {
      self::loadLegacyStructure();
    }

    return self::$loadingStats;
  }

  /**
   * --------------------------------------------------------------------------
   * Load Keys from File
   * --------------------------------------------------------------------------
   *
   * Extracts language keys from a PHP language file.
   *
   * @param string $filePath Path to the language file
   *
   * @return array Array of language keys found in the file
   */
  private static function loadKeysFromFile($filePath) {
    if (!file_exists($filePath)) {
      return [];
    }

    // Load file content safely
    $LANG = [];
    include $filePath;

    return array_keys($LANG);
  }

  /**
   * --------------------------------------------------------------------------
   * Load Language File
   * --------------------------------------------------------------------------
   *
   * Loads a specific language file if it exists and hasn't been loaded yet.
   *
   * @param string $file The file name without extension
   */
  private static function loadLanguageFile($file) {
    global $LANG;

    // Avoid loading the same file twice
    if (in_array($file, self::$loadedFiles)) {
      return;
    }

    $filePath = WEBSITE_ROOT . '/languages/' . self::$language . '/' . $file . '.php';

    if (file_exists($filePath)) {
      $keysBeforeLoad = count($LANG);
      require $filePath;

      self::$loadedFiles[] = $file;
      self::$loadingStats['filesLoaded']++;
      self::$loadingStats['keysLoaded'] += (count($LANG) - $keysBeforeLoad);
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Load Legacy File Structure
   * --------------------------------------------------------------------------
   *
   * Fallback to the original 4-file language loading system.
   */
  private static function loadLegacyStructure() {
    global $LANG;

    $legacyFiles = [
      WEBSITE_ROOT . '/languages/' . self::$language . '.php',
      WEBSITE_ROOT . '/languages/' . self::$language . '.app.php',
      WEBSITE_ROOT . '/languages/' . self::$language . '.gdpr.php',
      WEBSITE_ROOT . '/languages/' . self::$language . '.log.php'
    ];

    $keysBeforeLoad = count($LANG);

    foreach ($legacyFiles as $file) {
      if (file_exists($file)) {
        require_once $file;
        self::$loadingStats['filesLoaded']++;
      }
    }

    self::$loadingStats['keysLoaded'] = count($LANG) - $keysBeforeLoad;
  }

  /**
   * --------------------------------------------------------------------------
   * Load Modern Split File Structure
   * --------------------------------------------------------------------------
   *
   * Loads core.php plus controller-specific language files.
   *
   * @param string $controller The controller name
   */
  private static function loadModernStructure($controller) {
    global $LANG;

    // Always load core files used throughout the application
    self::loadLanguageFile('core');
    self::loadLanguageFile('alert');
    self::loadLanguageFile('upload'); // Avatar class needs upload error messages

    // Map controller to required files
    $requiredFiles = self::getRequiredFiles($controller);

    foreach ($requiredFiles as $file) {
      self::loadLanguageFile($file);
    }

    // Calculate performance benefits
    self::calculatePerformanceStats();
  }
}
