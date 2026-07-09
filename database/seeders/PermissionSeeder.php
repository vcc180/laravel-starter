<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::updateOrCreate(
            ['name' => 'admin.access'],
            ['label' => 'Acesso administrativo']
        );
    }
}
