<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Calendar View Controller
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
global $UO;
global $UL;
global $U;
global $R;
global $G;
global $UG;
global $A;
global $T;
global $H;
global $M;

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

// Load all config values in one query for maximum performance
$allConfig = $C->readAll();

//-----------------------------------------------------------------------------
// CHECK URL PARAMETERS OR USER DEFAULTS
//
$missingData = false;

//
// We need a Month
//
if (isset($_GET['month'])) {
  //
  // Passed by URL always wins
  //
  $monthfilter = sanitize($_GET['month']);
} elseif (L_USER && $monthfilter = $UO->read($UL->username, 'calfilterMonth')) {
  //
  // Nothing in URL but user has a last-used value in his profile. Let's try that one.
  // (The value was loaded via the if statement so nothing needed in this block.)
  //
} else {
  //
  // Default to current year and month
  //
  $monthfilter = date('Y') . date('m');
}

//
// If we have a Month, check validity
//
if (!$missingData) {
  $viewData['year'] = substr($monthfilter, 0, 4);
  $viewData['month'] = substr($monthfilter, 4, 2);
  if (!is_numeric($monthfilter) || strlen($monthfilter) != 6 || !checkdate(intval($viewData['month']), 1, intval($viewData['year']))) {
    $missingData = true;
  } else {
    if (L_USER) {
      $UO->save($UL->username, 'calfilterMonth', $monthfilter);
    }
  }
}

//
// We need a Region
//
if (isset($_GET['region'])) {
  //
  // Passed by URL always wins
  //
  $regionfilter = sanitize($_GET['region']);
  if (L_USER) {
    $UO->save($UL->username, 'calfilterRegion', $regionfilter);
  }
} elseif (L_USER && $regionfilter = $UO->read($UL->username, 'calfilterRegion')) {
  //
  // Nothing in URL but user has a last-used value in his profile. Let's try that one.
  // (The value was loaded via the if statement so nothing needed in this block.)
  //
} else {
  //
  // Default to default region
  //
  $regionfilter = '1';
}

//
// If we have a Region, check validity
//
if (!$missingData) {
  if (!$R->getById($regionfilter)) {
    $missingData = true;
  } else {
    $viewData['regionid'] = $R->id;
    $viewData['regionname'] = $R->name;
    if (L_USER) {
      $UO->save($UL->username, 'calfilterRegion', $regionfilter);
    }
  }
}

if ($missingData) {
  //
  // No valid Month or Region
  //
  $alertData['type'] = 'danger';
  $alertData['title'] = $LANG['alert_danger_title'];
  $alertData['subject'] = $LANG['alert_no_data_subject'];
  $alertData['text'] = $LANG['alert_no_data_text'];
  $alertData['help'] = $LANG['alert_no_data_help'];
  require_once WEBSITE_ROOT . '/controller/alert.php';
  die();
}

//
// Get users for this calendar page
// Get all users first
//
$users = $U->getAllButHidden();
// Get user options individually
$groupOption = L_USER ? $UO->read($UL->username, 'calfilterGroup') : false;
$absOption = L_USER ? $UO->read($UL->username, 'calfilterAbs') : false;
// Get filters with fallbacks
$groupfilter = $_GET['group'] ?? ($groupOption ?: 'all');
$absfilter = $_GET['abs'] ?? ($absOption ?: 'all');

if (L_USER) {
  if (isset($_GET['group'])) $UO->save($UL->username, 'calfilterGroup', $groupfilter);
  if (isset($_GET['abs'])) $UO->save($UL->username, 'calfilterAbs', $absfilter);
}

$viewData['groupid'] = $groupfilter;
$viewData['absid'] = $absfilter;


// Apply filters in single loop
if ($groupfilter !== 'all' || $absfilter !== 'all') {
  $filteredUsers = [];
  foreach ($users as $usr) {
    $include = true;
    
    if ($groupfilter !== 'all') {
      $include = $UG->isMemberOrGuestOfGroup($usr['username'], $groupfilter);
      if (!$include) continue;
    }
    
    if ($absfilter !== 'all') {
      $include = $T->hasAbsence($usr['username'], date('Y'), date('m'), $absfilter);
    }
    
    if ($include) $filteredUsers[] = $usr;
  }
  $users = $filteredUsers;
}

