<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\UserTaskName;
class LogUserTaskName
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */


 public function handle(UserTaskName $event)
    {
        Logs::create([
            'action' => 'Returning Name of the Task',
            'account_type' => $event->user->account_type,
            'message' => 'Task returned to user Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
    }
}
