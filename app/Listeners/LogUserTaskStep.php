<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\UserTaskStep;
class LogUserTaskStep
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


    public function handle(UserTaskStep $event)
    {
        Logs::create([
            'action' => 'Returning Step of the Task',
            'account_type' => $event->user->account_type,
            'message' => 'Task Step returned Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
