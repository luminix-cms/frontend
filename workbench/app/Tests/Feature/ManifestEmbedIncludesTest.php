<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use Workbench\App\Tests\TestCase;

class ManifestEmbedIncludesTest extends TestCase
{
    use InteractsWithViews; 


    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('luminix.frontend.boot.method', 'embed');
        $app['config']->set('luminix.frontend.boot.includes_manifest', true);
    }


    public function test_if_manifest_is_generated_on_embed_config()
    {
        $view = $this->blade("@luminixEmbed");
        
        $view->assertSee('luminix-data::config');

        $xmlObj = simplexml_load_string($view);

        $manifest = [];

        foreach ((array) $xmlObj as $key => $value) {
            if ($key == 'div') {
                
                $value = (array) $value;
                
                foreach ($value['@attributes'] as $k => $attributes) {
                    if ($k == 'data-value') {
                        $config = json_decode($attributes, true);
                        
                        if (!isset($config['manifest']) || empty($config['manifest'])) {
                            $this->assertTrue(false);
                        } else {
                            $manifest = $config['manifest'];
                        }
                    }
                }
            }
        }

        $this->assertEquals($this->expected_config['manifest'], $manifest);
    }

}