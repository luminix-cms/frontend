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
        $app['config']->set('luminix.backend.security.middleware', ['web']);
        $app['config']->set('luminix.backend.models.include', [
            \Workbench\App\Models\User::class,
            \Workbench\App\Models\Post::class,
            \Workbench\App\Models\Tag::class,
        ]);
    }



    /**
     * Simula contexto HTTP fazendo runningInConsole() retornar false.
     * Necessário em testes que verificam filtragem de modelos/rotas por autenticação,
     * já que PHPUnit roda em CLI e o ManifestService suprime o filtro quando em console.
     */
    protected function simulateHttpContext(): void
    {
        $reflection = new \ReflectionProperty($this->app, 'isRunningInConsole');
        $reflection->setAccessible(true);
        $reflection->setValue($this->app, false);
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
}