<?php
if (!defined('VALID_ROOT')) exit('');
/**
 * Footer View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2020 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @subpackage Views
 * @since 3.0.0
 */
?>

      <!-- ==================================================================== 
      view.footer 
      -->
      <footer class="footer">
         <div class="container">
            <div class="row">
               
               <div class="col-lg-4">
                  <ul class="list-unstyled">
                     <?php
                     $footerCopyright = "";
                     if ($copyright = $C->read("footerCopyright")) {
                        $footerCopyright .= "&copy; ".date('Y')." by ";
                        if ($copyrightUrl = $C->read("footerCopyrightUrl")) {
                           $footerCopyright .= '<a href="'.$copyrightUrl.'" target="_blank">'.$copyright.'</a>';
                        }
                        else {
                           $footerCopyright .= $copyright;
                        }
                     }
                     ?>
                     <li><?=$footerCopyright?></li>
                  </ul>
               </div>
               
               <div class="col-lg-4">
                  <ul class="list-unstyled">
                     <li><a href="index.php"><?=$LANG['footer_home']?></a></li>
                     <?php if ($docLink = $C->read("userManual")) {?>
                        <li><a href="<?=urldecode($docLink)?>" target="_blank"><?=$LANG['footer_help']?></a></li>
                     <?php } ?>
                     <li><a href="index.php?action=about"><?=$LANG['footer_about']?></a></li>
                     <li><a href="index.php?action=imprint"><?=$LANG['footer_imprint']?></a></li>
                     <li><a href="index.php?action=dataprivacy"><?=$LANG['footer_dataprivacy']?></a></li>
                  </ul>
               </div>
            
               <div class="col-lg-4 text-right">
                  <?php if ($urls = $C->read("footerSocialLinks") AND strlen($urls)) {
                     $urlArray = explode(';', $urls);
                     foreach ($urlArray as $url) { 
                        if (strlen($url)) { ?>
                           <span class="social-icon"><a href="<?=$url?>" target="_blank"><i class="fab fa-lg"></i></a></span>                     
                        <?php } 
                     }
                  } ?>
               </div>
            </div>
         </div>
         
         <!-- As per the license agreement, you are not allowed to change or remove the following block! -->
         <div class="container" style="margin-top: 40px">
            <div class="col-lg-12 text-right text-italic xsmall">
               <?=APP_POWERED?><br>
               <?php if ($C->read("footerViewport")) { ?><i id="size" class="text-italic xsmall"></i><?php } ?>
            </div>
         </div>
         
      </footer>

      <script>

         $(document).ready(function()
         {
            <?php if (MAGNIFICPOPUP) { ?>
            //
            // Magnific Popup
            //
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
            <?php } ?>
                     
            //
            // Tooltip
            //
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

         //
         // Back to Top Icon
         //
         $(function() {
            var btn = $('#top-link-block');
            $(window).scroll(function() {
               if ($(window).scrollTop() > 400) {
                  btn.addClass('show');
               } else {
                  btn.removeClass('show');
               }
            });
            btn.on('click', function(e) {
            e.preventDefault();
               $('html, body').animate({scrollTop:0}, '400');
            });
         });

         <?php if ($C->read("footerViewport")) { ?>
         /**
         * Window size in footer
         */
         $(window).on('resize', showSize);
         showSize();
         function showSize() { $('#size').html($(window).width() + ' x ' + $(window).height()); }
         <?php } ?>
         
         <?php if ($C->read("googleAnalytics") AND $C->read("googleAnalyticsID")) { ?>
         //
         // Google Analytics
         //
         (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

         ga('create', '<?=$C->read("googleAnalyticsID")?>', 'auto');
         ga('send', 'pageview');

         //
         // Opt out Google Analytics
         //
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
