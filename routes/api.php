<?php

use Illuminate\Support\Facades\Route;

if (config('luminix.frontend.boot.method', 'api') === 'api') {
    Route::middleware(config('luminix.frontend.boot.middleware', ['api']))
        ->get(config('luminix.backend.api.prefix', 'luminix-api') . '/init', 'Luminix\Frontend\Controllers\InitController@init')
        ->name('luminix.init');
}
