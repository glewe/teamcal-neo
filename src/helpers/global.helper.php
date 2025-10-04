<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Global Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * Returns the base URL of the application.
 *
 * @param string $path Optional path to append to the base URL.
 * 
 * @return string The base URL with the optional path appended.
 */
function base_url(string $path = ''): string {
  static $baseUrl = null;
  
  // Cache the base URL to avoid recalculating on multiple calls
  if ($baseUrl === null) {
    // Determine protocol (more robust HTTPS detection)
    $isHttps = (
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
      (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
      (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
      (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
    );
    $protocol = $isHttps ? 'https://' : 'http://';

    // Get and validate the host (security: prevent Host header injection)
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
    
    // Basic validation for host header
    if (!preg_match('/^[a-zA-Z0-9.-]+(?::[0-9]+)?$/', $host)) {
      $host = $_SERVER['SERVER_NAME'] ?? 'localhost';
    }

    // Get the base directory more reliably
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $baseDir = $scriptName ? dirname($scriptName) : '';
    
    // Normalize the base directory
    $baseDir = str_replace('\\', '/', $baseDir); // Windows compatibility
    $baseDir = rtrim($baseDir, '/') . '/';
    if ($baseDir === './') {
      $baseDir = '/';
    }

    // Construct and cache the base URL
    $baseUrl = $protocol . $host . $baseDir;
  }

  // Append the optional path
  if ($path) {
    $path = ltrim($path, '/');
    return $baseUrl . $path;
  }
  
  return $baseUrl;
}

//-----------------------------------------------------------------------------
/**
 * Cleans and returns a given string.
 * Not called directly, but used by function sanitize()
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
 * Compare two language files for missing keys
 * 
 * @param string $lang1 Primary language (reference)
 * @param string $lang2 Secondary language (to compare)
 * @return array Comparison results
 */
function compareLanguageFiles(string $lang1, string $lang2): array {
  global $C, $appTitle, $LANG;
  
  $result = [
    'lang1' => $lang1,
    'lang2' => $lang2,
    'lang1_missing' => [],
    'lang2_missing' => [],
    'lang1_total' => 0,
    'lang2_total' => 0,
    'errors' => []
  ];
  
  $languageFiles = ['.php', '.log.php', '.app.php'];
  
  // Provide safe defaults for variables that language files might reference
  if (!isset($appTitle)) {
    $appTitle = $C ? $C->read('appTitle') : 'TeamCal Neo';
  }
  
  // Load first language
  $lang1Array = [];
  foreach ($languageFiles as $suffix) {
    $file = WEBSITE_ROOT . '/languages/' . $lang1 . $suffix;
    if (file_exists($file)) {
      // Backup current $LANG if it exists
      $backupLang = isset($LANG) ? $LANG : null;
      unset($LANG);
      
      // Use output buffering to catch any unexpected output
      ob_start();
      try {
        require $file;
        if (isset($LANG) && is_array($LANG)) {
          $lang1Array = array_merge($lang1Array, $LANG);
        }
      } catch (Exception $e) {
        $result['errors'][] = "Error loading $file: " . $e->getMessage();
      } catch (Error $e) {
        $result['errors'][] = "Fatal error loading $file: " . $e->getMessage();
      }
      ob_end_clean();
      
      // Restore $LANG if it existed
      if ($backupLang !== null) {
        $LANG = $backupLang;
      }
    } else {
      $result['errors'][] = "File not found: $file";
    }
  }
  
  // Load second language
  $lang2Array = [];
  foreach ($languageFiles as $suffix) {
    $file = WEBSITE_ROOT . '/languages/' . $lang2 . $suffix;
    if (file_exists($file)) {
      // Backup current $LANG if it exists
      $backupLang = isset($LANG) ? $LANG : null;
      unset($LANG);
      
      // Use output buffering to catch any unexpected output
      ob_start();
      try {
        require $file;
        if (isset($LANG) && is_array($LANG)) {
          $lang2Array = array_merge($lang2Array, $LANG);
        }
      } catch (Exception $e) {
        $result['errors'][] = "Error loading $file: " . $e->getMessage();
      } catch (Error $e) {
        $result['errors'][] = "Fatal error loading $file: " . $e->getMessage();
      }
      ob_end_clean();
      
      // Restore $LANG if it existed
      if ($backupLang !== null) {
        $LANG = $backupLang;
      }
    } else {
      $result['errors'][] = "File not found: $file";
    }
  }
  
  $result['lang1_total'] = count($lang1Array);
  $result['lang2_total'] = count($lang2Array);
  
  // Find missing keys
  foreach ($lang1Array as $key => $val) {
    if (!array_key_exists($key, $lang2Array)) {
      $result['lang1_missing'][] = $key;
    }
  }
  
  foreach ($lang2Array as $key => $val) {
    if (!array_key_exists($key, $lang1Array)) {
      $result['lang2_missing'][] = $key;
    }
  }
  
  // Sort for easier reading
  sort($result['lang1_missing']);
  sort($result['lang2_missing']);
  
  return $result;
}

//-----------------------------------------------------------------------------
/**
 * Computes several date related information for a given date
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
  
  $year = (int)$year;
  $month = (int)$month;
  $day = (int)$day;
  
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
  $yearStr = $dateTime->format('Y');
  $monthStr = $dateTime->format('m');
  $dayStr = $dateTime->format('d');
  $monthNum = (int)$monthStr;
  
  // Build basic date info
  $dateInfo = [
    'dd' => $dayStr,
    'mm' => $monthStr,
    'year' => (int)$yearStr,
    'month' => $monthNum,
    'daysInMonth' => (int)$dateTime->format('t'),
    'ISO' => $dateTime->format('Y-m-d'),
    'wday' => (int)$dateTime->format('N'),
    'week' => (int)$dateTime->format('W')
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
  
  // Calculate month boundaries
  $dateInfo['firstOfMonth'] = $yearStr . '-' . $monthStr . '-01';
  $dateInfo['lastOfMonth'] = $yearStr . '-' . $monthStr . '-' . str_pad($dateInfo['daysInMonth'], 2, '0', STR_PAD_LEFT);
  
  // Calculate year boundaries
  $dateInfo['firstOfYear'] = $yearStr . '-01-01';
  $dateInfo['lastOfYear'] = $yearStr . '-12-31';
  
  // Calculate quarter and half-year boundaries more efficiently
  $quarterMap = [
    1 => ['start' => '01-01', 'end' => '03-31', 'half_end' => '06-30'],
    2 => ['start' => '04-01', 'end' => '06-30', 'half_end' => '06-30'], 
    3 => ['start' => '07-01', 'end' => '09-30', 'half_end' => '12-31'],
    4 => ['start' => '10-01', 'end' => '12-31', 'half_end' => '12-31']
  ];
  
  $quarter = (int)ceil($monthNum / 3);
  $quarterInfo = $quarterMap[$quarter];
  
  $dateInfo['firstOfQuarter'] = $yearStr . '-' . $quarterInfo['start'];
  $dateInfo['lastOfQuarter'] = $yearStr . '-' . $quarterInfo['end'];
  $dateInfo['firstOfHalf'] = $yearStr . '-' . ($quarter <= 2 ? '01-01' : '07-01');
  $dateInfo['lastOfHalf'] = $yearStr . '-' . $quarterInfo['half_end'];
  
  return $dateInfo;
}

//-----------------------------------------------------------------------------
/**
 * This function is used to dump a variable for debugging purposes.
 *
 * @param mixed $var The variable that you want to dump.
 * @param bool $dieafter Optional. If set to true, the script will stop executing after the variable is dumped. Default is true.
 */
function dnd($var, bool $dieafter = true): void {
  echo "<pre>";
  var_dump($var);
  echo "</pre>";
  if ($dieafter) {
    die();
  }
}

//-----------------------------------------------------------------------------
/**
 * Checks whether a string ends with a given suffix
 *
 * @param string $haystack String to check
 * @param string $needle Suffix to look for
 *
 * @return boolean True or False
 */
function endsWith(string $haystack, string $needle): bool {
  // search forward starting from end minus needle length characters
  return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== false;
}

//-----------------------------------------------------------------------------
/**
 * Validates a form field against a ruleset
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
  $rules = explode('|', $ruleset);
  $rulesLookup = array_flip($rules); // O(1) lookups instead of O(n) in_array calls
  $label = explode('_', $field);
  $fieldExists = isset($_POST[$field]);
  $fieldValue = $fieldExists ? $_POST[$field] : '';
  $hasValue = $fieldExists && strlen($fieldValue);
  
  // Static cache for compiled regex patterns (performance optimization)
  static $regexPatterns = null;
  if ($regexPatterns === null) {
    $regexPatterns = [
      'alpha' => '/^[\pL]+$/u',
      'alpha_numeric' => '/^[\pL\w]+$/u',
      'alpha_numeric_dash' => '/^[\pL\w_-]+$/u',
      'username' => '/^[\pL\w.@_-]+$/u',
      'alpha_numeric_dash_blank' => '/^[ \pL\w_-]+$/u',
      'alpha_numeric_dash_blank_dot' => '/^[ \pL\w._-]+$/u',
      'alpha_numeric_dash_blank_special' => '/^[ \pL\w\'!@#$%^&*()._-]+$/u',
      'date' => '/^(\d{4})-(\d{2})-(\d{2})$/',
      'hexadecimal' => '/^[a-f0-9]+$/i',
      'hex_color' => '/^#?[a-f0-9]{6}$/i',
      'phone_number' => '/^[ +0-9-()]+$/i',
      'pwdlow' => '/^.*(?=.{4,})[a-zA-Z0-9!@#$%^&*().]+$/',
      'pwdmedium' => '/^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*().]+$/',
      'pwdhigh' => '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()])[a-zA-Z0-9!@#$%^&*().]+$/'
    ];
  }
  
  // Helper function to set error and return false
  $setError = function(string $messageKey, ...$args) use (&$inputAlert, $label, $LANG, $field) {
    $inputAlert[$label[1]] = empty($args) ? $LANG[$messageKey] : sprintf($LANG[$messageKey], ...$args);
    return false;
  };

  // Sanitize first if requested
  if (isset($rulesLookup['sanitize']) && $hasValue) {
    $_POST[$field] = sanitize($fieldValue);
    $fieldValue = $_POST[$field]; // Update cached value
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
  $mbLength = null;
  $getMbLength = function() use (&$mbLength, $fieldValue) {
    if ($mbLength === null) {
      $mbLength = mb_strlen($fieldValue);
    }
    return $mbLength;
  };
  
  // Cache numeric check for numeric validations
  $isNumeric = null;
  $getIsNumeric = function() use (&$isNumeric, $fieldValue) {
    if ($isNumeric === null) {
      $isNumeric = is_numeric($fieldValue);
    }
    return $isNumeric;
  };

  // Pattern-based validations using cached regex patterns
  foreach (['alpha', 'alpha_numeric', 'alpha_numeric_dash', 'username', 'alpha_numeric_dash_blank', 
           'alpha_numeric_dash_blank_dot', 'alpha_numeric_dash_blank_special', 'hexadecimal', 
           'hex_color', 'phone_number', 'pwdlow', 'pwdmedium', 'pwdhigh'] as $rule) {
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
 * Generates a cryptographically secure password
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
    'require_mixed' => true,
    'custom_chars' => null
  ];
  $options = array_merge($defaultOptions, $options);
  
  // Define character sets
  if ($options['custom_chars'] !== null) {
    $characters = $options['custom_chars'];
    $charSets = [$characters]; // Single set for custom characters
  } else {
    if ($options['exclude_ambiguous']) {
      // Exclude ambiguous characters (0, O, l, 1, I)
      $lowercase = 'abcdefghjkmnpqrstuvwxyz';
      $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
      $numbers = '23456789';
      $symbols = '@#$%&*+=?';
    } else {
      $lowercase = 'abcdefghijklmnopqrstuvwxyz';
      $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $numbers = '0123456789';
      $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
    }
    
    $characters = $lowercase . $uppercase . $numbers . $symbols;
    $charSets = $options['require_mixed'] ? [$lowercase, $uppercase, $numbers, $symbols] : [$characters];
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
    $randomIndex = unpack('n', substr($randomBytes, $i * 2, 2))[1] % $charactersLength;
    $char = $characters[$randomIndex];
    $password .= $char;
    
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
      if ($length <= count($missingSetIndices)) break; // Not enough space for all sets
      
      // Generate secure random position and character
      $randomPos = unpack('n', random_bytes(2))[1] % $length;
      $randomCharIndex = unpack('n', random_bytes(2))[1] % strlen($charSets[$setIndex]);
      $password[$randomPos] = $charSets[$setIndex][$randomCharIndex];
    }
  }
  
  return $password;
}

//-----------------------------------------------------------------------------
/**
 * Retrieves the client's real IP address with comprehensive proxy support and validation.
 *
 * This function checks multiple server variables in order of trustworthiness to determine 
 * the client's real IP address, handling various proxy configurations and load balancers.
 * It validates all IP addresses and provides caching for better performance.
 *
 * @param bool $validate Whether to validate IP addresses (default: true)
 * @param array $trustedProxies Optional array of trusted proxy IP addresses/ranges
 * 
 * @return string The client's IP address, or fallback IP if none found/valid
 */
function getClientIp(bool $validate = true, array $trustedProxies = []): string {
  // Static cache for performance (IP shouldn't change during request)
  static $cachedIp = null;
  static $cacheKey = null;
  
  // Create cache key based on parameters
  $currentCacheKey = md5(serialize([$validate, $trustedProxies]));
  
  if ($cachedIp !== null && $cacheKey === $currentCacheKey) {
    return $cachedIp;
  }
  
  // Headers to check in order of preference (most trusted first)
  $headers = [
    'HTTP_CF_CONNECTING_IP',     // Cloudflare
    'HTTP_TRUE_CLIENT_IP',       // Cloudflare/Fastly
    'HTTP_X_REAL_IP',           // Nginx proxy
    'HTTP_X_FORWARDED_FOR',     // Standard proxy header
    'HTTP_X_FORWARDED',         // Alternative proxy header
    'HTTP_X_CLUSTER_CLIENT_IP', // Cluster/load balancer
    'HTTP_FORWARDED_FOR',       // RFC 7239 (older)
    'HTTP_FORWARDED',           // RFC 7239 (newer)
    'HTTP_CLIENT_IP',           // Some proxies
    'REMOTE_ADDR'               // Direct connection (always available)
  ];
  
  $fallbackIp = '127.0.0.1'; // Safe fallback
  $foundIp = '';
  
  foreach ($headers as $header) {
    if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
      continue;
    }
    
    $headerValue = trim($_SERVER[$header]);
    
    // Handle comma-separated lists (X-Forwarded-For can contain multiple IPs)
    if (strpos($headerValue, ',') !== false) {
      $ips = array_map('trim', explode(',', $headerValue));
      
      // Take the first valid IP (leftmost is usually the original client)
      foreach ($ips as $ip) {
        if ($validate) {
          $cleanIp = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
          if ($cleanIp !== false) {
            $foundIp = $cleanIp;
            break 2; // Break out of both loops
          }
        } else {
          // Basic format validation without filtering private ranges
          if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
            $foundIp = $ip;
            break 2;
          }
        }
      }
    } else {
      // Single IP address
      if ($validate) {
        $cleanIp = filter_var($headerValue, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        if ($cleanIp !== false) {
          $foundIp = $cleanIp;
          break;
        }
      } else {
        // Basic format validation without filtering private ranges
        if (filter_var($headerValue, FILTER_VALIDATE_IP) !== false) {
          $foundIp = $headerValue;
          break;
        }
      }
    }
  }
  
  // If no valid public IP found and validation is enabled, try again without strict filtering
  if (empty($foundIp) && $validate) {
    foreach ($headers as $header) {
      if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
        continue;
      }
      
      $headerValue = trim($_SERVER[$header]);
      
      if (strpos($headerValue, ',') !== false) {
        $ips = array_map('trim', explode(',', $headerValue));
        foreach ($ips as $ip) {
          if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
            $foundIp = $ip;
            break 2;
          }
        }
      } else {
        if (filter_var($headerValue, FILTER_VALIDATE_IP) !== false) {
          $foundIp = $headerValue;
          break;
        }
      }
    }
  }
  
  // Final fallback
  if (empty($foundIp)) {
    $foundIp = $fallbackIp;
  }
  
  // Optional: Validate against trusted proxies (if specified)
  if (!empty($trustedProxies) && !empty($foundIp)) {
    $isTrustedProxy = false;
    foreach ($trustedProxies as $trustedProxy) {
      if (strpos($trustedProxy, '/') !== false) {
        // CIDR notation support
        if (ipInRange($foundIp, $trustedProxy)) {
          $isTrustedProxy = true;
          break;
        }
      } else {
        // Single IP comparison
        if ($foundIp === $trustedProxy) {
          $isTrustedProxy = true;
          break;
        }
      }
    }
    
    // If IP is from trusted proxy, look for the next IP in chain
    if ($isTrustedProxy && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $forwardedIps = array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
      foreach ($forwardedIps as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP) !== false && $ip !== $foundIp) {
          $foundIp = $ip;
          break;
        }
      }
    }
  }
  
  // Cache the result
  $cachedIp = $foundIp;
  $cacheKey = $currentCacheKey;
  
  return $foundIp;
}

