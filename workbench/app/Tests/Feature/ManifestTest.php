<?php

namespace Workbench\App\Tests\App;

use Workbench\App\Tests\TestCase;

use Illuminate\Support\Facades\Artisan;

use Luminix\Frontend\Services\ManifestService;

class ManifestTest extends TestCase
{

    public function test_if_admin_manifest_command_is_registered()
    {
        $commands = collect(Artisan::all());

        $this->assertTrue($commands->has('admin:manifest'));
    }

    public function test_execute_admin_manifest_command()
    {
        $this->artisan('admin:manifest')->assertSuccessful();
    }

    public function test_if_application_data_is_generated_via_command()
    {
        /** @var ManifestService */
        $manifest = app(ManifestService::class);
        
        $expected = array_keys($manifest->get());

        /* * */

        ob_start();

        Artisan::call('admin:manifest', [ '--makeManifest' => true ]);
        
        $output = get_object_vars(json_decode(ob_get_clean()));

        $manifest = $output['manifest'];
        $path = $output['path'];

        if (empty($manifest) || empty($path)) {
            $this->assertTrue(false);
        }
        
        $this->assertStringContainsString('js/config/manifest', $path);
        // $this->assertDirectoryExists($path);

        $assertions = [];

        foreach ($manifest as $key => $_) {
            if (in_array($key, $expected)) {
                $assertions[$key] = true;
            } else {
                $assertions[$key] = false;
            }
        }

        $this->assertEquals($assertions, array_fill_keys($expected, true));
    }

    public function test_if_application_data_is_generated_via_boot()
    {
        /** @var ManifestService */
        $manifest = app(ManifestService::class);
        
        $expected = array_keys($manifest->get());

        if (config('luminix.frontend.boot.includes_manifest', true)) {
            /** @var ManifestService */
            $manifest = app(ManifestService::class);

            $resolve = $manifest->make()->get();

            $this->assertEquals($expected, array_keys($resolve));
        }
    }

}