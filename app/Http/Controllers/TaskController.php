<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');
        if (! in_array($filter, ['all', 'pending', 'done'], true)) {
            $filter = 'all';
        }

        $search = trim((string) $request->query('q', ''));
        if (strlen($search) > 255) {
            $search = substr($search, 0, 255);
        }

        $query = Task::query()->orderByRaw('deadline IS NULL')->orderBy('deadline')->latest();

        if ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'done') {
            $query->where('status', 'done');
        }

        if ($search !== '') {
            $pattern = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($pattern): void {
                $q->where('title', 'like', $pattern)
                    ->orWhere('description', 'like', $pattern);
            });
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks', 'filter', 'search'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,done',
            'deadline' => 'nullable|date',
        ]);

        Task::create($validated);

        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

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

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task deleted.');
    }

    public function toggleStatus(Request $request, Task $task): RedirectResponse
    {
        $task->update([
            'status' => $task->status === 'done' ? 'pending' : 'done',
        ]);

        return redirect()
            ->route('tasks.index', $this->listQueryFromRequest($request))
            ->with('success', 'Task status updated.');
    }

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
