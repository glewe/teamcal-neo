<?php
/**
 * Alert View
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
view.alert
-->
<div class="container content">
  <div class="col-lg-12">
    <div class="alert alert-<?= $alertData['type'] ?> fade show" role="alert">
      <h5><strong><?= $alertData['title'] ?>!</strong></h5>
      <hr>
      <p><strong><?= $alertData['subject'] ?></strong></p>
      <p><?= $alertData['text'] ?></p>
      <p><i><?= $alertData['help'] ?></i></p>
    </div>
  </div>
</div>
