<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Calendar Edit Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

// echo '<script type="text/javascript">alert("Debug: ");</script>';

//=============================================================================
//
// CHECK URL PARAMETERS
//
if (isset($_GET['month']) and isset($_GET['region']) and isset($_GET['user'])) {
  $missingData = FALSE;
  $doNotSave = FALSE;

  //
  // Check month
  //
  $yyyymm = sanitize($_GET['month']);
  $viewData['year'] = substr($yyyymm, 0, 4);
  $viewData['month'] = substr($yyyymm, 4, 2);
  if (!is_numeric($yyyymm) or strlen($yyyymm) != 6 or !checkdate(intval($viewData['month']), 1, intval($viewData['year']))) {
    $missingData = TRUE;
    die("month");
  }

  //
  // Check region
  //
  $region = sanitize($_GET['region']);
  if (!$R->getById($region)) {
    $missingData = TRUE;
  } else {
    if ($R->getAccess($R->id, $UL->getRole($UL->username)) == 'view') {
      //
      // The current user (role) can only view the region specified in the URL.
      // So we replace it with the default region.
      //
      $R->getById('1');
    }
    $viewData['regionid'] = $R->id;
    $viewData['regionname'] = $R->name;
  }

  //
  // Check user
  //
  $caluser = sanitize($_GET['user']);
  if (!$U->findByName($caluser)) {
    $missingData = TRUE;
  }
} else {
  $missingData = TRUE;
}

if ($missingData) {
  //
  // URL param fail
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_danger_title'];
  $alertData['subject'] = $LANG['alert_no_data_subject'];
  $alertData['text'] = $LANG['alert_no_data_text'];
  $alertData['help'] = $LANG['alert_no_data_help'];
  require(WEBSITE_ROOT . '/controller/alert.php');
  die();
}

//
// Default back to current yearmonth if option is set
//
if ($C->read('currentYearOnly') and $viewData['year'] != date('Y')) {
  if ($C->read("currYearRoles")) {
    //
    // Applies to roles. Check if current user in in one of them.
    //
    $arrCurrYearRoles = array();
    $arrCurrYearRoles = explode(',', $C->read("currYearRoles"));
    $userRole = $U->getRole(L_USER);
    if (in_array($userRole, $arrCurrYearRoles)) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . date('Ym') . "&region=" . $region . "&user=" . $caluser);
      die();
    }
  }
}

//=============================================================================
//
// CHECK PERMISSION
//
$allowed = false;
if (isAllowed($CONF['controllers'][$controller]->permission)) {
  if ($UL->username == $caluser) {
    if (isAllowed("calendareditown")) $allowed = true;
  } else if ($UG->shareGroupMemberships($UL->username, $caluser)) {
    if (isAllowed("calendareditgroup")) {
      $allowed = true;
    } elseif (isAllowed("calendareditgroupmanaged") and $UG->isGroupManagerOfUser($UL->username, $caluser)) {
      $allowed = true;
    }
  } else {
    if (isAllowed("calendareditall")) $allowed = true;
  }
}

if (!$allowed) {
  $alertData['type'] = 'warning';
  $alertData['title'] = $LANG['alert_alert_title'];
  $alertData['subject'] = $LANG['alert_not_allowed_subject'];
  $alertData['text'] = $LANG['alert_not_allowed_text'];
  $alertData['help'] = $LANG['alert_not_allowed_help'];
  require(WEBSITE_ROOT . '/controller/alert.php');
  die();
}

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// CHECK LICENSE
// Checks when the current weekday matches a random number between 1 and 7
//
$date = new DateTime();
$weekday = $date->format('N');
if ($weekday == rand(1, 7)) {
  $alertData = array();
  $showAlert = false;
  $licExpiryWarning = $C->read('licExpiryWarning');
  $LIC = new License();
  $LIC->check($alertData, $showAlert, $licExpiryWarning, $LANG);
}

//=============================================================================
//
// VARIABLE DEFAULTS
//
$users = $U->getAll();
$inputAlert = array();
$currDate = date('Y-m-d');
$viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

//
// See if a region template exists. If not, create one.
//
if (!$M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid'])) {
  createMonth($viewData['year'], $viewData['month'], 'region', $viewData['regionid']);
  $M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);

  //
  // Log this event
  //
  $LOG->log("logMonth", L_USER, "log_month_tpl_created", $M->region . ": " . $M->year . "-" . $M->month);
}

