<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Alert View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo Pro
 * @subpackage Views
 * @since 3.0.0
 */
?>

      <!-- ==================================================================== 
      view.alert 
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            <div class="alert alert-<?=$alertData['type']?>">
               <h5><strong><?=$alertData['title']?>!</strong></h5>
               <hr>
               <p><strong><?=$alertData['subject']?></strong></p>
               <p><?=$alertData['text']?></p>
               <p><?=$alertData['help']?></p>
            </div>         
         </div>
         
      </div>
