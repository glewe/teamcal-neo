<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Calendar View Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Controllers
 * @since 3.0.0
 */

// echo "<script type=\"text/javascript\">alert(\"calendarview.php: \");</script>";

//=============================================================================
//
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission)) {
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
// CHECK URL PARAMETERS OR USER DEFAULTS
//

$missingData = FALSE;

//-----------------------------------------------------------------------------
//
// We need a Month
//
if (isset($_GET['month'])) {
    //
    // Passed by URL always wins
    //
    $monthfilter = sanitize($_GET['month']);
} elseif (L_USER and $monthfilter = $UO->read($UL->username, 'calfilterMonth')) {
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
    if (
        !is_numeric($monthfilter) or
        strlen($monthfilter) != 6 or
        !checkdate(intval($viewData['month']), 1, intval($viewData['year']))
    ) {
        $missingData = TRUE;
    } else {
        if (L_USER) $UO->save($UL->username, 'calfilterMonth', $monthfilter);
    }
}

//-----------------------------------------------------------------------------
//
// We need a Region
//
if (isset($_GET['region'])) {
    //
    // Passed by URL always wins
    //
    $regionfilter = sanitize($_GET['region']);
    if (L_USER) $UO->save($UL->username, 'calfilterRegion', $regionfilter);
} elseif (L_USER and $regionfilter = $UO->read($UL->username, 'calfilterRegion')) {
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
        $missingData = TRUE;
    } else {
        $viewData['regionid'] = $R->id;
        $viewData['regionname'] = $R->name;
        if (L_USER) $UO->save($UL->username, 'calfilterRegion', $regionfilter);
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
    require(WEBSITE_ROOT . '/controller/alert.php');
    die();
}

//-----------------------------------------------------------------------------
//
// Get users for this calendar page
//

//
// Get all users first
//
$users = $U->getAllButHidden();

if (isset($_GET['group'])) {
    $groupfilter = sanitize($_GET['group']);
    if (L_USER) $UO->save($UL->username, 'calfilterGroup', $groupfilter);
} elseif (L_USER and $groupfilter = $UO->read($UL->username, 'calfilterGroup')) {
    //
    // Nothing in URL but user has a last-used value in his profile.
    // That value was loaded via the if statement so nothing needed in this block.
    //
} else {
    $groupfilter = 'all';
}

//
// If a group filter has been specified, create a new array and only copy those
// that belong to the group or are guests in that group.
//
$viewData['groupid'] = $groupfilter;
if ($groupfilter == "all") {
    $viewData['group'] = $LANG['all'];
} else {
    $viewData['group'] = $G->getNameById($groupfilter);
    $calusers = array();
    foreach ($users as $key => $usr) {
        if ($UG->isMemberOrGuestOfGroup($usr['username'], $groupfilter)) {
            $calusers[] = $usr;
        }
    }
    $users = $calusers;
}

//-----------------------------------------------------------------------------
//
// Absence filter (optional, defaults to 'all')
//
if (isset($_GET['abs'])) {
    $absfilter = sanitize($_GET['abs']);
    if (L_USER) $UO->save($UL->username, 'calfilterAbs', $absfilter);
} elseif (L_USER and $absfilter = $UO->read($UL->username, 'calfilterAbs')) {
    //
    // Nothing in URL but user has a last-used value in his profile.
    // (The value was loaded via the if statement so nothing needed in this block.)
    //
} else {
    $absfilter = 'all';
}

$viewData['absid'] = $absfilter;
if ($absfilter == "all") {
    $viewData['absfilter'] = false;
    $viewData['absence'] = $LANG['all'];
} else {
    $viewData['absfilter'] = true;
    $viewData['absence'] = $A->getName($absfilter);
    $ausers = array();
    foreach ($users as $usr) {
        if ($T->hasAbsence($usr['username'], date('Y'), date('m'), $absfilter)) {
            array_push($ausers, $usr);
        }
    }
    unset($users);
    $users = $ausers;
}

//-----------------------------------------------------------------------------
//
// Search filter (optional, defaults to 'all')
//
$viewData['search'] = '';
if (L_USER and $searchfilter = $UO->read($UL->username, 'calfilterSearch')) {
    //
    // Nothing in URL but user has a last-used value in his profile.
    // (The value was loaded via the if statement so nothing needed in this block.)
    //
    $viewData['search'] = $searchfilter;
    unset($users);
    $users = $U->getAllLike($searchfilter);
}

//-----------------------------------------------------------------------------
//
// Search Reset
//
if (isset($_GET['search']) and $_GET['search'] == "reset") {
    if (L_USER) $UO->deleteUserOption($UL->username, 'calfilterSearch');
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller);
    die();
}

