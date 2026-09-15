# Reducers

`BootService` and `ManifestService` each expose a static reducer pipeline (`arandu/reducible`).
Register from a service provider's `boot()`; a reducer receives the value built so far and returns
its replacement.

**Neighbours:** the keys you are extending -> `boot-payload.md`, `manifest.md`.

## Shaping the whole payload — `wireConfig`

```php
use Luminix\Frontend\Services\BootService;

BootService::reducer('wireConfig', function (array $boot): array {
    $boot['app']['version'] = config('app.version');
    return $boot;
});
```

`wireConfig` is the only key `BootService` runs, and it runs after `app`, `auth` and `manifest` are
assembled — so a reducer can rewrite the manifest as well as add top-level keys.

## Shaping a model entry — `modelManifest`

```php
use Luminix\Frontend\Services\ManifestService;

// every model; the second argument is the fully-qualified class
ManifestService::reducer('modelManifest', function (array $data, string $class): array {
    return [...$data, 'searchable' => in_array(Searchable::class, class_uses_recursive($class))];
});

// App\Models\User only
ManifestService::reducer('modelUserManifest', function (array $data): array {
    return [...$data, 'has_avatar' => true];
});
```

The per-model key is `model{ClassBasename}Manifest` — the class short name as written, not the
alias, so `ToDo` gives `modelToDoManifest`. For each model `modelManifest` runs first, then the
per-model key. Neither sees `routes`; route entries have no reducer.

Arguments are padded and truncated to the closure's declared parameter count, so a `modelManifest`
reducer that only needs the data may declare one parameter and ignore the class.

## Priority and removal

```php
$unsubscribe = BootService::reducer('wireConfig', $fn, 5);   // default priority is 10
$unsubscribe();
```

Lower priority runs first; equal priorities run in registration order. The registry is a static
property that lives for the whole process — in tests call `BootService::flushReducers()` /
`ManifestService::flushReducers()` from `tearDown()`, or a reducer leaks into the next test.

A reducer must return the value it was handed. One that falls off the end passes `null` down the
chain, and in `BootService` that surfaces as a `TypeError` from the `Init` constructor, far from the
reducer that caused it.

## The `Init` event

`Luminix\Frontend\Events\Init` fires at the end of `BootService::get()` carrying the finished array
as `public array $boot`. Mutating it changes nothing — the payload is already on its way back by
value. Use the event for logging and auditing, and a reducer to shape data.
