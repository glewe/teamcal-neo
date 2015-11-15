<?php
/**
 * error.php
 * 
 * The view of the alert page
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>
<!DOCTYPE html>
<!--
===============================================================================
Version:     TeamCal Neo 0.2.00
Author:      George Lewe
Copyright:   (c) 2014 by George Lewe (http://www.lewe.com)
             All rights reserved.
===============================================================================
-->
<html lang="en">
   <head>
      <title>TeamCal Neo</title>
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
