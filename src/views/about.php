<?php
/**
 * About View
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
view.about
-->
<div class="container content">

  <div class="col-lg-12">

    <?php
    if (
      ($showAlert && $viewData['showAlerts'] != "none") &&
      ($viewData['showAlerts'] == "all" || $viewData['showAlerts'] == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    ?>

    <div class="card text-<?= $CONF['controllers'][$controller]->panelColor ?>">
      <?php
      $pageHelp = '';
      if ($viewData['pageHelp']) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> bi-lg me-3"></i><?= $LANG['mnu_help_about'] ?><?= $pageHelp ?></div>
      <div class="card-body row">
        <div class="col-lg-2 text-center pt-0 mt-0"><i class="bi-calendar-week bi-8x logo-gradient"></i>
        </div>
        <div class="col-lg-10 mt-4">
          <h4><?= APP_NAME ?></h4>
          <p>
            <strong><?= $LANG['about_version'] ?>:</strong>&nbsp;&nbsp;<?= APP_VER ?><span id="versioncompare"></span><br>
            <strong><?= $LANG['about_copyright'] ?>:</strong>&nbsp;&nbsp;&copy;&nbsp;<?= APP_YEAR ?> by <a class="about" href="https://www.lewe.com/" target="_blank" rel="noopener"><?= APP_AUTHOR ?></a><br>
            <strong><?= $LANG['about_license'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://lewe.gitbook.io/teamcal-neo/readme/teamcal-neo-license" target="_blank" rel="noopener"><?= $LANG['license'] ?></a><br>
            <strong><?= $LANG['about_support'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://georgelewe.atlassian.net/servicedesk/customer/portal/5" target="_blank" rel="noopener">Lewe Service Desk</a><br>
            <strong><?= $LANG['about_issues'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://github.com/glewe/teamcal-neo/issues" target="_blank" rel="noopener">GitHub Issues</a><br>
            <strong><?= $LANG['about_documentation'] ?>:</strong>&nbsp;&nbsp;<a class="about" href="https://lewe.gitbook.io/teamcal-neo/" target="_blank" rel="noopener">TeamCal Neo User Manual</a><br>
          </p>
          <h4><?= $LANG['about_credits'] ?>:</h4>
          <ul>
            <li>Bootstrap Team <?= $LANG['about_for'] ?> <a href="https://getbootstrap.com/" target="_blank" rel="noopener">Bootstrap Framework <?= BOOTSTRAP_VER ?></a> and <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">Bootstrap Icons <?= BOOTSTRAP_ICONS_VER ?></a></li>
            <li>Nick Downie <?= $LANG['about_for'] ?> <a href="https://www.chartjs.org/" target="_blank" rel="noopener">Chart.js <?= CHARTJS_VER ?></a></li>
            <li>Momo Bassit <?= $LANG['about_for'] ?> <a href="https://coloris.js.org/" target="_blank" rel="noopener">Coloris <?= COLORIS_VER ?></a></li>
            <li>SpryMedia Ltd. <?= $LANG['about_for'] ?> <a href="https://datatables.net/" target="_blank" rel="noopener">DataTables <?= DATATABLES_VER ?></a></li>
            <li>Dave Gandy <?= $LANG['about_for'] ?> <a href="https://fontawesome.com/" target="_blank" rel="noopener">Font Awesome <?= FONTAWESOME_VER ?></a></li>
            <li>Google Team <?= $LANG['about_for'] ?> <a href="https://www.google.com/fonts/" target="_blank" rel="noopener">Google Fonts</a></li>
            <li>jQuery Team <?= $LANG['about_for'] ?> <a href="https://www.jquery.com/" target="_blank" rel="noopener">jQuery <?= JQUERY_VER ?></a> <?= $LANG['about_and'] ?> <a href="http://www.jqueryui.com/" target="_blank" rel="noopener">jQuery UI <?= JQUERY_UI_VER ?></a></li>
            <li>Stefan Petre <?= $LANG['about_for'] ?> <a href="https://www.eyecon.ro/colorpicker/" target="_blank" rel="noopener">jQuery Color Picker</a></li>
            <li>Dimitri Semenov <?= $LANG['about_for'] ?> <a href="https://dimsemenov.com/plugins/magnific-popup/" target="_blank" rel="noopener">Magnific Popup <?= MAGNIFICPOPUP_VER ?></a></li>
            <li>Drew Phillips <?= $LANG['about_for'] ?> <a href="https://github.com/dapphp/securimage" target="_blank" rel="noopener">SecureImage <?= SECUREIMAGE_VER ?></a></li>
            <li>Iconshock Team <?= $LANG['about_for'] ?> <a href="https://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank" rel="noopener">User Icons</a></li>
            <?php if (SYNTAXHIGHLIGHTER) { ?>
              <li>Alex Gorbatchev <?= $LANG['about_for'] ?> <a href="https://select2.github.io/" target="_blank" rel="noopener">Syntaxhighlighter <?= SYNTAXHIGHLIGHTER_VER ?></a></li>
            <?php } ?>
            <li>RobThree <?= $LANG['about_for'] ?> <a href="https://github.com/RobThree/TwoFactorAuth" target="_blank" rel="noopener">TwoFactorAuth</a></li>
            <li><?= $LANG['about_misc'] ?></li>
          </ul>
        </div>
      </div>
    </div>
    <div style="height:20px;"></div>

    <!--begin::Release Information-->
    <div class="card my-3">
      <div class="card-header cursor-pointer">
        <div data-bs-toggle="collapse" href="#releaseInformation" role="button" aria-expanded="false" aria-controls="releaseInformation">
          <div class="row">
            <div class="col-lg-12">
              <i class="bi bi-git bi-lg me-3"></i><?= $LANG['about_view_releaseinfo'] ?>...
            </div>
          </div>
        </div>
      </div>
      <div class="collapse" id="releaseInformation">
        <div class="card-body">
          <?php require_once 'doc/releaseinfo.php'; ?>
        </div>
      </div>
    </div>
    <!--end::Release Information-->

  </div>

</div>

<?php if ($viewData['versionCompare']) { ?>
  <script src="https://support.lewe.com/version/tcneo.js"></script>
  <script>
    var running_version = parseVersionString('<?= APP_VER ?>');
    if (running_version.major < latest_version.major) {
      //
      // Major version smaller
      //
      document.getElementById("versioncompare").innerHTML = '&nbsp;&nbsp;<a class="btn btn-sm btn-danger" href="https://github.com/glewe/teamcal-neo/releases" target="_blank"><?= $LANG['about_majorUpdateAvailable'] ?></a>';
    } else if (running_version.major == latest_version.major) {
      //
      // Major version equal
      //
      if (running_version.minor < latest_version.minor || (running_version.minor == latest_version.minor && running_version.patch < latest_version.patch)) {
        //
        // Minor version smaller OR (minor version equal AND patch version smaller)
        //
        document.getElementById("versioncompare").innerHTML = '&nbsp;&nbsp;<a class="btn btn-sm btn-warning" href="https://github.com/glewe/teamcal-neo/releases" target="_blank"><?= $LANG['about_minorUpdateAvailable'] ?></a>';
      } else {
        //
        // Same versions
        //
        //document.getElementById("versioncompare").innerHTML = '&nbsp;&nbsp;<a class="btn btn-sm btn-success" href="https://github.com/glewe/teamcal-neo/releases" target="_blank"><?= $LANG['about_newestVersion'] ?></a>';
      }
    }
  </script>
<?php } ?>
