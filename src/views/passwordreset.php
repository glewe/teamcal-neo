<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Password Reset View
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
view.passwordreset
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

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>&amp;token=<?= $token ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['pwdreq_title'] . $pageHelp ?></div>
        <div class="card-body">

          <div class="card">
            <div class="card-body">
              <?php foreach ($viewData['personal'] as $formObject) {
                echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
              } ?>
            </div>
          </div>

          <div style="height:20px;"></div>
          <div class="card">
            <div class="card-body">
              <button type="submit" class="btn btn-success" tabindex="<?= $tabindex++ ?>" name="btn_update"><?= $LANG['btn_update'] ?></button>
            </div>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>
