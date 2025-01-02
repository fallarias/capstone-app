<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\UserDownload;
class LogUserDownload
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
    public function handle(UserDownload $event)
    {
        Logs::create([
            'action' => 'Task Downloaded',
            'account_type' => $event->user->account_type,
            'message' => 'Task downloaded Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
