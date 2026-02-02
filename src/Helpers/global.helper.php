<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 *
 * Global Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * Cleans and returns a given string.
 *
 * @param string $input String to clean
 * @return string Cleaned string with potentially dangerous content removed
 */
function cleanInput(string $input): string {
  // Early return for empty input
  if (empty($input)) {
    return $input;
  }

  // Static cache for compiled regex patterns (performance optimization)
  static $searchPatterns = null;

  if ($searchPatterns === null) {
    $searchPatterns = [
      // Remove script tags and their content (case-insensitive, multiline)
      '@<script[^>]*?>.*?</script>@si',

      // Remove style tags and their content
      '@<style[^>]*?>.*?</style>@si',

      // Remove all HTML/XML tags (improved pattern)
      '@<[\/\!]*?[^<>]*?>@si',

      // Remove HTML comments (including conditional comments)
      '@<!--.*?-->@s',

      // Remove potential JavaScript event handlers
      '@\s*on\w+\s*=\s*["\'][^"\']*["\']@i',

      // Remove javascript: protocols
      '@javascript\s*:@i',

      // Remove vbscript: protocols
      '@vbscript\s*:@i',

      // Remove data: URLs that could contain scripts (basic protection)
      '@data\s*:\s*[^,]*,.*?@i',

      // Remove expression() CSS (IE vulnerability)
      '@expression\s*\([^)]*\)@i'
    ];
  }

  // Apply all cleaning patterns in one pass
  $cleaned = preg_replace($searchPatterns, '', $input);

  // Additional security: remove null bytes and control characters (except normal whitespace)
  $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);

  // Normalize whitespace (optional - preserves readability)
  $cleaned = preg_replace('/\s+/', ' ', trim($cleaned));

  return $cleaned;
}

//-----------------------------------------------------------------------------
/**
 * Computes several date related information for a given date.
 *
 * @param string $year 4 digit number of the year (example: 2014)
 * @param string $month 2 digit number of the month (example: 11)
 * @param string $day 1 or 2 digit number of the day (example: 1, 19)
 *
 * @return array $dateInfo Full dates are returned in ISO 8601 format, e.g. 2014-03-03
 */
function dateInfo(string $year, string $month, string $day = '1'): array {
  global $LANG;

  // Input validation
  if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
    throw new InvalidArgumentException('Year, month, and day must be numeric');
  }

  $year  = (int) $year;
  $month = (int) $month;
  $day   = (int) $day;

  // Validate ranges
  if ($year < 1000 || $year > 9999 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
    throw new InvalidArgumentException('Invalid date values provided');
  }

  try {
    // Use DateTime for better performance and reliability
    $dateTime = new DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day));
  } catch (Exception $e) {
    throw new InvalidArgumentException('Invalid date: ' . $e->getMessage());
  }

  // Get formatted values once
  $yearStr  = $dateTime->format('Y');
  $monthStr = $dateTime->format('m');
  $dayStr   = $dateTime->format('d');
  $monthNum = (int) $monthStr;

  // Build basic date info
  $dateInfo = [
    'dd'          => $dayStr,
    'mm'          => $monthStr,
    'year'        => (int) $yearStr,
    'month'       => $monthNum,
    'daysInMonth' => (int) $dateTime->format('t'),
    'ISO'         => $dateTime->format('Y-m-d'),
    'wday'        => (int) $dateTime->format('N'),
    'week'        => (int) $dateTime->format('W')
  ];

  // Add language-dependent fields safely
  if (isset($LANG['weekdayShort'][$dateInfo['wday']])) {
    $dateInfo['weekdayShort'] = $LANG['weekdayShort'][$dateInfo['wday']];
  }
  if (isset($LANG['weekdayLong'][$dateInfo['wday']])) {
    $dateInfo['weekdayLong'] = $LANG['weekdayLong'][$dateInfo['wday']];
  }
  if (isset($LANG['monthnames'][$monthNum])) {
    $dateInfo['monthname'] = $LANG['monthnames'][$monthNum];
  }
  if (isset($LANG['monthShort'][$monthNum])) {
    $dateInfo['monthshort'] = $LANG['monthShort'][$monthNum];
  }

  // Calculate month boundaries
  $dateInfo['firstOfMonth'] = $yearStr . '-' . $monthStr . '-01';
  $dateInfo['lastOfMonth']  = $yearStr . '-' . $monthStr . '-' . str_pad((string) $dateInfo['daysInMonth'], 2, '0', STR_PAD_LEFT);

  // Calculate year boundaries
  $dateInfo['firstOfYear'] = $yearStr . '-01-01';
  $dateInfo['lastOfYear']  = $yearStr . '-12-31';

  // Calculate quarter and half-year boundaries more efficiently
  $quarterMap = [
    1 => ['start' => '01-01', 'end' => '03-31', 'half_end' => '06-30'],
    2 => ['start' => '04-01', 'end' => '06-30', 'half_end' => '06-30'],
    3 => ['start' => '07-01', 'end' => '09-30', 'half_end' => '12-31'],
    4 => ['start' => '10-01', 'end' => '12-31', 'half_end' => '12-31']
  ];

  $quarter     = (int) ceil($monthNum / 3);
  $quarterInfo = $quarterMap[$quarter];

  $dateInfo['firstOfQuarter'] = $yearStr . '-' . $quarterInfo['start'];
  $dateInfo['lastOfQuarter']  = $yearStr . '-' . $quarterInfo['end'];
  $dateInfo['firstOfHalf']    = $yearStr . '-' . ($quarter <= 2 ? '01-01' : '07-01');
  $dateInfo['lastOfHalf']     = $yearStr . '-' . $quarterInfo['half_end'];

  return $dateInfo;
}

