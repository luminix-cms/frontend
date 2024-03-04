<?php

namespace Luminix\Frontend\Facades;

use Illuminate\Support\Facades\Facade;
use Luminix\Frontend\Services\BootService;

/**
 * 
 * @method static array get()
 */
class Boot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BootService::class;
    }
}