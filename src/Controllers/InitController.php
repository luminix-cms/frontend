<?php

namespace Luminix\Frontend\Controllers;

use Illuminate\Routing\Controller;
use Luminix\Frontend\Facades\Boot;

class InitController extends Controller
{
    public function init()
    {
        return response()->json(
            Boot::get()
        );
    }
}