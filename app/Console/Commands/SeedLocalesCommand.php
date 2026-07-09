<?php

namespace App\Console\Commands;

use App\Models\Locale;
use Illuminate\Console\Command;

class SeedLocalesCommand extends Command
{
    protected $signature = 'app:seed-locales';
    protected $description = 'Seed default locales without touching existing';

    public function handle()
    {
        $defaults = [
            ['locale' => 'en', 'name' => 'English', 'is_active' => true],
            ['locale' => 'pt-BR', 'name' => 'Português (Brasil)', 'is_active' => true],
        ];

        foreach ($defaults as $default) {
            $item = Locale::where('locale', $default['locale'])->first();

            if ($item) {
                $item->update($default);

                $this->components->info("Updated locale: {$default['locale']}");
            } else {
                $item = Locale::create($default);

                $this->components->info("Created locale: {$default['locale']}");
            }
        }

        $this->call('cache:clear');

        $this->components->info('Locales seeded.');
    }
}
