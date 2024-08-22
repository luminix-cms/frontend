<?php

namespace Workbench\App\Console\Commands;

use Illuminate\Console\Command;

use Luminix\Frontend\Services\ManifestService;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdminManifestCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:manifest
                            {--makeManifest= : Generate temporary manifest.}
                            {--no-auth : If provided, will generate a "public" manifest file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate frontend manifest.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $make_manifest = $this->option('makeManifest') ?? false;

        if ($make_manifest) {
            $infix = $this->option('no-auth') ? '.public' : '';
            $filepath = resource_path("js/config/manifest{$infix}.json");
        
            /** @var ManifestService */
            $manifest = app(ManifestService::class);

            $resolve = [
                'manifest' => $manifest->make($this->option('no-auth'))->get(), 
                'path' => $filepath, 
            ];
            
            echo json_encode($resolve, true);
        }
        
        return Command::SUCCESS;
    }

}