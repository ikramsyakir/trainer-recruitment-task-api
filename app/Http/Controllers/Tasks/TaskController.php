<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = TaskResource::collection($request->user()->tasks);

        return response()->json(['status' => true, 'tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Task::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
        ]);

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

        if ($task->user_id != auth()->user()->id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to view this task'],
                Response::HTTP_UNAUTHORIZED);
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

        if ($task->user_id != $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to update this task'],
                Response::HTTP_UNAUTHORIZED);
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

        if ($task->user_id != auth()->user()->id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to delete this task'],
                Response::HTTP_UNAUTHORIZED);
        }

        $task->delete();

        return response()->json(['status' => true, 'message' => 'Task successfully deleted'], Response::HTTP_OK);
    }
}
