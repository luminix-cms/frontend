<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use Workbench\App\Tests\TestCase;

class ManifestApiIncludesTest extends TestCase
{
    use InteractsWithViews; 


    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('luminix.frontend.boot.method', 'api');
        $app['config']->set('luminix.frontend.boot.includes_manifest', true);
    }


    public function test_if_manifest_is_generated_via_api()
    {
        $from_api = $this->json('GET', '/luminix-api/init');
        $from_api = $from_api->json();

        if (empty($from_api)) {
            $this->assertTrue(false);
        } else if (!isset($from_api['manifest']) || empty($from_api['manifest'])) {
            $this->assertTrue(false);
        }

        $manifest = $from_api['manifest'];

        if (empty($manifest)) {
            $this->assertTrue(false);
        }

        $this->assertEquals($this->expected_config['manifest'], $manifest);
    }

}