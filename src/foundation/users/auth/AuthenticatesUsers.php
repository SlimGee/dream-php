<?php
namespace Dream\Foundation\Users\Auth;
use Dream\Http\Sessions\Session;
use App\Models\User;
/**
 *
 */
trait AuthenticatesUsers
{
    public function login()
    {
        if (is_logged_in()) {
            alert('You are already logged in.');
            return redirect_back();
        }
    }

    public function logout()
    {
        Session::erase('current_user');
        notice("You have successfuly logged out.");
        redirect_back();
    }

    public function verify()
    {
        $user = User::find_by('email',$this->user_params()['email']);
        if ($user && password_verify($this->user_params()['password'],$user->password)){
            unset($user->password);
            Session::set('current_user',serialize($user));
            $back = (Session::check('login_back_link')) ? Session::get('login_back_link') : $this->redirectTo;
            Session::erase('login_back_link');
            notice("You have successfuly logged in.");
            return redirect_to($back);
        }
        alert('Incorect username or password');
        return redirect_back();
    }

    public static function authenticate($user)
    {
        if (!is_object($user)) {
            return false;
        }
        if (!$user->id) {
            return false;
        }
        if (!User::find($user->id)) {
            return false;
        }
        return true;
    }
}
