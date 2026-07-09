# Starter Laravel

Starter reutilizável para projetos Laravel 11.x. Inclui estrutura base, admin mínimo e CRUDs de exemplo.

## Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL 8.x (ou SQLite se habilitado)
- Extensões PHP: `pdo_mysql` (ou `pdo_sqlite`), `mbstring`, `openssl`

## Instalação

1. `composer install`
2. copie `.env.example` para `.env`
3. `php artisan key:generate`
4. ajuste `.env` (veja abaixo)
5. `php artisan migrate --seed`
6. `php artisan serve`

## .env (exemplo mínimo)

```env
APP_NAME="Starter Laravel"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=pt-BR
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=UTC

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starter_laravel
DB_USERNAME=root
DB_PASSWORD=

LOG_CHANNEL=stack
LOG_LEVEL=debug

SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Se usar SQLite:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/home/jarvies/starter-laravel/database/database.sqlite
```

## Estrutura

- `app/Models/BaseModel.php` — base abstrata com `$guarded=['id']` e `scopeActive`
- `app/Http/Controllers/Controller.php` — controller base com helper `ok()`
- `app/Http/Controllers/Admin/AdminController.php` — controller admin genérico
- `resources/views/layouts/app.blade.php` — layout público (Bootstrap)
- `resources/views/layouts/admin.blade.php` — layout admin (Bootstrap)
- `app/Http/Middleware/AdminMiddleware.php` — middleware de grupo admin

## CRUDs incluídos

- `/admin/examples` — controller + migration + views (template copy-paste)
- `/admin/locales` — CRUD debug com comando `php artisan app:seed-locales`

## Rotas importantes

- `/` — home pública
- `/admin/login` — login admin
- `/admin` — dashboard
- `/admin/examples`, `/admin/locales` — CRUDs exemplo

## Seeders

- `php artisan db:seed --class=AdminUserSeeder` — cria admin padrão
- `php artisan app:seed-locales` — popula locales padrão

Login admin padrão: `test@example.com` / `test123`

## Comandos úteis

```bash
php artisan route:cache
php artisan config:cache
npm run build
php artisan test
```

## Troubleshooting

- **PDO drivers vazios**: verifique `php -m | grep pdo`
- **MySQL access denied**: teste conexão com `mysql -h127.0.0.1 -P3306 -u root -p`
- **Rotas não atualizadas**: delete `bootstrap/cache/routes.php` e rode `php artisan route:cache`

## Convenções

- Não altere o core frequentemente
- Copie controllers existentes para novos módulos
- Prefixos e comportamentos específicos devem ir no controller filho
