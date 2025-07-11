<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        return response()->json(TaskResource::collection($tasks));
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create([
            'user_id'     => auth()->id(),
            ...$request->validated(),
        ]);

        return response()->json(new TaskResource($task), 201);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task); // Optional policy
        return response()->json(new TaskResource($task));
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return response()->json(new TaskResource($task));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(null, 204);
    }
}
