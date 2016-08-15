<?php
/**
 * messageedit.php
 * 
 * Message editor view
 *
 * @category TeamCal Neo 
 * @version 0.9.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.messageedit
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
                  <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['msg_title_edit']?></div>
                  <div class="panel-body">

                     <!-- Message type -->
                     <div class="form-group">
                        <label class="col-lg-<?=$colsleft?> control-label">
                           <?=$LANG['msg_type']?><br>
                           <span class="text-normal"><?=$LANG['msg_type_desc']?></span>
                        </label>
                        <div class="col-lg-<?=$colsright?>">
                           <div class="radio"><label><input name="opt_msgtype" value="email" tabindex="<?=$tabindex++?>" <?=($viewData['msgtype']=='email')?"checked":"";?> type="radio"><?=$LANG['msg_type_email']?></label></div>
                           <div class="radio"><label><input name="opt_msgtype" value="silent" tabindex="<?=$tabindex++?>" <?=($viewData['msgtype']=='silent')?"checked":"";?> type="radio"><?=$LANG['msg_type_silent']?></label></div>
                           <div class="radio"><label><input name="opt_msgtype" value="popup" tabindex="<?=$tabindex++?>" <?=($viewData['msgtype']=='popup')?"checked":"";?> type="radio"><?=$LANG['msg_type_popup']?></label></div>
                        </div>
                     </div>
                     <div class="divider"><hr></div>

                     <!-- Content type -->
                     <div class="form-group">
                        <label class="col-lg-<?=$colsleft?> control-label">
                           <?=$LANG['msg_content_type']?><br>
                           <span class="text-normal"><?=$LANG['msg_content_type_desc']?></span>
                        </label>
                        <div class="col-lg-<?=$colsright?>">
                           <div class="radio"><label><input name="opt_contenttype" value="primary" tabindex="<?=$tabindex++?>" <?=($viewData['contenttype']=='primary')?"checked":"";?> type="radio"><span class="label label-primary"><?=$LANG['msg_content_type_primary']?></span></label></div>
                           <div class="radio"><label><input name="opt_contenttype" value="info" tabindex="<?=$tabindex++?>"  <?=($viewData['contenttype']=='info')?"checked":"";?> type="radio"><span class="label label-info"><?=$LANG['msg_content_type_info']?></span></label></div>
                           <div class="radio"><label><input name="opt_contenttype" value="success" tabindex="<?=$tabindex++?>" <?=($viewData['contenttype']=='success')?"checked":"";?> type="radio"><span class="label label-success"><?=$LANG['msg_content_type_success']?></span></label></div>
                           <div class="radio"><label><input name="opt_contenttype" value="warning" tabindex="<?=$tabindex++?>" <?=($viewData['contenttype']=='warning')?"checked":"";?> type="radio"><span class="label label-warning"><?=$LANG['msg_content_type_warning']?></span></label></div>
                           <div class="radio"><label><input name="opt_contenttype" value="danger" tabindex="<?=$tabindex++?>" <?=($viewData['contenttype']=='danger')?"checked":"";?> type="radio"><span class="label label-danger"><?=$LANG['msg_content_type_danger']?></span></label></div>
                        </div>
                     </div>
                     <div class="divider"><hr></div>
                           
                     <!-- Send to -->
                     <div class="form-group">
                        <label class="col-lg-<?=$colsleft?> control-label">
                           <?=$LANG['msg_sendto']?><br>
                           <span class="text-normal"><?=$LANG['msg_sendto_desc']?></span>
                        </label>
                        <div class="col-lg-<?=$colsright?>">
                           <div class="radio"><label><input name="opt_sendto" value="all" tabindex="<?=$tabindex++?>" <?=($viewData['sendto']=='all')?"checked":"";?> type="radio"><?=$LANG['msg_sendto_all']?></label></div>
                           
                           <div class="radio"><label><input name="opt_sendto" value="group" tabindex="<?=$tabindex++?>" <?=($viewData['sendto']=='group')?"checked":"";?> type="radio"><?=$LANG['msg_sendto_group']?></label></div>
                           <select class="form-control" name="sel_sendToGroup[]" multiple="multiple" size="5" tabindex="<?=$tabindex++?>">
                              <?php foreach ($viewData['groups'] as $group) { ?>
                                 <option value="<?=$group?>" <?=(in_array($group,$viewData['sendToGroup']))?"selected":"";?>><?=$group?></option>
                              <?php } ?>
                           </select>
                           
                           <div class="radio"><label><input name="opt_sendto" value="user" tabindex="<?=$tabindex++?>" <?=($viewData['sendto']=='user')?"checked":"";?> type="radio"><?=$LANG['msg_sendto_user']?></label></div>
                           <select class="form-control" name="sel_sendToUser[]" multiple="multiple" size="5" tabindex="<?=$tabindex++?>">
                           <?php foreach ($viewData['users'] as $user) {
                              if ( $user['firstname']!="" ) $showname = $user['lastname'].", ".$user['firstname'];
                              else $showname = $user['lastname']; ?>
                              <option class="option" value="<?=$user['username']?>" <?=(in_array($user['username'],$viewData['sendToUser']))?"selected":"";?>><?=$showname?></option>
                           <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="divider"><hr></div>

                     <!-- Subject -->
                     <div class="form-group">
                        <label class="col-lg-6 control-label">
                           <?=$LANG['msg_msg_title']?><br>
                           <span class="text-normal"><?=$LANG['msg_msg_title_comment']?></span>
                        </label>
                        <div class="col-lg-6">
                           <input class="form-control" tabindex="<?=$tabindex++?>" name="txt_subject" maxlength="160" value="<?=$viewData['subject']?>" type="text" placeholder="<?=$LANG['msg_msg_title_sample']?>">
                        </div>
                     </div>
                     <div class="divider"><hr></div>

                     <!-- Body -->
                     <div class="form-group">
                        <label class="col-lg-6 control-label">
                           <?=$LANG['msg_msg_body']?><br>
                           <span class="text-normal"><?=$LANG['msg_msg_body_comment']?></span>
                        </label>
                        <div class="col-lg-6">
                           <textarea class="form-control" tabindex="<?=$tabindex++?>" name="txt_text" rows="8" placeholder="<?=$LANG['msg_msg_body_sample']?>"><?=$viewData['text']?></textarea>
                        </div>
                     </div>
                     <div class="divider"><hr></div>

                     <!-- Captcha -->
                     <div class="form-group">
                        <label for="inputCode" class="col-lg-<?=$colsleft?> control-label">
                           <?=$LANG['msg_code']?><br>
                           <span class="text-normal"><?=$LANG['msg_code_comment']?></span>
                        </label>
                        <div class="col-lg-<?=$colsright?>">
                           <img id="captcha" src="addons/securimage/securimage_show.php" alt="CAPTCHA Image"><br>
                           [<a href="#" onclick="document.getElementById('captcha').src = 'addons/securimage/securimage_show.php?' + Math.random(); return false"><?=$LANG['msg_code_new']?></a>]
                           <input class="form-control" name="txt_code" id="inputCode" placeholder="Code" tabindex="<?=$tabindex++?>" type="text" maxlength="6">
                        </div>
                     </div>
                     <div class="divider"><hr></div>
                     
                  </div>
               </div>

               <div class="panel panel-default">
                  <div class="panel-body">
                     <button type="submit" class="btn btn-primary" tabindex="<?=$tabindex++;?>" name="btn_send"><?=$LANG['btn_send']?></button>
                  </div>
               </div>
               
            </form>
            
         </div>
         
      </div>      
            