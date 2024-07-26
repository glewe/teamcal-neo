<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * Declination View
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
view.declination
-->
<div class="container content">

    <div class="col-lg-12">
        <?php
        if ($showAlert && $C->read("showAlerts") != "none") {
            if (
                $C->read("showAlerts") == "all" or
                $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" or $alertData['type'] == "danger")
            ) {
                echo createAlertBox($alertData);
            }
        } ?>
        <?php $tabindex = 1;
        $colsleft = 8;
        $colsright = 4; ?>

        <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

            <div class="card">
                <?php
                $pageHelp = '';
                if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                ?>
                <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['decl_title'] . $pageHelp ?></div>
                <div class="card-body">

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
                        </div>
                    </div>
                    <div style="height:20px;"></div>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab_overview-tab" href="#tab_overview" data-bs-toggle="tab" role="tab" aria-controls="tab_overview" aria-selected="true"><?= $LANG['decl_tab_overview'] ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_absence-tab" href="#tab_absence" data-bs-toggle="tab" role="tab" aria-controls="tab_absence" aria-selected="false"><?= $LANG['decl_tab_absence'] ?><?= (($viewData['declAbsence']) ? ' <i class="fas fa-check text-danger"></i>' : '') ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_before-tab" href="#tab_before" data-bs-toggle="tab" role="tab" aria-controls="tab_before" aria-selected="false"><?= $LANG['decl_tab_before'] ?><?= (($viewData['declBefore']) ? ' <i class="fas fa-check text-danger"></i>' : '') ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_period1-tab" href="#tab_period1" data-bs-toggle="tab" role="tab" aria-controls="tab_period1" aria-selected="false"><?= $LANG['decl_tab_period1'] ?><?= (($viewData['declPeriod1']) ? ' <i class="fas fa-check text-danger"></i>' : '') ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_period2-tab" href="#tab_period2" data-bs-toggle="tab" role="tab" aria-controls="tab_period2" aria-selected="false"><?= $LANG['decl_tab_period2'] ?><?= (($viewData['declPeriod2']) ? ' <i class="fas fa-check text-danger"></i>' : '') ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_period3-tab" href="#tab_period3" data-bs-toggle="tab" role="tab" aria-controls="tab_period3" aria-selected="false"><?= $LANG['decl_tab_period3'] ?><?= (($viewData['declPeriod3']) ? ' <i class="fas fa-check text-danger"></i>' : '') ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tab_scope-tab" href="#tab_scope" data-bs-toggle="tab" role="tab" aria-controls="tab_scope" aria-selected="false"><?= $LANG['decl_tab_scope'] ?></a></li>
                    </ul>

                    <div id="myTabContent" class="tab-content">

                        <!-- Overview tab -->
                        <div class="tab-pane fade show active" id="tab_overview" role="tabpanel" aria-labelledby="tab_overview-tab">
                            <div class="card">
                                <div class="card-body">

                                    <?php $overviews = array('Absence', 'Before', 'Period1', 'Period2', 'Period3');
                                    foreach ($overviews as $overview) {

                                        $details = '';
                                        $schedule = '';

                                        switch ($overview) {
                                            case 'Absence':
                                                $details = $viewData['declThreshold'] . '%';
                                                break;
                                            case 'Before':
                                                $details = $viewData['declBeforeDate'];
                                                break;
                                            case 'Period1':
                                                $details = $viewData['declPeriod1Start'] . ' - ' . $viewData['declPeriod1End'];
                                                break;
                                            case 'Period2':
                                                $details = $viewData['declPeriod2Start'] . ' - ' . $viewData['declPeriod2End'];
                                                break;
                                            case 'Period3':
                                                $details = $viewData['declPeriod3Start'] . ' - ' . $viewData['declPeriod3End'];
                                                break;
                                        }

                                        switch ($viewData['decl' . $overview . 'Period']) {
                                            case 'nowForever':
                                                $schedule = $LANG['decl_schedule_nowForever'];
                                                break;
                                            case 'nowEnddate':
                                                $schedule = sprintf($LANG['decl_schedule_nowEnddate'], $viewData['decl' . $overview . 'Enddate']);
                                                break;
                                            case 'startdateForever':
                                                $schedule = sprintf($LANG['decl_schedule_startdateForever'], $viewData['decl' . $overview . 'Startdate']);
                                                break;
                                            case 'startdateEnddate':
                                                $schedule = sprintf($LANG['decl_schedule_startdateEnddate'], $viewData['decl' . $overview . 'Startdate'], $viewData['decl' . $overview . 'Enddate']);
                                                break;
                                        }
                                    ?>

                                        <div class="form-group row" id="form-group-overview-<?= $overview ?>">
                                            <label class="col-lg-8 control-label">
                                                <?= $LANG['decl_tab_' . strtolower($overview)] ?><br>
                                                <span class="text-normal"><?= $LANG['decl_summary_' . strtolower($overview)] ?><br>
                                                    <span class="small text-italic text-info"><strong><?= $LANG['decl_value'] ?>: </strong><?= $details ?></span><br>
                                                    <span class="small text-italic text-info"><strong><?= $LANG['decl_schedule'] ?>: </strong><?= $schedule ?></span><br>
                                                </span>
                                            </label>
                                            <div class="col-lg-4">
                                                <?php switch ($viewData['decl' . $overview . 'Status']) {
                                                    case 'active': ?>
                                                        <span class="badge badge-danger"><?= $LANG['decl_label_active'] ?></span>
                                                    <?php break;
                                                    case 'expired': ?>
                                                        <span class="badge badge-success"><?= $LANG['decl_label_expired'] ?></span>
                                                    <?php break;
                                                    case 'inactive': ?>
                                                        <span class="badge badge-dark"><?= $LANG['decl_label_inactive'] ?></span>
                                                    <?php break;
                                                    case 'scheduled': ?>
                                                        <span class="badge badge-warning"><?= $LANG['decl_label_scheduled'] ?></span>
                                                <?php break;
                                                } ?>
                                            </div>
                                            <div class="divider">
                                                <hr>
                                            </div>
                                        </div>

                                    <?php } ?>

                                </div>
                            </div>
                        </div>

                        <!-- Absence tab -->
                        <div class="tab-pane fade" id="tab_absence" role="tabpanel" aria-labelledby="tab_absence-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['absence'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Before tab -->
                        <div class="tab-pane fade" id="tab_before" role="tabpanel" aria-labelledby="tab_before-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['before'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Period 1 tab -->
                        <div class="tab-pane fade" id="tab_period1" role="tabpanel" aria-labelledby="tab_period1-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['period1'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Period 2 tab -->
                        <div class="tab-pane fade" id="tab_period2" role="tabpanel" aria-labelledby="tab_period2-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['period2'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Period 3 tab -->
                        <div class="tab-pane fade" id="tab_period3" role="tabpanel" aria-labelledby="tab_period3-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['period3'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Scope tab -->
                        <div class="tab-pane fade" id="tab_scope" role="tabpanel" aria-labelledby="tab_scope-tab">
                            <div class="card">
                                <div class="card-body">
                                    <?php foreach ($viewData['scope'] as $formObject) {
                                        echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                                    } ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div style="height:20px;"></div>
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++; ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

