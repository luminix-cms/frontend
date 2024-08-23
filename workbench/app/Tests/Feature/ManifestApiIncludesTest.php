<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews; 

class ManifestApiIncludesTest extends ManifestTest
{
    use InteractsWithViews; 

    protected function defineEnvironment($app)
    {
        $app['config']->set('luminix.frontend.boot.method', 'api');
    }


    public function test_if_manifest_is_generated_via_api()
    {
        $expected_keys = array_keys($this->expected);

        $from_api = $this->json('GET', '/luminix-api/init');
        $from_api = $from_api->json();

        if (empty($from_api)) {
            $this->assertTrue(false);
        } else if (!isset($from_api['manifest']) || empty($from_api['manifest'])) {
            $this->assertTrue(false);
        }

        $manifest = $from_api['manifest'];
        // dd($manifest);

        if (empty($manifest)) {
            $this->assertTrue(false);
        }

        $this->assertEquals($expected_keys, array_keys($manifest));

        foreach ($this->expected as $key => $value) {
            foreach ($value as $param => $val) {
                if (!isset($manifest[$key][$param])) {
                    $this->assertTrue(false);
                } else {
                    $this->assertEquals($manifest[$key][$param], $val);
                }
            }
        }
    }

}