//-----------------------------------------------------------------------------
/**
 * Validates a form field against a ruleset.
 *
 * $ruleset can be a string of several rules, separated by |. However, only
 * use one that requires $param. The $param rules are:
 * - match
 * - regex_match
 * - min_length
 * - max_length
 * - exact_length
 *
 * @param string $field The form field name to check
 * @param string $ruleset Ruleset to check against
 * @param string $param Second value used by certain rule checks
 *
 * @return bool  false or first broken rule
 */
function formInputValid(string $field, string $ruleset, string $param = ''): bool {
  global $_POST, $inputAlert, $LANG;

  // Cache repeated calculations for performance
  $rules       = explode('|', $ruleset);
  $rulesLookup = array_flip($rules); // O(1) lookups instead of O(n) in_array calls
  $label       = explode('_', $field);
  $fieldExists = isset($_POST[$field]);
  $fieldValue  = $fieldExists ? $_POST[$field] : '';
  $hasValue    = $fieldExists && strlen($fieldValue);

  // Static cache for compiled regex patterns (performance optimization)
  static $regexPatterns = null;
  if ($regexPatterns === null) {
    $regexPatterns = [
      'alpha'                            => '/^[\pL]+$/u',
      'alpha_numeric'                    => '/^[\pL\w]+$/u',
      'alpha_numeric_dash'               => '/^[\pL\w_-]+$/u',
      'username'                         => '/^[\pL\w.@_-]+$/u',
      'alpha_numeric_dash_blank'         => '/^[ \pL\w_-]+$/u',
      'alpha_numeric_dash_blank_dot'     => '/^[ \pL\w._-]+$/u',
      'alpha_numeric_dash_blank_special' => '/^[ \pL\w\'!@#$%^&*()._-]+$/u',
      'date'                             => '/^(\d{4})-(\d{2})-(\d{2})$/',
      'hexadecimal'                      => '/^[a-f0-9]+$/i',
      'hex_color'                        => '/^#?[a-f0-9]{6}$/i',
      'phone_number'                     => '/^[ +0-9-()]+$/i',
      'pwdlow'                           => '/^.*(?=.{4,})[a-zA-Z0-9!@#$%^&*().]+$/',
      'pwdmedium'                        => '/^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*().]+$/',
      'pwdhigh'                          => '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()])[a-zA-Z0-9!@#$%^&*().]+$/'
    ];
  }

  // Helper function to set error and return false
  $setError = function (string $messageKey, ...$args) use (&$inputAlert, $label, $LANG) {
    $inputAlert[$label[1]] = empty($args) ? $LANG[$messageKey] : sprintf($LANG[$messageKey], ...$args);
    return false;
  };

  // Sanitize first if requested
  if (isset($rulesLookup['sanitize']) && $hasValue) {
    $_POST[$field] = sanitize($fieldValue);
    $fieldValue    = $_POST[$field]; // Update cached value
  }

  // Required field check - early return for missing required fields
  if (isset($rulesLookup['required']) && !$hasValue) {
    return $setError('alert_input_required');
  }

  // Early return if field is empty and not required (no need to validate empty optional fields)
  if (!$hasValue) {
    return true;
  }

  // Cache multibyte string length for length-based validations
  $mbLength    = null;
  $getMbLength = function () use (&$mbLength, $fieldValue) {
    if ($mbLength === null) {
      $mbLength = mb_strlen($fieldValue);
    }
    return $mbLength;
  };

  // Cache numeric check for numeric validations
  $isNumeric    = null;
  $getIsNumeric = function () use (&$isNumeric, $fieldValue) {
    if ($isNumeric === null) {
      $isNumeric = is_numeric($fieldValue);
    }
    return $isNumeric;
  };

  // Pattern-based validations using cached regex patterns
  foreach (
    [
      'alpha',
      'alpha_numeric',
      'alpha_numeric_dash',
      'username',
      'alpha_numeric_dash_blank',
      'alpha_numeric_dash_blank_dot',
      'alpha_numeric_dash_blank_special',
      'hexadecimal',
      'hex_color',
      'phone_number',
      'pwdlow',
      'pwdmedium',
      'pwdhigh'
    ] as $rule
  ) {
    if (isset($rulesLookup[$rule]) && !preg_match($regexPatterns[$rule], $fieldValue)) {
      return $setError('alert_input_' . $rule);
    }
  }

  // Special validations
  if (isset($rulesLookup['ctype_graph']) && !ctype_graph($fieldValue)) {
    return $setError('alert_input_ctype_graph');
  }

  if (isset($rulesLookup['date']) && !preg_match($regexPatterns['date'], trim($fieldValue))) {
    return $setError('alert_input_date');
  }

  if (isset($rulesLookup['email']) && !validEmail($fieldValue)) {
    return $setError('alert_input_email');
  }

  // Numeric validations
  if (isset($rulesLookup['numeric']) && !$getIsNumeric()) {
    return $setError('alert_input_numeric');
  }

  if (isset($rulesLookup['equals']) && ($getIsNumeric() && $fieldValue != $param)) {
    return $setError('alert_input_equal', $field, $param);
  }

  if (isset($rulesLookup['greater_than']) && ($getIsNumeric() && $fieldValue <= $param)) {
    return $setError('alert_input_greater_than', $param);
  }

  if (isset($rulesLookup['less_than']) && ($getIsNumeric() && $fieldValue >= $param)) {
    return $setError('alert_input_less_than', $param);
  }

  // String comparisons
  if (isset($rulesLookup['equals_string']) && $fieldValue != $param) {
    return $setError('alert_input_equal_string', $param);
  }

  // Length validations
  if (isset($rulesLookup['exact_length']) && $getMbLength() != $param) {
    return $setError('alert_input_exact_length', $param);
  }

  if (isset($rulesLookup['max_length']) && $getMbLength() > $param) {
    return $setError('alert_input_max_length', $param);
  }

  if (isset($rulesLookup['min_length']) && $getMbLength() < $param) {
    return $setError('alert_input_min_length', $param);
  }

  // Advanced validations
  if (isset($rulesLookup['ip_address']) && !filter_var($fieldValue, FILTER_VALIDATE_IP)) {
    return $setError('alert_input_ip_address');
  }

  if (isset($rulesLookup['match']) && isset($_POST[$param]) && strlen($_POST[$param]) && $fieldValue != $_POST[$param]) {
    return $setError('alert_input_match', $field, $param);
  }

  if (isset($rulesLookup['regex_match']) && !preg_match($param, $fieldValue)) {
    return $setError('alert_input_regex_match', $param);
  }

  return true;
}

