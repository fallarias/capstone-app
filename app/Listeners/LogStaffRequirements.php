<?php

namespace App\Listeners;

use App\Events\StaffRequirements;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogStaffRequirements
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
    

    public function handle(StaffRequirements $event)
    {
        Logs::create([
            'action' => 'Client Lack of Requirements',
            'account_type' => $event->user->account_type,
            'message' => 'Lack of Requirements created successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    } 

}
