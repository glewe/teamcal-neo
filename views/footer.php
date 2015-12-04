<?php
/**
 * footer.php
 * 
 * The view of the footer
 *
 * @category TeamCal Neo 
 * @version 0.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2015 by George Lewe
 * @link http://www.lewe.com
 * @license
 */
if (!defined('VALID_ROOT')) die('No direct access allowed!');
?>

      <!-- ==================================================================== 
      view.footer 
      -->
      <footer class="footer">
         <div class="container">
               
            <div class="col-lg-3">
               <ul class="list-unstyled">
                  <li><?=$C->read("appFooterCpy")?></li>
               </ul>
            </div>
            
            <div class="col-lg-3">
               <ul class="list-unstyled">
                  <li><a href="index.php">Home</a></li>
               </ul>
            </div>
            
            <div class="col-lg-3">
               <ul class="list-unstyled">
                  <li><a href="index.php?action=about">About</a></li>
                  <li><a href="index.php?action=imprint">Imprint</a></li>
               </ul>
            </div>
            
            <div class="col-lg-3 text-right">
               <a href="#top" title="<?=$LANG['back_to_top']?>"><i class="glyphicon glyphicon-arrow-up"></i></a><br>
               <img src="images/valid-html5.gif" alt="Valid HTML5"><br>
               <img src="images/valid-css3.gif" alt="Valid CSS3"><br>
               <img src="images/responsive.gif" alt="Responsive"><br>
               <br>
               <i id="size" class="small"></i>
            </div>
            
         </div>
         
         <div class="container">
            <div class="col-lg-12 text-right small"><?=$CONF['app_powered_by']?></div>
         </div>
         
      </footer>

      <script type="text/javascript">

         /**
          * Initialize tooltip
          */
         $(document).ready(function(){
            $("[data-position=tooltip-top]").tooltip({
               placement : 'top',
               html: true
            });
            $("[data-position=tooltip-right]").tooltip({
              placement : 'right',
              html: true
            });
            $("[data-position=tooltip-bottom]").tooltip({
               placement : 'bottom',
               html: true
            });
            $("[data-position=tooltip-left]").tooltip({
               placement : 'left',
               html: true
            });
         });

         /**
          * Window size in footer
          */
         $(window).on('resize', showSize);
         showSize();
         function showSize() { $('#size').html($(window).width() + ' x ' + $(window).height()); }
         
         <?php if ($C->read("googleAnalytics") AND $C->read("googleAnalyticsID")) { ?>
         
         /**
          * Google Analytics
          */
         var _gaq = _gaq || [];
         _gaq.push(['_setAccount', '<?=$C->read("googleAnalyticsID")?>']);
         _gaq.push(['_trackPageview']);
         (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
         })();

         /**
          * Opt out Google Analytics
          */
         // Set to the same value as the web property used on the site
         var gaID = '<?=$C->read('googleAnalyticsID');?>';
          
         // Disable tracking if the opt-out cookie exists.
         var disableStr = 'ga-disable-' + gaID;
         if (document.cookie.indexOf(disableStr + '=true') > -1) {
           window[disableStr] = true;
         }
          
         // Opt-out function
         function gaOptout() {
           document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
           window[disableStr] = true;
         }
         <?php } ?>
         
      </script>
      
   </body>
</html>
