<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web routes
|--------------------------------------------------------------------------
|
| The root path redirects to the task list so the app opens on a
| useful page. The "toggle" route is declared BEFORE the resource
| route so it is not shadowed by tasks/{task}.
|
*/

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Quick "mark done / mark pending" action on a single task.
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
    ->name('tasks.toggle');

// Full CRUD: index, create, store, show, edit, update, destroy.
Route::resource('tasks', TaskController::class);
