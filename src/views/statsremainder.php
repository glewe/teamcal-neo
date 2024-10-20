<?php
/**
 * Remainder Statistics View
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
  $tabindex = 1;
  ?>

  <form class="form-control-horizontal noprint" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
    <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="page-menu">
      <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalGroup"><?= $LANG['group'] ?> <span class="badge text-bg-light"><?= $viewData['groupName'] ?></span></button>
      <?php if (!$C->read('currentYearOnly')) { ?>
        <button type="button" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalYear"><?= $LANG['year'] ?> <span class="badge text-bg-light"><?= $viewData['year'] ?></span></button>
      <?php } ?>
      <button type="button" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" data-bs-toggle="modal" data-bs-target="#modalDiagram"><?= $LANG['diagram'] ?></button>
      <a class="btn btn-secondary" href="index.php?action=<?= $controller ?>"><?= $LANG['btn_reset'] ?></a>
    </div>
    <div style="height:20px;"></div>

    <!-- Modal: Group -->
    <?= createModalTop('modalGroup', $LANG['stats_modalGroupTitle']) ?>
    <span class="text-bold"><?= $LANG['stats_group'] ?></span><br>
    <span class="text-normal"><?= $LANG['stats_group_comment'] ?></span>
    <select id="group" class="form-select" name="sel_group" tabindex="<?= $tabindex++ ?>">
      <option value="all" <?= (($viewData['groupid'] == 'all') ? ' selected="selected"' : '') ?>><?= $LANG['all'] ?></option>
      <?php foreach ($viewData['groups'] as $grp) { ?>
        <option value="<?= $grp['id'] ?>" <?= (($viewData['groupid'] == $grp['id']) ? 'selected="selected"' : '') ?>><?= $grp['name'] ?></option>
      <?php } ?>
    </select><br>
    <?= createModalBottom('btn_apply', 'success', $LANG['btn_apply']) ?>

    <!-- Modal: Year -->
    <?php if (!$C->read('currentYearOnly')) {
      echo createModalTop('modalYear', $LANG['stats_modalYearTitle']); ?>
      <div>
        <span class="text-bold"><?= $LANG['stats_year'] ?></span><br>
        <span class="text-normal"><?= $LANG['stats_year_comment'] ?></span>
        <select id="sel_year" class="form-select" name="sel_year" tabindex="<?= $tabindex++ ?>">
          <option value="<?= date("Y") - 1 ?>" <?= (($viewData['year'] == date("Y") - 1) ? "selected" : "") ?>><?= date("Y") - 1 ?></option>
          <option value="<?= date("Y") ?>" <?= (($viewData['year'] == date("Y")) ? "selected" : "") ?>><?= date("Y") ?></option>
          <option value="<?= date("Y") + 1 ?>" <?= (($viewData['year'] == date("Y") + 1) ? "selected" : "") ?>><?= date("Y") + 1 ?></option>
        </select><br>
      </div>
      <?php echo createModalBottom('btn_apply', 'success', $LANG['btn_apply']);
    } ?>

    <!-- Modal: Diagram -->
    <?= createModalTop('modalDiagram', $LANG['stats_modalDiagramTitle']) ?>
    <div>
      <span class="text-bold"><?= $LANG['stats_color'] ?></span><br>
      <label for="sel_color" class="text-normal"><?= $LANG['stats_color_comment'] ?></label>
      <select id="sel_color" class="form-select" name="sel_color" tabindex="<?= $tabindex++ ?>">
        <option value="#0000ff" <?= (($viewData['color'] == 'blue') ? "selected" : "") ?>><?= $LANG['blue'] ?></option>
        <option value="#00ffff" <?= (($viewData['color'] == 'cyan') ? "selected" : "") ?>><?= $LANG['cyan'] ?></option>
        <option value="#00d000" <?= (($viewData['color'] == 'green') ? "selected" : "") ?>><?= $LANG['green'] ?></option>
        <option value="#808080" <?= (($viewData['color'] == 'grey') ? "selected" : "") ?>><?= $LANG['grey'] ?></option>
        <option value="#ff00ff" <?= (($viewData['color'] == 'magenta') ? "selected" : "") ?>><?= $LANG['magenta'] ?></option>
        <option value="#ffa500" <?= (($viewData['color'] == 'orange') ? "selected" : "") ?>><?= $LANG['orange'] ?></option>
        <option value="#800080" <?= (($viewData['color'] == 'purple') ? "selected" : "") ?>><?= $LANG['purple'] ?></option>
        <option value="#ff0000" <?= (($viewData['color'] == 'red') ? "selected" : "") ?>><?= $LANG['red'] ?></option>
        <option value="#ffff00" <?= (($viewData['color'] == 'yellow') ? "selected" : "") ?>><?= $LANG['yellow'] ?></option>
      </select><br>
      <br>
    </div>
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
      <i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['stats_title_remainder'] ?>&nbsp;(<?= $viewData['year'] ?>)<span class="badge text-bg-secondary float-end badge-header-right"><i data-bs-placement="bottom" data-type="info" data-bs-toggle="tooltip" title="<?= $LANG['stats_total'] ?>"><?= $viewData['total'] ?></i></span><?= $pageHelp ?>
    </div>
    <div class="card-body">
      <p><?= $LANG['stats_remainder_desc'] ?></p>

      <canvas id="myChart" height="<?= $viewData['height'] ?>"></canvas>

      <script src="addons/chart.js.4.4.3/chart.js"></script>
      <script>
        window.onload = function () {
          const ctx = document.getElementById('myChart');
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: [<?= $viewData['labels'] ?>],
              datasets: [
                {
                  label: '<?= $LANG['allowance'] ?>',
                  data: [<?= $viewData['dataAllowance'] ?>],
                  backgroundColor: '#00d00080', // 80 is adding transparency of 0.5
                  borderColor: '#00d000',
                  borderWidth: 1
                },
                {
                  label: '<?= $LANG['remainder'] ?>',
                  data: [<?= $viewData['dataRemainder'] ?>],
                  backgroundColor: '<?= $viewData['color'] ?>80', // 80 is adding transparency of 0.5
                  borderColor: '<?= $viewData['color'] ?>',
                  borderWidth: 1
                }
              ]
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
      </script>

    </div>
  </div>
</div>
