<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendTaskAssignedNotification
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TaskAssigned $event
     * @return void
     */
    public function handle(TaskAssigned $event)
    {
        $user = $event->user;
        $task = $event->task;

        Notification::send($user, new TaskAssignedNotification($task));
    }

}
