<?php
/**
 * users.php
 * 
 * Users page view
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
      view.users
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
            
            <form  class="bs-example form-control-horizontal" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">

               <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
                  <?php 
                  $pageHelp = '';
                  if ($C->read('pageHelp')) $pageHelp = '<a href="'.$CONF['controllers'][$controller]->docurl.'" target="_blank" class="pull-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                  ?>
                  <div class="panel-heading"><i class="<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-header"></i><?=$LANG['users_title'].$pageHelp?></div>
                  
                  <div class="panel-body">

                     <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="col-lg-3">
                              <label for="inputSearch"><?=$LANG['search']?></label>
                              <input id="inputSearch" class="form-control" tabindex="<?=$tabindex++;?>" name="txt_searchUser" maxlength="40" value="<?=$viewData['searchUser']?>" type="text">
                           </div>
                           <div class="col-lg-2">
                              <label for="inputSearch"><?=$LANG['group']?></label>
                              <select class="form-control" name="sel_searchGroup" tabindex="<?=$tabindex++?>">
                                 <option value="All"<?=('All'==$viewData['searchGroup'])?' selected=""':'';?>><?=$LANG['all']?></option>
                                 <?php foreach ($viewData['groups'] as $group) { ?>
                                    <option value="<?=$group['id']?>"<?=($group['id']==$viewData['searchGroup'])?' selected=""':'';?>><?=$group['name']?></option>
                                 <?php } ?>
                              </select>
                           </div>
                           <div class="col-lg-2">
                              <label for="inputSearch"><?=$LANG['role']?></label>
                              <select class="form-control" name="sel_searchRole" tabindex="<?=$tabindex++?>">
                                 <option value="All"<?=('All'==$viewData['searchRole'])?' selected=""':'';?>><?=$LANG['all']?></option>
                                 <?php foreach ($viewData['roles'] as $role) { ?>
                                    <option value="<?=$role['id']?>"<?=($role['id']==$viewData['searchRole'])?' selected=""':'';?>><?=$role['name']?></option>
                                 <?php } ?>
                              </select>
                           </div>
                           <div class="col-lg-5 text-right">
                              <br>
                              <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_search"><?=$LANG['btn_search']?></button>
                              <button type="submit" class="btn btn-default" tabindex="<?=$tabindex++;?>" name="btn_reset"><?=$LANG['btn_reset']?></button>
                              <a href="index.php?action=useradd" class="btn btn-success" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_create_user']?></a>
                              <a href="index.php?action=userimport" class="btn btn-info" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_import']?></a>
                           </div>
                        </div>
                     </div>
               
                     <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <li class="active"><a href="#tabActive" data-toggle="tab"><?=$LANG['users_tab_active']?></a></li>
                        <li><a href="#tabArchived" data-toggle="tab"><?=$LANG['users_tab_archived']?></a></li>
                     </ul>
                     
                     <div id="myTabContent" class="tab-content">
                        
                        <!-- Active tab -->
                        <div class="tab-pane fade active in" id="tabActive">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                                    <div class="col-lg-4"><?=$LANG['users_user']?></div>
                                    <div class="col-lg-2"><?=$LANG['users_attributes']?></div>
                                    <div class="col-lg-2"><?=$LANG['users_created']?></div>
                                    <div class="col-lg-2"><?=$LANG['users_last_login']?></div>
                                    <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                                 </div>
                                 
                                 <?php foreach ($viewData['users'] as $user) { ?>
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                    <div class="col-lg-4">
                                       <?php if ($user['username']!="admin") {?>
                                          <input type="checkbox" name="chk_userActive[]" value="<?=$user['username']?>">&nbsp;&nbsp;
                                       <?php } else { ?>
                                          <span style="padding-left: 16px;">&nbsp;</span>
                                       <?php } ?>
                                       <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<img src='<?=APP_AVATAR_DIR.$UO->read($user['username'],'avatar')?>' style='width: 80px; height: 80px;'>"><img src="<?=APP_AVATAR_DIR?>/<?=$UO->read($user['username'],'avatar')?>" alt="" style="width: 16px; height: 16px;"></i>&nbsp;&nbsp;<?=$user['dispname']?>
                                    </div>
                                    <div class="col-lg-2">
                                       <a href="#" data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['role']?>: <?=$user['role']?>"><i class="fas fa-user-circle fa-sm text-<?=$user['color']?>"></i></a>
                                       <?=(($user['locked'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_locked'].'"><i class="fas fa-lock fa-sm text-danger"></i></i>':'')?>
                                       <?=(($user['onhold'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_onhold'].'"><i class="far fa-clock fa-sm text-warning"></i></i>':'')?>
                                       <?=(($user['verify'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_verify'].'"><i class="fas fa-exclamation-circle fa-sm text-success"></i></i>':'')?>
                                       <?=(($user['hidden'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_hidden'].'"><i class="far fa-eye-slash fa-sm text-info"></i></i>':'')?>
                                    </div>
                                    <div class="col-lg-2"><?=$user['created']?></div>
                                    <div class="col-lg-2"><?=(($user['last_login']!=DEFAULT_TIMESTAMP)?$user['last_login']:"")?></div>
                                    <div class="col-lg-2 text-right">
                                       <a href="index.php?action=viewprofile&amp;profile=<?=$user['username']?>" class="btn btn-default btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_view']?></a>
                                       <a href="index.php?action=useredit&amp;profile=<?=$user['username']?>" class="btn btn-warning btn-xs" tabindex="<?=$tabindex++;?>"><?=$LANG['btn_edit']?></a>
                                    </div>
                                 </div>
                                 <?php } ?>
                                 
                                 <div class="col-lg-12" style="margin-top: 10px; padding-bottom: 0px;">
                                    <div class="col-lg-2">
                                       <div class="checkbox"><label><input type="checkbox" name="chk_selectAllActive" id="chk_selectAllActive"><?=$LANG['select_all']?></label></div>
                                    </div>
                                    <div class="col-lg-10">
                                       
                                       <button type="button" class="btn btn-primary" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalResetPassword"><?=$LANG['btn_reset_password_selected']?></button>
                                       <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalArchiveSelected"><?=$LANG['btn_archive_selected']?></button>
                                       <button type="button" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteSelected"><?=$LANG['btn_delete_selected']?></button>
                                       <button type="button" class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalActivateSelected"><?=$LANG['btn_activate_selected']?></button>
                                       
                                       <!-- Modal: Activate selected -->
                                       <?=createModalTop('modalActivateSelected', $LANG['modal_confirm'])?>
                                          <?=$LANG['users_confirm_activate']?>
                                       <?=createModalBottom('btn_userActivate', 'success', $LANG['btn_activate_selected'])?>
                                       
                                       <!-- Modal: Archive selected -->
                                       <?=createModalTop('modalArchiveSelected', $LANG['modal_confirm'])?>
                                          <?=$LANG['users_confirm_archive']?>
                                       <?=createModalBottom('btn_userArchive', 'warning', $LANG['btn_archive_selected'])?>
                                       
                                       <!-- Modal: Delete selected -->
                                       <?=createModalTop('modalDeleteSelected', $LANG['modal_confirm'])?>
                                          <?=$LANG['users_confirm_delete']?>
                                       <?=createModalBottom('btn_profileDelete', 'danger', $LANG['btn_delete_selected'])?>
                                       
                                       <!-- Model: Reset password -->
                                       <?=createModalTop('modalResetPassword', $LANG['modal_confirm'])?>
                                          <?=$LANG['users_confirm_password']?>
                                       <?=createModalBottom('btn_userResetPassword', 'primary', $LANG['btn_reset_password_selected'])?>
                                       
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>
                        </div>
                        
                        <!-- Archived tab -->
                        <div class="tab-pane fade" id="tabArchived">
                           <div class="panel panel-default">
                              <div class="panel-body">
                              
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                                    <div class="col-lg-4"><?=$LANG['users_user']?></div>
                                    <div class="col-lg-2"><?=$LANG['users_attributes']?></div>
                                    <div class="col-lg-2"><?=$LANG['users_created']?></div>
                                    <div class="col-lg-2"><?=$LANG['users_last_login']?></div>
                                    <div class="col-lg-2 text-right"><?=$LANG['action']?></div>
                                 </div>
                                 
                                 <?php foreach ($viewData['users1'] as $user1) { ?>
                                 <div class="col-lg-12" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                    <div class="col-lg-4">
                                       <?php if ($user1['username']!="admin") {?><input type="checkbox" name="chk_userArchived[]" value="<?=$user1['username']?>">&nbsp;&nbsp;<?php }?>
                                       <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<img src='<?=APP_AVATAR_DIR.$UO->read($user1['username'],'avatar',true)?>' style='width: 80px; height: 80px;'>"><img src="<?=APP_AVATAR_DIR?>/<?=$UO->read($user1['username'],'avatar',true)?>" alt="" style="width: 16px; height: 16px;"></i>&nbsp;&nbsp;<?=$user1['dispname']?>
                                    </div>
                                    <div class="col-lg-2">
                                       <i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="<?=$LANG['role']?>: <?=$LANG['role_'.$user1['role']]?>"><i class="fas fa-user-circle fa-sm text-<?=$user1['color']?>"></i></i>
                                       <?=(($user1['locked'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_locked'].'"><i class="fas fa-lock fa-sm text-danger"></i></i>':'')?>
                                       <?=(($user1['onhold'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_onhold'].'"><i class="far fa-clock fa-sm text-warning"></i></i>':'')?>
                                       <?=(($user1['verify'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_verify'].'"><i class="fas fa-exclamation-circle fa-sm text-success"></i></i>':'')?>
                                       <?=(($user1['hidden'])?'<i data-position="tooltip-top" class="tooltip-warning" data-toggle="tooltip" data-title="'.$LANG['users_attribute_hidden'].'"><i class="far fa-eye-slash fa-sm text-info"></i></i>':'')?>
                                    </div>
                                    <div class="col-lg-2"><?=$user1['created']?></div>
                                    <div class="col-lg-2"><?=$user1['last_login']?></div>
                                 </div>
                                 <?php } ?>
                                 
                                 <div class="col-lg-12" style="margin-top: 10px; padding-bottom: 0px;">
                                    <div class="col-lg-2">
                                       <div class="checkbox"><label><input type="checkbox" name="chk_selectAllArchived" id="chk_selectAllArchived"><?=$LANG['select_all']?></label></div>
                                    </div>
                                    <div class="col-lg-10">
                                       
                                       <button type="button" class="btn btn-warning" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalRestoreArchived"><?=$LANG['btn_restore_selected']?></button>
                                       <button type="button" class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteArchived"><?=$LANG['btn_delete_selected']?></button>
                                       
                                       <!-- Modal: Delete archived -->
                                       <?=createModalTop('modalDeleteArchived', $LANG['modal_confirm'])?>
                                          <?=$LANG['users_confirm_delete']?>
                                       <?=createModalBottom('btn_profileDeleteArchived', 'danger', $LANG['btn_delete_selected'])?>
                                       
                                       <!-- Modal: Restore archived -->
                                       <?=createModalTop('modalRestoreArchived', $LANG['modal_confirm'])?>
                                          <?=$LANG['users_confirm_restore']?>
                                       <?=createModalBottom('btn_profileRestore', 'warning', $LANG['btn_restore_selected'])?>
                                       
                                    </div>
                                 </div>
                              
                              </div>
                           </div>
                        </div>
                        
                     </div>
                  
                  </div>
               </div>
               
            </form>
         </div>
      </div>

      <script>
      $('#chk_selectAllActive').click(function(event) {   
         if(this.checked) {
            $(":checkbox[name='chk_userActive[]']").each(function() {
               this.checked = true;                        
            });
         }
         else {
            $(":checkbox[name='chk_userActive[]']").each(function() {
               this.checked = false;                        
            });
         }
      });
      $('#chk_selectAllArchived').click(function(event) {   
         if(this.checked) {
            $(":checkbox[name='chk_userArchived[]']").each(function() {
               this.checked = true;                        
            });
         }
         else {
            $(":checkbox[name='chk_userArchived[]']").each(function() {
               this.checked = false;                        
            });
         }
      });
      </script>
      