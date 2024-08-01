<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Calendar Options View
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
view.calendaroptions
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

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['calopt_title'] ?><?= $pageHelp ?></div>
        <div class="card-body">

          <div class="card">
            <div class="card-body">
              <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_caloptApply"><?= $LANG['btn_apply'] ?></button>
            </div>
          </div>
          <div style="height:20px;"></div>

          <div class="card">

            <div class="card-header">
              <ul class="nav nav-tabs card-header-tabs" id="myTabs" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link active" id="display-tab" href="#display" data-bs-toggle="tab" role="tab" aria-controls="display" aria-selected="true"><?= $LANG['calopt_tab_display'] ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="filter-tab" href="#filter" data-bs-toggle="tab" role="tab" aria-controls="filter" aria-selected="false"><?= $LANG['calopt_tab_filter'] ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="options-tab" href="#options" data-bs-toggle="tab" role="tab" aria-controls="options" aria-selected="false"><?= $LANG['calopt_tab_options'] ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="stats-tab" href="#stats" data-bs-toggle="tab" role="tab" aria-controls="stats" aria-selected="false"><?= $LANG['calopt_tab_stats'] ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="summary-tab" href="#summary" data-bs-toggle="tab" role="tab" aria-controls="summary" aria-selected="false"><?= $LANG['calopt_tab_summary'] ?></a></li>
              </ul>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Tab: General -->
                <div class="tab-pane fade show active" id="display" role="tabpanel" aria-labelledby="display-tab">
                  <?php foreach ($caloptData['display'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Tab: Filter -->
                <div class="tab-pane fade" id="filter" role="tabpanel" aria-labelledby="filter-tab">
                  <?php foreach ($caloptData['filter'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Tab: Options -->
                <div class="tab-pane fade" id="options" role="tabpanel" aria-labelledby="options-tab">
                  <?php foreach ($caloptData['options'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Tab: Summary -->
                <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                  <?php foreach ($caloptData['summary'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Statistics tab -->
                <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                  <?php foreach ($caloptData['stats'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

              </div>
            </div>
          </div>

          <div style="height:20px;"></div>
          <div class="card">
            <div class="card-body">
              <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_caloptApply"><?= $LANG['btn_apply'] ?></button>
            </div>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>
