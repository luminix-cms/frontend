<?php

namespace Luminix\Frontend\Services;

use Arandu\Reducible\Reducible;
use Luminix\Frontend\Events\Init;

class BootService {

    use Reducible;

    public function get()
    {
        $boot = [
            'app' => [
                'name' => config('app.name', 'Laravel'),
                'env' => config('app.env', 'production'),
                'debug' => config('app.debug', false),
                'url' => config('app.url', 'http://localhost'),
                'locale' => config('app.locale', 'en'),
                'fallback_locale' => config('app.fallback_locale', 'en'),
            ],
            'auth' => [
                'user' => auth()->user(),
                'csrf' => csrf_token(),
            ],
        ];

        if (config('luminix.frontend.boot.includes_manifest', true)) {
            /** @var ManifestService */
            $manifest = app(ManifestService::class);
            $boot['manifest'] = $manifest->make()->get();
        }

        $boot = static::wireConfig($boot);

        event(new Init($boot));

        return $boot;
    }
}