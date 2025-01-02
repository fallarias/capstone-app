<?php

namespace App\Listeners;

use App\Events\AdminGetEdited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogAdminGetEdited
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
    public function handle(AdminGetEdited $event)
    {
        Logs::create([
            'action' => 'Edit Task',
            'account_type' => $event->user->account_type,
            'message' => 'Task edited successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
