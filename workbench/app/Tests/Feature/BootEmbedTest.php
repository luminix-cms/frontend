<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use Workbench\App\Tests\TestCase;

class BootEmbedTest extends TestCase
{
    use InteractsWithViews; 


    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

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

        $xmlObj = simplexml_load_string($view);

        $config = [];

        foreach ((array) $xmlObj as $key => $value) {
            if ($key == 'div') {
                
                $value = (array) $value;
                
                foreach ($value['@attributes'] as $k => $attributes) {
                    if ($k == 'data-value') {
                        $config = json_decode($attributes, true);
                    }
                }
            }
        }

        $this->assertEquals($this->expected_config, $config);
    }
    
}