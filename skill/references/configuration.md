# Configuration — `config/luminix/frontend.php`

Publish it with `php artisan vendor:publish --tag=luminix-config`; the keys read as
`luminix.frontend.*`.

```php
'boot'   => ['includes_manifest' => true],
'models' => [
    'public'  => ['user'],
    'exclude' => [],
],
'routes' => [
    'public'  => ['login', 'logout', 'password.request', 'password.reset',
                  'password.email', 'password.confirm'],
    'exclude' => [],
],
```

- `boot.includes_manifest` — `false` drops the `manifest` key from the payload entirely, leaving the
  frontend to load a generated file instead -> `manifest.md`
- `models.public` / `routes.public` — the guest allow-list. `models.*` takes backend aliases,
  `routes.*` takes full route names, both compared exactly -> *Visibility* in `manifest.md`
- `models.exclude` / `routes.exclude` — hidden from everyone, authenticated users included

Nothing here is cached or resolved once: the values are read on every `Boot::get()`, so a
`config()->set()` from a middleware or a test takes effect on the next render.
