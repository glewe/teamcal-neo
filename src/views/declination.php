<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Declination View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
?>

<!-- ====================================================================
view.declination
-->
<div class="container content">

  <div class="col-lg-12">
    <?php
    if (
      ($showAlert && $C->read("showAlerts") != "none") &&
      ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    $tabindex = 1;
    $colsleft = 8;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['decl_title'] . $pageHelp ?></div>
        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                ['id' => 'tab-overview', 'href' => '#panel-overview', 'label' => $LANG['decl_tab_overview'], 'active' => true],
                ['id' => 'tab-absence', 'href' => '#panel-absence', 'label' => $LANG['decl_tab_absence'] . (($viewData['declAbsence']) ? ' <i class="fas fa-check text-danger"></i>' : ''), 'active' => false],
                ['id' => 'tab-before', 'href' => '#panel-before', 'label' => $LANG['decl_tab_before'] . (($viewData['declBefore']) ? ' <i class="fas fa-check text-danger"></i>' : ''), 'active' => false],
                ['id' => 'tab-period1', 'href' => '#panel-period1', 'label' => $LANG['decl_tab_period1'] . (($viewData['declPeriod1']) ? ' <i class="fas fa-check text-danger"></i>' : ''), 'active' => false],
                ['id' => 'tab-period2', 'href' => '#panel-period2', 'label' => $LANG['decl_tab_period2'] . (($viewData['declPeriod2']) ? ' <i class="fas fa-check text-danger"></i>' : ''), 'active' => false],
                ['id' => 'tab-period3', 'href' => '#panel-period3', 'label' => $LANG['decl_tab_period3'] . (($viewData['declPeriod3']) ? ' <i class="fas fa-check text-danger"></i>' : ''), 'active' => false],
                ['id' => 'tab-scope', 'href' => '#panel-scope', 'label' => $LANG['decl_tab_scope'], 'active' => false],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Overview tab -->
                <div class="tab-pane fade show active" id="panel-overview" role="tabpanel" aria-labelledby="tab-overview">

                  <?php $overviews = array( 'Absence', 'Before', 'Period1', 'Period2', 'Period3' );
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
                      default:
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
                      default:
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
                            <span class="badge text-bg-danger"><?= $LANG['decl_label_active'] ?></span>
                            <?php break;
                          case 'expired': ?>
                            <span class="badge text-bg-success"><?= $LANG['decl_label_expired'] ?></span>
                            <?php break;
                          case 'inactive': ?>
                            <span class="badge text-bg-dark"><?= $LANG['decl_label_inactive'] ?></span>
                            <?php break;
                          case 'scheduled': ?>
                            <span class="badge text-bg-warning"><?= $LANG['decl_label_scheduled'] ?></span>
                            <?php break;
                          default:
                            break;
                        } ?>
                      </div>
                      <div class="divider">
                        <hr>
                      </div>
                    </div>

                  <?php } ?>

                </div>

                <!-- Absence tab -->
                <div class="tab-pane fade" id="panel-absence" role="tabpanel" aria-labelledby="tab-absence">
                  <?php foreach ($viewData['absence'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Before tab -->
                <div class="tab-pane fade" id="panel-before" role="tabpanel" aria-labelledby="tab-before">
                  <?php foreach ($viewData['before'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Period 1 tab -->
                <div class="tab-pane fade" id="panel-period1" role="tabpanel" aria-labelledby="panel-period1">
                  <?php foreach ($viewData['period1'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Period 2 tab -->
                <div class="tab-pane fade" id="panel-period2" role="tabpanel" aria-labelledby="tab-period2">
                  <?php foreach ($viewData['period2'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Period 3 tab -->
                <div class="tab-pane fade" id="panel-period3" role="tabpanel" aria-labelledby="tab-period3">
                  <?php foreach ($viewData['period3'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Scope tab -->
                <div class="tab-pane fade" id="panel-scope" role="tabpanel" aria-labelledby="tab-scope">
                  <?php foreach ($viewData['scope'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

              </div>
            </div>
          </div>

          <div class="mt-4 float-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
          </div>

        </div>
      </div>

    </form>

  </div>
</div>

<script>
  $(document).ready(function () {

    $('input[type=radio][name=opt_absencePeriod]').change(function () {
      if (this.value == 'nowForever') {
        $("#absenceStartdate").prop("disabled", true);
        $("#absenceEnddate").prop("disabled", true);
      }
      elseif(this.value == 'nowEnddate')
      {
        $("#absenceStartdate").prop("disabled", true);
        $("#absenceEnddate").prop("disabled", false);
      }
      elseif(this.value == 'startdateForever')
      {
        $("#absenceStartdate").prop("disabled", false);
        $("#absenceEnddate").prop("disabled", true);
      }
      elseif(this.value == 'startdateEnddate')
      {
        $("#absenceStartdate").prop("disabled", false);
        $("#absenceEnddate").prop("disabled", false);
      }
    });

    $('input[type=radio][name=opt_beforePeriod]').change(function () {
      if (this.value == 'nowForever') {
        $("#beforeStartdate").prop("disabled", true);
        $("#beforeEnddate").prop("disabled", true);
      }
      elseif(this.value == 'nowEnddate')
      {
        $("#beforeStartdate").prop("disabled", true);
        $("#beforeEnddate").prop("disabled", false);
      }
      elseif(this.value == 'startdateForever')
      {
        $("#beforeStartdate").prop("disabled", false);
        $("#beforeEnddate").prop("disabled", true);
      }
      elseif(this.value == 'startdateEnddate')
      {
        $("#beforeStartdate").prop("disabled", false);
        $("#beforeEnddate").prop("disabled", false);
      }
    });

    $('input[type=radio][name=opt_period1Period]').change(function () {
      if (this.value == 'nowForever') {
        $("#period1Startdate").prop("disabled", true);
        $("#period1Enddate").prop("disabled", true);
      }
      elseif(this.value == 'nowEnddate')
      {
        $("#period1Startdate").prop("disabled", true);
        $("#period1Enddate").prop("disabled", false);
      }
      elseif(this.value == 'startdateForever')
      {
        $("#period1Startdate").prop("disabled", false);
        $("#period1Enddate").prop("disabled", true);
      }
      elseif(this.value == 'startdateEnddate')
      {
        $("#period1Startdate").prop("disabled", false);
        $("#period1Enddate").prop("disabled", false);
      }
    });

    $('input[type=radio][name=opt_period2Period]').change(function () {
      if (this.value == 'nowForever') {
        $("#period2Startdate").prop("disabled", true);
        $("#period2Enddate").prop("disabled", true);
      }
      elseif(this.value == 'nowEnddate')
      {
        $("#period2Startdate").prop("disabled", true);
        $("#period2Enddate").prop("disabled", false);
      }
      elseif(this.value == 'startdateForever')
      {
        $("#period2Startdate").prop("disabled", false);
        $("#period2Enddate").prop("disabled", true);
      }
      elseif(this.value == 'startdateEnddate')
      {
        $("#period2Startdate").prop("disabled", false);
        $("#period2Enddate").prop("disabled", false);
      }
    });

    $('input[type=radio][name=opt_period3Period]').change(function () {
      if (this.value == 'nowForever') {
        $("#period3Startdate").prop("disabled", true);
        $("#period3Enddate").prop("disabled", true);
      }
      elseif(this.value == 'nowEnddate')
      {
        $("#period3Startdate").prop("disabled", true);
        $("#period3Enddate").prop("disabled", false);
      }
      elseif(this.value == 'startdateForever')
      {
        $("#period3Startdate").prop("disabled", false);
        $("#period3Enddate").prop("disabled", true);
      }
      elseif(this.value == 'startdateEnddate')
      {
        $("#period3Startdate").prop("disabled", false);
        $("#period3Enddate").prop("disabled", false);
      }
    });

  });
</script>
