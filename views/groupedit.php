<?php
/**
 * groupedit.php
 * 
 * Group edit page view
 *
 * @category TeamCal Neo 
 * @version 1.3.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.groupedit
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
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;id=<?=$viewData['id']?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['group_edit_title'].$viewData['name']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <input name="hidden_id" type="hidden" value="<?=$viewData['id']?>">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_groupUpdate"><?=$LANG['btn_update']?></button>
                           <a href="index.php?action=groups" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_group_list']?></a>
                        </div>
                     </div>

                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tab_settings" data-toggle="tab"><?=$LANG['group_tab_settings']?></a></li>
                        <li><a href="#tab_members" data-toggle="tab"><?=$LANG['group_tab_members']?></a></li>
                     </ul>

                     <div id="myTabContent" class="tab-content">
                     
                        <!-- Group Settings -->
                        <div class="tab-pane fade active in" id="tab_settings">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['group'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <!-- Group Members -->
                        <div class="tab-pane fade" id="tab_members">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['members'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                                 <?php foreach($viewData['managers'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_groupUpdate"><?=$LANG['btn_update']?></button>
                        </div>
                     </div>
                     
                  </div>
               </div>
                  
            </form>
            
         </div>
         
      </div>      
            