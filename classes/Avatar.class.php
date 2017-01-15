<?php
/**
 * Avatar.class.php
 *
 * @category TeamCal Neo 
 * @version 1.3.004
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2017 by George Lewe
 * @link http://www.lewe.com
 * @license This program cannot be licensed. Redistribution is not allowed.
 */
if (!defined('VALID_ROOT')) exit('No direct access allowed!');

/**
 * Provides properties and methods to deal with user avatars
 */
class Avatar
{
   public $allowedTypes = '';
   public $error = array();
   public $fileExtension = '';
   public $fileName = '';
   public $tmpFileName = '';
   public $maxHeight = '';
   public $maxWidth = '';
   public $maxSize = '';
   public $message = '';
   public $path = '';
   
   // ---------------------------------------------------------------------
   /**
    * Constructor
    */
   public function __construct()
   {
      global $C, $CONF, $LANG;
      
      $this->maxHeight = 80;
      $this->maxWidth = 80;
      $this->maxSize = $CONF['avatarMaxsize'];
      $this->path = 'upload/images/avatars/';
      $this->allowedTypes = $CONF['avatarExtensions'];
     
      
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
   
   // ---------------------------------------------------------------------
   /**
    * Find avatar for a given user (username=avatar file name)
    *
    * @param string $uname Username (file name) to find
    * @return boolean True if found, false if not
    */
   public function find($uname)
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
   public function delete($uname, $avatar)
   {
      foreach ( $this->allowedTypes as $extension )
      {
         if (file_exists($this->path . $uname . "." . $extension))
         {
            unlink($this->path . $uname . "." . $extension);
         }
      }
      
      if (file_exists($this->path . $avatar) and $avatar != 'default_male.png' and $avatar != 'default_female.png' and substr($avatar, 0, 3) != 'is_')
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
   public function save($uname)
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
            
            //
            // Check size and resize if necessary
            //
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
               
               // echo "<script type=\"text/javascript\">alert(\"Debug: ".$imgsize[0]." ".$imgsize[1]." ".$nWidth." ".$nHeight."\");</script>";
               
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
                  $this->message = $this->error[19];
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
            $extList = implode(',', $this->allowedTypes); 
            $this->message = sprintf($this->error[11], $extList);
         }
      }
      else
      {
         switch ($_FILES['imgfile']['error'])
         {
            case 1 : // UPLOAD_ERR_INI_SIZE
               $this->message = $this->error[1];
               break;
            
            case 2 : // UPLOAD_ERR_FORM_SIZE
               $this->message = $this->error[2];
               break;
            
            case 3 : // UPLOAD_ERR_PARTIAL
               $this->message = $this->error[3];
               break;
            
            case 4 : // UPLOAD_ERR_NO_FILE
               $this->message = $this->error[4];
               break;
            
            default :
               $this->message = $this->error[18];
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
   private function getFileExtension($str)
   {
      $i = strrpos($str, ".");
      if (!$i) return "";
      $l = strlen($str) - $i;
      $ext = substr($str, $i + 1, $l);
      return $ext;
   }
}
?>
