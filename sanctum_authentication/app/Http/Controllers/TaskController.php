<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // GET /api/tasks
    public function index()
    {
        $tasks = Task::all();
        return TaskResource::collection($tasks);
    }

    // GET /api/tasks/{task}
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    // POST /api/tasks
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed'   => 'sometimes|boolean'
        ]);

        $task = Task::create($data);

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    // PUT /api/tasks/{task}
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'completed'   => 'sometimes|boolean'
        ]);

        $task->update($data);

        return new TaskResource($task);
    }

    // DELETE /api/tasks/{task}
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
