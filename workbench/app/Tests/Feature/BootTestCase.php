<?php

namespace Workbench\App\Tests\Feature;

use Workbench\App\Tests\TestCase;

class BootTestCase extends TestCase
{

    protected $expected = [
        'app' => [
            'name' => 'Laravel',
            'env' => 'testing',
            // 'env' => 'local',
            'debug' => true, 
            'url' => 'http://localhost', 
            'locale' => 'en',
            // 'locale' => 'pt-BR',
            'fallback_locale' => 'en',
        ], 
        'auth' => [
            'user' => null, 
            'csrf' => null
        ], 
        'manifest' => [
            'models' => [],
            'routes' => []
        ],
    ];
    
}