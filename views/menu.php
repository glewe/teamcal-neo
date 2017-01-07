<?php
/**
 * menu.php
 * 
 * The view of the top navigation menu
 *
 * @category TeamCal Neo 
 * @version 1.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>
      <!-- ==================================================================== 
      view.menu
      -->
      <div class="navbar navbar<?=($htmlData['theme']['menuBarInverse'])?'-inverse':'-default';?> navbar-fixed-top">
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
                           <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-language fa-lg text-danger fa-menu"></i><?=$LANG['mnu_app_language']?></a>
                           <ul class="dropdown-menu">
                              <?php foreach ($appLanguages as $appLang) { ?>
                                 <li><a href="index.php?<?=str_replace('&','&amp;',$_SERVER['QUERY_STRING'])?>&amp;applang=<?=$appLang?>"><img src="languages/<?=$appLang?>.png" style="margin-right: 4px;" alt="<?=proper($appLang)?>"><?=proper($appLang)?></a></li>
                              <?php } ?>
                           </ul>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>

                  <?php if ( (isAllowed($CONF['controllers']['messages']->permission) AND $C->read('activateMessages')) OR
                              isAllowed($CONF['controllers']['calendarview']->permission) OR
                              isAllowed($CONF['controllers']['year']->permission) OR
                              isAllowed($CONF['controllers']['statsabsence']->permission) OR
                              isAllowed($CONF['controllers']['statsabstype']->permission) OR
                              isAllowed($CONF['controllers']['statspresence']->permission) OR
                              isAllowed($CONF['controllers']['statsremainder']->permission) OR
                              isAllowed($CONF['controllers']['absum']->permission)
                        ) { ?>
                     <!-- View Menu -->
                     <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="view"><?=$LANG['mnu_view']?><span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="view">
                           <?php if (isAllowed($CONF['controllers']['calendarview']->permission)) {
                              if ($controller=='logout') 
                                 $urlparams = "&amp;month=".date('Y').date('m')."&amp;region=1&amp;group=all&amp;abs=all";
                              else
                                 if (!$urlparams=$UO->read($UL->username, 'calfilter')) $urlparams = "&amp;month=".date('Y').date('m')."&amp;region=1&amp;group=all&amp;abs=all";
                              ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['calendarview']->name.$urlparams?>"><i class="fa fa-<?=$CONF['controllers']['calendarview']->faIcon?> fa-lg text-<?=$CONF['controllers']['calendarview']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_calendar']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['year']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['year']->name?>&amp;year=<?=date('Y')?>&amp;region=1&amp;user=<?=$UL->username?>"><i class="fa fa-<?=$CONF['controllers']['year']->faIcon?> fa-lg text-<?=$CONF['controllers']['year']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_year']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['messages']->permission) AND $C->read('activateMessages')) { ?>
                              <li class="divider"></li>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['messages']->name?>"><i class="fa fa-<?=$CONF['controllers']['messages']->faIcon?> fa-lg text-<?=$CONF['controllers']['messages']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_messages']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['statsabsence']->permission) OR
                                     isAllowed($CONF['controllers']['statspresence']->permission)
                                 ) { ?>
                              <li class="dropdown-submenu">
                                 <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart fa-lg text-warning fa-menu"></i><?=$LANG['mnu_view_stats']?></a>
                                 <ul class="dropdown-menu">
                                    <?php if (isAllowed($CONF['controllers']['statsabsence']->permission)) { ?>
                                       <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['statsabsence']->name?>"><i class="fa fa-<?=$CONF['controllers']['statsabsence']->faIcon?> fa-lg text-<?=$CONF['controllers']['statsabsence']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_absences']?></a></li>
                                    <?php } ?>
                                    <?php if (isAllowed($CONF['controllers']['statspresence']->permission)) { ?>
                                       <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['statspresence']->name?>"><i class="fa fa-<?=$CONF['controllers']['statspresence']->faIcon?> fa-lg text-<?=$CONF['controllers']['statspresence']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_presences']?></a></li>
                                    <?php } ?>
                                    <?php if (isAllowed($CONF['controllers']['statsabstype']->permission)) { ?>
                                       <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['statsabstype']->name?>"><i class="fa fa-<?=$CONF['controllers']['statsabstype']->faIcon?> fa-lg text-<?=$CONF['controllers']['statsabstype']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_abstype']?></a></li>
                                    <?php } ?>
                                    <?php if (isAllowed($CONF['controllers']['statsremainder']->permission)) { ?>
                                       <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['statsremainder']->name?>"><i class="fa fa-<?=$CONF['controllers']['statsremainder']->faIcon?> fa-lg text-<?=$CONF['controllers']['statsremainder']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_remainder']?></a></li>
                                    <?php } ?>
                                    <?php if (isAllowed($CONF['controllers']['absum']->permission)) { ?>
                                       <li class="divider"></li>
                                       <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['absum']->name?>&amp;user=<?=$userData['username']?>"><i class="fa fa-<?=$CONF['controllers']['absum']->faIcon?> fa-lg text-<?=$CONF['controllers']['absum']->iconColor?> fa-menu"></i><?=$LANG['mnu_view_stats_absum']?></a></li>
                                    <?php } ?>
                                 </ul>
                              </li>
                           <?php } ?>
                        </ul>
                     </li>
                  <?php } ?>                     
                  
                  <?php if ( isAllowed($CONF['controllers']['calendaredit']->permission) OR
                             isAllowed($CONF['controllers']['monthedit']->permission) OR
                             isAllowed($CONF['controllers']['messageedit']->permission) OR
                             isAllowed($CONF['controllers']['attachments']->permission)
                        ) { ?>
                     <!-- Edit Menu -->
                     <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="tools"><?=$LANG['mnu_edit']?><span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="tools">
                            <?php if (isAllowed($CONF['controllers']['calendaredit']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['calendaredit']->name?>&amp;month=<?=date('Y').date('m')?>&amp;region=1&amp;user=<?=$userData['username']?>"><i class="fa fa-<?=$CONF['controllers']['calendaredit']->faIcon?> fa-lg text-<?=$CONF['controllers']['calendaredit']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_calendaredit']?></a></li>
                           <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['monthedit']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['monthedit']->name?>&amp;month=<?=date('Y').date('m')?>&amp;region=1"><i class="fa fa-<?=$CONF['controllers']['monthedit']->faIcon?> fa-lg text-<?=$CONF['controllers']['monthedit']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_monthedit']?></a></li>
                           <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['messageedit']->permission) AND $C->read('activateMessages')) { ?>
                              <li class="divider"></li>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['messageedit']->name?>"><i class="fa fa-<?=$CONF['controllers']['messageedit']->faIcon?> fa-lg text-<?=$CONF['controllers']['messageedit']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_messageedit']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['attachments']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['attachments']->name?>"><i class="fa fa-<?=$CONF['controllers']['attachments']->faIcon?> fa-lg text-<?=$CONF['controllers']['attachments']->iconColor?> fa-menu"></i><?=$LANG['mnu_edit_attachments']?></a></li>
                           <?php } ?>
                        </ul>
                     </li>
                  <?php } ?>                     
                  
               </ul>
      
               <ul class="nav navbar-nav navbar-right">
               
                  <?php if ( isAllowed($CONF['controllers']['config']->permission) OR 
                             isAllowed($CONF['controllers']['calendaroptions']->permission) OR
                             isAllowed($CONF['controllers']['permissions']->permission) OR
                             isAllowed($CONF['controllers']['users']->permission) OR
                             isAllowed($CONF['controllers']['groups']->permission) OR
                             isAllowed($CONF['controllers']['roles']->permission) OR
                             isAllowed($CONF['controllers']['database']->permission) OR
                             isAllowed($CONF['controllers']['database']->permission) OR
                             isAllowed($CONF['controllers']['phpinfo']->permission) OR
                             isAllowed($CONF['controllers']['absences']->permission) OR
                             isAllowed($CONF['controllers']['holidays']->permission) OR
                             isAllowed($CONF['controllers']['regions']->permission) OR
                             isAllowed($CONF['controllers']['declination']->permission)
                        ) { ?>
                     <!-- Admin Menu -->
                     <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="admin"><?=$LANG['mnu_admin']?><span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="admin">
                           <?php if (isAllowed($CONF['controllers']['config']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['config']->name?>"><i class="fa fa-<?=$CONF['controllers']['config']->faIcon?> fa-lg text-<?=$CONF['controllers']['config']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_config']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['calendaroptions']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['calendaroptions']->name?>"><i class="fa fa-<?=$CONF['controllers']['calendaroptions']->faIcon?> fa-lg text-<?=$CONF['controllers']['calendaroptions']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_calendaroptions']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['permissions']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['permissions']->name?>"><i class="fa fa-<?=$CONF['controllers']['permissions']->faIcon?> fa-lg text-<?=$CONF['controllers']['permissions']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_perm']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['users']->permission)) { ?>
                              <li class="divider"></li>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['users']->name?>"><i class="fa fa-<?=$CONF['controllers']['users']->faIcon?> fa-lg text-<?=$CONF['controllers']['users']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_users']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['groups']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['groups']->name?>"><i class="fa fa-<?=$CONF['controllers']['groups']->faIcon?> fa-lg text-<?=$CONF['controllers']['groups']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_groups']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['roles']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['roles']->name?>"><i class="fa fa-<?=$CONF['controllers']['roles']->faIcon?> fa-lg text-<?=$CONF['controllers']['roles']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_roles']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['absences']->permission)) { ?>
                              <li class="divider"></li>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['absences']->name?>"><i class="fa fa-<?=$CONF['controllers']['absences']->faIcon?> fa-lg text-<?=$CONF['controllers']['absences']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_absences']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['holidays']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['holidays']->name?>"><i class="fa fa-<?=$CONF['controllers']['holidays']->faIcon?> fa-lg text-<?=$CONF['controllers']['holidays']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_holidays']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['regions']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['regions']->name?>"><i class="fa fa-<?=$CONF['controllers']['regions']->faIcon?> fa-lg text-<?=$CONF['controllers']['regions']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_regions']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['declination']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['declination']->name?>"><i class="fa fa-<?=$CONF['controllers']['declination']->faIcon?> fa-lg text-<?=$CONF['controllers']['declination']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_declination']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['database']->permission)) { ?>
                              <li class="divider"></li>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['database']->name?>"><i class="fa fa-<?=$CONF['controllers']['database']->faIcon?> fa-lg text-<?=$CONF['controllers']['database']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_database']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['log']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['log']->name?>"><i class="fa fa-<?=$CONF['controllers']['log']->faIcon?> fa-lg text-<?=$CONF['controllers']['log']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_systemlog']?></a></li>
                           <?php } ?>
                           <?php if (isAllowed($CONF['controllers']['phpinfo']->permission)) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['phpinfo']->name?>"><i class="fa fa-<?=$CONF['controllers']['phpinfo']->faIcon?> fa-lg text-<?=$CONF['controllers']['phpinfo']->iconColor?> fa-menu"></i><?=$LANG['mnu_admin_phpinfo']?></a></li>
                           <?php } ?>                     
                        </ul>
                     </li>
                  <?php } ?>                     
                  
                  <!-- Help Menu -->
                  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="help"><?=$LANG['mnu_help']?><span class="caret"></span></a>
                     <ul class="dropdown-menu" aria-labelledby="help">
                        <?php if ($docLink = $C->read("userManual")) {?>
                           <li><a tabindex="-1" href="<?=urldecode($docLink)?>" target="_blank"><i class="fa fa-book fa-lg text-success fa-menu"></i><?=$LANG['mnu_help_help']?></a></li>
                           <li class="divider"></li>
                        <?php } ?>
                        <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['imprint']->name?>"><i class="fa fa-<?=$CONF['controllers']['imprint']->faIcon?> fa-lg text-<?=$CONF['controllers']['imprint']->iconColor?> fa-menu"></i><?=$LANG['mnu_help_imprint']?></a></li>
                        <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['about']->name?>"><i class="fa fa-<?=$CONF['controllers']['about']->faIcon?> fa-lg text-<?=$CONF['controllers']['about']->iconColor?> fa-menu"></i><?=$LANG['mnu_help_about']?></a></li>
                        <li><a tabindex="-1" href="http://www.lewe.com/teamcal-neo/#tcnvote" target="_blank"><i class="fa fa-comment fa-lg text-warning fa-menu"></i><?=$LANG['mnu_help_vote']?></a></li>
                     </ul>
                  </li>

                  <!-- User Menu -->
                  <li class="dropdown">
                     <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="user">
                        <img src="<?=APP_AVATAR_DIR.$userData['avatar']?>" width="40" height="40" alt="" style="margin: -10px 0 -10px 0;"><span class="caret"></span>
                     </a>
                     <ul class="dropdown-menu" aria-labelledby="user">
                        <li style="padding: 0 10px 0 10px;"><a tabindex="-1" href="#"><?=$userData['loginInfo']?></a></li>
                        <li class="divider"></li>
                        <?php if ($userData['isLoggedIn']) { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['useredit']->name?>&amp;profile=<?=$userData['username']?>"><i class="fa fa-<?=$CONF['controllers']['useredit']->faIcon?> fa-lg text-<?=$CONF['controllers']['useredit']->iconColor?> fa-menu"></i><?=$LANG['mnu_user_profile']?></a></li>
                           <li><a tabindex="-1" href="index.php?action=logout"><i class="fa fa-sign-out fa-lg text-danger fa-menu"></i><?=$LANG['mnu_user_logout']?></a></li>
                        <?php } 
                        else { ?>
                           <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['login']->name?>"><i class="fa fa-<?=$CONF['controllers']['login']->faIcon?> fa-lg text-<?=$CONF['controllers']['login']->iconColor?> fa-menu"></i><?=$LANG['mnu_user_login']?></a></li>
                           <?php if ($C->read("allowRegistration")) { ?>
                              <li><a tabindex="-1" href="index.php?action=<?=$CONF['controllers']['register']->name?>"><i class="fa fa-<?=$CONF['controllers']['register']->faIcon?> fa-lg text-<?=$CONF['controllers']['register']->iconColor?> fa-menu"></i><?=$LANG['mnu_user_register']?></a></li>
                           <?php } ?>
                        <?php } ?>
                     </ul>
                  </li>

               </ul>
               
            </div>
               
         </div>
      </div>
      
      <?php if ($C->read('showBanner')) { ?>
      <!-- Banner -->
      <div class="jumbotron">
         <div class="container">
            <div class="col-lg-9 banner">
               <?=$C->read('appTitle')?>
            </div>
            <div class="col-lg-3" style="padding-top: 20px;">
               <form name="search" method="get" action="http://www.google.com/search">
                  <div class="input-group">
                     <input class="form-control" id="txt_search" type="text" name="q" size="20" maxlength="255" placeholder="Search">
                     <span class="input-group-btn">
                        <button id="btn_search" type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                     </span>
                  </div>
                  <input type="hidden" name="sitesearch" value="lewe.com">
               </form>
            </div>
         </div>
      </div>
      <?php } ?>

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
      