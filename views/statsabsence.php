<?php
/**
 * statistics.php
 * 
 * The view of the statitics page
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.statistics 
      -->
      <div class="container content">

         <?php 
         if ($showAlert AND $C->read("showAlerts")!="none")
         { 
            if ( $C->read("showAlerts")=="all" OR 
                 $C->read("showAlerts")=="warnings" AND ($alertData['type']=="warning" OR $alertData['type']=="danger")
               ) 
            {
               echo createAlertBox($alertData);
            }
         }
         $tabindex = 1;
         ?>

         <form  class="bs-example form-control-horizontal noprint" enctype="multipart/form-data" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_absence" type="hidden" class="text" value="<?=$statsData['absence']?>">
            <input name="hidden_groupid" type="hidden" class="text" value="<?=$statsData['groupid']?>">
            <input name="hidden_period" type="hidden" class="text" value="<?=$statsData['period']?>">
            <input name="hidden_from" type="hidden" class="text" value="<?=$statsData['from']?>">
            <input name="hidden_to" type="hidden" class="text" value="<?=$statsData['to']?>">

            <div class="page-menu">
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalAbsence"><?=$LANG['absence'] . ': ' . $statsData['absName']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalGroup"><?=$LANG['group'] . ': ' . $statsData['groupName']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalPeriod"><?=$LANG['period'] . ': ' . $statsData['periodName']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalScale"><?=$LANG['scale'] . ': ' . $statsData['scaleName']?></button>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>"><?=$LANG['btn_reset']?></a>
            </div>

            <!-- Modal: Absence Type -->
            <?=createModalTop('modalAbsence', $LANG['stats_modalAbsenceTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_absenceType']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_absenceType_comment']?></span>
                  <select id="user" class="form-control" name="sel_absence" tabindex="<?=$tabindex++?>">
                     <option value="all" <?=(($statsData['absid']=='all')?"selected":"")?>><?=$LANG['all']?></option>
                     <?php foreach($statsData['absences'] as $abs) { ?>
                        <option value="<?=$abs['id']?>" <?=(($statsData['absid']==$abs['id'])?"selected":"")?>><?=$abs['name']?></option>
                     <?php } ?>
                  </select>
               </div>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>

              <!-- Modal: Group -->
            <?=createModalTop('modalGroup', $LANG['stats_modalGroupTitle'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($statsData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($statsData['groups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>" <?=(($statsData['groupid'] == $grp['id'])?'selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
  
            <!-- Modal: Period -->
            <?=createModalTop('modalPeriod', $LANG['stats_modalPeriodTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_period']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_period_comment']?></span>
                  <select id="sel_period" class="form-control" name="sel_period" tabindex="<?=$tabindex++?>">
                     <option value="year" <?=(($statsData['period']=='year')?"selected":"")?>><?=$LANG['period_year']?></option>
                     <option value="half" <?=(($statsData['period']=='half')?"selected":"")?>><?=$LANG['period_half']?></option>
                     <option value="quarter" <?=(($statsData['period']=='quarter')?"selected":"")?>><?=$LANG['period_quarter']?></option>
                     <option value="month" <?=(($statsData['period']=='month')?"selected":"")?>><?=$LANG['period_month']?></option>
                     <option value="custom" <?=(($statsData['period']=='custom')?"selected":"")?>><?=$LANG['custom']?></option>
                  </select>
                  <script>
                  $( "#sel_period" ).change(function() 
                  {
                     if ($(this).val() == 'custom')
                     {
                        $('#from').prop('disabled', false);
                        $('#to').prop('disabled', false);
                     }
                     else
                     {
                        $('#from').prop('disabled', true);
                        $('#to').prop('disabled', true);
                     }
                  });
                  </script>
               </div>
               <div>&nbsp;</div>
               
               <div>
                  <span class="text-bold"><?=$LANG['stats_startDate']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_startDate_comment']?></span>
                  <input id="from" class="form-control" tabindex="<?=$tabindex++?>" name="txt_from" type="text" maxlength="10" value="<?=$statsData['from']?>" <?=(($statsData['period']=='custom')?"":"disabled")?>>
                  <script>
                     $(function(){ 
                        $( "#from" ).datepicker({ 
                           changeMonth: true, 
                           changeYear: true, 
                           dateFormat: "yy-mm-dd" 
                        });
                     });
                  </script>
                  <?php if ( isset($inputAlert["from"]) AND strlen($inputAlert["from"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['from']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               
               <div>
                  <span class="text-bold"><?=$LANG['stats_endDate']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_endDate_comment']?></span>
                  <input id="to" class="form-control" tabindex="<?=$tabindex++?>" name="txt_to" type="text" maxlength="10" value="<?=$statsData['to']?>" <?=(($statsData['period']=='custom')?"":"disabled")?>>
                  <script>
                     $(function(){ 
                        $( "#to" ).datepicker({ 
                           changeMonth: true, 
                           changeYear: true, 
                           dateFormat: "yy-mm-dd" 
                        });
                     });
                  </script>
                  <?php if ( isset($inputAlert["to"]) AND strlen($inputAlert["to"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['to']?></div>
                  <?php } ?>
               </div>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
            <!-- Modal: Scale -->
            <?=createModalTop('modalScale', $LANG['stats_modalScaleTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_scale']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_scale_comment']?></span>
                  <select id="sel_scale" class="form-control" name="sel_scale" tabindex="<?=$tabindex++?>">
                     <option value="auto" <?=(($statsData['scale']=='auto')?"selected":"")?>><?=$LANG['auto']?></option>
                     <option value="smart" <?=(($statsData['scale']=='smart')?"selected":"")?>><?=$LANG['smart']?></option>
                     <option value="custom" <?=(($statsData['scale']=='custom')?"selected":"")?>><?=$LANG['custom']?></option>
                  </select>
                  <script>
                  $( "#sel_scale" ).change(function() 
                  {
                     if ($(this).val() == 'custom')
                     {
                        $('#scaleSmart').val('');
                        $('#scaleSmart').prop('disabled', true);
                        $('#scaleMax').prop('disabled', false);
                        $('#scaleMax').val('30');
                     }
                     else if ($(this).val() == 'smart')
                     {
                        $('#scaleSmart').prop('disabled', false);
                        $('#scaleSmart').val('4');
                        $('#scaleMax').prop('disabled', true);
                        $('#scaleMax').val('');
                     }
                     else
                     {
                        $('#scaleSmart').prop('disabled', true);
                        $('#scaleSmart').val('');
                        $('#scaleMax').prop('disabled', true);
                        $('#scaleMax').val('');
                     }
                  });
                  </script>
               </div>
               <div>&nbsp;</div>
               
               <div>
                  <span class="text-bold"><?=$LANG['stats_scale_smart']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_scale_smart_comment']?></span>
                  <input id="scaleSmart" class="form-control" tabindex="<?=$tabindex++?>" name="txt_scaleSmart" type="text" maxlength="3" value="<?=$statsData['scaleSmart']?>" <?=(($statsData['scale']=='smart')?"":"disabled")?>>
                  <?php if ( isset($inputAlert["scaleSmart"]) AND strlen($inputAlert["scaleSmart"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['scaleSmart']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               
               <div>
                  <span class="text-bold"><?=$LANG['stats_scale_max']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_scale_max_comment']?></span>
                  <input id="scaleMax" class="form-control" tabindex="<?=$tabindex++?>" name="txt_scaleMax" type="text" maxlength="3" value="<?=$statsData['scaleMax']?>" <?=(($statsData['scale']=='custom')?"":"disabled")?>>
                  <?php if ( isset($inputAlert["scaleMax"]) AND strlen($inputAlert["scaleMax"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['scaleMax']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
         </form>

         <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
            <div class="panel-heading">
               <i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['stats_title_absences']?>
            </div>
            <div class="panel-body">
               <strong><?=$LANG['absence'] . ':</strong> ' . $statsData['absName']?><br>
               <strong><?=$LANG['group'] . ':</strong> ' . $statsData['groupName']?><br>
               <strong><?=$LANG['period'] . ':</strong> ' . $statsData['periodName']?>
               <canvas id="myChart" style="padding-right: 40px;"></canvas>
               <script>
                  var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
      
                  var barChartData = {
                     labels : [<?=$statsData['labels']?>],
                     datasets : [
                        {
                           <?=$statsData['colorSet']['red']?>
                           data : [<?=$statsData['data']?>]
                        }
                     ]
                  }
                  
                  window.onload = function(){
                     var ctx = document.getElementById("myChart").getContext("2d");
                     window.myBar = new Chart(ctx).HorizontalBar(barChartData, 
                        {
                           responsive: true,
                           <?=$statsData['chartjsScaleSettings']?>
                        }
                     );
                  }
               </script>
            </div>
         </div>
         
      </div>

