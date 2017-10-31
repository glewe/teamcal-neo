<?php
/**
 * calendarviewmonthheader.php
 * 
 * Calendar view month header rows
 *
 * @category TeamCal Neo 
 * @version 1.8.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>
      <!-- ==================================================================== 
      view.calendarviewmonthheader
      -->

      <!-- Row: Month name and day numbers -->
      <tr>
      <th class="m-monthname"><?=$viewData['dateInfo']['monthname']?> <?=$viewData['dateInfo']['year']?></th>
         <?php for ($i=$daystart; $i<=$dayend; $i++) {
            if ($D->get($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), 'all', $viewData['regionid'], true))
            {
               //
               // This is a global daynote
               //
               $notestart = '<div class="tooltip-'.$D->color.'" style="width: 100%; height: 100%;" data-position="tooltip-top" data-toggle="tooltip" data-title="' . $D->daynote . '">';
               $noteend = '</div>';
               $notestyle = 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;';
            }
            else
            {
               $notestart = '';
               $noteend = '';
               $notestyle = '';
            }
            
            if (isset($viewData['dayStyles'][$i]) AND strlen($viewData['dayStyles'][$i]))
            {
               $dayStyles = ' style="' . $viewData['dayStyles'][$i] . $notestyle . '"';
            }
            else
            {
               $dayStyles = '';
            }
            ?> 
            <th class="m-daynumber text-center"<?=$dayStyles?>><?=$notestart.$i.$noteend?></th>
         <?php } ?>
      </tr>
      
      <!-- Row: Weekdays -->
      <tr>
         <th class="m-label">&nbsp;</th>
         <?php for ($i=$daystart; $i<=$dayend; $i++) { 
            if (isset($viewData['dayStyles'][$i]) AND strlen($viewData['dayStyles'][$i])) $dayStyles = ' style="' . $viewData['dayStyles'][$i] . '"';
            else $dayStyles = '';
            $prop = 'wday'.$i; 
            ?>
            <th class="m-weekday text-center"<?=$dayStyles?>><?=$LANG['weekdayShort'][$M->$prop]?></th>
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
