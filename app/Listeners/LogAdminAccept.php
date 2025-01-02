<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AdminAccept;
use App\Models\Logs;
class LogAdminAccept
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
    public function handle(AdminAccept $event)
    {
        Logs::create([
            'action' => 'Accepted New User',
            'account_type' => $event->user->account_type,
            'message' => 'Task created Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
