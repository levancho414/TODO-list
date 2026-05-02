@extends('layouts.app')

@section('title', $task->title)

@section('content')
    <div class="mb-4">
        <a href="{{ route('tasks.index', request()->query()) }}" class="text-decoration-none small">&larr; Back to list</a>
        <h1 class="h3 mt-2">{{ $task->title }}</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                @if ($task->status === 'done')
                    <span class="badge bg-success">Done</span>
                @else
                    <span class="badge bg-warning text-dark">Pending</span>
                @endif
            </div>

            @if ($task->description)
                <p class="card-text">{{ $task->description }}</p>
            @else
                <p class="text-muted small">No description.</p>
            @endif

            <p class="small text-muted mb-4">
                Deadline:
                @if ($task->deadline)
                    {{ $task->deadline->format('l, F j, Y') }}
                @else
                    Not set
                @endif
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('tasks.edit', array_merge(['task' => $task], request()->query())) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('tasks.destroy', array_merge(['task' => $task], request()->query())) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
