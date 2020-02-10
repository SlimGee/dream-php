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
            return redirect_to($this->redirectTo);
        }
        return redirect_back();
    }

    public function logut()
    {
        auth()->logout();
        notice("You have succefully logged out.");
        return redirect_back();
    }
}