$viewData['group'] = ($groupfilter == "all") ? $LANG['all'] : $G->getNameById($groupfilter);
$viewData['absfilter'] = ($absfilter !== "all");
$viewData['absence'] = ($absfilter == "all") ? $LANG['all'] : $A->getName($absfilter);

//
// Search filter (optional, defaults to 'all')
//
$viewData['search'] = '';
if (L_USER && $searchfilter = $UO->read($UL->username, 'calfilterSearch')) {
  //
  // Nothing in URL but user has a last-used value in his profile.
  // (The value was loaded via the if statement so nothing needed in this block.)
  //
  $viewData['search'] = $searchfilter;
  unset($users);
  $users = $U->getAllLike($searchfilter);
}

//
// Search Reset
//
if (isset($_GET['search']) && $_GET['search'] == "reset") {
  if (L_USER) {
    $UO->deleteUserOption($UL->username, 'calfilterSearch');
  }
  header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller);
  die();
}

//
// Default back to current year/month if option is set and role matches
//
if ($allConfig['currentYearOnly'] && $viewData['year'] != date('Y')) {
  if ($allConfig['currYearRoles']) {
    //
    // Applies to roles. Check if current user in in one of them.
    //
    $arrCurrYearRoles = array();
    $arrCurrYearRoles = explode(',', $allConfig['currYearRoles']);
    $userRole = $U->getRole(L_USER);
    if (in_array($userRole, $arrCurrYearRoles)) {
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . date('Ym') . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
      die();
    }
  } else {
    //
    // Just in case currYearRoles is not set yet
    //
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . date('Ym') . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
    die();
  }
}

//
// Paging
//
if ($limit = $allConfig['usersPerPage']) {
  //
  // How many users do we have?
  //
  $total = count($users);
  //
  // How many pages do we need for them?
  //
  $pages = ceil($total / $limit);
  //
  // What page are we currently on?
  //
  $page = min($pages, filter_input(
    INPUT_GET,
    'page',
    FILTER_VALIDATE_INT,
    array(
      'options' => array(
        'default' => 1,
        'min_range' => 1,
      ),
    )
  ));
  //
  // Get the $users records do we need for this page
  //
  $offset = ($page - 1) * $limit;
  $start = $offset;
  $end = min(($offset + $limit), $total) - 1;
  $pageusers = array();
  for ($i = $start; $i <= $end; $i++) {
    array_push($pageusers, $users[$i]);
  }
  unset($users);
  $users = $pageusers;
}

//-----------------------------------------------------------------------------
// LOAD CONTROLLER RESOURCES
//

//-----------------------------------------------------------------------------
// VARIABLE DEFAULTS
//
$inputAlert = array();
$currDate = date('Y-m-d');
$viewmode = 'fullmonth';  // Initialize viewmode, will be set properly after form processing

// Initialize months array to prevent undefined array key warning
$viewData['months'] = array();

//
// Figure out how many months to display
//
if ($UO->read($UL->username, 'showMonths')) {
  $showMonths = intval($UO->read($UL->username, 'showMonths'));
} elseif ($allConfig['showMonths']) {
  $showMonths = intval($allConfig['showMonths']);
} else {
  $showMonths = 1;
  $C->save('showMonths', 1);
}

//
// Check for temp amount of months
//
// ,---,
// | - |
// '---'
if (!empty($_POST) && isset($_POST['btn_oneless'])) {
  $showMonths = intval($_POST['hidden_showmonths']);
  if ($showMonths > 1) {
    $showMonths--;
  }
}
// ,---,
// | + |
// '---'
if (!empty($_POST) && isset($_POST['btn_onemore'])) {
  $showMonths = intval($_POST['hidden_showmonths']);
  if ($showMonths <= 12) {
    $showMonths++;
  }
}

//
// Get the roles that can view confidential absences and daynotes
//
if ($trustedRoles = $allConfig['trustedRoles']) {
  $viewData['trustedRoles'] = explode(',', $trustedRoles);
} else {
  $viewData['trustedRoles'] = array( '1' );
  $C->save("trustedRoles", '1');
}

