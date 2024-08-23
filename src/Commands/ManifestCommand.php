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

        $folder_path = $this->option('path') ?? resource_path("js/config");

        /** @var ManifestService */
        $manifest = app(ManifestService::class);

        if (!file_exists($folder_path)) {
            mkdir($folder_path);
        }

        $filepath = $folder_path . "/manifest{$infix}.json";

        file_put_contents($filepath, json_encode($manifest->make($this->option('no-auth'))->get(), JSON_PRETTY_PRINT));

        $this->info('Manifest file created successfully!');

        return 0;
    }
}
