<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Absence Types View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2022 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

<!-- ==================================================================== 
view.absences
-->
<div class="container content">

    <div class="col-lg-12">
        <?php
        if ($showAlert and $C->read("showAlerts") != "none") {
            if (
                $C->read("showAlerts") == "all" or
                $C->read("showAlerts") == "warnings" and ($alertData['type'] == "warning" or $alertData['type'] == "danger")
            ) {
                echo createAlertBox($alertData);
            }
        } ?>
        <?php $tabindex = 1;
        $colsleft = 8;
        $colsright = 4; ?>

        <div class="card">
            <?php
            $pageHelp = '';
            if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
            ?>
            <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['abs_list_title'] ?><?= $pageHelp ?></div>

            <div class="card-body">

                <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn btn-success float-right" tabindex="<?= $tabindex++; ?>" data-toggle="modal" data-target="#modalCreateAbsence"><?= $LANG['btn_create_abs'] ?></button>
                        </div>
                    </div>
                    <div style="height:20px;"></div>

                    <!-- Modal: Creates Absence -->
                    <?= createModalTop('modalCreateAbsence', $LANG['btn_create_abs']) ?>
                    <label for="inputName"><?= $LANG['name'] ?></label>
                    <input id="inputName" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_name" maxlength="40" value="<?= $viewData['txt_name'] ?>" type="text">
                    <?php if (isset($inputAlert["name"]) and strlen($inputAlert["name"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><?= $inputAlert["name"] ?></div>
                    <?php } ?>
                    <?= createModalBottom('btn_absCreate', 'success', $LANG['btn_create_abs']) ?>

                </form>

                <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                    <div class="col-lg-1"><?= $LANG['abs_display'] ?></div>
                    <div class="col-lg-4"><?= $LANG['abs_name'] ?></div>
                    <div class="col-lg-5"><?= $LANG['options'] ?></div>
                    <div class="col-lg-2 text-right"><?= $LANG['action'] ?></div>
                </div>

                <?php foreach ($viewData['absences'] as $absence) {
                    if (!$absence['counts_as']) { ?>
                        <form class="form-control-horizontal" name="form_<?= $absence['id'] ?>" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
                            <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                <div class="col-lg-1">
                                    <?php if ($absence['bgtrans']) $bgstyle = "";
                                    else $bgstyle = "background-color: #" . $absence['bgcolor'] . ";"; ?>
                                    <div style="color: #<?= $absence['color'] ?>;<?= $bgstyle ?>border: 1px solid #333333; width: 26px; height: 26px; text-align: center; padding-top: 2px;">
                                        <?php if ($absence['icon'] != "No") { ?>
                                            <span class="<?= $absence['icon'] ?>"></span>
                                        <?php } else { ?>
                                            <?= $absence['symbol'] ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-lg-4"><?= $absence['name'] ?></div>
                                <div class="col-lg-5">
                                    <?= (($absence['approval_required']) ? '<i data-placement="top" data-type="info" data-toggle="tooltip" title="' . $LANG['abs_approval_required'] . '"><i class="far fa-edit fa-lg text-danger"></i></i>' : '') ?>
                                    <?= (($absence['manager_only']) ? '<i data-placement="top" data-type="info" data-toggle="tooltip" title="' . $LANG['abs_manager_only'] . '"><i class="fas fa-user-circle fa-lg text-warning"></i></i>' : '') ?>
                                    <?= (($absence['hide_in_profile']) ? '<i data-placement="top" data-type="info" data-toggle="tooltip" title="' . $LANG['abs_hide_in_profile'] . '"><i class="far fa-eye-slash fa-lg text-info"></i></i>' : '') ?>
                                    <?= (($absence['confidential']) ? '<i data-placement="top" data-type="info" data-toggle="tooltip" title="' . $LANG['abs_confidential'] . '"><i class="fas fa-exclamation-circle fa-lg text-success"></i></i>' : '') ?>
                                    <?= (($absence['allowmonth'] or ($absence['allowweek'])) ? '<i data-placement="top" data-type="info" data-toggle="tooltip" title="' . $LANG['abs_allow_active'] . '"><i class="far fa-hand-paper fa-lg text-warning"></i></i>' : '') ?>
                                </div>
                                <div class="col-lg-2 text-right">
                                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++; ?>" data-toggle="modal" data-target="#modalDeleteAbsence_<?= $absence['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                                    <a href="index.php?action=absenceedit&amp;id=<?= $absence['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_edit'] ?></a>
                                    <input name="hidden_id" type="hidden" value="<?= $absence['id'] ?>">
                                    <input name="hidden_name" type="hidden" value="<?= $absence['name'] ?>">
                                </div>
                            </div>

                            <!-- Modal: Delete Absence -->
                            <?= createModalTop('modalDeleteAbsence_' . $absence['id'], $LANG['modal_confirm']) ?>
                            <?= sprintf($LANG['abs_confirm_delete'], $absence['name']) ?>
                            <?= createModalBottom('btn_absDelete', 'success', $LANG['btn_delete_abs']) ?>

                        </form>

                        <?php
                        $subabsences = $A->getAllSub($absence['id']);
                        foreach ($subabsences as $subabs) { ?>
                            <form class="form-control-horizontal" name="form_<?= $subabs['id'] ?>" action="index.php?action=absences" method="post" target="_self" accept-charset="utf-8">
                                <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                                    <div class="col-lg-1 text-right"><i class="fas fa-angle-double-right"></i></div>
                                    <div class="col-lg-1">
                                        <div style="color: #<?= $subabs['color'] ?>; background-color: #<?= $subabs['bgcolor'] ?>; border: 1px solid #333333; width: 26px; height: 26px; text-align: center; padding-top: 2px;">
                                            <span class="<?= $subabs['icon'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3"><?= $subabs['name'] ?></div>
                                    <div class="col-lg-5">

                                    </div>
                                    <div class="col-lg-2 text-right">
                                        <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++; ?>" data-toggle="modal" data-target="#modalDeleteSubAbsence_<?= $subabs['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                                        <a href="index.php?action=absenceedit&amp;id=<?= $subabs['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_edit'] ?></a>
                                        <input name="hidden_id" type="hidden" value="<?= $subabs['id'] ?>">
                                        <input name="hidden_name" type="hidden" value="<?= $subabs['name'] ?>">
                                    </div>
                                </div>

                                <!-- Modal: Delete SubAbsence -->
                                <?= createModalTop('modalDeleteSubAbsence_' . $subabs['id'], $LANG['modal_confirm']) ?>
                                <?= sprintf($LANG['abs_confirm_delete'], $subabs['name']) ?>
                                <?= createModalBottom('btn_absDelete', 'success', $LANG['btn_delete_abs']) ?>

                            </form>
                        <?php } ?>

                <?php }
                } ?>

            </div>
        </div>

    </div>
</div>