<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * View Profile View
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
view.viewprofile
-->
<div class="container content">

    <div class="col-lg-12">
        <?php
        if ($showAlert and $C->read("showAlerts") != "none") {
            if (
                $C->read("showAlerts") == "all" or
                $C->read("showAlerts") == "warnings" and ($alertData['type'] == "warning" or $alertData['type'] == "danger")
            ) {
                echo createAlertBox($alertData);
            }
        } ?>
        <?php $tabindex = 1;
        $colsleft = 8;
        $colsright = 4; ?>

        <div class="card">
            <?php
            $pageHelp = '';
            if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
            ?>
            <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['profile_view_title'] . $viewData['fullname'] ?> (<?= $viewData['username'] ?>)<?= $pageHelp ?></div>

            <div class="card-body">

                <?php if ($viewData['allowEdit'] or $viewData['allowAbsum']) { ?>
                    <div class="card">
                        <div class="card-body">
                            <?php if ($viewData['allowEdit']) { ?>
                                <a class="btn btn-primary" tabindex="<?= $tabindex++; ?>" href="index.php?action=<?= $CONF['controllers']['useredit']->name ?>&amp;profile=<?= $viewData['username'] ?>"><?= $LANG['btn_edit'] ?></a>
                            <?php } ?>
                            <?php if ($viewData['allowAbsum']) { ?>
                                <a class="btn btn-info" tabindex="<?= $tabindex++; ?>" href="index.php?action=<?= $CONF['controllers']['absum']->name ?>&amp;user=<?= $viewData['username'] ?>"><?= $LANG['btn_absum'] ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div style="height:20px;"></div>
                <?php } ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td><i class="fas fa-camera text-default" style="width: 26px;"></i><strong><?= $LANG['profile_avatar'] ?></strong></td>
                                <td><img src="<?= APP_AVATAR_DIR . $viewData['avatar'] ?>" alt="" class="boxshadow-1" style="width: 80px; height: 80px; padding: 4px; border: 1px solid #999999;"></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-circle text-default" style="width: 26px;"></i><strong><?= $LANG['profile_role'] ?></strong></td>
                                <td><?= $viewData['role'] ?></td>
                            </tr>
                            <?php if (strlen($viewData['title'])) { ?>
                                <tr>
                                    <td><i class="fas fa-pencil-alt text-default" style="width: 26px;"></i><strong><?= $LANG['profile_title'] ?></strong></td>
                                    <td><?= $viewData['title'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['position'])) { ?>
                                <tr>
                                    <td><i class="fas fa-building text-default" style="width: 26px;"></i><strong><?= $LANG['profile_position'] ?></strong></td>
                                    <td><?= $viewData['position'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['email'])) { ?>
                                <tr>
                                    <td><i class="fas fa-envelope text-default" style="width: 26px;"></i><strong><?= $LANG['profile_email'] ?></strong></td>
                                    <td><?= $viewData['email'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['phone'])) { ?>
                                <tr>
                                    <td><i class="fas fa-phone text-default" style="width: 26px;"></i><strong><?= $LANG['profile_phone'] ?></strong></td>
                                    <td><?= $viewData['phone'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['mobile'])) { ?>
                                <tr>
                                    <td><i class="fas fa-mobile-alt text-default" style="width: 26px;"></i><strong><?= $LANG['profile_mobilephone'] ?></strong></td>
                                    <td><?= $viewData['mobile'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['facebook'])) { ?>
                                <tr>
                                    <td><i class="fab fa-facebook text-default" style="width: 26px;"></i><strong><?= $LANG['profile_facebook'] ?></strong></td>
                                    <td><?= $viewData['facebook'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['google'])) { ?>
                                <tr>
                                    <td><i class="fab fa-google-plus text-default" style="width: 26px;"></i><strong><?= $LANG['profile_google'] ?></strong></td>
                                    <td><?= $viewData['google'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['linkedin'])) { ?>
                                <tr>
                                    <td><i class="fab fa-linkedin text-default" style="width: 26px;"></i><strong><?= $LANG['profile_linkedin'] ?></strong></td>
                                    <td><?= $viewData['linkedin'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['skype'])) { ?>
                                <tr>
                                    <td><i class="fab fa-skype text-default" style="width: 26px;"></i><strong><?= $LANG['profile_skype'] ?></strong></td>
                                    <td><?= $viewData['skype'] ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($viewData['twitter'])) { ?>
                                <tr>
                                    <td><i class="fab fa-twitter text-default" style="width: 26px;"></i><strong><?= $LANG['profile_twitter'] ?></strong></td>
                                    <td><?= $viewData['twitter'] ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
