<?php
/**
 * year.php
 * 
 * The view of the year calendar page
 *
 * @category TeamCal Neo 
 * @version 0.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.year
      -->
      <div class="content container" style="padding-left: 4px; padding-right: 4px;">
      
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
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;year=<?=$yearData['year']?>&amp;region=<?=$yearData['regionid']?>&amp;user=<?=$yearData['username']?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_user" type="hidden" class="text" value="<?=$yearData['user']?>">
            <input name="hidden_year" type="hidden" class="text" value="<?=$yearData['year']?>">
            <input name="hidden_region" type="hidden" class="text" value="<?=$yearData['regionid']?>">

            <div class="page-menu">
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;year=<?=($yearData['year']-1)?>&amp;region=<?=$yearData['regionid']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;year=<?=($yearData['year']+1)?>&amp;region=<?=$yearData['regionid']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;year=<?=date('Y')?>&amp;region=<?=$yearData['regionid']?>"><?=$LANG['today']?></a>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $yearData['regionname']?></button>
               <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectUser"><?=$LANG['user'] . ': ' . $yearData['fullname']?></button>
            </div>
               
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['year_title'], $yearData['year'], $yearData['fullname'], $yearData['regionname'])?></div>
               <div class="panel-body" id="mobile">
                  <p><button type="button" class="btn btn-primary" name="btn_showmobile" onclick="javascript: $('#mobile').hide(); $('#fullscreen').show();"><?=$LANG['btn_showcalendar']?></button></p>
                  <?=$LANG['year_showyearmobile']?>
               </div>
            </div>
            
            <div id="fullscreen">
            
               <table class="table table-bordered year">
                  <thead>
                     <!-- Row: Month name and day numbers -->
                     <tr>
                        <th class="y-year"><?=$yearData['year']?></th>
                        <?php for ($i=1; $i<=37; $i++) {
                           $style = '';
                           if ( ($wday = $i%7) == 0 ) $wday = 7;
                           if ($wday == 6) $style = $yearData['satStyle']; 
                           if ($wday == 7) $style = $yearData['sunStyle']; 
                           ?>
                           <th class="y-weekday text-center"<?=$style?>><?=$LANG['weekdayShort'][$wday]?></th>
                        <?php } ?>
                     </tr>
                  </thead>
                  
                  <tbody>
                  <?php 
                  /**
                   * Loop through all months
                   */
                  for ($m=1; $m<=12; $m++) { ?>
                     <tr>
                        <td class="y-label"><?=$LANG['monthnames'][$m]?></td>
                        <?php
                        /**
                         * Loop through the 37 columns of the table template
                         */
                        for ($i=1; $i<=37; $i++) 
                        {
                           if ( $i < $yearData['month'][$m][1]['wday'] ) 
                           {
                              /**
                               * Not the first day of the month yet. Gray it out.
                               */
                              echo "<td class=\"y-grayday\"></td>\n";
                           }
                           else if ( $i == $yearData['month'][$m][1]['wday'] )
                           { 
                              /**
                               * Here we go. Do the month days.
                               */
                              for ($d=1; $d<=$yearData['monthInfo'][$m]['daysInMonth']; $d++)
                              {
                                 if ( $yearData['month'][$m][$d]['wday'] == 1 ) $wn = date('W', mktime(0, 0, 0, $m, $d, $yearData['year'])); else $wn = '&nbsp;';
                                 echo "
                                 <td class=\"y-day text-center\"" . $yearData['month'][$m][$d]['style'] . ">
                                    <div class=\"daynumber\">" . $d . "</div>
                                    <div class=\"absence\"" . $yearData['month'][$m][$d]['absstyle'] . "><span class=\"fa fa-" . $yearData['month'][$m][$d]['icon'] . "\"></span></div>
                                    <div class=\"weeknumber text-info\">" . $wn . "</div>
                                 </td>\n";
                                 $i++; 
                              }
                              $i--;                        
                           }
                           else 
                           {
                              /**
                               * Past the last month day. Gray it out.
                               */
                              echo "<td class=\"y-grayday\"></td>\n";
                           }
                        } ?>
                     </tr>
                  <?php } ?>   
                  </tbody>
                  
               </table>
                  
               <!-- Modal: Select Region -->
               <?=createModalTop('modalSelectRegion', $LANG['year_selRegion'])?>
                  <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                     <?php foreach($yearData['regions'] as $reg) { ?>
                        <option  value="<?=$reg['id']?>"<?=(($yearData['regionid'] == $reg['id'])?' selected="selected"':'')?>><?=$reg['name']?></option>
                     <?php } ?>
                  </select>
               <?=createModalBottom('btn_region', 'warning', $LANG['btn_select'])?>
            
               <!-- Modal: Select User -->
               <?=createModalTop('modalSelectUser', $LANG['year_selUser'])?>
                  <select id="user" class="form-control" name="sel_user" tabindex="<?=$tabindex++?>">
                     <?php foreach($yearData['users'] as $usr) { ?>
                        <option  value="<?=$usr['username']?>"<?=(($yearData['username'] == $usr['username'])?' selected="selected"':'')?>><?=$usr['lastfirst']?></option>
                     <?php } ?>
                  </select>
               <?=createModalBottom('btn_user', 'warning', $LANG['btn_select'])?>
            
            </div>
            
         </form>
            
      </div>      
