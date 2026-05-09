<?php

namespace Workbench\App\Tests\Feature;

use Workbench\App\Tests\TestCase;

class ManifestCommandTest extends TestCase
{
    private string $tempPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempPath = sys_get_temp_dir() . '/luminix-manifest-test-' . uniqid();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempPath)) {
            unlink($this->tempPath);
        }
        parent::tearDown();
    }

    public function test_comando_gera_arquivo_de_manifest(): void
    {
        $this->artisan('luminix:manifest', ['--path' => $this->tempPath])
            ->assertExitCode(0);

        $this->assertFileExists($this->tempPath);
    }

    public function test_manifest_gerado_e_json_valido(): void
    {
        $this->artisan('luminix:manifest', ['--path' => $this->tempPath]);

        $conteudo = file_get_contents($this->tempPath);
        $manifest = json_decode($conteudo, true);

        $this->assertNotNull($manifest, 'O arquivo não contém JSON válido');
        $this->assertArrayHasKey('models', $manifest);
        $this->assertArrayHasKey('routes', $manifest);
    }

    public function test_manifest_completo_contem_todos_os_modelos(): void
    {
        $this->artisan('luminix:manifest', ['--path' => $this->tempPath]);

        $manifest = json_decode(file_get_contents($this->tempPath), true);

        $this->assertArrayHasKey('user', $manifest['models']);
        $this->assertArrayHasKey('post', $manifest['models']);
        $this->assertArrayHasKey('tag', $manifest['models']);
    }

    public function test_flag_no_auth_gera_manifest_publico(): void
    {
        config(['luminix.frontend.models.public' => ['user']]);

        $this->artisan('luminix:manifest', [
            '--no-auth' => true,
            '--path' => $this->tempPath,
        ])->assertExitCode(0);

        $manifest = json_decode(file_get_contents($this->tempPath), true);

        $this->assertArrayHasKey('user', $manifest['models']);
        $this->assertArrayNotHasKey('post', $manifest['models']);
        $this->assertArrayNotHasKey('tag', $manifest['models']);
    }

    public function test_manifest_e_formatado_com_pretty_print(): void
    {
        $this->artisan('luminix:manifest', ['--path' => $this->tempPath]);

        $conteudo = file_get_contents($this->tempPath);

        $this->assertStringContainsString("\n", $conteudo);
    }

    public function test_comando_cria_diretorios_intermediarios(): void
    {
        $caminhoAninhado = sys_get_temp_dir() . '/luminix-test-' . uniqid() . '/sub/manifest.json';

        $this->artisan('luminix:manifest', ['--path' => $caminhoAninhado])
            ->assertExitCode(0);

        $this->assertFileExists($caminhoAninhado);

        unlink($caminhoAninhado);
        rmdir(dirname($caminhoAninhado));
        rmdir(dirname(dirname($caminhoAninhado)));
    }
}
