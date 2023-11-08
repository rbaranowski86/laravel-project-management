<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    private $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database']; // Just an example, adjust as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A task '.$this->task->id.' has been assigned to you.');
    }

    public function toArray($notifiable)
    {
        // Return the array representation of the notification
        return [
            'task_id' => $this->task->id,
            'message' => 'A task has been assigned to you.'
        ];
    }
}
