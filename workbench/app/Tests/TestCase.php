<?php

namespace Workbench\App\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Workbench\Database\Seeders\DatabaseSeeder;

use function Orchestra\Testbench\artisan;

class TestCase extends TestbenchTestCase
{

    use WithWorkbench;
    # use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            \Luminix\Backend\BackendServiceProvider::class,
            \Luminix\Frontend\FrontendServiceProvider::class,
            \Workbench\App\Providers\WorkbenchServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.debug', true);
        // $app['config']->set('app.env', 'local');
        $app['config']->set('app.env', 'testing');
        $app['config']->set('app.locale', 'pt-BR');

        $app['config']->set('luminix.backend.security.middleware', ['web']);
        $app['config']->set('luminix.backend.models.include', [
            'Workbench\App\Models\User',
            'Workbench\App\Models\Post',
        ]);

        // $app['config']->set('auth', require __DIR__.'/../../config/auth.ci.php');
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations() 
    {
        artisan($this, 'migrate', ['--database' => 'testing']);

        $this->beforeApplicationDestroyed(
            fn () => artisan($this, 'migrate:rollback', ['--database' => 'testing'])
        );
    }
    

    protected function resourcePath($path = '')
    {
        $dir_path = explode('workbench', __DIR__)[0];

        return $dir_path . 'workbench/resources/' . $path;
    }
}