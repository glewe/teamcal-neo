<?php
/**
 * calendarview.php
 * 
 * Calendar view page view
 *
 * @category TeamCal Neo 
 * @version 0.9.013
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');

$formLink = 'index.php?action='.$controller.'&amp;month='.$viewData['year'].$viewData['month'].'&amp;region='.$viewData['regionid'].'&amp;group='.$viewData['groupid'].'&amp;abs='.$viewData['absid'];
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
         
         <form class="bs-example form-control-horizontal" enctype="multipart/form-data" action="<?=$formLink?>" method="post" target="_self" accept-charset="utf-8">

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
               <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageBwdYear.$pageBwdMonth?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>&amp;abs=<?=$viewData['absid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_backward']?>"><span class="fa fa-angle-double-left"></span></a>
               <a class="btn btn-default tooltip-warning" href="index.php?action=<?=$controller?>&amp;month=<?=$pageFwdYear.$pageFwdMonth?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>&amp;abs=<?=$viewData['absid']?>" data-position="tooltip-top" data-toggle="tooltip" data-title="<?=$LANG['cal_tt_forward']?>"><span class="fa fa-angle-double-right"></span></a>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=$viewData['yearToday'].$viewData['monthToday']?>&amp;region=<?=$viewData['regionid']?>&amp;group=<?=$viewData['groupid']?>&amp;abs=<?=$viewData['absid']?>"><?=$LANG['today']?></a>
               <?php if ($C->read('showRegionButton')) { ?>
                  <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectRegion"><?=$LANG['region'] . ': ' . $viewData['regionname']?></button>
               <?php } ?>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $viewData['group']?></button>
               <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectAbsence"><?=$LANG['absence'] . ': ' . $viewData['absence']?></button>
               <button type="submit" class="btn btn-info" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSearchUser"><?=$LANG['search'] . ': ' . $viewData['search']?></button>
               <a class="btn btn-default" href="index.php?action=<?=$controller?>&amp;month=<?=date('Y').date('m')?>&amp;region=<?=$viewData['regionid']?>&amp;group=all&amp;abs=all"><?=$LANG['btn_reset']?></a>
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
                        
                        <?php require("calendarviewmonthheader.php"); ?>
                        
                        <!-- Rows 4ff: Users -->
                        <?php
                        $dayAbsCount = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                        $dayPresCount = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                        
                        if ($C->read("defgroupfilter") == "allbygroup")
                        {
                           foreach ($viewData['groups'] as $grp)
                           { 
                              $groupHeader = false;
                              $repeatHeaderCount = $C->read("repeatHeaderCount");
                              if ($repeatHeaderCount) $rowcount = 1;
                              foreach ($viewData['users'] as $usr)
                              {
                                 if ($UG->isMemberOfGroup($usr['username'], $grp['id']))
                                 {
                                    if (!$groupHeader)
                                    { ?>
                                       <!-- Row: Group <?=$grp['name']?> -->
                                       <tr><th class="m-groupname" colspan="<?=$days+1?>"><?=$LANG['group'].': '.$grp['name']?></th></tr>
                                       <?php  $groupHeader = true; 
                                    }
                                    
                                    if ($repeatHeaderCount AND $rowcount>$repeatHeaderCount)
                                    {
                                       require("calendarviewmonthheader.php");
                                       $rowcount = 1;
                                    } ?>
                                    <!-- Row: User <?=$usr['username']?> --> 
                                    <?php require("calendarviewuserrow.php");
                                    if ($repeatHeaderCount) $rowcount++;
                                 }
                              }
                           }
                        }                                                                  
                        else
                        {
                           $repeatHeaderCount = $C->read("repeatHeaderCount");
                           if ($repeatHeaderCount) $rowcount = 1;
                           foreach ($viewData['users'] as $usr) 
                           {
                              if ($repeatHeaderCount AND $rowcount>$repeatHeaderCount)
                              {
                                 require("calendarviewmonthheader.php");
                                 $rowcount = 1;
                              } ?>
                              <!-- Row: User <?=$usr['username']?> --> 
                              <?php require("calendarviewuserrow.php");
                              if ($repeatHeaderCount) $rowcount++;
                           } 
                        } // End if AllByGroup ?>
                        
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
                     </table>
                  </div>
               
               <?php } ?>
            <?php } ?>

            <!-- Modal: Select Region -->
            <?=createModalTop('modalSelectRegion', $LANG['cal_selRegion'])?>
               <select id="region" class="form-control" name="sel_region" tabindex="<?=$tabindex++?>">
                  <?php foreach($viewData['regions'] as $reg) { ?>
                     <option value="<?=$reg['id']?>"<?=(($viewData['regionid'] == $reg['id'])?' selected="selected"':'')?>><?=$reg['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_region', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select Group -->
            <?=createModalTop('modalSelectGroup', $LANG['cal_selGroup'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($viewData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['groups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>"<?=(($viewData['groupid'] == $grp['id'])?' selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_group', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Select Absence -->
            <?=createModalTop('modalSelectAbsence', $LANG['cal_selAbsence'])?>
               <p><?=$LANG['cal_selAbsence_comment']?></p>
               <select id="absence" class="form-control" name="sel_absence" tabindex="<?=$tabindex++?>">
                  <option value="all" <?=(($viewData['absid'] == 'all')?'selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['absences'] as $abs) { ?>
                     <option value="<?=$abs['id']?>"<?=(($viewData['absid'] == $abs['id'])?' selected="selected"':'')?>><?=$abs['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_abssearch', 'warning', $LANG['btn_search'])?>

            <!-- Modal: Search User -->
            <?=createModalTop('modalSearchUser', $LANG['cal_search'])?>
               <input id="search" class="form-control" tabindex="<?=$tabindex++?>" name="txt_search" type="text" value="">
               <?php if ( isset($inputAlert["search"]) ) { ?> 
                  <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['search']?></div>
               <?php } ?> 
            <?=createModalBottom('btn_search', 'info', $LANG['btn_search'])?>
            
         </form>
         
         <?php if ($limit = $C->read("usersPerPage")) { ?>
         <nav aria-label="Paging">
            <ul class="pagination">
            
               <!-- First Page Link -->
               <?php if ($page==1) { ?>
                  <li class="disabled"><span><span aria-hidden="true"><i class="fa fa-angle-double-left"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=1" title="<?=$LANG['page_first']?>"><span><i class="fa fa-angle-double-left"></i></span></a></li>
               <?php } ?>
               
               <!-- Previous Page Link -->
               <?php if ($page==1) { ?>
                  <li class="disabled"><span><span aria-hidden="true"><i class="fa fa-angle-left"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=<?=$page-1?>" title="<?=$LANG['page_prev']?>"><span><i class="fa fa-angle-left"></i></span></a></li>
               <?php } ?>
               
               <!-- Page Link -->
               <?php for ($p=1; $p<=$pages; $p++) { 
                  if ($p==$page) { ?>
                     <li class="active"><span><?=$p?><span class="sr-only">(current)</span></span></li>
                  <?php } else { ?>
                     <li><a href="<?=$formLink?>&amp;page=<?=$p?>" title="<?=sprintf($LANG['page_page'],$p)?>"><span><?=$p?></span></a></li>
                  <?php } 
               } ?>
               
               <!-- Next Page Link -->
               <?php if ($page==$pages) { ?>
                  <li class="disabled"><span><span aria-hidden="true"><i class="fa fa-angle-right"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=<?=$page+1?>" title="<?=$LANG['page_next']?>"><span><i class="fa fa-angle-right"></i></span></a></li>
               <?php } ?>
               
               <!-- Last Page Link -->
               <?php if ($page==$pages) { ?>
                  <li class="disabled"><span><span aria-hidden="true"><i class="fa fa-angle-double-right"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=<?=$pages?>" title="<?=$LANG['page_last']?>"><span><i class="fa fa-angle-double-right"></i></span></a></li>
               <?php } ?>
               
           </ul>
         </nav>
         <?php } ?>
         
      </div>      