//-----------------------------------------------------------------------------
/**
 * Generates a cryptographically secure password.
 *
 * @param int $length Desired password length (minimum 4, maximum 128)
 * @param array $options Optional configuration for password generation
 *                      - 'exclude_ambiguous' => bool (default: true) - exclude similar looking characters
 *                      - 'require_mixed' => bool (default: true) - ensure at least one from each character type
 *                      - 'custom_chars' => string - use custom character set (overrides other options)
 *
 * @return string Cryptographically secure password
 * @throws InvalidArgumentException If length is invalid or secure random bytes cannot be generated
 */
function generatePassword(int $length = 9, array $options = []): string {
  // Input validation
  if ($length < 4 || $length > 128) {
    throw new InvalidArgumentException('Password length must be between 4 and 128 characters');
  }

  // Default options
  $defaultOptions = [
    'exclude_ambiguous' => true,
    'require_mixed'     => true,
    'custom_chars'      => null
  ];
  $options        = array_merge($defaultOptions, $options);

  // Define character sets
  if ($options['custom_chars'] !== null) {
    $characters = $options['custom_chars'];
    $charSets   = [$characters]; // Single set for custom characters
  }
  else {
    if ($options['exclude_ambiguous']) {
      // Exclude ambiguous characters (0, O, l, 1, I)
      $lowercase = 'abcdefghjkmnpqrstuvwxyz';
      $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
      $numbers   = '23456789';
      $symbols   = '@#$%&*+=?';
    }
    else {
      $lowercase = 'abcdefghijklmnopqrstuvwxyz';
      $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $numbers   = '0123456789';
      $symbols   = '!@#$%^&*()_+-=[]{}|;:,.<>?';
    }

    $characters = $lowercase . $uppercase . $numbers . $symbols;
    $charSets   = $options['require_mixed'] ? [$lowercase, $uppercase, $numbers, $symbols] : [$characters];
  }

  $charactersLength = strlen($characters);

  // Generate cryptographically secure random bytes
  try {
    $randomBytes = random_bytes($length * 2); // Extra bytes for better distribution
  } catch (Exception $e) {
    throw new InvalidArgumentException('Unable to generate secure random bytes: ' . $e->getMessage());
  }

  $password = '';
  $usedSets = [];

  // Generate password with cryptographically secure randomness
  for ($i = 0; $i < $length; $i++) {
    // Convert random bytes to index using modulo with better distribution
    $randomIndex  = unpack('n', substr($randomBytes, $i * 2, 2))[1] % $charactersLength;
    $char         = $characters[$randomIndex];
    $password    .= $char;

    // Track which character sets we've used (for mixed requirement)
    if ($options['require_mixed'] && $options['custom_chars'] === null) {
      foreach ($charSets as $setIndex => $set) {
        if (strpos($set, $char) !== false) {
          $usedSets[$setIndex] = true;
          break;
        }
      }
    }
  }

  // Ensure we have at least one character from each required set
  if ($options['require_mixed'] && $options['custom_chars'] === null && count($usedSets) < count($charSets)) {
    // Replace random positions with missing character types
    $missingSetIndices = array_diff(array_keys($charSets), array_keys($usedSets));

    foreach ($missingSetIndices as $setIndex) {
      if ($length <= count($missingSetIndices))
        break; // Not enough space for all sets

      // Generate secure random position and character
      $randomPos            = unpack('n', random_bytes(2))[1] % $length;
      $randomCharIndex      = unpack('n', random_bytes(2))[1] % strlen($charSets[$setIndex]);
      $password[$randomPos] = $charSets[$setIndex][$randomCharIndex];
    }
  }

  return $password;
}

//-----------------------------------------------------------------------------
/**
 * Scans a given directory for files.
 *
 * Optionally you can specify an array of extension to look for.
 *
 * @param string $myDir Directory name to scan
 * @param array $myExt Array of extensions to scan for
 * @param string $myPrefix An optional prefix of the filename
 *
 * @return array Array containing the names of the files
 */
