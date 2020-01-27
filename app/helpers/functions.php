<?php
use Dream\Registry;
use Dream\Http\Sessions\Session;

function current_user()
{
  return (Session::check('current_user')) ? unserialize(Session::get('current_user')) : null ;
}

function is_logged_in()
{
  return (Session::check('current_user')) ? true : false ;
}

function authenticate_user()
{
    // REVIEW: Bad code !!!!! but works though 
    $back = Registry::get('back_link');
    Registry::get('controller')->set_back_link();
    Registry::set('back_link',$back);
  if (!is_logged_in()) {
      Session::set('login_back_link',back_link());
      return redirect_to(users_login_path());
  }
  if (!App\Controllers\Users::authenticate(current_user())) {
      Session::set('login_back_link',back_link());
      return redirect_to(users_login_path());
  }
  return true;
}
