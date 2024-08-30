<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Header View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
?>
<!DOCTYPE html>
<!-- Theme CSS -->
<?php if ($htmlData['theme']['name'] == 'bootstrap-dark') { ?>
<html lang="<?= $LANG['html_locale'] ?>" data-bs-theme="dark">
<?php } else { ?>
<html lang="<?= $LANG['html_locale'] ?>" data-bs-theme="auto">
<?php } ?>
<head>
  <!--
  ===============================================================================
  Application: <?= APP_NAME . " " . APP_VER . "\n" ?>
  Author:      <?= APP_AUTHOR . "\n" ?>
  License:     <?= APP_LICENSE . "\n" ?>
  Copyright:   <?= APP_COPYRIGHT . "\n" ?>
               All rights reserved.
  ===============================================================================
  -->
  <title><?= $htmlData['title'] ?></title>

  <meta http-equiv="content-type" content="text/html;charset=utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="shortcut icon" href="images/icons/logo-16.png">

  <!-- Robots -->
  <meta name="robots" content="<?= $htmlData['robots'] ?>">

  <?php if (!$C->read('noIndex')) { ?>
    <!-- SEO -->
    <link rel="canonical" href="<?= WEBSITE_URL ?>/">
    <meta name="description" content="<?= $htmlData['description'] ?>">
    <meta name="keywords" content="<?= $htmlData['keywords'] ?>">
    <meta property="og:locale" content="<?= $htmlData['locale'] ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $htmlData['title'] ?>">
    <meta property="og:description" content="<?= $htmlData['description'] ?>">
    <meta property="og:url" content="<?= WEBSITE_URL ?>/">
    <meta property="og:site_name" content="<?= $htmlData['title'] ?>">
    <meta property="og:image" content="<?= WEBSITE_URL ?>/images/icons/logo-200.png">
    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="<?= $htmlData['description'] ?>">
    <meta name="twitter:title" content="<?= $htmlData['title'] ?>">
    <meta name="twitter:image" content="<?= WEBSITE_URL ?>/images/icons/logo-200.png">
  <?php } ?>

  <!-- Theme CSS -->
  <?php if ($htmlData['theme']['name'] == 'bootstrap' || $htmlData['theme']['name'] == 'bootstrap-dark') { ?>
    <link rel="stylesheet" href="themes/bootstrap/bootstrap.min.css">
  <?php } else { ?>
    <link rel="stylesheet" href="themes/<?= $htmlData['theme']['name'] ?>/bootstrap.min.css">
  <?php } ?>

  <?php if ($C->read('font') && $C->read('font') != 'default') { ?>
    <!-- Fonts -->
    <link rel="stylesheet" href="css/font-<?= $C->read('font') ?>.min.css">
  <?php } ?>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="addons/bootstrap-icons-<?= BOOTSTRAP_ICONS_VER ?>/font/bootstrap-icons.min.css">

  <!-- Font Awesome -->
  <?php if ($htmlData['faCDN']) { ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/<?= FONTAWESOME_VER ?>/css/all.min.css">
  <?php } else { ?>
    <link rel="stylesheet" href="fonts/font-awesome/<?= FONTAWESOME_VER ?>/css/all.min.css">
  <?php } ?>

  <!-- jQuery -->
  <?php if ($htmlData['jQueryCDN']) { ?>
    <script src="https://code.jquery.com/jquery-<?= JQUERY_VER ?>.min.js"></script>
    <script src="https://code.jquery.com/ui/<?= JQUERY_UI_VER ?>/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/<?= JQUERY_UI_VER ?>/themes/<?= $htmlData['jQueryTheme'] ?>/jquery-ui.min.css">
  <?php } else { ?>
    <script src="js/jquery/jquery-<?= JQUERY_VER ?>.min.js"></script>
    <script src="js/jquery/ui/<?= JQUERY_UI_VER ?>/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="js/jquery/ui/<?= JQUERY_UI_VER ?>/themes/<?= $htmlData['jQueryTheme'] ?>/jquery-ui.min.css">
  <?php } ?>

  <!-- Bootstrap Color Mode Switcher -->
  <script src="js/color-modes.min.js"></script>

  <!-- Bootstrap Javascript -->
  <script src="themes/bootstrap/bootstrap.bundle.min.js"></script>

  <!-- TeamCalNeo CSS -->
  <link rel="stylesheet" href="css/teamcalneo.min.css">

  <!-- Colorpicker -->
  <link rel="stylesheet" media="screen" type="text/css" href="js/colorpicker/css/colorpicker.css">
  <script src="js/colorpicker/js/colorpicker.js"></script>

  <!-- Google Code Prettify -->
  <link rel="stylesheet" href="addons/google-code-prettify/prettify.css" type="text/css">
  <script src="addons/google-code-prettify/prettify.js"></script>

  <!--Datatables CSS-->
