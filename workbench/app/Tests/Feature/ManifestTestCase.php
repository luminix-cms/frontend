<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Support\Facades\Artisan;

use Workbench\App\Tests\TestCase;

class ManifestTestCase extends TestCase
{

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('luminix.frontend.boot.includes_manifest', true);
    }



    public function test_if_admin_manifest_command_is_registered()
    {
        $commands = collect(Artisan::all());

        $this->assertTrue($commands->has('luminix:manifest'));
    }

    public function test_execute_admin_manifest_command_when_including_manifest()
    {
        $resolve = $this->artisan(
            'luminix:manifest', 
            [ '--path' => $this->resourcePath('js/config') ]
        );

        $resolve->assertFailed();
    }

}