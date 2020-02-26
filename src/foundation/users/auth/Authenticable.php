<?php
namespace Dream\Foundation\Users\Auth;
use Dream\Session\Session;
use App\Models\User;
/**
 *
 */
trait Authenticable
{
    public function login()
    {
    }

    public function authenticate()
    {
        if (auth()->authenticate($this->user_params())) {
            $back = Session::has('login_back_link') ? Session::get('login_back_link') : $this->redirectTo;
            Session::erase('login_back_link');
            notice("You have succefully logged in");
            return redirect_to($this->redirectTo);
        }
        alert("There was an error with username or password");
        return redirect_back();
    }

    public function logout()
    {
        auth()->logout();
        notice("You have succefully logged out.");
        return redirect_back();
    }
}
