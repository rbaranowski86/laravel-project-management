<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['completed', 'in-progress', 'pending']),
            'user_id' => $user->id,
            'project_id' => $project->id,
        ];
    }
}
