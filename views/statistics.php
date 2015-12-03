<?php
/**
 * statistics.php
 * 
 * The view of the statitics page
 *
 * @category TeamCal Neo 
 * @version 0.3.00
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

         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_absence" type="hidden" class="text" value="<?=$statsData['absence']?>">
            <input name="hidden_period" type="hidden" class="text" value="<?=$statsData['period']?>">
            <input name="hidden_from" type="hidden" class="text" value="<?=$statsData['from']?>">
            <input name="hidden_to" type="hidden" class="text" value="<?=$statsData['to']?>">

            <div class="page-menu">
               <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSettings"><?=$LANG['settings']?></button>
            </div>

            <!-- Modal: Period -->
            <?=createModalTop('modalSettings', $LANG['stats_settingsTitle'])?>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['stats_absenceType']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_absenceType_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <select id="user" class="form-control" name="sel_absence" tabindex="<?=$tabindex++?>">
                        <option value="all" <?=(($statsData['absence']=='all')?"selected":"")?>><?=$LANG['all']?></option>
                        <?php foreach($statsData['absences'] as $abs) { ?>
                           <option value="<?=$abs['id']?>" <?=(($statsData['absence']==$abs['id'])?"selected":"")?>><?=$abs['name']?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div>&nbsp;</div>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['stats_period']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_period_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <select id="user" class="form-control" name="sel_period" tabindex="<?=$tabindex++?>">
                        <option value="year" <?=(($statsData['period']=='year')?"selected":"")?>><?=$LANG['period_year']?></option>
                        <option value="half" <?=(($statsData['period']=='half')?"selected":"")?>><?=$LANG['period_half']?></option>
                        <option value="quarter" <?=(($statsData['period']=='quarter')?"selected":"")?>><?=$LANG['period_quarter']?></option>
                        <option value="month" <?=(($statsData['period']=='month')?"selected":"")?>><?=$LANG['period_month']?></option>
                        <option value="custom" <?=(($statsData['period']=='custom')?"selected":"")?>><?=$LANG['period_custom']?></option>
                     </select>
                  </div>
               </div>
               <div>&nbsp;</div>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['stats_startDate']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_startDate_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <input id="from" class="form-control" tabindex="<?=$tabindex++?>" name="txt_from" type="text" maxlength="10" value="<?=$statsData['from']?>">
                     <script type="text/javascript">$(function() { $( "#from" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                  </div>
                  <?php if ( isset($inputAlert["from"]) AND strlen($inputAlert["from"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['from']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['stats_endDate']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_endDate_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <input id="to" class="form-control" tabindex="<?=$tabindex++?>" name="txt_to" type="text" maxlength="10" value="<?=$statsData['to']?>">
                     <script type="text/javascript">$(function() { $( "#to" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                  </div>
                  <?php if ( isset($inputAlert["to"]) AND strlen($inputAlert["to"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['to']?></div>
                  <?php } ?>
               </div>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
         </form>

         <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
            <div class="panel-heading">
               <i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['stats_title_absences']?>:&nbsp;
               <span class="label label-default"><?=$statsData['titleAbsenceType']?></span>&nbsp;
               <span class="label label-info"><?=$statsData['titlePeriod']?></span>
            </div>
         </div>
         
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
                  }
               );
            }
         </script>
         
      </div>

