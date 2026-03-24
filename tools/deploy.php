<?php
declare(strict_types=1);

/**
 * TeamCal Neo 5 Deploy Script
 * Deploys the dist/ folder contents to the IONOS demo server via SFTP.
 *
 * Usage:
 *   composer deploy           Deploy dist/ to remote server
 *
 * Requires: phpseclib/phpseclib ^3.0 (dev dependency)
 * Config:   .env.deploy in project root
 */

use phpseclib3\Net\SFTP;

require_once __DIR__ . '/../vendor/autoload.php';

// ANSI Color Constants
define('CLR_RED', "\033[0;31m");
define('CLR_GRN', "\033[0;32m");
define('CLR_YLW', "\033[1;33m");
define('CLR_BLU', "\033[0;34m");
define('CLR_CYN', "\033[0;36m");
define('CLR_RST', "\033[0m");

$root    = dirname(__DIR__);
$dist    = $root . '/dist';

// ---------------------------------------------------------------------------
// Header
// ---------------------------------------------------------------------------
$composerJson = json_decode((string) file_get_contents($root . '/composer.json'), true);
$version      = $composerJson['version'] ?? '?';
$build        = $composerJson['build'] ?? '?';

echo CLR_CYN . "\n\n===============================================================================\n" . CLR_RST;
echo CLR_CYN . "TeamCal Neo Deploy System\n" . CLR_RST;
echo CLR_CYN . "Version: $version | Build: $build\n" . CLR_RST;
echo CLR_CYN . "===============================================================================\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// Phase 1 – Load .env.deploy
// ---------------------------------------------------------------------------
echo CLR_YLW . "-------------------------------------------------------------------------------\nPhase 1: Loading deploy configuration\n-------------------------------------------------------------------------------\n" . CLR_RST;

$envFile = $root . '/.env.deploy';
if (!file_exists($envFile)) {
  echo CLR_RED . "\n[ERROR] .env.deploy not found. Create it from .env.deploy.example.\n\n" . CLR_RST;
  exit(1);
}

$env = [];
foreach (file($envFile) as $line) {
  $line = trim($line);
  if ($line === '' || str_starts_with($line, '#')) {
    continue;
  }
  // Strip inline comments
  $line = preg_replace('/#.*$/', '', $line);
  if (str_contains($line, '=')) {
    [$key, $val] = explode('=', $line, 2);
    $env[trim($key)] = trim($val);
  }
}

$required = ['DEPLOY_HOST', 'DEPLOY_USER', 'DEPLOY_PASS', 'DEPLOY_PORT', 'DEPLOY_REMOTE_PATH', 'DEPLOY_PROTOCOL'];
foreach ($required as $key) {
  if (empty($env[$key])) {
    echo CLR_RED . "\n[ERROR] Missing required key '$key' in .env.deploy.\n\n" . CLR_RST;
    exit(1);
  }
}

$host       = $env['DEPLOY_HOST'];
$user       = $env['DEPLOY_USER'];
$pass       = $env['DEPLOY_PASS'];
$port       = (int) $env['DEPLOY_PORT'];
$remotePath = rtrim($env['DEPLOY_REMOTE_PATH'], '/');
$protocol   = strtolower($env['DEPLOY_PROTOCOL']);

echo CLR_GRN . "\n[OK] Config loaded.\n" . CLR_RST;
echo CLR_CYN . "     Host:     $host:$port\n" . CLR_RST;
echo CLR_CYN . "     User:     $user\n" . CLR_RST;
echo CLR_CYN . "     Protocol: $protocol\n" . CLR_RST;
echo CLR_CYN . "     Remote:   $remotePath\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// Phase 2 – Pre-flight checks
// ---------------------------------------------------------------------------
echo CLR_YLW . "-------------------------------------------------------------------------------\nPhase 2: Pre-flight checks\n-------------------------------------------------------------------------------\n" . CLR_RST;

if (!is_dir($dist)) {
  echo CLR_RED . "\n[ERROR] dist/ folder does not exist. Run 'composer build' first.\n\n" . CLR_RST;
  exit(1);
}
echo CLR_GRN . "\n[OK] dist/ folder found.\n" . CLR_RST;

$distConfig = $dist . '/config/config.app.php';
if (!file_exists($distConfig)) {
  echo CLR_RED . "\n[ERROR] dist/config/config.app.php not found.\n\n" . CLR_RST;
  exit(1);
}

$distConfigContent = (string) file_get_contents($distConfig);
if (!preg_match("/define\s*\(\s*'APP_INSTALLED'\s*,\s*\"0\"\s*\)/", $distConfigContent)) {
  echo CLR_RED . "\n[ERROR] dist/config/config.app.php does not have APP_INSTALLED = \"0\".\n       This does not look like a clean build. Aborting.\n\n" . CLR_RST;
  exit(1);
}
echo CLR_GRN . "[OK] dist/config/config.app.php has APP_INSTALLED = \"0\" (clean build confirmed).\n" . CLR_RST;

