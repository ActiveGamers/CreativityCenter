Contributing to CreativityCenter

First off, thank you for considering contributing to CreativityCenter! It's people like you that make this project better for everyone.

Please take a moment to review this document before opening an issue or submitting a pull request.

---

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [I Have a Question](#i-have-a-question)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)
- [Development Setup](#development-setup)
- [Branching Model](#branching-model)
- [Commit Conventions](#commit-conventions)
- [Pull Request Process](#pull-request-process)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [CI/CD](#cicd)
- [Security Vulnerabilities](#security-vulnerabilities)

---

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to **ActiveGamers.info@gmail.com**.

---

## I Have a Question

Before asking a question, please:

1. Search existing [Issues](../../issues) and [Discussions](../../discussions).
2. Read the [README](README.md) and this document carefully.
3. Check the official docs for [Laravel](https://laravel.com/docs), [Horizon](https://laravel.com/docs/horizon), and [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary).

If you still need help, open a new Discussion (not an Issue) with a clear title and as much context as possible.

---

## Reporting Bugs

A good bug report is one that is **reproducible**. Before submitting, please:

- Confirm the bug is not already reported.
- Test against the latest `dev` branch.
- Collect the following:
  - **PHP** and **Laravel** versions (`php -v`, `php artisan --version`).
  - Operating system and browser (if frontend-related).
  - Steps to reproduce.
  - Expected vs. actual behavior.
  - Logs from `storage/logs/laravel.log` or the output of `php artisan pail`.

Use the issue template and fill in every section. Screenshots or short screen recordings are highly appreciated.

---

## Suggesting Features

We love feature suggestions. To make the discussion productive:

1. Explain **the problem** you are trying to solve, not just the solution.
2. Describe alternatives you have considered.
3. If possible, sketch the API or user flow you have in mind.
4. Keep the scope as small as possible — one feature per discussion.

Feature requests that lack a clear use case may be closed.

---

## Development Setup

### Requirements

- PHP **8.3+** with the **`imagick`** extension, plus the ImageMagick binaries that extension
  wraps (`convert`, `identify`) and **ghostscript** for PDF input. There is no GD fallback:
  `config/media-library.php` selects `imagick`, and the Docker image installs nothing else.
  - **Docker**: nothing to do — the extension, the binaries and ghostscript are part of the `app`
    image, which also re-enables the PDF coder in ImageMagick's policy.
  - **Linux host**: `sudo apt-get install imagemagick ghostscript libmagickwand-dev`, then
    `sudo pecl install imagick`, then allow PDF input in `/etc/ImageMagick-6/policy.xml` (Debian
    denies it by default).
  - **Windows host**: install ImageMagick (Q16-HDRI, 64-bit) with *Add application directory to your
    system path*, install Ghostscript for PDF input, then enable the `php_imagick.dll` matching your
    PHP version, thread safety and compiler in `php.ini` (`extension=imagick`).
- Composer **2.x**
- Node.js **20.19+** or **22.12+** and npm
- MySQL **8.x** or PostgreSQL **15+**
- Redis **7+** (required for Horizon, queues, and cache)
- Docker & Docker Compose (optional but recommended)

### Local Installation

Fork the repository, clone your fork, add the upstream remote, then run every command **inside the
matching container** — the site runs in Docker and `AGENTS.md` §5 requires `artisan`, `composer` and
`npm` to run there. The host commands are only the fallback for when the containers are not running.

**Step 1 — Start the containers**

    docker compose up -d

**Step 2 — Install dependencies**

    docker compose exec app composer install
    docker compose exec app npm install

Host fallback (containers unavailable): `composer install` and `npm install`.

**Step 3 — Prepare the environment**

    cp .env.example .env
    docker compose exec app php artisan key:generate

Host fallback: `php artisan key:generate`.

**Step 4 — Configure `.env`**

Inside the containers, the database settings come from the `DOCKER_DB_*` variables (see the header of
`docker-compose.yml`); `DB_*` values in `.env` are overridden there and apply only to the host
fallback. The same holds for `REDIS_HOST` and `QUEUE_CONNECTION`, which the containers take from the
`redis` service. Every other setting — the app key, mail, broadcasting, the school profile — is read
from `.env` through the bind mount.

**Step 5 — Migrate and seed**

    docker compose exec app php artisan migrate --seed

Host fallback: `php artisan migrate --seed`.

**Step 6 — Link storage (required for Spatie Media Library)**

    docker compose exec app php artisan storage:link

Host fallback: `php artisan storage:link`.

**Step 7 — Build the frontend assets**

    docker compose exec app npm run build

The production build is the required final state: never leave `npm run dev` running as the last
step of the setup. Start the dev server only while you work on the frontend, then run
`npm run build` again before you finish.

**Step 8 — Horizon and the queue workers**

`docker compose up -d` of Step 1 already started the `horizon` service: the master supervisor runs
the queue workers (the media conversions, ...) and the dashboard is open at
http://localhost:8080/horizon. Watch the workers with:

    docker compose logs --tail 30 horizon

After a code change the workers keep the old code until they are told to terminate, so restart them
before you test the new code:

    docker compose exec app php artisan horizon:terminate

Host fallback (containers unavailable): `php artisan serve` for the site and `php artisan horizon`
for the queue workers.

We recommend keeping Horizon running locally so you can inspect queued jobs during development: the
dashboard shows every job — the media conversions included — as it is processed.

---

## Branching Model

This project uses a **two-branch** model:

| Branch   | Purpose                                              | Rules                                              |
|----------|------------------------------------------------------|----------------------------------------------------|
| `main`   | Production-ready code only. Deployed to production.  | **Protected.** No direct pushes. Only the maintainers' promotion PR from `dev`. |
| `dev`    | Active development and integration branch.           | **Protected.** PRs only, no direct pushes.         |

### Important: PRs are only accepted against `dev`

**Every Pull Request must target the `dev` branch.** A pull request opened against `main` is closed
without review: `main` only ever receives the promotion pull request of a release.

The promotion of changes from `dev` to `main` is handled exclusively by the maintainers, by hand,
when a release is due.

### Workflow

1. Create your feature branch **from `dev`**:

       git checkout dev
       git pull upstream dev
       git checkout -b feat/short-description

2. Do your work, commit using [Conventional Commits](#commit-conventions).

3. Push to your fork and open a Pull Request **against `dev`**.

4. After review and merge into `dev`, the maintainers promote `dev` to `main` with a pull request when
   a release is due. Nothing is promoted automatically.

### Branch Naming

Use a short, descriptive prefix:

- `feat/...` – new feature
- `fix/...` – bug fix
- `refactor/...` – code refactor without behavior change
- `docs/...` – documentation only
- `chore/...` – tooling, dependencies, CI
- `test/...` – adding or fixing tests
- `perf/...` – performance improvement

Example: `feat/media-library-thumbnails`

---

## Commit Conventions

This project uses **[Conventional Commits](https://www.conventionalcommits.org/)** so that the history
says what each change does and how large it is. Nothing derives the version from it automatically:
`Docker Release` is dispatched by hand with the version to publish, and the table below is the
convention for choosing that version.

### Format

    <type>(<scope>): <short description>

    [optional body]

    [optional footer(s)]

### Allowed types

| Type       | Meaning                                            | Suggested bump   |
|------------|----------------------------------------------------|------------------|
| `feat`     | New feature                                        | Minor            |
| `fix`      | Bug fix                                            | Patch            |
| `perf`     | Performance improvement                            | Patch            |
| `refactor` | Code change without feature or fix                 | No               |
| `docs`     | Documentation only                                 | No               |
| `style`    | Formatting, missing semicolons, etc.               | No               |
| `test`     | Adding or updating tests                           | No               |
| `chore`    | Build process, deps, tooling                       | No               |
| `ci`       | CI/CD configuration changes                        | No               |
| `build`    | Changes to build system or dependencies            | No               |
| `revert`   | Revert a previous commit                           | Patch            |

### Breaking changes

Any commit that breaks backward compatibility **must** include `BREAKING CHANGE:` in the footer (or `!` after the type). By the convention above it is a **major** bump.

Example:

    feat(api)!: change response schema for /users

    BREAKING CHANGE: `name` is now split into `first_name` and `last_name`.

### Examples

    feat(media): add thumbnail generation for uploaded images
    fix(horizon): prevent duplicate job dispatch on retry
    docs(readme): update local setup instructions
    chore(deps): bump laravel/framework to 11.30

Commits that don't follow this format may be rejected during review.

---

## Pull Request Process

1. **Sync with `dev`** before opening the PR — rebase only while your branch is still local:

       git fetch upstream
       git rebase upstream/dev

   Once the task branch has been pushed, do **not** rebase it: the rewrite would require a force
   push, which this project forbids. Merge `dev` into the branch instead:

       git fetch upstream
       git merge upstream/dev

2. **Target the `dev` branch.** PRs against `main` are closed without review.

3. **Fill in the PR template** completely:
   - What the change does and why.
   - Link to the related issue (`Closes #123`).
   - Screenshots for UI changes.
   - Any migration or `.env` changes.

4. **Keep PRs small.** A PR that changes 30 files is very hard to review. Prefer several focused PRs over one giant one.

5. **One concern per PR.** Don't mix a refactor with a feature.

6. **Ensure CI passes.** The `QA` workflow runs the test suite for every branch and every pull
   request and must be green. Formatting is not part of CI: run `vendor/bin/pint --dirty` locally
   before you push.

7. **Respond to review comments.** If you disagree, explain why politely. The goal is a better project, not winning an argument.

8. **Rebase only local history; merge `dev` into a pushed branch.** A force push is forbidden, so once the branch is public, bring `dev` in with a merge instead of a rebase.

### Merge Strategy

Only **Merge** and **Rebase** are allowed. **Squash merges are disabled.** The individual commits are
the record of what a change did, and a squashed branch would leave one commit for work that was
reviewed in several steps.

---

## Coding Standards

### PHP

- Follow **PSR-12** and the **Laravel** conventions.
- Code style is enforced by **Laravel Pint**:

      ./vendor/bin/pint

- Static analysis is enforced by **PHPStan / Larastan** at level 5+:

      ./vendor/bin/phpstan analyse

- Use **type hints** everywhere (parameters, return types, properties).
- Prefer **readonly properties** and **enums** where applicable.
- Avoid facades inside domain/service classes when constructor injection is possible.
- All new classes must be **final** unless designed for extension.

### Blade & Frontend

- Use Tailwind utility classes; avoid custom CSS unless necessary.
- Extract repeated markup into Blade components.
- All user-facing strings must be wrapped in `__('...')` for i18n.

### Database

- Every migration must be reversible (`down()` implemented).
- Never modify an existing migration that has been merged to `dev`. Create a new one instead.
- Always use **foreign key constraints** and **indexes** where appropriate.
- Avoid raw SQL unless justified in a comment.

### Horizon / Queues

- All jobs must be **idempotent**.
- Always set `$tries`, `$timeout`, and `$backoff` explicitly.
- Never dispatch a job from a job unless justified.
- Long-running tasks must implement `ShouldBeUnique` or a lock.

### Media Library

- Use dedicated **collections** with clear names (e.g., `avatars`, `covers`).
- Define conversions in the model, not on the fly.
- Always register `MediaConversion` for thumbnails.

---

## Testing

Every PR that adds or changes behavior **must** include tests.

- Unit tests: `tests/Unit`
- Feature tests: `tests/Feature`

Run the suite:

    php artisan test
    # or with coverage
    php artisan test --coverage --min=80

Guidelines:

- Use **factories** and **seeders**, never raw inserts in tests.
- Prefer `RefreshDatabase` over `DatabaseTransactions`.
- Tests must not depend on execution order.
- Test names must describe the expected behavior, not the method name.

---

## CI/CD

The repository ships four workflows in `.github/workflows`:

| Workflow | Trigger | What it does |
| --- | --- | --- |
| `QA` | every push and every pull request | installs the PHP and Node dependencies, builds the frontend assets and runs `php artisan test`. Code style is **not** part of it. |
| `OSV-Scanner` | PRs into `dev`/`main`, pushes to `main`, weekly | scans the dependencies for known vulnerabilities. |
| `Auto-assign` | a newly opened issue or pull request | assigns it to the maintainer. |
| `Docker Release` | manual (`workflow_dispatch`) | builds the application image and publishes it to GHCR. |

Formatting is a local step, not a CI check: run `vendor/bin/pint --dirty` before you push.

### Docker releases

Releases are published by hand, never by a merge:

1. Open **Actions → Docker Release → Run workflow**.
2. Pick the branch and type the version to publish, for example `v1.0.1`.
3. The run pushes `ghcr.io/<owner>/<repo>:v1.0.1` and moves `latest` onto that same image, so the
   previous release keeps only its own tag. No commit-SHA tag is produced.
4. After a successful push the registry is pruned: only the newest **8** versions stay, and the
   oldest ones are deleted.

Do not create release tags by hand; the workflow owns them.

---

## Security Vulnerabilities

If you discover a security vulnerability, **do not open a public issue**. Instead, email **ActiveGamers.info@gmail.com**. We will acknowledge your report within **48 hours** and work with you on a coordinated disclosure.

---

## Thank You

Your time and effort are what make open source possible. Whether you report a typo, fix a bug, or submit a large feature — thank you. 🙏

If anything in this document is unclear, please open a Discussion and tell us. This file is a living document and improves with your feedback..