//-----------------------------------------------------------------------------
/**
 * Helper function to check if an IP address is within a CIDR range
 *
 * @param string $ip IP address to check
 * @param string $cidr CIDR notation (e.g., "192.168.1.0/24")
 * 
 * @return bool True if IP is in range, false otherwise
 */
function ipInRange(string $ip, string $cidr): bool {
  if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    return false;
  }
  
  list($subnet, $mask) = explode('/', $cidr);
  
  if (!filter_var($subnet, FILTER_VALIDATE_IP)) {
    return false;
  }
  
  $mask = (int)$mask;
  
  // Convert IP addresses to long integers
  $ipLong = ip2long($ip);
  $subnetLong = ip2long($subnet);
  
  if ($ipLong === false || $subnetLong === false) {
    return false;
  }
  
  // Create subnet mask
  $subnetMask = -1 << (32 - $mask);
  
  // Apply mask to both IPs and compare
  return ($ipLong & $subnetMask) === ($subnetLong & $subnetMask);
}

//-----------------------------------------------------------------------------
/**
 * Scans a given directory for files.
 * Optionally you can specify an array of extension to look for.
 *
 * @param string $myDir Directory name to scan
 * @param array $myExt Array of extensions to scan for
 * @param string $myPrefix An optional prefix of the filename
 *
 * @return array Array containing the names of the files
 */