//
// See if a user template exists. If not, create one.
//
if (!$T->getTemplate($caluser, $viewData['year'], $viewData['month'])) {
  createMonth($viewData['year'], $viewData['month'], 'user', $caluser);
  $T->getTemplate($caluser, $viewData['year'], $viewData['month']);

  //
  // Log this event
  //
  $LOG->log("logMonth", L_USER, "log_month_tpl_created", $caluser . ": " . $M->year . "-" . $M->month);
}

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {
  if (isset($_POST['btn_save']) or isset($_POST['btn_clearall']) or isset($_POST['btn_saveperiod']) or isset($_POST['btn_saverecurring'])) {
    //
    // All changes to the calendar are handled in this block since it finishes with the Approval routine.
    // First, get the current absences of the user into an array $currentAbsences
    // Secondly, set the $requestedAbsences array to the current ones. Updates are done below.
    // Thirdly, clear the $approvedAbsences array with 0s
    // Fourthly, clear the $declinedAbsences array with 0s
    //
    for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
      $currentAbsences[$i] = $T->getAbsence($caluser, $viewData['year'], $viewData['month'], $i);
      $requestedAbsences[$i] = $currentAbsences[$i];
      $approvedAbsences[$i] = '0';
      $declinedAbsences[$i] = '0';
    }

    // ,------,
    // | Save |
    // '------'
    if (isset($_POST['btn_save'])) {
      //
      // Loop thru the radio boxes
      //
      for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
        $key = 'opt_abs_' . $i;
        if (isset($_POST[$key])) {
          $requestedAbsences[$i] = $_POST[$key];
        } else {
          //
          // If no radio button is set, it could also mean that the user has an absence on this
          // day already that he cannot see in his calendar edit screen, e.g. a manager only absence.
          // Just take over the current absence.
          //
          $requestedAbsences[$i] = $currentAbsences[$i];
        }
      }
    }
    // ,-----------,
    // | Clear All |
    // '-----------'
    else if (isset($_POST['btn_clearall'])) {
      //
      // Loop thru the radio boxes
      //
      if (isset($_POST['chk_clearAbsences']) or isset($_POST['chk_clearDaynotes'])) {
        //
        // Clear Absences
        //
        if (isset($_POST['chk_clearAbsences'])) {
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $requestedAbsences[$i] = '0';
          }
        }
        //
        // Clear Daynotes
        //
        if (isset($_POST['chk_clearDaynotes'])) {
          for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
            $daynoteDate = $viewData['year'] . $viewData['month'] . sprintf("%02d", ($i));
            $D->delete($daynoteDate, $caluser, $viewData['regionid']);
          }
        }
      }
    }
    // ,-------------,
    // | Save Period |
    // '-------------'
    elseif (isset($_POST['btn_saveperiod'])) {
      //
      // Form validation
      //
      $inputError = false;
      if (!formInputValid('txt_periodStart', 'required|date')) $inputError = true;
      if (!formInputValid('txt_periodEnd', 'required|date')) $inputError = true;

      if (!$inputError) {
        $startPieces = explode("-", $_POST['txt_periodStart']);
        $startYear = $startPieces[0];
        $startMonth = $startPieces[1];

        $endPieces = explode("-", $_POST['txt_periodEnd']);
        $endYear = $endPieces[0];
        $endMonth = $endPieces[1];

        if ($startYear == $viewData['year'] and $endYear == $viewData['year'] and $startMonth == $viewData['month'] and $endMonth == $viewData['month']) {
          $startDate = str_replace("-", "", $_POST['txt_periodStart']);
          $endDate = str_replace("-", "", $_POST['txt_periodEnd']);

          for ($i = $startDate; $i <= $endDate; $i++) {
            $year = substr($i, 0, 4);
            $month = substr($i, 4, 2);
            $day = intval(substr($i, 6, 2));
            $requestedAbsences[$day] = $_POST['sel_periodAbsence'];
          }
        } else {
          //
          // Input out of range
          //
          $doNotSave = TRUE;
          $showAlert = TRUE;
          $alertData['type'] = 'danger';
          $alertData['title'] = $LANG['alert_danger_title'];
          $alertData['subject'] = $LANG['alert_input'];
          $alertData['text'] = $LANG['caledit_alert_out_of_range'];
          $alertData['help'] = '';
        }
      } else {
        //
        // Input validation failed
        //
        $doNotSave = TRUE;
        $showAlert = TRUE;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_input'];
        $alertData['text'] = $LANG['caledit_alert_save_failed'];
        $alertData['help'] = '';
      }
    }
    // ,----------------,
    // | Save Recurring |
    // '----------------'
    elseif (isset($_POST['btn_saverecurring'])) {
      $startDate = $viewData['year'] . $viewData['month'] . '01';
      $endDate = $viewData['year'] . $viewData['month'] . $viewData['dateInfo']['daysInMonth'];
      $wdays = array( 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7 );

      foreach ($_POST as $key => $value) {
        foreach ($wdays as $wday => $wdaynr) {
          if ($key == $wday) {
            //
            // The checkbox for this weekday was set. Loop through the month and mark all of them.
            //
            for ($i = $startDate; $i <= $endDate; $i++) {
              $day = intval(substr($i, 6, 2));
              $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], $day);
              if ($loopDayInfo['wday'] == $wdaynr) {
                $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
              }
            }
          } elseif ($key == "workdays") {
            //
            // The checkbox for workdays was set. Loop through the month and mark all workdays.
            //
            for ($i = $startDate; $i <= $endDate; $i++) {
              $day = intval(substr($i, 6, 2));
              $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], $day);
              if ($loopDayInfo['wday'] >= 1 and $loopDayInfo['wday'] <= 5) {
                $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
              }
            }
          } elseif ($key == "weekends") {
            //
            // The checkbox for weekends was set. Loop through the month and mark all weekend days.
            //
            for ($i = $startDate; $i <= $endDate; $i++) {
              $day = intval(substr($i, 6, 2));
              $loopDayInfo = dateInfo($viewData['year'], $viewData['month'], $day);
              if ($loopDayInfo['wday'] >= 6 and $loopDayInfo['wday'] <= 7) {
                $requestedAbsences[$day] = $_POST['sel_recurringAbsence'];
              }
            }
          }
        }
      }
    }

    if (!$doNotSave) {
      //
      // At this point we have four arrays:
      // - $currentAbsences (Absences before this request)
      // - $requestedAbsences (Absences requested)
      // - $approved['approvedAbsences'] (Absences approved, coming back from the approval function)
      // - $approved['declinedReasons'] (Declined Reasons, coming back from the approval function)
      //
      $approved = approveAbsences($caluser, $viewData['year'], $viewData['month'], $currentAbsences, $requestedAbsences, $viewData['regionid']);

      $sendNotification = false;
      $alerttype = 'success';
      $alertHelp = '';
      $logText = '<br>';
      switch ($approved['approvalResult']) {
        case 'all':
          $logText .= $LANG['approved'] . '<br>';
          foreach ($requestedAbsences as $key => $val) {
            $col = 'abs' . $key;
            $T->$col = $val;
            if ($val) $logText .= '- ' . $viewData['year'] . $viewData['month'] . sprintf("%02d", $key) . ': ' . $A->getName($val) . '<br>';
          }
          $T->update($caluser, $viewData['year'], $viewData['month']);
          $sendNotification = true;
          break;

        case 'partial':
          $logText .= $LANG['partially_approved'] . '<br>';
          foreach ($approved['approvedAbsences'] as $key => $val) {
            $col = 'abs' . $key;
            $T->$col = $val;
            if ($val) $logText .= '- ' . $viewData['year'] . $viewData['month'] . sprintf("%02d", $key) . ': ' . $A->getName($val) . '<br>';
          }
          $T->update($caluser, $viewData['year'], $viewData['month']);
          $sendNotification = true;
          $alerttype = 'info';
          foreach ($approved['declinedReasons'] as $reason) {
            if (strlen($reason)) {
              $alertHelp .= $reason . "<br>";
            }
          }
          foreach ($approved['declinedReasonsLog'] as $reason) {
            if (strlen($reason)) {
              $logText .= "<i>" . $reason . "</i><br>";
            }
          }
          break;

        case 'none':
        default:
          $alerttype = 'info';
          break;
      }

      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($C->read("emailNotifications") and $sendNotification) {
        sendUserCalEventNotifications("changed", $caluser, $viewData['year'], $viewData['month']);
      }

      //
      // Log this event
      //
      $LOG->log("logCalendar", $UL->username, "log_cal_usr_tpl_chg", $caluser . " " . $viewData['year'] . $viewData['month'] . $logText);

      //
      // Success
      //
      $showAlert = TRUE;
      $alertData['type'] = $alerttype;
      $alertData['title'] = $LANG['alert_' . $alerttype . '_title'];
      $alertData['subject'] = $LANG['caledit_alert_update'];
      $alertData['text'] = $LANG['caledit_alert_update_' . $approved['approvalResult']];
      $alertData['help'] = $alertHelp;
    }
  }
  // ,---------------,
  // | Select Region |
  // '---------------'
  elseif (isset($_POST['btn_region'])) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $_POST['sel_region'] . "&user=" . $caluser);
    die();
  }
  // ,---------------------,
  // | Select Screen Width |
  // '---------------------'
  elseif (isset($_POST['btn_width'])) {
    $UO->save($UL->username, 'width', $_POST['sel_width']);
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&user=" . $caluser);
    die();
  }
  // ,-------------,
  // | Select User |
  // '-------------'
  elseif (isset($_POST['btn_user'])) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $region . "&user=" . $_POST['sel_user']);
    die();
  }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['username'] = $caluser;
