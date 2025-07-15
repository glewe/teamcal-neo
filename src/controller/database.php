<?php

/**
 * Database Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $LIC;
global $DB;
global $U;
global $UO;
global $G;
global $UG;
global $MSG;
global $UMSG;
global $D;
global $M;
global $T;
global $AL;
global $P;

//-----------------------------------------------------------------------------
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission)) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//-----------------------------------------------------------------------------
// CHECK LICENSE
//
$alertData = array();
$showAlert = false;
$licExpiryWarning = $C->read('licExpiryWarning');
$LIC = new License();
$LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$inputAlert = array();
$viewData['cleanBefore'] = '';

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_delete']) && !formInputValid('txt_deleteConfirm', 'required|alpha|equals_string', 'DELETE')) {
    $inputError = true;
  }
  if (isset($_POST['btn_cleanup'])) {
    if (!formInputValid('txt_cleanBefore', 'required|date')) {
      $inputError = true;
    }
    if (!formInputValid('txt_cleanConfirm', 'required|alpha|equals_string', 'CLEANUP')) {
      $inputError = true;
    }
    if (!isValidDate($_POST['txt_cleanBefore'])) {
      $inputError = true;
    }
    $viewData['cleanBefore'] = $_POST['txt_cleanBefore'];
  }

  if (isset($_POST['btn_repair']) && !formInputValid('txt_repairConfirm', 'required|alpha|equals_string', 'REPAIR')) {
    $inputError = true;
  }

  if (!$inputError) {
    // ,---------,
    // | Repair  |
    // '---------'
    if (isset($_POST['btn_repair'])) {
      //
      // Daynote regions
      //
      if (isset($_POST['chk_daynoteRegions'])) {
        $daynotes = $D->getAllRegionless();
        foreach ($daynotes as $daynote) {
          $D->setRegion($daynote['id'], '1');
        }
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['db_alert_repair'];
      $alertData['text'] = $LANG['db_alert_repair_success'];
      $alertData['help'] = '';
    }
    // ,---------,
    // | Cleanup |
    // '---------'
    if (isset($_POST['btn_cleanup'])) {
      if (isset($_POST['chk_cleanDaynotes'])) {
        $result = $D->deleteAllBefore(str_replace('-', '', $_POST['txt_cleanBefore']));
      }

      if (isset($_POST['chk_cleanMonths'])) {
        $param1 = substr($_POST['txt_cleanBefore'], 0, 4);
        $param2 = substr($_POST['txt_cleanBefore'], 5, 2);
        $result = $M->deleteBefore($param1, $param2);
      }

      if (isset($_POST['chk_cleanTemplates'])) {
        $result = $T->deleteBefore(substr($_POST['txt_cleanBefore'], 0, 4), substr($_POST['txt_cleanBefore'], 5, 2));
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['db_alert_delete'];
      $alertData['text'] = $LANG['db_alert_delete_success'];
      $alertData['help'] = '';
    }
    // ,--------,
    // | Delete |
    // '--------'
    elseif (isset($_POST['btn_delete'])) {
      $deleteActions = [
        'chk_delUsers' => function() use ($U, $UO, $D, $T, $AL, $LOG) {
          $U->deleteAll();
          $UO->deleteAll();
          $D->deleteAll();
          $T->deleteAll();
          $AL->deleteAll();
          $LOG->logEvent("logDatabase", L_USER, "log_db_delete_users");
        },
        'chk_delGroups' => function() use ($G, $UG, $LOG) {
          $G->deleteAll();
          $UG->deleteAll();
          $LOG->logEvent("logDatabase", L_USER, "log_db_delete_groups");
        },
        'chk_delMessages' => function() use ($MSG, $UMSG, $LOG) {
          $MSG->deleteAll();
          $UMSG->deleteAll();
          $LOG->logEvent("logDatabase", L_USER, "log_db_delete_msg");
        },
        'chk_delOrphMessages' => function() use ($LOG) {
          deleteOrphanedMessages();
          $LOG->logEvent("logMessages", L_USER, "log_db_delete_msg_orph");
        },
        'chk_delPermissions' => function() use ($P, $LOG) {
          $P->deleteAll();
          $LOG->logEvent("logDatabase", L_USER, "log_db_delete_perm");
        },
        'chk_delLog' => function() use ($LOG) {
          $LOG->deleteAll();
          $LOG->logEvent("logDatabase", L_USER, "log_db_delete_log");
        },
        'chkDBDeleteArchive' => function() use ($U, $UG, $UO, $UMSG, $LOG) {
          $U->deleteAll(true);
          $UG->deleteAll(true);
          $UO->deleteAll(true);
          $UMSG->deleteAll(true);
          $LOG->logEvent("logDatabase", L_USER, "log_db_delete_archive");
        },
      ];
      foreach ($deleteActions as $postKey => $action) {
        if (isset($_POST[$postKey])) {
          $action();
        }
      }
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['db_alert_delete'];
      $alertData['text'] = $LANG['db_alert_delete_success'];
      $alertData['help'] = '';
    }
    // ,----------,
    // | Optimize |
    // '----------'
    elseif (isset($_POST['btn_optimize'])) {
      //
      // Optimize tables
      //
      $DB->optimizeTables();
      //
      // Log this event
      //
      $LOG->logEvent("logDatabase", L_USER, "log_db_optimized");
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['db_alert_optimize'];
      $alertData['text'] = $LANG['db_alert_optimize_success'];
      $alertData['help'] = '';
    }
    // ,----------,
    // | Save URL |
    // '----------'
    elseif (isset($_POST['btn_saveURL'])) {
      if (filter_var($_POST['txt_dbURL'], FILTER_VALIDATE_URL)) {
        $C->save("dbURL", $_POST['txt_dbURL']);
        //
        // Success
        //
        $showAlert = true;
        $alertData['type'] = 'success';
        $alertData['title'] = $LANG['alert_success_title'];
        $alertData['subject'] = $LANG['db_alert_url'];
        $alertData['text'] = $LANG['db_alert_url_success'];
        $alertData['help'] = '';
      } else {
        //
        // Fail
        //
        $showAlert = true;
        $alertData['type'] = 'warning';
        $alertData['title'] = $LANG['alert_warning_title'];
        $alertData['subject'] = $LANG['db_alert_url'];
        $alertData['text'] = $LANG['db_alert_url_fail'];
        $alertData['help'] = '';
        $C->save("dbURL", "#");
      }
    }
    // ,----------,
    // | Reset DB |
    // '----------'
    elseif (isset($_POST['btn_reset']) && $_POST['txt_dbResetString'] == "YesIAmSure") {
      $query = file_get_contents("sql/sample.sql");
      $DB->db->exec($query);
      //
      // Log this event
      //
      $LOG->logEvent("logDatabase", L_USER, "log_db_reset");
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['db_alert_reset'];
      $alertData['text'] = $LANG['db_alert_reset_success'];
      $alertData['help'] = '';
    }
    //
    // Renew CSRF token after successful form processing
    //
    if (isset($_SESSION)) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['db_alert_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['dbURL'] = $C->read('dbURL');
$viewData['dbInfo'] = $DB->getDatabaseInfo();

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
