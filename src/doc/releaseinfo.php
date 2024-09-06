<?php
$releases = [
  //---------------------------------------------------------------------------
  [
    'version' => '4.0.0',
    'date' => '2024-09-xx',
    'info' => 'This is a new major release of TeamCal Neo. It comes with several interface changes and retires the custom themes that needed too much effort to maintain compared to their benefit.',
    'bugfixes' => [
      [ 'summary' => 'Fixed missing user in sample SQL', 'issue' => '' ],
    ],
    'features' => [
      [ 'summary' => 'Added LDAP test config', 'issue' => '' ],
      [ 'summary' => 'Added Bootstrap Dark and Light mode', 'issue' => '' ],
      [ 'summary' => 'Added Bootstrap Icons', 'issue' => '' ],
    ],
    'improvements' => [
      [ 'summary' => 'Cleaner form button layout', 'issue' => '' ],
      [ 'summary' => 'Reduced footer links to Imprint and Data Privacy Policy', 'issue' => '' ],
      [ 'summary' => 'Added table indexes for faster database operations', 'issue' => '' ],
    ],
    'removals' => [
      [ 'summary' => 'Removed Banner option (outdated feature)', 'issue' => '' ],
      [ 'summary' => 'Removal of Bootswatch user themes (unnecessary maintenance)', 'issue' => '' ],
      [ 'summary' => 'Removal of X-editable addon (unused)', 'issue' => '' ],
      [ 'summary' => 'Removal of Select2 addon (unused)', 'issue' => '' ],
      [ 'summary' => 'Removal of google-code-prettify addon (unused)', 'issue' => '' ],
    ],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.9.3',
    'date' => '2024-08-28',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed license server request error', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Group managers can now edit settings, members and managers of their groups', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.9.2',
    'date' => '2024-08-14',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed datatable error on users page', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Adjusted absence icon position on calendar page', 'issue' => '' ],
      [ 'summary' => 'Adjusted Boostrap 5 styles', 'issue' => '' ],
      [ 'summary' => 'Code quality fixes', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.9.1',
    'date' => '2024-08-02',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'HTML 5 no caching fix', 'issue' => '' ],
    ],
    'features' => [
      [ 'summary' => 'Datatables module on pages with tables', 'issue' => '' ],
    ],
    'improvements' => [
      [ 'summary' => 'Update to Bootstrap 5.3.3 (incl. Bootswatch themes)', 'issue' => '' ],
      [ 'summary' => 'Update to Chart.js 4.4.3', 'issue' => '' ],
      [ 'summary' => 'Current language now marked in TeamCal Neo menu', 'issue' => '' ],
      [ 'summary' => 'Disabled PHP error reporting in production build', 'issue' => '' ],
      [ 'summary' => 'Page tabs redesign', 'issue' => '' ],
      [ 'summary' => 'Code quality fixes', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.9.0',
    'date' => '2024-07-31',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed Bootstrap 5.2 CSS class bugs', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Upgraded from legacy Google Analytics to GA4', 'issue' => '' ],
      [ 'summary' => 'SonarQube code analysis and quality fixes', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.8.2',
    'date' => '2024-03-12',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed tooltip positioning', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Allow all printable characters for absence type symbols', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.8.1',
    'date' => '2024-02-29',
    'info' => '',
    'bugfixes' => [],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Users can select to get own calendar notifications only', 'issue' => '' ],
      [ 'summary' => 'Hidden feature to display the calendar only (e.g in iFrames)', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.8.0',
    'date' => '2024-02-23',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed hide group managers bug', 'issue' => '' ],
      [ 'summary' => 'Fixed button style on role edit page', 'issue' => '' ],
      [ 'summary' => 'Fixed typos in the language files', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Added a filter option to absence type icon selection', 'issue' => '' ],
      [ 'summary' => 'Update to Font Awesome 6.5.1', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.7.8',
    'date' => '2023-09-11',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed custom sort accounts bug', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.7.4',
    'date' => '2023-06-29',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed delete absence type bug', 'issue' => '' ],
      [ 'summary' => 'Fixed add user account bug', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.7.3',
    'date' => '2023-06-27',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed login page redirect bug', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.7.2',
    'date' => '2023-06-11',
    'info' => '',
    'bugfixes' => [],
    'features' => [
      [ 'summary' => 'Sort accounts by custom order key', 'issue' => '' ],
    ],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.7.1',
    'date' => '2023-05-23',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed bug in cleanup database feature', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.7.0',
    'date' => '2023-03-25',
    'info' => '',
    'bugfixes' => [],
    'features' => [
      [ 'summary' => '2-factor authentication', 'issue' => '' ],
    ],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.6.0',
    'date' => '2022-11-25',
    'info' => '',
    'bugfixes' => [],
    'features' => [
      [ 'summary' => 'Custom font selection', 'issue' => '' ],
      [ 'summary' => 'Six new themes', 'issue' => '' ],
    ],
    'improvements' => [
      [ 'summary' => 'Enhanced logging of calendar events', 'issue' => '' ],
      [ 'summary' => 'Update to Bootstrap 5.2.3', 'issue' => '' ],
      [ 'summary' => 'Update to CKEditor 4.20.0', 'issue' => '' ],
      [ 'summary' => 'Update to CookieConsent2 3.1.1', 'issue' => '' ],
      [ 'summary' => 'Update to Font Awesome 6.2.1', 'issue' => '' ],
      [ 'summary' => 'Update to jQuery 3.6.1', 'issue' => '' ],
      [ 'summary' => 'Update to jQuery UI 1.13.2', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.5.2',
    'date' => '2022-11-05',
    'info' => '',
    'bugfixes' => [],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Show Calendar caching workaround', 'issue' => '' ],
      [ 'summary' => 'New documentation on Gitbook.io (help links updated)', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.5.1',
    'date' => '2022-10-14',
    'info' => '',
    'bugfixes' => [],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Minimized license server connections', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.5.0',
    'date' => '2022-10-12',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Permission save bug fixes', 'issue' => '' ],
    ],
    'features' => [
      [ 'summary' => 'Allowance bulk edit', 'issue' => '' ],
    ],
    'improvements' => [
      [ 'summary' => 'Reduced license checks', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.4.1',
    'date' => '2022-07-28',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Minor bug fixes', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.4.0',
    'date' => '2022-02-01',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed bug on user list page', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Update to Font Awesome 5.15.4', 'issue' => '' ],
      [ 'summary' => 'Update to Bootstrap 4.6.1', 'issue' => '' ],
      [ 'summary' => 'Sample database update', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.3.0',
    'date' => '2022-01-15',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed bug on user profile save', 'issue' => '' ],
      [ 'summary' => 'Fixed bug on user search', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Cookie Consent local load', 'issue' => '' ],
      [ 'summary' => 'PSR12 Code Maintenance', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.8',
    'date' => '2021-08-05',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Public cannot edit calendar when given permission', 'issue' => 'https://georgelewe.atlassian.net/browse/TCN-296' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.7',
    'date' => '2021-02-15',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'User absence counted more than once', 'issue' => 'https://georgelewe.atlassian.net/browse/TCN-294' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.6',
    'date' => '2021-01-29',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Calendar view error', 'issue' => 'https://georgelewe.atlassian.net/browse/TCN-293' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.5',
    'date' => '2021-01-25',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'MySQL single quotes', 'issue' => 'https://georgelewe.atlassian.net/browse/TCN-292' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.4',
    'date' => '2021-01-09',
    'info' => '',
    'bugfixes' => [],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Allow year selection on Remainder page', 'issue' => 'https://georgelewe.atlassian.net/browse/TCN-291' ],
      [ 'summary' => 'Cookie consent update', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.3',
    'date' => '2020-12-11',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed German absence approval mail body', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.2',
    'date' => '2020-07-17',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed calendar save bug if license expiry warning', 'issue' => '' ],
    ],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Updated obsolete sample DB entries', 'issue' => '' ],
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.1',
    'date' => '2020-06-11',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed default icon for new absence types', 'issue' => '' ],
      [ 'summary' => 'Fixed missing allowance attribute in absence suummary', 'issue' => '' ]
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.2.0',
    'date' => '2020-03-03',
    'info' => '',
    'bugfixes' => [],
    'features' => [],
    'improvements' => [
      [ 'summary' => 'Separate presence/absence group thresholds for weekdays and weeekends', 'issue' => '' ]
    ],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.1.0',
    'date' => '2020-02-23',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed styling of option titles', 'issue' => '' ]
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.0.1',
    'date' => '2020-01-06',
    'info' => '',
    'bugfixes' => [
      [ 'summary' => 'Fixed opening menu in responsive mode', 'issue' => '' ]
    ],
    'features' => [],
    'improvements' => [],
    'removals' => [],
  ],
  //---------------------------------------------------------------------------
  [
    'version' => '3.0.0',
    'date' => '2020-01-01',
    'info' => 'This is the third major - and now commercial - release of TeamCal Neo. It requires a subscription (license key) to run.
    Bootstrap and Font Awesome were update to their newest releases. Also, 22 user selectable themes are now included.',
    'bugfixes' => [],
    'features' => [
      [ 'summary' => '22 updated/new themes', 'issue' => '' ],
      [ 'summary' => 'License Management', 'issue' => '' ]
    ],
    'improvements' => [
      [ 'summary' => 'Update to Bootstrap 4.4.1', 'issue' => '' ],
      [ 'summary' => 'Update to Font Awesome 5.12.0', 'issue' => '' ]
    ],
    'removals' => [],
  ],
];
?>
<!-- Release Info -->
<ul class="timeline">
  <?php foreach ($releases as $release) { ?>
    <li>
      <div class="card mb-3">
        <div class="card-header"><i class="bi-box me-3"></i>Release <?= $release['version'] ?><span class="float-end"><i class="bi-calendar me-3"></i><?= $release['date'] ?></span></div>
        <div class="card-body">

          <?php if (strlen($release['info'])) { ?>
            <p><?= $release['info'] ?></p>
          <?php } ?>

          <?php if (count($release['bugfixes'])) { ?>
            <h6><?= $LANG['about_bugfixes'] ?></h6>
            <ul>
              <?php foreach ($release['bugfixes'] as $bugfix) { ?>
                <li><?= $bugfix['summary'] ?><?= (strlen($bugfix['issue']) ? ' ( <a href="' . $bugfix['issue'] . '" target="_blank"><i class="bi-box-arrow-up-right me-1"></i>Issue</a> )' : '') ?></li>
              <?php } ?>
            </ul>
          <?php } ?>

          <?php if (count($release['improvements'])) { ?>
            <h6><?= $LANG['about_improvements'] ?></h6>
            <ul>
              <?php foreach ($release['improvements'] as $improvement) { ?>
                <li><?= $improvement['summary'] ?><?= (strlen($improvement['issue']) ? ' ( <a href="' . $improvement['issue'] . '" target="_blank"><i class="bi-box-arrow-up-right me-1"></i>Issue</a> )' : '') ?></li>
              <?php } ?>
            </ul>
          <?php } ?>

          <?php if (count($release['features'])) { ?>
            <h6><?= $LANG['about_features_new'] ?></h6>
            <ul>
              <?php foreach ($release['features'] as $feature) { ?>
                <li><?= $feature['summary'] ?><?= (strlen($feature['issue']) ? ' ( <a href="' . $feature['issue'] . '" target="_blank"><i class="bi-box-arrow-up-right me-1"></i>Issue</a> )' : '') ?></li>
              <?php } ?>
            </ul>
          <?php } ?>

          <?php if (count($release['removals'])) { ?>
            <h6><?= $LANG['about_features_retired'] ?></h6>
            <ul>
              <?php foreach ($release['removals'] as $removal) { ?>
                <li><?= $removal['summary'] ?><?= (strlen($removal['issue']) ? ' ( <a href="' . $removal['issue'] . '" target="_blank"><i class="bi-box-arrow-up-right me-1"></i>Issue</a> )' : '') ?></li>
              <?php } ?>
            </ul>
          <?php } ?>

        </div>
      </div>
    </li>
  <?php } ?>
</ul>
<!-- End Release Info -->
