<?php
/**
 * groupcalendaredit.php
 * 
 * Group calendar edit page view
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.groupcalendaredit
      -->
      <div class="container content" style="padding-left: 4px; padding-right: 4px;">
      
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
          
         <?php $tabindex = 1; $colsleft = 1; $colsright = 4;?>
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;month=<?=$viewData['year'].$viewData['month']?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_month" type="hidden" class="text" value="<?=$viewData['month']?>">
            <input name="hidden_region" type="hidden" class="text" value="<?=$viewData['regionid']?>">

            <?php 
            if ($viewData['month']==1) 
            {
               $pageBwdYear = $viewData['year'] - 1;
               $pageBwdMonth = '12'; 
               $pageFwdYear = $viewData['year']; 
               $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1); 
            }
            elseif ($viewData['month']==12) 
            {
               $pageBwdYear = $viewData['year']; 
               $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1); 
               $pageFwdYear = $viewData['year'] + 1; 
               $pageFwdMonth = '01'; 
            }
            else 
            {
               $pageBwdYear = $viewData['year']; 
               $pageFwdYear = $viewData['year']; 
               $pageBwdMonth = sprintf('%02d', $viewData['month'] - 1); 
               $pageFwdMonth = sprintf('%02d', $viewData['month'] + 1); 
            }
            ?>
                        
            <div class="page-menu">
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$viewData['yearToday'].$viewData['monthToday']?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>"><?=$LANG['today']?></a>
               <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalPeriod"><?=$LANG['caledit_Period']?></button>
               <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalRecurring"><?=$LANG['caledit_Recurring']?></button>
               <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $viewData['regionname']?></button>
               <button type="button" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $viewData['groupname']?></button>
               <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSave"><?=$LANG['btn_save']?></button>
               <button type="button" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalClearAll"><?=$LANG['btn_clear_all']?></button>
               <?php if ($viewData['supportMobile']) { ?> 
                  <button type="button" class="btn btn-default" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectWidth"><?=$LANG['screen'] . ': ' . $viewData['width']?></button>
               <?php } ?>
            </div>

            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['caledit_title'], $viewData['year'], $viewData['month'], $LANG['group'] . ': ' . $viewData['groupname'])?></div>
            </div>
            
            <?php if (!$viewData['supportMobile']) 
            {
               $mobilecols = array('full'=>$viewData['dateInfo']['daysInMonth']);
            }
            else 
            {
               switch ($viewData['width'])
               {
                  case '1024plus':
                     $mobilecols = array('full'=>$viewData['dateInfo']['daysInMonth']);
                     break;
                      
                  case '1024':
                     $mobilecols = array('1024'=>25);
                     break;
                     
                  case '800':
                     $mobilecols = array('800'=>17);
                     break;
                         
                  case '640':
                     $mobilecols = array('640'=>14);
                     break;
                         
                  case '480':
                     $mobilecols = array('480'=>9);
                     break;

                  case '400':
                     $mobilecols = array('400'=>7);
                     break;

                  case '320':
                     $mobilecols = array('320'=>5);
                     break;

                  case '240':
                     $mobilecols = array('240'=>3);
                     break;

                  default:
                     $mobilecols = array('full'=>$viewData['dateInfo']['daysInMonth']);
                     break;
               }
            }
            
            foreach ($mobilecols as $key => $cols) 
                        { 
               $days = $viewData['dateInfo']['daysInMonth'];
               $tables = ceil( $days / $cols);
               for ($t=0; $t<$tables; $t++)
               {
                  $daystart = ($t * $cols) + 1;
                  $daysleft = $days - ($cols * $t);
                  if ($daysleft >= $cols) $dayend = $daystart + ($cols - 1);
                  else $dayend = $days;
            ?>
                  <div class="table<?=($viewData['supportMobile'])?$key:'';?>">
                     <table class="table table-bordered month">
                        <thead>
                           <!-- Row: Month name and day numbers -->
                           <tr>
                              <th class="m-monthname"><?=$viewData['dateInfo']['monthname']?> <?=$viewData['dateInfo']['year']?></th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { ?> 
                                 <th class="m-daynumber text-center"<?=$viewData['dayStyles'][$i]?>><?=$i?></th>
                              <?php } ?>
                           </tr>
                           
                           <!-- Row: Weekdays -->
                           <tr>
                              <th class="m-label">&nbsp;</th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 $prop = 'wday'.$i; ?>
                                 <th class="m-weekday text-center"<?=$viewData['dayStyles'][$i]?>><?=$LANG['weekdayShort'][$M->$prop]?></th>
                              <?php } ?>
                           </tr>
                           
                           <?php if ($viewData['showWeekNumbers']) { ?>
                              <!-- Row: Week numbers -->
                              <tr>
                                 <th class="m-label"><?=$LANG['weeknumber']?></th>
                                 <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                    $prop = 'week'.$i; 
                                    $wprop = 'wday'.$i; ?>
                                    <th class="m-weeknumber text-center<?=(($M->$wprop==$viewData['firstDayOfWeek'])?' first':' inner')?>"><?=(($M->$wprop==$viewData['firstDayOfWeek'])?$M->$prop:'')?></th>
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                           
                        </thead>
                        <tbody>
                           
                           <!-- Rows: Current absence -->
                           <tr>
                              <td class="m-label"><?=$LANG['caledit_currentAbsence']?></td>
                              <?php 
                              for ($i=$daystart; $i<=$dayend; $i++) { 
                                 $style = $viewData['dayStyles'][$i];
                                 $icon = '';
                                 if ($abs = $T->getAbsence($viewData['groupusername'], $viewData['year'], $viewData['month'], $i)) 
                                 {
                                    /**
                                     * This is an absence. Get the coloring info.
                                     */
                                    $style = ' style="color: #' . $A->getColor($abs) . '; background-color: #' . $A->getBgColor($abs) . ';';
                                    if ($C->read('symbolAsIcon'))
                                    {
                                       $icon = $A->getSymbol($abs);
                                    }
                                    else
                                    {
                                       $icon = '<span class="fa fa-'.$A->getIcon($abs).'"></span>';
                                    }
                                    $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
                                    if ( $loopDate == $currDate )
                                    {
                                       $style .= 'border-left: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';border-right: ' . $C->read("todayBorderSize") . 'px solid #' . $C->read("todayBorderColor") . ';';
                                    }
                                    $style .= '"';
                                 }
                                 ?>
                                 <td class="m-day text-center"<?=$style?>><?=$icon?></td>
                              <?php } ?>
                           </tr>
                           
                           <!-- Rows ff: Absences -->
                           <?php foreach ($viewData['absences'] as $abs) { 
                              if( ($abs['manager_only'] AND ($UG->isGroupManagerOfGroup($UL->username, $viewData['groupid']) OR $UL->username=='admin')) OR !$abs['manager_only']) { ?>
                              <tr>
                                 <td class="m-name"><?=$abs['name']?></td>
                                 <?php 
                                 for ($i=$daystart; $i<=$dayend; $i++) { 
                                    $prop = 'abs'.$i; 
                                    ?>
                                    <td class="m-day text-center"<?=$viewData['dayStyles'][$i]?>><input name="opt_abs_<?=$i?>" type="radio" value="<?=$abs['id']?>"<?=(($T->$prop==$abs['id'])?' checked':'')?>></td>
                                 <?php } ?>
                              </tr>
                           <?php } 
                           } ?>
                           
                           <!-- Last Row: Clear radio button -->
                           <tr>
                              <td class="m-label"><?=$LANG['caledit_clearAbsence']?></td>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { ?> 
                                 <td class="m-label text-center"><input name="opt_abs_<?=$i?>" type="radio" value="0"></td>
                              <?php } ?>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               
               <?php } ?>
            <?php } ?>
            
            <!-- Modal: Save -->
            <?=createModalTop('modalSave', $LANG['modal_confirm'])?>
               <?=sprintf($LANG['caledit_confirm_savegroup'], $viewData['year'], $viewData['month'], $viewData['groupname'])?>
               <div class="checkbox">
                  <label><input type="checkbox" name="chk_keepExisting" tabindex="<?=$tabindex++?>" checked><?=$LANG['caledit_keepExisting']?></label>
               </div>
            <?=createModalBottom('btn_save', 'primary', $LANG['btn_save'])?>
            
            <!-- Modal: Clear All -->
            <?=createModalTop('modalClearAll', $LANG['modal_confirm'])?>
               <?=sprintf($LANG['caledit_confirm_clearall'], $viewData['year'], $viewData['month'], $LANG['group'] . ': ' . $viewData['groupname'])?>
            <?=createModalBottom('btn_clearall', 'success', $LANG['btn_clear_all'])?>
            
            <!-- Modal: Select Region -->
            <?=createModalTop('modalSelectRegion', $LANG['cal_selRegion'])?>
               <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                  <?php foreach($viewData['regions'] as $reg) { ?>
                     <option value="<?=$reg['id']?>" <?=(($viewData['regionid'] == $reg['id'])?'selected="selected"':'')?>><?=$reg['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_region', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Screen Width -->
            <?=createModalTop('modalSelectWidth', $LANG['cal_selWidth'])?>
               <p><?=$LANG['cal_selWidth_comment']?></p>
               <select id="width" class="form-control" name="sel_width" tabindex="<?=$tabindex++?>">
                  <?php foreach($LANG['widths'] as $key => $value) { ?>
                     <option value="<?=$key?>"<?=(($viewData['width'] == $key)?' selected="selected"':'')?>><?=$value?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_width', 'warning', $LANG['btn_select'])?>

            <!-- Modal: Select Group -->
            <?=createModalTop('modalSelectGroup', $LANG['caledit_selGroup'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <?php foreach($viewData['groups'] as $group) { ?>
                     <option  value="<?=$group['id']?>"<?=(($viewData['groupid'] == $group['id'])?' selected="selected"':'')?>><?=$group['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_group', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Period -->
            <?=createModalTop('modalPeriod', $LANG['caledit_PeriodTitle'])?>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['caledit_absenceType']?></span><br>
                     <span class="text-normal"><?=$LANG['caledit_absenceType_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <select id="user" class="form-control" name="sel_periodAbsence" tabindex="<?=$tabindex++?>">
                        <?php foreach($viewData['absences'] as $abs) { ?>
                           <option  value="<?=$abs['id']?>"><?=$abs['name']?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div>&nbsp;</div>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['caledit_startDate']?></span><br>
                     <span class="text-normal"><?=$LANG['caledit_startDate_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <input id="periodStart" class="form-control" tabindex="<?=$tabindex++?>" name="txt_periodStart" type="text" maxlength="10" value="">
                     <script type="text/javascript">$(function() { $( "#periodStart" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                  </div>
                  <?php if ( isset($inputAlert["periodStart"]) AND strlen($inputAlert["periodStart"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['periodStart']?></div>
                  <?php } ?>
               </div>
               <div>&nbsp;</div>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['caledit_endDate']?></span><br>
                     <span class="text-normal"><?=$LANG['caledit_endDate_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <input id="periodEnd" class="form-control" tabindex="<?=$tabindex++?>" name="txt_periodEnd" type="text" maxlength="10" value="">
                     <script type="text/javascript">$(function() { $( "#periodEnd" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                  </div>
                  <?php if ( isset($inputAlert["periodEnd"]) AND strlen($inputAlert["periodEnd"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['periodEnd']?></div>
                  <?php } ?>
               </div>
            <?=createModalBottom('btn_saveperiod', 'success', $LANG['btn_save'])?>

            <!-- Modal: Recurring -->
            <?=createModalTop('modalRecurring', $LANG['caledit_RecurringTitle'])?>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px; padding-right: 10px;">
                     <span class="text-bold"><?=$LANG['caledit_absenceType']?></span><br>
                     <span class="text-normal"><?=$LANG['caledit_absenceType_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <select id="user" class="form-control" name="sel_recurringAbsence" tabindex="<?=$tabindex++?>">
                        <?php foreach($viewData['absences'] as $abs) { ?>
                           <option  value="<?=$abs['id']?>"><?=$abs['name']?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div>&nbsp;</div>
               <div style="clear: left;">
                  <span class="text-bold"><?=$LANG['caledit_recurrence']?></span><br>
                  <span class="text-normal"><?=$LANG['caledit_recurrence_comment']?></span>
               </div>
               <div style="padding-left: 20px;">
                  <div style="width: 50%; float: left; padding-right: 10px;">
                     <div class="checkbox"><input id="monday" name="monday" value="monday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][1]?></div>
                     <div class="checkbox"><input id="tuesday" name="tuesday" value="tuesday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][2]?></div>
                     <div class="checkbox"><input id="wedensday" name="wednesday" value="wednesday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][3]?></div>
                     <div class="checkbox"><input id="thursday" name="thursday" value="thursday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][4]?></div>
                     <div class="checkbox"><input id="friday" name="friday" value="friday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][5]?></div>
                  </div>
                  <div style="width: 50%; float: right;">
                     <div class="checkbox"><input id="saturday" name="saturday" value="saturday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][6]?></div>
                     <div class="checkbox"><input id="sunday" name="sunday" value="sunday" tabindex="<?=$tabindex++?>" type="checkbox"><?=$LANG['weekdayLong'][7]?></div>
                     <div class="checkbox"><input id="workdays" name="workdays" value="workdays" tabindex="<?=$tabindex++?>" type="checkbox">Mon-Fri</div>
                     <div class="checkbox"><input id="weekends" name="weekends" value="weekends" tabindex="<?=$tabindex++?>" type="checkbox">Sat-Sun</div>
                  </div>
               </div>
               <div>&nbsp;</div>
            <?=createModalBottom('btn_saverecurring', 'success', $LANG['btn_save'])?>
                     
         </form>
            
      </div>      
