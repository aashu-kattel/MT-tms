<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Auth;

class TaskController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $users = User::with('tasks')->get();
            return view('home', compact('users'));
        } else {
            $tasks = Auth::user()->tasks;
            return view('home', compact('tasks'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        Auth::user()->tasks()->create([
            'status' => $request->status,
        ]);

        return redirect()->route('home');
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return redirect()->route('home');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('home');
    }
}
