<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Home View
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
view.home
-->
<div class="container content">

  <div class="col-lg-12">

    <?php
    if (
      ($showAlert && $C->read("showAlerts") != "none") &&
      ($C->read("showAlerts") == "all" || $C->read("showAlerts") == "warnings" && ($alertData['type'] == "warning" || $alertData['type'] == "danger"))
    ) {
      echo createAlertBox($alertData);
    }
    ?>

    <div class="card text-<?= $CONF['controllers'][$controller]->panelColor ?>">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
      }
      ?>
      <div class="card-header"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['home_title'] . $pageHelp ?></div>
      <div class="card-body">
        <?php echo $C->read("welcomeText"); ?>
      </div>
    </div>
  </div>

</div>

<script>
  // Check if a new cache is available on page load.
  window.addEventListener('load', function (e) {
    window.applicationCache.addEventListener('updateready', function (e) {
      if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
        // Browser downloaded a new app cache.
        // Swap it in and reload the page to get the new hotness.
        window.applicationCache.swapCache();
        if (confirm('A new version of this site is available. Load it?')) {
          window.location.reload();
        }
      } else {
        // Manifest didn't changed. Nothing new to server.
      }
    }, false);
  }, false);
</script>
