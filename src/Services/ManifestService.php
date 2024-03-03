<?php

namespace Luminix\Frontend\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Traits\Macroable;
use Luminix\Backend\Services\ModelFinder;

class ManifestService
{

    use Macroable;

    protected ModelFinder $modelFinder;
    protected array $manifest = [
        'models' => new \stdClass,
        'routes' => new \stdClass,
    ];

    public function __construct(
        protected \Illuminate\Contracts\Foundation\Application $app
    )
    {
        $this->modelFinder = app(ModelFinder::class);    
    }

    public function make()
    {
        $modelList = $this->modelFinder->all();
        $routeList = Route::getRoutes()->getRoutesByName();
        $models = [];
        $routes = [];
        
        foreach ($modelList as $alias => $model) {
            if (in_array($alias, Config::get('luminix.frontend.exclude.models', []))) {
                continue;
            }
            if (!$this->app->runningInConsole() && !Auth::check() && !in_array($alias, Config::get('luminix.frontend.public.models', []))) {
                continue;
            }
            
            /** @var Model */
            $instance = new $model;

            $models[$alias] = [
                'fillable' => $instance->getFillable(),
                'casts' => $instance->getCasts(),
                'primaryKey' => $instance->getKeyName(),
                'labeledBy' => $instance->getLabel(),
                'timestamps' => $instance->usesTimestamps(),
                'softDeletes' => $this->modelFinder->classUses($model, \Illuminate\Database\Eloquent\SoftDeletes::class),
                'importable' => $this->modelFinder->classUses($model, \Luminix\Backend\Model\Importable::class),
                'exportable' => $this->modelFinder->classUses($model, \Luminix\Backend\Model\Exportable::class),
                'relations' => $instance->getRelationships(),
            ];

            if (static::hasMacro('modelManifest')) {
                $models[$alias] = static::modelManifest($models[$alias], $model);
            }
            if (static::hasMacro('model' . class_basename($model) . 'Manifest')) {
                $models[$alias] = static::{'model' . class_basename($model) . 'Manifest'}($models[$alias], $model);
            }
        }


        foreach ($routeList as $name => $route) {
            if (in_array($name, Config::get('luminix.frontend.exclude.routes', []) + ['luminix.init'])) {
                continue;
            }

            if (!$this->app->runningInConsole() && !Auth::check() && !in_array($name, Config::get('luminix.frontend.public.routes', []))) {
                continue;
            }

            Arr::set($routes, $name, [
                $route->uri(),
                ...collect($route->methods())
                    ->filter(fn ($method) => !in_array($method, ['HEAD', 'OPTIONS']))
                    ->map(fn ($method) => Str::lower($method))
                    ->values()
            ]);
        }

        if (!empty($models)) {
            $this->manifest['models'] = $models;
        }

        if (!empty($routes)) {
            $this->manifest['routes'] = $routes;
        }

        return $this;
    }

    public function get()
    {
        return $this->manifest;
    }
}

