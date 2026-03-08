<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default roles using Spatie
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
