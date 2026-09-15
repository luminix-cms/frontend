<?php

namespace Luminix\Frontend;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Luminix\Frontend\Commands\ManifestCommand;

class FrontendServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/../views', 'luminix');

        // Installs the consumer skill into the app so it triggers without /luminix. The whole
        // tree is copied, so refreshing it after a package upgrade takes `--force`. Shared tag
        // across every luminix/* package -> one `vendor:publish --tag=luminix-skill` covers all
        // of them.
        $this->publishes([
            __DIR__ . '/../skill' => base_path('.claude/skills/luminix-frontend'),
        ], 'luminix-skill');

        $this->luminixEmbed();

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

    public function luminixEmbed()
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
