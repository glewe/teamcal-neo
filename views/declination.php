<?php
/**
 * declination.php
 * 
 * Declination page view
 *
 * @category TeamCal Neo 
 * @version 0.4.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.declination
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
            
            <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-primary">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['decl_title']?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                        </div>
                     </div>
                     
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tab_absence" data-toggle="tab"><?=$LANG['decl_tab_absence']?><?=(($viewData['declAbsence'])?' <i class="fa fa-check text-success"></i>':'')?></a></li>
                        <li><a href="#tab_before" data-toggle="tab"><?=$LANG['decl_tab_before']?><?=(($viewData['declBefore'])?' <i class="fa fa-check text-success"></i>':'')?></a></li>
                        <li><a href="#tab_period1" data-toggle="tab"><?=$LANG['decl_tab_period1']?><?=(($viewData['declPeriod1'])?' <i class="fa fa-check text-success"></i>':'')?></a></li>
                        <li><a href="#tab_period2" data-toggle="tab"><?=$LANG['decl_tab_period2']?><?=(($viewData['declPeriod2'])?' <i class="fa fa-check text-success"></i>':'')?></a></li>
                        <li><a href="#tab_period3" data-toggle="tab"><?=$LANG['decl_tab_period3']?><?=(($viewData['declPeriod3'])?' <i class="fa fa-check text-success"></i>':'')?></a></li>
                        <li><a href="#tab_scope" data-toggle="tab"><?=$LANG['decl_tab_scope']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Absence tab -->
                        <div class="tab-pane fade active in" id="tab_absence">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['absence'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
      
                        <!-- Before tab -->
                        <div class="tab-pane fade" id="tab_before">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['before'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
      
                        <!-- Period 1 tab -->
                        <div class="tab-pane fade" id="tab_period1">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['period1'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>

                        <!-- Period 2 tab -->
                        <div class="tab-pane fade" id="tab_period2">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['period2'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>

                        <!-- Period 3 tab -->
                        <div class="tab-pane fade" id="tab_period3">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['period3'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>

                        <!-- Scope tab -->
                        <div class="tab-pane fade" id="tab_scope">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php foreach($viewData['scope'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                              </div>
                           </div>
                        </div>
                        
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                        </div>
                     </div>
                     
                  </div>
               </div>
                  
            </form>
            
         </div>
      </div>      
            