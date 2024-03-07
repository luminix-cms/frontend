<?php

namespace Luminix\Frontend\Commands;

use Illuminate\Console\Command;
use Luminix\Frontend\Services\ManifestService;

class ManifestCommand extends Command
{
    protected $signature = 'luminix:manifest
                            {--path= : The path to the manifest file}';

    protected $description = 'Create a manifest file for the Luminix backend';


    public function handle()
    {
        if (config('luminix.frontend.boot.includes_manifest', true)) {
            $this->info('Manifest data is already included in the boot process.');
            $this->info('Set the "include_manifest" configuration in `config/luminix/frontend.php` to `false` to disable this command.');
            return 1;
        }
        $this->info('Creating manifest file...');

        $filepath = $this->option('path') ?? resource_path('js/config/manifest.json');

        /** @var ManifestService */
        $manifest = app(ManifestService::class);

        file_put_contents($filepath, json_encode($manifest->make()->get(), JSON_PRETTY_PRINT));

        $this->info('Manifest file created successfully!');

        return 0;
    }
}
