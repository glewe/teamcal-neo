<?php
/**
 * absum.php
 * 
 * Absence Summary page controller
 *
 * @category TemCal Neo 
 * @version 0.5.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// CHECK PERMISSION
//
if (!isAllowed($CONF['controllers'][$controller]->permission))
{
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['alert_alert_title'];
   $alertData['subject'] = $LANG['alert_not_allowed_subject'];
   $alertData['text'] = $LANG['alert_not_allowed_text'];
   $alertData['help'] = $LANG['alert_not_allowed_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}

//=============================================================================
//
// CHECK URL PARAMETERS
//
if (isset($_GET['user']))
{
   $missingData = FALSE;
   $caluser = sanitize($_GET['user']);
   if (!$U->findByName($caluser)) 
   {
      $missingData = TRUE;
   }
}
else
{
   $missingData = TRUE;
}

if ($missingData)
{
   //
   // URL param fail
   //
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
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
$users = $U->getAll();
$inputAlert = array();
$viewData['year'] = date("Y");

//=============================================================================
//
// PROCESS FORM
//
if (!empty($_POST))
{
   //
   // Sanitize input
   //
   $_POST = sanitize($_POST);
    
   //
   // Form validation
   //
   $inputError = false;
   
   //
   // TODO
   // Validate input data. If something is wrong or missing, set $inputError = true
   //
   
   if (!$inputError)
   {
      // ,-------------,
      // | Select User |
      // '-------------'
      if (isset($_POST['btn_user']))
      {
         header("Location: " . $_SERVER['PHP_SELF'] . "?action=".$controller . "&user=" . $_POST['sel_user']);
         die();
      }
      // ,-------------,
      // | Select Year |
      // '-------------'
      elseif (isset($_POST['btn_year']))
      {
         $viewData['year'] = $_POST['sel_year'];
      }
   }
   else
   {
      //
      // Input validation failed
      //
      $showAlert = TRUE;
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_danger_title'];
      $alertData['subject'] = $LANG['alert_input'];
      $alertData['text'] = $LANG['register_alert_failed'];
      $alertData['help'] = '';
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['username'] = $caluser;
$viewData['fullname'] = $U->getFullname($caluser);

$viewData['users'] = array();
foreach ($users as $usr)
{
   $viewData['users'][] = array ('username' => $usr['username'], 'lastfirst' => $U->getLastFirst($usr['username']));
}

$viewData['from'] = $viewData['year'] . '-01-01';
$viewData['to'] = $viewData['year'] . '-12-31';

$viewData['absences'] = array();
$absences = $A->getAll();
foreach ($absences as $abs)
{
   $count = 0;
   if ($A->get($abs['id']) AND !$A->counts_as_present)
   {
      $countFrom = str_replace('-', '' , $viewData['from']);
      $countTo = str_replace('-', '' , $viewData['to']);
      $count += countAbsence($caluser, $abs['id'], $countFrom, $countTo, false, false);
   }
   
   $contingent = $LANG['absum_unlimited'];
   if ($abs['allowance']) $contingent = $abs['allowance'] + $AL->getCarryover($caluser, $abs['id']);

   $remainder = $LANG['absum_unlimited'];
   if ($abs['allowance']) $remainder = $contingent - $count;
    
   $viewData['absences'][] = array (
      'icon' => $abs['icon'],
      'bgcolor' => $abs['bgcolor'],
      'color' => $abs['color'],
      'name' => $abs['name'],
      'contingent' => $contingent,
      'taken' => $count,
      'remainder' => $remainder,
   );
}


//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
