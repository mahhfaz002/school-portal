# Deployment Guide

Recommended host: **Laravel Cloud** (laravel.com/cloud) — first-party, free hobby tier, handles PHP, a managed database, persistent object storage, queues and scheduler with no server config.

> Note on Vercel: Vercel can run Laravel only via a community PHP runtime, has a read-only filesystem (so SQLite and local uploads don't persist), and needs an external DB + S3 storage. It works but fights the framework. Laravel Cloud is the smoother path and is what this guide targets. A short Vercel recipe is at the end.

---

## A. Deploy to Laravel Cloud

### 1. Push the project to GitHub

Laravel Cloud deploys from a Git repo. The Laravel app lives in the `school-portal/` subdirectory.

```bash
cd school-portal
git init
git add .
git commit -m "School portal"
gh repo create school-portal --private --source=. --push
```

(Or create the repo on github.com and `git remote add origin … && git push -u origin main`.)

### 2. Create the app on Laravel Cloud

1. Sign in at https://cloud.laravel.com with GitHub.
2. **Create application** → select your repo.
3. If the repo root is the parent folder, set the **project path / root directory** to `school-portal`. (If you pushed from inside `school-portal/`, leave it as the root.)
4. Choose the **PHP 8.2+** environment.

### 3. Attach a database

In the app's **Database** tab, create a managed **MySQL** (or Postgres) instance and attach it. Laravel Cloud injects `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` automatically — you do **not** set these manually.

### 4. Set environment variables

In **Environment**, set at minimum:

```
APP_NAME="Your School Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-app>.laravel.cloud

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public        # or s3 if you attach object storage
LOG_CHANNEL=stack
MAIL_MAILER=log               # swap for SMTP/Resend when ready
```

Generate `APP_KEY` from the Cloud UI (or run `php artisan key:generate --show` locally and paste it).

For uploaded files (logos, admission documents) to survive deploys, attach **Laravel Cloud object storage** (S3-compatible) and set `FILESYSTEM_DISK=s3` with the provided credentials. Then re-run `php artisan storage:link` is not needed for S3.

### 5. Build & deploy commands

Laravel Cloud auto-detects Laravel. Confirm these in **Build / Deploy settings**:

- **Build command:** `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
- **Deploy/release command:** `php artisan migrate --force && php artisan storage:link && php artisan config:cache && php artisan route:cache`

The included `Procfile` documents the same release step for hosts that read it.

### 6. First deploy & seed

Trigger the deploy. After it succeeds, run the seeders **once** from the Cloud console/terminal:

```bash
php artisan db:seed --force
```

This creates the default school settings and the demo role accounts. Then log in as `proprietor@excellence.edu` / `password`, open **School Settings**, rebrand for the school, and change the seeded passwords.

### 7. Going live checklist

- [ ] `APP_DEBUG=false`, `APP_ENV=production`
- [ ] Real `APP_KEY` set
- [ ] Database attached and `php artisan migrate --force` ran clean
- [ ] Seeded demo passwords changed (or demo users deleted)
- [ ] Object storage attached if you accept document/logo uploads
- [ ] SMTP/mail provider configured if you want real emails
- [ ] Custom domain added in the Cloud UI

---

## B. Alternative: Railway / Render

Both give a persistent disk + managed Postgres.

1. New project → deploy from GitHub, root `school-portal`.
2. Add a Postgres plugin; set `DB_*` from its connection string.
3. Build: `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
4. Release: `php artisan migrate --force && php artisan storage:link`
5. Start: `php artisan serve --host 0.0.0.0 --port $PORT` (or use the platform's PHP/nginx buildpack).

---

## C. Alternative: Vercel (works, but constrained)

1. Add the community PHP runtime via a `vercel.json` using `vercel-php`.
2. You **must** use an external database (Neon/Supabase Postgres or PlanetScale) — set `DB_*` accordingly. SQLite will not persist.
3. Use S3-compatible storage for uploads (`FILESYSTEM_DISK=s3`); the filesystem is read-only.
4. Run migrations from your machine against the external DB before/after deploy: `php artisan migrate --force`.
5. Set `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` to `database` (or external Redis), not `file`.

Because of the read-only filesystem and cold starts, prefer Laravel Cloud unless you specifically need Vercel.
