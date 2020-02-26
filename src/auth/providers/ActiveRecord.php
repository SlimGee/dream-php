<?php

namespace Dream\Auth\Providers;

use Dream\Standards\Auth\AuthServiceInterface;
use Dream\Standards\Auth\AuthInterface;
use App\Models\User;

/**
 *
 */
class ActiveRecord implements AuthServiceInterface
{
    public function attempt(AuthInterface $auth, $cred, $blueprint)
    {
        if (!isset($cred[$blueprint['username']])) {
            return $auth->authenticate($cred);
        }
        
        $user = User::find_by($blueprint['username'], $cred[$blueprint['username']]);
        if ($user && password_verify($cred['password'], $user->{$blueprint['password']})) {
            if (isset($cred[$blueprint['remember']])) {
                $user->{$blueprint['remember']} = md5(openssl_random_pseudo_bytes(128));
                $user->save();
                app()->registry()
                    ->get('cookie')
                    ::set($auth->key, $user->{$blueprint['remember']}, $auth->rememberMeEx);
            }
            $auth->login($user);
            return true;
        }
        return $auth->authenticate($cred);
    }
}
