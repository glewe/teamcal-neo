<?php
/**
 * home.php
 * 
 * The view of the home page
 *
 * @category TeamCal Neo 
 * @version 0.3.00
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
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
                  <p>
                     <?php if ($C->read("welcomeIcon")) echo '<img src="'.$CONF['app_homepage_dir'].'/'.$C->read("welcomeIcon").'" alt="" class="img_floatleft">'; ?>
                     <h3><?=stripslashes($C->read("welcomeTitle"))?></h3>
                     <?=nl2br(html_entity_decode(stripslashes($C->read("welcomeText"))))?>
                  </p>
               </div>
            </div>
         </div>
         
      </div>
