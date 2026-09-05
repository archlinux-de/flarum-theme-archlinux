# Development and scaffolding

This extension targets Flarum 1.x. Its development tooling started from Flarum
CLI scaffolding, but a few parts are deliberately maintained locally. Keep this
document with those decisions so a tooling refresh does not reintroduce removed
infrastructure.

## Baseline

- `flarum/core: ^1.0` is the supported Flarum line.
- `PHP: ^8.3` is intentional. It matches the minimum required by Flarum 2, so
  retaining it does not add work to a future Flarum 2 migration.
- Composer's lockfile is intentionally ignored. CI resolves the declared PHP
  dependency constraints on every supported PHP version. npm's lockfile is
  committed because it controls the frontend build.
- Use `just install`, `just test`, `just check`, and `just check-frontend` for
  local work. `just test` runs the PHP unit suite only.

## Flarum CLI

Flarum CLI 2 is the compatible CLI line for this Flarum 1 extension. Run a
read-only scaffolding audit with:

```sh
npx --yes @flarum/cli@2 audit infra --no-interaction
```

Do not run `audit infra --fix` or `infra` without reviewing its diff. The CLI
overwrites generated files and does not remove obsolete files or configuration.
The expected audit differences are listed below; they are not regressions.

The `extra.flarum-cli.modules` metadata keeps `backendTesting` enabled because
the extension retains Flarum's PHPUnit and PHPStan tooling. That module also
scaffolds integration-test infrastructure, which this extension intentionally
does not use.

## Intentional deviations from scaffolding

| Area                     | Deviation                                                                                     | Reason                                                                                                                                                                                                                                                              |
| ------------------------ | --------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Backend workflow         | `.github/workflows/backend.yml` is local rather than the reusable backend workflow.           | There are no integration tests. The upstream job starts databases and runs setup unconditionally. Local CI runs unit tests on PHP 8.3, 8.4, and 8.5 and PHPStan on 8.5 without a database.                                                                          |
| Frontend workflow        | The reusable frontend workflow is pinned to `flarum/framework` `1.x`, not `main`.             | The `1.x` workflow uses PHP 8.3, compatible with this extension. The `main` workflow previously selected PHP 8.2 and could not install this project's dependencies. Composer installation remains necessary because TypeScript reads Flarum typings from `vendor/`. |
| Backend test scaffolding | No `tests/integration`, fixtures, setup script, or integration PHPUnit configuration is kept. | The extension has no persistence or API behavior to exercise. The former suite discovered zero tests but still required a database. Add integration infrastructure only alongside an actual integration test.                                                       |
| Just recipes             | Only `test` is exposed.                                                                       | It is the unit suite; separate setup and integration recipes would be dead commands.                                                                                                                                                                                |
| Ignore files             | Root and JS ignore files differ from CLI output.                                              | Integration temporary files are unnecessary, while generated frontend outputs remain ignored.                                                                                                                                                                       |
| Dependabot               | Composer, npm, and Actions updates run weekly; Composer and npm major updates are ignored.    | Routine compatible updates stay automated. Major Flarum/tooling migrations are reviewed deliberately against CLI scaffolding.                                                                                                                                       |

## Updating tooling

1. Run the read-only CLI audit above and inspect all reported changes.
2. Update routine Composer or npm dependencies only within the declared ranges.
   Test Composer resolution with PHP 8.3 (`php-legacy /usr/bin/composer`) before
   accepting a dependency update.
3. Preserve the local backend workflow and the absence of integration files
   unless a new test needs a booted Flarum application or database.
4. Run `just check` and `just check-frontend`, then verify the PHP and JS GitHub
   Actions runs.

## Flarum 2 migration checklist

1. Update `flarum/core` to the Flarum 2 constraint and review the official
   upgrade guide. PHP 8.3 already satisfies Flarum 2's requirement.
2. Switch the frontend workflow reference from `@1.x` to `@2.x` in the same
   change. Do not track `@main` across major Flarum lines.
3. Use Flarum CLI 3 for the audit, then review its changes without applying
   them wholesale. Update TypeScript, webpack, and other generated package
   versions together rather than independently.
4. Run the CLI's JS-import migration if its audit or the Flarum upgrade guide
   calls for it, then run all local checks and CI.
5. Reassess whether new extension behavior warrants integration or frontend
   tests; add the relevant scaffold and at least one real test together.

## References

- [Flarum CLI and infrastructure modules](https://github.com/flarum/cli)
- [Flarum extension testing](https://docs.flarum.org/extend/testing)
- [Flarum 1.x frontend workflow](https://raw.githubusercontent.com/flarum/framework/1.x/.github/workflows/REUSABLE_frontend.yml)
- [Flarum 2.x frontend workflow](https://raw.githubusercontent.com/flarum/framework/2.x/.github/workflows/REUSABLE_frontend.yml)
- [Flarum 2 upgrade guide](https://docs.flarum.org/2.x/extend/update-2_0/)
