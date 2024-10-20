<?php
/**
 * Password Request View
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
view.passwordrequest
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
      $colsleft = 4;
      $colsright = 8;
      $paddingBottom = "36px";
      ?>

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['pwdreq_title'] . $pageHelp ?></div>
        <div class="card-body">
          <div class="col-lg-12">
            <form id="login" action="index.php?action=<?= $controller ?>" method="post" target="_self" name="loginform" accept-charset="utf-8">
              <input name="csrf_token" type="hidden" value="<?= $_SESSION['csrf_token'] ?>">
              <fieldset>
                <div class="form-group row" style="padding-bottom: <?= $paddingBottom ?>;">
                  <label class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['pwdreq_email'] ?></label>
                  <div class="col-lg-<?= $colsright ?>">
                    <input id="inputUsername" class="form-control" autofocus="autofocus" tabindex="<?= $tabindex++ ?>" name="txt_email" type="text" value="<?= $viewData['email'] ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <?= $LANG['pwdreq_email_comment'] ?>
                  </div>
                </div>

                <?php if ($viewData['multipleUsers']) { ?>
                  <div class="divider">
                    <hr>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <label class="control-label"><?= $LANG['pwdreq_selectUser'] ?></label><br>
                      <?= $LANG['pwdreq_selectUser_comment'] ?>
                    </div>
                    <div class="col-lg-12">
                      <?php foreach ($viewData['pwdUsers'] as $usr) { ?>
                        <div class="form-check"><label><input class="form-check-input" name="opt_user" value="<?= $usr['username'] ?>" tabindex="<?= $tabindex++ ?>" type="radio"><?= $usr['username'] ?></label></div>
                      <?php } ?>
                    </div>
                  </div>
                <?php } ?>

                <div class="form-group row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-secondary" tabindex="<?= $tabindex++ ?>" name="btn_request_password"><?= $LANG['btn_reset_password'] ?></button>
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
