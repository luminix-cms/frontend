<?php

return [
    'boot' => [
        'method' => 'api',
        'includes_manifest' => true,
        'middleware' => ['api'],
    ],
    'models' => [
        'public' => [],
        'exclude' => [],
    ],
    'routes' => [
        'public' => [],
        'exclude' => [],
    ],
    
];