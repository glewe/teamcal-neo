<?php
/**
 * Sidebar View
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 4.0.0
 */
?>
<!-- ====================================================================
view.sidebar
-->
<!--begin::Sidebar-->
<aside id="sidebar">
  <div class="d-flex">
    <button class="sidebar-toggle" type="button">
      <i class="bi-calendar-week navbar-logo logo-gradient"></i>
    </button>
    <div class="sidebar-logo">
      <a href="<?= WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['home']->name ?>"><?= APP_NAME ?></a>
    </div>
  </div>
  <ul class="sidebar-nav">

    <!--Home-->
    <li class="sidebar-item">
      <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#home" aria-expanded="false" aria-controls="home">
        <i class="bi-house"></i>
        <span><?= $LANG['mnu_app'] ?></span>
      </a>
      <ul id="home" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
        <?php
        $item = [
          'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['home']->name,
          'icon' => $CONF['controllers']['home']->faIcon,
          'label' => $LANG['mnu_app_homepage']
        ];
        echo createSidebarItem($item);
        ?>
        <?php if ($userData['isLoggedIn']) {
          foreach ($appLanguages as $appLang) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?' . str_replace('&', '&amp;', $_SERVER['QUERY_STRING']) . '&amp;applang=' . $appLang,
              'icon' => 'bi-translate',
              'label' => proper($appLang),
            ];
            if ($appLang === $language) {
              $item['suffix'] = '<i class="bi-check-lg ms-2 text-success"></i>';
            }
            echo createSidebarItem($item);
          }
        }
        ?>
      </ul>
    </li>

    <!--View-->
    <?php if ((isAllowed($CONF['controllers']['messages']->permission) && $C->read('activateMessages')) ||
      isAllowed($CONF['controllers']['calendarview']->permission) ||
      isAllowed($CONF['controllers']['year']->permission) ||
      isAllowed($CONF['controllers']['remainder']->permission) ||
      isAllowed($CONF['controllers']['statsabsence']->permission) ||
      isAllowed($CONF['controllers']['statsabstype']->permission) ||
      isAllowed($CONF['controllers']['statspresence']->permission) ||
      isAllowed($CONF['controllers']['statsremainder']->permission) ||
      isAllowed($CONF['controllers']['absum']->permission)
    ) { ?>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#view" aria-expanded="false" aria-controls="view">
          <i class="bi-window"></i>
          <span><?= $LANG['mnu_view'] ?></span>
        </a>
        <ul id="view" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <?php
          if (isAllowed($CONF['controllers']['calendarview']->permission)) {
            if ($controller == 'logout' || (!$urlparams = $UO->read($UL->username, 'calfilter'))) {
              $urlparams = "";
            }
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['calendarview']->name . $urlparams,
              'icon' => $CONF['controllers']['calendarview']->faIcon,
              'label' => $LANG['mnu_view_calendar']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['year']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['year']->name . '&amp;year=' . date('Y') . '&amp;region=1&amp;user=' . $UL->username,
              'icon' => $CONF['controllers']['year']->faIcon,
              'label' => $LANG['mnu_view_year']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['remainder']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['remainder']->name,
              'icon' => $CONF['controllers']['remainder']->faIcon,
              'label' => $LANG['mnu_view_remainder']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['messages']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['messages']->name,
              'icon' => $CONF['controllers']['messages']->faIcon,
              'label' => $LANG['mnu_view_messages']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['statsabsence']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['statsabsence']->name,
              'icon' => $CONF['controllers']['statsabsence']->faIcon,
              'label' => $LANG['mnu_view_stats_absences']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['statspresence']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['statspresence']->name,
              'icon' => $CONF['controllers']['statspresence']->faIcon,
              'label' => $LANG['mnu_view_stats_presences']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['statsabstype']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['statsabstype']->name,
              'icon' => $CONF['controllers']['statsabstype']->faIcon,
              'label' => $LANG['mnu_view_stats_abstype']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['statsremainder']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['statsremainder']->name,
              'icon' => $CONF['controllers']['statsremainder']->faIcon,
              'label' => $LANG['mnu_view_stats_remainder']
            ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['absum']->permission)) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['absum']->name . '&amp;user=' . $userData['username'],
              'icon' => $CONF['controllers']['absum']->faIcon,
              'label' => $LANG['mnu_view_stats_absum']
            ];
            echo createSidebarItem($item);
          }
          ?>
        </ul>
      </li>
    <?php } ?>

    <!--Edit-->
    <?php if (
      isAllowed($CONF['controllers']['calendaredit']->permission) ||
      isAllowed($CONF['controllers']['monthedit']->permission) ||
      isAllowed($CONF['controllers']['messageedit']->permission) ||
      isAllowed($CONF['controllers']['attachments']->permission)
    ) { ?>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#edit" aria-expanded="false" aria-controls="edit">
          <i class="bi-pencil-square"></i>
          <span><?= $LANG['mnu_edit'] ?></span>
        </a>
        <ul id="edit" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <?php
          if (isAllowed($CONF['controllers']['calendaredit']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['calendaredit']->name . '&amp;month=' . date('Y') . date('m') . '&amp;region=1&amp;user=' . $userData['username'],
              'icon' => $CONF['controllers']['calendaredit']->faIcon,
              'label' => $LANG['mnu_edit_calendaredit'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['monthedit']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['monthedit']->name . '&amp;month=' . date('Y') . date('m') . '&amp;region=1',
              'icon' => $CONF['controllers']['monthedit']->faIcon,
              'label' => $LANG['mnu_edit_monthedit'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['messageedit']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['messageedit']->name,
              'icon' => $CONF['controllers']['messageedit']->faIcon,
              'label' => $LANG['mnu_edit_messageedit'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['attachments']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['attachments']->name,
              'icon' => $CONF['controllers']['attachments']->faIcon,
              'label' => $LANG['mnu_edit_attachments'] ];
            echo createSidebarItem($item);
          }
          ?>
        </ul>
      </li>
    <?php } ?>

    <!--CalendarAdmin-->
    <?php if (
      isAllowed($CONF['controllers']['calendaroptions']->permission) ||
      isAllowed($CONF['controllers']['absences']->permission) ||
      isAllowed($CONF['controllers']['holidays']->permission) ||
      isAllowed($CONF['controllers']['patterns']->permission) ||
      isAllowed($CONF['controllers']['regions']->permission) ||
      isAllowed($CONF['controllers']['declination']->permission) ||
      isAllowed($CONF['controllers']['bulkedit']->permission)
    ) { ?>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#caladmin" aria-expanded="false" aria-controls="caladmin">
          <i class="bi-calendar3"></i>
          <span><?= $LANG['mnu_admin'] ?></span>
        </a>
        <ul id="caladmin" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <?php
          if (isAllowed($CONF['controllers']['absences']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['absences']->name,
              'icon' => $CONF['controllers']['absences']->faIcon,
              'label' => $LANG['mnu_admin_absences'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['holidays']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['holidays']->name,
              'icon' => $CONF['controllers']['holidays']->faIcon,
              'label' => $LANG['mnu_admin_holidays'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['regions']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['regions']->name,
              'icon' => $CONF['controllers']['regions']->faIcon,
              'label' => $LANG['mnu_admin_regions'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['patterns']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['patterns']->name,
              'icon' => $CONF['controllers']['patterns']->faIcon,
              'label' => $LANG['mnu_admin_patterns'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['bulkedit']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['bulkedit']->name,
              'icon' => $CONF['controllers']['bulkedit']->faIcon,
              'label' => $LANG['mnu_admin_bulkedit'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['declination']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['declination']->name,
              'icon' => $CONF['controllers']['declination']->faIcon,
              'label' => $LANG['mnu_admin_declination'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['calendaroptions']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['calendaroptions']->name,
              'icon' => $CONF['controllers']['calendaroptions']->faIcon,
              'label' => $LANG['mnu_admin_calendaroptions'] ];
            echo createSidebarItem($item);
          }
          ?>
        </ul>
      </li>
    <?php } ?>

    <!--Admin-->
    <?php if (
      isAllowed($CONF['controllers']['config']->permission) ||
      isAllowed($CONF['controllers']['permissions']->permission) ||
      isAllowed($CONF['controllers']['users']->permission) ||
      isAllowed($CONF['controllers']['groups']->permission) || $UG->isGroupManager($UL->username) ||
      isAllowed($CONF['controllers']['roles']->permission) ||
      isAllowed($CONF['controllers']['database']->permission) ||
      isAllowed($CONF['controllers']['log']->permission) ||
      isAllowed($CONF['controllers']['phpinfo']->permission)
    ) { ?>
      <li class="sidebar-item">
        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#admin" aria-expanded="false" aria-controls="admin">
          <i class="bi-gear-fill"></i>
          <span><?= $LANG['mnu_admin'] ?></span>
        </a>
        <ul id="admin" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
          <?php
          if (isAllowed($CONF['controllers']['config']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['config']->name,
              'icon' => $CONF['controllers']['config']->faIcon,
              'label' => $LANG['mnu_admin_config'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['permissions']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['permissions']->name,
              'icon' => $CONF['controllers']['permissions']->faIcon,
              'label' => $LANG['mnu_admin_perm'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['users']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['users']->name,
              'icon' => $CONF['controllers']['users']->faIcon,
              'label' => $LANG['mnu_admin_users'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['groups']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['groups']->name,
              'icon' => $CONF['controllers']['groups']->faIcon,
              'label' => $LANG['mnu_admin_groups'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['roles']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['roles']->name,
              'icon' => $CONF['controllers']['roles']->faIcon,
              'label' => $LANG['mnu_admin_roles'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['database']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['database']->name,
              'icon' => $CONF['controllers']['database']->faIcon,
              'label' => $LANG['mnu_admin_database'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['log']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['log']->name,
              'icon' => $CONF['controllers']['log']->faIcon,
              'label' => $LANG['mnu_admin_systemlog'] ];
            echo createSidebarItem($item);
          }
          if (isAllowed($CONF['controllers']['phpinfo']->permission)) {
            $item = [ 'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['phpinfo']->name,
              'icon' => $CONF['controllers']['phpinfo']->faIcon,
              'label' => $LANG['mnu_admin_phpinfo'] ];
            echo createSidebarItem($item);
          }
          ?>
        </ul>
      </li>
    <?php } ?>

    <!--Help-->
    <li class="sidebar-item">
      <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#help" aria-expanded="false" aria-controls="help">
        <i class="bi-question-circle-fill"></i>
        <span><?= $LANG['mnu_help'] ?></span>
      </a>
      <ul id="help" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
        <?php
        if ($docLink = $C->read("userManual")) {
          $item = [
            'url' => urldecode($docLink),
            'icon' => 'fas fa-book',
            'label' => $LANG['mnu_help_help']
          ];
          echo createSidebarItem($item);
        }
        if ($C->read("gdprPolicyPage")) {
          $item = [
            'url' => WEBSITE_URL . '/index.php?action=dataprivacy',
            'icon' => 'bi-shield-shaded',
            'label' => $LANG['mnu_help_dataprivacy']
          ];
          echo createSidebarItem($item);
        }
        $item = [
          'url' => WEBSITE_URL . '/index.php?action=imprint',
          'icon' => 'bi-vector-pen',
          'label' => $LANG['mnu_help_imprint']
        ];
        echo createSidebarItem($item);
        $item = [
          'url' => WEBSITE_URL . '/index.php?action=about',
          'icon' => 'bi-calendar-week logo-gradient',
          'label' => $LANG['mnu_help_about']
        ];
        echo createSidebarItem($item);
        ?>
      </ul>
    </li>

    <li class="sidebar-item mt-2 mb-2">
      <hr class="sidebar-divider">
    </li>

    <!--User-->
    <li class="sidebar-item">
      <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#user" aria-expanded="false" aria-controls="user">
        <img src="<?= APP_AVATAR_DIR . $userData['avatar'] ?>" class="sidebar-avatar" alt="">
        <span><?= $userData['isLoggedIn'] ? $userData['username'] : 'Not logged in' ?></span>
      </a>
      <ul id="user" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
        <?php
        if (!$userData['isLoggedIn']) {
          $item = [
            'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['login']->name,
            'icon' => 'bi-box-arrow-in-right',
            'label' => $LANG['mnu_user_login']
          ];
          echo createSidebarItem($item);
          if (!empty($settings) && $settings['allowRegistration']) {
            $item = [
              'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['register']->name,
              'icon' => 'bi-person-fill-add',
              'label' => $LANG['mnu_user_register']
            ];
            echo createSidebarItem($item);
          }
        } else {
          $item = [
            'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['useredit']->name . '&amp;profile=' . $userData['username'],
            'icon' => 'bi-person-square',
            'label' => $LANG['mnu_user_profile']
          ];
          echo createSidebarItem($item);
          $item = [
            'url' => WEBSITE_URL . '/index.php?action=' . $CONF['controllers']['logout']->name,
            'icon' => 'bi-box-arrow-left',
            'label' => $LANG['mnu_user_logout']
          ];
          echo createSidebarItem($item);
        }
        ?>
      </ul>
    </li>

    <!-- Width Menu -->
    <li class="sidebar-item">
      <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#widthmode" aria-expanded="false" aria-controls="widthmode">
        <i class="bi-fullscreen-exit width-icon-active"></i>
        <span><?= $LANG['mnu_width'] ?></span>
      </a>
      <ul id="widthmode" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
        <li class="sidebar-item">
          <a class="sidebar-link sidebar-sublink d-flex align-items-center" href="#" data-width-value="narrow">
            <i class="bi-fullscreen-exit me-3 text-<?= $CONF['menuIconColor'] ?>"></i>
            <?= $LANG['mnu_width_narrow'] ?>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link sidebar-sublink d-flex align-items-center" href="#" data-width-value="wide">
            <i class="bi-fullscreen me-3 text-<?= $CONF['menuIconColor'] ?>"></i>
            <?= $LANG['mnu_width_wide'] ?>
          </a>
        </li>
      </ul>
    </li>

    <!--Theme-->
    <li class="sidebar-item">
      <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#darkmode" aria-expanded="false" aria-controls="darkmode">
        <i class="bi-moon-stars-fill theme-icon-active"></i>
        <span><?= $LANG['mnu_theme'] ?></span>
      </a>
      <ul id="darkmode" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
        <li class="sidebar-item">
          <a class="sidebar-link sidebar-sublink d-flex align-items-center" href="#" data-bs-theme-value="light">
            <i class="bi-sun-fill me-3 text-<?= $CONF['menuIconColor'] ?>"></i>
            <?= $LANG['mnu_theme_light'] ?>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link sidebar-sublink d-flex align-items-center" href="#" data-bs-theme-value="dark">
            <i class="bi-moon-stars-fill me-3 text-<?= $CONF['menuIconColor'] ?>"></i>
            <?= $LANG['mnu_theme_dark'] ?>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link sidebar-sublink d-flex align-items-center" href="#" data-bs-theme-value="auto">
            <i class="bi-circle-half me-3 text-<?= $CONF['menuIconColor'] ?>"></i>
            <?= $LANG['mnu_theme_auto'] ?>
          </a>
        </li>
      </ul>
    </li>

  </ul>

  <div class="sidebar-footer">
    <ul class="sidebar-nav pb-0">
    </ul>
  </div>

</aside>
<script>
  const sidebarToggle = document.querySelector('.sidebar-toggle');
  sidebarToggle.addEventListener('click', function () {
    const eleSidebar = document.querySelector('#sidebar');
    const eleNavbar = document.querySelector('#navbar');
    const eleMain = document.querySelector('#main');

    if (eleSidebar) {
      eleSidebar.classList.toggle('expand');
    }
    if (eleNavbar) {
      eleNavbar.classList.toggle('expand');
    }
    if (eleMain) {
      eleMain.classList.toggle('expand');
    }
  });
</script>
<!--end::Sidebar-->
