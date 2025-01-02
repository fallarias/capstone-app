<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\StaffScan;
class LogStaffScan
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
    public function handle(StaffScan $event)
    {
        Logs::create([
            'action' => 'Returning Scanned QR CODE',
            'account_type' => $event->user->account_type,
            'message' => 'Returing all Staff scan QR Code Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
