<?php

namespace Workbench\App\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Config\Repository; 

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

use Workbench\Database\Seeders\DatabaseSeeder;

use function Orchestra\Testbench\artisan;

class TestCase extends TestbenchTestCase
{
    use WithWorkbench;
    # use RefreshDatabase;

    protected $expected_models = [
        'Workbench\App\Models\User',
        'Workbench\App\Models\Post',
    ];

    protected $expected_config = [
        'app' => [
            'name' => 'Laravel',
            'env' => 'testing',
            'debug' => true, 
            'url' => 'http://localhost', 
            'locale' => 'pt-BR',
            'fallback_locale' => 'en',
        ], 
        'auth' => [
            'user' => null, 
            'csrf' => null
        ], 
        'manifest' => [
            'models' => [
                'user' => [
                    'attributes' => [
                        [
                            'name' => 'id',
                            'phpType' => 'int',
                            'type' => 'integer', // 'bigint unsigned',
                            'increments' => true,
                            'nullable' => false,
                            'default' => null,
                            'primary' => true,
                            'unique' => true,
                            'fillable' => false,
                            'appended' => null,
                            'cast' => 'int',
                            'virtual' => false,
                            'hidden' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => true,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'name',
                            'nullable' => false,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'varchar', // 'varchar(255)',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => true,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'email',
                            'nullable' => false,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'varchar', // 'varchar(255)',
                            'unique' => true,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => 'datetime',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'email_verified_at',
                            'nullable' => true,
                            'phpType' => "\\Carbon\\CarbonInterface",
                            'primary' => false,
                            'type' => 'datetime', // 'timestamp',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => 'hashed',
                            'default' => null,
                            'fillable' => true,
                            'hidden' => true,
                            'increments' => false,
                            'name' => 'password',
                            'nullable' => false,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'varchar', // 'varchar(255)',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => false,
                            'hidden' => true,
                            'increments' => false,
                            'name' => 'remember_token',
                            'nullable' => true,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'varchar', // 'varchar(100)',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => 'datetime',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'created_at',
                            'nullable' => true,
                            'phpType' => "\\Carbon\\CarbonInterface",
                            'primary' => false,
                            'type' => 'datetime', // 'timestamp',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => 'datetime',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'updated_at',
                            'nullable' => true,
                            'phpType' => "\\Carbon\\CarbonInterface",
                            'primary' => false,
                            'type' => 'datetime', // 'timestamp',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => true,
                            'cast' => 'accessor',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'age',
                            'nullable' => null,
                            'phpType' => 'int',
                            'primary' => null,
                            'type' => null,
                            'unique' => null,
                            'virtual' => true
                        ],
                    ], 
                    'casts' => [
                        'email_verified_at' => "datetime",
                        'id' => "int",
                        'password' => "hashed"
                    ],
                    'displayName' => [
                        'plural' => "Users",
                        'singular' => "User"
                    ],
                    'fillable' => ['name', 'email', 'password'],
                    'labeledBy' => "name",
                    'primaryKey' => "id",
                    'relations' => [
                        'posts' => [
                            'foreignKey' => "user_id",
                            'model' => "post",
                            'ownerKey' => null,
                            'type' => "HasMany",
                        ]
                    ],
                    'softDeletes' => false,
                    'timestamps' => true
                ],
                'post' => [
                    'attributes' => [
                        [
                            'appended' => null,
                            'cast' => 'int',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => true,
                            'name' => 'id',
                            'nullable' => false,
                            'phpType' => 'int',
                            'primary' => true,
                            'type' => 'integer', // 'bigint unsigned',
                            'unique' => true,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => true,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'title',
                            'nullable' => false,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'varchar', // 'varchar(255)',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => true,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'slug',
                            'nullable' => false,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'varchar', // 'varchar(255)',
                            'unique' => true,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => true,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'content',
                            'nullable' => false,
                            'phpType' => 'string',
                            'primary' => false,
                            'type' => 'text',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => null,
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'user_id',
                            'nullable' => false,
                            'phpType' => 'int',
                            'primary' => false,
                            'type' => 'integer', // 'bigint unsigned',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => 'datetime',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'created_at',
                            'nullable' => true,
                            'phpType' => "\\Carbon\\CarbonInterface",
                            'primary' => false,
                            'type' => 'datetime', // 'timestamp',
                            'unique' => false,
                            'virtual' => false
                        ],
                        [
                            'appended' => null,
                            'cast' => 'datetime',
                            'default' => null,
                            'fillable' => false,
                            'hidden' => false,
                            'increments' => false,
                            'name' => 'updated_at',
                            'nullable' => true,
                            'phpType' => "\\Carbon\\CarbonInterface",
                            'primary' => false,
                            'type' => 'datetime', // 'timestamp',
                            'unique' => false,
                            'virtual' => false
                        ],
                    ],
                    'casts' => [
                        'id' => "int",
                    ],
                    'displayName' => [
                        'plural' => "Posts",
                        'singular' => "Post"
                    ],
                    'fillable' => ['title', 'slug', 'content'],
                    'labeledBy' => "title",
                    'primaryKey' => "id",
                    'relations' => [
                        'user' => [
                            'foreignKey' => "user_id",
                            'model' => "user",
                            'ownerKey' => 'id',
                            'type' => "BelongsTo",
                        ]
                    ],
                    'softDeletes' => false,
                    'timestamps' => true
                ],
            ],
            'routes' => [
                'luminix' => [
                    'post' => [
                        'destroy' => [ 'luminix-api/posts/{id}', 'delete' ],
                        'destroyMany' => [ 'luminix-api/posts', 'delete' ],
                        'index' => [ 'luminix-api/posts', 'get' ],
                        'show' => [ 'luminix-api/posts/{id}', 'get' ],
                        'store' => [ 'luminix-api/posts', 'post' ],
                        'update' => [ 'luminix-api/posts/{id}', 'post' ],
                    ],
                    'user' => [
                        'destroy' => [ 'luminix-api/users/{id}', 'delete' ],
                        'destroyMany' => [ 'luminix-api/users', 'delete' ],
                        'index' => [ 'luminix-api/users', 'get' ],
                        'show' => [ 'luminix-api/users/{id}', 'get' ],
                        'store' => [ 'luminix-api/users', 'post' ],
                        'update' => [ 'luminix-api/users/{id}', 'post' ],
                    ],
                ],
            ],
        ],
    ];


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
        $app['config']->set('app.locale', 'pt-BR');

        $app['config']->set('luminix.backend.security.middleware', ['web']);
        $app['config']->set('luminix.backend.models.include', [
            'Workbench\App\Models\User',
            'Workbench\App\Models\Post',
        ]);
        $app['config']->set('luminix.backend.api.controller_overrides', [
            'Workbench\App\Models\Post' => 'Workbench\App\Http\Controllers\PostController',
        ]);
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
    

    /**
     * Get the path to the workbench resources directory.
     */
    protected function resourcePath($path = '')
    {
        $dir_path = explode('workbench', __DIR__)[0];

        return $dir_path . 'workbench/resources/' . $path;
    }
}