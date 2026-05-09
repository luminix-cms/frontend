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
        $this->info('Creating manifest file...');

        $infix = $this->option('no-auth') ? '.public' : '';

        $filepath = $this->option('path') ?? resource_path("js/config/manifest{$infix}.json");

        /** @var ManifestService */
        $manifest = app(ManifestService::class);

        $directory = dirname($filepath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($filepath, json_encode($manifest->make($this->option('no-auth'))->get(), JSON_PRETTY_PRINT));

        $this->info('Manifest file created successfully!');

        return 0;
    }
}
