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
        $user = User::find_by($blueprint['username'], $cred['username']);
        if ($user && password_verify($user->{$blueprint['password']}, $cred['password'])) {
            if (isset($cred[$blueprint['remember']]) && $cred[$blueprint['remember']] == true) {
                $user->{$blueprint['remember']} = md5(openssl_random_pseudo_bytes(128));
                $user->save();
                app()->cookie()->set($auth->key, $user->{$blueprint['remember']}, $auth->rememberMeEx);
            }
            $auth->login($user);
            return true;
        }
        return $auth->authenticate($cred);
    }
}
