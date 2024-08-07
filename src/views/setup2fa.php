<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Setup 2FA View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.7.0
 */
?>

<!-- ====================================================================
view.setup2fa
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
    ?>

    <form class="form-control-horizontal" enctype="multipart/form-data" action="index.php?action=setup2fa&amp;profile=<?= $viewData['profile'] ?>" method="post" target="_self" accept-charset="utf-8">
      <input name="hidden_s" type="hidden" value="<?= $viewData['secret'] ?>">

      <div class="card">
        <?php
        $pageHelp = '<a href="https://lewe.gitbook.io/teamcal-neo/user-guide/two-factor-authentication" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        ?>
        <div class="card-header text-white bg-danger"><i class="fas fa-user-lock fa-lg me-3"></i><?= $LANG['setup2fa_title'] . ' ' . $viewData['fullname'] . $pageHelp ?></div>
        <div class="card-body">
          <div class="row">
            <div class="col text-center">
              <img src="<?= $viewData['bcode'] ?>" alt="" style="max-width:300px;"><br>
              <?= $viewData['secret'] ?>
            </div>
          </div>
          <hr>
          <?= ($C->read('forceTfa') ? '<div class="mb-2">' . $LANG['setup2fa_required_comment'] . '</div>' : '') ?>
          <?= $LANG['setup2fa_comment'] ?>
          <hr>
          <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-2 text-center">
              <div class="form-group">
                <label for="totp"><?= $LANG['setup2fa_totp'] ?></label><br>
                <input id="totp" class="form-control text-center" name="txt_totp" type="text" minlength="6" maxlength="6" required pattern="^[0-9]{1,6}$">
              </div>
              <div style="height:20px;"></div>
              <button type="submit" class="btn btn-primary" name="btn_verify"><?= $LANG['btn_verify'] ?></button>
            </div>
            <div class="col-md-5"></div>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>
