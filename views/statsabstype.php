<?php
/**
 * statsabstype.php
 * 
 * Absence type statistics page view
 *
 * @category TeamCal Neo 
 * @version 0.9.007
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
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
                     <option value="custom" <?=(($viewData['period']=='custom')?"selected":"")?>><?=$LANG['custom']?></option>
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
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['from']?></div>
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
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['to']?></div>
                  <?php } ?>
               </div>
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
            <!-- Modal: Diagram -->
            <?=createModalTop('modalDiagram', $LANG['stats_modalDiagramTitle'])?>
               <div>
                  <span class="text-bold"><?=$LANG['stats_color']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_color_comment']?></span>
                  <select id="sel_color" class="form-control" name="sel_color" tabindex="<?=$tabindex++?>">
                     <option value="blue" <?=(($viewData['color']=='blue')?"selected":"")?>><?=$LANG['blue']?></option>
                     <option value="cyan" <?=(($viewData['color']=='cyan')?"selected":"")?>><?=$LANG['cyan']?></option>
                     <option value="green" <?=(($viewData['color']=='green')?"selected":"")?>><?=$LANG['green']?></option>
                     <option value="magenta" <?=(($viewData['color']=='magenta')?"selected":"")?>><?=$LANG['magenta']?></option>
                     <option value="orange" <?=(($viewData['color']=='orange')?"selected":"")?>><?=$LANG['orange']?></option>
                     <option value="red" <?=(($viewData['color']=='red')?"selected":"")?>><?=$LANG['red']?></option>
                     <option value="custom" <?=(($viewData['color']=='custom')?"selected":"")?>><?=$LANG['custom']?></option>
                  </select><br>
                  <script>
                  $( "#sel_color" ).change(function() 
                  {
                     if ($(this).val() == 'custom')
                     {
                        $('#colorHex').prop('disabled', false);
                     }
                     else
                     {
                        $('#colorHex').prop('disabled', true);
                     }
                  });
                  </script>
                  
                  <span class="text-bold"><?=$LANG['stats_customColor']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_customColor_comment']?></span>
                  <input id="colorHex" class="form-control" tabindex="6" name="txt_colorHex" maxlength="6" value="<?=$viewData['colorHex']?>" type="text" <?=(($viewData['color']=='custom')?"":"disabled")?>>
                  <script type="text/javascript">$(function() { $( "#colorHex" ).ColorPicker({ onSubmit: function(hsb, hex, rgb, el) { $(el).val(hex.toUpperCase()); $(el).ColorPickerHide(); }, onBeforeShow: function () { $(this).ColorPickerSetColor(this.value); } }) .bind('keyup', function(){ $(this).ColorPickerSetColor(this.value); }); });</script>
                  <?php if ( isset($inputAlert["colorHex"]) AND strlen($inputAlert["colorHex"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['colorHex']?></div>
                  <?php } ?>
                  <br>
                  
                  <span class="text-bold"><?=$LANG['stats_scale']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_scale_comment']?></span>
                  <select id="sel_scale" class="form-control" name="sel_scale" tabindex="<?=$tabindex++?>">
                     <option value="auto" <?=(($viewData['scale']=='auto')?"selected":"")?>><?=$LANG['auto']?></option>
                     <option value="smart" <?=(($viewData['scale']=='smart')?"selected":"")?>><?=$LANG['smart']?></option>
                     <option value="custom" <?=(($viewData['scale']=='custom')?"selected":"")?>><?=$LANG['custom']?></option>
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
                  <input id="scaleSmart" class="form-control" tabindex="<?=$tabindex++?>" name="txt_scaleSmart" type="text" maxlength="3" value="<?=$viewData['scaleSmart']?>" <?=(($viewData['scale']=='smart')?"":"disabled")?>>
                  <?php if ( isset($inputAlert["scaleSmart"]) AND strlen($inputAlert["scaleSmart"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['scaleSmart']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               
               <div>
                  <span class="text-bold"><?=$LANG['stats_scale_max']?></span><br>
                  <span class="text-normal"><?=$LANG['stats_scale_max_comment']?></span>
                  <input id="scaleMax" class="form-control" tabindex="<?=$tabindex++?>" name="txt_scaleMax" type="text" maxlength="3" value="<?=$viewData['scaleMax']?>" <?=(($viewData['scale']=='custom')?"":"disabled")?>>
                  <?php if ( isset($inputAlert["scaleMax"]) AND strlen($inputAlert["scaleMax"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['scaleMax']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               
            <?=createModalBottom('btn_apply', 'success', $LANG['btn_apply'])?>
            
         </form>

         <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
            <div class="panel-heading">
               <i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['stats_title_abstype']?>&nbsp;(<?=$viewData['periodName']?>)&nbsp;<span class="label label-default pull-right"><i data-position="tooltip-bottom" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['stats_total']?>"><?=$viewData['total']?></i></span>
            </div>
            <div class="panel-body">
               <p><?=$LANG['stats_abstype_desc']?></p>
               <canvas id="myChart" style="padding-right: 40px;"></canvas>
               <script>
                  var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
      
                  var barChartData = {
                     labels : [<?=$viewData['labels']?>],
                     datasets : [
                        {
                           <?=$viewData['chartjsColor']?>
                           data : [<?=$viewData['data']?>]
                        }
                     ]
                  }
                  
                  window.onload = function(){
                     var ctx = document.getElementById("myChart").getContext("2d");
                     window.myBar = new Chart(ctx).HorizontalBar(barChartData, 
                        {
                           responsive: true,
                           <?=$viewData['chartjsScaleSettings']?>
                        }
                     );
                  }
               </script>
            </div>
         </div>
         
      </div>

