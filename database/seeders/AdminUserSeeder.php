<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::updateOrCreate(
            ['name' => 'admin.access'],
            ['label' => 'Acesso administrativo']
        );

        $admin = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('test123'),
            ]
        );

        $admin->permissions()->syncWithoutDetaching([$permission->id]);

        $this->command?->info("Admin user ready: {$admin->email}");
    }
}
