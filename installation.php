<?php
/**
 * Installation
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

//=============================================================================
//
// DEFINES
//
define('VALID_ROOT', 1);
define('WEBSITE_ROOT', __DIR__);

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
$LANG['inst_dbData'] = '<span class="text-bold text-danger">*&nbsp;</span>Sample data';
$LANG['inst_dbData_comment'] = 'Check whether you want a set of sample data loaded or not. Select "Use existing data" if your database already exists and you want to use the existing data.<br>
      Attention! "Use existing data" only works if your existing data set is compatible with the version you are installing. Find details in Upgradeinfo.txt.';
$LANG['inst_dbData_sample'] = 'Sample data';
$LANG['inst_dbData_none'] = 'Use existing data';
$LANG['inst_dbData_error'] = 'An error occured trying to load the sample data into the database.';
$LANG['inst_dbName'] = '<span class="text-bold text-danger">*&nbsp;</span>Database Name';
$LANG['inst_dbName_comment'] = 'Specify the name of the database. This needs to be an existing database.';
$LANG['inst_dbUser'] = '<span class="text-bold text-danger">*&nbsp;</span>Database User';
$LANG['inst_dbUser_comment'] = 'Specify the username to log in to your database.';
$LANG['inst_dbPassword'] = 'Database Password';
$LANG['inst_dbPassword_comment'] = 'Specify the password to log in to your database.';
$LANG['inst_dbPrefix'] = 'Database Table Prefix';
$LANG['inst_dbPrefix_comment'] = 'Specify a prefix for your database tables or leave empty for none. E.g. "tcneo_".';
$LANG['inst_dbServer'] = '<span class="text-bold text-danger">*&nbsp;</span>Database Server';
$LANG['inst_dbServer_comment'] = 'Specify the URL of the database server.';
$LANG['inst_executed'] = 'Installation already executed';
$LANG['inst_executed_comment'] = 'The configuration file shows that the installation script was already executed for this instance.<br>
      For security reasons, if you want to run it again, you need to reset the flag in the application config file:<br>
      Set define[\'APP_INSTALLED\'] to 0 in "config/config.app.php"<br>
      Otherwise, delete the installation script from the server. Then click the button below.<br><br><a class="btn btn-primary" href="index.php">Start</a>';
$LANG['inst_lic'] = '<span class="text-bold text-danger">*&nbsp;</span>License Agreement';
$LANG['inst_lic_comment'] = 'You must accept the license agreement if you want to use this application.';
$LANG['inst_lic_app'] = 'I accept the TeamCal Neo License';
$LANG['inst_lic_error'] = 'Before you can start the installation you must accept the license agreement!\n\rBe aware that this might require a support fee dependent on your usage of TeamCal Neo.';
$LANG['inst_error'] = 'Installation Error';
$LANG['inst_congrats'] = 'Congratulations';
$LANG['inst_success'] = 'Installation Success';
$LANG['inst_success_comment'] = 'The installation was successful. Please delete the installation script from the server before you start.<br><br><a class="btn btn-primary" href="index.php">Start</a>';
$LANG['inst_update'] = 'Do not run for update';
$LANG['inst_update_comment'] = 'Do not run the installation script for updating TeamCal Neo. Instead, follow the instructions <a href="doc/upgradeinfo.txt">here</a>.<br>
      If this is a fresh install, you can close this message in the upper right corner and continue below.';
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
      if ( isset($_POST['chk_licApp']) AND
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
         else
            writeConfig("db_table_prefix",'',$configDbFile);
   
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
                  writeDef("APP_INSTALLED","1",$configAppFile);
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
               writeDef("APP_INSTALLED","1",$configAppFile);
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
if (!$installationComplete AND readDef('APP_INSTALLED', $configAppFile) <> '0') 
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
      <link rel="stylesheet" href="themes/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="css/custom.css">
      <link rel="shortcut icon" href="images/icons/logo-16.png">
      <link rel="stylesheet" href="fonts/font-awesome/5.12.0/css/all.css">   
      <script src="js/ajax.js"></script>
      <script src="js/jquery/jquery-3.1.1.min.js"></script>
      <script src="js/jquery/ui/1.12.1/jquery-ui.min.js"></script>
      <script src="themes/bootstrap/js/bootstrap.min.js"></script>
   </head>
   
   <body>
      
      <!-- ==================================================================== 
      view.menu
      -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
         <div class="container">
            <a href="<?=WEBSITE_URL?>/installation.php" class="navbar-brand" style="padding: 2px 8px 0 8px;"><img src="images/logo.png" width="48" height="48" alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
         </div>
      </nav>
      <div style="height:20px;"></div>
            
      <!-- ==================================================================== 
      view.installation 
      -->
      <div class="container content">
         <div class="col-lg-12">

            <?php if ($showAlert) { ?> 
               <div class="alert alert-<?=$alertData['type']?>">
                  <h5><?=$alertData['title']?></h5>
                  <hr>
                  <p><strong><?=$alertData['subject']?></strong></p>
                  <p><?=$alertData['text']?></p>
               </div>
            <?php } ?>

           <div class="alert alert-dismissable alert-warning">
               <button type="button" class="close" data-dismiss="alert" title="Close this message"><i class="far fa-times-circle"></i></span></button>
               <h5><?=$LANG['inst_warning']?></h5>
               <hr>
               <p><strong><?=$LANG['inst_update']?></strong></p>
               <p><?=$LANG['inst_update_comment']?></p>
            </div>        
               
            <?php if (!$installationExecuted and !$installationComplete) { ?> 
            <form class="form-control-horizontal" action="installation.php" method="post" target="_self" accept-charset="utf-8">

               <div class="card">
                  <div class="card-header"><i class="fas fa-cog fa-lg"></i>&nbsp;<?=readConfig('app_name',$configAppFile)?> <?=readConfig('app_version',$configAppFile)?> Installation<a href="https://support.lewe.com/docs/teamcal-neo-manual/installation/" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a></div>
                  <div class="card-body">

                     <!-- DB Server -->
                     <div class="form-group row">
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
                     <div class="form-group row">
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
                     <div class="form-group row">
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
                     <div class="form-group row">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_dbPassword']?><br>
                           <span class="text-normal"><?=$LANG['inst_dbPassword_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <input id="txt_instDbPassword" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_instDbPassword" type="password" maxlength="80" value="<?=readConfig('db_password',$configDbFile)?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- DB Table Prefix -->
                     <div class="form-group row">
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
                     <div class="form-group row">
                        <div class="col-lg-12">
                           <a class="btn btn-secondary text-white" tabindex="<?=$tabindex++;?>" id="btn_testDb" onclick="javascript:checkDB();"><?=$LANG['btn_test']?></a>
                           <div style="height:20px;"></div>
                           <p><span id="checkDbOutput"></span></p>
                        </div>
                     </div>
                     <div class="divider"><hr></div>
               
                     <!-- Sample Data -->
                     <div class="form-group row">
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
                     <div class="form-group row">
                        <label class="col-lg-8 control-label">
                           <?=$LANG['inst_lic']?><br>
                           <span class="text-normal"><?=$LANG['inst_lic_comment']?></span>
                        </label>
                        <div class="col-lg-4">
                           <div class="checkbox">
                              <label><input type="checkbox" id="chk_licApp" name="chk_licApp" value="chk_activateMessages" tabindex="<?=$tabindex++;?>"><a href="https://support.lewe.com/docs/teamcal-neo-manual/teamcal-neo-license/"><?=$LANG['inst_lic_app']?></a></label>
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
            </div>
            
         </div>
         
         <!-- As per license, you are not allowed to change or remove the following block! -->
         <div class="container">
            <div class="col-lg-12 text-right text-italic xsmall"><?=APP_POWERED?></div>
         </div>
         
      </footer>
         
      <script>
      function checkDB(){
         var myDbServer = document.getElementById('txt_instDbServer');
         var myDbUser = document.getElementById('txt_instDbUser');
         var myDbPass = document.getElementById('txt_instDbPassword');
         var myDbName = document.getElementById('txt_instDbName');
         var myDbPrefix = document.getElementById('txt_instDbPrefix');
         ajaxCheckDB(myDbServer.value, myDbUser.value, myDbPass.value, myDbName.value, myDbPrefix.value, 'checkDbOutput');
      }
      
      function checkLicense(){
         var myLicApp = document.getElementById('chk_licApp');
         if ( !myLicApp.checked ) {
            alert("<?=$LANG['inst_lic_error']?>");
         }
      }
      </script>
      
   </body>
</html>
