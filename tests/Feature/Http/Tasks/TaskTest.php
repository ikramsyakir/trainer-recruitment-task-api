<?php

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('returns all tasks', function () {
    // Create multiple tasks
    $tasks = Task::factory()->count(3)->create();

    // Make request to index route
    $response = $this->getJson(route('tasks.index'));

    // Assertions
    expect($response->status())->toBe(200)
        ->and($response->json())->toHaveKey('status', true)
        ->and($response->json())->toHaveKey('tasks');

    // Ensure response structure matches TaskResource
    $expectedTasks = TaskResource::collection($tasks)->response()->getData(true);
    expect($response->json('tasks'))->toMatchArray($expectedTasks['data']);
});

it('creates a new task', function () {
    // Define task data
    $taskData = [
        'title' => 'Test Task',
        'description' => 'This is a test task',
        'status' => Task::PENDING,
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
    ];

    // Make request to store route
    $response = $this->postJson(route('tasks.store'), $taskData);

    // Assertions
    expect($response->status())->toBe(201)
        ->and($response->json())->toMatchArray([
            'status' => true,
            'message' => 'Task successfully created',
        ])
        ->and(Task::where('title', 'Test Task')->exists())->toBeTrue();
});

it('returns task data when found', function () {
    $task = Task::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->getJson(route('tasks.show', $task->id));
    $selectedTask = new TaskResource($task);

    expect($response->status())->toBe(200)
        ->and($response->json('status'))->toBeTrue()
        ->and($response->json('task'))->toMatchArray($selectedTask->response()->getData(true)['data']);
});

it('returns not found when task does not exist', function () {
    $response = $this->getJson(route('tasks.show', 'non-existent-id'));

    expect($response->status())->toBe(404)
        ->and($response->json('status'))->toBeFalse()
        ->and($response->json('message'))->toBe('Task not found');
});

it('returns unauthorized if the task belongs to another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task = Task::factory()->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user1)->getJson(route('tasks.show', $task->id));

    expect($response->status())->toBe(401)
        ->and($response->json('status'))->toBeFalse()
        ->and($response->json('message'))->toBe('Unauthorized to view this task');
});

it('updates an existing task', function () {
    $task = Task::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $updatedData = Task::factory()->make()->toArray();

    $response = $this->putJson(route('tasks.update', $task->id), $updatedData);

    expect($response->status())->toBe(200)
        ->and($response->json('status'))->toBeTrue()
        ->and($response->json('message'))->toBe('Task successfully updated');
});

it('returns not found when updating a non-existent task', function () {
    $response = $this->putJson(route('tasks.update', 'non-existent-id'), [
        'title' => 'Test title',
        'description' => 'Test description',
        'status' => Task::PENDING,
    ]);

    expect($response->status())->toBe(404)
        ->and($response->json('status'))->toBeFalse()
        ->and($response->json('message'))->toBe('Task not found');
});

it('returns unauthorized if the user tries to update another user\'s task', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task = Task::factory()->create(['user_id' => $user2->id]); // Task belongs to user2

    $response = $this->actingAs($user1)->putJson(route('tasks.update', $task->id), [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'status' => 'in_progress',
    ]);

    expect($response->status())->toBe(401)
        ->and($response->json('status'))->toBeFalse()
        ->and($response->json('message'))->toBe('Unauthorized to update this task');
});

it('deletes an existing task', function () {
    $task = Task::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->deleteJson(route('tasks.destroy', $task->id));

    expect($response->status())->toBe(200)
        ->and($response->json('status'))->toBeTrue()
        ->and($response->json('message'))->toBe('Task successfully deleted')
        ->and(Task::find($task->id))->toBeNull();
});

it('returns not found when deleting a non-existent task', function () {
    $response = $this->deleteJson(route('tasks.destroy', 'non-existent-id'));

    expect($response->status())->toBe(404)
        ->and($response->json('status'))->toBeFalse()
        ->and($response->json('message'))->toBe('Task not found');
});

it('returns unauthorized if the user tries to delete another user\'s task', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task = Task::factory()->create(['user_id' => $user2->id]); // Task belongs to user2

    $response = $this->actingAs($user1)->deleteJson(route('tasks.destroy', $task->id));

    expect($response->status())->toBe(401)
        ->and($response->json('status'))->toBeFalse()
        ->and($response->json('message'))->toBe('Unauthorized to delete this task');
});
