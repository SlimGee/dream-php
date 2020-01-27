<?php
namespace Dream;

/**
 * upload file class
 * @author Given Ncube
 * @version 1.0
 */
class Uploader
{
  /*
  * destion directory
  * where the files will be stored
  * @access Protected
  */
  Protected $destination;

  /**
  * store error messages
  * @access private
  */
  public $messages = [];

  protected $maxSize = 1024 * 800;

  /*
  * class construtor
  * @params path path where files will be uploaded to
  * @return void
  */
  function __construct($path)
  {
      if (!is_dir($path) || !is_writable($path)) {
        throw new \Exception($path . 'is not a valid writable folder');
      }
      if ($path[strlen($path)-1] != '/') {
        $path .= '/';
      }
      $this->destination = $path;
  }

  /*
  * upload method
  * uploads the file to the server
  * @params
  * @return bool;
  */
  Public function Upload()
  {

      $uploaded = current($_FILES);

    if (is_array($uploaded)) {
      foreach ($uploaded['name'] as $key => $value) {
        $uploadedFile['name'] = $uploaded['name'][$key];
        $uploadedFile['tmp_name'] = $uploaded['tmp_name'][$key];
        $uploadedFile['size'] = $uploaded['size'][$key];
        $uploadedFile['type'] = $uploaded['type'][$key];
        $uploadedFile['error'] = $uploaded['error'][$key];
        if($this->CheckFile($uploadedFile)){
          if ($this->MoveFile($uploadedFile)) {
            // code...
          }
        }
      }
    }else{
      if ($this->CheckFile($uploaded)) {
        if($this->MoveFile($uploaded)){

        }
      }
    }
  }

  /*
  * Check the file for size and type
  * @params file uploaded file
  * @return bool;
  */
  Protected function CheckFile($file)
  {
    if ($file['error'] != 0) {
      $this->GetErrorMessage($file);
      return false;
    }
    if (!$this->checkSize($file)) {
      return false;
    }
    return true;
  }

  /**
  * Get the error code about the uploaed file
  * @params file uploded file
  * @return void;
  */
  Protected function GetErrorMessage($file)
  {
    switch ($file['error']) {
      case 1:
      case 2:
        $this->messages[] = $file['name'] . 'is too big to upload';
        break;
      case 3:
        $this->messages[] = $file['name'] . 'was partially uploaded';
        break;

      case 4:
        $this->messages[] =  'no file was selected';
        break;
      default:
        $this->messages[] = 'there was an error uploading ' .$file['name'];
        break;
    }
  }

  /*
  * Move uploded file to the uploads dir
  * @params file file uploaded
  * @return bool
  */
  Protected function MoveFile($file)
  {
    $success = move_uploaded_file($file['tmp_name'],$this->destination . $file['name']);
    if(!$success){
      return false;
    }
    $this->messages[] = $file['name'] . 'was successfully uploaded';
    return true;
  }

  Protected function setMaxSize($bytes)
  {
    if (is_numeric($bytes) && $bytes > 0) {
      $this->maxSize = $bytes;
    }
  }

  protected function checkSize($file)
  {
    if ($file['size'] > $this->maxSize) {
      $this->messages[] = $file['name'] . 'exceeds maximum file size of '. $this->maxSize/1024 . 'MB';
      return false;
    }
    return true;
  }
}
