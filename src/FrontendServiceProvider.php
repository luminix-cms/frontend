<?php

namespace Luminix\Frontend;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Luminix\Frontend\Commands\ManifestCommand;
use Luminix\Frontend\Facades\Boot;
use Luminix\Frontend\Services\JsService;

class FrontendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(JsService::class, function () {
            return new JsService();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadViewsFrom(__DIR__ . '/../views', 'luminix');

        Blade::directive('luminixEmbed', function () {
            return "<?php echo view('luminix::embed')->render(); ?>";
        });

        View::composer('luminix::embed', function () {
            /** @var JsService */
            if (config('luminix.frontend.boot.method', 'api') === 'embed') {
                $boot = Boot::get();
                $js = app(JsService::class);                
                $js->set('config', $boot);

            }
        });
    }

    public function register()
    {
        $this->commands([
            ManifestCommand::class,
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/frontend.php', 'luminix.frontend');

        $this->publishes([
            __DIR__ . '/../config/frontend.php' => config_path('luminix/frontend.php'),
        ], 'luminix-config');
    }
}
