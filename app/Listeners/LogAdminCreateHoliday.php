<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\AdminCreateHoliday;
class LogAdminCreateHoliday
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


    public function handle(AdminCreateHoliday $event)
    {
        Logs::create([
            'action' => 'Created Holiday',
            'account_type' => $event->user->account_type,
            'message' => 'Holiday created successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }  

}
