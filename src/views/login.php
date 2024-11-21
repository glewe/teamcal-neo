<?php
/**
 * Login View
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
?>
<!-- ====================================================================
view.login
-->
<div class="container content">
  <div class="row">

    <div class="col-lg-3"></div>

    <div class="col-lg-6">
      <?php
      if (
        ($showAlert && $C->read("showAlerts") != "none") &&
        ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
      ) {
        echo createAlertBox($alertData);
      }
      $tabindex = 1;
      $colsleft = 5;
      $colsright = 7;
      $paddingBottom = "12px";
      ?>
      <div class="card">
        <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['login_login'] ?></div>
        <div class="card-body">
          <div class="col-lg-12">
            <form id="login" action="index.php?action=<?= $controller ?>" method="post" target="_self" name="loginform" accept-charset="utf-8">
              <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
              <fieldset>
                <div class="form-group row mb-3" style="padding-bottom: <?= $paddingBottom ?>;">
                  <label for="inputUsername" class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_username'] ?></label>
                  <div class="col-lg-<?= $colsright ?>">
                    <input id="inputUsername" class="form-control" autofocus="autofocus" name="uname" type="text" value="<?= (isset($uname)) ? $uname : ""; ?>">
                  </div>
                </div>
                <div class="form-group row" style="padding-bottom: <?= $paddingBottom ?>;">
                  <label for="pword" class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_password'] ?></label>
                  <div class="col-lg-<?= $colsright ?>">
                    <input class="form-control" id="pword" name="pword" type="password" autocomplete="off">
                  </div>
                </div>
                <hr>

                <?php if (!$C->read("disableTfa")) { ?>
                  <?= $LANG['login_authcode_comment'] ?>
                  <div class="form-group row" style="padding-bottom: <?= $paddingBottom ?>;">
                    <label for="totp" class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_authcode'] ?></label>
                    <div class="col-lg-<?= $colsright ?>">
                      <input id="totp" class="form-control" name="totp" type="text" minlength="6" maxlength="6" pattern="^[0-9]{1,6}$">
                    </div>
                  </div>
                  <hr>
                <?php } ?>

                <div class="form-group row">
                  <label for="inputSubmit" class="col-lg-<?= $colsleft ?> control-label"></label>
                  <div class="col-lg-<?= $colsright ?>">
                    <input id="inputSubmit" name="btn_login" type="text" value="true" style="visibility: hidden; display: none">
                    <button type="submit" class="btn btn-primary" name="submit"><?= $LANG['btn_login'] ?></button>
                    <a href="index.php?action=passwordrequest" class="btn btn-secondary float-end"><?= $LANG['btn_reset_password'] ?></a>
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
