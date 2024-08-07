<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Maintenance View
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
view.maintenance
-->
<div class="container content">

  <div class="col-lg-12">
    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="fas fa-question-circle fa-lg"></i></a>';
      }
      ?>
      <div class="card-header text-white bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['mtce_title'] . $pageHelp ?></div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-3"><img src="images/maintenance.gif" alt="" class="img_floatleft">
          </div>
          <div class="col-lg-9">
            <h4><?= $LANG['mtce_title'] ?></h4>
            <p><?= $LANG['mtce_text'] ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
