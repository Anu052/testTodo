<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('is_completed', false)->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|unique:tasks,description',
        ]);

        $task = Task::create([
            'description' => $request->description,
            'is_completed' => false,
        ]);

        return response()->json(['task' => $task]);
    }

    public function complete(Task $task)
    {
        $task->update(['is_completed' => true]);
        return response()->json(['message' => 'Task completed successfully']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function showAll()
    {
        $tasks = Task::all();
        return response()->json(['tasks' => $tasks]);
    }
}