//-----------------------------------------------------------------------------
//
// Default back to current year/month if option is set and role matches
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

//-----------------------------------------------------------------------------
//
// Paging
//
if ($limit = $C->read("usersPerPage")) {
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
                'default'   => 1,
                'min_range' => 1,
            ),
        )
    ));

    // 
    // Get the $users records do we need for this page
    //
    $offset = ($page - 1)  * $limit;
    $start = $offset;
    $end = min(($offset + $limit), $total) - 1;
    $pageusers = array();
    for ($i = $start; $i <= $end; $i++) {
        array_push($pageusers, $users[$i]);
    }
    unset($users);
    $users = $pageusers;
}

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//
$inputAlert = array();
$currDate = date('Y-m-d');
// $viewData['dateInfo'] = dateInfo($viewData['year'], $viewData['month']);

$viewData['months'] = array(
    array(
        'year' => $viewData['year'],
        'month' => $viewData['month'],
        'dateInfo' => dateInfo($viewData['year'], $viewData['month']),
        'M' => new Months(),
        'dayStyles' => array(),
        'businessDays' => 0,
    ),
);

//
// Figure out how many months to display
//
if ($showMonths = $UO->read($UL->username, 'showMonths')) {
    // Nothing to do. We have the profile value now.
} else if ($showMonths = $C->read('showMonths')) {
    // Nothing to do. We have the global value now.
} else {
    // Profile and global value missing. Set to default 1 and save as global.
    $showMonths = 1;
    $C->save('showMonths', 1);
}

//
// Check for temp amount of months
//
// ,---,
// | - |
// '---'
if (!empty($_POST) and isset($_POST['btn_oneless'])) {
    $showMonths = intval($_POST['hidden_showmonths']);
    if ($showMonths > 1) $showMonths--;
}
// ,---,
// | + |
// '---'
if (!empty($_POST) and isset($_POST['btn_onemore'])) {
    $showMonths = intval($_POST['hidden_showmonths']);
    if ($showMonths <= 12) $showMonths++;
}

//
// Prepare following months if required
//
if ($showMonths > 1) {
    $prevYear = intval($viewData['year']);
    $prevMonth = intval($viewData['month']);
    for ($i = 2; $i <= $showMonths; $i++) {
        if ($prevMonth == 12) {
            if ($C->read('currentYearOnly') and $C->read("currYearRoles")) {
                //
                // Applies to roles
                //
                $arrCurrYearRoles = array();
                $arrCurrYearRoles = explode(',', $C->read("currYearRoles"));
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

        $viewData['months'][] = array(
            'year' => $nextYear,
            'month' => $nextMonth,
            'dateInfo' => dateInfo($nextYear, $nextMonth),
            'M' => new Months(),
            'dayStyles' => array(),
            'businessDays' => 0,
        );
        $prevYear = intval($nextYear);
        $prevMonth = intval($nextMonth);
    }
}

//
// Get the roles that can view confidential absences and daynotes
//
if ($trustedRoles = $C->read("trustedRoles")) {
    $viewData['trustedRoles'] = explode(',', $trustedRoles);
} else {
    $viewData['trustedRoles'] = array('1');
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
        if ($C->read("emailNotifications")) {
            sendMonthEventNotifications("created", $vmonth['year'], $vmonth['month'], $viewData['regionname']);
        }

        //
        // Log this event
        //
        $LOG->log("logMonth", L_USER, "log_month_tpl_created", $vmonth['M']->region . ": " . $vmonth['M']->year . "-" . $vmonth['M']->month);
    }
}

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST)) {
    //
    // Sanitize input
    //
    $_POST = sanitize($_POST);

    //
    // Form validation
    //
    $inputError = false;
    if (isset($_POST['btn_search'])) {
        if (!formInputValid('txt_search', 'required|alpha_numeric_dash')) $inputError = true;
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
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $viewData['year'] . $viewData['month'] . "&region=" . $regionfilter . "&group=" . $groupfilter . "&abs=" . $_POST['sel_absence']);
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
        $showAlert = TRUE;
        $alertData['type'] = 'danger';
        $alertData['title'] = $LANG['alert_danger_title'];
        $alertData['subject'] = $LANG['alert_input'];
        $alertData['text'] = $LANG['abs_alert_save_failed'];
        $alertData['help'] = '';
    }
}

