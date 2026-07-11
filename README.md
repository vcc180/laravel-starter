# Starter Laravel

Starter admin com RBAC e repositório modular.

## Requisitos
- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node/npm opcional (não obrigatório)

## Setup rápido

```bash
git clone https://github.com/vcc180/laravel-starter.git
cd laravel-starter
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan serve --host=0.0.0.0 --port=8001
```

Acesse:
- Admin: http://localhost:8001/admin/login
- Blog público: http://localhost:8001/blog

Credenciais de teste:
- Email: test@example.com
- Senha: test123

## Comandos úteis

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
./vendor/bin/phpunit
```

## Estrutura principal
- `app/` — código da aplicação
- `core/` — núcleo do framework modular
- `modules/blog/` — módulo de blog
- `themes/institutional/` — tema padrão
- `plugins/seo/` — plugin exemplo
