<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        // $this->actingAs($this->user);
    }

    public function test_user_can_get_all_tasks()
    {
        Task::factory()->count(3)->for($this->user)->create();

        $response = $this->actingAs($this->user)->getJson(route('tasks.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'title', 'description', 'status', 'user_id', 'completed_at', 'created_at', 'updated_at']]
            ]);
    }

    public function test_user_can_create_a_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => TaskStatusEnum::PENDING,
        ];

        $response = $this->actingAs($this->user)->postJson(route('tasks.store'), $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment($taskData)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'description', 'status', 'completed_at', 'created_at', 'updated_at']
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_get_a_specific_task()
    {

        $task = Task::factory()->for($this->user)->create();

        $response = $this->actingAs($this->user)->getJson(route('tasks.show', $task->id));

        $response->assertStatus(200)
            ->assertJson([
                'data' => $task->toArray()
            ]);
    }

    public function test_user_can_update_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Task Description',
            'status' => TaskStatusEnum::IN_PROGRESS,
        ];

        $response = $this->actingAs($this->user)->putJson(route('tasks.update', $task), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => TaskStatusEnum::IN_PROGRESS,
        ]);
    }

    public function test_user_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson(route('tasks.destroy', $task));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_completed_at_is_set_when_task_is_completed()
    {
        $task = Task::factory()->pending()->create([
            'status' => TaskStatusEnum::PENDING,
            'user_id' => $this->user->id,
        ]);

        $this->assertNull($task->completed_at);

        $response = $this->actingAs($this->user)->putJson(route('tasks.update', $task), [
            'status' => TaskStatusEnum::COMPLETED,
        ]);

        $response->assertStatus(200);

        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_completed_at_is_set_to_null_when_task_is_uncompleted()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatusEnum::COMPLETED,
            'completed_at' => now(),
        ]);

        $this->assertNotNull($task->completed_at);

        $response = $this->actingAs($this->user)->putJson(route('tasks.update', $task), [
            'status' => TaskStatusEnum::IN_PROGRESS,
        ]);

        $response->assertStatus(200);

        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_user_cannot_create_task_with_invalid_status()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'invalid_status',
        ];

        $response = $this->actingAs($this->user)->postJson(route('tasks.store'), $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
