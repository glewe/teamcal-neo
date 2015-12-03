<?php
/**
 * log.php
 * 
 * The view of the log page
 *
 * @category TeamCal Neo 
 * @version 0.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.log
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
            
            <form  class="bs-example form-control-horizontal" action="index.php?action=log&amp;sort=<?=$logData['sort']?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['mnu_admin_systemlog'] . ' ( ' . count($logData['events']) . ' ' . $LANG['log_title_events'] . ' )'?></div>
                  <div class="panel-body">
                  
                     <div class="panel panel-default">
                        <div class="panel-body">
                        
                           <div class="col-lg-3">
                              <label><?=$LANG['period']?></label>
                              <select name="sel_logPeriod" id="sel_logPeriod" class="form-control" tabindex="<?=$tabindex++;?>">
                                 <option class="option" value="curr_all" <?=(($logData['logperiod']=="curr_all")?'selected':'')?>><?=$LANG['all']?></option>
                                 <option class="option" value="curr_month" <?=(($logData['logperiod']=="curr_month")?'selected':'')?>><?=$LANG['period_month']?></option>
                                 <option class="option" value="curr_quarter" <?=(($logData['logperiod']=="curr_quarter")?'selected':'')?>><?=$LANG['period_quarter']?></option>
                                 <option class="option" value="curr_half" <?=(($logData['logperiod']=="curr_half")?'selected':'')?>><?=$LANG['period_half']?></option>
                                 <option class="option" value="curr_year" <?=(($logData['logperiod']=="curr_year")?'selected':'')?>><?=$LANG['period_year']?></option>
                                 <option class="option" value="custom" <?=(($logData['logperiod']=="custom")?'selected':'')?>><?=$LANG['period_custom']?></option>
                              </select>
                           </div>
                        
                           <div class="col-lg-2">
                              <label><?=$LANG['from']?></label>
                              <input id="logPeriodFrom" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_logPeriodFrom" maxlength="10" value="<?=$logData['logfrom']?>" type="text" <?=($logData['logPeriod']!='custom')?'disabled="disabled"':''?>>
                              <script type="text/javascript">$(function() { $( "#logPeriodFrom" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                           </div>
                  
                           <div class="col-lg-2">
                              <label><?=$LANG['to']?></label>
                              <input id="logPeriodTo" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_logPeriodTo" maxlength="10" value="<?=$logData['logto']?>" type="text" <?=($logData['logPeriod']!='custom')?'disabled="disabled"':''?>>
                              <script type="text/javascript">$(function() { $( "#logPeriodTo" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                           </div>
                        
                           <div class="col-lg-5">
                              <br>
                              <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_refresh"><?=$LANG['btn_refresh']?></button>
                              <button type="submit" class="btn btn-default" tabindex="<?=$tabindex++;?>" name="btn_reset"><?=$LANG['btn_reset']?></button>
                              <button class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalClear"><?=$LANG['log_clear']?></button>
                              <button type="submit" class="btn btn-info" title="<?=($logData['sort']=='DESC')?$LANG['log_sort_asc']:$LANG['log_sort_desc']?>" tabindex="<?=$tabindex++;?>" name="btn_sort"><?=($logData['sort']=='DESC')?'<i class="fa fa-arrow-up fa-lg"></i>':'<i class="fa fa-arrow-down fa-lg"></i>'?></button>
                              
                              <!-- Modal: Clear -->
                              <?=createModalTop('modalClear', $LANG['modal_confirm'])?>
                                 <?=$LANG['log_clear_confirm']?>
                              <?=createModalBottom('btn_clear', 'danger', $LANG['log_clear'])?>
                              
                           </div>
                           
                        </div>
                     </div>
                  
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tabLog" data-toggle="tab"><?=$LANG['log_title']?></a></li>
                        <li><a href="#tabSettings" data-toggle="tab"><?=$LANG['log_settings']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Log tab -->
                        <div class="tab-pane fade active in" id="tabLog">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php
                                 if ( count($logData['events']) ) {
                                    $color = 'text-default';
                                    $startEvent = ($logData['currentPage'] * $logData['eventsPerPage']) - $logData['eventsPerPage'];
                                    $endEvent = $startEvent + $logData['eventsPerPage'];
                                    for ($i=$startEvent; $i<$endEvent; $i++) { 
                                       if (isset($logData['events'][$i])) $event = $logData['events'][$i]; else break;
                                       if (in_array($event['type'], $logData['class']['admin']))
                                          $color = 'text-danger';
                                       else if (in_array($event['type'], $logData['class']['user']))
                                          $color = 'text-success';
                                       else if (in_array($event['type'], $logData['class']['app']))
                                          $color = 'text-primary';
                                       ?>
                                       <div class="col-lg-12 <?=$color?>" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                          <div class="col-lg-1"><?=$i+1?></div>
                                          <div class="col-lg-3"><i class="fa fa-clock-o fa-lg fa-menu" title="<?=$LANG['log_header_when']?>"></i><?=$event['timestamp']?></div>
                                          <div class="col-lg-2"><i class="fa fa-tag fa-lg fa-menu" title="<?=$LANG['log_header_type']?>"></i><?=substr($event['type'],3)?></div>
                                          <div class="col-lg-2"><i class="fa fa-user fa-lg fa-menu" title="<?=$LANG['log_header_user']?>"></i></span><?=$event['user']?></div>
                                          <div class="col-lg-4"><i class="fa fa-pencil fa-lg fa-menu" title="<?=$LANG['log_header_event']?>"></i><?=$event['event']?></div>
                                       </div>
                                    <?php } 
                                 } ?>
                                 
                                 <ul id="paginator"></ul>
                                 <script type="text/javascript">
                                    var bpOptions = {
                                       bootstrapMajorVersion: 3,
                                       currentPage: <?=$logData['currentPage']?>,
                                       numberOfPages: 5,
                                       totalPages: <?=$logData['numPages']?>,
                                       pageUrl: function(type, page, current){return "index.php?action=log&page="+page;},
                                       itemTexts: function (type, page, current) {
                                          switch (type) {
                                          case "first":
                                              return "&laquo;";
                                          case "prev":
                                              return "&lsaquo;";
                                          case "next":
                                              return "&rsaquo;";
                                          case "last":
                                              return "&raquo;";
                                          case "page":
                                              return page;
                                          }
                                       },
                                    };
                                    $('#paginator').bootstrapPaginator(bpOptions);
                                 </script>                                 
                                 
                              </div>
                           </div>
                        </div>
                        
                        <!-- Log settings -->
                        <div class="tab-pane fade" id="tabSettings">
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <?php 
                                 foreach( $logData['types'] as $type ) { 
                                    if (in_array('log'.$type, $logData['class']['admin']))
                                       $color = ' text-danger';
                                    else if (in_array('log'.$type, $logData['class']['user']))
                                       $color = ' text-success';
                                    else if (in_array('log'.$type, $logData['class']['app']))
                                       $color = ' text-primary';
                                 ?>
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; padding-top: 10px; padding-bottom: 10px;">
                                    <div class="col-lg-4 <?=$color?>"><label><i class="fa fa-tag fa-lg fa-menu"></i><?=$type?></label></div>
                                    <div class="col-lg-4">
                                       <input style="margin-right: 10px;" name="chk_log<?=$type?>" value="chk_log<?=$type?>" type="checkbox"<?=($C->read("log".$type))?' checked=""':''?>><?=$LANG['log_settings_log']?>
                                    </div>
                                    <div class="col-lg-4">
                                       <input style="margin-right: 10px;" name="chk_logfilter<?=$type?>" value="chk_logfilter<?=$type?>" type="checkbox"<?=($C->read("logfilter".$type))?' checked=""':''?>><?=$LANG['log_settings_show']?>
                                    </div>
                                 </div>
                                 <?php } ?>
                              </div>
                           </div>
                           
                           <div class="panel panel-default">
                              <div class="panel-body">
                                 <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_logSave"><?=$LANG['btn_save']?></button>
                              </div>
                           </div>
                        </div>
                        
                     </div>
                  
                  </div>
               </div>
               
            </form>
         </div>
      </div>
      
      <script type="text/javascript">
      $('#sel_logPeriod').change(function() {
         if (this.value == "custom") {
            $("#logPeriodFrom").prop('disabled', false);
            $("#logPeriodTo").prop('disabled', false);
         }
         else
         {
            $("#logPeriodFrom").prop('disabled', true);
            $("#logPeriodTo").prop('disabled', true);
         }
      });
      </script>
      