//
// See if a region month template exists for each month to show. If not, create one.
//
foreach ($viewData['months'] as $vmonth) {
  if (!$vmonth['M']->getMonth($vmonth['year'], $vmonth['month'], $viewData['regionid'])) {
    createMonth($vmonth['year'], $vmonth['month'], 'region', $viewData['regionid']);
    $vmonth['M']->getMonth($vmonth['year'], $vmonth['month'], $viewData['regionid']);
    //
    // Send notification e-mails to the subscribers of user events
    //
    if ($allConfig['emailNotifications']) {
      sendMonthEventNotifications("created", $vmonth['year'], $vmonth['month'], $viewData['regionname']);
    }
    //
    // Log this event
    //
    $LOG->logEvent("logMonth", L_USER, "log_month_tpl_created", $vmonth['M']->region . ": " . $vmonth['M']->year . "-" . $vmonth['M']->month);
  }
}

//-----------------------------------------------------------------------------
// PROCESS FORM
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

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
  // Sanitize input
  //
  $_POST = sanitize($_POST);

  //
  // Form validation
  //
  $inputError = false;
  if (isset($_POST['btn_search']) && !formInputValid('txt_search', 'required|alpha_numeric_dash')) {
    $inputError = true;
  }

  if (!$inputError) {
    // ,--------------,
    // | Select Month |
    // '--------------'
    if (isset($_POST['btn_month'])) {
      if (L_USER) {
        $UO->save($UL->username, 'calfilterMonth', $_POST['txt_year'] . $_POST['sel_month']);
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $_POST['txt_year'] . $_POST['sel_month'] . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
      die();
    }
    // ,---------------,
    // | Select Region |
    // '---------------'
    elseif (isset($_POST['btn_region'])) {
      if (L_USER) {
        $UO->save($UL->username, 'calfilterRegion', $_POST['sel_region']);
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $monthfilter . "&region=" . $_POST['sel_region'] . "&group=" . $groupfilter . "&abs=" . $absfilter);
      die();
    }
    // ,---------------,
    // | Select Group  |
    // '---------------'
    elseif (isset($_POST['btn_group'])) {
      if (L_USER) {
        $UO->save($UL->username, 'calfilterGroup', $_POST['sel_group']);
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $_POST['sel_group'] . "&abs=" . $absfilter);
      die();
    }
    // ,----------------,
    // | Select Absence |
    // '----------------'
    elseif (isset($_POST['btn_abssearch'])) {
      if (L_USER) {
        $UO->save($UL->username, 'calfilterAbs', $_POST['sel_absence']);
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence']);
      die();
    }
    // ,---------------------,
    // | Select Screen Width |
    // '---------------------'
    elseif (isset($_POST['btn_width'])) {
      $UO->save($UL->username, 'width', $_POST['sel_width']);
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence'] . "&viewmode=" . $viewmode);
      die();
    }
    // ,---------------------,
    // | Select View Mode    |
    // '---------------------'
    elseif (isset($_POST['btn_viewmode'])) {
      if (L_USER) {
        $UO->save($UL->username, 'calViewMode', $_POST['sel_viewmode']);
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter . "&viewmode=" . $_POST['sel_viewmode']);
      die();
    }
    // ,---------------,
    // | Search User   |
    // '---------------'
    elseif (isset($_POST['btn_search'])) {
      if (L_USER) {
        $UO->save($UL->username, 'calfilterSearch', $_POST['txt_search']);
      }
      $viewData['search'] = $_POST['txt_search'];
      unset($users);
      $users = $U->getAllLike($_POST['txt_search']);
    }
    // ,-------------------,
    // | Search User Clear |
    // '-------------------'
    elseif (isset($_POST['btn_search_clear'])) {
      if (L_USER) {
        $UO->deleteUserOption($UL->username, 'calfilterSearch');
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $absfilter);
      die();
    }
    // ,-------,
    // | Reset |
    // '-------'
    elseif (isset($_POST['btn_reset'])) {
      if (L_USER) {
        $UO->deleteUserOption($UL->username, 'calfilter');
        $UO->deleteUserOption($UL->username, 'calfilterMonth');
        $UO->deleteUserOption($UL->username, 'calfilterRegion');
        $UO->deleteUserOption($UL->username, 'calfilterGroup');
        $UO->deleteUserOption($UL->username, 'calfilterAbs');
        $UO->deleteUserOption($UL->username, 'calfilterSearch');
      }
      header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller);
      die();
    }
  } else {
    //
    // Input validation failed
    //
    $showAlert = true;
    $alertData['type'] = 'danger';
    $alertData['title'] = $LANG['alert_danger_title'];
    $alertData['subject'] = $LANG['alert_input'];
    $alertData['text'] = $LANG['abs_alert_save_failed'];
    $alertData['help'] = '';
  }
}

