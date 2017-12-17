<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RenamingTasks extends Notification
{
    use Queueable;

    public $renamingTasksCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($count)
    {
         $this->renaming_tasks_count = $count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hi there!')
                    ->subject('Todo @webquarters')
                    ->line('You have '.$this->renaming_tasks_count.' pending tasks')
                    ->action('View', url('/tasklist'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
          'renaming_tasks_count' => $this->renaming_tasks_count
        ];
    }
}
