<?php
/**
 * Upload.class.php
 *
 * @category TeamCal Neo 
 * @version 0.5.002
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to upload files
 */
class Upload
{
   var $the_file;
   var $the_temp_file;
   var $the_new_file;
   
   var $create_directory = true;
   var $do_filename_check;
   var $error;
   var $ext_string;
   var $extensions = array();
   var $file_copy; // the new name
   var $http_error;
   var $max_length_filename = 100;
   var $message = array ();
   var $rename_file; // if this var is true the file copy get a new name
   var $replace;
   var $upload_dir;
   var $uploaded_file = array();
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $CONF, $LANG;
      
      $this->rename_file = false;
      $this->ext_string = "";
      
      $this->error[0] = $LANG['upload_error_0'];
      $this->error[1] = $LANG['upload_error_1'];
      $this->error[2] = $LANG['upload_error_2'];
      $this->error[3] = $LANG['upload_error_3'];
      $this->error[4] = $LANG['upload_error_4'];
      $this->error[10] = $LANG['upload_error_10'];
      $this->error[11] = $LANG['upload_error_11'];
      $this->error[12] = $LANG['upload_error_12'];
      $this->error[13] = $LANG['upload_error_13'];
      $this->error[14] = $LANG['upload_error_14'];
      $this->error[15] = $LANG['upload_error_15'];
      $this->error[16] = $LANG['upload_error_16'];
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a given directory exists. If not, it creates it.
    *
    * @param string $directory
    *           Directory to check
    * @return boolean True if exists or created, false if not or creation failed.
    */
   function checkDir($directory)
   {
      if (!is_dir($directory))
      {
         if ($this->create_directory)
         {
            umask(0);
            mkdir($directory, 0777);
            return true;
         }
         else
         {
            return false;
         }
      }
      else
      {
         return true;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks the filename
    *
    * @param string $the_name
    *           Filename to check
    * @return bool True if correct, false if not.
    */
   function checkFileName($the_name)
   {
      if ($the_name != "")
      {
         if (strlen($the_name) > $this->max_length_filename)
         {
            $this->message[] = sprintf($this->error[13], $this->max_length_filename);
            return false;
         }
         else
         {
            if ($this->do_filename_check == "y")
            {
               if (preg_match("/^[a-z0-9_-]*\.(.){1,5}$/i", $the_name))
               {
                  return true;
               }
               else
               {
                  $this->message[] = $this->error[12];
                  return false;
               }
            }
            else
            {
               return true;
            }
         }
      }
      else
      {
         $this->message[] = $this->error[10];
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes a temporary file
    *
    * @param string $file
    *           Temp file to delete
    */
   function deleteTempFile($file)
   {
      $delete = @ unlink($file);
      clearstatcache();
      if (@ file_exists($file))
      {
         $filesys = eregi_replace("/", "\\", $file);
         $delete = @ system("del $filesys");
         clearstatcache();
         if (@ file_exists($file))
         {
            $delete = @ chmod($file, 0644);
            $delete = @ unlink($file);
            $delete = @ system("del $filesys");
         }
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Checks whether a given file exists.
    *
    * @param string $file_name
    *           Filename to check
    * @return bool True if exists, false if not
    */
   function fileExists($file_name)
   {
      if ($this->replace == "y")
      {
         return true;
      }
      else
      {
         if (file_exists($this->upload_dir . $file_name))
         {
            return false;
         }
         else
         {
            return true;
         }
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a string containing all error strings, separated by a <br> tag
    *
    * @return string HTML error messages
    */
   function getErrors()
   {
      $msg_string = '';
      foreach ( $this->message as $value ) $msg_string .= $value . "<br>";
      return $msg_string;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the extension of a given filename
    *
    * @param string $from_file
    *           Filename to check
    * @return string Filename extension
    */
   function getExtension($from_file)
   {
      $ext = strtolower(strrchr($from_file, "."));
      return $ext;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates a string containing all extensions, separated by ', '
    * 
    * @return string Allowed file extensions
    */
   function getExtensions()
   {
      $this->ext_string = implode(", ", $this->extensions);
   }
   
   // ---------------------------------------------------------------------
   /**
    * Gets the uploaded file info
    *
    * @param string $name Filename to check
    * @return string $str File info
    */
   function getUploadedFileInfo($name)
   {
      $this->uploaded_file['name'] = basename($name);
      $this->uploaded_file['size'] = filesize($name);
      
      if (function_exists("mime_content_type"))
      {
         $this->uploaded_file['mime'] = mime_content_type($name);
      }
      
      if ($img_dim = getimagesize($name))
      {
         $this->uploaded_file['dimX'] = $img_dim[0];
         $this->uploaded_file['dimY'] = $img_dim[1];
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Validates the file extension of the file to upload against an array of
    * allowed extensions.
    *
    * @return bool True if valid, false if not
    */
   function isValidExtension()
   {
      $extension = strtolower(substr($this->getExtension($this->the_file),1)); // Remove first character dot (.)
      $ext_array = $this->extensions;
      if (in_array($extension, $ext_array))
      {
         return true;
      }
      else
      {
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Moves the uploaded temporary file to its final folder/name
    *
    * @param string $tmp_file Temp filename
    * @param string $new_file New filename
    * @return bool True if successful, false if not.
    */
   function moveUpload($tmp_file, $new_file)
   {
      if ($this->fileExists($new_file))
      {
         $newfile = $this->upload_dir . $new_file;
         if ($this->checkDir($this->upload_dir))
         {
            if (move_uploaded_file($tmp_file, $newfile))
            {
               umask(0);
               chmod($newfile, 0644);
               return true;
            }
            else
            {
               $this->message[] = 'Could not move uploaded file.';
               return false;
            }
         }
         else
         {
            $this->message[] = $this->error[14];
            return false;
         }
      }
      else
      {
         $this->message[] = sprintf($this->error[15], $this->the_file);
         return false;
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Creates and returns a unique new filename
    *
    * @param string $new_name
    *           New desired file name (optional)
    * @return string $name New filename
    */
   function setFileName($new_name = "")
   {
      if ($this->rename_file)
      {
         if ($this->the_file == "") return;
         $name = ($new_name == "") ? strtotime("now") : $new_name;
         sleep(3);
         $name = "f" . $name . $this->getExtension($this->the_file);
      }
      else
      {
         /**
          * Spaces will result in problems on linux systems.
          * So let's replace them.
          */
         $name = str_replace(" ", "_", $this->the_file);
      }
      return $name;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Uploads the file submitted thru class variables
    *
    * @param string $to_name
    *           New desired file name (optional)
    * @return string True if upload successful, false if not
    */
   function upload($to_name = "")
   {
      $new_name = $this->setFileName($to_name);
      
      if ($this->checkFileName($new_name))
      {
         if ($this->isValidExtension())
         {
            if (is_uploaded_file($this->the_temp_file))
            {
               $this->file_copy = $new_name;
               if ($this->moveUpload($this->the_temp_file, $this->file_copy))
               {
                  $this->message[] = $this->error[$this->http_error];
                  if ($this->rename_file) $this->message[] = sprintf($this->error[16], $this->file_copy);
                  else $this->message[] = sprintf($this->error[0], $this->the_file);
                  return true;
               }
            }
            else
            {
               $this->message[] = $this->error[$this->http_error];
               return false;
            }
         }
         else
         {
            $this->getExtensions();
            $this->message[] = sprintf($this->error[11], $this->ext_string);
            return false;
         }
      }
      else
      {
         return false;
      }
   }
}
?>
