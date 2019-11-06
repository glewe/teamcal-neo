<?php
/**
 * statsabstype.php
 * 
 * Absence type statistics page view
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
      view.statsabstype
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

            <div class="page-menu">
               <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalGroup"><?=$LANG['group']?> <span class="badge"><?=$viewData['groupName']?></span></button>
               <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalPeriod"><?=$LANG['period']?> <span class="badge"><?=$viewData['periodName']?></span></button>
               <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDiagram"><?=$LANG['diagram']?></button>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>"><?=$LANG['btn_reset']?></a>
            </div>

            <!-- Modal: Group -->
            <?=createModalTop('modalGroup', $LANG['stats_modalGroupTitle'])?>
               <span class="text-bold"><?=$LANG['stats_group']?></span><br>
               <span class="text-normal"><?=$LANG['stats_group_comment']?></span>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($viewData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['groups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>" <?=(($viewData['groupid'] == $grp['id'])?'selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select><br>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
  
            <!-- Modal: Period -->
            <?=createModalTop('modalPeriod', $LANG['stats_modalPeriodTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_period']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_period_comment']?></span>
                  <select id="sel_period" class="form-control" name="sel_period" tabindex="<?=$tabindex++?>">
                     <option value="year" <?=(($viewData['period']=='year')?"selected":"")?>><?=$LANG['period_year']?></option>
                     <option value="half" <?=(($viewData['period']=='half')?"selected":"")?>><?=$LANG['period_half']?></option>
                     <option value="quarter" <?=(($viewData['period']=='quarter')?"selected":"")?>><?=$LANG['period_quarter']?></option>
                     <option value="month" <?=(($viewData['period']=='month')?"selected":"")?>><?=$LANG['period_month']?></option>
                     <?php if (!$C->read('currentYearOnly')) {?>
                        <option value="custom" <?=(($viewData['period']=='custom')?"selected":"")?>><?=$LANG['custom']?></option>
                     <?php } ?>
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
               
               <?php if (!$C->read('currentYearOnly')) {?>
                  <div>
                     <span class="text-bold"><?=$LANG['stats_startDate']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_startDate_comment']?></span>
                     <input id="from" class="form-control" tabindex="<?=$tabindex++?>" name="txt_from" type="text" maxlength="10" value="<?=$viewData['from']?>" <?=(($viewData['period']=='custom')?"":"disabled")?>>
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
                     <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="far fa-times-circle"></i></button><?=$inputAlert['from']?></div>
                     <?php } ?>
                  </div>
                  <div>&nbsp;</div>
                  
                  <div>
                     <span class="text-bold"><?=$LANG['stats_endDate']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_endDate_comment']?></span>
                     <input id="to" class="form-control" tabindex="<?=$tabindex++?>" name="txt_to" type="text" maxlength="10" value="<?=$viewData['to']?>" <?=(($viewData['period']=='custom')?"":"disabled")?>>
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
                     <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="far fa-times-circle"></i></button><?=$inputAlert['to']?></div>
                     <?php } ?>
                  </div>
               <?php } ?>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
            <!-- Modal: Diagram -->
            <?=createModalTop('modalDiagram', $LANG['stats_modalDiagramTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_color']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_color_comment']?></span>
                  <select id="sel_color" class="form-control" name="sel_color" tabindex="<?=$tabindex++?>" <?=(($viewData['showAsPieChart'])?"disabled":"");?>>
                     <option value="blue" <?=(($viewData['color']=='blue')?"selected":"")?>><?=$LANG['blue']?></option>
                     <option value="cyan" <?=(($viewData['color']=='cyan')?"selected":"")?>><?=$LANG['cyan']?></option>
                     <option value="green" <?=(($viewData['color']=='green')?"selected":"")?>><?=$LANG['green']?></option>
                     <option value="grey" <?=(($viewData['color']=='grey')?"selected":"")?>><?=$LANG['grey']?></option>
                     <option value="magenta" <?=(($viewData['color']=='magenta')?"selected":"")?>><?=$LANG['magenta']?></option>
                     <option value="orange" <?=(($viewData['color']=='orange')?"selected":"")?>><?=$LANG['orange']?></option>
                     <option value="purple" <?=(($viewData['color']=='purple')?"selected":"")?>><?=$LANG['purple']?></option>
                     <option value="red" <?=(($viewData['color']=='red')?"selected":"")?>><?=$LANG['red']?></option>
                     <option value="yellow" <?=(($viewData['color']=='yellow')?"selected":"")?>><?=$LANG['yellow']?></option>
                  </select><br>
               </div>
               <div>
                  <div class="checkbox">
                     <label><input id="chk_showAsPieChart" name="chk_showAsPieChart" value="chk_showAsPieChart" type="checkbox" <?=(($viewData['showAsPieChart'])?"checked":"");?>><?=$LANG['stats_showAsPieChart']?></label>
                  </div>
               </div>
               <script>
               $('#chk_showAsPieChart').change(function() {
            	      if ($('#chk_showAsPieChart').prop('checked')) 
                  {
                     $( "#sel_color" ).prop( "disabled", true );
                  }
                  else {
                     $( "#sel_color" ).prop( "disabled", false );
                  }
               });
               </script>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
         </form>

         <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
            <?php 
            $pageHelp = '';
            if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
            ?>
            <div class="panel-heading">
               <i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['stats_title_abstype']?>&nbsp;(<?=$viewData['periodName']?>)<span class="label label-default pull-right label-header-right"><i data-position="tooltip-bottom" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['stats_total']?>"><?=$viewData['total']?></i></span><?=$pageHelp?>
            </div>
            <div class="panel-body">
               <p><?=$LANG['stats_abstype_desc']?></p>
               <canvas id="myChart" height="<?=$viewData['height']?>"></canvas>
               
               <script>
               <?php if (!$viewData['showAsPieChart']) { ?>
               
                  //
                  // Chart.js Bar Chart
                  //
                  var color = Chart.helpers.color;
                  var data = {
                     labels: [<?=$viewData['labels']?>],
                     datasets: [{
                        label: '<?=$LANG['taken']?>',
                        backgroundColor: color(window.chartColors.<?=$viewData['color']?>).alpha(0.5).rgbString(),
                        borderColor: window.chartColors.<?=$viewData['color']?>,
                        borderWidth: 1,
                        data: [<?=$viewData['data']?>]
                     }]
                  };

                  window.onload = function() {
                     var ctx = document.getElementById("myChart").getContext("2d");
                     window.myHorizontalBar = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: data,
                        options: {
                           elements: { rectangle: { borderWidth: 2, } },
                           responsive: true,
                           legend: { display: false },
                           title: { display: false }
                        }
                     });
                  };
               
               <?php } else { ?>
               
                  //
                  // Chart.js Pie Chart
                  //
                  var color = Chart.helpers.color;
                  var data = {
                     labels: [<?=$viewData['labels']?>],
                     datasets: [
                     {
                        data: [<?=$viewData['data']?>],
                        backgroundColor: [<?=$viewData['sliceColors']?>],
                        hoverBackgroundColor: [<?=$viewData['sliceColors']?>],
                     }]
                  };

                  window.onload = function() {
                     var ctx = document.getElementById("myChart").getContext("2d");
                     window.myHorizontalBar = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: {
                           responsive: true,
                           legend: { display: false },
                           title: { display: false }
                        }
                     });
                  };

               <?php } ?>
                  
               </script>
               
            </div>
         </div>
         
      </div>

