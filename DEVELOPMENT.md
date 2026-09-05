# Local Flarum scaffolding deviations

This extension began with Flarum CLI scaffolding. The following differences are
intentional and should be preserved when refreshing that infrastructure.

Use this read-only audit before updating scaffolding:

```sh
npx --yes @flarum/cli@2 audit infra --no-interaction
```

Do not run `audit infra --fix` or `infra` without reviewing its diff: Flarum
CLI overwrites generated files and does not remove obsolete ones.

| Area                     | Deviation                                                                                     | Reason                                                                                                                                                                                                                                                              |
| ------------------------ | --------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Backend workflow         | `.github/workflows/backend.yml` is local rather than the reusable backend workflow.           | There are no integration tests. The upstream job starts databases and runs setup unconditionally. Local CI runs unit tests on PHP 8.3, 8.4, and 8.5 and PHPStan on 8.5 without a database.                                                                          |
| Frontend workflow        | The reusable frontend workflow is pinned to `flarum/framework` `1.x`, not `main`.             | The `1.x` workflow uses PHP 8.3, compatible with this extension. The `main` workflow previously selected PHP 8.2 and could not install this project's dependencies. Composer installation remains necessary because TypeScript reads Flarum typings from `vendor/`. |
| Backend test scaffolding | No `tests/integration`, fixtures, setup script, or integration PHPUnit configuration is kept. | The extension has no persistence or API behavior to exercise. The former suite discovered zero tests but still required a database. Add integration infrastructure only alongside an actual integration test.                                                       |
| Just recipes             | Only `test` is exposed.                                                                       | It is the unit suite; separate setup and integration recipes would be dead commands.                                                                                                                                                                                |
| Ignore files             | Root and JS ignore files differ from CLI output.                                              | Integration temporary files are unnecessary. Built frontend assets and typings are tracked so the frontend workflow can commit them on `main`.                                                                                                                      |
| Dependabot               | Composer, npm, and Actions updates run weekly; Composer and npm major updates are ignored.    | Routine compatible updates stay automated. Major Flarum/tooling migrations are reviewed deliberately against CLI scaffolding.                                                                                                                                       |

The `extra.flarum-cli.modules` metadata keeps `backendTesting` enabled because
the extension retains Flarum's PHPUnit and PHPStan tooling. That module also
scaffolds integration-test infrastructure, which this extension intentionally
does not use.

When upgrading to Flarum 2, review each row above. In particular, update the
frontend workflow reference from `@1.x` to `@2.x` in the same change as the
Flarum core constraint, then run the CLI audit with Flarum CLI 3 and review its
output manually.

## References

- [Flarum CLI and infrastructure modules](https://github.com/flarum/cli)
- [Flarum extension testing](https://docs.flarum.org/extend/testing)
- [Flarum 1.x frontend workflow](https://raw.githubusercontent.com/flarum/framework/1.x/.github/workflows/REUSABLE_frontend.yml)
- [Flarum 2.x frontend workflow](https://raw.githubusercontent.com/flarum/framework/2.x/.github/workflows/REUSABLE_frontend.yml)