//
// Check for view mode (fullmonth or splitmonth)
// This is done AFTER form processing so that the toggle button works correctly
//
if (isset($_GET['viewmode'])) {
  $viewmode = sanitize($_GET['viewmode']);
  if (L_USER) {
    $UO->save($UL->username, 'calViewMode', $viewmode);
  }
} elseif (L_USER && $viewmode = $UO->read($UL->username, 'calViewMode')) {
  //
  // Nothing in URL but user has a last-used value in his profile.
  //
} else {
  $viewmode = 'fullmonth';
}

//
// Validate view mode
//
if ($viewmode !== 'splitmonth') {
  $viewmode = 'fullmonth';
}

$viewData['viewmode'] = $viewmode;

//
// Build months array based on view mode
// This is done AFTER view mode is determined
//
if ($viewmode === 'splitmonth') {
  //
  // Split month view: last 15 days of current month + first 15 days of next month
  // This is displayed as a single combined table
  // Note: In split mode, each entry shows 2 months, so we need to account for this
  //
  $viewData['months'] = array();
  $currYear = intval($viewData['year']);
  $currMonth = intval($viewData['month']);
  
  for ($splitIdx = 0; $splitIdx < $showMonths; $splitIdx++) {
    $nextMonth = $currMonth + 1;
    $nextYear = $currYear;
    if ($nextMonth > 12) {
      $nextMonth = 1;
      $nextYear++;
    }
    
    $currMonthInfo = dateInfo($currYear, sprintf('%02d', $currMonth));
    $nextMonthInfo = dateInfo($nextYear, sprintf('%02d', $nextMonth));
    
    //
    // Create a combined month entry that spans both months
    //
    $M = new Months();
    if (!$M->getMonth($currYear, sprintf('%02d', $currMonth), $viewData['regionid'])) {
      createMonth($currYear, sprintf('%02d', $currMonth), 'region', $viewData['regionid']);
      $M->getMonth($currYear, sprintf('%02d', $currMonth), $viewData['regionid']);
    }
    
    //
    // Load the next month's Months object for split month display
    // Create it if it doesn't exist
    //
    $nextM = new Months();
    if (!$nextM->getMonth($nextYear, sprintf('%02d', $nextMonth), $viewData['regionid'])) {
      createMonth($nextYear, sprintf('%02d', $nextMonth), 'region', $viewData['regionid']);
      $nextM->getMonth($nextYear, sprintf('%02d', $nextMonth), $viewData['regionid']);
      //
      // Send notification e-mails to the subscribers of user events
      //
      if ($allConfig['emailNotifications']) {
        sendMonthEventNotifications("created", $nextYear, sprintf('%02d', $nextMonth), $viewData['regionname']);
      }
      //
      // Log this event
      //
      $LOG->logEvent("logMonth", L_USER, "log_month_tpl_created", $nextM->region . ": " . $nextM->year . "-" . $nextM->month);
    }
    
    $viewData['months'][] = array(
      'year' => $currYear,
      'month' => sprintf('%02d', $currMonth),
      'dateInfo' => $currMonthInfo,
      'dayStart' => $currMonthInfo['daysInMonth'] - 14,  // Last 15 days of current month
      'dayEnd' => $currMonthInfo['daysInMonth'],
      'nextMonthInfo' => $nextMonthInfo,  // Include next month info for combined display
      'nextMonthDays' => 15,  // First 15 days of next month
      'isSplitMonth' => true,  // Flag to indicate this is a split month view
      'M' => $M,
      'nextM' => $nextM,  // Store the next month's Months object
      'dayStyles' => array(),
      'businessDays' => 0,
    );
    
    //
    // Move to next month for next iteration
    //
    $currMonth = $nextMonth;
    $currYear = $nextYear;
  }
} else {
  //
  // Standard fullmonth view
  //
  $M = new Months();
  $M->getMonth($viewData['year'], $viewData['month'], $viewData['regionid']);
  $viewData['months'] = array(
    array(
      'year' => $viewData['year'],
      'month' => $viewData['month'],
      'dateInfo' => dateInfo($viewData['year'], $viewData['month']),
      'M' => $M,
      'dayStyles' => array(),
      'businessDays' => 0,
    ),
  );
}

