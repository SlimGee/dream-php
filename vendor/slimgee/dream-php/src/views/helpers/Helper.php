<?php
namespace Dream\Views\Helpers;
use App\Helpers\ApplicationHelper;
use Dream\Registry;
use Dream\Http\Sessions\Session;

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
      Session::set('authenticity_token',sha1(Registry::get('token')));
      return sha1(Registry::get('token'));
  }
}
