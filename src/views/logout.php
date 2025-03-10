<?php
/**
 * Logout View
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
view.logout
-->
<script>
  document.cookie = '<?= $viewData['cookie_name'] ?>=; expires=Thu, 01 Jan 1970 00:00:01 UTC; path=/';
</script>
<div class="container content">
  <div class="row">

    <div class="col-lg-3"></div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['logout_title'] ?></div>
        <div class="card-body">
          <div class="col-lg-12">
            <p><?= $LANG['logout_comment'] ?></p>
            <div class="divider">
              <hr>
            </div>
            <a href="index.php?action=login" class="btn btn-primary"><?= $LANG['btn_login'] ?></a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3"></div>

  </div>
</div>
