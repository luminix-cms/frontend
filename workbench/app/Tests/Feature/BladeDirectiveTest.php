<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Workbench\App\Models\User;
use Workbench\App\Tests\TestCase;

class BladeDirectiveTest extends TestCase
{
    public function test_diretiva_renderiza_container_luminix(): void
    {
        $html = Blade::render('@luminixEmbed()');

        $this->assertStringContainsString('id="luminix-embed"', $html);
    }

    public function test_diretiva_inclui_div_de_dados_de_config(): void
    {
        $html = Blade::render('@luminixEmbed()');

        $this->assertStringContainsString('id="luminix-data::config"', $html);
        $this->assertStringContainsString('data-json="1"', $html);
        $this->assertStringContainsString('data-value=', $html);
    }

    public function test_diretiva_serializa_dados_de_boot_em_json(): void
    {
        $html = Blade::render('@luminixEmbed()');

        // O atributo data-value deve conter JSON válido com as chaves esperadas
        preg_match('/data-value="([^"]+)"/', $html, $matches);
        $this->assertNotEmpty($matches, 'Atributo data-value não encontrado');

        $dados = json_decode(html_entity_decode($matches[1]), true);
        $this->assertNotNull($dados, 'data-value não contém JSON válido');
        $this->assertArrayHasKey('app', $dados);
        $this->assertArrayHasKey('auth', $dados);
    }

    public function test_diretiva_inclui_dados_de_usuario_autenticado(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $html = Blade::render('@luminixEmbed()');

        $this->assertStringContainsString((string) $user->id, $html);
    }

    public function test_diretiva_aceita_parametros_catchable_sem_errar(): void
    {
        // @error() requer $errors — normalmente injetado pela middleware ShareErrorsFromSession
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());

        $html = Blade::render("@luminixEmbed('email|password')");

        $this->assertStringContainsString('id="luminix-embed"', $html);
    }

    public function test_diretiva_renderiza_erro_de_validacao_quando_presente(): void
    {
        $bag = new \Illuminate\Support\MessageBag(['email' => ['E-mail inválido']]);
        $viewErrors = (new \Illuminate\Support\ViewErrorBag())->put('default', $bag);

        view()->share('errors', $viewErrors);

        $html = Blade::render("@luminixEmbed('email')");

        $this->assertStringContainsString('luminix-error::email', $html);
        $this->assertStringContainsString('E-mail inválido', $html);
    }

    public function test_container_e_oculto_por_padrao(): void
    {
        $html = Blade::render('@luminixEmbed()');

        $this->assertStringContainsString('display: none', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }
}
