<?php

/**
 * Absence Edit View
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
view.absenceedit
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

    <?php
    //
    // This is a sample toast message. You can use this as a template for your own toast messages.
    // Try it out by setting $showToast = true; and reload the page.
    //
    if ($showToast) { ?>
      <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3">
        <?php
        $toast = [
          'id' => 'toast-absence-edit',
          'color' => 'success',
          'icon' => 'bi-person text-light',
          'title' => 'TeamCal Neo',
          'message' => 'Absence updated successfully.'
        ];
        echo createToast($toast);
        ?>
      </div>
      <script>
        var toastAbsenceEdit = new bootstrap.Toast(document.getElementById('toast-absence-edit'));
        toastAbsenceEdit.show();
      </script>
    <?php } ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;id=<?= htmlspecialchars($viewData['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      <input name="hidden_id" type="hidden" class="text" value="<?= htmlspecialchars($viewData['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($allConfig['pageHelp']) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['abs_edit_title'] . $viewData['name'] ?><?= $pageHelp ?></div>

        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $pageTabs = [
                ['id' => 'tab-general', 'href' => '#panel-general', 'label' => $LANG['general'], 'active' => true],
                ['id' => 'tab-options', 'href' => '#panel-options', 'label' => $LANG['options'], 'active' => false],
                ['id' => 'tab-groupassignments', 'href' => '#panel-groupassignments', 'label' => $LANG['abs_tab_groups'], 'active' => false]
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div id="myTabContent" class="tab-content">

                <div class="tab-pane fade show active" id="panel-general" role="tabpanel" aria-labelledby="tab-general">

                  <!-- Sample display -->
                  <div class="form-group row">
                    <label class="col-lg-<?= $colsleft ?> control-label">
                      <?= $LANG['abs_sample'] ?><br>
                      <span class="text-normal"><?= $LANG['abs_sample_comment'] ?></span>
                    </label>
                    <div class="col-lg-<?= $colsright ?>">
                      <?php
                      // Pre-calculate styles once
                      $sampleColor = htmlspecialchars($viewData['color'] ?? '', ENT_QUOTES, 'UTF-8');
                      $sampleBgStyle = ($viewData['bgtrans'] ?? false) ? "" : ("background-color: #" . htmlspecialchars($viewData['bgcolor'] ?? '', ENT_QUOTES, 'UTF-8'));
                      $sampleIcon = htmlspecialchars($viewData['icon'] ?? '', ENT_QUOTES, 'UTF-8');
                      $sampleSymbol = htmlspecialchars($viewData['symbol'] ?? '', ENT_QUOTES, 'UTF-8');
                      $sampleStyle = "color: #" . $sampleColor . "; " . $sampleBgStyle . "; border: 1px solid #333333; width: 32px; height: 32px; text-align: center; padding-top: 4px;";
                      ?>
                      <div id="sample" style="<?= $sampleStyle ?>" class="mb-1">
                        <span class="<?= $sampleIcon ?>"></span>
                      </div>
                      <div id="sample" style="<?= $sampleStyle ?>">
                        <?= $sampleSymbol ?>
                      </div>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <!-- Icon -->
                  <div class="form-group row">
                    <label class="col-lg-<?= $colsleft ?> control-label">
                      <?= $LANG['abs_icon'] ?><br>
                      <span class="text-normal"><?= $LANG['abs_icon_comment'] ?></span>
                    </label>
                    <div class="col-lg-<?= $colsright ?>">
                      <span class="<?= $sampleIcon ?> text-<?= $sampleColor ?>" style="font-size: 150%; padding-right: 8px; vertical-align: middle;"></span>
                      <a href="index.php?action=absenceicon&amp;id=<?= htmlspecialchars($viewData['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary btn-sm" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_abs_icon'] ?></a>
                    </div>
                  </div>
                  <div class="divider">
                    <hr>
                  </div>

                  <?php foreach ($viewData['formObjects']['general'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>

                </div>

                <div class="tab-pane fade" id="panel-options" role="tabpanel" aria-labelledby="tab-options">
                  <?php foreach ($viewData['formObjects']['options'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

                <div class="tab-pane fade" id="panel-groupassignments" role="tabpanel" aria-labelledby="tab-groupassignments">
                  <?php foreach ($viewData['formObjects']['groups'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
                  } ?>
                </div>

              </div>
            </div>

          </div>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary me-2" tabindex="<?= ++$tabindex ?>" name="btn_save"><?= $LANG['btn_save'] ?></button>
            <a href="index.php?action=absences" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_abs_list'] ?></a>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>