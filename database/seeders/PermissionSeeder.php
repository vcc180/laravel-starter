<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Acesso administrativo', 'slug' => 'admin.access', 'module' => 'core', 'description' => 'Acesso à área admin'],
            ['name' => 'Ver artigos', 'slug' => 'articles.view', 'module' => 'articles', 'description' => 'Visualizar artigos'],
            ['name' => 'Criar artigos', 'slug' => 'articles.create', 'module' => 'articles', 'description' => 'Criar artigos'],
            ['name' => 'Editar artigos', 'slug' => 'articles.edit', 'module' => 'articles', 'description' => 'Editar artigos'],
            ['name' => 'Excluir artigos', 'slug' => 'articles.delete', 'module' => 'articles', 'description' => 'Excluir artigos'],
            ['name' => 'Gerenciar usuários', 'slug' => 'users.manage', 'module' => 'users', 'description' => 'Gerenciar usuários'],
            ['name' => 'Gerenciar permissões', 'slug' => 'permissions.manage', 'module' => 'acl', 'description' => 'Gerenciar permissões e perfis'],
            ['name' => 'Gerenciar perfis', 'slug' => 'roles.manage', 'module' => 'acl', 'description' => 'Gerenciar perfis'],
            ['name' => 'Gerenciar repositório', 'slug' => 'repository.manage', 'module' => 'system', 'description' => 'Instalar/remover módulos, plugins e temas'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }
    }
}
