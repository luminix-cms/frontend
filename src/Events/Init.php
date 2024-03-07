<?php

namespace Luminix\Frontend\Events;

use Illuminate\Foundation\Events\Dispatchable;

class Init
{
    use Dispatchable;

    public function __construct(
        public array $boot
    )
    {
        //
    }
}