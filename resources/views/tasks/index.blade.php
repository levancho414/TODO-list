@extends('layouts.app')

@section('title', 'All tasks')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-0">Tasks</h1>
            <p class="text-muted small mb-0">Create, filter, search, and manage your work.</p>
        </div>
        <a href="{{ route('tasks.create', request()->query()) }}" class="btn btn-primary">Add task</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form action="{{ route('tasks.index') }}" method="get" class="row g-2 g-md-3 align-items-end mb-3">
                @if ($filter !== 'all')
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <div class="col-12 col-md">
                    <label for="q" class="form-label small text-muted mb-1">Search</label>
                    <input type="search" name="q" id="q" class="form-control" value="{{ $search }}"
                           placeholder="Search title or description…" maxlength="255" autocomplete="off">
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if ($search !== '')
                        <a href="{{ route('tasks.index', array_filter(['filter' => $filter !== 'all' ? $filter : null])) }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>
            <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top border-secondary border-opacity-50">
                <span class="text-muted small me-1">Show:</span>
                @foreach (['all' => 'All', 'pending' => 'Active (pending)', 'done' => 'Completed'] as $key => $label)
                    @php
                        $params = [];
                        if ($search !== '') {
                            $params['q'] = $search;
                        }
                        if ($key !== 'all') {
                            $params['filter'] = $key;
                        }
                    @endphp
                    <a href="{{ route('tasks.index', $params) }}"
                       class="btn btn-sm {{ $filter === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if ($tasks->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <p class="mb-2">No tasks match this filter{{ $search !== '' ? ' or search' : '' }}.</p>
                <a href="{{ route('tasks.create', request()->query()) }}" class="btn btn-outline-primary btn-sm">Create a task</a>
            </div>
        </div>
    @else
        <div class="list-group shadow-sm">
            @foreach ($tasks as $task)
                <div class="list-group-item list-group-item-action d-flex flex-column flex-lg-row gap-3 py-3 align-items-lg-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="{{ route('tasks.show', array_merge(['task' => $task], request()->query())) }}" class="fw-semibold text-decoration-none link-task-title">
                                {{ $task->title }}
                            </a>
                            @if ($task->status === 'done')
                                <span class="badge bg-success">Done</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </div>
                        @if ($task->description)
                            <p class="small text-muted mb-1 mt-1">{{ \Illuminate\Support\Str::limit($task->description, 120) }}</p>
                        @endif
                        <div class="small text-muted">
                            Deadline:
                            @if ($task->deadline)
                                {{ $task->deadline->format('M j, Y') }}
                            @else
                                Not set
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <form action="{{ route('tasks.toggle', array_merge(['task' => $task], request()->query())) }}" method="post" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ $task->status === 'done' ? 'Mark pending' : 'Mark done' }}
                            </button>
                        </form>
                        <a href="{{ route('tasks.edit', array_merge(['task' => $task], request()->query())) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('tasks.destroy', array_merge(['task' => $task], request()->query())) }}" method="post" class="d-inline"
                              onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
