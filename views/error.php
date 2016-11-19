<?php
/**
 * error.php
 * 
 * Error page view
 *
 * @category TeamCal Neo 
 * @version 1.2.000
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>
<!DOCTYPE html>
<!--
===============================================================================
Application: TeamCal Neo
Author:      George Lewe
Copyright:   (c) 2014-2016
             All rights reserved.
===============================================================================
-->
<html>
   <head>
      <title>TeamCal Neo Error</title>
      <meta http-equiv="Content-type" content="text/html;charset=utf8">
      <meta charset="utf-8">
      <link rel="stylesheet" href="themes/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="themes/bootstrap/css/bootstrap-theme.min.css">
   </head>
   <body>
      <!-- ==================================================================== 
      view.error 
      -->
      <div class="container content">
         <div class="col-lg-12">
            <p></p>
            <div class="alert alert-danger">
               <h4><strong><?=$errorData['title']?>!</strong></h4>
               <hr>
               <p><strong><?=$errorData['subject']?></strong></p>
               <p><?=$errorData['text']?></p>
               <p></p>
            </div>         
         </div>
      </div>
   </body>
</html>
