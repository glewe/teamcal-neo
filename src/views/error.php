<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Error View
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
<!--
===============================================================================
Application: TeamCal Neo
Author:      George Lewe
Copyright:   (c) 2014-2020
             All rights reserved.
===============================================================================
-->
<!DOCTYPE html>
<html lang="en">
<head>
  <title>TeamCal Neo Error</title>
  <meta http-equiv="Content-type" content="text/html;charset=utf8">
  <meta charset="utf-8">
  <link rel="stylesheet" href="themes/bootstrap/bootstrap.min.css">
</head>

<body>
<!-- ====================================================================
view.error
-->
<div class="container content" style="padding-left: 4px; padding-right: 4px;">
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <h5><strong><?= $errorData['title'] ?>!</strong></h5>
    <hr>
    <p><strong><?= $errorData['subject'] ?></strong></p>
    <p><?= $errorData['text'] ?></p>
    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" title="<?= $LANG['close_this_message'] ?>"></button>
  </div>
</div>
</body>

</html>