echo CLR_YLW . "\nDeploying v$version (build $build) to:\n  $protocol://$host:$port$remotePath\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// Phase 4 – Prepare modified config for upload
// ---------------------------------------------------------------------------
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 4: Preparing deploy copy of config.app.php\n-------------------------------------------------------------------------------\n" . CLR_RST;

$deployConfigContent = preg_replace(
  "/define\s*\(\s*'APP_INSTALLED'\s*,\s*\"0\"\s*\)/",
  "define('APP_INSTALLED', \"1\")",
  $distConfigContent
);
echo CLR_GRN . "\n[OK] APP_INSTALLED set to \"1\" for deployment.\n" . CLR_RST;

// ---------------------------------------------------------------------------
// Phase 5 – Connect via SFTP
// ---------------------------------------------------------------------------
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 5: Connecting via SFTP\n-------------------------------------------------------------------------------\n" . CLR_RST;

if ($protocol !== 'sftp') {
  echo CLR_RED . "\n[ERROR] Only 'sftp' protocol is currently supported. Got: '$protocol'.\n\n" . CLR_RST;
  exit(1);
}

$sftp = new SFTP($host, $port);
if (!$sftp->login($user, $pass)) {
  echo CLR_RED . "\n[ERROR] SFTP login failed. Check credentials in .env.deploy.\n\n" . CLR_RST;
  exit(1);
}
echo CLR_GRN . "\n[OK] Connected to $host:$port.\n" . CLR_RST;

// ---------------------------------------------------------------------------
// Phase 6 – Upload
// ---------------------------------------------------------------------------
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 6: Uploading files\n-------------------------------------------------------------------------------\n" . CLR_RST;

// Count total uploadable files first so we can show a progress bar
echo CLR_CYN . "\n>>> Scanning dist/ folder...\n" . CLR_RST;
$totalFiles = countFiles($dist, $dist);
echo CLR_CYN . "    Found $totalFiles file(s) to process.\n\n" . CLR_RST;

$uploaded = 0;
$skipped  = 0;
$errors   = 0;
$current  = 0;
$start    = microtime(true);

uploadDirectory($dist, $remotePath, $sftp, $dist, $deployConfigContent, $uploaded, $skipped, $errors, $current, $totalFiles);

// Clear the progress line and move to the next line
echo "\n";

// ---------------------------------------------------------------------------
// Phase 7 – Report
// ---------------------------------------------------------------------------
$elapsed = round(microtime(true) - $start, 1);
echo CLR_YLW . "\n===============================================================================\n" . CLR_RST;
echo CLR_GRN . "Deploy complete in {$elapsed}s\n" . CLR_RST;
echo CLR_CYN . "  Uploaded: $uploaded file(s)\n" . CLR_RST;
echo CLR_CYN . "  Skipped:  $skipped file(s)\n" . CLR_RST;
if ($errors > 0) {
  echo CLR_RED . "  Errors:   $errors file(s)\n" . CLR_RST;
}
else {
  echo CLR_CYN . "  Errors:   0\n" . CLR_RST;
}

// Log deployment
$logFile  = $root . '/_resources/deploy.log';
$logEntry = date('Y-m-d H:i:s') . " | v$version | build $build | uploaded=$uploaded skipped=$skipped errors=$errors | {$elapsed}s\n";
file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
echo CLR_CYN . "\n  Logged to _resources/deploy.log\n" . CLR_RST;
echo CLR_YLW . "===============================================================================\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

//---------------------------------------------------------------------------
/**
 * Count all files in a directory that will be uploaded (not skipped).
 *
 * @param string $localDir  Absolute local directory to scan
 * @param string $distRoot  Absolute path to dist/ root
 *
 * @return int Total number of uploadable files
 */
function countFiles(string $localDir, string $distRoot): int {
  $count = 0;
  $items = array_diff((array) scandir($localDir), ['.', '..']);
  foreach ($items as $item) {
    $localPath = $localDir . '/' . $item;
    if (is_dir($localPath)) {
      $count += countFiles($localPath, $distRoot);
    }
    else {
      $count++; // Count all files (skipped ones still advance the bar)
    }
  }
  return $count;
}

//---------------------------------------------------------------------------
/**
 * Render an in-place progress bar to STDOUT.
 *
 * @param int    $current   Files processed so far
 * @param int    $total     Total files to process
 * @param string $label     Current file path to show beneath the bar
 * @param bool   $success   Whether the last file succeeded (affects bar color)
 *
 * @return void
 */
