<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * User Import View
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
view.userimport
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
    $colsleft = 6;
    $colsright = 6;
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['imp_title'] . $pageHelp ?></div>
        <div class="card-body">

          <div class="form-group row">
            <label class="col-lg-<?= $colsleft ?> control-label">
              <?= $LANG['imp_file'] ?><br>
              <span class="text-normal"><?= sprintf($LANG['imp_file_comment'], $viewData['upl_maxsize'] / 1024, $viewData['upl_formats'], APP_UPL_DIR) ?></span>
            </label>
            <div class="col-lg-<?= $colsright ?>">
              <input type="hidden" name="MAX_FILE_SIZE" value="<?= $viewData['upl_maxsize'] ?>"><br>
              <input class="form-control" tabindex="<?= ++$tabindex ?>" name="file_image" type="file"><br>
            </div>
          </div>
          <div class="divider">
            <hr>
          </div>

          <?php foreach ($viewData['import'] as $formObject) {
            echo createFormGroup($formObject, $colsleft, $colsright, ++$tabindex);
          } ?>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary me-2" tabindex="<?= ++$tabindex ?>" name="btn_import"><?= $LANG['btn_import'] ?></button>
            <?php if (isAllowed("useraccount")) { ?>
              <a href="index.php?action=users" class="btn btn-secondary float-end" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_user_list'] ?></a>
            <?php } ?>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>
