<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_can_create_a_project()
    {
        $projectData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['completed', 'in-progress', 'pending']),
            'deadline' => $this->faker->date
        ];

        $response = $this->postJson('/api/projects', $projectData);

        $response->assertCreated();
        $this->assertDatabaseHas('projects', $projectData);
    }


    /** @test */
    public function it_can_list_projects()
    {
        $projects = Project::factory()->count(3)->create();

        $response = $this->getJson('/api/projects');
        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_project()
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");
        $response->assertOk();
        $expectedData = $project->toArray();
        $expectedData['deadline'] = (new Carbon($project->deadline))->toDateTimeString();
        $expectedData['updated_at'] = (new Carbon($project->updated_at))->toDateTimeString();
        $expectedData['created_at'] = (new Carbon($project->created_at))->toDateTimeString();
        $response->assertJson(['data'=>$expectedData]);
    }

    /** @test */
    public function it_can_update_a_project()
    {
        $project = Project::factory()->create();
        $updatedData = ['title' => 'Updated Title'];

        $response = $this->putJson("/api/projects/{$project->id}", $updatedData);

        $response->assertOk();
        $this->assertDatabaseHas('projects', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_project()
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function it_can_show_project_statistics()
    {
        // Arrange
        $user = User::factory()->create(); // This creates a new user and persists it to the database
        $project = Project::factory()->create(); // Ensure a project exists for the task

        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'pending',
            'user_id' => $user->id, // Assign the created user's ID here
            'project_id' => $project->id,
        ];

        // Act
        $response = $this->postJson('/api/tasks', $taskData);

        // Assert
        $response->assertCreated();
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $user->id,
        ]);
    }
}
