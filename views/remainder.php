<?php
/**
 * remainder.php
 * 
 * Remainder page view
 *
 * @category TeamCal Neo 
 * @version 2.0.0
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');

$formLink = 'index.php?action='.$controller.'&amp;group='.$viewData['groupid'];
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

            <div class="page-menu">
               <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSelectGroup"><?=$LANG['group'] . ': ' . $viewData['group']?></button>
               <button type="button" class="btn btn-info" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalSearchUser"><?=$LANG['search'] . ': ' . $viewData['search']?></button>
               <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" name="btn_reset"><?=$LANG['btn_reset']?></button>
               <a href="index.php?action=calendarview" class="btn btn-default pull-right" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_showcalendar']?></a>
            </div>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
               ?>
               <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['rem_title'].$pageHelp?></div>
            </div>
            
            <!-- Remainder Table -->
            <div class="panel panel-default">
               <div class="panel-body">

                  <table class="table table-bordered table-hover year">
                     <thead>
                        <tr>
                           <th><?=$LANG['users_user']?></th>
                           <?php foreach ($viewData['absences'] as $abs) { 
                              if ($abs['show_in_remainder']) {
                              ?>
                              <th class="text-center">
                                 <?php if($abs['bgtrans']) $bgstyle=""; else $bgstyle="background-color: #".$abs['bgcolor'].";";?>
                                 <div style="color:#<?=$abs['color']?>;<?=$bgstyle?>border:1px solid #333333; width:26px; height:26px;">
                                    <?php if ($abs['icon'] != "No") { ?>
                                       <a href="#" style="color:inherit;" data-position="tooltip-top" class="tooltip-default" data-toggle="tooltip" data-title="<?=$abs['name']?>"><span class="<?=$abs['icon']?>"></span></a>
                                    <?php } else { ?>
                                       <?=$abs['symbol']?>
                                    <?php } ?>
                                 </div>
                              </th>
                              <?php } 
                           } ?>
                        </tr>
                     </thead>

                     <tbody>
                        <?php foreach ($viewData['users'] as $user) { ?>
                        <tr>                  
                           <td class="m-name"><a href="index.php?action=useredit&amp;profile=<?=$user['username']?>" tabindex="<?=$tabindex++;?>"><?=$user['dispname']?></a></td>
                           <?php foreach ($viewData['absences'] as $abs) { 
                              if ($abs['show_in_remainder']) 
                              {
                                 echo '<td class="m-day text-center">';

                                 if ($AL->find($user['username'], $abs['id']))
                                 {
                                    $carryover = $AL->carryover;
                                    if (!$AL->allowance) {
                                       //
                                       // Zero personal allowance will take over global yearly allowance
                                       //
                                       $AL->allowance = $abs['allowance'];
                                       $AL->update();
                                    }
                                    $allowance = $AL->allowance;
                                 }
                                 else
                                 {
                                    $carryover = 0;
                                    $allowance = $abs['allowance'];
                                 }
                                 $totalAllowance = $allowance + $carryover;
                                 
                                 $taken = 0;
                                 if (!$abs['counts_as_present'])
                                 {
                                    $taken = countAbsence($user['username'], $abs['id'], $countFrom, $countTo, false, false);
                                 }

                                 $remainder = $allowance + $carryover - ($taken * $abs['factor']);

                                 $dispTaken = '<span class="badge btn-info">'.$taken.'</span>';
                                 $dispAllowance = '<span class="badge btn-primary">'.$totalAllowance.'</span>';
                                 $dispRemainder = '<span class="badge btn-'.(($remainder<0)?"danger":"success").'">'.$remainder.'</span>';
                                 $separator = "-";
                                 echo $dispTaken . $separator . $dispAllowance . $separator . $dispRemainder;
                                 echo '</td>';
                              }
                           } ?>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>

                  <p><span class="badge btn-info"><?=$LANG['rem_legend_taken']?></span>-<span class="badge btn-primary"><?=$LANG['rem_legend_allowance']?></span>-<span class="badge btn-success"><?=$LANG['rem_legend_remainder']?></span></p>

               </div>
            </div>

            <!-- Modal: Select Group -->
            <?=createModalTop('modalSelectGroup', $LANG['cal_selGroup'])?>
               <select id="group" class="form-control" name="sel_group" tabindex="<?=$tabindex++?>">
                  <option value="all"<?=(($viewData['groupid'] == 'all')?' selected="selected"':'')?>><?=$LANG['all']?></option>
                  <?php foreach($viewData['allGroups'] as $grp) { ?>
                     <option value="<?=$grp['id']?>"<?=(($viewData['groupid'] == $grp['id'])?' selected="selected"':'')?>><?=$grp['name']?></option>
                  <?php } ?>
               </select>
            <?=createModalBottom('btn_group', 'success', $LANG['btn_select'])?>
            
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
                           <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="far fa-times-circle"></i></button><?=$inputAlert['search']?></div>
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
                  <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=1" title="<?=$LANG['page_first']?>"><span><i class="fas fa-angle-double-left"></i></span></a></li>
               <?php } ?>
               
               <!-- Previous Page Link -->
               <?php if ($page==1) { ?>
                  <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-left"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=<?=$page-1?>" title="<?=$LANG['page_prev']?>"><span><i class="fas fa-angle-left"></i></span></a></li>
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
                  <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-right"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=<?=$page+1?>" title="<?=$LANG['page_next']?>"><span><i class="fas fa-angle-right"></i></span></a></li>
               <?php } ?>
               
               <!-- Last Page Link -->
               <?php if ($page==$pages) { ?>
                  <li class="disabled"><span><span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span></span></li>
               <?php } else { ?>
                  <li><a href="<?=$formLink?>&amp;page=<?=$pages?>" title="<?=$LANG['page_last']?>"><span><i class="fas fa-angle-double-right"></i></span></a></li>
               <?php } ?>
               
           </ul>
         </nav>
         <?php } ?>
         
      </div>      
