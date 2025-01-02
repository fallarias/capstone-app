<?php

namespace App\Listeners;

use App\Events\AdminGetCreate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogAdminGetCreate
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
    public function handle(AdminGetCreate $event)
    {
        Logs::create([
            'action' => 'Click Create Task',
            'account_type' => $event->user->account_type,
            'message' => 'Click create task successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
