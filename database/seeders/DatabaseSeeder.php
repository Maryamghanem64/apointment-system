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
        // Create Spatie roles first
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $providerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'provider']);
        $clientRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'client']);

        // Admin user
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@schedora.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Provider user
        $provider = \App\Models\User::firstOrCreate(
            ['email' => 'provider@schedora.com'],
            [
                'name' => 'Demo Provider',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $provider->assignRole('provider');

        // Client user
        $client = \App\Models\User::firstOrCreate(
            ['email' => 'client@schedora.com'],
            [
                'name' => 'Demo Client',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $client->assignRole('client');

        // Call additional seeders
$this->call([
            ReviewSeeder::class,
            ProviderWorkingHoursSeeder::class,
        ]);
    }
}
