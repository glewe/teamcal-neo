<?php
/**
 * Calendar Options View
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
    $tabindex = 0;
    $colsleft = 8;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['calopt_title'] ?><?= $pageHelp ?></div>
        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                ['id' => 'tab-display', 'href' => '#panel-display', 'label' => $LANG['calopt_tab_display'], 'active' => true],
                ['id' => 'tab-filter', 'href' => '#panel-filter', 'label' => $LANG['calopt_tab_filter'], 'active' => false],
                ['id' => 'tab-options', 'href' => '#panel-options', 'label' => $LANG['calopt_tab_options'], 'active' => false],
                ['id' => 'tab-stats', 'href' => '#panel-stats', 'label' => $LANG['calopt_tab_stats'], 'active' => false],
                ['id' => 'tab-summary', 'href' => '#panel-summary', 'label' => $LANG['calopt_tab_summary'], 'active' => false]
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- Tab: General -->
                <div class="tab-pane fade show active" id="panel-display" role="tabpanel" aria-labelledby="tab-display">
                  <?php foreach ($viewData['formFields']['display'] as $field) {
                    echo createFormGroup($field, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <!-- Tab: Filter -->
                <div class="tab-pane fade" id="panel-filter" role="tabpanel" aria-labelledby="tab-filter">
                  <?php foreach ($viewData['formFields']['filter'] as $field) {
                    echo createFormGroup($field, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <!-- Tab: Options -->
                <div class="tab-pane fade" id="panel-options" role="tabpanel" aria-labelledby="tab-options">
                  <?php foreach ($viewData['formFields']['options'] as $field) {
                    echo createFormGroup($field, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <!-- Statistics tab -->
                <div class="tab-pane fade" id="panel-stats" role="tabpanel" aria-labelledby="tab-stats">
                  <?php foreach ($viewData['formFields']['stats'] as $field) {
                    echo createFormGroup($field, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <!-- Tab: Summary -->
                <div class="tab-pane fade" id="panel-summary" role="tabpanel" aria-labelledby="tab-summary">
                  <?php foreach ($viewData['formFields']['summary'] as $field) {
                    echo createFormGroup($field, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

              </div>
            </div>
          </div>

          <div class="mt-4 float-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_caloptApply"><?= $LANG['btn_apply'] ?></button>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>
