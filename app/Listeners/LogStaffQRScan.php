<?php

namespace App\Listeners;

use App\Events\StaffQRScan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class LogStaffQRScan
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
    public function handle(StaffQRScan $event)
    {
        Logs::create([
            'action' => 'Scan QR CODE',
            'account_type' => $event->user->account_type,
            'message' => 'QR Code scanned successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    } 
}
