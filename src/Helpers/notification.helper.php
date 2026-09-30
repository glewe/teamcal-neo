<?php
if (!defined('VALID_ROOT')) {
  exit('');
}
/**
 * Notification Helper Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
/**
 * If a user was added or updated we send him an info to let him know.
 * Esepcially when he was added he needs to know what URL to navigate to and
 * how to login.
 *
 * @param string $password The password created
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountCreatedMail(string $email, string $username, string $password, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $logModel, $userOptionModel;

  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }

  try {
    $language = $configModel->read('defaultLanguage');
    $userLang = $userOptionModel->read($username, 'language');
    if ($userLang && $userLang !== 'default') {
      $language = $userLang;
    }
    $appTitle = $configModel->read('appTitle');

    //
    // Load localized subject
    //
    $localizedLANG = $LANG;
    $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
    if (file_exists($langFile)) {
      $loadedLang    = (function () use ($langFile) {
        $LANG = [];
        include_once $langFile;
        return $LANG;
      })();
      $localizedLANG = array_merge($localizedLANG, $loadedLang);
    }
    $subjectKey = 'email_subject_user_account_created';
    $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
    $subject    = str_replace('%app_name%', $appTitle, $subject);

    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
      'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
      'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_user_account_created.html',
      'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
      'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
    ];

    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
        if (file_exists($fallbackPath))
          $path = $fallbackPath;
        else
          throw new \App\Exceptions\TemplateException("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }

    //
    // Build message with all replacements
    //
    $message      = $templates['message'];
    $replacements = [
      '%intro%'    => $templates['intro'],
      '%body%'     => $templates['body'],
      '%outro%'    => $templates['outro'],
      '%footer%'   => $templates['footer'],
      '%subject%'  => $subject,
      '%app_name%' => $appTitle,
      '%app_url%'  => WEBSITE_URL,
      '%site_url%' => WEBSITE_URL,
      '%username%' => $username,
      '%password%' => $password
    ];

    $message = str_replace(array_keys($replacements), array_values($replacements), $message);

    return sendEmail($email, $subject, $message, '', $errorMessage);
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logRegistration", "System", "Failed to send account creation email: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user has registered and admin approval is needed, we send a mail to admin.
 *
 * @param string $lastname The user's lastname
 * @param string $firstname The user's firstname
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountNeedsApprovalMail(string $email, string $username, string $lastname, string $firstname, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $logModel, $userOptionModel;

  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }

  try {
    $language = $configModel->read('defaultLanguage');
    $appTitle = $configModel->read('appTitle');

    $userLang = $userOptionModel->read($username, 'language');
    if ($userLang && $userLang !== 'default') {
      $language = $userLang;
    }

    //
    // Load localized subject
    //
    $localizedLANG = $LANG;
    $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
    if (file_exists($langFile)) {
      $loadedLang    = (function () use ($langFile) {
        $LANG = [];
        include_once $langFile;
        return $LANG;
      })();
      $localizedLANG = array_merge($localizedLANG, $loadedLang);
    }
    $subjectKey = 'email_subject_user_account_needs_approval';
    $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
    $subject    = str_replace('%app_name%', $appTitle, $subject);

    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
      'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
      'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_user_account_needs_approval.html',
      'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
      'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
    ];

    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
        if (file_exists($fallbackPath))
          $path = $fallbackPath;
        else
          throw new \App\Exceptions\TemplateException("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }

    //
    // Build message with all replacements
    //
    $message      = $templates['message'];
    $replacements = [
      '%intro%'     => $templates['intro'],
      '%body%'      => $templates['body'],
      '%outro%'     => $templates['outro'],
      '%footer%'    => $templates['footer'],
      '%subject%'   => $subject,
      '%app_name%'  => $appTitle,
      '%app_url%'   => WEBSITE_URL,
      '%site_url%'  => WEBSITE_URL,
      '%username%'  => $username,
      '%lastname%'  => $lastname,
      '%firstname%' => $firstname
    ];

    $message = str_replace(array_keys($replacements), array_values($replacements), $message);

    return sendEmail($email, $subject, $message, '', $errorMessage);
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logRegistration", "System", "Failed to send account approval request email: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user has registered we send him a mail with the verification link.
 *
 * @param string $firstname The user's firstname
 * @param string $verifycode The verification code for the user
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountRegisteredMail(string $email, string $username, string $lastname, string $firstname, string $verifycode, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $logModel, $userOptionModel;

  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }

  try {
    $language = $configModel->read('defaultLanguage');
    $appTitle = $configModel->read('appTitle');

    $userLang = $userOptionModel->read($username, 'language');
    if ($userLang && $userLang !== 'default') {
      $language = $userLang;
    }

    //
    // Load localized subject
    //
    $localizedLANG = $LANG;
    $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
    if (file_exists($langFile)) {
      $loadedLang    = (function () use ($langFile) {
        $LANG = [];
        include_once $langFile;
        return $LANG;
      })();
      $localizedLANG = array_merge($localizedLANG, $loadedLang);
    }
    $subjectKey = 'email_subject_user_account_registered';
    $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
    $subject    = str_replace('%app_name%', $appTitle, $subject);

    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
      'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
      'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_user_account_registered.html',
      'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
      'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
    ];

    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
        if (file_exists($fallbackPath))
          $path = $fallbackPath;
        else
          throw new \App\Exceptions\TemplateException("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }

    //
    // Build message with all replacements
    //
    $message      = $templates['message'];
    $replacements = [
      '%intro%'      => $templates['intro'],
      '%body%'       => $templates['body'],
      '%outro%'      => $templates['outro'],
      '%footer%'     => $templates['footer'],
      '%subject%'    => $subject,
      '%app_name%'   => $appTitle,
      '%app_url%'    => WEBSITE_URL,
      '%site_url%'   => WEBSITE_URL,
      '%verifycode%' => $verifycode,
      '%username%'   => $username,
      '%lastname%'   => $lastname,
      '%firstname%'  => $firstname
    ];

    $message = str_replace(array_keys($replacements), array_values($replacements), $message);

    return sendEmail($email, $subject, $message, '', $errorMessage);
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logRegistration", "System", "Failed to send account registration email: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * If a user has tried to verify his account with an incorrect verify code
 * we send a mail to admin about it.
 *
 * @param string $vcode The verification code for the user
 * @param string $vcodeSubmitted The verification submitted by the user
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendAccountVerificationMismatchMail(string $email, string $username, string $vcode, string $vcodeSubmitted, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $logModel, $userOptionModel;

  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }

  try {
    $language = $configModel->read('defaultLanguage');
    $appTitle = $configModel->read('appTitle');

    $userLang = $userOptionModel->read($username, 'language');
    if ($userLang && $userLang !== 'default') {
      $language = $userLang;
    }

    //
    // Load localized subject
    //
    $localizedLANG = $LANG;
    $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
    if (file_exists($langFile)) {
      $loadedLang    = (function () use ($langFile) {
        $LANG = [];
        include_once $langFile;
        return $LANG;
      })();
      $localizedLANG = array_merge($localizedLANG, $loadedLang);
    }
    $subjectKey = 'email_subject_user_account_mismatch';
    $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
    $subject    = str_replace('%app_name%', $appTitle, $subject);

    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
      'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
      'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_user_account_verify_mismatch.html',
      'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
      'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
    ];

    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
        if (file_exists($fallbackPath))
          $path = $fallbackPath;
        else
          throw new \App\Exceptions\TemplateException("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }

    //
    // Build message with all replacements
    //
    $message      = $templates['message'];
    $replacements = [
      '%intro%'           => $templates['intro'],
      '%body%'            => $templates['body'],
      '%outro%'           => $templates['outro'],
      '%footer%'          => $templates['footer'],
      '%subject%'         => $subject,
      '%app_name%'        => $appTitle,
      '%app_url%'         => WEBSITE_URL,
      '%site_url%'        => WEBSITE_URL,
      '%username%'        => $username,
      '%vcode%'           => $vcode,
      '%vcode_submitted%' => $vcodeSubmitted
    ];

    $message = str_replace(array_keys($replacements), array_values($replacements), $message);

    return sendEmail($email, $subject, $message, '', $errorMessage);
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logRegistration", "System", "Failed to send verification mismatch email: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a group change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $groupname The groupname
 * @param string $groupdesc The group description
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
/**
 * Sends an email to all users that subscribed to a group change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $groupname The group name
 * @param string $groupdesc The group description
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendGroupEventNotifications(string $event, string $groupname, string $groupdesc = '', string &$errorMessage = ''): bool {
  global $configModel, $LANG, $userModel, $userOptionModel, $logModel;

  $events = ['changed', 'created', 'deleted'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all users and group them by language
    //
    $users           = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    $usersByLanguage = [];
    foreach ($users as $profile) {
      if ($userOptionModel->read($profile['username'], 'notifyGroupEvents')) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default') {
          $lang = $configModel->read('defaultLanguage');
        }
        $usersByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($usersByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_group_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_group_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'     => $templates['intro'],
        '%body%'      => $templates['body'],
        '%outro%'     => $templates['outro'],
        '%footer%'    => $templates['footer'],
        '%subject%'   => $subject,
        '%app_name%'  => $appTitle,
        '%app_url%'   => WEBSITE_URL,
        '%site_url%'  => WEBSITE_URL,
        '%groupname%' => $groupname,
        '%groupdesc%' => $groupdesc
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logGroup", "System", "Failed to send group event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends a password reset token mail.
 *
 * @param string $token The password reset token
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if email was sent successfully, false otherwise
 */
