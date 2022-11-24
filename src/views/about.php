<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * About View
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
view.about
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

        <div class="card text-<?= $CONF['controllers'][$controller]->panelColor ?>">
            <?php
            $pageHelp = '';
            if ($C->read('pageHelp')) $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
            ?>
            <div class="card-header"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg fa-header"></i><?= $LANG['mnu_help_about'] ?><?= $pageHelp ?></div>
            <div class="card-body row">
                <div class="col-lg-3"><img src="images/icons/logo-128.png" width="128" height="128" alt="" class="img_floatleft">
                </div>
                <div class="col-lg-9">
                    <h4><?= APP_NAME ?></h4>
                    <p>
                        <strong><?= $LANG['about_version'] ?>:</strong>&nbsp;&nbsp;<?= APP_VER ?><span id="versioncompare"></span><br>
                        <strong><?= $LANG['about_copyright'] ?>:</strong>&nbsp;&nbsp;&copy;&nbsp;<?= APP_YEAR ?> by <a class="about" href="http://www.lewe.com/" target="_blank"><?= APP_AUTHOR ?></a><br>
                        <strong><?= $LANG['about_license'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://lewe.gitbook.io/teamcal-neo/readme/teamcal-neo-license" target="_blank"><?= $LANG['license'] ?></a><br>
                        <strong><?= $LANG['about_support'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://georgelewe.atlassian.net/servicedesk/customer/portal/5" target="_blank">Lewe Service Desk</a><br>
                        <strong><?= $LANG['about_documentation'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://lewe.gitbook.io/teamcal-neo/" target="_blank">TeamCal Neo User Manual</a><br>
                    </p>
                    <h4><?= $LANG['about_credits'] ?>:</h4>
                    <ul>
                        <li>Bootstrap Team <?= $LANG['about_for'] ?> <a href="http://getbootstrap.com/" target="_blank">Bootstrap Framework <?= BOOTSTRAP_VER ?></a></li>
                        <li>Thomas Park <?= $LANG['about_for'] ?> <a href="http://bootswatch.com/" target="_blank">Bootswatch Themes</a></li>
                        <?php if (CHARTJS) { ?><li>Nick Downie <?= $LANG['about_for'] ?> <a href="http://www.chartjs.org/" target="_blank">Chart.js <?= CHARTJS_VER ?></a></li><?php } ?>
                        <?php if (CKEDITOR) { ?><li>CKSource Sp. <?= $LANG['about_for'] ?> <a href="http://ckeditor.com/" target="_blank">CKEditor <?= CKEDITOR_VER ?></a></li><?php } ?>
                        <li>Dave Gandy <?= $LANG['about_for'] ?> <a href="http://fontawesome.com/" target="_blank">Font Awesome <?= FONTAWESOME_VER ?></a></li>
                        <li>Google Team <?= $LANG['about_for'] ?> <a href="https://www.google.com/fonts/" target="_blank">Google Fonts</a></li>
                        <li>jQuery Team <?= $LANG['about_for'] ?> <a href="http://www.jquery.com/" target="_blank">jQuery <?= JQUERY_VER ?></a> <?= $LANG['about_and'] ?> <a href="http://www.jqueryui.com/" target="_blank">jQuery UI <?= JQUERY_UI_VER ?></a></li>
                        <li>Stefan Petre <?= $LANG['about_for'] ?> <a href="http://www.eyecon.ro/colorpicker/" target="_blank">jQuery Color Picker</a></li>
                        <li>Dimitri Semenov <?= $LANG['about_for'] ?> <a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific Popup <?= MAGNIFICPOPUP_VER ?></a></li>
                        <li>Drew Phillips <?= $LANG['about_for'] ?> <a href="https://www.phpcaptcha.org/" target="_blank">SecureImage <?= SECUREIMAGE_VER ?></a></li>
                        <li>Ahkâm <?= $LANG['about_for'] ?> the beautiful <a href="https://www.freeiconspng.com/img/4109" target="_blank">Calendar Icon</a></li>
                        <li>Iconshock Team <?= $LANG['about_for'] ?> <a href="http://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank">User Icons</a></li>
                        <?php if (SELECT2) { ?><li>Kevin Brown &amp; Igor Vaynberg <?= $LANG['about_for'] ?> <a href="https://select2.github.io/" target="_blank">Select2 <?= SELECT2_VER ?></a></li><?php } ?>
                        <?php if (SYNTAXHIGHLIGHTER) { ?><li>Alex Gorbatchev <?= $LANG['about_for'] ?> <a href="https://select2.github.io/" target="_blank">Syntaxhighlighter <?= SYNTAXHIGHLIGHTER_VER ?></a></li><?php } ?>
                        <?php if (XEDITABLE) { ?><li>Vitaliy Potapov <?= $LANG['about_for'] ?> <a href="https://vitalets.github.io/x-editable/" target="_blank">X-Editable <?= XEDITABLE_VER ?></a></li><?php } ?>
                        <li><?= $LANG['about_misc'] ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="height:20px;"></div>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#releaseinfo"><?= $LANG['about_view_releaseinfo'] ?></button>
        <div style="height:20px;"></div>
        <div class="collapse" id="releaseinfo"><?php include('doc/releasenotes.html'); ?></div>

    </div>

</div>

<?php if ($C->read("versionCompare")) { ?>
    <script src="https://support.lewe.com/version/tcneo.js"></script>
    <script>
        var running_version = parseVersionString('<?= APP_VER ?>');
        if (running_version.major < latest_version.major) {
            //
            // Major version smaller
            //
            document.getElementById("versioncompare").innerHTML = '&nbsp;&nbsp;<a class="btn btn-sm btn-danger" href="https://www.lewe.com/teamcal-neo/" target="_blank"><?= $LANG['about_majorUpdateAvailable'] ?></a>';
        } else if (running_version.major == latest_version.major) {
            //
            // Major version equal
            //
            if (running_version.minor < latest_version.minor || (running_version.minor == latest_version.minor && running_version.patch < latest_version.patch)) {
                //
                // Minor version smaller OR (minor version equal AND patch version smaller)
                //
                document.getElementById("versioncompare").innerHTML = '&nbsp;&nbsp;<a class="btn btn-sm btn-warning" href="https://www.lewe.com/teamcal-neo/" target="_blank"><?= $LANG['about_minorUpdateAvailable'] ?></a>';
            } else {
                //
                // Same versions
                //
                //document.getElementById("versioncompare").innerHTML = '&nbsp;&nbsp;<a class="btn btn-sm btn-success" href="https://www.lewe.com/teamcal-neo/" target="_blank"><?= $LANG['about_newestVersion'] ?></a>';
            }
        }
    </script>
<?php } ?>