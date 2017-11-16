<?php
/**
 * statsremainder.php
 * 
 * Remainder statistics page view
 *
 * @category TeamCal Neo 
 * @version 1.9.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
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
               <?php if (!$C->read('currentYearOnly')) {?>
                  <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalYear"><?=$LANG['year']?> <span class="badge"><?=$viewData['year']?></span></button>
               <?php } ?>
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
  
            <!-- Modal: Year -->
            <?php if (!$C->read('currentYearOnly')) { 
               echo createModalTop('modalYear', $LANG['stats_modalYearTitle']); ?>
                  <div>
                     <span class="text-bold"><?=$LANG['stats_year']?></span><br>
                     <span class="text-normal"><?=$LANG['stats_year_comment']?></span>
                     <select id="sel_year" class="form-control" name="sel_year" tabindex="<?=$tabindex++?>">
                        <option value="<?=date("Y")-1?>" <?=(($viewData['year']==date("Y")-1)?"selected":"")?>><?=date("Y")-1?></option>
                        <option value="<?=date("Y")?>" <?=(($viewData['year']==date("Y"))?"selected":"")?>><?=date("Y")?></option>
                        <option value="<?=date("Y")+1?>" <?=(($viewData['year']==date("Y")+1)?"selected":"")?>><?=date("Y")+1?></option>
                     </select><br>
                  </div>
               <?php echo createModalBottom('btn_apply', 'success', $LANG['btn_apply']);
            } ?>
            
            <!-- Modal: Diagram -->
            <?=createModalTop('modalDiagram', $LANG['stats_modalDiagramTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_color']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_color_comment']?></span>
                  <select id="sel_color" class="form-control" name="sel_color" tabindex="<?=$tabindex++?>">
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
                  <br>
               </div>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
         </form>

         <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
            <?php 
            $pageHelp = '';
            if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fa fa-question-circle fa-lg fa-menu"></i></a>';
            ?>
            <div class="panel-heading">
               <i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['stats_title_remainder']?>&nbsp;(<?=$viewData['year']?>)&nbsp;<span class="label label-default pull-right"><i data-position="tooltip-bottom" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['stats_total']?>"><?=$viewData['total']?></i></span><?=$pageHelp?>
            </div>
            <div class="panel-body">
               <p><?=$LANG['stats_remainder_desc']?></p>
               <canvas id="myChart" height="<?=$viewData['height']?>"></canvas>
               
               <script>
                  //
                  // Chart.js Bar Chart
                  //
                  var color = Chart.helpers.color;
                  var horizontalBarChartData = {
                     labels: [<?=$viewData['labels']?>],
                     datasets: [
                     {
                        label: '<?=$LANG['allowance']?>',
                        backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                        borderColor: window.chartColors.green,
                        borderWidth: 1,
                        data: [<?=$viewData['dataAllowance']?>]
                     },
                     {
                         label: '<?=$LANG['remainder']?>',
                         backgroundColor: color(window.chartColors.<?=$viewData['color']?>).alpha(0.5).rgbString(),
                         borderColor: window.chartColors.<?=$viewData['color']?>,
                         borderWidth: 1,
                         data: [<?=$viewData['dataRemainder']?>]
                     }]
                  };

                  window.onload = function() {
                     var ctx = document.getElementById("myChart").getContext("2d");
                     window.myHorizontalBar = new Chart(ctx, {
                        type: 'horizontalBar',
                        data: horizontalBarChartData,
                        options: {
                           elements: { rectangle: { borderWidth: 2, } },
                           responsive: true,
                           legend: { display: false },
                           title: { display: false }
                        }
                    });
                  };
               </script>
               
            </div>
         </div>
         
      </div>

