<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Captcha Service
 *
 * Provides a simple math-based captcha and honeypot protection.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @package   TeamCal Neo
 * @since     5.0.0
 */
class CaptchaService
{
  //---------------------------------------------------------------------------
  /**
   * Generates a simple math question and stores the result in the session.
   *
   * @return string The math question text.
   */
  public function generateQuestion(): string {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $n1        = random_int(1, 10);
    $n2        = random_int(1, 10);
    $operators = ['+', '-'];
    $operator  = $operators[random_int(0, 1)];

    if ($operator === '-') {
      // Ensure positive result
      if ($n1 < $n2) {
        $temp = $n1;
        $n1   = $n2;
        $n2   = $temp;
      }
      $_SESSION['captcha_result'] = $n1 - $n2;
    }
    else {
      $_SESSION['captcha_result'] = $n1 + $n2;
    }

    $_SESSION['captcha_question'] = "{$n1} {$operator} {$n2} = ?";
    return $_SESSION['captcha_question'];
  }

  //---------------------------------------------------------------------------
  /**
   * Verifies the answer provided by the user.
   *
   * @param string|int|null $answer The user's answer.
   * @return bool True if correct, false otherwise.
   */
  public function verifyAnswer(string|int|null $answer): bool {
    if ($answer === null) {
      return false;
    }

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    return isset($_SESSION['captcha_result']) && (int) $answer === (int) $_SESSION['captcha_result'];
  }

  //---------------------------------------------------------------------------
  /**
   * Verifies that the honeypot field is empty.
   *
   * @param array<string, mixed> $post      The POST data.
   * @param string               $fieldName The honeypot field name.
   *
   * @return bool True if valid (empty), false otherwise.
   */
  public function verifyHoneypot(array $post, string $fieldName = 'website'): bool {
    // If the field exists and is not empty, it's likely a bot
    return !isset($post[$fieldName]) || empty($post[$fieldName]);
  }
}
