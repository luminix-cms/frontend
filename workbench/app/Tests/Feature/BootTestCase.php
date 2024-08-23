<?php

namespace Workbench\App\Tests\Feature;

use Luminix\Backend\Services\ModelFinder;

use Workbench\App\Tests\TestCase;

class BootTestCase extends TestCase
{    

    public function test_if_luminix_backend_includes_models()
    {
        $includes = config('luminix.backend.models.include');

        $this->assertEquals($includes, $this->expected_models);
    }

    public function test_it_can_find_models()
    {
        $models = $this->app->make(ModelFinder::class)->all();

        $this->assertEquals([
            'user' => 'Workbench\App\Models\User',
            'post' => 'Workbench\App\Models\Post',
        ], $models->toArray());
    }
    
}