# Developing `luminix/frontend`

`src/`, `config/frontend.php` and `views/embed.blade.php` are the whole product; everything else
here is tests, documentation or packaging.

## Two audiences, two trees

| Tree | Written for | Language | Ships |
|---|---|---|---|
| `skill/SKILL.md` + `skill/references/` | an agent **consuming** the package in an app | English | yes |
| `AGENTS.md` (this file), `CLAUDE.md` | an agent **developing** the package | English | no |
| `README.md` | a human landing on Packagist | Portuguese | yes |

`.gitattributes` decides what ships. `git archive HEAD | tar -t` must list `skill/`, and never this
file, `CLAUDE.md`, `workbench/` or the test config.

An app that ran `vendor:publish --tag=luminix-skill` holds a copy of `skill/`, so a fix here
reaches it on that app's next publish with `--force`.

## Writing `skill/`

- update it when a change is observable from a consuming app: a payload key, a manifest field, a
  config key, a reducer name, an artisan flag. Internal refactors leave it alone
- an API described there that `src/` does not have is a bug in `skill/`
- it describes the behaviour of this commit. What an older release did belongs to the release notes
- every sentence serves the reader's current task and says something the agent could not get from a
  glance at the repository
- describe the package, not the documentation system: no prose about where the guide ships from,
  how skills are found, or what else exists in the ecosystem. Name the neighbouring package when
  the answer lives outside this one

## Working here

There is no host app. `orchestra/testbench` builds a throwaway Laravel around the package, and
`workbench/` holds the models, the migrations **and the tests** — `workbench/app/Tests/Feature`, the
only directory `phpunit.xml` looks at. The `Luminix\Frontend\Tests\` -> `tests/` entry in
`composer.json` autoload-dev is dead; that directory does not exist.

`ManifestService` uses `Spatie\ModelInfo\ModelInfo` and `Luminix\Backend\Facades\Finder`, neither
required here — both arrive through `luminix/backend`. Loosening that constraint breaks the
manifest, not just the models it describes.

Guest-visibility tests cannot simply skip `actingAs()`: `ManifestService::make()` suppresses the
guest filter whenever `runningInConsole()` is true, which it always is under PHPUnit. Use
`TestCase::simulateHttpContext()`.

```bash
composer test                                  # testbench package:test
php vendor/bin/testbench vendor:publish --tag=luminix-skill --force
```

`composer lint` is declared but unusable — `phpstan/phpstan` is not in `require-dev` and the repo
carries no config for it.

## Git

- `v1.x` is the release branch; work on `feat/`/`fix/` branches and merge into it
- every push to `v1.x` runs the 10-job matrix (Laravel 11/12/13 x PHP 8.2-8.5) and, when it is
  green, cuts a tag + GitHub Release. That single `ci.yml` is the whole pipeline
- semver comes from the commit subject: `(MAJOR)` -> major, `(MINOR)` -> minor, absence -> patch
- commit messages and branch names in português
