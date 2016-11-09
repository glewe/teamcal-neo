<?php
/**
 * logout.php
 * 
 * Logout page view
 *
 * @category TeamCal Neo 
 * @version 1.0.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.logout 
      -->
      <script>document.cookie = '<?=$viewData['cookie_name']?>=; expires=Thu, 01 Jan 1970 00:00:01 UTC; path=/';</script>
      <div class="container content">
      
         <div class="col-lg-3"></div>
         
         <div class="col-lg-6">
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['logout_title']?></div>
               <div class="panel-body">
                  <div class="col-lg-12">
                     <p><?=$LANG['logout_comment']?></p>
                     <div class="divider"><hr></div>
                     <a href="index.php?action=login" class="btn btn-primary" tabindex="1"><?=$LANG['btn_login']?></a>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-lg-3"></div>
               
      </div>
