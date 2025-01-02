<?php

namespace App\Listeners;

use App\Events\StaffResume;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogStaffResume
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
    public function handle(StaffResume $event)
    {
        Logs::create([
            'action' => 'Resume Task',
            'account_type' => $event->user->account_type,
            'message' => 'Resume task successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    } 
}
