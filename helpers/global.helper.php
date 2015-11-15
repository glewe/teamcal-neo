<?php
/**
 * global.helper.php
 *
 * Collection of global helper functions
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

// ---------------------------------------------------------------------------
/**
 * Builds the menu based on permissions
 *
 * @return array menu
 */
function buildMenu()
{
   global $C, $CONF, $L, $UL;
   
   /**
    * Create default public menu
    */
   $mnu = array (
      "mnu_app" => TRUE,
      "mnu_app_home" => TRUE,
      "mnu_view" => FALSE,
      "mnu_view_calendar" => FALSE,
      "mnu_view_year" => FALSE,
      "mnu_view_messages" => FALSE,
      "mnu_edit" => FALSE,
      "mnu_edit_calendaredit" => FALSE,
      "mnu_edit_monthedit" => FALSE,
      "mnu_edit_messageedit" => FALSE,
      "mnu_admin" => FALSE,
      "mnu_admin_config" => FALSE,
      "mnu_admin_calendaroptions" => FALSE,
      "mnu_admin_perm" => FALSE,
      "mnu_admin_users" => FALSE,
      "mnu_admin_groups" => FALSE,
      "mnu_admin_absences" => FALSE,
      "mnu_admin_holidays" => FALSE,
      "mnu_admin_regions" => FALSE,
      "mnu_admin_declination" => FALSE,
      "mnu_admin_database" => FALSE,
      "mnu_admin_systemlog" => FALSE,
      "mnu_admin_env" => FALSE,
      "mnu_admin_phpinfo" => FALSE,
      "mnu_help" => TRUE,
      "mnu_help_help" => TRUE,
      "mnu_help_imprint" => TRUE,
      "mnu_help_about" => TRUE, 
      "mnu_user_login" => TRUE,
      "mnu_user_logout" => FALSE,
      "mnu_user_register" => FALSE,
      "mnu_user_profile" => FALSE,
   );
   
   /**
    * Now enable entries based on permission
    */
   if ($L->checkLogin())
   {
      $mnu['mnu_user_login'] = FALSE;
      $mnu['mnu_user_logout'] = TRUE;
      $mnu['mnu_user_profile'] = TRUE;
      
      if ($C->read("activateMessages"))
      {
         $mnu['mnu_view'] = TRUE;
         $mnu['mnu_view_messages'] = TRUE;
      
         if (isAllowed("messageedit"))
         {
            $mnu['mnu_edit'] = TRUE;
            $mnu['mnu_edit_messageedit'] = TRUE;
         }
      }
   }
   else
   {
      if ($C->read("allowRegistration")) $mnu['mnu_user_register'] = TRUE;
   }
   
   if (isAllowed("calendarview"))
   {
      $mnu['mnu_view'] = TRUE;
      $mnu['mnu_view_calendar'] = TRUE;
   }
   
   if (isAllowed("year"))
   {
      $mnu['mnu_view'] = TRUE;
      $mnu['mnu_view_year'] = TRUE;
   }
   
   if (isAllowed("calendaredit") AND 
       (isAllowed("calendareditall") OR isAllowed("calendareditgroup") OR isAllowed("calendareditown")) 
   )
   {
      $mnu['mnu_edit'] = TRUE;
      $mnu['mnu_edit_calendaredit'] = TRUE;
   }
   
   if (isAllowed("monthedit"))
   {
      $mnu['mnu_edit'] = TRUE;
      $mnu['mnu_edit_monthedit'] = TRUE;
   }
   
   if (isAllowed("config"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_config'] = TRUE;
   }
   
   if (isAllowed("calendaroptions"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_calendaroptions'] = TRUE;
   }
   
   if (isAllowed("permissions"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_perm'] = TRUE;
   }
   
   if (isAllowed("users"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_users'] = TRUE;
   }
   
   if (isAllowed("groups"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_groups'] = TRUE;
   }
   
   if (isAllowed("roles"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_roles'] = TRUE;
   }
   
   if (isAllowed("absences"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_absences'] = TRUE;
   }
   
   if (isAllowed("holidays"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_holidays'] = TRUE;
   }
   
   if (isAllowed("regions"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_regions'] = TRUE;
   }
   
   if (isAllowed("declination"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_declination'] = TRUE;
   }
   
   if (isAllowed("database"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_database'] = TRUE;
   }
   
   if (isAllowed("log"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_systemlog'] = TRUE;
   }
   
   if (isAllowed("phpinfo"))
   {
      $mnu['mnu_admin'] = TRUE;
      $mnu['mnu_admin_env'] = TRUE;
      $mnu['mnu_admin_phpinfo'] = TRUE;
   }
   
   return $mnu;
}

// ---------------------------------------------------------------------------
/**
 * Cleans and returns a given string.
 * Not called directly, but used by function sanitize()
 *
 * @param string $input String to clean
 */
function cleanInput($input)
{
   /**
    * Strip out javascript
    * Strip out HTML tags
    * Strip style tags properly
    * Strip multi-line comments
    */
   $search = array (
      '@<script[^>]*?>.*?</script>@si',
      '@<[\/\!]*?[^<>]*?>@si',
      '@<style[^>]*?>.*?</style>@siU',
      '@<![\s\S]*?--[ \t\n\r]*>@' 
   );
   $output = preg_replace($search, '', $input);
   return $output;
}

// ---------------------------------------------------------------------------
/**
 * Computes several date related information for a given date
 *
 * @param string $year 4 digit number of the year (example: 2014)
 * @param string $month 2 digit number of the month (example: 11)
 * @param string $day 1 or 2 digit number of the day (example: 1, 19)
 *
 * @return array $dateInfo Full dates are returned in ISO 8601 format, e.g. 2014-03-03
 */
function dateInfo($year, $month, $day='1')
{
   global $LANG;
    
   $dateInfo = array ();
    
   $myts = strtotime($year . '-' . $month . '-' . $day);
   $mydate = getdate($myts);
    
   $dateInfo['dd'] = sprintf("%02d", $mydate['mday']); // Numeric representation of todays' day of the month, 2 digits
   $dateInfo['mm'] = sprintf("%02d", $mydate['mon']); // Numeric representation of todays' month, 2 digits
   $dateInfo['year'] = $mydate['year']; // Numeric representation of todays' year, 4 digits
   $dateInfo['month'] = $mydate['month']; // Numeric representation of todays' month, 2 digits
   $dateInfo['daysInMonth'] = date("t", $myts); // Number of days in current month
    
   /**
    * Current day
    * ISO 8601 formatted date of today, e.g.
    * 2014-03-03
   */
   $dateInfo['ISO'] = $dateInfo['year'] . '-' . $dateInfo['mm'] . '-' . $dateInfo['dd'];
    
   /**
    * Weekday
    * 1 = Monday, 2 = Tuesday, ..., 7 = Sunday
    */
   $dateInfo['wday'] = date("N", $myts);
   $dateInfo['weekdayShort'] = $LANG['weekdayShort'][$dateInfo['wday']];
   $dateInfo['weekdayLong'] = $LANG['weekdayLong'][$dateInfo['wday']];

   /**
    * Week number
    */
   $dateInfo['week'] = date('W', $myts);
    
   /**
    * Current month
    * - ISO 8601 formatted date of the first day of the current month, e.g. 2014-03-01
    * - ISO 8601 formatted date of the last day of the current month, e.g. 2014-03-31
   */
   $dateInfo['monthname'] = $LANG['monthnames'][$mydate['mon']];
   $dateInfo['firstOfMonth'] = $dateInfo['year'] . '-' . $dateInfo['mm'] . '-01';
   $dateInfo['lastOfMonth'] = $dateInfo['year'] . '-' . $dateInfo['mm'] . '-' . $dateInfo['daysInMonth'];
    
   /**
    * Current year
    */
   $dateInfo['firstOfYear'] = $dateInfo['year'] . "-01-01";
   $dateInfo['lastOfYear'] = $dateInfo['year'] . "-12-31";
    
   /**
    * Current quarter and half year
    */
   switch ($dateInfo['mm'])
   {
      case 1 :
      case 2 :
      case 3 :
         $dateInfo['firstOfQuarter'] = $dateInfo['year'] . "-01-01";
         $dateInfo['lastOfQuarter'] = $dateInfo['year'] . "-03-31";
         $dateInfo['firstOfHalf'] = $dateInfo['year'] . "-01-01";
         $dateInfo['lastOfHalf'] = $dateInfo['year'] . "-06-30";
         break;
      case 4 :
      case 5 :
      case 6 :
         $dateInfo['firstOfQuarter'] = $dateInfo['year'] . "-04-01";
         $dateInfo['lastOfQuarter'] = $dateInfo['year'] . "-06-30";
         $dateInfo['firstOfHalf'] = $dateInfo['year'] . "-01-01";
         $dateInfo['lastOfHalf'] = $dateInfo['year'] . "-06-30";
         break;
      case 7 :
      case 8 :
      case 9 :
         $dateInfo['firstOfQuarter'] = $dateInfo['year'] . "-07-01";
         $dateInfo['lastOfQuarter'] = $dateInfo['year'] . "-09-30";
         $dateInfo['firstOfHalf'] = $dateInfo['year'] . "-07-01";
         $dateInfo['lastOfHalf'] = $dateInfo['year'] . "-12-31";
         break;
      case 10 :
      case 11 :
      case 12 :
         $dateInfo['firstOfQuarter'] = $dateInfo['year'] . "-10-01";
         $dateInfo['lastOfQuarter'] = $dateInfo['year'] . "-12-31";
         $dateInfo['firstOfHalf'] = $dateInfo['year'] . "-07-01";
         $dateInfo['lastOfHalf'] = $dateInfo['year'] . "-12-31";
         break;
   }
   return $dateInfo;
}

// ---------------------------------------------------------------------------
/**
 * Checks whether a string ends with a given suffix
 *
 * @param string $haystack String to check
 * @param string $needle Suffix to look for
 * 
 * @return boolean True or False
 */
function endsWith($haystack, $needle) 
{
   // search forward starting from end minus needle length characters
   return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
}

// ---------------------------------------------------------------------------
/**
 * Validates a form field against a ruleset
 *
 * $ruleset can be a string of several rules, separated by |. However, only
 * use one that requires $param. The $param rules are:
 * - match
 * - regex_match
 * - min_length
 * - max_length
 * - exact_length
 *
 * @param string $field The form field name to check
 * @param string $ruleset Ruleset to check against
 * @param string $param Second value used by certain rule checks
 * 
 * @return bool FALSE or first broken rule
 */
function formInputValid($field, $ruleset, $param = '')
{
   global $_POST, $inputAlert, $LANG;
   
   $rules = explode('|', $ruleset);
   $label = explode('_', $field);
   
   /**
    * Sanitize?
    */
   if (in_array('sanitize', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]))
      {
         $_POST[$field] = sanitize($_POST[$field]);
      }
   }
   
   /**
    * Required field submitted?
    */
   if (in_array('required', $rules))
   {
      if (!isset($_POST[$field]) or !strlen($_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_required'];
         return false;
      }
   }
   
   /**
    * Alpha characters?
    */
   if (in_array('alpha', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([a-z])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_alpha'];
         return false;
      }
   }
   
   /**
    * Alphanumeric characters?
    */
   if (in_array('alpha_numeric', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([a-z0-9])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_alpha_numeric'];
         return false;
      }
   }
   
   /**
    * Alphanumeric plus dash and underscore?
    */
   if (in_array('alpha_numeric_dash', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([a-z0-9_-])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_alpha_numeric_dash'];
         return false;
      }
   }
   
   /**
    * Alphanumeric plus dash, underscore and blank?
    */
   if (in_array('alpha_numeric_dash_blank', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([ a-z0-9_-])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_alpha_numeric_dash_blank'];
         return false;
      }
   }
   
   /**
    * Alphanumeric plus dash, underscore, blank and dot?
    */
   if (in_array('alpha_numeric_dash_blank_dot', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([ a-z0-9._-])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_alpha_numeric_dash_blank_dot'];
         return false;
      }
   }
   
   /**
    * Alphanumeric plus dash, underscore, blank and special?
    */
   if (in_array('alpha_numeric_dash_blank_special', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([ a-z0-9'!@#$%^&*()_-])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_alpha_numeric_dash_blank_special'];
         return false;
      }
   }
   
   /**
    * Date-only in ISO 8601 format YYYY-MM-DD?
    */
   if (in_array('date', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", trim($_POST[$field])))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_date'];
         return false;
      }
   }
   
   /**
    * Valid email?
    */
   if (in_array('email', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !validEmail($_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_email'];
         return false;
      }
   }
   
   /**
    * Equals?
    */
   if (in_array('equals', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and is_numeric($_POST[$field]) and !$_POST[$field] == $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_equal'], $field, $param);
         return false;
      }
   }
   
   /**
    * Equals string?
    */
   if (in_array('equals_string', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and $_POST[$field] != $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_equal_string'], $param);
         return false;
      }
   }
   
   /**
    * Exact length?
    */
   if (in_array('exact_length', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and mb_strlen($_POST[$field]) != $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_exact_length'], $param);
         return false;
      }
   }
   
   /**
    * Greater than?
    */
   if (in_array('greater_than', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and is_numeric($_POST[$field]) and !$_POST[$field] > $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_greater_than'], $param);
         return false;
      }
   }
   
   /**
    * Hexadecimal
    */
   if (in_array('hexadecimal', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([a-f0-9])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_hexadecimal'];
         return false;
      }
   }
   
   /**
    * IP address?
    */
   if (in_array('ip_address', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !filter_var($_POST[$field], FILTER_VALIDATE_IP))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_ip_address'];
         return false;
      }
   }
   
   /**
    * Less than?
    */
   if (in_array('less_than', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and is_numeric($_POST[$field]) and !$_POST[$field] < $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_less_than'], $param);
         return false;
      }
   }
   
   /**
    * Matches another field?
    */
   if (in_array('match', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and isset($_POST[$param]) and strlen($_POST[$param]) and $_POST[$field] != $_POST[$param])
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_match'], $param);
         return false;
      }
   }
   
   /**
    * Maximal length?
    */
   if (in_array('max_length', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and mb_strlen($_POST[$field]) > $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_max_length'], $param);
         return false;
      }
   }
   
   /**
    * Minimal length?
    */
   if (in_array('min_length', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and mb_strlen($_POST[$field]) < $param)
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_min_length'], $param);
         return false;
      }
   }
   
   /**
    * Numeric?
    */
   if (in_array('numeric', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !is_numeric($_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_numeric'];
         return false;
      }
   }
   
   /**
    * Password low strength?
    */
   if (in_array('pwdlow', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^.*(?=.{4,})[a-zA-Z0-9!@#$%^&*()]+$/", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_pwdlow'];
         return false;
      }
   }
   
   /**
    * Password med strength?
    */
   if (in_array('pwdmedium', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*()]+$/", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_pwdmedium'];
         return false;
      }
   }
   
   /**
    * Password med strength?
    */
   if (in_array('pwdhigh', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()])[a-zA-Z0-9!@#$%^&*()]+$/", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_pwdhigh'];
         return false;
      }
   }
   
   /**
    * Phone number?
    */
   if (in_array('phone_number', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match("/^([ +0-9-()])+$/i", $_POST[$field]))
      {
         $inputAlert[$label[1]] = $LANG['alert_input_phone_number'];
         return false;
      }
   }
   
   /**
    * Validates against a custom regex?
    */
   if (in_array('regex_match', $rules))
   {
      if (isset($_POST[$field]) and strlen($_POST[$field]) and !preg_match($param, $_POST[$field]))
      {
         $inputAlert[$label[1]] = sprintf($LANG['alert_input_regex_match'], $param);
         return false;
      }
   }
   
   return true;
}

// ---------------------------------------------------------------------------
/**
 * Generates a password
 *
 * @param integer $length Desired password length
 * @return string Password
 */
function generatePassword($length = 9)
{
   $characters = 'abcdefghjklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789@#$%';
   
   $password = '';
   for($i = 0; $i < $length; $i++)
   {
      $password .= $characters[(rand() % strlen($characters))];
   }
   return $password;
}

// ---------------------------------------------------------------------------
/**
 * Scans a given directory for files.
 * Optionally you can specify an array of extension to look for.
 *
 * @param string $myDir Directory name to scan
 * @param string $myExt Array of extensions to scan for
 * @param string $myPrefix An optional prefix of the filename
 * 
 * @return array Array containing the names of the files
 */
function getFiles($myDir, $myExt = NULL, $myPrefix = NULL)
{
   $myDir = rtrim($myDir, "/");
   $dir = opendir($myDir);
   
   while ( false !== ($filename = readdir($dir)) )
   {
      $files[] = $filename;
   }
   
   foreach ( $files as $pos => $file )
   {
      if (is_dir($file))
      {
         $dirs[] = $file;
         unset($files[$pos]);
      }
   }
   
   if (count($myExt) OR $myPrefix)
   {
      if (count($files))
      {
         foreach ( $files as $pos => $file )
         {
            $thisExt = explode(".", $file);
            if (count($myExt) AND !$myPrefix)
            {
               if (in_array($thisExt[1], $myExt))
               {
                  $filearray[] = $file;
               }
            }
            elseif (!count($myExt) AND $myPrefix)
            {
               if (startsWith($thisExt[0], $myPrefix))
               {
                  $filearray[] = $file;
               }
            }
            elseif (count($myExt) AND $myPrefix)
            {
               if (in_array($thisExt[1], $myExt) AND startsWith($thisExt[0], $myPrefix))
               {
                  $filearray[] = $file;
               }
            }
         }
      }
      return $filearray;
   }
   else
   {
      return $files;
   }
}

// ---------------------------------------------------------------------------
/**
 * Extracts the file extension from a given file name
 *
 * @param string $str String containing the path or filename
 * 
 * @return string File extension of the string passed
 */
function getFileExtension($str)
{
   $i = strrpos($str, ".");
   if (!$i) return "";
   $l = strlen($str) - $i;
   $ext = substr($str, $i + 1, $l);
   return $ext;
}

// ---------------------------------------------------------------------------
/**
 * Gets all folders in a given directory
 *
 * @param string $myDir String containing the pathname
 * 
 * @return array Array containing the folder names
 */
function getFolders($myDir)
{
   $myDir = rtrim($myDir, '/') . '/'; // Ensure trailing slash
   $handle = opendir($myDir);
   $diridx = 0;
   
   while ( false !== ($dir = readdir($handle)) )
   {
      if (is_dir($myDir . "/$dir") && $dir != "." && $dir != "..")
      {
         // $dirarray[$diridx]['name'] = $dir;
         $dirarray[] = $dir;
         $diridx++;
      }
   }
   closedir($handle);
   return $dirarray;
}

// ---------------------------------------------------------------------------
/**
 * Returns todays date in ISO 8601 format
 *
 * @return string ISO 8601 format, e.g. 2014-03-03
 */
function getISOToday()
{
   $mydate = getdate();
   $year = $mydate['year'];                  // Numeric representation of todays' year, 4 digits
   $month = sprintf("%02d", $mydate['mon']); // Numeric representation of todays' month, 2 digits
   $day = sprintf("%02d", $mydate['mday']);  // Numeric representation of todays' day of the month, 2 digits
   
   return $year . '-' . $month . '-' . $day;
}

// ---------------------------------------------------------------------------
/**
 * Gets all language directory names from the language directory
 *
 * @param string $type Look for application or log languages
 * 
 * @return array Array containing the names
 */
function getLanguages($type = 'app')
{
   $mydir = "languages/";
   $handle = opendir($mydir); // open directory
   $fileidx = 0;
   while ( false !== ($file = readdir($handle)) )
   {
      if (!is_dir($mydir . "/$file") && $file != "." && $file != "..")
      {
         $filearray[$fileidx]["name"] = $file;
         $fileidx++;
      }
   }
   closedir($handle);
   
   // If there are language files
   if ($fileidx > 0)
   {
      // Extract the language name
      for($i = 0; $i < $fileidx; $i++)
      {
         $langName = explode(".", $filearray[$i]["name"]);
         if ($type == 'log')
         {
            if ($langName[1] == 'log') $langarray[$i] = $langName[0];
         }
         else
         {
            if ($langName[1] == 'php') $langarray[$i] = $langName[0];
         }
      }
   }
   return $langarray;
}

// ---------------------------------------------------------------------------
/**
 * Gets all $_GET and $_POST parameters and fills the $CONF['options'][] array
 */
function getOptions()
{
   global $A, $C, $CONF, $G, $L, $R, $UO;
   
   $user = $L->checkLogin();
   
   /**
    * Set time zone
    */
   $tz = $C->read("timeZone");
   if (!strlen($tz) or $tz == "default") date_default_timezone_set('UTC');
   else date_default_timezone_set($tz);
   
   /**
    * Get app defaults from database
    */
   $CONF['options']['lang'] = $C->read("defaultLanguage");
   
   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE)
   {
      $debug = "After Defaults\\r\\n";
      $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
   }
   
   /**
    * Get user preferences if someone is logged in
    */
   if ($user)
   {
      if ($userlang = $UO->read($user, "language") and $userlang != "default")
      {
         $CONF['options']['lang'] = $userlang;
      }
   }
   
   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE)
   {
      $debug = "After User Preferences\\r\\n";
      $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
   }
   
   /**
    * Get $_GET (overwriting user preferences)
    */
   
   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE)
   {
      $debug = "After _GET\\r\\n";
      $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
   }
   
   /**
    * Now get $_POST (overwrites $_GET and user preferences)
    */
   if (isset($_POST['user_lang']) && strlen($_POST['user_lang']) and in_array($_POST['user_lang'], getLanguages())) $CONF['options']['lang'] = trim($_POST['user_lang']);
   
   
   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE)
   {
      $debug = "After _POST\\r\\n";
      $debug .= "tc_config['options']['lang'] = " . $CONF['options']['lang'] . "\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"" . $debug . "\");</script>";
   }
}

// ---------------------------------------------------------------------------
/**
 * Strips body and html from phpinfo() and puts it all in a div container
 *
 * @return string $phpi Stripped phpinfo() output
 */
function getPhpInfo()
{
   $phpi = '';
   ob_start();
   phpinfo();
   
   preg_match('%<style type="text/css">(.*?)</style>.*?<body>(.*?)</body>%s', ob_get_clean(), $matches);
   
   /**
    * $matches [1]; Style information
    * $matches [2]; Body information
    */
   $phpi = "<div class='phpinfodisplay'><style type='text/css'>\n";
   $phpi .= join("\n", array_map(create_function('$i', 'return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );'), preg_split('/\n/', trim(preg_replace("/\nbody/", "\n", $matches[1])))));
   $phpi .= "</style>\n";
   $phpi .= $matches[2];
   $phpi .= "\n</div>\n";
   return $phpi;
}

// ---------------------------------------------------------------------------
/**
 * Determines the role bootstrap color
 *
 * @param class $role Role name
 * 
 * @return string Bootstrap color name
 */
function getRoleColor($role)
{
   switch ($role)
   {
      case 'assistant' :
         $color = 'primary';
         break;
      
      case 'manager' :
         $color = 'warning';
         break;
      
      case 'director' :
         $color = 'default';
         break;
      
      case 'admin' :
         $color = 'danger';
         break;
      
      default :
         $color = 'success';
         break;
   }
   return $color;
}

// ---------------------------------------------------------------------------
/**
 * Gets the theme to use
 *
 * @return string $theme The theme to apply
 */
function getTheme()
{
   global $C, $L, $UO;
   
   $theme = $C->read("theme");
   if ($thisuser = $L->checkLogin())
   {
      /**
       * A user is logged in
       */
      if ($C->read("allowUserTheme"))
      {
         /**
          * User theme selection is allowed.
          * If none is found set it to 'default'
          */
         if (!$theme = $UO->read($thisuser, "theme"))
         {
            $UO->create($thisuser, "theme", "bootstrap");
         }
         else
         {
            /**
             * If user wants the default theme, use it.
             */
            if ($theme == "default") $theme = $C->read("theme");
         }
      }
   }
   
   /**
    * If by now nothing is in $theme set it to 'bootstrap'
    */
   if (!strlen($theme))
   {
      $theme = "bootstrap";
      $C->save("theme", "bootstrap");
   }
   
   return $theme;
}

// ---------------------------------------------------------------------------
/**
 * Capitalizes the first letter of a given word and makes the rest lower case
 *
 * @param string $string String to properize
 * 
 * @return string Properly capitalized string
 */
function proper($string)
{
   $string = strtolower($string);
   $string = substr_replace($string, strtoupper(substr($string, 0, 1)), 0, 1);
   return $string;
}

// ---------------------------------------------------------------------------
/**
 * Sanitizes and returns a given string
 *
 * @param string $input String to sanitize
 * 
 * @return string Sanitized string
 */
function sanitize($input)
{
   if (is_array($input))
   {
      foreach ( $input as $var => $val )
      {
         $output[$var] = sanitize($val);
      }
   }
   else
   {
      if (get_magic_quotes_gpc())
      {
         $input = stripslashes($input);
      }
      $output = cleanInput($input);
      // $output = mysql_real_escape_string($output);
   }
   return $output;
}

// ---------------------------------------------------------------------------
/**
 * Checks whether a string starts with a given prefix
 *
 * @param string $haystack String to check
 * @param string $needle Prefix to look for
 * 
 * @return boolean True or False
 */
function startsWith($haystack, $needle) 
{
   // search backwards starting from haystack length characters from the end
   return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

// ---------------------------------------------------------------------------
/**
 * Restores a user and all related records from archive
 *
 * @return string Login information
 */
function loginInfo()
{
   global $L, $LANG, $RO, $UL;
   
   $loginInfo = $LANG['status_logged_out'];
   
   if ($luser = $L->checkLogin())
   {
      /**
       * Get the user
       */
      $UL->findByName($luser);
      $loginInfo  = $UL->getFullname($luser) . " (" . $luser . ")<br>";
      $loginInfo .= $LANG['role'] . ': '. $RO->getNameById($UL->role);
   }
   
   return $loginInfo;
}
?>
