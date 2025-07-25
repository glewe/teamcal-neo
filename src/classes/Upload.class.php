<?php

/**
 * Upload
 *
 * This class provides methods and properties for attachment uploads.
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */
class Upload {
  public bool $create_directory = true;
  public $do_filename_check;
  public array $error = [];
  public string $ext_string = '';
  public array $extensions = [];
  public $file_copy; // the new name
  public $http_error;
  public int $max_length_filename = 100;
  public array $message = [];
  public bool $rename_file = false; // if this private is true the file copy get a new name
  public $replace;
  public $the_file;
  public $the_temp_file;
  public $the_new_file;
  public $upload_dir;
  public array $uploaded_file = [];

  //---------------------------------------------------------------------------
  /**
   * Constructor
   */
  public function __construct() {
    global $LANG;

    $this->rename_file = false;
    $this->ext_string = "";

    $this->error[0] = $LANG['upl_error_0'];
    $this->error[1] = $LANG['upl_error_1'];
    $this->error[2] = $LANG['upl_error_2'];
    $this->error[3] = $LANG['upl_error_3'];
    $this->error[4] = $LANG['upl_error_4'];
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
   * Check whether a given directory exists. If not, create it.
   *
   * @param string $directory Directory to check
   * @return boolean True if exists or created, false if not or creation failed.
   */
  public function checkDir(string $directory): bool {
    if (!is_dir($directory)) {
      if ($this->create_directory) {
        umask(0027);
        mkdir($directory, 0777);
        return true;
      }
      return false;
    }
    return true;
  }

  //---------------------------------------------------------------------------
  /**
   * Checks the filename
   *
   * @param string $the_name Filename to check
   * @return boolean True if correct, false if not.
   */
  public function checkFileName(string $the_name): bool {
    if ($the_name != "") {
      if (strlen($the_name) > $this->max_length_filename) {
        $this->message[] = sprintf($this->error[13], $this->max_length_filename);
        return false;
      } else {
        if ($this->do_filename_check == "y") {
          if (preg_match("/^[a-z0-9\._-]*\.(.){1,5}$/i", $the_name)) {
            return true;
          } else {
            $this->message[] = $this->error[12];
            return false;
          }
        } else {
          return true;
        }
      }
    } else {
      $this->message[] = $this->error[10];
      return false;
    }
  }

  //---------------------------------------------------------------------------
  /**
   * Checks whether a given file exists.
   *
   * @param string $file_name Filename to check
   * @return boolean True if exists, false if not
   */
  public function fileExists(string $file_name): bool {
    if ($this->replace == "y") {
      return true;
    }
    return file_exists($this->upload_dir . $file_name);
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a string containing all error strings, separated by a <br> tag
   *
   * @return string HTML error messages
   */
  public function getErrors(): string {
    return implode("<br>", $this->message);
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the extension of a given filename
   *
   * @param string $from_file Filename to check
   * @return string Filename extension
   */
  private function getExtension(string $from_file): string {
    $ext = strtolower(strrchr($from_file, "."));
    return ltrim($ext, ".");
  }

  //---------------------------------------------------------------------------
  /**
   * Creates a string containing all extensions, separated by ', '
   *
   * @return void
   */
  private function getExtensions(): void {
    $this->ext_string = implode(", ", $this->extensions);
  }

  //---------------------------------------------------------------------------
  /**
   * Gets the uploaded file info
   *
   * @param string $name Filename to check
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
   * Moves the uploaded temporary file to its final folder/name
   *
   * @param string $tmp_file Temp filename
   * @param string $new_file New filename
   * @return boolean True if successful, false if not.
   */
  private function moveUpload(string $tmp_file, string $new_file): bool {
    if ($this->fileExists($new_file)) {
      $newfile = $this->upload_dir . $new_file;
      if ($this->checkDir($this->upload_dir)) {
        if (move_uploaded_file($tmp_file, $newfile)) {
          umask(0027);
          chmod($newfile, 0750);
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
   * Creates and returns a unique new filename
   *
   * @param string $new_name New desired file name (optional)
   * @return string $name New filename
   */
  private function setFileName(string $new_name = ""): string {
    if ($this->rename_file) {
      if ($this->the_file == "") {
        return '';
      }
      $name = ($new_name == "") ? strtotime("now") : $new_name;
      sleep(3);
      $name = "f" . $name . $this->getExtension($this->the_file);
    } else {
      // Spaces will result in problems on linux systems. So let's replace them.
      $name = str_replace(" ", "_", $this->the_file);
    }
    return $name;
  }

  //---------------------------------------------------------------------------
  /**
   * Uploads the file submitted thru class variables
   *
   * @param string $to_name New desired file name (optional)
   * @return string|boolean True if upload successful, false if not
   */
  public function uploadFile(string $to_name = ""): bool {
    $new_name = $this->setFileName($to_name);
    if ($this->checkFileName($new_name)) {
      if ($this->isValidExtension()) {
        if (is_uploaded_file($this->the_temp_file)) {
          $this->file_copy = $new_name;
          if ($this->moveUpload($this->the_temp_file, $this->file_copy)) {
            $this->message[] = $this->error[$this->http_error];
            if ($this->rename_file) {
              $this->message[] = sprintf($this->error[16], $this->file_copy);
            } else {
              $this->message[] = sprintf($this->error[0], $this->the_file);
            }
            return true;
          }
        } else {
          $this->message[] = $this->error[$this->http_error];
          return false;
        }
      } else {
        $this->getExtensions();
        $this->message[] = sprintf($this->error[11], $this->ext_string);
        return false;
      }
    }
    return false;
  }
}
