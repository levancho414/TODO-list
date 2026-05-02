<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Task Eloquent model.
 *
 * Represents a single task row. The `status` column is a simple
 * string ("pending" or "done") and is kept as-is instead of being
 * modelled as an enum to keep the midterm scope focused.
 */
class Task extends Model
{
    /**
     * Columns the user is allowed to mass-assign through
     * Task::create() / $task->update() coming from form input.
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
    ];

    /**
     * Cast `deadline` to a Carbon date instance so views can call
     * ->format() directly without manually parsing the raw string.
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }
}
