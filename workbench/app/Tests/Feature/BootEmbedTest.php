<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use stdClass;

class BootEmbedTest extends BootTestCase
{
    use InteractsWithViews; 


    protected function defineEnvironment($app)
    {
        $app['config']->set('luminix.frontend.boot.method', 'embed');
        $app['config']->set('luminix.frontend.boot.includes_manifest', true);
    }


    public function test_if_blade_has_luminix_embedded_data()
    {
        $view = $this->blade("@luminixEmbed");
        
        $view->assertSee('luminix-embed');
        $view->assertSee('luminix-data');
    }

    public function test_if_blade_has_luminix_embedded_data_with_errors()
    {
        $view = $this->withViewErrors([
            'name' => ['The name field is required.'],
        ])->blade("@luminixEmbed(name|email)");
         
        $view->assertSee('The name field is required.');
    }

    public function test_if_blade_has_luminix_embedded_data_config()
    {
        $view = $this->blade("@luminixEmbed");
        
        $view->assertSee('luminix-data::config');

        $expected_json = new stdClass();

        foreach (array_keys($this->expected) as $key) {
            if (in_array($key, [ 'manifest' ])) {
                $expected_json->$key = new stdClass;

                foreach (array_keys($this->expected[$key]) as $k) {                    
                    $expected_json->$key->$k = new stdClass;
                }
            } else {
                $expected_json->$key = $this->expected[$key];
            }
        }

        $view->assertSee(json_encode($expected_json));
    }
    
}