<?php
declare(strict_types=1);

use phpseclib3\Net\SFTP;

/**
 * TeamCal Neo Version File Updater
 * Updates lewe.com/support/version/tcneo.js on the hosting server via SFTP.
 *
 * Usage:
 *   php tools/update_version.php 5.0.4
 *   composer update-version -- 5.0.4
 *
 * @author     George Lewe <george@lewe.com>
 * @copyright  Copyright (c) 2014-2026 by George Lewe
 * @link       https://www.lewe.com
 *
 * @package    TeamCal Neo
 * @subpackage Tools
 * @since      5.0.0
 */

$root = dirname(__DIR__);

require_once $root . '/vendor/autoload.php';

// ANSI Color Constants
define('CLR_RED', "\033[0;31m");
define('CLR_GRN', "\033[0;32m");
define('CLR_YLW', "\033[1;33m");
define('CLR_CYN', "\033[0;36m");
define('CLR_RST', "\033[0m");

echo CLR_CYN . "\n\n===============================================================================\n" . CLR_RST;
echo CLR_CYN . "TeamCal Neo – Update Version File on Hosting Server\n" . CLR_RST;
echo CLR_CYN . "===============================================================================\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// 1. Determine version
// ---------------------------------------------------------------------------
$version = $argv[1] ?? '';

if (empty($version)) {
  echo "Enter version number (e.g. 5.0.4): ";
  $handle  = fopen("php://stdin", "r");
  $version = trim((string) fgets($handle));
  fclose($handle);
}

if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
  echo CLR_RED . "[ERROR] Invalid version format '$version'. Expected semver, e.g. 5.0.4\n\n" . CLR_RST;
  exit(1);
}

echo "Version to set: " . CLR_GRN . "v$version" . CLR_RST . "\n\n";

// ---------------------------------------------------------------------------
// 2. Load .env.deploy
// ---------------------------------------------------------------------------
echo CLR_YLW . ">>> Loading deploy configuration...\n" . CLR_RST;

$envFile = $root . '/.env.deploy';
if (!file_exists($envFile)) {
  echo CLR_RED . "[ERROR] .env.deploy not found. Create it from .env.deploy.example.\n\n" . CLR_RST;
  exit(1);
}

$env = [];
foreach (file($envFile) as $line) {
  $line = trim($line);
  if ($line === '' || str_starts_with($line, '#')) {
    continue;
  }
  $line = preg_replace('/#.*$/', '', $line);
  if (str_contains($line, '=')) {
    [$key, $val] = explode('=', $line, 2);
    $env[trim($key)] = trim($val);
  }
}

foreach (['DEPLOY_HOST', 'DEPLOY_USER', 'DEPLOY_PASS', 'DEPLOY_PORT'] as $key) {
  if (empty($env[$key])) {
    echo CLR_RED . "[ERROR] Missing required key '$key' in .env.deploy.\n\n" . CLR_RST;
    exit(1);
  }
}

echo CLR_GRN . "[OK] Config loaded.\n" . CLR_RST;
echo CLR_CYN . "     Host: {$env['DEPLOY_HOST']}:{$env['DEPLOY_PORT']}\n" . CLR_RST;
echo CLR_CYN . "     User: {$env['DEPLOY_USER']}\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// 3. Connect via SFTP
// ---------------------------------------------------------------------------
echo CLR_YLW . ">>> Connecting via SFTP...\n" . CLR_RST;

$sftp = new SFTP($env['DEPLOY_HOST'], (int) $env['DEPLOY_PORT']);
if (!$sftp->login($env['DEPLOY_USER'], $env['DEPLOY_PASS'])) {
  echo CLR_RED . "[ERROR] SFTP login failed. Check credentials in .env.deploy.\n\n" . CLR_RST;
  exit(1);
}

echo CLR_GRN . "[OK] Connected.\n\n" . CLR_RST;

// ---------------------------------------------------------------------------
// 4. Download, patch, upload
// ---------------------------------------------------------------------------
$versionFilePath = 'lewe.com/support/version/tcneo.js';

echo CLR_YLW . ">>> Downloading $versionFilePath...\n" . CLR_RST;
$remoteContent = $sftp->get($versionFilePath);
if ($remoteContent === false) {
  echo CLR_RED . "[ERROR] Could not read remote file: $versionFilePath\n\n" . CLR_RST;
  exit(1);
}
echo CLR_GRN . "[OK] File downloaded.\n\n" . CLR_RST;

$updatedContent = preg_replace(
  "/var latest_version = parseVersionString\\('[^']*'\\);/",
  "var latest_version = parseVersionString('$version');",
  $remoteContent
);

if ($updatedContent === $remoteContent) {
  echo CLR_YLW . "[WARN] Version line not found or already set to v$version. Nothing to do.\n\n" . CLR_RST;
  exit(0);
}

echo CLR_YLW . ">>> Uploading updated file...\n" . CLR_RST;
if (!$sftp->put($versionFilePath, $updatedContent)) {
  echo CLR_RED . "[ERROR] Could not write updated file to: $versionFilePath\n\n" . CLR_RST;
  exit(1);
}

echo CLR_GRN . "[SUCCESS] $versionFilePath updated to v$version on hosting server.\n\n" . CLR_RST;
