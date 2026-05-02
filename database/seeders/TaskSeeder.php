<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

/**
 * TaskSeeder
 *
 * Inserts a small, realistic demo dataset so the app isn't empty
 * the first time it is opened. Safe to re-run: it clears the
 * tasks table before inserting so rows never pile up on repeat runs.
 */
class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::query()->delete();

        Task::create([
            'title' => 'Prepare Laravel midterm demo',
            'description' => 'Implement MVC, migrations, CRUD, filters, and Blade layout with Bootstrap.',
            'status' => 'pending',
            'deadline' => now()->addDays(3)->toDateString(),
        ]);

        Task::create([
            'title' => 'Review routes and controllers',
            'description' => 'Confirm resource routes and validation messages display correctly.',
            'status' => 'pending',
            'deadline' => now()->addWeek()->toDateString(),
        ]);

        Task::create([
            'title' => 'Submit assignment',
            'description' => 'Zip project or push to repository before the deadline.',
            'status' => 'done',
            'deadline' => now()->addDays(14)->toDateString(),
        ]);
    }
}
