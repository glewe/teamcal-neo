<?php
/**
 * userimport.php
 * 
 * CSV user import page
 *
 * @category TeamCal Neo 
 * @version 1.5.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

// echo '<script type="text/javascript">alert("Debug: ");</script>';

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
// LOAD CONTROLLER RESOURCES
//
$UPL = new Upload();

//=============================================================================
//
// VARIABLE DEFAULTS
//
$uplDir = WEBSITE_ROOT . '/' . APP_IMP_DIR;
$viewData = array();

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
   // Validate input data. If something is wrong or missing, set $inputError = true
   //
    
   if (!$inputError)
   {
      // ,--------,
      // | Import |
      // '--------'
      if (isset($_POST['btn_import']))
      {
         $UPL->upload_dir = $uplDir;
         $UPL->extensions = $CONF['impExtensions'];
         $UPL->do_filename_check = "y";
         $UPL->replace = "y";
         $UPL->the_temp_file = $_FILES['file_image']['tmp_name'];
         $UPL->the_file = $_FILES['file_image']['name'];
         $UPL->http_error = $_FILES['file_image']['error'];
          
         if ($UPL->upload())
         {
            $viewData['defaultGroup'] = $_POST['sel_group'];
            $viewData['defaultRole'] = $_POST['sel_role'];
            
            if (($handle = fopen($uplDir . $UPL->the_file, "r")) !== FALSE)
            {
               //
               // Loop through all lines
               //
               $line=0;
               $errorCount=0;
               $errorText='';
               $importCount=0;
               $numCols=5;
               while (($arr = fgetcsv($handle, 1000, ";")) !== FALSE)
               {
                  $line++;
                  if (is_array($arr) && !empty ($arr))
                  {
                     if (count($arr) <> $numCols)
                     {
                        //
                        // Wrong column count in CSV
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_columns'], $line, $numCols, $arr[0], count($arr), $numCols) . '</li>';
                        continue;
                     }

                     $CSVusername = trim($arr[0]);
                     $CSVfirstname = trim($arr[1]);
                     $CSVlastname = trim($arr[2]);
                     $CSVemail = trim($arr[3]);
                     $CSVgender = trim($arr[4]);
                      
                     if ( $CSVusername == "admin" )
                     {
                        //
                        // Admin username cannot be imported
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_admin'], $line ) . '</li>';
                        continue;
                     }

                     if ( $U->findByName($CSVusername) )                      
                     {
                        //
                        // Username already exists
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_exists'], $line, $CSVusername) . '</li>';
                        continue;
                     }

                     if (!preg_match("/^([\pL\w.@])+$/u",$CSVusername))
                     {
                        //
                        // Wrong username format
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_username'], $line, $CSVusername) . '</li>';
                        continue;
                     }
                        
                     if (!preg_match("/^[ \pL\w._-]+$/u", $CSVfirstname))
                     {
                        //
                        // Wrong firstname format
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_firstname'], $line, $CSVfirstname) . '</li>';
                        continue;
                     }

                     if (!preg_match("/^[ \pL\w._-]+$/u", $CSVlastname))
                     {
                        //
                        // Wrong lastname format
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_lastname'], $line, $CSVlastname) . '</li>';
                        continue;
                     }
                        
                     if (!validEmail($CSVemail))
                     {
                        //
                        // Wrong email format
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_email'], $line, $CSVemail) . '</li>';
                        continue;
                     }
                     
                     if ($CSVgender != "male" AND $CSVgender != "female")
                     {
                        //
                        // Wrong gender
                        //
                        $errorCount++;
                        $errorText .= '<li>' . sprintf($LANG['alert_imp_gender'], $line, $CSVgender) . '</li>';
                        continue;
                     }
                      
                     //
                     // All is good with this line. Let's create the user.
                     //
                     $U->username = $CSVusername;
                     $U->password = crypt("password", SALT);
                     $U->firstname = $CSVfirstname;
                     $U->lastname = $CSVlastname;
                     $U->email = $CSVemail;
                     if (isset($_POST['sel_role'])) $U->role = $_POST['sel_role']; else $U->role = '2';
                     if (isset($_POST['chk_hidden']) && $_POST['chk_hidden']) $U->hidden = '1'; else $U->hidden = '0';
                     if (isset($_POST['chk_locked']) && $_POST['chk_locked']) $U->locked = '1'; else $U->locked = '0';
                     $U->onhold = '0';
                     $U->verify = '0';
                     $U->bad_logins = '0';
                     $U->grace_start = DEFAULT_TIMESTAMP;
                     $U->last_login = DEFAULT_TIMESTAMP;
                     $U->created = date('YmdHis');
                     $U->last_pw_change = date('YmdHis');
                     $U->create();
                     
                     //
                     // Assign to default group
                     //
                     if (isset($_POST['sel_group']) )
                     {
                        if ($G->getById($_POST['sel_group']) AND $U->findByName($CSVusername)) $UG->save($CSVusername, $_POST['sel_group'], 'member');
                     }
                      
                     //
                     // Default user options
                     //
                     $UO->save($CSVusername, 'gender', $CSVgender);
                     $UO->save($CSVusername, 'avatar', 'default_'.$CSVgender.'.png');
                     $UO->save($CSVusername, 'language', 'default');
                     $UO->save($CSVusername, 'theme', 'default');
                      
                     $importCount++;
                  }
               }
               fclose($handle);
               
               if ($errorCount)
               {
                  //
                  // Errors detected
                  //
                  $showAlert=true;
                  $alertData['type'] = 'warning';
                  $alertData['title'] = $LANG['alert_warning_title'];
                  $alertData['subject'] = $LANG['alert_imp_subject'];
                  $alertData['text'] = '<ul>' . $errorText . '</ul><p><br>' . sprintf($LANG['imp_alert_success_text'] , $importCount) . '</p>';
                  $alertData['help'] = $LANG['imp_alert_help'];
               }
               else 
               {
                  //
                  // Success
                  //
                  $showAlert = TRUE;
                  $alertData['type'] = 'success';
                  $alertData['title'] = $LANG['alert_success_title'];
                  $alertData['subject'] = $LANG['imp_alert_success'];
                  $alertData['text'] = sprintf($LANG['imp_alert_success_text'] , $importCount);
                  $alertData['help'] = '';
               }
               
               //
               // Log this event
               //
               $LOG->log("logImport", $UL->username, "log_imp_success", $UPL->the_file . " (".$importCount." ".$LANG['user'].")");
            }
         }
         else
         {
            //
            // Upload failed
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['alert_danger_title'];
            $alertData['subject'] = $LANG['alert_upl_img_subject'];
            $alertData['text'] = $UPL->getErrors();
            $alertData['help'] = '';
         }
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
$viewData['upl_maxsize'] = $CONF['uplMaxsize'];
$viewData['upl_formats'] = 'csv';
$groups = $G->getAll();
$roles = $RO->getAll();

foreach ($groups as $group)
{
   $viewData['groups'][] = array('val' => $group['id'], 'name' => $group['name'], 'selected' => false);
}
foreach ($roles as $role)
{
   $viewData['roles'][] = array ('val' => $role['id'], 'name' => $role['name'], 'selected' => ($role['id']==2)?true:false );
}
$viewData['import'] = array (
   array ( 'prefix' => 'imp', 'name' => 'group', 'type' => 'list', 'values' => $viewData['groups']),
   array ( 'prefix' => 'imp', 'name' => 'role', 'type' => 'list', 'values' => $viewData['roles']),
   array ( 'prefix' => 'imp', 'name' => 'hidden', 'type' => 'check', 'values' => '', 'value' => 1 ),
   array ( 'prefix' => 'imp', 'name' => 'locked', 'type' => 'check', 'values' => '', 'value' => 1 ),
);

//=============================================================================
//
// SHOW VIEW
//
require (WEBSITE_ROOT . '/views/header.php');
require (WEBSITE_ROOT . '/views/menu.php');
include (WEBSITE_ROOT . '/views/'.$controller.'.php');
require (WEBSITE_ROOT . '/views/footer.php');
?>
