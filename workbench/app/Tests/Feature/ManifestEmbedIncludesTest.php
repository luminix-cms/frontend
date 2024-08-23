<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

use stdClass;

class ManifestEmbedIncludesTest extends ManifestTest
{
    use InteractsWithViews; 

    protected function defineEnvironment($app)
    {
        $app['config']->set('luminix.frontend.boot.method', 'embed');
    }


    /* * REVIEW THIS TEST * */
    public function test_if_manifest_is_generated_on_embed_content()
    {
        $view = $this->blade("@luminixEmbed");
        
        $view->assertSee('luminix-data::config');

        $expected_json = new stdClass();

        foreach (array_keys($this->expected) as $key) {
            $expected_json->$key = new stdClass;
        }

        $view->assertSee(json_encode($expected_json));
    }

}