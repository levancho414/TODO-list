<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_filters_tasks_by_search_query(): void
    {
        Task::create([
            'title' => 'Alpha unique title xyz',
            'description' => 'Common body',
            'status' => 'pending',
            'deadline' => null,
        ]);
        Task::create([
            'title' => 'Beta',
            'description' => 'Another unique desc abc',
            'status' => 'pending',
            'deadline' => null,
        ]);

        $response = $this->get('/tasks?q=xyz');

        $response->assertOk();
        $response->assertSee('Alpha unique title xyz');
        $response->assertDontSee('Another unique desc');
    }
}
