<?php

namespace App\Console\Commands\Admin;

use App\Models\Permission;
use Illuminate\Console\Command;

class SeedPermissionsCommand extends Command
{
    protected $signature = 'app:seed-permissions';
    protected $description = 'Seed default permissions';

    public function handle(): void
    {
        Permission::updateOrCreate(['name' => 'admin.access'], ['label' => 'Acesso administrativo']);

        $this->components->info('Default permissions seeded.');
    }
}
