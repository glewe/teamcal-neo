<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Roles View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
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

            <div class="card">
               <?php 
               $pageHelp = '';
               if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
               ?>
               <div class="card-header text-white bg-<?=$CONF['controllers'][$controller]->panelColor?>"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['roles_title'].$pageHelp?></div>
               
               <div class="card-body">

                  <form class="form-control-horizontal" name="form_create" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">
                     <div class="card">
                        <div class="card-body row">
                           <div class="col-lg-4">
                              <label for="inputSearch"><?=$LANG['search']?></label>
                              <input id="inputSearch" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_searchRole" maxlength="40" value="<?=$viewData['searchRole']?>" type="text">
                           </div>
                           <div class="col-lg-3">
                              <br>
                              <button type="submit" class="btn btn-secondary" tabindex="<?=$tabindex++;?>" name="btn_search"><?=$LANG['btn_search']?></button>
                              <a href="index.php?action=roles" class="btn btn-secondary" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_reset']?></a>
                           </div>
                           <div class="col-lg-5 text-right">
                              <br>
                              <button type="button" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalCreateRole"><?=$LANG['btn_create_role']?></button>
                           </div>
                        </div>
                     </div>
                     <div style="height:20px;"></div>
                     
                     <!-- Modal: Create role -->
                     <?=createModalTop('modalCreateRole', $LANG['btn_create_role'])?>
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
                     <?=createModalBottom('btn_roleCreate', 'success', $LANG['btn_create_role'])?>
                     
                  </form>
            
                  <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight:bold;">
                     <div class="col-lg-3"><?=$LANG['roles_name']?></div>
                     <div class="col-lg-7"><?=$LANG['roles_description']?></div>
                     <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                  </div>
                              
                  <?php foreach ($viewData['roles'] as $role) { ?>
                  <form  class="form-control-horizontal" name="form_<?=$role['name']?>" action="index.php?action=roles" method="post" target="_self" accept-charset="utf-8">
                     <div class="row">
                        <div class="col-lg-3"><span class="fas fa-user-circle fa-lg text-<?=$role['color']?>" style="margin-right: 8px;"></span><?=$role['name']?></div>
                        <div class="col-lg-7"><?=$role['description']?></div>
                        <div class="col-lg-2 text-right">
                           <?php
                           $protectedRoles = array(1, 2, 3);
                           if (!in_array($role['id'], $protectedRoles)) { ?>
                           <button type="button" class="btn btn-danger btn-sm" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteRole_<?=$role['id']?>"><?=$LANG['btn_delete']?></button>
                           <input name="hidden_id" type="hidden" value="<?=$role['id']?>">
                           <input name="hidden_name" type="hidden" value="<?=$role['name']?>">
                           <input name="hidden_description" type="hidden" value="<?=$role['description']?>">
                           <?php } ?>
                           <a href="index.php?action=roleedit&amp;id=<?=$role['id']?>" class="btn btn-warning btn-sm" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                        </div>
                     </div>
                     
                     <!-- Modal: Delete role -->
                     <?=createModalTop('modalDeleteRole_'.$role['id'], $LANG['modal_confirm'])?>
                        <?=$LANG['roles_confirm_delete'].$role['name']?> ?
                     <?=createModalBottom('btn_roleDelete', 'danger', $LANG['btn_delete_role'])?>
                     
                  </form>
                  <?php } ?>
                                    
               </div>
            </div>
               
         </div>
      </div>
