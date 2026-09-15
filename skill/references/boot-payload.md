# The boot payload

`@luminixEmbed()` renders a hidden container holding the payload. Nothing is cached: every render
calls `Boot::get()`, which rebuilds the manifest by introspecting every discovered model and the
whole route table — worth knowing before putting the directive in a partial that repeats.

**Neighbours:** what goes inside `manifest` -> `manifest.md` · adding your own keys ->
`reducers.md`.

## The emitted DOM

```html
<div id="luminix-embed" style="display: none" aria-hidden="true">
  <div id="luminix-data::config" data-json="1" data-value="{…}"></div>
</div>
```

The payload is HTML-escaped JSON in `data-value`; `data-json="1"` marks that value as JSON.

## Payload shape

```json
{
  "app":  { "name", "env", "debug", "url", "locale", "fallback_locale" },
  "auth": { "user": { … } | null, "csrf": "…" },
  "manifest": { "models": {…}, "routes": {…} }
}
```

- `app.url` is `url('/')`, not `config('app.url')` — it carries the scheme, host and port the
  request actually arrived on, so a dev server on a custom port is reflected correctly
- `app.env` and `app.debug` reach the browser verbatim, as does anything a reducer adds
- `auth.user` is the authenticated model serialized by `json_encode`, so `$hidden` and `$appends`
  on that model decide the fields; `null` for a guest
- `auth.csrf` is `csrf_token()` — the value to send as `X-CSRF-TOKEN` against session-cookie APIs
- `manifest` exists only while `boot.includes_manifest` is on -> `configuration.md`

## Mirroring validation errors

Pass a pipe-separated list of field names to copy Laravel's active validation errors into the
container:

```blade
@luminixEmbed('email|password')
```

Each named field that currently has an error gets its own element:

```html
<div id="luminix-error::email" data-value="The email field is required."></div>
```

No `data-json` — the value is the message string, and only the first message per field. A field
with no error emits nothing, so the frontend must read a missing element as "no error".

This form reads `$errors`, which Laravel shares only with views rendered through the `web`
middleware group. Rendering it anywhere else (an API response, `Blade::render()`) throws unless
something has already shared an error bag with the view.

## `Boot::get()` in PHP

```php
use Luminix\Frontend\Facades\Boot;

$payload = Boot::get();   // runs the reducers, then fires the Init event
```

The array is **not** the JSON the page carries. `auth.user` is an Eloquent model instance, and each
model's `attributes` is a collection of `Spatie\ModelInfo\Attributes\Attribute` objects; both
flatten only under `json_encode`. Inside a reducer reach a field as `$data['attributes'][0]->name`,
never as a nested array.