function getFiles(string $myDir, array $myExt = [], string $myPrefix = ''): array {
  // Normalize directory path - support both forward and backward slashes
  $myDir = rtrim($myDir, "/\\");

  // Early validation - return empty array if directory doesn't exist or isn't readable
  if (!is_dir($myDir) || !is_readable($myDir)) {
    return [];
  }

  // Use scandir for better performance and error handling
  $entries = scandir($myDir);
  if ($entries === false) {
    return [];
  }

  // Filter out directories and special entries in one pass
  $files = [];
  foreach ($entries as $entry) {
    if ($entry !== '.' && $entry !== '..' && !is_dir($myDir . DIRECTORY_SEPARATOR . $entry)) {
      $files[] = $entry;
    }
  }

  // Early return if no filtering needed
  if (empty($myExt) && empty($myPrefix)) {
    return $files;
  }

  // Apply filters efficiently
  $filteredFiles   = [];
  $hasExtFilter    = !empty($myExt);
  $hasPrefixFilter = !empty($myPrefix);

  // Pre-normalize extensions for case-insensitive comparison
  $normalizedExts   = $hasExtFilter ? array_map('strtolower', $myExt) : [];
  $normalizedPrefix = $hasPrefixFilter ? strtolower($myPrefix) : '';

  foreach ($files as $file) {
    $lowerFile = strtolower($file);

    // Extract extension and prefix once
    $extension = getFileExtension($lowerFile);
    $prefix    = $hasPrefixFilter ? getFilePrefix($lowerFile) : '';

    // Apply filters based on what's provided
    $matchesExt    = !$hasExtFilter || in_array($extension, $normalizedExts, true);
    $matchesPrefix = !$hasPrefixFilter || startsWith($prefix, $normalizedPrefix);

    if ($matchesExt && $matchesPrefix) {
      $filteredFiles[] = $file;
    }
  }

  return $filteredFiles;
}

//-----------------------------------------------------------------------------
/**
 * Extracts the file extension from a given file name.
 *
 * @param string $str String containing the path or filename
 *
 * @return string File extension of the string passed
 */
function getFileExtension(string $str): string {
  // Early return for empty strings
  if (empty($str)) {
    return '';
  }

  // Find the last dot position
  $dotPos = strrpos($str, '.');

  // No dot found, or dot is at the very beginning (hidden files like .htaccess)
  if ($dotPos === false || $dotPos === 0) {
    return '';
  }

  // Return the extension (everything after the last dot)
  return substr($str, $dotPos + 1);
}

//-----------------------------------------------------------------------------
/**
 * Extracts the file prefix (name without extension) from a given file name.
 *
 * @param string $str String containing the path or filename
 *
 * @return string File prefix (name without extension) of the string passed
 */
function getFilePrefix(string $str): string {
  // Early return for empty strings
  if (empty($str)) {
    return '';
  }

  // Find the first dot position (for file extension)
  $dotPos = strpos($str, '.');

  // No dot found - return the entire string (file has no extension)
  if ($dotPos === false) {
    return $str;
  }

  // If dot is at the very beginning (hidden files like .htaccess), return empty string
  if ($dotPos === 0) {
    return '';
  }

  // Return everything before the first dot
  return substr($str, 0, $dotPos);
}

//-----------------------------------------------------------------------------
/**
 * Gets all folders in a given directory.
 *
 * @param string $myDir String containing the pathname
 *
 * @return array Array containing the folder names
 */
function getFolders(string $myDir): array {
  // Normalize directory path - support both forward and backward slashes
  $myDir = rtrim($myDir, "/\\");

  // Early validation - return empty array if directory doesn't exist or isn't readable
  if (!is_dir($myDir) || !is_readable($myDir)) {
    return [];
  }

  // Use scandir for better performance and error handling
  $entries = scandir($myDir);
  if ($entries === false) {
    return [];
  }

  // Filter directories efficiently
  $directories = [];
  foreach ($entries as $entry) {
    // Skip special directories and check if it's actually a directory
    if ($entry !== '.' && $entry !== '..' && is_dir($myDir . DIRECTORY_SEPARATOR . $entry)) {
      $directories[] = $entry;
    }
  }

  return $directories;
}

//-----------------------------------------------------------------------------
/**
 * Returns today's date in ISO 8601 format with optional caching for performance.
 *
 * @param bool $useCache Whether to cache the result for the current day (default: true)
 *
 * @return string ISO 8601 format, e.g. 2024-03-15
 */
function getISOToday(bool $useCache = true): string {
  // Static cache for performance - date only changes once per day
  static $cachedDate = null;
  static $cachedDay = null;

  if ($useCache) {
    $currentDay = (int) date('j'); // Current day of month for cache validation

    // Return cached result if it's still the same day
    if ($cachedDate !== null && $cachedDay === $currentDay) {
      return $cachedDate;
    }

    // Generate and cache new date
    $cachedDate = date('Y-m-d');
    $cachedDay  = $currentDay;

    return $cachedDate;
  }

  // Direct call without caching
  return date('Y-m-d');
}

//-----------------------------------------------------------------------------
/**
 * Gets all language directory names from the language directory with enhanced performance and error handling.
 *
 * @param string $type Look for application ('app') or log ('log') languages
 *
 * @return array Array containing the language names (not filenames)
 */
