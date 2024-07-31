<?php

namespace Luminix\Frontend\Services;

use Arandu\Reducible\Reducible;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

use Luminix\Backend\Services\ModelFinder;
use Spatie\ModelInfo\ModelInfo;

class ManifestService
{

    use Reducible;

    protected ModelFinder $modelFinder;
    protected array $manifest = [
        'models' => [],
        'routes' => [],
    ];

    public function __construct(
        protected \Illuminate\Contracts\Foundation\Application $app
    )
    {
        $this->modelFinder = app(ModelFinder::class);    
    }

    public function make($noAuth = false): self
    {
        $modelList = $this->modelFinder->all();
        $routeList = Route::getRoutes()->getRoutesByName();
        $models = [];
        $routes = [];
        
        foreach ($modelList as $alias => $model) {
            if (in_array($alias, Config::get('luminix.frontend.models.exclude', []))) {
                continue;
            }
            if ((!$this->app->runningInConsole() || $noAuth) && !Auth::check() && !in_array($alias, Config::get('luminix.frontend.models.public', []))) {
                continue;
            }
            
            /** @var Model */
            $instance = new $model;

            $models[$alias] = [
                'attributes' => ModelInfo::forModel($model)->attributes,
                'displayName' => $model::getDisplayName(),
                'fillable' => $instance->getFillable(),
                'casts' => $instance->getCasts(),
                'primaryKey' => $instance->getKeyName(),
                'labeledBy' => $instance->getLabel(),
                'timestamps' => $instance->usesTimestamps(),
                'softDeletes' => $this->modelFinder->classUses($model, \Illuminate\Database\Eloquent\SoftDeletes::class),
                'relations' => $instance->getRelationships(),
            ];

            $models[$alias] = static::modelManifest($models[$alias], $model);
            $models[$alias] = static::{'model' . class_basename($model) . 'Manifest'}($models[$alias]);

        }


        foreach ($routeList as $name => $route) {
            if (in_array($name, Config::get('luminix.frontend.routes.exclude', []) + ['luminix.init'])) {
                continue;
            }

            if ((!$this->app->runningInConsole() || $noAuth) && !Auth::check() && !in_array($name, Config::get('luminix.frontend.routes.public', []))) {
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

        $this->manifest['models'] = $models;
        $this->manifest['routes'] = $routes;

        if (empty($this->manifest['models'])) {
            $this->manifest['models'] = new \stdClass;
        }

        if (empty($this->manifest['routes'])) {
            $this->manifest['routes'] = new \stdClass;
        }

        return $this;
    }

    public function get()
    {
        return $this->manifest;
    }
}

