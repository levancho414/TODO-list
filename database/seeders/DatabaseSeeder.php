<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Main seeder entry point.
 *
 * `php artisan db:seed` runs this class; it creates a default
 * test user (handy for any future auth extension) and then
 * delegates task sample data to TaskSeeder.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(TaskSeeder::class);
    }
}
