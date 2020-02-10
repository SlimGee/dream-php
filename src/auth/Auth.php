<?php

namespace Dream\Auth;

use Dream\Standards\Auth\AuthInterface;
use Dream\Standards\Auth\AuthServiceInterface;
use Dream\Standards\Auth\StorageInterface;

/**
 *
 */
class Auth implements AuthInterface
{
    protected $providers = [];

    protected $cred;

    public $storage;

    public $key;

    public function __construct(array $config, Storage\SessionStorage $storage)
    {
        $this->cred = $config['cred'];
        foreach ($config['providers'] as $provider) {
            $this->use(
                app()->get($provider)
            );
        }
        $this->storage = $storage;
        $this->key = "current_user";
        $this->rememberMeEx = 60 * 60 * 2;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate($cred)
    {
        if (app()->registry()->get('cookie')::has($this->key)) {
            $cred['rememberToken'] = app()->registry()->get('cookie')::get($this->key);
        }
        if (count($this->providers) === 0) {
            if ($this->hasIdentity()) {
                return true;
            }
            return false;
        }
        $auth = array_shift($this->providers);
        return $auth->attempt($this, $cred, $this->cred);
    }

    /**
     * {@inheritDoc}ServerRequestInterface
     */
    public function use(AuthServiceInterface $service)
    {
        $this->providers[] = $service;
    }

    /**
     * {@inheritDoc}
     */
    public function hasIdentity()
    {
        if (app()->registry()->get('cookie')::has($this->key)) {
            return true;
        }
        return $this->storage->has($this->key);
    }

    /**
     * {@inheritDoc}
     */
    public function user()
    {
        return User::find(
            $this->storage->get($this->key)
        );
    }

    /**
     * @inheritDoc
     */
    public function logout()
    {
        $this->storage->erase($this->key);
        app()->registry()->get('cookie')::erase($this->key);
    }

    public function login($user)
    {
        $this->storage->set($this->key, $user->id);
    }

    public static function routes()
    {
        \Dream\Route\Route::get('/register', 'users#register')->name('new_user');
        \Dream\Route\Route::get('/login', 'users#login')->name('login');
        \Dream\Route\Route::get('/logout', 'users#logout')->name('logout');
        \Dream\Route\Route::get('/password/reset', 'users#attempt')->name('password_reset');
        \Dream\Route\Route::get('/password/reset/verify', 'users#verify')->name('token_verify');
        \Dream\Route\Route::post('/authenticate', 'users#authenticate')->name('authenticate');
        \Dream\Route\Route::post('/create', 'users#createAction')->name('users_create');
        \Dream\Route\Route::post('/users/password/new', 'users#newPassword')->name('new_password');
    }
}
