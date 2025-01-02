<?php

namespace App\Listeners;

use App\Events\StaffFinish;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;

class LogStaffFinish
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
    public function handle(StaffFinish $event)
    {
        Logs::create([
            'action' => 'Staff Click Finish',
            'account_type' => $event->user->account_type,
            'message' => 'Click finish task successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    } 
}
