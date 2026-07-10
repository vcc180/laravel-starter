<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::updateOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'description' => 'Acesso total']);
        $editor = Role::updateOrCreate(['slug' => 'editor'], ['name' => 'Editor', 'description' => 'Edição de conteúdo']);
        $viewer = Role::updateOrCreate(['slug' => 'viewer'], ['name' => 'Visualizador', 'description' => 'Somente leitura']);

        $all = Permission::all();
        $editorPerms = $all->whereIn('slug', ['articles.view', 'articles.create', 'articles.edit']);
        $viewerPerms = $all->whereIn('slug', ['articles.view', 'admin.access']);

        $admin->permissions()->sync($all->pluck('id')->all());
        $editor->permissions()->sync($editorPerms->pluck('id')->all());
        $viewer->permissions()->sync($viewerPerms->pluck('id')->all());
    }
}