function getFiles(string $myDir, array $myExt = [], string $myPrefix = ''): array {
  $myDir = rtrim($myDir, "/");
  $dir = opendir($myDir);
  while (false !== ($filename = readdir($dir))) {
    $files[] = $filename;
  }
  foreach ($files as $pos => $file) {
    if (is_dir($file)) {
      unset($files[$pos]);
    }
  }
  if (count($myExt) || $myPrefix) {
    $filearray = array();
    if (count($files)) {
      foreach ($files as $pos => $file) {
        $thisPref = getFilePrefix(strtolower($file));
        $thisExt = getFileExtension(strtolower($file));
        if (count($myExt) && !$myPrefix) {
          if (in_array($thisExt, $myExt)) {
            $filearray[] = $file;
          }
        } elseif (!count($myExt) && $myPrefix) {
          if (startsWith($thisPref, $myPrefix)) {
            $filearray[] = $file;
          }
        } elseif (count($myExt) && $myPrefix) {
          if (in_array($thisExt, $myExt) && startsWith($thisPref, $myPrefix)) {
            $filearray[] = $file;
          }
        }
      }
    }
    return $filearray;
  } else {
    return $files;
  }
}

//-----------------------------------------------------------------------------
/**
 * Extracts the file extension from a given file name
 *
 * @param string $str String containing the path or filename
 *
 * @return string File extension of the string passed
 */