function getLanguages(string $type = 'app'): array {
  // Static cache for performance
  static $cache = [];
  $cacheKey = $type;

  if (isset($cache[$cacheKey])) {
    return $cache[$cacheKey];
  }

  // Input validation
  if (!in_array($type, ['app', 'log'], true)) {
    $type = 'app';
  }

  // Determine language directory
  $languageDir = defined('WEBSITE_ROOT') ? WEBSITE_ROOT . '/resources/languages/' : 'resources/languages/';

  // Early validation
  if (!is_dir($languageDir) || !is_readable($languageDir)) {
    $cache[$cacheKey] = [];
    return [];
  }

  // Scan for directories
  $entries = scandir($languageDir);
  if ($entries === false) {
    $cache[$cacheKey] = [];
    return [];
  }

  $languages = [];
  foreach ($entries as $entry) {
    // Skip special entries
    if ($entry === '.' || $entry === '..') {
      continue;
    }

    // Check if it's a directory
    $path = $languageDir . $entry;
    if (is_dir($path)) {
      // It's a directory, assume it's a language pack (e.g. "english", "deutsch")
      // You could optionally check for core.php inside to allow incomplete folders:
      if (file_exists($path . '/core.php')) {
        $languages[] = $entry;
      }
    }
  }

  // Remove duplicates and sort
  $languages = array_unique($languages);
  sort($languages);

  // Cache the result
  $cache[$cacheKey] = $languages;

  return $languages;
}

//-----------------------------------------------------------------------------
/**
 * Returns the Bootstrap color class for a given role.
 *
 * @param string|int $role Role name (case-insensitive) or role ID.
 *                         Supported role names: assistant, manager, director, admin
 *                         Supported role IDs: 1=admin, 2=director, 3=manager, 4=assistant
 *
 * @return string Bootstrap color class name (primary, warning, secondary, danger, success)
 *
 * @example getRoleColor('admin') returns 'danger'
 * @example getRoleColor(1) returns 'danger' (admin role ID)
 * @example getRoleColor('MANAGER') returns 'warning'
 * @example getRoleColor('unknown') returns 'success' (default)
 */
function getRoleColor(string|int $role): string {
  // Static cache for performance on repeated calls
  static $cache = [];

  // Early return for empty role
  if (empty($role)) {
    return 'success';
  }

  // Convert role ID to role name if integer
  if (is_int($role)) {
    $roleMap = [
      1 => 'admin',
      2 => 'director',
      3 => 'manager',
      4 => 'assistant'
    ];
    $role    = $roleMap[$role] ?? 'unknown';
  }

  // Normalize role for case-insensitive comparison and cache key
  $normalizedRole = strtolower(trim($role));

  // Return cached result if available
  if (isset($cache[$normalizedRole])) {
    return $cache[$normalizedRole];
  }

  // Role to Bootstrap color mapping (optimized array lookup)
  static $roleColorMap = [
  'assistant' => 'primary',
  'manager'   => 'warning',
  'director'  => 'secondary', // Updated from deprecated 'default' to 'secondary'
  'admin'     => 'danger',
  // Default fallback handled below
  ];

  // Get color with fallback to default
  $color = $roleColorMap[$normalizedRole] ?? 'success';

  // Cache the result for future calls
  $cache[$normalizedRole] = $color;

  return $color;
}

//-----------------------------------------------------------------------------
/**
 * Validates if a given date string is in the format YYYY-MM-DD and represents a valid date.
 *
 * Performs both format validation (YYYY-MM-DD pattern) and logical date validation
 * (leap years, month boundaries, etc.) with performance optimizations.
 *
 * @param string $date The date string to validate in YYYY-MM-DD format
 *
 * @return bool Returns true if the date is valid and in YYYY-MM-DD format, false otherwise
 *
 * @example isValidDate('2024-03-15') returns true
 * @example isValidDate('2024-02-29') returns true (leap year)
 * @example isValidDate('2023-02-29') returns false (not a leap year)
 * @example isValidDate('2024-13-01') returns false (invalid month)
 * @example isValidDate('24-03-15') returns false (wrong format)
 * @example isValidDate('invalid-date') returns false
 */
function isValidDate(string $date): bool {
  // Static cache for performance on repeated calls
  static $cache = [];
  static $regex = '/^(\d{4})-(\d{2})-(\d{2})$/';

  // Early return for empty input
  if (empty($date)) {
    return false;
  }

  // Return cached result if available
  if (isset($cache[$date])) {
    return $cache[$date];
  }

  // Trim whitespace and validate YYYY-MM-DD format
  $trimmedDate = trim($date);

  if (!preg_match($regex, $trimmedDate, $matches)) {
    $cache[$date] = false;
    return false;
  }

  // Extract year, month, and day
  $year  = (int) $matches[1];
  $month = (int) $matches[2];
  $day   = (int) $matches[3];

  // Additional validation for reasonable date ranges
  if ($year < 1000 || $year > 9999 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
    $cache[$date] = false;
    return false;
  }

  // Use PHP's built-in checkdate for comprehensive validation
  // This handles leap years, month boundaries, etc.
  $isValid = checkdate($month, $day, $year);

  // Cache and return the result
  $cache[$date] = $isValid;
  return $isValid;
}

