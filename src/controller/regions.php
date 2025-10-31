<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Regions Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
global $allConfig;
global $C;
global $CONF;
global $controller;
global $LANG;
global $LOG;
global $M;
global $R;
global $UO;

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
$date = new DateTime();
$weekday = $date->format('N');
if ($weekday === rand(1, 7)) {
  $alertData = array();
  $showAlert = false;
  $licExpiryWarning = $allConfig['licExpiryWarning'];
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//
$MM = new Months();
$RS = new Regions(); // Source region for merge
$RT = new Regions(); // Target region for merge

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];
$viewData['currentYearOnly'] = $allConfig['currentYearOnly'];
$viewData['txt_name'] = '';
$viewData['txt_description'] = '';

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
  //
  // Sanitize all POST data at the start of POST processing
  //
  $_POST = sanitize($_POST);

  //
  // Sanitize all FILES data at the start of POST processing
  //
  if (isset($_FILES) && is_array($_FILES)) {
    foreach ($_FILES as $key => $file) {
      if (isset($file['name'])) {
        $_FILES[$key]['name'] = htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8');
      }
      if (isset($file['type'])) {
        $_FILES[$key]['type'] = htmlspecialchars($file['type'], ENT_QUOTES, 'UTF-8');
      }
      if (isset($file['tmp_name'])) {
        $_FILES[$key]['tmp_name'] = htmlspecialchars($file['tmp_name'], ENT_QUOTES, 'UTF-8');
      }
      if (isset($file['error'])) {
        $_FILES[$key]['error'] = htmlspecialchars((string)$file['error'], ENT_QUOTES, 'UTF-8');
      }
      if (isset($file['size'])) {
        $_FILES[$key]['size'] = htmlspecialchars((string)$file['size'], ENT_QUOTES, 'UTF-8');
      }
    }
  }

  //
  // CSRF token check
  //
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    $alertData['type'] = 'warning';
    $alertData['title'] = $LANG['alert_alert_title'];
    $alertData['subject'] = $LANG['alert_csrf_invalid_subject'];
    $alertData['text'] = $LANG['alert_csrf_invalid_text'];
    $alertData['help'] = $LANG['alert_csrf_invalid_help'];
    require_once WEBSITE_ROOT . '/controller/alert.php';
    die();
  }

  // ,--------,
  // | Create |
  // '--------'
  if (isset($_POST['btn_regionCreate'])) {
    //
    // Form validation
    //
    $inputAlert = array();
    $inputError = false;
    if (!formInputValid('txt_name', 'required|alpha_numeric_dash')) {
      $inputError = true;
    }
    if (!formInputValid('txt_description', 'alpha_numeric_dash_blank')) {
      $inputError = true;
    }


    $viewData['txt_name'] = $_POST['txt_name'] ?? '';
    $viewData['txt_description'] = $_POST['txt_description'] ?? '';

    if (!$inputError) {
      $R->name = $viewData['txt_name'];
      $R->description = $viewData['txt_description'];
      $R->create();
      //
      // Log this event
      //
      $LOG->logEvent("logRegion", L_USER, "log_region_created", $R->name . " " . $R->description);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['btn_create_region'];
      $alertData['text'] = $LANG['regions_alert_region_created'];
      $alertData['help'] = '';
      //
      // Renew CSRF token after successful POST
      //
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
      //
      // Fail
      //
      $showAlert = true;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['btn_create_region'];
      $alertData['text'] = $LANG['regions_alert_region_created_fail'];
      $alertData['help'] = '';
    }
  }
  // ,--------,
  // | Delete |
  // '--------'
  elseif (isset($_POST['btn_regionDelete'])) {
    $hiddenId = $_POST['hidden_id'] ?? null;
    $hiddenName = $_POST['hidden_name'] ?? '';
    if ($hiddenId !== null) {
      $R->delete($hiddenId);
      $R->deleteAccess($hiddenId);
      $M->deleteRegion($hiddenId);
      $UO->deleteOptionByValue('calfilterRegion', $hiddenId);
      //
      // Log this event
      //
      $LOG->logEvent("logRegion", L_USER, "log_region_deleted", $hiddenName);
    }
    //
    // Success
    //
    $showAlert = true;
    $alertData['type'] = 'success';
    $alertData['title'] = $LANG['alert_success_title'];
    $alertData['subject'] = $LANG['btn_delete_region'];
    $alertData['text'] = $LANG['regions_alert_region_deleted'];
    $alertData['help'] = '';
    //
    // Renew CSRF token after successful POST
    //
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  // ,-------------,
  // | iCal Import |
  // '-------------'
  elseif (isset($_POST['btn_uploadIcal'])) {
    $fileIcal = $_FILES['file_ical']['tmp_name'] ?? '';
    if (trim($fileIcal) === '') {
      //
      // No filename was submitted
      //
      $showAlert = true;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['regions_alert_no_file'];
      $alertData['help'] = '';
      // Renew CSRF token after successful POST
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
      $viewData['icalRegionID'] = $_POST['sel_ical_region'] ?? '';
      $viewData['icalRegionName'] = $R->getNameById($viewData['icalRegionID']);
      //
      // Parse the iCal file events
      //
      $iCalEvents = array();
      preg_match_all("#(?sU)BEGIN:VEVENT.*END:VEVENT#", file_get_contents($fileIcal), $events);
      //
      // Now go through all events
      //
      foreach ($events[0] as $event) {
        //
        // Read the event start and end string
        //
        preg_match("#(?sU)DTSTART;.*DATE:(\\d{8})#", $event, $start);
        preg_match("#(?sU)DTEND;.*DATE:(\\d{8})#", $event, $end);
        if (!isset($start[1], $end[1])) {
          continue;
        }
        //
        // Create time stamps and substract 24h from the end date cause the
        // end date of an iCal event is not included
        //
        $start = mktime(0, 0, 0, substr($start[1], 4, 2), substr($start[1], 6, 2), substr($start[1], 0, 4));
        $end = mktime(0, 0, 0, substr($end[1], 4, 2), substr($end[1], 6, 2), substr($end[1], 0, 4));
        $end = $end - 86400;
        //
        // Loop through all events and save the date string into an array
        //
        for ($i = $start; $i <= $end; $i += 86400) {
          $eventDate = date("Ymd", $i);
          $iCalEvents[] = $eventDate;
        }
      }
      //
      // Loop through the date string array and save each one in the region template
      //
      $lastCachedMonth = null;
      $lastCachedYear = null;
      foreach ($iCalEvents as $i) {
        $eventYear = substr($i, 0, 4);
        $eventMonth = substr($i, 4, 2);
        $eventDay = intval(substr($i, 6, 2));

        //
        // Only load month if it's different from the last cached month
        //
        if ($lastCachedYear !== $eventYear || $lastCachedMonth !== $eventMonth) {
          if (!$MM->getMonth($eventYear, $eventMonth, $viewData['icalRegionID'])) {
            //
            // Seems there is no template for this month yet.
            // If we have one in cache, write it first.
            //
            if ($M->year) {
              $M->update($M->year, $M->month, $viewData['icalRegionID']);
            }
            //
            // Create the new blank template
            //
            createMonth($eventYear, $eventMonth, 'region', $viewData['icalRegionID']);
          } else {
            //
            // There is a template for this month in memory. Save it first.
            //
            $M->update($M->year, $M->month, $viewData['icalRegionID']);
          }
          $lastCachedYear = $eventYear;
          $lastCachedMonth = $eventMonth;
        }

        $holidayId = $_POST['sel_ical_holiday'] ?? null;
        if ($holidayId === null) {
          continue;
        }
        if ($M->getHoliday($eventYear, $eventMonth, $eventDay, $viewData['icalRegionID']) === 0) {
          //
          // No Holiday set yet for this day. Good to overwrite.
          //
          $M->setHoliday($eventYear, $eventMonth, $eventDay, $viewData['icalRegionID'], $holidayId);
        } else {
          //
          // This is an existing holiday. Check the overwrite flag.
          //
          if (isset($_POST['chk_ical_overwrite'])) {
            $M->setHoliday($eventYear, $eventMonth, $eventDay, $viewData['icalRegionID'], $holidayId);
          }
        }
      }
      //
      // Log this event
      //
      $fileName = $_FILES['file_ical']['name'] ?? '';
      $LOG->logEvent("logRegion", L_USER, "log_region_ical", $fileName . ' => ' . $viewData['icalRegionName']);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['regions_tab_ical'];
      $alertData['text'] = sprintf($LANG['regions_ical_imported'], $fileName, $viewData['icalRegionName']);
      $alertData['help'] = '';
      //
      // Renew CSRF token after successful POST
      //
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
  }
  // ,----------,
  // | Transfer |
  // '----------'
  elseif (isset($_POST['btn_regionTransfer'])) {
    $sregion = $_POST['sel_region_a'] ?? '';
    $tregion = $_POST['sel_region_b'] ?? '';
    if ($sregion === $tregion) {
      //
      // Same source and target region
      //
      $showAlert = true;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['regions_alert_transfer_same'];
      $alertData['help'] = '';
    } else {
      //
      // Cache region names to avoid duplicate queries
      //
      $sourceRegionName = $R->getNameById($sregion);
      $targetRegionName = $R->getNameById($tregion);
      //
      // Loop through every month of the source region
      //
      $stemplates = $M->getRegion($sregion);

      foreach ($stemplates as $stpl) {
        if (!$M->getMonth($stpl['year'], $stpl['month'], $tregion)) {
          //
          // No target template found for this year/month/region
          // Create an empty template first.
          //
          createMonth($stpl['year'], $stpl['month'], 'region', $tregion);
        } else {
          //
          // Load the target month template into memory
          //
          $M->getMonth($stpl['year'], $stpl['month'], $tregion);
        }
        for ($i = 1; $i <= 31; $i++) {
          $prop = 'hol' . $i;
          if (($stpl[$prop] ?? 0) > 3) {
            //
            // Source holds a custom holiday here (1 = Business day, 2 = Saturday, 3 = Sunday)
            //
            if (($M->$prop ?? 0) <= 3) {
              //
              // Target holds no custom holiday here. Save to overwrite.
              //
              $M->$prop = $stpl[$prop];
            } else {
              //
              // Target holds a custom holiday here. Check overwrite flag.
              //
              if (isset($_POST['chk_overwrite'])) {
                $M->$prop = $stpl[$prop];
              }
            }
          }
        }
        //
        // And save the template
        //
        $M->update($stpl['year'], $stpl['month'], $tregion);
      }
      //
      // Log this event
      //
      $LOG->logEvent("logRegion", L_USER, "log_region_transferred", $sourceRegionName . " => " . $targetRegionName);
      //
      // Success
      //
      $showAlert = true;
      $alertData['type'] = 'success';
      $alertData['title'] = $LANG['alert_success_title'];
      $alertData['subject'] = $LANG['regions_tab_transfer'];
      $alertData['text'] = sprintf($LANG['regions_transferred'], $sourceRegionName, $targetRegionName);
      $alertData['help'] = '';
    }
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['regions'] = $R->getAll();
foreach ($viewData['regions'] as $region) {
  $viewData['regionList'][] = array('val' => $region['id'], 'name' => $region['name'], 'selected' => false);
}

$holidays = $H->getAll();
foreach ($holidays as $holiday) {
  $viewData['holidayList'][] = array('val' => $holiday['id'], 'name' => $holiday['name'], 'selected' => false);
}
$viewData['ical'] = array(
  array('prefix' => 'regions', 'name' => 'ical_region', 'type' => 'list', 'values' => $viewData['regionList']),
  array('prefix' => 'regions', 'name' => 'ical_holiday', 'type' => 'list', 'values' => $viewData['holidayList']),
  array('prefix' => 'regions', 'name' => 'ical_overwrite', 'type' => 'check', 'value' => false),
);

$viewData['merge'] = array(
  array('prefix' => 'regions', 'name' => 'region_a', 'type' => 'list', 'values' => $viewData['regionList']),
  array('prefix' => 'regions', 'name' => 'region_b', 'type' => 'list', 'values' => $viewData['regionList']),
  array('prefix' => 'regions', 'name' => 'region_overwrite', 'type' => 'check', 'value' => false),
);

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
require_once WEBSITE_ROOT . '/views/menu.php';
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
require_once WEBSITE_ROOT . '/views/footer.php';