function getFileExtension(string $str): string {
  $i = strrpos($str, ".");
  if (!$i) {
    return '';
  }
  $l = strlen($str) - $i;
  return substr($str, $i + 1, $l);
}

//-----------------------------------------------------------------------------
/**
 * Extracts the file extension from a given file name
 *
 * @param string $str String containing the path or filename
 *
 * @return string File extension of the string passed
 */
function getFilePrefix(string $str): string {
  $i = strpos($str, ".");
  if (!$i) {
    return '';
  }
  return substr($str, 0, $i);
}

//-----------------------------------------------------------------------------
/**
 * Gets all folders in a given directory
 *
 * @param string $myDir String containing the pathname
 *
 * @return array Array containing the folder names
 */
function getFolders(string $myDir): array {
  $myDir = rtrim($myDir, '/') . '/'; // Ensure trailing slash
  $handle = opendir($myDir);
  while (false !== ($dir = readdir($handle))) {
    if (is_dir($myDir . "/$dir") && $dir != "." && $dir != "..") {
      $dirarray[] = $dir;
    }
  }
  closedir($handle);
  return $dirarray;
}

//-----------------------------------------------------------------------------
/**
 * Returns todays date in ISO 8601 format
 *
 * @return string ISO 8601 format, e.g. 2014-03-03
 */
