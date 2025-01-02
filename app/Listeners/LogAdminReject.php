<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\AdminReject;
class LogAdminReject
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


    public function handle(AdminReject $event)
    {
        Logs::create([
            'action' => 'Rejected or Remove User Account',
            'account_type' => $event->user->account_type,
            'message' => 'Rejecting/Removing account suceesfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
