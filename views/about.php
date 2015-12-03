<?php
/**
 * about.php
 * 
 * The view of the about page
 *
 * @category TeamCal Neo 
 * @version 0.3.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.about
      -->
      <div class="container content">
      
         <div class="col-lg-12">
            
            <div class="panel panel-<?=$CONF['controllers'][$controller]->panelColor?>">
               <div class="panel-heading"><i class="fa fa-<?=$CONF['controllers'][$controller]->faIcon?> fa-lg fa-menu"></i><?=$LANG['mnu_help_about']?></div>
               <div class="panel-body">
                  <div class="col-lg-3"><img src="images/icons/logo-128.png" alt="" class="img_floatleft">
                  </div>
                  <div class="col-lg-9">
                     <h2><?=$CONF['app_name']?></h2>
                     <p>
                        <strong><?=$LANG['about_version']?>:</strong>&nbsp;&nbsp;<?=$CONF['app_version']?><br>
                        <strong><?=$LANG['about_copyright']?>:</strong>&nbsp;&nbsp;&copy;&nbsp;<?=$CONF['app_year'] . "-" . $CONF['app_curr_year']?> by <a class="about" href="http://www.lewe.com/" target="_blank"><?=$CONF['app_author']?></a><br>
                        <strong><?=$LANG['about_license']?>:</strong>&nbsp;&nbsp;<?=$LANG['license']?><br>
                     </p>
                     <h3><?=$LANG['about_credits']?>:</h3>
                     <ul>
                        <li>Bootstrap Team <?=$LANG['about_for']?> <a href="http://getbootstrap.com/" target="_blank">Bootstrap framework</a></li>
                        <li>Thomas Park <?=$LANG['about_for']?> <a href="http://bootswatch.com/" target="_blank">Bootswatch themes</a></li>
                        <li>Yun Lai <?=$LANG['about_for']?> <a href="http://bootstrappaginator.org/" target="_blank">Bootstrap Paginator</a></li>
                        <li>Dave Gandy <?=$LANG['about_for']?> <a href="https://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome</a></li>
                        <li>jQuery Team <?=$LANG['about_for']?> <a href="http://www.jquery.com/" target="_blank">jQuery</a> <?=$LANG['about_and']?> <a href="http://www.jqueryui.com/" target="_blank">jQuery UI</a></li>
                        <li>Stefan Petre <?=$LANG['about_for']?> <a href="http://www.eyecon.ro/colorpicker/" target="_blank">jQuery Color Picker</a></li>
                        <li>David Vignoni <?=$LANG['about_for']?> <a href="http://www.icon-king.com" target="_blank">Nuvola Icons</a></li>
                        <li>Iconshock Team <?=$LANG['about_for']?> <a href="http://www.iconshock.com/icon_sets/vector-user-icons/" target="_blank">User Icons</a></li>
                        <li>Drew Phillips <?=$LANG['about_for']?> <a href="https://www.phpcaptcha.org/" target="_blank">SecureImage</a></li>
                        <li>Nick Downie <?=$LANG['about_for']?> <a href="https://www.chartjs.org/" target="_blank">Chart.js</a></li>
                        <li>Promotably <?=$LANG['about_for']?> <a href="https://devblog.promotably.com/how-to-create-horizontal-bar-charts-with-chartjs/" target="_blank">HorizontalBar for Chart.js</a></li>
                        <li><?=$LANG['about_misc']?></li>
                     </ul>
                  </div>
               </div>
            </div>

<p><a class="btn" data-toggle="collapse" data-target="#releaseinfo"><?=$LANG['about_view_releaseinfo']?></a></p>
<pre class="prettyprint collapse" style="font-size: 100%; padding: 16px;" id="releaseinfo">
<?php include('doc/Releaseinfo.txt');?>
</pre>
<script>!function ($) { $(function(){ window.prettyPrint && prettyPrint() }) }(window.jQuery)</script>

         </div>
         
      </div>