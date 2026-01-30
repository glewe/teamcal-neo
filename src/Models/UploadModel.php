<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * UploadModel
 *
 * This class provides methods and properties for attachment uploads.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class UploadModel
{
  public bool   $create_directory    = true;
  public string $do_filename_check   = 'n';
  public array  $error               = [];
  public string $ext_string          = '';
  public array  $extensions          = [];
  public string $file_copy           = ''; // the new name
  public int    $http_error          = 0;
  public int    $max_length_filename = 100;
  public array  $message             = [];
  public bool   $rename_file         = false; // if this true the file copy gets a new name
  public string $replace             = 'n';
  public string $the_file            = '';
  public string $the_temp_file       = '';
  public string $the_new_file        = '';
  public string $upload_dir          = '';
  public array  $uploaded_file       = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor.
   *
   * @param array|null $lang Optional language array
   */
  public function __construct(?array $lang = null) {
    global $LANG;

    $language = $lang ?? $LANG;

    $this->rename_file = false;
    $this->ext_string  = "";

    $this->error[0]  = $language['upl_error_0'] ?? 'File uploaded successfully.';
    $this->error[1]  = $language['upl_error_1'] ?? 'File too large (PHP limit).';
    $this->error[2]  = $language['upl_error_2'] ?? 'File too large (HTML limit).';
    $this->error[3]  = $language['upl_error_3'] ?? 'Partial upload.';
    $this->error[4]  = $language['upl_error_4'] ?? 'No file uploaded.';
    $this->error[10] = $language['upl_error_10'] ?? 'Filename missing.';
    $this->error[11] = $language['upl_error_11'] ?? 'Invalid file extension: %s';
    $this->error[12] = $language['upl_error_12'] ?? 'Invalid filename.';
    $this->error[13] = $language['upl_error_13'] ?? 'Filename too long (max %d characters).';
    $this->error[14] = $language['upl_error_14'] ?? 'Upload directory does not exist and cannot be created.';
    $this->error[15] = $language['upl_error_15'] ?? 'File already exists: %s';
    $this->error[16] = $language['upl_error_16'] ?? 'File renamed to: %s';
    $this->error[17] = $language['upl_error_17'] ?? 'MIME type not allowed.';
    $this->error[18] = $language['upl_error_18'] ?? 'File size zero.';
    $this->error[19] = $language['upl_error_19'] ?? 'Transfer failed.';
  }

  //---------------------------------------------------------------------------
  /**
   * Check whether a given directory exists. If not, create it.
   *
   * @param string $directory Directory to check
   *
   * @return boolean True if exists or created, false if not or creation failed.
   */
  public function checkDir(string $directory): bool {
    if (!is_dir($directory)) {
      if ($this->create_directory) {
        umask(0000);
        mkdir($directory, 0777, true);
        return true;
      }
      return false;
    }
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks the filename.
   *
   * @param string $the_name Filename to check
   *
   * @return boolean True if correct, false if not.
   */
  public function checkFileName(string $the_name): bool {
    if ($the_name != "") {
      if (strlen($the_name) > $this->max_length_filename) {
        $this->message[] = sprintf($this->error[13], $this->max_length_filename);
        return false;
      }
      else {
        if ($this->do_filename_check == "y") {
          if (preg_match("/^[a-z0-9\._-]*\.(.){1,5}$/i", $the_name)) {
            return true;
          }
          else {
            $this->message[] = $this->error[12];
            return false;
          }
        }
        else {
          return true;
        }
      }
    }
    else {
      $this->message[] = $this->error[10];
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given file exists.
   *
   * @param string $file_name Filename to check
   *
   * @return boolean True if exists, false if not
   */
  public function fileExists(string $file_name): bool {
    if ($this->replace == "y") {
      return false;
    }
    return file_exists($this->upload_dir . $file_name);
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a string containing all error strings, separated by a <br> tag.
   *
   * @return string HTML error messages
   */
  public function getErrors(): string {
    return implode("<br>", $this->message);
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the extension of a given filename.
   *
   * @param string $from_file Filename to check
   *
   * @return string Filename extension
   */
  private function getExtension(string $from_file): string {
    $ext = strtolower(strrchr($from_file, "."));
    return ltrim($ext, ".");
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a string containing all extensions, separated by ', '.
   *
   * @return void
   */
  private function getExtensions(): void {
    $this->ext_string = implode(", ", $this->extensions);
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the uploaded file info.
   *
   * @param string $name Filename to check
   *
   * @return void
   */
  public function getUploadedFileInfo(string $name): void {
    $this->uploaded_file['name'] = basename($name);
    $this->uploaded_file['size'] = filesize($name);
    if (function_exists("mime_content_type")) {
      $this->uploaded_file['mime'] = mime_content_type($name);
    }
    if ($img_dim = getimagesize($name)) {
      $this->uploaded_file['dimX'] = $img_dim[0];
      $this->uploaded_file['dimY'] = $img_dim[1];
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Validates the file extension of the file to upload against an array of
   * allowed extensions.
   *
   * @return boolean True if valid, false if not
   */
  private function isValidExtension(): bool {
    $extension = $this->getExtension($this->the_file);
    return in_array($extension, $this->extensions);
  }

  //---------------------------------------------------------------------------
  /**
   * Moves the uploaded temporary file to its final folder/name.
   *
   * @param string $tmp_file Temp filename
   * @param string $new_file New filename
   *
   * @return boolean True if successful, false if not.
   */
  private function moveUpload(string $tmp_file, string $new_file): bool {
    if (!$this->fileExists($new_file)) {
      $newfile = $this->upload_dir . $new_file;
      if ($this->checkDir($this->upload_dir)) {
        if (move_uploaded_file($tmp_file, $newfile)) {
          umask(0000);
          chmod($newfile, 0666);
          return true;
        }
        $this->message[] = $this->error[19];
        return false;
      }
      $this->message[] = $this->error[14];
      return false;
    }
    $this->message[] = sprintf($this->error[15], $this->the_file);
    return false;
  }

  //---------------------------------------------------------------------------
  /**
   * Creates and returns a unique new filename.
   *
   * @param string $new_name New desired file name (optional)
   *
   * @return string $name New filename
   */
  private function setFileName(string $new_name = ""): string {
    if ($this->rename_file) {
      if ($this->the_file == "") {
        return '';
      }
      $name = ($new_name == "") ? (string) strtotime("now") : $new_name;
      // sleep(3); // Removed sleep 3 as it's a bit extreme for production
      $name = "f" . $name . "." . $this->getExtension($this->the_file);
    }
    else {
      // Spaces will result in problems on linux systems. So let's replace them.
      $name = str_replace(" ", "_", $this->the_file);
    }
    return $name;
  }

  //---------------------------------------------------------------------------
  /**
   * Uploads the file submitted thru class variables.
   *
   * @param string $to_name New desired file name (optional)
   *
   * @return boolean True if upload successful, false if not
   */
  public function uploadFile(string $to_name = ""): bool {
    $new_name = $this->setFileName($to_name);
    if ($this->checkFileName($new_name)) {
      if ($this->isValidExtension()) {
        if (is_uploaded_file($this->the_temp_file)) {
          $this->file_copy = $new_name;
          if ($this->moveUpload($this->the_temp_file, $this->file_copy)) {
            $this->message[] = $this->error[(int) $this->http_error] ?? $this->error[0];
            if ($this->rename_file) {
              $this->message[] = sprintf($this->error[16], $this->file_copy);
            }
            else {
              $this->message[] = sprintf($this->error[0], $this->the_file);
            }
            return true;
          }
        }
        else {
          $this->message[] = $this->error[(int) $this->http_error] ?? 'Upload failed.';
          return false;
        }
      }
      else {
        $this->getExtensions();
        $this->message[] = sprintf($this->error[11], $this->ext_string);
        return false;
      }
    }
    return false;
  }
}