function sendPasswordResetMail(string $email, string $username, string $lastname, string $firstname, string $token, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $logModel, $userOptionModel;

  //
  // Input validation
  //
  if (!validEmail($email)) {
    return false;
  }

  try {
    $language = $configModel->read('defaultLanguage');
    $appTitle = $configModel->read('appTitle');

    $userLang = $userOptionModel->read($username, 'language');
    if ($userLang && $userLang !== 'default') {
      $language = $userLang;
    }

    //
    // Load localized subject
    //
    $localizedLANG = $LANG;
    $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
    if (file_exists($langFile)) {
      $loadedLang    = (function () use ($langFile) {
        $LANG = [];
        include_once $langFile;
        return $LANG;
      })();
      $localizedLANG = array_merge($localizedLANG, $loadedLang);
    }
    $subjectKey = 'email_subject_password_reset';
    $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
    $subject    = str_replace('%app_name%', $appTitle, $subject);

    //
    // Load all templates at once to reduce file operations
    //
    $templates = [
      'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
      'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
      'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_user_pwdreq.html',
      'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
      'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
    ];

    //
    // Load and validate all templates exist
    //
    foreach ($templates as $key => $path) {
      if (!file_exists($path)) {
        $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
        if (file_exists($fallbackPath))
          $path = $fallbackPath;
        else
          throw new \App\Exceptions\TemplateException("Missing template file: $path");
      }
      $templates[$key] = file_get_contents($path);
    }

    //
    // Build message with all replacements
    //
    $message      = $templates['message'];
    $replacements = [
      '%intro%'     => $templates['intro'],
      '%body%'      => $templates['body'],
      '%outro%'     => $templates['outro'],
      '%footer%'    => $templates['footer'],
      '%subject%'   => $subject,
      '%app_name%'  => $appTitle,
      '%app_url%'   => WEBSITE_URL,
      '%site_url%'  => WEBSITE_URL,
      '%token%'     => $token,
      '%username%'  => $username,
      '%lastname%'  => $lastname,
      '%firstname%' => $firstname
    ];

    $message = str_replace(array_keys($replacements), array_values($replacements), $message);

    return sendEmail($email, $subject, $message, '', $errorMessage);
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logUser", "System", "Failed to send password reset email: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a role change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $rolename The role name
 * @param string $roledesc The role description
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendRoleEventNotifications(string $event, string $rolename, string $roledesc = '', string &$errorMessage = ''): bool {
  global $configModel, $LANG, $userModel, $userOptionModel, $logModel;

  $events = ['changed', 'created', 'deleted'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all users and group them by language
    //
    $users           = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    $usersByLanguage = [];
    foreach ($users as $profile) {
      if ($userOptionModel->read($profile['username'], 'notifyRoleEvents')) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default') {
          $lang = $configModel->read('defaultLanguage');
        }
        $usersByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($usersByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_role_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_role_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'    => $templates['intro'],
        '%body%'     => $templates['body'],
        '%outro%'    => $templates['outro'],
        '%footer%'   => $templates['footer'],
        '%subject%'  => $subject,
        '%app_name%' => $appTitle,
        '%app_url%'  => WEBSITE_URL,
        '%site_url%' => WEBSITE_URL,
        '%rolename%' => $rolename,
        '%roledesc%' => $roledesc
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
          if (isset($logModel)) {
            $logModel->logEvent("logRole", "System", "Failed to send role event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logRole", "System", "Failed to send role event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a user change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $username The username
 * @param string $firstname The firstname
 * @param string $lastname The lastname
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendUserEventNotifications(string $event, string $username, string $firstname, string $lastname, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $userModel, $userOptionModel, $logModel;

  $events = ['created', 'changed', 'deleted'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all users and group them by language
    //
    $users           = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    $usersByLanguage = [];
    foreach ($users as $profile) {
      if ($userOptionModel->read($profile['username'], 'notifyUserEvents')) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default') {
          $lang = $configModel->read('defaultLanguage');
        }
        $usersByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($usersByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_user_account_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_user_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'     => $templates['intro'],
        '%body%'      => $templates['body'],
        '%outro%'     => $templates['outro'],
        '%footer%'    => $templates['footer'],
        '%subject%'   => $subject,
        '%app_name%'  => $appTitle,
        '%app_url%'   => WEBSITE_URL,
        '%site_url%'  => WEBSITE_URL,
        '%username%'  => $username,
        '%firstname%' => $firstname,
        '%lastname%'  => $lastname
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
          if (isset($logModel)) {
            $logModel->logEvent("logUser", "System", "Failed to send user event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logUser", "System", "Failed to send user event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to an absence change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $absname The absence name
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendAbsenceEventNotifications(string $event, string $absname, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $userModel, $userOptionModel, $logModel;

  $events = ['changed', 'created', 'deleted'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all users and group them by language
    //
    $users           = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    $usersByLanguage = [];
    foreach ($users as $profile) {
      if ($userOptionModel->read($profile['username'], 'notifyAbsenceEvents')) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default') {
          $lang = $configModel->read('defaultLanguage');
        }
        $usersByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($usersByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_absence_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_absence_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'    => $templates['intro'],
        '%body%'     => $templates['body'],
        '%outro%'    => $templates['outro'],
        '%footer%'   => $templates['footer'],
        '%subject%'  => $subject,
        '%app_name%' => $appTitle,
        '%app_url%'  => WEBSITE_URL,
        '%site_url%' => WEBSITE_URL,
        '%absname%'  => $absname
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
          if (isset($logModel)) {
            $logModel->logEvent("logAbsence", "System", "Failed to send absence event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logAbsence", "System", "Failed to send absence event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a holiday change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $holname The holiday name
 * @param string $holdesc The holiday description
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendHolidayEventNotifications(string $event, string $holname, string $holdesc = '', string &$errorMessage = ''): bool {
  global $configModel, $LANG, $userModel, $userOptionModel, $logModel;

  $events = ['changed', 'created', 'deleted'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all users and group them by language
    //
    $users           = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    $usersByLanguage = [];
    foreach ($users as $profile) {
      if ($userOptionModel->read($profile['username'], 'notifyHolidayEvents')) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default') {
          $lang = $configModel->read('defaultLanguage');
        }
        $usersByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($usersByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_holiday_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_holiday_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'    => $templates['intro'],
        '%body%'     => $templates['body'],
        '%outro%'    => $templates['outro'],
        '%footer%'   => $templates['footer'],
        '%subject%'  => $subject,
        '%app_name%' => $appTitle,
        '%app_url%'  => WEBSITE_URL,
        '%site_url%' => WEBSITE_URL,
        '%holname%'  => $holname,
        '%holdesc%'  => $holdesc
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
          if (isset($logModel)) {
            $logModel->logEvent("logHoliday", "System", "Failed to send holiday event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logHoliday", "System", "Failed to send holiday event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a month change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $year The year
 * @param string $month The month
 * @param string $region The region
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendMonthEventNotifications(string $event, string $year, string $month, string $region, string &$errorMessage = ''): bool {
  global $configModel, $LANG, $userModel, $userOptionModel, $logModel;

  $events = ['created', 'changed', 'deleted'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all users and group them by language
    //
    $users           = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);
    $usersByLanguage = [];
    foreach ($users as $profile) {
      if ($userOptionModel->read($profile['username'], 'notifyMonthEvents')) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default') {
          $lang = $configModel->read('defaultLanguage');
        }
        $usersByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($usersByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_month_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_month_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'    => $templates['intro'],
        '%body%'     => $templates['body'],
        '%outro%'    => $templates['outro'],
        '%footer%'   => $templates['footer'],
        '%subject%'  => $subject,
        '%app_name%' => $appTitle,
        '%app_url%'  => WEBSITE_URL,
        '%site_url%' => WEBSITE_URL,
        '%year%'     => $year,
        '%month%'    => $month,
        '%region%'   => $region
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
          if (isset($logModel)) {
            $logModel->logEvent("logMonth", "System", "Failed to send month event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logMonth", "System", "Failed to send month event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an email to all users that subscribed to a user calendar change event.
 *
 * @param string $event The event type: added, changed, deleted
 * @param string $username The username
 * @param string $year Numeric representation of the year
 * @param string $month Numeric representation of the month
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool True if all emails were sent successfully, false if any failed
 */
function sendUserCalEventNotifications(string $event, string $username, string $year, string $month, string &$errorMessage = ''): bool {
  global $absenceModel, $absenceDayModel, $configModel, $LANG, $userModel, $userGroupModel, $userOptionModel, $logModel;

  $events = ['changed'];

  if (!in_array($event, $events)) {
    return false;
  }

  try {
    $appTitle = $configModel->read('appTitle');

    //
    // Get all groups for the user whose calendar was changed.
    //
    $ugroups  = $userGroupModel->getAllforUser($username);
    $allUsers = $userModel->getAll('lastname', 'firstname', 'ASC', false, true);

    //
    // Determine who needs to be notified
    //
    $recipientsByLanguage = [];
    foreach ($allUsers as $profile) {
      $sendmail = false;

      // Check whether this user wants to get userCalEvents notifications for himself only
      if ($profile['username'] === $username && $userOptionModel->read($username, 'notifyUserCalEventsOwn')) {
        $sendmail = true;
      }
      // Check whether this user wants to get userCalEvents notifications for groups
      elseif (
        $userOptionModel->read($profile['username'], 'notifyUserCalEvents') &&
        !$userOptionModel->read($profile['username'], 'notifyUserCalEventsOwn') &&
        ($notifyUserCalGroups = $userOptionModel->read($profile['username'], 'notifyUserCalGroups'))
      ) {
        $ngroups = explode(',', $notifyUserCalGroups);
        foreach ($ugroups as $ugroup) {
          if (in_array($ugroup['groupid'], $ngroups)) {
            $sendmail = true;
            break;
          }
        }
      }

      if ($sendmail) {
        $lang = $userOptionModel->read($profile['username'], 'language');
        if (!$lang || $lang === 'default')
          $lang = $configModel->read('defaultLanguage');
        $recipientsByLanguage[$lang][] = $profile;
      }
    }

    $allSuccessful = true;
    foreach ($recipientsByLanguage as $language => $recipients) {
      //
      // Load localized subject
      //
      $localizedLANG = $LANG;
      $langFile      = WEBSITE_ROOT . '/resources/languages/' . $language . '/core.php';
      if (file_exists($langFile)) {
        $loadedLang    = (function () use ($langFile) {
          $LANG = [];
          include_once $langFile;
          return $LANG;
        })();
        $localizedLANG = array_merge($localizedLANG, $loadedLang);
      }
      $subjectKey = 'email_subject_usercal_' . $event;
      $subject    = isset($localizedLANG[$subjectKey]) ? $localizedLANG[$subjectKey] : $subjectKey;
      $subject    = str_replace('%app_name%', $appTitle, $subject);

      //
      // Load templates for this language
      //
      $templates = [
        'message' => WEBSITE_ROOT . '/resources/templates/email_html.html',
        'intro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/intro.html',
        'body'    => WEBSITE_ROOT . '/resources/templates/' . $language . '/body_usercal_' . $event . '.html',
        'outro'   => WEBSITE_ROOT . '/resources/templates/' . $language . '/outro.html',
        'footer'  => WEBSITE_ROOT . '/resources/templates/' . $language . '/footer.html'
      ];

      foreach ($templates as $key => $path) {
        if (!file_exists($path)) {
          $fallbackPath = str_replace('/' . $language . '/', '/english/', $path);
          if (file_exists($fallbackPath))
            $path = $fallbackPath;
          else
            throw new \App\Exceptions\TemplateException("Missing template file: $path");
        }
        $templates[$key] = file_get_contents($path);
      }

      //
      // Build html calendar table for email
      //
      $monthInfo = dateInfo($year, $month, '1');
      $lastday   = $monthInfo['daysInMonth'];
      $monthMap  = $absenceDayModel->getMonthMap($username, $year, $month);
      $calendar = '<table style="border-collapse:collapse;"><tr style="background-color:#f0f0f0;">';
      for ($i = 1; $i <= $lastday; $i++) {
        $calendar .= '<th style="border:1px solid #bababa;padding:4px;text-align:center;">' . $i . '</th>';
      }
      $calendar .= '</tr><tr>';
      for ($i = 1; $i <= $lastday; $i++) {
        $calendar .= '<td style="border:1px solid #bababa;padding:4px;text-align:center;">' . $absenceModel->getName($monthMap[$i]) . '</td>';
      }
      $calendar .= '</tr></table>';

      //
      // Build message
      //
      $message      = $templates['message'];
      $replacements = [
        '%intro%'    => $templates['intro'],
        '%body%'     => $templates['body'],
        '%outro%'    => $templates['outro'],
        '%footer%'   => $templates['footer'],
        '%subject%'  => $subject,
        '%app_name%' => $appTitle,
        '%app_url%'  => WEBSITE_URL,
        '%site_url%' => WEBSITE_URL,
        '%fullname%' => $userModel->getFullname($username),
        '%username%' => $username,
        '%month%'    => $year . "-" . $month,
        '%calendar%' => $calendar
      ];
      $message      = str_replace(array_keys($replacements), array_values($replacements), $message);

      //
      // Send to all users for this language
      //
      foreach ($recipients as $profile) {
        if (!sendEmail($profile['email'], $subject, $message, '', $errorMessage)) {
          $allSuccessful = false;
          if (isset($logModel)) {
            $logModel->logEvent("logUser", "System", "Failed to send user calendar event notification to {$profile['email']}: ", "Email send failed");
          }
        }
      }
    }

    return $allSuccessful;
  } catch (Exception $e) {
    if (isset($logModel)) {
      $logModel->logEvent("logUser", "System", "Failed to send user calendar event notifications: ", $e->getMessage());
    }
    $errorMessage = "General: " . $e->getMessage();
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Sends an HTML eMail, either via SMTP or regular PHP mail.
 * Uses PHPMailer for SMTP functionality
 *
 * @param string $to eMail to address
 * @param string $subject eMail subject
 * @param string $body eMail body
 * @param string $from eMail from address (optional)
 * @param string $errorMessage Error message (optional, passed by reference)
 *
 * @return bool Email success
 */
function sendEmail(string $to, string $subject, string $body, string $from = '', string &$errorMessage = ''): bool {
  global $configModel, $logModel;
  $debug         = false;
  $from_mailonly = $configModel->read("mailReply");
  $replyto       = "";

  $from_regexp = preg_match('/<(.*?)>/', $from, $fetch);

  //
  // Set From and ReplyTo
  //
  if ((!strlen($from)) || ($from_regexp && ($fetch[1] == $configModel->read("mailReply")))) {
    $from          = $replyto = mb_encode_mimeheader($configModel->read("mailFrom"), 'UTF-8') . " <" . $configModel->read("mailReply") . ">";
    $from_mailonly = $configModel->read("mailReply");
  }
  elseif ($from_regexp) {
    $from_mailonly = $fetch[1];
    $replyto       = mb_encode_mimeheader($from, 'UTF-8');
  }

  //
  // "To" has to be a valid email or comma separated list of valid emails.
  // It might be empty if a user to be notified has not setup his email address
  //
  $to      = rtrim($to, ', '); // remove the last ", " if exists
  $to      = rtrim($to, ',');  // remove the last "," if exists
  $toArray = explode(",", $to);
  $toValid = "";
  foreach ($toArray as $toPiece) {
    if (!validEmail($toPiece)) {
      $toValid .= $configModel->read("mailReply") . ",";
    }
    else {
      $toValid .= $toPiece . ",";
    }
  }
  $toValid = rtrim($toValid, ','); // remove the last "," if exists (just in case)

  try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    if ($configModel->read("mailSMTP")) {
      //
      // SMTP Mail
      //
      $mail->isSMTP();
      $mail->Host = $configModel->read("mailSMTPhost");
      $mail->Port = intval($configModel->read("mailSMTPport"));

      if ($configModel->read("mailSMTPSSL")) {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
      }
      else {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
      }

      if (!$configModel->read("mailSMTPAnonymous")) {
        $mail->SMTPAuth = true;
        $mail->Username = $configModel->read("mailSMTPusername");
        $mail->Password = $configModel->read("mailSMTPpassword");
      }
    }
    else {
      //
      // Regular PHP Mail using PHPMailer's wrapper
      //
      $mail->isMail();
    }

    // Recipients
    $mail->setFrom($from_mailonly, $configModel->read("mailFrom"));
    $mail->addReplyTo($configModel->read("mailReply"));

    // Add recipients
    $toRecipients = explode(",", $toValid);
    foreach ($toRecipients as $recipient) {
      $mail->addAddress(trim($recipient));
    }

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->CharSet = 'UTF-8';

    // Explicitly safe-guard manual line-breaks if needed, though PHPMailer handles it.
    // $mail->Encoding = 'quoted-printable';

    $mail->send();
    return true;

  } catch (PHPMailer\PHPMailer\Exception $e) {
    //
    // Capture PHPMailer error
    //
    $errorMessage = "PHPMailer: " . $mail->ErrorInfo;
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
    if (isset($logModel)) {
      $logModel->logEvent("logMessage", "System", "log_email_error", "PHPMailer: " . $mail->ErrorInfo);
    }
    return false;
  } catch (Exception $e) {
    //
    // Capture general error
    //
    $errorMessage = "General: " . $e->getMessage();
    error_log("Email sending error: " . $e->getMessage());
    if (isset($logModel)) {
      $logModel->logEvent("logMessage", "System", "log_email_error", "General: " . $e->getMessage());
    }
    return false;
  }
}

//-----------------------------------------------------------------------------
/**
 * Validate an email address.
 *
 * @param string $email The email address to validate
 *
 * @return boolean True or False indicating validity
 */
function validEmail(string $email): bool {
  $isValid = true;
  $atIndex = strrpos($email, "@");
  if ($atIndex === false) {
    $isValid = false;
  }
  else {
    $domain    = substr($email, $atIndex + 1);
    $local     = substr($email, 0, $atIndex);
    $localLen  = strlen($local);
    $domainLen = strlen($domain);
    if ($localLen < 1 || $localLen > 64) {
      //
      // local part length exceeded
      //
      $isValid = false;
    }
    elseif ($domainLen < 1 || $domainLen > 255) {
      //
      // domain part length exceeded
      //
      $isValid = false;
    }
    elseif ($local[0] == '.' || $local[$localLen - 1] == '.') {
      //
      // local part starts or ends with '.'
      //
      $isValid = false;
    }
    elseif (preg_match('/\\.\\./', $local)) {
      //
      // local part has two consecutive dots
      //
      $isValid = false;
    }
    elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
      //
      // character not valid in domain part
      //
      $isValid = false;
    }
    elseif (preg_match('/\\.\\./', $domain)) {
      //
      // domain part has two consecutive dots
      //
      $isValid = false;
    }
    elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
      //
      // character not valid in local part unless
      // local part is quoted
      //
      if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
        $isValid = false;
      }
    }
  }
  return $isValid;
}
