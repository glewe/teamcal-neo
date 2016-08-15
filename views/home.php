<?php
/**
 * home.php
 * 
 * Home page view
 *
 * @category TeamCal Neo 
 * @version 0.9.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.home 
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['home_title']?></div>
               <div class="panel-body">
                  <?php echo $C->read("welcomeText"); ?>
               </div>
            </div>
         </div>
         
      </div>
      
      <script>
      // Check if a new cache is available on page load.
      window.addEventListener('load', function(e) {

        window.applicationCache.addEventListener('updateready', function(e) {
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