<!--    <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.2/b-3.1.0/b-html5-3.1.0/r-3.0.2/datatables.min.css" rel="stylesheet">-->
  <link href="addons/datatables/datatables.min.css" rel="stylesheet">

  <?php if (CKEDITOR) { ?>
    <!-- CKEditor -->
    <script src="addons/ckeditor/ckeditor.js"></script>
  <?php } ?>

  <?php if (MAGNIFICPOPUP) { ?>
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="addons/magnific/magnific-popup.css" type="text/css">
    <script src="addons/magnific/jquery.magnific-popup.min.js"></script>
  <?php } ?>

  <?php if (SELECT2) { ?>
    <!-- select2 -->
    <link href="addons/select2/css/select2.min.css" rel="stylesheet">
    <script src="addons/select2/js/select2.min.js"></script>
  <?php } ?>

  <?php if (SYNTAXHIGHLIGHTER) { ?>
    <!-- Syntax Highlighter -->
    <link rel="stylesheet" href="addons/syntaxhighlighter/styles/shCore.css" type="text/css">
    <link rel="stylesheet" href="addons/syntaxhighlighter/styles/shThemeDefault.css" type="text/css">
    <script src="addons/syntaxhighlighter/scripts/shCore.js"></script>
    <script src="addons/syntaxhighlighter/scripts/shAutoloader.js"></script>
    <script src="addons/syntaxhighlighter/scripts/shBrushCss.js"></script>
    <script src="addons/syntaxhighlighter/scripts/shBrushJScript.js"></script>
    <script src="addons/syntaxhighlighter/scripts/shBrushPhp.js"></script>
    <script src="addons/syntaxhighlighter/scripts/shBrushXml.js"></script>
  <?php } ?>

  <?php if (XEDITABLE) { ?>
    <!-- Bootstrap Editable -->
    <link href="addons/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <script src="addons/x-editable/js/bootstrap-editable.js"></script>
  <?php } ?>

  <?php if ($htmlData['cookieConsent']) { ?>
    <!-- Cookie Consent -->
    <?php if ($htmlData['cookieConsentCDN']) { ?>
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/<?= COOKIECONSENT_VER ?>/cookieconsent.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/<?= COOKIECONSENT_VER ?>/cookieconsent.min.js"></script>
  <?php } else { ?>
  <link rel="stylesheet" type="text/css" href="addons/cookieconsent2/<?= COOKIECONSENT_VER ?>/cookieconsent.min.css">
    <script src="addons/cookieconsent2/<?= COOKIECONSENT_VER ?>/cookieconsent.min.js"></script>
  <?php } ?>
    <script>
      window.addEventListener("load", function () {
        window.cookieconsent.initialise({
          "cookie": {
            "name": "tcneo_cookieconsent"
          },
          "palette": {
            "popup": {
              "background": "#252e39"
            },
            "button": {
              "background": "#14a7d0"
            }
          },
          "theme": "classic",
          "content": {
            "message": "<?= $LANG['cookie_message'] ?>",
            "link": "<?= $LANG['cookie_learnMore'] ?>",
            "dismiss": "<?= $LANG['cookie_dismiss'] ?>",
            "href": "index.php?action=dataprivacy"
          }
        })
      });
    </script>
  <?php } ?>

  <?php if (isset($viewData['calendaronly']) && $viewData['calendaronly']) { ?>
    <style>
      body {
        padding-top: 0;
      }

      .content {
        padding: 5px 0 5px 0;
      }
    </style>
  <?php } ?>

  <?php if ($C->read("googleAnalytics") && $C->read("googleAnalyticsID")) { ?>
    <!--Begin: Google Analytics GA4-->
    <script>
      // Define dataLayer and the gtag function.
      window.dataLayer = window.dataLayer || [];
      function gtag() {
        dataLayer.push(arguments);
      }
      // Set default consents to 'denied'
      // Set analytic consent to 'granted' (see Imprint page for opt-out option)
      gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'granted'
      });
    </script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $C->read("googleAnalyticsID") ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', '<?= $C->read("googleAnalyticsID") ?>');
      // Opt out to GA
      function gaOptout() {
        gtag('consent', 'update', {
          'analytics_storage': 'denied'
        });
      }
    </script>
    <!--End: Google Analytics GA4-->
  <?php } ?>

</head>

<body>

<!-- Back to Top -->
<a id="top-link-block"><i class="fas fa-chevron-up fa-3x text-white"></i></a>
