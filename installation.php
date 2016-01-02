<?php
/**
 * index.php
 * 
 * Installation script
 *
 * @category TeamCal Neo 
 * @version 0.4.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// DEFINES
//
define('VALID_ROOT', 1);
define('WEBSITE_ROOT', __DIR__);
$fullURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$pos = strrpos($fullURL,'/');
define('WEBSITE_URL', substr($fullURL,0,$pos)); //Remove trailing slash

//=============================================================================
//
// CONFIG
//
require_once (WEBSITE_ROOT . '/config/config.app.php');

//=============================================================================
//
// HELPERS
//
require_once (WEBSITE_ROOT . '/helpers/global.helper.php');

//=============================================================================
//
// LANGUAGE
//
$LANG['btn_install'] = 'Install';
$LANG['btn_test'] = 'Test Database';
$LANG['inst_db_error'] = 'An error occured trying to connect to the database.';
$LANG['inst_dbData'] = 'Sample data';
$LANG['inst_dbData_comment'] = 'Check whether you want a set of sample data loaded or not. Select "Use existing data" if your database already exists and you want to use the existing data.<br>
      Attention! "Use existing data" only works if your existing data set is compatible with the version you are installing. Find details in Upgradeinfo.txt.';
$LANG['inst_dbData_sample'] = 'Sample data';
$LANG['inst_dbData_none'] = 'Use existing data';
$LANG['inst_dbData_error'] = 'An error occured trying to load the sample data into the database.';
$LANG['inst_dbName'] = 'Database Name';
$LANG['inst_dbName_comment'] = 'Specify the name of the database. This needs to be an existing database.';
$LANG['inst_dbUser'] = 'Database User';
$LANG['inst_dbUser_comment'] = 'Specify the username to log in to your database.';
$LANG['inst_dbPassword'] = 'Database Password';
$LANG['inst_dbPassword_comment'] = 'Specify the password to log in to your database.';
$LANG['inst_dbPrefix'] = 'Database Table Prefix';
$LANG['inst_dbPrefix_comment'] = 'Specify a prefix for your TeamCal Neo database tables or leave empty for none. E.g. "tcneo_".';
$LANG['inst_dbServer'] = 'Database Server';
$LANG['inst_dbServer_comment'] = 'Specify the URL of the database server.';
$LANG['inst_executed'] = 'Installation already executed';
$LANG['inst_executed_comment'] = 'The configuration file shows that the installation script was already executed for this instance.<br>
      For security reasons, if you want to run it again, you need to reset the flag in the application config file.<br>
      Otherwise, it is highly recommended to delete the installation script from the server.<br><br><a class="btn btn-primary" href="index.php">Start TeamCal Neo</a>';
$LANG['inst_lic'] = 'License Agreement';
$LANG['inst_lic_comment'] = 'TeamCal Neo is a free open source application. However, if you want to use it you must accept the license agreements.';
$LANG['inst_lic_gpl'] = 'I accept the General Public License';
$LANG['inst_lic_app'] = 'I accept the TeamCal Neo License';
$LANG['inst_lic_error'] = 'Before you can start the installation you must accept both licenses!';
$LANG['inst_error'] = 'Installation Error';
$LANG['inst_congrats'] = 'Congratulations';
$LANG['inst_success'] = 'Installation Success';
$LANG['inst_success_comment'] = 'The installation of TeamCal Neo was successful. It is highly recommended to delete the installation script from the server.<br><br><a class="btn btn-primary" href="index.php">Start TeamCal Neo</a>';
$LANG['inst_warning'] = 'Installation Warning';

//=============================================================================
//
// VARIABLES
//
$installationExecuted = false;
$installationComplete = false;
$configAppFile = "config/config.app.php";
$configDbFile = "config/config.db.php";
$showAlert = false;
$tabindex = 1;

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
   
   // ,---------,
   // | Install |
   // '---------'
   if ( isset($_POST['btn_install']) ) 
   {
      if ( isset($_POST['chk_licGpl']) AND 
           isset($_POST['chk_licApp']) AND
           ( isset($_POST['txt_instDbServer']) AND strlen($_POST['txt_instDbServer']) ) AND
           ( isset($_POST['txt_instDbName'])   AND strlen($_POST['txt_instDbName'])   ) AND
           ( isset($_POST['txt_instDbUser'])   AND strlen($_POST['txt_instDbUser'])   )
         )
         {
         
         //
         // Write database info to config file
         //
         writeConfig("db_server",$_POST['txt_instDbServer'],$configDbFile);
         writeConfig("db_name",$_POST['txt_instDbName'],$configDbFile);
         writeConfig("db_user",$_POST['txt_instDbUser'],$configDbFile);
         
         if (isset($_POST['txt_instDbPassword']) AND strlen($_POST['txt_instDbPassword'])) 
            writeConfig("db_pass",$_POST['txt_instDbPassword'],$configDbFile);
         
         if (isset($_POST['txt_instDbPrefix']) AND strlen($_POST['txt_instDbPrefix'])) 
            writeConfig("db_table_prefix",$_POST['txt_instDbPrefix'],$configDbFile);
   
         //
         // Connect to database
         //
         $dberror=false;
         try
         {
            $pdo = new PDO('mysql:host=' . $_POST['txt_instDbServer'] . ';dbname=' . $_POST['txt_instDbName'] . ';charset=utf8', $_POST['txt_instDbUser'], $_POST['txt_instDbPassword']);
         } catch ( PDOException $e )
         {
            $dberror=true;
         }
   
         if (!$dberror) 
         {
            //
            // Check whether sample data shall be installed
            //
            $installData = false;
            switch ($_POST['opt_data'])
            {
               case "sample":
                  $installData = true;
                  $query = file_get_contents("sql/sample.sql");
                  break;
               default:
                  $installData = false;
                  break;
            }

            if ($installData)
            {
               //
               // Replace prefix in sample file
               //
               if (isset($_POST['txt_instDbPrefix']) AND strlen($_POST['txt_instDbPrefix'])) 
                  $query = str_replace("tcneo_",$_POST['txt_instDbPrefix'],$query);
               else
                  $query = str_replace("tcneo_","",$query);
                  
               //
               // Run query
               //
               $result = $pdo->query($query);
               if ($result)
               {
                  //
                  // Success and sample data loaded
                  //
                  writeConfig("app_installed","1",$configAppFile);
                  $installationComplete = true;
                  $showAlert = true;
                  $alertData['type'] = 'success';
                  $alertData['title'] = $LANG['inst_success'];
                  $alertData['subject'] = $LANG['inst_congrats'];
                  $alertData['text'] = $LANG['inst_success_comment'];
               }
               else 
               {
                  //
                  // Sample data load failed
                  //
                  $showAlert = true;
                  $alertData['type'] = 'danger';
                  $alertData['title'] = $LANG['inst_error'];
                  $alertData['subject'] = $LANG['inst_dbData'];
                  $alertData['text'] = $LANG['inst_dbData_error'];
               }
            }
            else 
            {
               //
               // Success, No sample data loaded
               //
               writeConfig("app_installed","1",$configAppFile);
               $installationComplete = true;
               $showAlert = true;
               $alertData['type'] = 'success';
               $alertData['title'] = $LANG['inst_success'];
               $alertData['subject'] = $LANG['inst_congrats'];
               $alertData['text'] = $LANG['inst_success_comment'];
            }
         }
         else 
         {
            //
            // Database connection failed
            //
            $showAlert = true;
            $alertData['type'] = 'danger';
            $alertData['title'] = $LANG['inst_error'];
            $alertData['subject'] = $LANG['inst_db'];
            $alertData['text'] = $LANG['inst_db_error'];
         }
      }
      else 
      {
         //
         // License not accepted
         //
         $showAlert = true;
         $alertData['type'] = 'warning';
         $alertData['title'] = $LANG['inst_warning'];
         $alertData['subject'] = $LANG['inst_lic'];
         $alertData['text'] = $LANG['inst_lic_error'];
      }
   }
}

//=============================================================================
//
// PREPARE VIEW
//
if (!$installationComplete AND readConfig('app_installed', $configAppFile) <> '0') 
{
   //
   // Installation has been executed already
   //
   $installationExecuted = true;
   $showAlert = true;
   $alertData['type'] = 'warning';
   $alertData['title'] = $LANG['inst_warning'];
   $alertData['subject'] = $LANG['inst_executed'];
   $alertData['text'] = $LANG['inst_executed_comment'];
}
?>

<!DOCTYPE html>
<html>
   <head>
      <title>TeamCal Neo Installation</title>
      <meta http-equiv="Content-type" content="text/html;charset=utf8">
      <meta charset="utf-8">
      <script type="text/javascript" src="js/ajax.js"></script>
      <link rel="stylesheet" href="themes/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="themes/bootstrap/css/bootstrap-theme.min.css">
      <link rel="stylesheet" href="css/custom.css">
      <link rel="shortcut icon" href="images/icons/logo-16.png">
      <link rel="stylesheet" href="fonts/font-awesome/4.5.0/css/font-awesome.min.css">   
   </head>
   
   <body>
      
      <!-- ==================================================================== 
      view.menu
      -->
      <div class="navbar navbar-inverse navbar-fixed-top">
         <div class="container">
            <div class="navbar-header">
               <a href="<?=WEBSITE_URL?>/installation.php" class="navbar-brand" style="padding: 2px 8px 0 8px;"><img src="images/logo.png" width="48" height="48" alt=""></a>
            </div>
         </div>
      </div>
            
      <!-- ==================================================================== 
      view.installation 
      -->
      <div class="container content">
         <div class="col-lg-12">

            <?php if ($showAlert) { ?> 
               <div class="alert alert-<?=$alertData['type']?>">
                  <h4><strong><?=$alertData['title']?></strong></h4>
                  <hr>
                  <p><strong><?=$alertData['subject']?></strong></p>
                  <p><?=$alertData['text']?></p>
               </div>
            <?php } ?>
         
            <?php if (!$installationExecuted and !$installationComplete) { ?> 
            <form  class="bs-example form-control-horizontal" action="installation.php" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-default">
                  <div class="panel-heading"><i class="fa fa-cog fa-lg"></i>&nbsp;TeamCal Neo <?=readConfig('app_version',$configAppFile)?> Installation</div>
                  <div class="panel-body">

                     <!-- DB Server -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbServer']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbServer_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <input id="txt_instDbServer" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_instDbServer" type="text" maxlength="160" value="<?=readConfig('db_server',$configDbFile)?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- DB Name -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbName']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbName_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <input id="txt_instDbName" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_instDbName" type="text" maxlength="160" value="<?=readConfig('db_name',$configDbFile)?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- DB User -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbUser']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbUser_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <input id="txt_instDbUser" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_instDbUser" type="text" maxlength="160" value="<?=readConfig('db_user',$configDbFile)?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- DB Password -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbPassword']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbPassword_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <input id="txt_instDbPassword" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_instDbPassword" type="text" maxlength="160" value="<?=readConfig('db_password',$configDbFile)?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- DB Table Prefix -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbPrefix']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbPrefix_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <input id="txt_instDbPrefix" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_instDbPrefix" type="text" maxlength="160" value="<?=readConfig('db_table_prefix',$configDbFile)?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>

                     <!-- Test Database -->
                     <div class="form-group">
                        <div class="col-lg-12">
                           <a class="btn btn-default" tabindex="<?=$tabindex++;?>" name="btn_testDb" onclick="javascript:checkDB();"><?=$LANG['btn_test']?></a>
                           <p><span id="checkDbOutput"></span></p>
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- Sample Data -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbData']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbData_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <div class="radio"><label><input name="opt_data" value="sample" tabindex="<?=$tabindex++;?>" type="radio" checked><?=$LANG['inst_dbData_sample']?></label></div>
                           <div class="radio"><label><input name="opt_data" value="none" tabindex="<?=$tabindex++;?>" type="radio"><?=$LANG['inst_dbData_none']?></label></div>
                        </div>
                     </div>
                     <div class="divider"><hr></div>
                     
                     <!-- License -->
                     <div class="form-group">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_lic']?><br>
                           <span class="text-normal"><?=$LANG['inst_lic_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <div class="checkbox">
                              <label><input type="checkbox" id="chk_licGpl" name="chk_licGpl" value="chk_activateMessages" tabindex="<?=$tabindex++;?>"><a href="doc/gpl.txt"><?=$LANG['inst_lic_gpl']?></a></label>
                           </div>
                           <div class="checkbox">
                              <label><input type="checkbox" id="chk_licApp" name="chk_licApp" value="chk_activateMessages" tabindex="<?=$tabindex++;?>"><a href="doc/license.txt"><?=$LANG['inst_lic_app']?></a></label>
                           </div>
                        </div>
                     </div>
                     <div class="divider"><hr></div>

                     <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_install" onmouseover="checkLicense();"><?=$LANG['btn_install']?></button>
                     
                  </div>
               </div>

            </form>
            <?php } ?>
         </div>
      </div>  

      <!-- ==================================================================== 
      view.footer 
      -->
      <footer class="footer">
         <div class="container">
               
            <div class="col-lg-3">
               <ul class="list-unstyled">
                  <li>&copy; <?=date('Y')?> Lewe.com</li>
               </ul>
            </div>
            
            <div class="col-lg-3">
            </div>
            
            <div class="col-lg-3">
            </div>
            
            <div class="col-lg-3 text-right">
               <a href="http://www.w3.org/html/logo/"><img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3.png" style="margin-right: -12px; margin-bottom: 60px;" width="66" height="32" alt="HTML5 Powered with CSS3 / Styling" title="HTML5 Powered with CSS3 / Styling"></a><br>
            </div>
            
         </div>
         
         <!-- As per license, you are not allowed to change or remove the following block! -->
         <div class="container">
            <div class="col-lg-12 text-right text-italic xsmall"><?=$CONF['app_powered']?></div>
         </div>
         
      </footer>
         
      <script type="text/javascript">
      function checkDB(){
         var myDbServer = document.getElementById('txt_instDbServer');
         var myDbUser = document.getElementById('txt_instDbUser');
         var myDbPass = document.getElementById('txt_instDbPassword');
         var myDbName = document.getElementById('txt_instDbName');
         var myDbPrefix = document.getElementById('txt_instDbPrefix');
         ajaxCheckDB(myDbServer.value, myDbUser.value, myDbPass.value, myDbName.value, myDbPrefix.value, 'checkDbOutput');
      }
      
      function checkLicense(){
         var myLicGpl = document.getElementById('chk_licGpl');
         var myLicApp = document.getElementById('chk_licApp');
         if ( !myLicGpl.checked || !myLicApp.checked ) {
            alert("<?=$LANG['inst_lic_error']?>");
         }
      }
      </script>
      
   </body>
</html>
