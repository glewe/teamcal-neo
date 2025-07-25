<?php
/**
 * Absence Type Statistics View
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
view.statsabstype
-->
<div class="container content">

  <?php
  if (
    ($showAlert && $C->read("showAlerts") != "none") &&
    ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
  ) {
    echo createAlertBox($alertData);
  }
  $tabindex = 0;
  ?>

  <form class="form-control-horizontal noprint" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
    <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="page-menu">
      <button type="button" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalGroup"><?= $LANG['group'] ?> <span class="badge text-bg-light"><?= $viewData['groupName'] ?></span></button>
      <button type="button" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalPeriod"><?= $LANG['period'] ?> <span class="badge text-bg-light"><?= $viewData['periodName'] ?></span></button>
      <button type="button" class="btn btn-warning" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalDiagram"><?= $LANG['diagram'] ?></button>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>"><?= $LANG['btn_reset'] ?></a>
    </div>
    <div style="height:20px;"></div>

    <!-- Modal: Group -->
    <?= createModalTop('modalGroup', $LANG['stats_modalGroupTitle']) ?>
    <span class="text-bold"><?= $LANG['stats_group'] ?></span><br>
    <span class="text-normal"><?= $LANG['stats_group_comment'] ?></span>
    <select id="group" class="form-select" name="sel_group" tabindex="<?= ++$tabindex ?>">
      <option value="all" <?= (($viewData['groupid'] == 'all') ? ' selected="selected"' : '') ?>><?= $LANG['all'] ?></option>
      <?php foreach ($viewData['groups'] as $grp) { ?>
        <option value="<?= $grp['id'] ?>" <?= (($viewData['groupid'] == $grp['id']) ? 'selected="selected"' : '') ?>><?= $grp['name'] ?></option>
      <?php } ?>
    </select><br>
    <?= createModalBottom('btn_apply', 'success', $LANG['btn_apply']) ?>

    <!-- Modal: Period -->
    <?= createModalTop('modalPeriod', $LANG['stats_modalPeriodTitle']) ?>
    <div>
      <span class="text-bold"><?= $LANG['stats_period'] ?></span><br>
      <span class="text-normal"><?= $LANG['stats_period_comment'] ?></span>
      <select id="sel_period" class="form-select" name="sel_period" tabindex="<?= ++$tabindex ?>">
        <option value="year" <?= (($viewData['period'] == 'year') ? "selected" : "") ?>><?= $LANG['period_year'] ?></option>
        <option value="half" <?= (($viewData['period'] == 'half') ? "selected" : "") ?>><?= $LANG['period_half'] ?></option>
        <option value="quarter" <?= (($viewData['period'] == 'quarter') ? "selected" : "") ?>><?= $LANG['period_quarter'] ?></option>
        <option value="month" <?= (($viewData['period'] == 'month') ? "selected" : "") ?>><?= $LANG['period_month'] ?></option>
        <?php if (!$C->read('currentYearOnly')) { ?>
          <option value="custom" <?= (($viewData['period'] == 'custom') ? "selected" : "") ?>><?= $LANG['custom'] ?></option>
        <?php } ?>
      </select>
      <script>
        $("#sel_period").change(function () {
          if ($(this).val() == 'custom') {
            $('#from').prop('disabled', false);
            $('#to').prop('disabled', false);
          } else {
            $('#from').prop('disabled', true);
            $('#to').prop('disabled', true);
          }
        });
      </script>
    </div>
    <div>&nbsp;</div>

    <?php if (!$C->read('currentYearOnly')) { ?>
      <div>
        <span class="text-bold"><?= $LANG['stats_startDate'] ?></span><br>
        <span class="text-normal"><?= $LANG['stats_startDate_comment'] ?></span>
        <input id="from" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_from" type="text" maxlength="10" value="<?= $viewData['from'] ?>" <?= (($viewData['period'] == 'custom') ? "" : "disabled") ?>>
        <script>
          $(function () {
            $("#from").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: "yy-mm-dd"
            });
          });
        </script>
        <?php if (isset($inputAlert["from"]) && strlen($inputAlert["from"])) { ?>
          <br>
          <div class="alert alert-dismissable alert-danger">
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['from'] ?></div>
        <?php } ?>
      </div>
      <div>&nbsp;</div>

      <div>
        <span class="text-bold"><?= $LANG['stats_endDate'] ?></span><br>
        <span class="text-normal"><?= $LANG['stats_endDate_comment'] ?></span>
        <input id="to" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_to" type="text" maxlength="10" value="<?= $viewData['to'] ?>" <?= (($viewData['period'] == 'custom') ? "" : "disabled") ?>>
        <script>
          $(function () {
            $("#to").datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: "yy-mm-dd"
            });
          });
        </script>
        <?php if (isset($inputAlert["to"]) && strlen($inputAlert["to"])) { ?>
          <br>
          <div class="alert alert-dismissable alert-danger">
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button><?= $inputAlert['to'] ?></div>
        <?php } ?>
      </div>
    <?php } ?>
    <?= createModalBottom('btn_apply', 'success', $LANG['btn_apply']) ?>

    <!-- Modal: Diagram -->
    <?= createModalTop('modalDiagram', $LANG['stats_modalDiagramTitle']) ?>
    <div>
      <span class="text-bold"><?= $LANG['stats_color'] ?></span><br>
      <label for="sel_color" class="text-normal"><?= $LANG['stats_color_comment'] ?></label>
      <select id="sel_color" class="form-select" name="sel_color" tabindex="<?= ++$tabindex ?>" <?= (($viewData['showAsPieChart']) ? "disabled" : ""); ?>>
        <option value="#0000ff" <?= (($viewData['color'] == 'blue') ? "selected" : "") ?>><?= $LANG['blue'] ?></option>
        <option value="#00ffff" <?= (($viewData['color'] == 'cyan') ? "selected" : "") ?>><?= $LANG['cyan'] ?></option>
        <option value="#008000" <?= (($viewData['color'] == 'green') ? "selected" : "") ?>><?= $LANG['green'] ?></option>
        <option value="#808080" <?= (($viewData['color'] == 'grey') ? "selected" : "") ?>><?= $LANG['grey'] ?></option>
        <option value="#ff00ff" <?= (($viewData['color'] == 'magenta') ? "selected" : "") ?>><?= $LANG['magenta'] ?></option>
        <option value="#ffa500" <?= (($viewData['color'] == 'orange') ? "selected" : "") ?>><?= $LANG['orange'] ?></option>
        <option value="#800080" <?= (($viewData['color'] == 'purple') ? "selected" : "") ?>><?= $LANG['purple'] ?></option>
        <option value="#ff0000" <?= (($viewData['color'] == 'red') ? "selected" : "") ?>><?= $LANG['red'] ?></option>
        <option value="#ffff00" <?= (($viewData['color'] == 'yellow') ? "selected" : "") ?>><?= $LANG['yellow'] ?></option>
      </select><br>
    </div>
    <div>
      <div class="form-check">
        <label><input class="form-check-input" id="chk_showAsPieChart" name="chk_showAsPieChart" value="chk_showAsPieChart" type="checkbox" <?= (($viewData['showAsPieChart']) ? "checked" : ""); ?>><?= $LANG['stats_showAsPieChart'] ?></label>
      </div>
    </div>
    <script>
      $('#chk_showAsPieChart').change(function () {
        if ($('#chk_showAsPieChart').prop('checked')) {
          $("#sel_color").prop("disabled", true);
        } else {
          $("#sel_color").prop("disabled", false);
        }
      });
    </script>
    <?= createModalBottom('btn_apply', 'success', $LANG['btn_apply']) ?>

  </form>

  <div class="card">
    <?php
    $pageHelp = '';
    if ($C->read('pageHelp')) {
      $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
    }
    ?>
    <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>">
      <i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['stats_title_abstype'] ?>&nbsp;(<?= $viewData['periodName'] ?>)<span class="badge text-bg-secondary float-end badge-header-right"><i data-bs-placement="bottom" data-type="info" data-bs-toggle="tooltip" title="<?= $LANG['stats_total'] ?>"><?= $viewData['total'] ?></i></span><?= $pageHelp ?>
    </div>
    <div class="card-body">
      <p><?= $LANG['stats_abstype_desc'] ?></p>

      <canvas id="myChart" height="<?= $viewData['height'] ?>"></canvas>

      <script src="addons/chart.js/<?= CHARTJS_VER ?>/chart.umd.js"></script>
      <script>
        <?php if (!$viewData['showAsPieChart']) { ?>

        //
        // Chart.js Pie Chart
        //
        window.onload = function () {
          const ctx = document.getElementById('myChart');
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: [<?= $viewData['labels'] ?>],
              datasets: [{
                label: '<?= $LANG['taken'] ?>',
                data: [<?= $viewData['data'] ?>],
                backgroundColor: '<?= $viewData['color'] ?>80', // 80 is adding transparency of 0.5
                borderColor: '<?= $viewData['color'] ?>',
                borderWidth: 1
              }]
            },
            options: {
              indexAxis: 'y',
              elements: {
                rectangle: {
                  borderWidth: 2,
                }
              },
              responsive: true,
              legend: {
                display: false
              },
              title: {
                display: false
              }
            }
          });
        };

        <?php } else { ?>

        //
        // Chart.js Pie Chart
        //
        var color = Chart.helpers.color;
        var data = {
          labels: [<?= $viewData['labels'] ?>],
          datasets: [{
            data: [<?= $viewData['data'] ?>],
            backgroundColor: [<?= $viewData['sliceColors'] ?>],
            hoverBackgroundColor: [<?= $viewData['sliceColors'] ?>],
          }]
        };

        window.onload = function () {
          var ctx = document.getElementById("myChart").getContext("2d");
          window.myHorizontalBar = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
              responsive: true,
              legend: {
                display: false
              },
              title: {
                display: false
              }
            }
          });
        };

        <?php } ?>
      </script>

    </div>
  </div>
</div>
