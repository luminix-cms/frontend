# Getting started

```bash
composer require luminix/frontend
php artisan vendor:publish --tag=luminix-config   # optional — config/luminix/frontend.php
```

## Wire the directive

`@luminixEmbed()` must render **before** the script that boots the frontend: it writes the payload
into the DOM, and the bundle reads it synchronously at startup.

```blade
<body>
  @luminixEmbed()
  @vite('resources/js/app.js')
</body>
```

Read it back on the JS side:

```js
const boot = JSON.parse(
  document.getElementById('luminix-data::config').dataset.value
);
```

`@luminix/support` wraps that same lookup as `reader('config')`.

## What the manifest needs from your models

The manifest describes the models `luminix/backend` discovers — the `LuminixModel` trait plus that
package's model-discovery config. This package adds no discovery of its own, so a model the backend
never found is a model no setting here can bring back.

Silent failures, in the order worth checking:

1. **No `manifest` key at all** — `boot.includes_manifest` is `false` -> `configuration.md`.
2. **Manifest present but nearly empty** — the request is unauthenticated, so only `models.public`
   and `routes.public` came through -> *Visibility* in `manifest.md`.
3. **One model missing while authenticated** — its alias sits in `models.exclude`, or the backend
   never discovered the class.
4. **A relation missing from a model entry** — the related class is not a Luminix model, or the
   relation method takes a parameter or lacks a return type. Both are rules of `luminix/backend`.