function getISOToday(): string {
  $mydate = getdate();
  $year = $mydate['year'];                  // Numeric representation of todays' year, 4 digits
  $month = sprintf("%02d", $mydate['mon']); // Numeric representation of todays' month, 2 digits
  $day = sprintf("%02d", $mydate['mday']);  // Numeric representation of todays' day of the month, 2 digits
  return $year . '-' . $month . '-' . $day;
}

//-----------------------------------------------------------------------------
/**
 * Gets all language directory names from the language directory
 *
 * @param string $type Look for application or log languages
 *
 * @return array Array containing the names
 */
function getLanguages(string $type = 'app'): array {
  $mydir = "languages/";
  $handle = opendir($mydir); // open directory
  $fileidx = 0;
  while (false !== ($file = readdir($handle))) {
    if (!is_dir($mydir . "/$file") && $file != "." && $file != "..") {
      $filearray[$fileidx]["name"] = $file;
      $fileidx++;
    }
  }
  closedir($handle);
  // If there are language files
  if ($fileidx > 0) {
    // Extract the language name
    for ($i = 0; $i < $fileidx; $i++) {
      $langName = explode(".", $filearray[$i]["name"]);
      if ($type == 'log') {
        if ($langName[1] == 'log') {
          $langarray[$i] = $langName[0];
        }
      } else {
        if ($langName[1] == 'php') {
          $langarray[$i] = $langName[0];
        }
      }
    }
  }
  return $langarray;
}

