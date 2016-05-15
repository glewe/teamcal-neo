<?php
/**
 * messages.php
 * 
 * Messages page view
 *
 * @category TeamCal Neo 
 * @version 0.5.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.messages 
      -->
      <div class="container content">
      
         <div class="col-lg-12">
         
            <?php $tabindex = 1;?>
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['msg_title']." ".$UL->firstname." ".$UL->lastname?></div>
               <div class="panel-body">
                  
                  <?php if (!empty($msgData)) { ?>
                  <div class="panel panel-default">
                     <div class="panel-body">
                        <form class="bs-example form-control-horizontal" action="index.php?action=<?=$controller?>" method="post" target="_self" accept-charset="utf-8">
                           <button class="btn btn-success" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalConfirmAll"><?=$LANG['btn_confirm_all']?></button>
                           <button class="btn btn-danger" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDeleteAll"><?=$LANG['btn_delete_all']?></button>
                           
                           <!-- Modal: Confirm all -->
                           <?=createModalTop('modalConfirmAll', $LANG['modal_confirm'])?>
                              <?=$LANG['msg_confirm_all_confirm']?>
                           <?=createModalBottom('btn_confirm_all', 'success', $LANG['btn_confirm_all'])?>
                           
                           <!-- Modal: Delete all -->
                           <?=createModalTop('modalDeleteAll', $LANG['modal_confirm'])?>
                              <?=$LANG['msg_delete_all_confirm']?>
                           <?=createModalBottom('btn_delete_all', 'danger', $LANG['btn_delete_all'])?>
                           
                        </form>
                     </div>
                  </div>
                  <?php } ?>
                  
                  <?php foreach ($msgData as $msg) { ?>
                  <form class="bs-example form-control-horizontal" action="index.php?action=messages" method="post" target="_self" accept-charset="utf-8">
                     <div class="alert alert-<?=$msg['type']?>">
                        <input name="msgId" type="hidden" class="text" value="<?=$msg['id']?>">
                        <button class="btn btn-danger btn-xs pull-right" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalDelete-<?=$msg['id']?>"><?=$LANG['btn_delete']?></button>
                        <?php if ($msg['popup']) { ?><button class="btn btn-success btn-xs pull-right" style="margin-right: 4px;" tabindex="<?=$tabindex++;?>" data-toggle="modal" data-target="#modalConfirm-<?=$msg['id']?>"><?=$LANG['btn_confirm']?></button><?php } ?>
                        <h4><strong><?=$msg['timestamp']?></strong></h4>
                        <hr>
                        <p><?=$msg['text']?></p>
                     </div>
                     
                     <!-- Modal: Confirm -->
                     <?=createModalTop('modalConfirm-'.$msg['id'], $LANG['modal_confirm'])?>
                        <?=sprintf($LANG['msg_confirm_confirm'], $msg['id'])?>
                     <?=createModalBottom('btn_confirm', 'success', $LANG['btn_confirm'])?>
                     
                     <!-- Modal: Delete -->
                     <?=createModalTop('modalDelete-'.$msg['id'], $LANG['modal_confirm'])?>
                        <?=sprintf($LANG['msg_delete_confirm'], $msg['id'])?>
                     <?=createModalBottom('btn_delete', 'danger', $LANG['btn_delete'])?>
                     
                  </form>
                  <?php } ?>
                           
               </div>
            </div>
            
         </div>
         
      </div>

