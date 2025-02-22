<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = TaskResource::collection(Task::all());

        return response()->json(['status' => true, 'tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Task::create($validated);

        return response()->json(['status' => true, 'message' => 'Task successfully created'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $task = Task::query()->find($id);

        if (! $task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['status' => true, 'task' => new TaskResource($task)], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $task = Task::query()->find($id);

        if (! $task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $task->update($validated);

        return response()->json(['status' => true, 'message' => 'Task successfully updated'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::query()->find($id);

        if (! $task) {
            return response()->json(['status' => false, 'message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $task->delete();

        return response()->json(['status' => true, 'message' => 'Task successfully deleted'], Response::HTTP_OK);
    }
}
