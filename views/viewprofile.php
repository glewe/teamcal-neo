<?php
/**
 * viewprofile.php
 * 
 * View profile page view
 *
 * @category TeamCal Neo 
 * @version 1.9.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2018 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.viewprofile
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <?php 
            if ($showAlert AND $C->read("showAlerts")!="none")
            { 
               if ( $C->read("showAlerts")=="all" OR 
                    $C->read("showAlerts")=="warnings" AND ($alertData['type']=="warning" OR $alertData['type']=="danger")
                  ) 
               {
                  echo createAlertBox($alertData);
               }
            } ?>
            <?php $tabindex = 1; $colsleft = 8; $colsright = 4;?>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fa fa-question-circle fa-lg fa-menu"></i></a>';
               ?>
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['profile_view_title'].$viewData['fullname']?> (<?=$viewData['username']?>)<?=$pageHelp?></div>

               <div class="panel-body">

                  <?php if ($viewData['allowEdit'] OR $viewData['allowAbsum']) { ?>
                  <div class="panel panel-default">
                     <div class="panel-body">
                        <?php if ($viewData['allowEdit']) { ?>
                           <a class="btn btn-primary" tabindex="<?=$tabindex++;?>" href="index.php?action=<?=$CONF['controllers']['useredit']->name?>&amp;profile=<?=$viewData['username']?>"><?=$LANG['btn_edit']?></a>
                        <?php } ?>
                        <?php if ($viewData['allowAbsum']) { ?>
                           <a class="btn btn-info" tabindex="<?=$tabindex++;?>" href="index.php?action=<?=$CONF['controllers']['absum']->name?>&amp;user=<?=$viewData['username']?>"><?=$LANG['btn_absum']?></a>
                        <?php } ?>
                     </div>
                  </div>
                  <?php } ?>
               
                  <div class="bs-example table-responsive">
                     <table class="table table-striped table-bordered table-hover">
                        <tbody>
                           <tr>
                              <td><i class="fa fa-camera text-danger" style="width: 26px;"></i><strong><?=$LANG['profile_avatar']?></strong></td>
                              <td><img src="<?=APP_AVATAR_DIR.$viewData['avatar']?>" alt="" class="boxshadow-1" style="width: 80px; height: 80px; padding: 4px; border: 1px solid #999999;"></td>
                           </tr>
                           <tr>
                              <td><i class="fa fa-users text-<?=$CONF['controller']['roles']['menucolor']?>" style="width: 26px;"></i><strong><?=$LANG['profile_role']?></strong></td>
                              <td><?=$viewData['role']?></td>
                           </tr>
                           <?php if (strlen($viewData['title'])) { ?>
                           <tr>
                              <td><i class="fa fa-pencil text-info" style="width: 26px;"></i><strong><?=$LANG['profile_title']?></strong></td>
                              <td><?=$viewData['title']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['position'])) { ?>
                           <tr>
                              <td><i class="fa fa-building text-default" style="width: 26px;"></i><strong><?=$LANG['profile_position']?></strong></td>
                              <td><?=$viewData['position']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['email'])) { ?>
                           <tr>
                              <td><i class="fa fa-envelope-o text-success" style="width: 26px;"></i><strong><?=$LANG['profile_email']?></strong></td>
                              <td><?=$viewData['email']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['phone'])) { ?>
                           <tr>
                              <td><i class="fa fa-phone text-info" style="width: 26px;"></i><strong><?=$LANG['profile_phone']?></strong></td>
                              <td><?=$viewData['phone']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['mobile'])) { ?>
                           <tr>
                              <td><i class="fa fa-mobile text-warning" style="width: 26px;"></i><strong><?=$LANG['profile_mobilephone']?></strong></td>
                              <td><?=$viewData['mobile']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['facebook'])) { ?>
                           <tr>
                              <td><i class="fa fa-facebook text-primary" style="width: 26px;"></i><strong><?=$LANG['profile_facebook']?></strong></td>
                              <td><?=$viewData['facebook']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['google'])) { ?>
                           <tr>
                              <td><i class="fa fa-google-plus text-danger" style="width: 26px;"></i><strong><?=$LANG['profile_google']?></strong></td>
                              <td><?=$viewData['google']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['linkedin'])) { ?>
                           <tr>
                              <td><i class="fa fa-linkedin text-primary" style="width: 26px;"></i><strong><?=$LANG['profile_linkedin']?></strong></td>
                              <td><?=$viewData['linkedin']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['skype'])) { ?>
                           <tr>
                              <td><i class="fa fa-skype text-success" style="width: 26px;"></i><strong><?=$LANG['profile_skype']?></strong></td>
                              <td><?=$viewData['skype']?></td>
                           </tr>
                           <?php } ?>
                           <?php if (strlen($viewData['twitter'])) { ?>
                           <tr>
                              <td><i class="fa fa-twitter text-primary" style="width: 26px;"></i><strong><?=$LANG['profile_twitter']?></strong></td>
                              <td><?=$viewData['twitter']?></td>
                           </tr>
                           <?php } ?>
                           
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>      
