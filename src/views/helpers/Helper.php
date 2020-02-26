<?php
namespace Dream\Views\Helpers;

use Dream\Session\Session;

/**
 * View helper
 */
class Helper
{
  public function __construct()
  {
  }

  public function csrf_token()
  {
      Session::set('authenticity_token',sha1(app()->registry()->get('token')));
      return Session::get('authenticity_token');
  }
}
