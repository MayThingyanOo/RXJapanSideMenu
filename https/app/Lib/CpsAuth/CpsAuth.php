<?php

namespace App\Lib\CpsAuth;

use Auth;
use Illuminate\Support\Str;

class CpsAuth
{
    private $guard;
    private $is_admin = false;

    /**
     * @param $guard
     * @return $this
     */
    public function setGuard($guard)
    {
        $this->guard = Auth::guard($guard);

        return $this;
    }

    public function isGuest()
    {
        return $this->guard->guest();
    }

    public function user()
    {
        if (empty($this->guard)) {
            return [];
        }

        return $this->guard->user();
    }

    public function id()
    {
        // @todo
        if (empty($this->guard)) {
            return 999999;
        }

        return $this->guard->id();
    }

    public function logout()
    {
        return $this->guard->logout();
    }

    public function attempt($credentials)
    {
        return $this->guard->attempt($credentials);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function isSuperUser()
    {
        return $this->user()->is_super_user_flag;
    }

    public function loginUsingId($id)
    {
        return $this->guard->loginUsingId($id);
    }
}