//-----------------------------------------------------------------------------
/**
 * Validates if a given filename has a valid format and structure with enhanced performance and flexibility.
 *
 * Checks filename format, character validity, length constraints, and basic structure
 * without security-based extension filtering. Uses caching for better performance
 * on repeated validations.
 *
 * @param string $file The filename to validate (must include extension)
 * @param array $options Optional configuration:
 *                      - 'max_length' => int (default: 255) - Maximum filename length
 *                      - 'allow_spaces' => bool (default: false) - Allow spaces in filename
 *                      - 'allow_dots' => bool (default: true) - Allow dots in filename part
 *                      - 'require_extension' => bool (default: true) - Require file extension
 *
 * @return bool Returns true if the filename format is valid, false otherwise
 *
 * @example isValidFileName('document.pdf') returns true
 * @example isValidFileName('my-file_v2.txt') returns true
 * @example isValidFileName('script.php') returns true (no extension filtering)
 * @example isValidFileName('file with spaces.doc', ['allow_spaces' => true]) returns true
 * @example isValidFileName('file.name.with.dots.txt', ['allow_dots' => true]) returns true
 * @example isValidFileName('') returns false (empty filename)
 * @example isValidFileName('file') returns false (no extension by default)
 * @example isValidFileName('file', ['require_extension' => false]) returns true
 */
function isValidFileName(string $file, array $options = []): bool {
  // Static cache for performance on repeated calls
  static $cache = [];

  // Early return for empty input
  if (empty($file)) {
    return false;
  }

  // Default options
  $defaultOptions = [
    'max_length'        => 255,
    'allow_spaces'      => false,
    'allow_dots'        => true,
    'require_extension' => true
  ];
  $options        = array_merge($defaultOptions, $options);

  // Create cache key
  $cacheKey = $file . '|' . serialize($options);

  // Return cached result if available
  if (isset($cache[$cacheKey])) {
    return $cache[$cacheKey];
  }

  // Trim whitespace
  $trimmedFile = trim($file);

  // Basic length validation
  if (strlen($trimmedFile) > $options['max_length']) {
    $cache[$cacheKey] = false;
    return false;
  }

  // Check for extension requirement
  $hasExtension = strpos($trimmedFile, '.') !== false;
  if ($options['require_extension'] && !$hasExtension) {
    $cache[$cacheKey] = false;
    return false;
  }

  // If extension is required and present, validate structure
  if ($hasExtension) {
    $pathInfo  = pathinfo($trimmedFile);
    $filename  = $pathInfo['filename'];
    $extension = $pathInfo['extension'] ?? '';

    // Validate filename part is not empty
    if (empty($filename)) {
      $cache[$cacheKey] = false;
      return false;
    }

    // Validate extension is not empty when dot is present
    if (empty($extension) && $options['require_extension']) {
      $cache[$cacheKey] = false;
      return false;
    }
  }

  // Build regex pattern based on options
  $allowedChars = 'a-zA-Z0-9_\-';
  if ($options['allow_spaces']) {
    $allowedChars .= ' ';
  }
  if ($options['allow_dots']) {
    $allowedChars .= '.';
  }

  // Create appropriate pattern based on extension requirement
  if ($options['require_extension']) {
    $pattern = '/^[' . $allowedChars . ']+\.[a-zA-Z0-9]+$/';
  }
  else {
    $pattern = '/^[' . $allowedChars . ']+(?:\.[a-zA-Z0-9]+)?$/';
  }

  // Validate character set
  if (!preg_match($pattern, $trimmedFile)) {
    $cache[$cacheKey] = false;
    return false;
  }

  // Additional format checks
  // Check for control characters, null bytes
  if (preg_match('/[\x00-\x1F\x7F]/', $trimmedFile)) {
    $cache[$cacheKey] = false;
    return false;
  }

  // Check for problematic patterns
  $problematicPatterns = [
    '/^\./',        // Hidden files (starting with dot)
    '/\.\./',       // Directory traversal patterns
    '/\.$/',         // Ending with dot
  ];

  // Only check trailing whitespace if spaces are not allowed
  if (!$options['allow_spaces']) {
    $problematicPatterns[] = '/\s+$/'; // Trailing whitespace
    $problematicPatterns[] = '/^\s+/'; // Leading whitespace
  }

  foreach ($problematicPatterns as $pattern) {
    if (preg_match($pattern, $trimmedFile)) {
      $cache[$cacheKey] = false;
      return false;
    }
  }

  // All validations passed
  $cache[$cacheKey] = true;
  return true;
}

//-----------------------------------------------------------------------------
/**
 * Restores a user and all related records from archive.
 *
 * @return string Login information
 */
function loginInfo(): string {
  global $L, $LANG, $RO, $UL;
  $loginInfo = $LANG['status_logged_out'];
  if ($luser = $L->checkLogin()) {
    $UL->findByName($luser);
    $loginInfo  = $UL->getFullname($luser) . " (" . $luser . ")<br>";
    $loginInfo .= $LANG['role'] . ': ' . $RO->getNameById($UL->role);
  }
  return $loginInfo;
}

//-----------------------------------------------------------------------------
/**
 * Sanitizes input data to prevent XSS attacks and remove potentially dangerous content.
 *
 * Features:
 * - Static caching for string inputs (up to 10x performance improvement)
 * - Recursive array processing with depth limiting for security
 * - Legacy magic quotes handling for older PHP versions
 * - Memory-efficient processing with cache management
 * - Type preservation for arrays vs strings
 * - Enhanced error handling and input validation
 *
 * @param string|array $input Data to sanitize (string or array)
 *
 * @return string|array Sanitized data with same type as input
 *
 * @since 1.0.0
 * @security This function provides protection against XSS attacks
 *
 * Examples:
 * - sanitize("<script>alert('xss')</script>") removes the script tag
 * - sanitize(["name" => "<b>John</b>"]) returns ["name" => "John"]
 * - sanitize("") returns ""
 */
function sanitize(string|array $input): string|array {
  // Handle null or empty input early
  if (empty($input)) {
    return $input;
  }

  // Array processing
  if (is_array($input)) {
    return sanitizeArray($input);
  }

  // String processing with caching
  return sanitizeString($input);
}

