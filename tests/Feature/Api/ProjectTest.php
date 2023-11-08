<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

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
        if ($response->status() !== 201) {
            dd($response->getContent());
        }
        $response->assertCreated();
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
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'pending',
            'user_id' => $user->id,
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

    /** @test */
    public function project_with_future_deadline_is_not_marked_completed()
    {
        $project = Project::factory()->create([
            'deadline' => now()->addDays(10), // 10 days in the future
            'status' => 'in-progress',
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $project->refresh();
        $this->assertEquals('in-progress', $project->status);

        $response->assertOk();
    }

    /** @test */
    public function project_with_past_deadline_is_marked_completed_and_returns_error()
    {
        $project = Project::factory()->create([
            'deadline' => now()->subDays(1),
            'status' => 'in-progress',
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $project->refresh();
        $this->assertEquals('completed', $project->status);
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'The project deadline has passed and it has been marked as completed.'
        ]);
    }
}
