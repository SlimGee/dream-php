<?php

namespace Dream\Auth\Providers;

use Dream\Standards\Auth\AuthServiceInterface;
use Dream\Standards\Auth\AuthInterface;
use App\Models\User;

/**
 *
 */
class Remember implements AuthServiceInterface
{
    public function attempt(AuthInterface $auth, $cred, $blueprint)
    {
        if (isset($cred['rememberToken'])) {
            $user = User::find_by($blueprint['remember'], $cred[$blueprint['remember']]);
            if ($user) {
                $auth->login($user);
                return true;
            }
        }
        return $auth->authenticate($cred);
    }
}
