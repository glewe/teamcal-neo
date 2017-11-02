<?php
/**
 * remainder.php
 * 
 * Remainder view page view
 *
 * @category TeamCal Neo 
 * @version 1.8.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');

$formLink = 'index.php?action='.$controller.'&amp;month='.$viewData['year'].$viewData['month'].'&amp;region='.$viewData['regionid'].'&amp;group='.$viewData['groupid'].'&amp;abs='.$viewData['absid'];
?>

      <!-- ==================================================================== 
      view.remainder
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
               <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $viewData['group']?></button>
               <button type="button" class="btn btn-info" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSearchUser"><?=$LANG['search'] . ': ' . $viewData['search']?></button>
               <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" name="btn_reset"><?=$LANG['btn_reset']?></button>
               <?php if ($viewData['supportMobile']) { ?> 
                  <button type="button" class="btn btn-default" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectWidth"><?=$LANG['screen'] . ': ' . $viewData['width']?></button>
               <?php } ?>
               <a href="index.php?action=calendarview" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_showcalendar']?></a>
            </div>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><?=$LANG['rem_title']?></div>
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
                        
                        <?php 
                        // 
                        // TODO: Remainder Header
                        //
                        // require("calendarviewmonthheader.php"); 
                        ?>
                        
                        <!-- Rows 4ff: Users -->
                        <?php
                        if ($C->read("defgroupfilter") == "allbygroup")
                        {
                           $repeatHeaderCount = $C->read("repeatHeaderCount");
                           if ($repeatHeaderCount) $rowcount = 1;
                           foreach ($viewData['groups'] as $grp)
                           { 
                              $groupHeader = false;
                              foreach ($viewData['users'] as $usr)
                              {
                                 if ($UG->isMemberOrManagerOfGroup($usr['username'], $grp['id']))
                                 {
                                    if ($repeatHeaderCount AND $rowcount>$repeatHeaderCount)
                                    {
                                       // 
                                       // TODO: Remainder Header
                                       //
                                       // require("calendarviewmonthheader.php"); 
                                       $rowcount = 1;
                                    }
                                    
                                    if (!$groupHeader)
                                    { ?>
                                       <!-- Row: Group <?=$grp['name']?> -->
                                       <tr><th class="m-groupname" colspan="<?=$days+1?>"><?=$grp['description'].' ('.$grp['name'].')'?></th></tr>
                                       <?php  $groupHeader = true; 
                                    } ?>
                                    
                                    <!-- Row: User <?=$usr['username']?> --> 
                                    <?php 
                                    // 
                                    // TODO: Remainder User Row
                                    //
                                    // require("calendarviewuserrow.php");
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
                                 // 
                                 // TODO: Remainder Header
                                 //
                                 // require("calendarviewmonthheader.php"); 
                                 $rowcount = 1;
                              } ?>
                              <!-- Row: User <?=$usr['username']?> --> 
                              <?php 
                              // 
                              // TODO: Remainder User Row
                              //
                              // require("calendarviewuserrow.php");
                              if ($repeatHeaderCount) $rowcount++;
                           } 
                        } // End if AllByGroup ?>
                        
                     </table>
                  </div>
               
               <?php } ?>
            <?php } ?>

            <!-- Modal: Select Group -->
            <?=createModalTop('modalSelectGroup', $LANG['cal_selGroup'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($viewData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['allGroups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>"<?=(($viewData['groupid'] == $grp['id'])?' selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_group', 'success', $LANG['btn_select'])?>
            
            <!-- Modal: Screen Width -->
            <?=createModalTop('modalSelectWidth', $LANG['cal_selWidth'])?>
               <p><?=$LANG['cal_selWidth_comment']?></p>
               <select id="width" class="form-control" name="sel_width" tabindex="<?=$tabindex++?>">
                  <?php foreach($LANG['widths'] as $key => $value) { ?>
                     <option value="<?=$key?>"<?=(($viewData['width'] == $key)?' selected="selected"':'')?>><?=$value?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_width', 'warning', $LANG['btn_select'])?>

            <!-- Modal: Search User -->
            <div class="modal fade" id="modalSearchUser" role="dialog" aria-labelledby="modalSearchUserLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalSearchUserLabel"><?=$LANG['cal_search']?></h4>
                     </div>
                     <div class="modal-body">
                        <input id="search" class="form-control" tabindex="<?=$tabindex++?>" name="txt_search" type="text" value="<?=$viewData["search"]?>">
                        <?php if ( isset($inputAlert["search"]) ) { ?> 
                           <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove-circle"></span></button><?=$inputAlert['search']?></div>
                        <?php } ?> 
                     </div>
                     <div class="modal-footer">
                        <button type="submit" class="btn btn-info" tabindex="<?=$tabindex++?>" name="btn_search" style="margin-top: 4px;"><?=$LANG['btn_search']?></button>
                        <?php if (strlen($viewData["search"])) { ?><button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++?>" name="btn_search_clear"><?=$LANG['btn_clear']?></button><?php } ?>
                     </div>
                  </div>
               </div>
            </div>                        
            
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
