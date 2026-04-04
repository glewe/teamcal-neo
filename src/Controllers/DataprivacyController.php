<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

/**
 * Data Privacy Controller
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class DataprivacyController extends BaseController
{
  //---------------------------------------------------------------------------
  /**
   * Execute the controller logic.
   *
   * @return void
   */
  public function execute(): void {
    global $LANG;
    $language = $this->C->read('defaultLanguage');

    if (!$this->C->read('gdprPolicyPage')) {
      header("Location: index.php?action=home");
      die();
    }

    $gdpr_text = str_replace("%ENTITY%", $this->allConfig['gdprOrganization'], $LANG['gdpr_start']);
    $gdpr_text = str_replace("%CONTROLLER%", nl2br($this->allConfig['gdprController']), $gdpr_text);
    $gdpr_text = str_replace("%DATAPROTECTIONOFFICER%", nl2br($this->allConfig['gdprOfficer']), $gdpr_text);

    $sectionNbr = 11;
    if ($this->allConfig['gdprFacebook']) {
      $gdpr_text .= sprintf($LANG['gdpr_facebook'], $sectionNbr++);
    }
    if ($this->allConfig['gdprGoogleAnalytics']) {
      $gdpr_text .= sprintf($LANG['gdpr_google_analytics'], $sectionNbr++);
    }
    if ($this->allConfig['gdprInstagram']) {
      $gdpr_text .= sprintf($LANG['gdpr_instagram'], $sectionNbr++);
    }
    if ($this->allConfig['gdprLinkedin']) {
      $gdpr_text .= sprintf($LANG['gdpr_linkedin'], $sectionNbr++);
    }
    if ($this->allConfig['gdprPaypal']) {
      $gdpr_text .= sprintf($LANG['gdpr_paypal'], $sectionNbr++);
    }
    if ($this->allConfig['gdprPinterest']) {
      $gdpr_text .= sprintf($LANG['gdpr_pinterest'], $sectionNbr++);
    }
    if ($this->allConfig['gdprSlideshare']) {
      $gdpr_text .= sprintf($LANG['gdpr_slideshare'], $sectionNbr++);
    }
    if ($this->allConfig['gdprTumblr']) {
      $gdpr_text .= sprintf($LANG['gdpr_tumblr'], $sectionNbr++);
    }
    if ($this->allConfig['gdprTwitter']) {
      $gdpr_text .= sprintf($LANG['gdpr_twitter'], $sectionNbr++);
    }
    if ($this->allConfig['gdprXing']) {
      $gdpr_text .= sprintf($LANG['gdpr_xing'], $sectionNbr++);
    }
    if ($this->allConfig['gdprYoutube']) {
      $gdpr_text .= sprintf($LANG['gdpr_youtube'], $sectionNbr++);
    }

    $gdpr_text .= sprintf($LANG['gdpr_end'], $sectionNbr++, $sectionNbr++, $sectionNbr++, $sectionNbr++, $sectionNbr++);

    $viewData              = [];
    $viewData['gdpr_text'] = $gdpr_text;
    $this->render('dataprivacy', ['viewData' => $viewData]);
  }
}
