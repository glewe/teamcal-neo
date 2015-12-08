<?php
/**
 * menu.php
 * 
 * The view of the top navigation menu
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>
      <!-- ==================================================================== 
      view.menu
      -->
      <div class="navbar navbar-inverse navbar-fixed-top">
         <div class="container">
            
            <div class="navbar-header">
               <a href="<?=WEBSITE_URL?>" class="navbar-brand" style="padding: 2px 8px 0 8px;"><img src="images/logo.png" width="48" height="48" alt=""></a>
               <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                  <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
               </button>
            </div>
            
            <div class="navbar-collapse collapse" id="navbar-main">
               
               <ul class="nav navbar-nav">

                  <!-- App Menu -->
                  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="app"><?=$C->read("appTitle")?><span class="caret"></span></a>
                     <ul class="dropdown-menu" aria-labelledby="app">
                        <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['home']->name?>"><i class="fa fa-<?=$CONF['controllers']['home']->faIcon?> fa-lg text-<?=$CONF['controllers']['home']->iconColor?> fa-menu"></i><?=$LANG['mnu_app_homepage']?></a></li>
                        <?php if ($userData['isLoggedIn']) { ?>
                        <li class="divider"></li>
                        <li class="dropdown-submenu">
                           <a tabindex="-1" href="#"><i class="fa fa-language fa-lg text-danger fa-menu"></i><?=$LANG['mnu_app_language']?></a>
                           <ul class="dropdown-menu">
                              <?php foreach ($appLanguages as $appLang) { ?>
                                 <li><a href="index.php?<?=str_replace('&','&amp;',$_SERVER['QUERY_STRING'])?>&amp;applang=<?=$appLang?>"><img src="languages/<?=$appLang?>.png" style="margin-right: 4px;" alt="<?=proper($appLang)?>"><?=proper($appLang)?></a></li>
                              <?php } ?>
                           </ul>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>

                  <!-- View Menu -->
                  <?php if ($menuData['mnu_view']) { ?>
                  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="view"><?=$LANG['mnu_view']?><span class="caret"></span></a>
                     <ul class="dropdown-menu" aria-labelledby="view">
                        <?php if ($menuData['mnu_view_calendar']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['calendarview']->name?>&amp;month=<?=date('Y').date('m')?>&amp;region=1"><i class="fa fa-<?=$CONF['controllers']['calendarview']->faIcon?> fa-lg text-<?=$CONF['controllers']['calendarview']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_calendar']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_view_year']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['year']->name?>&amp;year=<?=date('Y')?>&amp;region=1&amp;user=<?=$UL->username?>"><i class="fa fa-<?=$CONF['controllers']['year']->faIcon?> fa-lg text-<?=$CONF['controllers']['year']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_year']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_view_messages'] OR $menuData['mnu_view_statistics']) { ?>
                           <li class="divider"></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_view_messages']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['messages']->name?>"><i class="fa fa-<?=$CONF['controllers']['messages']->faIcon?> fa-lg text-<?=$CONF['controllers']['messages']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_messages']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_view_stats']) { ?>
                        <li class="dropdown-submenu">
                           <a tabindex="-1" href="#"><i class="fa fa-bar-chart fa-lg text-warning fa-menu"></i><?=$LANG['mnu_view_stats']?></a>
                           <ul class="dropdown-menu">
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['statsabsence']->name?>"><i class="fa fa-<?=$CONF['controllers']['statsabsence']->faIcon?> fa-lg text-<?=$CONF['controllers']['statsabsence']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_absences']?></a></li>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['statspresence']->name?>"><i class="fa fa-<?=$CONF['controllers']['statspresence']->faIcon?> fa-lg text-<?=$CONF['controllers']['statspresence']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_presences']?></a></li>
                           </ul>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>
                  <?php } ?>                     
                  
                  <!-- Edit Menu -->
                  <?php if ($menuData['mnu_edit']) { ?>
                  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="tools"><?=$LANG['mnu_edit']?><span class="caret"></span></a>
                     <ul class="dropdown-menu" aria-labelledby="tools">
                        <?php if ($menuData['mnu_edit_calendaredit']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['calendaredit']->name?>&amp;month=<?=date('Y').date('m')?>&amp;region=1&amp;user=<?=$userData['username']?>"><i class="fa fa-<?=$CONF['controllers']['calendaredit']->faIcon?> fa-lg text-<?=$CONF['controllers']['calendaredit']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_calendaredit']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_edit_monthedit']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['monthedit']->name?>&amp;month=<?=date('Y').date('m')?>&amp;region=1"><i class="fa fa-<?=$CONF['controllers']['monthedit']->faIcon?> fa-lg text-<?=$CONF['controllers']['monthedit']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_monthedit']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_edit_messageedit']) { ?>
                           <li class="divider"></li>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['messageedit']->name?>"><i class="fa fa-<?=$CONF['controllers']['messageedit']->faIcon?> fa-lg text-<?=$CONF['controllers']['messageedit']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_messageedit']?></a></li>
                        <?php } ?>
                     </ul>
                  </li>
                  <?php } ?>                     
                  
               </ul>
      
               <ul class="nav navbar-nav navbar-right">
               
                  <!-- Admin Menu -->
                  <?php if ($menuData['mnu_admin']) { ?>
                  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="admin"><?=$LANG['mnu_admin']?><span class="caret"></span></a>
                     <ul class="dropdown-menu" aria-labelledby="admin">
                        <?php if ($menuData['mnu_admin_config']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['config']->name?>"><i class="fa fa-<?=$CONF['controllers']['config']->faIcon?> fa-lg text-<?=$CONF['controllers']['config']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_config']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_calendaroptions']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['calendaroptions']->name?>"><i class="fa fa-<?=$CONF['controllers']['calendaroptions']->faIcon?> fa-lg text-<?=$CONF['controllers']['calendaroptions']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_calendaroptions']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_perm']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['permissions']->name?>"><i class="fa fa-<?=$CONF['controllers']['permissions']->faIcon?> fa-lg text-<?=$CONF['controllers']['permissions']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_perm']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_users']) { ?>
                           <li class="divider"></li>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['users']->name?>"><i class="fa fa-<?=$CONF['controllers']['users']->faIcon?> fa-lg text-<?=$CONF['controllers']['users']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_users']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_groups']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['groups']->name?>"><i class="fa fa-<?=$CONF['controllers']['groups']->faIcon?> fa-lg text-<?=$CONF['controllers']['groups']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_groups']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_roles']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['roles']->name?>"><i class="fa fa-<?=$CONF['controllers']['roles']->faIcon?> fa-lg text-<?=$CONF['controllers']['roles']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_roles']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_absences']) { ?>
                           <li class="divider"></li>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['absences']->name?>"><i class="fa fa-<?=$CONF['controllers']['absences']->faIcon?> fa-lg text-<?=$CONF['controllers']['absences']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_absences']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_holidays']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['holidays']->name?>"><i class="fa fa-<?=$CONF['controllers']['holidays']->faIcon?> fa-lg text-<?=$CONF['controllers']['holidays']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_holidays']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_regions']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['regions']->name?>"><i class="fa fa-<?=$CONF['controllers']['regions']->faIcon?> fa-lg text-<?=$CONF['controllers']['regions']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_regions']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_declination']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['declination']->name?>"><i class="fa fa-<?=$CONF['controllers']['declination']->faIcon?> fa-lg text-<?=$CONF['controllers']['declination']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_declination']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_database']) { ?>
                           <li class="divider"></li>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['database']->name?>"><i class="fa fa-<?=$CONF['controllers']['database']->faIcon?> fa-lg text-<?=$CONF['controllers']['database']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_database']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_systemlog']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['log']->name?>"><i class="fa fa-<?=$CONF['controllers']['log']->faIcon?> fa-lg text-<?=$CONF['controllers']['log']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_systemlog']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_admin_env']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['phpinfo']->name?>"><i class="fa fa-<?=$CONF['controllers']['phpinfo']->faIcon?> fa-lg text-<?=$CONF['controllers']['phpinfo']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_phpinfo']?></a></li>
                        <?php } ?>                     
                     </ul>
                  </li>
                  <?php } ?>                     
                  
                  <!-- Help Menu -->
                  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="help"><?=$LANG['mnu_help']?><span class="caret"></span></a>
                     <ul class="dropdown-menu" aria-labelledby="help">
                        <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['imprint']->name?>"><i class="fa fa-<?=$CONF['controllers']['imprint']->faIcon?> fa-lg text-<?=$CONF['controllers']['imprint']->iconColor?> fa-menu"></i><?=$LANG['mnu_help_imprint']?></a></li>
                        <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['about']->name?>"><i class="fa fa-<?=$CONF['controllers']['about']->faIcon?> fa-lg text-<?=$CONF['controllers']['about']->iconColor?> fa-menu"></i><?=$LANG['mnu_help_about']?></a></li>
                     </ul>
                  </li>

                  <!-- User Menu -->
                  <li class="dropdown">
                     <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="user">
                        <img src="<?=$CONF['app_avatar_dir']?>/<?=$userData['avatar']?>" width="40" height="40" alt="" style="margin: -10px 0 -10px 0;"><span class="caret"></span>
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="user">
                        <li style="padding: 0 10px 0 10px;"><a tabindex="-1" href="#"><?=$userData['loginInfo']?></a></li>
                        <li class="divider"></li>
                        <?php if ($menuData['mnu_user_profile'] AND $userData['isLoggedIn']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['useredit']->name?>&amp;profile=<?=$userData['username']?>"><i class="fa fa-<?=$CONF['controllers']['useredit']->faIcon?> fa-lg text-<?=$CONF['controllers']['useredit']->iconColor?> fa-menu"></i><?=$LANG['mnu_user_profile']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_user_login']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['login']->name?>"><i class="fa fa-<?=$CONF['controllers']['login']->faIcon?> fa-lg text-<?=$CONF['controllers']['login']->iconColor?> fa-menu"></i><?=$LANG['mnu_user_login']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_user_register']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['register']->name?>"><i class="fa fa-<?=$CONF['controllers']['register']->faIcon?> fa-lg text-<?=$CONF['controllers']['register']->iconColor?> fa-menu"></i><?=$LANG['mnu_user_register']?></a></li>
                        <?php } ?>
                        <?php if ($menuData['mnu_user_logout']) { ?>
                           <li><a tabindex="-1" href="index.php?action=logout"><i class="fa fa-sign-out fa-lg text-danger fa-menu"></i><?=$LANG['mnu_user_logout']?></a></li>
                        <?php } ?>
                     </ul>
                  </li>

               </ul>
               
            </div>
               
         </div>
      </div>
      
      <?php if ($appStatus['maintenance'] AND $userData['roleid']=='1') { ?>
      <!-- Under Maintenance -->
      <div class="container content">
         <div class="col-lg-12">
            <div class="alert alert-danger">
               <h4><strong><?=$LANG['alert_alert_title']?></strong></h4>
               <hr>
               <p><strong><?=$LANG['alert_maintenance_subject']?></strong></p>
               <p><?=$LANG['alert_maintenance_text']?></p>
               <p><?=$LANG['alert_maintenance_help']?></p>
            </div>         
         </div>
      </div>
      <?php } ?>
      