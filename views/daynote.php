<?php
/**
 * daynote.php
 * 
 * Daynote editor view
 *
 * @category TeamCal Neo 
 * @version 0.9.013
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.daynote
      -->
      <div class="container content">
      
               
         <div class="col-lg-12">
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
            <?php $tabindex = 1; $colsleft = 6; $colsright = 6;?>
            
            <form  class="bs-example form-control-horizontal" action="index.php?action=<?=$controller?>&amp;date=<?=str_replace('-','',$viewData['date'])?>&amp;for=<?=$viewData['user']?>&amp;region=<?=$viewData['region']?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <?php 
                  $title = $LANG['dn_title'].' '.$LANG['dn_title_for'].' '.$viewData['date'].' ('.$LANG['user'].': '.$viewData['userFullname'];
                  if ($viewData['user']=='all') $title .= ', '.$LANG['region'].': '.$viewData['regionName'];
                  $title .= ')';
                  ?>
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$title?></div>
                  <div class="panel-body">
                  
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <?php if ($viewData['exists']) { ?>
                              <input name="hidden_id" type="hidden" value="<?=$viewData['id']?>">
                              <button type="submit" class="btn btn-warning" tabindex="<?=$tabindex++;?>" name="btn_update"><?=$LANG['btn_update']?></button>
                              <button type="submit" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteDaynote_<?=$viewData['id']?>"><?=$LANG['btn_delete']?></button>
                           <?php } else { ?>
                              <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_create"><?=$LANG['btn_create']?></button>
                           <?php } ?>
                        </div>
                     </div>
                     
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <?php foreach($viewData['daynote'] as $formObject) {
                              echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                           } ?>
                        </div>
                     </div>
      
                  </div>
               </div>

               <!-- Modal: Delete Daynote -->
               <?=createModalTop('modalDeleteDaynote_'.$viewData['id'], $LANG['btn_delete'])?>
                  <?=sprintf($LANG['dn_confirm_delete'])?>
               <?=createModalBottom('btn_delete', 'danger', $LANG['btn_delete'])?>
               
            </form>
            
         </div>
         
      </div>      
