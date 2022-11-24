<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Holidays View
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
view.holidays
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
            if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
            ?>
            <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['hol_list_title'] . $pageHelp ?></div>

            <div class="card-body">

                <form class="form-control-horizontal" name="form_create" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn btn-success float-end" tabindex="<?= $tabindex++; ?>" data-bs-toggle="modal" data-bs-target="#modalCreateHoliday"><?= $LANG['btn_create_holiday'] ?></button>
                        </div>
                    </div>
                    <div style="height:20px;"></div>

                    <!-- Modal: Create Holiday -->
                    <?= createModalTop('modalCreateHoliday', $LANG['btn_create_holiday']) ?>
                    <label for="inputName"><?= $LANG['name'] ?></label>
                    <input id="inputName" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_name" maxlength="40" value="<?= $viewData['txt_name'] ?>" type="text">
                    <?php if (isset($inputAlert["name"]) and strlen($inputAlert["name"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-bs-dismiss="alert">x</button><?= $inputAlert["name"] ?></div>
                    <?php } ?>
                    <label for="inputDescription"><?= $LANG['description'] ?></label>
                    <input id="inputDescription" class="form-control" tabindex="<?= $tabindex++; ?>" name="txt_description" maxlength="100" value="<?= $viewData['txt_description'] ?>" type="text">
                    <?php if (isset($inputAlert["description"]) and strlen($inputAlert["description"])) { ?>
                        <br>
                        <div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-bs-dismiss="alert">x</button><?= $inputAlert["description"] ?></div>
                    <?php } ?>
                    <?= createModalBottom('btn_holCreate', 'success', $LANG['btn_create_holiday']) ?>

                </form>

                <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px; font-weight: bold;">
                    <div class="col-lg-1"><?= $LANG['display'] ?></div>
                    <div class="col-lg-2"><?= $LANG['name'] ?></div>
                    <div class="col-lg-4"><?= $LANG['description'] ?></div>
                    <div class="col-lg-3"><?= $LANG['options'] ?></div>
                    <div class="col-lg-2 text-end"><?= $LANG['action'] ?></div>
                </div>

                <?php foreach ($viewData['holidays'] as $holiday) { ?>
                    <form class="rorm-control-horizontal" name="form_<?= $holiday['id'] ?>" action="index.php?action=<?= $CONF['controllers'][$controller]->name ?>" method="post" target="_self" accept-charset="utf-8">
                        <div class="row" style="border-bottom: 1px dotted; margin-bottom: 10px; padding-bottom: 10px;">
                            <div class="col-lg-1">
                                <div style="color: #<?= $holiday['color'] ?>; background-color: #<?= $holiday['bgcolor'] ?>; border: 1px solid; width: 26px; height: 26px; text-align: center;">
                                    23
                                </div>
                            </div>
                            <div class="col-lg-2"><?= $holiday['name'] ?></div>
                            <div class="col-lg-4"><?= $holiday['description'] ?></div>
                            <div class="col-lg-3">
                                <?= (($holiday['businessday']) ? '<i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="' . $LANG['hol_businessday'] . '"><i class="fas fa-wrench fa-lg text-default"></i></i>&nbsp;' : '') ?>
                                <?= (($holiday['keepweekendcolor']) ? '<i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="' . $LANG['hol_keepweekendcolor'] . '"><i class="fas fa-paint-brush fa-lg text-success"></i></i>&nbsp;' : '') ?>
                                <?= (($holiday['noabsence']) ? '<i data-placement="top" data-type="info" data-bs-toggle="tooltip" title="' . $LANG['hol_noabsence'] . '"><i class="fas fa-minus-circle fa-lg text-danger"></i></i>' : '') ?>
                            </div>
                            <div class="col-lg-2 text-end">
                                <?php if ($holiday['id'] > 3) { ?>
                                    <button type="button" class="btn btn-danger btn-sm" tabindex="<?= $tabindex++; ?>" data-bs-toggle="modal" data-bs-target="#modalDeleteHoliday_<?= $holiday['id'] ?>"><?= $LANG['btn_delete'] ?></button>
                                    <input name="hidden_id" type="hidden" value="<?= $holiday['id'] ?>">
                                    <input name="hidden_name" type="hidden" value="<?= $holiday['name'] ?>">
                                    <input name="hidden_description" type="hidden" value="<?= $holiday['description'] ?>">
                                <?php } ?>
                                <a href="index.php?action=holidayedit&amp;id=<?= $holiday['id'] ?>" class="btn btn-warning btn-sm" tabindex="<?= $tabindex++; ?>"><?= $LANG['btn_edit'] ?></a>
                            </div>
                        </div>

                        <?php if ($holiday['id'] > 3) { ?>
                            <!-- Modal: Delete Holiday -->
                            <?= createModalTop('modalDeleteHoliday_' . $holiday['id'], $LANG['btn_delete_holiday']) ?>
                            <?= sprintf($LANG['hol_confirm_delete'], $holiday['name']) ?>
                            <?= createModalBottom('btn_holDelete', 'danger', $LANG['btn_delete_holiday']) ?>
                        <?php } ?>

                    </form>
                <?php } ?>

            </div>
        </div>

    </div>
</div>