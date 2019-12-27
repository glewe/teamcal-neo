<?php
/**
 * header.php
 * 
 * The view of the HTML header
 *
 * @category TeamCal Neo 
 * @version 2.2.3
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2019 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');?><!DOCTYPE html>
<html lang="<?=$LANG['html_locale']?>">

   <head>
      <!--
      ===============================================================================
      Application: <?=APP_NAME." ".APP_VER."\n"?>
      Author:      <?=APP_AUTHOR."\n"?>
      License:     <?=APP_LICENSE."\n"?>
      Copyright:   <?=APP_COPYRIGHT."\n"?>
                   All rights reserved.
      ===============================================================================
      -->
      <title><?=$htmlData['title']?></title>
      
      <meta http-equiv="Content-type" content="text/html;charset=utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <link rel="shortcut icon" href="images/icons/logo-16.png">

      <?php if ($C->read('noCaching')) { ?><!-- No caching -->
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="0" />
      <?php } ?>
      
      <!-- Robots -->
      <meta name="robots" content="<?=$htmlData['robots']?>"/>
      
      <?php if (!$C->read('noIndex')) { ?><!-- SEO -->
      <link rel="canonical" href="<?=WEBSITE_URL?>/" />
      <meta name="description" content="<?=$htmlData['description']?>" />
      <meta name="keywords" content="<?=$htmlData['keywords']?>">
      <meta property="og:locale" content="<?=$htmlData['locale']?>" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="<?=$htmlData['title']?>" />
      <meta property="og:description" content="<?=$htmlData['description']?>" />
      <meta property="og:url" content="<?=WEBSITE_URL?>/" />
      <meta property="og:site_name" content="<?=$htmlData['title']?>" />
      <meta property="og:image" content="<?=WEBSITE_URL?>/images/icons/logo-200.png" />
      <meta property="og:image:width" content="200" />
      <meta property="og:image:height" content="200" />
      <meta name="twitter:card" content="summary" />
      <meta name="twitter:description" content="<?=$htmlData['description']?>" />
      <meta name="twitter:title" content="<?=$htmlData['title']?>" />
      <meta name="twitter:image" content="<?=WEBSITE_URL?>/images/icons/logo-200.png" />
      <?php } ?>
      
      <!-- Theme CSS -->
      <?php if ($htmlData['theme']['name']=='bootstrap') { ?>
<link rel="stylesheet" href="themes/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="themes/bootstrap/css/bootstrap-theme.min.css">
      <?php } else { ?>
<link rel="stylesheet" href="themes/<?=$htmlData['theme']['name']?>/css/bootstrap.min.css" media="screen">
      <?php } ?>
      
      <!-- Google Fonts -->
      <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'> -->
      
      <!-- Font Awesome -->
      <?php if ($htmlData['faCDN']) { ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
      <?php } else { ?>
<link rel="stylesheet" href="fonts/font-awesome/5.6.3/css/all.min.css">
      <?php } ?>
         
      <!-- jQuery -->
      <?php if ($htmlData['jQueryCDN']) { ?>
<script src="https://code.jquery.com/jquery-<?=JQUERY_VER?>.min.js"></script>
      <script src="https://code.jquery.com/ui/<?=JQUERY_UI_VER?>/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="https://code.jquery.com/ui/<?=JQUERY_UI_VER?>/themes/<?=$htmlData['jQueryTheme']?>/jquery-ui.min.css">
      <?php } else { ?>
<script src="js/jquery/jquery-<?=JQUERY_VER?>.min.js"></script>
      <script src="js/jquery/ui/<?=JQUERY_UI_VER?>/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="js/jquery/ui/<?=JQUERY_UI_VER?>/themes/<?=$htmlData['jQueryTheme']?>/jquery-ui.min.css">
      <?php } ?>
      
      <!-- Bootstrap Javascript -->
      <script src="themes/bootstrap/js/bootstrap.min.js"></script>
      
      <!-- Custom CSS -->
      <link rel="stylesheet" href="css/bootstrap-submenu.min.css">
      <link rel="stylesheet" href="css/custom.css">

      <!-- Colorpicker -->
      <link rel="stylesheet" media="screen" type="text/css" href="js/colorpicker/css/colorpicker.css">
      <script src="js/colorpicker/js/colorpicker.js"></script>

      <!-- Google Code Prettify -->
      <link rel="stylesheet" href="addons/google-code-prettify/prettify.css" type="text/css">
      <script src="addons/google-code-prettify/prettify.js"></script>

      <?php if (CHARTJS) { ?><!-- Chart.js -->  
      <script src="addons/chart.js/Chart.bundle.min.js"></script>
      <script src="addons/chart.js/utils.js"></script>
      <?php } ?>

      <?php if (CKEDITOR) { ?><!-- CKEditor -->
      <script src="addons/ckeditor/ckeditor.js"></script>
      <?php } ?>

      <?php if (MAGNIFICPOPUP) { ?><!-- Magnific Popup -->
      <link rel="stylesheet" href="addons/magnific/magnific-popup.css" type="text/css">
      <script async src="addons/magnific/jquery.magnific-popup.min.js"></script>
      <?php } ?>
      
      <?php if (SELECT2) { ?><!-- select2 --> 
      <link href="addons/select2/css/select2.min.css" rel="stylesheet">
      <script src="addons/select2/js/select2.min.js"></script>
      <?php } ?>
      
      <?php if (SYNTAXHIGHLIGHTER) { ?><!-- Syntax Highlighter -->
      <link rel="stylesheet" href="addons/syntaxhighlighter/styles/shCore.css" type="text/css">
      <link rel="stylesheet" href="addons/syntaxhighlighter/styles/shThemeDefault.css" type="text/css">
      <script src="addons/syntaxhighlighter/scripts/shCore.js"></script>
      <script src="addons/syntaxhighlighter/scripts/shAutoloader.js"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushCss.js"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushJScript.js"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushPhp.js"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushXml.js"></script>
      <?php } ?>
      
      <?php if (XEDITABLE) { ?><!-- Bootstrap Editable -->
      <link href="addons/x-editable/css/bootstrap-editable.css" rel="stylesheet">
      <script src="addons/x-editable/js/bootstrap-editable.js"></script>      
      <?php } ?>

      <?php if ($htmlData['cookieConsent']) { ?><!-- Cookie Consent by Silktide - http://silktide.com/cookieconsent -->
      <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
      <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
      <script>
      window.addEventListener("load", function(){
      window.cookieconsent.initialise({
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
          "href": "index.php?action=imprint"
        }
      })});
      </script>
      <?php } ?>
      
   </head>

   <body>

      <!-- Back to Top -->
      <span id="top-link-block" class="hidden" data-spy="affix">
         <a class="back-to-top fa fa-arrow-circle-up fa-3x" href="#top" title="Back to top..."></a>
      </span>   
   