//-----------------------------------------------------------------------------
/**
 * Gets all $_GET and $_POST parameters and fills the $CONF['options'][] array
 */
function getOptions(): void {
  global $C, $CONF, $L, $UO;
  $showDebug = false;
  $user = $L->checkLogin();
  /**
   * Set time zone
   */
  $tz = $C->read("timeZone");
  if (!strlen($tz) || $tz == "default") {
    date_default_timezone_set('UTC');
  } else {
    date_default_timezone_set($tz);
  }
  /**
   * Get app defaults from database
   */
  $CONF['options']['lang'] = $C->read("defaultLanguage");
  /**
   * DEBUG: Set to true for debug info
   */
  if ($showDebug) {
    $debug = "After Defaults\\r\\n";
    $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
    echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
  }
  /**
   * Get user preferences if someone is logged in
   */
  if ($user) {
    $userlang = $UO->read($user, "language");
    if ($userlang && $userlang != "default") {
      $CONF['options']['lang'] = $userlang;
    }
  }
  /**
   * DEBUG: Set to true for debug info
   */
  if ($showDebug) {
    $debug = "After User Preferences\\r\\n";
    $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
    echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
  }
  /**
   * Get $_GET (overwriting user preferences)
   */

  /**
   * DEBUG: Set to true for debug info
   */
  if ($showDebug) {
    $debug = "After _GET\\r\\n";
    $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
    echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
  }
  /**
   * Now get $_POST (overwrites $_GET and user preferences)
   */
  if (isset($_POST['user_lang']) && strlen($_POST['user_lang']) && in_array($_POST['user_lang'], getLanguages())) {
    $CONF['options']['lang'] = trim($_POST['user_lang']);
  }

  /**
   * DEBUG: Set to true for debug info
   */
  if ($showDebug) {
    $debug = "After _POST\\r\\n";
    $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
    echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
  }
}

//-----------------------------------------------------------------------------
/**
 * Reads phpinfo() and parses it into a Bootstrap panel display.
 *
 * @return string $output Bootstrap formatted phpinfo() output
 */
