<?php
if (!defined('VALID_ROOT')) { exit(''); }
/**
 * Menu View
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
view.menu
-->
<?php if ($C->read("menuBarDark")) $navcolor = "dark";
else $navcolor = "light"; ?>
<nav class="navbar navbar-expand-lg navbar-<?= $navcolor ?> bg-<?= $C->read("menuBarBg") ?> fixed-top">
    <div class="container">
        <a href="<?= WEBSITE_URL ?>" class="navbar-brand" style="padding: 2px 8px 0 8px;"><img src="images/logo.png" width="48" height="48" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTop">
            <ul class="navbar-nav">
                <!-- App Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="app" aria-haspopup="true" aria-expanded="false"><?= $C->read("appTitle") ?></a>
                    <div class="dropdown-menu" aria-labelledby="app">
                        <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['home']->name ?>"><i class="<?= $CONF['controllers']['home']->faIcon ?> fa-lg text-<?= $CONF['controllers']['home']->iconColor ?> me-3"></i><?= $LANG['mnu_app_homepage'] ?></a>
                        <?php if ($userData['isLoggedIn']) { ?>
                            <div class="dropdown-divider"></div>
                            <?php foreach ($appLanguages as $appLang) { ?>
                                <a class="dropdown-item" href="index.php?<?= str_replace('&', '&amp;', $_SERVER['QUERY_STRING']) ?>&amp;applang=<?= $appLang ?>"><img src="languages/<?= $appLang ?>.png" style="margin-right: 4px;" alt="<?= proper($appLang) ?>"><?= proper($appLang) ?></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </li>
                <!-- View Menu -->
                <?php if ((isAllowed($CONF['controllers']['messages']->permission) && $C->read('activateMessages')) or
                    isAllowed($CONF['controllers']['calendarview']->permission) or
                    isAllowed($CONF['controllers']['year']->permission) or
                    isAllowed($CONF['controllers']['remainder']->permission) or
                    isAllowed($CONF['controllers']['statsabsence']->permission) or
                    isAllowed($CONF['controllers']['statsabstype']->permission) or
                    isAllowed($CONF['controllers']['statspresence']->permission) or
                    isAllowed($CONF['controllers']['statsremainder']->permission) or
                    isAllowed($CONF['controllers']['absum']->permission)
                ) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="view" aria-haspopup="true" aria-expanded="false"><?= $LANG['mnu_view'] ?><span class="caret"></span></a>
                        <div class="dropdown-menu" aria-labelledby="view">
                            <?php if (isAllowed($CONF['controllers']['calendarview']->permission)) {
                                if ($controller == 'logout')
                                    $urlparams = "";
                                else
                                 if (!$urlparams = $UO->read($UL->username, 'calfilter')) $urlparams = "";
                            ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['calendarview']->name . $urlparams ?>"><i class="<?= $CONF['controllers']['calendarview']->faIcon ?> fa-lg text-<?= $CONF['controllers']['calendarview']->iconColor ?> me-3"></i><?= $LANG['mnu_view_calendar'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['year']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['year']->name ?>&amp;year=<?= date('Y') ?>&amp;region=1&amp;user=<?= $UL->username ?>"><i class="<?= $CONF['controllers']['year']->faIcon ?> fa-lg text-<?= $CONF['controllers']['year']->iconColor ?> me-3"></i><?= $LANG['mnu_view_year'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['remainder']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['remainder']->name ?>"><i class="<?= $CONF['controllers']['remainder']->faIcon ?> fa-lg text-<?= $CONF['controllers']['remainder']->iconColor ?> me-3"></i><?= $LANG['mnu_view_remainder'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['messages']->permission) and $C->read('activateMessages')) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['messages']->name ?>"><i class="<?= $CONF['controllers']['messages']->faIcon ?> fa-lg text-<?= $CONF['controllers']['messages']->iconColor ?> me-3"></i><?= $LANG['mnu_view_messages'] ?></a>
                            <?php } ?>
                            <?php if (
                                isAllowed($CONF['controllers']['statsabsence']->permission) or
                                isAllowed($CONF['controllers']['statspresence']->permission) or
                                isAllowed($CONF['controllers']['statsabstype']->permission) or
                                isAllowed($CONF['controllers']['statsremainder']->permission) or
                                isAllowed($CONF['controllers']['absum']->permission)
                            ) { ?>
                                <div class="dropdown-divider"></div>
                                <?php if (isAllowed($CONF['controllers']['statsabsence']->permission)) { ?>
                                    <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['statsabsence']->name ?>"><i class="<?= $CONF['controllers']['statsabsence']->faIcon ?> fa-lg text-<?= $CONF['controllers']['statsabsence']->iconColor ?> me-3"></i><?= $LANG['mnu_view_stats_absences'] ?></a>
                                <?php } ?>
                                <?php if (isAllowed($CONF['controllers']['statspresence']->permission)) { ?>
                                    <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['statspresence']->name ?>"><i class="<?= $CONF['controllers']['statspresence']->faIcon ?> fa-lg text-<?= $CONF['controllers']['statspresence']->iconColor ?> me-3"></i><?= $LANG['mnu_view_stats_presences'] ?></a>
                                <?php } ?>
                                <?php if (isAllowed($CONF['controllers']['statsabstype']->permission)) { ?>
                                    <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['statsabstype']->name ?>"><i class="<?= $CONF['controllers']['statsabstype']->faIcon ?> fa-lg text-<?= $CONF['controllers']['statsabstype']->iconColor ?> me-3"></i><?= $LANG['mnu_view_stats_abstype'] ?></a>
                                <?php } ?>
                                <?php if (isAllowed($CONF['controllers']['statsremainder']->permission)) { ?>
                                    <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['statsremainder']->name ?>"><i class="<?= $CONF['controllers']['statsremainder']->faIcon ?> fa-lg text-<?= $CONF['controllers']['statsremainder']->iconColor ?> me-3"></i><?= $LANG['mnu_view_stats_remainder'] ?></a>
                                <?php } ?>
                                <?php if (isAllowed($CONF['controllers']['absum']->permission)) { ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['absum']->name ?>&amp;user=<?= $userData['username'] ?>"><i class="<?= $CONF['controllers']['absum']->faIcon ?> fa-lg text-<?= $CONF['controllers']['absum']->iconColor ?> me-3"></i><?= $LANG['mnu_view_stats_absum'] ?></a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
                <!-- Edit Menu -->
                <?php if (
                    isAllowed($CONF['controllers']['calendaredit']->permission) or
                    isAllowed($CONF['controllers']['monthedit']->permission) or
                    isAllowed($CONF['controllers']['messageedit']->permission) or
                    isAllowed($CONF['controllers']['attachments']->permission)
                ) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="tools" aria-haspopup="true" aria-expanded="false"><?= $LANG['mnu_edit'] ?><span class="caret"></span></a>
                        <div class="dropdown-menu" aria-labelledby="tools">
                            <?php if (isAllowed($CONF['controllers']['calendaredit']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['calendaredit']->name ?>&amp;month=<?= date('Y') . date('m') ?>&amp;region=1&amp;user=<?= $userData['username'] ?>"><i class="<?= $CONF['controllers']['calendaredit']->faIcon ?> fa-lg text-<?= $CONF['controllers']['calendaredit']->iconColor ?> me-3"></i><?= $LANG['mnu_edit_calendaredit'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['monthedit']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['monthedit']->name ?>&amp;month=<?= date('Y') . date('m') ?>&amp;region=1"><i class="<?= $CONF['controllers']['monthedit']->faIcon ?> fa-lg text-<?= $CONF['controllers']['monthedit']->iconColor ?> me-3"></i><?= $LANG['mnu_edit_monthedit'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['messageedit']->permission) and $C->read('activateMessages')) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['messageedit']->name ?>"><i class="<?= $CONF['controllers']['messageedit']->faIcon ?> fa-lg text-<?= $CONF['controllers']['messageedit']->iconColor ?> me-3"></i><?= $LANG['mnu_edit_messageedit'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['attachments']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['attachments']->name ?>"><i class="<?= $CONF['controllers']['attachments']->faIcon ?> fa-lg text-<?= $CONF['controllers']['attachments']->iconColor ?> me-3"></i><?= $LANG['mnu_edit_attachments'] ?></a>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- Admin Menu -->
                <?php if (
                    isAllowed($CONF['controllers']['config']->permission) or
                    isAllowed($CONF['controllers']['calendaroptions']->permission) or
                    isAllowed($CONF['controllers']['permissions']->permission) or
                    isAllowed($CONF['controllers']['users']->permission) or
                    isAllowed($CONF['controllers']['groups']->permission) or
                    isAllowed($CONF['controllers']['roles']->permission) or
                    isAllowed($CONF['controllers']['database']->permission) or
                    isAllowed($CONF['controllers']['database']->permission) or
                    isAllowed($CONF['controllers']['phpinfo']->permission) or
                    isAllowed($CONF['controllers']['absences']->permission) or
                    isAllowed($CONF['controllers']['holidays']->permission) or
                    isAllowed($CONF['controllers']['regions']->permission) or
                    isAllowed($CONF['controllers']['declination']->permission) or
                    isAllowed($CONF['controllers']['bulkedit']->permission)
                ) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="admin" aria-haspopup="true" aria-expanded="false"><?= $LANG['mnu_admin'] ?><span class="caret"></span></a>
                        <div class="dropdown-menu" aria-labelledby="admin">
                            <?php if (isAllowed($CONF['controllers']['config']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['config']->name ?>"><i class="<?= $CONF['controllers']['config']->faIcon ?> fa-lg text-<?= $CONF['controllers']['config']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_config'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['calendaroptions']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['calendaroptions']->name ?>"><i class="<?= $CONF['controllers']['calendaroptions']->faIcon ?> fa-lg text-<?= $CONF['controllers']['calendaroptions']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_calendaroptions'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['permissions']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['permissions']->name ?>"><i class="<?= $CONF['controllers']['permissions']->faIcon ?> fa-lg text-<?= $CONF['controllers']['permissions']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_perm'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['users']->permission)) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['users']->name ?>"><i class="<?= $CONF['controllers']['users']->faIcon ?> fa-lg text-<?= $CONF['controllers']['users']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_users'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['groups']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['groups']->name ?>"><i class="<?= $CONF['controllers']['groups']->faIcon ?> fa-lg text-<?= $CONF['controllers']['groups']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_groups'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['roles']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['roles']->name ?>"><i class="<?= $CONF['controllers']['roles']->faIcon ?> fa-lg text-<?= $CONF['controllers']['roles']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_roles'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['absences']->permission)) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['absences']->name ?>"><i class="<?= $CONF['controllers']['absences']->faIcon ?> fa-lg text-<?= $CONF['controllers']['absences']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_absences'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['holidays']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['holidays']->name ?>"><i class="<?= $CONF['controllers']['holidays']->faIcon ?> fa-lg text-<?= $CONF['controllers']['holidays']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_holidays'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['regions']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['regions']->name ?>"><i class="<?= $CONF['controllers']['regions']->faIcon ?> fa-lg text-<?= $CONF['controllers']['regions']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_regions'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['declination']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['declination']->name ?>"><i class="<?= $CONF['controllers']['declination']->faIcon ?> fa-lg text-<?= $CONF['controllers']['declination']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_declination'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['bulkedit']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['bulkedit']->name ?>"><i class="<?= $CONF['controllers']['bulkedit']->faIcon ?> fa-lg text-<?= $CONF['controllers']['bulkedit']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_bulkedit'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['database']->permission)) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['database']->name ?>"><i class="<?= $CONF['controllers']['database']->faIcon ?> fa-lg text-<?= $CONF['controllers']['database']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_database'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['log']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['log']->name ?>"><i class="<?= $CONF['controllers']['log']->faIcon ?> fa-lg text-<?= $CONF['controllers']['log']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_systemlog'] ?></a>
                            <?php } ?>
                            <?php if (isAllowed($CONF['controllers']['phpinfo']->permission)) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['phpinfo']->name ?>"><i class="<?= $CONF['controllers']['phpinfo']->faIcon ?> fa-lg text-<?= $CONF['controllers']['phpinfo']->iconColor ?> me-3"></i><?= $LANG['mnu_admin_phpinfo'] ?></a>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
                <!-- Help Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="help" aria-haspopup="true" aria-expanded="false"><?= $LANG['mnu_help'] ?><span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="help">
                        <?php if ($docLink = $C->read("userManual")) { ?>
                            <a class="dropdown-item" tabindex="-1" href="<?= urldecode($docLink) ?>" target="_blank"><i class="fas fa-book fa-lg text-<?= $CONF['menuIconColor'] ?> me-3"></i><?= $LANG['mnu_help_help'] ?></a>
                            <div class="dropdown-divider"></div>
                        <?php } ?>
                        <?php if ($C->read("gdprPolicyPage")) { ?>
                            <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['dataprivacy']->name ?>"><i class="<?= $CONF['controllers']['dataprivacy']->faIcon ?> fa-lg text-<?= $CONF['controllers']['dataprivacy']->iconColor ?> me-3"></i><?= $LANG['mnu_help_dataprivacy'] ?></a>
                        <?php } ?>
                        <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['imprint']->name ?>"><i class="<?= $CONF['controllers']['imprint']->faIcon ?> fa-lg text-<?= $CONF['controllers']['imprint']->iconColor ?> me-3"></i><?= $LANG['mnu_help_imprint'] ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['about']->name ?>"><i class="<?= $CONF['controllers']['about']->faIcon ?> fa-lg text-<?= $CONF['controllers']['about']->iconColor ?> me-3"></i><?= $LANG['mnu_help_about'] ?></a>
                    </div>
                </li>
                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="user" aria-haspopup="true" aria-expanded="false">
                        <img src="<?= APP_AVATAR_DIR . $userData['avatar'] ?>" width="40" height="40" alt="" style="margin: -10px 0 -10px 0;"><span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="user">
                        <a class="dropdown-item" tabindex="-1" href="#"><?= $userData['loginInfo'] ?></a>
                        <div class="dropdown-divider"></div>
                        <?php if ($userData['isLoggedIn']) { ?>
                            <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['useredit']->name ?>&amp;profile=<?= $userData['username'] ?>"><i class="<?= $CONF['controllers']['useredit']->faIcon ?> fa-lg text-<?= $CONF['controllers']['useredit']->iconColor ?> me-3"></i><?= $LANG['mnu_user_profile'] ?></a>
                            <a class="dropdown-item" tabindex="-1" href="index.php?action=logout"><i class="fas fa-sign-out-alt fa-lg text-<?= $CONF['menuIconColor'] ?> me-3"></i><?= $LANG['mnu_user_logout'] ?></a>
                        <?php } else { ?>
                            <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['login']->name ?>"><i class="<?= $CONF['controllers']['login']->faIcon ?> fa-lg text-<?= $CONF['controllers']['login']->iconColor ?> me-3"></i><?= $LANG['mnu_user_login'] ?></a>
                            <?php if ($C->read("allowRegistration")) { ?>
                                <a class="dropdown-item" tabindex="-1" href="index.php?action=<?= $CONF['controllers']['register']->name ?>"><i class="<?= $CONF['controllers']['register']->faIcon ?> fa-lg text-<?= $CONF['controllers']['register']->iconColor ?> me-3"></i><?= $LANG['mnu_user_register'] ?></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php if ($C->read('showBanner')) { ?>
    <!-- Banner -->
    <div class="jumbotron">
        <div class="container">
            <div class="col-lg-9 banner">
                <?= $C->read('appTitle') ?>
            </div>
            <div class="col-lg-3" style="padding-top: 20px;">
                <form name="search" method="get" action="https://www.google.com/search">
                    <div class="input-group">
                        <input class="form-control" id="txt_search" type="text" name="q" size="20" maxlength="255" placeholder="Search">
                        <span class="input-group-btn">
                            <button id="btn_search" type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                    <input type="hidden" name="sitesearch" value="lewe.com">
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if ($appStatus['maintenance'] and $userData['roleid'] == '1') { ?>
    <!-- Under Maintenance -->
    <div class="container content">
        <div class="col-lg-12">
            <div class="alert alert-danger">
                <h4><strong><?= $LANG['alert_alert_title'] ?></strong></h4>
                <hr>
                <p><strong><?= $LANG['alert_maintenance_subject'] ?></strong></p>
                <p><?= $LANG['alert_maintenance_text'] ?></p>
                <p><?= $LANG['alert_maintenance_help'] ?></p>
            </div>
        </div>
    </div>
<?php } ?>
