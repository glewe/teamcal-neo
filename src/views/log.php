<?php
/**
 * Log View
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
view.log
-->
<div class="container content">

  <div class="col-lg-12">
    <?php
    if (
      ($showAlert && $viewData['showAlerts'] != "none") &&
      ($viewData['showAlerts'] == "all" || $viewData['showAlerts'] == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    $tabindex = 0;
    $colsleft = 8;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" action="index.php?action=log&amp;sort=<?= $viewData['sort'] ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($viewData['pageHelp']) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['mnu_admin_systemlog'] . ' ( ' . count($viewData['events']) . ' ' . $LANG['log_title_events'] . ' )' . $pageHelp ?></div>
        <div class="card-body">

          <div class="row mb-4">

            <div class="col-lg-3">
              <label for="sel_logPeriod"><?= $LANG['period'] ?></label>
              <select name="sel_logPeriod" id="sel_logPeriod" class="form-select mb-2" tabindex="<?= ++$tabindex ?>">
                <option class="option" value="curr_all" <?= (($viewData['logperiod'] == "curr_all") ? 'selected' : '') ?>><?= $LANG['all'] ?></option>
                <option class="option" value="curr_month" <?= (($viewData['logperiod'] == "curr_month") ? 'selected' : '') ?>><?= $LANG['period_month'] ?></option>
                <option class="option" value="curr_quarter" <?= (($viewData['logperiod'] == "curr_quarter") ? 'selected' : '') ?>><?= $LANG['period_quarter'] ?></option>
                <option class="option" value="curr_half" <?= (($viewData['logperiod'] == "curr_half") ? 'selected' : '') ?>><?= $LANG['period_half'] ?></option>
                <option class="option" value="curr_year" <?= (($viewData['logperiod'] == "curr_year") ? 'selected' : '') ?>><?= $LANG['period_year'] ?></option>
                <option class="option" value="custom" <?= (($viewData['logperiod'] == "custom") ? 'selected' : '') ?>><?= $LANG['period_custom'] ?></option>
              </select>
              <label for="sel_logType"><?= $LANG['log_header_type'] ?></label>
              <select name="sel_logType" id="sel_logType" class="form-select mb-2" tabindex="<?= ++$tabindex ?>">
                <option class="option" value="%" <?= (($viewData['logtype'] == "%") ? 'selected' : '') ?>><?= $LANG['all'] ?></option>
                <?php foreach ($viewData['types'] as $type) { ?>
                  <option class="option" value="log<?= $type ?>" <?= (($viewData['logtype'] == "log" . $type) ? 'selected' : '') ?>><?= $type ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-lg-2">
              <label for="logPeriodFrom"><?= $LANG['from'] ?></label>
              <input id="logPeriodFrom" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_logPeriodFrom" maxlength="10" value="<?= $viewData['logfrom'] ?>" type="text" <?= ($viewData['logPeriod'] != 'custom') ? 'disabled="disabled"' : '' ?>>
              <script>
                $(function () {
                  $("#logPeriodFrom").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd"
                  });
                });
              </script>
            </div>

            <div class="col-lg-2">
              <label for="logPeriodTo"><?= $LANG['to'] ?></label>
              <input id="logPeriodTo" class="form-control" tabindex="<?= ++$tabindex ?>" name="txt_logPeriodTo" maxlength="10" value="<?= $viewData['logto'] ?>" type="text" <?= ($viewData['logPeriod'] != 'custom') ? 'disabled="disabled"' : '' ?>>
              <script>
                $(function () {
                  $("#logPeriodTo").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd"
                  });
                });
              </script>
            </div>

            <div class="col-lg-5 text-end">
              <br>
              <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_refresh"><?= $LANG['btn_refresh'] ?></button>
              <button type="submit" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>" name="btn_reset"><?= $LANG['btn_reset'] ?></button>
              <button type="button" class="btn btn-danger" tabindex="<?= ++$tabindex ?>" data-bs-toggle="modal" data-bs-target="#modalClear"><?= $LANG['log_clear'] ?></button>

              <!-- Modal: Clear -->
              <?= createModalTop('modalClear', $LANG['modal_confirm']) ?>
              <?= $LANG['log_clear_confirm'] ?>
              <?= createModalBottom('btn_clear', 'danger', $LANG['log_clear']) ?>
            </div>

          </div>

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                [ 'id' => 'tab-log', 'href' => '#panel-log', 'label' => $LANG['log_title'], 'active' => true ],
                [ 'id' => 'tab-settings', 'href' => '#panel-settings', 'label' => $LANG['log_settings'], 'active' => false ],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Log tab -->
                <div class="tab-pane fade show active" id="panel-log" role="tabpanel" aria-labelledby="tab-log">

                  <?php if (count($viewData['events'])) :
                    $i = 1; ?>
                    <table id="dataTableLog" class="table table-bordered dt-responsive nowrap table-striped align-middle data-table" style="width:100%">
                      <thead>
                      <tr>
                        <th class="text-end" data-ordering="false">#</th>
                        <th class="text-start"><i class="bi-clock fa-lg me-3"></i><?= $LANG['log_header_when'] ?></th>
                        <th data-ordering="true"><i class="bi-folder fa-lg me-3"></i><?= $LANG['log_header_type'] ?></th>
                        <th data-ordering="true"><i class="bi-person fa-lg me-3"></i><?= $LANG['log_header_user'] ?></th>
                        <th data-ordering="true"><i class="bi-display fa-lg me-3"></i><?= $LANG['log_header_ip'] ?></th>
                        <th data-ordering="true"><i class="bi-pencil-square fa-lg me-3"></i><?= $LANG['log_header_event'] ?></th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($viewData['events'] as $event) :
                        $color = $allConfig['logcolor' . substr($event['type'], 3)];
                        ?>
                        <tr>
                          <td class="align-top text-end text-<?= $color ?>"><?= $i++ ?></td>
                          <td class="align-top text-start text-<?= $color ?>"><?= $event['timestamp'] ?></td>
                          <td class="align-top text-<?= $color ?>"><?= substr($event['type'], 3) ?></td>
                          <td class="align-top text-<?= $color ?>"><a style="color: inherit;" href="index.php?action=viewprofile&amp;profile=<?= $event['user'] ?>" target="_blank"><?= $event['user'] ?></a></td>
                          <td class="align-top text-start text-<?= $color ?>"><?= $event['ip'] ?></td>
                          <td class="align-top text-<?= $color ?>"><?= $event['event'] ?></td>
                        </tr>
                      <?php endforeach; ?>
                      </tbody>
                    </table>
                    <script>
                      $(document).ready(function () {
                        $('#dataTableLog').DataTable({
                          paging: true,
                          ordering: true,
                          info: true,
                          pageLength: 50,
                          deferRender: true,
                          processing: true,
                          language: {
                            url: 'addons/datatables/datatables.<?= $LANG['locale'] ?>.json'
                          },
                          columnDefs: [
                            {targets: [0], orderable: true, searchable: true}
                          ]
                        });
                      });
                    </script>

                  <?php else : ?>
                    <div class="mt-2">
                      <?php
                      $alertData['type'] = 'warning';
                      $alertData['title'] = 'Oops';
                      $alertData['subject'] = 'No log entries found';
                      $alertData['text'] = '';
                      $alertData['help'] = '';
                      echo createAlertBox($alertData);
                      ?>
                    </div>
                  <?php endif ?>

                </div>

                <!-- Log settings -->
                <div class="tab-pane fade" id="panel-settings" role="tabpanel" aria-labelledby="tab-settings">

                  <?php
                  foreach ($viewData['types'] as $type) {
                    $color = "text-" . $allConfig['logcolor' . $type];
                    ?>
                    <div class="row" style="border-bottom: 1px dotted; padding-top: 10px; padding-bottom: 10px;">
                      <div class="col-lg-3 <?= $color ?>"><label><i class="fas fa-tag fa-lg me-3"></i><?= $type ?></label></div>
                      <div class="col-lg-3">
                        <input class="form-check-input" style="margin-right: 10px;" name="chk_log<?= $type ?>" value="chk_log<?= $type ?>" type="checkbox" <?= ($allConfig['log' . $type]) ? ' checked=""' : '' ?>><?= $LANG['log_settings_log'] ?>
                      </div>
                      <div class="col-lg-3">
                        <input class="form-check-input" style="margin-right: 10px;" name="chk_logfilter<?= $type ?>" value="chk_logfilter<?= $type ?>" type="checkbox" <?= ($allConfig['logfilter' . $type]) ? ' checked=""' : '' ?>><?= $LANG['log_settings_show'] ?>
                      </div>
                      <div class="col-lg-3">
                        <div class="radio"><label><input class="form-check-input" name="opt_logcolor<?= $type ?>" value="default" tabindex="<?= ++$tabindex ?>" type="radio" <?= ($allConfig['logcolor' . $type] == "default") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-default"></i></label></div>
                        <div class="radio"><label><input class="form-check-input" name="opt_logcolor<?= $type ?>" value="primary" tabindex="<?= ++$tabindex ?>" type="radio" <?= ($allConfig['logcolor' . $type] == "primary") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-primary"></i></label></div>
                        <div class="radio"><label><input class="form-check-input" name="opt_logcolor<?= $type ?>" value="info" tabindex="<?= ++$tabindex ?>" type="radio" <?= ($allConfig['logcolor' . $type] == "info") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-info"></i></label></div>
                        <div class="radio"><label><input class="form-check-input" name="opt_logcolor<?= $type ?>" value="success" tabindex="<?= ++$tabindex ?>" type="radio" <?= ($allConfig['logcolor' . $type] == "success") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-success"></i></label></div>
                        <div class="radio"><label><input class="form-check-input" name="opt_logcolor<?= $type ?>" value="warning" tabindex="<?= ++$tabindex ?>" type="radio" <?= ($allConfig['logcolor' . $type] == "warning") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-warning"></i></label></div>
                        <div class="radio"><label><input class="form-check-input" name="opt_logcolor<?= $type ?>" value="danger" tabindex="<?= ++$tabindex ?>" type="radio" <?= ($allConfig['logcolor' . $type] == "danger") ? ' checked=""' : '' ?>><i class="fas fa-square fa-sm text-danger"></i></label></div>
                      </div>
                    </div>
                  <?php } ?>

                  <div class="mt-4 float-end">
                    <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_logSave"><?= $LANG['btn_save'] ?></button>
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
  $('#sel_logPeriod').change(function () {
    if (this.value == "custom") {
      $("#logPeriodFrom").prop('disabled', false);
      $("#logPeriodTo").prop('disabled', false);
    } else {
      $("#logPeriodFrom").prop('disabled', true);
      $("#logPeriodTo").prop('disabled', true);
    }
  });
</script>
