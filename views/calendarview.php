<?php
/**
 * calendar.php
 * 
 * The view of the calendar page
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
      view.calendar
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
            }
             
            $tabindex = 1; $colsleft = 1; $colsright = 4;
            $xeditable = false;
            ?>
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;month=<?=$calData['year'].$calData['month']?>&amp;region=<?=$calData['regionid']?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_month" type="hidden" class="text" value="<?=$calData['month']?>">
            <input name="hidden_region" type="hidden" class="text" value="<?=$calData['region']?>">

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
               <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$calData['regionid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_backward']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$calData['regionid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_forward']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$calData['yearToday'].$calData['monthToday']?>&amp;region=<?=$calData['regionid']?>"><?=$LANG['today']?></a>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $calData['regionname']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $calData['group']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectAbsence"><?=$LANG['absence'] . ': ' . $calData['absence']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSearchUser"><?=$LANG['search'] . ': ' . $calData['search']?></button>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=date('Y').date('m')?>&amp;region=<?=$calData['regionid']?>"><?=$LANG['btn_reset']?></a>
            </div>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['cal_title'], $calData['year'], $calData['month'], $calData['regionname'])?></div>
            </div>
            
            <?php if (!$calData['supportMobile']) $mobilecols = array('full'=>$calData['dateInfo']['daysInMonth']);
            foreach ($mobilecols as $key => $cols) 
            { 
               $days = $calData['dateInfo']['daysInMonth'];
               $tables = ceil( $days / $cols);
               $script = '';
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
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 if (isset($calData['dayStyles'][$i]) AND strlen($calData['dayStyles'][$i])) $dayStyles = ' style="' . $calData['dayStyles'][$i] . '"';
                                 else $dayStyles = '';
                                 ?> 
                                 <th class="m-daynumber text-center"<?=$dayStyles?>><?=$i?></th>
                              <?php } ?>
                           </tr>
                           
                           <!-- Row: Weekdays -->
                           <tr>
                              <th class="m-label">&nbsp;</th>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 if (isset($calData['dayStyles'][$i]) AND strlen($calData['dayStyles'][$i])) $dayStyles = ' style="' . $calData['dayStyles'][$i] . '"';
                                 else $dayStyles = '';
                                 $prop = 'wday'.$i; 
                                 ?>
                                 <th class="m-weekday text-center"<?=$dayStyles?>><?=$LANG['weekdayShort'][$M->$prop]?></th>
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
                           <!-- Rows 4ff: Users -->
                           <?php foreach ($calData['users'] as $usr) { 
                              $href = 'index.php?action=viewprofile&amp;profile=' . $usr['username'];
                              if ( ($UL->username==$usr['username']) 
                                    OR isAllowed("editAllUserProfiles") 
                                    OR (isAllowed("editGroupUserProfiles") AND $UG->shareGroups($UL->username, $usr['username'])) 
                                 ) 
                              {
                                 $href = 'index.php?action=profile&amp;profile=' . $usr['username'];
                              }                        
                              ?>
                              <tr>
                                 <td class="m-name"><a href="<?=$href?>"><?=$U->getLastFirst($usr['username'])?></a></td>
                                 <?php 
                                 $T->getTemplate($usr['username'], $calData['year'], $calData['month']);
                                 $currDate = date('Y-m-d');
                                 for ($i=$daystart; $i<=$dayend; $i++) 
                                 { 
                                    $loopDate = date('Y-m-d', mktime(0, 0, 0, $calData['month'], $i, $calData['year']));
                                    $abs = 'abs'.$i; 
                                    $style = $calData['dayStyles'][$i];
                                    $icon = '&nbsp;';
                                    $absstart = '';
                                    $absend = '';
                                    $note = false;
                                    $notestart = '';
                                    $noteend = '';
                                    $bday = false;
                                    $bdaystart = '';
                                    $bdayend = '';
                                    
                                    if ($T->$abs) 
                                    {
                                       /**
                                        * This is an absence. Get icon and coloring info.
                                        */
                                       if ($A->getBgTrans($T->$abs)) $bgStyle = ""; else $bgStyle = "background-color: #". $A->getBgColor($T->$abs) . ";";
                                       $style .= 'color: #' . $A->getColor($T->$abs) . ';' . $bgStyle;
                                       $icon = '<span class="fa fa-'.$A->getIcon($T->$abs).'"></span>';
                                       $absstart = '<div class="tooltip-danger" style="width: 100%; height: 100%;" data-position="tooltip-top" data-toggle="tooltip" data-title="'.$A->getName($T->$abs).'">';                 
                                       $absend = '</div>';                 
                                    }
                                    
                                    if ($D->findByDay($calData['year'] . $calData['month'] . sprintf("%02d", $i), $usr['username']))
                                    {
                                       /**
                                        * This is a user's daynote
                                        */
                                       $note = true;
                                       $notestart = '<div class="tooltip-info" style="width: 100%; height: 100%;" data-position="tooltip-right" data-toggle="tooltip" data-title="' . $D->daynote . '">';
                                       $noteend = '</div>';
                                    }
                                    
                                    if ( $bdate = $UO->read($usr['username'], 'birthday') AND $bdate == $loopDate AND $UO->read($usr['username'], 'showbirthday') )
                                    {
                                       /**
                                        * This is the user's birthday and he wants to show it
                                        */
                                       $bday = true;
                                       $bdaystart = '<div class="tooltip-warning" style="width: 100%; height: 100%;" data-position="tooltip-bottom" data-toggle="tooltip" data-title="Birthday!!!">';                 
                                       $bdayend = '</div>';                 
                                    }
                                    
                                    /**
                                     * Select the upper right corner indicator if applicable
                                     */
                                    if ($note AND $bday)
                                    {
                                       $style .= 'background-image: url(images/ovl_bdaynote.gif); background-repeat: no-repeat; background-position: top right;';
                                    }
                                    else if ($note)
                                    {
                                       $style .= 'background-image: url(images/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;';
                                    }
                                    else if ($bday)
                                    {
                                       $style .= 'background-image: url(images/ovl_birthday.gif); background-repeat: no-repeat; background-position: top right;';
                                    }
                                    
                                    /**
                                     * If we have styles collected, build the style attribute
                                     */
                                    if (strlen($style)) $style = ' style="' . $style . '"';
                                    
                                    ?>
                                    <td class="m-day text-center"<?=$style?>>
                                    
                                       <?php if ($xeditable) 
                                       { ?>
                                          <a href="#" id="<?=$usr['username'].$loopDate.$key?>" data-type="select2" data-pk="1" data-url="/post" data-value="HO" data-title="Select absence">
                                          <?php 
                                          echo $bdaystart . $notestart. $absstart . $icon . $absend . $noteend . $bdayend;
                                          $script .= "$('#".$usr['username'].$loopDate.$key."').editable({source: absences, select2: { width: 200, placeholder: 'Select absence', allowClear: true } });\n"
                                          ?>
                                          </a>
                                       <?php } 
                                       else 
                                       {
                                          echo $bdaystart . $notestart. $absstart . $icon . $absend . $noteend . $bdayend;
                                       } ?>
                                       
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                           
                           <!-- Last Row: Summary -->
                           <tr>
                              <td class="m-label" colspan="<?=$dayend-$daystart+2?>"><?=$LANG['cal_summary']?></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               
               <?php } ?>
            <?php } ?>

            <?php if ($xeditable) { ?>
            <script type="text/javascript">
               $(document).ready(function() {
                  var absences = [];
                  $.each({"HO": "Ho", "VA": "Va"}, function(k, v) {
                      absences.push({id: k, text: v});
                  }); 
                  <?=$script?> 
               });
            </script>
            <?php } ?>
            
            <!-- Modal: Select Region -->
            <?=createModalTop('modalSelectRegion', $LANG['cal_selRegion'])?>
               <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                  <?php foreach($calData['regions'] as $reg) { ?>
                     <option value="<?=$reg['id']?>" <?=(($calData['regionid'] == $reg['id'])?'selected="selected"':'')?>><?=$reg['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_region', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select Group -->
            <?=createModalTop('modalSelectGroup', $LANG['cal_selGroup'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($calData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($calData['groups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>" <?=(($calData['groupid'] == $grp['id'])?'selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_group', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select Absence -->
            <?=createModalTop('modalSelectAbsence', $LANG['cal_selAbsence'])?>
               <p><?=$LANG['cal_selAbsence_comment']?></p>
               <select id="absence" class="form-control" name="sel_absence" tabindex="<?=$tabindex++?>">
                  <option value="all" <?=(($calData['absid'] == 'all')?'selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($calData['absences'] as $abs) { ?>
                     <option value="<?=$abs['id']?>" <?=(($calData['absid'] == $abs['id'])?'selected="selected"':'')?>><?=$abs['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_abssearch', 'warning', $LANG['btn_search'])?>

            <!-- Modal: Search User -->
            <?=createModalTop('modalSearchUser', $LANG['cal_search'])?>
               <input id="search" class="form-control" tabindex="<?=$tabindex++?>" name="txt_search" type="text" value="">
               <?php if ( isset($inputAlert["search"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['search']?></div>
               <?php } ?> 
            <?=createModalBottom('btn_search', 'warning', $LANG['btn_search'])?>
            
         </form>
            
      </div>      
