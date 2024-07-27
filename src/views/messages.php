<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * Messages View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

<!-- ==================================================================== 
view.messages 
-->
<div class="container content">

    <div class="col-lg-12">

        <?php $tabindex = 1; ?>

        <div class="card">
            <?php
            $pageHelp = '';
            if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
            ?>
            <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['msg_title'] . " " . $UL->firstname . " " . $UL->lastname . $pageHelp ?></div>
            <div class="card-body">

                <?php if (!empty($msgData)) { ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
                                <button type="button" class="btn btn-success" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalConfirmAll"><?= $LANG['btn_confirm_all'] ?></button>
                                <button type="button" class="btn btn-danger" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteAll"><?= $LANG['btn_delete_all'] ?></button>

                                <!-- Modal: Confirm all -->
                                <?= createModalTop('modalConfirmAll', $LANG['modal_confirm']) ?>
                                <?= $LANG['msg_confirm_all_confirm'] ?>
                                <?= createModalBottom('btn_confirm_all', 'success', $LANG['btn_confirm_all']) ?>

                                <!-- Modal: Delete all -->
                                <?= createModalTop('modalDeleteAll', $LANG['modal_confirm']) ?>
                                <?= $LANG['msg_delete_all_confirm'] ?>
                                <?= createModalBottom('btn_delete_all', 'danger', $LANG['btn_delete_all']) ?>

                            </form>
                        </div>
                    </div>
                    <div style="height:20px;"></div>
                <?php } ?>

                <?php foreach ($msgData as $msg) { ?>
                    <form class="form-control-horizontal" action="index.php?action=messages" method="post" target="_self" accept-charset="utf-8">
                        <div class="alert alert-<?= $msg['type'] ?>">
                            <input name="msgId" type="hidden" class="text" value="<?= $msg['id'] ?>">
                            <button type="button" class="btn btn-danger btn-sm float-end" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDelete-<?= $msg['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                            <?php if ($msg['popup']) { ?><button type="button" class="btn btn-success btn-sm float-end" style="margin-right: 4px;" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalConfirm-<?= $msg['id'] ?>"><?= $LANG['btn_confirm'] ?></button><?php } ?>
                            <h5><?= $msg['timestamp'] ?></h5>
                            <hr>
                            <p><?= $msg['text'] ?></p>
                        </div>

                        <!-- Modal: Confirm -->
                        <?= createModalTop('modalConfirm-' . $msg['id'], $LANG['modal_confirm']) ?>
                        <?= sprintf($LANG['msg_confirm_confirm'], $msg['id']) ?>
                        <?= createModalBottom('btn_confirm', 'success', $LANG['btn_confirm']) ?>

                        <!-- Modal: Delete -->
                        <?= createModalTop('modalDelete-' . $msg['id'], $LANG['modal_confirm']) ?>
                        <?= sprintf($LANG['msg_delete_confirm'], $msg['id']) ?>
                        <?= createModalBottom('btn_delete', 'danger', $LANG['btn_delete']) ?>

                    </form>
                <?php } ?>

            </div>
        </div>

    </div>

</div>
