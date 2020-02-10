<?php

namespace App\Http\Controllers;

use Dream\Foundation\Users\Auth\{
    PasswordResetable,
    Authenticable,
    Registerable
};

/**
 *
 */
class Users extends ApplicationController
{
    use PasswordResetable;
    use Authenticable;
    use Registerable;

    protected $redirectTo = '/';

    public function validate($data)
    {
        return \Dream\Validator::make($data, [
            'username' => ['required' => true, 'display' => 'Username'],
            'password' => ['required' => true, 'display' => 'Password', 'min' => 6],
            'password_confirm' => ['required' => true, 'display' => 'Confirmation', 'min' => 6, 'matches' => 'password']
        ]);
    }

    public function create($data = [])
    {
        if (empty($data)) {
            $data = $this->user_params();
        }
        return \App\Models\User::create([
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT , ['cost' => 12])
        ]);
    }

    public function user_params()
    {
        return $this->params['user']->permit(['email', 'password', 'password_confirmation', 'remember_me']);
    }
}
