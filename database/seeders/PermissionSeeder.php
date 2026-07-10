<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Acesso administrativo', 'slug' => 'admin', 'module' => 'core', 'description' => 'Acesso à área admin'],
            ['name' => 'Gerenciar exemplos', 'slug' => 'example', 'module' => 'examples', 'description' => 'Acesso completo a exemplos'],
            ['name' => 'Gerenciar artigos', 'slug' => 'article', 'module' => 'articles', 'description' => 'Acesso completo a artigos'],
            ['name' => 'Gerenciar locales', 'slug' => 'locale', 'module' => 'locales', 'description' => 'Acesso completo a locales'],
            ['name' => 'Gerenciar permissões', 'slug' => 'permission', 'module' => 'acl', 'description' => 'Gerenciar permissões'],
            ['name' => 'Gerenciar perfis', 'slug' => 'role', 'module' => 'acl', 'description' => 'Gerenciar perfis/roles'],
            ['name' => 'Gerenciar usuários', 'slug' => 'user', 'module' => 'users', 'description' => 'Gerenciar usuários'],
            ['name' => 'Gerenciar repositório', 'slug' => 'repository', 'module' => 'system', 'description' => 'Instalar/remover módulos, plugins e temas'],
            ['name' => 'Gerenciar blog', 'slug' => 'blog', 'module' => 'blog', 'description' => 'Acesso completo ao blog'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }
    }
}
