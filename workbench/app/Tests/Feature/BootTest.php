<?php

namespace Workbench\App\Tests\App;

use Illuminate\Support\Facades\Blade;

use Luminix\Frontend\Services\BootService;

use Workbench\App\Tests\TestCase;

class BootTest extends TestCase
{

    private $allowed_method = [
        'embed',
        'api',
    ];

    public function test_embed_boot()
    {
        $method = config('luminix.frontend.boot.method', 'api');

        if ($method == 'embed') {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(in_array($method, $this->allowed_method));
        }

        /* * */
        
        /** @var BootService */
        $boot = app(BootService::class);

        $expected = $boot->get();
        $expected_keys = array_keys($expected);

        // -- REVIEW THIS TEST -- //
        Blade::directive(
            'luminixEmbed', 
            function (string $arguments) 
                use ($expected, $expected_keys) 
                {
                    $arguments = trim($arguments, '\'"');
                    $catchables = explode('|', $arguments);
                    
                    if (empty($arguments)) {
                        $this->assertTrue(false);
                    } else {
                        $this->assertEquals($expected_keys, array_keys($catchables));

                        $assertions = [];

                        foreach ($catchables as $method => $value) {
                            if (empty($value)) {
                                $this->assertTrue(false);
                            } else if (in_array($method, $expected_keys)) {
                                $assertions[$method] = true;
                            } else {
                                $assertions[$method] = false;
                            }
                        }

                        $this->assertEquals($assertions, array_fill_keys($expected_keys, true));
                    }
                }
        );
    }

    public function test_api_boot()
    {
        $method = config('luminix.frontend.boot.method', 'api');

        if ($method == 'api') {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(in_array($method, $this->allowed_method));
        }

        /* * */
        
        /** @var BootService */
        $boot = app(BootService::class);

        $expected = $boot->get();
        $expected_keys = array_keys($expected);

        $from_api = $this->json('GET', '/luminix-api/init');
        $from_api = $from_api->json();

        if (empty($from_api)) {
            $this->assertTrue(false);
        } else {
            $this->assertEquals($expected_keys, array_keys($from_api));

            $assertions = [];

            foreach ($from_api as $method => $value) {
                if (empty($value)) {
                    $this->assertTrue(false);
                } else if (in_array($method, $expected_keys)) {
                    $assertions[$method] = true;
                } else {
                    $assertions[$method] = false;
                }
            }

            $this->assertEquals($assertions, array_fill_keys($expected_keys, true));
        }

        /* * */

        if (config('luminix.frontend.boot.includes_manifest', true)) {
            $this->assertTrue(in_array('manifest', $expected_keys));
        } else {
            $this->assertFalse(in_array('manifest', $expected_keys));
        }
    }

    public function test_if_boot_includes_manifest()
    {
        $this->assertTrue(config('luminix.frontend.boot.includes_manifest', true));
    }
    
}