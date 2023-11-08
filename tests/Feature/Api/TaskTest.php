<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    public function it_can_create_a_task()
    {
        $user = User::factory()->create();
        $taskData = Task::factory()->make(['user_id' => $user->id])->toArray();

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertCreated();
        $this->assertDatabaseHas('tasks', $taskData);
    }

    /** @test */
    public function it_can_list_tasks()
    {
        $tasks = Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertOk();

        $expectedData = $task->toArray();
        $expectedData['updated_at'] = (new Carbon($task->updated_at))->toDateTimeString();
        $expectedData['created_at'] = (new Carbon($task->created_at))->toDateTimeString();
        $response->assertJson(['data'=>$expectedData]);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create();
        $updatedData = ['title' => 'Updated Task Title'];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function it_can_assign_a_task_to_a_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => null]);

        $response = $this->postJson("/api/tasks/{$task->id}/assign", ['user_id' => $user->id]);

        $response->assertOk();
        $response->assertJson(['message' => 'Task assigned successfully.']);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function it_assigns_task_to_current_user_if_no_user_id_is_provided()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => null]);

        // Simulate a logged-in user
        $this->actingAs($user);

        // Act
        $response = $this->postJson(route('tasks.assign', ['task' => $task->id]));

        // Assert
        $response->assertOk();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id,
        ]);
    }

}
