<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use Workbench\App\Tests\TestCase;

class BootApiTest extends TestCase
{
    use InteractsWithViews; 


    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('luminix.frontend.boot.method', 'api');
        $app['config']->set('luminix.frontend.boot.includes_manifest', true);
    }


    public function test_if_blade_doesnt_has_luminix_embedded_data()
    {
        $view = $this->blade("@luminixEmbed");
         
        $view->assertSee('luminix-embed');
        $view->assertDontSee('luminix-data');
    }

    public function test_api_config_boot()
    {
        $from_api = $this->json('GET', '/luminix-api/init');
        $from_api = $from_api->json();

        if (empty($from_api)) {
            $this->assertTrue(false);
        }

        $this->assertEquals($this->expected_config, $from_api);
    }
    
}