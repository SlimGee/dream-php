<?php

namespace Dream\Foundation\Users\Auth;

use App\Models\User;

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

            $link = "http://" . app()->host() . token_verify_path() . "?token=" . $user->verify_token . "&email=" . $user->email;

            $mail = Mailer::make('reset_password', [
                'name' => $user->name,
                'link' => $link
            ]);
            
            Mailer::mail($user->name, $user->email, $link);
            notice("Reset instruction has been sent to your email address");
            return redirect_back();
        }
        alert("Email address not found");
        return redirect_back();
    }

    public function verify()
    {
        $user = User::find_by('email', $this->params['email']);
        if (!$user->verify_token === $this->params['token']) {
            alert("We could not verify your token");
            return redirect_to(password_reset_path());
        }
        $this->email = $user->email;
    }

    public function newPassword()
    {
        $data = $this->user_params();

        $valid = \Dream\Validator::make($data, [
                'password' => ['display' => 'Password', 'min' => 6],
                'password_confirmation' => ['display' => 'Password Confirmation', 'matches' => 'password']
            ]);

        if (!$valid) {
            validation_errors();
            return redirect_back();
        }
        $user = User::find_by('email', $data['email']);
        if (!$user) {
            alert("An unknown error occured while processing your request");
            return redirect_back();
        }
        $user->password = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        unset($user->verify_token);
        if (!$user->save()) {
            alert("There was an error changing your password");
            return redirect_back();
        }

        notice("Your password was succesfully changed");
        return redirect_to(login_path());
    }
}