//=============================================================================
//
// PREPARE VIEW
//
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
    } else if (!$U->isHidden($usr['username'])) {
        if (
            isAllowed("calendarviewall") or
            (isAllowed("calendarviewgroup") and $UG->shareGroups($usr['username'], $UL->username))
        ) {
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
$j = 0;
foreach ($viewData['months'] as $vmonth) {
    $dayStyles = array();
    for ($i = 1; $i <= $vmonth['dateInfo']['daysInMonth']; $i++) {
        $color = '';
        $bgcolor = '';
        $border = '';
        $dayStyles[$i] = '';
        $hprop = 'hol' . $i;
        $wprop = 'wday' . $i;
        if ($vmonth['M']->$hprop) {
            //
            // This is a holiday. Get the coloring info.
            //
            if ($H->keepWeekendColor($M->$hprop)) {
                //
                // Weekend color shall be kept. So if this a weekend day color it as such.
                //
                if ($vmonth['M']->$wprop == 6 or $vmonth['M']->$wprop == 7) {
                    $color = 'color:#' . $H->getColor($vmonth['M']->$wprop - 4) . ';';
                    $bgcolor = 'background-color:#' . $H->getBgColor($vmonth['M']->$wprop - 4) . ';';
                } else {
                    $color = 'color:#' . $H->getColor($vmonth['M']->$hprop) . ';';
                    $bgcolor = 'background-color:#' . $H->getBgColor($vmonth['M']->$hprop) . ';';
                }
            } else {
                $color = 'color:#' . $H->getColor($vmonth['M']->$hprop) . ';';
                $bgcolor = 'background-color:#' . $H->getBgColor($vmonth['M']->$hprop) . ';';
            }
        } else if ($vmonth['M']->$wprop == 6 or $vmonth['M']->$wprop == 7) {
            //
            // This is a Saturday or Sunday. Get the coloring info.
            //
            $color = 'color:#' . $H->getColor($vmonth['M']->$wprop - 4) . ';';
            $bgcolor = 'background-color:#' . $H->getBgColor($vmonth['M']->$wprop - 4) . ';';
        }

        //
        // Get today style
        //
        $loopDate = date('Y-m-d', mktime(0, 0, 0, $vmonth['month'], $i, $vmonth['year']));
        if ($loopDate == $currDate) {
            $border = 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
        }

        //
        // Build styles
        //
        if (strlen($color) or strlen($bgcolor) or strlen($border)) {
            $dayStyles[$i] = $color . $bgcolor . $border;
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
    $j++;
}

$todayDate = getdate(time());
$viewData['yearToday'] = $todayDate['year'];
$viewData['monthToday'] = sprintf("%02d", $todayDate['mon']);
$viewData['regions'] = $R->getAll();
$viewData['showWeekNumbers'] = $C->read('showWeekNumbers');
$viewData['supportMobile'] = $C->read('supportMobile');
$viewData['firstDayOfWeek'] = $C->read("firstDayOfWeek");

$mobilecols['full'] = $viewData['months'][0]['dateInfo']['daysInMonth'];

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
