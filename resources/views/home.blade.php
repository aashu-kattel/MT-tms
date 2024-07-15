@extends('layouts.app')

@section('content')
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
<div class="container">
    @if(Auth::user()->role === 'admin')
        <h1>Admin Dashboard</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Online Status</th>
                    <th>Total Task Complete</th>
                    <th>Total Task In Progress</th>
                    <th>Total Task To Do</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->isOnline() ? 'Online' : 'Offline' }}</td>
                        <td>{{ $user->tasks->where('status', 'completed')->count() }}</td>
                        <td>{{ $user->tasks->where('status', 'in_progress')->count() }}</td>
                        <td>{{ $user->tasks->where('status', 'to_do')->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h1>User Dashboard</h1>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="status">Task Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="completed">Completed</option>
                    <option value="in_progress">In Progress</option>
                    <option value="to_do">To Do</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
        <ul class="list-group mt-3">
            @foreach($tasks as $task)
                <li class="list-group-item">
                    {{ $task->status }}
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control d-inline" style="width: auto;">
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
