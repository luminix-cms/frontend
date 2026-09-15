# The manifest

The model and route map the frontend runtimes build their model layer from — inlined in the payload
per request, or written once to a file for an SPA build.

**Neighbours:** adding fields to a model entry -> `reducers.md` · turning the inline copy off ->
`configuration.md`.

## Shape

```json
{
  "models": {
    "post": {
      "attributes": [{ "name": "title", "phpType": "string", "type": "varchar",
                       "nullable": false, "fillable": true, "cast": null, "hidden": false }],
      "displayName": { "singular": "Post", "plural": "Posts" },
      "fillable": ["title", "body", "user_id"],
      "casts": { "id": "int", "deleted_at": "datetime" },
      "primaryKey": "id",
      "labeledBy": "title",
      "timestamps": true,
      "softDeletes": true,
      "relations": { "tags": { "type": "BelongsToMany", "model": "tag",
                               "foreignKey": "post_id", "ownerKey": null } }
    }
  },
  "routes": { "luminix": { "post": { "index": ["luminix-api/posts", "get"] } } }
}
```

- keys under `models` are backend aliases (`Post` -> `post`), and `relations[].model` is an alias
  too, never a class name
- `labeledBy` falls back to the first `$fillable` column when the model declares no `$labeledBy`
- `relations` is `null`, not `{}`, for a model with no relation to another Luminix model
- `attributes` rows are whole `spatie/laravel-model-info` records, so they also carry `increments`,
  `default`, `primary`, `unique`, `appended` and `virtual`
- an empty `models` or `routes` serializes as `{}`, never as `[]`

`routes` is a **tree, not a flat map**: names are split on `.`, so `luminix.post.index` lands at
`routes.luminix.post.index`. Each leaf is `[uri, ...methods]` — the URI has no leading slash,
methods are lowercased, and `HEAD`/`OPTIONS` are dropped, so a route answering `GET` and `POST` is
`["contact", "get", "post"]`. Route names that are prefixes of one another (`foo` and `foo.bar`)
collide in that tree and one clobbers the other.

`luminix_generator`, the Flutter codegen, reads `primaryKey`, `fillable`, `attributes[].name`,
`attributes[].phpType`, `relations[].{type, model, foreignKey, ownerKey}` and the mere existence of
the model's key under `routes.luminix`. The remaining fields are runtime-only.

## Visibility

Two independent filters:

| Config | Applies to | Effect |
|---|---|---|
| `models.exclude` / `routes.exclude` | everyone | never serialized, authenticated or not |
| `models.public` / `routes.public` | guests only | the allow-list a guest gets; an authenticated user sees everything not excluded |

Matching is an exact comparison against the model alias (`'post'`) or the full route name
(`'luminix.post.index'`). **There is no wildcard support** — an entry `'password.*'` matches only a
route literally named that, which is why the shipped default spells out each password route.

Hiding a route here does not unregister it; the server still answers. This controls what the
frontend is told about, not who may call it — authorization belongs to `luminix/backend`.

## Static manifest for an SPA build

An SPA whose manifest never changes at runtime can bundle it as a file instead:

```bash
php artisan luminix:manifest                   # -> resources/js/config/manifest.json
php artisan luminix:manifest --no-auth         # -> resources/js/config/manifest.public.json
php artisan luminix:manifest --path=public/manifest.json
```

Then set `boot.includes_manifest` to `false`, so the inline payload stays small and the bundled file
is the only manifest source. Both halves are needed; either one alone leaves the frontend with two
manifests or none.

Without `--no-auth` the command emits the **full** manifest: nobody is authenticated on the CLI, so
the guest filter is suppressed outright and everything but `exclude` is written. `--no-auth`
re-applies that filter and produces the guest view. `--path` overrides both default names and its
directory is created if missing.
