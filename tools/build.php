<?php
declare(strict_types=1);

/**
 * TeamCal Neo 5 Build Script
 * prepares the distribution package.
 */

$root = dirname(__DIR__);
$dist = $root . '/dist';

// ANSI Color Constants
define('CLR_RED', "\033[0;31m");
define('CLR_GRN', "\033[0;32m");
define('CLR_YLW', "\033[1;33m");
define('CLR_BLU', "\033[0;34m");
define('CLR_CYN', "\033[0;36m");
define('CLR_RST', "\033[0m");

// Read version from composer.json
$composerJson = json_decode(file_get_contents($root . '/composer.json'), true);
$version      = $composerJson['version'] ?? '5.0.0';
$buildDate    = date('Y-m-d');

echo CLR_CYN . "\n\n===============================================================================\n" . CLR_RST;
echo CLR_CYN . "TeamCal Neo Build System\n" . CLR_RST;
echo CLR_CYN . "===============================================================================\n\n" . CLR_RST;

// 1. Pre-build Checks
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 1: Pre-build Checks\n-------------------------------------------------------------------------------\n" . CLR_RST;

$steps = [
  'Static Analysis' => 'composer phpstan',
  'Unit Tests'      => 'composer phpunit',
  'Documentation'   => 'composer docs',
  'Minification'    => 'composer minify',
  'SBOM Generation' => 'composer sbom',
];

foreach ($steps as $name => $cmd) {
  echo CLR_CYN . "\n>>> Running $name ($cmd)...\n\n" . CLR_RST;
  passthru($cmd, $result);
  if ($result !== 0) {
    echo CLR_RED . "\n[ERROR] $name failed with exit code $result. Build aborted.\n\n" . CLR_RST;
    exit(1);
  }
}
echo CLR_GRN . "\n[SUCCESS] Pre-build checks passed.\n\n" . CLR_RST;

// 2. Prepare Dist Folder
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 2: Preparing /dist folder\n-------------------------------------------------------------------------------\n" . CLR_RST;
if (is_dir($dist)) {
  echo CLR_CYN . "\n>>> Cleaning existing /dist folder...\n" . CLR_RST;
  deleteDirectory($dist);
}
mkdir($dist, 0755, true);

// 3. Assemble Files
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 3: Assembling files\n-------------------------------------------------------------------------------\n" . CLR_RST;

$foldersToCopy = [
  'cache',
  'config',
  'doc',
  'public',
  'resources',
  'sql',
  'src',
  'temp',
  'views',
];

$filesToCopy = [
  'composer.json',
  'composer.lock',
  'index.php',
  'LICENSE',
  'README.md',
  'installation.org.php',
  '.env.example',
];

foreach ($foldersToCopy as $folder) {
  echo CLR_CYN . "\n>>> Copying $folder/...\n" . CLR_RST;
  copyRecursive($root . '/' . $folder, $dist . '/' . $folder);
}

foreach ($filesToCopy as $file) {
  echo CLR_CYN . "\n>>> Copying $file...\n" . CLR_RST;
  copy($root . '/' . $file, $dist . '/' . $file);
}

// 4. Distribution adjustments
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 4: Distribution adjustments\n-------------------------------------------------------------------------------\n" . CLR_RST;

// Rename installation script
echo CLR_CYN . "\n>>> Renaming installation.org.php to installation.php...\n" . CLR_RST;
rename($dist . '/installation.org.php', $dist . '/installation.php');

// Update config/config.app.php for distribution
echo CLR_CYN . "\n>>> Updating config/config.app.php for distribution...\n" . CLR_RST;
$configFile    = $dist . '/config/config.app.php';
$configContent = file_get_contents($configFile);

$replacements = [
  '/\bdefine\s*\(\s*[\'"]APP_INSTALLED[\'"]\s*,\s*[\'"]\d+[\'"]\s*\)/' => "define('APP_INSTALLED', \"0\")",
  '/\bdefine\s*\(\s*[\'"]APP_VER[\'"]\s*,\s*[\'"][^\'"]+[\'"]\s*\)/'   => "define('APP_VER', \"$version\")",
  '/\bdefine\s*\(\s*[\'"]APP_DATE[\'"]\s*,\s*[\'"][^\'"]+[\'"]\s*\)/'  => "define('APP_DATE', \"$buildDate\")",
];

foreach ($replacements as $pattern => $replacement) {
  $configContent = preg_replace($pattern, $replacement, $configContent);
}
file_put_contents($configFile, $configContent);

// Wipe cache and temp contents (but keep folders)
echo CLR_CYN . "\n>>> Wiping cache and temp contents...\n" . CLR_RST;
$wipeDirs = ['cache', 'temp/twig'];
foreach ($wipeDirs as $wd) {
  if (is_dir($dist . '/' . $wd)) {
    deleteDirectoryContents($dist . '/' . $wd);
    file_put_contents($dist . '/' . $wd . '/.gitkeep', '');
  }
}

