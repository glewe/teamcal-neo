<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * AvatarModel
 *
 * This class provides methods and properties for user avatars.
 *
 * @author    George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link      https://www.lewe.com
 *
 * @package   TeamCal Neo
 * @since     3.0.0
 */
class AvatarModel
{
  private const DEFAULT_MALE   = 'default_male.png';
  private const DEFAULT_FEMALE = 'default_female.png';
  private const IS_PREFIX      = 'is_';

  public array  $allowedTypes  = [];
  public array  $error         = [];
  public string $fileExtension = '';
  public string $fileName      = '';
  public string $tmpFileName   = '';
  public int    $maxHeight     = 80;
  public int    $maxWidth      = 80;
  public int    $maxSize       = 0;
  public string $message       = '';
  public string $path          = '';

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param array      $LANG Language array
   * @param array|null $conf Configuration array
   */
  public function __construct(array $LANG, ?array $conf = null) {
    if ($conf === null) {
      global $CONF;
      $conf = $CONF;
    }

    $this->maxHeight    = 80;
    $this->maxWidth     = 80;
    $this->maxSize      = (int) $conf['avatarMaxsize'];
    $this->path         = APP_AVATAR_DIR;
    $this->allowedTypes = $conf['avatarExtensions'];

    $this->error[0]  = $LANG['upl_error_0'];
    $this->error[1]  = $LANG['upl_error_1'];
    $this->error[2]  = $LANG['upl_error_2'];
    $this->error[3]  = $LANG['upl_error_3'];
    $this->error[4]  = $LANG['upl_error_4'];
    $this->error[10] = $LANG['upl_error_10'];
    $this->error[11] = $LANG['upl_error_11'];
    $this->error[12] = $LANG['upl_error_12'];
    $this->error[13] = $LANG['upl_error_13'];
    $this->error[14] = $LANG['upl_error_14'];
    $this->error[15] = $LANG['upl_error_15'];
    $this->error[16] = $LANG['upl_error_16'];
    $this->error[17] = $LANG['upl_error_17'];
    $this->error[18] = $LANG['upl_error_18'];
    $this->error[19] = $LANG['upl_error_19'];
  }

