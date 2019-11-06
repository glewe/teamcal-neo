<?php
/**
 * declination.php
 * 
 * Declination page view
 *
 * @category TeamCal Neo 
 * @version 2.2.1
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
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
                  <?php 
                  $pageHelp = '';
                  if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                  ?>
                  <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['decl_title'].$pageHelp?></div>
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                        </div>
                     </div>
                     
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tab_overview" data-toggle="tab"><?=$LANG['decl_tab_overview']?></a></li>
                        <li><a href="#tab_absence" data-toggle="tab"><?=$LANG['decl_tab_absence']?><?=(($viewData['declAbsence'])?' <i class="fas fa-check text-danger"></i>':'')?></a></li>
                        <li><a href="#tab_before" data-toggle="tab"><?=$LANG['decl_tab_before']?><?=(($viewData['declBefore'])?' <i class="fas fa-check text-danger"></i>':'')?></a></li>
                        <li><a href="#tab_period1" data-toggle="tab"><?=$LANG['decl_tab_period1']?><?=(($viewData['declPeriod1'])?' <i class="fas fa-check text-danger"></i>':'')?></a></li>
                        <li><a href="#tab_period2" data-toggle="tab"><?=$LANG['decl_tab_period2']?><?=(($viewData['declPeriod2'])?' <i class="fas fa-check text-danger"></i>':'')?></a></li>
                        <li><a href="#tab_period3" data-toggle="tab"><?=$LANG['decl_tab_period3']?><?=(($viewData['declPeriod3'])?' <i class="fas fa-check text-danger"></i>':'')?></a></li>
                        <li><a href="#tab_scope" data-toggle="tab"><?=$LANG['decl_tab_scope']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Overview tab -->
                        <div class="tab-pane fade active in" id="tab_overview">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <?php $overviews = array('Absence', 'Before', 'Period1', 'Period2', 'Period3'); 
                                 foreach ($overviews as $overview) { 
                                 
                                    $details='';
                                    $schedule='';
                                    
                                    switch ($overview) {
                                       case 'Absence':
                                          $details = $viewData['declThreshold'] . '%';
                                          break;
                                       case 'Before':
                                          $details = $viewData['declBeforeDate'];
                                          break;
                                       case 'Period1':
                                          $details = $viewData['declPeriod1Start'] . ' - ' . $viewData['declPeriod1End'];
                                          break;
                                       case 'Period2':
                                          $details = $viewData['declPeriod2Start'] . ' - ' . $viewData['declPeriod2End'];
                                          break;
                                       case 'Period3':
                                          $details = $viewData['declPeriod3Start'] . ' - ' . $viewData['declPeriod3End'];
                                          break;
                                    }
                                    
                                    switch ($viewData['decl'.$overview.'Period']) {
                                       case 'nowForever':
                                          $schedule = $LANG['decl_schedule_nowForever'];
                                          break;
                                       case 'nowEnddate':
                                          $schedule = sprintf($LANG['decl_schedule_nowEnddate'],$viewData['decl'.$overview.'Enddate']);
                                          break;
                                       case 'startdateForever':
                                          $schedule = sprintf($LANG['decl_schedule_startdateForever'],$viewData['decl'.$overview.'Startdate']);
                                          break;
                                       case 'startdateEnddate':
                                          $schedule = sprintf($LANG['decl_schedule_startdateEnddate'],$viewData['decl'.$overview.'Startdate'],$viewData['decl'.$overview.'Enddate']);
                                          break;
                                    }
                                    ?>
                              
                                    <div class="form-group" id="form-group-overview-<?=$overview?>">
                                       <label class="col-lg-8 control-label">
                                          <?=$LANG['decl_tab_'.strtolower($overview)]?><br>
                                          <span class="text-normal"><?=$LANG['decl_summary_'.strtolower($overview)]?><br>
                                             <span class="small text-italic text-info"><strong><?=$LANG['decl_value']?>: </strong><?=$details?></span><br>
                                             <span class="small text-italic text-info"><strong><?=$LANG['decl_schedule']?>: </strong><?=$schedule?></span><br>
                                          </span>
                                       </label>
                                       <div class="col-lg-4">
                                          <?php switch ($viewData['decl'.$overview.'Status']) { 
                                             case 'active': ?>
                                                <span class="label label-danger"><?=$LANG['decl_label_active']?></span>
                                                <?php break;
                                             case 'expired': ?>
                                                <span class="label label-success"><?=$LANG['decl_label_expired']?></span>
                                                <?php break;
                                             case 'inactive': ?>
                                                <span class="label label-default"><?=$LANG['decl_label_inactive']?></span>
                                                <?php break;
                                             case 'scheduled': ?>
                                                <span class="label label-warning"><?=$LANG['decl_label_scheduled']?></span>
                                                <?php break;
                                          } ?>
                                       </div>
                                       <div class="divider"><hr></div>
                                    </div>
                                    
                                 <?php } ?>                              
                              
                              </div>
                           </div>
                        </div>
      
                        <!-- Absence tab -->
                        <div class="tab-pane fade" id="tab_absence">
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
            
      <script>
         $(document).ready(function() {
             
             $('input[type=radio][name=opt_absencePeriod]').change(function() {
                 if (this.value == 'nowForever') {
                     $( "#absenceStartdate" ).prop( "disabled", true );
                     $( "#absenceEnddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'nowEnddate') {
                     $( "#absenceStartdate" ).prop( "disabled", true );
                     $( "#absenceEnddate" ).prop( "disabled", false );
                 }
                 else if (this.value == 'startdateForever') {
                     $( "#absenceStartdate" ).prop( "disabled", false );
                     $( "#absenceEnddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'startdateEnddate') {
                     $( "#absenceStartdate" ).prop( "disabled", false );
                     $( "#absenceEnddate" ).prop( "disabled", false );
                 }
             });
             
             $('input[type=radio][name=opt_beforePeriod]').change(function() {
                 if (this.value == 'nowForever') {
                     $( "#beforeStartdate" ).prop( "disabled", true );
                     $( "#beforeEnddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'nowEnddate') {
                     $( "#beforeStartdate" ).prop( "disabled", true );
                     $( "#beforeEnddate" ).prop( "disabled", false );
                 }
                 else if (this.value == 'startdateForever') {
                     $( "#beforeStartdate" ).prop( "disabled", false );
                     $( "#beforeEnddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'startdateEnddate') {
                     $( "#beforeStartdate" ).prop( "disabled", false );
                     $( "#beforeEnddate" ).prop( "disabled", false );
                 }
             });
             
             $('input[type=radio][name=opt_period1Period]').change(function() {
                 if (this.value == 'nowForever') {
                     $( "#period1Startdate" ).prop( "disabled", true );
                     $( "#period1Enddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'nowEnddate') {
                     $( "#period1Startdate" ).prop( "disabled", true );
                     $( "#period1Enddate" ).prop( "disabled", false );
                 }
                 else if (this.value == 'startdateForever') {
                     $( "#period1Startdate" ).prop( "disabled", false );
                     $( "#period1Enddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'startdateEnddate') {
                     $( "#period1Startdate" ).prop( "disabled", false );
                     $( "#period1Enddate" ).prop( "disabled", false );
                 }
             });
             
             $('input[type=radio][name=opt_period2Period]').change(function() {
                 if (this.value == 'nowForever') {
                     $( "#period2Startdate" ).prop( "disabled", true );
                     $( "#period2Enddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'nowEnddate') {
                     $( "#period2Startdate" ).prop( "disabled", true );
                     $( "#period2Enddate" ).prop( "disabled", false );
                 }
                 else if (this.value == 'startdateForever') {
                     $( "#period2Startdate" ).prop( "disabled", false );
                     $( "#period2Enddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'startdateEnddate') {
                     $( "#period2Startdate" ).prop( "disabled", false );
                     $( "#period2Enddate" ).prop( "disabled", false );
                 }
             });
             
             $('input[type=radio][name=opt_period3Period]').change(function() {
                 if (this.value == 'nowForever') {
                     $( "#period3Startdate" ).prop( "disabled", true );
                     $( "#period3Enddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'nowEnddate') {
                     $( "#period3Startdate" ).prop( "disabled", true );
                     $( "#period3Enddate" ).prop( "disabled", false );
                 }
                 else if (this.value == 'startdateForever') {
                     $( "#period3Startdate" ).prop( "disabled", false );
                     $( "#period3Enddate" ).prop( "disabled", true );
                 }
                 else if (this.value == 'startdateEnddate') {
                     $( "#period3Startdate" ).prop( "disabled", false );
                     $( "#period3Enddate" ).prop( "disabled", false );
                 }
             });
             
         });
      
      </script>