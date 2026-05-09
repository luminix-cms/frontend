<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Luminix\Frontend\Events\Init;
use Luminix\Frontend\Facades\Boot;
use Luminix\Frontend\Services\BootService;
use Workbench\App\Models\User;
use Workbench\App\Tests\TestCase;

class BootServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        BootService::flushReducers();
        parent::tearDown();
    }

    public function test_boot_retorna_dados_da_aplicacao(): void
    {
        $boot = Boot::get();

        $this->assertArrayHasKey('app', $boot);
        $this->assertArrayHasKey('name', $boot['app']);
        $this->assertArrayHasKey('env', $boot['app']);
        $this->assertArrayHasKey('debug', $boot['app']);
        $this->assertArrayHasKey('url', $boot['app']);
        $this->assertArrayHasKey('locale', $boot['app']);
        $this->assertArrayHasKey('fallback_locale', $boot['app']);
    }

    public function test_boot_retorna_usuario_nulo_para_visitante(): void
    {
        $boot = Boot::get();

        $this->assertArrayHasKey('auth', $boot);
        $this->assertNull($boot['auth']['user']);
    }

    public function test_boot_retorna_usuario_autenticado(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $boot = Boot::get();

        $this->assertNotNull($boot['auth']['user']);
        $this->assertEquals($user->id, $boot['auth']['user']->id);
    }

    public function test_boot_inclui_csrf(): void
    {
        $boot = Boot::get();

        $this->assertArrayHasKey('csrf', $boot['auth']);
    }

    public function test_boot_inclui_manifest_por_padrao(): void
    {
        $boot = Boot::get();

        $this->assertArrayHasKey('manifest', $boot);
        $this->assertArrayHasKey('models', $boot['manifest']);
        $this->assertArrayHasKey('routes', $boot['manifest']);
    }

    public function test_boot_omite_manifest_quando_configurado(): void
    {
        config(['luminix.frontend.boot.includes_manifest' => false]);

        $boot = Boot::get();

        $this->assertArrayNotHasKey('manifest', $boot);
    }

    public function test_reducer_wireConfig_transforma_dados_do_boot(): void
    {
        BootService::reducer('wireConfig', function (array $boot) {
            return [...$boot, 'versao' => '1.0.0'];
        });

        $boot = Boot::get();

        $this->assertEquals('1.0.0', $boot['versao']);
    }

    public function test_reducer_wireConfig_pode_modificar_campos_existentes(): void
    {
        BootService::reducer('wireConfig', function (array $boot) {
            return [...$boot, 'app' => [...$boot['app'], 'name' => 'App Modificada']];
        });

        $boot = Boot::get();

        $this->assertEquals('App Modificada', $boot['app']['name']);
    }

    public function test_multiplos_reducers_sao_encadeados_em_ordem(): void
    {
        BootService::reducer('wireConfig', function (array $boot) {
            return [...$boot, 'ordem' => ['primeiro']];
        }, 5);

        BootService::reducer('wireConfig', function (array $boot) {
            return [...$boot, 'ordem' => [...($boot['ordem'] ?? []), 'segundo']];
        }, 10);

        $boot = Boot::get();

        $this->assertEquals(['primeiro', 'segundo'], $boot['ordem']);
    }

    public function test_evento_init_e_disparado(): void
    {
        Event::fake([Init::class]);

        Boot::get();

        Event::assertDispatched(Init::class);
    }

    public function test_evento_init_recebe_dados_do_boot(): void
    {
        $bootCapturado = null;

        Event::listen(Init::class, function (Init $event) use (&$bootCapturado) {
            $bootCapturado = $event->boot;
        });

        Boot::get();

        $this->assertNotNull($bootCapturado);
        $this->assertArrayHasKey('app', $bootCapturado);
        $this->assertArrayHasKey('auth', $bootCapturado);
    }
}