$viewData['fullname'] = $U->getFullname($caluser);
$viewData['absences'] = $A->getAll();
$viewData['holidays'] = $H->getAllCustom();
$viewData['dayStyles'] = array();

//
// Prepare a comma seperated group name list for the caluser
//
$usergroups = $UG->getAllforUser($caluser);
$viewData['groupnames'] = " <span style=\"font-weight:normal;\">(";
foreach ($usergroups as $ug) {
  $viewData['groupnames'] .= $G->getNameByID($ug['groupid']) . ", ";
}
$viewData['groupnames'] = substr($viewData['groupnames'], 0, -2);
$viewData['groupnames'] .= ")</span>";

//
// Only prepare those regions the current user (role) can edit
//
$allRegions = $R->getAll();
foreach ($allRegions as $reg) {
  if (!$R->getAccess($reg['id'], $UL->getRole($UL->username)) or $R->getAccess($reg['id'], $UL->getRole($UL->username)) == 'edit') {
    $viewData['regions'][] = $reg;
  }
}

//
// Get the users to display
//
$viewData['users'] = array();
foreach ($users as $usr) {
  $allowed = false;
  if ($usr['username'] == $UL->username and isAllowed("calendareditown")) {
    $allowed = true;
  } else if (!$U->isHidden($usr['username'])) {
    if (isAllowed("calendareditall")) {
      $allowed = true;
    } elseif (isAllowed("calendareditgroup") and $UG->shareGroups($usr['username'], $UL->username)) {
      $allowed = true;
    }
  }
  if ($allowed) {
    $viewData['users'][] = array( 'username' => $usr['username'], 'lastfirst' => $U->getLastFirst($usr['username']) );
  }
}

