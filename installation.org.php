<?php
declare(strict_types=1);

/**
 * Installation
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */

/**
 * Dedicated exception for installation and configuration errors
 */
class InstallationException extends \Exception {}

define('VALID_ROOT', 1);
define('WEBSITE_ROOT', __DIR__);

require_once WEBSITE_ROOT . '/config/config.app.php';
require_once WEBSITE_ROOT . '/src/Helpers/global.helper.php';

//-----------------------------------------------------------------------------
/**
 * Reads a configuration value from a PHP config file.
 *
 * @param string $var  The variable name to read
 * @param string $file The absolute path to the config file
 *
 * @return string The value of the configuration variable
 */
function getInstallConfig(string $var = '', string $file = ''): string {
  if (empty($var) || empty($file) || !file_exists($file) || !is_readable($file)) {
    return '';
  }

  $content = file_get_contents($file);
  if ($content === false) {
    return '';
  }

  $patterns = [
    '/\$CONF\[\s*[\'"]' . preg_quote($var, '/') . '[\'"]\s*\]\s*=\s*[\'"]([^\'"]*)[\'"]\s*;/m',
    '/\$' . preg_quote($var, '/') . '\s*=\s*[\'"]([^\'"]*)[\'"]\s*;/m',
    '/define\s*\(\s*[\'"]' . preg_quote($var, '/') . '[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]\s*\)/m',
  ];

  foreach ($patterns as $pattern) {
    if (preg_match($pattern, $content, $matches)) {
      return isset($matches[1]) ? trim(htmlspecialchars_decode($matches[1], ENT_QUOTES)) : '';
    }
  }

  return '';
}

//-----------------------------------------------------------------------------
/**
 * Writes a define() constant value into a config file with enhanced safety and performance.
 *
 * @param string $var          Constant name to update
 * @param string $value        New value to assign
 * @param string $file         Path to the config file
 * @param bool   $createBackup Whether to create a backup before modification
 *
 * @return bool True on success, false on failure
 */
function writeDef(string $var = '', string $value = '', string $file = '', bool $createBackup = true): bool {
  // Input validation
  if (empty($var)) {
    error_log("writeDef: Constant name cannot be empty");
    return false;
  }

  if (empty($file)) {
    error_log("writeDef: File path cannot be empty");
    return false;
  }

  // Validate constant name (prevent code injection)
  if (!preg_match('/^[a-zA-Z_]\w*$/', $var)) {
    error_log("writeDef: Invalid constant name format: {$var}");
    return false;
  }

  // Check if file exists and is readable
  if (!file_exists($file)) {
    error_log("writeDef: Config file does not exist: {$file}");
    return false;
  }

  if (!is_readable($file)) {
    error_log("writeDef: Config file is not readable: {$file}");
    return false;
  }

  if (!is_writable($file)) {
    error_log("writeDef: Config file is not writable: {$file}");
    return false;
  }

  // Create backup if requested
  if ($createBackup) {
    $backupFile = $file . '.backup.' . date('Y-m-d_H-i-s');
    if (!copy($file, $backupFile)) {
      error_log("writeDef: Failed to create backup: {$backupFile}");
      return false;
    }
  }

  // Sanitize value (prevent code injection)
  $sanitizedValue = addslashes($value);

  // Use file locking to prevent concurrent modifications
  $lockFile   = $file . '.lock';
  $lockHandle = fopen($lockFile, 'w');

  if (!$lockHandle) {
    error_log("writeDef: Failed to create lock file: {$lockFile}");
    return false;
  }

  if (!flock($lockHandle, LOCK_EX)) {
    fclose($lockHandle);
    unlink($lockFile);
    error_log("writeDef: Failed to acquire file lock");
    return false;
  }

  try {
    // Read file content
    $content = file_get_contents($file);
    if ($content === false) {
      throw new InstallationException("Failed to read config file");
    }

    // Pattern to match define() statements
    // Matches: define('CONSTANT', 'value');
    $pattern = '/(define\s*\(\s*[\'"]\s*' . preg_quote($var, '/') . '\s*[\'"]\s*,\s*[\'"])([^\'"]*)([\'"][\s]*\);?)/';

    // Check if constant exists in config
    if (preg_match($pattern, $content)) {
      // Replace existing value
      $newContent = preg_replace($pattern, '${1}' . $sanitizedValue . '${3}', $content);
    }
    else {
      // Constant not found - this might indicate a problem
      error_log("writeDef: Constant '{$var}' not found in config file");
      return false;
    }

    // Validate that replacement occurred
    if ($newContent === null || $newContent === $content) {
      throw new InstallationException("Failed to update define value");
    }

    // Write updated content atomically
    $tempFile = $file . '.tmp.' . uniqid();

    if (file_put_contents($tempFile, $newContent, LOCK_EX) === false) {
      throw new InstallationException("Failed to write temporary file");
    }

    // Atomic rename
    if (!rename($tempFile, $file)) {
      unlink($tempFile);
      throw new InstallationException("Failed to replace config file");
    }

    // Success
    return true;
  } catch (InstallationException $e) {
    error_log("writeDef: " . $e->getMessage());

    // Restore from backup if available
    if ($createBackup && isset($backupFile) && file_exists($backupFile)) {
      copy($backupFile, $file);
      error_log("writeDef: Restored from backup due to error");
    }

    // Clean up temporary file if it exists
    if (isset($tempFile) && file_exists($tempFile)) {
      unlink($tempFile);
    }

    return false;
  } finally {
    // Release lock and cleanup
    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);
    unlink($lockFile);
  }
}