  //---------------------------------------------------------------------------
  /**
   * Find avatar for a given user (username=avatar file name).
   *
   * @param string $uname Username (file name) to find
   *
   * @return bool True if found, false if not
   */
  public function find(string $uname): bool {
    foreach ($this->allowedTypes as $extension) {
      if (file_exists($this->path . $uname . "." . $extension)) {
        $this->fileName      = $uname;
        $this->fileExtension = $extension;
        return true;
      }
    }
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Deletes avatar for a given user (username=avatar file name).
   *
   * @param string $uname  Username (for avatars named like the username)
   * @param string $avatar Current user avatar (file name with extension)
   */
  public function delete(string $uname, string $avatar): void {
    foreach ($this->allowedTypes as $extension) {
      $filePath = $this->path . $uname . "." . $extension;
      if (file_exists($filePath)) {
        unlink($filePath);
        break; // Only one avatar per user should exist
      }
    }

    $avatarPath = $this->path . $avatar;
    if (
      file_exists($avatarPath) &&
      $avatar !== self::DEFAULT_MALE &&
      $avatar !== self::DEFAULT_FEMALE &&
      substr($avatar, 0, 3) !== self::IS_PREFIX
    ) {
      unlink($avatarPath);
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Extract file extension of a given file name.
   *
   * @param string $str File name to scan
   *
   * @return string File extension if exists
   */
  private function getFileExtension(string $str): string {
    $i = strrpos($str, ".");
    if ($i === false) {
      return "";
    }
    return substr($str, $i + 1);
  }

  //---------------------------------------------------------------------------
  /**
   * Saves avatar for a given user (username=avatar file name).
   *
   * @param string $uname Username (file name) to save
   */
  public function save(string $uname): void {
    global $_FILES;
    $this->message = '';

    if (is_uploaded_file($_FILES['imgfile']['tmp_name'])) {
      $this->fileName      = (string) $_FILES['imgfile']['name'];
      $this->tmpFileName   = (string) $_FILES['imgfile']['tmp_name'];
      $this->fileExtension = strtolower($this->getFileExtension($this->fileName));

      if (in_array($this->fileExtension, $this->allowedTypes, true)) {
        $newfile = $this->path . $uname . "." . $this->fileExtension;

        // Check size and resize if necessary
        $imgsize = @getimagesize($this->tmpFileName);
        if ($imgsize) {
          $width  = $imgsize[0];
          $height = $imgsize[1];
          if (($width > $this->maxWidth) || ($height > $this->maxHeight)) {
            if ($width > $this->maxWidth && $height <= $this->maxHeight) {
              $ratio = $this->maxWidth / $width;
            }
            elseif ($height > $this->maxHeight && $width <= $this->maxWidth) {
              $ratio = $this->maxHeight / $height;
            }
            elseif ($width > $this->maxWidth && $height > $this->maxHeight) {
              $ratio1 = $this->maxWidth / $width;
              $ratio2 = $this->maxHeight / $height;
              $ratio  = ($ratio1 < $ratio2) ? $ratio1 : $ratio2;
            }
            else {
              $ratio = 1;
            }
            $nWidth  = (int) floor($width * $ratio);
            $nHeight = (int) floor($height * $ratio);

            switch (strtolower($this->fileExtension)) {
              case "gif":
                $origPic = @imagecreatefromgif($this->tmpFileName);
                if ($origPic) {
                  $newPic = @imagecreate($nWidth, $nHeight);
                  if ($newPic) {
                    @imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                    @imagegif($newPic, $newfile);
                    @imagedestroy($newPic);
                  }
                  @imagedestroy($origPic);
                }
                break;

              case "jpg":
              case "jpeg":
                $origPic = @imagecreatefromjpeg($this->tmpFileName);
                if ($origPic) {
                  $newPic = @imagecreatetruecolor($nWidth, $nHeight);
                  if ($newPic) {
                    @imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                    @imagejpeg($newPic, $newfile, 90);
                    @imagedestroy($newPic);
                  }
                  @imagedestroy($origPic);
                }
                break;

              case "png":
                $origPic = @imagecreatefrompng($this->tmpFileName);
                if ($origPic) {
                  $newPic = @imagecreate($nWidth, $nHeight);
                  if ($newPic) {
                    @imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                    @imagepng($newPic, $newfile);
                    @imagedestroy($newPic);
                  }
                  @imagedestroy($origPic);
                }
                break;

              default:
                break;
            }
          }
          else {
            // The file is within the size restrictions. Just copy it to its destination.
            if (!@copy($this->tmpFileName, $newfile)) {
              $this->message = $this->error[19];
            }
          }
        }

        if (file_exists($newfile)) {
          chmod($newfile, 0666);
        }

        // Delete the temporary uploaded file
        @unlink($this->tmpFileName);
        // Delete previous avatars if exist
        foreach ($this->allowedTypes as $type) {
          if ($type !== $this->fileExtension && file_exists($this->path . $uname . "." . $type)) {
            @unlink($this->path . $uname . "." . $type);
          }
        }
      }
      else {
        $extList       = implode(',', $this->allowedTypes);
        $this->message = sprintf($this->error[11], $extList);
      }
    }
    else {
      switch ($_FILES['imgfile']['error']) {
        case 1: // UPLOAD_ERR_INI_SIZE
          $this->message = $this->error[1];
          break;
        case 2: // UPLOAD_ERR_FORM_SIZE
          $this->message = $this->error[2];
          break;
        case 3: // UPLOAD_ERR_PARTIAL
          $this->message = $this->error[3];
          break;
        case 4: // UPLOAD_ERR_NO_FILE
          $this->message = $this->error[4];
          break;
        default:
          $this->message = $this->error[18];
          break;
      }
    }
  }
}
