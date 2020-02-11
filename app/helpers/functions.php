<?php

use Dream\Session\Session;

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
    $back = app()->registry()->get('back_link');
    app()->registry()->set('back_link',$back);
    if (!auth()->hasIdentity()) {
        Session::set('login_back_link',back_link());
        return redirect_to(login_path());
    }
    return true;
}
