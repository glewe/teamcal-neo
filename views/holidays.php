<?php
/**
 * holidays.php
 * 
 * Holiday list view
 *
 * @category TeamCal Neo 
 * @version 0.9.012
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.holidays
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
            <?php $tabindex = 1; $colsleft = 8; $colsright = 4;?>

            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['hol_list_title']?></div>
               
               <div class="panel-body">

                  <form class="bs-example form-control-horizontal" name="form_create" action="index.php?action=<?=$CONF['controllers'][$controller]->name?>" method="post" target="_self" accept-charset="utf-8">
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-12 text-right">
                              <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalCreateHoliday"><?=$LANG['btn_create_holiday']?></button>
                           </div>
                        </div>
                     </div>
                     
                     <!-- Modal: Create Holiday -->
                     <?=createModalTop('modalCreateHoliday', $LANG['btn_create_holiday'])?>
                        <label for="inputName"><?=$LANG['name']?></label>
                        <input id="inputName" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_name" maxlength="40" value="<?=$viewData['txt_name']?>" type="text">
                        <?php if ( isset($inputAlert["name"]) AND strlen($inputAlert["name"]) ) { ?> 
                           <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><?=$inputAlert["name"]?></div>
                        <?php } ?> 
                        <label for="inputDescription"><?=$LANG['description']?></label>
                        <input id="inputDescription" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_description" maxlength="100" value="<?=$viewData['txt_description']?>" type="text">
                        <?php if ( isset($inputAlert["description"]) AND strlen($inputAlert["description"]) ) { ?> 
                           <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><?=$inputAlert["description"]?></div>
                        <?php } ?> 
                     <?=createModalBottom('btn_holCreate', 'success', $LANG['btn_create_holiday'])?>
                     
                  </form>
            
                  <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                     <div class="col-lg-1"><?=$LANG['display']?></div>
                     <div class="col-lg-2"><?=$LANG['name']?></div>
                     <div class="col-lg-4"><?=$LANG['description']?></div>
                     <div class="col-lg-3"><?=$LANG['options']?></div>
                     <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                  </div>
                              
                  <?php foreach ($viewData['holidays'] as $holiday) { ?>
                     <form  class="bs-example form-control-horizontal" name="form_<?=$holiday['id']?>" action="index.php?action=<?=$CONF['controllers'][$controller]->name?>" method="post" target="_self" accept-charset="utf-8">
                        <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                           <div class="col-lg-1">
                              <div style="color: #<?=$holiday['color']?>; background-color: #<?=$holiday['bgcolor']?>; border: 1px solid; width: 26px; height: 26px; text-align: center;">
                                 23
                              </div>
                           </div>
                           <div class="col-lg-2"><?=$holiday['name']?></div>
                           <div class="col-lg-4"><?=$holiday['description']?></div>
                           <div class="col-lg-3">
                              <?=(($holiday['businessday'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['hol_businessday'].'"><i class="fa fa-wrench fa-lg text-danger"></i></i>':'')?>
                           </div>
                           <div class="col-lg-2 text-right">
                              <a href="index.php?action=holidayedit&amp;id=<?=$holiday['id']?>" class="btn btn-warning btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                              <?php if ($holiday['id'] > 3) { ?>
                                 <button type="submit" class="btn btn-danger btn-xs" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteHoliday_<?=$holiday['id']?>"><?=$LANG['btn_delete']?></button>
                                 <input name="hidden_id" type="hidden" value="<?=$holiday['id']?>">
                                 <input name="hidden_name" type="hidden" value="<?=$holiday['name']?>">
                                 <input name="hidden_description" type="hidden" value="<?=$holiday['description']?>">
                              <?php } ?>
                           </div>
                        </div>
                        
                        <?php if ($holiday['id'] > 3) { ?>
                           <!-- Modal: Delete Holiday -->
                           <?=createModalTop('modalDeleteHoliday_'.$holiday['id'], $LANG['btn_delete_holiday'])?>
                              <?=sprintf($LANG['hol_confirm_delete'], $holiday['name'])?>
                           <?=createModalBottom('btn_holDelete', 'danger', $LANG['btn_delete_holiday'])?>
                        <?php } ?>
                           
                     </form>
                     <?php } ?>
                     
               </div>
            </div>
               
         </div>
      </div>
