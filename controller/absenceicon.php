<?php
/**
 * absenceicon.php
 * 
 * Absence icon page controller
 *
 * @category TeamCal Neo 
* @version 1.0.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
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
$AA = new Absences(); // for the absence type to be edited

if (isset($_GET['id']))
{
   $missingData = FALSE;
   $id = sanitize($_GET['id']);
   if (!$AA->get($id)) $missingData = TRUE;
}
else
{
   $missingData = TRUE;
}

if ($missingData)
{
   /**
    * URL param fail
    */
   $alertData['type'] = 'danger';
   $alertData['title'] = $LANG['alert_danger_title'];
   $alertData['subject'] = $LANG['alert_no_data_subject'];
   $alertData['text'] = $LANG['alert_no_data_text'];
   $alertData['help'] = $LANG['alert_no_data_help'];
   require (WEBSITE_ROOT . '/controller/alert.php');
   die();
}
else
{
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
if (!empty($_POST))
{
   // ,------,
   // | Save |
   // '------'
   if (isset($_POST['btn_save']))
   {
      $AA->id = $_POST['hidden_id'];
      if (isset($_POST['opt_absIcon'])) $AA->icon = $_POST['opt_absIcon']; else $AA->icon = 'times';
         
      //
      // Update the record
      //
      $AA->update($AA->id);
       
      //
      // Go back to absence edit page
      //
      header("Location: index.php?action=".$controller."&id=".$AA->id);
   }
}

//=============================================================================
//
// PREPARE VIEW
//
$viewData['id'] = $AA->id;
$viewData['name'] = $AA->name;
$viewData['icon'] = $AA->icon;

foreach ($faIcons as $faIcon)
{
   $viewData['faIcons'][] = array ('val' => $faIcon, 'name' => proper($faIcon), 'selected' => ($AA->icon == $faIcon)?true:false );
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