//
// Prepare following months if required (only for fullmonth mode)
// In split month mode, all months are already prepared above
//
if ($showMonths > 1 && $viewmode === 'fullmonth') {
  $prevYear = intval($viewData['year']);
  $prevMonth = intval($viewData['month']);
  for ($i = 2; $i <= $showMonths; $i++) {
    if ($prevMonth == 12) {
      if ($allConfig['currentYearOnly'] && $allConfig["currYearRoles"]) {
        //
        // Applies to roles
        //
        $arrCurrYearRoles = array();
        $arrCurrYearRoles = explode(',', $allConfig["currYearRoles"]);
        $userRole = $U->getRole(L_USER);
        if (in_array($userRole, $arrCurrYearRoles)) {
          $i = $showMonths + 1;
          continue;
        } else {
          $nextMonth = "01";
          $nextYear = $prevYear + 1;
        }
      } else {
        $nextMonth = "01";
        $nextYear = $prevYear + 1;
      }
    } else {
      $nextMonth = sprintf('%02d', $prevMonth + 1);
      $nextYear = $prevYear;
    }

    $M = new Months();
    $M->getMonth($nextYear, $nextMonth, $viewData['regionid']);
    
    //
    // Standard fullmonth view
    //
    $viewData['months'][] = array(
      'year' => $nextYear,
      'month' => $nextMonth,
      'dateInfo' => dateInfo($nextYear, $nextMonth),
      'M' => $M,
      'dayStyles' => array(),
      'businessDays' => 0,
    );
    $prevYear = intval($nextYear);
    $prevMonth = intval($nextMonth);
  }
}

//-----------------------------------------------------------------------------
// PREPARE VIEW
//
$viewData['pageHelp'] = $allConfig['pageHelp'];
$viewData['showAlerts'] = $allConfig['showAlerts'];

$viewData['absences'] = $A->getAll();
$viewData['allGroups'] = $G->getAll();
$viewData['holidays'] = $H->getAllCustom();

if ($groupfilter == 'all') {
  $viewData['groups'] = $G->getAll();
} else {
  $viewData['groups'] = $G->getRowById($groupfilter);
}
$viewData['dayStyles'] = array();

//
// Loop through all users and catch those that will be displayed
//
$viewData['users'] = array();
foreach ($users as $usr) {
  $allowed = false;
  if ($usr['username'] == $UL->username) {
    $allowed = true;
  } elseif (!$U->isHidden($usr['username'])) {
    if (isAllowed("calendarviewall") || (isAllowed("calendarviewgroup") && $UG->shareGroups($usr['username'], $UL->username))) {
      $allowed = true;
    }
  }
  if ($allowed) {
    $viewData['users'][] = $usr;
  }
}

//
// Loop through all display users
// See if a month template for each user exists. If not, create one.
//
foreach ($viewData['users'] as $user) {
  foreach ($viewData['months'] as $vmonth) {
    if (!$T->getTemplate($user['username'], $vmonth['year'], $vmonth['month'])) {
      createMonth($vmonth['year'], $vmonth['month'], 'user', $user['username']);
    }
  }
}

