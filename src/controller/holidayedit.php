<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * Holiday Edit Controller
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
// CHECK URL PARAMETERS
//
$HH = new Holidays(); // for the holiday to be edited

if (isset($_GET['id'])) {
    $missingData = FALSE;
    $id = sanitize($_GET['id']);
    if (!$HH->get($id)) $missingData = TRUE;
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

//=============================================================================
//
// LOAD CONTROLLER RESOURCES
//

//=============================================================================
//
// VARIABLE DEFAULTS
//
$inputAlert = array();

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
    // Load sanitized form info for the view
    //
    $viewData['id'] = $_POST['hidden_id'];
    $viewData['name'] = $_POST['txt_name'];
    $viewData['description'] = $_POST['txt_description'];
    $viewData['color'] = $_POST['txt_color'];
    $viewData['bgcolor'] = $_POST['txt_bgcolor'];
    if (isset($_POST['chk_businessday'])) $viewData['businessday'] = '1';
    else $viewData['businessday'] = '0';
    if (isset($_POST['chk_noabsence'])) $viewData['noabsence'] = '1';
    else $viewData['noabsence'] = '0';
    if (isset($_POST['chk_keepweekendcolor'])) $viewData['keepweekendcolor'] = '1';
    else $viewData['keepweekendcolor'] = '0';

    //
    // Form validation
    //
    $inputError = false;
    if (isset($_POST['btn_holidayUpdate'])) {
        if (!formInputValid('txt_name', 'required|alpha_numeric_dash_blank')) $inputError = true;
        if (!formInputValid('txt_description', 'alpha_numeric_dash_blank_special')) $inputError = true;
        if (!formInputValid('txt_color', 'required|hexadecimal')) $inputError = true;
        if (!formInputValid('txt_bgcolor', 'required|hexadecimal')) $inputError = true;
    }

    if (!$inputError) {
        // ,--------,
        // | Update |
        // '--------'
        if (isset($_POST['btn_holidayUpdate'])) {
            $id  = $_POST['hidden_id'];
            $HH->name = $_POST['txt_name'];
            $HH->description = $_POST['txt_description'];
            $HH->color = $_POST['txt_color'];
            $HH->bgcolor = $_POST['txt_bgcolor'];
            if (isset($_POST['chk_businessday'])) $HH->businessday = '1';
            else $HH->businessday = '0';
            if (isset($_POST['chk_noabsence'])) $HH->noabsence = '1';
            else $HH->noabsence = '0';
            if (isset($_POST['chk_keepweekendcolor'])) $HH->keepweekendcolor = '1';
            else $HH->keepweekendcolor = '0';

            $HH->update($id);

            //
            // Send notification e-mails to the subscribers of user events
            //
            if ($C->read("emailNotifications")) {
                sendHolidayEventNotifications("changed", $HH->name, $HH->description, $HH->description);
            }

            //
            // Log this event
            //
            $LOG->logEvent("logHoliday", L_USER, "log_hol_updated", $HH->name);

            //
            // Success
            //
            $showAlert = TRUE;
            $alertData['type'] = 'success';
            $alertData['title'] = $LANG['alert_success_title'];
            $alertData['subject'] = $LANG['hol_alert_edit'];
            $alertData['text'] = $LANG['hol_alert_edit_success'];
            $alertData['help'] = '';
        } else {
            //
            // Input validation failed
            //
            $showAlert = TRUE;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_input'];
            $alertData['text'] = $LANG['hol_alert_save_failed'];
            $alertData['help'] = '';
        }
    }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['id'] = $HH->id;
$viewData['name'] = $HH->name;
$viewData['description'] = $HH->description;
$viewData['color'] = $HH->color;
$viewData['bgcolor'] = $HH->bgcolor;
$viewData['businessday'] = $HH->businessday;
$viewData['noabsence'] = $HH->noabsence;
$viewData['keepweekendcolor'] = $HH->keepweekendcolor;

$viewData['holiday'] = array(
    array('prefix' => 'hol', 'name' => 'name', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['name'], 'maxlength' => '40', 'mandatory' => true, 'error' => (isset($inputAlert['name']) ? $inputAlert['name'] : '')),
    array('prefix' => 'hol', 'name' => 'description', 'type' => 'text', 'placeholder' => '', 'value' => $viewData['description'], 'maxlength' => '100', 'error' => (isset($inputAlert['description']) ? $inputAlert['description'] : '')),
    array('prefix' => 'hol', 'name' => 'color', 'type' => 'color', 'value' => $viewData['color'], 'maxlength' => '6', 'mandatory' => true, 'error' => (isset($inputAlert['color']) ? $inputAlert['color'] : '')),
    array('prefix' => 'hol', 'name' => 'bgcolor', 'type' => 'color', 'value' => $viewData['bgcolor'], 'maxlength' => '6', 'mandatory' => true, 'error' => (isset($inputAlert['bgcolor']) ? $inputAlert['bgcolor'] : '')),
    array('prefix' => 'hol', 'name' => 'keepweekendcolor', 'type' => 'check', 'value' => $viewData['keepweekendcolor']),
    array('prefix' => 'hol', 'name' => 'businessday', 'type' => 'check', 'value' => $viewData['businessday']),
    array('prefix' => 'hol', 'name' => 'noabsence', 'type' => 'check', 'value' => $viewData['noabsence']),
);

//=============================================================================
//
// SHOW VIEW
//
require WEBSITE_ROOT . '/views/header.php';
require WEBSITE_ROOT . '/views/menu.php';
include WEBSITE_ROOT . '/views/' . $controller . '.php';
require WEBSITE_ROOT . '/views/footer.php';
