<?php
/**
 * config.php
 * 
 * Framework config page view
 *
 * @category TeamCal Neo 
 * @version 1.9.011
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.config 
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
            
            <form  class="bs-example form-control-horizontal" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <?php 
                  $pageHelp = '';
                  if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                  ?>
                  <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['config_title']?><?=$pageHelp?></div>
                  <div class="panel-body">
                  
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_confApply"><?=$LANG['btn_apply']?></button>
                        </div>
                     </div>
                     
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#general" data-toggle="tab"><?=$LANG['general']?></a></li>
                        <li><a href="#email" data-toggle="tab"><?=$LANG['config_tab_email']?></a></li>
                        <li><a href="#footer" data-toggle="tab"><?=$LANG['config_tab_footer']?></a></li>
                        <li><a href="#homepage" data-toggle="tab"><?=$LANG['config_tab_homepage']?></a></li>
                        <li><a href="#login" data-toggle="tab"><?=$LANG['config_tab_login']?></a></li>
                        <li><a href="#registration" data-toggle="tab"><?=$LANG['config_tab_registration']?></a></li>
                        <li><a href="#system" data-toggle="tab"><?=$LANG['config_tab_system']?></a></li>
                        <li><a href="#tabtheme" data-toggle="tab"><?=$LANG['config_tab_theme']?></a></li>
                        <li><a href="#usericons" data-toggle="tab"><?=$LANG['config_tab_user']?></a></li>
                        <li><a href="#gdpr" data-toggle="tab"><?=$LANG['config_tab_gdpr']?></a></li>
                     </ul>

                     <div id="myTabContent" class="tab-content">
                        
                        <!-- General tab -->
                        <div class="tab-pane fade active in" id="general">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['general'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
      
                        <!-- E-mail tab -->
                        <div class="tab-pane fade" id="email">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['email'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Footer tab -->
                        <div class="tab-pane fade" id="footer">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['footer'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Homepage tab -->
                        <div class="tab-pane fade" id="homepage">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['homepage'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
      
                        <!-- Login tab -->
                        <div class="tab-pane fade" id="login">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['login'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Registration tab -->
                        <div class="tab-pane fade" id="registration">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['registration'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- System tab -->
                        <div class="tab-pane fade" id="system">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['system'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Theme tab -->
                        <div class="tab-pane fade" id="tabtheme">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['theme'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- User tab -->
                        <div class="tab-pane fade" id="usericons">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['user'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>

                        <!-- GDPR tab -->
                        <div class="tab-pane fade" id="gdpr">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['gdpr'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                                 <div class="form-group" id="form-group-gdprPlatforms">
                                    <label for="gdprFacebook" class="col-lg-8 control-label">
                                       <?=$LANG['config_gdprPlatforms']?><br>
                                       <span class="text-normal"><?=$LANG['config_gdprPlatforms_comment']?></span>
                                    </label>
                                    <div class="col-lg-4">
                                       <div class="checkbox">
                                          <label><input type="checkbox" id="gdprFacebook" name="chk_gdprFacebook" value="chk_gdprFacebook" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprFacebook')?" checked":"")?>><i class="fab fa-facebook"></i>&nbsp;Facebook</label><br>
                                          <label><input type="checkbox" id="gdprGoogleAnalytics" name="chk_gdprGoogleAnalytics" value="chk_gdprGoogleAnalytics" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprGoogleAnalytics')?" checked":"")?>><i class="fab fa-google"></i>&nbsp;Google Analytics</label><br>
                                          <label><input type="checkbox" id="gdprGooglePlus" name="chk_gdprGooglePlus" value="chk_gdprGooglePlus" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprGooglePlus')?" checked":"")?>><i class="fab fa-google-plus-g"></i>&nbsp;Google+</label><br>
                                          <label><input type="checkbox" id="gdprInstagram" name="chk_gdprInstagram" value="chk_gdprInstagram" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprInstagram')?" checked":"")?>><i class="fab fa-instagram"></i>&nbsp;Instagram</label><br>
                                          <label><input type="checkbox" id="gdprLinkedin" name="chk_gdprLinkedin" value="chk_gdprLinkedin" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprLinkedin')?" checked":"")?>><i class="fab fa-linkedin"></i>&nbsp;LinkedIn</label><br>
                                          <label><input type="checkbox" id="gdprPaypal" name="chk_gdprPaypal" value="chk_gdprPaypal" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprPaypal')?" checked":"")?>><i class="fab fa-paypal"></i>&nbsp;Paypal</label><br>
                                          <label><input type="checkbox" id="gdprPinterest" name="chk_gdprPinterest" value="chk_gdprPinterest" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprPinterest')?" checked":"")?>><i class="fab fa-pinterest"></i>&nbsp;Pinterest</label><br>
                                          <label><input type="checkbox" id="gdprSlideshare" name="chk_gdprSlideshare" value="chk_gdprSlideshare" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprSlideshare')?" checked":"")?>><i class="fab fa-slideshare"></i>&nbsp;Slideshare</label><br>
                                          <label><input type="checkbox" id="gdprTumblr" name="chk_gdprTumblr" value="chk_gdprTumblr" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprTumblr')?" checked":"")?>><i class="fab fa-tumblr"></i>&nbsp;Tumblr</label><br>
                                          <label><input type="checkbox" id="gdprTwitter" name="chk_gdprTwitter" value="chk_gdprTwitter" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprTwitter')?" checked":"")?>><i class="fab fa-twitter"></i>&nbsp;Twitter</label><br>
                                          <label><input type="checkbox" id="gdprXing" name="chk_gdprXing" value="chk_gdprXing" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprXing')?" checked":"")?>><i class="fab fa-xing"></i>&nbsp;Xing</label><br>
                                          <label><input type="checkbox" id="gdprYoutube" name="chk_gdprYoutube" value="chk_gdprYoutube" tabindex="<?=$tabindex++;?>"<?=($C->read('gdprYoutube')?" checked":"")?>><i class="fab fa-youtube"></i>&nbsp;Youtube</label>
                                       </div>
                                    </div>
                                    <div class="divider"><hr></div>
                                 </div>    
                              </div>
                           </div>
                        </div>
                        
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_confApply"><?=$LANG['btn_apply']?></button>
                        </div>
                     </div>
                     
                  </div>
               </div>
               
            </form>
            
         </div>
         
      </div>      
            