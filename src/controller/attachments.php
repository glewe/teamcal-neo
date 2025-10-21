<?php
if (!defined('VALID_ROOT')) {
  exit('');
}

/**
 * Attachments page controller
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
global $U;
global $UL;
global $UG;
global $RO;

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
// LOAD CONTROLLER RESOURCES
//
$UPL = new Upload();
$AT = new Attachment();
$UAT = new UserAttachment();

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$uplDir = WEBSITE_ROOT . '/' . APP_UPL_DIR;
$viewData = array();
$viewData['shareWith'] = 'all';
$viewData['shareWithGroup'] = array();
$viewData['shareWithRole'] = array();
$viewData['shareWithUser'] = array();
$viewData['uplFiles'] = array();

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  //
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // Form validation
  //
  $inputError = false;

  //
  // Validate input data. If something is wrong or missing, set $inputError = true
  //

  if (!$inputError) {
    // ,-------------,
    // | Upload File |
    // '-------------'
    if (isset($_POST['btn_uploadFile'])) {
      $UPL->upload_dir = $uplDir;
      $UPL->extensions = $CONF['uplExtensions'];
      $UPL->do_filename_check = "y";
      $UPL->replace = "y";
      $UPL->the_temp_file = $_FILES['file_image']['tmp_name'] ?? '';
      // Replace blanks with underscores in the file name
      $safeFileName = str_replace(' ', '_', $_FILES['file_image']['name'] ?? '');
      $UPL->the_file = $safeFileName;
      $UPL->http_error = $_FILES['file_image']['error'] ?? 0;

      if ($UPL->uploadFile()) {
        $AT->create($UPL->the_file, $UL->username);
        $fileid = $AT->getId($UPL->the_file);
        $UAT->create($UL->username, $fileid);

        switch ($_POST['opt_shareWith']) {
          case "admin":
            $UAT->create('admin', $fileid);
            break;

          case "all":
            $users = $U->getAll();
            foreach ($users as $user) {
              $UAT->create($user['username'], $fileid);
            }
            break;

          case "group":
            if (isset($_POST['sel_shareWithGroup'])) {
              foreach ($_POST['sel_shareWithGroup'] as $gto) {
                $groupusers = $UG->getAllForGroup($gto);
                foreach ($groupusers as $groupuser) {
                  $UAT->create($groupuser['username'], $fileid);
                }
              }
            } else {
              //
              // No group selected
              //
              $showAlert = true;
              $alertData['type'] = 'warning';
              $alertData['title'] = $LANG['alert_warning_title'];
              $alertData['subject'] = $LANG['msg_no_group_subject'];
              $alertData['text'] = $LANG['msg_no_group_text'];
              $alertData['help'] = '';
            }
            break;

          case "role":
            if (isset($_POST['sel_shareWithRole'])) {
              foreach ($_POST['sel_shareWithRole'] as $rto) {
                $roleusers = $U->getAllForRole($rto);
                foreach ($roleusers as $roleuser) {
                  $UAT->create($roleuser['username'], $fileid);
                }
              }
            } else {
              //
              // No group selected
              //
              $showAlert = true;
              $alertData['type'] = 'warning';
              $alertData['title'] = $LANG['alert_warning_title'];
              $alertData['subject'] = $LANG['msg_no_group_subject'];
              $alertData['text'] = $LANG['msg_no_group_text'];
              $alertData['help'] = '';
            }
            break;

          case "user":
            if (isset($_POST['sel_shareWithUser'])) {
              foreach ($_POST['sel_shareWithUser'] as $uto) {
                $UAT->create($uto, $fileid);
              }
            } else {
              //
              // No user selected
              //
              $showAlert = true;
              $alertData['type'] = 'warning';
              $alertData['title'] = $LANG['alert_warning_title'];
              $alertData['subject'] = $LANG['msg_no_user_subject'];
              $alertData['text'] = $LANG['msg_no_user_text'];
              $alertData['help'] = '';
            }
            break;

          default:
            break;
        }

        //
        // Renew CSRF token after successful form processing
        //
        if (session_status() === PHP_SESSION_ACTIVE) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if (!$showAlert) {
          //
          // Log this event
          //
          $uploadedFileName = isset($UPL->uploaded_file['name']) ? $UPL->uploaded_file['name'] : $UPL->the_file;
          $LOG->logEvent("logUpload", $UL->username, "log_upload_image", $uploadedFileName);
          header("Location: index.php?action=" . $controller);
        }
      } else {
        //
        // Upload failed
        //
        $showAlert = true;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_upl_img_subject'];
        $alertData['text'] = $UPL->getErrors();
        $alertData['help'] = sprintf($LANG['att_file_comment'], $CONF['uplMaxsize'] / 1024, implode(', ', $CONF['uplExtensions']), APP_UPL_DIR);
      }
    }
    // ,-------------,
    // | Delete File |
    // '-------------'
    elseif (isset($_POST['btn_deleteFile'])) {
      if (isset($_POST['chk_file'])) {
        $selected_files = $_POST['chk_file'];
        foreach ($selected_files as $file) {
          if (isValidFileName($file) && ($UL->username == 'admin' || $UL->username == $AT->getUploader($file))) {
            $fileid = $AT->getId($file);
            $AT->delete($file);
            $UAT->deleteFile($fileid);
            unlink($uplDir . $file);
          }
        }
      } else {
        //
        // No file selected
        //
        $showAlert = true;
        $alertData['type'] = 'warning';
        $alertData['title'] = $LANG['alert_warning_title'];
        $alertData['subject'] = $LANG['msg_no_file_subject'];
        $alertData['text'] = $LANG['msg_no_file_text'];
        $alertData['help'] = '';
      }
    }
    // ,---------------,
    // | Update Shares |
    // '---------------'
    $files = $AT->getAll();
    foreach ($files as $file) {
      if (isset($_POST['btn_updateShares' . $file['id']])) {
        $UAT->deleteFile($file['id']);
        if (isset($_POST['sel_shares' . $file['id']])) {
          foreach ($_POST['sel_shares' . $file['id']] as $uto) {
            $UAT->create($uto, $file['id']);
          }
        }
        // Make sure the uploader has access
        $UAT->create($AT->getUploaderById($file['id']), $file['id']);
      } elseif (isset($_POST['btn_clearShares' . $file['id']])) {
        $UAT->deleteFile($file['id']);
        // Make sure the uploader has access
        $UAT->create($AT->getUploaderById($file['id']), $file['id']);
      }
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['register_alert_failed'];
    $alertData['help'] = '';
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['upl_maxsize'] = $CONF['uplMaxsize'];
$viewData['upl_formats'] = implode(', ', $CONF['uplExtensions']);
$files = getFiles(APP_UPL_DIR, $CONF['uplExtensions'], '');
$allUsers = $U->getAll();
foreach ($files as $file) {
  $fid = $AT->getId($file);
  $owner = $AT->getUploader($file);
  $isOwner = ($UL->username == 'admin' || $UL->username == $owner);
  $access = array();
  foreach ($allUsers as $user) {
    $access[$user['username']] = $UAT->hasAccess($user['username'], $fid) ? true : false;
  }
  $ext = getFileExtension($file);
  if ($UL->username != 'admin') {
    if ($UAT->hasAccess($UL->username, $fid)) {
      $viewData['uplFiles'][] = array(
        'fid' => $fid,
        'fname' => $file,
        'owner' => $owner,
        'isOwner' => $isOwner,
        'access' => $access,
        'ext' => $ext
      );
    }
  } else {
    $viewData['uplFiles'][] = array(
      'fid' => $fid,
      'fname' => $file,
      'owner' => $owner,
      'isOwner' => $isOwner,
      'access' => $access,
      'ext' => $ext
    );
  }
}
$viewData['groups'] = $G->getAll();
$viewData['roles'] = $RO->getAll();
$viewData['users'] = $U->getAll();

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
