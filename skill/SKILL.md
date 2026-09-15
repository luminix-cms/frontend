---
name: luminix-frontend
description: luminix/frontend — embeds a boot payload in the HTML page so the frontend starts with no init request. The @luminixEmbed directive and the DOM it emits, mirrored validation errors, the model and route manifest and its visibility rules, boot and manifest reducers, the luminix:manifest command, config keys. Read this before crawling vendor/luminix/frontend/src.
allowed-tools: Read(.claude/skills/luminix-frontend/**), Read(vendor/luminix/frontend/**)
---

# `luminix/frontend`

Laravel package: one Blade directive ships a JSON snapshot of app state — config, authenticated
user, every model's schema and every named route — inside the page. The frontend boots from markup
it already has, with no init request.

## Where to read

| Read this | When |
|---|---|
| `references/getting-started.md` | wiring the directive into a layout, why the payload or the model list came out empty |
| `references/boot-payload.md` | what the embedded DOM and the payload hold, surfacing validation errors, `Boot::get()` in PHP |
| `references/manifest.md` | the manifest schema, which models and routes a given user sees, generating a static manifest for an SPA build |
| `references/reducers.md` | adding to the payload or to a model's manifest entry, the `Init` event |
| `references/configuration.md` | `config/luminix/frontend.php` |

## Owned elsewhere

- the `LuminixModel` trait, model discovery and the REST API the manifest routes point at ->
  `luminix/backend`
- booting the JS runtime off this payload, and the `reader()` lookup helper -> `@luminix/core`,
  `@luminix/support`
- the manifest as Dart codegen input -> `luminix_flutter`, `luminix_generator`
