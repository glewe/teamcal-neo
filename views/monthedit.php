<?php
/**
 * declination.php
 * 
 * The view of the month edit page
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.monthedit
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
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;month=<?=$monthData['year'].$monthData['month']?>&amp;region=<?=$monthData['regionid']?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_month" type="hidden" class="text" value="<?=$monthData['month']?>">
            <input name="hidden_region" type="hidden" class="text" value="<?=$monthData['regionid']?>">

            <div class="page-menu">
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$monthData['regionid']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$monthData['regionid']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$monthData['yearToday'].$monthData['monthToday']?>&amp;region=<?=$monthData['regionid']?>"><?=$LANG['today']?></a>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $monthData['regionname']?></button>
               
               <div class="pull-right">
                  <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_save"><?=$LANG['btn_save']?></button>
                  <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalClearAll"><?=$LANG['btn_clear_all']?></button>
               </div>
            </div>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['monthedit_title'], $monthData['year'], $monthData['month'], $monthData['regionname'])?></div>
            </div>
            
            <?php if (!$monthData['supportMobile']) $mobilecols = array('full'=>$monthData['dateInfo']['daysInMonth']);
            foreach ($mobilecols as $key => $cols) 
            { 
               $days = $monthData['dateInfo']['daysInMonth'];
               $tables = ceil( $days / $cols);
               for ($t=0; $t<$tables; $t++)
               {
                  $daystart = ($t * $cols) + 1;
                  $daysleft = $days - ($cols * $t);
                  if ($daysleft >= $cols) $dayend = $daystart + ($cols - 1);
                  else $dayend = $days;
            ?>
                  <div class="table<?=($monthData['supportMobile'])?$key:'';?>">
                     <table class="table table-bordered month">
                        <thead>
                           <!-- Row: Month name and day numbers -->
                           <tr>
                              <th class="m-monthname"><?=$monthData['dateInfo']['monthname']?> <?=$monthData['dateInfo']['year']?></th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { ?> 
                                 <th class="m-daynumber text-center"<?=$monthData['dayStyles'][$i]?>><?=$i?></th>
                              <?php } ?>
                           </tr>
                           
                           <!-- Row: Weekdays -->
                           <tr>
                              <th class="m-label">&nbsp;</th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 $prop = 'wday'.$i; ?>
                                 <th class="m-weekday text-center"<?=$monthData['dayStyles'][$i]?>><?=$LANG['weekdayShort'][$M->$prop]?></th>
                              <?php } ?>
                           </tr>
                           
                           <?php if ($monthData['showWeekNumbers']) { ?>
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
                           <!-- Rows 4ff: Holidays -->
                           <?php foreach ($monthData['holidays'] as $hol) { ?>
                              <tr>
                                 <td class="m-name"><?=$hol['name']?></td>
                                 <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                    $prop = 'hol'.$i; ?>
                                    <td class="m-day text-center"<?=$monthData['dayStyles'][$i]?>><input name="opt_hol_<?=$i?>" type="radio" value="<?=$hol['id']?>"<?=(($M->$prop==$hol['id'])?' checked':'')?>></td>
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                           
                           <!-- Last Row: Clear radio button -->
                           <tr>
                              <td class="m-label"><?=$LANG['monthedit_clearHoliday']?></td>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { ?> 
                                 <td class="m-label text-center"><input name="opt_hol_<?=$i?>" type="radio" value="0"></td>
                              <?php } ?>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               
               <?php } ?>
            <?php } ?>
            
            <?php 
            if ($monthData['month']==1) 
            {
               $pageBwdYear = $monthData['year'] - 1;
               $pageBwdMonth = 12; 
               $pageFwdYear = $monthData['year']; 
               $pageFwdMonth = sprintf('%02d', $monthData['month'] + 1); 
            }
            elseif ($monthData['month']==12) 
            {
               $pageBwdYear = $monthData['year']; 
               $pageBwdMonth = sprintf('%02d', $monthData['month'] - 1); 
               $pageFwdYear = $monthData['year'] + 1; 
               $pageFwdMonth = '01'; 
            }
            else 
            {
               $pageBwdYear = $monthData['year']; 
               $pageBwdMonth = sprintf('%02d', $monthData['month'] - 1); 
               $pageFwdYear = $monthData['year']; 
               $pageFwdMonth = sprintf('%02d', $monthData['month'] + 1); 
            }
            ?>
            
            <!-- Modal: Clear All -->
            <?=createModalTop('modalClearAll', $LANG['modal_confirm'])?>
               <?=sprintf($LANG['monthedit_confirm_clearall'], $monthData['year'], $monthData['month'], $monthData['regionname'])?>
            <?=createModalBottom('btn_clearall', 'success', $LANG['btn_clear_all'])?>
            
            <!-- Modal: Select Region -->
            <?=createModalTop('modalSelectRegion', $LANG['monthedit_selRegion'])?>
               <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                  <?php foreach($monthData['regions'] as $reg) { ?>
                     <option  value="<?=$reg['id']?>"<?=(($monthData['regionid'] == $reg['id'])?' selected="selected"':'')?>><?=$reg['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_region', 'success', $LANG['btn_select'])?>
            
         </form>
            
      </div>      
