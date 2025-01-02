<?php

namespace App\Listeners;

use App\Events\AdminGetList;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogAdminGetList
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
    public function handle(AdminGetList $event)
    {
        Logs::create([
            'action' => 'Click List',
            'account_type' => $event->user->account_type,
            'message' => 'Click List successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
