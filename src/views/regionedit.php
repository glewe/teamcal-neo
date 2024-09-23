<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Region Edit View
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
view.regionedit
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

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;id=<?= $viewData['id'] ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['region_edit_title'] . $viewData['name'] . $pageHelp ?></div>
        <div class="card-body">

          <?php foreach ($viewData['region'] as $formObject) {
            echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
          } ?>

          <div class="mt-4 text-end">
            <input name="hidden_id" type="hidden" value="<?= $viewData['id'] ?>">
            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_regionUpdate"><?= $LANG['btn_save'] ?></button>
            <a href="index.php?action=regions" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_region_list'] ?></a>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>
