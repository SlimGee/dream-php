<?php
namespace Dream\Foundation\Users\Auth;
use Dream\Validator;
use Dream\Http\Sessions\Session;
/**
 *
 */
trait RegistersUsers
{
    public function new()
    {
    }

    public function create_user()
    {
        $data = $this->user_params();
        if (!$this->validate($data)) {
            foreach (Validator::get_errors() as $error) {
                alert($error);
            }
            fallback_vals($data);
            return redirect_back();
        }
        $u = $this->create($data);
        if ($u->id) {
            flush_fallback_vals();
            unset($u->password);
            Session::set('current_user',serialize($u));
            notice("Your account was successfuly created.");
            return redirect_to($this->redirectTo);
        }
        alert('There was an error registering your account');
        return redirect_back();
    }
}
