<?php
/**
 * Pattern Edit View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.0.0
 */
?>
<!-- ====================================================================
view.patternedit
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

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= htmlspecialchars($controller ?? '', ENT_QUOTES, 'UTF-8') ?>&amp;id=<?= htmlspecialchars($viewData['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($viewData['pageHelp']) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['ptn_edit_title'] . htmlspecialchars($viewData['name'] ?? '', ENT_QUOTES, 'UTF-8') . $pageHelp ?></div>
        <div class="card-body">

          <div class="form-group row" id="form-group-name">
            <label for="name" class="col-lg-8 control-label">
              <?= $LANG['ptn_currentPattern'] ?><br>
              <span class="text-normal"><?= $LANG['ptn_currentPattern_comment'] ?></span>
            </label>
            <div class="col-lg-4">
              <?= createPatternTable($viewData['id']) ?>
            </div>
          </div>
          <div class="divider">
            <hr>
          </div>

          <?php foreach ($viewData['pattern'] as $formObject) {
            echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
          } ?>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="btn_update"><?= $LANG['btn_update'] ?></button>
            <a href="index.php?action=patterns" class="btn btn-secondary" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_pattern_list'] ?></a>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>