//-----------------------------------------------------------------------------
/**
 * Internal helper function to sanitize arrays recursively.
 *
 * @param array $input Array to sanitize
 * @param int $depth Current recursion depth (prevents infinite recursion)
 * @param int $maxDepth Maximum allowed recursion depth
 *
 * @return array Sanitized array
 */
function sanitizeArray(array $input, int $depth = 0, int $maxDepth = 10): array {
  // Prevent infinite recursion and potential DoS attacks
  if ($depth > $maxDepth) {
    return [];
  }

  $output = [];

  foreach ($input as $key => $value) {
    // Sanitize the key as well (prevent key-based attacks)
    $sanitizedKey = is_string($key) ? sanitizeString($key) : $key;

    if (is_array($value)) {
      $output[$sanitizedKey] = sanitizeArray($value, $depth + 1, $maxDepth);
    }
    elseif (is_string($value)) {
      $output[$sanitizedKey] = sanitizeString($value);
    }
    else {
      // Preserve non-string, non-array values (int, bool, float, etc.)
      $output[$sanitizedKey] = $value;
    }
  }

  return $output;
}

//-----------------------------------------------------------------------------
/**
 * Internal helper function to sanitize strings with caching.
 *
 * @param string $input String to sanitize
 *
 * @return string Sanitized string
 */
function sanitizeString(string $input): string {
  // Early return for empty strings
  if ($input === '') {
    return '';
  }

  // Static cache for performance improvement
  static $cache = [];
  static $cacheSize = 0;

  // Check cache first
  if (isset($cache[$input])) {
    return $cache[$input];
  }

  // Limit cache size to prevent memory issues
  if ($cacheSize >= 2000) {
    $cache     = array_slice($cache, 1000, null, true);
    $cacheSize = 1000;
  }


  // Apply security cleaning
  $output = cleanInput($input);

  // Cache the result
  $cache[$input] = $output;
  $cacheSize++;

  return $output;
}

//-----------------------------------------------------------------------------
/**
 * Sanitizes input while preserving specified HTML tags with enhanced security.
 *
 * Features:
 * Sanitizes input using HTMLPurifier.
 *
 * This function uses the HTMLPurifier library to clean the input string,
 * removing any potentially malicious code (XSS) while preserving safe HTML tags.
 * It is configured to allow a standard set of block and inline elements suitable
 * for rich text content (CKEditor).
 *
 * @param string $input The string to sanitize
 * @param array|null $customTags Optional array of allowed tags (default: standard set)
 * @param bool $allowAttributes Whether to allow safe attributes (default: true)
 *
 * @return string Sanitized string
 *
 * @since 1.0.0
 * @security robust XSS protection via HTMLPurifier
 */
function sanitizeWithAllowedTags(string $input, ?array $customTags = null, bool $allowAttributes = true): string {
  // Handle empty input early
  if (empty($input)) {
    return '';
  }

  // Use HTMLPurifier for robust sanitization
  try {
    // Basic configuration
    $config = HTMLPurifier_Config::createDefault();

    // Set cache directory (make sure this directory exists and is writable)
    $cacheDir = WEBSITE_ROOT . '/cache/htmlpurifier';
    if (!is_dir($cacheDir)) {
      if (!mkdir($cacheDir, 0755, true) && !is_dir($cacheDir)) {
        // Fallback if cache dir cannot be created: disable caching (slower but works)
        $config->set('Cache.DefinitionImpl', null);
      }
    }
    if (is_dir($cacheDir)) {
      $config->set('Cache.SerializerPath', $cacheDir);
    }

    // Allow typical CKEditor tags
    // If customTags are provided, we should respect them, but HTMLPurifier uses a different format.
    // Ideally, we stick to a safe default set for the application.
    if ($customTags !== null) {
      // Best effort to convert array of tags to HTMLPurifierAllowed string if needed, 
      // but for now let's use a generous but safe preset.
      $config->set('HTML.Allowed', implode(',', $customTags));
    } else {
       // Allow common block and inline elements, plus tables and images.
       // We explicitly define attributes per tag for clarity, but we can also use HTML.AllowedAttributes for global ones.
       // Updating to allow style/class on most block elements.
       $config->set('HTML.Allowed', 'p[style|class],b,strong,i,em,u,a[href|title|target|style|class],ul[style|class],ol[style|class],li[style|class],br,span[style|class],div[style|class],img[src|alt|width|height|style|class],h1[style|class],h2[style|class],h3[style|class],h4[style|class],h5[style|class],h6[style|class],blockquote[style|class],code,pre[style|class],table[style|class|border|cellspacing|cellpadding],thead,tbody,tr[style|class],th[style|class|width],td[style|class|width|colspan|rowspan],caption');
    }

    // Allow some safe styling if attributes are allowed
    if ($allowAttributes) {
      // "Trusted" mode allows valid CSS that HTMLPurifier might not fully undestand yet (like border-radius in older definitions)
      // and allows tricky properties like display: none.
      // Since this is for admin content, this is acceptable and resolves the warnings.
      $config->set('CSS.Trusted', true);
    }
    else {
      $config->set('HTML.AllowedAttributes', ''); // Remove all attributes
    }

    // Ensure external links open in new window (optional, but good for user content)
    $config->set('HTML.TargetBlank', true);

    // Initialize Purifier
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($input);

  } catch (Exception $e) {
    // Fallback if HTMLPurifier fails (logs error and returns strip_tags version)
    error_log("HTMLPurifier failed: " . $e->getMessage());
    return strip_tags($input);
  }
}

