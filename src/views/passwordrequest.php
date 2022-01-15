<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Password Request View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2022 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
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
            if ($showAlert and $C->read("showAlerts") != "none") {
                if (
                    $C->read("showAlerts") == "all" or
                    $C->read("showAlerts") == "warnings" and ($alertData['type'] == "warning" or $alertData['type'] == "danger")
                ) {
                    echo createAlertBox($alertData);
                }
            } ?>

            <div class="card">
                <?php
                $pageHelp = '';
                if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-right" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
                ?>
                <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['pwdreq_title'] . $pageHelp ?></div>
                <div class="card-body">
                    <div class="col-lg-12">
                        <?php $tabindex = 1;
                        $colsleft = 4;
                        $colsright = 8;
                        $paddingBottom = "36px"; ?>
                        <form id="login" action="index.php?action=<?= $controller ?>" method="post" target="_self" name="loginform" accept-charset="utf-8">
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
                                                <div class="radio"><label><input name="opt_user" value="<?= $usr['username'] ?>" tabindex="<?= $tabindex++ ?>" type="radio"><?= $usr['username'] ?></label></div>
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