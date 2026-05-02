<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * TaskController
 *
 * Handles the full CRUD lifecycle for tasks plus a small "toggle
 * status" action. It also supports a filter (all/pending/done) and
 * a free-text search over title and description, both passed as
 * query-string parameters on the index route.
 */
class TaskController extends Controller
{
    /**
     * Display a list of tasks, optionally narrowed by filter + search.
     *
     * Query params:
     *   - filter: all | pending | done (defaults to "all")
     *   - q:      free-text search across title/description
     */
    public function index(Request $request): View
    {
        // Normalise the filter value so unexpected input falls back to "all".
        $filter = $request->query('filter', 'all');
        if (! in_array($filter, ['all', 'pending', 'done'], true)) {
            $filter = 'all';
        }

        // Trim and length-limit the search string to avoid absurdly long input.
        $search = trim((string) $request->query('q', ''));
        if (strlen($search) > 255) {
            $search = substr($search, 0, 255);
        }

        // Base query: tasks without a deadline sink to the bottom, then by
        // nearest deadline, then newest-created as a final tiebreaker.
        $query = Task::query()->orderByRaw('deadline IS NULL')->orderBy('deadline')->latest();

        if ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'done') {
            $query->where('status', 'done');
        }

        if ($search !== '') {
            // Escape LIKE wildcards in the user's input so "50%" searches literally.
            $pattern = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($pattern): void {
                $q->where('title', 'like', $pattern)
                    ->orWhere('description', 'like', $pattern);
            });
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks', 'filter', 'search'));
    }

    /**
     * Show the "create task" form.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Validate the submitted form and persist a new task.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,done',
            'deadline' => 'nullable|date',
        ]);

        Task::create($validated);

        // Redirect back to the list, preserving the user's current filter/search.
        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display a single task's detail page.
     *
     * Uses route-model binding: Laravel resolves {task} to a Task instance
     * or automatically returns 404 if the id is missing.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the "edit task" form pre-filled with the task's values.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Validate input and update an existing task.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,done',
            'deadline' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Permanently delete the task.
     */
    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task deleted.');
    }

    /**
     * Flip a task between "pending" and "done" in one click.
     *
     * Using PATCH here (instead of a full update form) keeps the "mark done"
     * button on the list page simple and avoids re-posting the whole record.
     */
    public function toggleStatus(Request $request, Task $task): RedirectResponse
    {
        $task->update([
            'status' => $task->status === 'done' ? 'pending' : 'done',
        ]);

        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task status updated.');
    }

    /**
     * Rebuild the current list-page query string (filter + search)
     * from an incoming request so every redirect back to the index
     * keeps the user in the same view they started from.
     */
    private function listQueryFromRequest(Request $request): array
    {
        $out = [];

        $f = $request->query('filter', 'all');
        if (in_array($f, ['pending', 'done'], true)) {
            $out['filter'] = $f;
        }

        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $out['q'] = strlen($q) > 255 ? substr($q, 0, 255) : $q;
        }

        return $out;
    }
}