//-----------------------------------------------------------------------------
/**
 * Writes a $CONF variable value into a config file with enhanced safety and performance.
 *
 * @param string $var          Variable name to update
 * @param string $value        New value to assign
 * @param string $file         Path to the config file
 * @param bool   $createBackup Whether to create a backup before modification
 *
 * @return bool True on success, false on failure
 */
function writeConfig(string $var = '', string $value = '', string $file = '', bool $createBackup = true): bool {
  // Input validation
  if (empty($var)) {
    error_log("writeConfig: Variable name cannot be empty");
    return false;
  }

  if (empty($file)) {
    error_log("writeConfig: File path cannot be empty");
    return false;
  }

  // Validate variable name (prevent code injection)
  if (!preg_match('/^[a-zA-Z_]\w*$/', $var)) {
    error_log("writeConfig: Invalid variable name format: {$var}");
    return false;
  }

  // Check if file exists and is readable
  if (!file_exists($file)) {
    error_log("writeConfig: Config file does not exist: {$file}");
    return false;
  }

  if (!is_readable($file)) {
    error_log("writeConfig: Config file is not readable: {$file}");
    return false;
  }

  if (!is_writable($file)) {
    error_log("writeConfig: Config file is not writable: {$file}");
    return false;
  }

  // Create backup if requested
  if ($createBackup) {
    $backupFile = $file . '.backup.' . date('Y-m-d_H-i-s');
    if (!copy($file, $backupFile)) {
      error_log("writeConfig: Failed to create backup: {$backupFile}");
      return false;
    }
  }

  // Sanitize value (prevent code injection)
  $sanitizedValue = addslashes($value);

  // Use file locking to prevent concurrent modifications
  $lockFile   = $file . '.lock';
  $lockHandle = fopen($lockFile, 'w');

  if (!$lockHandle) {
    error_log("writeConfig: Failed to create lock file: {$lockFile}");
    return false;
  }

  if (!flock($lockHandle, LOCK_EX)) {
    fclose($lockHandle);
    unlink($lockFile);
    error_log("writeConfig: Failed to acquire file lock");
    return false;
  }

  try {
    // Read file content
    $content = file_get_contents($file);
    if ($content === false) {
      throw new Exception("Failed to read config file");
    }

    // Pattern to match $CONF['key'] = "value";
    $pattern = '/(\$CONF\[\s*[\'"]' . preg_quote($var, '/') . '[\'"]\s*\]\s*=\s*[\'"])([^\'"]*)([\'"]\s*;)/m';

    // Check if variable exists in config
    if (preg_match($pattern, $content)) {
      // Replace existing value
      $newContent = preg_replace($pattern, '${1}' . $sanitizedValue . '${3}', $content);
    }
    else {
      // Variable not found - this might indicate a problem
      error_log("writeConfig: Variable '{$var}' not found in config file");
      return false;
    }

    // Validate that replacement occurred
    if ($newContent === null || $newContent === $content) {
      throw new InstallationException("Failed to update config value");
    }

    // Write updated content atomically
    $tempFile = $file . '.tmp.' . uniqid();

    if (file_put_contents($tempFile, $newContent, LOCK_EX) === false) {
      throw new InstallationException("Failed to write temporary file");
    }

    // Atomic rename
    if (!rename($tempFile, $file)) {
      unlink($tempFile);
      throw new InstallationException("Failed to replace config file");
    }

    // Success
    return true;
  } catch (InstallationException $e) {
    error_log("writeConfig: " . $e->getMessage());

    // Restore from backup if available
    if ($createBackup && isset($backupFile) && file_exists($backupFile)) {
      copy($backupFile, $file);
      error_log("writeConfig: Restored from backup due to error");
    }

    // Clean up temporary file if it exists
    if (isset($tempFile) && file_exists($tempFile)) {
      unlink($tempFile);
    }

    return false;
  } finally {
    // Release lock and cleanup
    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);
    unlink($lockFile);
  }
}

