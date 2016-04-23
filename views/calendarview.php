<?php
/**
 * calendarview.php
 * 
 * Calendar view page view
 *
 * @category TeamCal Neo 
 * @version 0.5.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license (Not available yet) http://tcneo.lewe.com/doc/license.txt
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.calendarview
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
            ?>
         
         <form  class="bs-example form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?=$controller?>&amp;month=<?=$viewData['year'].$viewData['month']?>&amp;region=<?=$viewData['regionid']?>" method="post" target="_self" accept-charset="utf-8">

            <input name="hidden_month" type="hidden" class="text" value="<?=$viewData['month']?>">
            <input name="hidden_region" type="hidden" class="text" value="<?=$viewData['region']?>">

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
               <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$viewData['regionid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_backward']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$viewData['regionid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_forward']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$viewData['yearToday'].$viewData['monthToday']?>&amp;region=<?=$viewData['regionid']?>"><?=$LANG['today']?></a>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $viewData['regionname']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $viewData['group']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectAbsence"><?=$LANG['absence'] . ': ' . $viewData['absence']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSearchUser"><?=$LANG['search'] . ': ' . $viewData['search']?></button>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=date('Y').date('m')?>&amp;region=<?=$viewData['regionid']?>"><?=$LANG['btn_reset']?></a>
            </div>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=sprintf($LANG['cal_title'], $viewData['year'], $viewData['month'], $viewData['regionname'])?></div>
            </div>
            
            <?php if (!$viewData['supportMobile']) $mobilecols = array('full'=>$viewData['dateInfo']['daysInMonth']);
            foreach ($mobilecols as $key => $cols) 
            { 
               $days = $viewData['dateInfo']['daysInMonth'];
               $tables = ceil( $days / $cols);
               $script = '';
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
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 if (isset($viewData['dayStyles'][$i]) AND strlen($viewData['dayStyles'][$i])) $dayStyles = ' style="' . $viewData['dayStyles'][$i] . '"';
                                 else $dayStyles = '';
                                 ?> 
                                 <th class="m-daynumber text-center"<?=$dayStyles?>><?=$i?></th>
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
                                    <th class="m-weeknumber text-center<?=(($M->$wprop==1)?' first':' inner')?>"><?=(($M->$wprop==1)?$M->$prop:'')?></th>
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                        </thead>
                        <tbody>
                           <!-- Rows 4ff: Users -->
                           <?php
                           $dayAbsCount = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                           $dayPresCount = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                           foreach ($viewData['users'] as $usr) 
                           { 
                              //
                              // Check whether the current user may view users profiles.
                              // If so, we link the name to the viewprofile page
                              //
                              $profileLink = '';
                              if (isAllowed($CONF['controllers']['viewprofile']->permission))
                              {
                                 $profileName = '<a href="index.php?action=viewprofile&amp;profile=' . $usr['username'] . '">'.$U->getLastFirst($usr['username']).'</a>';
                              }
                              else
                              {
                                 $profileName = $U->getLastFirst($usr['username']);
                              }
                              
                              //
                              // Check whether the current user may edit this loop users calendar.
                              // If so, we link each table cell to the editcalendar page (done so in the day loop below).
                              //
                              $editAllowed = false;
                              $editLink = '';
                              if (isAllowed($CONF['controllers']['calendaredit']->permission))
                              {
                                 if ( $UL->username == $usr['username'] )
                                 {
                                    if (isAllowed("calendareditown")) $editAllowed = true;
                                 }
                                 else if ( $UG->shareGroups($UL->username, $usr['username']) )
                                 {
                                    if (isAllowed("calendareditgroup")) $editAllowed = true;
                                 }
                                 else
                                 {
                                    if (isAllowed("calendareditall")) $editAllowed = true;
                                 }
                              }
                              if ($editAllowed)
                              {
                                 $editLink = ' onclick="window.location.href = \'index.php?action=calendaredit&amp;month='.date('Y').date('m').'&amp;region='.$viewData['regionid'].'&user='.$usr['username'].'\';"';
                              }
                              ?>
                              <tr>
                                 <td class="m-name">
                                    <?php if ($C->read('showAvatars')) { ?>
                                       <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<img src='<?=$CONF['app_avatar_dir'].$UO->read($usr['username'],'avatar')?>' alt='' style='width: 80px; height: 80px;'>"><img src="<?=$CONF['app_avatar_dir']?>/<?=$UO->read($usr['username'],'avatar')?>" alt="" style="width: 16px; height: 16px;"></i>
                                    <?php } ?>
                                    <?php if ($C->read('showRoleIcons')) { ?>
                                       <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['role']?>: <?=$RO->getNameById($usr['role'])?>"><i class="fa fa-user text-<?=$RO->getColorById($usr['role'])?>" style="font-size: 128%; padding-right: 8px;"></i></i>
                                    <?php } ?>
                                    <?=$profileName?>
                                 </td>
                                 <?php 
                                 $T->getTemplate($usr['username'], $viewData['year'], $viewData['month']);
                                 $currDate = date('Y-m-d');
                                 for ($i=$daystart; $i<=$dayend; $i++) 
                                 { 
                                    $loopDate = date('Y-m-d', mktime(0, 0, 0, $viewData['month'], $i, $viewData['year']));
                                    $abs = 'abs'.$i; 
                                    $style = $viewData['dayStyles'][$i];
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
                                       //
                                       // This is an absence. Get icon and coloring info.
                                       //
                                       if ($A->getBgTrans($T->$abs)) $bgStyle = ""; else $bgStyle = "background-color: #". $A->getBgColor($T->$abs) . ";";
                                       $style .= 'color: #' . $A->getColor($T->$abs) . ';' . $bgStyle;
                                       $icon = '<span class="fa fa-'.$A->getIcon($T->$abs).'"></span>';
                                       $absstart = '<div class="tooltip-danger" style="width: 100%; height: 100%;" data-position="tooltip-top" data-toggle="tooltip" data-title="'.$A->getName($T->$abs).'">';                 
                                       $absend = '</div>';
                                       $dayAbsCount[$i]++;
                                    }
                                    else 
                                    {
                                       $dayPresCount[$i]++;
                                    }
                                    
                                    if ($D->findByDay($viewData['year'] . $viewData['month'] . sprintf("%02d", $i), $usr['username']))
                                    {
                                       //
                                       // This is a user's daynote
                                       //
                                       $note = true;
                                       $notestart = '<div class="tooltip-info" style="width: 100%; height: 100%;" data-position="tooltip-right" data-toggle="tooltip" data-title="' . $D->daynote . '">';
                                       $noteend = '</div>';
                                    }
                                    
                                    if ( $bdate = $UO->read($usr['username'], 'birthday') AND $bdate == $loopDate AND $UO->read($usr['username'], 'showbirthday') )
                                    {
                                       //
                                       // This is the user's birthday and he wants to show it
                                       //
                                       $bday = true;
                                       $bdaystart = '<div class="tooltip-warning" style="width: 100%; height: 100%;" data-position="tooltip-bottom" data-toggle="tooltip" data-title="Birthday!!!">';                 
                                       $bdayend = '</div>';                 
                                    }
                                    
                                    //
                                    // Select the upper right corner indicator if applicable
                                    //
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
                                    
                                    //
                                    // If we have styles collected, build the style attribute
                                    //
                                    if (strlen($style)) $style = ' style="' . $style . '"';
                                    ?>
                                    
                                    <td class="m-day text-center"<?=$style?><?=$editLink?>>
                                       <?php echo $bdaystart . $notestart. $absstart . $icon . $absend . $noteend . $bdayend; ?>
                                    </td>
                                    
                                 <?php } ?>
                              </tr>
                           <?php } ?>
                           
                           <?php if ($C->read('includeSummary')) { ?>
                           <!-- Row: Summary Header -->
                           <tr>
                              <td class="m-label" colspan="<?=$dayend-$daystart+2?>">
                                 <span style="float: left;"><?=$LANG['cal_summary']?>&nbsp;<a class="btn btn-default btn-xs" data-toggle="collapse" data-target=".summary">...</a></span>
                                 <span class="pull-right text-normal"><?=$viewData['businessDays']?>&nbsp;<?=$LANG['cal_businessDays']?></span>
                              </td>
                           </tr>

                           <!-- Row: Summary Absences -->
                           <tr class="summary <?=(!$C->read('showSummary'))?'collapse':'in';?>">
                              <td class="m-name"><?=$LANG['sum_absent']?></td>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 $style = substr($viewData['dayStyles'][$i], 14);
                                 $style = ' style="' . $style . '"';
                                 ?>   
                                 <td class="m-day text-center text-danger"<?=$style?>><?=$dayAbsCount[$i]?></td>
                              <?php } ?> 
                           </tr>
                           
                           <!-- Row: Summary Presences -->
                           <tr class="summary <?=(!$C->read('showSummary'))?'collapse':'in';?>">
                              <td class="m-name"><?=$LANG['sum_present']?></td>
                              <?php for ($i=$daystart; $i<=$dayend; $i++) { 
                                 $style = substr($viewData['dayStyles'][$i], 14);
                                 $style = ' style="' . $style . '"';
                                 ?>   
                                 <td class="m-day text-center text-success"<?=$style?>><?=$dayPresCount[$i]?></td>
                              <?php } ?> 
                           </tr>
                           <?php } ?> 
                           
                        </tbody>
                     </table>
                  </div>
               
               <?php } ?>
            <?php } ?>

            <!-- Modal: Select Region -->
            <?=createModalTop('modalSelectRegion', $LANG['cal_selRegion'])?>
               <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                  <?php foreach($viewData['regions'] as $reg) { ?>
                     <option value="<?=$reg['id']?>" <?=(($viewData['regionid'] == $reg['id'])?'selected="selected"':'')?>><?=$reg['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_region', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select Group -->
            <?=createModalTop('modalSelectGroup', $LANG['cal_selGroup'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($viewData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['groups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>" <?=(($viewData['groupid'] == $grp['id'])?'selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_group', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select Absence -->
            <?=createModalTop('modalSelectAbsence', $LANG['cal_selAbsence'])?>
               <p><?=$LANG['cal_selAbsence_comment']?></p>
               <select id="absence" class="form-control" name="sel_absence" tabindex="<?=$tabindex++?>">
                  <option value="all" <?=(($viewData['absid'] == 'all')?'selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['absences'] as $abs) { ?>
                     <option value="<?=$abs['id']?>" <?=(($viewData['absid'] == $abs['id'])?'selected="selected"':'')?>><?=$abs['name']?></option>
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
