<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskTableSeeder extends Seeder
{
    public function run()
    {
        Task::factory()->count(50)->create();
    }
}
