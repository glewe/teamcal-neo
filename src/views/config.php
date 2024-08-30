<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Framework Configuration View
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
view.config
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
    $tabindex = 1;
    $colsleft = 8;
    $colsright = 4;
    ?>

    <form class="form-control-horizontal" action="index.php?action=<?= $controller ?>" method="post" target="_self" accept-charset="utf-8">

      <div class="card">
        <?php
        $pageHelp = '';
        if ($C->read('pageHelp')) {
          $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
        }
        ?>
        <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['config_title'] ?><?= $pageHelp ?></div>
        <div class="card-body">

          <div class="card">

            <div class="card-header">
              <?php
              $attention = '<i class="fas fa-exclamation-triangle" style="color:#ffb000;margin-left: 8px;"></i>';
              $pageTabs = [
                [ 'id' => 'tab-general', 'href' => '#panel-general', 'label' => $LANG['general'], 'active' => true ],
                [ 'id' => 'tab-email', 'href' => '#panel-email', 'label' => $LANG['config_tab_email'], 'active' => false ],
                [ 'id' => 'tab-footer', 'href' => '#panel-footer', 'label' => $LANG['config_tab_footer'], 'active' => false ],
                [ 'id' => 'tab-homepage', 'href' => '#panel-homepage', 'label' => $LANG['config_tab_homepage'], 'active' => false ],
                [ 'id' => 'tab-license', 'href' => '#panel-license', 'label' => $LANG['config_tab_license'] . (($LIC->status() != "active" || !$LIC->domainRegistered()) ? $attention : ''), 'active' => false ],
                [ 'id' => 'tab-login', 'href' => '#panel-login', 'label' => $LANG['config_tab_login'], 'active' => false ],
                [ 'id' => 'tab-system', 'href' => '#panel-system', 'label' => $LANG['config_tab_system'], 'active' => false ],
                [ 'id' => 'tab-theme', 'href' => '#panel-theme', 'label' => $LANG['config_tab_theme'], 'active' => false ],
                [ 'id' => 'tab-usercustom', 'href' => '#panel-usercustom', 'label' => $LANG['config_tab_user'], 'active' => false ],
                [ 'id' => 'tab-gdpr', 'href' => '#panel-gdpr', 'label' => $LANG['config_tab_gdpr'], 'active' => false ],
              ];
              echo createPageTabs($pageTabs);
              ?>
            </div>

            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <!-- General tab -->
                <div class="tab-pane fade show active" id="panel-general" role="tabpanel" aria-labelledby="tab-general">
                  <?php foreach ($viewData['general'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- E-mail tab -->
                <div class="tab-pane fade" id="panel-email" role="tabpanel" aria-labelledby="tab-email">
                  <?php foreach ($viewData['email'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Footer tab -->
                <div class="tab-pane fade" id="panel-footer" role="tabpanel" aria-labelledby="tab-footer">
                  <?php foreach ($viewData['footer'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Homepage tab -->
                <div class="tab-pane fade" id="panel-homepage" role="tabpanel" aria-labelledby="tab-homepage">
                  <?php foreach ($viewData['homepage'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- License tab -->
                <div class="tab-pane fade" id="panel-license" role="tabpanel" aria-labelledby="tab-license">
                  <?php
                  echo $LIC->show($LIC->details, true);
                  foreach ($viewData['license'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  }
                  ?>

                  <?php
                  $licStatus = $LIC->status();
                  switch ($licStatus) {

                    case "pending": ?>
                      <div class="form-group row" id="form-lic-activate">
                        <label for="licActivate" class="col-lg-8 control-label">
                          <?= $LANG['config_licActivate'] ?><br>
                          <span class="text-normal"><?= $LANG['config_licActivate_comment'] ?></span>
                        </label>
                        <div class="col-lg-4">
                          <button type="submit" class="btn btn-success" tabindex="<?= $tabindex++ ?>" name="btn_licActivate"><?= $LANG['btn_activate'] ?></button>
                        </div>
                        <div class="divider">
                          <hr>
                        </div>
                      </div>
                      <?php break;

                    case "unregistered": ?>
                      <div class="form-group row" id="form-lic-register">
                        <label for="licRegister" class="col-lg-8 control-label">
                          <?= $LANG['config_licRegister'] ?><br><br>
                          <div class="text-normal alert alert-warning"><?= $LANG['config_licRegister_comment'] ?></div>
                        </label>
                        <div class="col-lg-4">
                          <button type="submit" class="btn btn-success" tabindex="<?= $tabindex++ ?>" name="btn_licRegister"><?= $LANG['btn_register'] ?></button>
                        </div>
                        <div class="divider">
                          <hr>
                        </div>
                      </div>
                      <?php break;

                    case "active": ?>
                      <div class="form-group row" id="form-lic-deregister">
                        <label for="licDeregister" class="col-lg-8 control-label">
                          <?= $LANG['config_licDeregister'] ?><br>
                          <span class="text-normal"><?= $LANG['config_licDeregister_comment'] ?></span>
                        </label>
                        <div class="col-lg-4">
                          <button type="submit" class="btn btn-warning" tabindex="<?= $tabindex++ ?>" name="btn_licDeregister"><?= $LANG['btn_deregister'] ?></button>
                        </div>
                        <div class="divider">
                          <hr>
                        </div>
                      </div>
                      <?php break;

                    default:
                      break;
                  } ?>

                </div>

                <!-- Login tab -->
                <div class="tab-pane fade" id="panel-login" role="tabpanel" aria-labelledby="tab-login">
                  <?php foreach ($viewData['login'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Registration tab -->
                <div class="tab-pane fade" id="panel-registration" role="tabpanel" aria-labelledby="tab-registration">
                  <?php foreach ($viewData['registration'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- System tab -->
                <div class="tab-pane fade" id="panel-system" role="tabpanel" aria-labelledby="tab-system">
                  <?php foreach ($viewData['system'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Theme tab -->
                <div class="tab-pane fade" id="panel-theme" role="tabpanel" aria-labelledby="tab-theme">
                  <?php foreach ($viewData['theme'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- Usericons tab -->
                <div class="tab-pane fade" id="panel-usercustom" role="tabpanel" aria-labelledby="tab-usercustom">
                  <?php foreach ($viewData['user'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                </div>

                <!-- GDPR tab -->
                <div class="tab-pane fade" id="panel-gdpr" role="tabpanel" aria-labelledby="tab-gdpr">
                  <?php foreach ($viewData['gdpr'] as $formObject) {
                    echo createFormGroup($formObject, $colsleft, $colsright, $tabindex++);
                  } ?>
                  <div class="form-group" id="form-group-gdprPlatforms">
                    <label for="gdprFacebook" class="col-lg-8 control-label">
                      <?= $LANG['config_gdprPlatforms'] ?><br>
                      <span class="text-normal"><?= $LANG['config_gdprPlatforms_comment'] ?></span>
                    </label>
                    <div class="col-lg-4">
                      <div class="checkbox">
                        <label><input type="checkbox" id="gdprFacebook" name="chk_gdprFacebook" value="chk_gdprFacebook" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprFacebook') ? " checked" : "") ?>><i class="fab fa-facebook"></i>&nbsp;Facebook</label><br>
                        <label><input type="checkbox" id="gdprGoogleAnalytics" name="chk_gdprGoogleAnalytics" value="chk_gdprGoogleAnalytics" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprGoogleAnalytics') ? " checked" : "") ?>><i class="fab fa-google"></i>&nbsp;Google Analytics</label><br>
                        <label><input type="checkbox" id="gdprInstagram" name="chk_gdprInstagram" value="chk_gdprInstagram" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprInstagram') ? " checked" : "") ?>><i class="fab fa-instagram"></i>&nbsp;Instagram</label><br>
                        <label><input type="checkbox" id="gdprLinkedin" name="chk_gdprLinkedin" value="chk_gdprLinkedin" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprLinkedin') ? " checked" : "") ?>><i class="fab fa-linkedin"></i>&nbsp;LinkedIn</label><br>
                        <label><input type="checkbox" id="gdprPaypal" name="chk_gdprPaypal" value="chk_gdprPaypal" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprPaypal') ? " checked" : "") ?>><i class="fab fa-paypal"></i>&nbsp;Paypal</label><br>
                        <label><input type="checkbox" id="gdprPinterest" name="chk_gdprPinterest" value="chk_gdprPinterest" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprPinterest') ? " checked" : "") ?>><i class="fab fa-pinterest"></i>&nbsp;Pinterest</label><br>
                        <label><input type="checkbox" id="gdprSlideshare" name="chk_gdprSlideshare" value="chk_gdprSlideshare" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprSlideshare') ? " checked" : "") ?>><i class="fab fa-slideshare"></i>&nbsp;Slideshare</label><br>
                        <label><input type="checkbox" id="gdprTumblr" name="chk_gdprTumblr" value="chk_gdprTumblr" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprTumblr') ? " checked" : "") ?>><i class="fab fa-tumblr"></i>&nbsp;Tumblr</label><br>
                        <label><input type="checkbox" id="gdprTwitter" name="chk_gdprTwitter" value="chk_gdprTwitter" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprTwitter') ? " checked" : "") ?>><i class="fab fa-twitter"></i>&nbsp;X (Twitter)</label><br>
                        <label><input type="checkbox" id="gdprXing" name="chk_gdprXing" value="chk_gdprXing" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprXing') ? " checked" : "") ?>><i class="fab fa-xing"></i>&nbsp;Xing</label><br>
                        <label><input type="checkbox" id="gdprYoutube" name="chk_gdprYoutube" value="chk_gdprYoutube" tabindex="<?= $tabindex++ ?>" <?= ($C->read('gdprYoutube') ? " checked" : "") ?>><i class="fab fa-youtube"></i>&nbsp;Youtube</label>
                      </div>
                    </div>
                    <div class="divider">
                      <hr>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="mt-4 float-end">
            <button type="submit" class="btn btn-primary" tabindex="<?= $tabindex++ ?>" name="btn_confApply"><?= $LANG['btn_apply'] ?></button>
          </div>

        </div>
      </div>

    </form>

  </div>

</div>
