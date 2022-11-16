<?php

namespace App\Lib\CpsMail;

use Illuminate\Support\Facades\Facade;

class CpsMailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cpsmailfacade';
    }
}
