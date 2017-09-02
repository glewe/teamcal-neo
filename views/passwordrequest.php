<?php
/**
 * passwordrequest.php
 * 
 * Password request page view
 *
 * @category TeamCal Neo 
 * @version 1.5.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.passwordrequest 
      -->
      <div class="container content">
      
         <div class="col-lg-3"></div>
         
         <div class="col-lg-6">
               
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
                                             
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['pwdreq_title']?></div>
               <div class="panel-body">
                  <div class="col-lg-12">
                     <?php $tabindex = 1; $colsleft = 4; $colsright = 8; $paddingBottom = "36px"; ?>
                     <form id="login" action="index.php?action=<?=$controller?>" method="post" target="_self" name="loginform" accept-charset="utf-8">
                        <fieldset>
                           <div class="form-group" style="padding-bottom: <?=$paddingBottom?>;">
                              <label class="col-lg-<?=$colsleft?> control-label"><?=$LANG['pwdreq_email']?></label>
                              <div class="col-lg-<?=$colsright?>">
                                 <input id="inputUsername" class="form-control" autofocus="autofocus" tabindex="<?=$tabindex++?>" name="txt_email" type="text" value="<?=$viewData['email']?>">
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-lg-12">
                                 <?=$LANG['pwdreq_email_comment']?>
                              </div>
                           </div>
                           
                           <?php if ($viewData['multipleUsers']) {?>
                           <div class="divider"><hr></div>
                           <div class="form-group">
                              <div class="col-lg-12">
                                 <label class="control-label"><?=$LANG['pwdreq_selectUser']?></label><br>
                                 <?=$LANG['pwdreq_selectUser_comment']?>
                              </div>
                              <div class="col-lg-12">
                                 <?php foreach($viewData['pwdUsers'] as $usr) {?>
                                 <div class="radio"><label><input name="opt_user" value="<?=$usr['username']?>" tabindex="<?=$tabindex++?>" type="radio"><?=$usr['username']?></label></div>
                                 <?php } ?>
                              </div>
                           </div>
                           <?php } ?>
                           
                           <div class="form-group">
                              <div class="col-lg-12" style="padding-top: 26px;">
                                 <button type="submit" class="btn btn-default" tabindex="<?=$tabindex++?>" name="btn_request_password"><?=$LANG['btn_reset_password']?></button>
                              </div>
                           </div>
                        </fieldset>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-lg-3"></div>
               
      </div>
