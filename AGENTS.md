# TL Commerce SaaS — Agent & developer guide

Laravel 9 multi-tenant e-commerce SaaS (Stancl Tenancy). Laragon-friendly PHP app with a plugin architecture and Vue 3 storefront theme.

## Top-level layout

| Path | Purpose |
|------|---------|
| `app/` | Laravel app (HTTP kernel, providers, tenant auth middleware) |
| `Core/` | Shared admin/core modules (blog, media, tenants, system updates) |
| `config/` | Laravel & package configuration |
| `database/` | Migrations, seeders, factories |
| `plugins/` | Feature plugins (see below) |
| `public/` | Web root (`index.php`, backend assets, compiled theme assets) |
| `resources/` | Root Laravel views, lang |
| `routes/` | Central & tenant route entry points |
| `storage/` | Logs, cache, uploads (tenant paths under `storage/`) |
| `themes/` | Storefront themes (`tlcommerce` = main Vue shop, `default` = marketing) |
| `tests/` | PHPUnit tests |
| `vendor/` | Composer dependencies (gitignored) |
| `node_modules/` | Root Mix tooling (gitignored) |

## Plugins (`plugins/`)

| Plugin | Role |
|--------|------|
| `tlecommercecore` | Products, orders, cart, payments, shipping, customers |
| `saas` | Packages, subscriptions, tenant onboarding |
| `multivendor` | Seller shops & marketplace |
| `pagebuilder` / `tlcommerce-pagebuilder` | Page builder widgets |
| `carrier`, `pickuppoint` | Shipping integrations |
| `coupon`, `flashdeal`, `wallet`, `refund` | Promotions & money flows |
| `support-ticket` | Help desk |

Each plugin typically has `src/`, `views/`, `routes/`, and `plugin.json`.

## Storefront theme (`themes/tlcommerce/`)

| Path | Purpose |
|------|---------|
| `resources/js/` | Vue 3 SPA (pages, components, router) |
| `public/js/` | Webpack/Mix build output (committed in this repo) |
| `package.json` | Theme frontend dependencies |
| `webpack.mix.js` | Theme asset build |

Edit Vue sources under `resources/js/`; rebuild with npm from this theme directory.

## Common commands

```bash
# PHP (from repo root)
composer install
php artisan key:generate   # if .env has no APP_KEY
php artisan migrate
php artisan serve

# Root assets (optional admin mix)
npm install
npm run dev

# Storefront theme (primary frontend work)
cd themes/tlcommerce
npm install
npm run dev
# production: npm run prod
```

## Environment

Copy `.env.example` to `.env` if missing. Configure `DB_*`, `APP_URL`, and tenancy-related settings per your Laragon MySQL database.

## Where to change things

- **Product card / listing UI**: `themes/tlcommerce/resources/js/components/product/`
- **API / commerce logic**: `plugins/tlecommercecore/src/`
- **SaaS billing / tenants**: `plugins/saas/src/`
- **Admin UI**: `Core/Views/`, plugin `views/` folders
