@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
    <div class="mb-4">
        <a href="{{ route('tasks.index', request()->query()) }}" class="text-decoration-none small">&larr; Back to list</a>
        <h1 class="h3 mt-2">Edit task</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('tasks.update', array_merge(['task' => $task], request()->query())) }}" method="post">
                @csrf
                @method('PUT')
                @include('tasks._form', ['task' => $task])

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Update task</button>
                    <a href="{{ route('tasks.index', request()->query()) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
