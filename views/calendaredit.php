<?php
/**
 * calendaredit.php
 * 
 * The view of the calendar edit page
 *
 * @category TeamCal Neo 
 * @version 0.3.003
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.editcal
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
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;month=<?=$calData['year'].$calData['month']?>&amp;region=<?=$calData['regionid']?>&amp;user=<?=$calData['username']?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_month" type="hidden" class="text" value="<?=$calData['month']?>">
            <input name="hidden_region" type="hidden" class="text" value="<?=$calData['regionid']?>">

            <?php 
            if ($calData['month']==1) 
            {
               $pageBwdYear = $calData['year'] - 1;
               $pageBwdMonth = '12'; 
               $pageFwdYear = $calData['year']; 
               $pageFwdMonth = sprintf('%02d', $calData['month'] + 1); 
            }
            elseif ($calData['month']==12) 
            {
               $pageBwdYear = $calData['year']; 
               $pageBwdMonth = sprintf('%02d', $calData['month'] - 1); 
               $pageFwdYear = $calData['year'] + 1; 
               $pageFwdMonth = '01'; 
            }
            else 
            {
               $pageBwdYear = $calData['year']; 
               $pageFwdYear = $calData['year']; 
               $pageBwdMonth = sprintf('%02d', $calData['month'] - 1); 
               $pageFwdMonth = sprintf('%02d', $calData['month'] + 1); 
            }
            ?>
                        
            <div class="page-menu">
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$calData['regionid']?>&amp;user=<?=$calData['username']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$calData['regionid']?>&amp;user=<?=$calData['username']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$calData['yearToday'].$calData['monthToday']?>&amp;region=<?=$calData['regionid']?>&amp;user=<?=$calData['username']?>"><?=$LANG['today']?></a>
               <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalPeriod"><?=$LANG['caledit_Period']?></button>
               <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalRecurring"><?=$LANG['caledit_Recurring']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $calData['regionname']?></button>
               <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectUser"><?=$LANG['user'] . ': ' . $calData['fullname']?></button>
               
               <div class="pull-right">
                  <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                  <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalClearAll"><?=$LANG['btn_clear_all']?></button>
               </div>
            </div>

            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['caledit_title'], $calData['year'], $calData['month'], $calData['fullname'])?></div>
            </div>
            
            <?php if (!$calData['supportMobile']) $mobilecols = array('full'=>$calData['dateInfo']['daysInMonth']);
            foreach ($mobilecols as $key => $cols) 
                        { 
               $days = $calData['dateInfo']['daysInMonth'];
               $tables = ceil( $days / $cols);
               for ($t=0; $t<$tables; $t++)
               {
                  $daystart = ($t * $cols) + 1;
                  $daysleft = $days - ($cols * $t);
                  if ($daysleft >= $cols) $dayend = $daystart + ($cols - 1);
                  else $dayend = $days;
            ?>
                  <div class="table<?=($calData['supportMobile'])?$key:'';?>">
                     <table class="table table-bordered month">
                        <thead>
                           <!-- Row: Month name and day numbers -->
                           <tr>
                              <th class="m-monthname"><?=$calData['dateInfo']['monthname']?> <?=$calData['dateInfo']['year']?></th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { ?> 
                                 <th class="m-daynumber text-center"<?=$calData['dayStyles'][$i]?>><?=$i?></th>
                              <?php } ?>
                           </tr>
                           
                           <!-- Row: Weekdays -->
                           <tr>
                              <th class="m-label">&nbsp;</th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 $prop = 'wday'.$i; ?>
                                 <th class="m-weekday text-center"<?=$calData['dayStyles'][$i]?>><?=$LANG['weekdayShort'][$M->$prop]?></th>
                              <?php } ?>
                           </tr>
                           
                           <?php if ($calData['showWeekNumbers']) { ?>
                              <!-- Row: Week numbers -->
                              <tr>
                                 <th class="m-label"><?=$LANG['weeknumber']?></th>
                                 <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                    $prop = 'week'.$i; 
                                    $wprop = 'wday'.$i; ?>
                                    <th class="m-weeknumber text-center<?=(($M->$wprop==1)?' first':' inner')?>"><?=(($M->$wprop==1)?$M->$prop:'')?></th>
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
                                 $style = $calData['dayStyles'][$i];
                                 $icon = '';
                                 if ($abs = $T->getAbsence($calData['username'], $calData['year'], $calData['month'], $i)) 
                                 {
                                    /**
                                     * This is an absence. Get the coloring info.
                                     */
                                    $style = ' style="color: #' . $A->getColor($abs) . '; background-color: #' . $A->getBgColor($abs) . ';';
                                    $icon = '<span class="fa fa-'.$A->getIcon($abs).'"></span>';
                                    $loopDate = date('Y-m-d', mktime(0, 0, 0, $calData['month'], $i, $calData['year']));
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
                           <?php foreach ($calData['absences'] as $abs) { ?>
                              <tr>
                                 <td class="m-name"><?=$abs['name']?></td>
                                 <?php 
                                 for ($i=$daystart; $i<=$dayend; $i++) { 
                                    $prop = 'abs'.$i; 
                                    ?>
                                    <td class="m-day text-center"<?=$calData['dayStyles'][$i]?>><input name="opt_abs_<?=$i?>" type="radio" value="<?=$abs['id']?>"<?=(($T->$prop==$abs['id'])?' checked':'')?>></td>
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                           
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
            
            <!-- Modal: Clear All -->
            <?=createModalTop('modalClearAll', $LANG['modal_confirm'])?>
               <?=sprintf($LANG['caledit_confirm_clearall'], $calData['year'], $calData['month'], $calData['fullname'])?>
            <?=createModalBottom('btn_clearall', 'success', $LANG['btn_clear_all'])?>
            
            <!-- Modal: Select Region -->
            <?=createModalTop('modalSelectRegion', $LANG['cal_selRegion'])?>
               <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                  <?php foreach($calData['regions'] as $reg) { ?>
                     <option value="<?=$reg['id']?>" <?=(($calData['regionid'] == $reg['id'])?'selected="selected"':'')?>><?=$reg['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_region', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select User -->
            <?=createModalTop('modalSelectUser', $LANG['caledit_selUser'])?>
               <select id="user" class="form-control" name="sel_user" tabindex="<?=$tabindex++?>">
                  <?php foreach($calData['users'] as $usr) { ?>
                     <option  value="<?=$usr['username']?>"<?=(($calData['username'] == $usr['username'])?' selected="selected"':'')?>><?=$usr['lastfirst']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_user', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Period -->
            <?=createModalTop('modalPeriod', $LANG['caledit_PeriodTitle'])?>
               <div>
                  <div style="width: 60%; float: left; margin-bottom: 20px;">
                     <span class="text-bold"><?=$LANG['caledit_absenceType']?></span><br>
                     <span class="text-normal"><?=$LANG['caledit_absenceType_comment']?></span>
                  </div>
                  <div style="width: 40%; margin-bottom: 20px;">
                     <select id="user" class="form-control" name="sel_periodAbsence" tabindex="<?=$tabindex++?>">
                        <?php foreach($calData['absences'] as $abs) { ?>
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
                        <?php foreach($calData['absences'] as $abs) { ?>
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
               <div>
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
    