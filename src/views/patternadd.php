<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Pattern Add View
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
view.patternadd
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
    $colsleft = 6;
    $colsright = 6;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['btn_create_pattern'] . $pageHelp ?></div>
        <div class="card-body">

          <?php foreach ($viewData['pattern'] as $formObject) {
            echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
          } ?>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_create"><?= $LANG['btn_create'] ?></button>
            <a href="index.php?action=patterns" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_pattern_list'] ?></a>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>
