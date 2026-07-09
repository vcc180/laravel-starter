<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('test123'),
            ]
        );

        $this->command?->info("Admin user ready: {$user->email}");
    }
}