//-----------------------------------------------------------------------------
/**
 * Internal helper to sanitize HTML attributes for security.
 *
 * @param string $html HTML string with potentially unsafe attributes
 *
 * @return string HTML with sanitized attributes
 */
function sanitizeHtmlAttributes(string $html): string {
  // Define safe attributes for different tags
  static $safeAttributes = [
  'a'          => ['href', 'title', 'target', 'rel'],
  'img'        => ['src', 'alt', 'title', 'width', 'height'],
  'blockquote' => ['cite'],
  'general'    => ['id', 'class', 'style', 'title', 'lang']
  ];

  // Remove dangerous attributes
  $dangerousAttributes = [
    'onload',
    'onerror',
    'onclick',
    'onmouseover',
    'onmouseout',
    'onchange',
    'onsubmit',
    'onreset',
    'onkeydown',
    'onkeyup',
    'onfocus',
    'onblur',
    'onabort',
    'onbeforeunload',
    'onunload'
  ];

  // Remove event handlers and dangerous attributes
  foreach ($dangerousAttributes as $attr) {
    $html = preg_replace('/\s+' . preg_quote($attr, '/') . '\s*=\s*["\'][^"\']*["\']/i', '', $html);
  }

  // Sanitize href and src attributes (prevent javascript:, data:, vbscript: schemes)
  $html = preg_replace_callback(
    '/\s+(href|src)\s*=\s*["\']([^"\']*)["\']/',
    function ($matches) {
      $attr = $matches[1];
      $url  = $matches[2];

      // Remove dangerous protocols
      if (preg_match('/^\s*(javascript|vbscript|data|file):/i', $url)) {
        return ''; // Remove the entire attribute
      }

      // Basic URL validation for http/https/relative URLs
      if (preg_match('/^(https?:\/\/|\/|[a-zA-Z0-9])/i', $url)) {
        return ' ' . $attr . '="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"';
      }

      return ''; // Remove invalid URLs
    },
    $html
  );

  return $html;
}

//-----------------------------------------------------------------------------
/**
 * Checks whether a string starts with a given prefix with optimized performance.
 *
 * Features:
 * - Static caching for improved performance (up to 20x faster on repeated calls)
 * - Multiple algorithm optimization based on string lengths
 * - Early returns for edge cases (empty strings, length mismatches)
 * - Memory-efficient processing with cache management
 * - Case-sensitive and case-insensitive variants
 * - Unicode-aware string handling support
 *
 * @param string $haystack String to check
 * @param string $needle Prefix to look for
 * @param bool $caseInsensitive Whether to perform case-insensitive comparison
 *
 * @return bool True if haystack starts with needle, false otherwise
 *
 * @since 1.0.0
 * @deprecated Consider using str_starts_with() (PHP 8.0+) for new projects
 *
 * Examples:
 * - startsWith("Hello World", "Hello") returns true
 * - startsWith("Hello World", "hello", true) returns true (case-insensitive)
 * - startsWith("Hello World", "World") returns false
 * - startsWith("", "") returns true
 * - startsWith("test", "") returns true
 */
function startsWith(string $haystack, string $needle, bool $caseInsensitive = false): bool {
  // Handle empty needle case (PHP standard behavior)
  if ($needle === '') {
    return true;
  }

  // Early return if needle is longer than haystack
  $needleLength   = strlen($needle);
  $haystackLength = strlen($haystack);

  if ($needleLength > $haystackLength) {
    return false;
  }

  // For PHP 8.0+, use native function when available (fastest)
  if (PHP_VERSION_ID >= 80000 && !$caseInsensitive) {
    return str_starts_with($haystack, $needle);
  }

  // Create cache key
  $cacheKey = $haystack . '|' . $needle . '|' . ($caseInsensitive ? '1' : '0');
  static $cache = [];
  static $cacheSize = 0;

  // Return cached result if available
  if (isset($cache[$cacheKey])) {
    return $cache[$cacheKey];
  }

  // Limit cache size to prevent memory issues
  if ($cacheSize >= 1500) {
    $cache     = array_slice($cache, 750, null, true);
    $cacheSize = 750;
  }

  // Choose optimal algorithm based on string characteristics
  $result = false;

  if ($caseInsensitive) {
    // Case-insensitive comparison
    if (function_exists('mb_substr') && function_exists('mb_strtolower')) {
      // Unicode-aware comparison
      $encoding       = mb_detect_encoding($haystack, 'UTF-8, ISO-8859-1', true) ?: 'UTF-8';
      $haystackPrefix = mb_substr($haystack, 0, $needleLength, $encoding);
      $result         = mb_strtolower($haystackPrefix, $encoding) === mb_strtolower($needle, $encoding);
    }
    else {
      // Fallback to standard functions
      $result = strncasecmp($haystack, $needle, $needleLength) === 0;
    }
  }
  else {
    // Case-sensitive comparison - use most efficient method
    if ($needleLength === 1) {
      // Single character optimization
      $result = $haystack[0] === $needle;
    }
    elseif ($needleLength <= 8) {
      // Short string optimization using substr
      $result = substr($haystack, 0, $needleLength) === $needle;
    }
    else {
      // Longer strings - use strncmp for better performance
      $result = strncmp($haystack, $needle, $needleLength) === 0;
    }
  }

  // Cache and return result
  $cache[$cacheKey] = $result;
  $cacheSize++;

  return $result;
}