//
// Get the holiday and weekend colors
// These styles are saved in the dayStyles array of each month and affect the whole
// column of a day.
//
// Pre-cache holiday colors
$holidayColors = [];
$weekendColors = [];
for ($i=1; $i<=15; $i++) {
  $holidayColors[$i] = [
    'color' => $H->getColor($i),
    'bgcolor' => $H->getBgColor($i)
  ];
  $weekendColors[$i] = [
    'color' => $H->getColor($i),
    'bgcolor' => $H->getBgColor($i)
  ];
}

$j = 0;
$todayBorderStyle = 'border-left: ' . $allConfig["todayBorderSize"] . 'px solid #' . $allConfig["todayBorderColor"] . ';border-right: ' . $allConfig["todayBorderSize"] . 'px solid #' . $allConfig["todayBorderColor"] . ';';

foreach ($viewData['months'] as $vmonth) {
  $dayStyles = array();
  $monthObj = $vmonth['M'];
  $monthNum = intval($vmonth['month']);
  $yearNum = intval($vmonth['year']);

  for ($i = 1; $i <= $vmonth['dateInfo']['daysInMonth']; $i++) {
    $hprop = 'hol' . $i;
    $wprop = 'wday' . $i;
    $holidayId = $monthObj->$hprop;
    $weekday = $monthObj->$wprop;

    $color = '';
    $bgcolor = '';
    $border = '';

    if ($holidayId) {
      if ($H->keepWeekendColor($holidayId) && ($weekday == 6 || $weekday == 7)) {
        $color = 'color:#' . $weekendColors[$weekday - 4]['color'] . ';';
        $bgcolor = 'background-color:#' . $weekendColors[$weekday - 4]['bgcolor'] . ';';
      } else {
        $color = 'color:#' . $holidayColors[$holidayId]['color'] . ';';
        $bgcolor = 'background-color:#' . $holidayColors[$holidayId]['bgcolor'] . ';';
      }
    } elseif ($weekday == 6 || $weekday == 7) {
      $color = 'color:#' . $weekendColors[$weekday - 4]['color'] . ';';
      $bgcolor = 'background-color:#' . $weekendColors[$weekday - 4]['bgcolor'] . ';';
    }

    if (date('Y-m-d', mktime(0, 0, 0, $monthNum, $i, $yearNum)) == $currDate) {
      $border = $todayBorderStyle;
    }

    if ($color || $bgcolor || $border) {
      $dayStyles[$i] = $color . $bgcolor . $border;
    }
  }

  if (isset($vmonth['isSplitMonth']) && $vmonth['isSplitMonth']) {
    $nextMonthObj = $vmonth['nextM'];
    $nextMonthNum = $monthNum + 1 > 12 ? 1 : $monthNum + 1;
    $nextYearNum = $monthNum + 1 > 12 ? $yearNum + 1 : $yearNum;

    for ($i = 1; $i <= 15; $i++) {
      $hprop = 'hol' . $i;
      $wprop = 'wday' . $i;
      $holidayId = $nextMonthObj->$hprop;
      $weekday = $nextMonthObj->$wprop;

      $color = '';
      $bgcolor = '';
      $border = '';

      if ($holidayId) {
        if ($H->keepWeekendColor($holidayId) && ($weekday == 6 || $weekday == 7)) {
          $color = 'color:#' . $weekendColors[$weekday - 4]['color'] . ';';
          $bgcolor = 'background-color:#' . $weekendColors[$weekday - 4]['bgcolor'] . ';';
        } else {
          $color = 'color:#' . $holidayColors[$holidayId]['color'] . ';';
          $bgcolor = 'background-color:#' . $holidayColors[$holidayId]['bgcolor'] . ';';
        }
      } elseif ($weekday == 6 || $weekday == 7) {
        $color = 'color:#' . $weekendColors[$weekday - 4]['color'] . ';';
        $bgcolor = 'background-color:#' . $weekendColors[$weekday - 4]['bgcolor'] . ';';
      }

      if (date('Y-m-d', mktime(0, 0, 0, $nextMonthNum, $i, $nextYearNum)) == $currDate) {
        $border = $todayBorderStyle;
      }

      if ($color || $bgcolor || $border) {
        $dayStyles['next_' . $i] = $color . $bgcolor . $border;
      }
    }
  }

  $viewData['months'][$j]['dayStyles'] = $dayStyles;
  $j++;
}

