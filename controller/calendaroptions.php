<?php
/**
 * calendaroptions.php
 * 
 * Calendar config page controller
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

// ========================================================================
// Check if allowed
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

// ========================================================================
// Load controller stuff
//

// ========================================================================
// Initialize variables
//
$arrTrustedRoles = array();
$arrCurrYearRoles = array();

// ========================================================================
// Process form
//
//
// ,-------,
// | Apply |
// '-------'
//
if (isset($_POST['btn_caloptApply']))
{
   //
   // Display
   //
   $C->save("todayBorderColor", sanitize($_POST['txt_todayBorderColor']));
   $C->save("todayBorderSize", intval($_POST['txt_todayBorderSize']));
   $C->save("pastDayColor", sanitize($_POST['txt_pastDayColor']));
   if (isset($_POST['chk_showWeekNumbers']) && $_POST['chk_showWeekNumbers']) $C->save("showWeekNumbers", "1"); else $C->save("showWeekNumbers", "0");
   $C->save("repeatHeaderCount", intval($_POST['txt_repeatHeaderCount']));
   $C->save("usersPerPage", intval($_POST['txt_usersPerPage']));
   if (isset($_POST['chk_showAvatars']) && $_POST['chk_showAvatars'] ) $C->save("showAvatars","1"); else $C->save("showAvatars","0");
   if (isset($_POST['chk_showRoleIcons']) && $_POST['chk_showRoleIcons'] ) $C->save("showRoleIcons","1"); else $C->save("showRoleIcons","0");
   if (isset($_POST['chk_showTooltipCount']) && $_POST['chk_showTooltipCount'] ) $C->save("showTooltipCount","1"); else $C->save("showTooltipCount","0");
   if (isset($_POST['chk_supportMobile']) && $_POST['chk_supportMobile']) $C->save("supportMobile", "1"); else $C->save("supportMobile", "0");
   if (isset($_POST['chk_symbolAsIcon']) && $_POST['chk_symbolAsIcon']) $C->save("symbolAsIcon", "1"); else $C->save("symbolAsIcon", "0");
   if (isset($_POST['chk_showTwoMonths']) && $_POST['chk_showTwoMonths']) $C->save("showTwoMonths", "1"); else $C->save("showTwoMonths", "0");
   
   //
   // Filter
   //
   if (isset($_POST['chk_hideDaynotes']) && $_POST['chk_hideDaynotes']) $C->save("hideDaynotes", "1"); else $C->save("hideDaynotes", "0");
   if (isset($_POST['chk_hideManagers']) && $_POST['chk_hideManagers']) $C->save("hideManagers", "1"); else $C->save("hideManagers", "0");
   if (isset($_POST['chk_hideManagerOnlyAbsences'])) $C->save("hideManagerOnlyAbsences", "1"); else $C->save("hideManagerOnlyAbsences", "0");
   if (isset($_POST['chk_showUserRegion']) && $_POST['chk_showUserRegion']) $C->save("showUserRegion", "1"); else $C->save("showUserRegion", "0");
   if (isset($_POST['sel_trustedRoles']))
   {
      foreach ( $_POST['sel_trustedRoles'] as $role )
      {
         $arrTrustedRoles[] = $role;
      }
      $trustedRoles = implode(',', $arrTrustedRoles);
      $C->save("trustedRoles", $trustedRoles);
   }
    
   //
   // Settings
   //
   if ($_POST['opt_firstDayOfWeek']) $C->save("firstDayOfWeek", $_POST['opt_firstDayOfWeek']);
   if (isset($_POST['chk_satBusi']) && $_POST['chk_satBusi']) $C->save("satBusi", "1"); else $C->save("satBusi", "0");
   if (isset($_POST['chk_sunBusi']) && $_POST['chk_sunBusi']) $C->save("sunBusi", "1"); else $C->save("sunBusi", "0");
   if ($_POST['sel_defregion']) $C->save("defregion", $_POST['sel_defregion']); else $C->save("defregion", "default");
   if (isset($_POST['chk_showRegionButton']) && $_POST['chk_showRegionButton']) $C->save("showRegionButton", "1"); else $C->save("showRegionButton", "0");
   if ($_POST['opt_defgroupfilter']) $C->save("defgroupfilter", $_POST['opt_defgroupfilter']); else $C->save("defgroupfilter", 'All');
   if (isset($_POST['chk_currentYearOnly']) && $_POST['chk_currentYearOnly']) $C->save("currentYearOnly", "1"); else $C->save("currentYearOnly", "0");
   if (isset($_POST['sel_currentYearRoles']))
   {
      foreach ( $_POST['sel_currentYearRoles'] as $role )
      {
         $arrCurrYearRoles[] = $role;
      }
      $currYearRoles = implode(',', $arrCurrYearRoles);
      $C->save("currYearRoles", $currYearRoles);
   }
   
   if (isset($_POST['chk_takeover']) && $_POST['chk_takeover']) $C->save("takeover", "1"); else $C->save("takeover", "0");
   if (isset($_POST['chk_notificationsAllGroups']) && $_POST['chk_notificationsAllGroups']) $C->save("notificationsAllGroups", "1"); else $C->save("notificationsAllGroups", "0");

   //
   // Statistics
   //
   if ($_POST['sel_statsDefaultColorAbsences']) $C->save("statsDefaultColorAbsences", $_POST['sel_statsDefaultColorAbsences']); else $C->save("statsDefaultColorAbsences", "red");
   if ($_POST['sel_statsDefaultColorPresences']) $C->save("statsDefaultColorPresences", $_POST['sel_statsDefaultColorPresences']); else $C->save("statsDefaultColorPresences", "green");
   if ($_POST['sel_statsDefaultColorAbsencetype']) $C->save("statsDefaultColorAbsencetype", $_POST['sel_statsDefaultColorAbsencetype']); else $C->save("statsDefaultColorAbsencetype", "cyan");
   if ($_POST['sel_statsDefaultColorRemainder']) $C->save("statsDefaultColorRemainder", $_POST['sel_statsDefaultColorRemainder']); else $C->save("statsDefaultColorRemainder", "orange");
    
   //
   // Summary
   //
   if (isset($_POST['chk_includeSummary']) && $_POST['chk_includeSummary']) $C->save("includeSummary", "1"); else $C->save("includeSummary", "0");
   if (isset($_POST['chk_showSummary']) && $_POST['chk_showSummary']) $C->save("showSummary", "1"); else $C->save("showSummary", "0");
    
   //
   // Log this event
   //
   $LOG->log("logCalendarOptions", $UL->username, "log_calopt");
   header("Location: index.php?action=".$controller);
}

// ========================================================================
// Prepare data for the view
//
//
// Display
//
$caloptData['display'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'todayBorderColor', 'type' => 'color', 'value' => $C->read("todayBorderColor"), 'maxlength' => '6' ),
   array ( 'prefix' => 'calopt', 'name' => 'todayBorderSize', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("todayBorderSize"), 'maxlength' => '2' ),
   array ( 'prefix' => 'calopt', 'name' => 'pastDayColor', 'type' => 'color', 'value' => $C->read("pastDayColor"), 'maxlength' => '6' ),
   array ( 'prefix' => 'calopt', 'name' => 'showWeekNumbers', 'type' => 'check', 'values' => '', 'value' => $C->read("showWeekNumbers") ),
   array ( 'prefix' => 'calopt', 'name' => 'repeatHeaderCount', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("repeatHeaderCount"), 'maxlength' => '4' ),
   array ( 'prefix' => 'calopt', 'name' => 'usersPerPage', 'type' => 'text', 'placeholder' => '', 'value' => $C->read("usersPerPage"), 'maxlength' => '4' ),
   array ( 'prefix' => 'calopt', 'name' => 'showAvatars', 'type' => 'check', 'values' => '', 'value' => $C->read("showAvatars") ),
   array ( 'prefix' => 'calopt', 'name' => 'showRoleIcons', 'type' => 'check', 'values' => '', 'value' => $C->read("showRoleIcons") ),
   array ( 'prefix' => 'calopt', 'name' => 'showTooltipCount', 'type' => 'check', 'values' => '', 'value' => $C->read("showTooltipCount") ),
   array ( 'prefix' => 'calopt', 'name' => 'supportMobile', 'type' => 'check', 'values' => '', 'value' => $C->read("supportMobile") ),
   array ( 'prefix' => 'calopt', 'name' => 'symbolAsIcon', 'type' => 'check', 'values' => '', 'value' => $C->read("symbolAsIcon") ),
   array ( 'prefix' => 'calopt', 'name' => 'showTwoMonths', 'type' => 'check', 'values' => '', 'value' => $C->read("showTwoMonths") ),
);

//
// Filter
//
$roles = $RO->getAll();
$arrTrustedRoles = explode(',', $C->read("trustedRoles"));
foreach ($roles as $role)
{
   $caloptData['roleList'][] = array ('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'],$arrTrustedRoles))?true:false );
}
$caloptData['filter'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'hideManagers', 'type' => 'check', 'values' => '', 'value' => $C->read("hideManagers") ),
   array ( 'prefix' => 'calopt', 'name' => 'hideDaynotes', 'type' => 'check', 'values' => '', 'value' => $C->read("hideDaynotes") ),
   array ( 'prefix' => 'calopt', 'name' => 'hideManagerOnlyAbsences', 'type' => 'check', 'values' => '', 'value' => $C->read("hideManagerOnlyAbsences") ),
   array ( 'prefix' => 'calopt', 'name' => 'showUserRegion', 'type' => 'check', 'values' => '', 'value' => $C->read("showUserRegion") ),
   array ( 'prefix' => 'calopt', 'name' => 'trustedRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList'] ),
);

//
// Options
//
$regions = $R->getAllNames();
foreach ($regions as $region)
{
   $caloptData['regionList'][] = array ('val' => $region, 'name' => $region, 'selected' => ($C->read("defregion") == $region)?true:false );
}
$arrCurrYearRoles = explode(',', $C->read("currYearRoles"));
foreach ($roles as $role)
{
   $caloptData['roleList2'][] = array ('val' => $role['id'], 'name' => $role['name'], 'selected' => (in_array($role['id'],$arrCurrYearRoles))?true:false );
}
$caloptData['options'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'firstDayOfWeek', 'type' => 'radio', 'values' => array ('1', '7'), 'value' => $C->read("firstDayOfWeek") ),
   array ( 'prefix' => 'calopt', 'name' => 'satBusi', 'type' => 'check', 'values' => '', 'value' => $C->read("satBusi") ),
   array ( 'prefix' => 'calopt', 'name' => 'sunBusi', 'type' => 'check', 'values' => '', 'value' => $C->read("sunBusi") ),
   array ( 'prefix' => 'calopt', 'name' => 'defregion', 'type' => 'list', 'values' => $caloptData['regionList'] ),
   array ( 'prefix' => 'calopt', 'name' => 'showRegionButton', 'type' => 'check', 'values' => '', 'value' => $C->read("showRegionButton") ),
   array ( 'prefix' => 'calopt', 'name' => 'defgroupfilter', 'type' => 'radio', 'values' => array ('all', 'allbygroup'), 'value' => $C->read("defgroupfilter") ),
   array ( 'prefix' => 'calopt', 'name' => 'currentYearOnly', 'type' => 'check', 'values' => '', 'value' => $C->read("currentYearOnly") ),
   array ( 'prefix' => 'calopt', 'name' => 'currentYearRoles', 'type' => 'listmulti', 'values' => $caloptData['roleList2'] ),
   array ( 'prefix' => 'calopt', 'name' => 'takeover', 'type' => 'check', 'values' => '', 'value' => $C->read("takeover") ),
   array ( 'prefix' => 'calopt', 'name' => 'notificationsAllGroups', 'type' => 'check', 'values' => '', 'value' => $C->read("notificationsAllGroups") ),
);

//
// Statistics
//
$statsPages = array('Absences', 'Presences', 'Absencetype', 'Remainder');
$colors = array('blue', 'cyan', 'green', 'grey', 'magenta', 'orange', 'purple', 'red', 'yellow');
foreach ($statsPages as $statsPage)
{
   $statsColorArray[$statsPage] = array();
   foreach ($colors as $color)
   {
      $statsColorArray[$statsPage][] = array ( 'val' => $color, 'name' => $LANG[$color], 'selected' => ($C->read("statsDefaultColor".$statsPage) == $color)?true:false );
   }
}
$caloptData['stats'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'statsDefaultColorAbsences', 'type' => 'list', 'values' => $statsColorArray['Absences'] ),
   array ( 'prefix' => 'calopt', 'name' => 'statsDefaultColorPresences', 'type' => 'list', 'values' => $statsColorArray['Presences'] ),
   array ( 'prefix' => 'calopt', 'name' => 'statsDefaultColorAbsencetype', 'type' => 'list', 'values' => $statsColorArray['Absencetype'] ),
   array ( 'prefix' => 'calopt', 'name' => 'statsDefaultColorRemainder', 'type' => 'list', 'values' => $statsColorArray['Remainder'] ),
);

//
// Summary
//
$caloptData['summary'] = array (
   array ( 'prefix' => 'calopt', 'name' => 'includeSummary', 'type' => 'check', 'values' => '', 'value' => $C->read("includeSummary") ),
   array ( 'prefix' => 'calopt', 'name' => 'showSummary', 'type' => 'check', 'values' => '', 'value' => $C->read("showSummary") ),
);

/**
 * ========================================================================
 * Show view
 */
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
