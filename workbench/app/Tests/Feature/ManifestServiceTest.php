<?php

namespace Workbench\App\Tests\Feature;

use Illuminate\Support\Arr;
use Luminix\Frontend\Services\ManifestService;
use Workbench\App\Models\User;
use Workbench\App\Tests\TestCase;

class ManifestServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        ManifestService::flushReducers();
        parent::tearDown();
    }

    private function manifest(bool $noAuth = false): array
    {
        return app(ManifestService::class)->make($noAuth)->get();
    }

    // -------------------------------------------------------------------------
    // Modelos
    // -------------------------------------------------------------------------

    public function test_manifest_contem_todos_os_modelos_quando_autenticado(): void
    {
        $this->actingAs(User::factory()->create());

        $manifest = $this->manifest();

        $this->assertArrayHasKey('user', $manifest['models']);
        $this->assertArrayHasKey('post', $manifest['models']);
        $this->assertArrayHasKey('tag', $manifest['models']);
    }

    public function test_manifest_exibe_apenas_modelos_publicos_para_visitante(): void
    {
        $this->simulateHttpContext();
        config(['luminix.frontend.models.public' => ['user']]);

        $manifest = $this->manifest();

        $this->assertArrayHasKey('user', $manifest['models']);
        $this->assertArrayNotHasKey('post', $manifest['models']);
        $this->assertArrayNotHasKey('tag', $manifest['models']);
    }

    public function test_manifest_oculta_modelo_excluido(): void
    {
        $this->actingAs(User::factory()->create());
        config(['luminix.frontend.models.exclude' => ['tag']]);

        $manifest = $this->manifest();

        $this->assertArrayNotHasKey('tag', $manifest['models']);
        $this->assertArrayHasKey('post', $manifest['models']);
    }

    public function test_manifest_do_modelo_tem_todos_os_campos_esperados(): void
    {
        $this->actingAs(User::factory()->create());

        $postManifest = $this->manifest()['models']['post'];

        foreach (['attributes', 'displayName', 'fillable', 'casts', 'primaryKey', 'labeledBy', 'timestamps', 'softDeletes', 'relations'] as $campo) {
            $this->assertArrayHasKey($campo, $postManifest, "Campo '{$campo}' ausente no manifest do Post");
        }
    }

    public function test_manifest_detecta_soft_deletes_corretamente(): void
    {
        $this->actingAs(User::factory()->create());

        $manifest = $this->manifest();

        $this->assertTrue($manifest['models']['post']['softDeletes']);
        $this->assertFalse($manifest['models']['tag']['softDeletes']);
    }

    public function test_manifest_detecta_relacoes_do_modelo(): void
    {
        $this->actingAs(User::factory()->create());

        $relacoes = $this->manifest()['models']['post']['relations'];

        $this->assertIsArray($relacoes);
        $this->assertArrayHasKey('tags', $relacoes);
        $this->assertEquals('BelongsToMany', $relacoes['tags']['type']);
        $this->assertEquals('tag', $relacoes['tags']['model']);
    }

    public function test_manifest_retorna_stdclass_vazio_quando_sem_modelos(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        config(['luminix.frontend.models.exclude' => ['user', 'post', 'tag']]);

        $manifest = $this->manifest();

        $this->assertInstanceOf(\stdClass::class, $manifest['models']);
    }

    // -------------------------------------------------------------------------
    // Rotas
    // -------------------------------------------------------------------------

    public function test_manifest_contem_rotas_quando_autenticado(): void
    {
        $this->actingAs(User::factory()->create());

        $routes = $this->manifest()['routes'];

        $this->assertIsArray($routes);
        $this->assertNotNull(Arr::get($routes, 'luminix.post.index'));
        $this->assertNotNull(Arr::get($routes, 'luminix.post.store'));
        $this->assertNotNull(Arr::get($routes, 'luminix.user.index'));
    }

    public function test_manifest_oculta_rotas_privadas_para_visitante(): void
    {
        $this->simulateHttpContext();
        config(['luminix.frontend.routes.public' => ['luminix.user.index']]);

        $routes = $this->manifest()['routes'];

        $this->assertNotNull(Arr::get($routes, 'luminix.user.index'));
        $this->assertNull(Arr::get($routes, 'luminix.post.index'));
    }

    public function test_manifest_oculta_rota_excluida(): void
    {
        $this->actingAs(User::factory()->create());
        config(['luminix.frontend.routes.exclude' => ['luminix.post.index']]);

        $routes = $this->manifest()['routes'];

        $this->assertNull(Arr::get($routes, 'luminix.post.index'));
        $this->assertNotNull(Arr::get($routes, 'luminix.post.store'));
    }

    public function test_rota_de_cada_modelo_tem_uri_e_metodo(): void
    {
        $this->actingAs(User::factory()->create());

        $routes = $this->manifest()['routes'];
        $indexRoute = Arr::get($routes, 'luminix.post.index');

        $this->assertIsArray($indexRoute);
        $this->assertStringContainsString('luminix-api', $indexRoute[0]);
        $this->assertContains('get', $indexRoute);
    }

    // -------------------------------------------------------------------------
    // Flag --no-auth
    // -------------------------------------------------------------------------

    public function test_flag_no_auth_gera_manifest_publico(): void
    {
        config(['luminix.frontend.models.public' => ['user']]);

        $manifest = $this->manifest(noAuth: true);

        $this->assertArrayHasKey('user', $manifest['models']);
        $this->assertArrayNotHasKey('post', $manifest['models']);
    }

    // -------------------------------------------------------------------------
    // Reducers
    // -------------------------------------------------------------------------

    public function test_reducer_modelManifest_e_aplicado_a_todos_os_modelos(): void
    {
        $this->actingAs(User::factory()->create());

        ManifestService::reducer('modelManifest', function (array $data) {
            return [...$data, 'campo_customizado' => true];
        });

        $manifest = $this->manifest();

        $this->assertTrue($manifest['models']['post']['campo_customizado']);
        $this->assertTrue($manifest['models']['tag']['campo_customizado']);
        $this->assertTrue($manifest['models']['user']['campo_customizado']);
    }

    public function test_reducer_modelManifest_recebe_classe_do_modelo(): void
    {
        $this->actingAs(User::factory()->create());

        $classesRecebidas = [];

        ManifestService::reducer('modelManifest', function (array $data, string $class) use (&$classesRecebidas) {
            $classesRecebidas[] = $class;
            return $data;
        });

        $this->manifest();

        $this->assertContains(\Workbench\App\Models\Post::class, $classesRecebidas);
        $this->assertContains(\Workbench\App\Models\Tag::class, $classesRecebidas);
    }

    public function test_reducer_especifico_e_aplicado_apenas_ao_modelo_alvo(): void
    {
        $this->actingAs(User::factory()->create());

        ManifestService::reducer('modelPostManifest', function (array $data) {
            return [...$data, 'apenas_post' => true];
        });

        $manifest = $this->manifest();

        $this->assertTrue($manifest['models']['post']['apenas_post']);
        $this->assertArrayNotHasKey('apenas_post', $manifest['models']['tag']);
        $this->assertArrayNotHasKey('apenas_post', $manifest['models']['user']);
    }

    public function test_reducers_de_modelos_diferentes_nao_interferem(): void
    {
        $this->actingAs(User::factory()->create());

        ManifestService::reducer('modelPostManifest', function (array $data) {
            return [...$data, 'flag_post' => true];
        });

        ManifestService::reducer('modelTagManifest', function (array $data) {
            return [...$data, 'flag_tag' => true];
        });

        $manifest = $this->manifest();

        $this->assertTrue($manifest['models']['post']['flag_post']);
        $this->assertArrayNotHasKey('flag_post', $manifest['models']['tag']);

        $this->assertTrue($manifest['models']['tag']['flag_tag']);
        $this->assertArrayNotHasKey('flag_tag', $manifest['models']['post']);
    }
}
