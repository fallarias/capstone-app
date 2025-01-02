<?php

namespace App\Listeners;

use App\Events\AdminGetEdit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogAdminGetEdit
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
    public function handle(AdminGetEdit $event)
    {
        Logs::create([
            'action' => 'Click Edit Button',
            'account_type' => $event->user->account_type,
            'message' => 'Click edit button successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
