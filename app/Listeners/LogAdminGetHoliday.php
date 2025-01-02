<?php

namespace App\Listeners;

use App\Events\AdminGetHoliday;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogAdminGetHoliday
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
    public function handle(AdminGetHoliday $event)
    {
        Logs::create([
            'action' => 'Click Holiday',
            'account_type' => $event->user->account_type,
            'message' => 'Click Holiday successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    } 
}