function renderProgressBar(int $current, int $total, string $label, bool $success): void {
  $barWidth  = 40;
  $pct       = $total > 0 ? (int) round($current / $total * 100) : 100;
  $filled    = $total > 0 ? (int) round($current / $total * $barWidth) : $barWidth;
  $empty     = $barWidth - $filled;
  $barColor  = $success ? CLR_GRN : CLR_RED;
  $bar       = $barColor . str_repeat('=', max(0, $filled - 1)) . ($filled > 0 ? '>' : '') . CLR_RST . str_repeat(' ', $empty);
  $counter   = CLR_CYN . "$current/$total" . CLR_RST;
  $percent   = CLR_YLW . "($pct%)" . CLR_RST;

  // Truncate label so the line stays on one terminal line (max 60 chars)
  $maxLabel = 60;
  if (strlen($label) > $maxLabel) {
    $label = '...' . substr($label, -($maxLabel - 3));
  }
  $labelPadded = str_pad($label, $maxLabel);

  // \r moves cursor to start of line; \033[K clears to end of line
  echo "\r\033[K  [{$bar}] {$counter} {$percent}  {$labelPadded}";
}

//---------------------------------------------------------------------------
/**
 * Recursively upload a local directory to the remote SFTP path.
 *
 * @param string $localDir         Absolute local directory to upload
 * @param string $remoteDir        Absolute remote target directory
 * @param SFTP   $sftp             Authenticated SFTP connection
 * @param string $distRoot         Root dist/ path (for config.app.php detection)
 * @param string $deployConfig     Modified config.app.php content for deploy
 * @param int    &$uploaded        Counter: files uploaded
 * @param int    &$skipped         Counter: files skipped
 * @param int    &$errors          Counter: upload errors
 * @param int    &$current         Counter: total files processed (for progress bar)
 * @param int    $total            Total file count (for progress bar)
 *
 * @return void
 */
function uploadDirectory(
  string $localDir,
  string $remoteDir,
  SFTP $sftp,
  string $distRoot,
  string $deployConfig,
  int &$uploaded,
  int &$skipped,
  int &$errors,
  int &$current,
  int $total
): void {
  // Ensure remote directory exists
  if (!$sftp->stat($remoteDir)) {
    $sftp->mkdir($remoteDir, -1, true);
  }

  $items = array_diff((array) scandir($localDir), ['.', '..']);
  foreach ($items as $item) {
    $localPath  = $localDir . '/' . $item;
    $remotePath = $remoteDir . '/' . $item;

    if (is_dir($localPath)) {
      uploadDirectory($localPath, $remotePath, $sftp, $distRoot, $deployConfig, $uploaded, $skipped, $errors, $current, $total);
      continue;
    }

    $current++;
    $label = relativePath($localPath, $distRoot);

    // Skip rules
    if (shouldSkip($localPath, $distRoot)) {
      $skipped++;
      renderProgressBar($current, $total, '[SKIP] ' . $label, true);
      continue;
    }

    // Use modified config content for config.app.php
    $relPath = str_replace('\\', '/', substr($localPath, strlen($distRoot) + 1));
    if ($relPath === 'config/config.app.php') {
      $success = $sftp->put($remotePath, $deployConfig);
    }
    else {
      $success = $sftp->put($remotePath, $localPath, SFTP::SOURCE_LOCAL_FILE);
    }

    if ($success) {
      $uploaded++;
      renderProgressBar($current, $total, $label, true);
    }
    else {
      $errors++;
      renderProgressBar($current, $total, '[FAIL] ' . $label, false);
    }
  }
}

//---------------------------------------------------------------------------
/**
 * Determine whether a local file should be skipped during upload.
 *
 * @param string $localPath Absolute local file path
 * @param string $distRoot  Absolute path to dist/ root
 *
 * @return bool True if the file should be skipped
 */
function shouldSkip(string $localPath, string $distRoot): bool {
  $rel = str_replace('\\', '/', substr($localPath, strlen($distRoot) + 1));

  // Skip ZIP archives
  if (str_ends_with($localPath, '.zip')) {
    return true;
  }

  // Skip installation.php
  if ($rel === 'installation.php') {
    return true;
  }

  // Skip .gitkeep files
  if (basename($localPath) === '.gitkeep') {
    return true;
  }

  return false;
}

//---------------------------------------------------------------------------
/**
 * Return a path relative to the dist/ root for display purposes.
 *
 * @param string $absolute Absolute file path
 * @param string $distRoot Absolute path to dist/ root
 *
 * @return string Relative path
 */
function relativePath(string $absolute, string $distRoot): string {
  return str_replace('\\', '/', substr($absolute, strlen($distRoot) + 1));
}
