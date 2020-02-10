<?php

namespace Dream\Foundation\Users\Auth;

/**
 *
 */
trait PasswordResetable
{
    public function attempt()
    {
    }

    public function token()
    {
        $user = User::find_by('email', $this->params['user']['email']);
        if ($user) {
            $user->verify_token = md5(bin2hex(openssl_random_pseudo_bytes(128)));
            $user->save();
            $link = "Click the following link to reset your password {$user->verify_token}";
            Mailer::mail($user->email, $user->email, $link);
        }
    }

    public function verify()
    {
        $user = User::find_by('email',$this->params['email']);
        if (!$user->verify_token === $this->params['token']) {
            alert("We could not verify your token");
            return redirect_to(password_reset_path());
        }
    }

    public function newPassword()
    {
        $data = $this->users_params();

        $valid = \Dream\Validator::validate($data, [
                'password' => ['display' => 'Password', 'min' => 6],
                'password_confirmation' => ['display' => 'Password Confirmation', 'matches' => 'password']
            ]);

        if (!$valid) {
            validation_errors();
            return redirect_back();
        }
        $user->password = password_hash($data['password'], BYCRYPT, ['cost' => 12]);
        if ($user->save()) {
            alert("There was an error changing your password");
            return redirect_back();
        }
        notice("Your password was succesfully changed");
        return redirect_to(login_path());
    }
}
