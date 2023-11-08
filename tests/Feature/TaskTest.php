<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_task()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'pending',
            'project_id' => $project->id,
            'user_id' => $user->id
        ];

        $this->actingAs($user)->post('/tasks', $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_user_can_view_a_task()
    {
        $task = Task::factory()->create();

        $this->get('/tasks/' . $task->id)
            ->assertSee($task->title)
            ->assertSee($task->description);
    }
}