//
// Get the number of business days
//
$j = 0;
foreach ($viewData['months'] as $vmonth) {
  $cntfrom = $vmonth['year'] . $vmonth['month'] . '01';
  $cntto = $vmonth['year'] . $vmonth['month'] . $vmonth['dateInfo']['daysInMonth'];
  $viewData['months'][$j]['businessDays'] = countBusinessDays($cntfrom, $cntto, $viewData['regionid']);
  
  // In split month view, also calculate business days for the next month
  if (isset($vmonth['isSplitMonth']) && $vmonth['isSplitMonth']) {
    $nextMonthNum = intval($vmonth['month']) + 1;
    $nextYearNum = intval($vmonth['year']);
    if ($nextMonthNum > 12) {
      $nextMonthNum = 1;
      $nextYearNum++;
    }
    $nextMonthInfo = dateInfo($nextYearNum, sprintf('%02d', $nextMonthNum));
    $nextCntfrom = $nextYearNum . sprintf('%02d', $nextMonthNum) . '01';
    $nextCntto = $nextYearNum . sprintf('%02d', $nextMonthNum) . $nextMonthInfo['daysInMonth'];
    $viewData['months'][$j]['nextMonthBusinessDays'] = countBusinessDays($nextCntfrom, $nextCntto, $viewData['regionid']);
  }
  
  $j++;
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['regions'] = $R->getAll();

// Set calendar options in viewData for easy access
$viewData['calendarFontSize'] = $allConfig['calendarFontSize'];
$viewData['defgroupfilter'] = $allConfig['defgroupfilter'];
$viewData['firstDayOfWeek'] = $allConfig["firstDayOfWeek"];
$viewData['hideManagers'] = $allConfig['hideManagers'];
$viewData['includeSummary'] = $allConfig['includeSummary'];
$viewData['monitorAbsence'] = $C->read('monitorAbsence');
$viewData['pastDayColor'] = $allConfig['pastDayColor'];
$viewData['regionalHolidays'] = $C->read("regionalHolidays");
$viewData['regionalHolidaysColor'] = $C->read("regionalHolidaysColor");
$viewData['repeatHeaderCount'] = $allConfig['repeatHeaderCount'];
$viewData['showAvatars'] = $allConfig['showAvatars'];
$viewData['showRegionButton'] = $allConfig['showRegionButton'];
$viewData['showRoleIcons'] = $allConfig['showRoleIcons'];
$viewData['showSummary'] = $allConfig['showSummary'];
$viewData['symbolAsIcon'] = $C->read('symbolAsIcon');
$viewData['showTooltipCount'] = $allConfig['showTooltipCount'];
$viewData['showWeekNumbers'] = $allConfig['showWeekNumbers'];
$viewData['summaryAbsenceTextColor'] = $allConfig['summaryAbsenceTextColor'];
$viewData['summaryPresenceTextColor'] = $allConfig['summaryPresenceTextColor'];
$viewData['supportMobile'] = $allConfig['supportMobile'];
$viewData['userPerPage'] = $allConfig['usersPerPage'];

$mobilecols['full'] = $viewData['months'][0]['dateInfo']['daysInMonth'];
if (!$viewData['width'] = $UO->read($UL->username, 'width')) {
  $UO->save($UL->username, 'width', 'full');
  $viewData['width'] = 'full';
}
//
// Lastly, check whether only the calendar shall be displayed. This may be
// useful in iFrames.
//
if (isset($_GET['calendaronly']) && $_GET['calendaronly'] === "1") {
  $viewData['calendaronly'] = true;
} else {
  $viewData['calendaronly'] = false;
}

//-----------------------------------------------------------------------------
// SHOW VIEW
//
require_once WEBSITE_ROOT . '/views/header.php';
if (!$viewData['calendaronly']) {
  require_once WEBSITE_ROOT . '/views/menu.php';
}
include_once WEBSITE_ROOT . '/views/' . $controller . '.php';
if (!$viewData['calendaronly']) {
  require_once WEBSITE_ROOT . '/views/footer.php';
}
