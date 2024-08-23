<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews; 

class BootApiTest extends BootTestCase
{
    use InteractsWithViews; 


    protected function defineEnvironment($app)
    {
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
        $expected_keys = array_keys($this->expected);

        $from_api = $this->json('GET', '/luminix-api/init');
        $from_api = $from_api->json();

        if (empty($from_api)) {
            $this->assertTrue(false);
        }
        
        $this->assertEquals($expected_keys, array_keys($from_api));

        // dd([
        //     'from_api' => $from_api, 
        //     'expected' => $this->expected
        // ]);

        foreach ($this->expected as $key => $value) {
            foreach ($value as $param => $val) {
                if (!isset($from_api[$key][$param])) {
                    $this->assertTrue(false);
                } else {
                    $this->assertEquals($from_api[$key][$param], $val);
                }
            }
        }
    }
    
}