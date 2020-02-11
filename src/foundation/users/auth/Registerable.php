<?php

namespace Dream\Foundation\Users\Auth;

use Dream\Validator;
use Dream\Session\Session;
/**
 *
 */
trait Registerable
{
    public function register()
    {
    }

    public function createAction()
    {
        if (!$this->validate($this->user_params())) {
            validation_errors();
            return redirect_back();
        }
        $user = $this->create($this->user_params());
        if ($user) {
            auth()->login($user);
        }
        notice("You have succefully registered");
        $back = Session::has('login_back_link') ? Session::get('login_back_link') : $this->redirectTo;
        return redirect_to($back);
    }
}
