<?php

namespace Workbench\App\Tests\Feature;

class ManifestFileTest extends ManifestTest
{

    protected function defineEnvironment($app)
    {
        $app['config']->set('luminix.frontend.boot.includes_manifest', false);
    }


    public function test_execute_admin_manifest_command_when_not_including_manifest()
    {
        $resolve = $this->artisan(
            'luminix:manifest', 
            [ '--path' => $this->resourcePath('js/config') ]
        );

        $resolve->assertSuccessful();
    }

    public function test_if_application_data_is_generated_via_command()
    {
        $expected_keys = array_keys($this->expected);

        $file = $this->resourcePath('js/config/manifest.json');

        $manifest = json_decode(file_get_contents($file), true);
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