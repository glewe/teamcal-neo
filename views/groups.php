<?php
/**
 * groups.php
 * 
 * Groups page view
 *
 * @category TeamCal Neo 
 * @version 0.5.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.groups
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
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['groups_title']?></div>
               
               <div class="panel-body">

                  <form class="bs-example form-control-horizontal" name="form_create" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">
                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-4">
                              <label for="inputSearch"><?=$LANG['search']?></label>
                              <input id="inputSearch" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_searchGroup" maxlength="40" value="<?=$viewData['searchGroup']?>" type="text">
                           </div>
                           <div class="col-lg-3">
                              <br>
                              <button type="submit" class="btn btn-default" tabindex="<?=$tabindex++;?>" name="btn_search"><?=$LANG['btn_search']?></button>
                              <a href="index.php?action=groups" class="btn btn-default" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_reset']?></a>
                           </div>
                           <div class="col-lg-5 text-right">
                              <br>
                              <button type="submit" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalCreateGroup"><?=$LANG['btn_create_group']?></button>
                           </div>
                        </div>
                     </div>
                     
                     <!-- Modal: Create group -->
                     <?=createModalTop('modalCreateGroup', $LANG['btn_create_group'])?>
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
                     <?=createModalBottom('btn_groupCreate', 'success', $LANG['btn_create_group'])?>
                     
                  </form>
            
                  <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                     <div class="col-lg-3"><?=$LANG['groups_name']?></div>
                     <div class="col-lg-7"><?=$LANG['groups_description']?></div>
                     <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                  </div>
                              
                  <?php foreach ($viewData['groups'] as $group) { ?>
                  <form  class="bs-example form-control-horizontal" name="form_<?=$group['id']?>" action="index.php?action=groups" method="post" target="_self" accept-charset="utf-8">
                     <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                        <div class="col-lg-3"><?=$group['name']?></div>
                        <div class="col-lg-7"><?=$group['description']?></div>
                        <div class="col-lg-2 text-right">
                           <button type="submit" class="btn btn-danger btn-xs" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteGroup_<?=$group['id']?>"><?=$LANG['btn_delete']?></button>
                           <a href="index.php?action=groupedit&amp;id=<?=$group['id']?>" class="btn btn-warning btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                           <input name="hidden_id" type="hidden" value="<?=$group['id']?>">
                           <input name="hidden_name" type="hidden" value="<?=$group['name']?>">
                           <input name="hidden_description" type="hidden" value="<?=$group['description']?>">
                        </div>
                     </div>
                     
                     <!-- Modal: Delete group -->
                     <?=createModalTop('modalDeleteGroup_'.$group['id'], $LANG['modal_confirm'])?>
                        <?=$LANG['groups_confirm_delete'].$group['name']?> ?
                     <?=createModalBottom('btn_groupDelete', 'danger', $LANG['btn_delete_group'])?>
                     
                  </form>
                  <?php } ?>
                                    
               </div>
            </div>
               
         </div>
      </div>
