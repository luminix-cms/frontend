<?php

namespace Luminix\Frontend\Facades;

use Illuminate\Support\Facades\Facade;
use Luminix\Frontend\Services\ManifestService;

class Manifest extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ManifestService::class;
    }
}