// 5. Production Dependencies
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 5: Installing production dependencies\n-------------------------------------------------------------------------------\n\n" . CLR_RST;
$currentDir = getcwd();
chdir($dist);
passthru('composer install --no-dev --optimize-autoloader', $result);
chdir($currentDir);

if ($result !== 0) {
  echo CLR_RED . "\n[ERROR] Production composer install failed. Build aborted.\n\n" . CLR_RST;
  exit(1);
}

// 6. Packaging
echo CLR_YLW . "\n-------------------------------------------------------------------------------\nPhase 6: Packaging\n-------------------------------------------------------------------------------\n" . CLR_RST;
$zipFile = $root . "/tcneo-v{$version}.zip";
if (file_exists($zipFile)) {
  unlink($zipFile);
}

echo CLR_CYN . "\n>>> Creating ZIP archive: $zipFile\n" . CLR_RST;
if (extension_loaded('zip')) {
  $zip = new ZipArchive();
  if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($dist),
      RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
      if (!$file->isDir()) {
        $filePath     = $file->getRealPath();
        $relativePath = str_replace('\\', '/', substr($filePath, strlen($dist) + 1));
        $zip->addFile($filePath, $relativePath);
      }
    }
    $zip->close();
    $distZipFile = $dist . "/tcneo-v{$version}.zip";
    if (file_exists($distZipFile)) {
      unlink($distZipFile);
    }
    if (rename($zipFile, $distZipFile)) {
      echo CLR_GRN . "\n[SUCCESS] Successfully created $distZipFile\n" . CLR_RST;

      // Copy to _resources/_archive
      $archiveDir = $root . '/_resources/_archive';
      if (!is_dir($archiveDir)) {
        mkdir($archiveDir, 0755, true);
      }

      $vParts         = explode('.', $version);
      $vMajor         = str_pad($vParts[0] ?? '0', 2, '0', STR_PAD_LEFT);
      $vMinor         = str_pad($vParts[1] ?? '0', 2, '0', STR_PAD_LEFT);
      $vPatch         = str_pad($vParts[2] ?? '0', 2, '0', STR_PAD_LEFT);
      $archiveZipFile = $archiveDir . "/tcneo_{$vMajor}{$vMinor}{$vPatch}.zip";

      echo CLR_CYN . "\n>>> Copying archive to $archiveZipFile...\n" . CLR_RST;
      if (copy($distZipFile, $archiveZipFile)) {
        echo CLR_GRN . "\n[SUCCESS] Successfully copied build to $archiveZipFile\n" . CLR_RST;
      }
      else {
        echo CLR_RED . "\n[ERROR] Failed to copy build to $archiveZipFile\n" . CLR_RST;
      }
    }
    else {
      echo CLR_RED . "\n[ERROR] Successfully created ZIP but failed to move it to $dist\n" . CLR_RST;
    }
  }
  else {
    echo CLR_RED . "\n[ERROR] Failed to create ZIP archive.\n\n" . CLR_RST;
  }
}
else {
  echo CLR_YLW . "\n[WARNING] Zip extension not loaded. Skipping ZIP creation.\n\n" . CLR_RST;
}

echo CLR_GRN . "\n[SUCCESS] Build sequence completed successfully.\n\n" . CLR_RST;
echo CLR_YLW . "===============================================================================\n\n" . CLR_RST;

//---------------------------------------------------------------------------
/**
 * Recursive directory deletion
 *
 * @param string $dir Path to the directory to delete
 *
 * @return void
 */
function deleteDirectory(string $dir): void {
  if (!file_exists($dir))
    return;
  $files = array_diff(scandir($dir), ['.', '..']);
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? deleteDirectory("$dir/$file") : unlink("$dir/$file");
  }
  rmdir($dir);
}

//---------------------------------------------------------------------------
/**
 * Delete only contents of a directory
 *
 * @param string $dir Path to the directory to wipe
 *
 * @return void
 */
function deleteDirectoryContents(string $dir): void {
  if (!file_exists($dir))
    return;
  $files = array_diff(scandir($dir), ['.', '..']);
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? deleteDirectory("$dir/$file") : unlink("$dir/$file");
  }
}

//---------------------------------------------------------------------------
/**
 * Recursive directory copy
 *
 * @param string $src Source path
 * @param string $dst Destination path
 *
 * @return void
 */
function copyRecursive(string $src, string $dst): void {
  $dir = opendir($src);
  @mkdir($dst);
  while (false !== ($file = readdir($dir))) {
    if (($file != '.') && ($file != '..')) {
      if (is_dir($src . '/' . $file)) {
        copyRecursive($src . '/' . $file, $dst . '/' . $file);
      }
      else {
        copy($src . '/' . $file, $dst . '/' . $file);
      }
    }
  }
  closedir($dir);
}
