<?php
/**
 * alert.php
 * 
 * Alert page view
 *
 * @category TeamCal Neo 
 * @version 0.9.011
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license https://georgelewe.atlassian.net/wiki/x/AoC3Ag
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.alert 
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="alert alert-<?=$alertData['type']?>">
               <h4><strong><?=$alertData['title']?>!</strong></h4>
               <hr>
               <p><strong><?=$alertData['subject']?></strong></p>
               <p><?=$alertData['text']?></p>
               <p><?=$alertData['help']?></p>
            </div>         
         </div>
         
      </div>
