<?php
/**
 * header.php
 * 
 * The view of the HTML header
 *
 * @category TeamCal Neo 
 * @version 0.9.012
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');?><!DOCTYPE html>
<html lang="en">

   <head>
      <!--
      ===============================================================================
      Application: <?=$htmlData['title']." ".$htmlData['version']."\n"?>
      Author:      <?=$htmlData['author']."\n"?>
      License:     <?=$htmlData['license']."\n"?>
      Copyright:   <?=$htmlData['copyright']."\n"?>
                   All rights reserved.
      ===============================================================================
      -->
      <title><?=$htmlData['title']?></title>
      
      <meta http-equiv="Content-type" content="text/html;charset=utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <link rel="shortcut icon" href="images/icons/logo-16.png">
      
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
      <link rel="stylesheet" href="themes/<?=$htmlData['theme']['name']?>/css/bootstrap.min.css">
      <?php if ($htmlData['theme']['name']=='bootstrap') { ?><link rel="stylesheet" href="themes/bootstrap/css/bootstrap-theme.min.css"><?php } ?>
      
      
      <!-- Google Fonts -->
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
      <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
      
      <!-- Font Awesome -->
      <?php if ($htmlData['faCDN']) { ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
      <?php } else { ?>
<link rel="stylesheet" href="fonts/font-awesome/4.6.3/css/font-awesome.min.css">
      <?php } ?>
         
      <!-- jQuery -->
      <?php if ($htmlData['jQueryCDN']) { ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.12.3.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/<?=$htmlData['jQueryTheme']?>/jquery-ui.css">
      <?php } else { ?>
<script type="text/javascript" src="js/jquery/jquery-1.12.3.min.js"></script>
      <script type="text/javascript" src="js/jquery/ui/1.11.4/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="js/jquery/ui/1.11.4/themes/<?=$htmlData['jQueryTheme']?>/jquery-ui.css">
      <?php } ?>
      
      <!-- Bootstrap Javascript -->
      <script src="themes/bootstrap/js/bootstrap.min.js"></script>
      
      <!-- Bootstrap/jQuery Modal Patch -->
      <script type="text/javascript" src="js/modal.js"></script>

      <!-- Bootstrap Paginator -->
      <script src="js/bootstrap-paginator.min.js"></script>

      <!-- Custom CSS -->
      <link rel="stylesheet" href="css/bootstrap-submenu.min.css">
      <link rel="stylesheet" href="css/custom.css">

      <!-- Magnific Popup -->
      <link rel="stylesheet" href="addons/magnific/magnific-popup.css" type="text/css">
      <script async src="addons/magnific/jquery.magnific-popup.min.js"></script>
      
      <!-- Colorpicker -->
      <link rel="stylesheet" media="screen" type="text/css" href="js/colorpicker/css/colorpicker.css">
      <script type="text/javascript" src="js/colorpicker/js/colorpicker.js"></script>

      <!-- Google Code Prettify -->
      <link rel="stylesheet" href="addons/google-code-prettify/prettify.css" type="text/css">
      <script src="addons/google-code-prettify/prettify.js"></script>

      <?php if ($CONF['addon_syntaxhighlighter']) { ?><!-- Syntax Highlighter -->
      <link rel="stylesheet" href="addons/syntaxhighlighter/styles/shCore.css" type="text/css">
      <link rel="stylesheet" href="addons/syntaxhighlighter/styles/shThemeDefault.css" type="text/css">
      <script src="addons/syntaxhighlighter/scripts/shCore.js" type="text/javascript"></script>
      <script src="addons/syntaxhighlighter/scripts/shAutoloader.js" type="text/javascript"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushCss.js" type="text/javascript"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushJScript.js" type="text/javascript"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushPhp.js" type="text/javascript"></script>
      <script src="addons/syntaxhighlighter/scripts/shBrushXml.js" type="text/javascript"></script>
      <?php } ?>
      
      <?php if ($CONF['addon_select2']) { ?><!-- select2 --> 
      <link href="addons/select2/select2.css" rel="stylesheet">
      <script src="addons/select2/select2.js"></script>
      <link href="addons/select2/select2-bootstrap.css" rel="stylesheet">
      <?php } ?>
      
      <?php if ($CONF['addon_bootstrap_editable']) { ?><!-- Bootstrap Editable -->
      <link href="addons/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
      <script src="addons/bootstrap3-editable/js/bootstrap-editable.js"></script>      
      <?php } ?>

      <?php if ($CONF['addon_ckeditor']) { ?><!-- CKEditor -->
      <script src="addons/ckeditor/ckeditor.js"></script>
      <?php } ?>

      <?php if ($CONF['addon_chartjs']) { ?><!-- Chart.js -->  
      <script src="addons/chart.js/Chart.min.js"></script>
      <script src="addons/chart.js/src/Chart.HorizontalBar.js"></script>
      <?php } ?>

      <?php if ($htmlData['cookieConsent']) { ?>
      <!-- Cookie Consent by Silktide - http://silktide.com/cookieconsent -->
      <script type="text/javascript">
          window.cookieconsent_options = {
             "message":"<?=$LANG['cookie_message']?>",
             "dismiss":"<?=$LANG['cookie_dismiss']?>",
             "learnMore":"<?=$LANG['cookie_learnMore']?>",
             "link":"index.php?action=imprint",
             "theme":"dark-bottom"
          };
      </script>
      <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
      <?php } ?>
      
   </head>

   <body>

      <!-- Back to Top -->
      <span id="top-link-block" class="hidden" data-spy="affix">
         <a class="back-to-top fa fa-arrow-circle-up fa-3x" href="#top" title="Back to top..."></a>
      </span>   
   