<?php
/**
 * absence.php
 * 
 * The view of the absence edit page
 *
 * @category TeamCal Neo 
 * @version 0.3.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.absence
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
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;id=<?=$absData['id']?>" method="post" target="_self" accept-charset="utf-8">

               <input name="hidden_id" type="hidden" class="text" value="<?=$absData['id']?>">
               
               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['abs_edit_title'].$absData['name']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absences" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_list']?></a>
                        </div>
                     </div>

                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#general" data-toggle="tab"><?=$LANG['general']?></a></li>
                        <li><a href="#options" data-toggle="tab"><?=$LANG['options']?></a></li>
                        <li><a href="#groupassignments" data-toggle="tab"><?=$LANG['abs_tab_groups']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                     
                        <div class="tab-pane fade active in" id="general">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 
                                 <!-- Sample display -->
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['abs_sample']?><br>
                                       <span class="text-normal"><?=$LANG['abs_sample_comment']?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <?php if ($absData['bgtrans']) $bgStyle = ""; else $bgStyle = "background-color: #".$absData['bgcolor']; ?>
                                       <div id="sample" style="color: #<?=$absData['color']?>; <?=$bgStyle?>; border: 1px solid #333333; width: 26px; height: 26px; text-align: center; padding-top: 2px;">
                                          <?php if ($absData['icon'] != "No") { ?>
                                             <span class="fa fa-<?=$absData['icon']?>"></span>
                                          <?php } else { ?>
                                             <?=$absData['symbol']?>
                                          <?php } ?>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>

                                 <!-- Icon -->
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['abs_icon']?><br>
                                       <span class="text-normal"><?=$LANG['abs_icon_comment']?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <span class="fa fa-<?=$absData['icon']?> text-<?=$absData['iconcolor']?>" style="font-size: 150%; padding-right: 8px; vertical-align: middle;"></span>
                                       <a href="index.php?action=absenceicon&amp;id=<?=$absData['id']?>" class="btn btn-primary btn-sm" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_icon']?></a>
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                       
                                 <?php foreach($absData['general'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>

                              </div>
                           </div>
                        </div>
                        
                        <div class="tab-pane fade in" id="options">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($absData['options'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                        <div class="tab-pane fade in" id="groupassignments">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($absData['groups'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                           <a href="index.php?action=absences" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_abs_list']?></a>
                        </div>
                     </div>

                  </div>
               </div>
                  
            </form>
            
         </div>
         
      </div>      
            