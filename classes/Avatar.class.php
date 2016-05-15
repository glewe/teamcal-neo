<?php
/**
 * Avatar.class.php
 *
 * @category TeamCal Neo 
 * @version 0.5.006
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2016 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed. (Not available yet)
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to deal with user avatars
 */
class Avatar
{
   var $allowedTypes = '';
   var $fileExtension = '';
   var $fileName = '';
   var $tmpFileName = '';
   var $maxHeight = '';
   var $maxWidth = '';
   var $maxSize = '';
   var $message = '';
   var $path = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   function __construct()
   {
      global $C, $CONF;
      
      $this->maxHeight = $C->read("avatarHeight");
      $this->maxWidth = $C->read("avatarWidth");
      $this->maxSize = $C->read("avatarMaxSize");
      $this->path = $CONF['app_avatar_dir'];
      $this->allowedTypes = array (
         "gif",
         "jpg",
         "png" 
      );
   }
   
   // ---------------------------------------------------------------------
   /**
    * Find avatar for a given user (username=avatar file name)
    *
    * @param string $uname Username (file name) to find
    * @return boolean True if found, false if not
    */
   function find($uname)
   {
      foreach ( $this->allowedTypes as $extension )
      {
         if (file_exists($this->path . $uname . "." . $extension))
         {
            $this->fileName = $uname;
            $this->fileExtension = $extension;
            return true;
         }
      }
      return false;
   }
   
   // ---------------------------------------------------------------------
   /**
    * Deletes avatar for a given user (username=avatar file name)
    *
    * @param string $uname Username (for avatars named like the username)
    * @param string $avatar Current user avatar (file name with extension)
    */
   function delete($uname, $avatar)
   {
      foreach ( $this->allowedTypes as $extension )
      {
         if (file_exists($this->path . $uname . "." . $extension))
         {
            unlink($this->path . $uname . "." . $extension);
         }
      }
      
      if (file_exists($this->path . $avatar) and $avatar != 'noavatar_male.png' and $avatar != 'noavatar_female.png' and substr($avatar, 0, 3) != 'is_')
      {
         unlink($this->path . $avatar);
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Saves avatar for a given user (username=avatar file name)
    *
    * @param string $uname Username (file name) to save
    */
   function save($uname)
   {
      global $_FILES;
      global $LANG;
      $this->message = '';
      
      if (is_uploaded_file($_FILES['imgfile']['tmp_name']))
      {
         $this->fileName = $_FILES['imgfile']['name'];
         $this->tmpFileName = $_FILES['imgfile']['tmp_name'];
         $this->fileExtension = $this->getFileExtension($this->fileName);
         $this->fileExtension = strtolower($this->fileExtension);
         
         if (is_numeric(array_search(strtolower($this->fileExtension), $this->allowedTypes)))
         {
            $newfile = $this->path . $uname . "." . $this->fileExtension;
            /**
             * Check size and resize if necessary
             */
            $imgsize = GetImageSize($this->tmpFileName);
            $width = $imgsize[0];
            $height = $imgsize[1];
            if (($imgsize[0] > $this->maxWidth) || ($imgsize[1] > $this->maxHeight))
            {
               if ($width > $this->maxWidth && $height <= $this->maxHeight)
               {
                  $ratio = $this->maxWidth / $width;
               }
               elseif ($height > $this->maxHeight && $width <= $this->maxWidth)
               {
                  $ratio = $this->maxHeight / $height;
               }
               elseif ($width > $this->maxWidth && $height > $this->maxHeight)
               {
                  $ratio1 = $this->maxWidth / $width;
                  $ratio2 = $this->maxHeight / $height;
                  $ratio = ($ratio1 < $ratio2) ? $ratio1 : $ratio2;
               }
               else
               {
                  $ratio = 1;
               }
               $nWidth = floor($width * $ratio);
               $nHeight = floor($height * $ratio);
               
               // echo "<script type=\"text/javascript\">alert(\"Debug: ".$imgsize[0]." ".$imgsize[1]." ".$nWidth."
               // ".$nHeight."\");</script>";
               
               switch (strtolower($this->fileExtension))
               {
                  case "gif" :
                     $origPic = imagecreatefromgif($this->tmpFileName);
                     $newPic = imagecreate($nWidth, $nHeight);
                     imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                     imagegif($newPic, $newfile);
                     imagedestroy($origPic);
                     break;
                  
                  case "jpg" :
                  case "jpeg" :
                     $origPic = imagecreatefromjpeg($this->tmpFileName);
                     $newPic = imagecreatetruecolor($nWidth, $nHeight);
                     imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                     imagejpeg($newPic, $newfile, 90);
                     imagedestroy($origPic);
                     break;
                  
                  case "png" :
                     $origPic = imagecreatefrompng($this->tmpFileName);
                     $newPic = imagecreate($nWidth, $nHeight);
                     imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                     imagepng($newPic, $newfile);
                     imagedestroy($origPic);
                     break;
               }
            }
            else
            {
               /**
                * The file is within the size restrictions.
                * Just copy it to its destination.
                */
               if (!copy($this->tmpFileName, $newfile))
               {
                  $this->message = $LANG['ava_write_error'];
               }
            }
            /**
             * Delete the temporary uploaded file
             */
            unlink($this->tmpFileName);
            /**
             * Delete previous avatars if exist
             */
            foreach ( $this->allowedTypes as $type )
            {
               if ($type != $this->fileExtension && file_exists($this->path . $uname . "." . $type)) unlink($this->path . $uname . "." . $type);
            }
         }
         else
         {
            $this->message = $LANG['ava_wrongtype_1'];
            $this->message .= $this->fileextension . " . ";
            $this->message .= $LANG['ava_wrongtype_2'];
            foreach ( $this->allowedTypes as $allowedtype )
            {
               $this->message .= strtoupper($allowedtype) . ", ";
            }
            $this->message = substr($this->message, 0, strlen($this->message) - 2);
            $this->message .= ".";
         }
      }
      else
      {
         switch ($_FILES['imgfile']['error'])
         {
            case 1 : // UPLOAD_ERR_INI_SIZE
               $this->message = $LANG['ava_upload_error_1'];
               break;
            
            case 2 : // UPLOAD_ERR_FORM_SIZE
               $this->message = $LANG['ava_upload_error_2a'] . $this->maxSize . $LANG['ava_upload_error_2b'];
               break;
            
            case 3 : // UPLOAD_ERR_PARTIAL
               $this->message = $LANG['ava_upload_error_3'];
               break;
            
            case 4 : // UPLOAD_ERR_NO_FILE
               $this->message = $LANG['ava_upload_error_4'];
               break;
            
            default :
               $this->message = $LANG['ava_upload_error'];
               break;
         }
      }
   }
   
   // ---------------------------------------------------------------------
   /**
    * Extract file extension of a given file name
    *
    * @param string $str File name to scan
    * @return string File extension if exists
    */
   function getFileExtension($str)
   {
      $i = strrpos($str, ".");
      if (!$i) return "";
      $l = strlen($str) - $i;
      $ext = substr($str, $i + 1, $l);
      return $ext;
   }
}
?>
