<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Login View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2023 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
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
            if ($showAlert and $C->read("showAlerts") != "none") {
                if (
                    $C->read("showAlerts") == "all" or
                    $C->read("showAlerts") == "warnings" and ($alertData['type'] == "warning" or $alertData['type'] == "danger")
                ) {
                    echo createAlertBox($alertData);
                }
            } ?>
            <div class="card">
                <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['login_login'] ?></div>
                <div class="card-body">
                    <div class="col-lg-12">
                        <?php $tabindex = 1;
                        $colsleft = 5;
                        $colsright = 7;
                        $paddingBottom = "12px"; ?>
                        <form id="login" action="index.php?action=<?= $controller ?>" method="post" target="_self" name="loginform" accept-charset="utf-8">
                            <fieldset>
                                <div class="form-group row" style="padding-bottom: <?= $paddingBottom ?>;">
                                    <label class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_username'] ?></label>
                                    <div class="col-lg-<?= $colsright ?>">
                                        <input id="inputUsername" class="form-control" autofocus="autofocus" tabindex="1" name="uname" type="text" value="<?= (isset($uname)) ? $uname : ""; ?>">
                                    </div>
                                </div>
                                <div class="form-group row" style="padding-bottom: <?= $paddingBottom ?>;">
                                    <label class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_password'] ?></label>
                                    <div class="col-lg-<?= $colsright ?>">
                                        <input class="form-control" name="pword" tabindex="2" type="password" autocomplete="off">
                                    </div>
                                </div>
                                <hr>
                                <?= $LANG['login_authcode_comment'] ?>
                                <div class="form-group row" style="padding-bottom: <?= $paddingBottom ?>;">
                                    <label class="col-lg-<?= $colsleft ?> control-label"><?= $LANG['login_authcode'] ?></label>
                                    <div class="col-lg-<?= $colsright ?>">
                                        <input id="totp" class="form-control" name="totp" type="text" minlength="6" maxlength="6" pattern="^[0-9]{1,6}$">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-lg-<?= $colsleft ?> control-label"></label>
                                    <div class="col-lg-<?= $colsright ?>">
                                        <input id="inputSubmit" name="btn_login" type="text" value="true" style="visibility: hidden; display: none">
                                        <button type="submit" class="btn btn-primary" tabindex="3" name="submit"><?= $LANG['btn_login'] ?></button>
                                        <a href="index.php?action=passwordrequest" class="btn btn-secondary float-end" tabindex="4"><?= $LANG['btn_reset_password'] ?></a>
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