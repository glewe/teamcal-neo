<?php
/**
 * absences.php
 * 
 * Absence type list page view
 *
 * @category TeamCal Neo 
 * @version 1.3.001
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.absences
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
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['abs_list_title']?></div>
               
               <div class="panel-body">

                  <form class="bs-example form-control-horizontal" name="form_create" action="index.php?action=<?=$CONF['controllers'][$controller]->name?>" method="post" target="_self" accept-charset="utf-8">
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-12 text-right">
                              <button type="button" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalCreateAbsence"><?=$LANG['btn_create_abs']?></button>
                           </div>
                        </div>
                     </div>
                     
                     <!-- Modal: Creates Absence -->
                     <?=createModalTop('modalCreateAbsence', $LANG['btn_create_abs'])?>
                        <label for="inputName"><?=$LANG['name']?></label>
                        <input id="inputName" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_name" maxlength="40" value="<?=$viewData['txt_name']?>" type="text">
                        <?php if ( isset($inputAlert["name"]) AND strlen($inputAlert["name"]) ) { ?> 
                           <br><div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><?=$inputAlert["name"]?></div>
                        <?php } ?> 
                     <?=createModalBottom('btn_absCreate', 'success', $LANG['btn_create_abs'])?>
                     
                  </form>
            
                  <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                     <div class="col-lg-1"><?=$LANG['abs_display']?></div>
                     <div class="col-lg-4"><?=$LANG['abs_name']?></div>
                     <div class="col-lg-5"><?=$LANG['options']?></div>
                     <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                  </div>
                              
                  <?php foreach ($viewData['absences'] as $absence) { 
                     if (!$absence['counts_as']) { ?>
                     <form  class="bs-example form-control-horizontal" name="form_<?=$absence['id']?>" action="index.php?action=<?=$CONF['controllers'][$controller]->name?>" method="post" target="_self" accept-charset="utf-8">
                        <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                           <div class="col-lg-1">
                              <?php if($absence['bgtrans']) $bgstyle=""; else $bgstyle="background-color: #".$absence['bgcolor'].";";?>
                              <div style="color: #<?=$absence['color']?>;<?=$bgstyle?>border: 1px solid #333333; width: 26px; height: 26px; text-align: center; padding-top: 2px;">
                                 <?php if ($absence['icon'] != "No") { ?>
                                    <span class="fa fa-<?=$absence['icon']?>"></span>
                                 <?php } else { ?>
                                    <?=$absence['symbol']?>
                                 <?php } ?>
                              </div>
                           </div>
                           <div class="col-lg-4"><?=$absence['name']?></div>
                           <div class="col-lg-5">
                              <?=(($absence['approval_required'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['abs_approval_required'].'"><i class="fa fa-pencil-square-o fa-lg text-danger"></i></i>':'')?>
                              <?=(($absence['manager_only'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['abs_manager_only'].'"><i class="fa fa-user fa-lg text-warning"></i></i>':'')?>
                              <?=(($absence['hide_in_profile'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['abs_hide_in_profile'].'"><i class="fa fa-eye-slash fa-lg text-info"></i></i>':'')?>
                              <?=(($absence['confidential'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['abs_confidential'].'"><i class="fa fa-exclamation-circle fa-lg text-success"></i></i>':'')?>
                              <?=(($absence['allowmonth'] OR ($absence['allowweek']))?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['abs_allow_active'].'"><i class="fa fa-hand-stop-o fa-lg text-warning"></i></i>':'')?>
                           </div>
                           <div class="col-lg-2 text-right">
                              <button type="button" class="btn btn-danger btn-xs" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteAbsence_<?=$absence['id']?>"><?=$LANG['btn_delete']?></button>
                              <a href="index.php?action=absenceedit&amp;id=<?=$absence['id']?>" class="btn btn-warning btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                              <input name="hidden_id" type="hidden" value="<?=$absence['id']?>">
                              <input name="hidden_name" type="hidden" value="<?=$absence['name']?>">
                           </div>
                        </div>
                        
                        <!-- Modal: Delete Absence -->
                        <?=createModalTop('modalDeleteAbsence_'.$absence['id'], $LANG['modal_confirm'])?>
                           <?=sprintf($LANG['abs_confirm_delete'], $absence['name'])?>
                        <?=createModalBottom('btn_absDelete', 'success', $LANG['btn_delete_abs'])?>
                        
                     </form>
                     
                     <?php 
                     $subabsences = $A->getAllSub($absence['id']);
                     foreach ($subabsences as $subabs) { ?>
                        <form  class="bs-example form-control-horizontal" name="form_<?=$subabs['id']?>" action="index.php?action=absences" method="post" target="_self" accept-charset="utf-8">
                           <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                              <div class="col-lg-1 text-right"><i class="fa fa-angle-double-right"></i></div>
                              <div class="col-lg-1">
                                 <div id="sample" style="color: #<?=$subabs['color']?>; background-color: #<?=$subabs['bgcolor']?>; border: 1px solid #333333; width: 26px; height: 26px; text-align: center; padding-top: 2px;">
                                    <span class="fa fa-<?=$subabs['icon']?>"></span>
                                 </div>
                              </div>
                              <div class="col-lg-3"><?=$subabs['name']?></div>
                              <div class="col-lg-5">
                                 
                              </div>
                              <div class="col-lg-2 text-right">
                                 <button type="button" class="btn btn-danger btn-xs" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteSubAbsence_<?=$subabs['id']?>"><?=$LANG['btn_delete']?></button>
                                 <a href="index.php?action=absenceedit&amp;id=<?=$subabs['id']?>" class="btn btn-warning btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                                 <input name="hidden_id" type="hidden" value="<?=$subabs['id']?>">
                                 <input name="hidden_name" type="hidden" value="<?=$subabs['name']?>">
                              </div>
                           </div>

                           <!-- Modal: Delete SubAbsence -->
                           <?=createModalTop('modalDeleteSubAbsence_'.$subabs['id'], $LANG['modal_confirm'])?>
                              <?=sprintf($LANG['abs_confirm_delete'], $subabs['name'])?>
                           <?=createModalBottom('btn_absDelete', 'success', $LANG['btn_delete_abs'])?>
                           
                        </form>
                     <?php } ?>
                     
                  <?php } 
                  } ?>
                                                   
               </div>
            </div>
               
         </div>
      </div>
