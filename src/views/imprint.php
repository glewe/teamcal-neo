<?php
/**
 * Imprint View
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
view.imprint
-->
<div class="container content">
  <div class="col-lg-12">

    <div class="card">
      <?php
      $pageHelp = '';
      if ($C->read('pageHelp')) {
        $pageHelp = '<a href="' . $CONF['controllers'][$controller]->docurl . '" target="_blank" class="float-end" style="color:inherit;"><i class="bi bi-question-circle-fill bi-lg"></i></a>';
      }
      ?>
      <div class="card-header bg-<?= $CONF['controllers'][$controller]->panelColor ?>"><i class="<?= $CONF['controllers'][$controller]->faIcon ?> fa-lg me-3"></i><?= $LANG['mnu_help_imprint'] . $pageHelp ?></div>
      <div class="card-body">
        <div class="col-lg-12">
          <?php foreach ($LANG['imprint'] as $imprint) { ?>
            <h4 style="margin-top: 40px"><?= $imprint['title'] ?></h4>
            <?= $imprint['text'] ?>
          <?php } ?>
        </div>
      </div>
    </div>

  </div>
</div>