//
// Get the holiday and weekend colors
//
for ($i = 1; $i <= $viewData['dateInfo']['daysInMonth']; $i++) {
  $color = '';
  $bgcolor = '';
  $border = '';
  $viewData['dayStyles'][$i] = '';
  $hprop = 'hol' . $i;
  $wprop = 'wday' . $i;
  if ($M->$hprop) {
    //
    // This is a holiday. Get the coloring info.
    //
    $color = 'color:#' . $H->getColor($M->$hprop) . ';';
    $bgcolor = 'background-color:#' . $H->getBgColor($M->$hprop) . ';';
  } else if ($M->$wprop == 6 or $M->$wprop == 7) {
    //
    // This is a Saturday or Sunday. Get the coloring info.
    //
    $color = 'color:#' . $H->getColor($M->$wprop - 4) . ';';
    $bgcolor = 'background-color:#' . $H->getBgColor($M->$wprop - 4) . ';';
  }

  //
  // Get today style
  //
  $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
  if ($loopDate == $currDate) {
    $border = 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
  }

  //
  // Build styles
  //
  if (strlen($color) or strlen($bgcolor) or strlen($border)) {
    $viewData['dayStyles'][$i] = ' style="' . $color . $bgcolor . $border . '"';
  }
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['showWeekNumbers'] = $C->read('showWeekNumbers');
$mobilecols['full'] = $viewData['dateInfo']['daysInMonth'];
$viewData['supportMobile'] = $C->read('supportMobile');
$viewData['firstDayOfWeek'] = $C->read("firstDayOfWeek");
if (!$viewData['width'] = $UO->read($UL->username, 'width')) {
  $UO->save($UL->username, 'width', 'full');
  $viewData['width'] = 'full';
}

//=============================================================================
//
// SHOW VIEW
//
require(WEBSITE_ROOT . '/views/header.php');
require(WEBSITE_ROOT . '/views/menu.php');
include(WEBSITE_ROOT . '/views/' . $controller . '.php');
require(WEBSITE_ROOT . '/views/footer.php');
