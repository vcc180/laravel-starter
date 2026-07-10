<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Acesso total']
        );

        $admin = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('test123'),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$role->id]);

        $this->command?->info("Admin user ready: {$admin->email}");
    }
}