function getPhpInfoBootstrap(): string {
  $output = '';
  $rowstart = "<div class='row' style='border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;'>\n";
  $rowend = "</div>\n";
  ob_start();
  phpinfo(11);
  $phpinfo = array('phpinfo' => array());

  if (preg_match_all('#(?:<h2>(?:<a>)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
      if (strlen($match[1])) {
        $phpinfo[$match[1]] = array();
      } elseif (isset($match[3])) {
        $keys1 = array_keys($phpinfo);
        $phpinfo[end($keys1)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
      } else {
        $keys1 = array_keys($phpinfo);
        $phpinfo[end($keys1)][] = $match[2];
      }
    }
  }

  if (!empty($phpinfo)) {
    foreach ($phpinfo as $name => $section) {
      foreach ($section as $key => $val) {
        $output .= $rowstart;
        if (is_array($val)) {
          $output .= "<div class='col-lg-4 text-bold'>" . $key . "</div>\n<div class='col-lg-4'>" . $val[0] . "</div>\n<div class='col-lg-4'>" . $val[1] . "</div>\n";
        } elseif (is_string($key)) {
          $output .= "<div class='col-lg-4 text-bold'>" . $key . "</div>\n<div class='col-lg-8'>" . $val . "</div>\n";
        } else {
          $output .= "<div class='col-lg-12'>" . $val . "</div>\n";
        }
        $output .= $rowend;
      }
    }
  } else {
    $output .= '<p>An error occurred executing the phpinfo() function. It may not be accessible or disabled. <a href="https://php.net/manual/en/function.phpinfo.php">See the documentation.</a></p>';
  }
  //
  // Some HTML fixes
  //
  $output = str_replace('border="0"', 'style="border: 0px;"', $output);
  $output = str_replace("<font ", "<span ", $output);
  $output = str_replace("</font>", "</span>", $output);
  return $output;
}

//-----------------------------------------------------------------------------
/**
 * Determines the role bootstrap color
 *
 * @param string $role Role name
 *
 * @return string Bootstrap color name
 */
function getRoleColor(string $role): string {
  switch ($role) {
    case 'assistant':
      $color = 'primary';
      break;
    case 'manager':
      $color = 'warning';
      break;
    case 'director':
      $color = 'default';
      break;
    case 'admin':
      $color = 'danger';
      break;
    default:
      $color = 'success';
      break;
  }
  return $color;
}

//-----------------------------------------------------------------------------
/**
 * Returns a hex color value as an array of decimal RGB values
 *
 * @param string $color Hex color string to convert
 *
 * @return array RGB values in an array
 */
function hex2rgb(string $color): array {
  $color = str_replace('#', '', $color);
  if (strlen($color) != 6) {
    return array(0, 0, 0);
  }
  $rgb = array();
  for ($x = 0; $x < 3; $x++) {
    $rgb[$x] = hexdec(substr($color, (2 * $x), 2));
  }
  return $rgb;
}

//-----------------------------------------------------------------------------
/**
 * Validates if a given date string is in the format YYYY-MM-DD and checks if it is a valid date.
 *
 * @param string $date The date string to validate.
 * 
 * @return bool Returns true if the date is valid and in the format YYYY-MM-DD, false otherwise.
 */
function isValidDate(string $date): bool {
  // Regular expression to check if the date is in the format YYYY-MM-DD
  $regex = '/^\d{4}-\d{2}-\d{2}$/';
  if (preg_match($regex, $date)) {
    // Check if the date is valid
    $parts = explode('-', $date);
    return checkdate($parts[1], $parts[2], $parts[0]);
  }
  return false;
}

//-----------------------------------------------------------------------------
/**
 * Validates if a given file name is valid.
 *
 * @param string $file The file name to validate.
 * 
 * @return bool Returns true if the file name is valid, false otherwise.
 */
function isValidFileName(string $file): bool {
  // Regular expression to check if the file name contains only valid characters
  $regex = '/^[a-zA-Z0-9_\-]+\.[a-zA-Z0-9]+$/';
  return preg_match($regex, $file);
}

//-----------------------------------------------------------------------------
/**
 * Restores a user and all related records from archive
 *
 * @return string Login information
 */
function loginInfo(): string {
  global $L, $LANG, $RO, $UL;
  $loginInfo = $LANG['status_logged_out'];
  if ($luser = $L->checkLogin()) {
    /**
     * Get the user
     */
    $UL->findByName($luser);
    $loginInfo = $UL->getFullname($luser) . " (" . $luser . ")<br>";
    $loginInfo .= $LANG['role'] . ': ' . $RO->getNameById($UL->role);
  }
  return $loginInfo;
}

//-----------------------------------------------------------------------------
/**
 * Pretty prints an array dump
 *
 * @param array $a Array to print out pretty
 * 
 * @return string
 */
function prettyDump(array $a): string {
  return highlight_string("<?php\n\$data =\n" . var_export($a, true) . ";\n?>");
}

//-----------------------------------------------------------------------------
/**
 * Reads a value out of a config file
 *
 * @param string $var Array index to read
 * @param string $file File to scan
 * 
 * @return string The value of the read variable
 */
function readConfig(string $var = '', string $file = ''): string {
  $value = "";
  $handle = fopen($file, "r");
  if ($handle) {
    while (!feof($handle)) {
      $buffer = fgets($handle, 4096);
      if (strpos($buffer, "'" . $var . "'") == 6) {
        $pos1 = strpos($buffer, '"');
        $pos2 = strrpos($buffer, '"');
        $value = trim(substr($buffer, $pos1 + 1, $pos2 - ($pos1 + 1)));
      }
    }
    fclose($handle);
  }
  return $value;
}

//-----------------------------------------------------------------------------
/**
 * Reads a define value out of a config file
 *
 * @param string $var Array index to read
 * @param string $file File to scan
 * 
 * @return string The value of the read variable
 */
function readDef(string $var = '', string $file = ''): string {
  $value = "";
  $handle = fopen($file, "r");
  if ($handle) {
    while (!feof($handle)) {
      $buffer = fgets($handle, 4096);
      if (strpos($buffer, "'" . $var . "'") == 7) {
        $pos1 = strpos($buffer, '"');
        $pos2 = strrpos($buffer, '"');
        $value = trim(substr($buffer, $pos1 + 1, $pos2 - ($pos1 + 1)));
      }
    }
    fclose($handle);
  }
  return $value;
}

//-----------------------------------------------------------------------------
/**
 * Returns a comma separated string of RGB decimal values as a hex color value
 *
 * @param string $color Comma separated string of RGB decimal values
 * @param bool $hashPrefix If true, a hash prefix is added to the hex value
 *
 * @return string Hex color value
 */
function rgb2hex(string $color, bool $hashPrefix = true): string {
  $rgbArray = explode(',', $color);
  if ($hashPrefix) {
    $hex = '#';
  } else {
    $hex = '';
  }
  foreach ($rgbArray as $dec) {
    $hex .= sprintf("%02s", dechex($dec));
  }
  return $hex;
}

//-----------------------------------------------------------------------------
/**
 * Capitalizes the first letter of a given word and makes the rest lower case
 *
 * @param string $string String to properize
 *
 * @return string Properly capitalized string
 */
function proper(string $string): string {
  $string = strtolower($string);
  return substr_replace($string, strtoupper(substr($string, 0, 1)), 0, 1);
}

//-----------------------------------------------------------------------------
/**
 * Sanitizes and returns a given string
 *
 * @param string|array $input String to sanitize
 *
 * @return string|array Sanitized string
 */
function sanitize(string|array $input): string|array {
  if (is_array($input)) {
    foreach ($input as $var => $val) {
      $output[$var] = sanitize($val);
    }
  } else {
    if (function_exists('get_magic_quotes_gpc') && ini_get('magic_quotes_gpc')) {
      $input = stripslashes($input);
    }
    $output = cleanInput($input);
  }
  return $output;
}

//-----------------------------------------------------------------------------
/**
 * Sanitizes input while allowing certain HTML tags.
 *
 * @param string $input The input string to sanitize.
 *
 * @return string Sanitized string.
 */
function sanitizeWithAllowedTags(string $input): string {
  $allowedTags = [
    '<a>',
    '<b>',
    '<br>',
    '<em>',
    '<h1>',
    '<h2>',
    '<h3>',
    '<h4>',
    '<hr>',
    '<i>',
    '<img>',
    '<li>',
    '<ol>',
    '<p>',
    '<strong>',
    '<ul>',
  ];
  // Convert the array of allowed tags to a string
  $allowedTagsString = implode('', $allowedTags);
  // Strip tags except the allowed ones
  return strip_tags($input, $allowedTagsString);
}

//-----------------------------------------------------------------------------
/**
 * Checks whether a string starts with a given prefix
 *
 * @param string $haystack String to check
 * @param string $needle Prefix to look for
 *
 * @return boolean True or False
 */
function startsWith(string $haystack, string $needle): bool {
  // search backwards starting from haystack length characters from the end
  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

//-----------------------------------------------------------------------------
/**
 * Writes a value into config.tcneo.php
 *
 * @param string $var Variable name
 * @param string $value Value to assign to variable
 * @param string $file File in which to do so
 */
function writeConfig(string $var = '', string $value = '', string $file = ''): void {
  $newbuffer = "";
  $handle = fopen($file, "r");
  if ($handle) {
    while (!feof($handle)) {
      $buffer = fgets($handle, 4096);
      if (strpos($buffer, "'" . $var . "'") == 6) {
        $pos1 = strpos($buffer, '"');
        $pos2 = strrpos($buffer, '"');
        $newbuffer .= substr_replace($buffer, $value . "\"", $pos1 + 1, $pos2 - ($pos1));
      } else {
        $newbuffer .= $buffer;
      }
    }
    fclose($handle);
    $handle = fopen($file, "w");
    fwrite($handle, $newbuffer);
    fclose($handle);
  }
}

//-----------------------------------------------------------------------------
/**
 * Writes a define value into config.tcneo.php
 *
 * @param string $var Variable name
 * @param string $value Value to assign to variable
 * @param string $file File in which to do so
 */
function writeDef(string $var = '', string $value = '', string $file = ''): void {
  $newbuffer = "";
  $handle = fopen($file, "r");
  if ($handle) {
    while (!feof($handle)) {
      $buffer = fgets($handle, 4096);
      if (strpos($buffer, "'" . $var . "'") == 7) {
        $pos1 = strpos($buffer, '"');
        $pos2 = strrpos($buffer, '"');
        $newbuffer .= substr_replace($buffer, $value . "\"", $pos1 + 1, $pos2 - ($pos1));
      } else {
        $newbuffer .= $buffer;
      }
    }
    fclose($handle);
    $handle = fopen($file, "w");
    fwrite($handle, $newbuffer);
    fclose($handle);
  }
}