//=============================================================================
//
// LANGUAGE
//
$LANG['btn_install']             = 'Install';
$LANG['btn_test']                = 'Test Database';
$LANG['inst_db_error']           = 'An error occured trying to connect to the database.';
$LANG['inst_dbData']             = '<span class="text-bold text-danger">*&nbsp;</span>Sample data';
$LANG['inst_dbData_comment']     = 'Check whether you want a set of sample data loaded or not.<br>
  - Select "Basic data" if you want to add core data without sample information.<br>
  - Select "Sample data" if you want to add core data plus sample data to the database.<br>
  - Select "Use existing data" if your database already exists and you want to use the existing data.<br>
  <br>
  <i><strong>Attention!</strong> "Use existing data" only works if your existing data set is compatible with the version you are installing.<br>TeamCal Neo Basic is not compatible with TeamCal Neo!</i>';
$LANG['inst_dbData_basic']       = 'Basic data';
$LANG['inst_dbData_sample']      = 'Sample data';
$LANG['inst_dbData_none']        = 'Use existing data';
$LANG['inst_dbData_error']       = 'An error occured trying to load the sample data into the database.';
$LANG['inst_dbName']             = '<span class="text-bold text-danger">*&nbsp;</span>Database Name';
$LANG['inst_dbName_comment']     = 'Specify the name of the database. This needs to be an existing database.';
$LANG['inst_dbUser']             = '<span class="text-bold text-danger">*&nbsp;</span>Database User';
$LANG['inst_dbUser_comment']     = 'Specify the username to log in to your database.';
$LANG['inst_dbPassword']         = 'Database Password';
$LANG['inst_dbPassword_comment'] = 'Specify the password to log in to your database.';
$LANG['inst_dbPrefix']           = 'Database Table Prefix';
$LANG['inst_dbPrefix_comment']   = 'Specify a prefix for your database tables or leave empty for none. E.g. "tcneo_".';
$LANG['inst_dbServer']           = '<span class="text-bold text-danger">*&nbsp;</span>Database Server';
$LANG['inst_dbServer_comment']   = 'Specify the URL of the database server.';
$LANG['inst_dbPort']             = 'Database Port';
$LANG['inst_dbPort_comment']     = 'Specify the port of the database server (default: 3306).';
$LANG['inst_dbSocket']           = 'Database Socket';
$LANG['inst_dbSocket_comment']   = 'Specify the Unix socket for the database connection (overrides host and port).';
$LANG['inst_executed']           = 'Installation already executed';
$LANG['inst_executed_comment']   = 'The configuration file shows that the installation script was already executed for this instance.<br>
      For security reasons, if you want to run it again, you need to reset the flag in the application config file:<br>
      Look for <code>define(\'APP_INSTALLED\', "1");</code> in "config/config.app.php" and set the value to "0".<br>
      Otherwise, delete the installation script from the server. Then click the button below.<br><br><a class="btn btn-primary" href="index.php">Start</a>';
$LANG['inst_lic']                = '<span class="text-bold text-danger">*&nbsp;</span>License Agreement';
$LANG['inst_lic_comment']        = 'You must accept the license agreement if you want to use this application.';
$LANG['inst_lic_app']            = 'I accept the TeamCal Neo License';
$LANG['inst_lic_error']          = 'Before you can start the installation you must accept the license agreement!';
$LANG['inst_error']              = 'Installation Error';
$LANG['inst_congrats']           = 'Congratulations';
$LANG['inst_success']            = 'Installation Success';
$LANG['inst_success_comment']    = 'The installation was successful. Please delete the installation script from the server before you start.<br><br><a class="btn btn-primary" href="index.php">Start</a>';
$LANG['inst_update']             = 'Do not run for update';
$LANG['inst_update_comment']     = 'Do not run the installation script for updating TeamCal Neo. Instead, follow the instructions <a href="doc/Upgradeinfo.txt">here</a>.<br>
      If this is a fresh install, you can close this message in the upper right corner and continue below.';
$LANG['inst_warning']            = 'Installation Warning';

//-----------------------------------------------------------------------------
/**
 * Writes a variable value into a .env file.
 *
 * @param string $var          Variable name to update
 * @param string $value        New value to assign
 * @param string $file         Path to the env file
 *
 * @return bool True on success, false on failure
 */
function writeEnv(string $var, string $value, string $file = '.env'): bool {
  if (!file_exists($file)) {
    return false;
  }

  $content    = file_get_contents($file);
  $cleanValue = preg_replace('/^"|"$|^\'|\'$/', '', $value); // Removing existing quotes if any
  $cleanValue = '"' . str_replace('"', '\"', $cleanValue) . '"'; // Enclose in double quotes and escape internal quotes

  $pattern = "/^" . preg_quote($var, '/') . "=(.*)$/m";

  if (preg_match($pattern, $content)) {
    $content = preg_replace($pattern, "$var=$cleanValue", $content);
  }
  else {
    $content .= PHP_EOL . "$var=$cleanValue";
  }

  return (bool) file_put_contents($file, $content);
}

//=============================================================================
//
// VARIABLES
//
$installationExecuted = false;
$installationComplete = false;
$configAppFile        = "config/config.app.php";
$configDbFile         = "config/config.db.php";
$showAlert            = false;
$tabindex             = 1;

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {

  // Sanitize input
  $_POST = sanitize($_POST);

  // ,---------,
  // | Install |
  // '---------'
  if (isset($_POST['btn_install'])) {
    if (
      isset($_POST['chk_licApp']) &&
      (isset($_POST['txt_instDbServer']) && strlen($_POST['txt_instDbServer'])) &&
      (isset($_POST['txt_instDbName']) && strlen($_POST['txt_instDbName'])) &&
      (isset($_POST['txt_instDbUser']) && strlen($_POST['txt_instDbUser']))
    ) {
      // Write database info to config file
      writeConfig("db_server", $_POST['txt_instDbServer'], $configDbFile);
      writeConfig("db_port", $_POST['txt_instDbPort'] ?? '', $configDbFile);
      writeConfig("db_socket", $_POST['txt_instDbSocket'] ?? '', $configDbFile);
      writeConfig("db_name", $_POST['txt_instDbName'], $configDbFile);
      writeConfig("db_user", $_POST['txt_instDbUser'], $configDbFile);

      if (isset($_POST['txt_instDbPassword']) && strlen($_POST['txt_instDbPassword'])) {
        writeConfig("db_pass", $_POST['txt_instDbPassword'], $configDbFile);
      }

      if (isset($_POST['txt_instDbPrefix']) && strlen($_POST['txt_instDbPrefix'])) {
        writeConfig("db_table_prefix", $_POST['txt_instDbPrefix'], $configDbFile);
      }
      else {
        writeConfig("db_table_prefix", '', $configDbFile);
      }

      //
      // Update .env file
      //
      $envFile = '.env';
      if (!file_exists($envFile) && file_exists('.env.example')) {
        copy('.env.example', $envFile);
      }

      if (file_exists($envFile)) {
        writeEnv('DB_HOST', $_POST['txt_instDbServer'], $envFile);
        writeEnv('DB_PORT', $_POST['txt_instDbPort'] ?? '3306', $envFile);
        writeEnv('DB_SOCKET', $_POST['txt_instDbSocket'] ?? '', $envFile);
        writeEnv('DB_DATABASE', $_POST['txt_instDbName'], $envFile);
        writeEnv('DB_USERNAME', $_POST['txt_instDbUser'], $envFile);
        writeEnv('DB_PASSWORD', $_POST['txt_instDbPassword'] ?? '', $envFile);
      }

      // Connect to database
      $dberror = false;
      try {
        if (isset($_POST['txt_instDbSocket']) && strlen($_POST['txt_instDbSocket'])) {
          $dsn = 'mysql:unix_socket=' . $_POST['txt_instDbSocket'] . ';dbname=' . $_POST['txt_instDbName'] . ';charset=utf8';
        }
        else {
          $dsn = 'mysql:host=' . $_POST['txt_instDbServer'] . (isset($_POST['txt_instDbPort']) && strlen($_POST['txt_instDbPort']) ? ';port=' . $_POST['txt_instDbPort'] : '') . ';dbname=' . $_POST['txt_instDbName'] . ';charset=utf8';
        }
        $pdo = new PDO($dsn, $_POST['txt_instDbUser'], $_POST['txt_instDbPassword']);
      } catch (PDOException $e) {
        $dberror = true;
      }

      if (!$dberror) {
        // Check whether sample data shall be installed
        $installData = false;
        switch ($_POST['opt_data']) {
          case "basic":
            $installData = true;
            $query = file_get_contents("sql/basic.sql");
            break;
          case "sample":
            $installData = true;
            $query = file_get_contents("sql/sample.sql");
            break;
          default:
            $installData = false;
            break;
        }

        if ($installData) {
          // Replace prefix in sample file
          $dbPrefix = '';
          if (isset($_POST['txt_instDbPrefix'])) {
            $dbPrefix = preg_replace('/[^\w]/', '', $_POST['txt_instDbPrefix']);
          }
          $query = str_replace("tcneo_", $dbPrefix, $query);

          // Run query
          $result = $pdo->query($query);
          if ($result) {
            // Success and sample data loaded
            writeDef("APP_INSTALLED", "1", $configAppFile);
            $installationComplete = true;
            $showAlert            = true;
            $alertData['type']    = 'success';
            $alertData['title']   = $LANG['inst_success'];
            $alertData['subject'] = $LANG['inst_congrats'];
            $alertData['text']    = $LANG['inst_success_comment'];
          }
          else {
            // Sample data load failed
            $showAlert            = true;
            $alertData['type']    = 'danger';
            $alertData['title']   = $LANG['inst_error'];
            $alertData['subject'] = $LANG['inst_dbData'];
            $alertData['text']    = $LANG['inst_dbData_error'];
          }
        }
        else {
          // Success, No sample data loaded
          writeDef("APP_INSTALLED", "1", $configAppFile);
          $installationComplete = true;
          $showAlert            = true;
          $alertData['type']    = 'success';
          $alertData['title']   = $LANG['inst_success'];
          $alertData['subject'] = $LANG['inst_congrats'];
          $alertData['text']    = $LANG['inst_success_comment'];
        }
      }
      else {
        // Database connection failed
        $showAlert            = true;
        $alertData['type']    = 'danger';
        $alertData['title']   = $LANG['inst_error'];
        $alertData['subject'] = $LANG['inst_db_error'];
        $alertData['text']    = $LANG['inst_db_error'];
      }
    }
    else {
      // License not accepted
      $showAlert            = true;
      $alertData['type']    = 'warning';
      $alertData['title']   = $LANG['inst_warning'];
      $alertData['subject'] = $LANG['inst_lic'];
      $alertData['text']    = $LANG['inst_lic_error'];
    }
  }
}

//=============================================================================
//
// PREPARE VIEW
//
if (!$installationComplete && APP_INSTALLED <> '0') {
  // Installation has been executed already
  $installationExecuted = true;
  $showAlert            = true;
  $alertData['type']    = 'warning';
  $alertData['title']   = $LANG['inst_warning'];
  $alertData['subject'] = $LANG['inst_executed'];
  $alertData['text']    = $LANG['inst_executed_comment'];
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <title>TeamCal Neo Installation</title>
  <meta http-equiv="Content-type" content="text/html;charset=utf8">
  <meta charset="utf-8">
  <link rel="stylesheet" href="public/themes/bootstrap/<?= BOOTSTRAP_VER ?>/css/bootstrap.min.css">
  <link rel="stylesheet" href="public/css/teamcalneo.min.css">
  <link rel="shortcut icon" href="public/images/icons/tcn-icon-16.png">
  <link rel="stylesheet" href="public/fonts/font-awesome/<?= FONTAWESOME_VER ?>/css/all.min.css">
  <script src="public/js/teamcalneo.min.js"></script>
  <script src="public/js/jquery/jquery-<?= JQUERY_VER ?>.min.js"></script>
  <script src="public/js/jquery/ui/<?= JQUERY_UI_VER ?>/jquery-ui.min.js"></script>
  <script src="public/themes/bootstrap/<?= BOOTSTRAP_VER ?>/js/bootstrap.bundle.min.js"></script>
</head>

<body>

  <!-- ====================================================================
  view.menu
  -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a href="<?= WEBSITE_URL ?>/installation.php" class="navbar-brand" style="padding: 2px 8px 0 8px;"><img src="public/images/icons/tcn-icon-48.png" alt=""></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>
  <div style="height:20px;"></div>

  <!-- ====================================================================
  view.installation
  -->
  <div class="container content mt-2">
    <div class="col-lg-12">

      <?php if ($showAlert) { ?>
        <div class="alert alert-<?= $alertData['type'] ?>">
          <h5><?= $alertData['title'] ?></h5>
          <hr>
          <p><strong><?= $alertData['subject'] ?></strong></p>
          <p><?= $alertData['text'] ?></p>
        </div>
      <?php } ?>

      <div class="alert alert-dismissable alert-warning">
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" title="Close this message"></button>
        <h5><?= $LANG['inst_warning'] ?></h5>
        <hr>
        <p><strong><?= $LANG['inst_update'] ?></strong></p>
        <p><?= $LANG['inst_update_comment'] ?></p>
      </div>

      <?php if (!$installationExecuted && !$installationComplete) { ?>
        <form class="form-control-horizontal" action="installation.php" method="post" target="_self" accept-charset="utf-8">

          <div class="card">
            <div class="card-header"><i class="fas fa-cog fa-lg"></i>&nbsp;<?= getInstallConfig('app_name', $configAppFile) ?>   <?= getInstallConfig('app_version', $configAppFile) ?> Installation<a href="https://lewe.gitbook.io/teamcal-neo/installation" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a></div>
            <div class="card-body">

              <!-- DB Server -->
              <div class="form-group row">
                <label for="txt_instDbServer" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbServer'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbServer_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <input id="txt_instDbServer" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbServer" type="text" maxlength="160" value="<?= getInstallConfig('db_server', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- DB Port -->
              <div class="form-group row">
                <label for="txt_instDbPort" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbPort'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbPort_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <input id="txt_instDbPort" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbPort" type="text" maxlength="10" value="<?= getInstallConfig('db_port', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- DB Socket -->
              <div class="form-group row">
                <label for="txt_instDbSocket" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbSocket'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbSocket_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                 <input id="txt_instDbSocket" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbSocket" type="text" maxlength="255" value="<?= getInstallConfig('db_socket', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- DB Name -->
              <div class="form-group row">
                <label for="txt_instDbName" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbName'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbName_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <input id="txt_instDbName" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbName" type="text" maxlength="160" value="<?= getInstallConfig('db_name', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- DB User -->
              <div class="form-group row">
                <label for="txt_instDbUser" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbUser'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbUser_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <input id="txt_instDbUser" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbUser" type="text" maxlength="160" value="<?= getInstallConfig('db_user', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- DB Password -->
              <div class="form-group row">
                <label for="txt_instDbPassword" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbPassword'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbPassword_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <input id="txt_instDbPassword" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbPassword" type="password" maxlength="80" value="<?= getInstallConfig('db_password', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- DB Table Prefix -->
              <div class="form-group row">
                <label for="txt_instDbPrefix" class="col-lg-8 control-label">
                  <?= $LANG['inst_dbPrefix'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbPrefix_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <input id="txt_instDbPrefix" class="form-control" tabindex="<?= $tabindex++ ?>" name="txt_instDbPrefix" type="text" maxlength="160" value="<?= getInstallConfig('db_table_prefix', $configDbFile) ?>">
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- Test Database -->
              <div class="form-group row">
                <div class="col-lg-12">
                  <a class="btn btn-secondary text-white" tabindex="<?= $tabindex++ ?>" id="btn_testDb" onclick="checkDB();"><?= $LANG['btn_test'] ?></a>
                  <div style="height:20px;"></div>
                  <p><span id="checkDbOutput"></span></p>
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- Sample Data -->
              <div class="form-group row">
                <label class="col-lg-8 control-label">
                  <?= $LANG['inst_dbData'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_dbData_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <div class="form-check"><label><input class="form-check-input" name="opt_data" value="basic" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['inst_dbData_basic'] ?></label></div>
                  <div class="form-check"><label><input class="form-check-input" name="opt_data" value="sample" tabindex="<?= $tabindex++ ?>" type="radio" checked><?= $LANG['inst_dbData_sample'] ?></label></div>
                  <div class="form-check"><label><input class="form-check-input" name="opt_data" value="none" tabindex="<?= $tabindex++ ?>" type="radio"><?= $LANG['inst_dbData_none'] ?></label></div>
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <!-- License -->
              <div class="form-group row">
                <label class="col-lg-8 control-label">
                  <?= $LANG['inst_lic'] ?><br>
                  <span class="text-normal"><?= $LANG['inst_lic_comment'] ?></span>
                </label>
                <div class="col-lg-4">
                  <div class="form-check">
                    <label><input class="form-check-input" type="checkbox" id="chk_licApp" name="chk_licApp" value="chk_activateMessages" tabindex="<?= $tabindex++ ?>"><a href="https://lewe.gitbook.io/teamcal-neo/readme/teamcal-neo-license" target="_blank"><?= $LANG['inst_lic_app'] ?></a></label>
                  </div>
                </div>
              </div>
              <div class="divider">
                <hr>
              </div>

              <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_install" onmouseover="checkLicense();"><?= $LANG['btn_install'] ?></button>

            </div>
          </div>

        </form>
      <?php } ?>
    </div>
  </div>

  <!-- ====================================================================
  view.footer
  -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 text-start">
          <ul class="list-unstyled">
            <li class="text-muted">&copy; <?= date('Y') ?> by <a href="http://www.lewe.com" target="_blank">Lewe.com</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 text-center">
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 text-end">
          <ul class="list-unstyled">
            <li class="text-muted fst-italic fs-small mt-1"><?= APP_POWERED ?></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <script>
    function checkDB() {
      var myDbServer = document.getElementById('txt_instDbServer');
      var myDbPort = document.getElementById('txt_instDbPort');
      var myDbSocket = document.getElementById('txt_instDbSocket');
      var myDbUser = document.getElementById('txt_instDbUser');
      var myDbPass = document.getElementById('txt_instDbPassword');
      var myDbName = document.getElementById('txt_instDbName');
      var myDbPrefix = document.getElementById('txt_instDbPrefix');
      ajaxCheckDB(myDbServer.value, myDbUser.value, myDbPass.value, myDbName.value, myDbPrefix.value, 'checkDbOutput', myDbPort.value, myDbSocket.value);
    }

    function checkLicense() {
      var myLicApp = document.getElementById('chk_licApp');
      if (!myLicApp.checked) {
        alert("<?= $LANG['inst_lic_error'] ?>");
      }
    }
  </script>

</body>

</html>
