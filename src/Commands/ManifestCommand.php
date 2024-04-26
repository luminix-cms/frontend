<?php

namespace Luminix\Frontend\Commands;

use Illuminate\Console\Command;
use Luminix\Frontend\Services\ManifestService;

class ManifestCommand extends Command
{
    protected $signature = 'luminix:manifest
                            {--path= : The path to the manifest file}
                            {--no-auth : If provided, will generate a "public" manifest file}';

    protected $description = 'Create a manifest file for the Luminix backend';


    public function handle()
    {
        if (config('luminix.frontend.boot.includes_manifest', true)) {
            $this->error('Manifest data is already included in the boot process.');
            $this->error('Set the "include_manifest" configuration in `config/luminix/frontend.php` to `false` to enable this command.');
            return 1;
        }
        $this->info('Creating manifest file...');

        $infix = $this->option('no-auth') ? '.public' : '';

        $filepath = $this->option('path') ?? resource_path("js/config/manifest{$infix}.json");

        /** @var ManifestService */
        $manifest = app(ManifestService::class);

        if (!file_exists(resource_path('js/config'))) {
            mkdir(resource_path('js/config'));
        }

        file_put_contents($filepath, json_encode($manifest->make($this->option('no-auth'))->get(), JSON_PRETTY_PRINT));

        $this->info('Manifest file created successfully!');

        return 0;
    }
}
