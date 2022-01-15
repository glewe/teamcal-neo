<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Remainder Controller
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2022 by George Lewe
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

//-----------------------------------------------------------------------------
//
// Build array of users to show
//

//
// First, load all users we have that are not hidden
//
$users = $U->getAllButHidden();

//
// Second, remove all users not covered by the group filter
//
if (isset($_GET['group'])) {
    $groupfilter = sanitize($_GET['group']);
} else {
    $groupfilter = 'all';
}

$viewData['groupid'] = $groupfilter;
if ($groupfilter == "all") {
    $viewData['group'] = $LANG['all'];
    //
    // Remove all users from array that the current user is not manager of
    //
    $calusers = array();
    foreach ($users as $key => $usr) {
        if (L_USER == $usr['username'] or L_USER == 'admin' or $UG->isGroupManagerOfUser(L_USER, $usr['username'])) {
            $calusers[] = $usr;
        }
    }
    $users = $calusers;
} else {
    $viewData['group'] = $G->getNameById($groupfilter);
    //
    // Remove all users from array that are not in requested group.
    //
    $calusers = array();
    foreach ($users as $key => $usr) {
        if (L_USER == 'admin' or ($UG->isMemberOrGuestOfGroup($usr['username'], $groupfilter) and ($UG->isGroupManagerOfUser(L_USER, $usr['username']) or L_USER == $usr['username']))) {
            $calusers[] = $usr;
        }
    }
    $users = $calusers;
}

//-----------------------------------------------------------------------------
//
// Search Reset
//
if (isset($_GET['search']) and $_GET['search'] == "reset") {
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller);
    die();
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
$viewData['search'] = '';
$viewData['year'] = date("Y");

//=============================================================================
//
// VARIABLE DEFAULTS
//

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
        // ,---------------,
        // | Select Group  |
        // '---------------'
        if (isset($_POST['btn_group'])) {
            if (L_USER) {
                $UO->save($UL->username, 'calfilterGroup', $_POST['sel_group']);
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?action=" . $controller . "&month=" . $monthfilter . "&region=" . $regionfilter . "&group=" . $_POST['sel_group'] . "&abs=" . $absfilter);
            die();
        }
        // ,--------,
        // | Search |
        // '--------'
        elseif (isset($_POST['btn_search'])) {
            $viewData['search'] = $_POST['txt_search'];
            unset($users);
            $users = $U->getAllLike($_POST['txt_search']);
        }
        // ,-------------,
        // | Select Year |
        // '-------------'
        elseif (isset($_POST['btn_year'])) {
            $viewData['year'] = $_POST['sel_year'];
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

$countFrom = $viewData['year'] . '0101';
$countTo = $viewData['year'] . '1231';

if ($groupfilter == 'all') {

    $viewData['groups'] = $viewData['allGroups'];
} else {

    $viewData['groups'] = $G->getRowById($groupfilter);
}

$viewData['users'] = array();
$i = 0;

foreach ($users as $user) {

    $U->findByName($user['username']);

    $viewData['users'][$i]['username'] = $user['username'];
    if ($U->firstname != "") $viewData['users'][$i]['dispname'] = $U->lastname . ", " . $U->firstname;
    else $viewData['users'][$i]['dispname'] = $U->lastname;
    $viewData['users'][$i]['dispname'] .= ' (' . $U->username . ')';
    $viewData['users'][$i]['role'] = $RO->getNameById($U->role);
    $viewData['users'][$i]['color'] = $RO->getColorById($U->role);

    //
    // Determine attributes
    //
    $viewData['users'][$i]['locked'] = false;
    $viewData['users'][$i]['hidden'] = false;
    $viewData['users'][$i]['onhold'] = false;
    $viewData['users'][$i]['verify'] = false;
    if ($U->locked) $viewData['users'][$i]['locked'] = true;
    if ($U->hidden) $viewData['users'][$i]['hidden'] = true;
    if ($U->onhold) $viewData['users'][$i]['onhold'] = true;
    if ($U->verify) $viewData['users'][$i]['verify'] = true;

    $viewData['users'][$i]['created'] = $U->created;
    $viewData['users'][$i]['last_login'] = $U->last_login;

    $i++;
}

//=============================================================================
//
// SHOW VIEW
//
require(WEBSITE_ROOT . '/views/header.php');
require(WEBSITE_ROOT . '/views/menu.php');
include(WEBSITE_ROOT . '/views/' . $controller . '.php');
require(WEBSITE_ROOT . '/views/footer.php');
