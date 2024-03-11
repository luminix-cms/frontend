<?php

namespace Luminix\Frontend;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Luminix\Frontend\Commands\ManifestCommand;
use Luminix\Frontend\Facades\Boot;

class FrontendServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadViewsFrom(__DIR__ . '/../views', 'luminix');

        $this->registerLuminixEmbedDirective();

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

    public function registerLuminixEmbedDirective()
    {
        Blade::directive('luminixEmbed', function (string $arguments) {
            $directive = "<?php echo view('luminix::embed')";
            if (!empty($arguments)) {
                // remove leading and trailing quotes
                $arguments = trim($arguments, '\'"');
                $catchables = explode('|', $arguments);
                $directive .= "->with('catchables', ["
                    . collect($catchables)->map(function ($catchable) {
                        return "'$catchable'";
                    })->join(', ')
                    . "])";
            }

            $directive .= "->render(); ?>";

            return $directive;
        });
    }
}
