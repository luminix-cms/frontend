<?php

namespace Luminix\Frontend;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Luminix\Frontend\Facades\Boot;
use Luminix\Frontend\Services\JsService;
use Luminix\Frontend\Services\ManifestService;

class FrontendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(JsService::class, function () {
            return new JsService();
        });

        $this->loadViewsFrom(__DIR__ . '/../views', 'luminix');

        Blade::directive('luminixEmbed', function () {
            return "<?php echo view('luminix::embed')->render(); ?>";
        });

        View::composer('luminix::embed', function () {
            /** @var JsService */
            if (config('luminix.frontend.boot.method', 'api') === 'embed') {
                $boot = Boot::get();
                $js = app(JsService::class);
                foreach ($boot as $key => $value) {
                    $js->set($key, $value);
                }
            }
        });
    }

    public function register()
    {
        
    }
}
