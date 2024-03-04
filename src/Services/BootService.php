<?php

namespace Luminix\Frontend\Services;

use Illuminate\Support\Traits\Macroable;

class BootService {

    use Macroable;

    public function get()
    {
        $boot = [
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

        if (static::hasMacro('onInit')) {
            $boot = static::onInit($boot);
        }

        return $boot;
    }
}