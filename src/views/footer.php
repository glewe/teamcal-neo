<?php
/*
 * Footer View
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
view.footer
-->
<footer class="footer fs-small">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-3 col-md-3 col-sm-3 text-start">
        <ul class="list-unstyled">
          <?php
          $footerCopyright = "";
          if ($copyright = $C->read("footerCopyright")) {
            $footerCopyright .= "&copy; " . date('Y') . " by ";
            if ($copyrightUrl = $C->read("footerCopyrightUrl")) {
              $footerCopyright .= '<a href="' . $copyrightUrl . '" target="_blank">' . $copyright . '</a>';
            }
            else {
              $footerCopyright .= $copyright;
            }
          }
          ?>
          <li class="text-muted fs-small"><?= $footerCopyright ?></li>
        </ul>
      </div>

      <div class="col-lg-6 col-md-6 col-sm-6 text-center">
        <ul class="list-unstyled">
          <li class="text-muted size"><a href="index.php?action=about"><?= $LANG['footer_about'] ?></a> | <a href="index.php?action=imprint"><?= $LANG['footer_imprint'] ?></a> | <a href="index.php?action=dataprivacy"><?= $LANG['footer_dataprivacy'] ?></a></li>
          <!-- As per the license agreement, you are not allowed to change or remove the following line! -->
          <li class="text-muted fst-italic fs-small mt-1"><?= APP_POWERED ?></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-3 text-end">
        <?php if (($urls = $C->read("footerSocialLinks")) && strlen($urls)) {
          $urlArray = explode(';', $urls);
          foreach ($urlArray as $url) {
            if (strlen($url)) { ?>
              <span class="social-icon"><a href="<?= $url ?>" target="_blank" rel="noopener"><i class="fab fa-lg"></i></a></span>
            <?php }
          }
        } ?>
      </div>
    </div>
  </div>

  <!-- As per the license agreement, you are not allowed to change or remove the following block! -->
  <div class="container" style="margin-top: 40px">
    <div class="col-lg-12 text-end text-italic xsmall">
      <?php if (!$C->read("footerViewport")) { ?>
        <i id="size" class="text-italic xsmall"></i>
      <?php } ?>
    </div>
  </div>

</footer>
</main>

<!-- DataTables -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>-->
<!--<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.2/b-3.1.0/b-html5-3.1.0/r-3.0.2/datatables.min.js"></script>-->
<script src="addons/datatables/<?= DATATABLES_VER ?>/datatables.min.js"></script>

<!-- TeamCal Neo Width Mode Switcher -->
<script src="js/width-modes.min.js"></script>

<script>
  $(document).ready(function () {
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
    $('[data-bs-toggle="tooltip"]').each(function () {
      try {
        var options = {
          html: true
        };
        if ($(this)[0].hasAttribute('data-type')) {
          options['template'] =
            '<div class="tooltip ' + $(this).attr('data-type') + '" role="tooltip">' +
            '  <div class="tooltip-arrow"></div>' +
            '  <div class="tooltip-inner"></div>' +
            '</div>';
        }
        $(this).tooltip(options);
      } catch (e) {
        console.warn('Tooltip initialization error:', e);
      }
    });

    //
    // Focus search input when Search User modal is shown in calendarview
    //
    $('#modalSearchUser').on('shown.bs.modal', function () {
      $('#searchUser').focus();
    });

    //
    // Submit search when Enter key is pressed in search input
    //
    $('#searchUser').on('keypress', function (e) {
      if (e.which == 13) {
        $(this).closest('.modal').find('button[name="btn_search"]').click();
        return false;
      }
    });

  });

  //
  // Back to Top Icon
  //
  $(function () {
    var btn = $('#top-link-block');
    $(window).scroll(function () {
      if ($(window).scrollTop() > 400) {
        btn.addClass('show');
      } else {
        btn.removeClass('show');
      }
    });
    btn.on('click', function (e) {
      e.preventDefault();
      $('html, body').animate({
        scrollTop: 0
      }, '400');
    });
  });

  <?php if ($C->read("footerViewport")) { ?>
    /**
     * Window size in footer
     */
    $(window).on('resize', showSize);
    showSize();

    function showSize() {
      $('#size').html($(window).width() + ' x ' + $(window).height());
    }
  <?php } ?>

</script>

</body>

</html>