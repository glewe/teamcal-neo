<?php
/**
 * regions.php
 * 
 * Regions page view
 *
 * @category TeamCal Neo 
 * @version 2.2.3
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.roles
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
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
               ?>
               <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['regions_title'].$pageHelp?></div>
               
               <div class="panel-body">

                  <form class="bs-example form-control-horizontal" name="form_create" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-12 text-right">
                              <button type="button" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalCreateRegion"><?=$LANG['btn_create_region']?></button>
                           </div>
                        </div>
                     </div>

                     <!-- Modal: Create region -->
                     <?=createModalTop('modalCreateRegion', $LANG['btn_create_region'])?>
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
                     <?=createModalBottom('btn_regionCreate', 'success', $LANG['btn_create_region'])?>
                     
                  </form>

                  <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                     <li class="active"><a href="#list" data-toggle="tab"><?=$LANG['regions_tab_list']?></a></li>
                     <li><a href="#ical" data-toggle="tab"><?=$LANG['regions_tab_ical']?></a></li>
                     <li><a href="#transfer" data-toggle="tab"><?=$LANG['regions_tab_transfer']?></a></li>
                  </ul>
                  
                  <div id="myTabContent" class="tab-content">

                     <!-- List tab -->
                     <div class="tab-pane fade in active" id="list">
                        <div class="panel panel-default">
                           <div class="panel-body">
                  
                              <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                                 <div class="col-lg-3"><?=$LANG['regions_name']?></div>
                                 <div class="col-lg-6"><?=$LANG['regions_description']?></div>
                                 <div class="col-lg-3 text-right"><?=$LANG['action']?></div>
                              </div>
                                          
                              <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                 <div class="col-lg-3">Default</div>
                                 <div class="col-lg-6">Default Region</div>
                                 <div class="col-lg-3 text-right">
                                    <a href="index.php?action=monthedit&amp;month=<?=date('Y').date('m')?>&amp;region=1" class="btn btn-info btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_calendar']?></a>
                                 </div>
                              </div>
                              
                              <?php foreach ($viewData['regions'] as $region) { 
                                 if ($region['id'] != '1') { ?>
                                    <form  class="bs-example form-control-horizontal" name="form_<?=$region['name']?>" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">
                                       <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                          <div class="col-lg-3"><?=$region['name']?></div>
                                          <div class="col-lg-6"><?=$region['description']?></div>
                                          <div class="col-lg-3 text-right">
                                             <button type="button" class="btn btn-danger btn-xs" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteRegion_<?=$region['name']?>"><?=$LANG['btn_delete']?></button>
                                             <a href="index.php?action=regionedit&amp;id=<?=$region['id']?>" class="btn btn-warning btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                                             <a href="index.php?action=monthedit&amp;month=<?=date('Y').date('m')?>&amp;region=<?=$region['id']?>" class="btn btn-info btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_calendar']?></a>
                                             <input name="hidden_id" type="hidden" value="<?=$region['id']?>">
                                             <input name="hidden_name" type="hidden" value="<?=$region['name']?>">
                                             <input name="hidden_description" type="hidden" value="<?=$region['description']?>">
                                          </div>
                                       </div>
      
                                       <!-- Modal: Delete region -->
                                       <?=createModalTop('modalDeleteRegion_'.$region['name'], $LANG['modal_confirm'])?>
                                          <?=$LANG['regions_confirm_delete'].": ".$region['name']?>
                                       <?=createModalBottom('btn_regionDelete', 'danger', $LANG['btn_delete'])?>
                                       
                                    </form>
                                 <?php } 
                              } ?>
                              
                           </div>
                        </div>
                     </div>
                     
                     <!-- iCal tab -->
                     <div class="tab-pane fade in" id="ical">
                        <div class="panel panel-default">
                           <div class="panel-body">
                              <form class="bs-example form-control-horizontal" name="form_ical" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8" enctype="multipart/form-data">
                                 <?php foreach($viewData['ical'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                                 <div class="form-group">
                                    <label class="col-lg-<?=$colsleft?> control-label">
                                       <?=$LANG['regions_ical_file']?><br>
                                       <span class="text-normal"><?=$LANG['regions_ical_file_comment']?></span>
                                    </label>
                                    <div class="col-lg-<?=$colsright?>">
                                       <input class="form-control" tabindex="<?=$tabindex++?>" accept="text/calendar" name="file_ical" type="file">
                                    </div>
                                 </div>
                                 <div class="divider"><hr></div>
                                 <div class="form-group">
                                    <div class="col-lg-12 text-right">
                                       <button type="submit" class="btn btn-primary btn-sm" tabindex="32" name="btn_uploadIcal"><?=$LANG['btn_upload']?></button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                           
                     <!-- Transfer tab -->
                     <div class="tab-pane fade in" id="transfer">
                        <div class="panel panel-default">
                           <div class="panel-body">
                              <form class="bs-example form-control-horizontal" name="form_merge" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">
                                 <?php foreach($viewData['merge'] as $formObject) {
                                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                 } ?>
                                 <div class="form-group">
                                    <div class="col-lg-12 text-right">
                                       <button type="submit" class="btn btn-primary btn-sm" tabindex="32" name="btn_regionTransfer"><?=$LANG['btn_transfer']?></button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     
                  </div>
               
               </div>
            </div>
               
         </div>
      </div>
