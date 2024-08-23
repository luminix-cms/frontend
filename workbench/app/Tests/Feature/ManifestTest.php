<?php

namespace Workbench\App\Tests\Feature;

use Workbench\App\Tests\TestCase;

use Illuminate\Support\Facades\Artisan;

class ManifestTest extends TestCase
{
    
    protected $expected = [
        'models' => [
            'user' => [
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
                        'type' => 'bigint unsigned',
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
                        'name' => 'name',
                        'nullable' => false,
                        'phpType' => 'string',
                        'primary' => false,
                        'type' => 'varchar(255)',
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
                        'name' => 'email_verified_at',
                        'nullable' => true,
                        'phpType' => "\\Carbon\\CarbonInterface",
                        'primary' => false,
                        'type' => 'timestamp',
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
                        'name' => 'name',
                        'nullable' => false,
                        'phpType' => 'string',
                        'primary' => false,
                        'type' => 'varchar(255)',
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
                        'type' => 'varchar(255)',
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
                        'type' => 'varchar(100)',
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
                        'type' => 'timestamp',
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
                        'type' => 'timestamp',
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
                        'type' => 'bigint unsigned',
                        'unique' => true,
                        'virtual' => false
                    ],
                    [
                        'appended' => null,
                        'cast' => null,
                        'default' => null,
                        'fillable' => true,
                        'hidden' => false,
                        'increments' => true,
                        'name' => 'title',
                        'nullable' => false,
                        'phpType' => 'string',
                        'primary' => false,
                        'type' => 'varchar(255)',
                        'unique' => false,
                        'virtual' => false
                    ],
                    [
                        'appended' => null,
                        'cast' => null,
                        'default' => null,
                        'fillable' => true,
                        'hidden' => false,
                        'increments' => true,
                        'name' => 'slug',
                        'nullable' => false,
                        'phpType' => 'string',
                        'primary' => false,
                        'type' => 'varchar(255)',
                        'unique' => true,
                        'virtual' => false
                    ],
                    [
                        'appended' => null,
                        'cast' => null,
                        'default' => null,
                        'fillable' => true,
                        'hidden' => false,
                        'increments' => true,
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
                        'type' => 'bigint unsigned',
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
                        'type' => 'timestamp',
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
                        'type' => 'timestamp',
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
        'routes' => [],
    ];


    protected function defineEnvironment($app)
    {
        $app['config']->set('luminix.frontend.boot.includes_manifest', true);
    }


    public function test_if_admin_manifest_command_is_registered()
    {
        $commands = collect(Artisan::all());

        $this->assertTrue($commands->has('luminix:manifest'));
    }

    public function test_execute_admin_manifest_command()
    {
        $resolve = $this->artisan(
            'luminix:manifest', 
            [ '--path' => $this->resourcePath('js/config') ]
        );

        $resolve->assertFailed();
    }

}