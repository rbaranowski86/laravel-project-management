<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Notifications\ProjectDeadlineNotification;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notification;

class NotifyBeforeDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:before-deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::whereBetween('deadline', [now(), now()->addMinutes(10)])
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($projects as $project) {
            // Dispatch notifications to all users associated with the project
            Notification::send($project->users, new ProjectDeadlineNotification($project));
        }
    }
}
