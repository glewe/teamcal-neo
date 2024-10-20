<?php
/**
 * Menu View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
?>

<?php
if ($userData['defaultMenu'] === 'sidebar') { ?>
  <script>
    document.body.style.paddingTop = '0';
  </script>
  <?php
  require_once 'sidebar.php';
  echo '<main id="main" class="p-3">';
} else {
  echo '<main id="main" class="ms-0 pt-4 px-3">';
  require_once 'navbar.php';
}
?>

<?php if ($appStatus['maintenance'] && $userData['roleid'] === '1') { ?>
  <!-- Under Maintenance -->
  <div class="container content">
    <div class="col-lg-12">
      <?php
      $alertData['type'] = 'danger';
      $alertData['title'] = $LANG['alert_alert_title'];
      $alertData['subject'] = $LANG['alert_maintenance_subject'];
      $alertData['text'] = $LANG['alert_maintenance_text'];
      $alertData['help'] = $LANG['alert_maintenance_help'];
      echo createAlertBox($alertData);
      ?>
    </div>
  </div>
<?php } ?>
