<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
use App\Events\AdminEmail;
class LogAdminEmail
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
    
    public function handle(AdminEmail $event)
    {
        Logs::create([
            'action' => 'Send Email',
            'account_type' => $event->admin->account_type,
            'message' => 'Sending email for deadline Successfully',
            'user_id' => $event->admin->user_id, // Get the user ID from the event
        ]);
        
    }
}
