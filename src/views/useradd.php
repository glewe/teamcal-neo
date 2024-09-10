<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * User Add View
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
view.useradd
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
        <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['profile_create_title'] . $pageHelp ?></div>
        <div class="card-body">

          <!-- Personal tab -->
          <div class="card">
            <div class="card-body">
              <?php foreach ($viewData['personal'] as $formObject) {
                echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
              } ?>
            </div>
          </div>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success me-2" tabindex="<?= $tabindex++ ?>" name="btn_profileCreate"><?= $LANG['btn_create_user'] ?></button>
            <a href="index.php?action=users" class="btn btn-secondary float-end" tabindex="<?= $tabindex++ ?>"><?= $LANG['btn_user_list'] ?></a>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>