<script>
    $(document).ready(function() {

        $('input[type=radio][name=opt_absencePeriod]').change(function() {
            if (this.value == 'nowForever') {
                $("#absenceStartdate").prop("disabled", true);
                $("#absenceEnddate").prop("disabled", true);
            } elseif (this.value == 'nowEnddate') {
                $("#absenceStartdate").prop("disabled", true);
                $("#absenceEnddate").prop("disabled", false);
            } elseif (this.value == 'startdateForever') {
                $("#absenceStartdate").prop("disabled", false);
                $("#absenceEnddate").prop("disabled", true);
            } elseif (this.value == 'startdateEnddate') {
                $("#absenceStartdate").prop("disabled", false);
                $("#absenceEnddate").prop("disabled", false);
            }
        });

        $('input[type=radio][name=opt_beforePeriod]').change(function() {
            if (this.value == 'nowForever') {
                $("#beforeStartdate").prop("disabled", true);
                $("#beforeEnddate").prop("disabled", true);
            } elseif (this.value == 'nowEnddate') {
                $("#beforeStartdate").prop("disabled", true);
                $("#beforeEnddate").prop("disabled", false);
            } elseif (this.value == 'startdateForever') {
                $("#beforeStartdate").prop("disabled", false);
                $("#beforeEnddate").prop("disabled", true);
            } elseif (this.value == 'startdateEnddate') {
                $("#beforeStartdate").prop("disabled", false);
                $("#beforeEnddate").prop("disabled", false);
            }
        });

        $('input[type=radio][name=opt_period1Period]').change(function() {
            if (this.value == 'nowForever') {
                $("#period1Startdate").prop("disabled", true);
                $("#period1Enddate").prop("disabled", true);
            } elseif (this.value == 'nowEnddate') {
                $("#period1Startdate").prop("disabled", true);
                $("#period1Enddate").prop("disabled", false);
            } elseif (this.value == 'startdateForever') {
                $("#period1Startdate").prop("disabled", false);
                $("#period1Enddate").prop("disabled", true);
            } elseif (this.value == 'startdateEnddate') {
                $("#period1Startdate").prop("disabled", false);
                $("#period1Enddate").prop("disabled", false);
            }
        });

        $('input[type=radio][name=opt_period2Period]').change(function() {
            if (this.value == 'nowForever') {
                $("#period2Startdate").prop("disabled", true);
                $("#period2Enddate").prop("disabled", true);
            } elseif (this.value == 'nowEnddate') {
                $("#period2Startdate").prop("disabled", true);
                $("#period2Enddate").prop("disabled", false);
            } elseif (this.value == 'startdateForever') {
                $("#period2Startdate").prop("disabled", false);
                $("#period2Enddate").prop("disabled", true);
            } elseif (this.value == 'startdateEnddate') {
                $("#period2Startdate").prop("disabled", false);
                $("#period2Enddate").prop("disabled", false);
            }
        });

        $('input[type=radio][name=opt_period3Period]').change(function() {
            if (this.value == 'nowForever') {
                $("#period3Startdate").prop("disabled", true);
                $("#period3Enddate").prop("disabled", true);
            } elseif (this.value == 'nowEnddate') {
                $("#period3Startdate").prop("disabled", true);
                $("#period3Enddate").prop("disabled", false);
            } elseif (this.value == 'startdateForever') {
                $("#period3Startdate").prop("disabled", false);
                $("#period3Enddate").prop("disabled", true);
            } elseif (this.value == 'startdateEnddate') {
                $("#period3Startdate").prop("disabled", false);
                $("#period3Enddate").prop("disabled", false);
            }
        });

    });
</script>
