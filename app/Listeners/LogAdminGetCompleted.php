<?php

namespace App\Listeners;

use App\Events\AdminGetComplted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogAdminGetCompleted
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
    public function handle(AdminGetComplted $event)
    {
        Logs::create([
            'action' => 'Click Create Task',
            'account_type' => $event->user->account_type,
            'message' => 'Click create task successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
