<?php
/**
 * footer.php
 * 
 * The view of the footer
 *
 * @category TeamCal Neo 
 * @version 0.9.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
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
                  <li>&copy; <?=date('Y')." ".$C->read("appFooterCpy")?></li>
               </ul>
            </div>
            
            <div class="col-lg-3">
               <ul class="list-unstyled">
                  <li><a href="index.php"><?=$LANG['footer_home']?></a></li>
               </ul>
            </div>
            
            <div class="col-lg-3">
               <ul class="list-unstyled">
                  <?php if ($docLink = $C->read("userManual")) {?>
                     <li><a href="<?=urldecode($docLink)?>" target="_blank"><?=$LANG['footer_help']?></a></li>
                  <?php } ?>
                  <li><a href="index.php?action=about"><?=$LANG['footer_about']?></a></li>
                  <li><a href="index.php?action=imprint"><?=$LANG['footer_imprint']?></a></li>
               </ul>
            </div>
            
            <div class="col-lg-3 text-right">
               <?php if ($C->read("showSize")) { ?><i id="size" class="text-italic xsmall"></i><?php } ?>
            </div>
            
         </div>
         
         <!-- As per the license agreement, you are not allowed to change or remove the following block! -->
         <div class="container" style="margin-top: 40px">
            <div class="col-lg-12 text-right text-italic xsmall"><?=$CONF['app_powered']?></div>
         </div>
         
      </footer>

      <script type="text/javascript">

         $(document).ready(function(){
            /**
             * Bootstrap Submenu
             */
            $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
               event.preventDefault(); 
               event.stopPropagation(); 
               $(this).parent().siblings().removeClass('open');
               $(this).parent().toggleClass('open');
            });
            
            /**
             * Magnific Popup
             */
            $('.image-popup').magnificPopup({
               type: 'image',
               closeOnContentClick: true,
               closeBtnInside: false,
               fixedContentPos: true,
               mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
               image: {
                  verticalFit: true
               },
               zoom: {
                  enabled: true,
                  duration: 300 // don't forget to change the duration also in CSS
               }
            });
            
            $('.image-popup-vertical-fit').magnificPopup({
               type: 'image',
               closeOnContentClick: true,
               mainClass: 'mfp-img-mobile',
               image: {
                  verticalFit: true
               }
            });

            $('.image-popup-fit-width').magnificPopup({
               type: 'image',
               closeOnContentClick: true,
               image: {
                  verticalFit: false
               }
            });
                     
            /**
             * Tooltip
             */
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
          * Back to Top Icon
          */
         $(function() {
            $(window).scroll(function() {
                if($(window).scrollTop() >= 400) { // Set vertical offset in pixel when to appear  
                   $('#top-link-block').removeClass('hidden');
                   $('#top-link-block').fadeIn('fast');
                }else{
                   $('#top-link-block').fadeOut('fast');
                }
            });
         });

         <?php if ($C->read("showSize")) { ?>
            /**
             * Window size in footer
             */
            $(window).on('resize', showSize);
            showSize();
            function showSize() { $('#size').html($(window).width() + ' x ' + $(window).height()); }
         <?php } ?>
         
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
