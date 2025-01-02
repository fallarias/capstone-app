<?php

namespace App\Listeners;

use App\Events\StaffGetChart;
use App\Events\StaffScan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogStaffGetChart
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
    public function handle(StaffGetChart $event)
    {
        Logs::create([
            'action' => 'Return Staff Chart',
            'account_type' => $event->user->account_type,
            'message' => 'Chart returned Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
