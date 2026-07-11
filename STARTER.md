# Starter Laravel

Starter reutilizável para projetos Laravel 11.x. Inclui core modular, admin mínimo, RBAC/ACL, repository interno de módulos/plugins/themes e CRUDs de exemplo.

## Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL 8.x (ou SQLite para testes)
- Extensões PHP: `pdo_mysql` (ou `pdo_sqlite`), `mbstring`, `openssl`

## Instalação

1. `composer install`
2. copie `.env.example` para `.env`
3. `php artisan key:generate`
4. ajuste `.env`
5. `php artisan migrate --seed`
6. `php artisan serve`

Login admin padrão: `test@example.com` / `test123`

## Core modular

- `core/` com Registry, Manifest, Discovery, DependencyResolver, HookManager, CoreServiceProvider
- gestão por tipo:
  - `modules/` — módulos instaláveis com migrations, rotas, views e manifest
  - `plugins/` — plugins
  - `themes/` — temas
- instalação/remoção via `RepositoryController` em `/admin/repository`
- métricas principais em `modules/{plugin,theme}` e hooks para auditoria

## RBAC/ACL

- Roles, permissions, seeders
- Middlewares: `admin`, `permission`
- Todos os CRUDs admin são protegidos por permissões nomeadas

## Blog module

- instalável via repo
- rotas públicas: `/blog`, `/{post}`
- CRUD admin: `/admin/blog`, `/admin/categories`, `/admin/tags`
- Actions e hooks separados: `blog.post.*`, `blog.category.*`, `blog.tag.*`

## Estrutura

- `app/Http/Controllers/Admin/Controller.php` — controller admin base
- `app/Http/Controllers/Public/Controller.php` — controller público base
- `app/Http/Middleware/AdminMiddleware.php` — middleware admin
- `app/Http/Middleware/CheckPermission.php` — middleware de permissão
- `app/Models/BaseModel.php` — base abstrata com `$guarded=['id']`
- `app/Actions/` — actions reutilizáveis

## CRUDs incluídos

- `/admin/blog` — module instalável com CRUD + actions + hooks
- `/admin/examples` — controller + migration + views
- `/admin/articles` — CRUD exemplo Articles
- `/admin/locales` — CRUD de locales
- `/admin/permissions` — CRUD de permissões ACL

## Rotas importantes

- `/` — home pública
- `/admin/login` — login admin
- `/admin` — dashboard
- `/admin/repository` — instalar/desinstalar módulos/plugins/themes
- `/admin/examples`, `/admin/articles`, `/admin/locales`, `/admin/permissions` — CRUDs exemplo

## Seeders

- `php artisan db:seed --class=PermissionSeeder`
- `php artisan db:seed --class=AdminUserSeeder`
- `php artisan db:seed`

## .env.testing

```env
APP_ENV=testing
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=4
CACHE_STORE=array
MAIL_MAILER=array
PULSE_ENABLED=false
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
TELESCOPE_ENABLED=false
```

## Comandos úteis

```bash
php artisan route:cache
php artisan config:cache
npm run build
php artisan test
```

## Convenções

- Manter controllers finos
- Preferir Actions e hooks em vez de lógica direta
- Dar preferência a Services/Actions para lógica compartilhada

## Troubleshooting

- **PDO drivers vazios**: `php -m | grep pdo`
- **MySQL access denied**: `mysql -h127.0.0.1 -P3306 -u root -p`
- **Rotas não atualizadas**: delete cache `php artisan optimize:clear`
