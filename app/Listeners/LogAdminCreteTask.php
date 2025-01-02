<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Events\AdminCreteTask;
use App\Models\Logs;
class LogAdminCreteTask
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
    public function handle(AdminCreteTask $event)
    {
        Logs::create([
            'action' => 'Created New Task',
            'account_type' => $event->user->account_type,
            'message' => 'Task created Successfully',
            'user_id' => $event->user->user_id, // Get the user ID from the event
        ]);
        
    }
}
