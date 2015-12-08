<?php
/**
 * header.php
 * 
 * The view of the HTML header
 *
 * @category TeamCal Neo 
 * @version 0.3.005
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?><!DOCTYPE html>
<!--
===============================================================================
Application: <?=$htmlData['application']." ".$htmlData['version']."\n"?>
Author:      <?=$htmlData['author']."\n"?>
Copyright:   <?=$htmlData['copyright']."\n"?>
             All rights reserved.
===============================================================================
-->
<html lang="en">

   <head>
      <title><?=$htmlData['title']?></title>
      
      <meta http-equiv="Content-type" content="text/html;charset=utf8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <meta name="keywords" content="Lewe <?=$CONF['app_name']?>">
      <meta name="description" content="Lewe <?=$CONF['app_name']?> - The Online Calendar!">
      
      <link rel="shortcut icon" href="images/icons/logo-16.png">
      
      <!-- Theme CSS -->
      <link rel="stylesheet" href="themes/<?=$htmlData['theme']?>/css/bootstrap.min.css">
      <?php if ($htmlData['theme']=='bootstrap') { ?><link rel="stylesheet" href="themes/bootstrap/css/bootstrap-theme.min.css"><?php } ?>
      
      
      <!-- Google Fonts -->
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
      
      <!-- Font Awesome -->
      <?php if ($htmlData['faCDN']) { ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
      <?php } else { ?>
<link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
      <?php } ?>
         
      <!-- jQuery -->
      <?php if ($htmlData['jQueryCDN']) { ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/<?=$htmlData['jQueryTheme']?>/jquery-ui.css">
      <?php } else { ?>
<script type="text/javascript" src="js/jquery/jquery-1.11.0.min.js"></script>
      <script type="text/javascript" src="js/jquery/ui/1.10.4/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="js/jquery/ui/1.10.4/themes/<?=$htmlData['jQueryTheme']?>/jquery-ui.css">
      <?php } ?>
      
      <!-- Bootstrap Javascript -->
      <script src="themes/bootstrap/js/bootstrap.min.js"></script>
      
      <!-- Custom CSS -->
      <link rel="stylesheet" href="css/bootstrap-submenu.css">
      <link rel="stylesheet" href="css/custom.css">

      <!-- Colorpicker -->
      <link rel="stylesheet" media="screen" type="text/css" href="js/colorpicker/css/colorpicker.css">
      <script type="text/javascript" src="js/colorpicker/js/colorpicker.js"></script>

      <!-- Bootstrap/jQuery Modal Patch -->
      <script type="text/javascript" src="js/modal.js"></script>

      <!-- Bootstrap Paginator -->
      <script src="js/bootstrap-paginator.min.js"></script>
      
      <!-- select2 --> 
      <link href="addons/select2/select2.css" rel="stylesheet">
      <script src="addons/select2/select2.js"></script>
                     
      <!-- select2 bootstrap -->
      <link href="addons/select2/select2-bootstrap.css" rel="stylesheet">
      
      <!-- Bootstrap Editable -->
      <link href="addons/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
      <script src="addons/bootstrap3-editable/js/bootstrap-editable.js"></script>      

      <!-- CKEditor --> 
      <script src="addons/ckeditor/ckeditor.js"></script>

      <!-- Google Code Prettify -->
      <link rel="stylesheet" href="addons/google-code-prettify/prettify.css" type="text/css">
      <script src="addons/google-code-prettify/prettify.js"></script>

      <!-- Chart.js --> 
      <script src="addons/chart.js/Chart.min.js"></script>
      <script src="addons/chart.js/src/Chart.HorizontalBar.js"></script>
      
      <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
         <script src="js/html5shiv.js"></script>
         <script src="js/respond.min.js"></script>
      <![endif]-->
   </head>

   <body>
