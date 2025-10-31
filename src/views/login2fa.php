<?php
/**
 * Login 2FA View
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     4.3.0
 */
?>
<!-- ====================================================================
view.login2fa
-->
<div class="container content">
  <div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
      <?php
      if (
        ($showAlert && $viewData['showAlerts'] != "none") &&
        ($viewData['showAlerts'] == "all" || $viewData['showAlerts'] == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
      ) {
        echo createAlertBox($alertData);
      }
      $tabindex = 0;
      $colsleft = 5;
      $colsright = 7;
      $paddingBottom = "12px";
      ?>
      <div class="card">
        <div class="card-header text-bg-primary"><i class="fas fa-user-lock fa-lg me-3"></i><?= $LANG['login_authcode'] ?></div>
        <div class="card-body">
          <div class="col-lg-12">
            <form id="login2fa" action="index.php?action=login2fa" method="post" target="_self" name="login2faform" accept-charset="utf-8">
              <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
              <fieldset>
                <div class="form-group row mb-3" style="padding-bottom: <?= $paddingBottom ?>;">
                  <label for="totp" class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_authcode'] ?></label>
                  <div class="col-lg-<?= $colsright ?>">
                    <input id="totp" class="form-control" tabindex="<?= ++$tabindex ?>" name="totp" type="text" minlength="6" maxlength="6" pattern="^[0-9]{6}$" required autofocus autocomplete="one-time-code">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputSubmit" class="col-lg-<?= $colsleft ?> control-label"></label>
                  <div class="col-lg-<?= $colsright ?>">
                    <button type="submit" class="btn btn-primary" tabindex="<?= ++$tabindex ?>" name="submit"><?= $LANG['btn_login'] ?></button>
                    <a href="index.php?action=login" class="btn btn-secondary float-end" tabindex="<?= ++$tabindex ?>"><?= $LANG['btn_back'] ?? 'Back' ?></a>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3"></div>
  </div>
</div> 